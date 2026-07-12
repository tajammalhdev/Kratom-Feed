# Kratom Feed â€” WordPress Theme

Pacific Grass-inspired Kratom editorial blog theme with **Carbon Fields Page Builder** and **Tailwind CSS v4**.

## Requirements

- WordPress 6.4+
- PHP 8.0+
- Composer
- Node.js 18+

## Installation

1. Copy or symlink `kratom-feed/` to `wp-content/themes/kratom-feed`
2. Install PHP dependencies:

```bash
cd wp-content/themes/kratom-feed
composer install
```

3. Build CSS:

```bash
npm install
npm run build:css
```

4. Activate **Kratom Feed** in WordPress admin â†’ Appearance â†’ Themes

## Theme Options

**Appearance â†’ Theme Options** (Carbon Fields):

- **Global** â€” colors, Google rating
- **Header** â€” logo text, search, utility buttons
- **Footer** â€” tagline, copyright, disclaimer
- **Newsletter** â€” default signup copy
- **Blog** â€” archive title, reading time toggle

## Page Builder (Element Builder)

Edit any **Page** or **Block Snippet** and enable **Use Page Builder**.

### Available sections

| Section | Description |
|---------|-------------|
| Hero Featured | Magazine-style hero with featured post + 6-post grid |
| Trust & Reviews | â€œBest Place to buyâ€¦â€ + Google rating + review carousel |
| Categories Grid | Main Categories with Shop All button |
| Blog Posts | Editor's picks / latest articles grid or horizontal scroll |
| Newsletter Signup | PG-style promo signup block |
| Vein Types | 3-column education cards |
| Rich Text | Title + WYSIWYG content |
| FAQ Accordion | Expandable Q&A |
| Block Snippet | Embed a reusable Block Snippet |

### Block Snippets

Create reusable sections under **Block Snippets** in the admin menu. Embed anywhere with:

```
[lumen_block_snippet id="123"]
[lumen_block_snippet slug="my-snippet"]
```

## Homepage setup

1. Create a page titled **Home**
2. Set **Settings â†’ Reading â†’ Your homepage displays â†’ A static page â†’ Home**
3. Either leave Page Builder off (uses built-in demo sections) or enable it and add sections manually

## Development

```bash
npm run watch:css   # Tailwind watch mode
```

## File structure

```
kratom-feed/
â”œâ”€â”€ style.css                 # Theme header
â”œâ”€â”€ functions.php             # Bootstrap
â”œâ”€â”€ composer.json             # Carbon Fields packages
â”œâ”€â”€ inc/
â”‚   â”œâ”€â”€ carbon-fields.php     # Theme options + page builder fields
â”‚   â”œâ”€â”€ template-functions.php
â”‚   â”œâ”€â”€ builder-functions.php
â”‚   â”œâ”€â”€ post-types.php        # lumen_block_snippet CPT
â”‚   â””â”€â”€ shortcodes.php
â”œâ”€â”€ template-parts/
â”‚   â”œâ”€â”€ header/site-header.php
â”‚   â”œâ”€â”€ footer/site-footer.php
â”‚   â”œâ”€â”€ builder/              # One PHP file per section type
â”‚   â””â”€â”€ home/default-sections.php
â””â”€â”€ assets/
    â”œâ”€â”€ src/css/input.css     # Tailwind source
    â”œâ”€â”€ css/tailwind.css      # Compiled output
    â””â”€â”€ js/main.js
```

