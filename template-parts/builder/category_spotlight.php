<?php
/**
 * Builder: Category Spotlight
 * Two featured posts + compact post list.
 *
 * @package KratomFeeds
 *
 * @var array $section_data Section fields from Carbon.
 */

$title       = ! empty( $section_data['title'] ) ? $section_data['title'] : __( 'Safety & Basics', 'kratom-feed' );
$description = isset( $section_data['description'] ) ? (string) $section_data['description'] : '';
$show_button = ! empty( $section_data['show_button'] );
$button_text = ! empty( $section_data['button_text'] ) ? $section_data['button_text'] : __( 'Discover More', 'kratom-feed' );
$button_url  = ! empty( $section_data['button_url'] ) ? $section_data['button_url'] : '';
$category_id = isset( $section_data['category'] ) ? $section_data['category'] : '';
$count       = isset( $section_data['posts_per_page'] ) ? absint( $section_data['posts_per_page'] ) : 5;
$count       = max( 1, $count );

$icon_html = '';
$icon_id   = isset( $section_data['icon'] ) ? absint( $section_data['icon'] ) : 0;
$icon_svg  = isset( $section_data['icon_svg'] ) ? (string) $section_data['icon_svg'] : '';

if ( $icon_id ) {
	$mime = get_post_mime_type( $icon_id );
	$path = get_attached_file( $icon_id );
	if ( $path && is_readable( $path ) && ( 'image/svg+xml' === $mime || preg_match( '/\.svg$/i', $path ) ) ) {
		$icon_html = kratom_feed_get_cta_icon_html( $icon_id, '', 'h-7 w-7 shrink-0' );
	} else {
		$url = wp_get_attachment_image_url( $icon_id, 'medium' );
		if ( $url ) {
			$icon_html = sprintf(
				'<img src="%s" alt="" class="h-7 w-auto shrink-0" loading="lazy" decoding="async" />',
				esc_url( $url )
			);
		}
	}
}
if ( ! $icon_html && $icon_svg ) {
	$icon_html = kratom_feed_get_cta_icon_html( 0, $icon_svg, 'h-7 w-7 shrink-0' );
}

$posts = kratom_feed_query_posts_by_category( $category_id, $count );
if ( empty( $posts ) ) {
	return;
}

$featured  = array_shift( $posts );
$secondary = ! empty( $posts ) ? array_shift( $posts ) : null;
$list      = $posts;

$kf_resolve_url = static function ( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	if ( '#' === $url[0] || preg_match( '#^https?://#i', $url ) ) {
		return $url;
	}
	return home_url( $url );
};
$button_href = $show_button ? $kf_resolve_url( $button_url ) : '';

$featured_id    = $featured->ID;
$featured_link  = get_permalink( $featured_id );
$featured_title = get_the_title( $featured_id );
$featured_thumb = get_the_post_thumbnail_url( $featured_id, 'large' );
if ( ! $featured_thumb ) {
	$featured_thumb = 'https://placehold.co/800x900/0b1a13/b8f04a?text=Kratom+Feed';
}
$featured_cat     = kratom_feed_get_post_primary_category( $featured_id );
$featured_date    = get_the_date( '', $featured_id );
$featured_read    = kratom_feed_reading_time( $featured_id );
$featured_excerpt = get_the_excerpt( $featured_id );
if ( ! $featured_excerpt ) {
	$featured_excerpt = wp_trim_words( wp_strip_all_tags( $featured->post_content ), 18 );
}

