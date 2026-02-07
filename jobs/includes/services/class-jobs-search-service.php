<?php
/**
 * Service: Search & Filtering
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_Search_Service {

    public static function filter_jobs( $params ) {
        $search         = isset( $params['job_search'] ) ? sanitize_text_field( $params['job_search'] ) : '';
        $specialization = isset( $params['specialization'] ) ? sanitize_text_field( $params['specialization'] ) : '';
        $country        = isset( $params['country'] ) ? sanitize_text_field( $params['country'] ) : '';
        $city           = isset( $params['city'] ) ? sanitize_text_field( $params['city'] ) : '';
        $paged          = isset( $params['paged'] ) ? intval( $params['paged'] ) : 1;
        $per_page       = isset( $params['per_page'] ) ? intval( $params['per_page'] ) : 12;
        $lat            = isset( $params['lat'] ) ? floatval( $params['lat'] ) : 0;
        $lng            = isset( $params['lng'] ) ? floatval( $params['lng'] ) : 0;

        // Create a unique cache key based on params
        $cache_key = 'jobs_search_' . md5( serialize( array( $search, $specialization, $country, $city, $paged, $per_page, round($lat, 2), round($lng, 2) ) ) );
        $cached_results = get_transient( $cache_key );

        if ( $cached_results !== false ) {
            return $cached_results;
        }

        $args = array(
            'post_type'      => 'job',
            'posts_per_page' => $per_page,
            'paged'          => $paged,
            's'              => $search,
            'post_status'    => 'publish',
            'tax_query'      => array( 'relation' => 'AND' ),
        );

        if ( $specialization ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'specialization',
                'field'    => 'slug',
                'terms'    => $specialization,
            );
        }

        if ( $country ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'country',
                'field'    => 'slug',
                'terms'    => $country,
            );
        }

        if ( $city ) {
            $args['tax_query'][] = array(
                'taxonomy' => 'city',
                'field'    => 'slug',
                'terms'    => $city,
            );
        }

        // Apply proximity sorting if coords provided
        if ( $lat && $lng ) {
            add_filter( 'posts_fields', array( __CLASS__, 'proximity_fields' ) );
            add_filter( 'posts_join', array( __CLASS__, 'proximity_join' ) );
            add_filter( 'posts_orderby', array( __CLASS__, 'proximity_orderby' ) );

            // Pass vars via global or better via a filter closure if supported,
            // but for simplicity with WP_Query hooks:
            $GLOBALS['jobs_search_lat'] = $lat;
            $GLOBALS['jobs_search_lng'] = $lng;
        } else {
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
        }

        $query = new WP_Query( $args );

        // Cache for 1 hour
        set_transient( $cache_key, $query, HOUR_IN_SECONDS );

        // Cleanup filters
        if ( $lat && $lng ) {
            remove_filter( 'posts_fields', array( __CLASS__, 'proximity_fields' ) );
            remove_filter( 'posts_join', array( __CLASS__, 'proximity_join' ) );
            remove_filter( 'posts_orderby', array( __CLASS__, 'proximity_orderby' ) );
        }

        return $query;
    }

    public static function proximity_fields( $fields ) {
        global $wpdb;
        $lat = (float) $GLOBALS['jobs_search_lat'];
        $lng = (float) $GLOBALS['jobs_search_lng'];
        $fields .= ", ( 6371 * acos( LEAST(1.0, GREATEST(-1.0, cos( radians($lat) ) * cos( radians( CAST(mt_lat.meta_value AS DECIMAL(12,8)) ) ) * cos( radians( CAST(mt_lng.meta_value AS DECIMAL(12,8)) ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( CAST(mt_lat.meta_value AS DECIMAL(12,8)) ) ) )) ) ) AS distance";
        return $fields;
    }

    public static function proximity_join( $join ) {
        global $wpdb;
        $join .= " LEFT JOIN {$wpdb->postmeta} AS mt_lat ON ({$wpdb->posts}.ID = mt_lat.post_id AND mt_lat.meta_key = '_job_lat') ";
        $join .= " LEFT JOIN {$wpdb->postmeta} AS mt_lng ON ({$wpdb->posts}.ID = mt_lng.post_id AND mt_lng.meta_key = '_job_lng') ";
        return $join;
    }

    public static function proximity_orderby( $orderby ) {
        if ( empty( $orderby ) ) {
            return " distance IS NULL, distance ASC";
        }
        return " distance IS NULL, distance ASC, " . $orderby;
    }

    public static function clear_cache() {
        global $wpdb;
        $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_jobs_search_%'" );
        $wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_jobs_search_%'" );
    }
}

// Hook into job updates to clear cache
add_action( 'save_post_job', array( 'Jobs_Search_Service', 'clear_cache' ) );
add_action( 'save_post_application', array( 'Jobs_Search_Service', 'clear_cache' ) );
