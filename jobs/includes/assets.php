<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_enqueue_assets() {
    // Rubik font
    wp_enqueue_style( 'google-fonts-rubik', 'https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap', array(), null );

    // Main CSS
    wp_enqueue_style( 'jobs-main-style', JOBS_PLUGIN_URL . 'assets/css/jobs-style.css', array(), '1.0.0' );

    // Main JS
    wp_enqueue_script( 'jobs-main-script', JOBS_PLUGIN_URL . 'assets/js/jobs-script.js', array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'jobs-main-script', 'jobs_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'jobs_main_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'jobs_enqueue_assets' );
