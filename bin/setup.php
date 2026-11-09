<?php
/**
 * One-command local environment: php bin/setup.php [--seed]
 *
 * Idempotent. Safe to re-run. Steps:
 *   1. read .env
 *   2. download WP-CLI to tools/ (sha512 verified)
 *   3. download WordPress core to .wp/
 *   4. create wp-config.php
 *   5. create the database (via mysqli, no mysql client needed)
 *   6. install WordPress
 *   7. link theme/ into .wp/wp-content/themes/kelurahan and activate it
 *   8. --seed: create sample content (bin/seed.php)
 */

declare(strict_types=1);

require_once __DIR__ . '/lib.php';

[ , $flags ] = parse_args( $argv );

// 1. Environment ------------------------------------------------------------
$env = load_env();
if ( ! $env ) {
	fail( 'Missing .env. Copy .env.example to .env and fill in DB credentials.' );
}
foreach ( array( 'DB_NAME', 'DB_USER', 'DB_HOST' ) as $required ) {
	if ( empty( $env[ $required ] ) ) {
		fail( "{$required} is empty in .env" );
	}
}
$env += array(
	'DB_PASSWORD'       => '',
	'WP_URL'            => 'http://localhost:8080',
	'WP_TITLE'          => 'Profil Kelurahan Dev',
	'WP_ADMIN_USER'     => 'admin',
	'WP_ADMIN_PASSWORD' => '',
	'WP_ADMIN_EMAIL'    => 'admin@example.test',
	'THEME_SLUG'        => 'kelurahan',
);

// 2. WP-CLI -------------------------------------------------------------------
if ( ! is_file( WP_CLI ) ) {
	info( 'Downloading WP-CLI…' );
	@mkdir( dirname( WP_CLI ), 0777, true );
	$base = 'https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar';
	$phar = file_get_contents( $base );
	$sum  = trim( (string) file_get_contents( $base . '.sha512' ) );
	if ( false === $phar || '' === $sum ) {
		fail( 'Could not download WP-CLI.' );
	}
	if ( ! hash_equals( $sum, hash( 'sha512', $phar ) ) ) {
		fail( 'WP-CLI checksum mismatch. Aborting.' );
	}
	file_put_contents( WP_CLI, $phar );
	ok( 'WP-CLI downloaded and verified' );
} else {
	ok( 'WP-CLI present' );
}

// 3. Core ---------------------------------------------------------------------
if ( ! is_file( WP_DIR . '/wp-load.php' ) ) {
	info( 'Downloading WordPress core…' );
	wp_or_fail( array( 'core', 'download', '--skip-content', '--force' ) );
	ok( 'WordPress core downloaded to .wp/' );
} else {
	ok( 'WordPress core present' );
}
foreach ( array( 'themes', 'plugins', 'mu-plugins', 'uploads' ) as $dir ) {
	@mkdir( WP_DIR . "/wp-content/{$dir}", 0777, true );
}

