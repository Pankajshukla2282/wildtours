<?php
/**
 * Wild Tours child theme functions.
 *
 * Loads the parent and child theme styles, registers child theme features,
 * and includes helper files used by the theme.
 *
 * @package wildtours
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_stylesheet_directory() . '/inc/custom-header.php';
require_once get_stylesheet_directory() . '/inc/template-functions.php';
require_once get_stylesheet_directory() . '/inc/seo.php';
require_once get_stylesheet_directory() . '/inc/page-content.php';
require_once get_stylesheet_directory() . '/inc/content-enhancements.php';
require_once get_stylesheet_directory() . '/inc/upi-payment.php';

add_action( 'after_setup_theme', 'wildtours_setup' );
function wildtours_setup() {
    load_child_theme_textdomain( 'wildtours', get_stylesheet_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'wildtours-social', 1200, 630, true );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'html5', array(
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
    ) );
}

add_action( 'wp_enqueue_scripts', 'wildtours_styles' );
function wildtours_styles() {
    $parenthandle   = 'getwid-base-style';
    $childhandle    = 'wildtours-style';
    $theme          = wp_get_theme();
    $child_version  = $theme->get( 'Version' ) ?: filemtime( get_stylesheet_directory() . '/style.css' );
    $custom_version = filemtime( get_stylesheet_directory() . '/css/wildtours.css' );

    wp_enqueue_style( $parenthandle, esc_url_raw( get_template_directory_uri() . '/style.css' ),
        array(),
        $theme->parent()->get( 'Version' )
    );

    wp_enqueue_style( $childhandle, esc_url_raw( get_stylesheet_uri() ),
        array( $parenthandle ),
        $child_version
    );

    wp_enqueue_style( 'wildtours-custom-style', esc_url_raw( get_stylesheet_directory_uri() . '/css/wildtours.css' ),
        array( $childhandle ),
        $custom_version,
        'all'
    );

    add_action( 'wp_head', 'wildtours_preconnect_resources', 1 );
}

add_action( 'wp_enqueue_scripts', 'wildtours_scripts' );
function wildtours_scripts() {
    wp_enqueue_script( 'wildtours-script', esc_url_raw( get_stylesheet_directory_uri() . '/js/wildtours.js' ),
        array(),
        filemtime( get_stylesheet_directory() . '/js/wildtours.js' ),
        true
    );
}

add_action( 'wp_head', 'wildtours_seo_meta' );
add_action( 'wp_head', 'wildtours_json_ld' );
