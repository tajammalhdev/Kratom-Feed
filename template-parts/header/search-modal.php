<?php
/**
 * Magzin-style search modal
 *
 * @package KratomFeeds
 */

$placeholder = kratom_feed_get_option( 'header_search_placeholder', __( 'What Are You Looking For?', 'kratom-feed' ) );

$categories = get_categories(
	array(
		'hide_empty' => true,
		'number'     => 12,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);

$recommended = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 4,
		'ignore_sticky_posts' => true,
	)
);
?>
<div class="sidebar-overlay" id="search-modal-overlay"></div>
<div class="popup-search d-none d-md-block show relative" id="search-modal">
	<div class="container">
		<div class="row">
			<div class="col-10 mx-auto">
				<div class="popup-search-content position-relative">
					<a href="#" class="close-popup position-absolute top-0 end-0 m-3" id="search-modal-close"><svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M17.25 6.75L6.75 17.25" stroke="#0E0E0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.75 6.75L17.25 17.25" stroke="#0E0E0F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></a>
					<h5 class="mb-4">Search</h5>
					<form class="flex flex-wrap flex-lg-nowrap gap-2 lg:flex-nowrap" action="#">
						<input class="form-control" type="text" placeholder="What Are You Looking For?" 	
						value="<?php echo esc_attr( get_search_query() ); ?>"
						placeholder="<?php echo esc_attr( $placeholder ); ?>"
						>
						<button class="btn btn-dark" type="submit">Search</button>
					</form>
					<div class="block-tag mt-5">
						<?php foreach ( $categories as $cat ) : ?>
							<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" class="tag-item" role="listitem">
								<span><?php echo esc_html( $cat->name ); ?></span>
								<span class="number"><?php echo esc_html( (string) $cat->count ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
					<div class="mt-5">
						<div class="block-recomment">
							<h5>Recommended for you</h5>
							<?php if ( $recommended->have_posts() ) : ?>
								<div class="swiper swiper-popup-search">
									<div class="swiper-wrapper">
										<?php
										while ( $recommended->have_posts() ) :
											$recommended->the_post();
											?>
											<div class="swiper-slide">
												<div class="article card-10 style-1">
													<?php if ( has_post_thumbnail() ) : ?>
														<a class="card-img" href="<?php the_permalink(); ?>">
															<?php
															the_post_thumbnail(
																'medium',
																array(
																	'class'    => 'w-100',
																	'loading'  => 'lazy',
																	'alt'      => the_title_attribute( array( 'echo' => false ) ),
																)
															);
															?>
														</a>
													<?php endif; ?>
													<div class="card-body">
														<a href="<?php the_permalink(); ?>">
															<h6 class="fs-7 mb-2 text-truncate-2"><?php the_title(); ?></h6>
														</a>
														<div class="d-flex align-items-center text-600">
															<span class="fs-8"><?php echo esc_html( get_the_date( 'j M, Y' ) ); ?></span>
															<ul class="ps-4 m-0">
																<li><span class="fs-8"><?php echo esc_html( kratom_feed_reading_time() ); ?></span></li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										<?php endwhile; wp_reset_postdata(); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>