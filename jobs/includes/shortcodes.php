<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'jobs_search_page', 'jobs_render_search_page' );
add_shortcode( 'jobs_admin_panel', 'jobs_render_admin_panel' );
add_shortcode( 'jobs_login_registration', 'jobs_render_login_registration' );
add_shortcode( 'jobs_public_profile', 'jobs_render_public_profile' );

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

function jobs_render_public_profile() {
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/public-profile.php';
    return ob_get_clean();
}
