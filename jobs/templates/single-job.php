<?php
/**
 * Template for displaying single job listings - Professional Standard
 */
get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
    $company_name = get_post_meta( get_the_ID(), '_company_name', true );
    $company_logo = get_post_meta( get_the_ID(), '_company_logo', true );
    $countries = get_the_terms( get_the_ID(), 'country' );
    $cities = get_the_terms( get_the_ID(), 'city' );
    $specializations = get_the_terms( get_the_ID(), 'specialization' );
    $salary = get_post_meta(get_the_ID(), '_job_salary', true);
    $currency = get_post_meta(get_the_ID(), '_job_currency', true) ?: '$';
?>

<div class="jobs-single-wrapper jobs-transparent-bg">
    <div class="jobs-single-container">

        <main class="job-main-content">
            <div class="job-hero-section">
                <div class="job-header-flex">
                    <div class="single-logo-box">
                        <?php if ( $company_logo ) : ?>
                            <img src="<?php echo esc_url( $company_logo ); ?>" alt="<?php echo esc_attr($company_name); ?>">
                        <?php else : ?>
                            <div class="logo-placeholder"><span class="dashicons dashicons-building"></span></div>
                        <?php endif; ?>
                    </div>
                    <div class="job-title-area">
                        <h1><?php the_title(); ?></h1>
                        <p class="company-subname"><?php echo esc_html( $company_name ); ?></p>

                        <div class="job-meta-pills">
                            <?php if ( $specializations ) : ?>
                                <span class="meta-pill pill-spec"><?php echo $specializations[0]->name; ?></span>
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

                <?php
                // Potential sections for world-class standards
                $responsibilities = get_post_meta(get_the_ID(), '_job_responsibilities', true);
                if ($responsibilities) : ?>
                <div class="content-section">
                    <h3 class="section-title">Key Responsibilities</h3>
                    <div class="entry-content"><?php echo wpautop(esc_html($responsibilities)); ?></div>
                </div>
                <?php endif; ?>

                <?php
                $requirements = get_post_meta(get_the_ID(), '_job_requirements', true);
                if ($requirements) : ?>
                <div class="content-section">
                    <h3 class="section-title">Requirements</h3>
                    <div class="entry-content"><?php echo wpautop(esc_html($requirements)); ?></div>
                </div>
                <?php endif; ?>
            </div>

            <?php
            require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-ads-service.php';
            Jobs_Ads_Service::display_ad( 'job_details' );
            ?>
        </main>

        <aside class="job-sidebar">
            <div class="sidebar-sticky-wrapper">
                <div class="apply-action-card">
                    <h4>Interested in this role?</h4>
                    <p>Be among the first applicants to show your interest.</p>
                    <button class="jobs-btn quick-apply-toggle" data-job-id="<?php the_ID(); ?>">Apply for this Position</button>

                    <?php if ( is_user_logged_in() ) :
                        $favorites = get_user_meta( get_current_user_id(), 'jobs_favorites', true ) ?: array();
                        $is_fav = in_array( get_the_ID(), $favorites );
                    ?>
                        <button class="jobs-favorite-toggle-btn <?php echo $is_fav ? 'active' : ''; ?>" data-job-id="<?php the_ID(); ?>">
                            <span class="dashicons <?php echo $is_fav ? 'dashicons-heart' : 'dashicons-heart'; ?>"></span>
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
                        <div class="info-item">
                            <span class="info-label">Location</span>
                            <span class="info-value"><?php echo $countries ? $countries[0]->name : 'Remote'; ?></span>
                        </div>
                        <?php if ($cities) : ?>
                        <div class="info-item">
                            <span class="info-label">City</span>
                            <span class="info-value"><?php echo $cities[0]->name; ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <span class="info-label">Job Type</span>
                            <span class="info-value"><?php echo get_post_meta(get_the_ID(), '_job_category', true) ?: 'Full-time'; ?></span>
                        </div>
                    </div>
                </div>

                <div class="company-quick-card">
                    <h5 class="sidebar-info-title">About Company</h5>
                    <p><?php echo esc_html( $company_name ); ?></p>
                    <?php
                    $company_profile_url = home_url('/profile/' . strtolower(str_replace(' ', '-', $company_name)));
                    ?>
                    <a href="<?php echo esc_url($company_profile_url); ?>" class="view-profile-link">View Company Profile</a>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php endwhile; endif;

get_footer();
?>
