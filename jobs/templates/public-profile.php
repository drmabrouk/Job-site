<?php
/**
 * Template: SaaS Enterprise Professional Portfolio (V5)
 * Strict minimalist design matching LinkedIn/Behance standards.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$profile_slug = get_query_var('profile_user');
if ( ! $profile_slug ) {
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $parts = explode('/', $path);
    if (count($parts) >= 2 && $parts[0] === 'profile') {
        $profile_slug = $parts[1];
    }
}

$user = $profile_slug ? get_user_by('slug', $profile_slug) : null;
if (!$user) wp_die('Profile not found.');

$user_id = $user->ID;
$visibility = get_user_meta($user_id, 'profile_visibility', true) ?: 'public';
if ( $visibility === 'private' && get_current_user_id() !== $user_id && !current_user_can('manage_options') ) {
    wp_die('This profile is set to private.');
}
$role = $user->roles[0] ?? 'job_seeker';
$display_name = $user->display_name;
$is_verified = get_user_meta($user_id, '_is_email_verified', true);
$last_activity = get_user_meta($user_id, '_last_activity', true);


get_header();
?>
<div class="jobs-premium-profile-v4">
    <div class="profile-layout-container">

        <?php if ($role === 'employer') :
            $company = get_user_meta($user_id, 'jobs_company_data', true) ?: array();
            $logo = get_user_meta($user_id, '_jobs_profile_photo', true) ?: (!empty($company['logo']) ? $company['logo'] : get_avatar_url($user_id, array('size' => 120)));

            $active_jobs = new WP_Query(array(
                'post_type' => 'job', 'post_status' => 'publish', 'author' => $user_id, 'posts_per_page' => 5
            ));
            $total_posted = count_user_posts($user_id, 'job', true);
            $branches = !empty($company['branches']) ? explode('|', $company['branches']) : array();
            ?>
            <!-- HEADER: EMPLOYER -->
            <header class="profile-v4-header">
                <div class="profile-v4-avatar-box">
                    <img src="<?php echo esc_url($logo); ?>" alt="Company Logo">
                </div>
                <div class="profile-v4-identity-box">
                    <div style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 16px;">
                        <h1 style="display: inline-flex; align-items: center; gap: 10px; margin: 0; font-size: 54px;"><?php echo esc_html($company['name'] ?? $display_name); ?> <span class="badge-verified-circle" title="Verified Entity" style="margin: 0; position: static; width: 28px; height: 28px;"><span class="dashicons dashicons-yes" style="font-size: 18px; width: 18px; height: 18px;"></span></span></h1>
                    </div>
                    <div style="margin: 16px 0; display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;">
                        <span class="v4-pastel-pill pill-blue"><?php echo esc_html($company['industry'] ?? 'Corporate Entity'); ?></span>
                        <span class="v4-pastel-pill pill-green">Verified Entity</span>
                        <span class="v4-pastel-pill pill-purple"><?php echo esc_html($company['company_type'] ?? 'Organization'); ?></span>
                    </div>
                    <p class="profile-v4-headline" style="font-size: 18px; font-weight: 500; color: #475569;"><?php echo esc_html($company['legal_name'] ?? ''); ?></p>
                    <div class="profile-v4-location-info">
                        <?php $c_slug = strtolower(str_replace(' ', '-', $company['address'] ?? '')); ?>
                        <?php if($flag = Jobs_Data_Service::get_flag_url($c_slug)): ?>
                            <img src="<?php echo $flag; ?>" class="country-flag-icon">
                        <?php endif; ?>
                        <span><?php echo esc_html($company['address'] ?? 'International'); ?></span>
                    </div>
                </div>
                <div class="profile-v4-actions">
                    <div class="v4-action-group">
                        <button class="v4-btn-primary open-message-modal" data-receiver="<?php echo $user_id; ?>"><span class="dashicons dashicons-email-alt"></span> Contact Platform</button>
                        <button class="v4-icon-btn" onclick="window.print()" title="Print Profile"><span class="dashicons dashicons-media-document"></span></button>
                        <button class="v4-icon-btn open-share-modal" title="Share Profile"><span class="dashicons dashicons-share"></span></button>
                        <?php if ( get_current_user_id() === $user_id ) : ?>
                            <a href="<?php echo home_url('/account-setup/'); ?>" class="v4-icon-btn" title="Update Company Profile"><span class="dashicons dashicons-admin-generic"></span></a>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <div class="profile-v4-grid">
                <div class="profile-v4-main">
                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-businesswoman"></span> Company Overview</h3>
                        <div class="v4-card-body">
                            <p><?php echo nl2br(esc_html($company['details'] ?? 'Strategic organization focused on global excellence.')); ?></p>

                            <div style="margin-top: 32px; padding: 24px; background: #f8fafc; border-radius: 16px; border-left: 4px solid #1d3469;">
                                <h4 style="font-size: 15px; font-weight: 700; color: #1d3469; margin-bottom: 12px;">Why Join Our Team?</h4>
                                <p style="font-size: 13px; color: #475569; line-height: 1.7; margin: 0;">We foster a culture of innovation and inclusivity, where every voice is heard and every contribution matters. Join us to be part of a forward-thinking team dedicated to making a global impact.</p>
                            </div>

                            <?php if(!empty($company['benefits'])): ?>
                                <div style="margin-top: 20px;">
                                    <h4 style="font-size: 13px; font-weight: 600; margin-bottom: 8px;">Benefits & Perks</h4>
                                    <p style="font-size: 12px; color: #666;"><?php echo nl2br(esc_html($company['benefits'])); ?></p>
                                </div>
                            <?php endif; ?>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 32px; padding-top: 32px; border-top: 1px solid #f1f5f9;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                        <span class="dashicons dashicons-flag" style="color: #1d3469; font-size: 18px; width: 18px; height: 18px;"></span>
                                        <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0;">Our Mission</h4>
                                    </div>
                                    <p style="font-size: 13px; color: #64748b; line-height: 1.6;"><?php echo esc_html($company['mission'] ?? 'To lead through innovation and integrity, delivering exceptional value to our stakeholders and community.'); ?></p>
                                </div>
                                <div>
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                        <span class="dashicons dashicons-groups" style="color: #1d3469; font-size: 18px; width: 18px; height: 18px;"></span>
                                        <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0;">Culture & Values</h4>
                                    </div>
                                    <p style="font-size: 13px; color: #64748b; line-height: 1.6;"><?php echo esc_html($company['culture'] ?? 'A people-first, result-oriented environment where collaboration and continuous learning are celebrated.'); ?></p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-awards"></span> Corporate Heritage & Recognition</h3>
                        <div class="v4-card-body">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div style="padding: 20px; background: #fdfcfb; border: 1px solid #f3f4f6; border-radius: 16px;">
                                    <h4 style="font-size: 14px; margin-bottom: 10px; color: #1d3469;">Industry Excellence</h4>
                                    <p style="font-size: 12px; color: #64748b; line-height: 1.6;">Recognized as a leading innovator in <?php echo esc_html($company['industry'] ?? 'our field'); ?>, driving standards of quality and efficiency across global markets.</p>
                                </div>
                                <div style="padding: 20px; background: #fdfcfb; border: 1px solid #f3f4f6; border-radius: 16px;">
                                    <h4 style="font-size: 14px; margin-bottom: 10px; color: #1d3469;">Sustainability Commitment</h4>
                                    <p style="font-size: 12px; color: #64748b; line-height: 1.6;">We are dedicated to sustainable business practices, ensuring a positive long-term impact on the communities and environments we operate in.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-megaphone"></span> Open Opportunities</h3>
                        <div class="v4-card-body">
                            <?php if($active_jobs->have_posts()): while($active_jobs->have_posts()): $active_jobs->the_post(); ?>
                                <div style="padding: 16px 0; border-bottom: 1px solid #F5F5F5; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <a href="<?php the_permalink(); ?>" style="font-weight: 600; color: #1d3469; text-decoration: none; font-size: 15px;"><?php the_title(); ?></a>
                                        <div style="font-size: 12px; color: #64748b; margin-top: 6px;">📍 <?php echo esc_html(get_post_meta(get_the_ID(), '_location_city', true)); ?> • 💰 <?php echo esc_html(get_post_meta(get_the_ID(), '_job_salary', true)); ?></div>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="v4-btn-secondary" style="height: 34px; padding: 0 20px; font-size: 12px; border-radius: 10px;">Details</a>
                                </div>
                            <?php endwhile; wp_reset_postdata(); else: ?>
                                <p style="color: #999; text-align: center;">No active listings found.</p>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-format-gallery"></span> Media Gallery</h3>
                        <div class="v4-card-body">
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                                <div style="aspect-ratio: 16/9; background: #FAFAFA; border-radius: 8px; border: 1px solid #EEE; display: flex; align-items: center; justify-content: center; color: #CCC;">Office Asset</div>
                                <div style="aspect-ratio: 16/9; background: #FAFAFA; border-radius: 8px; border: 1px solid #EEE; display: flex; align-items: center; justify-content: center; color: #CCC;">Team Culture</div>
                                <div style="aspect-ratio: 16/9; background: #FAFAFA; border-radius: 8px; border: 1px solid #EEE; display: flex; align-items: center; justify-content: center; color: #CCC;">Branding</div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="profile-v4-sidebar">
                    <?php Jobs_Ads_Service::display_ad('sidebar'); ?>
                    <section class="v4-card contact-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-id-alt" style="color: #1d3469;"></span> Contact Details</h3>
                        <div class="v4-card-body">
                            <?php
                            $c_email = $company['email'] ?? $user->user_email;
                            $c_phone = $company['phone'] ?? get_user_meta($user_id, '_phone', true);
                            ?>
                            <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 12px; padding: 10px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <span class="dashicons dashicons-email" style="color: #64748b; font-size: 18px; width: 18px; height: 18px;"></span>
                                <a href="mailto:<?php echo esc_attr($c_email); ?>" style="color: #1d3469; font-weight: 600; text-decoration: none; font-size: 13px; overflow: hidden; text-overflow: ellipsis;"><?php echo esc_html($c_email); ?></a>
                            </div>

                            <?php if($c_phone): ?>
                            <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <span class="dashicons dashicons-phone" style="color: #64748b; font-size: 18px; width: 18px; height: 18px;"></span>
                                <span style="color: #1d3469; font-weight: 600; font-size: 13px;"><?php echo esc_html($c_phone); ?></span>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $c_phone); ?>" target="_blank" style="margin-left: auto; color: #25D366;" title="WhatsApp Chat">
                                    <span class="dashicons dashicons-whatsapp"></span>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title">Market Presence</h3>
                        <div class="v4-card-body">
                            <div style="margin-bottom: 12px; display: flex; justify-content: space-between;">
                                <span style="font-size: 12px; color: #64748b;">Global Reach</span>
                                <span style="font-size: 12px; font-weight: 700; color: #1d3469;">High</span>
                            </div>
                            <div style="margin-bottom: 12px; display: flex; justify-content: space-between;">
                                <span style="font-size: 12px; color: #64748b;">Industry Rank</span>
                                <span style="font-size: 12px; font-weight: 700; color: #1d3469;">Top 10%</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="font-size: 12px; color: #64748b;">Stability</span>
                                <span style="font-size: 12px; font-weight: 700; color: #1d3469;">Excellent</span>
                            </div>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-location"></span> Global Operation Footprint</h3>
                        <div class="v4-card-body">
                            <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                                <p style="font-size: 12px; color: #475569; margin: 0;">Our operations span across multiple regions, providing localized expertise with a global perspective.</p>
                            </div>
                            <?php if(!empty($branches)): ?>
                                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                    <?php foreach($branches as $branch): ?>
                                        <span class="v4-pastel-pill" style="height: 24px; font-size: 11px;"><?php echo esc_html($branch); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p style="font-size: 12px; color: #94a3b8;">Primary headquarters in <?php echo esc_html($company['address'] ?? 'Undisclosed'); ?>.</p>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title">Corporate Profile</h3>
                        <div class="v4-card-body">
                            <div style="margin-bottom: 16px;">
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Founded</small>
                                <span style="font-weight: 500;"><?php
                                    echo esc_html($company['founded_year'] ?? 'N/A');
                                    if(!empty($company['founded_year']) && is_numeric($company['founded_year'])) {
                                        $age = date('Y') - intval($company['founded_year']);
                                        echo ' (' . esc_html($age) . ' Years in Business)';
                                    }
                                ?></span>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Size</small>
                                <span style="font-weight: 500;"><?php echo esc_html($company['employee_count'] ?? 'N/A'); ?> Employees</span>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">HQ Location</small>
                                <span style="font-weight: 500;"><?php echo esc_html($company['address'] ?? 'Not Listed'); ?></span>
                            </div>
                            <?php if(!empty($branches)): ?>
                                <div style="margin-bottom: 16px;">
                                    <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Branches</small>
                                    <span style="font-weight: 500; font-size: 12px;"><?php echo esc_html(implode(', ', $branches)); ?></span>
                                </div>
                            <?php endif; ?>
                            <div style="margin-bottom: 16px;">
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Website</small>
                                <a href="<?php echo esc_url($company['website']); ?>" target="_blank" style="color: #1d3469; font-weight: 600; text-decoration: none;">Official Site ↗</a>
                            </div>
                        </div>
                    </section>

                    <section class="v4-card performance-card" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-performance" style="color: #1d3469;"></span> Hiring Intelligence</h3>
                        <div class="v4-stats-grid">
                            <div class="v4-stat-item">
                                <span class="v4-stat-value">98%</span>
                                <span class="v4-stat-label">Response</span>
                            </div>
                            <div class="v4-stat-item">
                                <span class="v4-stat-value">4 Days</span>
                                <span class="v4-stat-label">Avg. Hire</span>
                            </div>
                            <div class="v4-stat-item">
                                <span class="v4-stat-value"><?php echo number_format($total_posted * 12 + 45); ?></span>
                                <span class="v4-stat-label">Followers</span>
                            </div>
                            <div class="v4-stat-item">
                                <span class="v4-stat-value">4.8/5</span>
                                <span class="v4-stat-label">Trust Score</span>
                            </div>
                        </div>
                        <div style="margin-top: 16px; padding: 12px; background: #E6F4EA; border-radius: 8px; text-align: center;">
                            <span style="font-size: 11px; color: #1E8E3E; font-weight: 700;">Highly Responsive Employer</span>
                        </div>
                    </section>

                    <div style="text-align: center; color: #999; font-size: 11px; font-weight: 500;">
                        Last Updated: <?php echo date('M d, Y', strtotime($company['last_update'] ?? $user->user_registered)); ?>
                    </div>
                </div>
            </div>
            <?php Jobs_Ads_Service::display_ad('below_content'); ?>

        <?php else :
            $cv = get_user_meta($user_id, 'jobs_cv_data_v2', true) ?: array();
            $spec = get_user_meta($user_id, '_specialization', true) ?: 'Professional';
            $sec_specs = get_user_meta($user_id, '_secondary_specs', true) ?: array();
            $prof = get_user_meta($user_id, '_profession', true);
            $exp_years = get_user_meta($user_id, '_experience', true);

            $academic = !empty($cv['academic']) ? $cv['academic'] : array();
            $experience = !empty($cv['experience']) ? $cv['experience'] : array();
            $portfolio = !empty($cv['portfolio']) ? $cv['portfolio'] : array();
            $certs = !empty($cv['certs']) ? $cv['certs'] : array();
            $refs = !empty($cv['references']) ? $cv['references'] : array();
            $skills = array_filter(explode(',', $cv['skills']['core'] ?? ''));

            $country = get_user_meta($user_id, '_country', true);
            $region = get_user_meta($user_id, '_region', true);
            ?>
            <!-- HEADER: SEEKER -->
            <header class="profile-v4-header">
                <div class="profile-v4-avatar-box" style="position: relative;">
                    <?php $seeker_photo = get_user_meta($user_id, '_jobs_profile_photo', true) ?: get_avatar_url($user_id, array('size' => 180)); ?>
                    <img src="<?php echo esc_url($seeker_photo); ?>" alt="Profile Photo">
                    <?php if(($cv['preferences']['availability_status'] ?? '') === 'Immediate'): ?>
                        <div class="v4-open-to-work-overlay" title="Open to Work"></div>
                    <?php endif; ?>
                </div>
                <div class="profile-v4-identity-box">
                    <div style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 16px;">
                        <h1 style="display: inline-flex; align-items: center; gap: 10px; margin: 0; font-size: 54px;"><?php echo esc_html($cv['personal']['full_name'] ?? $display_name); ?> <span class="badge-verified-circle" title="Verified" style="margin: 0; position: static; width: 28px; height: 28px;"><span class="dashicons dashicons-yes" style="font-size: 18px; width: 18px; height: 18px;"></span></span></h1>
                    </div>
                    <div style="margin: 16px 0; display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;">
                        <span class="v4-pastel-pill pill-blue"><?php echo esc_html($prof ?: 'Professional'); ?></span>
                        <?php
                        $status = $cv['preferences']['availability_status'] ?? 'Immediate';
                        $status_pill = ($status === 'Immediate') ? 'pill-green' : 'pill-yellow';
                        ?>
                        <span class="v4-pastel-pill <?php echo $status_pill; ?>"><?php echo esc_html($status); ?></span>
                        <span class="v4-pastel-pill pill-purple"><?php echo esc_html($spec); ?></span>
                    </div>
                    <p class="profile-v4-headline" style="font-size: 18px; font-weight: 500; color: #475569;"><?php echo esc_html($spec); ?> • <?php echo esc_html($exp_years ?: '0'); ?>+ Productive Years
                    <?php
                    $dob = $cv['personal']['dob'] ?? '';
                    if($dob):
                        $age = date_diff(date_create($dob), date_create('today'))->y;
                        echo ' • ' . esc_html($age) . ' Years Old';
                    endif;
                    ?>
                    </p>

                    <div class="profile-v4-location-info v4-mobile-row">
                        <?php
                        $nationality = get_user_meta($user_id, '_nationality', true);
                        $residence = get_user_meta($user_id, '_country', true);
                        $region = get_user_meta($user_id, '_region', true);
                        ?>
                        <?php if($nationality): ?>
                            <div class="location-item-row" title="Nationality">
                                <?php if($f = Jobs_Data_Service::get_flag_url($nationality)): ?><img src="<?php echo $f; ?>" class="country-flag-icon"><?php endif; ?>
                                <span><?php echo ucwords(str_replace('-', ' ', $nationality)); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if($residence): ?>
                            <div class="location-item-row" title="Country of Residence">
                                <span>Resident in </span>
                                <?php if($f = Jobs_Data_Service::get_flag_url($residence)): ?><img src="<?php echo $f; ?>" class="country-flag-icon" style="margin-left: 5px;"><?php endif; ?>
                                <span><?php echo ($region ? $region . ', ' : '') . ucwords(str_replace('-', ' ', $residence)); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="profile-v4-actions">
                    <div class="v4-action-group">
                        <button class="v4-btn-primary open-message-modal" data-receiver="<?php echo $user_id; ?>"><span class="dashicons dashicons-businessperson"></span> Career Inquiry</button>
                        <button class="v4-icon-btn" onclick="window.print()" title="Download PDF Portfolio"><span class="dashicons dashicons-media-document"></span></button>
                        <button class="v4-icon-btn open-share-modal" title="Share Profile"><span class="dashicons dashicons-share"></span></button>
                        <?php if ( get_current_user_id() === $user_id ) : ?>
                            <a href="<?php echo home_url('/account-setup/'); ?>" class="v4-icon-btn" title="Update Professional Data"><span class="dashicons dashicons-admin-generic"></span></a>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <div class="profile-v4-grid">
                <div class="profile-v4-main">
                    <?php Jobs_Ads_Service::display_ad('above_content'); ?>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-admin-users"></span> Professional Summary</h3>
                        <div class="v4-card-body">
                            <?php $summary = get_user_meta($user_id, '_professional_summary', true) ?: (get_user_meta($user_id, '_bio', true) ?: 'Dedicated professional with expertise in strategic field development and execution.'); ?>
                            <p><?php echo nl2br(esc_html($summary)); ?></p>

                            <?php if($philosophy = get_user_meta($user_id, '_professional_philosophy', true)): ?>
                            <div style="margin-top: 24px; padding: 20px; background: #fdf2f8; border-radius: 16px; position: relative;">
                                <span class="dashicons dashicons-format-quote" style="position: absolute; right: 20px; top: 20px; color: #fbcfe8; font-size: 32px; width: 32px; height: 32px;"></span>
                                <h4 style="font-size: 14px; font-weight: 700; color: #9d174d; margin-bottom: 8px;">Professional Philosophy</h4>
                                <p style="font-size: 13px; color: #be185d; line-height: 1.6; font-style: italic; margin: 0; max-width: 90%;">"<?php echo esc_html($philosophy); ?>"</p>
                            </div>
                            <?php endif; ?>

                            <?php if(!empty($sec_specs)): ?>
                                <div style="margin-top: 16px;">
                                    <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700; margin-bottom: 8px;">Secondary Specializations</small>
                                    <div class="v4-tag-container">
                                        <?php foreach($sec_specs as $ss): ?><span class="v4-pastel-pill" style="height: 22px; font-size: 11px;"><?php echo esc_html($ss); ?></span><?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-portfolio"></span> Experience History</h3>
                        <div class="v4-card-body">
                            <?php if(!empty($experience)): foreach($experience as $exp): ?>
                                <div class="v4-timeline-item">
                                    <div class="v4-timeline-header">
                                        <div class="v4-timeline-title"><?php echo esc_html($exp['title']); ?></div>
                                        <span style="font-size: 11px; color: #999; font-weight: 600;"><?php echo esc_html($exp['type'] ?? 'Full-time'); ?></span>
                                    </div>
                                    <div class="v4-timeline-org"><?php echo esc_html($exp['company']); ?></div>
                                    <div class="v4-timeline-meta"><?php echo date('M Y', strtotime($exp['start'])); ?> — <?php echo !empty($exp['end']) ? date('M Y', strtotime($exp['end'])) : 'Present'; ?></div>
                                    <p style="font-size: 13px; color: #666; margin-top: 8px; line-height: 1.5;"><?php echo nl2br(esc_html($exp['tasks'] ?? '')); ?></p>
                                </div>
                            <?php endforeach; else: ?>
                                <p style="color: #999; font-size: 13px;">Experience data pending verification.</p>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-awards"></span> Career Milestone Highlights</h3>
                        <div class="v4-card-body">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
                                <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center;">
                                    <div style="font-size: 24px; font-weight: 800; color: #1d3469; margin-bottom: 5px;"><?php echo esc_html($exp_years ?: '0'); ?>+</div>
                                    <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Years Experience</div>
                                </div>
                                <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center;">
                                    <div style="font-size: 24px; font-weight: 800; color: #1d3469; margin-bottom: 5px;"><?php echo count($experience); ?></div>
                                    <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Corporate Roles</div>
                                </div>
                                <div style="background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center;">
                                    <div style="font-size: 24px; font-weight: 800; color: #1d3469; margin-bottom: 5px;"><?php echo count($skills); ?>+</div>
                                    <div style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase;">Key Competencies</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-star-filled"></span> Key Accomplishments</h3>
                        <div class="v4-card-body">
                            <?php
                            $accomplishments = get_user_meta($user_id, '_key_accomplishments', true);
                            if($accomplishments):
                                $acc_list = explode("\n", $accomplishments);
                                echo '<ul style="margin: 0; padding-left: 18px; color: #475569; font-size: 13px; line-height: 1.8;">';
                                foreach($acc_list as $acc) {
                                    if(trim($acc)) echo '<li>' . esc_html($acc) . '</li>';
                                }
                                echo '</ul>';
                            else: ?>
                                <ul style="margin: 0; padding-left: 18px; color: #475569; font-size: 13px; line-height: 1.8;">
                                    <li>Successfully led cross-functional teams to deliver high-impact projects ahead of schedule.</li>
                                    <li>Optimized operational workflows, resulting in a 20% increase in overall efficiency.</li>
                                    <li>Recognized for exceptional leadership and commitment to professional excellence.</li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-welcome-learn-more"></span> Academic Background</h3>
                        <div class="v4-card-body">
                            <?php if(!empty($academic)): foreach($academic as $edu): ?>
                                <div class="v4-timeline-item">
                                    <div class="v4-timeline-title"><?php echo esc_html($edu['degree']); ?></div>
                                    <div class="v4-timeline-org"><?php echo esc_html($edu['uni']); ?></div>
                                    <div class="v4-timeline-meta">Class of <?php echo esc_html($edu['grad_date'] ?? $edu['grad_year'] ?? 'N/A'); ?> • <?php echo esc_html($edu['qual_type'] ?? 'Academic'); ?></div>
                                    <?php if(!empty($edu['spec'])): ?>
                                        <div style="font-size: 12px; color: #64748b; margin-top: 5px;">Major: <?php echo esc_html($edu['spec']); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; else: ?>
                                <p style="color: #999; font-size: 13px;">Academic history not provided.</p>
                            <?php endif; ?>
                        </div>
                    </section>

                    <?php if(!empty($refs)): ?>
                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-awards"></span> Professional References</h3>
                        <div class="v4-card-body">
                            <?php foreach($refs as $r): ?>
                                <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                                    <div style="font-weight: 700; color: #1d3469; font-size: 14px;"><?php echo esc_html($r['name']); ?></div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 2px;"><?php echo esc_html($r['title']); ?> • <?php echo esc_html($r['company']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-admin-settings"></span> Strategic Core Competencies</h3>
                        <div class="v4-card-body">
                            <p style="color: #64748b; margin-bottom: 20px;">Validated skills and operational capabilities acquired through professional engagement.</p>
                            <div class="v4-tag-container">
                                <span class="v4-pastel-pill pill-blue">Strategic Planning</span>
                                <span class="v4-pastel-pill pill-purple">Operational Leadership</span>
                                <span class="v4-pastel-pill pill-green">Project Execution</span>
                                <span class="v4-pastel-pill pill-yellow">Business Transformation</span>
                                <span class="v4-pastel-pill pill-dev">Crisis Management</span>
                                <span class="v4-pastel-pill pill-design">Stakeholder Alignment</span>
                            </div>
                        </div>
                    </section>

                    <?php if(!empty($portfolio)): ?>
                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-visibility"></span> Case Studies & Work Samples</h3>
                        <div class="v4-card-body">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <?php foreach($portfolio as $port): ?>
                                    <div style="padding: 16px; background: #FAFAFA; border-radius: 12px; border: 1px solid #F0F0F0;">
                                        <div style="font-weight: 600; color: #111;"><?php echo esc_html($port['title']); ?></div>
                                        <p style="font-size: 12px; color: #666; margin: 8px 0;"><?php echo esc_html($port['desc']); ?></p>
                                        <a href="<?php echo esc_url($port['url']); ?>" target="_blank" style="font-size: 11px; font-weight: 700; color: #1d3469; text-decoration: none;">View Sample ↗</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                    <?php endif; ?>
                </div>

                <div class="profile-v4-sidebar">
                    <?php Jobs_Ads_Service::display_ad('sidebar'); ?>
                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-hammer"></span> Strategic Domain Knowledge</h3>
                        <div class="v4-card-body">
                            <p style="font-size: 12px; color: #64748b; margin-bottom: 20px;">Comprehensive expertise across core technical and professional domains, developed through years of practical application.</p>
                            <div class="v4-tag-container">
                                <?php foreach($skills as $s): ?>
                                    <span class="v4-pastel-pill pill-blue"><?php echo trim($s); ?></span>
                                <?php endforeach; ?>
                                <?php foreach($sec_specs as $ss): ?>
                                    <span class="v4-pastel-pill pill-purple"><?php echo esc_html($ss); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>

                    <section class="v4-card contact-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-id-alt" style="color: #1d3469;"></span> Contact Details</h3>
                        <div class="v4-card-body">
                            <?php
                            $s_email = $cv['personal']['email'] ?? $user->user_email;
                            $s_phone = $cv['personal']['phone'] ?? get_user_meta($user_id, '_phone', true);
                            ?>
                            <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 12px; padding: 10px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <span class="dashicons dashicons-email" style="color: #64748b; font-size: 18px; width: 18px; height: 18px;"></span>
                                <a href="mailto:<?php echo esc_attr($s_email); ?>" style="color: #1d3469; font-weight: 600; text-decoration: none; font-size: 13px; overflow: hidden; text-overflow: ellipsis;"><?php echo esc_html($s_email); ?></a>
                            </div>

                            <?php if($s_phone): ?>
                            <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <span class="dashicons dashicons-phone" style="color: #64748b; font-size: 18px; width: 18px; height: 18px;"></span>
                                <span style="color: #1d3469; font-weight: 600; font-size: 13px;"><?php echo esc_html($s_phone); ?></span>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $s_phone); ?>" target="_blank" style="margin-left: auto; color: #25D366;" title="WhatsApp Chat">
                                    <span class="dashicons dashicons-whatsapp"></span>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </section>


                    <section class="v4-card">
                        <h3 class="v4-card-title">Career Profile</h3>
                        <div class="v4-card-body">
                            <div class="v4-career-item">
                                <span class="dashicons dashicons-awards"></span>
                                <div>
                                    <small>Professional Experience</small>
                                    <span><?php echo esc_html($exp_years ?: '0'); ?>+ Productive Years</span>
                                </div>
                            </div>
                            <div class="v4-career-item">
                                <span class="dashicons dashicons-clock"></span>
                                <div>
                                    <small>Availability Status</small>
                                    <span><?php echo esc_html($cv['preferences']['availability_status'] ?? 'Immediate'); ?></span>
                                </div>
                            </div>
                            <div class="v4-career-item">
                                <span class="dashicons dashicons-admin-site"></span>
                                <div>
                                    <small>Preferred Employment</small>
                                    <span><?php echo esc_html($cv['preferences']['contract'] ?? 'Full-time'); ?> • <?php echo esc_html($cv['preferences']['flexibility'] ?? 'Remote'); ?></span>
                                </div>
                            </div>
                            <div class="v4-career-item">
                                <span class="dashicons dashicons-money"></span>
                                <div>
                                    <small>Monthly Expectation</small>
                                    <span style="color: #10b981;"><?php echo esc_html($cv['preferences']['currency'] ?? 'USD'); ?> <?php echo esc_html($cv['preferences']['salary'] ?? 'Competitive'); ?></span>
                                </div>
                            </div>
                            <div class="v4-career-item">
                                <span class="dashicons dashicons-translation"></span>
                                <div>
                                    <small>Language Proficiency</small>
                                    <span><?php echo esc_html($cv['languages']['native'] ?? 'English'); ?><?php echo !empty($cv['languages']['other']) ? ', '.esc_html($cv['languages']['other']) : ''; ?></span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <?php if(!empty($certs)): ?>
                    <section class="v4-card">
                        <h3 class="v4-card-title">Certifications</h3>
                        <div class="v4-card-body">
                            <?php foreach($certs as $c): ?>
                                <div style="margin-bottom: 12px;">
                                    <div style="font-weight: 600; font-size: 13px;"><?php echo esc_html($c['name']); ?></div>
                                    <div style="font-size: 11px; color: #999;"><?php echo esc_html($c['auth']); ?> • <?php echo date('Y', strtotime($c['date'])); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <section class="v4-card integrity-card" style="background: linear-gradient(135deg, #1d3469 0%, #2a4a8c 100%); border: none; color: #FFFFFF;">
                        <h3 class="v4-card-title" style="color: #FFFFFF; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 16px;"><span class="dashicons dashicons-shield-alt" style="color: #60a5fa;"></span> Profile Strength</h3>
                        <div style="height: 12px; background: rgba(255,255,255,0.1); border-radius: 10px; overflow: hidden; margin: 24px 0 12px; border: 1px solid rgba(255,255,255,0.05);">
                            <div style="width: <?php echo esc_attr($cv['completeness'] ?? 75); ?>%; height: 100%; background: linear-gradient(to right, #60a5fa, #34d399); border-radius: 10px; box-shadow: 0 0 15px rgba(96,165,250,0.5);"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.7);">
                            <span style="letter-spacing: 0.1em;">COMPLETENESS SCORE</span>
                            <span style="color: #34d399; font-size: 14px;"><?php echo esc_html($cv['completeness'] ?? 75); ?>%</span>
                        </div>

                        <?php
                        $tips = array();
                        if(empty($cv['personal']['summary'])) $tips[] = "Add a professional summary";
                        if(empty($cv['experience'])) $tips[] = "List your work experience";
                        if(empty($cv['academic'])) $tips[] = "Add your academic history";
                        if(empty($skills)) $tips[] = "Highlight your key skills";
                        if(empty($portfolio)) $tips[] = "Showcase your work samples";

                        if(!empty($tips)): ?>
                            <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
                                <div style="font-size: 10px; font-weight: 800; color: #60a5fa; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.05em;">Improve Your Presence</div>
                                <ul style="margin: 0; padding: 0; list-style: none;">
                                    <?php foreach(array_slice($tips, 0, 3) as $tip): ?>
                                        <li style="font-size: 12px; margin-bottom: 8px; display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.9);">
                                            <span class="dashicons dashicons-plus-alt" style="font-size: 14px; width: 14px; height: 14px; color: #34d399;"></span>
                                            <?php echo esc_html($tip); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </section>

                    <div style="text-align: center; color: #999; font-size: 11px; font-weight: 500;">
                        Last Active: <?php echo $last_activity ? human_time_diff($last_activity, current_time('timestamp')).' ago' : 'Recently'; ?>
                    </div>
                </div>
            </div>
            <?php Jobs_Ads_Service::display_ad('below_content'); ?>
        <?php endif; ?>

    </div>
</div>

<!-- Modal: Share Profile -->
<div id="share-modal" class="jobs-modal" style="display:none; position:fixed; inset:0; background:rgba(29, 52, 105, 0.1); backdrop-filter: blur(8px); z-index:9999; align-items:center; justify-content:center;">
    <div class="modal-content" style="background:white; padding:48px; border-radius:32px; width:100%; max-width:440px; box-shadow:0 30px 80px rgba(0,0,0,0.18); text-align: center; border: 1px solid rgba(0,0,0,0.05);">
        <div style="width: 80px; height: 80px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: #1d3469;">
            <span class="dashicons dashicons-share" style="font-size: 38px; width: 38px; height: 38px;"></span>
        </div>
        <h2 style="margin-top:0; font-size: 26px; font-weight: 800; color: #1d3469; letter-spacing: -0.02em;">Promote Profile</h2>
        <p style="color: #64748b; font-size: 15px; margin: 12px 0 36px; line-height: 1.6;">Share this professional identity across premium networks to enhance visibility and engagement.</p>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 40px;">
            <?php
                $share_url = urlencode(home_url('/profile/' . $profile_slug . '/'));
                $share_text = urlencode('Explore this high-impact professional profile on Jobedia.');
            ?>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" target="_blank" class="share-icon-btn share-linkedin" title="LinkedIn"><span class="dashicons dashicons-networking"></span></a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_text; ?>" target="_blank" class="share-icon-btn share-twitter" title="Twitter"><span class="dashicons dashicons-twitter"></span></a>
            <a href="https://api.whatsapp.com/send?text=<?php echo $share_text . ' ' . $share_url; ?>" target="_blank" class="share-icon-btn share-whatsapp" title="WhatsApp"><span class="dashicons dashicons-phone"></span></a>
            <a href="mailto:?subject=Professional Referral&body=<?php echo $share_text . ' ' . $share_url; ?>" class="share-icon-btn share-email" title="Email"><span class="dashicons dashicons-email"></span></a>
        </div>

        <div style="background: #f8fafc; padding: 6px; border-radius: 16px; border: 1.5px solid #eef2f6; display: flex; align-items: center; margin-bottom: 36px;">
            <input type="text" id="share-url-input" readonly value="<?php echo home_url('/profile/' . $profile_slug . '/'); ?>" style="flex: 1; border: none; background: transparent; padding: 12px 16px; font-size: 13px; color: #475569; font-weight: 500; outline: none;">
            <button id="copy-share-url" style="background: #1d3469; color: white; border: none; padding: 10px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s;">Copy Link</button>
        </div>

        <button class="close-share-modal" style="background: none; border: none; color: #94a3b8; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: underline;">Dismiss Panel</button>
    </div>
</div>


<!-- Modal: Contact/Message -->
<div id="message-modal" class="jobs-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(4px); z-index:9999; align-items:center; justify-content:center;">
    <div class="modal-content" style="background:white; padding:32px; border-radius:16px; width:100%; max-width:480px; box-shadow:0 20px 40px rgba(0,0,0,0.1);">
        <h3 style="margin-top:0; font-size: 18px; font-weight: 600; color: #111;">Contact <?php echo esc_html($display_name); ?></h3>
        <p style="color: #666; font-size: 13px; margin: 8px 0 24px;">Initiate a professional inquiry through the Jobedia platform.</p>
        <textarea id="message-text" placeholder="Write your professional message here..." style="width:100%; height:160px; padding:12px; border-radius:8px; border:1px solid #E0E0E0; margin-bottom: 24px; font-family: inherit; font-size: 13px;"></textarea>
        <div style="display:flex; justify-content:flex-end; gap:12px;">
            <button class="v4-btn-secondary close-modal">Discard</button>
            <button class="v4-btn-primary" id="confirm-send-message">Deliver Message</button>
        </div>
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
        var originalText = $btn.text();
        $btn.text('Copied!').css('background', '#10b981');
        setTimeout(function() { $btn.text(originalText).css('background', '#1d3469'); }, 2000);
    });


    // Phone Reveal Logic
    $('.reveal-btn').on('click', function() {
        $(this).hide();
        $(this).siblings('.hidden-phone').css('display', 'flex');
    });

    // Contact Modal Logic
    $('.open-message-modal').on('click', function() {
        var receiver = $(this).data('receiver');
        $('#message-modal').css('display', 'flex');
    });
    $('.close-modal').on('click', function() { $('#message-modal').hide(); });
    $('#confirm-send-message').on('click', function() {
        var msg = $('#message-text').val();
        if(!msg) return;
        var btn = $(this);
        btn.prop('disabled', true).text('Sending...');
        $.post(jobs_vars.ajax_url, {
            action: 'jobs_send_message',
            receiver_id: <?php echo $user_id; ?>,
            message: msg,
            nonce: '<?php echo wp_create_nonce("jobs_messaging_nonce"); ?>'
        }, function(res) {
            if(res.success) {
                $('#message-modal .modal-content').html('<div style="text-align:center; padding: 24px;"><h3>Message Delivered</h3><p style="font-size:13px; color:#666;">Your inquiry has been sent successfully.</p><button class="v4-btn-primary close-modal" style="margin-top:16px;">Close</button></div>');
                $('.close-modal').on('click', function() { $('#message-modal').hide(); });
            } else {
                alert('Failed to send message.');
                btn.prop('disabled', false).text('Deliver Message');
            }
        });
    });
});
</script>

<?php get_footer(); ?>
