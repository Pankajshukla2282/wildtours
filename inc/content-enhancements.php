<?php
/**
 * Content enhancements for the Wild Tours child theme.
 *
 * Provides automatic production-grade content and monetization placeholders
 * for pages with limited content.
 *
 * @package wildtours
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wildtours_complete_page_content' ) ) {
    function wildtours_complete_page_content( $content ) {
        if ( is_admin() || ! is_main_query() || ! is_page() ) {
            return $content;
        }

        global $post;
        $title = get_the_title( $post );
        $page_data = wildtours_default_page_content( $title );
        if ( empty( $page_data ) ) {
            return $content . wildtours_monetization_content();
        }

        $word_count = str_word_count( wp_strip_all_tags( $content ) );
        if ( $word_count > 40 ) {
            return $content . wildtours_monetization_content();
        }

        $enhanced = '<div class="page-content-block">';
        $enhanced .= '<h2>' . esc_html( $page_data['headline'] ) . '</h2>';
        if ( ! empty( $page_data['subtitle'] ) ) {
            $enhanced .= '<p>' . esc_html( $page_data['subtitle'] ) . '</p>';
        }

        if ( ! empty( $page_data['content'] ) && is_array( $page_data['content'] ) ) {
            foreach ( $page_data['content'] as $paragraph ) {
                $enhanced .= '<p>' . esc_html( $paragraph ) . '</p>';
            }
        }

        if ( ! empty( $page_data['features'] ) && is_array( $page_data['features'] ) ) {
            $enhanced .= '<h3>' . esc_html__( 'What we offer', 'wildtours' ) . '</h3>';
            $enhanced .= '<ul>';
            foreach ( $page_data['features'] as $feature ) {
                $enhanced .= '<li>' . esc_html( $feature ) . '</li>';
            }
            $enhanced .= '</ul>';
        }

        if ( ! empty( $page_data['steps'] ) && is_array( $page_data['steps'] ) ) {
            $enhanced .= '<h3>' . esc_html__( 'Booking process', 'wildtours' ) . '</h3>';
            $enhanced .= '<ol>';
            foreach ( $page_data['steps'] as $step ) {
                $enhanced .= '<li>' . esc_html( $step ) . '</li>';
            }
            $enhanced .= '</ol>';
        }

        if ( ! empty( $page_data['details'] ) && is_array( $page_data['details'] ) ) {
            $enhanced .= '<h3>' . esc_html__( 'Contact details', 'wildtours' ) . '</h3>';
            $enhanced .= '<dl>';
            foreach ( $page_data['details'] as $label => $value ) {
                $enhanced .= '<dt>' . esc_html( $label ) . '</dt>';
                $enhanced .= '<dd>' . esc_html( $value ) . '</dd>';
            }
            $enhanced .= '</dl>';
        }

        if ( ! empty( $page_data['team'] ) && is_array( $page_data['team'] ) ) {
            $enhanced .= '<h3>' . esc_html__( 'Our team', 'wildtours' ) . '</h3>';
            foreach ( $page_data['team'] as $member ) {
                $enhanced .= '<div class="page-team-member">';
                $enhanced .= '<h4>' . esc_html( $member['name'] ) . '</h4>';
                $enhanced .= '<p><strong>' . esc_html( $member['role'] ) . '</strong></p>';
                $enhanced .= '<p>' . esc_html( $member['bio'] ) . '</p>';
                $enhanced .= '</div>';
            }
        }

        $enhanced .= '</div>';

        return $content . $enhanced . wildtours_monetization_content();
    }
}

if ( ! function_exists( 'wildtours_monetization_content' ) ) {
    function wildtours_monetization_content() {
        $ad_html = '<div class="ad-banner" role="complementary">';
        $ad_html .= '<p>' . esc_html__( 'Monetization placeholder: this area is ideal for Google AdSense, affiliate promotions, or sponsored service offers.', 'wildtours' ) . '</p>';
        $ad_html .= '<p>' . esc_html__( 'Replace this block with your ad code or affiliate banner to start generating revenue.', 'wildtours' ) . '</p>';
        $ad_html .= '</div>';
        return $ad_html;
    }
}

if ( ! function_exists( 'wildtours_inject_ad_after_first_paragraph' ) ) {
    function wildtours_inject_ad_after_first_paragraph( $content ) {
        if ( is_admin() || ! is_singular() || ! in_the_loop() || is_feed() ) {
            return $content;
        }

        $ad = '<div class="ad-inline" role="complementary">';
        $ad .= '<p>' . esc_html__( 'Sponsored placement: add a relevant tour package offer, affiliate link, or ad unit here.', 'wildtours' ) . '</p>';
        $ad .= '</div>';

        $closing_p = '</p>';
        $pos = strpos( $content, $closing_p );
        if ( false !== $pos ) {
            return substr_replace( $content, $closing_p . $ad, $pos, strlen( $closing_p ) );
        }

        return $content . $ad;
    }
}

add_filter( 'the_content', 'wildtours_complete_page_content', 20 );
add_filter( 'the_content', 'wildtours_inject_ad_after_first_paragraph', 30 );
