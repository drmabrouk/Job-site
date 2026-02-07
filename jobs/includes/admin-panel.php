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
        if ( current_user_can( 'system_admin' ) || current_user_can( 'administrator' ) ) {
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
    if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'system_admin' ) ) {
        return false;
    }
    return $show;
});

/**
 * Activity Logger
 */
function jobs_log_activity( $user_id, $type, $message ) {
    global $wpdb;
    $table = $wpdb->prefix . 'jobs_activity_log';

    // Check if table exists
    if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) ) {
        $wpdb->insert( $table, array(
            'user_id' => $user_id,
            'type'    => $type,
            'message' => $message,
            'time'    => current_time( 'mysql' )
        ) );
    }
}

/**
 * Log Login
 */
add_action( 'wp_login', 'jobs_log_login', 10, 2 );
function jobs_log_login( $user_login, $user ) {
    jobs_log_activity( $user->ID, 'login', 'User logged in: ' . $user_login );
}
