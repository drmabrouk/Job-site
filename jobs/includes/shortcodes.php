<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'jobs_search_page', 'jobs_render_search_page' );
add_shortcode( 'jobs_admin_panel', 'jobs_render_admin_panel' );
add_shortcode( 'jobs_login_registration', 'jobs_render_login_registration' );

function jobs_render_search_page() {
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/search-page.php';
    return ob_get_clean();
}

function jobs_render_admin_panel() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return 'Access denied.';
    }
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/admin-panel.php';
    return ob_get_clean();
}

function jobs_render_login_registration() {
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/login-registration.php';
    return ob_get_clean();
}
