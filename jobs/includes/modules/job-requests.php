<?php
/**
 * Module: Job Requests (Review Queue for Reviewers / Application List for Employers)
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
        <h3>Pending Job Approvals</h3>
        <?php
        $pending_jobs = new WP_Query( array(
            'post_type'   => 'job',
            'post_status' => 'pending',
            'posts_per_page' => -1,
        ) );

        if ( $pending_jobs->have_posts() ) : ?>
            <table class="jobs-table" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(29, 52, 105, 0.1);">
                        <th style="text-align:left; padding: 10px;">Job Title</th>
                        <th style="text-align:left; padding: 10px;">Employer</th>
                        <th style="text-align:right; padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ( $pending_jobs->have_posts() ) : $pending_jobs->the_post(); ?>
                        <tr style="border-bottom: 1px solid rgba(29, 52, 105, 0.05);">
                            <td style="padding: 10px;"><?php the_title(); ?></td>
                            <td style="padding: 10px;"><?php echo esc_html( get_post_meta( get_the_ID(), '_company_name', true ) ); ?></td>
                            <td style="padding: 10px; text-align:right;">
                                <button class="jobs-btn approve-job-btn" data-job-id="<?php the_ID(); ?>">Approve</button>
                            </td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No jobs pending review.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ( $is_employer ) : ?>
        <hr style="margin: 30px 0; border: 0; border-top: 1px solid rgba(29, 52, 105, 0.1);">
        <h3>Applications Received</h3>
        <?php
        $applications = new WP_Query( array(
            'post_type'   => 'application',
            'posts_per_page' => -1,
            'author'      => $current_user->ID, // Wait, applications are created by job seekers.
            // I should filter by jobs owned by this employer.
        ) );
        // Actually, let's query applications where 'job_id' meta matches employer's jobs.
        // Simplified: query all applications and filter in loop or use meta_query if job_id is stored.

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
                )
            ) );

            if ( $apps->have_posts() ) : ?>
                <div class="applications-list">
                    <?php while ( $apps->have_posts() ) : $apps->the_post();
                        $job_id = get_post_meta( get_the_ID(), '_job_id', true );
                        $applicant_id = get_the_author_meta('ID');
                        $cv_data = get_user_meta( $applicant_id, 'jobs_cv_data', true );
                    ?>
                        <div class="app-item" style="border: 1px solid rgba(29, 52, 105, 0.1); padding: 15px; margin-bottom: 10px; border-radius: 8px;">
                            <strong><?php echo get_the_author(); ?></strong> applied for <em><?php echo get_the_title($job_id); ?></em>
                            <div class="app-details" style="margin-top: 10px; font-size: 0.9em;">
                                <p><strong>Cover Letter:</strong> <?php the_content(); ?></p>
                                <?php if ($cv_data) : ?>
                                    <p><strong>Education:</strong> <?php echo esc_html($cv_data['education'] ?? 'N/A'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            <?php else : ?>
                <p>No applications received yet.</p>
            <?php endif;
        } else {
            echo '<p>You haven\'t posted any jobs yet.</p>';
        }
        ?>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    $('.approve-job-btn').on('click', function() {
        var btn = $(this);
        var jobId = btn.data('job-id');

        if(!confirm('Approve this job?')) return;

        $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
            action: 'jobs_approve_job',
            job_id: jobId,
            nonce: '<?php echo wp_create_nonce("jobs_approve_nonce"); ?>'
        }, function(response) {
            if(response.success) {
                btn.closest('tr').fadeOut();
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
});
</script>
