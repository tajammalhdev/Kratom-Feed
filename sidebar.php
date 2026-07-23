<?php
/**
 * Sidebar
 *
 * @package KratomFeeds
 */

$recent_posts = get_posts(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'post__not_in'        => is_singular( 'post' ) ? array( get_the_ID() ) : array(),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>
<aside class="hidden lg:block" aria-label="<?php esc_attr_e( 'Sidebar', 'kratom-feed' ); ?>">
	<?php if ( $recent_posts ) : ?>
	<section class="mb-8">
		<h2 class="mb-5 text-lg font-bold uppercase tracking-wide text-gray-900">
			<?php esc_html_e( 'Recent Posts', 'kratom-feed' ); ?>
		</h2>

		<ul class="m-0 list-none p-0">
			<?php foreach ( $recent_posts as $recent_post ) : ?>
				<?php
				$post_id    = $recent_post->ID;
				$permalink  = get_permalink( $post_id );
				$post_title = get_the_title( $post_id );
				$thumbnail  = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
				$date       = get_the_date( 'M j, Y', $post_id );
				$read_time  = kratom_feed_reading_time( $post_id );
				?>
				<li class="border-b border-neutral-300 py-5 first:pt-0">
					<a href="<?php echo esc_url( $permalink ); ?>" class="group flex items-center gap-4">
						<div class="min-w-0 flex-1">
							<h3 class="line-clamp-2 text-base font-semibold leading-tight text-neutral-900 transition-colors group-hover:text-pg-hover">
								<?php echo esc_html( $post_title ); ?>
							</h3>
							<div class="mt-2 flex flex-wrap items-center gap-1 text-[11px] font-medium text-neutral-500">
								<span>• <?php echo esc_html( $date ); ?></span>
								<span>• <?php echo esc_html( $read_time ); ?></span>
							</div>
						</div>

						<span class="h-20 w-20 shrink-0 overflow-hidden rounded-md bg-pg-gray-light">
							<?php if ( $thumbnail ) : ?>
							<img
								src="<?php echo esc_url( $thumbnail ); ?>"
								alt=""
								class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
								loading="lazy"
								decoding="async"
							/>
							<?php else : ?>
							<span class="flex h-full w-full items-center justify-center text-xs font-bold uppercase text-pg-green-dark" aria-hidden="true">KF</span>
							<?php endif; ?>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-blog' ); ?>
	<?php endif; ?>
</aside>

