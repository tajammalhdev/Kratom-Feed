<?php
/**
 * Template Functions
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get theme option with fallback.
 */
function kratom_feed_get_option( $key, $default = '' ) {
	if ( ! function_exists( 'carbon_get_theme_option' ) ) {
		return $default;
	}
	$value = carbon_get_theme_option( $key );
	return ( $value !== '' && $value !== null && $value !== false ) ? $value : $default;
}

/**
 * Get attachment image URL from Carbon image field.
 */
function kratom_feed_image_url( $image_id, $size = 'large' ) {
	if ( ! $image_id ) {
		return '';
	}
	$src = wp_get_attachment_image_src( $image_id, $size );
	return $src ? $src[0] : '';
}

/**
 * Render page builder for current post.
 */
function kratom_feed_render_page_builder( $post_id = null ) {
	echo kratom_feed_get_builder_content( $post_id ?: get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Render builder sections for a post.
 */
function kratom_feed_render_builder_sections_for_post( $post_id ) {
	$post_id = absint( $post_id );
	if ( ! $post_id || ! function_exists( 'carbon_get_post_meta' ) ) {
		return '';
	}

	$sections = carbon_get_post_meta( $post_id, 'sections' );
	if ( empty( $sections ) || ! is_array( $sections ) ) {
		return '';
	}

	ob_start();
	foreach ( $sections as $section ) {
		$section_type = isset( $section['_type'] ) ? $section['_type'] : '';
		if ( ! $section_type ) {
			continue;
		}

		$template_path = locate_template( "template-parts/builder/{$section_type}.php" );
		if ( ! $template_path ) {
			continue;
		}

		$section_data = $section;
		include $template_path;
	}

	return ob_get_clean();
}

/**
 * Get builder content with fallback to post content.
 */
function kratom_feed_get_builder_content( $post_id ) {
	$post_id = absint( $post_id );
	if ( ! $post_id ) {
		return '';
	}

	if ( function_exists( 'carbon_get_post_meta' ) && carbon_get_post_meta( $post_id, 'use_page_builder' ) ) {
		$output = kratom_feed_render_builder_sections_for_post( $post_id );
		if ( $output !== '' ) {
			return $output;
		}
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}

	return apply_filters( 'the_content', $post->post_content );
}

/**
 * Primary category label for a post.
 */
function kratom_feed_get_post_primary_category( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$cats    = get_the_category( $post_id );
	return ! empty( $cats ) ? $cats[0]->name : '';
}

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

/**
 * Estimated reading time in minutes.
 */
function kratom_feed_reading_time( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	return sprintf(
		/* translators: %d: number of minutes */
		_n( '%d min read', '%d min read', $minutes, 'kratom-feed' ),
		$minutes
	);
}

/**
 * Primary nav fallback links.
 */
function kratom_feed_primary_nav_fallback() {
	$links = array(
		array( 'url' => get_post_type_archive_link( 'post' ), 'label' => __( 'All Articles', 'kratom-feed' ) ),
		array( 'url' => home_url( '/category/guides/' ), 'label' => __( 'Guides', 'kratom-feed' ) ),
		array( 'url' => home_url( '/category/strains/' ), 'label' => __( 'Strains', 'kratom-feed' ) ),
		array( 'url' => home_url( '/category/reviews/' ), 'label' => __( 'Reviews', 'kratom-feed' ) ),
		array( 'url' => home_url( '/category/research/' ), 'label' => __( 'Research', 'kratom-feed' ) ),
		array( 'url' => home_url( '/category/news/' ), 'label' => __( 'News', 'kratom-feed' ) ),
		array( 'url' => get_permalink( get_page_by_path( 'about' ) ) ?: home_url( '/about/' ), 'label' => __( 'About', 'kratom-feed' ) ),
	);

	echo '<div class="pg-container flex items-center justify-center gap-6 py-3 text-sm font-medium text-neutral-800 xl:gap-8">';
	foreach ( $links as $link ) {
		if ( empty( $link['url'] ) ) {
			continue;
		}
		printf(
			'<a href="%s" class="transition-colors hover:text-green-600 cursor-pointer">%s</a>',
			esc_url( $link['url'] ),
			esc_html( $link['label'] )
		);
	}
	echo '</div>';
}

/**
 * Render primary navigation.
 */
function kratom_feed_primary_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => 'nav',
			'container_class'=> 'hidden lg:block',
			'menu_class'     => 'pg-container flex items-center justify-center gap-6 py-3 text-sm font-medium text-neutral-800 xl:gap-8 list-none m-0 p-0',
			'fallback_cb'    => false,
			'depth'          => 1,
		) );
	} else {
		echo '<nav class="hidden lg:block" aria-label="' . esc_attr__( 'Main navigation', 'kratom-feed' ) . '">';
		kratom_feed_primary_nav_fallback();
		echo '</nav>';
	}
}

