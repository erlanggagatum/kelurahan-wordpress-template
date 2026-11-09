# PHP templates and partials

Use PHP when a block template can't express the logic.

## Override a hierarchy slug with PHP

Create `theme/<hierarchy-slug>.php`, for example `theme/single-book.php` or `theme/taxonomy-genre.php`.

**The PHP file only takes effect if no block template exists with the same slug or a more specific one.**
If `templates/single-book.html` exists, the block template wins, so delete it. See docs/architecture.md.

Skeleton (copy from `theme/taxonomy-event_type.php`):

```php
<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="wp-block-group is-layout-constrained has-global-padding">
	<?php while ( have_posts() ) : the_post(); ?>
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
```

- `get_header()` and `get_footer()` render the same `parts/header.html` and `parts/footer.html` that block templates use.
- The `is-layout-constrained has-global-padding` classes give the same content width and padding as block layouts.
- Put `alignwide` or `alignfull` on child elements to make wide or full-width sections.

## Partials

Put partials in `theme/template-parts/<name>-<variant>.php` and load them with `get_template_part( 'template-parts/<name>', '<variant>', $args )`.
Example: `template-parts/content-card.php`.

## Helpers (`inc/template-tags.php`)

| Function | Does |
|---|---|
| `kelurahan_meta( $key, $post_id = null )` | single `get_post_meta` value |
| `kelurahan_format_date( $ymd )` | formats a Y-m-d date with the site's date format |
| `kelurahan_block_part( $slug, $tag )` | renders `parts/<slug>.html` |
| `kelurahan_pattern( 'kelurahan/hero' )` | renders a pattern |
| `do_blocks( $markup )` | renders any block markup |

Escape all output: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`.
