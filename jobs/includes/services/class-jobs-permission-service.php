<?php
/**
 * Service: Permissions & Security
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Permission_Service {

    public static function can_post_job( $user_id = 0 ) {
        if ( ! $user_id ) $user_id = get_current_user_id();
        return user_can( $user_id, 'post_jobs' ) || user_can( $user_id, 'manage_options' );
    }

    public static function can_apply_job( $user_id = 0 ) {
        if ( ! $user_id ) $user_id = get_current_user_id();
        return user_can( $user_id, 'apply_jobs' ) || user_can( $user_id, 'manage_options' );
    }

    public static function is_admin( $user_id = 0 ) {
        if ( ! $user_id ) $user_id = get_current_user_id();
        return user_can( $user_id, 'manage_options' ) || user_can( $user_id, 'manage_jobs_users' );
    }

    public static function can_review_jobs( $user_id = 0 ) {
        if ( ! $user_id ) $user_id = get_current_user_id();
        return user_can( $user_id, 'review_jobs' ) || user_can( $user_id, 'manage_options' );
    }

    public static function verify_nonce( $nonce_name, $action ) {
        if ( ! isset( $_POST[$nonce_name] ) || ! wp_verify_nonce( $_POST[$nonce_name], $action ) ) {
            return false;
        }
        return true;
    }

    public static function check_ajax_nonce( $action = 'jobs_nonce', $query_arg = 'nonce' ) {
        if ( ! check_ajax_referer( $action, $query_arg, false ) ) {
            wp_send_json_error( array( 'message' => 'Invalid security token.' ), 403 );
        }
    }
}
