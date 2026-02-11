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
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-sample-data-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-auth-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-backup-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-data-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-ads-service.php';
require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-email-service.php';

// Initialize Services
Jobs_SEO_Service::init();

// Ensure DB schema is up to date (handles sender_id column addition)
add_action( 'init', array( 'Jobs_DB_Service', 'setup_tables' ), 5 );

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
require_once JOBS_PLUGIN_DIR . 'includes/security.php';

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

    // Load Sample Jobs
    Jobs_Sample_Data_Service::reset_and_load_samples();

    flush_rewrite_rules();
}

// Manual or activation hook for sample data loading
function jobs_maybe_load_samples() {
    // Only load if explicitly requested via URL parameter for setup/demo purposes
    if ( isset($_GET['jobs_load_samples']) && current_user_can('manage_options') ) {
        if ( class_exists( 'Jobs_Sample_Data_Service' ) ) {
            Jobs_Sample_Data_Service::reset_and_load_samples();
            update_option( 'jobs_samples_loaded_v2', 1 );
            wp_die('Sample data loaded successfully. <a href="'.admin_url().'">Return to Dashboard</a>');
        }
    }
}
add_action( 'init', 'jobs_maybe_load_samples' );

function jobs_plugin_deactivate() {
    // Roles and pages are typically not removed on deactivation to avoid data loss.
    // They will be removed on uninstallation as handled in uninstall.php.
    flush_rewrite_rules();
}

// Template Loader for Jobs and Profiles
function jobs_template_loader( $template ) {
    // Single Job
    if ( is_singular( 'job' ) ) {
        $plugin_template = JOBS_PLUGIN_DIR . 'templates/single-job.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }

    // Public Profile
    $profile_user = get_query_var( 'profile_user' );

    // Fallback: Check URL path if query var is empty (helps if rewrites aren't flushed)
    if ( ! $profile_user ) {
        $path = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
        $path_parts = explode( '/', $path );
        if ( count( $path_parts ) >= 2 && $path_parts[0] === 'profile' ) {
            $profile_user = $path_parts[1];
            set_query_var( 'profile_user', $profile_user );
        }
    }

    if ( $profile_user ) {
        $plugin_template = JOBS_PLUGIN_DIR . 'templates/public-profile.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }

    // Account Setup Standalone Page
    if ( is_page( 'account-setup' ) ) {
        $plugin_template = JOBS_PLUGIN_DIR . 'templates/account-setup-page.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }

    return $template;
}
add_filter( 'template_include', 'jobs_template_loader' );

/**
 * Add copyright footer to plugin pages
 */
function jobs_render_plugin_footer() {
    if ( is_admin() ) return;

    // Check if it's a plugin page
    $plugin_pages = array( 'job-search', 'login', 'profile', 'job-requests', 'applications-submitted', 'company-profile', 'advanced-settings', 'terms-conditions', 'analytics-insights', 'job-seekers', 'site-settings', 'policies', 'account-setup' );

    $is_plugin_page = false;
    foreach ( $plugin_pages as $slug ) {
        if ( is_page( $slug ) ) {
            $is_plugin_page = true;
            break;
        }
    }

    if ( is_singular('job') || get_query_var('profile_user') ) {
        $is_plugin_page = true;
    }

    if ( $is_plugin_page ) {
        ?>
        <footer class="jobs-global-footer" style="padding: 40px 20px; text-align: center; border-top: 1px solid rgba(0,0,0,0.05); background: transparent; color: #94a3b8; font-size: 0.9em; font-family: 'Rubik', sans-serif;">
            <p>&copy; <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All Rights Reserved.</p>
            <div style="margin-top: 10px; display: flex; justify-content: center; gap: 20px;">
                <a href="<?php echo home_url('/policies/'); ?>" style="color: inherit; text-decoration: none;">Privacy Policy</a>
                <a href="<?php echo home_url('/policies/'); ?>" style="color: inherit; text-decoration: none;">Terms of Use</a>
                <a href="<?php echo home_url('/support/'); ?>" style="color: inherit; text-decoration: none;">Support</a>
            </div>
        </footer>
        <?php
    }
}
add_action( 'wp_footer', 'jobs_render_plugin_footer' );

/**
 * Automatic Update Support
 */
add_filter( 'pre_set_site_transient_update_plugins', 'jobs_check_for_updates' );
function jobs_check_for_updates( $transient ) {
    if ( empty( $transient->checked ) ) {
        return $transient;
    }

    // This is a placeholder for actual remote update check logic
    // In a production environment, you would call a remote API here.

    return $transient;
}

add_filter( 'plugins_api', 'jobs_plugin_info', 20, 3 );
function jobs_plugin_info( $res, $action, $args ) {
    if ( $action !== 'plugin_information' ) return $res;
    if ( $args->slug !== 'jobs' ) return $res;

    // Placeholder for remote plugin info
    return $res;
}
