<?php
/**
 * Privacy / secret scan. Fails if repo files contain:
 *   - absolute machine paths (C:\Users\..., /home/..., /Users/...)
 *   - email addresses outside reserved example domains
 *   - secret-looking values (passwords, API keys, private keys)
 *   - any term in PRIVACY_DENYLIST from .env (local, git-ignored: personal names, usernames, hostnames)
 *
 * Usage: php bin/privacy-check.php      (also run by bin/check.php)
 * Exit code 0 = clean.
 */

declare(strict_types=1);

require_once __DIR__ . '/lib.php';

/**
 * Directories and files never scanned (git-ignored / generated / local-only).
 */
const PRIVACY_SKIP_DIRS  = array( '.git', '.wp', 'tools', 'vendor', 'node_modules' );
// Lock files are generated and list third-party package authors' emails.
const PRIVACY_SKIP_FILES = array( '.env', 'composer.lock', 'package-lock.json' );

/**
 * Email domains that are safe to commit (RFC 2606 reserved + no-reply style).
 */
const PRIVACY_ALLOWED_EMAIL = '/@(example\.(com|org|net|test)|[a-z0-9.-]+\.(test|example|invalid|localhost))$|^noreply@/i';

/**
 * Pattern name => regex. Matches are reported with file:line.
 */
const PRIVACY_PATTERNS = array(
	'absolute Windows user path' => '/\b[A-Za-z]:[\\\\\/]+(Users|Documents and Settings)[\\\\\/]+[^\\\\\/\s]+/i',
	'absolute Unix home path'    => '#(?<![\w.])/(home|Users)/(?!<)[A-Za-z0-9._-]+#',
	'private key'                => '/-----BEGIN [A-Z ]*PRIVATE KEY-----/',
	'API token'                  => '/\b(sk-ant-[A-Za-z0-9_-]{10,}|sk-[A-Za-z0-9]{20,}|ghp_[A-Za-z0-9]{20,}|github_pat_[A-Za-z0-9_]{20,}|AKIA[0-9A-Z]{16}|xox[baprs]-[A-Za-z0-9-]{10,})/',
	'hard-coded password'        => '/\b[A-Z_]*PASSWORD\s*=\s*[\'"]?(?!\s|$|[\'"]\s*$)[^\s\'"]{3,}/m',
);

/**
 * Collect scannable files.
 *
 * @return string[]
 */
function privacy_files(): array {
	$root  = realpath( ROOT );
	$files = array();
	$it    = new RecursiveIteratorIterator(
		new RecursiveCallbackFilterIterator(
			new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS ),
			static function ( SplFileInfo $f ) use ( $root ): bool {
				$rel = str_replace( '\\', '/', substr( $f->getPathname(), strlen( $root ) + 1 ) );
				if ( $f->isDir() ) {
					return ! in_array( explode( '/', $rel )[0], PRIVACY_SKIP_DIRS, true ) || str_contains( $rel, '/' );
				}
				return ! in_array( $rel, PRIVACY_SKIP_FILES, true );
			}
		)
	);
	foreach ( $it as $f ) {
		$rel = str_replace( '\\', '/', substr( $f->getPathname(), strlen( $root ) + 1 ) );
		if ( in_array( explode( '/', $rel )[0], PRIVACY_SKIP_DIRS, true ) ) {
			continue;
		}
		if ( $f->getSize() > 2_000_000 ) {
			continue; // Skip large binaries.
		}
		$files[ $rel ] = $f->getPathname();
	}
	ksort( $files );
	return $files;
}

/**
 * Denylist terms from PRIVACY_DENYLIST in .env (comma-separated).
 *
 * @return string[]
 */
function privacy_denylist(): array {
	$raw = load_env()['PRIVACY_DENYLIST'] ?? '';
	return array_values( array_filter( array_map( 'trim', explode( ',', $raw ) ), static fn( $t ) => strlen( $t ) >= 3 ) );
}

/**
 * Run the scan.
 *
 * @return string[] Findings as "file:line: reason". Never echoes the matched secret itself.
 */
function privacy_scan(): array {
	$findings = array();
	$deny     = privacy_denylist();

	foreach ( privacy_files() as $rel => $path ) {
		$text = (string) file_get_contents( $path );
		if ( str_contains( $text, "\0" ) ) {
			continue; // Binary.
		}
		foreach ( preg_split( '/\R/', $text ) as $i => $line ) {
			$n = $i + 1;

			foreach ( PRIVACY_PATTERNS as $label => $regex ) {
				if ( preg_match( $regex, $line ) ) {
					// Allow the scanner's own regex definitions and documented placeholders.
					if ( 'bin/privacy-check.php' === $rel ) {
						continue;
					}
					$findings[] = "{$rel}:{$n}: {$label}";
				}
			}

			if ( preg_match_all( '/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}/', $line, $m ) ) {
				foreach ( $m[0] as $email ) {
					if ( ! preg_match( PRIVACY_ALLOWED_EMAIL, $email ) ) {
						$findings[] = "{$rel}:{$n}: email address outside example domains";
					}
				}
			}

			foreach ( $deny as $term ) {
				if ( false !== stripos( $line, $term ) ) {
					$findings[] = "{$rel}:{$n}: denylisted term (PRIVACY_DENYLIST in .env)";
				}
			}
		}
	}

	return array_values( array_unique( $findings ) );
}

// Run standalone.
if ( realpath( $_SERVER['SCRIPT_FILENAME'] ?? '' ) === realpath( __FILE__ ) ) {
	$findings = privacy_scan();
	if ( $findings ) {
		foreach ( $findings as $f ) {
			fwrite( STDERR, "\033[31m✗\033[0m privacy: {$f}\n" );
		}
		fail( count( $findings ) . ' privacy finding(s). Remove them before committing.' );
	}
	ok( 'Privacy scan: clean (' . count( privacy_files() ) . ' files, ' . count( privacy_denylist() ) . ' denylist terms)' );
}
