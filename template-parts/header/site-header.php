<?php
/**
 * Site header
 *
 * @package KratomFeeds
 */

$logo_text     = kratom_feed_get_option( 'site_tagline_short', 'Kratom Feed' );
$search_on     = kratom_feed_get_option( 'header_search_enabled', true );
$placeholder   = kratom_feed_get_option( 'header_search_placeholder', __( 'Find: best guides for beginners', 'kratom-feed' ) );
$feed_label    = kratom_feed_get_option( 'header_feed_label', 'Kratom Feed' );
$feed_url      = kratom_feed_get_option( 'header_feed_url', get_post_type_archive_link( 'post' ) );
$subscribe_lbl = kratom_feed_get_option( 'header_subscribe_label', 'Subscribe' );
$about_label   = kratom_feed_get_option( 'header_about_label', 'About' );
$about_url     = get_permalink( get_page_by_path( 'about' ) ) ?: home_url( '/about/' );
$sticky        = kratom_feed_get_option( 'header_sticky', true );
?>
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-sm focus:bg-green-500 focus:px-4 focus:py-2 focus:text-white"><?php esc_html_e( 'Skip to content', 'kratom-feed' ); ?></a>

<div id="reading-progress" class="fixed left-0 top-0 z-[70] h-1 w-0 bg-green-500 transition-[width] duration-150" aria-hidden="true"></div>

