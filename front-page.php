<?php
/**
 * Front page template
 *
 * @package KratomFeeds
 */

get_header();
?>
<main id="main-content">
<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		if ( function_exists( 'carbon_get_post_meta' ) && carbon_get_post_meta( get_the_ID(), 'use_page_builder' ) ) {
			kratom_feed_render_page_builder();
		} else {
			get_template_part( 'template-parts/home/default', 'sections' );
		}
	endwhile;
else :
	get_template_part( 'template-parts/home/default', 'sections' );
endif;
?>
</main>
<?php
get_footer();

