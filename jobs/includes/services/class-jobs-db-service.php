<?php
/**
 * Service: Database & Tables
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_DB_Service {

    public static function setup_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        // Messages
        $table_messages = $wpdb->prefix . 'jobs_messages';
        $sql_messages = "CREATE TABLE $table_messages (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            sender_id bigint(20) NOT NULL,
            receiver_id bigint(20) NOT NULL,
            message text NOT NULL,
            timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            is_read tinyint(1) DEFAULT 0 NOT NULL,
            PRIMARY KEY  (id),
            KEY sender_id (sender_id),
            KEY receiver_id (receiver_id)
        ) $charset_collate;";
        dbDelta( $sql_messages );

        // Notifications
        $table_notifications = $wpdb->prefix . 'jobs_notifications';
        $sql_notifications = "CREATE TABLE $table_notifications (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            sender_id bigint(20) DEFAULT 0 NOT NULL,
            content text NOT NULL,
            timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            is_read tinyint(1) DEFAULT 0 NOT NULL,
            PRIMARY KEY  (id),
            KEY user_id (user_id)
        ) $charset_collate;";
        dbDelta( $sql_notifications );

        // Activity Log
        $table_activity = $wpdb->prefix . 'jobs_activity_log';
        $sql_activity = "CREATE TABLE $table_activity (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            type varchar(50) NOT NULL,
            message text NOT NULL,
            time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY type (type)
        ) $charset_collate;";
        dbDelta( $sql_activity );
    }

    public static function get_table( $name ) {
        global $wpdb;
        return $wpdb->prefix . 'jobs_' . $name;
    }
}
