<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function jobs_create_pages() {
    $pages = array(
        'job-search' => array(
            'title'   => 'Job Search',
            'content' => '[jobs_search_page]',
        ),
        'jobs-admin-panel' => array(
            'title'   => 'Jobs Admin Panel',
            'content' => '[jobs_admin_panel]',
        ),
        'login-registration' => array(
            'title'   => 'Login & Registration',
            'content' => '[jobs_login_registration]',
        ),
    );

    foreach ( $pages as $slug => $page ) {
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post( array(
                'post_title'   => $page['title'],
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_name'    => $slug,
            ) );
        }
    }
}

function jobs_remove_pages() {
    $pages = array( 'job-search', 'jobs-admin-panel', 'login-registration' );
    foreach ( $pages as $slug ) {
        $page = get_page_by_path( $slug );
        if ( $page ) {
            wp_delete_post( $page->ID, true );
        }
    }
}
