<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_handle_forms() {
    // Handle Registration
    if ( isset( $_POST['jobs_register'] ) && isset( $_POST['jobs_registration_nonce'] ) ) {
        global $jobs_registration_error;

        if ( ! wp_verify_nonce( $_POST['jobs_registration_nonce'], 'jobs_register_user' ) ) {
            $jobs_registration_error = 'Security check failed. Please try again.';
            return;
        }

        $username = sanitize_user( $_POST['user_login'] );
        $email    = sanitize_email( $_POST['user_email'] );
        $password = $_POST['user_pass'];
        $password_confirm = $_POST['user_pass_confirm'];
        $role     = sanitize_text_field( $_POST['user_role'] );

        // Validate password match
        if ( $password !== $password_confirm ) {
            $jobs_registration_error = 'Passwords do not match.';
            return;
        }

        // Validate role
        $allowed_roles = array( 'job_seeker', 'employer' );
        if ( ! in_array( $role, $allowed_roles ) ) {
            $jobs_registration_error = 'Invalid role selected.';
            return;
        }

        if ( username_exists( $username ) ) {
            $jobs_registration_error = 'Username already exists.';
            return;
        }

        if ( email_exists( $email ) ) {
            $jobs_registration_error = 'Email address already registered.';
            return;
        }

        $user_id = wp_create_user( $username, $password, $email );
        if ( ! is_wp_error( $user_id ) ) {
            $user = new WP_User( $user_id );
            $user->set_role( $role );

            // Save First and Last Name
            if ( isset( $_POST['first_name'] ) ) {
                wp_update_user( array(
                    'ID'         => $user_id,
                    'first_name' => sanitize_text_field( $_POST['first_name'] ),
                    'last_name'  => sanitize_text_field( $_POST['last_name'] ?? '' ),
                ) );
            }

            wp_set_auth_cookie( $user_id );

            $redirect = ! empty( $_POST['_wp_http_referer'] ) ? esc_url_raw( $_POST['_wp_http_referer'] ) : home_url();
            wp_safe_redirect( $redirect );
            exit;
        } else {
            $jobs_registration_error = $user_id->get_error_message();
        }
    }

    // Handle Site Settings (Advanced Control)
    if ( isset( $_POST['save_site_settings'] ) ) {
        if ( ! Jobs_Permission_Service::verify_nonce( 'jobs_admin_settings_nonce', 'jobs_save_settings' ) ) {
            return;
        }

        if ( Jobs_Permission_Service::is_system_admin() ) {
            // Branding
            if ( isset( $_POST['blogname'] ) ) update_option( 'blogname', sanitize_text_field( $_POST['blogname'] ) );
            if ( isset( $_POST['blogdescription'] ) ) update_option( 'blogdescription', sanitize_text_field( $_POST['blogdescription'] ) );
            if ( isset( $_POST['jobs_site_logo'] ) ) update_option( 'jobs_site_logo', esc_url_raw( $_POST['jobs_site_logo'] ) );

            // Appearance
            if ( isset( $_POST['jobs_primary_color'] ) ) update_option( 'jobs_primary_color', sanitize_hex_color( $_POST['jobs_primary_color'] ) );
            if ( isset( $_POST['jobs_secondary_color'] ) ) update_option( 'jobs_secondary_color', sanitize_hex_color( $_POST['jobs_secondary_color'] ) );
            if ( isset( $_POST['jobs_font_family'] ) ) update_option( 'jobs_font_family', sanitize_text_field( $_POST['jobs_font_family'] ) );

            // System
            if ( isset( $_POST['admin_email'] ) ) update_option( 'admin_email', sanitize_email( $_POST['admin_email'] ) );
            update_option( 'jobs_enable_notifs', isset( $_POST['jobs_enable_notifs'] ) ? 1 : 0 );

            // Permissions
            if ( isset( $_POST['jobs_visible_modules'] ) ) {
                update_option( 'jobs_visible_modules', array_map( 'sanitize_text_field', $_POST['jobs_visible_modules'] ) );
            } else {
                update_option( 'jobs_visible_modules', array() );
            }

            // Security
            if ( ! empty( $_POST['new_admin_pass'] ) ) {
                wp_set_password( $_POST['new_admin_pass'], get_current_user_id() );
            }
            update_option( 'jobs_maintenance_mode', isset( $_POST['jobs_maintenance_mode'] ) ? 1 : 0 );

            if ( isset( $_POST['jobs_adsense_code'] ) ) {
                $adsense_code = current_user_can('unfiltered_html') ? $_POST['jobs_adsense_code'] : wp_kses_post( $_POST['jobs_adsense_code'] );
                update_option( 'jobs_adsense_code', wp_unslash( $adsense_code ) );
            }

            Jobs_Activity_Service::log( get_current_user_id(), 'system_update', 'Updated site settings' );
        }
    }

    // Legacy Support for simple settings (if any still use it)
    if ( isset( $_POST['save_jobs_settings'] ) ) {
        if ( ! Jobs_Permission_Service::verify_nonce( 'jobs_admin_settings_nonce', 'jobs_save_settings' ) ) return;
        if ( Jobs_Permission_Service::is_admin() ) {
            if ( isset( $_POST['jobs_site_logo'] ) ) update_option( 'jobs_site_logo', esc_url_raw( $_POST['jobs_site_logo'] ) );
            if ( isset( $_POST['jobs_logo_width'] ) ) update_option( 'jobs_logo_width', sanitize_text_field( $_POST['jobs_logo_width'] ) );
            if ( isset( $_POST['jobs_logo_height'] ) ) update_option( 'jobs_logo_height', sanitize_text_field( $_POST['jobs_logo_height'] ) );
            if ( isset( $_POST['jobs_search_placeholder'] ) ) update_option( 'jobs_search_placeholder', sanitize_text_field( $_POST['jobs_search_placeholder'] ) );
            if ( isset( $_POST['jobs_archive_days'] ) ) update_option( 'jobs_archive_days', intval( $_POST['jobs_archive_days'] ) );
            if ( isset( $_POST['jobs_visible_modules'] ) ) update_option( 'jobs_visible_modules', array_map( 'sanitize_text_field', $_POST['jobs_visible_modules'] ) );
            if ( isset( $_POST['jobs_adsense_code'] ) ) update_option( 'jobs_adsense_code', wp_kses_post( $_POST['jobs_adsense_code'] ) );
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
        $new_username = sanitize_user( $_POST['user_login_change'] );

        $update_data = array(
            'ID'           => $user_id,
            'user_email'   => $email,
            'display_name' => $display_name,
        );

        // Update username if changed and allowed
        $current_user = get_userdata( $user_id );
        if ( ! empty( $new_username ) && $new_username !== $current_user->user_login ) {
            if ( ! username_exists( $new_username ) ) {
                global $wpdb;
                $wpdb->update( $wpdb->users, array( 'user_login' => $new_username ), array( 'ID' => $user_id ) );
                clean_user_cache( $user_id );
            }
        }

        wp_update_user( $update_data );

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
    $table = Jobs_DB_Service::get_table( 'messages' );
    $wpdb->insert( $table, array(
        'sender_id'   => $sender_id,
        'receiver_id' => $receiver_id,
        'message'     => $message,
    ) );

    // Also create a notification for the receiver
    $table_notifications = Jobs_DB_Service::get_table( 'notifications' );
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

    wp_send_json_success( 'Application submitted successfully!' );
}
add_action( 'wp_ajax_jobs_quick_apply', 'jobs_ajax_quick_apply' );

// AJAX Quick Apply Form Loader (Multi-step)
function jobs_ajax_load_quick_apply_form() {
    check_ajax_referer( 'jobs_main_nonce', 'nonce' );

    $job_id = intval( $_POST['job_id'] );
    if ( ! $job_id ) wp_send_json_error( 'Invalid job.' );

    $user_id = get_current_user_id();
    $saved_letters = get_user_meta( $user_id, 'jobs_cover_letters', true ) ?: array('', '');

    ob_start();
    ?>
    <div class="quick-apply-multi-step">
        <div class="apply-steps-header">
            <div class="apply-step-indicator active" data-step="1">1. Letter</div>
            <div class="apply-step-indicator" data-step="2">2. Profile</div>
            <div class="apply-step-indicator" data-step="3">3. Review</div>
            <div class="apply-step-indicator" data-step="4">4. Submit</div>
        </div>

        <div class="apply-step-panel active" id="apply-step-1">
            <h3>Choose or Write Cover Letter</h3>
            <p style="font-size: 0.85em; color: #64748b; margin-bottom: 20px;">Select one of your saved letters or write a new one for this application.</p>

            <div class="saved-letters-selector" style="display: flex; gap: 10px; margin-bottom: 20px;">
                <button type="button" class="jobs-btn-small select-saved-letter" data-index="0">Letter 1</button>
                <button type="button" class="jobs-btn-small select-saved-letter" data-index="1">Letter 2</button>
            </div>

            <form class="jobs-quick-apply-form">
                <?php wp_nonce_field( 'jobs_quick_apply', 'quick_apply_nonce' ); ?>
                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">

                <div class="form-group">
                    <textarea name="cover_letter" id="apply-cover-letter-text" placeholder="Write your cover letter here..." style="width:100%; height: 250px; border: 1px solid #e2e8f0; border-radius: 12px; padding:15px;"></textarea>
                </div>

                <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <div class="save-letter-actions">
                        <button type="button" class="jobs-btn-minimal save-current-letter" data-index="0" style="font-size: 0.75em;">Save to Slot 1</button>
                        <button type="button" class="jobs-btn-minimal save-current-letter" data-index="1" style="font-size: 0.75em;">Save to Slot 2</button>
                    </div>
                    <button type="button" class="jobs-btn next-apply-step" data-next="2">Next Step</button>
                </div>
            </form>
        </div>

        <div class="apply-step-panel" id="apply-step-2">
            <h3>Your Professional Profile</h3>
            <p style="font-size: 0.85em; color: #64748b; margin-bottom: 20px;">This is a summary of the profile that will be sent to the employer along with your letter.</p>

            <div class="profile-preview-box" style="background: #f8fafc; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                <?php
                $user = get_userdata($user_id);
                $cv = get_user_meta($user_id, 'jobs_cv_data', true) ?: array();
                ?>
                <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px;">
                    <?php echo get_avatar($user_id, 60, '', '', array('class'=>'rounded-full')); ?>
                    <div>
                        <strong style="display: block; font-size: 1.1em;"><?php echo esc_html($user->display_name); ?></strong>
                        <span style="font-size: 0.85em; color: #64748b;"><?php echo esc_html(get_user_meta($user_id, '_specialization', true) ?: 'Professional'); ?></span>
                    </div>
                </div>
                <div style="font-size: 0.9em; line-height: 1.6; color: #475569;">
                    <div style="margin-bottom: 10px;"><strong>Experience:</strong> <?php echo esc_html(get_user_meta($user_id, '_experience', true) ?: '0'); ?> Years</div>
                    <div style="margin-bottom: 10px;"><strong>Skills:</strong> <?php echo esc_html($cv['skills'] ?? 'Not specified'); ?></div>
                    <div><strong>Education:</strong> <?php echo wp_trim_words($cv['education'] ?? 'Not specified', 20); ?></div>
                </div>
                <div style="margin-top: 20px; text-align: center;">
                    <a href="#" class="jobs-module-link" data-module="cv-resume" style="font-size: 0.8em; color: var(--jobs-primary-color); text-decoration: underline;">Update Profile</a>
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <button type="button" class="jobs-btn-minimal next-apply-step" data-next="1">Back</button>
                <button type="button" class="jobs-btn next-apply-step" data-next="3">Next: Review Letter</button>
            </div>
        </div>

        <div class="apply-step-panel" id="apply-step-3">
            <h3>Review Application</h3>
            <div class="review-box" style="background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 20px; max-height: 300px; overflow-y: auto;">
                <div id="review-letter-content" style="white-space: pre-wrap; font-size: 0.95em; color: #334155;"></div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" class="jobs-btn-minimal export-pdf-letter">Export as PDF</button>
                <button type="button" class="jobs-btn-minimal print-letter">Print Preview</button>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <button type="button" class="jobs-btn-minimal next-apply-step" data-next="2">Back</button>
                <button type="button" class="jobs-btn next-apply-step" data-next="4">Finalize</button>
            </div>
        </div>

        <div class="apply-step-panel" id="apply-step-4">
            <h3>Ready to Submit?</h3>
            <p>You are about to apply for <strong><?php echo get_the_title($job_id); ?></strong>. Your professional profile and cover letter will be sent to the employer.</p>

            <div style="margin-top: 40px; display: flex; flex-direction: column; gap: 15px;">
                <button type="button" class="jobs-btn submit-quick-apply" style="width: 100%; padding: 18px;">Confirm and Send Application</button>
                <button type="button" class="jobs-btn-minimal next-apply-step" data-next="3" style="width: 100%;">Wait, let me check again</button>
            </div>
        </div>

        <script>
            window.savedCoverLetters = <?php echo json_encode($saved_letters); ?>;
        </script>
    </div>
    <?php
    $content = ob_get_clean();
    wp_send_json_success( $content );
}
add_action( 'wp_ajax_jobs_load_quick_apply_form', 'jobs_ajax_load_quick_apply_form' );

// AJAX Handler: Save Cover Letter
function jobs_ajax_save_cover_letter() {
    check_ajax_referer( 'jobs_main_nonce', 'nonce' );
    $user_id = get_current_user_id();
    if ( ! $user_id ) wp_send_json_error( 'Not logged in' );

    $index = intval( $_POST['index'] );
    $content = sanitize_textarea_field( $_POST['content'] );

    $letters = get_user_meta( $user_id, 'jobs_cover_letters', true ) ?: array('', '');
    $letters[$index] = $content;
    update_user_meta( $user_id, 'jobs_cover_letters', $letters );

    wp_send_json_success( 'Letter saved successfully.' );
}
add_action( 'wp_ajax_jobs_save_cover_letter', 'jobs_ajax_save_cover_letter' );

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

    // Update User Description (Bio)
    if ( isset( $_POST['cv_bio'] ) ) {
        wp_update_user( array(
            'ID'          => $user_id,
            'description' => sanitize_textarea_field( $_POST['cv_bio'] )
        ) );
    }

    // Also update individual meta for filtering
    update_user_meta( $user_id, '_nationality', sanitize_text_field( $_POST['cv_nationality'] ) );
    update_user_meta( $user_id, '_gender', sanitize_text_field( $_POST['cv_gender'] ) );
    update_user_meta( $user_id, '_qualification', sanitize_text_field( $_POST['cv_qualification'] ) );
    update_user_meta( $user_id, '_english_level', sanitize_text_field( $_POST['cv_english_level'] ) );
    update_user_meta( $user_id, '_experience', intval( $_POST['cv_experience_years'] ) );
    update_user_meta( $user_id, '_specialization', sanitize_text_field( $_POST['cv_specialization'] ) );

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
            'support', 'settings', 'user-management', 'terms-conditions', 'articles', 'analytics-insights', 'notifications',
            'advanced-settings'
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

/**
 * AJAX Handler: Get Notifications for Dropdown
 */
add_action( 'wp_ajax_jobs_get_notifications', 'jobs_ajax_get_notifications' );
function jobs_ajax_get_notifications() {
    Jobs_Permission_Service::check_ajax_nonce( 'jobs_main_nonce', 'nonce' );
    $user_id = get_current_user_id();
    if ( ! $user_id ) wp_send_json_error();

    global $wpdb;
    $table = Jobs_DB_Service::get_table( 'notifications' );
    $notifs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE user_id = %d ORDER BY timestamp DESC LIMIT 10", $user_id ) );

    ob_start();
    if ( $notifs ) {
        foreach ( $notifs as $n ) {
            $class = $n->is_read ? '' : 'unread';
            echo '<div class="notif-item ' . $class . '">';
            echo '<div class="notif-content">' . esc_html($n->content) . '</div>';
            echo '<div style="font-size: 0.7em; color: #999; margin-top: 5px;">' . human_time_diff(strtotime($n->timestamp), current_time('timestamp')) . ' ago</div>';
            echo '</div>';
        }
        // Mark all as read after fetching for dropdown
        $wpdb->update( $table, array('is_read' => 1), array('user_id' => $user_id) );
    } else {
        echo '<p style="padding:20px; text-align:center; color:#999;">No notifications yet.</p>';
    }
    $content = ob_get_clean();
    wp_send_json_success( $content );
}

add_action( 'wp_ajax_jobs_toggle_favorite', 'jobs_ajax_toggle_favorite' );
function jobs_ajax_toggle_favorite() {
    Jobs_Permission_Service::check_ajax_nonce( 'jobs_main_nonce', 'nonce' );

    $user_id = get_current_user_id();
    $job_id = intval( $_POST['job_id'] );

    if ( ! $user_id || ! $job_id ) wp_send_json_error( 'Unauthorized' );

    $favorites = get_user_meta( $user_id, 'jobs_favorites', true ) ?: array();

    // Check if it's stored in the new associative format or old flat array
    $is_associative = (bool)count(array_filter(array_keys($favorites), 'is_string')) || (empty($favorites) === false && is_numeric(array_keys($favorites)[0]) === false);

    // Normalize to associative for internal processing if it was flat
    if (!empty($favorites) && !$is_associative) {
        $temp = array();
        foreach ($favorites as $id) { $temp[$id] = time(); }
        $favorites = $temp;
    }

    if ( isset( $favorites[$job_id] ) ) {
        unset( $favorites[$job_id] );
        $status = 'removed';
    } else {
        $favorites[$job_id] = time();
        if (count($favorites) > 50) {
            asort($favorites); // Sort by time
            array_shift($favorites); // Remove oldest
        }
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

/**
 * AJAX Handler: Filter Job Seekers
 */
add_action( 'wp_ajax_jobs_filter_seekers', 'jobs_ajax_filter_seekers' );
add_action( 'wp_ajax_nopriv_jobs_filter_seekers', 'jobs_ajax_filter_seekers' );
function jobs_ajax_filter_seekers() {
    $specialization = isset($_POST['specialization']) ? sanitize_text_field($_POST['specialization']) : '';
    $qualification  = isset($_POST['qualification']) ? sanitize_text_field($_POST['qualification']) : '';
    $nationality    = isset($_POST['nationality']) ? sanitize_text_field($_POST['nationality']) : '';
    $experience     = isset($_POST['experience']) ? intval($_POST['experience']) : '';
    $gender         = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : '';
    $english_level  = isset($_POST['english_level']) ? sanitize_text_field($_POST['english_level']) : '';

    $args = array(
        'role' => 'job_seeker',
        'meta_query' => array('relation' => 'AND')
    );

    if ($specialization) {
        $args['meta_query'][] = array(
            'key' => '_specialization',
            'value' => $specialization,
            'compare' => '='
        );
    }
    if ($qualification) {
        $args['meta_query'][] = array(
            'key' => '_qualification',
            'value' => $qualification,
            'compare' => '='
        );
    }
    if ($nationality) {
        $args['meta_query'][] = array(
            'key' => '_nationality',
            'value' => $nationality,
            'compare' => 'LIKE'
        );
    }
    if ($experience !== '') {
        $args['meta_query'][] = array(
            'key' => '_experience',
            'value' => $experience,
            'compare' => '>=',
            'type' => 'NUMERIC'
        );
    }
    if ($gender) {
        $args['meta_query'][] = array(
            'key' => '_gender',
            'value' => $gender,
            'compare' => '='
        );
    }
    if ($english_level) {
        $args['meta_query'][] = array(
            'key' => '_english_level',
            'value' => $english_level,
            'compare' => '='
        );
    }

    $user_query = new WP_User_Query( $args );
    $seekers = $user_query->get_results();

    if ( ! empty( $seekers ) ) {
        foreach ( $seekers as $seeker ) {
            include JOBS_PLUGIN_DIR . 'templates/seeker-card.php';
        }
    } else {
        echo '<p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">No candidates found matching your criteria.</p>';
    }
    wp_die();
}

/**
 * AJAX Handler: Send Direct Job Offer
 */
add_action( 'wp_ajax_jobs_send_job_offer', 'jobs_ajax_send_job_offer' );
function jobs_ajax_send_job_offer() {
    Jobs_Permission_Service::check_ajax_nonce( 'jobs_main_nonce', 'nonce' );

    if ( ! Jobs_Permission_Service::can_post_job() ) {
        wp_send_json_error( 'Only Employers or Admins can send job offers.' );
    }

    $seeker_id = intval( $_POST['seeker_id'] );
    $message   = sanitize_textarea_field( $_POST['message'] );

    if ( ! $seeker_id || ! $message ) {
        wp_send_json_error( 'Invalid request.' );
    }

    global $wpdb;
    $table = Jobs_DB_Service::get_table( 'messages' );
    $wpdb->insert( $table, array(
        'sender_id'   => get_current_user_id(),
        'receiver_id' => $seeker_id,
        'message'     => 'DIRECT JOB OFFER: ' . $message,
    ) );

    // Notify seeker
    Jobs_Job_Service::add_notification( $seeker_id, 'You have received a direct job offer!' );

    wp_send_json_success( 'Job offer sent successfully.' );
}
