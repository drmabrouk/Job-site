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

<div class="jobs-single-container jobs-transparent-bg">
    <div class="job-header-professional">
        <?php if ( $company_logo ) : ?>
            <img src="<?php echo esc_url( $company_logo ); ?>" class="single-company-logo">
        <?php endif; ?>
        <div class="job-header-text">
            <h1><?php the_title(); ?></h1>
            <p class="company-sub"><?php echo esc_html( $company_name ); ?></p>
        </div>
    </div>

    <div class="job-meta-bar">
        <?php if ( $countries ) : ?>
            <span class="capsule capsule-country"><?php echo $countries[0]->name; ?></span>
        <?php endif; ?>
        <?php if ( $cities ) : ?>
            <span class="capsule capsule-city"><?php echo $cities[0]->name; ?></span>
        <?php endif; ?>
        <?php if ( $specializations ) : ?>
            <span class="capsule capsule-specialization"><?php echo $specializations[0]->name; ?></span>
        <?php endif; ?>
    </div>

    <div class="job-description-content">
        <?php the_content(); ?>
    </div>

    <?php
    require_once JOBS_PLUGIN_DIR . 'includes/services/class-jobs-ads-service.php';
    Jobs_Ads_Service::display_ad( 'job_details' );
    ?>

    <div class="job-single-actions">
        <button class="jobs-btn quick-apply-toggle" data-job-id="<?php the_ID(); ?>">Apply for this Job</button>
    </div>
</div>

<?php endwhile; endif;

get_footer();
?>

<style>
.jobs-single-container {
    max-width: 900px;
    margin: 60px auto;
    padding: 40px;
    font-family: 'Rubik', sans-serif;
}
.job-header-professional {
    display: flex;
    gap: 30px;
    align-items: center;
    margin-bottom: 30px;
}
.single-company-logo {
    width: 100px;
    height: 100px;
    object-fit: contain;
}
.job-header-text h1 {
    font-size: 2.5em;
    color: var(--jobs-primary-color);
    margin: 0;
}
.company-sub {
    font-size: 1.2em;
    color: #666;
}
.job-meta-bar {
    margin-bottom: 40px;
    display: flex;
    gap: 15px;
}
.job-description-content {
    line-height: 1.8;
    color: #444;
    font-size: 1.1em;
}
.job-single-actions {
    margin-top: 50px;
    border-top: 1px solid #eee;
    padding-top: 30px;
}
</style>
