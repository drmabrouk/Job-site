<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_render_top_bar() {
    static $rendered = false;
    if ( $rendered ) {
        return;
    }

    if ( ! is_user_logged_in() ) {
        return;
    }
    $rendered = true;

    $current_user = wp_get_current_user();
    $roles = $current_user->roles;

    // Only for Job Seekers and Employers as per prompt (Reviewers and Admins might have it too?)
    // "Job Seekers and Employers will use a custom transparent top bar"
    // "The admin control panel is visible only to System Administrators."

    // I will show it for all logged-in users for now, and filter modules by role.

    ob_start();
    ?>
    <div class="jobs-top-bar">
        <div class="jobs-logo">
            <!-- Logo will be here -->
        </div>
        <div class="jobs-user-menu">
            <?php
            global $wpdb;
            $table_notifications = $wpdb->prefix . 'jobs_notifications';
            $unread_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_notifications WHERE user_id = %d AND is_read = 0", $current_user->ID ) );
            ?>
            <div class="jobs-notification-icon" id="jobs-notif-toggle" style="position:relative; margin-right: 15px; cursor: pointer;">
                <span class="dashicons dashicons-bell" style="font-size: 24px; color: var(--jobs-primary-color);"></span>
                <?php if ($unread_count > 0) : ?>
                    <span class="notif-count" style="position:absolute; top:-5px; right:-5px; background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px;"><?php echo $unread_count; ?></span>
                <?php endif; ?>
            </div>
            <img src="<?php echo get_avatar_url( $current_user->ID ); ?>" class="user-avatar" id="jobs-avatar-toggle">
            <div class="jobs-dropdown-menu" id="jobs-dropdown">
                <?php jobs_render_modules_menu(); ?>
            </div>
        </div>
    </div>

    <!-- Module Overlay -->
    <div id="jobs-module-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
        <div id="jobs-module-modal" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:90%; max-width:800px; max-height:90%; background:white; padding:30px; border-radius:12px; overflow-y:auto;">
            <span id="jobs-close-module" style="position:absolute; top:15px; right:20px; cursor:pointer; font-size:24px;">&times;</span>
            <div id="jobs-module-container"></div>
        </div>
    </div>

    <?php
    echo ob_get_clean();
}
add_action( 'astra_header_after', 'jobs_render_top_bar' );
add_action( 'wp_body_open', 'jobs_render_top_bar' ); // Fallback if not using Astra

function jobs_render_modules_menu() {
    $current_user = wp_get_current_user();
    $roles = $current_user->roles;

    $modules = array(
        'job-posting' => array( 'label' => 'Job Posting', 'roles' => array( 'employer', 'reviewer', 'system_admin' ) ),
        'job-listings-history' => array( 'label' => 'Job Listings History', 'roles' => array( 'employer', 'reviewer', 'system_admin' ) ),
        'public-profile' => array( 'label' => 'Public Profile', 'roles' => array( 'job_seeker', 'employer', 'reviewer', 'system_admin' ) ),
        'applications-submitted' => array( 'label' => 'Applications Submitted', 'roles' => array( 'job_seeker' ) ),
        'job-requests' => array( 'label' => 'Job Requests', 'roles' => array( 'employer', 'reviewer', 'system_admin' ) ),
        'cv-resume' => array( 'label' => 'CV / Resume', 'roles' => array( 'job_seeker' ) ),
        'company-profile' => array( 'label' => 'Company Profile', 'roles' => array( 'employer' ) ),
        'favorites' => array( 'label' => 'Favorites', 'roles' => array( 'job_seeker', 'employer', 'reviewer', 'system_admin' ) ),
        'drafts' => array( 'label' => 'Drafts', 'roles' => array( 'job_seeker', 'employer', 'reviewer', 'system_admin' ) ),
        'support' => array( 'label' => 'Support', 'roles' => array( 'job_seeker', 'employer', 'reviewer', 'system_admin' ) ),
        'settings' => array( 'label' => 'Settings', 'roles' => array( 'job_seeker', 'employer', 'reviewer', 'system_admin' ) ),
        'advanced-settings' => array( 'label' => 'Advanced Settings', 'roles' => array( 'system_admin' ) ),
        'user-management' => array( 'label' => 'User Management', 'roles' => array( 'system_admin' ) ),
        'terms-conditions' => array( 'label' => 'Terms & Conditions', 'roles' => array( 'job_seeker', 'employer', 'reviewer', 'system_admin' ) ),
        'articles' => array( 'label' => 'Articles', 'roles' => array( 'job_seeker', 'employer', 'reviewer', 'system_admin' ) ),
    );

    echo '<ul>';
    $visible_modules = get_option( 'jobs_visible_modules', array_keys( $modules ) );

    foreach ( $modules as $slug => $data ) {
        if ( ! in_array( $slug, $visible_modules ) ) continue;

        $allowed = false;
        foreach ( $roles as $role ) {
            if ( in_array( $role, $data['roles'] ) ) {
                $allowed = true;
                break;
            }
        }

        if ( $allowed ) {
            echo '<li><a href="#" class="jobs-module-link" data-module="' . $slug . '">' . $data['label'] . '</a></li>';
        }
    }
    echo '<li><a href="' . wp_logout_url() . '">Logout</a></li>';
    echo '</ul>';
}
