<?php
/**
 * Module: Job Listings History
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
$is_employer = in_array( 'employer', $current_user->roles );
$is_reviewer = in_array( 'reviewer', $current_user->roles ) || in_array( 'system_admin', $current_user->roles );

$args = array(
    'post_type'      => 'job',
    'posts_per_page' => -1,
);

if ( $is_employer && ! $is_reviewer ) {
    $args['author'] = $current_user->ID;
    $args['post_status'] = array( 'publish', 'pending', 'private', 'draft' );
} elseif ( $is_reviewer ) {
    $args['post_status'] = array( 'publish', 'pending', 'private', 'draft', 'trash' );
} else {
    echo '<p>Permission denied.</p>';
    return;
}

$jobs_query = new WP_Query( $args );
?>
<div class="jobs-module-content" id="jobs-history-module">
    <h3>Job Listings History</h3>

    <?php if ( $jobs_query->have_posts() ) : ?>
        <table class="jobs-table" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--jobs-primary-color);">
                    <th style="text-align:left; padding: 10px;">Title</th>
                    <th style="text-align:left; padding: 10px;">Status</th>
                    <th style="text-align:left; padding: 10px;">Date</th>
                    <th style="text-align:right; padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ( $jobs_query->have_posts() ) : $jobs_query->the_post();
                    $status = get_post_status();
                ?>
                    <tr style="border-bottom: 1px solid rgba(29, 52, 105, 0.1);">
                        <td style="padding: 10px;"><?php the_title(); ?></td>
                        <td style="padding: 10px;">
                            <span class="status-badge status-<?php echo $status; ?>" style="padding: 4px 8px; border-radius: 4px; font-size: 0.8em; background: rgba(0,0,0,0.05);">
                                <?php echo ucfirst($status); ?>
                            </span>
                        </td>
                        <td style="padding: 10px;"><?php echo get_the_date(); ?></td>
                        <td style="padding: 10px; text-align:right;">
                            <a href="<?php echo get_edit_post_link(); ?>" class="jobs-btn-small" style="text-decoration:none; font-size: 0.8em;">Edit</a>
                            <?php if ( $status !== 'trash' ) : ?>
                                <button class="jobs-btn-small btn-danger jobs-delete-job" data-id="<?php the_ID(); ?>" style="font-size: 0.8em;">Delete</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; wp_reset_postdata(); ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No job listings found.</p>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    $('.jobs-delete-job').on('click', function() {
        var id = $(this).data('id');
        if(!confirm('Are you sure you want to delete this job listing?')) return;

        $.post(jobs_vars.ajax_url, {
            action: 'jobs_delete_job',
            job_id: id,
            nonce: jobs_vars.nonce
        }, function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
});
</script>
