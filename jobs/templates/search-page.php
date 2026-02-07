<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="jobs-search-page jobs-transparent-bg">
    <?php if ( is_front_page() || get_the_ID() == get_option('page_on_front') || (isset($is_job_homepage) && $is_job_homepage) ) : ?>
    <div class="jobs-google-header">
        <?php
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo_url = $custom_logo_id ? wp_get_attachment_image_src( $custom_logo_id , 'full' )[0] : get_option( 'jobs_site_logo' );
        $logo_width = get_option( 'jobs_logo_width', '300' );
        $logo_height = get_option( 'jobs_logo_height', 'auto' );
        ?>
        <img src="<?php echo esc_url( $logo_url ); ?>" alt="Site Logo" class="jobs-main-logo" style="width:<?php echo esc_attr($logo_width); ?>px; height:<?php echo esc_attr($logo_height); ?>;">
    </div>
    <?php endif; ?>

    <div class="jobs-search-engine-centered">
        <form id="jobs-search-form" action="" method="GET">
            <div class="search-row-primary">
                <div class="search-main-field">
                    <input type="text" name="job_search" id="jobs-input-search" placeholder="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'Job title, keywords, or company' ) ); ?>" autocomplete="off">
                    <div class="search-icon-inside">🔍</div>
                </div>
            </div>
            <div class="search-row-secondary">
                <div class="search-field-item">
                    <select name="specialization" id="jobs-input-specialization">
                        <option value="">Specialization</option>
                        <?php
                        $specializations = get_terms( array( 'taxonomy' => 'specialization', 'hide_empty' => false ) );
                        foreach ( $specializations as $term ) {
                            echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="search-field-item">
                    <select name="country" id="jobs-input-country">
                        <option value="">Country</option>
                        <option value="uae">United Arab Emirates</option>
                        <option value="saudi-arabia">Saudi Arabia</option>
                        <option value="qatar">Qatar</option>
                        <option value="kuwait">Kuwait</option>
                        <option value="egypt">Egypt</option>
                        <option value="jordan">Jordan</option>
                        <option value="lebanon">Lebanon</option>
                        <option value="oman">Oman</option>
                        <option value="bahrain">Bahrain</option>
                    </select>
                </div>
                <div class="search-field-item">
                    <select name="city" id="jobs-input-city">
                        <option value="">City</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div id="jobs-status-indicator" class="jobs-status-indicator" style="display:none;">
        <div class="indicator-spinner"></div>
        <p id="indicator-message">Finding the best jobs near you...</p>
    </div>

    <div id="jobs-search-results-wrapper">
        <div id="jobs-results-container">
            <!-- Results will appear here -->
        </div>
    </div>

    <?php
    $adsense_code = get_option( 'jobs_adsense_code' );
    if ( $adsense_code ) : ?>
    <div class="jobs-adsense-container" style="margin-top: 50px; text-align: center;">
        <?php echo $adsense_code; ?>
    </div>
    <?php endif; ?>
</div>
