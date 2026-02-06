<?php
/**
 * Job Card Template
 */
$specializations = get_the_terms( get_the_ID(), 'specialization' );
$countries       = get_the_terms( get_the_ID(), 'country' );
$cities          = get_the_terms( get_the_ID(), 'city' );
$states          = get_the_terms( get_the_ID(), 'state' );
$categories      = get_the_terms( get_the_ID(), 'job_category' );
?>
<div class="job-card">
    <h3 class="job-title"><?php the_title(); ?></h3>
    <p class="company-name"><?php echo esc_html( get_post_meta( get_the_ID(), '_company_name', true ) ); ?></p>

    <div class="job-meta">
        <?php if ( $categories ) : foreach ( $categories as $term ) : ?>
            <span class="capsule capsule-category"><?php echo esc_html( $term->name ); ?></span>
        <?php endforeach; endif; ?>

        <?php if ( $specializations ) : foreach ( $specializations as $term ) : ?>
            <span class="capsule capsule-specialization"><?php echo esc_html( $term->name ); ?></span>
        <?php endforeach; endif; ?>

        <?php if ( $countries ) : foreach ( $countries as $term ) : ?>
            <span class="capsule capsule-location"><?php echo esc_html( $term->name ); ?></span>
        <?php endforeach; endif; ?>

        <?php if ( $states ) : foreach ( $states as $term ) : ?>
            <span class="capsule capsule-location"><?php echo esc_html( $term->name ); ?></span>
        <?php endforeach; endif; ?>

        <?php if ( $cities ) : foreach ( $cities as $term ) : ?>
            <span class="capsule capsule-location"><?php echo esc_html( $term->name ); ?></span>
        <?php endforeach; endif; ?>
    </div>

    <div class="job-excerpt">
        <?php the_excerpt(); ?>
    </div>

    <div class="job-card-actions">
        <a href="<?php the_permalink(); ?>" class="view-job-btn">View Details</a>
        <button class="jobs-btn quick-apply-toggle" data-job-id="<?php the_ID(); ?>">Quick Apply</button>
    </div>

    <div class="quick-apply-form-container" id="quick-apply-<?php the_ID(); ?>" style="display:none; margin-top: 15px; padding: 15px; border-top: 1px solid rgba(29, 52, 105, 0.1);">
        <?php if ( is_user_logged_in() ) : ?>
            <form class="jobs-quick-apply-form">
                <?php wp_nonce_field( 'jobs_quick_apply', 'quick_apply_nonce' ); ?>
                <input type="hidden" name="job_id" value="<?php the_ID(); ?>">
                <div class="form-group">
                    <label>Cover Letter (Optional)</label>
                    <textarea name="cover_letter" style="width:100%; height: 60px;"></textarea>
                </div>
                <button type="button" class="jobs-btn submit-quick-apply">Submit Application</button>
            </form>
        <?php else : ?>
            <p>Please <a href="<?php echo get_permalink( get_page_by_path('login-registration') ); ?>">login</a> to apply.</p>
        <?php endif; ?>
    </div>
</div>

<script>
// This script is better in a global file, but for the sake of the card template:
if (typeof jobsCardInit === 'undefined') {
    window.jobsCardInit = true;
    jQuery(document).on('click', '.quick-apply-toggle', function() {
        var jobId = jQuery(this).data('job-id');
        jQuery('#quick-apply-' + jobId).slideToggle();
    });

    jQuery(document).on('click', '.submit-quick-apply', function() {
        var form = jQuery(this).closest('form');
        var container = form.closest('.quick-apply-form-container');
        var data = form.serialize() + '&action=jobs_quick_apply';

        jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
            if(response.success) {
                container.html('<p style="color: green;">Application submitted successfully!</p>');
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "JobPosting",
  "title": "<?php the_title(); ?>",
  "description": "<?php echo wp_strip_all_tags( get_the_content() ); ?>",
  "datePosted": "<?php echo get_the_date('c'); ?>",
  "hiringOrganization": {
    "@type": "Organization",
    "name": "<?php echo esc_attr( get_post_meta( get_the_ID(), '_company_name', true ) ); ?>"
  },
  "jobLocation": {
    "@type": "Place",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "<?php echo ($cities) ? $cities[0]->name : ''; ?>",
      "addressRegion": "<?php echo ($states) ? $states[0]->name : ''; ?>",
      "addressCountry": "<?php echo ($countries) ? $countries[0]->name : ''; ?>"
    }
  }
}
</script>
