<?php
/**
 * Block pattern categories.
 *
 * Pattern files in patterns/*.php are auto-registered by WordPress from
 * their file header (Title, Slug, Categories...). Only categories live here.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'kelurahan_register_pattern_categories' );

/**
 * Register pattern categories used by patterns/*.php.
 */
function kelurahan_register_pattern_categories(): void {
	register_block_pattern_category(
		'kelurahan',
		array( 'label' => __( 'Profil Kelurahan', 'kelurahan' ) )
	);
	register_block_pattern_category(
		'kelurahan-sections',
		array( 'label' => __( 'Profil Kelurahan: Sections', 'kelurahan' ) )
	);
}
