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
     * Send verification email with 6-digit code
     */
    public static function send_verification_email( $user_id ) {
        $user = get_userdata( $user_id );
        if ( ! $user ) return false;

        $code = self::generate_code();
        $expiry = time() + ( 5 * MINUTE_IN_SECONDS );

        update_user_meta( $user_id, '_jobs_email_verify_code', $code );
        update_user_meta( $user_id, '_jobs_email_verify_expiry', $expiry );

        $site_name = get_bloginfo( 'name' );
        $subject = "[{$site_name}] Your Verification Code";

        $message = "Hello " . $user->display_name . ",\n\n";
        $message .= "Your verification code is: " . $code . "\n\n";
        $message .= "This code is valid for 5 minutes.\n\n";
        $message .= "If you did not request this, please ignore this email.\n\n";
        $message .= "Regards,\nThe {$site_name} Team";

        // Set professional filters
        add_filter( 'wp_mail_from_name', function() use ($site_name) { return $site_name; } );

        $sent = wp_mail( $user->user_email, $subject, $message );

        // Remove filters to avoid affecting other emails
        remove_all_filters( 'wp_mail_from_name' );

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

        add_filter( 'wp_mail_from_name', function() use ($site_name) { return $site_name; } );
        $sent = wp_mail( $user->user_email, $subject, $message );
        remove_all_filters( 'wp_mail_from_name' );

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

        add_filter( 'wp_mail_from_name', function() use ($site_name) { return $site_name; } );
        $sent = wp_mail( $user->user_email, $subject, $message );
        remove_all_filters( 'wp_mail_from_name' );

        return $sent;
    }
}
