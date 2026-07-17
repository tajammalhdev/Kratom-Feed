# Kratom Feed - WordPress Theme

Pacific Grass-inspired Kratom editorial blog theme with **Carbon Fields** theme options and **Tailwind CSS v4**.

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

## Homepage setup

1. Create a page titled **Home**
2. Set **Settings -> Reading -> Your homepage displays -> A static page -> Home**
3. Edit the page content in the WordPress editor

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
|   |-- carbon-fields.php     # Theme options
|   |-- template-functions.php
|-- template-parts/
|   |-- header/site-header-storefront.php
|   |-- header/storefront-megamenu.php
|   |-- footer/site-footer.php
|   |-- components/newsletter-form.php
|   |-- content/
|-- assets/
    |-- src/css/input.css     # Tailwind source
    |-- css/tailwind.css      # Compiled output
    |-- js/main.js
```

