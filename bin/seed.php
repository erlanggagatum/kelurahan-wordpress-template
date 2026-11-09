<?php
/**
 * Sample content. Runs INSIDE WordPress:  php tools/wp-cli.phar eval-file bin/seed.php
 * Idempotent: skips items whose slug already exists.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create a post if no post with that slug exists. Returns the ID.
 *
 * @param array $postarr wp_insert_post() args; must include post_name.
 */
function kelurahan_seed_post( array $postarr ): int {
	$existing = get_page_by_path( $postarr['post_name'], OBJECT, $postarr['post_type'] ?? 'post' );
	if ( $existing ) {
		WP_CLI::log( "exists: {$postarr['post_type']}/{$postarr['post_name']}" );
		return $existing->ID;
	}
	$id = wp_insert_post( $postarr + array( 'post_status' => 'publish' ), true );
	if ( is_wp_error( $id ) ) {
		WP_CLI::warning( $id->get_error_message() );
		return 0;
	}
	WP_CLI::log( "created: {$postarr['post_type']}/{$postarr['post_name']} (#{$id})" );
	return $id;
}

// Pages.
$kelurahan_home_content = "<!-- wp:pattern {\"slug\":\"kelurahan/hero\"} /-->\n\n<!-- wp:pattern {\"slug\":\"kelurahan/call-to-action\"} /-->";

$home = kelurahan_seed_post(
	array(
		'post_type'     => 'page',
		'post_name'     => 'home',
		'post_title'    => 'Home',
		'post_content'  => $kelurahan_home_content,
		'page_template' => 'page-no-title',
	)
);
kelurahan_seed_post(
	array(
		'post_type'     => 'page',
		'post_name'     => 'landing',
		'post_title'    => 'Landing (PHP template)',
		'post_content'  => "<!-- wp:paragraph -->\n<p>This page uses page-templates/template-landing.php.</p>\n<!-- /wp:paragraph -->",
		'page_template' => 'page-templates/template-landing.php',
	)
);
kelurahan_seed_post(
	array(
		'post_type'    => 'page',
		'post_name'    => 'about',
		'post_title'   => 'About',
		'post_content' => "<!-- wp:paragraph -->\n<p>About this site.</p>\n<!-- /wp:paragraph -->",
	)
);
$blog = kelurahan_seed_post(
	array(
		'post_type'  => 'page',
		'post_name'  => 'blog',
		'post_title' => 'Blog',
	)
);

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );
update_option( 'page_for_posts', $blog );

// Blog post.
kelurahan_seed_post(
	array(
		'post_type'    => 'post',
		'post_name'    => 'hello-agents',
		'post_title'   => 'Hello, agents',
		'post_content' => "<!-- wp:paragraph -->\n<p>First post.</p>\n<!-- /wp:paragraph -->",
	)
);

flush_rewrite_rules( false );
WP_CLI::success( 'Seed complete.' );
