<?php
/**
 * Kratom Feed Theme Functions
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KRATOM_FEED_VERSION', '1.0.0' );
define( 'KRATOM_FEED_DIR', get_template_directory() );
define( 'KRATOM_FEED_URI', get_template_directory_uri() );

/**
 * Theme prefix helper.
 */
function kratom_feed_prefix() {
	return 'kratom-feed';
}

/**
 * Theme setup.
 */
function kratom_feed_setup() {
	load_theme_textdomain( 'kratom-feed', KRATOM_FEED_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );

	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Navigation', 'kratom-feed' ),
		'footer'  => esc_html__( 'Footer Menu', 'kratom-feed' ),
	) );

	$GLOBALS['content_width'] = 1320;
}
add_action( 'after_setup_theme', 'kratom_feed_setup' );

/**
 * Register widget areas.
 */
function kratom_feed_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'kratom-feed' ),
		'id'            => 'sidebar-blog',
		'description'   => esc_html__( 'Sidebar on archive and single posts.', 'kratom-feed' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="text-xs font-bold uppercase tracking-[0.15em] text-pg-lime mb-4">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'kratom_feed_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kratom_feed_scripts() {
	$prefix = kratom_feed_prefix();

	wp_enqueue_style(
		$prefix . '-fonts',
		'https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		$prefix . '-style',
		KRATOM_FEED_URI . '/assets/css/tailwind.css',
		array( $prefix . '-fonts' ),
		KRATOM_FEED_VERSION
	);

	wp_enqueue_script(
		$prefix . '-main',
		KRATOM_FEED_URI . '/assets/js/main.js',
		array(),
		KRATOM_FEED_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'kratom_feed_scripts' );

/**
 * Boot Carbon Fields.
 */
function kratom_feed_load_carbon_fields() {
	if ( ! file_exists( KRATOM_FEED_DIR . '/vendor/autoload.php' ) ) {
		return;
	}
	require_once KRATOM_FEED_DIR . '/vendor/autoload.php';
	\Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'after_setup_theme', 'kratom_feed_load_carbon_fields', 5 );

require_once KRATOM_FEED_DIR . '/inc/template-functions.php';
require_once KRATOM_FEED_DIR . '/inc/builder-functions.php';
require_once KRATOM_FEED_DIR . '/inc/post-types.php';
require_once KRATOM_FEED_DIR . '/inc/shortcodes.php';

if ( file_exists( KRATOM_FEED_DIR . '/vendor/autoload.php' ) ) {
	require_once KRATOM_FEED_DIR . '/inc/carbon-fields.php';
}

/**
 * Theme activation: flush rewrite rules for block snippets CPT.
 */
function kratom_feed_activate() {
	kratom_feed_register_block_snippet_post_type();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'kratom_feed_activate' );

