<?php
/**
 * Builder helper functions
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Parse spacing value from builder fields.
 */
function kratom_feed_parse_spacing( $value ) {
	if ( empty( $value ) ) {
		return '';
	}
	if ( is_numeric( $value ) ) {
		return ( (int) $value * 4 ) . 'px';
	}
	return (string) $value;
}

/**
 * Build inline section style from padding fields.
 */
function kratom_feed_section_style( $section_data ) {
	$parts = array();
	$pt    = kratom_feed_parse_spacing( $section_data['padding_top'] ?? '' );
	$pb    = kratom_feed_parse_spacing( $section_data['padding_bottom'] ?? '' );
	if ( $pt ) {
		$parts[] = 'padding-top:' . $pt;
	}
	if ( $pb ) {
		$parts[] = 'padding-bottom:' . $pb;
	}
	return $parts ? ' style="' . esc_attr( implode( ';', $parts ) ) . '"' : '';
}

/**
 * Render a CTA button with PG styles.
 */
function kratom_feed_render_button( $text, $url, $style = 'lime' ) {
	if ( ! $text || ! $url ) {
		return;
	}
	$classes = 'lime' === $style
		? 'pg-btn-lime'
		: 'inline-flex items-center justify-center gap-2 rounded-sm bg-pg-green px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-pg-green-dark cursor-pointer';
	printf(
		'<a href="%s" class="%s">%s</a>',
		esc_url( $url ),
		esc_attr( $classes ),
		esc_html( $text )
	);
}

