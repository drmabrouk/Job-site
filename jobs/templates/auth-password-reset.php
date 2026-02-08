<?php
/**
 * Template: Password Reset / Lost Password
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$action = isset( $_GET['action'] ) ? $_GET['action'] : 'lostpassword';
?>
<div class="jobs-auth-container">
    <div class="jobs-auth-card" style="padding: 40px;">
        <?php if ( $action === 'lostpassword' ) : ?>
            <div class="auth-header" style="text-align: center; margin-bottom: 30px;">
                <div class="reset-icon" style="font-size: 50px; color: var(--jobs-primary-color); margin-bottom: 20px;">
                    <span class="dashicons dashicons-lock" style="font-size: 60px; width: 60px; height: 60px;"></span>
                </div>
                <h3>Forgot Password?</h3>
                <p>Enter your username or email and we'll send you a link to reset your password.</p>
            </div>

            <form id="jobs-lostpassword-form" class="jobs-auth-form">
                <div class="form-group">
                    <input type="text" name="user_login" placeholder="Username or Email" required>
                </div>
                <button type="submit" class="jobs-btn auth-submit">Send Reset Link</button>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="<?php echo home_url('/login/'); ?>" style="color: #666; font-size: 0.9em;">Back to Login</a>
                </div>
            </form>
        <?php else :
            $key = isset( $_GET['key'] ) ? $_GET['key'] : '';
            $login = isset( $_GET['login'] ) ? $_GET['login'] : '';
            ?>
            <div class="auth-header" style="text-align: center; margin-bottom: 30px;">
                <h3>Reset Password</h3>
                <p>Choose a new secure password for your account.</p>
            </div>

            <form id="jobs-resetpassword-form" class="jobs-auth-form">
                <input type="hidden" name="rp_key" value="<?php echo esc_attr( $key ); ?>">
                <input type="hidden" name="rp_login" value="<?php echo esc_attr( $login ); ?>">
                <div class="form-group">
                    <input type="password" name="pass1" placeholder="New Password" required minlength="8">
                </div>
                <div class="form-group">
                    <input type="password" name="pass2" placeholder="Confirm New Password" required minlength="8">
                </div>
                <button type="submit" class="jobs-btn auth-submit">Reset Password</button>
            </form>
        <?php endif; ?>

        <div id="password-status" style="text-align: center; margin-top: 20px;"></div>
    </div>
</div>
