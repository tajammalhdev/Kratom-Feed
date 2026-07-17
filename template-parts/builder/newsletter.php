<?php
/**
 * Builder: Newsletter
 *
 * The signup panel is intentionally left as an integration slot. Omnisend
 * markup can be attached later through the kratom_feed_newsletter_form action.
 *
 * @package KratomFeeds
 *
 * @var array $section_data Section fields from Carbon.
 */

$section_id   = ! empty( $section_data['section_id'] ) ? sanitize_title( $section_data['section_id'] ) : 'newsletter';
$heading_top  = ! empty( $section_data['heading_top'] ) ? $section_data['heading_top'] : __( 'Never Miss a', 'kratom-feed' );
$heading_green = ! empty( $section_data['heading_accent'] ) ? $section_data['heading_accent'] : __( 'Kratom Update', 'kratom-feed' );
$description  = isset( $section_data['description'] ) ? (string) $section_data['description'] : '';
$form_heading = ! empty( $section_data['form_heading'] ) ? $section_data['form_heading'] : __( 'Join Our Kratom Newsletter', 'kratom-feed' );
$background_id = isset( $section_data['background_image'] ) ? absint( $section_data['background_image'] ) : 0;
$background    = $background_id ? wp_get_attachment_image_url( $background_id, 'full' ) : '';
$style         = $background ? sprintf( '--kf-newsletter-bg: url(%s);', esc_url( $background ) ) : '';
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="kf-newsletter py-10 lg:py-14">
	<div
		class="kf-newsletter__panel relative isolate min-h-132 overflow-hidden border border-[rgba(98,129,110,0.55)] bg-[#07120d] text-white"
		<?php echo $style ? 'style="' . esc_attr( $style ) . '"' : ''; ?>
	>
		<div class="container relative z-10 grid min-h-132 items-center gap-8 py-12 md:grid-cols-[minmax(0,1.7fr)_minmax(18rem,0.8fr)] md:py-14 lg:gap-12">
			<div class="max-w-176">
				<h2 class="flex flex-col text-[clamp(2.25rem,4vw,3.5rem)] font-bold leading-[1.12] tracking-[-0.035em]">
					<span><?php echo esc_html( $heading_top ); ?></span>
					<span class="mt-[0.2rem] text-[#41b955]"><?php echo esc_html( $heading_green ); ?></span>
				</h2>
				<?php if ( $description ) : ?>
				<p class="mt-7 max-w-172 text-[0.9375rem] leading-[1.55] text-white/90"><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>
			</div>

			<aside
				class="min-h-68 w-full border border-white/15 bg-[rgba(9,12,10,0.88)] px-8 pb-8 pt-12 shadow-[0_18px_50px_rgba(0,0,0,0.28)] backdrop-blur-[6px] md:min-h-76"
				aria-labelledby="<?php echo esc_attr( $section_id ); ?>-signup-heading"
			>
				<h3 id="<?php echo esc_attr( $section_id ); ?>-signup-heading" class="mx-auto max-w-68 text-center text-[1.65rem] font-semibold leading-[1.2]">
					<?php echo esc_html( $form_heading ); ?>
				</h3>
				<div class="kf-newsletter__form-slot mt-6 min-h-28">
					<?php
					/**
					 * Reserved for the future Omnisend embed.
					 *
					 * @param array $section_data Current builder section values.
					 */
					do_action( 'kratom_feed_newsletter_form', $section_data );
					?>
				</div>
			</aside>
		</div>
	</div>
</section>
