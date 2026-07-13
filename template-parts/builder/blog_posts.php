<?php
/**
 * Builder: Blog Posts
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$title     = $section_data['title'] ?? '';
$subtitle  = $section_data['subtitle'] ?? '';
$link_text = $section_data['link_text'] ?? '';
$link_url  = $section_data['link_url'] ?? get_post_type_archive_link( 'post' );
$layout    = $section_data['layout'] ?? 'grid';
$per_page  = max( 1, (int) ( $section_data['posts_per_page'] ?? 4 ) );
$category  = $section_data['category'] ?? '';
$bg        = ( $section_data['background'] ?? 'gray' ) === 'gray' ? 'border-t border-pg-border bg-pg-gray-light' : '';

$args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $per_page,
	'ignore_sticky_posts' => true,
);
if ( $category ) {
	$args['cat'] = (int) $category;
}
$q = new WP_Query( $args );
if ( ! $q->have_posts() ) {
	return;
}

$grid_class = 'horizontal' === $layout
	? 'mt-10 flex gap-5 overflow-x-auto pb-4 scrollbar-hide lg:grid lg:grid-cols-4 lg:overflow-visible'
	: 'mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4';
?>
<section class="<?php echo esc_attr( trim( "py-14 md:py-20 $bg" ) ); ?>" aria-labelledby="blog-posts-<?php echo esc_attr( sanitize_title( $title ) ); ?>">
	<div class="pg-container">
		<?php if ( $title ) : ?>
			<h2 id="blog-posts-<?php echo esc_attr( sanitize_title( $title ) ); ?>" class="pg-section-title <?php echo 'grid' === $layout ? '!text-left' : ''; ?>"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>
		<?php if ( $subtitle ) : ?>
			<p class="mx-auto mt-4 max-w-3xl text-center text-sm leading-relaxed text-gray-600"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>
		<?php if ( $link_text && $link_url ) : ?>
			<div class="mt-6 <?php echo 'grid' === $layout ? '' : 'text-center'; ?>">
				<a href="<?php echo esc_url( $link_url ); ?>" class="inline-block text-sm font-bold uppercase tracking-wider text-pg-green-dark hover:underline cursor-pointer"><?php echo esc_html( $link_text ); ?></a>
			</div>
		<?php endif; ?>
		<div class="<?php echo esc_attr( $grid_class ); ?>">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				$card_class = 'horizontal' === $layout ? 'pg-card group min-w-[260px] flex-shrink-0 lg:min-w-0' : 'pg-card group';
				?>
				<article <?php post_class( $card_class ); ?> data-title="<?php echo esc_attr( get_the_title() ); ?>">
					<a href="<?php the_permalink(); ?>" class="block">
						<div class="relative aspect-square overflow-hidden">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'medium_large', array( 'class' => 'h-full w-full object-cover group-hover:scale-105 transition-transform duration-500' ) );
							}
							?>
						</div>
						<div class="p-4">
							<h3 class="line-clamp-2 text-sm font-semibold text-gray-900 group-hover:text-pg-green-dark"><?php the_title(); ?></h3>
							<p class="mt-2 text-xs text-gray-500"><?php echo esc_html( kratom_feed_reading_time() . ' | ' . get_the_date() ); ?></p>
							<span class="mt-2 inline-block text-xs font-bold uppercase text-pg-green-dark"><?php esc_html_e( 'Read article ->', 'kratom-feed' ); ?></span>
						</div>
					</a>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>

