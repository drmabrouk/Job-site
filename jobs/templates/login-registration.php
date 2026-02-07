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
            <div class="auth-tabs">
                <button class="auth-tab active" data-target="login">Login</button>
                <button class="auth-tab" data-target="register">Register</button>
            </div>

            <div id="auth-login" class="auth-panel active">
                <div class="auth-header">
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
            </div>

            <div id="auth-register" class="auth-panel">
                <div class="auth-header">
                    <h3>Create Account</h3>
                    <p>Join our community of professionals and employers</p>
                </div>
                <form id="jobs-register-form" action="" method="POST" class="jobs-auth-form">
                    <?php wp_nonce_field( 'jobs_register_user', 'jobs_registration_nonce' ); ?>
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

    <script>
    jQuery(document).ready(function($) {
        $('.auth-tab').on('click', function() {
            var target = $(this).data('target');
            $('.auth-tab').removeClass('active');
            $(this).addClass('active');
            $('.auth-panel').removeClass('active');
            $('#auth-' + target).addClass('active');
        });
    });
    </script>
    <?php
}
