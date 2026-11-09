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

// Pages. Slugs follow docs/guide/struktur-situs.md so pattern links (/profil/, /layanan/) resolve.
$kelurahan_home_content = "<!-- wp:pattern {\"slug\":\"kelurahan/hero\"} /-->\n\n<!-- wp:pattern {\"slug\":\"kelurahan/call-to-action\"} /-->";

$home = kelurahan_seed_post(
	array(
		'post_type'     => 'page',
		'post_name'     => 'beranda',
		'post_title'    => 'Beranda',
		'post_content'  => $kelurahan_home_content,
		'page_template' => 'page-no-title',
	)
);
kelurahan_seed_post(
	array(
		'post_type'    => 'page',
		'post_name'    => 'profil',
		'post_title'   => 'Profil',
		'post_content' => "<!-- wp:paragraph -->\n<p>Halaman induk profil kelurahan: sejarah, visi dan misi, wilayah, dan potensi sumber daya alam.</p>\n<!-- /wp:paragraph -->",
	)
);
kelurahan_seed_post(
	array(
		'post_type'    => 'page',
		'post_name'    => 'layanan',
		'post_title'   => 'Layanan',
		'post_content' => "<!-- wp:paragraph -->\n<p>Daftar layanan administrasi, persyaratan, dan jam layanan kantor kelurahan.</p>\n<!-- /wp:paragraph -->",
	)
);
kelurahan_seed_post(
	array(
		'post_type'     => 'page',
		'post_name'     => 'landing',
		'post_title'    => 'Contoh templat PHP',
		'post_content'  => "<!-- wp:paragraph -->\n<p>Halaman ini memakai page-templates/template-landing.php.</p>\n<!-- /wp:paragraph -->",
		'page_template' => 'page-templates/template-landing.php',
	)
);
$blog = kelurahan_seed_post(
	array(
		'post_type'  => 'page',
		'post_name'  => 'berita',
		'post_title' => 'Berita dan pengumuman',
	)
);

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );
update_option( 'page_for_posts', $blog );

// News post.
kelurahan_seed_post(
	array(
		'post_type'    => 'post',
		'post_name'    => 'selamat-datang',
		'post_title'   => 'Selamat datang di situs profil kelurahan',
		'post_content' => "<!-- wp:paragraph -->\n<p>Contoh berita pertama. Ganti atau hapus setelah situs diisi.</p>\n<!-- /wp:paragraph -->",
	)
);

flush_rewrite_rules( false );
WP_CLI::success( 'Seed complete.' );
