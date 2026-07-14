<?php
/**
 * Site header - Kratom.org style
 *
 * Layout: Logo | Search | Nav links | Hamburger (opens right drawer)
 *
 * @package KratomFeeds
 */

$logo_text   = kratom_feed_get_option( 'site_tagline_short', 'Kratom.org' );
$search_on   = kratom_feed_get_option( 'header_search_enabled', true );
$placeholder = kratom_feed_get_option( 'header_search_placeholder', __( 'Type to search help articles', 'kratom-feed' ) );
$sticky      = kratom_feed_get_option( 'header_sticky', true );

$nav_links = array(
	array( 'url' => get_post_type_archive_link( 'post' ), 'label' => __( 'All Articles', 'kratom-feed' ) ),
	array( 'url' => home_url( '/category/guides/' ), 'label' => __( 'Guides', 'kratom-feed' ) ),
	array( 'url' => home_url( '/category/strains/' ), 'label' => __( 'Strains', 'kratom-feed' ) ),
	array( 'url' => home_url( '/category/reviews/' ), 'label' => __( 'Reviews', 'kratom-feed' ) ),
	array( 'url' => home_url( '/category/research/' ), 'label' => __( 'Research', 'kratom-feed' ) ),
	array( 'url' => home_url( '/category/news/' ), 'label' => __( 'News', 'kratom-feed' ) ),
	array( 'url' => get_permalink( get_page_by_path( 'about' ) ) ?: home_url( '/about/' ), 'label' => __( 'About', 'kratom-feed' ) ),
);
?>
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-sm focus:bg-black focus:px-4 focus:py-2 focus:text-white"><?php esc_html_e( 'Skip to content', 'kratom-feed' ); ?></a>

<div id="reading-progress" class="fixed left-0 top-0 z-[70] h-1 w-0 bg-black transition-[width] duration-150" aria-hidden="true"></div>

<header id="site-header" class="<?php echo $sticky ? 'sticky top-0' : ''; ?> z-[990] border-b border-gray-200 bg-white transition-shadow duration-300">
	<div class="pg-container">
		<div class="flex h-16 items-center gap-3 md:h-[4.5rem] md:gap-5 lg:gap-6">
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

			<?php if ( $search_on ) : ?>
			<form role="search" method="get" class="relative min-w-0 flex-1 max-w-xl" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label for="header-search" class="sr-only"><?php esc_html_e( 'Search articles', 'kratom-feed' ); ?></label>
				<span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true">
					<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
				</span>
				<input
					id="header-search"
					type="search"
					name="s"
					value="<?php echo esc_attr( get_search_query() ); ?>"
					placeholder="<?php echo esc_attr( $placeholder ); ?>"
					class="w-full rounded-full border border-gray-200 bg-white py-2.5 pl-11 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-black/5"
				/>
				<input type="hidden" name="post_type" value="post" />
			</form>
			<?php else : ?>
			<div class="min-w-0 flex-1"></div>
			<?php endif; ?>

			<nav class="hidden shrink-0 items-center gap-5 lg:flex xl:gap-6" aria-label="<?php esc_attr_e( 'Main navigation', 'kratom-feed' ); ?>">
				<?php
				$desktop_links = $nav_links;
				if ( has_nav_menu( 'primary' ) ) {
					$locations = get_nav_menu_locations();
					$menu_id   = $locations['primary'] ?? 0;
					$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : false;
					if ( $items ) {
						$desktop_links = array();
						foreach ( $items as $item ) {
							if ( (int) $item->menu_item_parent !== 0 ) {
								continue;
							}
							$desktop_links[] = array(
								'url'   => $item->url,
								'label' => $item->title,
							);
						}
					}
				}
				foreach ( $desktop_links as $link ) {
					if ( empty( $link['url'] ) ) {
						continue;
					}
					printf(
						'<a href="%s" class="whitespace-nowrap text-[15px] font-medium text-gray-900 transition-colors hover:text-black cursor-pointer">%s</a>',
						esc_url( $link['url'] ),
						esc_html( $link['label'] )
					);
				}
				?>
			</nav>

			<button
				id="mobile-menu-btn"
				type="button"
				class="inline-flex h-10 w-12 shrink-0 items-center justify-center rounded-full bg-black text-white transition-opacity hover:opacity-90 cursor-pointer"
				aria-expanded="false"
				aria-controls="mobile-menu"
				aria-label="<?php esc_attr_e( 'Open menu', 'kratom-feed' ); ?>"
			>
				<svg id="menu-icon-open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
					<path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>
				</svg>
				<svg id="menu-icon-close" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
					<path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
				</svg>
			</button>
		</div>
	</div>
</header>

<div id="menu-overlay" class="fixed inset-0 z-[995] bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300" aria-hidden="true"></div>

<aside
	id="mobile-menu"
	class="fixed inset-y-0 right-0 z-[1000] flex w-full max-w-sm translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out"
	aria-hidden="true"
	aria-label="<?php esc_attr_e( 'Site menu', 'kratom-feed' ); ?>"
>
	<div class="flex h-16 items-center justify-between border-b border-gray-200 px-5">
		<span class="text-base font-bold text-black"><?php esc_html_e( 'Menu', 'kratom-feed' ); ?></span>
		<button type="button" id="mobile-menu-close" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-800 transition-colors hover:bg-gray-200 cursor-pointer" aria-label="<?php esc_attr_e( 'Close menu', 'kratom-feed' ); ?>">
			<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
		</button>
	</div>

	<div class="flex-1 overflow-y-auto px-5 py-6">
		<?php if ( $search_on ) : ?>
		<form role="search" method="get" class="relative mb-6 lg:hidden" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="mobile-search" class="sr-only"><?php esc_html_e( 'Search', 'kratom-feed' ); ?></label>
			<span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true">
				<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
			</span>
			<input id="mobile-search" type="search" name="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="w-full rounded-full border border-gray-200 py-2.5 pl-11 pr-4 text-sm focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-black/5" />
			<input type="hidden" name="post_type" value="post" />
		</form>
		<?php endif; ?>

		<nav aria-label="<?php esc_attr_e( 'Mobile navigation', 'kratom-feed' ); ?>">
			<ul class="m-0 list-none space-y-1 p-0">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					$items = wp_get_nav_menu_items( get_nav_menu_locations()['primary'] ?? 0 );
					if ( $items ) {
						foreach ( $items as $item ) {
							if ( (int) $item->menu_item_parent !== 0 ) {
								continue;
							}
							printf(
								'<li><a href="%s" class="block rounded-xl px-3 py-3 text-base font-medium text-gray-900 transition-colors hover:bg-gray-50 cursor-pointer">%s</a></li>',
								esc_url( $item->url ),
								esc_html( $item->title )
							);
						}
					}
				} else {
					foreach ( $nav_links as $link ) {
						if ( empty( $link['url'] ) ) {
							continue;
						}
						printf(
							'<li><a href="%s" class="block rounded-xl px-3 py-3 text-base font-medium text-gray-900 transition-colors hover:bg-gray-50 cursor-pointer">%s</a></li>',
							esc_url( $link['url'] ),
							esc_html( $link['label'] )
						);
					}
				}
				?>
			</ul>
		</nav>
	</div>
</aside>
