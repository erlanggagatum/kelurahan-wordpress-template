<?php
/**
 * Registers custom post types declared in config/post-types/*.php.
 *
 * Config shape (all keys except slug/singular/plural optional):
 *
 *   return array(
 *       'slug'     => 'event',       // Max 20 chars, lowercase, no spaces.
 *       'singular' => 'Event',
 *       'plural'   => 'Events',
 *       'args'     => array(...),    // Merged over defaults, passed to register_post_type().
 *   );
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'kelurahan_register_post_types' );

/**
 * Register all configured post types.
 */
function kelurahan_register_post_types(): void {
	foreach ( kelurahan_load_configs( 'post-types' ) as $file => $config ) {
		if ( empty( $config['slug'] ) || empty( $config['singular'] ) || empty( $config['plural'] ) ) {
			_doing_it_wrong( __FUNCTION__, esc_html( "config/post-types/{$file}.php needs slug, singular, plural." ), '0.1.0' );
			continue;
		}

		$defaults = array(
			'labels'       => kelurahan_post_type_labels( $config['singular'], $config['plural'] ),
			'public'       => true,
			'show_in_rest' => true, // Required for the block editor and REST API.
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-admin-post',
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
			'rewrite'      => array( 'slug' => $config['slug'] . 's' ),
		);

		$args = array_replace( $defaults, $config['args'] ?? array() );

		// Allow partial label overrides without losing the generated ones.
		if ( isset( $config['args']['labels'] ) ) {
			$args['labels'] = array_replace( $defaults['labels'], $config['args']['labels'] );
		}

		register_post_type( $config['slug'], $args );
	}
}

/*
 * Flush rewrite rules once after the set of post types/taxonomies changes,
 * so new CPT URLs work without visiting Settings > Permalinks.
 */
add_action( 'init', 'kelurahan_maybe_flush_rewrites', 99 );

/**
 * Flush rewrites when the config hash changes.
 */
function kelurahan_maybe_flush_rewrites(): void {
	$hash = md5(
		wp_json_encode(
			array(
				kelurahan_load_configs( 'post-types' ),
				kelurahan_load_configs( 'taxonomies' ),
			)
		)
	);

	if ( get_option( 'kelurahan_rewrite_hash' ) !== $hash ) {
		flush_rewrite_rules( false );
		update_option( 'kelurahan_rewrite_hash', $hash );
	}
}
