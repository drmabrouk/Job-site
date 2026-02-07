<?php
/**
 * Job Card Template
 */
$specializations = get_the_terms( get_the_ID(), 'specialization' );
$countries       = get_the_terms( get_the_ID(), 'country' );
$cities          = get_the_terms( get_the_ID(), 'city' );
$states          = get_the_terms( get_the_ID(), 'state' );
$categories      = get_the_terms( get_the_ID(), 'job_category' );
$company_logo    = get_post_meta( get_the_ID(), '_company_logo', true );
$salary          = get_post_meta( get_the_ID(), '_job_salary', true );
$currency        = get_post_meta( get_the_ID(), '_job_currency', true ) ?: '$';
?>
<div class="job-card">
    <div class="job-card-header">
        <div class="job-company-logo">
            <?php if ( $company_logo ) : ?>
                <img src="<?php echo esc_url( $company_logo ); ?>" alt="Company Logo">
            <?php else : ?>
                <div class="logo-placeholder"></div>
            <?php endif; ?>
        </div>
        <div class="job-title-area">
            <h3 class="job-title"><?php the_title(); ?></h3>
            <p class="company-name"><?php echo esc_html( get_post_meta( get_the_ID(), '_company_name', true ) ); ?></p>
        </div>
    </div>

    <div class="job-meta">
        <?php if ( $salary ) : ?>
            <span class="capsule capsule-salary"><?php echo esc_html( $currency . ' ' . $salary ); ?></span>
        <?php endif; ?>

        <?php if ( $specializations ) : foreach ( $specializations as $term ) : ?>
            <span class="capsule capsule-specialization"><?php echo esc_html( $term->name ); ?></span>
        <?php endforeach; endif; ?>

        <?php if ( $countries ) : foreach ( $countries as $term ) : ?>
            <span class="capsule capsule-country"><?php echo esc_html( $term->name ); ?></span>
        <?php endforeach; endif; ?>

        <?php if ( $cities ) : foreach ( $cities as $term ) : ?>
            <span class="capsule capsule-city"><?php echo esc_html( $term->name ); ?></span>
        <?php endforeach; endif; ?>
    </div>

    <div class="job-excerpt">
        <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
    </div>

    <div class="job-card-actions">
        <a href="<?php the_permalink(); ?>" class="jobs-btn-small">Details</a>
        <button class="jobs-btn-small quick-apply-toggle" data-job-id="<?php the_ID(); ?>">Apply</button>
    </div>
</div>
