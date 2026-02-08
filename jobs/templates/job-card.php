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
    <div class="job-card-top-row">
        <?php if ( is_user_logged_in() ) :
            $user_id = get_current_user_id();
            $favorites = get_user_meta( $user_id, 'jobs_favorites', true ) ?: array();
            $fav_ids = is_numeric(array_keys($favorites)[0] ?? 0) ? $favorites : array_keys($favorites);
            $is_fav = in_array( get_the_ID(), $fav_ids );
        ?>
            <span class="jobs-favorite-toggle dashicons dashicons-archive <?php echo $is_fav ? 'active' : ''; ?>" data-job-id="<?php the_ID(); ?>" title="Save/Archive"></span>
        <?php endif; ?>
    </div>

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
                <span class="v-bar">|</span>
                <span class="p-date"><?php echo get_the_date('M d, Y'); ?></span>
            </p>
        </div>
    </div>

    <div class="job-meta">
        <?php if ( $work_setting ) : ?>
            <span class="capsule capsule-setting"><?php echo esc_html( $work_setting ); ?></span>
        <?php endif; ?>
        <?php if ( $salary ) : ?>
            <span class="capsule capsule-salary"><?php echo esc_html( $currency . ' ' . $salary ); ?></span>
        <?php endif; ?>
        <?php if ( $countries ) : ?>
            <span class="capsule capsule-location"><?php echo esc_html( $countries[0]->name ); ?></span>
        <?php endif; ?>
    </div>

    <?php if ( $skills ) : ?>
        <div class="job-skills-tags">
            <?php
            $skills_array = explode(',', $skills);
            foreach ( array_slice($skills_array, 0, 3) as $skill ) : ?>
                <span class="skill-tag"><?php echo esc_html( trim($skill) ); ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="job-excerpt">
        <?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
    </div>

    <div class="job-card-actions">
        <button class="jobs-btn-minimal card-quick-apply-btn" data-id="<?php the_ID(); ?>">Apply</button>
        <a href="<?php the_permalink(); ?>" class="jobs-btn-minimal">Details</a>
    </div>

    <!-- Hidden Dropdown Apply Form -->
    <div class="card-apply-dropdown" id="apply-dropdown-<?php the_ID(); ?>" style="display:none;">
        <div class="dropdown-arrow"></div>
        <p style="font-size: 0.8em; margin-bottom: 10px; color: #64748b;">Quick submit your profile for this position.</p>
        <button class="jobs-btn-small quick-apply-toggle" data-job-id="<?php the_ID(); ?>" style="width: 100%;">Open Application Form</button>
    </div>
</div>
