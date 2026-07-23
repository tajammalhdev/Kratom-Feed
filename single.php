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
		$post_tags   = get_the_tags();
		$author_id   = (int) get_the_author_meta( 'ID' );
		$avatar      = get_avatar(
			$author_id,
			40,
			'',
			'',
			array(
				'class' => 'h-7 w-7 rounded-full object-cover ring-2 ring-pg-green',
			)
		);
		?>
<main id="main-content">
	<header class="bg-pg-black">
		<div class="container py-8 md:py-12">
			<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'kratom-feed' ); ?>" class="mb-5 text-sm text-gray-400">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-pg-hover"><?php esc_html_e( 'Home', 'kratom-feed' ); ?></a>
				<?php if ( $primary_cat ) : ?>
					/ <a href="<?php echo esc_url( get_category_link( $primary_cat ) ); ?>" class="hover:text-pg-hover"><?php echo esc_html( $primary_cat->name ); ?></a>
				<?php endif; ?>
				/ <span class="text-white"><?php the_title(); ?></span>
			</nav>

			<?php if ( $primary_cat ) : ?>
			<a
				href="<?php echo esc_url( get_category_link( $primary_cat ) ); ?>"
				class="mb-2 inline-flex h-8 items-center gap-2 rounded-md border border-pg-lime/70 bg-neutral-950 px-3 transition-colors hover:border-pg-hover hover:bg-neutral-900"
			>
				<svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M9.56636 8.10031C9.53678 8.14974 9.49773 8.19283 9.45145 8.22713C9.40518 8.26143 9.35259 8.28625 9.29669 8.30017C9.2408 8.31409 9.18271 8.31684 9.12576 8.30826C9.0688 8.29968 9.0141 8.27993 8.96479 8.25016L5.2537 6.02273V10.0625C5.2537 10.1785 5.20761 10.2898 5.12556 10.3719C5.04351 10.4539 4.93223 10.5 4.8162 10.5C4.70017 10.5 4.58889 10.4539 4.50684 10.3719C4.42479 10.2898 4.3787 10.1785 4.3787 10.0625V6.02273L0.666511 8.25016C0.617226 8.28043 0.562426 8.30064 0.505281 8.3096C0.448136 8.31856 0.389782 8.3161 0.333594 8.30236C0.277406 8.28863 0.2245 8.26388 0.177936 8.22957C0.131373 8.19525 0.0920774 8.15204 0.0623227 8.10243C0.032568 8.05283 0.0129449 7.99782 0.00458899 7.94058C-0.00376696 7.88335 -0.000689814 7.82502 0.0136421 7.76898C0.027974 7.71294 0.0532762 7.6603 0.0880849 7.6141C0.122894 7.56791 0.166518 7.52907 0.216433 7.49984L3.96581 5.25L0.216433 3.00016C0.166518 2.97093 0.122894 2.93209 0.0880849 2.8859C0.0532762 2.8397 0.027974 2.78706 0.0136421 2.73102C-0.000689814 2.67498 -0.00376696 2.61665 0.00458899 2.55942C0.0129449 2.50218 0.032568 2.44717 0.0623227 2.39757C0.0920774 2.34796 0.131373 2.30475 0.177936 2.27043C0.2245 2.23612 0.277406 2.21137 0.333594 2.19763C0.389782 2.1839 0.448136 2.18144 0.505281 2.1904C0.562426 2.19936 0.617226 2.21957 0.666511 2.24984L4.3787 4.47727V0.4375C4.3787 0.321468 4.42479 0.210188 4.50684 0.128141C4.58889 0.0460937 4.70017 0 4.8162 0C4.93223 0 5.04351 0.0460937 5.12556 0.128141C5.20761 0.210188 5.2537 0.321468 5.2537 0.4375V4.47727L8.96589 2.24984C9.01517 2.21957 9.06997 2.19936 9.12712 2.1904C9.18426 2.18144 9.24262 2.1839 9.2988 2.19763C9.35499 2.21137 9.4079 2.23612 9.45446 2.27043C9.50103 2.30475 9.54032 2.34796 9.57008 2.39757C9.59983 2.44717 9.61945 2.50218 9.62781 2.55942C9.63616 2.61665 9.63309 2.67498 9.61876 2.73102C9.60442 2.78706 9.57912 2.8397 9.54431 2.8859C9.5095 2.93209 9.46588 2.97093 9.41597 3.00016L5.66659 5.25L9.41597 7.49984C9.46529 7.52938 9.50831 7.56834 9.54256 7.61451C9.57682 7.66068 9.60164 7.71314 9.61561 7.76891C9.62957 7.82468 9.63241 7.88265 9.62396 7.93951C9.61551 7.99638 9.59594 8.05102 9.56636 8.10031Z" fill="#84CC16"/>
				</svg>
				<span class="text-xs font-semibold uppercase tracking-wide text-pg-lime"><?php echo esc_html( $primary_cat->name ); ?></span>
			</a>
			<?php endif; ?>
			<div class="mt-4 flex flex-wrap items-center gap-2.5 mb-4">
				<div class="inline-flex items-center gap-2.5 rounded-full bg-neutral-800/90 py-1 pl-3.5 pr-1.5">
					<span class="text-sm font-medium text-white"><?php the_author(); ?></span>
					<?php echo $avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

				<time
					datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"
					class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-xs font-medium text-neutral-300"
				>
					<svg class="h-3.5 w-3.5 text-pg-lime" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M5.333 1.333V2.667M10.667 1.333V2.667M2.667 6H13.333M3.333 3.333H12.667C13.403 3.333 14 3.93 14 4.667V12.667C14 13.403 13.403 14 12.667 14H3.333C2.597 14 2 13.403 2 12.667V4.667C2 3.93 2.597 3.333 3.333 3.333Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<?php echo esc_html( get_the_date() ); ?>
				</time>

				<?php if ( kratom_feed_get_option( 'show_reading_time', true ) ) : ?>
				<span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-xs font-medium text-neutral-300">
					<svg class="h-3.5 w-3.5 text-pg-lime" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M8 4V8L10.5 9.5M14 8C14 11.314 11.314 14 8 14C4.686 14 2 11.314 2 8C2 4.686 4.686 2 8 2C11.314 2 14 4.686 14 8Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<?php echo esc_html( kratom_feed_reading_time() ); ?>
				</span>
				<?php endif; ?>
			</div>
			<h1 class="text-3xl font-bold uppercase leading-tight text-white md:text-5xl"><?php the_title(); ?></h1>

			

			<?php if ( ! empty( $post_tags ) && ! is_wp_error( $post_tags ) ) : ?>
			<div class="mt-5 flex flex-wrap items-center gap-x-3 gap-y-2" aria-label="<?php esc_attr_e( 'Tags', 'kratom-feed' ); ?>">
				<span class="text-[11px] font-semibold uppercase tracking-[0.16em] text-neutral-500">
					<?php esc_html_e( 'Topics', 'kratom-feed' ); ?>
				</span>
				<span class="hidden h-3 w-px bg-neutral-700 sm:block" aria-hidden="true"></span>
				<ul class="m-0 flex list-none flex-wrap items-center gap-2 p-0">
					<?php foreach ( $post_tags as $tag ) : ?>
					<li>
						<a
							href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"
							class="group inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-xs font-medium text-neutral-200 transition-all hover:border-pg-hover/50 hover:bg-pg-hover/10 hover:text-pg-hover"
						>
							<span class="text-pg-lime/80 transition-colors group-hover:text-pg-hover" aria-hidden="true">#</span>
							<?php echo esc_html( $tag->name ); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
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
		<?php
		$sidebar_enabled = kratom_feed_is_blog_sidebar_enabled() && is_active_sidebar( 'sidebar-blog' );
		$grid_class      = $sidebar_enabled
			? 'grid gap-12 lg:grid-cols-[220px_1fr_300px]'
			: 'grid gap-12 lg:grid-cols-[220px_minmax(0,1fr)]';
		?>
		<div class="<?php echo esc_attr( $grid_class ); ?>">
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
						<button type="button" id="copy-link-btn" class="rounded-full border border-pg-border px-4 py-2 text-sm font-medium text-gray-600 hover:border-pg-hover hover:text-pg-hover cursor-pointer"><?php esc_html_e( 'Copy link', 'kratom-feed' ); ?></button>
					</div>
				</div>
				<?php if ( comments_open() || get_comments_number() ) : ?>
					<div class="mx-auto mt-16 max-w-prose"><?php comments_template(); ?></div>
				<?php endif; ?>
			</article>

			<?php if ( $sidebar_enabled ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
		</div>
	</div>
</main>
		<?php
	endwhile;
endif;

get_footer();

