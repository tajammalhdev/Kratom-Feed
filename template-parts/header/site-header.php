<?php
/**
 * Site header - Kratom.org style
 *
 * Layout: Logo | Search | Desktop links | Hamburger
 * Toggle opens a black right panel with accordion submenus (kratom.org pattern).
 *
 * @package KratomFeeds
 */

$logo_text   = kratom_feed_get_option( 'site_tagline_short', 'Kratom.org' );
$search_on   = kratom_feed_get_option( 'header_search_enabled', true );
$placeholder = kratom_feed_get_option( 'header_search_placeholder', __( 'Type to search help articles', 'kratom-feed' ) );
$sticky      = kratom_feed_get_option( 'header_sticky', true );
$nav_tree    = kratom_feed_get_nav_tree();
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
				<?php foreach ( $nav_tree as $link ) : ?>
					<?php if ( empty( $link['url'] ) && empty( $link['children'] ) ) { continue; } ?>
					<a href="<?php echo esc_url( $link['url'] ?: '#' ); ?>" class="whitespace-nowrap text-[15px] font-medium text-gray-900 transition-colors hover:text-black cursor-pointer"><?php echo esc_html( $link['label'] ); ?></a>
				<?php endforeach; ?>
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
		</div>
	</nav>
</header>

<div id="menu-overlay" class="pg-nav-overlay" aria-hidden="true"></div>
