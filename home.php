<?php
/**
 * Blog posts index
 *
 * @package KratomFeeds
 */

get_header();

$title    = kratom_feed_get_option( 'blog_archive_title', __( 'Latest Articles', 'kratom-feed' ) );
$subtitle = kratom_feed_get_option( 'blog_archive_subtitle' );
?>
<main id="main-content">
	<section class="border-b border-pg-border bg-pg-gray-light py-14 md:py-20">
		<div class="container text-center">
			<h1 class="text-3xl font-bold uppercase text-gray-900 md:text-5xl"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="mx-auto mt-4 max-w-2xl text-sm text-gray-600"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<div class="container py-12">
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

