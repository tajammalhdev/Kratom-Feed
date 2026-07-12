<?php
/**
 * Sidebar
 *
 * @package KratomFeeds
 */

if ( ! is_active_sidebar( 'sidebar-blog' ) ) {
	return;
}
?>
<aside class="hidden lg:block" aria-label="<?php esc_attr_e( 'Sidebar', 'kratom-feed' ); ?>">
	<?php dynamic_sidebar( 'sidebar-blog' ); ?>
</aside>

