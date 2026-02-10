<?php
/**
 * Template: SaaS Enterprise Professional Job Details (V6)
 * Significantly improved structure, spacing, and proportional sizing.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
    $post_id = get_the_ID();
    $company_name = get_post_meta( $post_id, '_company_name', true );
    $company_logo = get_post_meta( $post_id, '_company_logo', true );

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
    $vacancies = get_post_meta($post_id, '_job_vacancies', true);
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

<div class="jobs-premium-job-page">
    <div class="job-page-container">

        <!-- HERO HEADER -->
        <header class="job-header-premium">
            <div class="job-logo-box">
                <?php if($company_logo): ?>
                    <img src="<?php echo esc_url($company_logo); ?>" alt="<?php echo esc_attr($company_name); ?>">
                <?php else: ?>
                    <div class="logo-placeholder"><span class="dashicons dashicons-businesswoman" style="font-size: 40px; width: 40px; height: 40px;"></span></div>
                <?php endif; ?>
            </div>

            <div class="job-header-main">
                <div class="job-header-meta">
                    <span class="v4-pastel-pill pill-blue"><?php echo esc_html($emp_type ?: 'Full Time'); ?></span>
                    <?php if(!empty($specializations)): ?>
                        <span class="v4-pastel-pill pill-purple"><?php echo esc_html($specializations[0]->name); ?></span>
                    <?php endif; ?>
                    <span style="color: #94a3b8; font-size: 13px; font-weight: 600; margin-left: auto;">
                        <span class="dashicons dashicons-calendar-alt" style="font-size: 14px; vertical-align: middle; margin-right: 5px;"></span>
                        Posted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> ago
                    </span>
                </div>

                <h1 class="job-title-v5"><?php the_title(); ?></h1>
                <a href="<?php echo esc_url($company_profile_url); ?>" class="company-anchor">
                    at <?php echo esc_html($company_name); ?>
                    <span class="badge-verified-circle" style="position: static; margin-left: 5px; width: 20px; height: 20px; border-width: 1.5px;"><span class="dashicons dashicons-yes" style="font-size: 14px; width: 14px; height: 14px;"></span></span>
                </a>
            </div>

            <div class="job-header-actions">
                <button class="apply-button-hero quick-apply-toggle" data-job-id="<?php echo $post_id; ?>">
                    <span class="dashicons dashicons-paper-plane"></span> Apply for Position
                </button>
                <?php if($deadline): ?>
                    <div style="margin-top: 18px; color: #ef4444; font-weight: 700; font-size: 14px;">
                        <span class="dashicons dashicons-warning" style="font-size: 16px; vertical-align: middle; margin-right: 5px;"></span>
                        Application Deadline: <?php echo date('M d, Y', strtotime($deadline)); ?>
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <div class="job-body-grid">

            <!-- MAIN CONTENT -->
            <div class="job-main-column">
                <div class="job-content-card">

                    <section class="job-details-section">
                        <h3 class="job-section-title"><span class="dashicons dashicons-text-page"></span> Role Description</h3>
                        <div class="job-text-content">
                            <?php the_content(); ?>
                        </div>
                    </section>

                    <?php if($responsibilities): ?>
                    <section class="job-details-section">
                        <h3 class="job-section-title"><span class="dashicons dashicons-list-view"></span> Key Responsibilities</h3>
                        <div class="job-text-content">
                            <?php echo wpautop(esc_html($responsibilities)); ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <?php if($qualifications): ?>
                    <section class="job-details-section">
                        <h3 class="job-section-title"><span class="dashicons dashicons-id"></span> Ideal Candidate Profile</h3>
                        <div class="job-text-content">
                            <?php echo wpautop(esc_html($qualifications)); ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <?php if($benefits): ?>
                    <section class="job-details-section">
                        <h3 class="job-section-title"><span class="dashicons dashicons-heart"></span> Perks & Benefits</h3>
                        <div class="job-text-content">
                            <?php echo wpautop(esc_html($benefits)); ?>
                        </div>
                    </section>
                    <?php endif; ?>

                </div>

                <div class="job-share-v5">
                    <span style="font-weight: 700; color: #1d3469; font-size: 16px;">Recommend this opportunity to your network:</span>
                    <div class="share-links-v5">
                        <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . '%20' . $share_url; ?>" target="_blank" class="v5-share-btn share-wa" title="Share via WhatsApp"><span class="dashicons dashicons-whatsapp"></span></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="v5-share-btn share-fb" title="Share via Facebook"><span class="dashicons dashicons-facebook"></span></a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" class="v5-share-btn share-tw" title="Share via X"><span class="dashicons dashicons-twitter"></span></a>
                    </div>
                </div>
            </div>

            <!-- SIDEBAR -->
            <aside class="job-sidebar-v5">

                <div class="sidebar-v5-card">
                    <h4 class="sidebar-v5-title">Job Insights</h4>
                    <div class="overview-list">
                        <div class="overview-item">
                            <div class="overview-icon"><span class="dashicons dashicons-location"></span></div>
                            <div>
                                <small class="overview-label">Location</small>
                                <span class="overview-value"><?php echo $countries ? esc_html($countries[0]->name) : 'International'; ?><?php echo $cities ? ', '.esc_html($cities[0]->name) : ''; ?></span>
                            </div>
                        </div>
                        <div class="overview-item">
                            <div class="overview-icon"><span class="dashicons dashicons-money"></span></div>
                            <div>
                                <small class="overview-label">Monthly Salary</small>
                                <span class="overview-value salary-value"><?php echo $salary ? $currency_sym . ' ' . $salary : 'Competitive Package'; ?></span>
                            </div>
                        </div>
                        <div class="overview-item">
                            <div class="overview-icon"><span class="dashicons dashicons-awards"></span></div>
                            <div>
                                <small class="overview-label">Experience</small>
                                <span class="overview-value"><?php echo esc_html($exp_level ?: 'Open to all levels'); ?></span>
                            </div>
                        </div>
                        <div class="overview-item">
                            <div class="overview-icon"><span class="dashicons dashicons-admin-site"></span></div>
                            <div>
                                <small class="overview-label">Work Setting</small>
                                <span class="overview-value"><?php echo esc_html($work_setting ?: 'On-site'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if($skills): ?>
                <div class="sidebar-v5-card">
                    <h4 class="sidebar-v5-title">Required Competencies</h4>
                    <div class="v4-tag-container">
                        <?php
                        $s_arr = explode(',', $skills);
                        foreach($s_arr as $s): if(trim($s)): ?>
                            <span class="v4-pastel-pill"><?php echo trim($s); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="sidebar-v5-card" style="background: #1d3469; color: #FFFFFF; text-align: center; border: none; overflow: hidden; position: relative;">
                    <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                    <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 1px solid rgba(255,255,255,0.2); position: relative; z-index: 1;">
                        <span class="dashicons dashicons-building" style="font-size: 32px; width: 32px; height: 32px;"></span>
                    </div>
                    <h4 style="margin: 0 0 10px; font-size: 13px; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.7); position: relative; z-index: 1;">Employer Spotlight</h4>
                    <p style="font-weight: 800; font-size: 22px; margin-bottom: 30px; line-height: 1.2; position: relative; z-index: 1;"><?php echo esc_html($company_name); ?></p>
                    <a href="<?php echo esc_url($company_profile_url); ?>" style="display: block; background: #FFFFFF; color: #1d3469; padding: 18px; border-radius: 16px; font-weight: 800; text-decoration: none; transition: transform 0.2s; position: relative; z-index: 1;">View Organization Profile</a>
                </div>

            </aside>

        </div>
    </div>
</div>

<?php endwhile; endif;

get_footer();
?>
