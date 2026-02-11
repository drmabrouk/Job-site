<?php
/**
 * Service: Professional Email System & Template Manager
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Email_Service {

    /**
     * Get default templates
     */
    public static function get_default_templates() {
        return array(
            'application_confirmation' => array(
                'label'   => 'Application Confirmation (To Seeker)',
                'subject' => 'Application Received: {job_title}',
                'body'    => 'Hello {seeker_name},<br><br>We are pleased to confirm that your application for the position of <strong>{job_title}</strong> has been successfully received by <strong>{company_name}</strong>.<br><br>The recruitment team will review your profile, and you will be notified if there is a response.<br><br>Best regards,<br>The Recruitment Team'
            ),
            'employer_response' => array(
                'label'   => 'Employer Response (To Seeker)',
                'subject' => 'Update on your application for {job_title}',
                'body'    => 'Hello {seeker_name},<br><br><strong>{company_name}</strong> has updated the status of your application for <strong>{job_title}</strong> to: <strong>{status}</strong>.<br><br>You can view more details and communicate with the employer through your dashboard.<br><br>Best regards,<br>The Support Team'
            )
        );
    }

    /**
     * Send branded professional email
     */
    public static function send( $to, $subject_type, $placeholders = array() ) {
        $templates = get_option( 'jobs_email_templates', self::get_default_templates() );
        if ( ! isset($templates[$subject_type]) ) return false;

        $template = $templates[$subject_type];
        $subject = $template['subject'];
        $body = $template['body'];

        // Replace placeholders
        foreach ( $placeholders as $key => $val ) {
            $subject = str_replace( '{' . $key . '}', $val, $subject );
            $body = str_replace( '{' . $key . '}', $val, $body );
        }

        $site_name = get_bloginfo( 'name' );
        $logo_url = get_option( 'jobs_site_logo' );
        $primary_color = get_option( 'jobs_primary_color', '#1d3469' );

        ob_start();
        ?>
        <div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; background-color: #ffffff;">
            <div style="background-color: <?php echo $primary_color; ?>; padding: 30px; text-align: center;">
                <?php if ($logo_url) : ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>" style="max-width: 150px;">
                <?php else : ?>
                    <h1 style="color: #ffffff; margin: 0; font-size: 22px;"><?php echo esc_html($site_name); ?></h1>
                <?php endif; ?>
            </div>
            <div style="padding: 40px; color: #1e293b; line-height: 1.6;">
                <?php echo wpautop($body); ?>
                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 30px 0;">
                <p style="font-size: 0.8em; color: #94a3b8; text-align: center; margin: 0;">
                    &copy; <?php echo date('Y'); ?> <?php echo esc_html($site_name); ?>. All rights reserved.
                </p>
            </div>
        </div>
        <?php
        $message = ob_get_clean();

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . get_option('admin_email') . '>',
        );

        add_filter( 'wp_mail_from_name', function() use ($site_name) { return $site_name; } );
        add_filter( 'wp_mail_from', function() { return get_option('admin_email'); } );

        $sent = wp_mail( $to, $subject, $message, $headers );

        return $sent;
    }
}
