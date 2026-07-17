<?php
/**
 * Builder: Posts by Category (two Swiper card styles)
 *
 * @package KratomFeeds
 *
 * @var array $section_data Section fields from Carbon.
 */

$style       = ! empty( $section_data['style'] ) ? $section_data['style'] : 'style_1';
$title       = ! empty( $section_data['title'] ) ? $section_data['title'] : __( 'Learn With Kratom Feed', 'kratom-feed' );
$description = isset( $section_data['description'] ) ? (string) $section_data['description'] : '';
$show_button = ! empty( $section_data['show_button'] );
$button_text = ! empty( $section_data['button_text'] ) ? $section_data['button_text'] : __( 'Discover More', 'kratom-feed' );
$button_url  = ! empty( $section_data['button_url'] ) ? $section_data['button_url'] : '';
$category_id = isset( $section_data['category'] ) ? $section_data['category'] : '';
$count       = isset( $section_data['posts_per_page'] ) ? absint( $section_data['posts_per_page'] ) : 4;

$style = in_array( $style, array( 'style_1', 'style_2' ), true ) ? $style : 'style_1';

$icon_html = '';
$icon_id   = isset( $section_data['icon'] ) ? absint( $section_data['icon'] ) : 0;
$icon_svg  = isset( $section_data['icon_svg'] ) ? (string) $section_data['icon_svg'] : '';

if ( $icon_id ) {
	$mime = get_post_mime_type( $icon_id );
	$path = get_attached_file( $icon_id );
	if ( $path && is_readable( $path ) && ( 'image/svg+xml' === $mime || preg_match( '/\.svg$/i', $path ) ) ) {
		$icon_html = kratom_feed_get_cta_icon_html( $icon_id, '', 'h-[27px] w-auto shrink-0' );
	} else {
		$url = wp_get_attachment_image_url( $icon_id, 'medium' );
		if ( $url ) {
			$icon_html = sprintf(
				'<img src="%s" alt="" class="h-[27px] w-auto shrink-0" width="55" height="27" loading="lazy" decoding="async" />',
				esc_url( $url )
			);
		}
	}
}
if ( ! $icon_html && $icon_svg ) {
	$icon_html = kratom_feed_get_cta_icon_html( 0, $icon_svg, 'h-[27px] w-auto shrink-0' );
}

