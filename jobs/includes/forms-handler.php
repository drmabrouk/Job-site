<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_handle_forms() {
    // Handle Registration
    if ( isset( $_POST['jobs_register'] ) && isset( $_POST['jobs_registration_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_POST['jobs_registration_nonce'], 'jobs_register_user' ) ) {
            return;
        }

        $username = sanitize_user( $_POST['user_login'] );
        $email    = sanitize_email( $_POST['user_email'] );
        $password = $_POST['user_pass'];
        $role     = sanitize_text_field( $_POST['user_role'] );

        // Validate role
        $allowed_roles = array( 'job_seeker', 'employer' );
        if ( ! in_array( $role, $allowed_roles ) ) {
            return;
        }

        if ( ! username_exists( $username ) && ! email_exists( $email ) ) {
            $user_id = wp_create_user( $username, $password, $email );
            if ( ! is_wp_error( $user_id ) ) {
                $user = new WP_User( $user_id );
                $user->set_role( $role );
                wp_set_auth_cookie( $user_id );

                $redirect = ! empty( $_POST['_wp_http_referer'] ) ? esc_url_raw( $_POST['_wp_http_referer'] ) : home_url();
                wp_safe_redirect( $redirect );
                exit;
            }
        }
    }

    // Handle Admin Settings
    if ( isset( $_POST['save_jobs_settings'] ) && isset( $_POST['jobs_admin_settings_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_POST['jobs_admin_settings_nonce'], 'jobs_save_settings' ) ) {
            return;
        }

        if ( current_user_can( 'manage_options' ) ) {
            if ( isset( $_POST['jobs_site_logo'] ) ) {
                update_option( 'jobs_site_logo', esc_url_raw( $_POST['jobs_site_logo'] ) );
            }
            if ( isset( $_POST['jobs_search_placeholder'] ) ) {
                update_option( 'jobs_search_placeholder', sanitize_text_field( $_POST['jobs_search_placeholder'] ) );
            }
            if ( isset( $_POST['jobs_archive_days'] ) ) {
                update_option( 'jobs_archive_days', intval( $_POST['jobs_archive_days'] ) );
            }
            if ( isset( $_POST['jobs_visible_modules'] ) ) {
                update_option( 'jobs_visible_modules', array_map( 'sanitize_text_field', $_POST['jobs_visible_modules'] ) );
            } else {
                update_option( 'jobs_visible_modules', array() );
            }
        }
    }

    // Handle User Account Update
    if ( isset( $_POST['jobs_save_account'] ) && isset( $_POST['jobs_account_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_POST['jobs_account_nonce'], 'jobs_update_account' ) ) {
            return;
        }

        $user_id = get_current_user_id();
        if ( ! $user_id ) return;

        $email = sanitize_email( $_POST['user_email'] );
        $display_name = sanitize_text_field( $_POST['display_name'] );
        $visibility = sanitize_text_field( $_POST['profile_visibility'] );

        wp_update_user( array(
            'ID'           => $user_id,
            'user_email'   => $email,
            'display_name' => $display_name,
        ) );

        if ( ! empty( $_POST['user_pass'] ) ) {
            wp_set_password( $_POST['user_pass'], $user_id );
        }

        update_user_meta( $user_id, 'profile_visibility', $visibility );

        // Add activity log entry here if needed
    }
}
add_action( 'init', 'jobs_handle_forms' );

// AJAX handler for sending messages
function jobs_ajax_send_message() {
    check_ajax_referer( 'jobs_messaging_nonce', 'nonce' );

    $receiver_id = intval( $_POST['receiver_id'] );
    $sender_id   = get_current_user_id();
    $message     = sanitize_textarea_field( $_POST['message'] );

    if ( ! $sender_id || ! $receiver_id || ! $message ) {
        wp_send_json_error( 'Invalid data' );
    }

    global $wpdb;
    $table = $wpdb->prefix . 'jobs_messages';
    $wpdb->insert( $table, array(
        'sender_id'   => $sender_id,
        'receiver_id' => $receiver_id,
        'message'     => $message,
    ) );

    // Also create a notification for the receiver
    $table_notifications = $wpdb->prefix . 'jobs_notifications';
    $wpdb->insert( $table_notifications, array(
        'user_id' => $receiver_id,
        'content' => 'You have a new message from ' . get_userdata($sender_id)->display_name,
    ) );

    wp_send_json_success( 'Message sent' );
}
add_action( 'wp_ajax_jobs_send_message', 'jobs_ajax_send_message' );

// Handle Quick Apply
function jobs_ajax_quick_apply() {
    check_ajax_referer( 'jobs_quick_apply', 'quick_apply_nonce' );

    $job_id = intval( $_POST['job_id'] );
    $user_id = get_current_user_id();
    $cover_letter = sanitize_textarea_field( $_POST['cover_letter'] );

    if ( ! $user_id || ! $job_id ) {
        wp_send_json_error( 'Unauthorized or invalid data.' );
    }

    // Create Application post
    $app_id = wp_insert_post( array(
        'post_title'   => 'Application for ' . get_the_title($job_id) . ' by ' . get_userdata($user_id)->display_name,
        'post_content' => $cover_letter,
        'post_status'  => 'publish',
        'post_type'    => 'application',
        'post_author'  => $user_id,
    ) );

    if ( ! is_wp_error( $app_id ) ) {
        update_post_meta( $app_id, '_job_id', $job_id );
    }

    // Save application as a notification for the employer (the post author)
    $employer_id = get_post_field( 'post_author', $job_id );

    global $wpdb;
    $table_notifications = $wpdb->prefix . 'jobs_notifications';
    $wpdb->insert( $table_notifications, array(
        'user_id' => $employer_id,
        'content' => 'New application for job: ' . get_the_title($job_id) . ' from ' . get_userdata($user_id)->display_name,
    ) );

    wp_send_json_success( 'Application submitted.' );
}
add_action( 'wp_ajax_jobs_quick_apply', 'jobs_ajax_quick_apply' );

// Handle Job Submission (Frontend)
function jobs_ajax_post_job_handler() {
    check_ajax_referer( 'jobs_post_job', 'jobs_post_nonce' );

    if ( ! is_user_logged_in() || current_user_can('job_seeker') ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $title = sanitize_text_field( $_POST['job_title'] );
    $company = sanitize_text_field( $_POST['company_name'] );
    $description = wp_kses_post( $_POST['job_description'] );
    $specialization = sanitize_text_field( $_POST['specialization'] );
    $category = sanitize_text_field( $_POST['category'] );
    $country = sanitize_text_field( $_POST['country'] );
    $city = sanitize_text_field( $_POST['city'] );

    $job_id = wp_insert_post( array(
        'post_title'   => $title,
        'post_content' => $description,
        'post_status'  => 'pending', // Pending review
        'post_type'    => 'job',
    ) );

    if ( is_wp_error( $job_id ) ) {
        wp_send_json_error( 'Failed to create job.' );
    }

    update_post_meta( $job_id, '_company_name', $company );

    // Handle taxonomies (simplified, assuming terms exist or creating them)
    if ( $specialization ) wp_set_object_terms( $job_id, $specialization, 'specialization' );
    if ( $category ) wp_set_object_terms( $job_id, $category, 'job_category' );
    if ( $country ) wp_set_object_terms( $job_id, $country, 'country' );
    if ( $city ) wp_set_object_terms( $job_id, $city, 'city' );

    // Notify Reviewers/Admins
    $reviewers = get_users( array( 'role__in' => array( 'reviewer', 'system_admin' ) ) );
    foreach ( $reviewers as $reviewer ) {
        global $wpdb;
        $table_notifications = $wpdb->prefix . 'jobs_notifications';
        $wpdb->insert( $table_notifications, array(
            'user_id' => $reviewer->ID,
            'content' => 'New job pending review: ' . $title . ' from ' . $company,
        ) );
    }

    wp_send_json_success( 'Job submitted successfully and is pending review.' );
}
add_action( 'wp_ajax_jobs_post_job_handler', 'jobs_ajax_post_job_handler' );

// Handle Job Approval (Reviewer)
function jobs_ajax_approve_job() {
    check_ajax_referer( 'jobs_approve_nonce', 'nonce' );

    if ( ! current_user_can( 'reviewer' ) && ! current_user_can( 'system_admin' ) ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $job_id = intval( $_POST['job_id'] );
    if ( ! $job_id ) wp_send_json_error( 'Invalid job ID.' );

    wp_update_post( array(
        'ID'          => $job_id,
        'post_status' => 'publish'
    ) );

    // Notify Employer
    $employer_id = get_post_field( 'post_author', $job_id );
    global $wpdb;
    $table_notifications = $wpdb->prefix . 'jobs_notifications';
    $wpdb->insert( $table_notifications, array(
        'user_id' => $employer_id,
        'content' => 'Your job has been approved: ' . get_the_title($job_id),
    ) );

    wp_send_json_success( 'Job approved and published.' );
}
add_action( 'wp_ajax_jobs_approve_job', 'jobs_ajax_approve_job' );

// Handle CV Save
function jobs_ajax_save_cv_handler() {
    check_ajax_referer( 'jobs_save_cv', 'jobs_cv_nonce' );

    $user_id = get_current_user_id();
    if ( ! $user_id ) wp_send_json_error( 'Not logged in.' );

    $cv_data = array(
        'education'  => sanitize_textarea_field( $_POST['cv_education'] ),
        'experience' => sanitize_textarea_field( $_POST['cv_experience'] ),
        'skills'     => sanitize_text_field( $_POST['cv_skills'] ),
    );

    update_user_meta( $user_id, 'jobs_cv_data', $cv_data );

    wp_send_json_success( 'CV updated successfully.' );
}
add_action( 'wp_ajax_jobs_save_cv_handler', 'jobs_ajax_save_cv_handler' );
