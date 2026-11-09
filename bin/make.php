<?php
/**
 * Scaffolder. Generates files that follow this theme's conventions.
 * Never overwrites unless --force. Prints every file it writes.
 *
 * Usage: php bin/make.php <command> [args] [--flags]
 *
 *   post-type <slug> [--singular=Book] [--plural=Books] [--icon=dashicons-book] [--no-templates]
 *       config/post-types/<slug>.php + templates/single-<slug>.html + templates/archive-<slug>.html
 *
 *   taxonomy <slug> --post-types=book,post [--singular=..] [--plural=..] [--flat]
 *       config/taxonomies/<slug>.php   (--flat = tag-like, non-hierarchical)
 *
 *   meta <post-type> <key:type[:Label]>... [--title="Book Details"] [--context=side]
 *       config/meta/<post-type>.php    types: text textarea url email date time select number integer checkbox
 *
 *   block <name> [--title="My Block"]
 *       blocks/<name>/{block.json,index.js,index.asset.php,render.php,style.css}
 *
 *   pattern <slug> [--title="My Pattern"] [--categories=kelurahan-sections]
 *       patterns/<slug>.php
 *
 *   template <slug>
 *       templates/<slug>.html   (any hierarchy slug: front-page, single-book, page-about, category-news …)
 *
 *   custom-template <slug> [--title="Landing"] [--post-types=page]
 *       templates/<slug>.html + entry in theme.json customTemplates (user-selectable)
 *
 *   page-template <slug> [--title="Landing (PHP)"] [--post-types=page]
 *       page-templates/template-<slug>.php   (user-selectable PHP template)
 *
 *   part <slug> [--title=..] [--area=header|footer|uncategorized]
 *       parts/<slug>.html + entry in theme.json templateParts
 */

declare(strict_types=1);

require_once __DIR__ . '/lib.php';

[ $pos, $flags ] = parse_args( $argv );
$command         = $pos[0] ?? 'help';
$force           = ! empty( $flags['force'] );

/** Write file unless it exists (or --force). */
function put( string $relative, string $contents ): void {
	global $force;
	$path = THEME_DIR . '/' . $relative;
	if ( file_exists( $path ) && ! $force ) {
		warn( "skip (exists): theme/{$relative}  (use --force to overwrite)" );
		return;
	}
	@mkdir( dirname( $path ), 0777, true );
	file_put_contents( $path, $contents );
	ok( "wrote theme/{$relative}" );
}

/** Replace {{key}} placeholders. */
function tpl( string $template, array $vars ): string {
	$map = array();
	foreach ( $vars as $k => $v ) {
		$map[ '{{' . $k . '}}' ] = $v;
	}
	return strtr( $template, $map );
}

/** Title Case from slug. */
function humanize( string $slug ): string {
	return ucwords( str_replace( array( '-', '_' ), ' ', $slug ) );
}

/** Naive English plural. */
function pluralize( string $word ): string {
	if ( preg_match( '/(s|x|z|ch|sh)$/i', $word ) ) {
		return $word . 'es';
	}
	if ( preg_match( '/[^aeiou]y$/i', $word ) ) {
		return substr( $word, 0, -1 ) . 'ies';
	}
	return $word . 's';
}

/** PHP single-quoted string literal. */
function q( string $s ): string {
	return "'" . str_replace( array( '\\', "'" ), array( '\\\\', "\\'" ), $s ) . "'";
}

