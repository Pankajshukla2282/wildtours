<?php
/**
 * Custom header feature support for the Wild Tours child theme.
 *
 * Handles child theme header feature registration and prepares the site for
 * future branding enhancements.
 *
 * @package wildtours
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'after_setup_theme', 'wildtours_custom_header_setup' );
function wildtours_custom_header_setup() {
    add_theme_support( 'custom-header', array(
        'width'         => 1200,
        'height'        => 280,
        'flex-height'   => true,
        'header-text'   => false,
    ) );

    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
}

