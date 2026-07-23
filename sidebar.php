<?php
/**
 * Sidebar
 *
 * @package KratomFeeds
 */

if ( ! kratom_feed_is_blog_sidebar_enabled() || ! is_active_sidebar( 'sidebar-blog' ) ) {
	return;
}
?>
<aside class="kf-blog-sidebar hidden lg:block" aria-label="<?php esc_attr_e( 'Sidebar', 'kratom-feed' ); ?>">
	<div class="sticky top-28 space-y-6">
		<?php dynamic_sidebar( 'sidebar-blog' ); ?>
	</div>
</aside>
