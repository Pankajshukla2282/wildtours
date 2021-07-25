<?php
/**
 * Enqueue scripts and styles.
 */

add_action( 'wp_enqueue_scripts', 'wildtours_styles' );
function wildtours_styles() {
    $parenthandle = 'getwid-base-style';
    $childhandle = 'wildtours-style';
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( $childhandle, get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
    wp_enqueue_style( 'wildtours-custom-style', get_stylesheet_directory_uri() . '/css/wildtours.css',
        array( $childhandle ),
        '1.0',
        'all'
    );
}

add_action( 'wp_enqueue_scripts', 'wildtours_scripts' );
function wildtours_scripts() {
	wp_enqueue_script( 'wildtours-script', get_stylesheet_directory_uri() . '/js/wildtours.js', array ( 'jquery' ), '1.0', true);
}
