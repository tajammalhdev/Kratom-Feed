<?php
/**
 * Builder: Newsletter Signup
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$heading  = $section_data['heading'] ?? kratom_feed_get_option( 'newsletter_heading', 'Sign up and Get Weekly Kratom Guides!' );
$desc     = $section_data['description'] ?? kratom_feed_get_option( 'newsletter_description' );
$bullets  = $section_data['bullets'] ?? array();
$badge    = $section_data['badge_text'] ?? 'Free Guide Active';
$fine     = $section_data['fine_print'] ?? '';
?>
<section id="newsletter" class="bg-pg-green-light py-14 md:py-20" aria-labelledby="newsletter-heading">
	<div class="pg-container max-w-3xl text-center">
		<h2 id="newsletter-heading" class="text-2xl font-bold uppercase tracking-wide text-gray-900 md:text-3xl"><?php echo esc_html( $heading ); ?></h2>
		<?php if ( $desc ) : ?>
			<p class="mt-4 text-sm text-gray-600"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $bullets ) ) : ?>
			<ul class="mt-6 space-y-2 text-sm text-gray-700 list-none m-0 p-0">
				<?php foreach ( $bullets as $bullet ) : ?>
					<?php if ( ! empty( $bullet['text'] ) ) : ?>
						<li>- <?php echo esc_html( $bullet['text'] ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<div class="mx-auto mt-8 max-w-md rounded-2xl border-2 border-pg-lime bg-white p-6 shadow-lg">
			<?php if ( $badge ) : ?>
				<p class="text-sm font-bold uppercase text-pg-green-dark"><?php echo esc_html( $badge ); ?></p>
			<?php endif; ?>
			<div class="mt-4"><?php get_template_part( 'template-parts/components/newsletter', 'form' ); ?></div>
			<?php if ( $fine ) : ?>
				<p class="mt-3 text-xs text-gray-400"><?php echo esc_html( $fine ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

