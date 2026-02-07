<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Hide admin bar for non-admins to keep focus on Top-Bar
add_filter( 'show_admin_bar', function($show) {
    if ( ! Jobs_Permission_Service::is_admin() ) {
        return false;
    }
    return $show;
});

/**
 * Log Login
 */
add_action( 'wp_login', 'jobs_log_login', 10, 2 );
function jobs_log_login( $user_login, $user ) {
    Jobs_Activity_Service::log( $user->ID, 'login', 'User logged in: ' . $user_login );
}
