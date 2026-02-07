<?php
/**
 * Template: Seeker Card
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$seeker_id = $seeker->ID;
$display_name = $seeker->display_name;
$avatar = get_avatar_url( $seeker_id );
$specialization = get_user_meta( $seeker_id, '_specialization', true );
$qualification = get_user_meta( $seeker_id, '_qualification', true );
$experience = get_user_meta( $seeker_id, '_experience', true );
$nationality = get_user_meta( $seeker_id, '_nationality', true );

$can_send_offer = Jobs_Permission_Service::can_post_job();
?>
<div class="seeker-card" style="background: white; border-radius: 16px; padding: 25px; border: 1px solid rgba(29, 52, 105, 0.08); transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.02);">
    <div class="seeker-card-header" style="text-align: center; margin-bottom: 20px;">
        <img src="<?php echo esc_url($avatar); ?>" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid #f0f4f8;">
        <h3 style="margin: 0; font-size: 1.2em; color: var(--jobs-primary-color);"><?php echo esc_html($display_name); ?></h3>
        <span class="seeker-tag" style="font-size: 0.8em; color: #666;"><?php echo esc_html(ucfirst($specialization ?: 'General Seeker')); ?></span>
    </div>

    <div class="seeker-info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.85em; margin-bottom: 20px;">
        <div class="info-item">
            <strong>Exp:</strong> <?php echo esc_html($experience ?: '0'); ?> yrs
        </div>
        <div class="info-item">
            <strong>Qual:</strong> <?php echo esc_html(ucfirst($qualification ?: 'N/A')); ?>
        </div>
        <div class="info-item" style="grid-column: span 2;">
            <strong>Nationality:</strong> <?php echo esc_html($nationality ?: 'N/A'); ?>
        </div>
    </div>

    <div class="seeker-card-actions" style="display: flex; gap: 10px;">
        <a href="<?php echo jobs_get_profile_link($seeker_id); ?>" class="jobs-btn-small" style="flex: 1; text-align: center; font-size: 0.8em;">View Profile</a>
        <?php if ($can_send_offer) : ?>
            <button class="jobs-btn-small send-offer-btn" data-seeker-id="<?php echo $seeker_id; ?>" data-seeker-name="<?php echo esc_attr($display_name); ?>" style="flex: 1; background: #28a745; border-color: #28a745; font-size: 0.8em;">Send Offer</button>
        <?php endif; ?>
    </div>
</div>
