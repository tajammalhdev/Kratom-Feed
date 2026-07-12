<?php
/**
 * Builder: Hero Featured (magazine layout)
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$featured_assoc = $section_data['featured_post'] ?? array();
$featured_id    = ! empty( $featured_assoc[0]['id'] ) ? (int) $featured_assoc[0]['id'] : 0;
$category       = $section_data['category'] ?? '';
$grid_count     = max( 1, min( 12, (int) ( $section_data['grid_count'] ?? 6 ) ) );

if ( $featured_id ) {
	$featured = get_post( $featured_id );
	if ( ! $featured || 'publish' !== $featured->post_status ) {
		$featured = null;
	}
} else {
	$featured_args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => true,
	);
	if ( $category ) {
		$featured_args['cat'] = (int) $category;
	}
	$featured_q = new WP_Query( $featured_args );
	$featured   = $featured_q->have_posts() ? $featured_q->posts[0] : null;
	wp_reset_postdata();
}

if ( ! $featured ) {
	return;
}

$featured_id = $featured->ID;

$grid_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $grid_count,
	'post__not_in'        => array( $featured_id ),
	'ignore_sticky_posts' => true,
);
if ( $category ) {
	$grid_args['cat'] = (int) $category;
}
$grid_q = new WP_Query( $grid_args );
?>
<section class="bg-white py-8 md:py-10 lg:py-12" aria-label="<?php esc_attr_e( 'Featured articles', 'kratom-feed' ); ?>">
	<div class="pg-container">
		<div class="grid items-start gap-10 lg:grid-cols-[minmax(0,42%)_minmax(0,58%)] lg:gap-12 xl:gap-16">
			<article class="pg-hero-featured">
				<a href="<?php echo esc_url( get_permalink( $featured ) ); ?>" class="group block">
					<?php if ( has_post_thumbnail( $featured ) ) : ?>
						<div class="overflow-hidden rounded-2xl">
							<?php
							echo get_the_post_thumbnail(
								$featured,
								'large',
								array(
									'class'   => 'aspect-[3/2] w-full object-cover transition-transform duration-500 group-hover:scale-[1.02]',
									'loading' => 'eager',
								)
							);
							?>
						</div>
					<?php endif; ?>
					<?php
					$featured_cat = kratom_feed_get_post_primary_category( $featured_id );
					if ( $featured_cat ) :
						?>
						<p class="mt-4 text-sm text-gray-400"><?php echo esc_html( $featured_cat ); ?></p>
					<?php endif; ?>
					<h2 class="mt-2 text-2xl font-bold leading-tight text-gray-900 transition-colors group-hover:text-pg-green-dark md:text-[1.75rem] lg:text-[2rem] lg:leading-snug">
						<?php echo esc_html( get_the_title( $featured ) ); ?>
					</h2>
					<?php if ( has_excerpt( $featured ) || $featured->post_content ) : ?>
						<p class="mt-3 text-sm leading-relaxed text-gray-500">
							<?php echo esc_html( wp_trim_words( get_the_excerpt( $featured ), 28, '…' ) ); ?>
						</p>
					<?php endif; ?>
				</a>
			</article>

			<?php if ( $grid_q->have_posts() ) : ?>
				<div class="grid gap-x-6 gap-y-7 sm:grid-cols-2 sm:gap-x-8 sm:gap-y-8">
					<?php
					while ( $grid_q->have_posts() ) :
						$grid_q->the_post();
						$item_cat = kratom_feed_get_post_primary_category();
						?>
						<article class="pg-hero-grid-item">
							<a href="<?php the_permalink(); ?>" class="group flex items-start gap-4">
								<div class="min-w-0 flex-1">
									<?php if ( $item_cat ) : ?>
										<p class="text-xs text-gray-400"><?php echo esc_html( $item_cat ); ?></p>
									<?php endif; ?>
									<h3 class="mt-1 text-[15px] font-bold leading-snug text-gray-900 transition-colors group-hover:text-pg-green-dark sm:text-base">
										<?php the_title(); ?>
									</h3>
								</div>
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="h-[72px] w-[72px] flex-shrink-0 overflow-hidden rounded-xl sm:h-20 sm:w-20">
										<?php
										the_post_thumbnail(
											'thumbnail',
											array(
												'class'   => 'h-full w-full object-cover',
												'loading' => 'lazy',
											)
										);
										?>
									</div>
								<?php endif; ?>
							</a>
						</article>
					<?php endwhile; ?>
				</div>
			<?php endif; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
