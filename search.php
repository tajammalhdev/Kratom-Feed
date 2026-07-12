<?php
/**
 * Search results template
 *
 * @package KratomFeeds
 */

get_header();
?>
<main id="main-content">
	<section class="border-b border-pg-border bg-pg-gray-light py-14">
		<div class="pg-container">
			<h1 class="text-3xl font-bold uppercase text-gray-900 md:text-5xl">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Search: %s', 'kratom-feed' ),
					esc_html( get_search_query() )
				);
				?>
			</h1>
		</div>
	</section>
	<div class="pg-container py-12">
		<div id="article-grid" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'template-parts/content/content', get_post_type() ); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
			<?php endif; ?>
		</div>
		<div class="mt-10"><?php the_posts_pagination(); ?></div>
	</div>
</main>
<?php
get_footer();

