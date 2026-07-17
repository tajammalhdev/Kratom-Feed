<?php
/**
 * Builder: Trending Categories carousel
 *
 * @package KratomFeeds
 *
 * @var array $section_data Section fields from Carbon.
 */

$title      = ! empty( $section_data['title'] ) ? $section_data['title'] : __( "What's trending:", 'kratom-feed' );
$title_icon = kratom_feed_get_cta_icon_html(
	isset( $section_data['title_icon'] ) ? $section_data['title_icon'] : 0,
	isset( $section_data['title_svg'] ) ? $section_data['title_svg'] : '',
	'kf-trending__title-icon h-4 w-4 shrink-0'
);
$items = isset( $section_data['items'] ) && is_array( $section_data['items'] ) ? $section_data['items'] : array();

if ( empty( $items ) ) {
	return;
}
?>
<section class="kf-trending container py-6" aria-label="<?php esc_attr_e( 'Trending categories', 'kratom-feed' ); ?>">
	<div class="kf-trending__bar flex flex-col gap-3 border-y border-neutral-900 px-3 py-4 sm:flex-row sm:items-center">
		<div class="flex shrink-0 items-center gap-3">
			<?php if ( $title_icon ) : ?>
				<span class="text-[#7D7D7D]" aria-hidden="true"><?php echo $title_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<?php else : ?>
				<svg width="16" height="10" viewBox="0 0 16 10" aria-hidden="true"><path d="M1 9L5 5L8 8L14 2" stroke="#7D7D7D" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			<?php endif; ?>
			<h2 class="text-base font-semibold text-neutral-900"><?php echo esc_html( $title ); ?></h2>
		</div>

		<div class="kf-trending__carousel relative min-w-0 flex-1">
			<button
				type="button"
				class="kf-trending__prev absolute left-0 top-1/2 z-10 hidden h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-900 shadow ring-1 ring-neutral-200 cursor-pointer"
				aria-label="<?php esc_attr_e( 'Scroll categories left', 'kratom-feed' ); ?>"
			>
				<svg width="14" height="14" viewBox="0 0 16 16" aria-hidden="true"><path d="M10 3L5 8l5 5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
			</button>

			<div class="kf-trending__track flex gap-x-2 overflow-x-auto scroll-smooth whitespace-nowrap text-sm font-medium text-[#7D7D7D] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" data-trending-track>
				<?php
				$total = count( $items );
				foreach ( $items as $i => $item ) :
					$label = isset( $item['label'] ) ? trim( (string) $item['label'] ) : '';
					if ( '' === $label ) {
						continue;
					}
					$url  = isset( $item['url'] ) ? trim( (string) $item['url'] ) : '';
					$icon = kratom_feed_get_cta_icon_html(
						isset( $item['icon'] ) ? $item['icon'] : 0,
						isset( $item['svg_code'] ) ? $item['svg_code'] : '',
						'kf-trending__item-icon h-3.5 w-3.5 shrink-0'
					);
					$suffix = ( $i < $total - 1 ) ? ',' : '';
					$classes = 'kf-trending__item inline-flex shrink-0 items-center gap-1.5 transition-colors hover:text-neutral-900';
					?>
					<?php if ( $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $classes ); ?>">
							<?php if ( $icon ) { echo $icon; } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( $label . $suffix ); ?></span>
						</a>
					<?php else : ?>
						<span class="<?php echo esc_attr( $classes ); ?>">
							<?php if ( $icon ) { echo $icon; } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( $label . $suffix ); ?></span>
						</span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<button
				type="button"
				class="kf-trending__next absolute right-0 top-1/2 z-10 hidden h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full bg-white text-neutral-900 shadow ring-1 ring-neutral-200 cursor-pointer"
				aria-label="<?php esc_attr_e( 'Scroll categories right', 'kratom-feed' ); ?>"
			>
				<svg width="14" height="14" viewBox="0 0 16 16" aria-hidden="true"><path d="M6 3l5 5-5 5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
			</button>
		</div>
	</div>
</section>
