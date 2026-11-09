<?php
/**
 * Module switches: reads config/modules.php and answers "is this module on?".
 *
 * IDs: top-level 'sda', sub-module 'sda.air'.
 *
 * A module is enabled when:
 *   - it is 'required', or its own 'enabled' is true and every ID in 'requires' is enabled;
 *   - for a sub-module: its parent is enabled and its own 'enabled' is true.
 *
 * Three ways to use it:
 *   PHP        kelurahan_module_enabled( 'layanan' ), kelurahan_module_url( 'layanan' )
 *   Block HTML add the class "kelurahan-module-<id>" to any block ('.' becomes '--',
 *              e.g. kelurahan-module-sda--air). The block is not rendered when the module is off.
 *   Override   add_filter( 'kelurahan_modules', fn( $m ) => … ) to change switches without editing config.
 *
 * @package ProfilKelurahan
 */

defined( 'ABSPATH' ) || exit;

/**
 * All module declarations from config/modules.php, after the 'kelurahan_modules' filter.
 *
 * @param bool $refresh Re-read the config (tests, or after changing the filter).
 * @return array<string, array>
 */
function kelurahan_modules( bool $refresh = false ): array {
	static $modules = null;

	if ( null === $modules || $refresh ) {
		$file    = KELURAHAN_DIR . '/config/modules.php';
		$config  = is_file( $file ) ? require $file : array();
		$modules = (array) apply_filters( 'kelurahan_modules', is_array( $config ) ? $config : array() );
	}

	return $modules;
}

/**
 * One module's declaration, or null if the ID is unknown.
 *
 * @param string $id Module ID, e.g. 'sda' or 'sda.air'.
 * @return array|null
 */
function kelurahan_module( string $id ): ?array {
	$modules = kelurahan_modules();
	$parts   = explode( '.', $id, 2 );

	if ( ! isset( $modules[ $parts[0] ] ) ) {
		return null;
	}
	if ( 1 === count( $parts ) ) {
		return $modules[ $parts[0] ];
	}

	return $modules[ $parts[0] ]['children'][ $parts[1] ] ?? null;
}

/**
 * Whether a module (or sub-module) is switched on for this site.
 *
 * Unknown IDs return false and raise a _doing_it_wrong notice (visible in debug.log), so typos surface.
 *
 * @param string   $id    Module ID, e.g. 'layanan' or 'sda.air'.
 * @param string[] $stack Internal: IDs being resolved, to stop circular 'requires'.
 * @return bool
 */
function kelurahan_module_enabled( string $id, array $stack = array() ): bool {
	$module = kelurahan_module( $id );

	if ( null === $module ) {
		_doing_it_wrong( __FUNCTION__, esc_html( "Unknown module ID '{$id}'. See theme/config/modules.php." ), KELURAHAN_VERSION );
		return false;
	}
	if ( in_array( $id, $stack, true ) ) {
		return false;
	}
	$stack[] = $id;

	if ( str_contains( $id, '.' ) ) {
		$parent = strstr( $id, '.', true );
		return kelurahan_module_enabled( $parent, $stack ) && ! empty( $module['enabled'] );
	}

	if ( ! empty( $module['required'] ) ) {
		return true;
	}
	if ( empty( $module['enabled'] ) ) {
		return false;
	}
	foreach ( (array) ( $module['requires'] ?? array() ) as $dependency ) {
		if ( ! kelurahan_module_enabled( $dependency, $stack ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Front-end URL of a module's page, from its 'path' in config/modules.php.
 *
 * @param string $id Module ID.
 * @return string Absolute URL, or '' when the module is off or declares no path. Check before printing a link.
 */
function kelurahan_module_url( string $id ): string {
	if ( ! kelurahan_module_enabled( $id ) ) {
		return '';
	}
	$path = kelurahan_module( $id )['path'] ?? '';

	return '' === $path ? '' : home_url( $path );
}

/**
 * Every enabled module ID, sub-modules included ('sda', 'sda.air', …).
 *
 * @return string[]
 */
function kelurahan_enabled_modules(): array {
	$ids = array();

	foreach ( kelurahan_modules() as $id => $module ) {
		if ( ! kelurahan_module_enabled( $id ) ) {
			continue;
		}
		$ids[] = $id;
		foreach ( array_keys( (array) ( $module['children'] ?? array() ) ) as $child ) {
			if ( kelurahan_module_enabled( "{$id}.{$child}" ) ) {
				$ids[] = "{$id}.{$child}";
			}
		}
	}

	return $ids;
}

/**
 * Skip blocks tagged with a disabled module's class (kelurahan-module-<id>).
 *
 * Works in block templates, parts, patterns and post content.
 *
 * @param string|null $content Rendered block HTML.
 * @param array       $block   Parsed block.
 * @return string|null
 */
function kelurahan_filter_module_blocks( $content, $block ) {
	$class = $block['attrs']['className'] ?? '';

	if ( ! is_string( $class ) || ! str_contains( $class, 'kelurahan-module-' ) ) {
		return $content;
	}
	if ( preg_match_all( '/(?:^|\s)kelurahan-module-([a-z0-9-]+)/', $class, $matches ) ) {
		foreach ( $matches[1] as $slug ) {
			if ( ! kelurahan_module_enabled( str_replace( '--', '.', $slug ) ) ) {
				return '';
			}
		}
	}

	return $content;
}
add_filter( 'render_block', 'kelurahan_filter_module_blocks', 10, 2 );
