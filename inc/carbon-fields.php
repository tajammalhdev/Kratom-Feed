<?php
/**
 * Carbon Fields - Theme Options & Page Builder
 *
 * @package KratomFeeds
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'carbon_fields_register_fields', 'kratom_feed_setup_carbon_fields' );

/**
 * Register Carbon containers.
 */
function kratom_feed_setup_carbon_fields() {

	// --- Theme Options -----------------------------------------------
	Container::make( 'theme_options', __( 'Theme Options', 'kratom-feed' ) )
		->set_page_parent( 'themes.php' )
		->add_tab( __( 'Global', 'kratom-feed' ), array(
			Field::make( 'color', 'primary_green', __( 'Primary Green', 'kratom-feed' ) )
				->set_default_value( '#42b251' ),
			Field::make( 'color', 'accent_lime', __( 'Accent Lime', 'kratom-feed' ) )
				->set_default_value( '#b8f04a' ),
			Field::make( 'text', 'google_rating', __( 'Google Rating', 'kratom-feed' ) )
				->set_default_value( '4.8' ),
			Field::make( 'text', 'review_count', __( 'Review Count Label', 'kratom-feed' ) )
				->set_default_value( '1000 reviews' ),
		) )
		->add_tab( __( 'Header', 'kratom-feed' ), array(
			Field::make( 'text', 'site_tagline_short', __( 'Logo Text', 'kratom-feed' ) )
				->set_default_value( 'Kratom.org' ),
			Field::make( 'checkbox', 'header_search_enabled', __( 'Enable Search', 'kratom-feed' ) )
				->set_default_value( true ),
			Field::make( 'text', 'header_search_placeholder', __( 'Search Placeholder', 'kratom-feed' ) )
				->set_default_value( __( 'What Are You Looking For?', 'kratom-feed' ) ),
			Field::make( 'checkbox', 'header_sticky', __( 'Sticky Header', 'kratom-feed' ) )
				->set_default_value( true ),
			Field::make( 'complex', 'header_trending_searches', __( 'Trending Searches', 'kratom-feed' ) )
				->set_help_text( __( 'Keyword chips under the search field.', 'kratom-feed' ) )
				->add_fields( array(
					Field::make( 'text', 'term', __( 'Search Term', 'kratom-feed' ) )->set_required( true ),
				) )
				->set_header_template( '<%- term %>' ),
			Field::make( 'text', 'header_search_pro_tip', __( 'Search Pro Tip', 'kratom-feed' ) )
				->set_default_value( __( 'Try searching by strain, topic, or vendor name.', 'kratom-feed' ) ),
			Field::make( 'text', 'header_cta_1_label', __( 'CTA 1 Label', 'kratom-feed' ) )
				->set_default_value( __( 'Blog', 'kratom-feed' ) ),
			Field::make( 'text', 'header_cta_1_url', __( 'CTA 1 URL', 'kratom-feed' ) )
				->set_default_value( '/blog/' ),
			Field::make( 'file', 'header_cta_1_icon', __( 'CTA 1 Icon (SVG upload)', 'kratom-feed' ) )
				->set_help_text( __( 'Upload an SVG (preferred) or image. Used if set; otherwise the SVG code below is used.', 'kratom-feed' ) ),
			Field::make( 'textarea', 'header_cta_1_svg', __( 'CTA 1 SVG Code', 'kratom-feed' ) )
				->set_rows( 4 )
				->set_help_text( __( 'Paste raw SVG markup (e.g. &lt;svg&gt;…&lt;/svg&gt;). Ignored if an icon is uploaded above.', 'kratom-feed' ) ),
			Field::make( 'text', 'header_cta_2_label', __( 'CTA 2 Label', 'kratom-feed' ) )
				->set_default_value( __( 'Subscribe', 'kratom-feed' ) ),
			Field::make( 'text', 'header_cta_2_url', __( 'CTA 2 URL', 'kratom-feed' ) )
				->set_default_value( '#newsletter' ),
			Field::make( 'file', 'header_cta_2_icon', __( 'CTA 2 Icon (SVG upload)', 'kratom-feed' ) )
				->set_help_text( __( 'Upload an SVG (preferred) or image. Used if set; otherwise the SVG code below is used.', 'kratom-feed' ) ),
			Field::make( 'textarea', 'header_cta_2_svg', __( 'CTA 2 SVG Code', 'kratom-feed' ) )
				->set_rows( 4 )
				->set_help_text( __( 'Paste raw SVG markup (e.g. &lt;svg&gt;…&lt;/svg&gt;). Ignored if an icon is uploaded above.', 'kratom-feed' ) ),
		) )
		->add_tab( __( 'Footer', 'kratom-feed' ), array(
			Field::make( 'textarea', 'footer_tagline', __( 'Footer Tagline', 'kratom-feed' ) )
				->set_default_value( 'Premium Kratom education - expert guides, strain reviews, and research summaries.' ),
			Field::make( 'text', 'copyright_text', __( 'Copyright Text', 'kratom-feed' ) )
				->set_default_value( '(c) ' . gmdate( 'Y' ) . ' Kratom Feed. All rights reserved.' ),
			Field::make( 'rich_text', 'footer_disclaimer', __( 'Disclaimer', 'kratom-feed' ) )
				->set_default_value( '<strong>Disclaimer:</strong> Kratom Feed provides educational content for informational purposes only. Not medical advice.' ),
			Field::make( 'checkbox', 'floating_chat_enabled', __( 'Show Floating Chat Button', 'kratom-feed' ) )
				->set_default_value( true ),
		) )
		->add_tab( __( 'Newsletter', 'kratom-feed' ), array(
			Field::make( 'text', 'newsletter_heading', __( 'Default Newsletter Heading', 'kratom-feed' ) )
				->set_default_value( 'Sign up and Get Weekly Kratom Guides!' ),
			Field::make( 'textarea', 'newsletter_description', __( 'Default Newsletter Description', 'kratom-feed' ) ),
			Field::make( 'text', 'newsletter_button_text', __( 'Submit Button Text', 'kratom-feed' ) )
				->set_default_value( 'Subscribe' ),
		) )
		->add_tab( __( 'Blog', 'kratom-feed' ), array(
			Field::make( 'text', 'blog_archive_title', __( 'Blog Archive Title', 'kratom-feed' ) )
				->set_default_value( __( 'Latest Articles', 'kratom-feed' ) ),
			Field::make( 'textarea', 'blog_archive_subtitle', __( 'Blog Archive Subtitle', 'kratom-feed' ) ),
			Field::make( 'checkbox', 'show_reading_time', __( 'Show Reading Time', 'kratom-feed' ) )
				->set_default_value( true ),
		) );

	// --- Featured flag on posts (WooCommerce-style) -------------------
	Container::make( 'post_meta', __( 'Featured', 'kratom-feed' ) )
		->where( 'post_type', '=', 'post' )
		->set_context( 'side' )
		->set_priority( 'high' )
		->add_fields( array(
			Field::make( 'checkbox', 'is_featured', __( 'Featured', 'kratom-feed' ) )
				->set_help_text( __( 'Show this post in the Hero Featured carousel when no posts are manually selected.', 'kratom-feed' ) ),
		) );

	// --- Page Builder -------------------------------------------------
	Container::make( 'post_meta', __( 'Page Builder', 'kratom-feed' ) )
		->where( 'post_type', '=', 'page' )
		->add_fields( array(
			Field::make( 'checkbox', 'use_page_builder', __( 'Use Page Builder', 'kratom-feed' ) )
				->set_help_text( __( 'Enable custom builder sections instead of the default editor content.', 'kratom-feed' ) ),

			Field::make( 'complex', 'sections', __( 'Sections', 'kratom-feed' ) )
				->set_layout( 'tabbed-horizontal' )
				->setup_labels( array(
					'plural_name'   => __( 'Sections', 'kratom-feed' ),
					'singular_name' => __( 'Section', 'kratom-feed' ),
				) )
				->set_conditional_logic( array(
					array( 'field' => 'use_page_builder', 'value' => true ),
				) )

				->add_fields( 'hero_featured', __( 'Hero Featured', 'kratom-feed' ), array(
					Field::make( 'checkbox', 'show_live_badge', __( 'Show Live Badge', 'kratom-feed' ) )
						->set_default_value( true ),
					Field::make( 'text', 'live_badge_text', __( 'Live Badge Text', 'kratom-feed' ) )
						->set_default_value( __( 'Live Updates', 'kratom-feed' ) )
						->set_conditional_logic( array(
							array( 'field' => 'show_live_badge', 'value' => true ),
						) ),
					Field::make( 'association', 'posts', __( 'Featured Posts', 'kratom-feed' ) )
						->set_types( array(
							array(
								'type'      => 'post',
								'post_type' => 'post',
							),
						) )
						->set_help_text( __( 'Optional. Leave empty to auto-load posts marked Featured. One post = single hero; multiple = carousel.', 'kratom-feed' ) ),
					Field::make( 'checkbox', 'autoplay', __( 'Autoplay Carousel', 'kratom-feed' ) )
						->set_default_value( true ),
					Field::make( 'text', 'autoplay_ms', __( 'Autoplay Interval (ms)', 'kratom-feed' ) )
						->set_attribute( 'type', 'number' )
						->set_default_value( '6000' )
						->set_conditional_logic( array(
							array( 'field' => 'autoplay', 'value' => true ),
						) ),
				) )

				->add_fields( 'trending_categories', __( 'Trending Categories', 'kratom-feed' ), array(
					Field::make( 'text', 'title', __( 'Title', 'kratom-feed' ) )
						->set_default_value( __( "What's trending:", 'kratom-feed' ) ),
					Field::make( 'file', 'title_icon', __( 'Title Icon (SVG upload)', 'kratom-feed' ) )
						->set_help_text( __( 'Optional icon next to the title. Upload wins over SVG code below.', 'kratom-feed' ) ),
					Field::make( 'textarea', 'title_svg', __( 'Title SVG Code', 'kratom-feed' ) )
						->set_rows( 3 )
						->set_help_text( __( 'Paste raw SVG markup if not uploading a file.', 'kratom-feed' ) ),
					Field::make( 'complex', 'items', __( 'Categories', 'kratom-feed' ) )
						->add_fields( array(
							Field::make( 'text', 'label', __( 'Label', 'kratom-feed' ) )->set_required( true ),
							Field::make( 'text', 'url', __( 'URL', 'kratom-feed' ) ),
							Field::make( 'file', 'icon', __( 'Icon (SVG upload)', 'kratom-feed' ) ),
							Field::make( 'textarea', 'svg_code', __( 'SVG Code', 'kratom-feed' ) )
								->set_rows( 3 ),
						) )
						->set_header_template( '<%- label %>' )
						->set_min( 1 ),
				) )

				->add_fields( 'posts_by_category', __( 'Posts by Category', 'kratom-feed' ), array(
					Field::make( 'select', 'style', __( 'Choose Style', 'kratom-feed' ) )
						->set_options( array(
							'style_1' => __( 'Style 1 — Horizontal cards', 'kratom-feed' ),
							'style_2' => __( 'Style 2 — Latest Articles', 'kratom-feed' ),
						) )
						->set_default_value( 'style_1' ),
					Field::make( 'text', 'title', __( 'Title', 'kratom-feed' ) )
						->set_default_value( __( 'Learn With Kratom Feed', 'kratom-feed' ) ),
					Field::make( 'textarea', 'description', __( 'Description', 'kratom-feed' ) )
						->set_rows( 4 )
						->set_default_value( __( 'Explore a growing library of carefully researched articles, expert guides, and community insights designed to help you understand kratom better.', 'kratom-feed' ) ),
					Field::make( 'file', 'icon', __( 'Icon (SVG / image upload)', 'kratom-feed' ) )
						->set_help_text( __( 'Shown next to the title. Upload wins over SVG code below.', 'kratom-feed' ) ),
					Field::make( 'textarea', 'icon_svg', __( 'Icon SVG Code', 'kratom-feed' ) )
						->set_rows( 3 ),
					Field::make( 'select', 'category', __( 'Category', 'kratom-feed' ) )
						->set_options( 'kratom_feed_get_blog_categories' )
						->set_help_text( __( 'Leave as All categories to show the latest posts from any category.', 'kratom-feed' ) ),
					Field::make( 'text', 'posts_per_page', __( 'Number of Posts', 'kratom-feed' ) )
						->set_attribute( 'type', 'number' )
						->set_default_value( '4' ),
					Field::make( 'checkbox', 'show_button', __( 'Enable Button', 'kratom-feed' ) )
						->set_default_value( true ),
					Field::make( 'text', 'button_text', __( 'Button Text', 'kratom-feed' ) )
						->set_default_value( __( 'Discover More', 'kratom-feed' ) )
						->set_conditional_logic( array(
							array( 'field' => 'show_button', 'value' => true ),
						) ),
					Field::make( 'text', 'button_url', __( 'Button URL', 'kratom-feed' ) )
						->set_default_value( '/blog/' )
						->set_conditional_logic( array(
							array( 'field' => 'show_button', 'value' => true ),
						) ),
				) )

				->add_fields( 'category_spotlight', __( 'Category Spotlight', 'kratom-feed' ), array(
					Field::make( 'text', 'title', __( 'Title', 'kratom-feed' ) )
						->set_default_value( __( 'Safety & Basics', 'kratom-feed' ) ),
					Field::make( 'textarea', 'description', __( 'Description', 'kratom-feed' ) )
						->set_rows( 4 )
						->set_default_value( __( 'Explore the most popular kratom articles, in-depth guides, expert tips, and breaking industry news—all in one place.', 'kratom-feed' ) ),
					Field::make( 'file', 'icon', __( 'Icon (SVG / image upload)', 'kratom-feed' ) )
						->set_help_text( __( 'Shown next to the title. Upload wins over SVG code below.', 'kratom-feed' ) ),
					Field::make( 'textarea', 'icon_svg', __( 'Icon SVG Code', 'kratom-feed' ) )
						->set_rows( 3 ),
					Field::make( 'select', 'category', __( 'Category', 'kratom-feed' ) )
						->set_options( 'kratom_feed_get_blog_categories' )
						->set_help_text( __( 'Posts are fetched from this category. First post is featured; the rest appear in the side list.', 'kratom-feed' ) ),
					Field::make( 'text', 'posts_per_page', __( 'Number of Posts', 'kratom-feed' ) )
						->set_attribute( 'type', 'number' )
						->set_default_value( '6' )
						->set_help_text( __( 'Total posts (2 featured + 4 in the list). Recommended: 6.', 'kratom-feed' ) ),
					Field::make( 'checkbox', 'show_button', __( 'Enable Button', 'kratom-feed' ) )
						->set_default_value( true ),
					Field::make( 'text', 'button_text', __( 'Button Text', 'kratom-feed' ) )
						->set_default_value( __( 'Discover More', 'kratom-feed' ) )
						->set_conditional_logic( array(
							array( 'field' => 'show_button', 'value' => true ),
						) ),
					Field::make( 'text', 'button_url', __( 'Button URL', 'kratom-feed' ) )
						->set_default_value( '/blog/' )
						->set_conditional_logic( array(
							array( 'field' => 'show_button', 'value' => true ),
						) ),
				) )

				->add_fields( 'newsletter', __( 'Newsletter', 'kratom-feed' ), array(
					Field::make( 'text', 'section_id', __( 'Section ID', 'kratom-feed' ) )
						->set_default_value( 'newsletter' )
						->set_help_text( __( 'Used for links such as #newsletter.', 'kratom-feed' ) ),
					Field::make( 'image', 'background_image', __( 'Background Image', 'kratom-feed' ) )
						->set_value_type( 'id' )
						->set_help_text( __( 'Use a dark botanical image for the Figma layout.', 'kratom-feed' ) ),
					Field::make( 'text', 'heading_top', __( 'Heading — First Line', 'kratom-feed' ) )
						->set_default_value( __( 'Never Miss a', 'kratom-feed' ) ),
					Field::make( 'text', 'heading_accent', __( 'Heading — Green Line', 'kratom-feed' ) )
						->set_default_value( __( 'Kratom Update', 'kratom-feed' ) ),
					Field::make( 'textarea', 'description', __( 'Description', 'kratom-feed' ) )
						->set_rows( 5 )
						->set_default_value( __( 'Stay ahead with the latest developments in the world of kratom. From newly released strains and updated vendor rankings to research summaries, industry news, and expert insights, we curate the information that matters most.', 'kratom-feed' ) ),
					Field::make( 'text', 'form_heading', __( 'Signup Panel Heading', 'kratom-feed' ) )
						->set_default_value( __( 'Join Our Kratom Newsletter', 'kratom-feed' ) )
						->set_help_text( __( 'The Omnisend signup form will be connected to the reserved panel later.', 'kratom-feed' ) ),
				) ),
		) );
}

