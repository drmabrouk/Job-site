<?php
/**
 * Plugin Name: Jobs
 * Description: A modern, powerful, and clean job management system.
 * Version: 1.0.0
 * Author: Jules
 * Text Domain: jobs
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define constants
define( 'JOBS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'JOBS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include files
require_once JOBS_PLUGIN_DIR . 'includes/roles.php';
require_once JOBS_PLUGIN_DIR . 'includes/cpt.php';
require_once JOBS_PLUGIN_DIR . 'includes/pages.php';
require_once JOBS_PLUGIN_DIR . 'includes/shortcodes.php';
require_once JOBS_PLUGIN_DIR . 'includes/search-logic.php';
require_once JOBS_PLUGIN_DIR . 'includes/forms-handler.php';
require_once JOBS_PLUGIN_DIR . 'includes/top-bar.php';
require_once JOBS_PLUGIN_DIR . 'includes/admin-panel.php';
require_once JOBS_PLUGIN_DIR . 'includes/assets.php';
require_once JOBS_PLUGIN_DIR . 'includes/cache-prevention.php';

// Activation and Deactivation hooks
register_activation_hook( __FILE__, 'jobs_plugin_activate' );
register_deactivation_hook( __FILE__, 'jobs_plugin_deactivate' );

function jobs_plugin_activate() {
    jobs_create_roles();
    jobs_create_pages();
    require_once JOBS_PLUGIN_DIR . 'includes/cpt.php';
    jobs_database_setup();
    flush_rewrite_rules();
}

function jobs_plugin_deactivate() {
    jobs_remove_pages();
    // Roles are typically not removed on deactivation to avoid data loss,
    // but the prompt says pages must be removed.
    flush_rewrite_rules();
}
