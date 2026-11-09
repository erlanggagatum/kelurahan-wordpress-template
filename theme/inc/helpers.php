<?php
/**
 * Generic helpers shared by the registries.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load every config file in a config/ subdirectory.
 *
 * Each file must `return` an array. Files starting with "_" are skipped,
 * so you can keep drafts/examples like `_example.php` around.
 *
 * @param string $dir Subdirectory of config/, e.g. "post-types".
 * @return array<string, array> Map of file basename => returned config.
 */
function kelurahan_load_configs( string $dir ): array {
	$configs = array();
	$files   = glob( KELURAHAN_DIR . '/config/' . $dir . '/*.php' );

	if ( ! $files ) {
		return $configs;
	}

	sort( $files );

	foreach ( $files as $file ) {
		$name = basename( $file, '.php' );
		if ( str_starts_with( $name, '_' ) ) {
			continue;
		}

		$config = require $file;

		if ( ! is_array( $config ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				esc_html( sprintf( 'Config file %s must return an array.', $file ) ),
				'0.1.0'
			);
			continue;
		}

		$configs[ $name ] = $config;
	}

	return $configs;
}

/**
 * Build a full post type labels array from singular/plural names.
 *
 * @param string $singular e.g. "Lembaga".
 * @param string $plural   e.g. "Lembaga" (Indonesian plural is often the same word).
 * @return array<string, string>
 */
function kelurahan_post_type_labels( string $singular, string $plural ): array {
	$lower_plural   = strtolower( $plural );
	$lower_singular = strtolower( $singular );

	return array(
		'name'                  => $plural,
		'singular_name'         => $singular,
		'menu_name'             => $plural,
		'add_new'               => __( 'Add New', 'kelurahan' ),
		/* translators: %s: singular post type name. */
		'add_new_item'          => sprintf( __( 'Add New %s', 'kelurahan' ), $singular ),
		/* translators: %s: singular post type name. */
		'edit_item'             => sprintf( __( 'Edit %s', 'kelurahan' ), $singular ),
		/* translators: %s: singular post type name. */
		'new_item'              => sprintf( __( 'New %s', 'kelurahan' ), $singular ),
		/* translators: %s: singular post type name. */
		'view_item'             => sprintf( __( 'View %s', 'kelurahan' ), $singular ),
		/* translators: %s: plural post type name. */
		'view_items'            => sprintf( __( 'View %s', 'kelurahan' ), $plural ),
		/* translators: %s: plural post type name. */
		'search_items'          => sprintf( __( 'Search %s', 'kelurahan' ), $plural ),
		/* translators: %s: lowercase plural post type name. */
		'not_found'             => sprintf( __( 'No %s found.', 'kelurahan' ), $lower_plural ),
		/* translators: %s: lowercase plural post type name. */
		'not_found_in_trash'    => sprintf( __( 'No %s found in Trash.', 'kelurahan' ), $lower_plural ),
		/* translators: %s: plural post type name. */
		'all_items'             => sprintf( __( 'All %s', 'kelurahan' ), $plural ),
		/* translators: %s: singular post type name. */
		'archives'              => sprintf( __( '%s Archives', 'kelurahan' ), $singular ),
		/* translators: %s: singular post type name. */
		'attributes'            => sprintf( __( '%s Attributes', 'kelurahan' ), $singular ),
		/* translators: %s: lowercase singular post type name. */
		'insert_into_item'      => sprintf( __( 'Insert into %s', 'kelurahan' ), $lower_singular ),
		/* translators: %s: lowercase singular post type name. */
		'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s', 'kelurahan' ), $lower_singular ),
		/* translators: %s: plural post type name. */
		'filter_items_list'     => sprintf( __( 'Filter %s list', 'kelurahan' ), $lower_plural ),
		/* translators: %s: plural post type name. */
		'items_list'            => sprintf( __( '%s list', 'kelurahan' ), $plural ),
	);
}

/**
 * Build a taxonomy labels array from singular/plural names.
 *
 * @param string $singular e.g. "Jenis Lembaga".
 * @param string $plural   e.g. "Jenis Lembaga".
 * @return array<string, string>
 */
function kelurahan_taxonomy_labels( string $singular, string $plural ): array {
	return array(
		'name'          => $plural,
		'singular_name' => $singular,
		'menu_name'     => $plural,
		/* translators: %s: plural taxonomy name. */
		'all_items'     => sprintf( __( 'All %s', 'kelurahan' ), $plural ),
		/* translators: %s: singular taxonomy name. */
		'edit_item'     => sprintf( __( 'Edit %s', 'kelurahan' ), $singular ),
		/* translators: %s: singular taxonomy name. */
		'view_item'     => sprintf( __( 'View %s', 'kelurahan' ), $singular ),
		/* translators: %s: singular taxonomy name. */
		'update_item'   => sprintf( __( 'Update %s', 'kelurahan' ), $singular ),
		/* translators: %s: singular taxonomy name. */
		'add_new_item'  => sprintf( __( 'Add New %s', 'kelurahan' ), $singular ),
		/* translators: %s: singular taxonomy name. */
		'new_item_name' => sprintf( __( 'New %s Name', 'kelurahan' ), $singular ),
		/* translators: %s: plural taxonomy name. */
		'search_items'  => sprintf( __( 'Search %s', 'kelurahan' ), $plural ),
		/* translators: %s: singular taxonomy name. */
		'parent_item'   => sprintf( __( 'Parent %s', 'kelurahan' ), $singular ),
		/* translators: %s: lowercase plural taxonomy name. */
		'not_found'     => sprintf( __( 'No %s found.', 'kelurahan' ), strtolower( $plural ) ),
	);
}
