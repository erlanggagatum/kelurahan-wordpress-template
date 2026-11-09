# Add a block pattern

```bash
php bin/make.php pattern pricing-table --title="Pricing table" --categories=kelurahan-sections
```

This creates `patterns/pricing-table.php`. WordPress registers it automatically from its file header.

Header fields:

- `Title`: required.
- `Slug: kelurahan/<slug>`: required.
- `Categories`, `Keywords`, `Description`: optional.
- `Block Types`: e.g. `core/post-content` offers the pattern as a starter for new pages.
- `Post Types`: limits where the pattern is offered.
- `Inserter: no`: hides the pattern from the inserter.

Body: block markup. Wrap text in `esc_html__( '…', 'kelurahan' )` and URLs in `esc_url()`.

Use it:

- In content or templates: `<!-- wp:pattern {"slug":"kelurahan/pricing-table"} /-->`
- In PHP: `kelurahan_pattern( 'kelurahan/pricing-table' );`

Categories:

- The theme registers `kelurahan` and `kelurahan-sections` in `inc/patterns.php`.
- Core categories also work: `banner`, `call-to-action`, `text`, `gallery`, `featured`, `footer`, `header`.

Tip: build the section in the editor, then use *Copy blocks* and paste the markup into the pattern. Hand-written static block markup must match what the editor would save.
