<?php
/**
 * Verification gate for agents and humans. Exit code 0 = all good.
 *
 *   php bin/check.php            static checks (no WordPress needed)
 *   php bin/check.php --wp       + runtime checks through WP-CLI (needs setup done)
 *   php bin/check.php --http     + fetch key URLs from the running server (needs `npm start`)
 *
 * Static: PHP lint, JSON validity, block markup balance, template-part refs,
 *         config shape (slugs, meta keys, field types), pattern headers.
 */

declare(strict_types=1);

require_once __DIR__ . '/lib.php';

[ , $flags ] = parse_args( $argv );
$errors      = array();
$warnings    = array();

function err( string $m ): void {
	global $errors;
	$errors[] = $m;
}

/** @return string[] */
function files( string $dir, string $ext ): array {
	if ( ! is_dir( $dir ) ) {
		return array();
	}
	$out = array();
	$it  = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ) );
	foreach ( $it as $f ) {
		if ( $f->isFile() && strtolower( $f->getExtension() ) === $ext ) {
			$out[] = $f->getPathname();
		}
	}
	sort( $out );
	return $out;
}

// 1. PHP lint -----------------------------------------------------------------
$php_files = array_merge( files( THEME_DIR, 'php' ), files( ROOT . '/bin', 'php' ) );
foreach ( $php_files as $file ) {
	exec( escapeshellarg( PHP_BINARY ) . ' -l ' . escapeshellarg( $file ) . ' 2>&1', $out, $code );
	if ( 0 !== $code ) {
		err( 'PHP syntax: ' . rel( $file ) . ' — ' . trim( implode( ' ', $out ) ) );
	}
	$out = array();
}
ok( 'PHP lint: ' . count( $php_files ) . ' files' );

// 2. JSON ---------------------------------------------------------------------
$json_files = array_merge( array( THEME_DIR . '/theme.json' ), glob( THEME_DIR . '/blocks/*/block.json' ) ?: array() );
foreach ( $json_files as $file ) {
	json_decode( (string) file_get_contents( $file ) );
	if ( JSON_ERROR_NONE !== json_last_error() ) {
		err( 'JSON: ' . rel( $file ) . ' — ' . json_last_error_msg() );
	}
}
$theme_json = json_decode( (string) file_get_contents( THEME_DIR . '/theme.json' ), true ) ?: array();
ok( 'JSON: ' . count( $json_files ) . ' files' );

// 3. Block markup -------------------------------------------------------------
$parts_declared = array_column( $theme_json['templateParts'] ?? array(), 'name' );
$part_files     = array_map( static fn( $f ) => basename( $f, '.html' ), glob( THEME_DIR . '/parts/*.html' ) ?: array() );
$markup_files   = array_merge(
	glob( THEME_DIR . '/templates/*.html' ) ?: array(),
	glob( THEME_DIR . '/parts/*.html' ) ?: array(),
	glob( THEME_DIR . '/patterns/*.php' ) ?: array()
);

foreach ( $markup_files as $file ) {
	$html  = (string) file_get_contents( $file );
	$stack = array();
	preg_match_all( '#<!--\s+(/?)wp:([a-z0-9/-]+)(\s+(\{.*?\}))?\s*(/?)-->#s', $html, $matches, PREG_SET_ORDER );

	foreach ( $matches as $m ) {
		[ , $closing, $name ] = $m;
		$attrs                = $m[4] ?? '';
		$self_close           = '/' === ( $m[5] ?? '' );

		if ( '' !== $attrs && null === json_decode( $attrs ) ) {
			err( rel( $file ) . ": invalid JSON attributes in wp:{$name}" );
		}
		if ( 'template-part' === $name && preg_match( '/"slug":"([^"]+)"/', $attrs, $s ) && ! in_array( $s[1], $part_files, true ) ) {
			err( rel( $file ) . ": template-part '{$s[1]}' has no parts/{$s[1]}.html" );
		}
		if ( $self_close ) {
			continue;
		}
		if ( '/' === $closing ) {
			$open = array_pop( $stack );
			if ( $open !== $name ) {
				err( rel( $file ) . ": closing wp:{$name} but open block is " . ( $open ? "wp:{$open}" : 'none' ) );
			}
		} else {
			$stack[] = $name;
		}
	}
	if ( $stack ) {
		err( rel( $file ) . ': unclosed blocks: wp:' . implode( ', wp:', $stack ) );
	}
}
foreach ( $part_files as $part ) {
	if ( ! in_array( $part, $parts_declared, true ) ) {
		$warnings[] = "parts/{$part}.html not declared in theme.json templateParts (it will show as 'General')";
	}
}
foreach ( $theme_json['customTemplates'] ?? array() as $t ) {
	if ( ! is_file( THEME_DIR . "/templates/{$t['name']}.html" ) ) {
		err( "theme.json customTemplates '{$t['name']}' has no templates/{$t['name']}.html" );
	}
}
if ( ! is_file( THEME_DIR . '/templates/index.html' ) ) {
	err( 'templates/index.html is required for a block theme' );
}
ok( 'Block markup: ' . count( $markup_files ) . ' files' );

