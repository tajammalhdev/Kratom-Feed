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
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
	)
);
?>
<div id="search-modal" class="pg-search-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
	<div id="search-modal-overlay" class="pg-search-modal__overlay" tabindex="-1"></div>
	<div class="pg-search-modal__panel">
		<div class="pg-search-modal__header">
			<h2 id="search-modal-title" class="pg-search-modal__title"><?php esc_html_e( 'Search', 'kratom-feed' ); ?></h2>
			<button type="button" id="search-modal-close" class="pg-search-modal__close" aria-label="<?php esc_attr_e( 'Close search', 'kratom-feed' ); ?>">
				<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
			</button>
		</div>

		<form role="search" method="get" class="pg-search-modal__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="modal-search-input" class="sr-only"><?php esc_html_e( 'Search articles', 'kratom-feed' ); ?></label>
			<input
				id="modal-search-input"
				type="search"
				name="s"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				class="pg-search-modal__input"
				autocomplete="off"
			/>
			<input type="hidden" name="post_type" value="post" />
			<button type="submit" class="pg-search-modal__submit"><?php esc_html_e( 'Search', 'kratom-feed' ); ?></button>
		</form>

		<?php if ( ! empty( $categories ) ) : ?>
		<div class="pg-search-modal__tags" role="list">
			<?php foreach ( $categories as $cat ) : ?>
				<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" class="pg-search-modal__tag" role="listitem">
					<span><?php echo esc_html( $cat->name ); ?></span>
					<span class="pg-search-modal__tag-count"><?php echo esc_html( (string) $cat->count ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( $recommended->have_posts() ) : ?>
		<div class="pg-search-modal__recommended">
			<p class="pg-search-modal__section-title"><?php esc_html_e( 'Recommended for you', 'kratom-feed' ); ?></p>
			<ul class="pg-search-modal__rec-list">
				<?php
				while ( $recommended->have_posts() ) :
					$recommended->the_post();
					?>
					<li>
						<a href="<?php the_permalink(); ?>" class="pg-search-modal__rec-item">
							<?php if ( has_post_thumbnail() ) : ?>
								<span class="pg-search-modal__rec-thumb">
									<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover', 'loading' => 'lazy' ) ); ?>
								</span>
							<?php endif; ?>
							<span class="pg-search-modal__rec-meta">
								<span class="pg-search-modal__rec-title"><?php the_title(); ?></span>
								<span class="pg-search-modal__rec-info"><?php echo esc_html( get_the_date( 'j M, Y' ) . ' / ' . kratom_feed_reading_time() ); ?></span>
							</span>
						</a>
					</li>
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</div>
