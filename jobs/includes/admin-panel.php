<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_redirect_admin_to_custom_panel() {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) && current_user_can( 'system_admin' ) ) {
        $admin_page = get_page_by_path( 'jobs-admin-panel' );
        if ( $admin_page ) {
            wp_safe_redirect( get_permalink( $admin_page->ID ) );
            exit;
        }
    }
}
add_action( 'admin_init', 'jobs_redirect_admin_to_custom_panel' );