// 4. Config shape (load configs with WP function stubs) ---------------------
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ );
}
if ( ! function_exists( '__' ) ) {
	function __( $s ) { return $s; } // phpcs:ignore
}
$load = static function ( string $dir ): array {
	$out = array();
	foreach ( glob( THEME_DIR . "/config/{$dir}/*.php" ) ?: array() as $f ) {
		if ( ! str_starts_with( basename( $f ), '_' ) ) {
			$out[ rel( $f ) ] = require $f;
		}
	}
	return $out;
};

$post_types = array();
foreach ( $load( 'post-types' ) as $f => $c ) {
	if ( ! is_array( $c ) || empty( $c['slug'] ) || empty( $c['singular'] ) || empty( $c['plural'] ) ) {
		err( "{$f}: must return array with slug, singular, plural" );
		continue;
	}
	if ( ! preg_match( '/^[a-z0-9_-]{1,20}$/', $c['slug'] ) ) {
		err( "{$f}: slug '{$c['slug']}' must be 1-20 chars [a-z0-9_-]" );
	}
	if ( in_array( $c['slug'], array( 'post', 'page', 'attachment', 'revision', 'nav_menu_item', 'action', 'author', 'order', 'theme' ), true ) ) {
		err( "{$f}: slug '{$c['slug']}' is reserved" );
	}
	$post_types[] = $c['slug'];
}
$known_types = array_merge( $post_types, array( 'post', 'page', 'attachment' ) );

foreach ( $load( 'taxonomies' ) as $f => $c ) {
	if ( ! is_array( $c ) || empty( $c['slug'] ) ) {
		err( "{$f}: must return array with slug, singular, plural, post_types" );
		continue;
	}
	if ( ! preg_match( '/^[a-z0-9_]{1,32}$/', $c['slug'] ) ) {
		err( "{$f}: slug '{$c['slug']}' must be 1-32 chars [a-z0-9_]" );
	}
	if ( in_array( $c['slug'], array( 'category', 'post_tag', 'tag', 'type', 'name', 'year', 'page', 'author', 'term', 'taxonomy' ), true ) ) {
		err( "{$f}: slug '{$c['slug']}' is reserved" );
	}
	foreach ( $c['post_types'] ?? array() as $pt ) {
		if ( ! in_array( $pt, $known_types, true ) ) {
			$warnings[] = "{$f}: post type '{$pt}' is not defined in config/post-types (fine if registered by a plugin)";
		}
	}
}

$field_types = array( 'text', 'textarea', 'url', 'email', 'date', 'time', 'select', 'number', 'integer', 'checkbox' );
$seen_keys   = array();
foreach ( $load( 'meta' ) as $f => $c ) {
	foreach ( (array) ( $c['post_type'] ?? array() ) as $pt ) {
		if ( ! in_array( $pt, $known_types, true ) ) {
			$warnings[] = "{$f}: post type '{$pt}' is not defined in config/post-types";
		}
	}
	foreach ( $c['fields'] ?? array() as $key => $field ) {
		if ( ! preg_match( '/^[a-z][a-z0-9_]*$/', (string) $key ) ) {
			err( "{$f}: meta key '{$key}' must be [a-z][a-z0-9_]* (no leading underscore)" );
		}
		if ( ! in_array( $field['type'] ?? 'text', $field_types, true ) ) {
			err( "{$f}: field '{$key}' has unknown type '{$field['type']}'" );
		}
		if ( 'select' === ( $field['type'] ?? '' ) && empty( $field['options'] ) ) {
			err( "{$f}: select field '{$key}' needs options" );
		}
		if ( isset( $seen_keys[ $key ] ) ) {
			$warnings[] = "{$f}: meta key '{$key}' also defined in {$seen_keys[$key]}";
		}
		$seen_keys[ $key ] = $f;
	}
}

// Meta keys bound in templates must exist.
foreach ( glob( THEME_DIR . '/templates/*.html' ) ?: array() as $file ) {
	preg_match_all( '/"source":"core\/post-meta","args":\{"key":"([^"]+)"\}/', (string) file_get_contents( $file ), $mm );
	foreach ( $mm[1] as $key ) {
		if ( ! isset( $seen_keys[ $key ] ) ) {
			err( rel( $file ) . ": binds meta '{$key}' which no config/meta file defines" );
		}
	}
}
ok( 'Config: ' . count( $post_types ) . ' post types, ' . count( $seen_keys ) . ' meta fields' );

