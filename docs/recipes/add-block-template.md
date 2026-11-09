# Add a block template

Block templates live in `theme/templates/<hierarchy-slug>.html`. WordPress picks one by name using the template hierarchy.

```bash
php bin/make.php template front-page          # static front page
php bin/make.php template single-book         # one CPT item
php bin/make.php template archive-book        # CPT archive
php bin/make.php template page-about          # the page with slug "about"
php bin/make.php template category-news       # category "news"
php bin/make.php template taxonomy-genre      # all genre terms
```

The scaffolder chooses single-style or archive-style markup from the slug. Edit the file afterwards.

## Common hierarchy slugs

| Request | Candidates (most to least specific) |
|---|---|
| Front page | front-page, home (if blog), page, index |
| Single post | single-post-<slug>, single-post, single, singular, index |
| CPT item | single-<type>-<slug>, single-<type>, single, singular, index |
| Page | page-<slug>, page-<id>, page, singular, index |
| CPT archive | archive-<type>, archive, index |
| Term | taxonomy-<tax>-<term>, taxonomy-<tax>, taxonomy, archive, index |
| Category | category-<slug>, category-<id>, category, archive, index |
| Search / 404 | search / 404, index |

## Skeleton

Every template has three pieces: the header part, a `<main>` group, and the footer part. Copy the structure from `templates/page.html`.

Useful blocks: `post-title`, `post-content`, `post-featured-image`, `post-date`, `post-excerpt`, `post-terms`, `query` + `post-template`, `query-title`, `query-pagination`, `template-part`, `pattern`, and any `kelurahan/*` block.

## Query loop for a specific post type (not the main query)

```html
<!-- wp:query {"queryId":5,"query":{"inherit":false,"postType":"book","perPage":6,"order":"desc","orderBy":"date"}} -->
<div class="wp-block-query">
	<!-- wp:post-template -->
		<!-- wp:post-title {"isLink":true} /-->
	<!-- /wp:post-template -->
</div>
<!-- /wp:query -->
```

## Verify

```bash
php bin/check.php
php bin/check.php --http
```

If a change doesn't show up, a Site Editor copy stored in the DB may be overriding the file. See docs/architecture.md.
