<?php
/**
 * Service: Activity Logging
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Activity_Service {

    public static function log( $user_id, $type, $message ) {
        global $wpdb;
        $table = Jobs_DB_Service::get_table( 'activity_log' );

        $wpdb->insert( $table, array(
            'user_id' => $user_id,
            'type'    => sanitize_text_field( $type ),
            'message' => sanitize_textarea_field( $message ),
            'time'    => current_time( 'mysql' )
        ) );
    }

    public static function get_recent_logs( $limit = 20 ) {
        global $wpdb;
        $table = Jobs_DB_Service::get_table( 'activity_log' );
        return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table ORDER BY time DESC LIMIT %d", $limit ) );
    }
}
