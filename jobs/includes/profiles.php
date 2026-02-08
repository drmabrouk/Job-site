<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle unique profile links and query variables
 */
function jobs_profile_query_vars( $vars ) {
    $vars[] = 'profile_user';
    return $vars;
}
add_filter( 'query_vars', 'jobs_profile_query_vars' );

function jobs_profile_rewrite_rules() {
    add_rewrite_rule(
        '^profile/([^/]+)/?',
        'index.php?pagename=profile&profile_user=$matches[1]',
        'top'
    );
}
add_action( 'init', 'jobs_profile_rewrite_rules' );

/**
 * Generate shareable profile link
 */
function jobs_get_profile_link( $user_id ) {
    $user = get_userdata( $user_id );
    if ( ! $user ) return '';

    // Use user_nicename for URL compatibility
    return home_url( '/profile/' . $user->user_nicename );
}
