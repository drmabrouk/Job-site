<?php
/**
 * Top Bar & Shortcode refactoring for custom external header integration.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the essential overlays globally in the footer.
 * These are needed for the shortcode-triggered menus to work.
 */
function jobs_render_essential_overlays() {
    if ( is_admin() ) return;
    ?>
    <!-- Grid-based Account Management Menu Overlay -->
    <div class="jobs-apps-overlay-container" id="jobs-apps-menu">
        <div class="apps-grid-card">
            <div class="apps-grid-header">
                <h3>Account Management</h3>
                <span class="apps-grid-close" id="jobs-apps-close">&times;</span>
            </div>
            <div class="apps-grid-content">
                <?php jobs_render_modules_grid(); ?>
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
}
add_action( 'wp_footer', 'jobs_render_essential_overlays' );

/**
 * Shortcode: [account_management_icon]
 * Outputs the Account Management icon.
 */
function jobs_account_management_shortcode() {
    if ( ! is_user_logged_in() ) return '';

    ob_start();
    ?>
    <div class="jobs-standalone-icon-wrap profile-management-trigger">
        <div class="top-bar-icon-item" id="jobs-account-mgmt-toggle" title="Account Management">
            <span class="dashicons dashicons-screenoptions"></span>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Shortcode: [account_icon]
 * Outputs Login state, avatar, account dropdown, and notifications icon.
 */
/**
 * Shortcode: [jobedia_logo]
 * Outputs the site logo, specifically styled for the top bar.
 */
function jobs_logo_shortcode() {
    $logo_url = get_option( 'jobs_site_logo' );
    if ( ! $logo_url ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo_url = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id , 'full' )[0] : '';
    }

    if ( ! $logo_url ) return '';

    $width = get_option( 'jobs_logo_width', '150' );

    ob_start();
    ?>
    <div class="jobedia-top-logo">
        <a href="<?php echo home_url(); ?>">
            <img src="<?php echo esc_url( $logo_url ); ?>" alt="Logo" style="max-width: <?php echo esc_attr($width); ?>px; height: auto;">
        </a>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Automatically inject logo at top-left on secondary pages if needed.
 */
function jobs_inject_secondary_logo() {
    if ( is_front_page() || is_admin() || is_page('job-search') ) return;
    echo jobs_logo_shortcode();
}
// add_action( 'wp_body_open', 'jobs_inject_secondary_logo' ); // Optional: uncomment if direct injection is preferred

/**
 * Shortcode: [notifications_icon]
 * Standalone notifications icon and dropdown.
 */
function jobs_notifications_icon_shortcode() {
    if ( ! is_user_logged_in() ) return '';

    $current_user_id = get_current_user_id();
    global $wpdb;
    $table_notifications = Jobs_DB_Service::get_table( 'notifications' );
    $unread_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_notifications WHERE user_id = %d AND is_read = 0", $current_user_id ) );

    ob_start();
    ?>
    <div class="jobs-standalone-icon-wrap notifications-trigger-wrap">
        <div class="top-bar-icon-item" id="jobs-notif-toggle" title="Notifications">
            <span class="dashicons dashicons-bell"></span>
            <?php if ($unread_count > 0) : ?>
                <span class="notif-badge"><?php echo $unread_count; ?></span>
            <?php endif; ?>

            <div class="jobs-notif-dropdown" id="jobs-notif-menu">
                <div class="dropdown-header">
                    <strong>Notifications</strong>
                </div>
                <div id="jobs-notif-list" class="notif-list">
                    <p style="padding:20px; text-align:center; color:#999;">Loading...</p>
                </div>
                <div id="jobs-notif-detail" style="display:none; padding: 20px; border-top: 1px solid #f1f5f9;">
                    <button id="notif-back" class="v4-btn-secondary" style="height: 30px; padding: 0 12px; margin-bottom: 20px; font-size: 11px; width: auto;"><span class="dashicons dashicons-arrow-left-alt2" style="font-size: 14px; width: 14px; height: 14px; vertical-align: middle;"></span> Back</button>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <div style="width: 45px; height: 45px; border-radius: 50%; overflow: hidden; border: 1px solid #eff6ff; background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                            <img src="" id="notif-detail-avatar" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            <span class="dashicons dashicons-bell" id="notif-detail-icon-placeholder" style="color: #1d3469; font-size: 20px;"></span>
                        </div>
                        <div>
                            <div id="notif-detail-sender" style="font-weight: 700; color: #1d3469; font-size: 14px;"></div>
                            <div id="notif-detail-time" style="font-size: 11px; color: #94a3b8;"></div>
                        </div>
                    </div>
                    <div id="notif-detail-content" style="font-size: 13px; color: #475569; line-height: 1.7; background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0;"></div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function jobs_account_icon_shortcode() {
    $current_user = wp_get_current_user();
    $is_logged_in = is_user_logged_in();

    ob_start();
    ?>
    <div class="jobs-standalone-icon-wrap account-access-wrap">
        <?php if ( $is_logged_in ) : ?>
            <div style="display: flex; align-items: center; gap: 15px;">
                <!-- User Avatar & Dropdown -->
                <div class="top-bar-user-item">
                    <img src="<?php echo get_avatar_url( $current_user->ID ); ?>" class="user-avatar-small" id="jobs-profile-toggle">
                    <div class="jobs-profile-dropdown" id="jobs-profile-menu">
                        <div class="dropdown-header">
                            <strong><?php echo esc_html( $current_user->display_name ); ?></strong>
                            <?php
                            $prof = get_user_meta($current_user->ID, '_profession', true) ?: 'Professional';
                            ?>
                            <div style="margin-top: 5px;">
                                <span style="background: #e0f2fe; color: #0369a1; padding: 2px 10px; border-radius: 50px; font-size: 11px; font-weight: 600; display: inline-block;"><?php echo esc_html($prof); ?></span>
                            </div>
                        </div>
                        <ul>
                            <li><a href="<?php echo jobs_get_profile_link($current_user->ID); ?>"><span class="dashicons dashicons-admin-users"></span> View Profile</a></li>
                            <li><a href="<?php echo home_url('/account-setup/'); ?>"><span class="dashicons dashicons-edit"></span> Data Editing</a></li>
                            <li><a href="#" class="jobs-module-link" data-module="favorites" data-type="modal"><span class="dashicons dashicons-heart"></span> Favorites</a></li>
                            <li><a href="#" class="jobs-module-link" data-module="settings" data-type="modal"><span class="dashicons dashicons-admin-generic"></span> Account Settings</a></li>
                            <li><a href="<?php echo home_url('/support'); ?>"><span class="dashicons dashicons-editor-help"></span> Support</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo wp_logout_url(); ?>"><span class="dashicons dashicons-exit"></span> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <a href="<?php echo get_permalink( get_page_by_path('login') ); ?>" class="jobs-btn-small">Login</a>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Keep the modules grid logic but remove the old top bar rendering.
 */
function jobs_render_modules_grid() {
    if ( ! is_user_logged_in() ) return;
    $user_id = get_current_user_id();

    $modules = array(
        'job-posting' => array(
            'label' => 'Post a Job',
            'icon' => 'plus',
            'bg' => '#e3f2fd',
            'color' => '#1976d2',
            'check' => 'can_post_job',
            'type' => 'modal'
        ),
        'job-seekers' => array(
            'label' => 'Job Seekers',
            'icon' => 'groups',
            'bg' => '#f1f8e9',
            'color' => '#33691e',
            'check' => 'is_user_logged_in',
            'type' => 'page',
            'url' => home_url('/job-seekers/')
        ),
        'job-requests' => array(
            'label' => 'Job Requests',
            'icon' => 'portfolio',
            'bg' => '#fff3e0',
            'color' => '#f57c00',
            'check' => 'is_user_logged_in',
            'type' => 'modal'
        ),
        'public-profile-view' => array(
            'label' => 'Public Profile View',
            'icon' => 'visibility',
            'bg' => '#f3e5f5',
            'color' => '#7b1fa2',
            'check' => 'is_user_logged_in',
            'type' => 'direct',
            'url' => jobs_get_profile_link( get_current_user_id() )
        ),
        'applications-submitted' => array(
            'label' => 'Submitted',
            'icon' => 'paper-plane',
            'bg' => '#f3e5f5',
            'color' => '#7b1fa2',
            'check' => 'can_apply_job',
            'type' => 'page',
            'url' => home_url('/applications-submitted/')
        ),
        'company-profile' => array(
            'label' => 'Company',
            'icon' => 'building',
            'bg' => '#efebe9',
            'color' => '#5d4037',
            'check' => 'can_post_job',
            'type' => 'page',
            'url' => home_url('/company-profile/')
        ),
        'drafts' => array(
            'label' => 'Drafts',
            'icon' => 'edit',
            'bg' => '#eceff1',
            'color' => '#455a64',
            'check' => 'is_user_logged_in',
            'type' => 'modal'
        ),
        'analytics-insights' => array(
            'label' => 'Insights',
            'icon' => 'chart-area',
            'bg' => '#e1f5fe',
            'color' => '#0288d1',
            'check' => 'can_post_job',
            'type' => 'page',
            'url' => home_url('/analytics-insights/')
        ),
        'articles' => array(
            'label' => 'Articles',
            'icon' => 'welcome-widgets-menus',
            'bg' => '#fafafa',
            'color' => '#9e9e9e',
            'check' => 'is_user_logged_in',
            'type' => 'direct',
            'url' => home_url('/Articles')
        ),
        'wp-admin' => array(
            'label' => 'Dashboard',
            'icon' => 'dashboard',
            'bg' => '#f5f5f5',
            'color' => '#333',
            'check' => 'is_user_logged_in',
            'type' => 'direct',
            'url' => admin_url()
        ),
        'advanced-settings' => array(
            'label' => 'Site Settings',
            'icon' => 'admin-tools',
            'bg' => '#e8eaf6',
            'color' => '#303f9f',
            'check' => 'is_system_admin',
            'type' => 'direct',
            'url' => home_url('/site-settings/')
        ),
        'policies' => array(
            'label' => 'Policies',
            'icon' => 'media-spreadsheet',
            'bg' => '#f5f5f5',
            'color' => '#616161',
            'check' => 'is_user_logged_in',
            'type' => 'direct',
            'url' => home_url('/policies/')
        ),
    );

    $is_system_admin = Jobs_Permission_Service::is_system_admin($user_id);
    $visible_modules = get_option( 'jobs_visible_modules', array_keys( $modules ) );
    $restricted_modules = get_user_meta( $user_id, 'jobs_restricted_modules', true ) ?: array();

    foreach ( $modules as $slug => $data ) {
        if ( !$is_system_admin ) {
            if ( ! in_array( $slug, $visible_modules ) && $slug !== 'advanced-settings' ) continue;
            if ( in_array( $slug, $restricted_modules ) ) continue;
        }

        $allowed = false;
        $check = $data['check'];
        if ( $is_system_admin ) {
            $allowed = true;
        } elseif ( $check === 'is_user_logged_in' ) {
            $allowed = is_user_logged_in();
        } elseif ( method_exists( 'Jobs_Permission_Service', $check ) ) {
            $allowed = Jobs_Permission_Service::$check( $user_id );
        }

        if ( $allowed ) {
            $url = $data['url'] ?? '#';
            ?>
            <div class="apps-grid-item">
                <a href="<?php echo esc_url($url); ?>" class="jobs-module-link" data-module="<?php echo $slug; ?>" data-type="<?php echo $data['type']; ?>">
                    <div class="apps-icon-wrapper" style="background-color: <?php echo $data['bg']; ?>; color: <?php echo $data['color']; ?>;">
                        <span class="dashicons dashicons-<?php echo $data['icon']; ?>"></span>
                    </div>
                    <span class="apps-label"><?php echo $data['label']; ?></span>
                </a>
            </div>
            <?php
        }
    }
}
