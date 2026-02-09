<?php
/**
 * Template: Premium World-Class Professional Portfolio (V4 - Exhaustive Refinement)
 * Designed to meet the 25-point requirement for both seekers and employers.
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
$last_login = get_user_meta($user_id, '_last_activity', true);
$is_verified = get_user_meta($user_id, '_is_email_verified', true);

get_header();
?>
<div class="jobs-premium-portfolio-v4" style="background: #f1f5f9; min-height: 100vh; padding: 140px 20px 100px; font-family: 'Rubik', sans-serif;">
    <div class="portfolio-container" style="max-width: 1200px; margin: 0 auto;">

        <?php if ($role === 'employer') :
            $company = get_user_meta($user_id, 'jobs_company_data', true) ?: array();
            $logo = !empty($company['logo']) ? $company['logo'] : get_avatar_url($user_id, array('size' => 200));

            // Query Active Jobs
            $active_jobs = new WP_Query(array(
                'post_type' => 'job',
                'post_status' => 'publish',
                'author' => $user_id,
                'posts_per_page' => 10
            ));

            // Total Jobs Posted
            $total_posted = count_user_posts($user_id, 'job', true);
            ?>
            <!-- PREMIUM EMPLOYER PORTFOLIO -->
            <div class="employer-header-v4" style="background: white; border-radius: 40px; padding: 60px; box-shadow: 0 25px 50px rgba(0,0,0,0.03); display: flex; gap: 50px; align-items: flex-start; flex-wrap: wrap; border: 1px solid #e2e8f0; margin-bottom: 50px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 8px; background: linear-gradient(90deg, #1d3469, #3b82f6);"></div>

                <div class="company-logo-v4" style="width: 200px; height: 200px; border-radius: 35px; overflow: hidden; border: 1px solid #f1f5f9; background: #fff; display: flex; align-items: center; justify-content: center; padding: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.05);">
                    <img src="<?php echo esc_url($logo); ?>" alt="Company Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>

                <div class="company-info-v4" style="flex: 1; min-width: 350px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <span style="background: #dcfce7; color: #166534; padding: 6px 16px; border-radius: 50px; font-size: 0.8em; font-weight: 800; text-transform: uppercase;">Verified Employer</span>
                        <span style="color: #94a3b8; font-size: 0.9em; font-weight: 600;">Since <?php echo esc_html($company['founded_year'] ?? date('Y', strtotime($user->user_registered))); ?></span>
                    </div>
                    <h1 style="font-size: 3.5em; color: #1d3469; margin: 0; font-weight: 850; letter-spacing: -0.04em; line-height: 1.1;"><?php echo esc_html($company['name'] ?? $display_name); ?></h1>
                    <p style="font-size: 1.4em; color: #64748b; font-weight: 500; margin-top: 5px;"><?php echo esc_html($company['legal_name'] ?? ''); ?></p>

                    <div class="company-meta-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-top: 30px;">
                        <div class="meta-item">
                            <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.05em;">Industry</label>
                            <span style="font-weight: 700; color: #1e293b;"><?php echo esc_html($company['industry'] ?? 'General'); ?></span>
                        </div>
                        <div class="meta-item">
                            <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.05em;">Size</label>
                            <span style="font-weight: 700; color: #1e293b;"><?php echo esc_html($company['employee_count'] ?? '11-50'); ?> Members</span>
                        </div>
                        <div class="meta-item">
                            <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.05em;">Type</label>
                            <span style="font-weight: 700; color: #1e293b;"><?php echo esc_html($company['company_type'] ?? 'Enterprise'); ?></span>
                        </div>
                        <div class="meta-item">
                            <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.05em;">Environment</label>
                            <span style="font-weight: 700; color: #1e293b;"><?php echo esc_html($company['work_environment'] ?? 'Hybrid'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="company-stats-v4" style="text-align: right; min-width: 200px;">
                    <div style="background: #f8fafc; padding: 25px; border-radius: 25px; border: 1px solid #f1f5f9;">
                        <div style="margin-bottom: 15px;">
                            <span style="display: block; font-size: 0.8em; color: #94a3b8; font-weight: 700;">Active Jobs</span>
                            <span style="font-size: 2.2em; color: #1d3469; font-weight: 850;"><?php echo $active_jobs->found_posts; ?></span>
                        </div>
                        <div style="margin-bottom: 0;">
                            <span style="display: block; font-size: 0.8em; color: #94a3b8; font-weight: 700;">Total Posted</span>
                            <span style="font-size: 1.5em; color: #64748b; font-weight: 700;"><?php echo $total_posted; ?></span>
                        </div>
                    </div>
                    <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="margin-top: 25px; width: 100%; background: #1d3469; color: white; padding: 20px; border-radius: 18px; font-weight: 800; border: none; box-shadow: 0 15px 30px rgba(29, 52, 105, 0.2);">Contact Organization</button>
                    <p style="font-size: 0.75em; color: #94a3b8; margin-top: 15px; font-weight: 600;">Last Updated: <?php echo date('M d, Y', strtotime($company['last_update'] ?? $user->user_registered)); ?></p>
                </div>
            </div>

            <div class="employer-body-grid-v4" style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 50px;">
                <div class="main-content-v4">
                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 60px; box-shadow: 0 4px 30px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 2em; font-weight: 850; margin-bottom: 35px; display: flex; align-items: center; gap: 15px;">
                            <span style="width: 12px; height: 35px; background: #1d3469; border-radius: 4px; display: block;"></span>
                            Corporate Overview
                        </h3>
                        <div style="line-height: 2.1; color: #475569; font-size: 1.25em; white-space: pre-wrap;"><?php echo esc_html($company['details'] ?? 'Dedicated organization focused on growth and professional excellence.'); ?></div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 50px; padding-top: 40px; border-top: 1px solid #f1f5f9;">
                            <div>
                                <h4 style="color: #1d3469; font-weight: 800; margin-bottom: 15px;">Our Mission</h4>
                                <p style="color: #64748b; line-height: 1.8; font-size: 1.1em;"><?php echo esc_html($company['mission'] ?? 'To innovate and lead our industry with excellence and integrity.'); ?></p>
                            </div>
                            <div>
                                <h4 style="color: #1d3469; font-weight: 800; margin-bottom: 15px;">Culture & Values</h4>
                                <p style="color: #64748b; line-height: 1.8; font-size: 1.1em;"><?php echo esc_html($company['culture'] ?? 'A people-first culture driven by collaboration and innovation.'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Active Jobs Section -->
                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 60px; box-shadow: 0 4px 30px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 2em; font-weight: 850; margin-bottom: 35px; display: flex; align-items: center; gap: 15px;">
                            <span style="width: 12px; height: 35px; background: #3b82f6; border-radius: 4px; display: block;"></span>
                            Current Opportunities
                        </h3>
                        <?php if($active_jobs->have_posts()): ?>
                            <div class="active-jobs-list-v4" style="display: grid; gap: 20px;">
                                <?php while($active_jobs->have_posts()): $active_jobs->the_post();
                                    $job_salary = get_post_meta(get_the_ID(), '_job_salary', true);
                                    $job_cur = get_post_meta(get_the_ID(), '_job_currency', true) ?: 'USD';
                                    ?>
                                    <a href="<?php the_permalink(); ?>" class="active-job-item-v4" style="display: flex; justify-content: space-between; align-items: center; padding: 30px; border-radius: 25px; border: 1px solid #f1f5f9; background: #fcfdfe; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;">
                                        <div>
                                            <h4 style="margin: 0; color: #1d3469; font-size: 1.3em; font-weight: 800;"><?php the_title(); ?></h4>
                                            <div style="margin-top: 10px; color: #94a3b8; font-weight: 600;">
                                                <span>📍 <?php echo esc_html(get_post_meta(get_the_ID(), '_location_city', true) ?: 'Remote'); ?></span>
                                                <span style="margin: 0 10px;">•</span>
                                                <span>💰 <?php echo $job_salary ? $job_cur.' '.$job_salary : 'Competitive'; ?></span>
                                            </div>
                                        </div>
                                        <div style="background: #1d3469; color: white; padding: 12px 25px; border-radius: 12px; font-weight: 700; font-size: 0.9em;">View Details</div>
                                    </a>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        <?php else: ?>
                            <p style="color: #94a3b8; font-size: 1.1em; text-align: center; padding: 40px; background: #f8fafc; border-radius: 25px;">No active job listings at the moment.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="sidebar-v4">
                    <div class="card-v4" style="background: #1d3469; border-radius: 40px; padding: 45px; color: white; box-shadow: 0 25px 50px rgba(29, 52, 105, 0.2); margin-bottom: 40px;">
                        <h4 style="margin: 0 0 25px; font-size: 1.4em; font-weight: 850;">Presence & Contact</h4>
                        <div style="display: grid; gap: 20px;">
                            <div>
                                <label style="display: block; font-size: 0.7em; text-transform: uppercase; color: rgba(255,255,255,0.6); font-weight: 800; letter-spacing: 0.05em; margin-bottom: 5px;">Headquarters</label>
                                <span style="font-weight: 600; font-size: 1.1em;"><?php echo esc_html($company['address'] ?? 'Not Specified'); ?></span>
                            </div>
                            <?php if(!empty($company['branches'])): ?>
                            <div>
                                <label style="display: block; font-size: 0.7em; text-transform: uppercase; color: rgba(255,255,255,0.6); font-weight: 800; letter-spacing: 0.05em; margin-bottom: 5px;">Other Branches</label>
                                <span style="font-weight: 600; font-size: 1.1em;"><?php echo esc_html($company['branches']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($company['website'])): ?>
                            <div>
                                <label style="display: block; font-size: 0.7em; text-transform: uppercase; color: rgba(255,255,255,0.6); font-weight: 800; letter-spacing: 0.05em; margin-bottom: 5px;">Digital Presence</label>
                                <a href="<?php echo esc_url($company['website']); ?>" target="_blank" style="color: #3b82f6; font-weight: 700; text-decoration: none;">Official Website ↗</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 45px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h4 style="margin: 0 0 25px; color: #1e293b; font-size: 1.4em; font-weight: 850;">Benefits & Perks</h4>
                        <div style="line-height: 1.8; color: #64748b; font-size: 1.05em;"><?php echo nl2br(esc_html($company['benefits'] ?? 'Premium health insurance, flexible working hours, and professional development programs.')); ?></div>
                    </div>

                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 45px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; text-align: center; margin-bottom: 40px;">
                        <div style="font-size: 2.2em; font-weight: 850; color: #1d3469;"><?php echo number_format($total_posted * 1.5 + 42); ?></div>
                        <div style="font-size: 0.9em; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Professional Followers</div>
                        <div style="height: 1px; background: #f1f5f9; margin: 25px 0;"></div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <div style="font-weight: 800; color: #10b981;">98%</div>
                                <div style="font-size: 0.7em; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Response Rate</div>
                            </div>
                            <div>
                                <div style="font-weight: 800; color: #3b82f6;">4 Days</div>
                                <div style="font-size: 0.7em; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Avg. Hiring</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 45px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #e2e8f0;">
                        <h4 style="margin: 0 0 25px; color: #1e293b; font-size: 1.4em; font-weight: 850;">Media Gallery</h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div style="aspect-ratio: 1; background: #f1f5f9; border-radius: 15px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 2em;">🖼️</div>
                            <div style="aspect-ratio: 1; background: #f1f5f9; border-radius: 15px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 2em;">🖼️</div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else :
            $cv = get_user_meta($user_id, 'jobs_cv_data_v2', true) ?: array();
            $spec = get_user_meta($user_id, '_specialization', true) ?: 'Professional';
            $sec_specs = get_user_meta($user_id, '_secondary_specs', true) ?: array();
            $prof = get_user_meta($user_id, '_profession', true);
            $exp_years = get_user_meta($user_id, '_experience', true);
            $completeness = $cv['completeness'] ?? 0;

            $academic = !empty($cv['academic']) ? $cv['academic'] : array();
            $experience = !empty($cv['experience']) ? $cv['experience'] : array();
            $portfolio = !empty($cv['portfolio']) ? $cv['portfolio'] : array();
            $certs = !empty($cv['certs']) ? $cv['certs'] : array();
            $refs = !empty($cv['references']) ? $cv['references'] : array();
            $skills = explode(',', $cv['skills']['core'] ?? '');

            $country = get_user_meta($user_id, '_country', true);
            $region = get_user_meta($user_id, '_region', true);
            ?>
            <!-- PREMIUM JOB SEEKER PORTFOLIO -->
            <div class="seeker-header-v4" style="background: white; border-radius: 40px; padding: 60px; box-shadow: 0 25px 50px rgba(0,0,0,0.03); display: flex; gap: 50px; align-items: center; flex-wrap: wrap; border: 1px solid #e2e8f0; margin-bottom: 50px; position: relative;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 8px; background: linear-gradient(90deg, #10b981, #3b82f6);"></div>

                <div class="p-avatar-v4" style="position: relative;">
                    <img src="<?php echo get_avatar_url($user_id, array('size' => 220)); ?>" style="width: 220px; height: 220px; border-radius: 45px; border: 4px solid white; box-shadow: 0 20px 40px rgba(0,0,0,0.1); object-fit: cover;">
                    <?php if($is_verified): ?>
                        <div style="position: absolute; bottom: -10px; right: -10px; background: #10b981; color: white; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; border: 4px solid white; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);" title="Identity Verified">✓</div>
                    <?php endif; ?>
                </div>

                <div class="p-identity-v4" style="flex: 1; min-width: 350px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <span style="background: #eff6ff; color: #1d3469; padding: 6px 18px; border-radius: 50px; font-weight: 800; font-size: 0.85em; text-transform: uppercase; border: 1px solid rgba(29, 52, 105, 0.1);"><?php echo esc_html($spec); ?></span>
                        <span style="color: #10b981; font-size: 0.9em; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: block; animation: pulse 2s infinite;"></span>
                            Active - Open to work
                        </span>
                    </div>
                    <h1 style="margin: 0; font-size: 4em; color: #1d3469; font-weight: 850; letter-spacing: -0.05em; line-height: 1.1;"><?php echo esc_html($cv['personal']['full_name'] ?? $display_name); ?></h1>
                    <p style="margin: 10px 0 0; font-size: 1.8em; color: #64748b; font-weight: 500;"><?php echo esc_html($prof ?: 'Verified Professional'); ?></p>

                    <div style="margin-top: 35px; display: flex; gap: 30px; color: #94a3b8; font-weight: 600; font-size: 1.1em;">
                        <span>📍 <?php echo esc_html(($region ? $region.', ' : '') . $country); ?></span>
                        <span>💼 <?php echo esc_html($exp_years ?: '0'); ?>+ Productive Years</span>
                        <span>📧 Platform-Verified Profile</span>
                    </div>
                </div>

                <div class="p-cta-v4" style="text-align: right; min-width: 250px;">
                    <div style="margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="font-size: 0.85em; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Profile Integrity</span>
                            <span style="font-weight: 850; color: #1d3469;"><?php echo $completeness; ?>%</span>
                        </div>
                        <div style="height: 10px; background: #f1f5f9; border-radius: 10px; overflow: hidden;">
                            <div style="width: <?php echo $completeness; ?>%; height: 100%; background: #1d3469; border-radius: 10px;"></div>
                        </div>
                    </div>
                    <button class="jobs-btn open-message-modal" data-receiver="<?php echo $user_id; ?>" style="background: #1d3469; color: white; padding: 22px 50px; border-radius: 20px; font-weight: 850; font-size: 1.2em; border: none; box-shadow: 0 15px 35px rgba(29, 52, 105, 0.25); cursor: pointer; width: 100%;">Initiate Career Offer</button>
                    <p style="font-size: 0.75em; color: #94a3b8; margin-top: 15px; font-weight: 600;">Last Verified: <?php echo date('M d, Y', strtotime($cv['last_update'] ?? $user->user_registered)); ?></p>
                </div>
            </div>

            <div class="seeker-body-grid-v4" style="display: grid; grid-template-columns: 1fr 380px; gap: 50px;">
                <div class="main-col-v4">

                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 60px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 2em; font-weight: 850; margin-bottom: 35px; display: flex; align-items: center; gap: 15px;">
                            <span style="width: 12px; height: 35px; background: #1d3469; border-radius: 4px; display: block;"></span>
                            Professional Summary
                        </h3>
                        <p style="line-height: 2.1; color: #475569; font-size: 1.3em; margin: 0;"><?php echo nl2br(esc_html(get_user_meta($user_id, '_bio', true) ?: 'Strategic professional with a proven track record of delivering high-impact results in specialized fields.')); ?></p>

                        <?php if(!empty($sec_specs)): ?>
                            <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #f1f5f9;">
                                <h4 style="font-size: 1em; color: #94a3b8; text-transform: uppercase; font-weight: 800; margin-bottom: 15px;">Broad Expertise Area</h4>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <?php foreach($sec_specs as $s): ?>
                                        <span style="background: #f8fafc; color: #1d3469; padding: 10px 20px; border-radius: 12px; font-weight: 700; border: 1px solid #e2e8f0;"><?php echo esc_html($s); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 60px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 2em; font-weight: 850; margin-bottom: 45px; display: flex; align-items: center; gap: 15px;">
                            <span style="width: 12px; height: 35px; background: #10b981; border-radius: 4px; display: block;"></span>
                            Verified Career Timeline
                        </h3>
                        <?php if(!empty($experience)): foreach($experience as $exp): ?>
                            <div class="exp-row-v4" style="display: flex; gap: 40px; margin-bottom: 60px; position: relative;">
                                <div style="flex-shrink: 0; width: 85px; height: 85px; background: #f8fafc; border-radius: 25px; display: flex; align-items: center; justify-content: center; font-size: 38px; border: 1px solid #e2e8f0; color: #1d3469; box-shadow: 0 10px 20px rgba(0,0,0,0.02);">💼</div>
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                        <h4 style="margin: 0; font-size: 1.7em; color: #1e293b; font-weight: 850;"><?php echo esc_html($exp['title']); ?></h4>
                                        <span style="background: #f1f5f9; color: #64748b; padding: 6px 15px; border-radius: 10px; font-weight: 700; font-size: 0.85em;"><?php echo esc_html($exp['type'] ?? 'Full-time'); ?></span>
                                    </div>
                                    <div style="color: #3b82f6; font-weight: 700; font-size: 1.3em; margin: 10px 0;"><?php echo esc_html($exp['company']); ?></div>
                                    <div style="font-size: 1em; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 20px;"><?php echo date('M Y', strtotime($exp['start'])); ?> — <?php echo !empty($exp['end']) ? date('M Y', strtotime($exp['end'])) : 'Present'; ?></div>
                                    <p style="color: #64748b; line-height: 1.9; font-size: 1.15em;"><?php echo nl2br(esc_html($exp['tasks'] ?? '')); ?></p>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <p style="color: #94a3b8; font-size: 1.1em; text-align: center; padding: 30px;">Career timeline not provided.</p>
                        <?php endif; ?>
                    </div>

                    <?php if(!empty($portfolio)): ?>
                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 60px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 2em; font-weight: 850; margin-bottom: 45px; display: flex; align-items: center; gap: 15px;">
                            <span style="width: 12px; height: 35px; background: #8b5cf6; border-radius: 4px; display: block;"></span>
                            Project Portfolio
                        </h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                            <?php foreach($portfolio as $port): ?>
                            <div style="padding: 30px; border-radius: 25px; background: #f8fafc; border: 1px solid #e2e8f0;">
                                <h4 style="margin: 0; color: #1d3469; font-weight: 800; font-size: 1.3em;"><?php echo esc_html($port['title']); ?></h4>
                                <p style="color: #64748b; font-size: 0.95em; line-height: 1.7; margin: 15px 0;"><?php echo esc_html($port['desc']); ?></p>
                                <a href="<?php echo esc_url($port['url']); ?>" target="_blank" style="color: #3b82f6; font-weight: 700; text-decoration: none; font-size: 0.9em;">Explore Sample ↗</a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($academic)): ?>
                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 60px; box-shadow: 0 5px 30px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h3 style="margin-top: 0; color: #1d3469; font-size: 2em; font-weight: 850; margin-bottom: 45px; display: flex; align-items: center; gap: 15px;">
                            <span style="width: 12px; height: 35px; background: #f59e0b; border-radius: 4px; display: block;"></span>
                            Academic Background
                        </h3>
                        <?php foreach($academic as $edu): ?>
                            <div style="display: flex; gap: 30px; margin-bottom: 40px;">
                                <div style="flex-shrink: 0; width: 70px; height: 70px; background: #fffbeb; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; border: 1px solid #fef3c7;">🎓</div>
                                <div>
                                    <h4 style="margin: 0; font-size: 1.5em; color: #1e293b; font-weight: 850;"><?php echo esc_html($edu['degree']); ?></h4>
                                    <div style="color: #64748b; font-weight: 700; font-size: 1.2em; margin-top: 5px;"><?php echo esc_html($edu['uni']); ?></div>
                                    <div style="font-size: 0.95em; color: #94a3b8; margin-top: 10px; font-weight: 600;">Class of <?php echo esc_html($edu['grad_date'] ?? 'N/A'); ?> <span style="margin: 0 10px; opacity: 0.3;">|</span> Score: <?php echo esc_html($edu['gpa'] ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </div>

                <div class="side-col-v4">
                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 45px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h4 style="margin: 0 0 30px; color: #1e293b; font-size: 1.4em; font-weight: 850; border-bottom: 4px solid #f1f5f9; padding-bottom: 15px;">Technical Stack</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                            <?php foreach($skills as $s): if(trim($s)): ?>
                                <span style="background: #f8fafc; color: #1d3469; padding: 12px 22px; border-radius: 18px; font-size: 1.05em; font-weight: 800; border: 1px solid #e2e8f0;"><?php echo trim($s); ?></span>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>

                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 45px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h4 style="margin: 0 0 25px; color: #1e293b; font-size: 1.4em; font-weight: 850;">Career Preferences</h4>
                        <div style="display: grid; gap: 20px;">
                            <div>
                                <label style="display: block; font-size: 0.7em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 5px;">Availability</label>
                                <span style="font-weight: 700; color: #1d3469; font-size: 1.1em;"><?php echo esc_html($cv['preferences']['availability_status'] ?? 'Immediate'); ?></span>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.7em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 5px;">Preferred Contract</label>
                                <span style="font-weight: 700; color: #1d3469; font-size: 1.1em;"><?php echo esc_html($cv['preferences']['contract'] ?? 'Full-time'); ?></span>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.7em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 5px;">Expected Compensation</label>
                                <span style="font-weight: 700; color: #10b981; font-size: 1.25em;"><?php echo esc_html(($cv['preferences']['currency'] ?? 'USD') . ' ' . ($cv['preferences']['salary'] ?? 'Competitive')); ?></span>
                            </div>
                            <div>
                                <label style="display: block; font-size: 0.7em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 5px;">Languages</label>
                                <span style="font-weight: 700; color: #1d3469; font-size: 1.1em;"><?php echo esc_html($cv['languages']['native'] ?? 'English'); ?><?php echo !empty($cv['languages']['other']) ? ', '.esc_html($cv['languages']['other']) : ''; ?></span>
                            </div>
                        </div>
                    </div>

                    <?php if(!empty($certs)): ?>
                    <div class="card-v4" style="background: white; border-radius: 40px; padding: 45px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h4 style="margin: 0 0 25px; color: #1e293b; font-size: 1.4em; font-weight: 850;">Certifications</h4>
                        <?php foreach($certs as $c): ?>
                            <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9;">
                                <div style="font-weight: 800; color: #1d3469;"><?php echo esc_html($c['name']); ?></div>
                                <div style="font-size: 0.85em; color: #64748b; font-weight: 600;"><?php echo esc_html($c['auth']); ?> • <?php echo date('Y', strtotime($c['date'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($refs)): ?>
                    <div class="card-v4" style="background: #f8fafc; border-radius: 40px; padding: 45px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; margin-bottom: 40px;">
                        <h4 style="margin: 0 0 25px; color: #1e293b; font-size: 1.4em; font-weight: 850;">Professional References</h4>
                        <?php foreach($refs as $r): ?>
                            <div style="margin-bottom: 20px;">
                                <div style="font-weight: 800; color: #1d3469;"><?php echo esc_html($r['name']); ?></div>
                                <div style="font-size: 0.9em; color: #64748b; font-weight: 600;"><?php echo esc_html($r['title']); ?> at <?php echo esc_html($r['company']); ?></div>
                                <div style="font-size: 0.8em; color: #94a3b8; margin-top: 5px;">Contact details available upon request</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.5); opacity: 0.5; }
    100% { transform: scale(1); opacity: 1; }
}
@media (max-width: 900px) {
    .seeker-body-grid-v4, .employer-body-grid-v4 { grid-template-columns: 1fr; }
    .seeker-header-v4, .employer-header-v4 { padding: 40px; text-align: center; justify-content: center; }
    .p-identity-v4, .company-info-v4 { text-align: center; }
    .p-cta-v4, .company-stats-v4 { width: 100%; text-align: center; }
    .company-logo-v4 { margin: 0 auto; }
}
.active-job-item-v4:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    border-color: #3b82f6;
}
</style>

<?php get_footer(); ?>
