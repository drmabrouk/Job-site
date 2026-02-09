<?php
/**
 * Template: Highly Professional Single Job Listing
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
    $post_id = get_the_ID();
    $company_name = get_post_meta( $post_id, '_company_name', true );
    $company_logo = get_post_meta( $post_id, '_company_logo', true );
    $countries = get_the_terms( $post_id, 'country' );
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

<div class="jobs-premium-details" style="background: #f8fafc; min-height: 100vh; padding: 120px 20px 80px; font-family: 'Rubik', sans-serif;">
    <div class="details-container" style="max-width: 1200px; margin: 0 auto;">

        <!-- Header / Hero -->
        <div class="job-hero-card" style="background: white; border-radius: 30px; padding: 50px; box-shadow: 0 15px 50px rgba(0,0,0,0.04); margin-bottom: 40px; display: flex; align-items: center; gap: 40px; flex-wrap: wrap; border: 1px solid #e2e8f0;">
            <div class="hero-logo-wrap" style="width: 140px; height: 140px; border-radius: 24px; overflow: hidden; border: 1px solid #f1f5f9; background: #fff; display: flex; align-items: center; justify-content: center; padding: 10px;">
                <img src="<?php echo esc_url($company_logo); ?>" alt="Company Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
            <div class="hero-text-wrap" style="flex: 1; min-width: 300px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <span style="background: #eff6ff; color: #1d3469; padding: 5px 14px; border-radius: 20px; font-size: 0.85em; font-weight: 700; text-transform: uppercase;"><?php echo esc_html($emp_type ?: 'Full Time'); ?></span>
                    <span style="color: #94a3b8; font-size: 0.9em; font-weight: 500;"><span class="dashicons dashicons-calendar-alt" style="font-size: 16px; margin-right: 5px;"></span> Posted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?> ago</span>
                </div>
                <h1 style="font-size: 2.8em; color: #1d3469; margin: 0; font-weight: 700; letter-spacing: -0.02em;"><?php the_title(); ?></h1>
                <p style="margin: 10px 0 0; font-size: 1.2em; color: #64748b;">at <a href="<?php echo esc_url($company_profile_url); ?>" style="color: #1d3469; font-weight: 600; text-decoration: none; border-bottom: 2px solid rgba(29, 52, 105, 0.1);"><?php echo esc_html($company_name); ?></a></p>
            </div>
            <div class="hero-cta">
                <button class="jobs-btn quick-apply-toggle" data-job-id="<?php echo $post_id; ?>" style="background: #1d3469; color: white; padding: 18px 45px; border-radius: 14px; font-weight: 700; font-size: 1.1em; border: none; cursor: pointer; box-shadow: 0 10px 20px rgba(29, 52, 105, 0.2);">Apply For This Job</button>
            </div>
        </div>

        <div class="details-grid" style="display: grid; grid-template-columns: 1fr 380px; gap: 40px;">

            <!-- Main Content Area -->
            <div class="details-main-col">

                <!-- Job Overview Cards -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
                    <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; text-align: center;">
                        <div style="font-size: 24px; margin-bottom: 10px;">📍</div>
                        <div style="font-size: 0.8em; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Location</div>
                        <div style="font-weight: 600; color: #1d3469; margin-top: 5px;"><?php echo $countries ? esc_html($countries[0]->name) : 'Remote'; ?></div>
                    </div>
                    <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; text-align: center;">
                        <div style="font-size: 24px; margin-bottom: 10px;">💰</div>
                        <div style="font-size: 0.8em; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Offered Salary</div>
                        <div style="font-weight: 600; color: #1d3469; margin-top: 5px;"><?php echo $salary ? $currency_sym . ' ' . $salary : 'Undisclosed'; ?></div>
                    </div>
                    <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; text-align: center;">
                        <div style="font-size: 24px; margin-bottom: 10px;">🛠️</div>
                        <div style="font-size: 0.8em; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Exp. Level</div>
                        <div style="font-weight: 600; color: #1d3469; margin-top: 5px;"><?php echo esc_html($exp_level ?: 'Not specified'); ?></div>
                    </div>
                </div>

                <div class="content-card" style="background: white; border-radius: 24px; padding: 45px; box-shadow: 0 4px 25px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
                    <section style="margin-bottom: 45px;">
                        <h3 style="font-size: 1.5em; color: #1d3469; margin-top: 0; margin-bottom: 20px;">Job Description</h3>
                        <div style="line-height: 1.8; color: #475569; font-size: 1.05em;"><?php the_content(); ?></div>
                    </section>

                    <?php if ($responsibilities) : ?>
                    <section style="margin-bottom: 45px;">
                        <h3 style="font-size: 1.5em; color: #1d3469; margin-bottom: 20px;">Key Responsibilities</h3>
                        <div style="line-height: 1.8; color: #475569; font-size: 1.05em;"><?php echo wpautop(esc_html($responsibilities)); ?></div>
                    </section>
                    <?php endif; ?>

                    <?php if ($qualifications) : ?>
                    <section style="margin-bottom: 45px;">
                        <h3 style="font-size: 1.5em; color: #1d3469; margin-bottom: 20px;">Requirements & Qualifications</h3>
                        <div style="line-height: 1.8; color: #475569; font-size: 1.05em;"><?php echo wpautop(esc_html($qualifications)); ?></div>
                    </section>
                    <?php endif; ?>

                    <?php if ($benefits) : ?>
                    <section>
                        <h3 style="font-size: 1.5em; color: #1d3469; margin-bottom: 20px;">Compensation & Benefits</h3>
                        <div style="line-height: 1.8; color: #475569; font-size: 1.05em;"><?php echo wpautop(esc_html($benefits)); ?></div>
                    </section>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 40px; display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 25px 40px; border-radius: 20px; border: 1px solid #f1f5f9;">
                    <span style="font-weight: 600; color: #1d3469;">Found this interesting? Share it:</span>
                    <div style="display: flex; gap: 15px;">
                        <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . '%20' . $share_url; ?>" target="_blank" style="width: 45px; height: 45px; border-radius: 50%; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none;"><span class="dashicons dashicons-whatsapp"></span></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" style="width: 45px; height: 45px; border-radius: 50%; background: #1877F2; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none;"><span class="dashicons dashicons-facebook"></span></a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $share_title; ?>&url=<?php echo $share_url; ?>" target="_blank" style="width: 45px; height: 45px; border-radius: 50%; background: #000; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none;"><strong>X</strong></a>
                    </div>
                </div>

            </div>

            <!-- Sidebar Area -->
            <div class="details-sidebar-col">

                <!-- Quick Info -->
                <div style="background: white; border-radius: 24px; padding: 35px; box-shadow: 0 4px 25px rgba(0,0,0,0.02); margin-bottom: 30px; border: 1px solid #f1f5f9;">
                    <h4 style="margin: 0 0 25px; color: #1d3469; font-size: 1.2em; border-bottom: 2px solid #f8fafc; padding-bottom: 15px;">Job Overview</h4>
                    <div style="display: grid; gap: 20px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8; font-weight: 500;">Job Type</span>
                            <span style="color: #1d3469; font-weight: 700;"><?php echo esc_html($emp_type ?: 'Full-time'); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8; font-weight: 500;">Work Setting</span>
                            <span style="color: #1d3469; font-weight: 700;"><?php echo esc_html($work_setting ?: 'On-site'); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8; font-weight: 500;">Vacancies</span>
                            <span style="color: #1d3469; font-weight: 700;"><?php echo esc_html($vacancies ?: '1'); ?> Position(s)</span>
                        </div>
                        <?php if($deadline): ?>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #94a3b8; font-weight: 500;">Deadline</span>
                            <span style="color: #ef4444; font-weight: 700;"><?php echo date('M d, Y', strtotime($deadline)); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Skills Needed -->
                <?php if($skills): ?>
                <div style="background: white; border-radius: 24px; padding: 35px; box-shadow: 0 4px 25px rgba(0,0,0,0.02); margin-bottom: 30px; border: 1px solid #f1f5f9;">
                    <h4 style="margin: 0 0 20px; color: #1d3469; font-size: 1.2em;">Required Skills</h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <?php
                        $s_arr = explode(',', $skills);
                        foreach($s_arr as $s): if(trim($s)): ?>
                            <span style="background: #f8fafc; color: #475569; padding: 8px 16px; border-radius: 10px; font-size: 0.85em; font-weight: 600; border: 1px solid #e2e8f0;"><?php echo trim($s); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Organization Info -->
                <div style="background: #1d3469; border-radius: 24px; padding: 40px; color: white; box-shadow: 0 20px 40px rgba(29, 52, 105, 0.2); text-align: center;">
                    <h4 style="margin: 0 0 15px; font-size: 1.3em;">About Hiring Co.</h4>
                    <p style="font-weight: 700; font-size: 1.4em; margin-bottom: 20px;"><?php echo esc_html($company_name); ?></p>
                    <a href="<?php echo esc_url($company_profile_url); ?>" style="display: inline-block; background: white; color: #1d3469; padding: 14px 30px; border-radius: 12px; font-weight: 700; text-decoration: none; transition: transform 0.2s;">View Organization Profile</a>
                </div>

            </div>

        </div>
    </div>
</div>

<?php endwhile; endif;

get_footer();
?>
