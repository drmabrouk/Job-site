<?php
/**
 * Security & Redirection logic
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hide Admin Bar for non-administrators
 */
function jobs_hide_admin_bar( $show ) {
    if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'manage_jobs_users' ) ) {
        return false;
    }
    return $show;
}
add_filter( 'show_admin_bar', 'jobs_hide_admin_bar' );

/**
 * Block /wp-admin/ access for non-administrators
 */
function jobs_block_admin_access() {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'manage_jobs_users' ) ) {
            wp_safe_redirect( home_url() );
            exit;
        }
    }
}
add_action( 'admin_init', 'jobs_block_admin_access' );

/**
 * Redirect to homepage on logout
 */
function jobs_logout_redirect( $redirect_to, $requested_redirect_to, $user ) {
    return home_url();
}
add_filter( 'logout_redirect', 'jobs_logout_redirect', 10, 3 );
