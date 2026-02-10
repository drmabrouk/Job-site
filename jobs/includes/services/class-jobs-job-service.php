<?php
/**
 * Service: Job Management
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Job_Service {

    public static function create_job( $data, $status = 'pending' ) {
        $user_id = get_current_user_id();
        if ( ! Jobs_Permission_Service::can_post_job( $user_id ) ) {
            return new WP_Error( 'unauthorized', 'You do not have permission to post jobs.' );
        }

        $job_id = wp_insert_post( array(
            'post_title'   => sanitize_text_field( $data['job_title'] ),
            'post_content' => wp_kses_post( $data['job_description'] ),
            'post_status'  => $status,
            'post_type'    => 'job',
            'post_author'  => $user_id,
        ) );

        if ( is_wp_error( $job_id ) ) return $job_id;

        self::update_job_meta( $job_id, $data );

        Jobs_Activity_Service::log( $user_id, 'job_post', 'Created job: ' . $data['job_title'] );

        return $job_id;
    }

    public static function update_job( $job_id, $data ) {
        $user_id = get_current_user_id();
        $post = get_post( $job_id );

        if ( ! $post || ( $post->post_author != $user_id && ! Jobs_Permission_Service::is_admin() ) ) {
            return new WP_Error( 'unauthorized', 'Unauthorized access.' );
        }

        wp_update_post( array(
            'ID'           => $job_id,
            'post_title'   => sanitize_text_field( $data['job_title'] ),
            'post_content' => wp_kses_post( $data['job_description'] ),
        ) );

        self::update_job_meta( $job_id, $data );
        Jobs_Activity_Service::log( $user_id, 'job_update', 'Updated job: ' . $data['job_title'] );

        return $job_id;
    }

    private static function update_job_meta( $job_id, $data ) {
        $author_id = get_post_field( 'post_author', $job_id );
        $company_data = get_user_meta( $author_id, 'jobs_company_data', true ) ?: array();

        $company_name = !empty($data['company_name']) ? $data['company_name'] : ($company_data['name'] ?? '');
        $company_logo = !empty($data['company_logo']) ? $data['company_logo'] : ($company_data['logo'] ?? '');

        update_post_meta( $job_id, '_company_name', sanitize_text_field( $company_name ) );
        update_post_meta( $job_id, '_company_logo', esc_url_raw( $company_logo ) );
        update_post_meta( $job_id, '_job_salary', sanitize_text_field( $data['job_salary'] ) );
        update_post_meta( $job_id, '_job_currency', sanitize_text_field( $data['job_currency'] ) );
        update_post_meta( $job_id, '_job_lat', sanitize_text_field( $data['job_lat'] ) );
        update_post_meta( $job_id, '_job_lng', sanitize_text_field( $data['job_lng'] ) );

        if ( isset( $data['specialization'] ) ) wp_set_object_terms( $job_id, $data['specialization'], 'specialization' );
        if ( isset( $data['category'] ) ) wp_set_object_terms( $job_id, $data['category'], 'job_category' );
        if ( isset( $data['country'] ) ) wp_set_object_terms( $job_id, $data['country'], 'country' );
        if ( isset( $data['city'] ) ) wp_set_object_terms( $job_id, $data['city'], 'city' );
        if ( isset( $data['state'] ) ) wp_set_object_terms( $job_id, $data['state'], 'state' );
    }

    public static function apply_for_job( $job_id, $cover_letter ) {
        $user_id = get_current_user_id();
        if ( ! Jobs_Permission_Service::can_apply_job( $user_id ) ) {
            return new WP_Error( 'unauthorized', 'Please login to apply.' );
        }

        $app_id = wp_insert_post( array(
            'post_title'   => 'Application for ' . get_the_title($job_id) . ' by ' . get_userdata($user_id)->display_name,
            'post_content' => sanitize_textarea_field( $cover_letter ),
            'post_status'  => 'publish',
            'post_type'    => 'application',
            'post_author'  => $user_id,
        ) );

        if ( ! is_wp_error( $app_id ) ) {
            update_post_meta( $app_id, '_job_id', $job_id );

            // Notify employer
            $employer_id = get_post_field( 'post_author', $job_id );
            self::add_notification( $employer_id, 'New application for: ' . get_the_title($job_id) );
        }

        return $app_id;
    }

    public static function add_notification( $user_id, $content, $sender_id = 0 ) {
        global $wpdb;
        $table = Jobs_DB_Service::get_table( 'notifications' );
        $data = array(
            'user_id'   => $user_id,
            'sender_id' => intval( $sender_id ),
            'content'   => sanitize_text_field( $content ),
        );

        $inserted = $wpdb->insert( $table, $data );

        // Fallback for missing sender_id column if insert failed
        if ( false === $inserted ) {
            unset($data['sender_id']);
            $wpdb->insert( $table, $data );
        }
    }
}
