<?php
/**
 * Template for displaying single job listings - Professional Standard
 */
get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
    $post_id = get_the_ID();
    $company_name = get_post_meta( $post_id, '_company_name', true );
    $company_logo = get_post_meta( $post_id, '_company_logo', true );
    $countries = get_the_terms( $post_id, 'country' );
    $cities = get_the_terms( $post_id, 'city' );
    $specializations = get_the_terms( $post_id, 'specialization' );
    $salary = get_post_meta($post_id, '_job_salary', true);
    $currency = get_post_meta($post_id, '_job_currency', true) ?: '$';

    $qualifications = get_post_meta($post_id, '_job_qualifications', true);
    $responsibilities = get_post_meta($post_id, '_job_responsibilities', true);
    $deadline = get_post_meta($post_id, '_job_deadline', true);
    $vacancies = get_post_meta($post_id, '_job_vacancies', true);
    $benefits = get_post_meta($post_id, '_job_benefits', true);
    $contact = get_post_meta($post_id, '_job_contact_info', true);
    $work_setting = get_post_meta($post_id, '_job_work_setting', true);
    $exp_level = get_post_meta($post_id, '_job_experience_level', true);
    $emp_type = get_post_meta($post_id, '_job_employment_type', true);
    $skills = get_post_meta($post_id, '_job_skills', true);
    $is_active = get_post_status() === 'publish';

    $share_url = urlencode(get_permalink());
    $share_title = urlencode(get_the_title());
?>

<div class="jobs-single-wrapper jobs-transparent-bg">
    <div class="jobs-single-container">

        <main class="job-main-content">
            <div class="job-hero-section">
                <div class="job-status-banner <?php echo $is_active ? 'active' : 'archived'; ?>">
                    <?php echo $is_active ? 'Active Opportunity' : 'Archived Listing'; ?>
                </div>
                <div class="job-header-flex">
                    <div class="single-logo-box">
                        <img src="<?php echo esc_url( $company_logo ); ?>" alt="Logo">
                    </div>
                    <div class="job-title-area">
                        <h1><?php the_title(); ?></h1>
                        <p class="company-subname"><?php echo esc_html( $company_name ); ?></p>

                        <div class="job-meta-pills">
                            <?php if ( $work_setting ) : ?>
                                <span class="meta-pill pill-setting"><span class="dashicons dashicons-admin-home"></span> <?php echo $work_setting; ?></span>
                            <?php endif; ?>
                            <?php if ( $countries ) : ?>
                                <span class="meta-pill pill-loc"><span class="dashicons dashicons-location"></span> <?php echo $countries[0]->name; ?></span>
                            <?php endif; ?>
                            <?php if ($salary) : ?>
                                <span class="meta-pill pill-salary"><?php echo $currency; ?> <?php echo $salary; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="job-details-content-card">
                <div class="content-section">
                    <h3 class="section-title">Job Description</h3>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </div>

                <?php if ($responsibilities) : ?>
                <div class="content-section">
                    <h3 class="section-title">Key Responsibilities</h3>
                    <div class="entry-content"><?php echo wpautop(esc_html($responsibilities)); ?></div>
                </div>
                <?php endif; ?>

                <?php if ($qualifications) : ?>
                <div class="content-section">
                    <h3 class="section-title">Required Qualifications</h3>
                    <div class="entry-content"><?php echo wpautop(esc_html($qualifications)); ?></div>
                </div>
                <?php endif; ?>

                <?php if ($benefits) : ?>
                <div class="content-section">
                    <h3 class="section-title">Benefits & Perks</h3>
                    <div class="entry-content"><?php echo wpautop(esc_html($benefits)); ?></div>
                </div>
                <?php endif; ?>

                <?php if ($skills) : ?>
                <div class="content-section">
                    <h3 class="section-title">Required Skills</h3>
                    <div class="job-skills-tags">
                        <?php
                        $skills_array = explode(',', $skills);
                        foreach ( $skills_array as $skill ) : ?>
                            <span class="skill-tag"><?php echo esc_html( trim($skill) ); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="job-share-section" style="margin-top: 30px; background: white; padding: 25px; border-radius: 20px; border: 1px solid #e2e8f0;">
                <h4 style="margin: 0 0 15px 0; font-size: 1.1em; color: #1d3469;">Share this opportunity</h4>
                <div class="share-buttons" style="display: flex; gap: 10px;">
                    <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . '%20' . $share_url; ?>" target="_blank" class="share-btn whatsapp"><span class="dashicons dashicons-whatsapp"></span> WhatsApp</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="share-btn facebook"><span class="dashicons dashicons-facebook"></span> Facebook</a>
                    <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" class="share-btn twitter">X / Twitter</a>
                </div>
            </div>

            <?php
            require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-ads-service.php';
            Jobs_Ads_Service::display_ad( 'job_details' );
            ?>
        </main>

        <aside class="job-sidebar">
            <div class="sidebar-sticky-wrapper">
                <div class="apply-action-card">
                    <h4>Ready to Apply?</h4>
                    <p>Submit your professional profile for review by the hiring team.</p>
                    <button class="jobs-btn quick-apply-toggle" data-job-id="<?php echo $post_id; ?>">Apply for this Position</button>

                    <?php if ( is_user_logged_in() ) :
                        $favorites = get_user_meta( get_current_user_id(), 'jobs_favorites', true ) ?: array();
                        $is_fav = in_array( $post_id, $favorites );
                    ?>
                        <button class="jobs-favorite-toggle-btn <?php echo $is_fav ? 'active' : ''; ?>" data-job-id="<?php echo $post_id; ?>">
                            <span class="dashicons dashicons-heart"></span>
                            <?php echo $is_fav ? 'Saved to Favorites' : 'Save for Later'; ?>
                        </button>
                    <?php endif; ?>
                </div>

                <div class="job-info-sidebar-card">
                    <h5 class="sidebar-info-title">Job Overview</h5>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-label">Published</span>
                            <span class="info-value"><?php echo get_the_date(); ?></span>
                        </div>
                        <?php if ($deadline) : ?>
                        <div class="info-item">
                            <span class="info-label">Deadline</span>
                            <span class="info-value" style="color: #ef4444;"><?php echo date('M d, Y', strtotime($deadline)); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <span class="info-label">Experience</span>
                            <span class="info-value"><?php echo esc_html($exp_level ?: 'Not specified'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Job Type</span>
                            <span class="info-value"><?php echo esc_html($emp_type ?: 'Full-time'); ?></span>
                        </div>
                        <?php if ($vacancies) : ?>
                        <div class="info-item">
                            <span class="info-label">Vacancies</span>
                            <span class="info-value"><?php echo esc_html($vacancies); ?> Positions</span>
                        </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <span class="info-label">Location</span>
                            <span class="info-value"><?php echo $countries ? $countries[0]->name : 'Remote'; ?></span>
                        </div>
                    </div>
                </div>

                <div class="company-quick-card">
                    <h5 class="sidebar-info-title">Hiring Organization</h5>
                    <p style="font-weight: 700; color: #1d3469; margin-bottom: 5px;"><?php echo esc_html( $company_name ); ?></p>
                    <p style="font-size: 0.85em; color: #64748b; margin-bottom: 15px;"><?php echo esc_html($contact ?: 'Contact details hidden'); ?></p>
                    <?php
                    $company_profile_url = home_url('/profile/' . strtolower(str_replace(' ', '-', $company_name)));
                    ?>
                    <a href="<?php echo esc_url($company_profile_url); ?>" class="view-profile-link">Visit Company Page</a>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php endwhile; endif;

get_footer();
?>
