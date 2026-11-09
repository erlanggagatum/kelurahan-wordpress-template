<?php
/**
 * Header for PHP templates.
 *
 * Block templates (templates/*.html) do NOT use this file. PHP templates call
 * get_header() and get the same header as block templates via parts/header.html.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'kelurahan-php-template' ); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'kelurahan' ); ?></a>
<div class="wp-site-blocks">
<?php kelurahan_block_part( 'header', 'header' ); ?>
