<?php
/**
 * Custom Post Types
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Block Snippet CPT for reusable builder sections.
 */
function kratom_feed_register_block_snippet_post_type() {
	register_post_type(
		'lumen_block_snippet',
		array(
			'label'               => __( 'Block Snippets', 'kratom-feed' ),
			'labels'              => array(
				'name'          => __( 'Block Snippets', 'kratom-feed' ),
				'singular_name' => __( 'Block Snippet', 'kratom-feed' ),
				'menu_name'     => __( 'Block Snippets', 'kratom-feed' ),
				'add_new_item'  => __( 'Add New Block Snippet', 'kratom-feed' ),
				'edit_item'     => __( 'Edit Block Snippet', 'kratom-feed' ),
			),
			'supports'            => array( 'title' ),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-screenoptions',
			'publicly_queryable'  => false,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
		)
	);
}
add_action( 'init', 'kratom_feed_register_block_snippet_post_type' );