$posts = kratom_feed_query_posts_by_category( $category_id, $count );
if ( empty( $posts ) ) {
	return;
}

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
?>
<section class="kf-posts-by-cat kf-posts-by-cat--<?php echo esc_attr( $style ); ?> container py-6 lg:py-6">
	<div class="flex flex-col items-start justify-between gap-8 sm:flex-row sm:items-center">
		<div class="flex min-w-0 flex-col gap-4">
			<div class="flex flex-wrap items-center gap-4">
				<h2 class="text-3xl font-semibold leading-tight text-neutral-900 sm:text-4xl">
					<?php echo esc_html( $title ); ?>
				</h2>
				<?php if ( $icon_html ) : ?>
					<span class="inline-flex shrink-0 items-center" aria-hidden="true"><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
			</div>
			<?php if ( $description ) : ?>
			<p class="max-w-2xl text-base leading-relaxed text-neutral-500">
				<?php echo esc_html( $description ); ?>
			</p>
			<?php endif; ?>
		</div>

		<?php if ( $show_button && $button_text && $button_href ) : ?>
		<a
			href="<?php echo esc_url( $button_href ); ?>"
			class="inline-flex h-12 shrink-0 items-center justify-center gap-2 rounded bg-pg-green px-6 text-base text-white transition-colors hover:bg-pg-green-dark"
		>
			<?php echo esc_html( $button_text ); ?>
			<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M13.8538 8.35378L9.35375 12.8538C9.25993 12.9476 9.13268 13.0003 9 13.0003C8.86732 13.0003 8.74007 12.9476 8.64625 12.8538C8.55243 12.76 8.49972 12.6327 8.49972 12.5C8.49972 12.3674 8.55243 12.2401 8.64625 12.1463L12.2931 8.50003H2.5C2.36739 8.50003 2.24021 8.44736 2.14645 8.35359C2.05268 8.25982 2 8.13264 2 8.00003C2 7.86743 2.05268 7.74025 2.14645 7.64648C2.24021 7.55271 2.36739 7.50003 2.5 7.50003H12.2931L8.64625 3.85378C8.55243 3.75996 8.49972 3.63272 8.49972 3.50003C8.49972 3.36735 8.55243 3.2401 8.64625 3.14628C8.74007 3.05246 8.86732 2.99976 9 2.99976C9.13268 2.99976 9.25993 3.05246 9.35375 3.14628L13.8538 7.64628C13.9002 7.69272 13.9371 7.74786 13.9623 7.80856C13.9874 7.86926 14.0004 7.93433 14.0004 8.00003C14.0004 8.06574 13.9874 8.13081 13.9623 8.1915C13.9371 8.2522 13.9002 8.30735 13.8538 8.35378Z" fill="white"/>
			</svg>
		</a>
		<?php endif; ?>
	</div>

	<div class="kf-posts-by-cat__swiper swiper mt-10 overflow-hidden">
		<div class="swiper-wrapper">
		<?php
		foreach ( $posts as $i => $post ) :
			$post_id   = $post->ID;
			$permalink = get_permalink( $post_id );
			$post_title = get_the_title( $post_id );
			$thumb     = get_the_post_thumbnail_url( $post_id, 'large' );
			if ( ! $thumb ) {
				$thumb = 'https://placehold.co/675x900/0b1a13/b8f04a?text=Kratom+Feed';
			}
			$category = kratom_feed_get_post_primary_category( $post_id );
			$variant  = ( 0 === $i % 4 ) ? 'light' : 'dark';
			$date     = get_the_date( '', $post_id );
			$read     = kratom_feed_reading_time( $post_id );
			$excerpt  = get_the_excerpt( $post_id );
			if ( ! $excerpt ) {
				$excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 18 );
			}
			?>
			<div class="swiper-slide">
			<?php if ( 'style_2' === $style ) : ?>
			<article class="kf-posts-by-cat__latest-card group">
				<div class="kf-posts-by-cat__latest-media">
					<a href="<?php echo esc_url( $permalink ); ?>" class="block h-full" aria-label="<?php echo esc_attr( $post_title ); ?>">
						<img
							src="<?php echo esc_url( $thumb ); ?>"
							alt="<?php echo esc_attr( $post_title ); ?>"
							class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
							loading="lazy"
							decoding="async"
						/>
					</a>
					<?php if ( $category ) : ?>
					<span class="kf-posts-by-cat__latest-category">
						<span class="h-1.5 w-1.5 rounded-full bg-pg-lime" aria-hidden="true"></span>
						<?php echo esc_html( $category ); ?>
					</span>
					<?php endif; ?>
				</div>

				<div class="kf-posts-by-cat__latest-content ">
					<div class="flex flex-wrap items-center gap-2.5 text-xs font-medium text-neutral-500">
						<time datetime="<?php echo esc_attr( get_the_date( 'c', $post_id ) ); ?>"><?php echo esc_html( $date ); ?></time>
						<span class="h-1 w-1 rounded-full bg-neutral-400" aria-hidden="true"></span>
						<span><?php echo esc_html( $read ); ?></span>
					</div>
					<h3 class="line-clamp-2 text-lg font-semibold leading-[1.35] text-neutral-900 ">
						<a href="<?php echo esc_url( $permalink ); ?>" class="transition-colors  card-title-black card-title ">
							<?php echo esc_html( $post_title ); ?>
						</a>
					</h3>
					<div class="mt-auto flex items-end gap-4">
						<?php if ( $excerpt ) : ?>
						<p class="line-clamp-2 min-w-0 flex-1 text-sm leading-relaxed text-neutral-500">
							<?php echo esc_html( $excerpt ); ?>
						</p>
						<?php endif; ?>
						<button
							type="button"
							class="kf-posts-by-cat__save"
							aria-label="<?php echo esc_attr( sprintf( __( 'Save %s', 'kratom-feed' ), $post_title ) ); ?>"
						>
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
								<path d="M6.75 4.75A1.75 1.75 0 0 1 8.5 3h7A1.75 1.75 0 0 1 17.25 4.75v15.1L12 16.65l-5.25 3.2V4.75Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
							</svg>
						</button>
					</div>
				</div>
			</article>
			<?php else : ?>
			<article class="group relative flex h-[400px] w-full flex-col overflow-hidden rounded-md sm:h-[420px] lg:h-[436px] hover-underline">
				<a href="<?php echo esc_url( $permalink ); ?>" class="absolute inset-0 z-[1]" aria-label="<?php echo esc_attr( $post_title ); ?>"></a>
				<img
					src="<?php echo esc_url( $thumb ); ?>"
					alt="<?php echo esc_attr( $post_title ); ?>"
					class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
					loading="lazy"
					decoding="async"
				/>
				<div class="absolute inset-0 bg-gradient-to-b from-black/0 to-black/[0.88]"></div>

				<?php if ( $category ) : ?>
				<div class="absolute right-4 top-4 z-[2] flex items-center gap-2 rounded-md border px-3 py-1.5 shadow-sm <?php echo 'light' === $variant ? 'border-neutral-300 bg-white' : 'border-neutral-900 bg-neutral-900'; ?>">
					<span class="h-1.5 w-1.5 rounded-full <?php echo 'light' === $variant ? 'bg-neutral-900' : 'bg-pg-lime'; ?>" aria-hidden="true"></span>
					<span class="text-xs font-medium leading-[18px] <?php echo 'light' === $variant ? 'text-neutral-900' : 'text-pg-lime'; ?>">
						<?php echo esc_html( $category ); ?>
					</span>
				</div>
				<?php endif; ?>

				<div class="relative z-[2] mt-auto flex flex-col gap-2 border-t border-white/90 px-6 pb-4 pt-4 ">
					<div class="flex flex-wrap items-center gap-2.5">
						<span class="text-xs font-medium leading-[14.4px] text-white"><?php echo esc_html( $date ); ?></span>
						<span class="flex items-center gap-1">
							<span class="h-1 w-1 rounded-full bg-[#686868]" aria-hidden="true"></span>
							<span class="text-xs font-medium leading-[14.4px] text-white"><?php echo esc_html( $read ); ?></span>
						</span>
					</div>
					<h3 class="line-clamp-2 text-lg font-semibold leading-[1.3] text-white ">
						<a href="<?php echo get_permalink( $post_id ); ?>" class="card-title"><?php echo esc_html( $post_title ); ?></a>
					</h3>
					<?php if ( $excerpt ) : ?>
					<p class="line-clamp-2 text-sm font-medium leading-[1.5] text-white">
						<a href="<?php echo get_permalink( $post_id ); ?>" ><?php echo esc_html( $post_title ); ?><?php echo esc_html( $excerpt ); ?></a>
					</p>
					<?php endif; ?>
				</div>
			</article>
			<?php endif; ?>
			</div>
		<?php endforeach; ?>
		</div>

	</div>
</section>
