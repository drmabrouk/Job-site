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
            <div id="auth-status-message"></div>
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
                        <label style="display: flex; align-items: center; gap: 8px;"><input type="checkbox" name="rememberme" value="forever" style="margin:0; width:auto;"> Remember Me</label>
                        <a href="<?php echo add_query_arg( 'action', 'lostpassword' ); ?>" class="forgot-password-link" style="color: var(--jobs-primary-color); text-decoration: none; font-weight: 500;">Forgot Password?</a>
                    </div>
                    <button type="submit" class="jobs-btn auth-submit">Sign In</button>

                    <button type="button" id="biometric-login-btn" class="jobs-btn-minimal" style="width: 100%; margin-top: 15px; border: 1px solid #ddd; padding: 12px; border-radius: 12px; font-size: 0.9em; display: flex; align-items: center; justify-content: center; gap: 10px; background: #fff; color: var(--jobs-primary-color); font-weight: 600;">
                        <span class="dashicons dashicons-id-alt"></span> Sign in with Biometrics
                    </button>
                </form>
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

                    <div class="form-group">
                        <div class="account-type-toggle" style="display: flex; gap: 10px; margin-bottom: 20px;">
                            <button type="button" class="account-type-btn active" data-role="job_seeker" style="flex: 1; padding: 12px; border-radius: 10px; border: 1px solid #ddd; background: #fff; cursor: pointer; font-weight: 600;">Job Seeker</button>
                            <button type="button" class="account-type-btn" data-role="employer" style="flex: 1; padding: 12px; border-radius: 10px; border: 1px solid #ddd; background: #fff; cursor: pointer; font-weight: 600;">Employer</button>
                        </div>
                        <input type="hidden" name="user_role" id="selected-user-role" value="job_seeker">
                    </div>

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
                            <input type="text" name="user_login" placeholder="Username (Min 4 letters)" required>
                            <p style="font-size: 0.7em; color: #64748b; margin: 4px 0 0 5px;">Min. 4 English letters</p>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <input type="email" name="user_email" placeholder="Email Address" required>
                        </div>
                    </div>

                    <!-- Conditional Fields for Job Seeker -->
                    <div id="job-seeker-fields" class="conditional-fields">
                        <div class="form-group">
                            <select name="seeker_specialization" class="jobs-select-field">
                                <option value="" disabled selected>Select Your Specialization</option>
                                <?php
                                $specializations = get_terms( array( 'taxonomy' => 'specialization', 'hide_empty' => false ) );
                                foreach ( $specializations as $term ) {
                                    echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Conditional Fields for Employer -->
                    <div id="employer-fields" class="conditional-fields" style="display: none;">
                        <div class="form-group">
                            <input type="text" name="company_name" placeholder="Company / Institution Name">
                        </div>
                        <div class="form-group">
                            <input type="text" name="company_website" placeholder="Website URL">
                        </div>
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
    <?php
}
