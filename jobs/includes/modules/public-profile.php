<?php
/**
 * Module: Public Profile (Shareable link & Preview)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();
$profile_link = jobs_get_profile_link( $current_user_id );
?>
<div class="jobs-module-content" id="jobs-public-profile-module">
    <h3>Your Public Profile</h3>
    <p>Your profile is fully shareable and professional. Anyone with the link below can view your professional details.</p>

    <div class="jobs-share-link-box" style="margin: 20px 0; padding: 20px; border: 2px dashed var(--jobs-primary-color); border-radius: 12px; text-align:center;">
        <strong>Shareable Link:</strong><br>
        <a href="<?php echo esc_url( $profile_link ); ?>" target="_blank" style="font-size: 1.2em; font-weight: 600;"><?php echo esc_html( $profile_link ); ?></a>
    </div>

    <div class="profile-preview-hint">
        <p>Tip: All updates made in the <strong>General Account Data Update</strong> or <strong>Company Profile</strong> modules are immediately reflected here.</p>
        <a href="<?php echo esc_url( $profile_link ); ?>" target="_blank" class="jobs-btn">View My Profile Now</a>
    </div>
</div>
