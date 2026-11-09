# Styling

## Order of preference

1. **`theme/theme.json`**: tokens and per-block styles. Applies to the front end and the editor.
2. **Block attributes** in templates and patterns: `"backgroundColor":"accent"`, `"fontSize":"large"`, `"style":{"spacing":{...}}`.
3. **`theme/assets/css/main.css`**: only for what theme.json can't do (hover effects, custom components, PHP template classes). The editor loads it too.
4. **Block CSS**: `blocks/<name>/style.css`.

## Tokens

| theme.json | CSS variable | in block attributes |
|---|---|---|
| `settings.color.palette` slug `accent` | `--wp--preset--color--accent` | `"textColor":"accent"` or `var:preset\|color\|accent` |
| `settings.typography.fontSizes` slug `large` | `--wp--preset--font-size--large` | `"fontSize":"large"` |
| `settings.typography.fontFamilies` slug `heading` | `--wp--preset--font-family--heading` | `"fontFamily":"heading"` |
| `settings.spacing.spacingSizes` slug `40` | `--wp--preset--spacing--40` | `var:preset\|spacing\|40` |

- Palette slugs: `base`, `base-2`, `contrast`, `contrast-2`, `accent`, `accent-2`, `border`.
- Spacing slugs: `10` to `60`.

## Recipes

- **Rebrand:** change the palette `color` values. Keep the slugs so templates keep working.
- **Default style for one block:** `styles.blocks["core/button"]`.
- **Element styles:** `styles.elements.link`, `button`, `heading`, `h1` to `h6`.
- **Web font:** add `fontFace` to a font family, pointing at `file:./assets/fonts/x.woff2`.
- **Content width:** `settings.layout.contentSize` and `wideSize`.

Schema: https://schemas.wp.org/wp/6.6/theme.json. Validate with `php bin/check.php`.

Global-style edits made in the Site Editor are stored in the DB and override theme.json. To undo them, use Editor → Styles → Reset, or delete the `wp_global_styles` post.
