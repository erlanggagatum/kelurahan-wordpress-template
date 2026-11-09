<?php
/**
 * Runtime checks. Runs INSIDE WordPress via: wp eval-file bin/runtime-check.php
 * Verifies that everything declared in the theme actually registered.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

/**
 * URLs worth fetching in `check.php --http`: home, search, archive + one item
 * per public CPT, recent pages/posts.
 *
 * @return string[]
 */
function kelurahan_check_urls(): array {
	$urls = array( home_url( '/' ), home_url( '/?s=test' ) );
	foreach ( get_post_types( array( 'public' => true, '_builtin' => false ) ) as $pt ) {
		$archive = get_post_type_archive_link( $pt );
		if ( $archive ) {
			$urls[] = $archive;
		}
		$one = get_posts( array( 'post_type' => $pt, 'numberposts' => 1 ) );
		if ( $one ) {
			$urls[] = get_permalink( $one[0] );
		}
	}
	foreach ( get_taxonomies( array( 'public' => true, '_builtin' => false ) ) as $tax ) {
		$terms = get_terms( array( 'taxonomy' => $tax, 'number' => 1, 'hide_empty' => true ) );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$urls[] = get_term_link( $terms[0] );
		}
	}
	foreach ( get_posts( array( 'post_type' => array( 'page', 'post' ), 'numberposts' => 10 ) ) as $p ) {
		$urls[] = get_permalink( $p );
	}
	return array_values( array_unique( $urls ) );
}

// `wp eval-file bin/runtime-check.php urls` prints the URL list and stops.
if ( in_array( 'urls', $args ?? array(), true ) ) {
	echo implode( "\n", kelurahan_check_urls() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$kelurahan_errors = array();

if ( get_template() !== 'kelurahan' && ! function_exists( 'kelurahan_load_configs' ) ) {
	WP_CLI::error( 'Profil Kelurahan is not active. Run: php tools/wp-cli.phar theme activate kelurahan' );
}

if ( ! wp_is_block_theme() ) {
	$kelurahan_errors[] = 'wp_is_block_theme() is false — templates/index.html missing?';
}

foreach ( kelurahan_load_configs( 'post-types' ) as $kelurahan_cfg ) {
	if ( ! post_type_exists( $kelurahan_cfg['slug'] ) ) {
		$kelurahan_errors[] = "Post type not registered: {$kelurahan_cfg['slug']}";
	}
}
foreach ( kelurahan_load_configs( 'taxonomies' ) as $kelurahan_cfg ) {
	if ( ! taxonomy_exists( $kelurahan_cfg['slug'] ) ) {
		$kelurahan_errors[] = "Taxonomy not registered: {$kelurahan_cfg['slug']}";
	}
}
foreach ( kelurahan_meta_configs() as $kelurahan_cfg ) {
	foreach ( $kelurahan_cfg['post_type'] as $kelurahan_pt ) {
		$kelurahan_registered = get_registered_meta_keys( 'post', $kelurahan_pt );
		foreach ( array_keys( $kelurahan_cfg['fields'] ) as $kelurahan_key ) {
			if ( ! isset( $kelurahan_registered[ $kelurahan_key ] ) ) {
				$kelurahan_errors[] = "Meta not registered: {$kelurahan_pt}.{$kelurahan_key}";
			}
		}
	}
}
foreach ( glob( KELURAHAN_DIR . '/blocks/*/block.json' ) ?: array() as $kelurahan_file ) {
	$kelurahan_block = json_decode( (string) file_get_contents( $kelurahan_file ), true );
	if ( ! WP_Block_Type_Registry::get_instance()->is_registered( $kelurahan_block['name'] ?? '' ) ) {
		$kelurahan_errors[] = 'Block not registered: ' . ( $kelurahan_block['name'] ?? $kelurahan_file );
	}
}
foreach ( glob( KELURAHAN_DIR . '/patterns/*.php' ) ?: array() as $kelurahan_file ) {
	$kelurahan_head = get_file_data( $kelurahan_file, array( 'slug' => 'Slug' ) );
	if ( ! WP_Block_Patterns_Registry::get_instance()->is_registered( $kelurahan_head['slug'] ) ) {
		$kelurahan_errors[] = "Pattern not registered: {$kelurahan_head['slug']}";
	}
}

// Every block template must resolve.
foreach ( get_block_templates( array(), 'wp_template' ) as $kelurahan_tpl ) {
	if ( '' === trim( (string) $kelurahan_tpl->content ) ) {
		$kelurahan_errors[] = "Empty block template: {$kelurahan_tpl->slug}";
	}
}

// Render every pattern & template part to catch PHP errors in render callbacks.
ob_start();
foreach ( glob( KELURAHAN_DIR . '/parts/*.html' ) ?: array() as $kelurahan_file ) {
	do_blocks( (string) file_get_contents( $kelurahan_file ) );
}
ob_end_clean();

if ( $kelurahan_errors ) {
	foreach ( $kelurahan_errors as $kelurahan_e ) {
		WP_CLI::warning( $kelurahan_e );
	}
	WP_CLI::error( count( $kelurahan_errors ) . ' runtime problem(s).' );
}
WP_CLI::success(
	sprintf(
		'Runtime OK: theme %s, %d CPT(s), %d block(s), %d module ID(s) enabled (sub-modules included).',
		get_template(),
		count( kelurahan_load_configs( 'post-types' ) ),
		count( glob( KELURAHAN_DIR . '/blocks/*/block.json' ) ?: array() ),
		count( kelurahan_enabled_modules() )
	)
);
