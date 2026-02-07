<?php
/**
 * Module: Drafts (Modal)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();
$draft_jobs = new WP_Query( array(
    'post_type' => 'job',
    'post_status' => 'draft',
    'author' => $user_id
) );
?>
<div class="jobs-module-content">
    <h3>Work in Progress</h3>
    <p>Resume your unfinished job postings.</p>

    <div class="drafts-list" style="margin-top: 20px;">
        <?php if ( $draft_jobs->have_posts() ) : while ( $draft_jobs->have_posts() ) : $draft_jobs->the_post(); ?>
            <div class="draft-item" style="padding: 15px; border: 1px solid #b2e2f2; border-radius: 12px; margin-bottom: 10px; background: rgba(178, 226, 242, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <strong style="color: #4eb0d1;"><?php the_title() ?: '(Untitled Draft)'; ?></strong>
                    <span style="font-size: 0.7em; background: #b2e2f2; color: #4eb0d1; padding: 2px 8px; border-radius: 4px; font-weight: 600;">DRAFT</span>
                </div>
                <div style="margin-top: 10px;">
                    <button class="jobs-btn-small resume-draft-job" data-id="<?php the_ID(); ?>" style="background: #4eb0d1; border: none; font-size: 0.75em; padding: 4px 10px;">Continue Editing</button>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); else : ?>
            <p style="text-align: center; color: #999; padding: 40px;">No drafts found.</p>
        <?php endif; ?>
    </div>
</div>
