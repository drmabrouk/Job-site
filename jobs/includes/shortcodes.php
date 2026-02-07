<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'jobs_search_page', 'jobs_render_search_page' );
add_shortcode( 'jobs_dashboard', 'jobs_render_dashboard' );
add_shortcode( 'jobs_login_registration', 'jobs_render_login_registration' );
add_shortcode( 'jobs_public_profile', 'jobs_render_public_profile' );

function jobs_render_search_page() {
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/search-page.php';
    return ob_get_clean();
}

function jobs_render_dashboard() {
    if ( ! is_user_logged_in() ) {
        return 'Please log in to view your dashboard.';
    }
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/dashboard.php';
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