// 4b. Modules (config/modules.php) and module IDs used in the theme ----------
$modules_file = THEME_DIR . '/config/modules.php';
$module_ids   = array();
if ( is_file( $modules_file ) ) {
	$modules = require $modules_file;
	$f       = rel( $modules_file );
	if ( ! is_array( $modules ) ) {
		err( "{$f}: must return an array" );
		$modules = array();
	}
	$id_re       = '/^[a-z0-9]+(-[a-z0-9]+)*$/';
	$paths       = array();
	$check_entry = static function ( string $id, $m, bool $top ) use ( $f, &$paths ): void {
		if ( ! is_array( $m ) || ! is_string( $m['label'] ?? null ) || '' === $m['label'] || ! is_bool( $m['enabled'] ?? null ) ) {
			err( "{$f}: module '{$id}' needs a non-empty 'label' and a bool 'enabled'" );
			return;
		}
		if ( $top && ! in_array( $m['group'] ?? null, array( 'inti', 'pendukung' ), true ) ) {
			err( "{$f}: module '{$id}' needs 'group' => 'inti' or 'pendukung'" );
		}
		if ( isset( $m['path'] ) ) {
			if ( ! is_string( $m['path'] ) || ! preg_match( '#^/([a-z0-9-]+/)*$#', $m['path'] ) ) {
				err( "{$f}: module '{$id}' path must look like '/a/b/' (lowercase, leading and trailing slash)" );
			} elseif ( isset( $paths[ $m['path'] ] ) ) {
				err( "{$f}: module '{$id}' path {$m['path']} is already used by '{$paths[ $m['path'] ]}'" );
			} else {
				$paths[ $m['path'] ] = $id;
			}
		}
	};
	foreach ( $modules as $id => $m ) {
		if ( ! is_string( $id ) || ! preg_match( $id_re, $id ) ) {
			err( "{$f}: module ID '{$id}' must be kebab-case [a-z0-9-]" );
			continue;
		}
		$module_ids[] = $id;
		$check_entry( $id, $m, true );
		if ( ! empty( $m['required'] ) && empty( $m['enabled'] ) ) {
			err( "{$f}: module '{$id}' is required and cannot be disabled" );
		}
		foreach ( (array) ( $m['children'] ?? array() ) as $child => $c ) {
			if ( ! is_string( $child ) || ! preg_match( $id_re, $child ) ) {
				err( "{$f}: sub-module ID '{$id}.{$child}' must be kebab-case [a-z0-9-]" );
				continue;
			}
			$module_ids[] = "{$id}.{$child}";
			$check_entry( "{$id}.{$child}", $c, false );
		}
	}
	foreach ( $modules as $id => $m ) {
		foreach ( (array) ( $m['requires'] ?? array() ) as $dep ) {
			if ( $dep === $id || ! in_array( $dep, $module_ids, true ) ) {
				err( "{$f}: module '{$id}' requires unknown or self module '{$dep}'" );
			}
		}
	}

	// Every module ID referenced in theme code/markup must exist.
	$scan = array_merge( files( THEME_DIR, 'php' ), files( THEME_DIR, 'html' ) );
	foreach ( $scan as $file ) {
		if ( in_array( rel( $file ), array( 'theme/config/modules.php', 'theme/inc/modules.php' ), true ) ) {
			continue; // The declaration itself and the engine (docblock examples).
		}
		$src  = (string) file_get_contents( $file );
		$used = array();
		if ( preg_match_all( "/kelurahan_module(?:_enabled|_url)?\(\s*['\"]([a-z0-9.-]+)['\"]/", $src, $mm ) ) {
			$used = $mm[1];
		}
		if ( preg_match_all( '/(?<![\w-])kelurahan-module-([a-z0-9]+(?:-[a-z0-9]+|--[a-z0-9]+)*)/', $src, $mm ) ) {
			foreach ( $mm[1] as $slug ) {
				$used[] = str_replace( '--', '.', $slug );
			}
		}
		foreach ( array_unique( $used ) as $used_id ) {
			if ( ! in_array( $used_id, $module_ids, true ) ) {
				err( rel( $file ) . ": unknown module ID '{$used_id}' (not in config/modules.php)" );
			}
		}
	}
}
ok( 'Modules: ' . count( $module_ids ) . ' IDs' );

