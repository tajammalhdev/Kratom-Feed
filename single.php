<?php
/**
 * Single post template
 *
 * @package KratomFeeds
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		$categories  = get_the_category();
		$primary_cat = ! empty( $categories ) ? $categories[0] : null;
		?>
<main id="main-content">
	<header class="bg-pg-black">
		<div class="container py-8 md:py-12">
			<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'kratom-feed' ); ?>" class="mb-4 text-sm text-gray-400">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-pg-lime"><?php esc_html_e( 'Home', 'kratom-feed' ); ?></a>
				<?php if ( $primary_cat ) : ?>
					/ <a href="<?php echo esc_url( get_category_link( $primary_cat ) ); ?>" class="hover:text-pg-lime"><?php echo esc_html( $primary_cat->name ); ?></a>
				<?php endif; ?>
				/ <span class="text-white"><?php the_title(); ?></span>
			</nav>
			<?php if ( $primary_cat ) : ?>
				<span class="pg-badge-new"><?php echo esc_html( $primary_cat->name ); ?></span>
			<?php endif; ?>
			<h1 class="mt-4 text-3xl font-bold uppercase leading-tight text-white md:text-5xl"><?php the_title(); ?></h1>
			<div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-gray-400">
				<span class="font-medium text-white"><?php the_author(); ?></span>
				<span><?php echo esc_html( get_the_date() ); ?></span>
				<?php if ( kratom_feed_get_option( 'show_reading_time', true ) ) : ?>
					<span><?php echo esc_html( kratom_feed_reading_time() ); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<?php if ( has_post_thumbnail() ) : ?>
		<div class="container pb-8">
			<?php the_post_thumbnail( 'full', array( 'class' => 'w-full rounded-2xl object-cover shadow-2xl aspect-[21/9]' ) ); ?>
		</div>
		<?php endif; ?>
	</header>

	<div class="border-b border-pg-border bg-pg-gray-light lg:hidden">
		<div class="container py-4">
			<button id="mobile-toc-toggle" type="button" class="flex w-full items-center justify-between rounded-xl border border-pg-border bg-white px-4 py-3 text-sm font-bold uppercase cursor-pointer" aria-expanded="false" aria-controls="mobile-toc"><?php esc_html_e( 'On this page', 'kratom-feed' ); ?> <span>v</span></button>
			<nav id="mobile-toc" class="mt-2 hidden rounded-xl border border-pg-border bg-white p-4" aria-label="<?php esc_attr_e( 'Table of contents', 'kratom-feed' ); ?>"></nav>
		</div>
	</div>

	<div class="container py-12">
		<div class="grid gap-12 lg:grid-cols-[220px_1fr_300px]">
			<aside class="hidden lg:block" aria-label="<?php esc_attr_e( 'Table of contents', 'kratom-feed' ); ?>">
				<nav id="desktop-toc" class="sticky top-28 space-y-1">
					<p class="mb-3 text-xs font-bold uppercase tracking-[0.15em] text-gray-500"><?php esc_html_e( 'On this page', 'kratom-feed' ); ?></p>
				</nav>
			</aside>

			<article id="article-content" class="min-w-0">
				<div class="pg-prose mx-auto max-w-prose">
					<?php the_content(); ?>
				</div>
				<div class="mx-auto mt-12 max-w-prose border-t border-pg-border pt-8">
					<p class="text-xs font-bold uppercase tracking-wider text-gray-900"><?php esc_html_e( 'Share this article', 'kratom-feed' ); ?></p>
					<div class="mt-4 flex flex-wrap gap-2">
						<button type="button" id="copy-link-btn" class="rounded-full border border-pg-border px-4 py-2 text-sm font-medium text-gray-600 hover:border-pg-green hover:text-pg-green-dark cursor-pointer"><?php esc_html_e( 'Copy link', 'kratom-feed' ); ?></button>
					</div>
				</div>
				<?php if ( comments_open() || get_comments_number() ) : ?>
					<div class="mx-auto mt-16 max-w-prose"><?php comments_template(); ?></div>
				<?php endif; ?>
			</article>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
		<?php
	endwhile;
endif;

get_footer();

