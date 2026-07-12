<?php
/**
 * Builder: Block Snippet embed
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$snippet = $section_data['snippet'] ?? array();
if ( empty( $snippet[0]['id'] ) ) {
	return;
}
echo kratom_feed_get_builder_content( (int) $snippet[0]['id'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

