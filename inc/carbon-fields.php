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
				->set_default_value( 'Kratom Feed' ),
			Field::make( 'checkbox', 'header_search_enabled', __( 'Enable Search', 'kratom-feed' ) )
				->set_default_value( true ),
			Field::make( 'text', 'header_search_placeholder', __( 'Search Placeholder', 'kratom-feed' ) )
				->set_default_value( __( 'Find: best guides for beginners', 'kratom-feed' ) ),
			Field::make( 'text', 'header_feed_label', __( 'Feed Button Label', 'kratom-feed' ) )
				->set_default_value( 'Kratom Feed' ),
			Field::make( 'text', 'header_feed_url', __( 'Feed Button URL', 'kratom-feed' ) )
				->set_default_value( '' ),
			Field::make( 'text', 'header_subscribe_label', __( 'Subscribe Button Label', 'kratom-feed' ) )
				->set_default_value( 'Subscribe' ),
			Field::make( 'text', 'header_about_label', __( 'About Button Label', 'kratom-feed' ) )
				->set_default_value( 'About' ),
			Field::make( 'checkbox', 'header_sticky', __( 'Sticky Header', 'kratom-feed' ) )
				->set_default_value( true ),
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

	// --- Page Builder (Pages + Block Snippets) -----------------------
	Container::make( 'post_meta', __( 'Page Builder', 'kratom-feed' ) )
		->where( 'post_type', 'IN', array( 'page', 'lumen_block_snippet' ) )
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

				// Hero Featured
				->add_fields( 'hero_carousel', __( 'Hero Featured', 'kratom-feed' ), array(
					Field::make( 'association', 'featured_post', __( 'Featured Post', 'kratom-feed' ) )
						->set_types( array(
							array(
								'type'      => 'post',
								'post_type' => 'post',
							),
						) )
						->set_max( 1 )
						->set_help_text( __( 'Leave empty to use the latest published post.', 'kratom-feed' ) ),
					Field::make( 'select', 'category', __( 'Category Filter', 'kratom-feed' ) )
						->set_options( 'kratom_feed_get_blog_categories' ),
					Field::make( 'text', 'grid_count', __( 'Grid Posts Count', 'kratom-feed' ) )
						->set_attribute( 'type', 'number' )
						->set_default_value( '6' )
						->set_help_text( __( 'Number of posts in the right-hand grid (default 6).', 'kratom-feed' ) ),
				) )

				// Trust + Reviews
				->add_fields( 'trust_reviews', __( 'Trust & Reviews', 'kratom-feed' ), array(
					Field::make( 'text', 'heading_before', __( 'Heading (before highlight)', 'kratom-feed' ) )
						->set_default_value( 'Best Place to buy' ),
					Field::make( 'text', 'heading_highlight', __( 'Heading Highlight', 'kratom-feed' ) )
						->set_default_value( 'Kratom Online' ),
					Field::make( 'text', 'heading_after', __( 'Heading (after highlight)', 'kratom-feed' ) )
						->set_default_value( 'in Canada' ),
					Field::make( 'textarea', 'subtitle', __( 'Subtitle', 'kratom-feed' ) ),
					Field::make( 'text', 'rating', __( 'Rating', 'kratom-feed' ) )
						->set_default_value( '4.8' ),
					Field::make( 'text', 'reviews_label', __( 'Reviews Link Text', 'kratom-feed' ) )
						->set_default_value( '1000 reviews' ),
					Field::make( 'complex', 'reviews', __( 'Reviews', 'kratom-feed' ) )
						->add_fields( array(
							Field::make( 'image', 'avatar', __( 'Avatar', 'kratom-feed' ) ),
							Field::make( 'text', 'name', __( 'Name', 'kratom-feed' ) )->set_required( true ),
							Field::make( 'textarea', 'text', __( 'Review Text', 'kratom-feed' ) )->set_required( true ),
							Field::make( 'text', 'date', __( 'Date', 'kratom-feed' ) ),
						) )
						->set_min( 1 ),
				) )

				// Categories Grid
				->add_fields( 'categories_grid', __( 'Categories Grid', 'kratom-feed' ), array(
					Field::make( 'text', 'title', __( 'Section Title', 'kratom-feed' ) )
						->set_default_value( 'Main Categories' ),
					Field::make( 'text', 'button_text', __( 'Button Text', 'kratom-feed' ) )
						->set_default_value( 'Shop All' ),
					Field::make( 'text', 'button_url', __( 'Button URL', 'kratom-feed' ) ),
					Field::make( 'complex', 'categories', __( 'Categories', 'kratom-feed' ) )
						->add_fields( array(
							Field::make( 'image', 'icon', __( 'Icon', 'kratom-feed' ) ),
							Field::make( 'text', 'label', __( 'Label', 'kratom-feed' ) )->set_required( true ),
							Field::make( 'text', 'url', __( 'URL', 'kratom-feed' ) ),
						) )
						->set_min( 1 ),
				) )

				// Blog Posts
				->add_fields( 'blog_posts', __( 'Blog Posts', 'kratom-feed' ), array(
					Field::make( 'text', 'title', __( 'Section Title', 'kratom-feed' ) )
						->set_default_value( "Editor's Picks" ),
					Field::make( 'textarea', 'subtitle', __( 'Subtitle', 'kratom-feed' ) ),
					Field::make( 'text', 'link_text', __( 'View All Link Text', 'kratom-feed' ) )
						->set_default_value( 'View All Articles' ),
					Field::make( 'text', 'link_url', __( 'View All URL', 'kratom-feed' ) ),
					Field::make( 'select', 'layout', __( 'Layout', 'kratom-feed' ) )
						->set_options( array(
							'grid'       => __( 'Grid (4 columns)', 'kratom-feed' ),
							'horizontal' => __( 'Horizontal scroll', 'kratom-feed' ),
						) )
						->set_default_value( 'horizontal' ),
					Field::make( 'text', 'posts_per_page', __( 'Posts Per Page', 'kratom-feed' ) )
						->set_attribute( 'type', 'number' )
						->set_default_value( '4' ),
					Field::make( 'select', 'category', __( 'Category Filter', 'kratom-feed' ) )
						->set_options( 'kratom_feed_get_blog_categories' ),
					Field::make( 'select', 'background', __( 'Background', 'kratom-feed' ) )
						->set_options( array(
							'gray'  => __( 'Light gray', 'kratom-feed' ),
							'white' => __( 'White', 'kratom-feed' ),
						) )
						->set_default_value( 'gray' ),
				) )

				// Newsletter
				->add_fields( 'newsletter_signup', __( 'Newsletter Signup', 'kratom-feed' ), array(
					Field::make( 'text', 'heading', __( 'Heading', 'kratom-feed' ) ),
					Field::make( 'textarea', 'description', __( 'Description', 'kratom-feed' ) ),
					Field::make( 'complex', 'bullets', __( 'Bullet Points', 'kratom-feed' ) )
						->add_fields( array(
							Field::make( 'text', 'text', __( 'Text', 'kratom-feed' ) ),
						) ),
					Field::make( 'text', 'badge_text', __( 'Card Badge Text', 'kratom-feed' ) )
						->set_default_value( 'Free Guide Active' ),
					Field::make( 'text', 'fine_print', __( 'Fine Print', 'kratom-feed' ) ),
				) )

				// Vein Types
				->add_fields( 'vein_types', __( 'Vein Types', 'kratom-feed' ), array(
					Field::make( 'text', 'title', __( 'Section Title', 'kratom-feed' ) )
						->set_default_value( 'Know which vein type is best for you!' ),
					Field::make( 'complex', 'cards', __( 'Cards', 'kratom-feed' ) )
						->add_fields( array(
							Field::make( 'image', 'image', __( 'Image', 'kratom-feed' ) ),
							Field::make( 'text', 'title', __( 'Title', 'kratom-feed' ) )->set_required( true ),
							Field::make( 'textarea', 'description', __( 'Description', 'kratom-feed' ) ),
							Field::make( 'text', 'url', __( 'URL', 'kratom-feed' ) ),
						) )
						->set_min( 1 ),
				) )

				// Rich Text
				->add_fields( 'rich_text', __( 'Rich Text', 'kratom-feed' ), array(
					Field::make( 'text', 'title', __( 'Title', 'kratom-feed' ) ),
					Field::make( 'rich_text', 'content', __( 'Content', 'kratom-feed' ) ),
					Field::make( 'select', 'background', __( 'Background', 'kratom-feed' ) )
						->set_options( array(
							'white' => __( 'White', 'kratom-feed' ),
							'gray'  => __( 'Light gray', 'kratom-feed' ),
						) )
						->set_default_value( 'white' ),
				) )

				// FAQ Accordion
				->add_fields( 'faq_accordion', __( 'FAQ Accordion', 'kratom-feed' ), array(
					Field::make( 'text', 'title', __( 'Title', 'kratom-feed' ) )
						->set_default_value( 'Questions? Bring them all!' ),
					Field::make( 'textarea', 'subtitle', __( 'Subtitle', 'kratom-feed' ) ),
					Field::make( 'complex', 'items', __( 'FAQ Items', 'kratom-feed' ) )
						->add_fields( array(
							Field::make( 'text', 'question', __( 'Question', 'kratom-feed' ) )->set_required( true ),
							Field::make( 'textarea', 'answer', __( 'Answer', 'kratom-feed' ) )->set_required( true ),
						) )
						->set_min( 1 ),
				) )

				// Block Snippet embed
				->add_fields( 'block_snippet', __( 'Block Snippet', 'kratom-feed' ), array(
					Field::make( 'association', 'snippet', __( 'Snippet', 'kratom-feed' ) )
						->set_types( array(
							array(
								'type'      => 'post',
								'post_type' => 'lumen_block_snippet',
							),
						) )
						->set_max( 1 ),
				) ),
		) );
}

