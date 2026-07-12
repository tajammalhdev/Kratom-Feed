<?php
/**
 * Blog card
 *
 * @package KratomFeeds
 */
?>
<article <?php post_class( 'pg-card group' ); ?> data-title="<?php echo esc_attr( get_the_title() ); ?>">
	<a href="<?php the_permalink(); ?>" class="block">
		<div class="relative aspect-square overflow-hidden bg-pg-gray-light">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'h-full w-full object-cover transition-transform duration-500 group-hover:scale-105' ) ); ?>
			<?php else : ?>
				<div class="flex h-full w-full items-center justify-center bg-neutral-100 text-neutral-400"><?php esc_html_e( 'No image', 'kratom-feed' ); ?></div>
			<?php endif; ?>
			<?php
			$cats = get_the_category();
			if ( ! empty( $cats ) ) :
			?>
				<div class="absolute left-3 top-3">
					<span class="pg-badge-new"><?php echo esc_html( $cats[0]->name ); ?></span>
				</div>
			<?php endif; ?>
		</div>
		<div class="p-4">
			<h3 class="line-clamp-2 text-sm font-semibold leading-snug text-gray-900 group-hover:text-pg-green-dark transition-colors"><?php the_title(); ?></h3>
			<p class="mt-2 text-xs text-gray-500">
				<?php
				if ( kratom_feed_get_option( 'show_reading_time', true ) ) {
					echo esc_html( kratom_feed_reading_time() ) . ' Â· ';
				}
				echo esc_html( get_the_date() );
				?>
			</p>
			<span class="mt-3 inline-block text-xs font-bold uppercase tracking-wider text-pg-green-dark group-hover:underline"><?php esc_html_e( 'Read article â†’', 'kratom-feed' ); ?></span>
		</div>
	</a>
</article>

