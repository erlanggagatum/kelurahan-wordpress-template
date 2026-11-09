<?php
/**
 * Title: Hero
 * Slug: kelurahan/hero
 * Categories: kelurahan-sections, banner
 * Keywords: hero, banner, intro, beranda
 * Block Types: core/post-content
 * Description: Full-width intro for the home page: site name, short description, link to the profile.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"base-2","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-2-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:heading {"textAlign":"center","level":1} -->
	<h1 class="wp-block-heading has-text-align-center"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"contrast-2","fontSize":"large"} -->
	<p class="has-text-align-center has-contrast-2-color has-text-color has-large-font-size"><?php echo esc_html__( 'Situs resmi profil kelurahan: wilayah, kependudukan, potensi, dan layanan untuk warga.', 'kelurahan' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/profil/' ) ); ?>"><?php echo esc_html__( 'Lihat profil', 'kelurahan' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
