<?php
/**
 * Builder: Quiz CTA
 *
 * @package KratomFeeds
 *
 * @var array $section_data
 */

$badge       = ! empty( $section_data['badge'] ) ? $section_data['badge'] : __( 'Free · Takes 60 Seconds', 'kratom-feed' );
$title       = ! empty( $section_data['title'] ) ? $section_data['title'] : __( 'Find Your Perfect Kratom', 'kratom-feed' );
$description = isset( $section_data['description'] ) ? (string) $section_data['description'] : '';
$button_text = ! empty( $section_data['button_text'] ) ? $section_data['button_text'] : __( 'Take the Quiz', 'kratom-feed' );
$button_url  = ! empty( $section_data['button_url'] ) ? $section_data['button_url'] : '/kratom-quiz/';

if ( $button_url && '#' !== $button_url[0] && ! preg_match( '#^https?://#i', $button_url ) ) {
	$button_url = home_url( $button_url );
}
?>
<section class="kf-quiz-cta container py-10 lg:py-14">
	<div class="overflow-hidden rounded-2xl border border-pg-green/20 bg-gradient-to-br from-[#0b1a13] via-[#123022] to-[#1a4a2e] px-8 py-12 text-white sm:px-12 lg:px-16">
		<div class="flex flex-col items-start justify-between gap-8 lg:flex-row lg:items-center">
			<div class="max-w-2xl">
				<?php if ( $badge ) : ?>
				<span class="mb-4 inline-block rounded-full border border-white/25 bg-white/10 px-3.5 py-1 text-[13px] font-semibold">
					<?php echo esc_html( $badge ); ?>
				</span>
				<?php endif; ?>
				<h2 class="m-0 text-3xl font-bold leading-tight sm:text-4xl"><?php echo esc_html( $title ); ?></h2>
				<?php if ( $description ) : ?>
				<p class="mt-4 mb-0 text-base leading-relaxed text-white/85"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $button_text && $button_url ) : ?>
			<a
				href="<?php echo esc_url( $button_url ); ?>"
				class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded-md bg-[#41b955] px-7 text-base font-semibold text-white transition-colors hover:bg-pg-hover"
			>
				<?php echo esc_html( $button_text ); ?>
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
					<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>
			<?php endif; ?>
		</div>
	</div>
</section>
