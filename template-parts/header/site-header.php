<?php
/**
 * Site header - Magzin-style full-width bar + kratom.org drawer
 *
 * Layout: Logo | Desktop nav | Search trigger | Hamburger
 * Search opens Magzin-style modal. Hamburger opens black accordion drawer
 * with Popular posts + Newsletter.
 *
 * @package KratomFeeds
 */

$logo_text = kratom_feed_get_option( 'site_tagline_short', 'Kratom.org' );
$search_on = kratom_feed_get_option( 'header_search_enabled', true );
$sticky    = kratom_feed_get_option( 'header_sticky', true );
$nav_tree    = kratom_feed_get_nav_tree();

$popular_q = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 4,
		'ignore_sticky_posts' => true,
		'orderby'             => 'comment_count',
		'order'               => 'DESC',
	)
);
if ( ! $popular_q->have_posts() ) {
	$popular_q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 4,
			'ignore_sticky_posts' => true,
		)
	);
}

$newsletter_heading = kratom_feed_get_option( 'newsletter_heading', __( 'Subscribe our newsletter', 'kratom-feed' ) );
$newsletter_desc    = kratom_feed_get_option( 'newsletter_description', __( "You'll only receive updates on new articles, no spam.", 'kratom-feed' ) );
?>
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-sm focus:bg-black focus:px-4 focus:py-2 focus:text-white"><?php esc_html_e( 'Skip to content', 'kratom-feed' ); ?></a>

<div id="reading-progress" class="fixed left-0 top-0 z-[70] h-1 w-0 bg-black transition-[width] duration-150" aria-hidden="true"></div>

