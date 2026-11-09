# Add a user-selectable page template

The editor's *Template* dropdown offers two kinds.

## A. Block template (no logic)

```bash
php bin/make.php custom-template landing --title="Landing" --post-types=page,post
```

This creates `templates/landing.html` and adds an entry to `theme.json → customTemplates`.

Assign it:

```bash
php tools/wp-cli.phar post update <id> --page_template=landing
```

The theme ships two examples: `page-no-title` and `page-wide`.

## B. PHP template (logic needed)

```bash
php bin/make.php page-template contact --title="Contact (PHP)"
```

This creates `page-templates/template-contact.php` with a `Template Name:` header. It calls `get_header()` and `get_footer()`.

Assign it:

```bash
php tools/wp-cli.phar post update <id> --page_template=page-templates/template-contact.php
```

For a full example with a custom query and a partial, see `page-templates/template-landing.php`.

## Verify

```bash
php bin/check.php
php tools/wp-cli.phar eval 'print_r( wp_get_theme()->get_page_templates() );'
php bin/check.php --http
```
