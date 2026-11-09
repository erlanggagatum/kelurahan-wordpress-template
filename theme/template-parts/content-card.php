<?php
/**
 * Card partial for any post type. Used via get_template_part( 'template-parts/content', 'card' ).
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'kelurahan-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="kelurahan-card__media">
			<?php the_post_thumbnail( 'kelurahan-card' ); ?>
		</a>
	<?php endif; ?>

	<div class="kelurahan-card__body">
		<h3 class="kelurahan-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

		<div class="kelurahan-card__excerpt"><?php the_excerpt(); ?></div>
	</div>
</article>
