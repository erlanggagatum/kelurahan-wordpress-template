<?php
/**
 * Rebrand the template for a new site. Run once, right after copying the repo.
 *
 *   php bin/rename.php "Acme Theme" acme [--prefix=acme] [--strip-examples] [--dry-run]
 *
 * Replaces, across theme/, bin/, docs/ and root config/docs:
 *   Agent Theme  -> "Acme Theme"            (display name)
 *   AgentTheme   -> AcmeTheme               (@package)
 *   agent-theme  -> acme                    (theme slug, text domain, block namespace, pattern slugs)
 *   agent_theme  -> acme                    (PHP function/option prefix)
 *   AGENT_THEME  -> ACME                    (PHP constants)
 *   agent_x / agent-x (short prefixes, e.g. $agent_args, .agent-card) -> acme_x / acme-x
 *
 * --strip-examples  delete the Event demo (CPT, taxonomy, fields, templates, block).
 * --dry-run         show what would change, write nothing.
 */

declare(strict_types=1);

require_once __DIR__ . '/lib.php';

[ $pos, $flags ] = parse_args( $argv );
$name            = $pos[0] ?? '';
$slug            = $pos[1] ?? '';
$dry             = ! empty( $flags['dry-run'] );

if ( '' === $name || ! preg_match( '/^[a-z][a-z0-9-]{1,30}$/', $slug ) ) {
	fail( "Usage: php bin/rename.php \"Display Name\" slug [--prefix=php_prefix] [--strip-examples] [--dry-run]\n  slug: lowercase letters, digits, hyphens (2-31 chars), e.g. acme or acme-site" );
}
if ( str_contains( $name, '"' ) || str_contains( $name, "'" ) || str_contains( $name, '\\' ) ) {
	fail( 'Display name must not contain quotes or backslashes.' );
}

$prefix = is_string( $flags['prefix'] ?? null ) ? $flags['prefix'] : str_replace( '-', '_', $slug );
if ( ! preg_match( '/^[a-z][a-z0-9_]{1,30}$/', $prefix ) ) {
	fail( "Invalid --prefix '{$prefix}': lowercase letters, digits, underscores." );
}
if ( in_array( $prefix, array( 'wp', 'wordpress', 'agent', 'theme', 'admin' ), true ) ) {
	fail( "Prefix '{$prefix}' is too generic; pick something unique to this site." );
}

$studly = preg_replace( '/[^A-Za-z0-9]/', '', ucwords( $name ) );
$upper  = strtoupper( $prefix );

// Order matters: longest / most specific tokens first.
$replacements = array(
	'AGENT_THEME' => $upper,
	'agent_theme' => $prefix,
	'agent-theme' => $slug,
	'AgentTheme'  => $studly,
	'Agent Theme' => $name,
);
$regexes = array(
	'/\bagent_/' => $prefix . '_',   // $agent_args, agent_seed_post().
	'/\bagent-/' => $slug . '-',     // .agent-card, 'agent-' . $name.
	'/<element value="agent"\/>/' => '<element value="' . $prefix . '"/>', // phpcs.xml prefixes.
);

// Files to process ----------------------------------------------------------
$targets = array( 'theme', 'bin', 'docs', 'AGENTS.md', 'CLAUDE.md', 'README.md', 'package.json', 'composer.json', 'phpcs.xml.dist', '.wp-env.json', '.env.example', '.env' );
$exts    = array( 'php', 'html', 'json', 'css', 'js', 'md', 'xml', 'dist', 'example', 'env', 'yml', 'txt' );
$self    = realpath( __FILE__ );

$files = array();
foreach ( $targets as $t ) {
	$path = ROOT . '/' . $t;
	if ( is_file( $path ) ) {
		$files[] = $path;
	} elseif ( is_dir( $path ) ) {
		$it = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path, FilesystemIterator::SKIP_DOTS ) );
		foreach ( $it as $f ) {
			$ext = strtolower( $f->getExtension() );
			if ( $f->isFile() && ( in_array( $ext, $exts, true ) || '' === $ext ) ) {
				$files[] = $f->getPathname();
			}
		}
	}
}
$files = array_values( array_filter( $files, static fn( $f ) => realpath( $f ) !== $self ) );

// Sanity: has this already been renamed? -----------------------------------
$already = true;
foreach ( $files as $f ) {
	if ( str_contains( (string) file_get_contents( $f ), 'agent-theme' ) ) {
		$already = false;
		break;
	}
}
if ( $already ) {
	fail( "No 'agent-theme' found. This copy is already renamed." );
}

// Strip examples (before rewriting, so paths are the original ones) ---------
if ( ! empty( $flags['strip-examples'] ) ) {
	$examples = array(
		'theme/config/post-types/event.php',
		'theme/config/taxonomies/event-type.php',
		'theme/config/meta/event.php',
		'theme/templates/single-event.html',
		'theme/templates/archive-event.html',
		'theme/taxonomy-event_type.php',
		'theme/blocks/event-list',
	);
	foreach ( $examples as $rel_path ) {
		$abs = ROOT . '/' . $rel_path;
		if ( ! file_exists( $abs ) ) {
			continue;
		}
		if ( $dry ) {
			info( "would delete {$rel_path}" );
			continue;
		}
		if ( is_dir( $abs ) ) {
			foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $abs, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST ) as $f ) {
				$f->isDir() ? rmdir( $f->getPathname() ) : unlink( $f->getPathname() );
			}
			rmdir( $abs );
		} else {
			unlink( $abs );
		}
		ok( "deleted {$rel_path}" );
	}
	$files = array_values( array_filter( $files, 'file_exists' ) );
}

// Rewrite --------------------------------------------------------------------
$changed = 0;
foreach ( $files as $f ) {
	$before = (string) file_get_contents( $f );
	$after  = strtr( $before, $replacements );
	$after  = preg_replace( array_keys( $regexes ), array_values( $regexes ), $after );
	if ( $after === $before ) {
		continue;
	}
	++$changed;
	if ( $dry ) {
		info( 'would update ' . rel( $f ) );
	} else {
		file_put_contents( $f, $after );
	}
}
$dry ? info( "{$changed} file(s) would change." ) : ok( "Updated {$changed} file(s)." );

if ( $dry ) {
	exit( 0 );
}

// Old theme link inside .wp/ (if setup already ran) --------------------------
$old_link = WP_DIR . '/wp-content/themes/agent-theme';
if ( is_link( $old_link ) || ( PHP_OS_FAMILY === 'Windows' && is_dir( $old_link ) && realpath( $old_link ) === realpath( THEME_DIR ) ) ) {
	// Removes only the link/junction, never the theme files.
	( PHP_OS_FAMILY === 'Windows' ) ? @rmdir( $old_link ) : @unlink( $old_link );
	ok( 'Removed old theme link .wp/wp-content/themes/agent-theme' );
}

// Verify ---------------------------------------------------------------------
passthru( escapeshellarg( PHP_BINARY ) . ' ' . escapeshellarg( __DIR__ . '/check.php' ), $code );
if ( 0 !== $code ) {
	fail( 'Rename finished but checks failed. See above.' );
}

echo "\n";
ok( "Theme is now \"{$name}\" (slug {$slug}, prefix {$prefix}_)." );
info( 'Next: npm run setup   (links + activates the renamed theme; safe to re-run)' );
