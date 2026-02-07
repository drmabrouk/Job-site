<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_enqueue_assets() {
    // Rubik font
    wp_enqueue_style( 'google-fonts-rubik', 'https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap', array(), null );

    // Enqueue Base
    wp_enqueue_style( 'jobs-base-style', JOBS_PLUGIN_URL . 'assets/css/base.css', array(), '1.0.0' );
    wp_enqueue_script( 'jobs-base-script', JOBS_PLUGIN_URL . 'assets/js/base.js', array(), '1.0.0', true );

    // Common Front-end Components
    if ( ! is_admin() ) {
        wp_enqueue_style( 'jobs-top-bar', JOBS_PLUGIN_URL . 'assets/css/top-bar.css', array(), '1.0.0' );
        wp_enqueue_script( 'jobs-top-bar', JOBS_PLUGIN_URL . 'assets/js/top-bar.js', array('jquery'), '1.0.0', true );

        wp_enqueue_style( 'jobs-modules-overlay', JOBS_PLUGIN_URL . 'assets/css/modules-overlay.css', array(), '1.0.0' );
        wp_enqueue_script( 'jobs-module-loader', JOBS_PLUGIN_URL . 'assets/js/module-loader.js', array('jquery', 'jobs-base-script'), '1.0.0', true );

        // Pass global variables and module assets map
        wp_localize_script( 'jobs-module-loader', 'jobs_vars', array(
            'ajax_url'      => admin_url( 'admin-ajax.php' ),
            'admin_url'     => get_permalink( get_page_by_path( 'jobs-admin-panel' ) ),
            'nonce'         => wp_create_nonce( 'jobs_main_nonce' ),
            'plugin_url'    => JOBS_PLUGIN_URL,
            'module_assets' => array(
                'job-posting' => array( 'css' => 'assets/css/modules/job-posting.css' ),
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
        wp_enqueue_style( 'jobs-search-page', JOBS_PLUGIN_URL . 'assets/css/search-page.css', array(), '1.0.0' );
        wp_enqueue_style( 'jobs-job-card', JOBS_PLUGIN_URL . 'assets/css/job-card.css', array(), '1.0.0' );
        wp_enqueue_script( 'jobs-search-engine', JOBS_PLUGIN_URL . 'assets/js/search.js', array('jquery', 'jobs-base-script'), '1.0.0', true );
    }

    // Dashboard specific
    if ( is_page('jobs-dashboard') ) {
        wp_enqueue_style( 'jobs-dashboard', JOBS_PLUGIN_URL . 'assets/css/dashboard.css', array(), '1.0.0' );
        wp_enqueue_script( 'jobs-dashboard', JOBS_PLUGIN_URL . 'assets/js/dashboard.js', array('jquery'), '1.0.0', true );
    }

    // Auth specific
    if ( is_page('login-registration') ) {
        wp_enqueue_style( 'jobs-auth', JOBS_PLUGIN_URL . 'assets/css/auth.css', array(), '1.0.0' );
        wp_enqueue_script( 'jobs-auth-system', JOBS_PLUGIN_URL . 'assets/js/auth.js', array('jquery'), '1.0.0', true );
    }

    // Profile specific
    if ( is_page('profile') || get_query_var('profile_user') ) {
        wp_enqueue_style( 'jobs-public-profile', JOBS_PLUGIN_URL . 'assets/css/public-profile.css', array(), '1.0.0' );
    }
}
add_action( 'wp_enqueue_scripts', 'jobs_enqueue_assets' );
