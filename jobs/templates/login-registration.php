<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url();

if ( is_user_logged_in() ) {
    echo '<p>You are already logged in.</p>';
} else {
    ?>
    <div class="jobs-auth-container">
        <div class="jobs-auth-card">
            <?php
            global $jobs_registration_error;
            $show_register = ! empty( $jobs_registration_error );
            ?>
            <div class="auth-tabs">
                <button class="auth-tab <?php echo $show_register ? '' : 'active'; ?>" data-target="login">Login</button>
                <button class="auth-tab <?php echo $show_register ? 'active' : ''; ?>" data-target="register">Register</button>
            </div>

            <div id="auth-login" class="auth-panel <?php echo $show_register ? '' : 'active'; ?>">
                <div class="auth-header" style="text-align: center;">
                    <?php
                    $logo_url = get_option( 'jobs_site_logo' );
                    if ( $logo_url ) : ?>
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="Site Logo" style="max-width: 150px; margin-bottom: 20px;">
                    <?php endif; ?>
                    <h3>Welcome Back</h3>
                    <p>Enter your credentials to access your account</p>
                </div>
                <?php
                wp_login_form( array(
                    'redirect' => $redirect,
                    'form_id' => 'jobs-login-form-custom',
                    'label_username' => 'Username or Email',
                    'label_password' => 'Password',
                    'label_remember' => 'Remember Me',
                    'label_log_in' => 'Sign In',
                ) );
                ?>

                <div class="social-login-separator" style="text-align: center; margin: 20px 0; position: relative;">
                    <span style="background: white; padding: 0 10px; position: relative; z-index: 2; color: #999; font-size: 0.8em;">OR LOGIN WITH</span>
                    <hr style="position: absolute; top: 50%; left: 0; right: 0; border: 0; border-top: 1px solid #eee; margin: 0; z-index: 1;">
                </div>

                <div class="social-login-icons" style="display: flex; justify-content: center; gap: 20px;">
                    <a href="#" class="social-icon google-login" title="Login with Google" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; border-radius: 50%; transition: all 0.3s;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/></svg>
                    </a>
                    <a href="#" class="social-icon apple-login" title="Login with Apple" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; border-radius: 50%; transition: all 0.3s;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.05 20.28c-.98.95-2.05 1.61-3.22 1.61-1.14 0-1.55-.71-2.91-.71-1.35 0-1.84.71-2.91.71-1.12 0-2.31-.76-3.32-1.81-2.04-2.12-3.13-5.99-3.13-9.5 0-3.53 1.83-5.4 3.73-5.4 1.03 0 1.87.6 2.51.6.61 0 1.61-.71 2.86-.71 1.13 0 2.5.55 3.39 1.65-2.31 1.34-1.92 4.67.45 5.62-.88 2.2-1.94 4.54-3.45 6.04zM12.03 5.07c-.05-1.57.85-3.11 2.04-4.07.13-.1.27-.19.41-.26-1.53.07-2.97.98-3.79 2.3-.77 1.22-.98 2.55-.77 3.86.11.05.22.1.34.14 1.1-.06 2.16-.62 2.85-1.53-.36-.14-.72-.28-1.08-.44z" fill="black"/></svg>
                    </a>
                </div>
            </div>

            <div id="auth-register" class="auth-panel <?php echo $show_register ? 'active' : ''; ?>">
                <div class="auth-header">
                    <h3>Create Account</h3>
                    <p>Join our community of professionals and employers</p>
                </div>

                <?php if ( $show_register ) : ?>
                    <div class="jobs-error-message" style="background: #fff5f5; color: #d32f2f; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9em; border: 1px solid #ffcdd2;">
                        <?php echo esc_html( $jobs_registration_error ); ?>
                    </div>
                <?php endif; ?>

                <form id="jobs-register-form" action="" method="POST" class="jobs-auth-form">
                    <?php wp_nonce_field( 'jobs_register_user', 'jobs_registration_nonce' ); ?>
                    <div class="form-row" style="display:flex; gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <label>First Name</label>
                            <input type="text" name="first_name" placeholder="John" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Last Name</label>
                            <input type="text" name="last_name" placeholder="Doe" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="user_login" placeholder="Choose a unique username" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="user_email" placeholder="email@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Register as</label>
                        <select name="user_role">
                            <option value="job_seeker">Job Seeker (Finding work)</option>
                            <option value="employer">Employer (Hiring staff)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="user_pass" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="jobs_register" class="jobs-btn auth-submit">Register Now</button>
                </form>
            </div>
        </div>
    </div>

    <?php
}
