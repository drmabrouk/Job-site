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
            if ( isset( $_POST['jobs_logo_width'] ) ) {
                update_option( 'jobs_logo_width', sanitize_text_field( $_POST['jobs_logo_width'] ) );
            }
            if ( isset( $_POST['jobs_logo_height'] ) ) {
                update_option( 'jobs_logo_height', sanitize_text_field( $_POST['jobs_logo_height'] ) );
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
            if ( isset( $_POST['jobs_adsense_code'] ) ) {
                update_option( 'jobs_adsense_code', wp_kses_post( $_POST['jobs_adsense_code'] ) );
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

        // Check "once per month" constraint for non-admins
        if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'system_admin' ) ) {
            $last_update = get_user_meta( $user_id, 'jobs_last_profile_update', true );
            if ( $last_update && ( time() - $last_update ) < 30 * DAY_IN_SECONDS ) {
                wp_die( 'You can only update your account once per month.' );
            }
        }

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
        update_user_meta( $user_id, 'jobs_last_profile_update', time() );

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

// AJAX Quick Apply Form Loader
function jobs_ajax_load_quick_apply_form() {
    check_ajax_referer( 'jobs_main_nonce', 'nonce' );

    $job_id = intval( $_POST['job_id'] );
    if ( ! $job_id ) wp_send_json_error( 'Invalid job.' );

    ob_start();
    ?>
    <div class="quick-apply-modal-content">
        <h3>Apply for: <?php echo get_the_title($job_id); ?></h3>
        <?php if ( is_user_logged_in() ) : ?>
            <form class="jobs-quick-apply-form">
                <?php wp_nonce_field( 'jobs_quick_apply', 'quick_apply_nonce' ); ?>
                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                <div class="form-group">
                    <label>Cover Letter (Optional)</label>
                    <textarea name="cover_letter" style="width:100%; height: 150px; border: 1px solid #ddd; border-radius: 8px; padding:10px;"></textarea>
                </div>
                <div style="margin-top: 20px;">
                    <button type="button" class="jobs-btn submit-quick-apply">Submit Application</button>
                </div>
            </form>
        <?php else : ?>
            <p>Please <a href="<?php echo get_permalink( get_page_by_path('login-registration') ); ?>">login</a> to apply.</p>
        <?php endif; ?>
    </div>
    <?php
    $content = ob_get_clean();
    wp_send_json_success( $content );
}
add_action( 'wp_ajax_jobs_load_quick_apply_form', 'jobs_ajax_load_quick_apply_form' );
add_action( 'wp_ajax_nopriv_jobs_load_quick_apply_form', 'jobs_ajax_load_quick_apply_form' );

// Handle Job Submission (Frontend)
function jobs_ajax_post_job_handler() {
    check_ajax_referer( 'jobs_post_job', 'jobs_post_nonce' );

    if ( ! is_user_logged_in() || current_user_can('job_seeker') ) {
        wp_send_json_error( 'Permission denied.' );
    }

    $title = sanitize_text_field( $_POST['job_title'] );
    $company = sanitize_text_field( $_POST['company_name'] );
    $logo = esc_url_raw( $_POST['company_logo'] );
    $description = wp_kses_post( $_POST['job_description'] );
    $specialization = sanitize_text_field( $_POST['specialization'] );
    $category = sanitize_text_field( $_POST['category'] );
    $country = sanitize_text_field( $_POST['country'] );
    $city = sanitize_text_field( $_POST['city'] );
    $salary = sanitize_text_field( $_POST['job_salary'] );
    $currency = sanitize_text_field( $_POST['job_currency'] );
    $lat = sanitize_text_field( $_POST['job_lat'] );
    $lng = sanitize_text_field( $_POST['job_lng'] );

    $is_draft = isset( $_POST['is_draft'] ) && $_POST['is_draft'] == '1';
    $status = $is_draft ? 'draft' : 'pending';
    $draft_id = isset( $_POST['draft_id'] ) ? intval( $_POST['draft_id'] ) : 0;

    $job_data = array(
        'post_title'   => $title,
        'post_content' => $description,
        'post_status'  => $status,
        'post_type'    => 'job',
    );

    if ( $draft_id ) {
        // Verify owner
        $draft_post = get_post( $draft_id );
        if ( $draft_post && $draft_post->post_author == get_current_user_id() ) {
            $job_data['ID'] = $draft_id;
            $job_id = wp_update_post( $job_data );
        } else {
            $job_id = wp_insert_post( $job_data );
        }
    } else {
        $job_id = wp_insert_post( $job_data );
    }

    if ( ! $job_id || is_wp_error( $job_id ) ) {
        wp_send_json_error( 'Failed to create job.' );
    }

    update_post_meta( $job_id, '_company_name', $company );
    update_post_meta( $job_id, '_company_logo', $logo );
    update_post_meta( $job_id, '_location_country', $country );
    update_post_meta( $job_id, '_location_city', $city );
    update_post_meta( $job_id, '_job_salary', $salary );
    update_post_meta( $job_id, '_job_currency', $currency );
    update_post_meta( $job_id, '_job_lat', $lat );
    update_post_meta( $job_id, '_job_lng', $lng );

    jobs_log_activity( get_current_user_id(), 'job_post', 'Posted job: ' . $title );

    // Handle taxonomies (simplified, assuming terms exist or creating them)
    if ( $specialization ) wp_set_object_terms( $job_id, $specialization, 'specialization' );
    if ( $category ) wp_set_object_terms( $job_id, $category, 'job_category' );
    if ( $country ) wp_set_object_terms( $job_id, $country, 'country' );
    if ( $city ) wp_set_object_terms( $job_id, $city, 'city' );

    if ( $is_draft ) {
        wp_send_json_success( 'Draft saved successfully.' );
    }

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
        'education'      => sanitize_textarea_field( $_POST['cv_education'] ),
        'experience'     => sanitize_textarea_field( $_POST['cv_experience'] ),
        'skills'         => sanitize_text_field( $_POST['cv_skills'] ),
        'certifications' => sanitize_textarea_field( $_POST['cv_certifications'] ),
    );

    update_user_meta( $user_id, 'jobs_cv_data', $cv_data );

    wp_send_json_success( 'CV updated successfully.' );
}
add_action( 'wp_ajax_jobs_save_cv_handler', 'jobs_ajax_save_cv_handler' );

