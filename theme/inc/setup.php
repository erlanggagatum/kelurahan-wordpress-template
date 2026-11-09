<?php
/**
 * Theme supports, menus, image sizes.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'kelurahan_setup' );

/**
 * Register theme features.
 */
function kelurahan_setup(): void {
	load_theme_textdomain( 'kelurahan', KELURAHAN_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	add_editor_style( 'assets/css/main.css' );

	/*
	 * Classic menu locations. Block templates use the Navigation block,
	 * but PHP templates (header.php) can render these with wp_nav_menu().
	 */
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'kelurahan' ),
			'footer'  => __( 'Footer Menu', 'kelurahan' ),
		)
	);

	add_image_size( 'kelurahan-card', 640, 400, true );
}
