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

$drawer_links = $nav_links;
if ( has_nav_menu( 'primary' ) ) {
	$locations = get_nav_menu_locations();
	$menu_id   = $locations['primary'] ?? 0;
	$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : false;
	if ( $items ) {
		$drawer_links = array();
		foreach ( $items as $item ) {
			if ( (int) $item->menu_item_parent !== 0 ) {
				continue;
			}
			$drawer_links[] = array(
				'url'   => $item->url,
				'label' => $item->title,
			);
		}
	}
}
?>
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-sm focus:bg-black focus:px-4 focus:py-2 focus:text-white"><?php esc_html_e( 'Skip to content', 'kratom-feed' ); ?></a>

<div id="reading-progress" class="fixed left-0 top-0 z-[70] h-1 w-0 bg-black transition-[width] duration-150" aria-hidden="true"></div>

<header id="site-header" class="<?php echo $sticky ? 'sticky top-0' : ''; ?> z-[990] border-b border-gray-200 bg-white/95 backdrop-blur-md transition-shadow duration-300">
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
				foreach ( $drawer_links as $link ) {
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
				class="pg-menu-toggle inline-flex h-10 w-12 shrink-0 items-center justify-center rounded-full bg-black text-white cursor-pointer"
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
</header>

<div id="menu-overlay" class="pg-drawer-overlay" aria-hidden="true"></div>

<aside
	id="mobile-menu"
	class="pg-drawer"
	aria-hidden="true"
	aria-label="<?php esc_attr_e( 'Site menu', 'kratom-feed' ); ?>"
>
	<div class="pg-drawer__accent" aria-hidden="true"></div>

	<div class="pg-drawer__header">
		<div class="pg-drawer__brand">
			<span class="pg-drawer__eyebrow"><?php esc_html_e( 'Explore', 'kratom-feed' ); ?></span>
			<span class="pg-drawer__title"><?php echo esc_html( $logo_text ); ?></span>
		</div>
		<button type="button" id="mobile-menu-close" class="pg-drawer__close" aria-label="<?php esc_attr_e( 'Close menu', 'kratom-feed' ); ?>">
			<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
		</button>
	</div>

	<div class="pg-drawer__body">
		<?php if ( $search_on ) : ?>
		<form role="search" method="get" class="pg-drawer__search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="mobile-search" class="sr-only"><?php esc_html_e( 'Search', 'kratom-feed' ); ?></label>
			<span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true">
				<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
			</span>
			<input id="mobile-search" type="search" name="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="w-full rounded-2xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm transition focus:border-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-black/5" />
			<input type="hidden" name="post_type" value="post" />
		</form>
		<?php endif; ?>

		<nav class="pg-drawer__nav" aria-label="<?php esc_attr_e( 'Mobile navigation', 'kratom-feed' ); ?>">
			<ul class="m-0 list-none space-y-1.5 p-0">
				<?php
				$i = 0;
				foreach ( $drawer_links as $link ) {
					if ( empty( $link['url'] ) ) {
						continue;
					}
					$i++;
					printf(
						'<li class="pg-drawer__item" style="--i:%d"><a href="%s" class="pg-drawer__link"><span>%s</span><svg class="pg-drawer__arrow" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg></a></li>',
						(int) $i,
						esc_url( $link['url'] ),
						esc_html( $link['label'] )
					);
				}
				?>
			</ul>
		</nav>
	</div>

	<div class="pg-drawer__footer">
		<p class="text-xs text-gray-400"><?php esc_html_e( 'Press Esc to close', 'kratom-feed' ); ?></p>
	</div>
</aside>
