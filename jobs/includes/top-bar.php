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
    $is_homepage = is_front_page() || (isset($GLOBALS['is_job_homepage']) && $GLOBALS['is_job_homepage']);

    ob_start();
    ?>
    <div class="jobs-header-system <?php echo $is_homepage ? 'jobs-header-minimal' : 'jobs-header-glass'; ?>">
        <div class="top-bar-content">
            <?php if ( ! $is_homepage ) : ?>
            <div class="top-bar-left">
                <a href="<?php echo home_url(); ?>" class="top-bar-home">
                    <span class="dashicons dashicons-admin-site"></span>
                    <span class="site-name"><?php bloginfo('name'); ?></span>
                </a>
            </div>
            <?php endif; ?>

            <div class="top-bar-right <?php echo $is_homepage ? 'floating-right' : ''; ?>">
                <?php if ( $is_logged_in ) : ?>
                    <div class="top-bar-icon-item" id="jobs-apps-toggle" title="Applications">
                        <span class="dashicons dashicons-grid-view"></span>
                    </div>

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
                    <a href="<?php echo get_permalink( get_page_by_path('login-registration') ); ?>" class="jobs-btn-small">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Grid-based Applications Menu -->
        <div class="jobs-apps-overlay-container" id="jobs-apps-menu">
            <div class="apps-grid-card">
                <div class="apps-grid-header">
                    <h3>Applications</h3>
                    <span class="apps-grid-close" id="jobs-apps-close">&times;</span>
                </div>
                <div class="apps-grid-content">
                    <?php jobs_render_modules_grid(); ?>
                </div>
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

function jobs_render_modules_grid() {
    if ( ! is_user_logged_in() ) return;
    $user_id = get_current_user_id();

    $modules = array(
        'job-requests' => array(
            'label' => 'Job Requests',
            'icon' => 'portfolio',
            'bg' => '#fff3e0',
            'color' => '#f57c00',
            'check' => 'is_user_logged_in', // View differently for seekers handled inside
            'type' => 'page'
        ),
        'public-profile' => array(
            'label' => 'Public Profile',
            'icon' => 'admin-users',
            'bg' => '#e0f2f1',
            'color' => '#00796b',
            'check' => 'is_user_logged_in',
            'type' => 'page'
        ),
        'applications-submitted' => array(
            'label' => 'Submitted',
            'icon' => 'paper-plane',
            'bg' => '#f3e5f5',
            'color' => '#7b1fa2',
            'check' => 'can_apply_job',
            'type' => 'page'
        ),
        'cv-resume' => array(
            'label' => 'CV / Resume',
            'icon' => 'media-text',
            'bg' => '#ffebee',
            'color' => '#d32f2f',
            'check' => 'can_apply_job',
            'type' => 'modal'
        ),
        'company-profile' => array(
            'label' => 'Company',
            'icon' => 'building',
            'bg' => '#efebe9',
            'color' => '#5d4037',
            'check' => 'can_post_job',
            'type' => 'page'
        ),
        'favorites' => array(
            'label' => 'Favorites',
            'icon' => 'heart',
            'bg' => '#fce4ec',
            'color' => '#c2185b',
            'check' => 'is_user_logged_in',
            'type' => 'modal'
        ),
        'drafts' => array(
            'label' => 'Drafts',
            'icon' => 'edit',
            'bg' => '#eceff1',
            'color' => '#455a64',
            'check' => 'is_user_logged_in',
            'type' => 'modal'
        ),
        'support' => array(
            'label' => 'Support',
            'icon' => 'headset',
            'bg' => '#e1f5fe',
            'color' => '#0288d1',
            'check' => 'is_user_logged_in',
            'type' => 'modal'
        ),
        'settings' => array(
            'label' => 'Settings',
            'icon' => 'admin-generic',
            'bg' => '#f3e5f5',
            'color' => '#7b1fa2',
            'check' => 'is_user_logged_in',
            'type' => 'modal'
        ),
        'advanced-settings' => array(
            'label' => 'Advanced',
            'icon' => 'shield',
            'bg' => '#e8eaf6',
            'color' => '#303f9f',
            'check' => 'is_admin',
            'type' => 'page'
        ),
        'terms-conditions' => array(
            'label' => 'Terms',
            'icon' => 'media-spreadsheet',
            'bg' => '#f5f5f5',
            'color' => '#616161',
            'check' => 'is_user_logged_in',
            'type' => 'page'
        ),
        'articles' => array(
            'label' => 'Articles',
            'icon' => 'welcome-widgets-menus',
            'bg' => '#fafafa',
            'color' => '#9e9e9e',
            'check' => 'is_user_logged_in',
            'type' => 'page'
        ),
        'notifications' => array(
            'label' => 'Alerts',
            'icon' => 'bell',
            'bg' => '#fff9c4',
            'color' => '#fbc02d',
            'check' => 'is_user_logged_in',
            'type' => 'modal'
        ),
        'analytics-insights' => array(
            'label' => 'Insights',
            'icon' => 'chart-area',
            'bg' => '#e1f5fe',
            'color' => '#0288d1',
            'check' => 'can_post_job',
            'type' => 'page'
        ),
    );

    $visible_modules = get_option( 'jobs_visible_modules', array_keys( $modules ) );

    foreach ( $modules as $slug => $data ) {
        if ( ! in_array( $slug, $visible_modules ) && $slug !== 'advanced-settings' ) continue;

        $allowed = false;
        $check = $data['check'];
        if ( $check === 'is_user_logged_in' ) {
            $allowed = is_user_logged_in();
        } elseif ( method_exists( 'Jobs_Permission_Service', $check ) ) {
            $allowed = Jobs_Permission_Service::$check( $user_id );
        }

        if ( $allowed ) {
            ?>
            <div class="apps-grid-item">
                <a href="#" class="jobs-module-link" data-module="<?php echo $slug; ?>" data-type="<?php echo $data['type']; ?>">
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