// 4. wp-config.php -----------------------------------------------------------
if ( ! is_file( WP_DIR . '/wp-config.php' ) ) {
	info( 'Creating wp-config.php…' );
	$extra = <<<'PHP'
define( 'WP_ENVIRONMENT_TYPE', 'local' );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );          // -> .wp/wp-content/debug.log
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
define( 'DISALLOW_FILE_EDIT', true );
PHP;
	wp_or_fail(
		array(
			'config', 'create',
			'--dbname=' . $env['DB_NAME'],
			'--dbuser=' . $env['DB_USER'],
			'--dbpass=' . $env['DB_PASSWORD'],
			'--dbhost=' . $env['DB_HOST'],
			'--skip-check',
			'--extra-php', // Reads the PHP from STDIN.
		),
		$extra
	);
	ok( 'wp-config.php created' );
} else {
	// .env is the source of truth: re-sync DB credentials on every run, so
	// fixing .env and re-running setup repairs a wrong/empty value.
	$synced = 0;
	foreach ( array( 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST' ) as $const ) {
		[ , $current ] = wp( array( 'config', 'get', $const ) );
		if ( $current !== $env[ $const ] ) {
			wp_or_fail( array( 'config', 'set', $const, $env[ $const ], '--type=constant' ) );
			++$synced;
		}
	}
	ok( $synced ? "wp-config.php present, {$synced} DB setting(s) synced from .env" : 'wp-config.php present, in sync with .env' );
}

// 5. Database -----------------------------------------------------------------
[ $db_host, $db_port ] = array_pad( explode( ':', $env['DB_HOST'], 2 ), 2, '3306' );
mysqli_report( MYSQLI_REPORT_OFF );
$mysqli = @new mysqli( $db_host, $env['DB_USER'], $env['DB_PASSWORD'], '', (int) $db_port );
if ( $mysqli->connect_errno ) {
	fail( "Cannot connect to MySQL at {$env['DB_HOST']} as {$env['DB_USER']}: {$mysqli->connect_error}" );
}
$db_name = $mysqli->real_escape_string( $env['DB_NAME'] );
if ( ! $mysqli->query( "CREATE DATABASE IF NOT EXISTS `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci" ) ) {
	fail( "Cannot create database {$env['DB_NAME']}: {$mysqli->error}" );
}
$mysqli->close();
ok( "Database {$env['DB_NAME']} ready" );

// 6. Install ------------------------------------------------------------------
[ $installed ] = wp( array( 'core', 'is-installed' ) );
if ( 0 !== $installed ) {
	info( 'Installing WordPress…' );
	$password = $env['WP_ADMIN_PASSWORD'] ?: bin2hex( random_bytes( 8 ) );
	wp_or_fail(
		array(
			'core', 'install',
			'--url=' . $env['WP_URL'],
			'--title=' . $env['WP_TITLE'],
			'--admin_user=' . $env['WP_ADMIN_USER'],
			'--admin_password=' . $password,
			'--admin_email=' . $env['WP_ADMIN_EMAIL'],
			'--skip-email',
		)
	);
	// Print credentials immediately, before any later step can fail.
	ok( "WordPress installed. Admin: {$env['WP_ADMIN_USER']} / {$password}" );
	if ( ! $env['WP_ADMIN_PASSWORD'] ) {
		warn( 'Generated admin password shown above. Save it, or set WP_ADMIN_PASSWORD in .env.' );
	}
	// Not `wp rewrite structure`: it re-launches WP-CLI in a child process,
	// which breaks when the repo path contains spaces (Windows).
	wp_or_fail( array( 'option', 'update', 'permalink_structure', '/%postname%/' ) );
	wp_or_fail( array( 'rewrite', 'flush' ) );
} else {
	ok( 'WordPress already installed' );
}

// 7. Link + activate theme -----------------------------------------------------
$link = WP_DIR . '/wp-content/themes/' . $env['THEME_SLUG'];
if ( ! file_exists( $link ) ) {
	info( 'Linking theme/ into WordPress…' );
	$target = realpath( THEME_DIR );
	if ( PHP_OS_FAMILY === 'Windows' ) {
		// Directory junction: no admin rights or Developer Mode needed.
		$win_link = str_replace( '/', '\\', ( realpath( dirname( $link ) ) ?: dirname( $link ) ) . '/' . basename( $link ) );
		exec( 'cmd /c mklink /J "' . $win_link . '" "' . $target . '" 2>&1', $out, $code );
		if ( 0 !== $code ) {
			fail( 'mklink failed: ' . implode( "\n", $out ) );
		}
	} elseif ( ! symlink( $target, $link ) ) {
		fail( "symlink failed: {$link}" );
	}
	ok( "Linked .wp/wp-content/themes/{$env['THEME_SLUG']} -> theme/" );
} else {
	ok( 'Theme link present' );
}
wp_or_fail( array( 'theme', 'activate', $env['THEME_SLUG'] ) );
ok( "Theme {$env['THEME_SLUG']} active" );

// 7b. Git hooks (privacy pre-commit) --------------------------------------------
if ( is_dir( ROOT . '/.git' ) && is_dir( ROOT . '/.githooks' ) ) {
	exec( 'git -C ' . escapeshellarg( realpath( ROOT ) ) . ' config core.hooksPath .githooks 2>&1', $out, $code );
	0 === $code ? ok( 'Git hooks enabled (.githooks/pre-commit runs the privacy scan)' ) : warn( 'Could not enable git hooks: ' . implode( ' ', $out ) );
}

// 8. Seed -------------------------------------------------------------------
if ( ! empty( $flags['seed'] ) ) {
	info( 'Seeding sample content…' );
	echo wp_or_fail( array( 'eval-file', ROOT . '/bin/seed.php' ) ), "\n";
}

echo "\n";
ok( "Done. Start the server:  npm start   (then open {$env['WP_URL']})" );
