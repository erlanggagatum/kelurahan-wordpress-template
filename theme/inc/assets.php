<?php
/**
 * Front-end assets.
 *
 * Plain CSS/JS, no build step. Add files under assets/ and enqueue here.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'kelurahan_enqueue_assets' );

/**
 * Enqueue front-end CSS and JS.
 */
function kelurahan_enqueue_assets(): void {
	wp_enqueue_style(
		'kelurahan-main',
		KELURAHAN_URI . '/assets/css/main.css',
		array(),
		kelurahan_asset_version( 'assets/css/main.css' )
	);

	wp_enqueue_script(
		'kelurahan-main',
		KELURAHAN_URI . '/assets/js/main.js',
		array(),
		kelurahan_asset_version( 'assets/js/main.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}

/**
 * Cache-busting version: file mtime in debug mode, theme version otherwise.
 *
 * @param string $relative_path Path relative to the theme root.
 */
function kelurahan_asset_version( string $relative_path ): string {
	$file = KELURAHAN_DIR . '/' . $relative_path;

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG && file_exists( $file ) ) {
		return (string) filemtime( $file );
	}

	return KELURAHAN_VERSION;
}
