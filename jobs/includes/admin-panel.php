<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_redirect_admin_to_custom_panel() {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        if ( current_user_can( 'system_admin' ) || current_user_can( 'administrator' ) ) {
            $admin_page = get_page_by_path( 'jobs-admin-panel' );
            if ( $admin_page ) {
                // If not already on a specific admin subpage we want to keep?
                // For now, redirect all.
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
