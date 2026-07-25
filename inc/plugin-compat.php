<?php
/**
 * Compatibility helpers for the Panna Wild Tour plugin.
 *
 * @package wildtours
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wildtours_is_pwt_plugin_active' ) ) {
    function wildtours_is_pwt_plugin_active() {
        return defined( 'PWT_PLUGIN_PATH' ) || class_exists( 'PWT\\Core\\Application' );
    }
}

if ( ! function_exists( 'wildtours_pwt_post_types' ) ) {
    function wildtours_pwt_post_types() {
        return array( 'pwt_package', 'pwt_safari', 'pwt_destination', 'pwt_resort', 'pwt_vehicle', 'pwt_testimonial', 'pwt_review', 'pwt_faq', 'pwt_gallery', 'pwt_booking' );
    }
}

if ( ! function_exists( 'wildtours_pwt_taxonomies' ) ) {
    function wildtours_pwt_taxonomies() {
        return array( 'pwt_package_category', 'pwt_safari_zone', 'pwt_season', 'pwt_destination_category', 'pwt_vehicle_type', 'pwt_activity' );
    }
}

if ( ! function_exists( 'wildtours_has_pwt_shortcode' ) ) {
    function wildtours_has_pwt_shortcode( $post = null ) {
        $post = get_post( $post );
        if ( ! $post instanceof WP_Post ) {
            return false;
        }

        return false !== strpos( (string) $post->post_content, '[pwt_' );
    }
}

if ( ! function_exists( 'wildtours_is_pwt_payment_page' ) ) {
    function wildtours_is_pwt_payment_page() {
        if ( ! is_singular( 'page' ) ) {
            return false;
        }

        $post = get_post();
        if ( $post instanceof WP_Post && wildtours_has_pwt_shortcode( $post ) && has_shortcode( $post->post_content, 'pwt_payment_page' ) ) {
            return true;
        }

        $settings         = get_option( 'pwt_settings', array() );
        $payment_page_url = trim( (string) ( $settings['payment_page_url'] ?? '' ) );
        if ( '' === $payment_page_url ) {
            return false;
        }

        $current_permalink = get_permalink( get_queried_object_id() );
        if ( empty( $current_permalink ) ) {
            return false;
        }

        return untrailingslashit( $current_permalink ) === untrailingslashit( $payment_page_url );
    }
}

if ( ! function_exists( 'wildtours_is_pwt_shortcode_page' ) ) {
    function wildtours_is_pwt_shortcode_page() {
        if ( ! is_singular() ) {
            return false;
        }

        return wildtours_has_pwt_shortcode( get_post() );
    }
}

if ( ! function_exists( 'wildtours_is_pwt_archive' ) ) {
    function wildtours_is_pwt_archive() {
        return is_post_type_archive( wildtours_pwt_post_types() ) || is_tax( wildtours_pwt_taxonomies() );
    }
}

if ( ! function_exists( 'wildtours_is_pwt_singular' ) ) {
    function wildtours_is_pwt_singular() {
        return is_singular( wildtours_pwt_post_types() );
    }
}

if ( ! function_exists( 'wildtours_is_pwt_owned_request' ) ) {
    function wildtours_is_pwt_owned_request() {
        if ( ! wildtours_is_pwt_plugin_active() ) {
            return false;
        }

        if ( wildtours_is_pwt_singular() || wildtours_is_pwt_archive() || wildtours_is_pwt_payment_page() ) {
            return true;
        }

        return wildtours_is_pwt_shortcode_page();
    }
}