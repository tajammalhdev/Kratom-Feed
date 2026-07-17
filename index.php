<?php
/**
 * Main template
 *
 * @package KratomFeeds
 */

get_header();
?>
<main id="main-content" class="site-main">
	<div class="container py-12">
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
</main>
<?php
get_footer();
