<?php
/**
 * Site footer
 *
 * @package KratomFeeds
 */

$tagline     = kratom_feed_get_option( 'footer_tagline' );
$copyright   = kratom_feed_get_option( 'copyright_text', '(c) ' . gmdate( 'Y' ) . ' ' . get_bloginfo( 'name' ) );
$disclaimer  = kratom_feed_get_option( 'footer_disclaimer' );
$show_chat   = kratom_feed_get_option( 'floating_chat_enabled', true );
$about_url   = get_permalink( get_page_by_path( 'about' ) ) ?: home_url( '/about/' );
?>
<footer class="border-t border-pg-border bg-pg-black text-white" role="contentinfo">
	<div class="pg-container py-14">
		<div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
			<div>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2">
					<span class="flex h-9 w-9 items-center justify-center rounded-full bg-pg-green-dark">
						<svg class="h-5 w-5 text-pg-lime" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C8 6 6 9 6 13c0 4 2.5 7 6 9 3.5-2 6-5 6-9 0-4-2-7-6-11z"/></svg>
					</span>
					<span class="text-lg font-extrabold uppercase tracking-wider text-white"><?php bloginfo( 'name' ); ?></span>
				</a>
				<?php if ( $tagline ) : ?>
					<p class="mt-4 text-sm leading-relaxed text-gray-400"><?php echo esc_html( $tagline ); ?></p>
				<?php endif; ?>
			</div>
			<div>
				<h3 class="text-xs font-bold uppercase tracking-[0.15em] text-pg-lime"><?php esc_html_e( 'Explore', 'kratom-feed' ); ?></h3>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'mt-4 space-y-2.5 list-none m-0 p-0',
					'fallback_cb'    => function () {
						$cats = array( 'guides', 'strains', 'reviews', 'research', 'news' );
						echo '<ul class="mt-4 space-y-2.5 list-none m-0 p-0">';
						foreach ( $cats as $slug ) {
							$term = get_category_by_slug( $slug );
							if ( $term ) {
								printf(
									'<li><a href="%s" class="text-sm text-gray-400 hover:text-pg-lime transition-colors">%s</a></li>',
									esc_url( get_category_link( $term ) ),
									esc_html( $term->name )
								);
							}
						}
						echo '</ul>';
					},
				) );
				?>
			</div>
			<div>
				<h3 class="text-xs font-bold uppercase tracking-[0.15em] text-pg-lime"><?php esc_html_e( 'Newsletter', 'kratom-feed' ); ?></h3>
				<p class="mt-4 text-sm text-gray-400"><?php esc_html_e( 'Weekly Kratom education in your inbox.', 'kratom-feed' ); ?></p>
				<div class="mt-4"><?php get_template_part( 'template-parts/components/newsletter', 'form' ); ?></div>
			</div>
			<div>
				<h3 class="text-xs font-bold uppercase tracking-[0.15em] text-pg-lime"><?php esc_html_e( 'Legal', 'kratom-feed' ); ?></h3>
				<ul class="mt-4 space-y-2.5">
					<li><a href="<?php echo esc_url( $about_url ); ?>" class="text-sm text-gray-400 hover:text-pg-lime transition-colors"><?php esc_html_e( 'Editorial Policy', 'kratom-feed' ); ?></a></li>
				</ul>
			</div>
		</div>
		<div class="mt-12 border-t border-white/10 pt-8">
			<?php if ( $disclaimer ) : ?>
				<div class="text-xs leading-relaxed text-gray-500"><?php echo wp_kses_post( $disclaimer ); ?></div>
			<?php endif; ?>
			<div class="mt-4 flex flex-col gap-2 text-xs text-gray-500 sm:flex-row sm:justify-between">
				<p><?php echo esc_html( $copyright ); ?></p>
			</div>
		</div>
	</div>
</footer>

<?php if ( $show_chat ) : ?>
<button type="button" class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-pg-lime shadow-lg transition-transform hover:scale-105 cursor-pointer" aria-label="<?php esc_attr_e( 'Contact us', 'kratom-feed' ); ?>">
	<svg class="h-6 w-6 text-pg-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
</button>
<?php endif; ?>

