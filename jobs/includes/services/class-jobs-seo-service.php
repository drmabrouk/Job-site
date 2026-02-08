<?php
/**
 * Service: SEO & Schema Markup
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Jobs_SEO_Service {

    public static function init() {
        add_action( 'wp_head', array( __CLASS__, 'inject_meta_tags' ), 1 );
        add_action( 'wp_head', array( __CLASS__, 'inject_json_ld' ) );
        add_action( 'init', array( __CLASS__, 'register_sitemap' ) );
        add_action( 'template_redirect', array( __CLASS__, 'handle_sitemap_request' ) );

        // Use filters for the title to prevent duplication and respect theme support
        add_filter( 'pre_get_document_title', array( __CLASS__, 'get_seo_title' ), 15 );
        add_filter( 'wp_title', array( __CLASS__, 'get_seo_title' ), 15 );
    }

    public static function inject_meta_tags() {
        if ( is_admin() ) return;

        $description = self::get_seo_description();
        $canonical = self::get_canonical_url();
        $title = self::get_seo_title();

        echo "\n<!-- Jobs Plugin SEO -->\n";
        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
        echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";

        // Open Graph
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";
        echo '<meta property="og:type" content="' . (is_singular('job') ? 'job' : 'website') . '">' . "\n";
        echo "<!-- End Jobs Plugin SEO -->\n";
    }

    public static function inject_json_ld() {
        $schema = array();

        if ( is_singular( 'job' ) ) {
            $job_id = get_the_ID();
            $schema = array(
                "@context" => "https://schema.org/",
                "@type" => "JobPosting",
                "title" => get_the_title(),
                "description" => get_the_excerpt(),
                "datePosted" => get_the_date('c'),
                "validThrough" => date('c', strtotime('+30 days')),
                "hiringOrganization" => array(
                    "@type" => "Organization",
                    "name" => get_post_meta($job_id, '_company_name', true),
                    "logo" => get_post_meta($job_id, '_company_logo', true),
                    "sameAs" => jobs_get_profile_link(get_post_field('post_author', $job_id))
                ),
                "jobLocation" => array(
                    "@type" => "Place",
                    "address" => array(
                        "@type" => "PostalAddress",
                        "addressLocality" => wp_get_post_terms($job_id, 'city', array('fields'=>'names'))[0] ?? '',
                        "addressRegion" => wp_get_post_terms($job_id, 'state', array('fields'=>'names'))[0] ?? '',
                        "addressCountry" => wp_get_post_terms($job_id, 'country', array('fields'=>'names'))[0] ?? ''
                    )
                ),
                "employmentType" => get_post_meta($job_id, '_job_employment_type', true),
                "experienceRequirements" => get_post_meta($job_id, '_job_experience_level', true),
                "occupationalCategory" => wp_get_post_terms($job_id, 'job_category', array('fields'=>'names'))[0] ?? '',
                "baseSalary" => array(
                    "@type" => "MonetaryAmount",
                    "currency" => get_post_meta($job_id, '_job_currency', true) ?: 'USD',
                    "value" => array(
                        "@type" => "QuantitativeValue",
                        "value" => get_post_meta($job_id, '_job_salary', true),
                        "unitText" => "MONTH"
                    )
                )
            );
        } elseif ( get_query_var('profile_user') ) {
            $user = get_user_by('slug', get_query_var('profile_user'));
            if ($user) {
                $schema = array(
                    "@context" => "https://schema.org/",
                    "@type" => "Person",
                    "name" => $user->display_name,
                    "jobTitle" => get_user_meta($user->ID, '_specialization', true),
                    "url" => jobs_get_profile_link($user->ID)
                );
            }
        } elseif ( is_singular('post') ) {
            $schema = array(
                "@context" => "https://schema.org",
                "@type" => "Article",
                "headline" => get_the_title(),
                "datePublished" => get_the_date('c'),
                "author" => array(
                    "@type" => "Person",
                    "name" => get_the_author()
                )
            );
        }

        if ( ! empty( $schema ) ) {
            echo "\n" . '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
    }

    public static function get_seo_title($title = '') {
        if ( is_singular('job') ) return get_the_title() . ' | ' . get_bloginfo('name');
        if ( get_query_var('profile_user') ) return get_query_var('profile_user') . ' Profile | ' . get_bloginfo('name');
        if ( is_tax() ) return single_term_title('', false) . ' Jobs | ' . get_bloginfo('name');
        return get_bloginfo('name') . ' - Find Your Next Career';
    }

    public static function get_seo_description() {
        if ( is_singular('job') ) return wp_trim_words(get_the_excerpt(), 25);
        return get_bloginfo('description');
    }

    private static function get_canonical_url() {
        return home_url( $_SERVER['REQUEST_URI'] );
    }

    public static function register_sitemap() {
        add_rewrite_rule( 'jobs-sitemap\.xml$', 'index.php?jobs_sitemap=1', 'top' );
        add_filter( 'query_vars', function($vars) {
            $vars[] = 'jobs_sitemap';
            return $vars;
        });
    }

    public static function handle_sitemap_request() {
        if ( get_query_var( 'jobs_sitemap' ) ) {
            header( 'Content-Type: application/xml; charset=utf-8' );
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            // Homepage
            echo '<url><loc>' . home_url('/') . '</loc><priority>1.0</priority></url>';

            // Jobs
            $jobs = get_posts(array('post_type' => 'job', 'posts_per_page' => 100));
            foreach ($jobs as $job) {
                echo '<url><loc>' . get_permalink($job->ID) . '</loc><changefreq>daily</changefreq></url>';
            }

            // Categories
            $terms = get_terms(array('taxonomy' => array('job_category', 'specialization'), 'hide_empty' => true));
            foreach ($terms as $term) {
                echo '<url><loc>' . get_term_link($term) . '</loc><changefreq>weekly</changefreq></url>';
            }

            // User Profiles
            $users = get_users(array('role' => 'job_seeker', 'number' => 50));
            foreach ($users as $user) {
                echo '<url><loc>' . jobs_get_profile_link($user->ID) . '</loc><changefreq>weekly</changefreq></url>';
            }

            echo '</urlset>';
            exit;
        }
    }
}
