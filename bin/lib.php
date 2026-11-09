<?php
/**
 * Shared helpers for bin/*.php scripts (plain PHP CLI, no WordPress loaded).
 */

declare(strict_types=1);

const ROOT      = __DIR__ . '/..';
const THEME_DIR = ROOT . '/theme';
const WP_DIR    = ROOT . '/.wp';
const WP_CLI    = ROOT . '/tools/wp-cli.phar';

if ( PHP_SAPI !== 'cli' ) {
	exit( 1 );
}

/** Print helpers. */
function info( string $msg ): void {
	fwrite( STDOUT, "\033[36m›\033[0m {$msg}\n" );
}
function ok( string $msg ): void {
	fwrite( STDOUT, "\033[32m✓\033[0m {$msg}\n" );
}
function warn( string $msg ): void {
	fwrite( STDERR, "\033[33m!\033[0m {$msg}\n" );
}
function fail( string $msg, int $code = 1 ): never {
	fwrite( STDERR, "\033[31m✗\033[0m {$msg}\n" );
	exit( $code );
}

/**
 * Parse .env (KEY=value, # comments, optional quotes).
 *
 * @return array<string, string>
 */
function load_env(): array {
	$file = ROOT . '/.env';
	if ( ! is_file( $file ) ) {
		return array();
	}
	$env = array();
	foreach ( file( $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES ) as $line ) {
		$line = trim( $line );
		if ( '' === $line || str_starts_with( $line, '#' ) || ! str_contains( $line, '=' ) ) {
			continue;
		}
		[ $key, $value ] = array_map( 'trim', explode( '=', $line, 2 ) );
		if ( preg_match( '/^([\'"])(.*)\1$/', $value, $m ) ) {
			$value = $m[2];
		}
		$env[ $key ] = $value;
	}
	return $env;
}

/**
 * Run WP-CLI without a shell (array form: no quoting issues on Windows,
 * where escapeshellarg() mangles "%" and "!").
 *
 * @param string[]    $args  Arguments.
 * @param string|null $stdin Data piped to STDIN (e.g. for --extra-php).
 * @return array{0:int,1:string} [exit code, stdout+stderr]
 */
function wp( array $args, ?string $stdin = null ): array {
	$cmd  = array_merge( array( PHP_BINARY, WP_CLI, '--path=' . WP_DIR ), $args );
	$spec = array( 0 => array( 'pipe', 'r' ), 1 => array( 'pipe', 'w' ), 2 => array( 'pipe', 'w' ) );
	$proc = proc_open( $cmd, $spec, $pipes, ROOT );
	if ( ! is_resource( $proc ) ) {
		return array( 1, 'proc_open failed' );
	}
	fwrite( $pipes[0], $stdin ?? '' );
	fclose( $pipes[0] );
	$out = stream_get_contents( $pipes[1] ) . stream_get_contents( $pipes[2] );
	fclose( $pipes[1] );
	fclose( $pipes[2] );
	return array( proc_close( $proc ), trim( $out ) );
}

/** Run WP-CLI and abort on failure. */
function wp_or_fail( array $args, ?string $stdin = null ): string {
	[ $code, $out ] = wp( $args, $stdin );
	if ( 0 !== $code ) {
		fail( 'wp ' . implode( ' ', $args ) . " failed:\n{$out}" );
	}
	return $out;
}

/**
 * Parse argv into positionals and --flags.
 *
 * @return array{0:string[],1:array<string,string|bool>}
 */
function parse_args( array $argv ): array {
	$pos   = array();
	$flags = array();
	foreach ( array_slice( $argv, 1 ) as $arg ) {
		if ( preg_match( '/^--([a-z0-9-]+)(?:=(.*))?$/i', $arg, $m ) ) {
			$flags[ $m[1] ] = $m[2] ?? true;
		} else {
			$pos[] = $arg;
		}
	}
	return array( $pos, $flags );
}

/** Relative path from repo root, for printing. */
function rel( string $path ): string {
	$root = realpath( ROOT );
	$real = realpath( $path ) ?: $path;
	return ltrim( str_replace( array( $root, '\\' ), array( '', '/' ), $real ), '/' );
}
