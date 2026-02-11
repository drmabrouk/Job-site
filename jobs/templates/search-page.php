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
        $logo_width = get_option( 'jobs_logo_width', '180' );
        $logo_height = get_option( 'jobs_logo_height', 'auto' );
        ?>
        <a href="<?php echo home_url('/'); ?>" style="display: block; width: fit-content; margin: 0 auto; border: none; outline: none; background: transparent; text-decoration: none;">
            <img src="<?php echo esc_url( $logo_url ); ?>" alt="Site Logo" class="jobs-main-logo" style="--logo-custom-width:<?php echo esc_attr($logo_width); ?>px; height:<?php echo esc_attr($logo_height); ?>; display: block;">
        </a>
    </div>
    <?php endif; ?>

    <div class="jobs-search-wrapper">
        <div class="jobs-search-engine-centered">
            <form id="jobs-search-form" action="" method="GET">
                <!-- Main Search Bar -->
                <div class="search-main-container">
                    <div class="search-main-field">
                        <span class="search-icon dashicons dashicons-search"></span>
                        <input type="text" name="job_search" id="jobs-input-search" placeholder="<?php echo esc_attr( get_option( 'jobs_search_placeholder', 'Job title, keywords, or company' ) ); ?>" autocomplete="off">
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="search-filters-container">
                    <div class="filter-item spec-field">
                        <span class="filter-icon dashicons dashicons-category"></span>
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
                    <div class="filter-separator"></div>
                    <div class="filter-item country-field">
                        <span class="filter-icon dashicons dashicons-location"></span>
                        <select name="country" id="jobs-input-country">
                            <option value="">Country</option>
                            <?php
                            $location_data = Jobs_Data_Service::get_location_data();
                            foreach ( $location_data as $group_key => $group ) : ?>
                                <optgroup label="<?php echo esc_attr($group['label']); ?>">
                                    <?php foreach ( $group['countries'] as $slug => $c ) : ?>
                                        <option value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($c['name']); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-separator"></div>
                    <div class="filter-item city-field">
                        <span class="filter-icon dashicons dashicons-admin-site"></span>
                        <select name="city" id="jobs-input-city">
                            <option value="">City</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div id="jobs-status-indicator" class="jobs-status-indicator" style="display:none; flex-direction:column; align-items:center; justify-content:center;">
            <div class="indicator-spinner"></div>
            <p id="indicator-message">Discovering more opportunities...</p>
        </div>
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
