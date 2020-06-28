<?php
/**
 * Enqueue scripts and styles.
 */
function wildtours_scripts() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() );
}

add_action( 'wp_enqueue_scripts', 'wildtours_scripts' );
