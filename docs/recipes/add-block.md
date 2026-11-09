# Add a custom block

```bash
php bin/make.php block testimonial --title="Testimonial"
```

This creates `theme/blocks/testimonial/`:

| File | Role |
|---|---|
| `block.json` | name, attributes, supports, file refs. **Source of truth.** |
| `render.php` | front-end and editor-preview HTML (dynamic block). Receives `$attributes`, `$content`, `$block`. |
| `index.js` | editor UI. Plain JS with `wp.*` globals, no build. `save` returns `null`. |
| `index.asset.php` | script deps for `index.js`. When you use a new `wp.<pkg>` global, add `wp-<pkg>` here. |
| `style.css` | CSS for the front end and the editor |

`inc/blocks.php` registers every `blocks/*/block.json` automatically, under the "Profil Kelurahan" inserter category.

## Add an attribute

1. Add it to `block.json → attributes`: a type (`string|number|boolean|array|object`) and a `default`.
2. Read it in `render.php` as `$attributes['name']`, escaped.
3. Add a control in `index.js` under `InspectorControls`. Controls in `wp.components` include `TextControl`, `ToggleControl`, `RangeControl`, `SelectControl` and `TextareaControl`.

## Use it

```html
<!-- wp:kelurahan/testimonial {"heading":"Great!"} /-->
```

This works in templates, parts, patterns and post content.

The scaffolder output is the reference: `render.php` receives `$attributes`, `$content` and `$block`; query posts and read meta with `kelurahan_meta()` there.

## Need JSX, InnerBlocks or a build step later?

Add `@wordpress/scripts`, put source in `src/`, build to `build/`, and point `block.json` at the built files. The build generates `index.asset.php` for you.

## Verify

```bash
php bin/check.php     # checks that files referenced in block.json exist
php tools/wp-cli.phar eval 'var_dump( WP_Block_Type_Registry::get_instance()->is_registered( "kelurahan/testimonial" ) );'
php tools/wp-cli.phar eval 'echo do_blocks( "<!-- wp:kelurahan/testimonial /-->" );'
```
