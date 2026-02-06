<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_ajax_filter_results() {
    $search       = isset( $_GET['job_search'] ) ? sanitize_text_field( $_GET['job_search'] ) : '';
    $specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( $_GET['specialization'] ) : '';
    $country      = isset( $_GET['country'] ) ? sanitize_text_field( $_GET['country'] ) : '';
    $city         = isset( $_GET['city'] ) ? sanitize_text_field( $_GET['city'] ) : '';

    $args = array(
        'post_type'      => 'job',
        'posts_per_page' => 12,
        's'              => $search,
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

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            include JOBS_PLUGIN_DIR . 'templates/job-card.php';
        }
        wp_reset_postdata();
    } else {
        echo '<p>No jobs found.</p>';
    }

    wp_die();
}
add_action( 'wp_ajax_jobs_filter', 'jobs_ajax_filter_results' );
add_action( 'wp_ajax_nopriv_jobs_filter', 'jobs_ajax_filter_results' );
