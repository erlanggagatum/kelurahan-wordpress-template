# Show or hide output by module

Each site turns modules on or off in `theme/config/modules.php`. The catalog is in `docs/guide/modul.md`.
`theme/inc/modules.php` reads that file. Gate everything that belongs to a module, so turning the module off
hides it without deleting files.

IDs: top-level `layanan`, sub-module `sda.air`. A sub-module is off whenever its parent is off.
`required` modules are always on. A module with `requires` is off until those modules are on too.

## PHP (templates, patterns, render.php)

```php
<?php if ( kelurahan_module_enabled( 'sda.air' ) ) : ?>
	…
<?php endif; ?>

<?php $url = kelurahan_module_url( 'layanan' ); // '' when off or no 'path' ?>
<?php if ( $url ) : ?>
	<a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Lihat layanan', 'kelurahan' ); ?></a>
<?php endif; ?>
```

`kelurahan_enabled_modules()` returns every enabled ID (`sda`, `sda.air`, …).

## Block markup (templates/*.html, parts/*.html, post content)

Block HTML can't run PHP. Instead, add the class `kelurahan-module-<id>` to any block. Write sub-modules with
`--` instead of `.`, for example `kelurahan-module-sda--air`. The block and everything inside it are not
rendered when the module is off. Put the class in both the attribute JSON and the HTML:

```html
<!-- wp:group {"className":"kelurahan-module-agenda","layout":{"type":"constrained"}} -->
<div class="wp-block-group kelurahan-module-agenda">…</div>
<!-- /wp:group -->
```

In the editor, the class goes in Block settings → Advanced → Additional CSS class(es).
Example: `theme/patterns/call-to-action.php`.

## Links to module pages

Take URLs from the module's `path` in `config/modules.php` through `kelurahan_module_url()`. Don't hard-code
`home_url( '/layanan/' )`. A site that renames a page then only changes `path`.

## Override switches without editing the config

```php
add_filter( 'kelurahan_modules', function ( array $modules ): array {
	$modules['umkm']['enabled'] = true;
	return $modules;
} );
```

## Add a module

1. Add it to `docs/guide/modul.md`, `theme/config/modules.php` and `docs/guide/struktur-situs.md` (rule 9 in AGENTS.md).
2. Use kebab-case IDs. `path` is optional and looks like `/a/b/`.
3. Run `php bin/check.php`. It validates the config and fails on any module ID used in `theme/` that isn't declared.
