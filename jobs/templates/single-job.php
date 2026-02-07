<?php
/**
 * Template for displaying single job listings
 */
get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
    $company_name = get_post_meta( get_the_ID(), '_company_name', true );
    $company_logo = get_post_meta( get_the_ID(), '_company_logo', true );
    $countries = get_the_terms( get_the_ID(), 'country' );
    $cities = get_the_terms( get_the_ID(), 'city' );
    $specializations = get_the_terms( get_the_ID(), 'specialization' );
?>

<div class="jobs-single-container jobs-transparent-bg" style="max-width: 1100px; margin: 60px auto; display: grid; grid-template-columns: 2fr 1fr; gap: 40px; padding: 0 20px;">

    <main class="job-main-content">
        <div class="job-hero-card" style="background: white; padding: 40px; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 30px;">
            <div style="display: flex; gap: 25px; align-items: flex-start; margin-bottom: 30px;">
                <div class="single-logo-box" style="width: 80px; height: 80px; border-radius: 16px; border: 1px solid #f1f5f9; padding: 5px; background: #fff; flex-shrink: 0;">
                    <?php if ( $company_logo ) : ?>
                        <img src="<?php echo esc_url( $company_logo ); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    <?php else : ?>
                        <div style="width: 100%; height: 100%; background: #f8fafc; display: flex; align-items: center; justify-content: center; color: #cbd5e1;"><span class="dashicons dashicons-building" style="font-size: 32px; width: 32px; height: 32px;"></span></div>
                    <?php endif; ?>
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 2.2em; color: var(--jobs-primary-color); line-height: 1.2;"><?php the_title(); ?></h1>
                    <p style="margin: 8px 0 0 0; font-size: 1.1em; color: #64748b; font-weight: 500;"><?php echo esc_html( $company_name ); ?></p>
                </div>
            </div>

            <div class="job-tags-row" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 40px;">
                <?php if ( $specializations ) : ?>
                    <span class="capsule capsule-specialization" style="padding: 6px 15px; font-size: 0.8em;"><?php echo $specializations[0]->name; ?></span>
                <?php endif; ?>
                <?php if ( $countries ) : ?>
                    <span class="capsule capsule-location" style="padding: 6px 15px; font-size: 0.8em;"><span class="dashicons dashicons-location" style="font-size: 16px;"></span> <?php echo $countries[0]->name; ?></span>
                <?php endif; ?>
                <?php
                $salary = get_post_meta(get_the_ID(), '_job_salary', true);
                if ($salary) : ?>
                    <span class="capsule capsule-salary" style="padding: 6px 15px; font-size: 0.8em;"><?php echo get_post_meta(get_the_ID(), '_job_currency', true) ?: '$'; ?> <?php echo $salary; ?></span>
                <?php endif; ?>
            </div>

            <div class="job-description-body" style="line-height: 1.8; color: #334155; font-size: 1.05em;">
                <h3 style="margin-bottom: 20px; color: #1e293b;">Job Description</h3>
                <?php the_content(); ?>
            </div>
        </div>

        <?php
        require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-ads-service.php';
        Jobs_Ads_Service::display_ad( 'job_details' );
        ?>
    </main>

    <aside class="job-sidebar">
        <div class="sidebar-card apply-card" style="background: white; padding: 30px; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(29, 52, 105, 0.05); position: sticky; top: 100px;">
            <h4 style="margin: 0 0 20px 0; color: #1e293b;">Interested in this role?</h4>
            <button class="jobs-btn quick-apply-toggle" data-job-id="<?php the_ID(); ?>" style="width: 100%; padding: 16px; font-size: 1em; margin-bottom: 15px;">Quick Apply Now</button>

            <?php if ( is_user_logged_in() ) :
                $favorites = get_user_meta( get_current_user_id(), 'jobs_favorites', true ) ?: array();
                $is_fav = in_array( get_the_ID(), $favorites );
            ?>
                <button class="jobs-favorite-toggle jobs-btn-minimal <?php echo $is_fav ? 'active' : ''; ?>" data-job-id="<?php the_ID(); ?>" style="width: 100%; justify-content: center; display: flex; gap: 8px; align-items: center; border: 1px solid #e2e8f0; color: #64748b; background: #f8fafc; padding: 12px; border-radius: 10px;">
                    <span class="dashicons dashicons-heart"></span>
                    <?php echo $is_fav ? 'Saved to Favorites' : 'Save for Later'; ?>
                </button>
            <?php endif; ?>

            <div style="margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
                <h5 style="margin: 0 0 15px 0; color: #64748b; text-transform: uppercase; font-size: 0.75em; letter-spacing: 0.05em;">Job Information</h5>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9em; display: flex; flex-direction: column; gap: 12px;">
                    <li style="display: flex; justify-content: space-between;">
                        <span style="color: #94a3b8;">Posted</span>
                        <span style="color: #475569; font-weight: 500;"><?php echo get_the_date(); ?></span>
                    </li>
                    <li style="display: flex; justify-content: space-between;">
                        <span style="color: #94a3b8;">Location</span>
                        <span style="color: #475569; font-weight: 500;"><?php echo $countries ? $countries[0]->name : 'Remote'; ?></span>
                    </li>
                    <?php if ($cities) : ?>
                    <li style="display: flex; justify-content: space-between;">
                        <span style="color: #94a3b8;">City</span>
                        <span style="color: #475569; font-weight: 500;"><?php echo $cities[0]->name; ?></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </aside>
</div>

<?php endwhile; endif;

get_footer();
?>

<style>
@media (max-width: 991px) {
    .jobs-single-container {
        grid-template-columns: 1fr !important;
    }
    .job-sidebar {
        order: -1;
    }
    .apply-card {
        position: static !important;
    }
}
</style>
