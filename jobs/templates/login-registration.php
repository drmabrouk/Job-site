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

                <form id="jobs-login-form-ajax" class="jobs-auth-form">
                    <div class="form-group">
                        <input type="text" name="log" id="user_login" placeholder="Username or Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="pwd" id="user_pass" placeholder="Password" required>
                    </div>
                    <div class="form-row-between" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 0.85em;">
                        <label><input type="checkbox" name="rememberme" value="forever"> Remember Me</label>
                        <a href="<?php echo add_query_arg( 'action', 'lostpassword' ); ?>" class="forgot-password-link" style="color: var(--jobs-primary-color); text-decoration: none;">Forgot Password?</a>
                    </div>
                    <button type="submit" class="jobs-btn auth-submit">Sign In</button>

                    <button type="button" id="biometric-login-btn" class="jobs-btn-minimal" style="width: 100%; margin-top: 15px; border: 1px solid #ddd; padding: 12px; border-radius: 8px; font-size: 0.9em; display: flex; align-items: center; justify-content: center; gap: 10px; background: #fff;">
                        <span class="dashicons dashicons-id"></span> Sign in with Biometrics
                    </button>
                </form>

                <div class="social-login-separator" style="text-align: center; margin: 30px 0 20px; position: relative;">
                    <span style="background: white; padding: 0 10px; position: relative; z-index: 2; color: #999; font-size: 0.8em; text-transform: uppercase; letter-spacing: 1px;">Fast Access</span>
                    <hr style="position: absolute; top: 50%; left: 0; right: 0; border: 0; border-top: 1px solid #eee; margin: 0; z-index: 1;">
                </div>

                <div class="social-login-icons" style="display: flex; justify-content: center;">
                    <a href="#" class="social-icon apple-login" title="Login with Apple" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border: 1px solid #eee; border-radius: 50%; transition: all 0.3s; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.05 20.28c-.98.95-2.05 1.61-3.22 1.61-1.14 0-1.55-.71-2.91-.71-1.35 0-1.84.71-2.91.71-1.12 0-2.31-.76-3.32-1.81-2.04-2.12-3.13-5.99-3.13-9.5 0-3.53 1.83-5.4 3.73-5.4 1.03 0 1.87.6 2.51.6.61 0 1.61-.71 2.86-.71 1.13 0 2.5.55 3.39 1.65-2.31 1.34-1.92 4.67.45 5.62-.88 2.2-1.94 4.54-3.45 6.04zM12.03 5.07c-.05-1.57.85-3.11 2.04-4.07.13-.1.27-.19.41-.26-1.53.07-2.97.98-3.79 2.3-.77 1.22-.98 2.55-.77 3.86.11.05.22.1.34.14 1.1-.06 2.16-.62 2.85-1.53-.36-.14-.72-.28-1.08-.44z" fill="black"/></svg>
                    </a>
                </div>
            </div>

            <div id="auth-register" class="auth-panel <?php echo $show_register ? 'active' : ''; ?>">
                <div class="auth-header" style="text-align: center;">
                    <?php
                    if ( $logo_url ) : ?>
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="Site Logo" style="max-width: 150px; margin-bottom: 20px;">
                    <?php endif; ?>
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
                            <input type="text" name="first_name" placeholder="First Name" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <input type="text" name="last_name" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="form-row" style="display:flex; gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <input type="text" name="user_login" placeholder="Username" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <input type="email" name="user_email" placeholder="Email Address" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="user_role" required>
                            <option value="" disabled selected>Register as...</option>
                            <option value="job_seeker">Job Seeker (Finding work)</option>
                            <option value="employer">Employer (Hiring staff)</option>
                        </select>
                    </div>
                    <div class="form-row" style="display:flex; gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <input type="password" name="user_pass" placeholder="Password" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <input type="password" name="user_pass_confirm" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <button type="submit" name="jobs_register" class="jobs-btn auth-submit">Register Now</button>
                </form>
            </div>
        </div>
    </div>
    <div id="auth-status-message" style="text-align: center; margin-top: 20px; font-weight: 500;"></div>
    <?php
}
