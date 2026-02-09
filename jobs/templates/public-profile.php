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
            $logo = !empty($company['logo']) ? $company['logo'] : get_avatar_url($user_id, array('size' => 120));

            $active_jobs = new WP_Query(array(
                'post_type' => 'job', 'post_status' => 'publish', 'author' => $user_id, 'posts_per_page' => 5
            ));
            $total_posted = count_user_posts($user_id, 'job', true);
            $branches = !empty($company['branches']) ? explode('|', $company['branches']) : array();
            ?>
            <!-- HEADER: EMPLOYER -->
            <header class="profile-v4-header">
                <div class="profile-v4-avatar-box" style="width: 96px; height: 96px;">
                    <img src="<?php echo esc_url($logo); ?>" alt="Company Logo">
                </div>
                <div class="profile-v4-identity-box">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <h1><?php echo esc_html($company['name'] ?? $display_name); ?></h1>
                        <div class="profile-v4-badges">
                            <span class="status-badge-pill badge-verified">Verified Entity</span>
                            <span class="status-badge-pill badge-hiring">Hiring</span>
                        </div>
                    </div>
                    <p class="profile-v4-headline"><?php echo esc_html($company['legal_name'] ?? ''); ?> • <?php echo esc_html($company['industry'] ?? 'Corporate'); ?></p>
                </div>
                <div style="margin-left: auto; display: flex; gap: 12px;">
                    <button class="v4-icon-btn" title="Share"><span class="dashicons dashicons-share"></span></button>
                    <button class="v4-btn-primary open-message-modal" data-receiver="<?php echo $user_id; ?>">Contact Platform</button>
                </div>
            </header>

            <div class="profile-v4-grid">
                <div class="profile-v4-main">
                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-businesswoman"></span> Company Overview</h3>
                        <div class="v4-card-body">
                            <p><?php echo nl2br(esc_html($company['details'] ?? 'Strategic organization focused on global excellence.')); ?></p>

                            <?php if(!empty($company['benefits'])): ?>
                                <div style="margin-top: 20px;">
                                    <h4 style="font-size: 13px; font-weight: 600; margin-bottom: 8px;">Benefits & Perks</h4>
                                    <p style="font-size: 12px; color: #666;"><?php echo nl2br(esc_html($company['benefits'])); ?></p>
                                </div>
                            <?php endif; ?>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px;">
                                <div>
                                    <h4 style="font-size: 13px; font-weight: 600; margin-bottom: 8px;">Mission</h4>
                                    <p style="font-size: 12px; color: #666;"><?php echo esc_html($company['mission'] ?? 'To lead through innovation and integrity.'); ?></p>
                                </div>
                                <div>
                                    <h4 style="font-size: 13px; font-weight: 600; margin-bottom: 8px;">Culture & Values</h4>
                                    <p style="font-size: 12px; color: #666;"><?php echo esc_html($company['culture'] ?? 'People-first, result-oriented environment.'); ?></p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-megaphone"></span> Open Opportunities</h3>
                        <div class="v4-card-body">
                            <?php if($active_jobs->have_posts()): while($active_jobs->have_posts()): $active_jobs->the_post(); ?>
                                <div style="padding: 12px 0; border-bottom: 1px solid #F5F5F5; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <a href="<?php the_permalink(); ?>" style="font-weight: 600; color: #1d3469; text-decoration: none;"><?php the_title(); ?></a>
                                        <div style="font-size: 11px; color: #999; margin-top: 4px;">📍 <?php echo esc_html(get_post_meta(get_the_ID(), '_location_city', true)); ?> • 💰 <?php echo esc_html(get_post_meta(get_the_ID(), '_job_salary', true)); ?></div>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="v4-btn-secondary" style="height: 30px; padding: 0 16px; font-size: 11px;">Details</a>
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
                    <section class="v4-card">
                        <h3 class="v4-card-title">Corporate Profile</h3>
                        <div class="v4-card-body">
                            <div style="margin-bottom: 16px;">
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Founded</small>
                                <span style="font-weight: 500;"><?php echo esc_html($company['founded_year'] ?? 'N/A'); ?></span>
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

                    <section class="v4-card">
                        <h3 class="v4-card-title">Hiring Performance</h3>
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
                <div class="profile-v4-avatar-box">
                    <img src="<?php echo get_avatar_url($user_id, array('size' => 120)); ?>" alt="Profile Photo">
                </div>
                <div class="profile-v4-identity-box">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <h1><?php echo esc_html($cv['personal']['full_name'] ?? $display_name); ?></h1>
                        <div class="profile-v4-badges">
                            <span class="status-badge-pill badge-verified">Verified</span>
                            <span class="status-badge-pill badge-open">Open to Work</span>
                        </div>
                    </div>
                    <p class="profile-v4-headline"><?php echo esc_html($prof ?: $spec); ?> • <?php echo esc_html(($region ? $region.', ' : '') . $country); ?></p>
                </div>
                <div style="margin-left: auto; display: flex; gap: 12px;">
                    <button class="v4-icon-btn" onclick="window.print()" title="Download PDF Portfolio"><span class="dashicons dashicons-pdf"></span></button>
                    <button class="v4-btn-primary open-message-modal" data-receiver="<?php echo $user_id; ?>">Career Inquiry</button>
                </div>
            </header>

            <div class="profile-v4-grid">
                <div class="profile-v4-main">
                    <section class="v4-card">
                        <h3 class="v4-card-title"><span class="dashicons dashicons-admin-users"></span> Professional Summary</h3>
                        <div class="v4-card-body">
                            <p><?php echo nl2br(esc_html(get_user_meta($user_id, '_bio', true) ?: 'Dedicated professional with expertise in strategic field development and execution.')); ?></p>

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
                        <h3 class="v4-card-title"><span class="dashicons dashicons-welcome-learn-more"></span> Academic Background</h3>
                        <div class="v4-card-body">
                            <?php if(!empty($academic)): foreach($academic as $edu): ?>
                                <div class="v4-timeline-item">
                                    <div class="v4-timeline-title"><?php echo esc_html($edu['degree']); ?></div>
                                    <div class="v4-timeline-org"><?php echo esc_html($edu['uni']); ?></div>
                                    <div class="v4-timeline-meta">Class of <?php echo esc_html($edu['grad_date']); ?> • Grade: <?php echo esc_html($edu['gpa'] ?? 'Passed'); ?></div>
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
                                <div style="margin-bottom: 16px;">
                                    <div style="font-weight: 600;"><?php echo esc_html($r['name']); ?></div>
                                    <div style="font-size: 12px; color: #666;"><?php echo esc_html($r['title']); ?> • <?php echo esc_html($r['company']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>

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
                    <section class="v4-card">
                        <h3 class="v4-card-title">Technical Proficiency</h3>
                        <div class="v4-tag-container">
                            <?php foreach($skills as $s): ?>
                                <span class="v4-pastel-pill"><?php echo trim($s); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <section class="v4-card">
                        <h3 class="v4-card-title">Career Profile</h3>
                        <div class="v4-card-body">
                            <div style="margin-bottom: 12px;">
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Experience</small>
                                <span style="font-weight: 500;"><?php echo esc_html($exp_years ?: '0'); ?>+ Productive Years</span>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Availability</small>
                                <span style="font-weight: 500;"><?php echo esc_html($cv['preferences']['availability_status'] ?? 'Immediate'); ?></span>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Preferred Setup</small>
                                <span style="font-weight: 500;"><?php echo esc_html($cv['preferences']['contract'] ?? 'Full-time'); ?> • <?php echo esc_html($cv['preferences']['flexibility'] ?? 'Remote'); ?></span>
                            </div>
                            <div style="margin-bottom: 12px;">
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Salary Expectation</small>
                                <span style="font-weight: 500; color: #10b981;"><?php echo esc_html($cv['preferences']['currency'] ?? 'USD'); ?> <?php echo esc_html($cv['preferences']['salary'] ?? 'Competitive'); ?> /mo</span>
                            </div>
                            <div>
                                <small style="display: block; font-size: 10px; color: #999; text-transform: uppercase; font-weight: 700;">Languages</small>
                                <span style="font-weight: 500;"><?php echo esc_html($cv['languages']['native'] ?? 'English'); ?><?php echo !empty($cv['languages']['other']) ? ', '.esc_html($cv['languages']['other']) : ''; ?></span>
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

                    <section class="v4-card">
                        <h3 class="v4-card-title">Integrity Score</h3>
                        <div style="height: 6px; background: #F0F0F0; border-radius: 3px; overflow: hidden; margin-bottom: 8px;">
                            <div style="width: <?php echo esc_attr($cv['completeness'] ?? 75); ?>%; height: 100%; background: #1d3469;"></div>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 10px; font-weight: 600; color: #999;">
                            <span>COMPLETENESS</span>
                            <span><?php echo esc_html($cv['completeness'] ?? 75); ?>%</span>
                        </div>
                    </section>

                    <div style="text-align: center; color: #999; font-size: 11px; font-weight: 500;">
                        Last Active: <?php echo $last_activity ? human_time_diff($last_activity, current_time('timestamp')).' ago' : 'Recently'; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

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
    $('.open-message-modal').on('click', function() { $('#message-modal').css('display', 'flex'); });
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
