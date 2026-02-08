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
        'login-registration' => array(
            'title'   => 'Login & Registration',
            'content' => '[jobs_login_registration]',
        ),
        'profile' => array(
            'title'   => 'Public Profile',
            'content' => '[jobs_public_profile]',
        ),
        'job-requests' => array(
            'title'   => 'Job Requests',
            'content' => '[jobs_module module="job-requests"]',
        ),
        'applications-submitted' => array(
            'title'   => 'Applications Submitted',
            'content' => '[jobs_module module="applications-submitted"]',
        ),
        'company-profile' => array(
            'title'   => 'Company Profile',
            'content' => '[jobs_module module="company-profile"]',
        ),
        'advanced-settings' => array(
            'title'   => 'Advanced Settings',
            'content' => '[jobs_module module="advanced-settings"]',
        ),
        'terms-conditions' => array(
            'title'   => 'Terms & Conditions',
            'content' => '[jobs_module module="terms-conditions"]',
        ),
        'analytics-insights' => array(
            'title'   => 'Analytics & Insights',
            'content' => '[jobs_module module="analytics-insights"]',
        ),
        'job-seekers' => array(
            'title'   => 'Job Seekers',
            'content' => '[jobs_job_seekers_page]',
        ),
        'site-settings' => array(
            'title'   => 'Site Settings',
            'content' => '[jobs_module module="advanced-settings"]',
        ),
        'policies' => array(
            'title'   => 'Policies',
            'content' => '<!-- Policies Content -->',
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
    $pages = array(
        'job-search',
        'login-registration',
        'profile',
        'job-requests',
        'applications-submitted',
        'company-profile',
        'advanced-settings',
        'terms-conditions',
        'analytics-insights',
        'job-seekers',
        'site-settings',
        'policies'
    );
    foreach ( $pages as $slug ) {
        $page = get_page_by_path( $slug );
        if ( $page ) {
            wp_delete_post( $page->ID, true );
        }
    }
}
