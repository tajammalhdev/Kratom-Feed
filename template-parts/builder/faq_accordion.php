<?php
/**
 * Builder: FAQ Accordion
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$title    = $section_data['title'] ?? 'Questions? Bring them all!';
$subtitle = $section_data['subtitle'] ?? '';
$items    = $section_data['items'] ?? array();
if ( empty( $items ) ) {
	return;
}
?>
<section class="border-t border-pg-border bg-pg-gray-light py-14 md:py-20" aria-labelledby="faq-heading">
	<div class="pg-container max-w-3xl">
		<h2 id="faq-heading" class="pg-section-title"><?php echo esc_html( $title ); ?></h2>
		<?php if ( $subtitle ) : ?>
			<p class="mt-4 text-center text-sm text-gray-600"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>
		<div class="faq-accordion mt-10 space-y-3">
			<?php foreach ( $items as $item ) : ?>
			<div class="faq-item overflow-hidden rounded-xl border border-pg-border bg-white">
				<button type="button" class="faq-trigger flex w-full items-center justify-between px-6 py-5 text-left text-sm font-bold text-gray-900 hover:text-pg-green-dark cursor-pointer" aria-expanded="false">
					<?php echo esc_html( $item['question'] ?? '' ); ?>
					<svg class="faq-icon h-5 w-5 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
				</button>
				<div class="faq-panel hidden border-t border-pg-border px-6 py-4">
					<p class="text-sm text-gray-600"><?php echo esc_html( $item['answer'] ?? '' ); ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
