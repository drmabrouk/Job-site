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
    if ( isset( $_POST['save_jobs_settings'] ) ) {
        if ( ! Jobs_Permission_Service::verify_nonce( 'jobs_admin_settings_nonce', 'jobs_save_settings' ) ) {
            return;
        }

        if ( Jobs_Permission_Service::is_admin() ) {
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
    if ( isset( $_POST['jobs_save_account'] ) ) {
        if ( ! Jobs_Permission_Service::verify_nonce( 'jobs_account_nonce', 'jobs_update_account' ) ) {
            return;
        }

        $user_id = get_current_user_id();
        if ( ! $user_id ) return;

        // Check "once per month" constraint for non-admins
        if ( ! Jobs_Permission_Service::is_admin( $user_id ) ) {
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

        Jobs_Activity_Service::log( $user_id, 'account_update', 'Updated personal information' );
    }

    // Handle Account Deletion
    if ( isset( $_POST['jobs_delete_account'] ) ) {
        if ( ! Jobs_Permission_Service::verify_nonce( 'jobs_account_nonce', 'jobs_update_account' ) ) {
            return;
        }

        $user_id = get_current_user_id();
        if ( ! $user_id ) return;

        require_once( ABSPATH . 'wp-admin/includes/user.php' );
        wp_delete_user( $user_id );
        wp_safe_redirect( home_url() );
        exit;
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
    Jobs_Permission_Service::check_ajax_nonce( 'jobs_quick_apply', 'quick_apply_nonce' );

    $job_id = intval( $_POST['job_id'] );
    $cover_letter = $_POST['cover_letter'] ?? '';

    $result = Jobs_Job_Service::apply_for_job( $job_id, $cover_letter );

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( $result->get_error_message() );
    }

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
    Jobs_Permission_Service::check_ajax_nonce( 'jobs_post_job', 'jobs_post_nonce' );

    $is_draft = isset( $_POST['is_draft'] ) && $_POST['is_draft'] == '1';
    $status = $is_draft ? 'draft' : 'pending';
    $draft_id = isset( $_POST['draft_id'] ) ? intval( $_POST['draft_id'] ) : 0;

    if ( $draft_id ) {
        $result = Jobs_Job_Service::update_job( $draft_id, $_POST );
    } else {
        $result = Jobs_Job_Service::create_job( $_POST, $status );
    }

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( $result->get_error_message() );
    }

    if ( $is_draft ) {
        wp_send_json_success( 'Draft saved successfully.' );
    }

    // Notify Reviewers/Admins
    $reviewers = get_users( array( 'role__in' => array( 'reviewer', 'system_admin' ) ) );
    foreach ( $reviewers as $reviewer ) {
        Jobs_Job_Service::add_notification( $reviewer->ID, 'New job pending review: ' . sanitize_text_field($_POST['job_title']) );
    }

    wp_send_json_success( 'Job submitted successfully and is pending review.' );
}
add_action( 'wp_ajax_jobs_post_job_handler', 'jobs_ajax_post_job_handler' );

// Handle Job Approval (Reviewer)
function jobs_ajax_approve_job() {
    Jobs_Permission_Service::check_ajax_nonce( 'jobs_approve_nonce', 'nonce' );

    if ( ! Jobs_Permission_Service::can_review_jobs() ) {
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
    Jobs_Job_Service::add_notification( $employer_id, 'Your job has been approved: ' . get_the_title($job_id) );

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
    try {
        Jobs_Permission_Service::check_ajax_nonce( 'jobs_main_nonce', 'nonce' );

        $module = sanitize_text_field( $_POST['module'] );

        // Whitelist allowed modules to prevent LFI
        $allowed_modules = array(
            'job-posting', 'job-listings-history', 'public-profile', 'applications-submitted',
            'job-requests', 'cv-resume', 'company-profile', 'favorites', 'drafts',
            'support', 'settings', 'user-management', 'terms-conditions', 'articles', 'analytics-insights', 'notifications'
        );

        if ( ! in_array( $module, $allowed_modules ) ) {
            throw new Exception( 'Unauthorized module access.' );
        }

        $file = JOBS_PLUGIN_DIR . 'includes/modules/' . $module . '.php';

        if ( ! file_exists( $file ) ) {
            throw new Exception( 'Module file does not exist.' );
        }

        ob_start();
        include $file;
        $content = ob_get_clean();

        wp_send_json_success( $content );

    } catch ( Exception $e ) {
        Jobs_Activity_Service::log( get_current_user_id(), 'error', 'Error loading module: ' . $e->getMessage() );
        wp_send_json_error( array( 'message' => $e->getMessage() ), 500 );
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
/**
 * AJAX Handler: Toggle Favorite
 */
/**
 * AJAX Handler: Get Unread Notification Count
 */
add_action( 'wp_ajax_jobs_get_unread_count', 'jobs_ajax_get_unread_count' );
function jobs_ajax_get_unread_count() {
    Jobs_Permission_Service::check_ajax_nonce( 'jobs_main_nonce', 'nonce' );
    $user_id = get_current_user_id();
    if ( ! $user_id ) wp_send_json_error();

    global $wpdb;
    $table = Jobs_DB_Service::get_table( 'notifications' );
    $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE user_id = %d AND is_read = 0", $user_id ) );
    wp_send_json_success( intval($count) );
}

add_action( 'wp_ajax_jobs_toggle_favorite', 'jobs_ajax_toggle_favorite' );
function jobs_ajax_toggle_favorite() {
    Jobs_Permission_Service::check_ajax_nonce( 'jobs_main_nonce', 'nonce' );

    $user_id = get_current_user_id();
    $job_id = intval( $_POST['job_id'] );

    if ( ! $user_id || ! $job_id ) wp_send_json_error( 'Unauthorized' );

    $favorites = get_user_meta( $user_id, 'jobs_favorites', true ) ?: array();

    if ( ( $key = array_search( $job_id, $favorites ) ) !== false ) {
        unset( $favorites[$key] );
        $status = 'removed';
    } else {
        array_unshift( $favorites, $job_id );
        $favorites = array_slice( $favorites, 0, 50 ); // Keep up to 50
        $status = 'added';
    }

    update_user_meta( $user_id, 'jobs_favorites', $favorites );
    wp_send_json_success( array( 'status' => $status ) );
}

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
