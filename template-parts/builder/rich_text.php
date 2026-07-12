<?php
/**
 * Builder: Rich Text
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$title   = $section_data['title'] ?? '';
$content = $section_data['content'] ?? '';
$bg      = ( $section_data['background'] ?? 'white' ) === 'gray' ? 'border-t border-pg-border bg-pg-gray-light' : '';
if ( ! $title && ! $content ) {
	return;
}
?>
<section class="py-14 md:py-20 <?php echo esc_attr( $bg ); ?>">
	<div class="pg-container max-w-4xl">
		<?php if ( $title ) : ?>
			<h2 class="pg-section-title !text-left"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>
		<?php if ( $content ) : ?>
			<div class="mt-8 space-y-4 text-sm leading-relaxed text-gray-600 pg-prose"><?php echo wp_kses_post( $content ); ?></div>
		<?php endif; ?>
	</div>
</section>
