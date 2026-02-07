<?php
/**
 * Top Bar Implementation
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_render_top_bar() {
    static $rendered = false;
    if ( $rendered ) return;
    $rendered = true;

    $current_user = wp_get_current_user();
    $is_logged_in = is_user_logged_in();

    ob_start();
    ?>
    <div class="jobs-top-bar-fixed">
        <div class="top-bar-content">
            <div class="top-bar-left">
                <!-- Branding or Home Link -->
                <a href="<?php echo home_url(); ?>" class="top-bar-home">
                    <span class="dashicons dashicons-admin-site"></span>
                    <span class="site-name"><?php bloginfo('name'); ?></span>
                </a>
            </div>

            <div class="top-bar-right">
                <?php if ( $is_logged_in ) : ?>
                    <!-- Applications Menu Toggle -->
                    <div class="top-bar-icon-item" id="jobs-apps-toggle" title="Applications">
                        <span class="dashicons dashicons-grid-view"></span>
                    </div>

                    <!-- Notifications -->
                    <?php
                    global $wpdb;
                    $table_notifications = $wpdb->prefix . 'jobs_notifications';
                    $unread_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_notifications WHERE user_id = %d AND is_read = 0", $current_user->ID ) );
                    ?>
                    <div class="top-bar-icon-item" id="jobs-notif-toggle" title="Notifications">
                        <span class="dashicons dashicons-bell"></span>
                        <?php if ($unread_count > 0) : ?>
                            <span class="notif-badge"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="top-bar-user-item">
                        <img src="<?php echo get_avatar_url( $current_user->ID ); ?>" class="user-avatar-small" id="jobs-profile-toggle">
                        <div class="jobs-profile-dropdown" id="jobs-profile-menu">
                            <div class="dropdown-header">
                                <strong><?php echo esc_html( $current_user->display_name ); ?></strong>
                                <span><?php echo esc_html( $current_user->user_email ); ?></span>
                            </div>
                            <ul>
                                <li><a href="#" class="jobs-module-link" data-module="settings">Account Settings</a></li>
                                <li><a href="#" class="jobs-module-link" data-module="public-profile">Activity / Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="<?php echo wp_logout_url(); ?>">Logout</a></li>
                            </ul>
                        </div>
                    </div>

                <?php else : ?>
                    <!-- Logged out view -->
                    <a href="<?php echo get_permalink( get_page_by_path('login-registration') ); ?>" class="jobs-btn-small">Login / Register</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Applications Dropdown Menu -->
        <div class="jobs-apps-dropdown" id="jobs-apps-menu">
            <div class="dropdown-inner">
                <h4>Applications</h4>
                <?php jobs_render_modules_menu(); ?>
            </div>
        </div>
    </div>

    <!-- Module Overlay -->
    <div id="jobs-module-overlay" style="display:none;">
        <div id="jobs-module-modal">
            <span id="jobs-close-module">&times;</span>
            <div id="jobs-module-container"></div>
        </div>
    </div>
    <?php
    echo ob_get_clean();
}
add_action( 'astra_header_after', 'jobs_render_top_bar' );
add_action( 'wp_body_open', 'jobs_render_top_bar' );

function jobs_render_modules_menu() {
    $current_user = wp_get_current_user();
    if ( ! is_user_logged_in() ) return;
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
    echo '</ul>';
}
