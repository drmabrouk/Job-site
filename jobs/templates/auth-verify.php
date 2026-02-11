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
<div class="jobs-auth-container" style="background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px;">
    <div class="jobs-auth-card" style="padding: 60px 40px; background: white; border-radius: 32px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08); width: 100%; max-width: 480px; text-align: center;">
        <div class="auth-header" style="margin-bottom: 40px;">
            <div style="width: 80px; height: 80px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; color: #1d3469;">
                <span class="dashicons dashicons-shield-alt" style="font-size: 40px; width: 40px; height: 40px;"></span>
            </div>
            <h2 style="font-size: 2em; font-weight: 800; color: #1d3469; margin-bottom: 12px;">Email Verification</h2>
            <p style="color: #64748b; font-size: 1.05em; line-height: 1.6;">We have dispatched a secure 6-digit OTP code to <strong><?php echo esc_html( $user->user_email ); ?></strong>.</p>
        </div>

        <form id="jobs-verify-email-form" class="jobs-auth-form">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <div class="form-group" style="margin-bottom: 30px;">
                <input type="text" name="code" id="verify-code" placeholder="000000" maxlength="6"
                       style="font-size: 32px; text-align: center; letter-spacing: 12px; font-weight: 800; padding: 20px; border-radius: 16px; border: 2px solid #e2e8f0; background: #f8fafc; width: 100%; outline: none; transition: border-color 0.3s;" required>
            </div>
            <button type="submit" class="jobs-btn auth-submit" style="width: 100%; padding: 18px; border-radius: 16px; font-size: 1.1em; font-weight: 800; background: #1d3469; color: white; border: none; cursor: pointer; transition: transform 0.2s;">Secure Verification</button>

            <div style="margin-top: 35px; font-size: 0.95em; color: #64748b;">
                Didn't receive the protocol code? <br>
                <a href="#" id="resend-verify-code" style="color: #1d3469; font-weight: 700; text-decoration: none; display: inline-block; margin-top: 8px; border-bottom: 2px solid #cbd5e1;">Request New OTP</a>
            </div>
        </form>

        <div id="verify-status" style="margin-top: 30px;"></div>
    </div>
</div>

<style>
#verify-code:focus { border-color: #1d3469; background: white; }
.auth-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(29, 52, 105, 0.2); }
</style>
