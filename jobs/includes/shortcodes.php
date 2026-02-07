<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'jobs_search_page', 'jobs_render_search_page' );
add_shortcode( 'jobs_login_registration', 'jobs_render_login_registration' );
add_shortcode( 'jobs_public_profile', 'jobs_render_public_profile' );
add_shortcode( 'jobs_module', 'jobs_render_module_shortcode' );
add_shortcode( 'jobs_job_seekers_page', 'jobs_render_job_seekers_page' );

function jobs_render_search_page() {
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/search-page.php';
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

function jobs_render_job_seekers_page() {
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/job-seekers-page.php';
    return ob_get_clean();
}

function jobs_render_module_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'module' => '',
    ), $atts );

    $module = sanitize_text_field( $atts['module'] );
    if ( empty( $module ) ) return '';

    // Verify permission if needed, but the module files usually handle it
    ob_start();
    $file = JOBS_PLUGIN_DIR . 'includes/modules/' . $module . '.php';
    if ( file_exists( $file ) ) {
        include $file;
    } else {
        echo 'Module not found.';
    }
    return ob_get_clean();
}
