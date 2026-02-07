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
        <?php
        $colors = array(
            array('bg' => '#e3f2fd', 'border' => '#90caf9', 'text' => '#1976d2'),
            array('bg' => '#f3e5f5', 'border' => '#ce93d8', 'text' => '#7b1fa2'),
            array('bg' => '#e8f5e9', 'border' => '#a5d6a7', 'text' => '#388e3c'),
            array('bg' => '#fff3e0', 'border' => '#ffcc80', 'text' => '#f57c00'),
            array('bg' => '#ffebee', 'border' => '#ef9a9a', 'text' => '#d32f2f')
        );
        $i = 0;
        if ( $draft_jobs->have_posts() ) : while ( $draft_jobs->have_posts() ) : $draft_jobs->the_post();
            $c = $colors[$i % count($colors)];
            $i++;
        ?>
            <div class="draft-item" style="padding: 15px; border: 1px solid <?php echo $c['border']; ?>; border-radius: 12px; margin-bottom: 10px; background: <?php echo $c['bg']; ?>;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <strong style="color: <?php echo $c['text']; ?>;"><?php the_title() ?: '(Untitled Draft)'; ?></strong>
                    <span style="font-size: 0.7em; background: <?php echo $c['border']; ?>; color: white; padding: 2px 8px; border-radius: 4px; font-weight: 600;">DRAFT</span>
                </div>
                <div style="margin-top: 10px;">
                    <button class="jobs-btn-small resume-draft-job" data-id="<?php the_ID(); ?>" style="background: <?php echo $c['text']; ?>; border: none; color: white; font-size: 0.75em; padding: 4px 10px;">Continue Editing</button>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata(); else : ?>
            <p style="text-align: center; color: #999; padding: 40px;">No drafts found.</p>
        <?php endif; ?>
    </div>
</div>
