<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_ajax_filter_results() {
    $search         = isset( $_GET['job_search'] ) ? sanitize_text_field( $_GET['job_search'] ) : '';
    $category       = isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '';
    $specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( $_GET['specialization'] ) : '';
    $country        = isset( $_GET['country'] ) ? sanitize_text_field( $_GET['country'] ) : '';
    $city           = isset( $_GET['city'] ) ? sanitize_text_field( $_GET['city'] ) : '';
    $paged          = isset( $_GET['paged'] ) ? intval( $_GET['paged'] ) : 1;

    $user_country   = isset( $_GET['user_country'] ) ? sanitize_text_field( $_GET['user_country'] ) : '';
    $user_city      = isset( $_GET['user_city'] ) ? sanitize_text_field( $_GET['user_city'] ) : '';

    $args = array(
        'post_type'      => 'job',
        'posts_per_page' => 12,
        'paged'          => $paged,
        's'              => $search,
        'tax_query'      => array( 'relation' => 'AND' ),
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    // Prioritization logic:
    if ( $user_country || $user_city ) {
        add_filter( 'posts_join', 'jobs_search_location_join' );
        add_filter( 'posts_orderby', 'jobs_search_location_orderby' );
        set_query_var( 'jobs_user_country', $user_country );
        set_query_var( 'jobs_user_city', $user_city );
    }

    if ( $category ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'job_category',
            'field'    => 'slug',
            'terms'    => $category,
        );
    }
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
            'relation' => 'OR',
            array(
                'taxonomy' => 'city',
                'field'    => 'slug',
                'terms'    => $city,
            ),
            array(
                'taxonomy' => 'state',
                'field'    => 'slug',
                'terms'    => $city,
            )
        );
    }

    $query = new WP_Query( $args );

    if ( $user_country || $user_city ) {
        remove_filter( 'posts_join', 'jobs_search_location_join' );
        remove_filter( 'posts_orderby', 'jobs_search_location_orderby' );
    }

    if ( $query->have_posts() ) {
        echo '<div class="jobs-results-grid">';
        while ( $query->have_posts() ) {
            $query->the_post();
            include JOBS_PLUGIN_DIR . 'templates/job-card.php';
        }
        echo '</div>';

        // Intuitive Circular Pagination (Max 5 numbers)
        $total_pages = $query->max_num_pages;
        if ( $total_pages > 1 ) {
            echo '<div class="jobs-pagination">';

            $range = 2;
            $showitems = ($range * 2) + 1;

            if($paged > 1) echo '<a href="#" class="page-numbers prev" data-page="'.($paged - 1).'">&laquo;</a>';

            for ($i=1; $i <= $total_pages; $i++) {
                if (1 != $total_pages && (!($i >= $paged+$range+1 || $i <= $paged-$range-1) || $total_pages <= $showitems )) {
                    $active = ($paged == $i) ? 'active' : '';
                    echo '<a href="#" class="page-numbers '.$active.'" data-page="'.$i.'">'.$i.'</a>';
                }
            }

            if($paged < $total_pages) echo '<a href="#" class="page-numbers next" data-page="'.($paged + 1).'">&raquo;</a>';

            echo '</div>';
        }
        wp_reset_postdata();
    } else {
        echo '<p>No jobs found.</p>';
    }

    wp_die();
}
add_action( 'wp_ajax_jobs_filter', 'jobs_ajax_filter_results' );
add_action( 'wp_ajax_nopriv_jobs_filter', 'jobs_ajax_filter_results' );

function jobs_search_location_join( $join ) {
    global $wpdb;
    $join .= " LEFT JOIN {$wpdb->postmeta} AS mt1 ON ({$wpdb->posts}.ID = mt1.post_id AND mt1.meta_key = '_location_country') ";
    $join .= " LEFT JOIN {$wpdb->postmeta} AS mt2 ON ({$wpdb->posts}.ID = mt2.post_id AND mt2.meta_key = '_location_city') ";
    return $join;
}

function jobs_search_location_orderby( $orderby ) {
    $user_country = esc_sql( get_query_var( 'jobs_user_country' ) );
    $user_city = esc_sql( get_query_var( 'jobs_user_city' ) );

    $priority = "CASE
        WHEN mt2.meta_value LIKE '%$user_city%' THEN 1
        WHEN mt1.meta_value LIKE '%$user_country%' THEN 2
        ELSE 3
    END ASC, ";

    return $priority . $orderby;
}
