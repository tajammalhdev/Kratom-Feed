# Kratom Feed - WordPress Theme

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

4. Activate **Kratom Feed** in WordPress admin -> Appearance -> Themes

## Theme Options

**Appearance -> Theme Options** (Carbon Fields):

- **Global** - colors, Google rating
- **Header** - logo text, search, utility buttons
- **Footer** - tagline, copyright, disclaimer
- **Newsletter** - default signup copy
- **Blog** - archive title, reading time toggle

## Page Builder (Element Builder)

Edit any **Page** or **Block Snippet** and enable **Use Page Builder**.

### Available sections

| Section | Description |
|---------|-------------|
| Hero Featured | Magazine-style hero with featured post + 6-post grid |
| Featured Mosaic | Ncmaz-style 4-card mosaic (2 small + wide + tall) |
| Trust & Reviews | "Best Place to buy..." + Google rating + review carousel |
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
2. Set **Settings -> Reading -> Your homepage displays -> A static page -> Home**
3. Either leave Page Builder off (uses built-in demo sections) or enable it and add sections manually

## Development

```bash
npm run watch:css   # Tailwind watch mode
```

## File structure

```
kratom-feed/
|-- style.css                 # Theme header
|-- functions.php             # Bootstrap
|-- composer.json             # Carbon Fields packages
|-- inc/
|   |-- carbon-fields.php     # Theme options + page builder fields
|   |-- template-functions.php
|   |-- builder-functions.php
|   |-- post-types.php        # lumen_block_snippet CPT
|   |-- shortcodes.php
|-- template-parts/
|   |-- header/site-header.php
|   |-- footer/site-footer.php
|   |-- builder/              # One PHP file per section type
|   |-- home/default-sections.php
|-- assets/
    |-- src/css/input.css     # Tailwind source
    |-- css/tailwind.css      # Compiled output
    |-- js/main.js
```

