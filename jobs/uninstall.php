<?php
// If uninstall not called from WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/pages.php';
jobs_remove_pages();
