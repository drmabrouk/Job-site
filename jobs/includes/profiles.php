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

/**
 * Filter avatar URL to use custom profile photo if set
 */
function jobs_custom_avatar_url( $url, $id_or_email, $args ) {
    $user_id = 0;
    if ( is_numeric( $id_or_email ) ) {
        $user_id = absint( $id_or_email );
    } elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ) {
        $user_id = $user->ID;
    } elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
        $user_id = (int) $id_or_email->user_id;
    }

    if ( $user_id ) {
        $custom_photo = get_user_meta( $user_id, '_jobs_profile_photo', true );
        if ( $custom_photo ) {
            // Force immediate propagation with high-resolution timestamp
            return add_query_arg( 'v', time(), $custom_photo );
        }
    }

    return $url;
}
add_filter( 'get_avatar_url', 'jobs_custom_avatar_url', 999, 3 );

/**
 * Ensure custom avatar is used in all avatar data requests
 */
function jobs_custom_avatar_data( $args, $id_or_email ) {
    $user_id = 0;
    if ( is_numeric( $id_or_email ) ) {
        $user_id = absint( $id_or_email );
    } elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ) {
        $user_id = $user->ID;
    } elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
        $user_id = (int) $id_or_email->user_id;
    }

    if ( $user_id ) {
        $custom_photo = get_user_meta( $user_id, '_jobs_profile_photo', true );
        if ( $custom_photo ) {
            $args['url'] = add_query_arg( 'v', time(), $custom_photo );
        }
    }
    return $args;
}
add_filter( 'pre_get_avatar_data', 'jobs_custom_avatar_data', 999, 2 );
