<?php
/**
 * Job Card Template
 */
$specializations = get_the_terms( get_the_ID(), 'specialization' );
$countries       = get_the_terms( get_the_ID(), 'country' );
$cities          = get_the_terms( get_the_ID(), 'city' );
$categories      = get_the_terms( get_the_ID(), 'job_category' );
?>
<div class="job-card">
    <h3 class="job-title"><?php the_title(); ?></h3>
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

        <?php if ( $cities ) : foreach ( $cities as $term ) : ?>
            <span class="capsule capsule-location"><?php echo esc_html( $term->name ); ?></span>
        <?php endforeach; endif; ?>
    </div>
    <div class="job-excerpt">
        <?php the_excerpt(); ?>
    </div>
    <a href="<?php the_permalink(); ?>" class="view-job-btn">View Details</a>
</div>
