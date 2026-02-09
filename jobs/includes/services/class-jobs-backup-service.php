<?php
/**
 * Service: Backup & Configuration Management
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Backup_Service {

    public static function export_settings() {
        $options = array(
            'blogname',
            'blogdescription',
            'jobs_primary_color',
            'jobs_secondary_color',
            'jobs_font_family',
            'jobs_visible_modules',
            'jobs_seo_description',
            'jobs_index_profiles',
            'jobs_maintenance_mode',
            'jobs_site_logo',
            'jobs_logo_width',
            'jobs_logo_height'
        );

        $data = array();
        foreach ( $options as $opt ) {
            $data[$opt] = get_option( $opt );
        }

        return $data;
    }

    public static function import_settings( $data ) {
        if ( ! is_array( $data ) ) return false;

        foreach ( $data as $key => $val ) {
            update_option( $key, $val );
        }

        return true;
    }
}
