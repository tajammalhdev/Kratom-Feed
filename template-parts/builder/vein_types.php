<?php
/**
 * Builder: Vein Types
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$title = $section_data['title'] ?? '';
$cards = $section_data['cards'] ?? array();
if ( empty( $cards ) ) {
	return;
}
?>
<section class="border-t border-pg-border bg-pg-gray-light py-14 md:py-20" aria-labelledby="veins-heading">
	<div class="pg-container">
		<?php if ( $title ) : ?>
			<h2 id="veins-heading" class="pg-section-title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>
		<div class="mt-12 grid gap-8 md:grid-cols-3">
			<?php foreach ( $cards as $card ) : ?>
			<a href="<?php echo esc_url( $card['url'] ?? '#' ); ?>" class="group overflow-hidden rounded-2xl bg-white shadow-md transition-shadow hover:shadow-xl cursor-pointer">
				<?php if ( ! empty( $card['image'] ) ) : ?>
					<img src="<?php echo esc_url( kratom_feed_image_url( $card['image'], 'medium_large' ) ); ?>" alt="<?php echo esc_attr( $card['title'] ?? '' ); ?>" class="h-48 w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" />
				<?php endif; ?>
				<div class="p-6">
					<h3 class="text-xl font-bold uppercase text-gray-900"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
					<?php if ( ! empty( $card['description'] ) ) : ?>
						<p class="mt-3 text-sm leading-relaxed text-gray-600"><?php echo esc_html( $card['description'] ); ?></p>
					<?php endif; ?>
					<span class="mt-4 inline-block text-sm font-bold uppercase text-pg-green-dark group-hover:underline"><?php esc_html_e( 'Learn more â†’', 'kratom-feed' ); ?></span>
				</div>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

