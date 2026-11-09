# Architecture

## Why hybrid

| Need | Use |
|---|---|
| Layout, static structure, anything a site editor should be able to tweak | Block template (`templates/*.html`) |
| Showing custom fields | Block template + Block Bindings (`core/post-meta`) |
| Logic: conditionals, external APIs, complex `WP_Query`, forms | PHP template |
| Reusable dynamic component usable in content *and* templates | Custom block (`blocks/<name>/render.php`) |
| Reusable static section | Pattern (`patterns/*.php`) |

The theme is a block theme because `templates/index.html` exists (`wp_is_block_theme()` is true).
PHP templates still work alongside.

## Template resolution (the important rule)

For each request WordPress builds a candidate list from the template hierarchy, e.g. for an
`event_type` term `workshop`:

```
taxonomy-event_type-workshop → taxonomy-event_type → taxonomy → archive → index
```

1. It finds the first existing **PHP** file in that list (`locate_template`).
2. It then looks for a **block** template, but only among candidates **at least as specific**
   as the PHP file it found.
3. If a block template is found there, it wins. Otherwise the PHP file is used.

So:

- `taxonomy-event_type.php` + `archive.html` → **PHP wins** (more specific).
- `taxonomy-event_type.php` + `taxonomy-event_type.html` → **block wins** (equal).
- No PHP file → normal block template resolution.

Templates edited in the Site Editor are stored in the DB (`wp_template` posts) and override the
theme's file. If your file change "does nothing", check Appearance → Editor → Templates for a
customized version, or run:

```bash
php tools/wp-cli.phar post list --post_type=wp_template,wp_template_part --fields=ID,post_name
```

and delete the stale DB copy.

## User-selectable templates

Two kinds show up in the editor's *Template* dropdown:

- **Block:** listed in `theme.json → customTemplates`, file at `templates/<name>.html`.
  Stored in post meta `_wp_page_template` as `<name>`.
- **PHP:** file in `page-templates/` with a `Template Name:` header.
  Stored as `page-templates/template-<slug>.php`.

## PHP templates share block chrome

`header.php` / `footer.php` render `parts/header.html` / `parts/footer.html` through
`kelurahan_block_part()`, so header and footer stay identical across both template types.
Edit the header once in `parts/header.html`.

## Config-driven registration

`inc/*.php` are generic engines. Each one loads every file in a `config/` folder
(files that start with `_` are skipped) and registers it with sensible defaults:

- post types: `show_in_rest`, archive, labels generated from singular/plural
- taxonomies: `show_in_rest`, admin column, hierarchical by default
- meta: `show_in_rest`, typed, sanitized, `edit_post` auth, plus an admin meta box

Moving post types and fields into a plugin later means moving `config/` and the matching `inc/` files.
The format stays the same.

## Data access cheat-sheet (hypothetical `event` CPT with an `event_date` field)

| Where | How |
|---|---|
| PHP | `kelurahan_meta( 'event_date' )` |
| Block template | `<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"event_date"}}}}} --><p></p><!-- /wp:paragraph -->` |
| REST | `GET /wp-json/wp/v2/event/<id>` → `meta.event_date` |
| WP-CLI | `wp post meta get <id> event_date` |
