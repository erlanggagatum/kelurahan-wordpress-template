# Content, settings and menus via WP-CLI

Every command starts with `php tools/wp-cli.phar`. Below it is shortened to `wp`.

## Pages and posts

```bash
wp post create --post_type=page --post_title="Contact" --post_status=publish --porcelain   # prints the new ID
wp post create --post_type=page --post_title="Services" --post_status=publish --post_content='<!-- wp:pattern {"slug":"kelurahan/hero"} /-->' --porcelain
wp post create ./content.html --post_type=page --post_title="From file" --post_status=publish
wp post update 12 --page_template=page-wide
wp post update 12 --post_parent=5
wp post list --post_type=page --fields=ID,post_title,post_name
wp post delete 12 --force
```

## Front page and blog

```bash
wp option update show_on_front page
wp option update page_on_front <home-id>
wp option update page_for_posts <blog-id>
```

## Meta and terms

```bash
wp post meta update <id> event_date 2026-12-01
wp term create event_type "Webinar"
wp post term set <id> event_type webinar
```

## Classic menus (for PHP templates / wp_nav_menu)

```bash
wp menu create "Primary"
wp menu item add-post primary <page-id>
wp menu item add-custom primary "Events" /events/
wp menu location assign primary primary
```

The block Navigation in `parts/header.html` lists pages by default. To pin it to a specific `wp_navigation` post, add `"ref":<id>` to the navigation block.

## Settings

```bash
wp option update blogname "My Site"
wp option update blogdescription "Tagline"
wp option update permalink_structure '/%postname%/'   # not `wp rewrite structure`: it spawns a child WP-CLI that breaks on paths with spaces
wp rewrite flush
```

## Sample content

```bash
npm run seed            # idempotent
wp site empty --yes     # DANGER: deletes ALL content
```
