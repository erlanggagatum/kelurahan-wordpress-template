<?php
/**
 * Helpers for PHP templates (header.php, page-templates/, template-parts/).
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get a registered meta value for the current (or given) post.
 *
 * @param string   $key     Meta key from config/meta/*.php.
 * @param int|null $post_id Defaults to current post.
 * @return mixed
 */
function kelurahan_meta( string $key, ?int $post_id = null ) {
	return get_post_meta( $post_id ?? get_the_ID(), $key, true );
}

/**
 * Render a block template part (parts/<slug>.html) from PHP.
 *
 * Lets PHP templates share the exact header/footer the block templates use.
 *
 * @param string $slug     Part slug, e.g. "header".
 * @param string $tag_name Wrapper element.
 */
function kelurahan_block_part( string $slug, string $tag_name = 'div' ): void {
	$block = sprintf(
		'<!-- wp:template-part {"slug":"%s","theme":"%s","tagName":"%s"} /-->',
		esc_attr( $slug ),
		esc_attr( get_stylesheet() ),
		esc_attr( $tag_name )
	);

	echo do_blocks( $block ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block markup.
}

/**
 * Render arbitrary block markup from PHP (e.g. a pattern).
 *
 * @param string $pattern_slug Registered pattern slug, e.g. "kelurahan/hero".
 */
function kelurahan_pattern( string $pattern_slug ): void {
	echo do_blocks( sprintf( '<!-- wp:pattern {"slug":"%s"} /-->', esc_attr( $pattern_slug ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Format a Y-m-d meta date with the site's date format.
 *
 * @param string $ymd Date string.
 */
function kelurahan_format_date( string $ymd ): string {
	if ( '' === $ymd ) {
		return '';
	}
	$timestamp = strtotime( $ymd );
	return $timestamp ? wp_date( get_option( 'date_format' ), $timestamp ) : '';
}
