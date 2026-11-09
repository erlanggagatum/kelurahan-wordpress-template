<?php
/**
 * Dev server. Host and port come from WP_URL in .env, so several sites can
 * run side by side (8080, 8081, …).   Usage: npm start  (= php bin/serve.php)
 */

declare(strict_types=1);

require_once __DIR__ . '/lib.php';

if ( ! is_file( WP_CLI ) || ! is_file( WP_DIR . '/wp-config.php' ) ) {
	fail( 'Not set up yet. Run: npm run setup' );
}

$url  = ( load_env()['WP_URL'] ?? '' ) ?: 'http://localhost:8080';
$host = parse_url( $url, PHP_URL_HOST ) ?: 'localhost';
$port = (int) ( parse_url( $url, PHP_URL_PORT ) ?: 80 );

// The URL WordPress was installed with must match, or every link/redirect breaks.
[ $code, $siteurl ] = wp( array( 'option', 'get', 'siteurl' ) );
if ( 0 === $code && rtrim( $siteurl, '/' ) !== rtrim( $url, '/' ) ) {
	warn( "WP_URL in .env ({$url}) differs from the installed site URL ({$siteurl})." );
	warn( "Fix: php tools/wp-cli.phar search-replace '{$siteurl}' '{$url}' --all-tables" );
	exit( 1 );
}

$sock = @fsockopen( $host, $port, $errno, $errstr, 0.5 );
if ( $sock ) {
	fclose( $sock );
	fail( "Port {$port} is already in use (another site running?). Change WP_URL in .env." );
}

info( "Serving {$url}  (admin: {$url}/wp-admin)  Ctrl+C to stop" );

$proc = proc_open(
	array( PHP_BINARY, WP_CLI, '--path=' . WP_DIR, 'server', "--host={$host}", "--port={$port}" ),
	array( STDIN, STDOUT, STDERR ),
	$pipes,
	ROOT
);
exit( is_resource( $proc ) ? proc_close( $proc ) : 1 );
