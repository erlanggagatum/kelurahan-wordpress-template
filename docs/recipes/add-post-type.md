# Add a custom post type

## Steps

1. Scaffold:
   ```bash
   php bin/make.php post-type book --singular=Book --plural=Books --icon=dashicons-book
   ```
   This creates:
   - `theme/config/post-types/book.php`: the declaration
   - `theme/templates/single-book.html`: the single view
   - `theme/templates/archive-book.html`: the `/books/` archive

2. Edit `config/post-types/book.php`. Any `register_post_type()` arg can go in `args`. Annotated options are in `config/post-types/_example.php`.
3. Optional: add fields ([add-meta-fields.md](add-meta-fields.md)) and a taxonomy ([add-taxonomy.md](add-taxonomy.md)).
4. Verify:
   ```bash
   php bin/check.php
   php tools/wp-cli.phar post-type get book --field=name
   php tools/wp-cli.phar post create --post_type=book --post_title="Test Book" --post_status=publish
   php bin/check.php --http
   ```

## Rules

- Slug: 1 to 20 chars, `[a-z0-9_-]`, singular (`book`, not `books`). Reserved: post, page, attachment, revision, nav_menu_item, action, author, order, theme.
- Keep `show_in_rest => true`. The block editor and REST need it.
- Keep `custom-fields` in `supports` if the type has meta. REST meta requires it.
- Rewrites flush automatically when config changes.

## Variants

- **Page-like (parent/child):** `'hierarchical' => true` and add `'page-attributes'` to supports.
- **Internal data (no front end):** `'public' => false, 'show_ui' => true, 'has_archive' => false`. Delete the generated templates.
- **No archive:** `'has_archive' => false`. Delete `archive-<slug>.html`.
- **Default blocks for new posts:** `'template' => array( array( 'core/paragraph', array( 'placeholder' => 'Write…' ) ) )`.
