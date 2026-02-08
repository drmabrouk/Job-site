<?php
/**
 * Template: Email Verification
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : 0;
$user = get_userdata( $user_id );

if ( ! $user ) {
    wp_die( 'Invalid user session.' );
}
?>
<div class="jobs-auth-container">
    <div class="jobs-auth-card" style="padding: 40px;">
        <div class="auth-header" style="text-align: center; margin-bottom: 30px;">
            <div class="verify-icon" style="font-size: 50px; color: var(--jobs-primary-color); margin-bottom: 20px;">
                <span class="dashicons dashicons-email-alt" style="font-size: 60px; width: 60px; height: 60px;"></span>
            </div>
            <h3>Verify Your Email</h3>
            <p>We've sent a 6-digit code to <strong><?php echo esc_html( $user->user_email ); ?></strong>. It expires in 5 minutes.</p>
        </div>

        <form id="jobs-verify-email-form" class="jobs-auth-form">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <div class="form-group" style="text-align: center;">
                <input type="text" name="code" id="verify-code" placeholder="Enter 6-digit code" maxlength="6"
                       style="font-size: 24px; text-align: center; letter-spacing: 10px; font-weight: 700; padding: 15px;" required>
            </div>
            <button type="submit" class="jobs-btn auth-submit">Verify Account</button>

            <div style="text-align: center; margin-top: 20px; font-size: 0.9em; color: #666;">
                Didn't receive the code? <a href="#" id="resend-verify-code" style="color: var(--jobs-primary-color); font-weight: 600;">Resend Code</a>
            </div>
        </form>

        <div id="verify-status" style="text-align: center; margin-top: 20px;"></div>
    </div>
</div>
