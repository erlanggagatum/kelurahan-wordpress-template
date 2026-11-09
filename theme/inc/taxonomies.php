<?php
/**
 * Registers taxonomies declared in config/taxonomies/*.php.
 *
 * Config shape:
 *
 *   return array(
 *       'slug'        => 'event_type',  // Max 32 chars.
 *       'singular'    => 'Event Type',
 *       'plural'      => 'Event Types',
 *       'post_types'  => array( 'event' ),
 *       'args'        => array(...),    // Merged over defaults, passed to register_taxonomy().
 *   );
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

// Priority 5 so taxonomies exist before post types attach to them on 10.
add_action( 'init', 'kelurahan_register_taxonomies', 5 );

/**
 * Register all configured taxonomies.
 */
function kelurahan_register_taxonomies(): void {
	foreach ( kelurahan_load_configs( 'taxonomies' ) as $file => $config ) {
		if ( empty( $config['slug'] ) || empty( $config['singular'] ) || empty( $config['plural'] ) ) {
			_doing_it_wrong( __FUNCTION__, esc_html( "config/taxonomies/{$file}.php needs slug, singular, plural." ), '0.1.0' );
			continue;
		}

		$defaults = array(
			'labels'            => kelurahan_taxonomy_labels( $config['singular'], $config['plural'] ),
			'public'            => true,
			'hierarchical'      => true, // true = categories UI, false = tags UI.
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => str_replace( '_', '-', $config['slug'] ) ),
		);

		$args = array_replace( $defaults, $config['args'] ?? array() );

		if ( isset( $config['args']['labels'] ) ) {
			$args['labels'] = array_replace( $defaults['labels'], $config['args']['labels'] );
		}

		register_taxonomy( $config['slug'], $config['post_types'] ?? array(), $args );
	}
}
