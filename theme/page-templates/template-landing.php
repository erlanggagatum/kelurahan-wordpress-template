<?php
/**
 * Template Name: Landing Page (PHP)
 * Template Post Type: page
 *
 * Example PHP page template. Selectable in the editor's Template panel
 * alongside block templates from theme.json "customTemplates".
 *
 * Use a PHP template when you need logic a block template can't express:
 * conditional output, external API data, complex queries, form handling.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="wp-block-group is-layout-constrained has-global-padding kelurahan-landing">
	<?php kelurahan_pattern( 'kelurahan/hero' ); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'entry-content is-layout-constrained' ); ?>>
			<?php the_content(); ?>
		</article>
		<?php
	endwhile;
	?>

</main>

<?php
get_footer();
