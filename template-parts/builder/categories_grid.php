<?php
/**
 * Builder: Categories Grid
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$title       = $section_data['title'] ?? 'Main Categories';
$button_text = $section_data['button_text'] ?? 'Shop All';
$button_url  = $section_data['button_url'] ?? get_post_type_archive_link( 'post' );
$categories  = $section_data['categories'] ?? array();
if ( empty( $categories ) ) {
	return;
}
?>
<section class="py-14 md:py-20" aria-labelledby="categories-heading">
	<div class="pg-container flex flex-col gap-12 lg:gap-16">
		<div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
			<h2 id="categories-heading" class="text-[32px] font-semibold text-gray-900 lg:text-5xl"><?php echo esc_html( $title ); ?></h2>
			<?php if ( $button_text && $button_url ) : ?>
				<a href="<?php echo esc_url( $button_url ); ?>" class="pg-btn-shop-all">
					<?php echo esc_html( $button_text ); ?>
					<svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13.854 8.354L9.354 12.854a.5.5 0 01-.708-.708L12.293 8.5H2.5a.5.5 0 010-1h9.793L8.646 3.854a.5.5 0 11.708-.708l4.5 4.5a.5.5 0 010 .708z" fill="currentColor"/></svg>
				</a>
			<?php endif; ?>
		</div>
		<ul class="grid grid-cols-2 gap-2 lg:grid-cols-4 lg:gap-4 xl:grid-cols-6 list-none m-0 p-0">
			<?php foreach ( $categories as $cat ) : ?>
			<li>
				<a href="<?php echo esc_url( $cat['url'] ?? '#' ); ?>" class="pg-category-card">
					<div class="pg-category-icon">
						<?php if ( ! empty( $cat['icon'] ) ) : ?>
							<img src="<?php echo esc_url( kratom_feed_image_url( $cat['icon'], 'thumbnail' ) ); ?>" alt="" class="h-12 w-12 object-contain" width="48" height="48" loading="lazy" />
						<?php endif; ?>
					</div>
					<h3 class="text-sm font-medium text-gray-900"><?php echo esc_html( $cat['label'] ?? '' ); ?></h3>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

