<?php
/**
 * Storefront megamenu panel
 *
 * @package KratomFeeds
 *
 * @var array $args {
 *   @type string   $mega_id
 *   @type array    $link
 *   @type WP_Query $latest_q
 *   @type WP_Query $popular_q
 * }
 */

$mega_id   = $args['mega_id'] ?? '';
$link      = $args['link'] ?? array();
$latest_q  = $args['latest_q'] ?? null;
$popular_q = $args['popular_q'] ?? null;
$children  = $link['children'] ?? array();
?>
<div id="<?php echo esc_attr( $mega_id ); ?>" class="kf-sf-mega" aria-hidden="true">
	<div class="container kf-sf-mega__inner">
		<div class="kf-sf-mega__grid">
			<div class="kf-sf-mega__col kf-sf-mega__col--cats">
				<p class="kf-sf-mega__heading"><?php echo esc_html( $link['label'] ?? '' ); ?></p>
				<ul class="kf-sf-mega__links">
					<?php if ( ! empty( $link['url'] ) ) : ?>
					<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php esc_html_e( 'View all', 'kratom-feed' ); ?></a></li>
					<?php endif; ?>
					<?php foreach ( $children as $child ) : ?>
					<li><a href="<?php echo esc_url( $child['url'] ); ?>"><?php echo esc_html( $child['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="kf-sf-mega__col">
				<p class="kf-sf-mega__heading"><?php esc_html_e( 'Latest', 'kratom-feed' ); ?></p>
				<?php if ( $latest_q instanceof WP_Query && $latest_q->have_posts() ) : ?>
				<ul class="kf-sf-mega__posts">
					<?php
					while ( $latest_q->have_posts() ) :
						$latest_q->the_post();
						?>
						<li>
							<a href="<?php the_permalink(); ?>" class="kf-sf-mega__post">
								<span class="kf-sf-mega__thumb">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover' ) );
									}
									?>
								</span>
								<span class="kf-sf-mega__post-meta">
									<span class="kf-sf-mega__post-title"><?php the_title(); ?></span>
									<span class="kf-sf-mega__post-date"><?php echo esc_html( get_the_date() ); ?></span>
								</span>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php
				$latest_q->rewind_posts();
				wp_reset_postdata();
				endif;
				?>
			</div>

			<div class="kf-sf-mega__col">
				<p class="kf-sf-mega__heading"><?php esc_html_e( 'Popular', 'kratom-feed' ); ?></p>
				<?php if ( $popular_q instanceof WP_Query && $popular_q->have_posts() ) : ?>
				<ul class="kf-sf-mega__posts">
					<?php
					$count = 0;
					while ( $popular_q->have_posts() && $count < 3 ) :
						$popular_q->the_post();
						++$count;
						?>
						<li>
							<a href="<?php the_permalink(); ?>" class="kf-sf-mega__post">
								<span class="kf-sf-mega__thumb">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover' ) );
									}
									?>
								</span>
								<span class="kf-sf-mega__post-meta">
									<span class="kf-sf-mega__post-title"><?php the_title(); ?></span>
									<span class="kf-sf-mega__post-date"><?php echo esc_html( get_the_date() ); ?></span>
								</span>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php
				$popular_q->rewind_posts();
				wp_reset_postdata();
				endif;
				?>
			</div>
		</div>
	</div>
</div>
