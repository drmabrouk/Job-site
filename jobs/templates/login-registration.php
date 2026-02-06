<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url();

if ( is_user_logged_in() ) {
    echo '<p>You are already logged in.</p>';
} else {
    ?>
    <div class="jobs-login-registration-container jobs-transparent-bg">
        <div class="jobs-login-form">
            <h3>Login</h3>
            <?php wp_login_form( array( 'redirect' => $redirect ) ); ?>
        </div>

        <div class="jobs-registration-form">
            <h3>Register</h3>
            <form id="jobs-register-form" action="" method="POST">
                <?php wp_nonce_field( 'jobs_register_user', 'jobs_registration_nonce' ); ?>
                <input type="text" name="user_login" placeholder="Username" required>
                <input type="email" name="user_email" placeholder="Email" required>
                <select name="user_role">
                    <option value="job_seeker">Job Seeker</option>
                    <option value="employer">Employer</option>
                </select>
                <input type="password" name="user_pass" placeholder="Password" required>
                <button type="submit" name="jobs_register">Register</button>
            </form>
        </div>
    </div>
    <?php
}
