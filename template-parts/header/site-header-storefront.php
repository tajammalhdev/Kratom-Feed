<?php
/**
 * Site header — Storefront 
 *
 * Two-row sticky bar, desktop search panel, secondary nav megamenus,
 * mobile drawer + search overlay. Blog/content adapted (no cart).
 *
 * @package KratomFeeds
 */

$logo_text       = kratom_feed_get_option( 'site_tagline_short', 'Kratom.org' );
$search_on       = kratom_feed_get_option( 'header_search_enabled', true );
$sticky          = kratom_feed_get_option( 'header_sticky', true );
$placeholder     = kratom_feed_get_option( 'header_search_placeholder', __( 'What Are You Looking For?', 'kratom-feed' ) );
$pro_tip         = kratom_feed_get_option( 'header_search_pro_tip', __( 'Try searching by strain, topic, or vendor name.', 'kratom-feed' ) );
$nav_tree        = kratom_feed_get_nav_tree();
$trending_raw    = kratom_feed_get_option( 'header_trending_searches', array() );
$cta_1_label     = kratom_feed_get_option( 'header_cta_1_label', __( 'Blog', 'kratom-feed' ) );
$cta_1_url       = kratom_feed_get_option( 'header_cta_1_url', '/blog/' );
$cta_1_icon      = kratom_feed_get_cta_icon_html(
	kratom_feed_get_option( 'header_cta_1_icon', 0 ),
	kratom_feed_get_option( 'header_cta_1_svg', '' )
);
$cta_2_label     = kratom_feed_get_option( 'header_cta_2_label', __( 'Subscribe', 'kratom-feed' ) );
$cta_2_url       = kratom_feed_get_option( 'header_cta_2_url', '#newsletter' );
$cta_2_icon      = kratom_feed_get_cta_icon_html(
	kratom_feed_get_option( 'header_cta_2_icon', 0 ),
	kratom_feed_get_option( 'header_cta_2_svg', '' )
);
$newsletter_heading = kratom_feed_get_option( 'newsletter_heading', __( 'Subscribe our newsletter', 'kratom-feed' ) );
$newsletter_desc    = kratom_feed_get_option( 'newsletter_description', __( "You'll only receive updates on new articles, no spam.", 'kratom-feed' ) );

$kf_resolve_url = static function ( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	if ( '#' === $url[0] || preg_match( '#^https?://#i', $url ) ) {
		return $url;
	}
	return home_url( $url );
};

$cta_1_href = $kf_resolve_url( $cta_1_url );
$cta_2_href = $kf_resolve_url( $cta_2_url );

$trending = array();
if ( is_array( $trending_raw ) ) {
	foreach ( $trending_raw as $row ) {
		$term = isset( $row['term'] ) ? trim( (string) $row['term'] ) : '';
		if ( '' !== $term ) {
			$trending[] = $term;
		}
	}
}
if ( empty( $trending ) ) {
	$trending = array(
		__( 'Kratom dosage', 'kratom-feed' ),
		__( 'Red vein', 'kratom-feed' ),
		__( 'Vendor reviews', 'kratom-feed' ),
		__( 'What is kratom', 'kratom-feed' ),
	);
}

$latest_q = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
	)
);

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

$quick_links = array_slice( $nav_tree, 0, 3 );
?>
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-sm focus:bg-black focus:px-4 focus:py-2 focus:text-white"><?php esc_html_e( 'Skip to content', 'kratom-feed' ); ?></a>

<div id="reading-progress" class="fixed left-0 top-0 z-[70] h-1 w-0 bg-black transition-[width] duration-150" aria-hidden="true"></div>

