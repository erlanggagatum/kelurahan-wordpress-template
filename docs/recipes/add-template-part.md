# Add a template part

```bash
php bin/make.php part sidebar --title="Sidebar" --area=uncategorized   # area: header | footer | uncategorized
```

This creates `parts/sidebar.html` and adds the part to `theme.json → templateParts`.

Use it:

- In a block template: `<!-- wp:template-part {"slug":"sidebar","tagName":"aside"} /-->`
- In PHP: `kelurahan_block_part( 'sidebar', 'aside' );`

The site header and footer live in `parts/header.html` and `parts/footer.html`. PHP templates use these same files.

Navigation: `parts/header.html` uses `<!-- wp:navigation /-->`, which falls back to a page list until a menu exists.
For classic menus in PHP, use `wp_nav_menu( array( 'theme_location' => 'primary' ) )`.

`php bin/check.php` fails if a template references a part that has no file.
