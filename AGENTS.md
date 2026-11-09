# AGENTS.md

Instructions for AI coding agents (Claude Code, Codex, Cursor, Aider, etc.) working in this repo.
Humans: see README.md.

## What this is

A **WordPress template for Indonesian *kelurahan* profile websites** ("Profil Kelurahan"), structured
after Permendagri No. 12 Tahun 2007 (Pedoman Penyusunan dan Pendayagunaan Data Profil Desa dan Kelurahan).
Technically: a **hybrid WordPress theme** (`theme/`), a block theme (FSE) with PHP templates where logic
is needed, plus tooling that lets an agent scaffold, run and verify changes without a GUI.

- **Domain guideline lives in `docs/guide/`** (Bahasa Indonesia): regulation summary, module catalog,
  proposed sitemap. Read `docs/guide/modul.md` before building any kelurahan feature.
- Features are grouped into **modules** that each site can switch on/off. The declaration is
  `theme/config/modules.php`. It is not wired into the theme yet, so treat it as the spec.

- WordPress runs from `.wp/` (git-ignored, created by `php bin/setup.php`).
- `theme/` is linked into `.wp/wp-content/themes/kelurahan`. **Edit files in `theme/`, never in `.wp/`.**
- Custom fields are **native** (`register_post_meta`). No ACF / Meta Box. No build step (plain CSS/JS).

## Golden rules

1. **Scaffold, don't hand-roll.** Use `php bin/make.php <command>` for new post types, taxonomies,
   fields, blocks, patterns, templates, parts. Then edit the generated files.
2. **Declare, don't register.** Post types, taxonomies and fields are *config files* in `theme/config/`.
   Never call `register_post_type()` / `register_taxonomy()` / `register_post_meta()` directly.
3. **Block template first, PHP template when needed.** Use `templates/*.html` unless you need
   logic (conditional output, external data, complex queries, forms) → then PHP.
4. **Design tokens live in `theme/theme.json`.** CSS in `assets/css/main.css` must use
   `var(--wp--preset--color--<slug>)` etc., never hard-coded colors/sizes.
5. **Verify before you say done:** `php bin/check.php` (always) and `php bin/check.php --http`
   when the site is set up. Exit code 0 = pass. Fix every error it prints.
6. Prefix everything: PHP functions `kelurahan_`, constants `KELURAHAN_`, text domain
   `kelurahan`, block namespace `kelurahan/`, pattern slugs `kelurahan/<slug>`.
