<?php
/**
 * Plugin Name: Jobs
 * Description: A modern, powerful, and clean job management system.
 * Version: 2.0.0
 * Author: Jobedia
 * Text Domain: jobs
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define constants
define( 'JOBS_VERSION', '2.0.0' );
define( 'JOBS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'JOBS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load Services
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-permission-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-db-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-activity-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-search-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-job-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-seo-service.php';

// Initialize Services
Jobs_SEO_Service::init();

// Include components
require_once JOBS_PLUGIN_DIR . 'includes/roles.php';
require_once JOBS_PLUGIN_DIR . 'includes/cpt.php';
require_once JOBS_PLUGIN_DIR . 'includes/pages.php';
require_once JOBS_PLUGIN_DIR . 'includes/shortcodes.php';
require_once JOBS_PLUGIN_DIR . 'includes/profiles.php';
require_once JOBS_PLUGIN_DIR . 'includes/search-logic.php';
require_once JOBS_PLUGIN_DIR . 'includes/forms-handler.php';
require_once JOBS_PLUGIN_DIR . 'includes/top-bar.php';
require_once JOBS_PLUGIN_DIR . 'includes/admin-panel.php';
require_once JOBS_PLUGIN_DIR . 'includes/admin-settings.php';
require_once JOBS_PLUGIN_DIR . 'includes/assets.php';
require_once JOBS_PLUGIN_DIR . 'includes/cache-prevention.php';

// Activation and Deactivation hooks
register_activation_hook( __FILE__, 'jobs_plugin_activate' );
register_deactivation_hook( __FILE__, 'jobs_plugin_deactivate' );

function jobs_plugin_activate() {
    jobs_create_roles();
    jobs_create_pages();

    // Default Taxonomies
    require_once JOBS_PLUGIN_DIR . 'includes/cpt.php';
    jobs_register_cpt(); // Ensure taxonomy is registered before inserting terms
    jobs_insert_default_specializations();

    // Ensure services are loaded
    require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-db-service.php';
    Jobs_DB_Service::setup_tables();

    // Ensure rewrite rules are registered before flushing
    Jobs_SEO_Service::register_sitemap();

    flush_rewrite_rules();
}

function jobs_plugin_deactivate() {
    // Roles and pages are typically not removed on deactivation to avoid data loss.
    // They will be removed on uninstallation as handled in uninstall.php.
    flush_rewrite_rules();
}

// Template Loader for Single Job
function jobs_template_loader( $template ) {
    if ( is_singular( 'job' ) ) {
        $plugin_template = JOBS_PLUGIN_DIR . 'templates/single-job.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'jobs_template_loader' );
