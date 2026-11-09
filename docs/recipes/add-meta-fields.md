# Add custom fields (native post meta)

## 1. Declare

```bash
php bin/make.php meta book book_isbn:text:ISBN book_pages:integer book_format:select "book_out:date:Release date"
```

This creates `theme/config/meta/book.php`. If the file already exists, edit it by hand to add fields. `--force` overwrites the whole file.

| type | stored as | admin input |
|---|---|---|
| text, textarea, url, email, date (Y-m-d), time (H:i), select | string | matching HTML input |
| number | float | number |
| integer | int | number |
| checkbox | bool | checkbox |

A `select` field needs `'options' => array( 'value' => 'Label' )`. Values not in options are rejected.

Optional per field: `description`, `default`. Optional per file: `title`, `context` (`normal|side|advanced`), `'meta_box' => false` to hide the admin box.

Every field gets `show_in_rest`, a sanitize callback by type, and an `edit_post` capability check.

## 2. Display

**Block template (preferred).** Bind a paragraph or heading to the meta:

```html
<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"book_isbn"}}}}} -->
<p></p>
<!-- /wp:paragraph -->
```

For a button link use `{"metadata":{"bindings":{"url":{"source":"core/post-meta","args":{"key":"book_url"}}}}}`. For an image, bind `url`.
Bindings print the raw value. If the value needs formatting (dates, labels for select values), use a PHP template or a custom block.
Put the bound blocks in `theme/templates/single-book.html` (the post-type scaffolder creates it; otherwise run `php bin/make.php template single-book`).

**PHP:**

```php
echo esc_html( kelurahan_meta( 'book_isbn' ) );
echo esc_html( kelurahan_format_date( (string) kelurahan_meta( 'book_out' ) ) );
```

## 3. Query by meta

```php
new WP_Query( array(
	'post_type'  => 'book',
	'meta_key'   => 'book_out',
	'orderby'    => 'meta_value',
	'order'      => 'DESC',
	'meta_query' => array(
		array( 'key' => 'book_pages', 'value' => 300, 'compare' => '>', 'type' => 'NUMERIC' ),
	),
) );
```

## Rules

- Key: `[a-z][a-z0-9_]*`. Prefix it with the post type (`book_isbn`). **Never start with `_`**, because protected keys can't be block-bound.
- The post type must support `custom-fields`. It does by default.

## Verify

```bash
php bin/check.php        # flags bad keys/types, and bindings to undefined keys
php tools/wp-cli.phar post meta update <id> book_isbn 978-0
php tools/wp-cli.phar eval 'print_r( get_registered_meta_keys( "post", "book" ) );'
```
