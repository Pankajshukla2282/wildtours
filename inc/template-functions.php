<?php
/**
 * Template helper functions for the Wild Tours child theme.
 *
 * Add custom template tags, display helpers, and site-specific utilities here.
 *
 * @package wildtours
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wildtours_site_tagline' ) ) {
    /**
     * Output the site tagline used by the Panna Wild Tours theme.
     */
    function wildtours_site_tagline() {
        echo '<p class="site-tagline">' . esc_html__( 'Live the Nature!', 'wildtours' ) . '</p>';
    }
}