<header id="site-header" class="<?php echo $sticky ? 'sticky top-0' : ''; ?> z-[990] border-b border-neutral-200 bg-white transition-shadow duration-300">
	<div class="border-b border-neutral-200 lg:py-4">
		<div class="pg-container">
			<div class="grid grid-cols-[auto_1fr_auto] items-center gap-3 lg:gap-6">
				<button id="mobile-menu-btn" type="button" class="flex h-14 w-14 items-center justify-center border-r border-neutral-200 lg:hidden cursor-pointer" aria-expanded="false" aria-controls="mobile-menu" aria-label="<?php esc_attr_e( 'Open menu', 'kratom-feed' ); ?>">
					<svg id="menu-icon-open" class="h-5 w-5 text-pg-green" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M17.5 10c0 .166-.066.325-.183.442a.625.625 0 01-.442.183H3.125a.625.625 0 01-.625-.625c0-.166.066-.325.183-.442A.625.625 0 013.125 9.375h13.75c.166 0 .325.066.442.183.117.117.183.276.183.442zm-13.75-4.375h13.75a.625.625 0 00.625-.625.625.625 0 00-.183-.442.625.625 0 00-.442-.183H3.125a.625.625 0 00-.442.183.625.625 0 00-.183.442c0 .166.066.325.183.442a.625.625 0 00.442.183zm13.75 8.75H3.125a.625.625 0 00-.625.625c0 .166.066.325.183.442a.625.625 0 00.442.183h13.75a.625.625 0 00.625-.625.625.625 0 00-.183-.442.625.625 0 00-.442-.183z"/></svg>
					<svg id="menu-icon-close" class="hidden h-5 w-5 text-pg-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
				</button>

				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex shrink-0 items-center gap-2.5 py-3 lg:py-0" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<span class="flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-full bg-neutral-100">
							<svg class="h-8 w-8" viewBox="0 0 32 32" fill="none" aria-hidden="true">
								<circle cx="16" cy="16" r="16" fill="#e8f0ea"/>
								<path d="M6 22c3-4 7-6 10-6s7 2 10 6" stroke="#2c7736" stroke-width="1.2" fill="none"/>
								<path d="M8 18c2-3 5-5 8-5s6 2 8 5" fill="#42b251" opacity="0.5"/>
								<path d="M10 14c1.5-2 3.5-3 6-3s4.5 1 6 3" fill="#42b251"/>
								<circle cx="22" cy="10" r="3" fill="#b8f04a" opacity="0.8"/>
							</svg>
						</span>
						<span class="text-[15px] font-bold uppercase tracking-[0.14em] text-[#1b3b36]"><?php echo esc_html( $logo_text ); ?></span>
					<?php endif; ?>
				</a>

				<?php if ( $search_on ) : ?>
				<form role="search" method="get" class="relative hidden w-full max-w-xl justify-self-center lg:block" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label for="header-search" class="sr-only"><?php esc_html_e( 'Search articles', 'kratom-feed' ); ?></label>
					<input id="header-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="w-full rounded-md border border-neutral-200 bg-white py-2.5 pl-4 pr-11 text-sm text-gray-700 placeholder:text-neutral-400 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500" />
					<input type="hidden" name="post_type" value="post" />
					<button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-pg-green cursor-pointer" aria-label="<?php esc_attr_e( 'Search', 'kratom-feed' ); ?>">
						<svg class="h-4 w-4" fill="none" viewBox="0 0 20 20" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.942 17.058l-3.912-3.911A6.5 6.5 0 1015.608 8.27a6.5 6.5 0 00-1.578 4.777z"/></svg>
					</button>
				</form>
				<?php endif; ?>

				<div class="flex items-center justify-end gap-2">
					<?php if ( $feed_url ) : ?>
					<a href="<?php echo esc_url( $feed_url ); ?>" class="hidden items-center gap-2 rounded-full bg-neutral-100 px-4 py-2 text-sm font-medium text-gray-800 transition-colors hover:bg-neutral-200 lg:inline-flex cursor-pointer">
						<?php echo esc_html( $feed_label ); ?>
						<svg class="h-4 w-4 text-pg-green" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
					</a>
					<?php endif; ?>
					<a href="#newsletter" class="hidden items-center gap-2 rounded-full bg-neutral-100 px-4 py-2 text-sm font-medium text-gray-800 transition-colors hover:bg-neutral-200 lg:inline-flex cursor-pointer">
						<?php echo esc_html( $subscribe_lbl ); ?>
						<svg class="h-4 w-4 text-pg-green" fill="currentColor" viewBox="0 0 256 256" aria-hidden="true"><path d="M230.92 212c-15.23-26.33-38.7-45.21-66.09-54.16a72 72 0 10-73.66 0C63.78 166.78 40.31 185.66 25.08 212a8 8 0 1013.85 8c18.84-32.56 52.14-52 89.07-52s70.23 19.44 89.07 52a8 8 0 1013.85-8zM72 96a56 56 0 1156 56 56.06 56.06 0 01-56-56z"/></svg>
					</a>
					<a href="<?php echo esc_url( $about_url ); ?>" class="inline-flex items-center gap-2 rounded-full bg-neutral-100 px-4 py-2 text-sm font-medium text-gray-800 transition-colors hover:bg-neutral-200 cursor-pointer">
						<span class="hidden sm:inline"><?php echo esc_html( $about_label ); ?></span>
						<svg class="h-4 w-4 text-pg-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5M16.5 6V4.875c0-.621-.504-1.125-1.125-1.125H4.875c-.621 0-1.125.504-1.125 1.125V17.25c0 .621.504 1.125 1.125 1.125h11.25c.621 0 1.125-.504 1.125-1.125V6.75"/></svg>
					</a>
				</div>
			</div>
		</div>
	</div>

	<?php kratom_feed_primary_nav(); ?>

	<div id="mobile-menu" class="hidden border-t border-neutral-200 bg-white lg:hidden" aria-hidden="true">
		<div class="pg-container space-y-1 py-4">
			<?php if ( $search_on ) : ?>
			<form role="search" method="get" class="mb-3" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label for="mobile-search" class="sr-only"><?php esc_html_e( 'Search', 'kratom-feed' ); ?></label>
				<input id="mobile-search" type="search" name="s" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="w-full rounded-md border border-neutral-200 px-4 py-2.5 text-sm" />
				<input type="hidden" name="post_type" value="post" />
			</form>
			<?php endif; ?>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'space-y-1 list-none m-0 p-0',
				'fallback_cb'    => function () {
					$links = array(
						__( 'All Articles', 'kratom-feed' ) => get_post_type_archive_link( 'post' ),
						__( 'Guides', 'kratom-feed' )       => home_url( '/category/guides/' ),
						__( 'About', 'kratom-feed' )        => get_permalink( get_page_by_path( 'about' ) ) ?: home_url( '/about/' ),
					);
					foreach ( $links as $label => $url ) {
						if ( $url ) {
							printf( '<a href="%s" class="block px-3 py-2.5 text-sm font-medium text-neutral-800">%s</a>', esc_url( $url ), esc_html( $label ) );
						}
					}
				},
			) );
			?>
		</div>
	</div>
</header>

