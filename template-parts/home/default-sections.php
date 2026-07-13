<?php
/**
 * Default homepage sections (static demo fallback)
 *
 * Used when the front page does not have Page Builder enabled.
 *
 * @package KratomFeeds
 */

$sections = array(
	array(
		'_type'       => 'hero_carousel',
		'grid_count'  => '6',
	),
	array(
		'_type'             => 'trust_reviews',
		'subtitle'          => "Canada's most trusted Kratom education blog with thousands of 5-star reader reviews and industry-leading editorial standards.",
		'reviews'           => array(
			array( 'name' => 'Russell Harry', 'text' => "Kratom Feed is my go-to for Kratom education. Clear guides, no sales pressure, and always up to date.", 'date' => 'Jun 19, 2026' ),
			array( 'name' => 'Trina McCavour', 'text' => "I'm new to Kratom and love the consistency of the guides. Everything is easy to understand.", 'date' => 'Jun 14, 2026' ),
			array( 'name' => 'James P.', 'text' => 'What an amazing editorial team - never have any issue finding what I need.', 'date' => 'Jun 10, 2026' ),
		),
	),
	array(
		'_type'      => 'categories_grid',
		'button_url' => get_post_type_archive_link( 'post' ),
		'categories' => array(
			array( 'label' => 'Guides', 'url' => home_url( '/category/guides/' ) ),
			array( 'label' => 'Strains', 'url' => home_url( '/category/strains/' ) ),
			array( 'label' => 'Reviews', 'url' => home_url( '/category/reviews/' ) ),
			array( 'label' => 'Research', 'url' => home_url( '/category/research/' ) ),
			array( 'label' => 'News', 'url' => home_url( '/category/news/' ) ),
			array( 'label' => 'Dosage', 'url' => home_url( '/category/dosage/' ) ),
		),
	),
	array(
		'_type'    => 'blog_posts',
		'title'    => "Editor's Picks",
		'subtitle' => 'Kratom Feed is the best place for Kratom education online.',
		'layout'   => 'horizontal',
	),
	array(
		'_type'       => 'newsletter_signup',
		'description' => 'Subscribe today and instantly get our complete Beginner\'s Guide PDF added to your inbox.',
		'bullets'     => array(
			array( 'text' => '100% Free - no spam, unsubscribe anytime.' ),
			array( 'text' => 'Weekly digest of new guides and research.' ),
			array( 'text' => 'Expert-reviewed content you can trust.' ),
		),
		'fine_print'  => 'For new subscribers only. We respect your privacy.',
	),
	array(
		'_type'          => 'blog_posts',
		'title'          => 'Latest Articles',
		'link_text'      => 'See All New',
		'layout'         => 'grid',
		'background'     => 'white',
		'posts_per_page' => '4',
	),
	array(
		'_type' => 'vein_types',
		'cards' => array(
			array( 'title' => 'Red Vein', 'description' => 'Red vein strains are calming and relaxing. Many readers choose red vein Kratom for unwinding after a long day.', 'url' => home_url( '/category/red-vein/' ) ),
			array( 'title' => 'Green Vein', 'description' => 'Green vein offers balanced effects suitable for daytime use.', 'url' => home_url( '/category/green-vein/' ) ),
			array( 'title' => 'White Vein', 'description' => 'White vein strains are uplifting and energizing.', 'url' => home_url( '/category/white-vein/' ) ),
		),
	),
	array(
		'_type'   => 'rich_text',
		'title'   => 'Why is Kratom Feed the best place for Kratom education online?',
		'content' => '<p>We understand how important accurate information is when learning about Kratom. That is why every article is fact-checked, sourced from peer-reviewed research, and updated regularly.</p><p>When you read Kratom Feed, you get access to our wide library of premium educational content including guides, strain profiles, research summaries, legal updates, and dosage frameworks.</p>',
	),
	array(
		'_type'    => 'faq_accordion',
		'subtitle' => 'We are here to answer all your questions about Kratom education.',
		'items'    => array(
			array( 'question' => 'What is Kratom?', 'answer' => 'Kratom (Mitragyna speciosa) is a tropical evergreen tree native to Southeast Asia.' ),
			array( 'question' => 'Is Kratom legal in the United States?', 'answer' => 'Kratom is legal at the federal level but regulated differently by state.' ),
			array( 'question' => "What's the difference between vein colors?", 'answer' => 'Red vein tends toward calming effects, white vein toward energizing, and green vein offers a middle ground.' ),
			array( 'question' => 'Does Kratom Feed sell Kratom products?', 'answer' => 'No. Kratom Feed is purely an educational publication.' ),
		),
	),
);

foreach ( $sections as $section_data ) {
	$section_type = $section_data['_type'];
	$template     = locate_template( "template-parts/builder/{$section_type}.php" );
	if ( $template ) {
		include $template;
	}
}

