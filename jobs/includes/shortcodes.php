<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'jobs_search_page', 'jobs_render_search_page' );
add_shortcode( 'jobs_login_registration', 'jobs_render_login_registration' );
add_shortcode( 'jobs_public_profile', 'jobs_render_public_profile' );
add_shortcode( 'jobs_module', 'jobs_render_module_shortcode' );
add_shortcode( 'jobs_job_seekers_page', 'jobs_render_job_seekers_page' );
add_shortcode( 'profile_management', 'jobs_account_management_shortcode' ); // Legacy support
add_shortcode( 'account_management_icon', 'jobs_account_management_shortcode' );
add_shortcode( 'account_icon', 'jobs_account_icon_shortcode' );
add_shortcode( 'notifications_icon', 'jobs_notifications_icon_shortcode' );
add_shortcode( 'jobedia_logo', 'jobs_logo_shortcode' );
add_shortcode( 'jobs_account_setup', 'jobs_render_account_setup' );
add_shortcode( 'jobs_policies', 'jobs_render_policies' );

function jobs_render_account_setup() {
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/account-setup.php';
    return ob_get_clean();
}

function jobs_render_search_page() {
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/search-page.php';
    return ob_get_clean();
}


function jobs_render_login_registration() {
    ob_start();
    $action = isset( $_GET['action'] ) ? $_GET['action'] : '';

    switch ( $action ) {
        case 'verify':
            include JOBS_PLUGIN_DIR . 'templates/auth-verify.php';
            break;
        case 'lostpassword':
        case 'rp':
            include JOBS_PLUGIN_DIR . 'templates/auth-password-reset.php';
            break;
        default:
            include JOBS_PLUGIN_DIR . 'templates/login-registration.php';
            break;
    }

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

function jobs_render_policies() {
    ob_start();
    include JOBS_PLUGIN_DIR . 'templates/policies.php';
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
