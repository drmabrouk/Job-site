<?php
/**
 * Module: Job Requests
 * Handles Job Approvals for Reviewers and Applications Received for Employers.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
$is_reviewer = in_array( 'reviewer', $current_user->roles ) || in_array( 'system_admin', $current_user->roles );
$is_employer = in_array( 'employer', $current_user->roles );
?>
<div class="jobs-module-content" id="jobs-requests-module">
    <?php if ( $is_reviewer ) : ?>
        <section class="reviewer-section">
            <h3>Job Approval Queue</h3>
            <p>Review and approve new job listings before they go live.</p>
            <?php
            $pending_jobs = new WP_Query( array(
                'post_type'   => 'job',
                'post_status' => 'pending',
                'posts_per_page' => -1,
            ) );

            if ( $pending_jobs->have_posts() ) : ?>
                <div class="jobs-grid">
                    <?php while ( $pending_jobs->have_posts() ) : $pending_jobs->the_post(); ?>
                        <div class="job-review-card" style="border: 1px solid rgba(29, 52, 105, 0.1); padding: 20px; margin-bottom: 15px; border-radius: 12px;">
                            <h4><?php the_title(); ?></h4>
                            <p><strong>Employer:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_company_name', true ) ); ?></p>
                            <div class="job-details-preview" style="font-size: 0.9em; color: #666; margin: 10px 0;">
                                <?php the_excerpt(); ?>
                            </div>
                            <div style="display:flex; gap: 10px;">
                                <button class="jobs-btn approve-job-btn" data-job-id="<?php the_ID(); ?>">Approve & Publish</button>
                                <button class="jobs-btn btn-danger reject-job-btn" data-job-id="<?php the_ID(); ?>" style="background: #d67a74;">Reject</button>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            <?php else : ?>
                <p>Queue is empty. No jobs pending review.</p>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if ( $is_employer ) : ?>
        <?php if ( $is_reviewer ) echo '<hr style="margin: 40px 0;">'; ?>

        <section class="employer-section">
            <h3>Applications Received</h3>
            <p>Review candidates who have applied to your job listings.</p>
            <?php
            $employer_jobs = get_posts( array(
                'post_type' => 'job',
                'author' => $current_user->ID,
                'fields' => 'ids',
                'post_status' => array('publish', 'pending', 'private')
            ) );

            if ( ! empty( $employer_jobs ) ) {
                $apps = new WP_Query( array(
                    'post_type' => 'application',
                    'meta_query' => array(
                        array(
                            'key' => '_job_id',
                            'value' => $employer_jobs,
                            'compare' => 'IN'
                        )
                    ),
                    'posts_per_page' => -1
                ) );

                if ( $apps->have_posts() ) : ?>
                    <div class="applications-grid">
                        <?php while ( $apps->have_posts() ) : $apps->the_post();
                            $job_id = get_post_meta( get_the_ID(), '_job_id', true );
                            $applicant_id = get_the_author_meta('ID');
                            $cv_data = get_user_meta( $applicant_id, 'jobs_cv_data', true );
                        ?>
                            <div class="app-card" style="border: 1px solid rgba(29, 52, 105, 0.1); padding: 20px; margin-bottom: 15px; border-radius: 12px; background: rgba(29, 52, 105, 0.02);">
                                <div style="display:flex; justify-content: space-between; align-items: flex-start;">
                                    <div>
                                        <strong><?php echo get_the_author(); ?></strong>
                                        <div style="font-size: 0.85em; color: #666;">Applied for: <?php echo get_the_title($job_id); ?></div>
                                    </div>
                                    <span style="font-size: 0.8em; opacity: 0.6;"><?php echo get_the_date(); ?></span>
                                </div>
                                <div class="app-content" style="margin-top: 15px; font-size: 0.95em;">
                                    <p><strong>Cover Letter:</strong><br><?php the_content(); ?></p>
                                    <?php if ($cv_data) : ?>
                                        <div class="cv-preview-box" style="background: white; padding: 10px; border-radius: 6px; margin-top: 10px; border: 1px solid rgba(0,0,0,0.05);">
                                            <strong>Candidate Highlights:</strong>
                                            <p style="margin: 5px 0;">Skills: <?php echo esc_html($cv_data['skills'] ?? 'N/A'); ?></p>
                                            <a href="<?php echo jobs_get_profile_link($applicant_id); ?>" target="_blank" style="font-size: 0.9em; color: var(--jobs-primary-color);">View Full Profile</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                <?php else : ?>
                    <p>No applications received yet.</p>
                <?php endif;
            } else {
                echo '<p>You haven\'t posted any jobs yet. Start by posting a job to receive applications.</p>';
            }
            ?>
        </section>
    <?php endif; ?>

    <?php if ( in_array( 'job_seeker', $current_user->roles ) ) : ?>
        <section class="seeker-section">
            <h3>Direct Job Offers</h3>
            <p>View exclusive job offers and invitations sent directly to you by employers.</p>
            <div class="offers-placeholder" style="text-align: center; color: #999; padding: 60px 0;">
                <span class="dashicons dashicons-email-alt" style="font-size: 48px; width:48px; height:48px;"></span>
                <p>No direct offers at this time. Keep your profile updated to attract employers!</p>
            </div>
        </section>
    <?php endif; ?>
</div>
