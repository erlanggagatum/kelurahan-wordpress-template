# Add a taxonomy

```bash
php bin/make.php taxonomy genre --post-types=book            # category-like (hierarchical)
php bin/make.php taxonomy mood --post-types=book,post --flat # tag-like
```

This creates `theme/config/taxonomies/<slug>.php`. Edit `args` with any `register_taxonomy()` arg.

## Rules

- Slug: 1 to 32 chars, `[a-z0-9_]`. Use underscores, not hyphens. Reserved: category, post_tag, tag, type, name, year, page, author, term, taxonomy.
- Term archive URL: `/<rewrite slug>/<term>/`. The rewrite slug defaults to the slug with `_` replaced by `-`.

## Show terms

- Block template: `<!-- wp:post-terms {"term":"genre"} /-->`
- PHP: `the_terms( get_the_ID(), 'genre', '', ', ' );`

## Custom term archive

- Block: `php bin/make.php template taxonomy-genre`
- PHP: create `theme/taxonomy-genre.php` from the skeleton in docs/recipes/add-php-template.md.

## Verify

```bash
php bin/check.php
php tools/wp-cli.phar term create genre "Sci-Fi"
php tools/wp-cli.phar post term add <post-id> genre sci-fi
```