$secondary_id      = $secondary ? $secondary->ID : 0;
$secondary_link    = $secondary_id ? get_permalink( $secondary_id ) : '';
$secondary_title   = $secondary_id ? get_the_title( $secondary_id ) : '';
$secondary_thumb   = $secondary_id ? get_the_post_thumbnail_url( $secondary_id, 'large' ) : '';
$secondary_cat     = $secondary_id ? kratom_feed_get_post_primary_category( $secondary_id ) : '';
$secondary_date    = $secondary_id ? get_the_date( '', $secondary_id ) : '';
$secondary_read    = $secondary_id ? kratom_feed_reading_time( $secondary_id ) : '';
$secondary_excerpt = $secondary_id ? get_the_excerpt( $secondary_id ) : '';
if ( $secondary_id && ! $secondary_thumb ) {
	$secondary_thumb = 'https://placehold.co/600x600/0b1a13/b8f04a?text=Kratom+Feed';
}
if ( $secondary_id && ! $secondary_excerpt ) {
	$secondary_excerpt = wp_trim_words( wp_strip_all_tags( $secondary->post_content ), 18 );
}
?>
<section class="kf-cat-spotlight container py-6 lg:py-6" aria-labelledby="kf-cat-spotlight-heading">
	<div class="flex flex-col items-start justify-between gap-7 md:flex-row md:items-center">
		<div class="flex min-w-0 max-w-4xl flex-col gap-4">
			<div class="flex flex-wrap items-center gap-3">
				<h2 id="kf-cat-spotlight-heading" class="text-3xl font-semibold leading-tight text-[#202b3c] sm:text-4xl">
					<?php echo esc_html( $title ); ?>
				</h2>
				<?php if ( $icon_html ) : ?>
					<span class="inline-flex shrink-0 items-center text-[#41b955]" aria-hidden="true"><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php else : ?>
					<svg class="h-10 w-10 shrink-0 text-[#41b955]" viewBox="0 0 42 46" fill="none" aria-hidden="true">
						<path d="M21 2.5c5.2 4.3 11 6.3 18 6.3v11.4c0 11.2-7 19.7-18 23.3C10 39.9 3 31.4 3 20.2V8.8c7 0 12.8-2 18-6.3Z" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
						<path d="M21 12v20M11 22h20" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
					</svg>
				<?php endif; ?>
			</div>
			<?php if ( $description ) : ?>
			<p class="max-w-[850px] text-sm leading-relaxed text-neutral-500 sm:text-base">
				<?php echo esc_html( $description ); ?>
			</p>
			<?php endif; ?>
		</div>

		<?php if ( $show_button && $button_text && $button_href ) : ?>
		<a
			href="<?php echo esc_url( $button_href ); ?>"
			class="inline-flex h-12 shrink-0 items-center justify-center gap-3 rounded bg-[#41b955] px-6 text-sm font-medium text-white transition-colors hover:bg-pg-green-dark"
		>
			<?php echo esc_html( $button_text ); ?>
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M13.8538 8.35378L9.35375 12.8538C9.25993 12.9476 9.13268 13.0003 9 13.0003C8.86732 13.0003 8.74007 12.9476 8.64625 12.8538C8.55243 12.76 8.49972 12.6327 8.49972 12.5C8.49972 12.3674 8.55243 12.2401 8.64625 12.1463L12.2931 8.50003H2.5C2.36739 8.50003 2.24021 8.44736 2.14645 8.35359C2.05268 8.25982 2 8.13264 2 8.00003C2 7.86743 2.05268 7.74025 2.14645 7.64648C2.24021 7.55271 2.36739 7.50003 2.5 7.50003H12.2931L8.64625 3.85378C8.55243 3.75996 8.49972 3.63272 8.49972 3.50003C8.49972 3.36735 8.55243 3.2401 8.64625 3.14628C8.74007 3.05246 8.86732 2.99976 9 2.99976C9.13268 2.99976 9.25993 3.05246 9.35375 3.14628L13.8538 7.64628C13.9002 7.69272 13.9371 7.74786 13.9623 7.80856C13.9874 7.86926 14.0004 7.93433 14.0004 8.00003C14.0004 8.06574 13.9874 8.13081 13.9623 8.1915C13.9371 8.2522 13.9002 8.30735 13.8538 8.35378Z" fill="white"/>
			</svg>
		</a>
		<?php endif; ?>
	</div>

	<div class="kf-cat-spotlight__grid mt-12 ">
		<article class="kf-cat-spotlight__feature group hover-underline">
			<div class="kf-cat-spotlight__media">
				<a href="<?php echo esc_url( $featured_link ); ?>" class="block h-full overflow-hidden rounded-md">
					<img
						src="<?php echo esc_url( $featured_thumb ); ?>"
						alt="<?php echo esc_attr( $featured_title ); ?>"
						class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
						loading="lazy"
						decoding="async"
					/>
				</a>
				<?php if ( $featured_cat ) : ?>
				<span class="kf-cat-spotlight__badge"><span class="h-1.5 w-1.5 rounded-full bg-neutral-900"></span><?php echo esc_html( $featured_cat ); ?></span>
				<?php endif; ?>
			</div>
			<div class="mt-4 flex flex-wrap items-center gap-2 text-xs font-medium text-neutral-500">
				<time datetime="<?php echo esc_attr( get_the_date( 'c', $featured_id ) ); ?>"><?php echo esc_html( $featured_date ); ?></time>
				<span>•</span><span><?php echo esc_html( $featured_read ); ?></span>
			</div>
			<h3 class="mt-3 max-w-xl text-2xl font-semibold leading-[1.18] text-neutral-900 ">
				<a href="<?php echo esc_url( $featured_link ); ?>" class="transition-colors  card-title card-title-black">
					<?php echo esc_html( $featured_title ); ?>
				</a>
			</h3>
			<?php if ( $featured_excerpt ) : ?>
			<p class="mt-3 line-clamp-2 max-w-xl text-sm leading-relaxed text-neutral-500">
				<?php echo esc_html( $featured_excerpt ); ?>
			</p>
			<?php endif; ?>
			<a href="<?php echo esc_url( $featured_link ); ?>" class="kf-cat-spotlight__read-more">
				<?php esc_html_e( 'Read more', 'kratom-feed' ); ?>
				<svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</a>
		</article>

		<?php if ( $secondary_id ) : ?>
		<article class="kf-cat-spotlight__feature group hover-underline">
			<div class="kf-cat-spotlight__media">
				<a href="<?php echo esc_url( $secondary_link ); ?>" class="block h-full overflow-hidden rounded-md">
					<img
						src="<?php echo esc_url( $secondary_thumb ); ?>"
						alt="<?php echo esc_attr( $secondary_title ); ?>"
						class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
						loading="lazy"
						decoding="async"
					/>
				</a>
				<?php if ( $secondary_cat ) : ?>
				<span class="kf-cat-spotlight__badge"><span class="h-1.5 w-1.5 rounded-full bg-neutral-900"></span><?php echo esc_html( $secondary_cat ); ?></span>
				<?php endif; ?>
			</div>
			<div class="mt-4 flex flex-wrap items-center gap-2 text-xs font-medium text-neutral-500">
				<time datetime="<?php echo esc_attr( get_the_date( 'c', $secondary_id ) ); ?>"><?php echo esc_html( $secondary_date ); ?></time>
				<span>•</span><span><?php echo esc_html( $secondary_read ); ?></span>
			</div>
			<h3 class="mt-3 text-xl font-semibold leading-[1.2] text-neutral-900 ">
				<a href="<?php echo esc_url( $secondary_link ); ?>" class="transition-colors card-title card-title-black">
					<?php echo esc_html( $secondary_title ); ?>
				</a>
			</h3>
			<?php if ( $secondary_excerpt ) : ?>
			<p class="mt-3 line-clamp-2 text-sm leading-relaxed text-neutral-500">
				<?php echo esc_html( $secondary_excerpt ); ?>
			</p>
			<?php endif; ?>
			<a href="<?php echo esc_url( $secondary_link ); ?>" class="kf-cat-spotlight__read-more">
				<?php esc_html_e( 'Read more', 'kratom-feed' ); ?>
				<svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</a>
		</article>
		<?php endif; ?>

		<?php if ( ! empty( $list ) ) : ?>
		<ul class="kf-cat-spotlight__list">
			<?php foreach ( $list as $post ) : ?>
			<?php
			$post_id    = $post->ID;
			$permalink  = get_permalink( $post_id );
			$post_title = get_the_title( $post_id );
			$thumb      = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			if ( ! $thumb ) {
				$thumb = 'https://placehold.co/160x160/0b1a13/b8f04a?text=KF';
			}
			$date = get_the_date( 'M j, Y', $post_id );
			$read = kratom_feed_reading_time( $post_id );
			?>
			<li class="kf-cat-spotlight__list-item">
				<a href="<?php echo esc_url( $permalink ); ?>" class="group flex h-full items-center gap-4 hover-underline ">
					<div class="min-w-0 flex-1">
						<h3 class="line-clamp-2 text-base font-semibold card-title card-title-black leading-[1.15] text-neutral-900 transition-colors ">
							<?php echo esc_html( $post_title ); ?>
						</h3>
						<div class="mt-2 flex flex-wrap items-center gap-1 text-[11px] font-medium text-neutral-500">
							<span>• <?php echo esc_html( $date ); ?></span>
							<span>• <?php echo esc_html( $read ); ?></span>
						</div>
					</div>
					<span class="h-20 w-20 shrink-0 overflow-hidden rounded-md">
						<img src="<?php echo esc_url( $thumb ); ?>" alt="" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" decoding="async" />
					</span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
</section>