/** Read, modify and write theme.json, keeping tab indentation. */
function update_theme_json( callable $mutate ): void {
	$file = THEME_DIR . '/theme.json';
	$json = json_decode( (string) file_get_contents( $file ), true, 512, JSON_THROW_ON_ERROR );
	$json = $mutate( $json );
	$out  = json_encode( $json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	$out  = preg_replace_callback( '/^( {4})+/m', static fn( $m ) => str_repeat( "\t", strlen( $m[0] ) / 4 ), $out );
	file_put_contents( $file, $out . "\n" );
	ok( 'updated theme/theme.json' );
}

function require_slug( ?string $slug, string $pattern = '/^[a-z][a-z0-9_-]*$/' ): string {
	if ( ! $slug || ! preg_match( $pattern, $slug ) ) {
		fail( 'Invalid or missing slug. Use lowercase letters, digits, "-" or "_".' );
	}
	return $slug;
}

/* -------------------------------------------------------------------------
 * Shared block-template building blocks.
 * ---------------------------------------------------------------------- */

const MAIN_OPEN = <<<'HTML'
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<main class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">

HTML;

const MAIN_CLOSE = <<<'HTML'
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->

HTML;

const SINGLE_BODY = <<<'HTML'
	<!-- wp:post-title {"level":1} /-->
	<!-- wp:post-featured-image {"align":"wide"} /-->
	<!-- wp:post-content {"layout":{"type":"constrained"}} /-->

HTML;

const ARCHIVE_BODY = <<<'HTML'
	<!-- wp:query-title {"type":"archive","align":"wide"} /-->
	<!-- wp:query {"queryId":1,"query":{"inherit":true,"perPage":12},"align":"wide"} -->
	<div class="wp-block-query alignwide">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->
			<!-- wp:post-title {"isLink":true,"level":2,"fontSize":"large"} /-->
			<!-- wp:post-excerpt {"excerptLength":20} /-->
		<!-- /wp:post-template -->
		<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
			<!-- wp:query-pagination-previous /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->
		<!-- wp:query-no-results -->
			<!-- wp:paragraph -->
			<p>Nothing found.</p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->

HTML;

function block_template( string $body ): string {
	return MAIN_OPEN . $body . MAIN_CLOSE;
}

/* -------------------------------------------------------------------------
 * Commands.
 * ---------------------------------------------------------------------- */

switch ( $command ) {

	case 'post-type':
		$slug = require_slug( $pos[1] ?? null, '/^[a-z][a-z0-9_-]{0,19}$/' );
		$singular = (string) ( $flags['singular'] ?? humanize( $slug ) );
		$plural   = (string) ( $flags['plural'] ?? pluralize( $singular ) );
		$archive  = strtolower( str_replace( ' ', '-', $plural ) );
		$icon     = (string) ( $flags['icon'] ?? 'dashicons-admin-post' );

		put(
			"config/post-types/{$slug}.php",
			tpl(
				<<<'PHP'
<?php
/**
 * Post type: {{singular}}.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'slug'     => {{slug}},
	'singular' => __( {{singular}}, 'kelurahan' ),
	'plural'   => __( {{plural}}, 'kelurahan' ),
	'args'     => array(
		'menu_icon'   => {{icon}},
		'rewrite'     => array( 'slug' => {{archive}} ),
		'has_archive' => {{archive}},
	),
);

PHP,
				array(
					'slug'     => q( $slug ),
					'singular' => q( $singular ),
					'plural'   => q( $plural ),
					'icon'     => q( $icon ),
					'archive'  => q( $archive ),
				)
			)
		);

		if ( empty( $flags['no-templates'] ) ) {
			put( "templates/single-{$slug}.html", block_template( SINGLE_BODY ) );
			put( "templates/archive-{$slug}.html", block_template( ARCHIVE_BODY ) );
		}
		info( "Next: add fields with  php bin/make.php meta {$slug} field_key:text:Label" );
		info( "URLs: /{$archive}/ and /{$archive}/<post-slug>/" );
		break;

	case 'taxonomy':
		$slug = require_slug( $pos[1] ?? null, '/^[a-z][a-z0-9_]{0,31}$/' );
		if ( empty( $flags['post-types'] ) || true === $flags['post-types'] ) {
			fail( 'taxonomy needs --post-types=type1,type2' );
		}
		$singular = (string) ( $flags['singular'] ?? humanize( $slug ) );
		$plural   = (string) ( $flags['plural'] ?? pluralize( $singular ) );
		$types    = implode( ', ', array_map( 'q', array_map( 'trim', explode( ',', (string) $flags['post-types'] ) ) ) );

		put(
			'config/taxonomies/' . str_replace( '_', '-', $slug ) . '.php',
			tpl(
				<<<'PHP'
<?php
/**
 * Taxonomy: {{singular}}.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'slug'       => {{slug}},
	'singular'   => __( {{singular}}, 'kelurahan' ),
	'plural'     => __( {{plural}}, 'kelurahan' ),
	'post_types' => array( {{types}} ),
	'args'       => array(
		'hierarchical' => {{hierarchical}},
		'rewrite'      => array( 'slug' => {{rewrite}} ),
	),
);

PHP,
				array(
					'slug'         => q( $slug ),
					'singular'     => q( $singular ),
					'plural'       => q( $plural ),
					'types'        => $types,
					'hierarchical' => empty( $flags['flat'] ) ? 'true' : 'false',
					'rewrite'      => q( str_replace( '_', '-', $slug ) ),
				)
			)
		);
		info( "Optional: templates/taxonomy-{$slug}.html via  php bin/make.php template taxonomy-{$slug}" );
		break;

	case 'meta':
		$post_type = require_slug( $pos[1] ?? null );
		$specs     = array_slice( $pos, 2 );
		if ( ! $specs ) {
			fail( 'meta needs at least one field: key:type[:Label]' );
		}
		$valid = array( 'text', 'textarea', 'url', 'email', 'date', 'time', 'select', 'number', 'integer', 'checkbox' );
		$lines   = array();
		$key_pad = max( array_map( static fn( $s ) => strlen( explode( ':', $s, 2 )[0] ), $specs ) ) + 2;
		foreach ( $specs as $spec ) {
			$parts = explode( ':', $spec, 3 );
			$key   = $parts[0];
			$type  = $parts[1] ?? 'text';
			$label = $parts[2] ?? humanize( preg_replace( '/^' . preg_quote( $post_type, '/' ) . '_/', '', $key ) );
			if ( ! preg_match( '/^[a-z][a-z0-9_]*$/', $key ) ) {
				fail( "Bad meta key '{$key}': lowercase, digits, underscores; must not start with '_'." );
			}
			if ( ! in_array( $type, $valid, true ) ) {
				fail( "Unknown type '{$type}'. Valid: " . implode( ' ', $valid ) );
			}
			// Align "=>" to the longest key (WPCS): 'options' only exists for selects.
			$pad    = 'select' === $type ? 7 : 5;
			$field  = "\t\t" . str_pad( q( $key ), $key_pad ) . " => array(\n";
			$field .= "\t\t\t" . str_pad( "'label'", $pad + 2 ) . ' => __( ' . q( $label ) . ", 'kelurahan' ),\n";
			$field .= "\t\t\t" . str_pad( "'type'", $pad + 2 ) . ' => ' . q( $type ) . ",\n";
			if ( 'select' === $type ) {
				$field .= "\t\t\t'options' => array(\n\t\t\t\t'option-a' => __( 'Option A', 'kelurahan' ),\n\t\t\t\t'option-b' => __( 'Option B', 'kelurahan' ),\n\t\t\t),\n";
			}
			$field  .= "\t\t),";
			$lines[] = $field;
		}

		put(
			"config/meta/{$post_type}.php",
			tpl(
				<<<'PHP'
<?php
/**
 * Custom fields for post type: {{post_type}}.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

return array(
	'post_type' => {{post_type_q}},
	'title'     => __( {{title}}, 'kelurahan' ),
	'context'   => {{context}},
	'fields'    => array(
{{fields}}
	),
);

PHP,
				array(
					'post_type'   => $post_type,
					'post_type_q' => q( $post_type ),
					'title'       => q( (string) ( $flags['title'] ?? humanize( $post_type ) . ' Details' ) ),
					'context'     => q( (string) ( $flags['context'] ?? 'normal' ) ),
					'fields'      => implode( "\n", $lines ),
				)
			)
		);
		info( 'Display in a block template with a paragraph bound to core/post-meta (see docs/recipes/add-meta-fields.md).' );
		break;

	case 'block':
		$name  = require_slug( $pos[1] ?? null, '/^[a-z][a-z0-9-]*$/' );
		$title = (string) ( $flags['title'] ?? humanize( $name ) );
		$class = 'kelurahan-' . $name;

		put(
			"blocks/{$name}/block.json",
			json_encode(
				array(
					'$schema'      => 'https://schemas.wp.org/trunk/block.json',
					'apiVersion'   => 3,
					'name'         => "kelurahan/{$name}",
					'version'      => '0.1.0',
					'title'        => $title,
					'category'     => 'kelurahan',
					'icon'         => 'block-default',
					'description'  => "{$title} block. Server-rendered.",
					'textdomain'   => 'kelurahan',
					'attributes'   => array(
						'heading' => array(
							'type'    => 'string',
							'default' => $title,
						),
					),
					'supports'     => array(
						'html'    => false,
						'align'   => array( 'wide', 'full' ),
						'spacing' => array( 'margin' => true, 'padding' => true ),
						'color'   => array( 'background' => true, 'text' => true ),
					),
					'editorScript' => 'file:./index.js',
					'style'        => 'file:./style.css',
					'render'       => 'file:./render.php',
				),
				JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
			) . "\n"
		);

		put(
			"blocks/{$name}/index.asset.php",
			<<<'PHP'
<?php
/**
 * Script dependencies for index.js (hand-maintained; no build step).
 *
 * @package ProfilKelurahan
 */

return array(
	'dependencies' => array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
	'version'      => '0.1.0',
);

PHP
		);

		put(
			"blocks/{$name}/index.js",
			tpl(
				<<<'JS'
/**
 * Editor script for kelurahan/{{name}}. Plain JS, wp.* globals, no build.
 */
( function ( wp ) {
	const { registerBlockType } = wp.blocks;
	const { createElement: el, Fragment } = wp.element;
	const { InspectorControls, useBlockProps } = wp.blockEditor;
	const { PanelBody, TextControl } = wp.components;
	const ServerSideRender = wp.serverSideRender;
	const { __ } = wp.i18n;

	registerBlockType( 'kelurahan/{{name}}', {
		edit( { attributes, setAttributes } ) {
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Settings', 'kelurahan' ) },
						el( TextControl, {
							label: __( 'Heading', 'kelurahan' ),
							value: attributes.heading,
							onChange: ( heading ) => setAttributes( { heading } ),
						} )
					)
				),
				el(
					'div',
					useBlockProps(),
					el( ServerSideRender, { block: 'kelurahan/{{name}}', attributes } )
				)
			);
		},
		save: () => null,
	} );
} )( window.wp );

JS,
				array( 'name' => $name )
			)
		);

		put(
			"blocks/{$name}/render.php",
			tpl(
				<<<'PHP'
<?php
/**
 * Server render for kelurahan/{{name}}.
 *
 * Variables: $attributes (array), $content (string), $block (WP_Block).
 * Escape everything you output.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => '{{class}}' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<h2 class="{{class}}__heading"><?php echo esc_html( $attributes['heading'] ?? '' ); ?></h2>
</div>

PHP,
				array( 'name' => $name, 'class' => $class )
			)
		);

		put( "blocks/{$name}/style.css", ".{$class}__heading {\n\tmargin: 0;\n}\n" );
		info( "Insert with: <!-- wp:kelurahan/{$name} {\"heading\":\"Hi\"} /-->" );
		break;

	case 'pattern':
		$slug       = require_slug( $pos[1] ?? null, '/^[a-z][a-z0-9-]*$/' );
		$title      = (string) ( $flags['title'] ?? humanize( $slug ) );
		$categories = (string) ( $flags['categories'] ?? 'kelurahan-sections' );

		put(
			"patterns/{$slug}.php",
			tpl(
				<<<'PHP'
<?php
/**
 * Title: {{title}}
 * Slug: kelurahan/{{slug}}
 * Categories: {{categories}}
 * Keywords: {{slug}}
 * Description: {{title}} section.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide">
	<!-- wp:heading -->
	<h2 class="wp-block-heading"><?php echo esc_html__( '{{title}}', 'kelurahan' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph -->
	<p><?php echo esc_html__( 'Replace this text.', 'kelurahan' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

PHP,
				array( 'title' => str_replace( "'", '’', $title ), 'slug' => $slug, 'categories' => $categories )
			)
		);
		info( "Use in content/templates: <!-- wp:pattern {\"slug\":\"kelurahan/{$slug}\"} /-->" );
		break;

	case 'template':
		$slug = require_slug( $pos[1] ?? null, '/^[a-z0-9][a-z0-9_-]*$/' );
		$body = ( str_starts_with( $slug, 'archive' ) || str_starts_with( $slug, 'taxonomy' ) || str_starts_with( $slug, 'category' ) || str_starts_with( $slug, 'tag' ) || in_array( $slug, array( 'home', 'index', 'search', 'author', 'date' ), true ) )
			? ARCHIVE_BODY
			: SINGLE_BODY;
		put( "templates/{$slug}.html", block_template( $body ) );
		break;

	case 'custom-template':
		$slug  = require_slug( $pos[1] ?? null, '/^[a-z0-9][a-z0-9-]*$/' );
		$title = (string) ( $flags['title'] ?? humanize( $slug ) );
		$types = array_map( 'trim', explode( ',', (string) ( $flags['post-types'] ?? 'page' ) ) );
		put( "templates/{$slug}.html", block_template( SINGLE_BODY ) );
		update_theme_json(
			static function ( array $json ) use ( $slug, $title, $types ) {
				$json['customTemplates'] = array_values(
					array_filter( $json['customTemplates'] ?? array(), static fn( $t ) => $t['name'] !== $slug )
				);
				$json['customTemplates'][] = array( 'name' => $slug, 'title' => $title, 'postTypes' => $types );
				return $json;
			}
		);
		break;

	case 'page-template':
		$slug  = require_slug( $pos[1] ?? null, '/^[a-z0-9][a-z0-9-]*$/' );
		$title = (string) ( $flags['title'] ?? humanize( $slug ) . ' (PHP)' );
		$types = (string) ( $flags['post-types'] ?? 'page' );

		put(
			"page-templates/template-{$slug}.php",
			tpl(
				<<<'PHP'
<?php
/**
 * Template Name: {{title}}
 * Template Post Type: {{types}}
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="wp-block-group is-layout-constrained has-global-padding">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'entry-content is-layout-constrained' ); ?>>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();

PHP,
				array( 'title' => $title, 'types' => $types )
			)
		);
		info( "Assign via CLI: wp post update <id> --page_template=page-templates/template-{$slug}.php" );
		break;

	case 'part':
		$slug  = require_slug( $pos[1] ?? null, '/^[a-z0-9][a-z0-9-]*$/' );
		$title = (string) ( $flags['title'] ?? humanize( $slug ) );
		$area  = (string) ( $flags['area'] ?? 'uncategorized' );
		put(
			"parts/{$slug}.html",
			"<!-- wp:group {\"layout\":{\"type\":\"constrained\"}} -->\n<div class=\"wp-block-group\">\n\t<!-- wp:paragraph -->\n\t<p>{$title}</p>\n\t<!-- /wp:paragraph -->\n</div>\n<!-- /wp:group -->\n"
		);
		update_theme_json(
			static function ( array $json ) use ( $slug, $title, $area ) {
				$json['templateParts'] = array_values(
					array_filter( $json['templateParts'] ?? array(), static fn( $p ) => $p['name'] !== $slug )
				);
				$json['templateParts'][] = array( 'name' => $slug, 'title' => $title, 'area' => $area );
				return $json;
			}
		);
		info( "Use: <!-- wp:template-part {\"slug\":\"{$slug}\"} /-->  or in PHP: kelurahan_block_part( '{$slug}' );" );
		break;

	default:
		$doc = file_get_contents( __FILE__ );
		preg_match( '#/\*\*(.*?)\*/#s', $doc, $m );
		echo preg_replace( '/^\s*\* ?/m', '', $m[1] ), "\n";
		exit( 'help' === $command ? 0 : 1 );
}
