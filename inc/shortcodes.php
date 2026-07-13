<?php
/**
 * Shortcodes
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a reusable block snippet by ID or slug.
 *
 * Usage: [lumen_block_snippet id="123"] or [lumen_block_snippet slug="homepage-trust"]
 */
function kratom_feed_block_snippet_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'id'   => '',
		'slug' => '',
	), $atts, 'lumen_block_snippet' );

	$post_id = 0;
	if ( $atts['id'] ) {
		$post_id = absint( $atts['id'] );
	} elseif ( $atts['slug'] ) {
		$post = get_page_by_path( sanitize_title( $atts['slug'] ), OBJECT, 'lumen_block_snippet' );
		$post_id = $post ? $post->ID : 0;
	}

	if ( ! $post_id ) {
		return '';
	}

	return kratom_feed_get_builder_content( $post_id );
}
add_shortcode( 'lumen_block_snippet', 'kratom_feed_block_snippet_shortcode' );

