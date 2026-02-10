<?php
/**
 * Service: AdSense & Placement Control
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Ads_Service {

    public static function display_ad( $placement = 'sidebar' ) {
        $adsense_code = get_option( 'jobs_adsense_code' );
        if ( ! $adsense_code ) return '';

        // In a real scenario, this would wrap the code in a div with placement-specific styling
        echo '<div class="jobs-ad-placement ads-' . esc_attr($placement) . '" style="margin: 20px 0; text-align: center; overflow: hidden;">';
        echo $adsense_code;
        echo '</div>';
    }
}
