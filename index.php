<?php
/**
 * Main template
 *
 * @package KratomFeeds
 */

get_header();
?>
<main id="main-content" class="site-main">
<?php
if ( is_page() && function_exists( 'carbon_get_post_meta' ) && carbon_get_post_meta( get_the_ID(), 'use_page_builder' ) ) {
	while ( have_posts() ) :
		the_post();
		kratom_feed_render_page_builder();
	endwhile;
} else {
	?>
	<div class="pg-container py-12">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/content', get_post_type() );
			}
			the_posts_navigation();
		} else {
			get_template_part( 'template-parts/content/content', 'none' );
		}
		?>
	</div>
	<?php
}
?>
</main>
<?php
get_footer();

