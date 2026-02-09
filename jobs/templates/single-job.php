<?php
/**
 * Template: Premium World-Class Single Job Listing
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
    $post_id = get_the_ID();
    $company_name = get_post_meta( $post_id, '_company_name', true );
    $company_logo = get_post_meta( $post_id, '_company_logo', true );
    $countries = get_the_terms( $post_id, 'country' );
    $cities = get_the_terms( $post_id, 'city' );
    $specializations = get_the_terms( $post_id, 'specialization' );
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

<div class="jobs-premium-job-page" style="background: #f8fafc; min-height: 100vh; padding: 140px 20px 100px; font-family: 'Rubik', sans-serif;">
    <div class="job-page-container" style="max-width: 1100px; margin: 0 auto;">

        <!-- Header Section -->
        <header class="job-header-premium" style="background: white; border-radius: 32px; padding: 60px; box-shadow: 0 20px 50px rgba(0,0,0,0.04); margin-bottom: 50px; border: 1px solid #edf2f7;">
            <div style="display: flex; gap: 40px; align-items: flex-start; flex-wrap: wrap;">
                <div class="job-logo-box" style="width: 120px; height: 120px; border-radius: 28px; background: #fff; border: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: center; padding: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                    <img src="<?php echo esc_url($company_logo); ?>" alt="Company" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div style="flex: 1; min-width: 300px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <span style="background: #eff6ff; color: #1d3469; padding: 6px 16px; border-radius: 50px; font-size: 0.85em; font-weight: 700; text-transform: uppercase;"><?php echo esc_html($emp_type ?: 'Full Time'); ?></span>
                        <span style="color: #94a3b8; font-size: 0.9em; font-weight: 600;"><span class="dashicons dashicons-clock" style="font-size: 16px; vertical-align: middle;"></span> Posted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> ago</span>
                    </div>
                    <h1 style="font-size: 3em; color: #1d3469; margin: 0; font-weight: 800; letter-spacing: -0.04em; line-height: 1.1;"><?php the_title(); ?></h1>
                    <p style="margin: 15px 0 0; font-size: 1.3em; color: #64748b; font-weight: 500;">at <a href="<?php echo esc_url($company_profile_url); ?>" style="color: #1d3469; text-decoration: none; border-bottom: 2px solid rgba(29, 52, 105, 0.1); padding-bottom: 2px;"><?php echo esc_html($company_name); ?></a></p>
                </div>
                <div style="text-align: right; min-width: 250px;">
                    <button class="jobs-btn quick-apply-toggle" data-job-id="<?php echo $post_id; ?>" style="background: #1d3469; color: white; padding: 22px 50px; border-radius: 18px; font-weight: 700; font-size: 1.2em; width: 100%; border: none; box-shadow: 0 15px 30px rgba(29, 52, 105, 0.25); cursor: pointer; transition: transform 0.3s ease;">Apply Now</button>
                    <?php if($deadline): ?>
                        <p style="margin-top: 15px; color: #ef4444; font-weight: 700; font-size: 0.95em;">Deadline: <?php echo date('M d, Y', strtotime($deadline)); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <div class="job-body-grid" style="display: grid; grid-template-columns: 1fr 350px; gap: 50px;">

            <div class="job-main-col">
                <div class="card" style="background: white; border-radius: 32px; padding: 60px; box-shadow: 0 4px 30px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">

                    <section style="margin-bottom: 50px;">
                        <h3 style="font-size: 1.8em; color: #1d3469; margin-top: 0; margin-bottom: 25px; font-weight: 800; border-left: 6px solid #1d3469; padding-left: 20px;">Role Description</h3>
                        <div style="line-height: 2; color: #475569; font-size: 1.15em;"><?php the_content(); ?></div>
                    </section>

                    <?php if($responsibilities): ?>
                    <section style="margin-bottom: 50px;">
                        <h3 style="font-size: 1.8em; color: #1d3469; margin-bottom: 25px; font-weight: 800; border-left: 6px solid #1d3469; padding-left: 20px;">Key Responsibilities</h3>
                        <div style="line-height: 2; color: #475569; font-size: 1.15em;"><?php echo wpautop(esc_html($responsibilities)); ?></div>
                    </section>
                    <?php endif; ?>

                    <?php if($qualifications): ?>
                    <section style="margin-bottom: 50px;">
                        <h3 style="font-size: 1.8em; color: #1d3469; margin-bottom: 25px; font-weight: 800; border-left: 6px solid #1d3469; padding-left: 20px;">Requirements</h3>
                        <div style="line-height: 2; color: #475569; font-size: 1.15em;"><?php echo wpautop(esc_html($qualifications)); ?></div>
                    </section>
                    <?php endif; ?>

                    <?php if($benefits): ?>
                    <section>
                        <h3 style="font-size: 1.8em; color: #1d3469; margin-bottom: 25px; font-weight: 800; border-left: 6px solid #1d3469; padding-left: 20px;">Perks & Benefits</h3>
                        <div style="line-height: 2; color: #475569; font-size: 1.15em;"><?php echo wpautop(esc_html($benefits)); ?></div>
                    </section>
                    <?php endif; ?>

                </div>

                <div class="job-share-v2" style="margin-top: 40px; display: flex; justify-content: space-between; align-items: center; background: white; padding: 30px 50px; border-radius: 25px; border: 1px solid #edf2f7;">
                    <span style="font-weight: 700; color: #1d3469; font-size: 1.1em;">Share this role with your network:</span>
                    <div style="display: flex; gap: 15px;">
                        <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . '%20' . $share_url; ?>" target="_blank" style="width: 50px; height: 50px; border-radius: 15px; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;"><span class="dashicons dashicons-whatsapp" style="font-size: 24px; width: 24px; height: 24px;"></span></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" style="width: 50px; height: 50px; border-radius: 15px; background: #1877F2; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;"><span class="dashicons dashicons-facebook" style="font-size: 24px; width: 24px; height: 24px;"></span></a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" style="width: 50px; height: 50px; border-radius: 15px; background: #000; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.2s;"><strong>X</strong></a>
                    </div>
                </div>
            </div>

            <div class="job-side-col">
                <div class="card" style="background: white; border-radius: 32px; padding: 40px; box-shadow: 0 4px 30px rgba(0,0,0,0.02); border: 1px solid #edf2f7; margin-bottom: 30px;">
                    <h4 style="margin: 0 0 25px; color: #1d3469; font-size: 1.3em; font-weight: 800; border-bottom: 2px solid #f8fafc; padding-bottom: 15px;">Quick Overview</h4>
                    <div style="display: grid; gap: 25px;">
                        <div>
                            <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.1em; margin-bottom: 8px;">Location</label>
                            <div style="font-weight: 700; color: #1d3469; font-size: 1.1em;"><?php echo $countries ? esc_html($countries[0]->name) : 'Remote'; ?><?php echo $cities ? ', '.esc_html($cities[0]->name) : ''; ?></div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.1em; margin-bottom: 8px;">Offered Salary</label>
                            <div style="font-weight: 700; color: #10b981; font-size: 1.2em;"><?php echo $salary ? $currency_sym . ' ' . $salary : 'Undisclosed'; ?></div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.1em; margin-bottom: 8px;">Experience Required</label>
                            <div style="font-weight: 700; color: #1d3469;"><?php echo esc_html($exp_level ?: 'Not specified'); ?></div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75em; text-transform: uppercase; color: #94a3b8; font-weight: 800; letter-spacing: 0.1em; margin-bottom: 8px;">Work Setting</label>
                            <div style="font-weight: 700; color: #1d3469;"><?php echo esc_html($work_setting ?: 'On-site'); ?></div>
                        </div>
                    </div>
                </div>

                <?php if($skills): ?>
                <div class="card" style="background: white; border-radius: 32px; padding: 40px; box-shadow: 0 4px 30px rgba(0,0,0,0.02); border: 1px solid #edf2f7; margin-bottom: 30px;">
                    <h4 style="margin: 0 0 20px; color: #1d3469; font-size: 1.3em; font-weight: 800;">Top Skills Needed</h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <?php
                        $s_arr = explode(',', $skills);
                        foreach($s_arr as $s): if(trim($s)): ?>
                            <span style="background: #f8fafc; color: #1d3469; padding: 8px 16px; border-radius: 12px; font-size: 0.9em; font-weight: 700; border: 1px solid #edf2f7;"><?php echo trim($s); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card" style="background: #1d3469; border-radius: 35px; padding: 50px; color: white; box-shadow: 0 20px 50px rgba(29, 52, 105, 0.25); text-align: center;">
                    <h4 style="margin: 0 0 15px; font-size: 1.4em; font-weight: 800;">The Employer</h4>
                    <p style="font-weight: 700; font-size: 1.6em; margin-bottom: 30px; line-height: 1.2;"><?php echo esc_html($company_name); ?></p>
                    <a href="<?php echo esc_url($company_profile_url); ?>" style="display: inline-block; background: white; color: #1d3469; padding: 18px 40px; border-radius: 16px; font-weight: 800; text-decoration: none; transition: transform 0.2s;">View Company</a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.quick-apply-toggle:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 40px rgba(29, 52, 105, 0.35);
}
.job-share-v2 a:hover { transform: scale(1.1); }
@media (max-width: 900px) {
    .job-body-grid { grid-template-columns: 1fr; }
    .job-header-premium { padding: 40px; text-align: center; justify-content: center; }
    .job-logo-box { margin: 0 auto; }
    .hero-text-wrap { text-align: center; }
    .hero-cta { width: 100%; }
}
</style>

<?php endwhile; endif;

get_footer();
?>
