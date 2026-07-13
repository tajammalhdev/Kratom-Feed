<?php
/**
 * Page template
 *
 * @package KratomFeeds
 */

get_header();
?>
<main id="main-content">
<?php
while ( have_posts() ) :
	the_post();
	if ( function_exists( 'carbon_get_post_meta' ) && carbon_get_post_meta( get_the_ID(), 'use_page_builder' ) ) {
		kratom_feed_render_page_builder();
	} else {
		?>
		<div class="pg-container py-12">
			<h1 class="text-3xl font-bold uppercase text-gray-900 md:text-5xl"><?php the_title(); ?></h1>
			<div class="pg-prose mt-8 max-w-prose"><?php the_content(); ?></div>
		</div>
		<?php
	}
endwhile;
?>
</main>
<?php
get_footer();

