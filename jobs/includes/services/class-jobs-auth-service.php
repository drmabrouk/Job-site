<?php
/**
 * Service: Authentication & Verification
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Auth_Service {

    public static function init() {
        // Any hooks if needed
    }

    /**
     * Generate a 6-digit verification code
     */
    public static function generate_code() {
        return str_pad( rand( 0, 999999 ), 6, '0', STR_PAD_LEFT );
    }

    /**
     * Helper to set mail from name
     */
    public static function get_mail_from_name() {
        return get_bloginfo( 'name' );
    }

    /**
     * Helper to set mail from address
     */
    public static function get_mail_from_address() {
        return get_option( 'admin_email' );
    }

    /**
     * Send verification email with 6-digit code (Professional Branded Version)
     */
    public static function send_verification_email( $user_id ) {
        $user = get_userdata( $user_id );
        if ( ! $user ) return false;

        $code = self::generate_code();
        $expiry = time() + ( 5 * MINUTE_IN_SECONDS );

        update_user_meta( $user_id, '_jobs_email_verify_code', $code );
        update_user_meta( $user_id, '_jobs_email_verify_expiry', $expiry );

        $site_name = get_bloginfo( 'name' );
        $logo_url = get_option( 'jobs_site_logo' );
        $primary_color = get_option( 'jobs_primary_color', '#1d3469' );
        $subject = "Your Verification Code - {$site_name}";

        ob_start();
        ?>
        <div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; background-color: #ffffff;">
            <div style="background-color: <?php echo $primary_color; ?>; padding: 40px; text-align: center;">
                <?php if ($logo_url) : ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>" style="max-width: 180px;">
                <?php else : ?>
                    <h1 style="color: #ffffff; margin: 0; font-size: 24px;"><?php echo esc_html($site_name); ?></h1>
                <?php endif; ?>
            </div>
            <div style="padding: 40px; color: #1e293b; line-height: 1.6;">
                <h2 style="margin-top: 0; color: #1d3469;">Verify Your Account</h2>
                <p>Hello <strong><?php echo esc_html($user->display_name); ?></strong>,</p>
                <p>Thank you for joining our community. To complete your registration and secure your account, please use the following one-time password (OTP):</p>

                <div style="background-color: #f8fafc; border-radius: 12px; padding: 30px; text-align: center; margin: 30px 0; border: 1px dashed #cbd5e1;">
                    <span style="font-size: 36px; font-weight: 800; letter-spacing: 12px; color: <?php echo $primary_color; ?>;"><?php echo $code; ?></span>
                </div>

                <p style="font-size: 0.9em; color: #64748b;">This code is valid for <strong>5 minutes</strong>. For security reasons, do not share this code with anyone.</p>
                <p>If you did not initiate this request, you can safely ignore this email.</p>

                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 30px 0;">

                <p style="font-size: 0.8em; color: #94a3b8; text-align: center; margin: 0;">
                    &copy; <?php echo date('Y'); ?> <?php echo esc_html($site_name); ?>. All rights reserved.<br>
                    Providing professional opportunities worldwide.
                </p>
            </div>
        </div>
        <?php
        $message = ob_get_clean();

        // Headers to hide WP identity and set HTML
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('admin_email')
        );

        add_filter( 'wp_mail_from_name', array( 'Jobs_Auth_Service', 'get_mail_from_name' ) );
        add_filter( 'wp_mail_from', array( 'Jobs_Auth_Service', 'get_mail_from_address' ) );

        $sent = wp_mail( $user->user_email, $subject, $message, $headers );

        remove_filter( 'wp_mail_from_name', array( 'Jobs_Auth_Service', 'get_mail_from_name' ) );
        remove_filter( 'wp_mail_from', array( 'Jobs_Auth_Service', 'get_mail_from_address' ) );

        return $sent;
    }

    /**
     * Verify the 6-digit code
     */
    public static function verify_code( $user_id, $code ) {
        $saved_code = get_user_meta( $user_id, '_jobs_email_verify_code', true );
        $expiry = get_user_meta( $user_id, '_jobs_email_verify_expiry', true );

        if ( ! $saved_code || $saved_code !== $code ) {
            return new WP_Error( 'invalid_code', 'The verification code is incorrect.' );
        }

        if ( time() > $expiry ) {
            return new WP_Error( 'expired_code', 'The verification code has expired.' );
        }

        // Success
        delete_user_meta( $user_id, '_jobs_email_verify_code' );
        delete_user_meta( $user_id, '_jobs_email_verify_expiry' );
        update_user_meta( $user_id, '_is_email_verified', '1' );

        return true;
    }

    /**
     * Send password reset email
     */
    public static function send_password_reset( $user_login ) {
        $user = get_user_by( 'login', $user_login );
        if ( ! $user ) $user = get_user_by( 'email', $user_login );

        if ( ! $user ) return new WP_Error( 'invalid_user', 'No user found with that username or email.' );

        $key = get_password_reset_key( $user );
        if ( is_wp_error( $key ) ) return $key;

        $site_name = get_bloginfo( 'name' );
        $subject = "[{$site_name}] Password Reset Request";
        $reset_url = add_query_arg( array( 'action' => 'rp', 'key' => $key, 'login' => $user->user_login ), home_url('/login/') );

        $message = "Someone has requested a password reset for the following account:\n\n";
        $message .= "Site Name: {$site_name}\n";
        $message .= "Username: {$user->user_login}\n\n";
        $message .= "If this was a mistake, ignore this email and nothing will happen.\n\n";
        $message .= "To reset your password, visit the following address:\n";
        $message .= $reset_url . "\n\n";
        $message .= "Regards,\nThe {$site_name} Team";

        add_filter( 'wp_mail_from_name', array( 'Jobs_Auth_Service', 'get_mail_from_name' ) );
        $sent = wp_mail( $user->user_email, $subject, $message );
        remove_filter( 'wp_mail_from_name', array( 'Jobs_Auth_Service', 'get_mail_from_name' ) );

        return $sent;
    }

    /**
     * Send inactivity warning email
     */
    public static function send_inactivity_warning( $user_id, $type ) {
        $user = get_userdata( $user_id );
        if ( ! $user ) return false;

        $site_name = get_bloginfo( 'name' );
        $timeframe = ( $type === 'month' ) ? 'one month' : 'one week';

        $subject = "[{$site_name}] Account Inactivity Notice";

        $message = "Hello " . $user->display_name . ",\n\n";
        $message .= "We noticed that you haven't logged into your account on {$site_name} for a long time.\n\n";
        $message .= "To keep our database clean, we automatically delete accounts that have been inactive for over a year.\n\n";
        $message .= "Your account is scheduled for deletion in {$timeframe} unless you log in soon.\n\n";
        $message .= "Just log in to your account at " . home_url('/login/') . " to keep it active.\n\n";
        $message .= "Regards,\nThe {$site_name} Team";

        add_filter( 'wp_mail_from_name', array( 'Jobs_Auth_Service', 'get_mail_from_name' ) );
        $sent = wp_mail( $user->user_email, $subject, $message );
        remove_filter( 'wp_mail_from_name', array( 'Jobs_Auth_Service', 'get_mail_from_name' ) );

        return $sent;
    }
}
