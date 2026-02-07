<?php
/**
 * Module: Drafts
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user_id = get_current_user_id();

// Query for draft jobs (for employers)
$draft_jobs = new WP_Query( array(
    'post_type'   => 'job',
    'post_status' => 'draft',
    'author'      => $current_user_id,
    'posts_per_page' => -1,
) );

// Query for draft applications (for seekers)
$draft_apps = new WP_Query( array(
    'post_type'   => 'application',
    'post_status' => 'draft',
    'author'      => $current_user_id,
    'posts_per_page' => -1,
) );
?>
<div class="jobs-module-content" id="jobs-drafts-module">
    <h3>Your Saved Drafts</h3>
    <p>Resume your work on partially completed forms.</p>

    <?php if ( $draft_jobs->have_posts() ) : ?>
        <section class="draft-section">
            <h4>Job Posting Drafts</h4>
            <div class="draft-list">
                <?php while ( $draft_jobs->have_posts() ) : $draft_jobs->the_post(); ?>
                    <div class="draft-item" style="border: 1px solid rgba(29, 52, 105, 0.1); padding: 15px; margin-bottom: 10px; border-radius: 8px; background: rgba(178, 226, 242, 0.1);">
                        <strong><?php the_title(); ?></strong>
                        <div style="margin-top: 10px;">
                            <button class="jobs-btn resume-draft-job" data-id="<?php the_ID(); ?>">Resume Editing</button>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( $draft_apps->have_posts() ) : ?>
        <section class="draft-section" style="margin-top: 30px;">
            <h4>Application Drafts</h4>
            <div class="draft-list">
                <?php while ( $draft_apps->have_posts() ) : $draft_apps->the_post();
                    $job_id = get_post_meta( get_the_ID(), '_job_id', true );
                ?>
                    <div class="draft-item" style="border: 1px solid rgba(29, 52, 105, 0.1); padding: 15px; margin-bottom: 10px; border-radius: 8px; background: rgba(255, 209, 220, 0.1);">
                        <strong>Application for: <?php echo get_the_title($job_id); ?></strong>
                        <div style="margin-top: 10px;">
                            <button class="jobs-btn resume-draft-app" data-id="<?php the_ID(); ?>">Complete Application</button>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( ! $draft_jobs->have_posts() && ! $draft_apps->have_posts() ) : ?>
        <p>No drafts found.</p>
    <?php endif; ?>
</div>
