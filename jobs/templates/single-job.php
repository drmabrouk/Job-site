<?php
/**
 * Template: SaaS Enterprise Professional Job Details (V7)
 * Strictly reusing the visual structure and design philosophy of the public profile page.
 * Optimized for centering, SEO, and AdSense.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
    $post_id = get_the_ID();
    $company_name = get_post_meta( $post_id, '_company_name', true );

    // Taxonomies
    $countries = get_the_terms( $post_id, 'country' );
    $cities = get_the_terms( $post_id, 'city' );
    $specializations = get_the_terms( $post_id, 'specialization' );

    // Meta Data
    $salary = get_post_meta($post_id, '_job_salary', true);
    $currency_code = get_post_meta($post_id, '_job_currency', true) ?: 'USD';
    $currencies = Jobs_Data_Service::get_currencies();
    $currency_sym = $currencies[$currency_code] ?? '$';

    $qualifications = get_post_meta($post_id, '_job_qualifications', true);
    $responsibilities = get_post_meta($post_id, '_job_responsibilities', true);
    $deadline = get_post_meta($post_id, '_job_deadline', true);
    $benefits = get_post_meta($post_id, '_job_benefits', true);
    $work_setting = get_post_meta($post_id, '_job_work_setting', true);
    $exp_level = get_post_meta($post_id, '_job_experience_level', true);
    $emp_type = get_post_meta($post_id, '_job_employment_type', true);
    $skills = get_post_meta($post_id, '_job_skills', true);

    $author_id = get_post_field( 'post_author', $post_id );
    $company_profile_url = jobs_get_profile_link( $author_id );

    $share_url = urlencode(get_permalink());
    $share_title = urlencode(get_the_title());
?>

<div class="jobs-premium-profile-v4 job-details-v7-optimized">
    <div class="profile-layout-container">

        <!-- HEADER: CENTERED AND BALANCED -->
        <header class="profile-v4-header no-avatar">
            <div class="profile-v4-identity-box">
                <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <h1 style="display: inline-flex; align-items: center; gap: 10px; margin: 0;"><?php the_title(); ?></h1>
                </div>
                <div style="margin: 10px 0; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <span class="v4-pastel-pill pill-blue" style="height: 24px; font-size: 11px; width: auto;"><?php echo esc_html($emp_type ?: 'Full Time'); ?></span>
                    <?php if(!empty($specializations)): ?>
                        <span class="v4-pastel-pill pill-purple" style="height: 24px; font-size: 11px; width: auto;"><?php echo esc_html($specializations[0]->name); ?></span>
                    <?php endif; ?>
                </div>
                <p class="profile-v4-headline">at <a href="<?php echo esc_url($company_profile_url); ?>" style="color: inherit; text-decoration: underline;"><?php echo esc_html($company_name); ?></a> • Posted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> ago</p>

                <div class="profile-v4-location-info">
                    <?php
                    $country_name = $countries ? $countries[0]->name : '';
                    $country_slug = $countries ? $countries[0]->slug : '';
                    if($flag = Jobs_Data_Service::get_flag_url($country_slug)): ?>
                        <img src="<?php echo $flag; ?>" class="country-flag-icon">
                    <?php endif; ?>
                    <span><?php echo $countries ? esc_html($countries[0]->name) : 'International'; ?><?php echo $cities ? ', '.esc_html($cities[0]->name) : ''; ?></span>
                </div>
            </div>

            <div class="profile-v4-actions">
                <div class="v4-action-group">
                    <button class="v4-btn-primary quick-apply-toggle" data-job-id="<?php echo $post_id; ?>"><span class="dashicons dashicons-paper-plane"></span> Apply Now</button>
                    <button class="v4-icon-btn open-share-modal" title="Share Job"><span class="dashicons dashicons-share"></span></button>
                </div>
            </div>
        </header>

        <?php Jobs_Ads_Service::display_ad('above_content'); ?>

        <div class="profile-v4-grid">

            <!-- MAIN COLUMN: JOB CONTENT -->
            <div class="profile-v4-main">

                <section class="v4-card">
                    <h3 class="v4-card-title"><span class="dashicons dashicons-text-page"></span> Role Description</h3>
                    <div class="v4-card-body" style="font-size: 15px; line-height: 1.8;">
                        <?php the_content(); ?>
                    </div>
                </section>

                <?php if($responsibilities): ?>
                <section class="v4-card">
                    <h3 class="v4-card-title"><span class="dashicons dashicons-list-view"></span> Key Responsibilities</h3>
                    <div class="v4-card-body" style="font-size: 15px; line-height: 1.8;">
                        <?php echo wpautop(esc_html($responsibilities)); ?>
                    </div>
                </section>
                <?php endif; ?>

                <?php if($qualifications): ?>
                <section class="v4-card">
                    <h3 class="v4-card-title"><span class="dashicons dashicons-id"></span> Ideal Candidate Profile</h3>
                    <div class="v4-card-body" style="font-size: 15px; line-height: 1.8;">
                        <?php echo wpautop(esc_html($qualifications)); ?>
                    </div>
                </section>
                <?php endif; ?>

                <?php if($benefits): ?>
                <section class="v4-card">
                    <h3 class="v4-card-title"><span class="dashicons dashicons-heart"></span> Perks & Benefits</h3>
                    <div class="v4-card-body" style="font-size: 15px; line-height: 1.8;">
                        <?php echo wpautop(esc_html($benefits)); ?>
                    </div>
                </section>
                <?php endif; ?>

                <div class="v4-card" style="display: flex; align-items: center; justify-content: space-between; padding: 25px 40px; background: #f8fafc;">
                    <span style="font-weight: 700; color: #1d3469; font-size: 14px;">Share this opportunity:</span>
                    <div style="display: flex; gap: 12px;">
                        <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . '%20' . $share_url; ?>" target="_blank" class="share-icon-btn share-whatsapp" title="Share via WhatsApp"><span class="dashicons dashicons-whatsapp"></span></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="share-icon-btn share-facebook" title="Share via Facebook" style="background:#1877F2; color:white;"><span class="dashicons dashicons-facebook"></span></a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" class="share-icon-btn share-twitter" title="Share via X" style="background:#000; color:white;"><span class="dashicons dashicons-twitter"></span></a>
                    </div>
                </div>

                <?php Jobs_Ads_Service::display_ad('below_content'); ?>

            </div>

            <!-- SIDEBAR: JOB INSIGHTS -->
            <div class="profile-v4-sidebar">

                <section class="v4-card">
                    <h3 class="v4-card-title">Job Insights</h3>
                    <div class="v4-card-body">
                        <div class="v4-career-item">
                            <span class="dashicons dashicons-money"></span>
                            <div>
                                <small>Offered Salary</small>
                                <span style="color: #10b981;"><?php echo $salary ? $currency_sym . ' ' . $salary : 'Competitive Package'; ?></span>
                            </div>
                        </div>
                        <div class="v4-career-item">
                            <span class="dashicons dashicons-awards"></span>
                            <div>
                                <small>Experience Level</small>
                                <span><?php echo esc_html($exp_level ?: 'Open to all levels'); ?></span>
                            </div>
                        </div>
                        <div class="v4-career-item">
                            <span class="dashicons dashicons-admin-site"></span>
                            <div>
                                <small>Work Environment</small>
                                <span><?php echo esc_html($work_setting ?: 'On-site'); ?></span>
                            </div>
                        </div>
                        <?php if($deadline): ?>
                        <div class="v4-career-item">
                            <span class="dashicons dashicons-calendar-alt" style="color:#ef4444;"></span>
                            <div>
                                <small>Application Deadline</small>
                                <span style="color:#ef4444;"><?php echo date('M d, Y', strtotime($deadline)); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <?php if($skills): ?>
                <section class="v4-card">
                    <h3 class="v4-card-title">Required Skills</h3>
                    <div class="v4-tag-container">
                        <?php
                        $s_arr = explode(',', $skills);
                        foreach($s_arr as $s): if(trim($s)): ?>
                            <span class="v4-pastel-pill"><?php echo trim($s); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <?php Jobs_Ads_Service::display_ad('sidebar'); ?>

                <section class="v4-card" style="background: linear-gradient(135deg, #1d3469 0%, #2a4a8c 100%); border: none; color: #FFFFFF; text-align: center;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 1px solid rgba(255,255,255,0.2);">
                        <span class="dashicons dashicons-building" style="font-size: 28px; width: 28px; height: 28px; color: #60a5fa;"></span>
                    </div>
                    <h4 style="margin: 0 0 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.7); letter-spacing: 0.1em;">Employer Spotlight</h4>
                    <p style="font-weight: 800; font-size: 20px; margin-bottom: 25px; line-height: 1.2;"><?php echo esc_html($company_name); ?></p>
                    <a href="<?php echo esc_url($company_profile_url); ?>" style="display: block; background: #FFFFFF; color: #1d3469; padding: 14px; border-radius: 12px; font-weight: 700; text-decoration: none; transition: transform 0.2s; font-size: 14px;">View Organization Profile</a>
                </section>

            </div>

        </div>
    </div>
</div>

<!-- Modal: Share Job -->
<div id="share-modal" class="jobs-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div class="modal-content" style="background:white; padding:40px; border-radius:24px; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.15); text-align: center;">
        <div style="width: 64px; height: 64px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #1d3469;">
            <span class="dashicons dashicons-share" style="font-size: 32px; width: 32px; height: 32px;"></span>
        </div>
        <h3 style="margin-top:0; font-size: 22px; font-weight: 700; color: #1d3469;">Share Opportunity</h3>
        <p style="color: #64748b; font-size: 14px; margin: 8px 0 32px; line-height: 1.5;">Help others discover this professional role by sharing it across your network.</p>

        <div style="display: flex; justify-content: center; gap: 16px; margin-bottom: 32px;">
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" target="_blank" class="share-icon-btn share-linkedin" title="Share on LinkedIn"><span class="dashicons dashicons-networking"></span></a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" class="share-icon-btn share-twitter" title="Share on Twitter"><span class="dashicons dashicons-twitter"></span></a>
            <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . ' ' . $share_url; ?>" target="_blank" class="share-icon-btn share-whatsapp" title="Share on WhatsApp"><span class="dashicons dashicons-phone"></span></a>
            <a href="mailto:?subject=Job Opportunity: <?php echo get_the_title(); ?>&body=Check this out: <?php echo get_permalink(); ?>" class="share-icon-btn share-email" title="Share via Email"><span class="dashicons dashicons-email"></span></a>
        </div>

        <div style="position: relative; margin-bottom: 32px;">
            <input type="text" id="share-url-input" readonly value="<?php echo get_permalink(); ?>" style="width:100%; padding:14px 48px 14px 16px; border-radius:12px; border:1px solid #e2e8f0; font-size: 13px; color: #475569; background: #f8fafc; font-family: 'Rubik', sans-serif;">
            <button id="copy-share-url" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #1d3469; cursor: pointer; padding: 4px;" title="Copy Link">
                <span class="dashicons dashicons-admin-links"></span>
            </button>
        </div>

        <button class="v4-btn-secondary close-share-modal" style="width: 100%; height: 46px; border-radius: 23px;">Dismiss</button>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Share Modal Logic
    $('.open-share-modal').on('click', function() { $('#share-modal').css('display', 'flex'); });
    $('.close-share-modal').on('click', function() { $('#share-modal').hide(); });

    $('#copy-share-url').on('click', function() {
        var copyText = document.getElementById("share-url-input");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);

        var $btn = $(this);
        var originalHtml = $btn.html();
        $btn.html('<span class="dashicons dashicons-yes" style="color: #10b981;"></span>');
        setTimeout(function() { $btn.html(originalHtml); }, 2000);
    });
});
</script>

<?php endwhile; endif;

get_footer();
?>