<header id="site-header" class="kf-header-storefront <?php echo $sticky ? 'sticky top-0 left-0' : 'relative'; ?> z-[98] border-b border-neutral-200 bg-white">
	<div class="kf-sf-top lg:border-b lg:border-neutral-200 lg:py-5">
		<div class="container">
			<div class="flex items-center justify-between gap-4">
				<button
					type="button"
					class="kf-sf-menu-open flex h-14 w-14 shrink-0 items-center justify-center border-r border-neutral-200 lg:hidden"
					aria-expanded="false"
					aria-controls="kf-sf-drawer"
					aria-label="<?php esc_attr_e( 'Open menu', 'kratom-feed' ); ?>"
				>
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M17.5 10C17.5 10.1658 17.4342 10.3247 17.3169 10.4419C17.1997 10.5592 17.0408 10.625 16.875 10.625H3.125C2.95924 10.625 2.80027 10.5592 2.68306 10.4419C2.56585 10.3247 2.5 10.1658 2.5 10C2.5 9.83424 2.56585 9.67527 2.68306 9.55806C2.80027 9.44085 2.95924 9.375 3.125 9.375H16.875C17.0408 9.375 17.1997 9.44085 17.3169 9.55806C17.4342 9.67527 17.5 9.83424 17.5 10Z" fill="currentColor"/>
						<path d="M17.5 5C17.5 5.16576 17.4342 5.32473 17.3169 5.44194C17.1997 5.55915 17.0408 5.625 16.875 5.625H3.125C2.95924 5.625 2.80027 5.55915 2.68306 5.44194C2.56585 5.32473 2.5 5.16576 2.5 5C2.5 4.83424 2.56585 4.67527 2.68306 4.55806C2.80027 4.44085 2.95924 4.375 3.125 4.375H16.875C17.0408 4.375 17.1997 4.44085 17.3169 4.55806C17.4342 4.67527 17.5 4.83424 17.5 5Z" fill="currentColor"/>
						<path d="M17.5 15C17.5 15.1658 17.4342 15.3247 17.3169 15.4419C17.1997 15.5592 17.0408 15.625 16.875 15.625H3.125C2.95924 15.625 2.80027 15.5592 2.68306 15.4419C2.56585 15.3247 2.5 15.1658 2.5 15C2.5 14.8342 2.56585 14.6753 2.68306 14.5581C2.80027 14.4408 2.95924 14.375 3.125 14.375H16.875C17.0408 14.375 17.1997 14.4408 17.3169 14.5581C17.4342 14.6753 17.5 14.8342 17.5 15Z" fill="currentColor"/>
					</svg>
				</button>

				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="kf-sf-logo inline-flex max-w-[200px] items-center" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php
					$logo_html = kratom_feed_get_custom_logo_html( 'custom-logo h-9 w-auto max-h-9' );
					if ( $logo_html ) :
						echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					else :
						?>
						<span class="flex h-8 w-8 shrink-0 items-center justify-center text-black" aria-hidden="true">
							<svg class="h-7 w-7" viewBox="0 0 32 32" fill="currentColor">
								<path d="M4 20c4-5 8-7.5 12-7.5S24 15 28 20c-4 1.5-8 2.2-12 2.2S8 21.5 4 20z" opacity="0.35"/>
								<path d="M5 16c3.5-4.2 7-6.2 11-6.2s7.5 2 11 6.2c-3.5 1.4-7 2-11 2s-7.5-.6-11-2z" opacity="0.65"/>
								<path d="M6.5 12c3-3.5 6-5.2 9.5-5.2S21.5 8.5 24.5 12c-3 1.2-6 1.8-9.5 1.8S9.5 13.2 6.5 12z"/>
							</svg>
						</span>
						<span class="truncate text-[17px] font-bold tracking-tight text-black md:text-lg"><?php echo esc_html( $logo_text ); ?></span>
					<?php endif; ?>
				</a>

				<?php if ( $search_on ) : ?>
				<div class="kf-sf-search relative hidden min-w-0  flex-1	lg:flex justify-center">
					<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="kf-sf-search__form relative   w-64 xl:w-96">
						<label class="sr-only" for="kf-sf-search-input"><?php esc_html_e( 'Search', 'kratom-feed' ); ?></label>
						<input
							type="search"
							id="kf-sf-search-input"
							name="s"
							class="kf-sf-search__input rounded-full w-full border border-neutral-200 bg-neutral-50 py-3 pl-5 pr-4 text-sm text-gray-900 placeholder:text-neutral-400 focus:border-[#42B251] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#42B251]/20"
							placeholder="<?php echo esc_attr( $placeholder ); ?>"
							autocomplete="off"
							aria-expanded="false"
							aria-controls="kf-sf-search-panel"
						/>
						<button type="submit" class="absolute top-1/2 -translate-y-1/2 right-0 w-12 h-12 flex items-center justify-center" aria-label="Search">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill="#A3A3A3" fill-rule="evenodd" d="M3.25 11.528a7.78 7.78 0 0 1 7.778-7.778.972.972 0 0 1 0 1.944 5.833 5.833 0 1 0 5.833 5.834.972.972 0 0 1 1.944 0c0 1.797-.61 3.452-1.633 4.77l3.293 3.292a.972.972 0 0 1-1.375 1.375l-3.293-3.293A7.778 7.778 0 0 1 3.25 11.528" clip-rule="evenodd"></path>
								<path fill="#A3A3A3" d="M15.5 2.75a.75.75 0 0 1 .703.49l.258.697c.362.978.478 1.244.668 1.434s.456.306 1.434.668l.697.258a.75.75 0 0 1 0 1.406l-.697.258c-.978.362-1.244.478-1.434.668s-.306.456-.668 1.434l-.258.697a.75.75 0 0 1-1.406 0l-.258-.697c-.362-.978-.478-1.244-.668-1.434s-.456-.306-1.434-.668l-.697-.258a.75.75 0 0 1 0-1.406l.697-.258c.978-.362 1.244-.478 1.434-.668s.306-.456.668-1.434l.258-.697a.75.75 0 0 1 .703-.49"></path>
							</svg>
						</button>
					</form>
					<div id="kf-sf-search-panel" class="kf-sf-search__panel w-64 xl:w-96" aria-hidden="true">
						<?php if ( $pro_tip ) : ?>
						<p class="kf-sf-search__tip">
							<span class="kf-sf-search__tip-label"><?php esc_html_e( 'Pro tip', 'kratom-feed' ); ?></span>
							<?php echo esc_html( $pro_tip ); ?>
						</p>
						<?php endif; ?>
						<?php if ( ! empty( $trending ) ) : ?>
						<div class="kf-sf-search__trending">
							<p class="kf-sf-search__trending-label"><?php esc_html_e( 'Trending', 'kratom-feed' ); ?></p>
							<ul class="kf-sf-search__trending-list">
								<?php foreach ( $trending as $term ) : ?>
								<li>
									<a href="<?php echo esc_url( home_url( '/?s=' . rawurlencode( $term ) ) ); ?>"><?php echo esc_html( $term ); ?></a>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>

				<div class="hidden items-center gap-2 lg:flex">
					<?php if ( $cta_1_label && $cta_1_href ) : ?>
					<a href="<?php echo esc_url( $cta_1_href ); ?>" class="flex items-center font-medium justify-center gap-2 px-4 py-2 rounded-full bg-neutral-100 text-sm transition-colors duration-300 hover:bg-neutral-900 hover:text-white">
						
						<?php echo esc_html( $cta_1_label ); ?>
						<?php
						if ( $cta_1_icon ) {
							echo $cta_1_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized via kratom_feed_get_cta_icon_html()
						}
						?>
					</a>
					<?php endif; ?>
					<?php if ( $cta_2_label && $cta_2_href ) : ?>
					<a href="<?php echo esc_url( $cta_2_href ); ?>" class="flex items-center font-medium justify-center gap-2 px-4 py-2 rounded-full bg-neutral-100 text-sm transition-colors duration-300 hover:bg-neutral-900 hover:text-white">
						
						<?php echo esc_html( $cta_2_label ); ?>
						<?php
						if ( $cta_2_icon ) {
							echo $cta_2_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized via kratom_feed_get_cta_icon_html()
						}
						?>
					</a>
					<?php endif; ?>
				</div>

				<?php if ( $search_on ) : ?>
				<button
					type="button"
					class="kf-sf-search-open flex h-14 w-14 shrink-0 items-center justify-center border-l border-neutral-200 lg:hidden"
					aria-expanded="false"
					aria-controls="kf-sf-search-mobile"
					aria-label="<?php esc_attr_e( 'Open search', 'kratom-feed' ); ?>"
				>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5" stroke-linecap="round"/></svg>
				</button>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if ( ! empty( $quick_links ) ) : ?>
	<div class="kf-sf-quick border-t border-neutral-100 lg:hidden">
		<div class="flex overflow-x-auto">
			<?php foreach ( $quick_links as $ql ) : ?>
				<?php if ( empty( $ql['url'] ) ) { continue; } ?>
				<a href="<?php echo esc_url( $ql['url'] ); ?>" class="kf-sf-quick__link"><?php echo esc_html( $ql['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

	<nav class="kf-sf-nav hidden lg:block" aria-label="<?php esc_attr_e( 'Main navigation', 'kratom-feed' ); ?>">
		<div class="container">
			<ul class="flex-wrap items-stretch gap-4 m-0 p-0 flex justify-center">
				<?php foreach ( $nav_tree as $index => $link ) : ?>
					<?php
					if ( empty( $link['url'] ) && empty( $link['children'] ) ) {
						continue;
					}
					$has_children = ! empty( $link['children'] );
					$mega_id      = 'kf-sf-mega-' . $index;
					?>
					<li class="kf-sf-nav__item<?php echo $has_children ? ' has-mega' : ''; ?>">
						<?php if ( $has_children ) : ?>
						<button
							type="button"
							class="kf-sf-nav__trigger"
							aria-expanded="false"
							aria-controls="<?php echo esc_attr( $mega_id ); ?>"
						>
							<?php echo esc_html( $link['label'] ); ?>
							<svg class="kf-sf-nav__chevron" width="12" height="12" viewBox="0 0 12 12" aria-hidden="true"><path d="M2 4l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
						<?php
						get_template_part(
							'template-parts/header/storefront',
							'megamenu',
							array(
								'mega_id'   => $mega_id,
								'link'      => $link,
								'latest_q'  => $latest_q,
								'popular_q' => $popular_q,
							)
						);
						?>
						<?php else : ?>
						<a href="<?php echo esc_url( $link['url'] ?: '#' ); ?>" class="kf-sf-nav__link"><?php echo esc_html( $link['label'] ); ?></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</nav>
</header>

<?php /* Mobile drawer */ ?>
<div id="kf-sf-overlay" class="kf-sf-overlay" aria-hidden="true"></div>
<div id="kf-sf-drawer" class="kf-sf-drawer" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Explore', 'kratom-feed' ); ?>">
	<div class="kf-sf-drawer__head">
		<p class="kf-sf-drawer__title"><?php esc_html_e( 'Explore', 'kratom-feed' ); ?></p>
		<button type="button" class="kf-sf-drawer__close" aria-label="<?php esc_attr_e( 'Close menu', 'kratom-feed' ); ?>">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
		</button>
	</div>
	<div class="kf-sf-drawer__body">
		<ul class="pg-h-nav__menu kf-sf-drawer__menu">
			<?php foreach ( $nav_tree as $link ) : ?>
				<?php
				if ( empty( $link['url'] ) && empty( $link['children'] ) ) {
					continue;
				}
				$kids = $link['children'] ?? array();
				?>
				<li class="pg-h-nav__item">
					<?php if ( ! empty( $kids ) ) : ?>
					<button type="button" class="pg-h-nav__parent" aria-expanded="false"><?php echo esc_html( $link['label'] ); ?></button>
					<div class="pg-h-nav__submenu-wrap">
						<ul class="pg-h-nav__submenu">
							<?php if ( ! empty( $link['url'] ) ) : ?>
							<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php esc_html_e( 'View all', 'kratom-feed' ); ?></a></li>
							<?php endif; ?>
							<?php foreach ( $kids as $child ) : ?>
							<li><a href="<?php echo esc_url( $child['url'] ); ?>"><?php echo esc_html( $child['label'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php else : ?>
					<a href="<?php echo esc_url( $link['url'] ?: '#' ); ?>" class="pg-h-nav__link"><?php echo esc_html( $link['label'] ); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( $popular_q->have_posts() ) : ?>
		<div class="pg-h-nav__popular">
			<p class="pg-h-nav__section-title"><?php esc_html_e( 'Popular', 'kratom-feed' ); ?></p>
			<ul class="pg-h-nav__popular-list">
				<?php
				while ( $popular_q->have_posts() ) :
					$popular_q->the_post();
					?>
					<li>
						<a href="<?php the_permalink(); ?>" class="pg-h-nav__popular-item">
							<span class="pg-h-nav__popular-thumb">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover' ) );
								}
								?>
							</span>
							<span class="pg-h-nav__popular-meta">
								<span class="pg-h-nav__popular-name"><?php the_title(); ?></span>
								<span class="pg-h-nav__popular-info"><?php echo esc_html( get_the_date() ); ?></span>
							</span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
		<?php
		$popular_q->rewind_posts();
		wp_reset_postdata();
		endif;
		?>

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
</div>

<?php if ( $search_on ) : ?>
<div id="kf-sf-search-mobile" class="kf-sf-search-mobile" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search', 'kratom-feed' ); ?>">
	<div class="kf-sf-search-mobile__bar">
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="kf-sf-search-mobile__form">
			<label class="sr-only" for="kf-sf-search-mobile-input"><?php esc_html_e( 'Search', 'kratom-feed' ); ?></label>
			<input
				type="search"
				id="kf-sf-search-mobile-input"
				name="s"
				class="kf-sf-search-mobile__input"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				autocomplete="off"
			/>
		</form>
		<button type="button" class="kf-sf-search-mobile__close" aria-label="<?php esc_attr_e( 'Close search', 'kratom-feed' ); ?>">
			<?php esc_html_e( 'Cancel', 'kratom-feed' ); ?>
		</button>
	</div>
	<div class="kf-sf-search-mobile__body">
		<?php if ( $pro_tip ) : ?>
		<p class="kf-sf-search__tip">
			<span class="kf-sf-search__tip-label"><?php esc_html_e( 'Pro tip', 'kratom-feed' ); ?></span>
			<?php echo esc_html( $pro_tip ); ?>
		</p>
		<?php endif; ?>
		<?php if ( ! empty( $trending ) ) : ?>
		<div class="kf-sf-search__trending">
			<p class="kf-sf-search__trending-label"><?php esc_html_e( 'Trending', 'kratom-feed' ); ?></p>
			<ul class="kf-sf-search__trending-list">
				<?php foreach ( $trending as $term ) : ?>
				<li>
					<a href="<?php echo esc_url( home_url( '/?s=' . rawurlencode( $term ) ) ); ?>"><?php echo esc_html( $term ); ?></a>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>
