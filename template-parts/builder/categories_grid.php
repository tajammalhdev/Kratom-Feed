<?php
/**
 * Builder: Categories Grid (Ncmaz "Top trending topics" style)
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$title            = $section_data['title'] ?? 'Top trending topics';
$subtitle         = $section_data['subtitle'] ?? 'Explore the most popular categories';
$show_ranks       = ! empty( $section_data['show_rank_badges'] );
$show_button      = ! empty( $section_data['show_button'] );
$button_text      = $section_data['button_text'] ?? '';
$button_url       = $section_data['button_url'] ?? '';
$categories       = $section_data['categories'] ?? array();

if ( empty( $categories ) ) {
	return;
}

$rank_badge_class = array(
	1 => 'bg-amber-100 text-amber-800',
	2 => 'bg-orange-100 text-orange-800',
	3 => 'bg-yellow-100 text-yellow-800',
);
?>
<section class="bg-white py-14 md:py-20" aria-labelledby="categories-heading">
	<div class="pg-container">
		<div class="mb-10 flex flex-col gap-4 sm:mb-12 sm:flex-row sm:items-end sm:justify-between">
			<div class="max-w-2xl">
				<h2 id="categories-heading" class="text-3xl font-bold tracking-tight text-gray-900 md:text-4xl lg:text-[2.75rem] lg:leading-tight">
					<?php echo esc_html( $title ); ?>
				</h2>
				<?php if ( $subtitle ) : ?>
					<p class="mt-2 text-base text-gray-500 md:text-lg"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $show_button && $button_text && $button_url ) : ?>
				<a href="<?php echo esc_url( $button_url ); ?>" class="pg-btn-shop-all shrink-0">
					<?php echo esc_html( $button_text ); ?>
					<svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13.854 8.354L9.354 12.854a.5.5 0 01-.708-.708L12.293 8.5H2.5a.5.5 0 010-1h9.793L8.646 3.854a.5.5 0 11.708-.708l4.5 4.5a.5.5 0 010 .708z" fill="currentColor"/></svg>
				</a>
			<?php endif; ?>
		</div>

		<ul class="grid list-none grid-cols-2 gap-3 m-0 p-0 sm:gap-4 md:grid-cols-3 lg:grid-cols-5 lg:gap-5">
			<?php
			$index = 0;
			foreach ( $categories as $cat ) :
				$index++;
				$label = $cat['label'] ?? '';
				$url   = $cat['url'] ?? '';
				$icon  = $cat['icon'] ?? 0;

				$count_label = trim( (string) ( $cat['count_label'] ?? '' ) );
				if ( '' === $count_label && ! empty( $cat['wp_category'] ) ) {
					$term = get_term( (int) $cat['wp_category'], 'category' );
					if ( $term && ! is_wp_error( $term ) ) {
						$count_label = sprintf(
							/* translators: %d: number of articles */
							_n( '%d article', '%d articles', (int) $term->count, 'kratom-feed' ),
							(int) $term->count
						);
						if ( ! $url ) {
							$url = get_term_link( $term );
							if ( is_wp_error( $url ) ) {
								$url = '';
							}
						}
					}
				}
				if ( '' === $count_label ) {
					$count_label = __( '0 articles', 'kratom-feed' );
				}

				$img_url = $icon ? kratom_feed_image_url( $icon, 'medium' ) : '';
				$rank    = ( $show_ranks && $index <= 3 ) ? $index : 0;
				$href    = $url ? $url : '#';
				?>
				<li>
					<a href="<?php echo esc_url( $href ); ?>" class="pg-category-card group relative flex flex-col items-center rounded-2xl border border-dashed border-gray-200 bg-white px-4 py-8 text-center transition-all duration-300 hover:border-solid hover:border-gray-300 hover:shadow-sm sm:px-5 sm:py-10">
						<?php if ( $rank ) : ?>
							<span class="absolute left-3 top-3 inline-flex rounded-md px-2 py-0.5 text-xs font-semibold <?php echo esc_attr( $rank_badge_class[ $rank ] ); ?>">
								#<?php echo esc_html( (string) $rank ); ?>
							</span>
						<?php endif; ?>

						<div class="pg-category-icon mb-4 flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-gray-100 sm:h-24 sm:w-24">
							<?php if ( $img_url ) : ?>
								<img
									src="<?php echo esc_url( $img_url ); ?>"
									alt=""
									class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
									width="96"
									height="96"
									loading="lazy"
								/>
							<?php else : ?>
								<span class="text-2xl font-bold text-gray-300" aria-hidden="true"><?php echo esc_html( function_exists( 'mb_substr' ) ? mb_substr( $label, 0, 1 ) : substr( $label, 0, 1 ) ); ?></span>
							<?php endif; ?>
						</div>

						<h3 class="text-base font-semibold text-gray-900 sm:text-lg"><?php echo esc_html( $label ); ?></h3>
						<p class="mt-1 text-sm text-gray-500"><?php echo esc_html( $count_label ); ?></p>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
