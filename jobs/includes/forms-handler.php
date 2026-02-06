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
            // Add more search engine customization here
        }
    }
}
add_action( 'init', 'jobs_handle_forms' );
