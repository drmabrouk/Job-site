<?php
/**
 * Public Profile Display Template
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$username = get_query_var( 'profile_user' );
if ( ! $username ) {
    echo '<p>User not found.</p>';
    return;
}

// Try looking up by slug (user_nicename), then fallback to login
$user = get_user_by( 'slug', $username );
if ( ! $user ) {
    $user = get_user_by( 'login', $username );
}

if ( ! $user ) {
    echo '<p style="padding: 50px; text-align: center; color: #64748b;">User not found. The profile may have been removed or the link is incorrect.</p>';
    return;
}

$user_id = $user->ID;
$role = $user->roles[0];
$cv_data = get_user_meta( $user_id, 'jobs_cv_data', true ) ?: array();
$company_data = get_user_meta( $user_id, 'jobs_company_data', true ) ?: array();
$visibility = get_user_meta( $user_id, 'profile_visibility', true ) ?: 'public';

if ( $visibility === 'private' && get_current_user_id() !== $user_id ) {
    echo '<p>This profile is private.</p>';
    return;
}

$format_pdf = isset( $_GET['format'] ) && $_GET['format'] === 'pdf';
?>

<?php if ( $format_pdf ) : ?>
<style>
    body * { visibility: hidden; }
    #jobs-pdf-content, #jobs-pdf-content * { visibility: visible; }
    #jobs-pdf-content { position: absolute; left: 0; top: 0; width: 100%; }
    .jobs-btn, .jobs-share-link-box { display: none !important; }
</style>
<script>window.onload = function() { window.print(); }</script>
<?php endif; ?>

<div class="jobs-public-profile-container jobs-transparent-bg" id="jobs-pdf-content">
    <header class="profile-header">
        <div class="profile-avatar">
            <?php if ( $role === 'employer' && ! empty( $company_data['logo'] ) ) : ?>
                <img src="<?php echo esc_url( $company_data['logo'] ); ?>" alt="Company Logo" class="company-logo-large">
            <?php else : ?>
                <?php echo get_avatar( $user_id, 150 ); ?>
            <?php endif; ?>
        </div>
        <div class="profile-basic-info">
            <h1><?php echo esc_html( $user->display_name ); ?></h1>
            <div style="display:flex; align-items: center; gap: 10px; margin-top: 5px;">
                <p class="role-badge" style="margin:0;"><?php echo ucfirst( str_replace('_', ' ', $role) ); ?></p>
                <?php if ($role === 'job_seeker' && $spec = get_user_meta($user_id, '_specialization', true)) : ?>
                    <span class="spec-badge" style="background: rgba(29, 52, 105, 0.05); padding: 2px 10px; border-radius: 20px; font-size: 0.8em; color: var(--jobs-primary-color);"><?php echo esc_html($spec); ?></span>
                <?php endif; ?>
            </div>
            <?php if ( $role === 'employer' ) : ?>
                <p class="company-tagline"><?php echo esc_html( $company_data['name'] ?? '' ); ?></p>
            <?php endif; ?>

            <?php if ( $user->description ) : ?>
                <p class="profile-bio" style="margin-top: 15px; color: #64748b; font-size: 0.95em; line-height: 1.6; max-width: 600px;"><?php echo nl2br(esc_html($user->description)); ?></p>
            <?php endif; ?>

            <?php if ( get_current_user_id() && get_current_user_id() !== $user_id ) : ?>
                <div style="margin-top: 20px;">
                    <button class="jobs-btn-small contact-user-btn" data-user-id="<?php echo $user_id; ?>" data-name="<?php echo esc_attr($user->display_name); ?>">
                        <span class="dashicons dashicons-email" style="font-size:16px; width:16px; height:16px; vertical-align:middle; margin-right:5px;"></span>
                        Send Message
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="profile-content">
        <?php
        require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-ads-service.php';
        Jobs_Ads_Service::display_ad( 'user_profile' );
        ?>

        <?php if ( $role === 'job_seeker' ) : ?>
            <section class="personal-highlights" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 40px; background: #f8fafc; padding: 30px; border-radius: 20px; border: 1px solid #e2e8f0;">
                <div class="highlight-item" style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #e0f2f1; color: #00796b; display: flex; align-items: center; justify-content: center;"><span class="dashicons dashicons-admin-site"></span></div>
                    <div>
                        <small style="display:block; color: #64748b; text-transform: uppercase; font-size: 0.65em; font-weight: 700; letter-spacing: 0.05em;">Nationality</small>
                        <strong style="color: #1e293b; font-size: 0.95em;"><?php echo esc_html(get_user_meta($user_id, '_nationality', true) ?: 'N/A'); ?></strong>
                    </div>
                </div>
                <div class="highlight-item" style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #fff3e0; color: #f57c00; display: flex; align-items: center; justify-content: center;"><span class="dashicons dashicons-portfolio"></span></div>
                    <div>
                        <small style="display:block; color: #64748b; text-transform: uppercase; font-size: 0.65em; font-weight: 700; letter-spacing: 0.05em;">Experience</small>
                        <strong style="color: #1e293b; font-size: 0.95em;"><?php echo esc_html(get_user_meta($user_id, '_experience', true) ?: '0'); ?> Years</strong>
                    </div>
                </div>
                <div class="highlight-item" style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #e3f2fd; color: #1976d2; display: flex; align-items: center; justify-content: center;"><span class="dashicons dashicons-translation"></span></div>
                    <div>
                        <small style="display:block; color: #64748b; text-transform: uppercase; font-size: 0.65em; font-weight: 700; letter-spacing: 0.05em;">English</small>
                        <strong style="color: #1e293b; font-size: 0.95em;"><?php echo ucfirst(esc_html(get_user_meta($user_id, '_english_level', true) ?: 'N/A')); ?></strong>
                    </div>
                </div>
                <div class="highlight-item" style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #f3e5f5; color: #7b1fa2; display: flex; align-items: center; justify-content: center;"><span class="dashicons dashicons-welcome-learn-more"></span></div>
                    <div>
                        <small style="display:block; color: #64748b; text-transform: uppercase; font-size: 0.65em; font-weight: 700; letter-spacing: 0.05em;">Education</small>
                        <strong style="color: #1e293b; font-size: 0.95em;"><?php echo ucfirst(esc_html(get_user_meta($user_id, '_qualification', true) ?: 'N/A')); ?></strong>
                    </div>
                </div>
            </section>

            <section class="cv-section">
                <h2>Education</h2>
                <div class="cv-item"><?php echo nl2br( esc_html( $cv_data['education'] ?? 'No education details provided.' ) ); ?></div>
            </section>

            <section class="cv-section">
                <h2>Work Experience</h2>
                <div class="cv-item"><?php echo nl2br( esc_html( $cv_data['experience'] ?? 'No experience details provided.' ) ); ?></div>
            </section>

            <section class="cv-section">
                <h2>Skills</h2>
                <div class="cv-item"><?php echo esc_html( $cv_data['skills'] ?? 'No skills listed.' ); ?></div>
            </section>

            <section class="cv-section">
                <h2>Certifications & Courses</h2>
                <div class="cv-item"><?php echo nl2br( esc_html( $cv_data['certifications'] ?? 'No certifications listed.' ) ); ?></div>
            </section>

        <?php elseif ( $role === 'employer' ) : ?>
            <section class="company-details">
                <h2>About Company</h2>
                <p><?php echo nl2br( esc_html( $company_data['details'] ?? 'No company details available.' ) ); ?></p>
            </section>

            <section class="company-info-grid">
                <div class="info-item">
                    <strong>Address:</strong>
                    <span><?php echo esc_html( $company_data['address'] ?? 'N/A' ); ?></span>
                </div>
                <div class="info-item">
                    <strong>Employees:</strong>
                    <span><?php echo esc_html( $company_data['employee_count'] ?? 'N/A' ); ?></span>
                </div>
            </section>
        <?php endif; ?>
    </div>
</div>
