<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_enqueue_assets() {
    // Rubik font
    wp_enqueue_style( 'google-fonts-rubik', 'https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap', array(), null );

    // Dashicons for all browsers/devices
    wp_enqueue_style( 'dashicons' );

    // Enqueue Base
    wp_enqueue_style( 'jobs-base-style', JOBS_PLUGIN_URL . 'assets/css/base.css', array(), JOBS_VERSION );
    wp_enqueue_script( 'jobs-base-script', JOBS_PLUGIN_URL . 'assets/js/base.js', array(), JOBS_VERSION, true );

    // Common Front-end Components
    if ( ! is_admin() ) {
        wp_enqueue_style( 'jobs-top-bar', JOBS_PLUGIN_URL . 'assets/css/top-bar.css', array(), JOBS_VERSION );
        wp_enqueue_script( 'jobs-top-bar', JOBS_PLUGIN_URL . 'assets/js/top-bar.js', array('jquery'), JOBS_VERSION, true );

        wp_enqueue_style( 'jobs-modules-overlay', JOBS_PLUGIN_URL . 'assets/css/modules-overlay.css', array(), JOBS_VERSION );
        wp_enqueue_script( 'jobs-module-loader', JOBS_PLUGIN_URL . 'assets/js/module-loader.js', array('jquery', 'jobs-base-script'), JOBS_VERSION, true );

        // Pass global variables and module assets map
        wp_localize_script( 'jobs-module-loader', 'jobs_vars', array(
            'ajax_url'      => admin_url( 'admin-ajax.php' ),
            'home_url'      => home_url(),
            'nonce'         => wp_create_nonce( 'jobs_main_nonce' ),
            'plugin_url'    => JOBS_PLUGIN_URL,
            'module_assets' => array(
                'job-posting' => array(
                    'css' => 'assets/css/modules/job-posting.css',
                    'js'  => 'assets/js/modules/job-posting.js'
                ),
                'cv-resume'   => array(
                    'css' => 'assets/css/modules/cv-resume.css',
                    'js'  => 'assets/js/modules/cv-resume.js'
                ),
                'company-profile' => array(
                    'css' => 'assets/css/modules/company-profile.css',
                    'js'  => 'assets/js/modules/company-profile.js'
                ),
                'support' => array(
                    'js' => 'assets/js/modules/support.js'
                ),
                'job-requests' => array(
                    'js' => 'assets/js/modules/job-requests.js'
                ),
                'settings' => array(
                    'js' => 'assets/js/modules/settings.js'
                ),
            )
        ) );
    }

    // Search Page specific
    if ( is_page('job-search') || is_front_page() ) {
        wp_enqueue_style( 'jobs-search-page', JOBS_PLUGIN_URL . 'assets/css/search-page.css', array(), JOBS_VERSION );
        wp_enqueue_style( 'jobs-job-card', JOBS_PLUGIN_URL . 'assets/css/job-card.css', array(), JOBS_VERSION );
        wp_enqueue_script( 'jobs-search-engine', JOBS_PLUGIN_URL . 'assets/js/search.js', array('jquery', 'jobs-base-script'), JOBS_VERSION, true );
    }

    // Single Job specific
    if ( is_singular('job') ) {
        wp_enqueue_style( 'jobs-details-style', JOBS_PLUGIN_URL . 'assets/css/job-details.css', array(), JOBS_VERSION );
    }


    // Auth specific
    if ( is_page('login') ) {
        wp_enqueue_style( 'jobs-auth', JOBS_PLUGIN_URL . 'assets/css/auth.css', array(), JOBS_VERSION );
        wp_enqueue_script( 'jobs-auth-system', JOBS_PLUGIN_URL . 'assets/js/auth.js', array('jquery'), JOBS_VERSION, true );

        // Add body class for the login page
        add_filter( 'body_class', function( $classes ) {
            $classes[] = 'jobs-auth-page';
            return $classes;
        } );
    }

    // Profile specific
    if ( is_page('profile') || get_query_var('profile_user') ) {
        wp_enqueue_style( 'jobs-public-profile', JOBS_PLUGIN_URL . 'assets/css/public-profile.css', array(), JOBS_VERSION );
    }

    // Job Seekers page specific
    if ( is_page('job-seekers') ) {
        wp_enqueue_style( 'jobs-seekers-style', JOBS_PLUGIN_URL . 'assets/css/job-seekers.css', array(), JOBS_VERSION );
        wp_enqueue_script( 'jobs-seekers-script', JOBS_PLUGIN_URL . 'assets/js/job-seekers.js', array('jquery', 'jobs-base-script'), JOBS_VERSION, true );
    }
}
add_action( 'wp_enqueue_scripts', 'jobs_enqueue_assets' );

/**
 * Defer non-critical scripts for better performance
 */
function jobs_defer_scripts( $tag, $handle, $src ) {
    $defer_handles = array(
        'jobs-base-script',
        'jobs-module-loader',
        'jobs-search-engine',
        'jobs-auth-system',
        'jobs-seekers-script'
    );

    if ( in_array( $handle, $defer_handles ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'jobs_defer_scripts', 10, 3 );