// AJAX module loader
function jobs_ajax_load_module() {
    check_ajax_referer( 'jobs_main_nonce', 'nonce' );

    $module = sanitize_text_field( $_POST['module'] );
    $file = JOBS_PLUGIN_DIR . 'includes/modules/' . $module . '.php';

    if ( file_exists( $file ) ) {
        ob_start();
        include $file;
        $content = ob_get_clean();
        wp_send_json_success( $content );
    } else {
        wp_send_json_error( 'Module not found.' );
    }
}
add_action( 'wp_ajax_jobs_load_module', 'jobs_ajax_load_module' );

// Handle Company Profile Save
function jobs_ajax_save_company_handler() {
    check_ajax_referer( 'jobs_save_company', 'jobs_company_nonce' );

    $user_id = get_current_user_id();
    if ( ! $user_id ) wp_send_json_error( 'Not logged in.' );

    $company_data = array(
        'name'           => sanitize_text_field( $_POST['company_name'] ),
        'logo'           => esc_url_raw( $_POST['company_logo'] ),
        'details'        => sanitize_textarea_field( $_POST['company_details'] ),
        'address'        => sanitize_text_field( $_POST['company_address'] ),
        'employee_count' => sanitize_text_field( $_POST['company_employee_count'] ),
    );

    update_user_meta( $user_id, 'jobs_company_data', $company_data );

    wp_send_json_success( 'Company profile updated successfully.' );
}
add_action( 'wp_ajax_jobs_save_company_handler', 'jobs_ajax_save_company_handler' );

// Handle Job Deletion (Move to trash)
function jobs_ajax_delete_job() {
    check_ajax_referer( 'jobs_main_nonce', 'nonce' );

    $job_id = intval( $_POST['job_id'] );
    if ( ! $job_id ) wp_send_json_error( 'Invalid job ID.' );

    $post = get_post( $job_id );
    if ( ! $post || $post->post_type !== 'job' ) {
        wp_send_json_error( 'Job not found.' );
    }

    // Check permission
    if ( ! current_user_can( 'administrator' ) && ! current_user_can( 'system_admin' ) && $post->post_author != get_current_user_id() ) {
        wp_send_json_error( 'Permission denied.' );
    }

    wp_trash_post( $job_id );

    wp_send_json_success( 'Job deleted successfully.' );
}
add_action( 'wp_ajax_jobs_delete_job', 'jobs_ajax_delete_job' );

/**
 * AJAX Handler: Get Draft Data
 */
add_action( 'wp_ajax_jobs_get_draft_data', 'jobs_ajax_get_draft_data' );
function jobs_ajax_get_draft_data() {
    check_ajax_referer( 'jobs_main_nonce', 'nonce' );

    $draft_id = intval( $_POST['draft_id'] );
    if ( ! $draft_id ) {
        wp_send_json_error( 'Invalid draft ID' );
    }

    $post = get_post( $draft_id );
    if ( ! $post || $post->post_author != get_current_user_id() ) {
        wp_send_json_error( 'Unauthorized' );
    }

    $data = array(
        'title'           => $post->post_title,
        'specialization'  => get_post_meta( $draft_id, '_specialization', true ),
        'country'         => get_post_meta( $draft_id, '_location_country', true ),
        'city'            => get_post_meta( $draft_id, '_location_city', true ),
        'company_name'    => get_post_meta( $draft_id, '_company_name', true ),
        'company_logo'    => get_post_meta( $draft_id, '_company_logo', true ),
        'job_description' => $post->post_content,
    );

    wp_send_json_success( $data );
}
