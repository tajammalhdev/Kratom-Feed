<?php
/**
 * Kratom Feed Theme Functions
 *
 * Debug mode: Carbon Fields only (no frontend template rendering).
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
 * Minimal theme setup.
 */
function kratom_feed_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'kratom_feed_setup' );

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

require_once KRATOM_FEED_DIR . '/inc/post-types.php';

/**
 * Blog categories for Carbon select fields.
 */
function kratom_feed_get_blog_categories() {
	$choices = array( '' => __( 'All categories', 'kratom-feed' ) );
	$terms   = get_categories( array( 'hide_empty' => false ) );
	foreach ( $terms as $term ) {
		$choices[ (string) $term->term_id ] = $term->name;
	}
	return $choices;
}

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
