<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_redirect_admin_to_custom_panel() {
    // Allow access to standard admin if specifically requested via URL parameter
    if ( isset( $_GET['bypass_custom_admin'] ) ) {
        return;
    }

    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        if ( Jobs_Permission_Service::is_admin() ) {
            $admin_page = get_page_by_path( 'jobs-admin-panel' );
            if ( $admin_page ) {
                wp_safe_redirect( get_permalink( $admin_page->ID ) );
                exit;
            }
        } else {
            // Non-admins completely blocked from dashboard
            wp_safe_redirect( home_url() );
            exit;
        }
    }
}
add_action( 'admin_init', 'jobs_redirect_admin_to_custom_panel' );

// Also hide admin bar for non-admins
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
