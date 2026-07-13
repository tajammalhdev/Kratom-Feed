<?php
/**
 * Builder: Featured Mosaic (Ncmaz home-5 style)
 *
 * Layout: 2 small cards + 1 wide card on the left, 1 tall card on the right.
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$posts          = kratom_feed_resolve_posts(
	$section_data['posts'] ?? array(),
	4,
	$section_data['category'] ?? ''
);
$show_engage    = ! empty( $section_data['show_engagement'] );
$show_play      = ! empty( $section_data['show_play_badge'] );

if ( count( $posts ) < 1 ) {
	return;
}

/**
 * Render one mosaic card.
 *
 * @param WP_Post $post    Post object.
 * @param string  $size    small|wide|tall.
 * @param bool    $play    Show play badge.
 * @param bool    $engage  Show engagement chips (tall only).
 */
$render_card = static function ( $post, $size = 'small', $play = false, $engage = false ) {
	$post_id   = $post->ID;
	$permalink = get_permalink( $post );
	$title     = get_the_title( $post );
	$cat       = kratom_feed_get_post_primary_category( $post_id );
	$badge_cls = kratom_feed_category_badge_class( $cat );
	$thumb     = get_the_post_thumbnail_url( $post, 'large' );

	$title_class = 'tall' === $size
		? 'text-xl font-semibold leading-snug text-white sm:text-2xl'
		: ( 'wide' === $size
			? 'text-lg font-semibold leading-snug text-white sm:text-xl'
			: 'text-base font-semibold leading-snug text-white sm:text-lg' );

	$min_h = 'tall' === $size
		? 'min-h-[420px] h-full lg:min-h-full'
		: ( 'wide' === $size ? 'min-h-[220px] sm:min-h-[240px]' : 'min-h-[220px] sm:min-h-[240px]' );

	$comments = (int) get_comments_number( $post_id );
	$likes    = (int) get_post_meta( $post_id, 'kf_likes', true );
	if ( $likes < 1 ) {
		$likes = max( 100, $comments * 48 + ( $post_id % 900 ) );
	}
	?>
	<article class="pg-mosaic-card group relative overflow-hidden rounded-3xl <?php echo esc_attr( $min_h ); ?>">
		<a href="<?php echo esc_url( $permalink ); ?>" class="absolute inset-0 z-10" aria-label="<?php echo esc_attr( $title ); ?>"></a>

		<?php if ( $thumb ) : ?>
			<img
				src="<?php echo esc_url( $thumb ); ?>"
				alt=""
				class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
				loading="<?php echo 'tall' === $size ? 'eager' : 'lazy'; ?>"
			/>
		<?php else : ?>
			<div class="absolute inset-0 bg-gradient-to-br from-pg-dark to-pg-green-dark"></div>
		<?php endif; ?>

		<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-black/10"></div>

		<?php if ( $play ) : ?>
			<span class="pointer-events-none absolute right-4 top-4 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-gray-900 shadow-sm backdrop-blur-sm" aria-hidden="true">
				<svg class="ml-0.5 h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72L19 12 8 5.14z"/></svg>
			</span>
		<?php endif; ?>

		<?php if ( $engage && 'tall' === $size ) : ?>
			<div class="pointer-events-none absolute left-4 top-4 z-20 flex items-center gap-2">
				<span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-gray-800 shadow-sm backdrop-blur-sm">
					<svg class="h-3.5 w-3.5 text-rose-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
					<?php echo esc_html( kratom_feed_compact_number( $likes ) ); ?>
				</span>
				<span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-gray-800 shadow-sm backdrop-blur-sm">
					<svg class="h-3.5 w-3.5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
					<?php echo esc_html( kratom_feed_compact_number( $comments ) ); ?>
				</span>
			</div>
			<span class="pointer-events-none absolute right-4 top-4 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-gray-800 shadow-sm backdrop-blur-sm" aria-hidden="true">
				<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
			</span>
		<?php endif; ?>

		<div class="absolute inset-x-0 bottom-0 z-20 p-5 sm:p-6">
			<?php if ( $cat ) : ?>
				<span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-semibold <?php echo esc_attr( $badge_cls ); ?>">
					<?php echo esc_html( $cat ); ?>
				</span>
			<?php endif; ?>
			<h3 class="mt-3 <?php echo esc_attr( $title_class ); ?>">
				<?php echo esc_html( $title ); ?>
			</h3>
		</div>
	</article>
	<?php
};

$card_0 = $posts[0] ?? null;
$card_1 = $posts[1] ?? null;
$card_2 = $posts[2] ?? null;
$card_3 = $posts[3] ?? null;
?>
<section class="bg-white py-8 md:py-10 lg:py-12" aria-label="<?php esc_attr_e( 'Featured mosaic', 'kratom-feed' ); ?>">
	<div class="pg-container">
		<div class="grid gap-4 lg:grid-cols-2 lg:gap-5 lg:items-stretch">
			<div class="grid gap-4 sm:grid-cols-2 lg:gap-5">
				<?php
				if ( $card_0 ) {
					$render_card( $card_0, 'small', $show_play, false );
				}
				if ( $card_1 ) {
					$render_card( $card_1, 'small', false, false );
				}
				if ( $card_2 ) {
					echo '<div class="sm:col-span-2">';
					$render_card( $card_2, 'wide', $show_play, false );
					echo '</div>';
				}
				?>
			</div>

			<div class="lg:min-h-full">
				<?php
				if ( $card_3 ) {
					$render_card( $card_3, 'tall', false, $show_engage );
				} elseif ( $card_0 ) {
					// Fallback: reuse first if fewer than 4 posts.
					$render_card( $card_0, 'tall', false, $show_engage );
				}
				?>
			</div>
		</div>
	</div>
</section>
