<?php
/**
 * SEO helpers for the Wild Tours child theme.
 *
 * @package wildtours
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wildtours_seo_meta' ) ) {
    function wildtours_seo_meta() {
        if ( ! is_singular() && ! is_front_page() && ! is_home() && ! is_archive() ) {
            return;
        }

        $description = wildtours_seo_description();
        $url = wildtours_seo_url();
        $title = wildtours_seo_title();
        $image = wildtours_seo_image();
        $site_name = get_bloginfo( 'name' );

        echo '<meta name="description" content="' . esc_attr( $description ) . '" />\n';
        echo '<link rel="canonical" href="' . esc_url( $url ) . '" />\n';
        echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '" />\n';
        echo '<meta property="og:type" content="' . esc_attr( is_singular() ? 'article' : 'website' ) . '" />\n';
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />\n';
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />\n';
        echo '<meta property="og:url" content="' . esc_url( $url ) . '" />\n';
        if ( $image ) {
            echo '<meta property="og:image" content="' . esc_url( $image ) . '" />\n';
        }
        echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '" />\n';
        echo '<meta name="twitter:card" content="summary_large_image" />\n';
        echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />\n';
        echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />\n';
        if ( $image ) {
            echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />\n';
        }
        echo '<meta name="robots" content="index, follow" />\n';
    }
}

if ( ! function_exists( 'wildtours_seo_description' ) ) {
    function wildtours_seo_description() {
        if ( is_front_page() ) {
            return 'Panna Wild Tours offers guided jungle safaris, wildlife experiences, and reliable booking support around Panna Tiger Reserve in Madhya Pradesh.';
        }

        if ( is_singular() ) {
            global $post;
            $excerpt = get_the_excerpt( $post );
            if ( $excerpt ) {
                return wp_trim_words( $excerpt, 28, '...' );
            }
            $content = wp_strip_all_tags( get_post_field( 'post_content', $post ) );
            return wp_trim_words( $content, 28, '...' );
        }

        return get_bloginfo( 'description' );
    }
}

if ( ! function_exists( 'wildtours_seo_title' ) ) {
    function wildtours_seo_title() {
        if ( is_front_page() ) {
            return get_bloginfo( 'name' ) . ' | Live the Nature!';
        }
        if ( is_singular() ) {
            return single_post_title( '', false ) . ' | ' . get_bloginfo( 'name' );
        }
        return get_bloginfo( 'name' );
    }
}

if ( ! function_exists( 'wildtours_seo_url' ) ) {
    function wildtours_seo_url() {
        if ( is_front_page() ) {
            return home_url( '/' );
        }
        if ( is_singular() ) {
            return get_permalink();
        }
        return home_url( add_query_arg( null, null ) );
    }
}

if ( ! function_exists( 'wildtours_seo_image' ) ) {
    function wildtours_seo_image() {
        if ( is_singular() && has_post_thumbnail() ) {
            return get_the_post_thumbnail_url( null, 'full' );
        }

        $default = get_stylesheet_directory_uri() . '/images/seo-default.jpg';
        if ( file_exists( get_stylesheet_directory() . '/images/seo-default.jpg' ) ) {
            return esc_url( $default );
        }

        return get_stylesheet_directory_uri() . '/screenshot.png';
    }
}

if ( ! function_exists( 'wildtours_preconnect_resources' ) ) {
    function wildtours_preconnect_resources() {
        echo '<link rel="dns-prefetch" href="//pagead2.googlesyndication.com" />\n';
        echo '<link rel="dns-prefetch" href="//www.googletagmanager.com" />\n';
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com" />\n';
    }
}

if ( ! function_exists( 'wildtours_json_ld' ) ) {
    function wildtours_json_ld() {
        if ( ! is_front_page() && ! is_singular() ) {
            return;
        }

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => get_bloginfo( 'name' ),
            'description' => wildtours_seo_description(),
            'url' => wildtours_seo_url(),
            'telephone' => '+91 992184....',
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => 'Panna Wild Tour, Madla Gate',
                'addressLocality' => 'Panna',
                'addressRegion' => 'Madhya Pradesh',
                'addressCountry' => 'IN',
            ),
            'image' => wildtours_seo_image(),
            'sameAs' => array(
                home_url(),
            ),
            'openingHoursSpecification' => array(
                array(
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
                    'opens' => '06:00',
                    'closes' => '22:00',
                ),
            ),
        );

        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
    }
}