7. Escape all output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`), sanitize all input,
   check nonces + capabilities on writes.
8. Don't modify WordPress core, `.wp/`, `tools/`, or `vendor/`.
9. **Module-driven.** Every kelurahan feature belongs to a module ID from `docs/guide/modul.md` /
   `theme/config/modules.php`. Use that ID in file names, CPT/meta slugs where practical, and keep the doc,
   the config and the sitemap (`docs/guide/struktur-situs.md`) in sync when adding or renaming a module.
10. **Personal data stays aggregate.** Data Dasar Keluarga and RIAD (mother & child) data are household-level
   personal data. Publish only aggregated numbers (per RW/lingkungan or whole kelurahan). Never store or
   render per-person or per-KK records (NIK, names, addresses) in the theme, seed data or fixtures.
11. **UI language is Bahasa Indonesia.** Front-end strings are Indonesian and wrapped in `__()`/`esc_html_e()`
   with text domain `kelurahan`. Code, comments and AGENTS/recipes stay English.

## Privacy & secrets guardrails (non-negotiable)

This repo is meant to be shared publicly. Nothing in it may identify the machine or the people working on it.

1. **No absolute paths.** Use repo-relative paths (`theme/inc/meta.php`, `bin/setup.php`) in code, docs, comments and examples. Never write a drive-letter user folder or a home directory path. At runtime, build paths from `__DIR__`, `ROOT`, `get_template_directory()` and similar.
2. **No personal names or handles** in any file: not as author, reviewer, maintainer, "contact", example data or TODO owner. Use roles ("the maintainer", "a reviewer") or placeholders (`Jane Doe`, `jdoe`).
3. **No real emails.** Use reserved domains only: `@example.com`, `@example.org`, `@example.test`.
4. **No secrets.** Credentials go in `.env` (git-ignored). `.env.example` keeps values empty. Never commit passwords, API keys, tokens, private keys, or DB dumps.
5. **No machine or environment details** that identify a person: OS username, hostname, local service names, installed-tool paths.
6. **Tool output is not documentation.** Error messages and logs may contain absolute paths. Don't paste them into files. Rewrite them relative to the repo.
7. **Generated content too.** Seed data, patterns, screenshots and fixtures follow the same rules.
8. **Enforced:** `php bin/check.php` (and `php bin/privacy-check.php` standalone) fails on absolute user paths, non-example emails, secret-looking values, and any term in `PRIVACY_DENYLIST` in the git-ignored `.env`. If it fails, fix the file. Never weaken the scanner or add exceptions to get past it.
9. **Never read `.env` values back into files, commits or chat.** `.env` is the only place real names, credentials and denylist terms may live.

## Map

```
theme/
├── style.css                theme header only (no CSS here)
├── theme.json               design tokens, layout, templateParts, customTemplates
├── functions.php            bootstrap: requires inc/*.php (add new inc files to its list)
├── inc/                     engine — rarely needs edits
│   ├── helpers.php          config loader, label builders
│   ├── post-types.php       registers config/post-types/*.php (+ auto rewrite flush)
│   ├── taxonomies.php       registers config/taxonomies/*.php
│   ├── meta.php             registers config/meta/*.php + generic admin meta box
│   ├── blocks.php           registers blocks/*/block.json
│   ├── patterns.php         pattern categories
│   ├── setup.php            theme supports, nav menus, image sizes
│   ├── assets.php           enqueue assets/css/main.css, assets/js/main.js
│   └── template-tags.php    kelurahan_meta(), kelurahan_block_part(), kelurahan_pattern()
├── config/                  ← DECLARATIONS (most work happens here)
│   ├── modules.php               kelurahan module on/off switches (spec only, not loaded yet)
│   ├── post-types/<slug>.php     return [ slug, singular, plural, args ]
│   ├── taxonomies/<slug>.php     return [ slug, singular, plural, post_types, args ]
│   └── meta/<post-type>.php      return [ post_type, title, fields => [ key => [label,type,...] ] ]
├── templates/*.html         block templates (template hierarchy names)
├── parts/*.html             block template parts (header, footer, …)
├── patterns/*.php           block patterns (auto-registered from file header)
├── blocks/<name>/           custom dynamic blocks: block.json, render.php, index.js, index.asset.php
├── header.php / footer.php  shared chrome for PHP templates (renders parts/header.html)
├── page-templates/*.php     user-selectable PHP page templates ("Template Name:")
├── template-parts/*.php     PHP partials for get_template_part()
├── taxonomy-event_type.php  example PHP override in the template hierarchy
└── assets/css|js            plain CSS/JS, no build
bin/
├── setup.php          one-time local install (idempotent)       npm run setup
├── serve.php          dev server on WP_URL host/port from .env   npm start
├── make.php           scaffolder — run with no args for help    npm run make
├── check.php          verification gate (--wp, --http, --phpcs) npm run check
├── privacy-check.php  path/email/secret/denylist scan (run by check.php)
├── rename.php         rebrand a fresh copy for a new site (once)
├── seed.php           sample content (runs inside WP)
├── runtime-check.php  registration checks (runs inside WP)
└── lib.php            shared helpers for bin scripts
docs/
├── guide/                   ← DOMAIN SPEC (Bahasa Indonesia)
│   ├── README.md                 index + regulation status notes
│   ├── ringkasan-permen-12-2007.md   article-by-article extraction of the regulation
│   ├── modul.md                  module catalog (IDs, sources, data, on/off checklist)
│   ├── struktur-situs.md         proposed sitemap / menus mapped to module IDs
│   └── Permen 12-2007-…pdf       source document
├── architecture.md          how hybrid template resolution works
└── recipes/*.md             step-by-step for every common task
```

The generic Event demo was removed (`bin/rename.php --strip-examples`). There is no reference CPT
yet: follow the recipes and the scaffolder output. The first module implementations will become the
reference.

`bin/rename.php` has already been run once (`agent_theme` → `kelurahan`). Don't run it again here. A
copy for one specific kelurahan keeps the `kelurahan` prefix and only changes content and settings.

## Commands

```bash
php bin/make.php                          # help
php bin/check.php                         # static checks (no WP needed)
php bin/check.php --http                  # + runtime + fetch pages (server must be running)
npm start                                 # dev server on WP_URL from .env
php tools/wp-cli.phar <cmd>               # WP-CLI (wp-cli.yml points it at .wp/)
```

Useful WP-CLI for verifying work:

```bash
php tools/wp-cli.phar post-type list --_builtin=0
php tools/wp-cli.phar post create --post_type=event --post_title="Test" --post_status=publish --porcelain
php tools/wp-cli.phar post meta update <id> event_date 2026-12-01
php tools/wp-cli.phar post update <id> --page_template=page-templates/template-landing.php
php tools/wp-cli.phar rewrite flush
php tools/wp-cli.phar eval 'var_dump( wp_is_block_theme() );'
```

## Recipes (read the one matching your task)

| Task | Recipe |
|---|---|
| New custom post type | docs/recipes/add-post-type.md |
| New taxonomy | docs/recipes/add-taxonomy.md |
| Custom fields (meta) | docs/recipes/add-meta-fields.md |
| Block template (any hierarchy slug) | docs/recipes/add-block-template.md |
| User-selectable page template | docs/recipes/add-page-template.md |
| PHP template / partial | docs/recipes/add-php-template.md |
| Template part (header/footer/…) | docs/recipes/add-template-part.md |
| Block pattern | docs/recipes/add-pattern.md |
| Custom block | docs/recipes/add-block.md |
| Colors, fonts, spacing, CSS | docs/recipes/styling.md |
| Pages, menus, content via CLI | docs/recipes/content-via-cli.md |

## Block markup rules (templates/, parts/, patterns/)

- Every `<!-- wp:name -->` needs a matching `<!-- /wp:name -->`, or self-close `<!-- wp:name /-->`.
- Attribute JSON must be valid JSON (double quotes, no trailing commas).
- Static blocks (paragraph, heading, group, button…) need HTML matching their attributes, or the
  editor shows "unexpected content". Copy markup from existing files rather than inventing it.
- Prefer self-closing dynamic blocks (`post-title`, `post-content`, `query-title`, `template-part`).
- No plain HTML comments inside block markup.
- Use preset refs: `"var:preset|color|accent"` in attributes, `var(--wp--preset--color--accent)` in style.

## Gotchas

- Changing `config/post-types` or `config/taxonomies` auto-flushes rewrites on next request.
  If a CPT URL 404s anyway: `php tools/wp-cli.phar rewrite flush`.
- Meta keys must not start with `_` (protected keys can't be block-bound).
- `customTemplates` in theme.json need a matching `templates/<name>.html` (check.php enforces).
- Block template vs PHP template with the **same** hierarchy slug → block wins. PHP wins only when
  it is **more specific**. Details: docs/architecture.md.
- `index.asset.php` lists `wp.*` script deps by hand. If `index.js` uses a new `wp.x` global, add `wp-x`.
- The `wp server` dev server is single-threaded (PHP built-in server). Slow, but fine for dev.
