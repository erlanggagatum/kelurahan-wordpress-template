<?php
/**
 * Profil Kelurahan bootstrap.
 *
 * Keep this file tiny. All logic lives in inc/*.php, all declarative
 * definitions live in config/**. See AGENTS.md at the repo root.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

define( 'KELURAHAN_VERSION', wp_get_theme( get_template() )->get( 'Version' ) );
define( 'KELURAHAN_DIR', get_template_directory() );
define( 'KELURAHAN_URI', get_template_directory_uri() );

/*
 * Load order matters: helpers first, then registries that consume config/.
 */
$kelurahan_includes = array(
	'inc/helpers.php',      // Generic helpers (config loader, label builder).
	'inc/setup.php',        // Theme supports, menus, image sizes.
	'inc/assets.php',       // Front-end and editor assets.
	'inc/post-types.php',   // Registers config/post-types/*.php.
	'inc/taxonomies.php',   // Registers config/taxonomies/*.php.
	'inc/meta.php',         // Registers config/meta/*.php + admin meta boxes.
	'inc/blocks.php',       // Registers blocks/*/block.json.
	'inc/patterns.php',     // Pattern categories (patterns/ auto-load).
	'inc/template-tags.php', // Helpers used inside PHP templates.
);

foreach ( $kelurahan_includes as $kelurahan_file ) {
	require_once KELURAHAN_DIR . '/' . $kelurahan_file;
}
unset( $kelurahan_includes, $kelurahan_file );