<header id="site-header" class="<?php echo $sticky ? 'sticky top-0' : ''; ?> z-[990] border-b border-gray-200 bg-white/95 backdrop-blur-md transition-shadow duration-300">
	<div class="pg-container">
		<div class="flex h-16 items-center justify-between gap-4 md:h-[4.5rem] md:gap-6">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex shrink-0 items-center gap-2" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<span class="flex h-8 w-8 shrink-0 items-center justify-center text-black" aria-hidden="true">
						<svg class="h-7 w-7" viewBox="0 0 32 32" fill="currentColor">
							<path d="M4 20c4-5 8-7.5 12-7.5S24 15 28 20c-4 1.5-8 2.2-12 2.2S8 21.5 4 20z" opacity="0.35"/>
							<path d="M5 16c3.5-4.2 7-6.2 11-6.2s7.5 2 11 6.2c-3.5 1.4-7 2-11 2s-7.5-.6-11-2z" opacity="0.65"/>
							<path d="M6.5 12c3-3.5 6-5.2 9.5-5.2S21.5 8.5 24.5 12c-3 1.2-6 1.8-9.5 1.8S9.5 13.2 6.5 12z"/>
						</svg>
					</span>
					<span class="text-[17px] font-bold tracking-tight text-black md:text-lg"><?php echo esc_html( $logo_text ); ?></span>
				<?php endif; ?>
			</a>

			<nav class="hidden min-w-0 flex-1 items-center justify-center gap-5 lg:flex xl:gap-7" aria-label="<?php esc_attr_e( 'Main navigation', 'kratom-feed' ); ?>">
				<?php foreach ( $nav_tree as $link ) : ?>
					<?php if ( empty( $link['url'] ) && empty( $link['children'] ) ) { continue; } ?>
					<a href="<?php echo esc_url( $link['url'] ?: '#' ); ?>" class="whitespace-nowrap text-[15px] font-medium text-gray-900 transition-colors hover:text-black cursor-pointer"><?php echo esc_html( $link['label'] ); ?></a>
				<?php endforeach; ?>
			</nav>

			<div class="flex shrink-0 items-center gap-3 md:gap-4">
				<?php if ( $search_on ) : ?>
				<button
					type="button"
					id="search-modal-open"
					class="pg-search-trigger inline-flex items-center gap-2 text-[15px] font-medium text-gray-900 transition-opacity hover:opacity-70 cursor-pointer"
					aria-expanded="false"
					aria-controls="search-modal"
					aria-label="<?php esc_attr_e( 'Open search', 'kratom-feed' ); ?>"
				>
					<svg class="h-7 w-7" viewBox="0 0 32 32" fill="none" aria-hidden="true">
						<path d="M25.6667 25.6667L20.6667 20.6667M6.33337 14.6667C6.33337 10.0643 10.0643 6.33337 14.6667 6.33337C19.2691 6.33337 23 10.0643 23 14.6667C23 19.2691 19.2691 23 14.6667 23C10.0643 23 6.33337 19.2691 6.33337 14.6667Z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<span class="hidden md:inline"><?php esc_html_e( 'Search', 'kratom-feed' ); ?></span>
				</button>
				<?php endif; ?>

				<button
					id="mobile-menu-btn"
					type="button"
					class="pg-menu-toggle inline-flex shrink-0 items-center justify-center cursor-pointer"
					aria-expanded="false"
					aria-controls="mobile-menu"
					aria-label="<?php esc_attr_e( 'Open menu', 'kratom-feed' ); ?>"
				>
					<span class="pg-menu-toggle__icon" aria-hidden="true">
						<span class="pg-menu-toggle__bar"></span>
						<span class="pg-menu-toggle__bar"></span>
						<span class="pg-menu-toggle__bar"></span>
					</span>
				</button>
			</div>
		</div>
	</div>

	<nav id="mobile-menu" class="pg-h-nav" aria-hidden="true" aria-label="<?php esc_attr_e( 'Site menu', 'kratom-feed' ); ?>">
		<div class="pg-h-nav__inner">
			<ul class="pg-h-nav__menu">
				<?php foreach ( $nav_tree as $item ) : ?>
					<?php
					$has_children = ! empty( $item['children'] );
					$item_class   = $has_children ? 'pg-h-nav__item has-children' : 'pg-h-nav__item';
					?>
					<li class="<?php echo esc_attr( $item_class ); ?>">
						<?php if ( $has_children ) : ?>
							<button type="button" class="pg-h-nav__parent" aria-expanded="false">
								<span><?php echo esc_html( $item['label'] ); ?></span>
							</button>
							<div class="pg-h-nav__submenu-wrap">
								<ul class="pg-h-nav__submenu">
									<?php if ( ! empty( $item['url'] ) ) : ?>
										<li>
											<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( sprintf( __( 'View all %s', 'kratom-feed' ), $item['label'] ) ); ?></a>
										</li>
									<?php endif; ?>
									<?php foreach ( $item['children'] as $child ) : ?>
										<li>
											<a href="<?php echo esc_url( $child['url'] ); ?>"><?php echo esc_html( $child['label'] ); ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php else : ?>
							<a class="pg-h-nav__link" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( $popular_q->have_posts() ) : ?>
			<div class="pg-h-nav__popular">
				<p class="pg-h-nav__section-title"><?php esc_html_e( 'Popular posts', 'kratom-feed' ); ?></p>
				<ul class="pg-h-nav__popular-list">
					<?php
					while ( $popular_q->have_posts() ) :
						$popular_q->the_post();
						?>
						<li>
							<a href="<?php the_permalink(); ?>" class="pg-h-nav__popular-item">
								<?php if ( has_post_thumbnail() ) : ?>
									<span class="pg-h-nav__popular-thumb">
										<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover', 'loading' => 'lazy' ) ); ?>
									</span>
								<?php endif; ?>
								<span class="pg-h-nav__popular-meta">
									<span class="pg-h-nav__popular-name"><?php the_title(); ?></span>
									<span class="pg-h-nav__popular-info"><?php echo esc_html( get_the_date() . ' / ' . kratom_feed_reading_time() ); ?></span>
								</span>
							</a>
						</li>
					<?php endwhile; wp_reset_postdata(); ?>
				</ul>
			</div>
			<?php endif; ?>

			<div class="pg-h-nav__newsletter">
				<p class="pg-h-nav__section-title"><?php echo esc_html( $newsletter_heading ); ?></p>
				<?php if ( $newsletter_desc ) : ?>
					<p class="pg-h-nav__newsletter-desc"><?php echo esc_html( $newsletter_desc ); ?></p>
				<?php endif; ?>
				<div class="pg-h-nav__newsletter-form">
					<?php get_template_part( 'template-parts/components/newsletter', 'form' ); ?>
				</div>
			</div>
		</div>
	</nav>
</header>

<div id="menu-overlay" class="pg-nav-overlay" aria-hidden="true"></div>

<?php if ( $search_on ) : ?>
	<?php get_template_part( 'template-parts/header/search', 'modal' ); ?>
<?php endif; ?>