// 5. Patterns & page templates headers ---------------------------------------
foreach ( glob( THEME_DIR . '/patterns/*.php' ) ?: array() as $file ) {
	$head = (string) file_get_contents( $file, false, null, 0, 2048 );
	if ( ! preg_match( '/^\s*\*\s*Title:/m', $head ) || ! preg_match( '/^\s*\*\s*Slug:\s*kelurahan\//m', $head ) ) {
		err( rel( $file ) . ': pattern header needs "Title:" and "Slug: kelurahan/<slug>"' );
	}
}
foreach ( glob( THEME_DIR . '/page-templates/*.php' ) ?: array() as $file ) {
	if ( ! preg_match( '/Template Name:/', (string) file_get_contents( $file, false, null, 0, 2048 ) ) ) {
		err( rel( $file ) . ': page template needs a "Template Name:" header' );
	}
}
foreach ( glob( THEME_DIR . '/blocks/*', GLOB_ONLYDIR ) ?: array() as $dir ) {
	$block = json_decode( (string) @file_get_contents( "{$dir}/block.json" ), true );
	if ( ! $block ) {
		err( rel( $dir ) . ': missing/invalid block.json' );
		continue;
	}
	foreach ( array( 'editorScript', 'script', 'viewScript', 'style', 'editorStyle', 'render' ) as $k ) {
		foreach ( (array) ( $block[ $k ] ?? array() ) as $ref ) {
			if ( str_starts_with( $ref, 'file:' ) && ! is_file( $dir . '/' . substr( $ref, 7 ) ) ) {
				err( rel( $dir ) . "/block.json: {$k} points to missing {$ref}" );
			}
		}
	}
	if ( ! empty( $block['editorScript'] ) && ! is_file( "{$dir}/index.asset.php" ) ) {
		$warnings[] = rel( $dir ) . ': no index.asset.php — editor script will load without wp.* dependencies';
	}
}
ok( 'Patterns, page templates, blocks' );

// 5b. Privacy (paths, emails, secrets, denylist) -----------------------------
require_once __DIR__ . '/privacy-check.php';
foreach ( privacy_scan() as $finding ) {
	err( "privacy: {$finding}" );
}
ok( 'Privacy scan (' . count( privacy_denylist() ) . ' denylist terms)' );

// 6. Runtime (WordPress) ----------------------------------------------------------
if ( ! empty( $flags['wp'] ) || ! empty( $flags['http'] ) ) {
	if ( ! is_file( WP_CLI ) || ! is_file( WP_DIR . '/wp-config.php' ) ) {
		err( 'Runtime checks need setup: run  php bin/setup.php' );
	} else {
		$log      = WP_DIR . '/wp-content/debug.log';
		$log_size = is_file( $log ) ? filesize( $log ) : 0;

		[ $code, $out ] = wp( array( 'eval-file', __DIR__ . '/runtime-check.php' ) );
		echo $out, "\n";
		if ( 0 !== $code ) {
			err( 'Runtime check failed (see output above)' );
		}

		if ( ! empty( $flags['http'] ) ) {
			[ , $urls ] = wp( array( 'eval-file', __DIR__ . '/runtime-check.php', 'urls' ) );
			foreach ( array_filter( explode( "\n", $urls ) ) as $url ) {
				$ctx  = stream_context_create( array( 'http' => array( 'ignore_errors' => true, 'timeout' => 20 ) ) );
				$body = @file_get_contents( $url, false, $ctx );
				$status = isset( $http_response_header[0] ) ? (int) explode( ' ', $http_response_header[0] )[1] : 0;
				if ( 0 === $status ) {
					err( "HTTP: cannot reach {$url} — is the server running (npm start)?" );
					break;
				}
				if ( $status >= 400 ) {
					err( "HTTP {$status}: {$url}" );
				} elseif ( preg_match( '/(Fatal error|Warning|Notice|Deprecated)<\/b>:|There has been a critical error/', (string) $body ) ) {
					err( "PHP error output on {$url}" );
				} else {
					ok( "HTTP {$status}: {$url}" );
				}
			}
		}

		clearstatcache();
		if ( is_file( $log ) && filesize( $log ) > $log_size ) {
			$new = (string) file_get_contents( $log, false, null, $log_size );
			err( "New entries in .wp/wp-content/debug.log:\n" . substr( $new, 0, 4000 ) );
		}
	}
}

// 7. PHPCS (optional) -------------------------------------------------------
$phpcs = ROOT . '/vendor/bin/phpcs' . ( PHP_OS_FAMILY === 'Windows' ? '.bat' : '' );
if ( ! empty( $flags['phpcs'] ) ) {
	if ( is_file( $phpcs ) ) {
		passthru( escapeshellarg( $phpcs ) . ' --report=summary', $code );
		if ( 0 !== $code ) {
			err( 'PHPCS reported issues (run: composer lint for details, composer fix to auto-fix)' );
		}
	} else {
		$warnings[] = 'PHPCS not installed: run  composer install';
	}
}

// Summary -------------------------------------------------------------------
foreach ( $warnings as $w ) {
	warn( $w );
}
if ( $errors ) {
	foreach ( $errors as $e ) {
		fwrite( STDERR, "\033[31m✗\033[0m {$e}\n" );
	}
	fail( count( $errors ) . ' error(s).' );
}
ok( 'All checks passed.' );
