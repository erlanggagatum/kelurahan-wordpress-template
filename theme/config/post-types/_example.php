<?php
/**
 * Annotated template for a new post type. Files starting with "_" are ignored.
 *
 * Prefer: php bin/make.php post-type <slug> "<Singular>" "<Plural>"
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'slug'     => 'book',              // Required. <=20 chars, lowercase, [a-z0-9_-].
	'singular' => __( 'Book', 'kelurahan' ),
	'plural'   => __( 'Books', 'kelurahan' ),
	'args'     => array(               // Optional. Any register_post_type() arg.
		'menu_icon'    => 'dashicons-book', // Icons: https://developer.wordpress.org/resource/dashicons/ .
		'rewrite'      => array( 'slug' => 'books' ),
		'has_archive'  => 'books',     // false to disable /books/ archive.
		'hierarchical' => false,       // true = page-like (parent/child).
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
		// phpcs:disable Squiz.PHP.CommentedOutCode.Found -- Intentional: optional args to uncomment.
		// 'public'       => false,    // Internal-only data type.
		// 'show_in_rest' => true,     // Keep true: block editor + REST need it.
		// 'template'     => array( array( 'core/paragraph' ) ), // Default blocks for new posts.
		// phpcs:enable
	),
);
