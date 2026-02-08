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
    <div class="job-card-header">
        <div class="job-company-logo-frame">
            <?php if ( $company_logo ) : ?>
                <img src="<?php echo esc_url( $company_logo ); ?>" alt="Logo">
            <?php else : ?>
                <div class="logo-placeholder"><span class="dashicons dashicons-building"></span></div>
            <?php endif; ?>
        </div>
        <div class="job-title-area">
            <div class="job-title-flex">
                <h3 class="job-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php if ( is_user_logged_in() ) :
                    $user_id = get_current_user_id();
                    $favorites = get_user_meta( $user_id, 'jobs_favorites', true ) ?: array();
                    $fav_ids = is_numeric(array_keys($favorites)[0] ?? 0) ? $favorites : array_keys($favorites);
                    $is_fav = in_array( get_the_ID(), $fav_ids );
                ?>
                    <span class="jobs-favorite-toggle dashicons dashicons-heart <?php echo $is_fav ? 'active' : ''; ?>" data-job-id="<?php the_ID(); ?>" title="Favorite"></span>
                <?php endif; ?>
            </div>
            <p class="company-info-line">
                <span class="c-name"><?php echo esc_html( get_post_meta( get_the_ID(), '_company_name', true ) ); ?></span>
            </p>
        </div>
    </div>

    <div class="job-meta">
        <?php if ( $countries ) : ?>
            <span class="capsule capsule-location"><?php echo esc_html( $countries[0]->name ); ?></span>
        <?php endif; ?>
        <?php if ( $salary ) : ?>
            <span class="capsule capsule-salary"><?php echo esc_html( $currency . ' ' . $salary ); ?></span>
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
            <button class="jobs-btn-minimal card-quick-apply-btn" data-id="<?php the_ID(); ?>">Apply</button>
            <a href="<?php the_permalink(); ?>" class="jobs-btn-minimal">Details</a>
        </div>
    </div>

    <!-- Professional Expandable Application Panel -->
    <div class="card-apply-dropdown" id="apply-dropdown-<?php the_ID(); ?>" style="display:none;">
        <div class="dropdown-arrow"></div>
        <div class="apply-panel-inner">
            <h4 style="margin: 0 0 10px 0; color: #1d3469; font-size: 1em;">Apply for this Position</h4>
            <p style="font-size: 0.85em; margin-bottom: 20px; color: #64748b; line-height: 1.5;">You are about to submit your professional profile to <strong><?php echo esc_html( get_post_meta( get_the_ID(), '_company_name', true ) ); ?></strong>.</p>

            <?php if ( is_user_logged_in() ) : ?>
                <button class="jobs-btn-small quick-apply-toggle" data-job-id="<?php the_ID(); ?>" style="width: 100%; padding: 12px; border-radius: 10px; background: #1d3469; font-weight: 600;">Confirm and Send Application</button>
            <?php else : ?>
                <a href="<?php echo home_url('/login/'); ?>" class="jobs-btn-small" style="display: block; width: 100%; text-align: center; padding: 12px; border-radius: 10px; background: #1d3469; text-decoration: none; color: white; font-weight: 600;">Login to Apply</a>
            <?php endif; ?>
        </div>
    </div>
</div>
