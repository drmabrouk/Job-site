<?php
/**
 * Template: Seeker Card (Premium Redesign)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$seeker_id = $seeker->ID;
$display_name = $seeker->display_name;
$avatar = get_avatar_url( $seeker_id, array('size' => 120) );
$specialization = get_user_meta( $seeker_id, '_specialization', true );
$profession = get_user_meta( $seeker_id, '_profession', true );
$experience = get_user_meta( $seeker_id, '_experience', true );
$nationality = get_user_meta( $seeker_id, '_nationality', true );
$region = get_user_meta( $seeker_id, '_region', true );

$can_send_offer = Jobs_Permission_Service::can_post_job();
?>
<div class="seeker-card-premium" style="background: white; border-radius: 24px; padding: 30px; border: 1px solid #e2e8f0; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; display: flex; flex-direction: column; height: 100%; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
    <div class="seeker-card-top" style="display: flex; gap: 20px; align-items: center; margin-bottom: 25px;">
        <div class="avatar-container" style="position: relative; flex-shrink: 0;">
            <img src="<?php echo esc_url($avatar); ?>" style="width: 70px; height: 70px; border-radius: 20px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 8px 16px rgba(0,0,0,0.08);">
            <div style="position: absolute; bottom: -5px; right: -5px; width: 18px; height: 18px; background: #10b981; border: 3px solid #fff; border-radius: 50%;" title="Verified"></div>
        </div>
        <div class="identity-info">
            <h3 style="margin: 0; font-size: 1.15em; color: #1d3469; font-weight: 700; line-height: 1.2;"><?php echo esc_html($display_name); ?></h3>
            <p style="margin: 4px 0 0; font-size: 0.85em; color: #64748b; font-weight: 500;"><?php echo esc_html($profession ?: 'Professional'); ?></p>
        </div>
    </div>

    <div class="seeker-tags" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 25px;">
        <span style="background: #eff6ff; color: #1d3469; padding: 4px 12px; border-radius: 8px; font-size: 0.75em; font-weight: 600;"><?php echo esc_html($specialization ?: 'General'); ?></span>
        <span style="background: #f8fafc; color: #64748b; padding: 4px 12px; border-radius: 8px; font-size: 0.75em; font-weight: 600; border: 1px solid #e2e8f0;"><?php echo esc_html($experience ?: '0'); ?>+ Years Exp</span>
    </div>

    <div class="seeker-details-list" style="margin-bottom: auto;">
        <div style="display: flex; align-items: center; gap: 10px; font-size: 0.85em; color: #475569; margin-bottom: 12px;">
            <span class="dashicons dashicons-location" style="font-size: 18px; color: #94a3b8;"></span>
            <span>
                <?php
                if($nationality && $f = Jobs_Data_Service::get_flag_url($nationality)): ?>
                    <img src="<?php echo $f; ?>" style="width: 14px; height: 10px; margin-right: 5px; vertical-align: middle; border-radius: 1px;">
                <?php endif; ?>
                <?php echo esc_html($region ? $region . ', ' . ucwords(str_replace('-', ' ', $nationality)) : ($nationality ? ucwords(str_replace('-', ' ', $nationality)) : 'Global')); ?>
            </span>
        </div>
        <div style="display: flex; align-items: center; gap: 10px; font-size: 0.85em; color: #475569;">
            <span class="dashicons dashicons-welcome-learn-more" style="font-size: 18px; color: #94a3b8;"></span>
            <span>Verified Portfolio Available</span>
        </div>
    </div>

    <div class="seeker-card-footer" style="margin-top: 30px; display: flex; gap: 12px;">
        <a href="<?php echo jobs_get_profile_link($seeker_id); ?>" class="jobs-btn-minimal" style="flex: 1; text-align: center; padding: 10px; font-size: 0.85em; font-weight: 600; border-radius: 12px; border: 1px solid #e2e8f0; text-decoration: none; color: #475569; transition: all 0.2s;">View Portfolio</a>
        <?php if ($can_send_offer) : ?>
            <button class="jobs-btn-small send-offer-btn-premium" data-seeker-id="<?php echo $seeker_id; ?>" data-seeker-name="<?php echo esc_attr($display_name); ?>" style="flex: 1.2; background: #1d3469; color: white; border: none; padding: 10px; font-size: 0.85em; font-weight: 700; border-radius: 12px; cursor: pointer; transition: transform 0.2s;">Send Job Offer</button>
        <?php endif; ?>
    </div>
</div>

<style>
.seeker-card-premium:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border-color: #cbd5e1;
}
.jobs-btn-minimal:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}
.send-offer-btn-premium:hover {
    background: #2a4a8c;
    transform: scale(1.02);
}
</style>
