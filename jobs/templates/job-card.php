<?php
/**
 * Enhanced Job Card Template
 */
$specializations = get_the_terms( get_the_ID(), 'specialization' );
$countries       = get_the_terms( get_the_ID(), 'country' );
$cities          = get_the_terms( get_the_ID(), 'city' );
$categories      = get_the_terms( get_the_ID(), 'job_category' );
$company_logo    = get_post_meta( get_the_ID(), '_company_logo', true );
$salary          = get_post_meta( get_the_ID(), '_job_salary', true );
$currency        = get_post_meta( get_the_ID(), '_job_currency', true ) ?: '$';
$work_setting    = get_post_meta( get_the_ID(), '_job_work_setting', true );
$skills          = get_post_meta( get_the_ID(), '_job_skills', true );
$post_date       = get_the_date('M d');
$is_active       = get_post_status() === 'publish';
?>
<div class="job-card" id="job-card-<?php the_ID(); ?>">
    <?php if ( is_user_logged_in() ) :
        $user_id = get_current_user_id();
        $favorites = get_user_meta( $user_id, 'jobs_favorites', true ) ?: array();
        $fav_ids = ( ! empty( $favorites ) && array_values( $favorites ) === $favorites ) ? $favorites : array_keys( $favorites );
        $is_fav = in_array( get_the_ID(), $fav_ids );
    ?>
        <span class="jobs-favorite-toggle dashicons dashicons-heart <?php echo $is_fav ? 'active' : ''; ?>" data-job-id="<?php the_ID(); ?>" title="Favorite"></span>
    <?php endif; ?>

    <div class="job-card-header">
        <div class="job-company-logo-frame">
            <?php if ( $company_logo ) : ?>
                <img src="<?php echo esc_url( $company_logo ); ?>" alt="Logo">
            <?php else : ?>
                <div class="logo-placeholder"><span class="dashicons dashicons-building"></span></div>
            <?php endif; ?>
        </div>
        <div class="job-title-area">
            <h3 class="job-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="company-info-line">
                <span class="c-name"><?php echo esc_html( get_post_meta( get_the_ID(), '_company_name', true ) ); ?></span>
            </p>
        </div>
    </div>

    <div class="job-meta">
        <?php if ( $countries ) :
            $c_slug = $countries[0]->slug;
            $flag = Jobs_Data_Service::get_flag_url($c_slug);
            ?>
            <span class="capsule capsule-location">
                <?php if($flag): ?><img src="<?php echo $flag; ?>" style="width: 14px; height: 10px; margin-right: 5px; vertical-align: middle; border-radius: 1px;"><?php endif; ?>
                <?php echo esc_html( $countries[0]->name ); ?>
            </span>
        <?php endif; ?>
        <?php if ( $salary ) :
            $display_currency = ($currency === 'USD') ? '$' : $currency;
            ?>
            <span class="capsule capsule-salary"><?php echo esc_html( $display_currency . ' ' . $salary ); ?></span>
        <?php endif; ?>
        <?php
        $emp_type = get_post_meta(get_the_ID(), '_job_employment_type', true);
        if ( $emp_type ) : ?>
            <span class="capsule capsule-type"><?php echo esc_html( $emp_type ); ?></span>
        <?php endif; ?>
        <?php if ( $specializations ) : ?>
            <span class="capsule capsule-specialization"><?php echo esc_html( $specializations[0]->name ); ?></span>
        <?php endif; ?>
    </div>

    <div class="job-excerpt">
        <?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
    </div>

    <div class="job-card-footer">
        <div class="job-pub-date">
            <span class="dashicons dashicons-clock"></span> <?php echo get_the_date('M d, Y'); ?>
        </div>
        <div class="job-card-actions">
            <button class="jobs-btn-minimal quick-apply-toggle" data-job-id="<?php the_ID(); ?>">Apply</button>
            <a href="<?php the_permalink(); ?>" class="jobs-btn-minimal">Details</a>
        </div>
    </div>

</div>
