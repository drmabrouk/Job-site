<?php
/**
 * Service: AdSense Management
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Ads_Service {

    public static function get_ad_code( $placement ) {
        $settings = get_option( 'jobs_adsense_placements', array() );
        if ( empty( $settings[$placement]['enabled'] ) ) return '';

        $global_code = get_option( 'jobs_adsense_code' );
        if ( ! $global_code ) return '';

        // Wrap in a responsive container
        return '<div class="jobs-ad-container ad-placement-' . esc_attr($placement) . '" style="margin: 20px 0; text-align: center; overflow: hidden;">' . $global_code . '</div>';
    }

    public static function inject_ads_js() {
        $global_code = get_option( 'jobs_adsense_code' );
        if ( ! $global_code ) return;

        // Ensure the script is loaded asynchronously
        // Most AdSense code already includes the script tag, but we can ensure it's handled well.
    }

    public static function display_ad( $placement ) {
        echo self::get_ad_code( $placement );
    }
}
