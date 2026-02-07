<?php
/**
 * Admin Settings & User Management in WP Dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add settings page to WP Admin
 */
function jobs_add_admin_menu() {
    add_menu_page(
        'Jobs Plugin Settings',
        'Jobs Settings',
        'manage_options',
        'jobs-settings',
        'jobs_render_wp_admin_settings',
        'dashicons-businessman'
    );
}
add_action( 'admin_menu', 'jobs_add_admin_menu' );

function jobs_register_settings() {
    register_setting( 'jobs_settings_group', 'jobs_site_logo' );
    register_setting( 'jobs_settings_group', 'jobs_logo_width' );
    register_setting( 'jobs_settings_group', 'jobs_logo_height' );
    register_setting( 'jobs_settings_group', 'jobs_search_placeholder' );
    register_setting( 'jobs_settings_group', 'jobs_archive_days' );
    register_setting( 'jobs_settings_group', 'jobs_visible_modules' );
    register_setting( 'jobs_settings_group', 'jobs_adsense_code' );

    add_settings_section( 'jobs_general_section', 'General Settings', null, 'jobs-settings' );

    add_settings_field( 'jobs_site_logo', 'Site Logo URL', 'jobs_logo_callback', 'jobs-settings', 'jobs_general_section' );
    add_settings_field( 'jobs_logo_width', 'Logo Width (px)', 'jobs_width_callback', 'jobs-settings', 'jobs_general_section' );
    add_settings_field( 'jobs_search_placeholder', 'Search Placeholder', 'jobs_placeholder_callback', 'jobs-settings', 'jobs_general_section' );
    add_settings_field( 'jobs_visible_modules', 'Globally Visible Modules', 'jobs_modules_callback', 'jobs-settings', 'jobs_general_section' );
}
add_action( 'admin_init', 'jobs_register_settings' );

function jobs_logo_callback() {
    $val = get_option( 'jobs_site_logo' );
    echo '<input type="text" name="jobs_site_logo" value="' . esc_attr( $val ) . '" class="regular-text">';
}
function jobs_width_callback() {
    $val = get_option( 'jobs_logo_width', '300' );
    echo '<input type="number" name="jobs_logo_width" value="' . esc_attr( $val ) . '">';
}
function jobs_placeholder_callback() {
    $val = get_option( 'jobs_search_placeholder' );
    echo '<input type="text" name="jobs_search_placeholder" value="' . esc_attr( $val ) . '" class="regular-text">';
}

function jobs_modules_callback() {
    $all_modules = array(
        'job-posting' => 'Post a Job',
        'job-requests' => 'Job Requests',
        'public-profile' => 'Public Profile',
        'applications-submitted' => 'Applications Submitted',
        'cv-resume' => 'CV / Resume',
        'company-profile' => 'Company Profile',
        'favorites' => 'Favorites',
        'drafts' => 'Drafts',
        'support' => 'Support',
        'settings' => 'Settings',
        'advanced-settings' => 'Advanced Settings',
        'terms-conditions' => 'Terms & Conditions',
        'articles' => 'Articles',
        'notifications' => 'Notifications',
        'analytics-insights' => 'Analytics / Insights',
    );
    $val = get_option( 'jobs_visible_modules', array_keys( $all_modules ) );
    foreach ( $all_modules as $slug => $label ) {
        echo '<label style="display:block;"><input type="checkbox" name="jobs_visible_modules[]" value="' . $slug . '" ' . checked( in_array( $slug, $val ), true, false ) . '> ' . $label . '</label>';
    }
}

function jobs_render_wp_admin_settings() {
    ?>
    <div class="wrap">
        <h1>Jobs Plugin Global Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'jobs_settings_group' );
            do_settings_sections( 'jobs-settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Add module restriction fields to WP User Edit page
 */
function jobs_user_profile_fields( $user ) {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $modules = array(
        'job-posting' => 'Post a Job',
        'job-requests' => 'Job Requests',
        'public-profile' => 'Public Profile',
        'applications-submitted' => 'Applications Submitted',
        'cv-resume' => 'CV / Resume',
        'company-profile' => 'Company Profile',
        'favorites' => 'Favorites',
        'drafts' => 'Drafts',
        'support' => 'Support',
        'settings' => 'Settings',
        'advanced-settings' => 'Advanced Settings',
        'terms-conditions' => 'Terms & Conditions',
        'articles' => 'Articles',
        'notifications' => 'Notifications',
        'analytics-insights' => 'Analytics / Insights',
    );

    $restricted = get_user_meta( $user->ID, 'jobs_restricted_modules', true ) ?: array();
    ?>
    <h3>Jobs Plugin - Application Restrictions</h3>
    <table class="form-table">
        <tr>
            <th><label>Restrict Applications</label></th>
            <td>
                <?php foreach ( $modules as $slug => $label ) : ?>
                    <label style="display:block; margin-bottom: 5px;">
                        <input type="checkbox" name="jobs_restricted_modules[]" value="<?php echo $slug; ?>" <?php checked( in_array( $slug, $restricted ) ); ?>>
                        <?php echo $label; ?>
                    </label>
                <?php endforeach; ?>
                <p class="description">Check the applications you want to HIDE for this user.</p>
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'jobs_user_profile_fields' );
add_action( 'edit_user_profile', 'jobs_user_profile_fields' );

function jobs_save_user_profile_fields( $user_id ) {
    if ( ! current_user_can( 'manage_options' ) ) return false;

    $restricted = isset( $_POST['jobs_restricted_modules'] ) ? array_map( 'sanitize_text_field', $_POST['jobs_restricted_modules'] ) : array();
    update_user_meta( $user_id, 'jobs_restricted_modules', $restricted );
}
add_action( 'personal_options_update', 'jobs_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'jobs_save_user_profile_fields' );
