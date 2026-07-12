<?php
/**
 * Newsletter form component
 *
 * @package KratomFeeds
 */
$button_text = kratom_feed_get_option( 'newsletter_button_text', __( 'Subscribe', 'kratom-feed' ) );
?>
<form class="newsletter-form flex flex-col gap-3 sm:flex-row sm:items-center" action="#" method="post">
	<label class="sr-only"><?php esc_html_e( 'Email address', 'kratom-feed' ); ?></label>
	<input type="email" name="email" required placeholder="<?php esc_attr_e( 'Enter your email', 'kratom-feed' ); ?>" class="flex-1 rounded-full border border-pg-border bg-white px-5 py-3 text-sm focus:border-pg-green focus:outline-none focus:ring-2 focus:ring-pg-green/20" autocomplete="email" />
	<button type="submit" class="pg-btn-lime shrink-0 whitespace-nowrap"><?php echo esc_html( $button_text ); ?></button>
</form>

