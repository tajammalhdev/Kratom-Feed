<?php
/**
 * Builder: Hero Featured carousel
 *
 * @package KratomFeeds
 *
 * @var array $section_data Section fields from Carbon.
 */

$show_badge   = ! empty( $section_data['show_live_badge'] );
$badge_text   = ! empty( $section_data['live_badge_text'] ) ? $section_data['live_badge_text'] : __( 'Live Updates', 'kratom-feed' );
$autoplay     = ! empty( $section_data['autoplay'] );
$autoplay_ms  = isset( $section_data['autoplay_ms'] ) ? max( 2000, absint( $section_data['autoplay_ms'] ) ) : 6000;
$association  = isset( $section_data['posts'] ) && is_array( $section_data['posts'] ) ? $section_data['posts'] : array();
$posts        = kratom_feed_resolve_featured_posts( $association );

if ( empty( $posts ) ) {
	return;
}

$slide_count = count( $posts );
$is_carousel = $slide_count > 1;
?>
<section
	class="kf-hero-featured container flex flex-col gap-6"
	aria-label="<?php esc_attr_e( 'Featured stories', 'kratom-feed' ); ?>"
	data-autoplay="<?php echo $autoplay && $is_carousel ? '1' : '0'; ?>"
	data-autoplay-ms="<?php echo esc_attr( (string) $autoplay_ms ); ?>"
>
	<div class="kf-hero-carousel relative overflow-hidden rounded-[10px]">
		<div class="kf-hero-carousel__track flex" data-hero-track>
			<?php foreach ( $posts as $index => $post ) : ?>
				<?php
				$post_id   = $post->ID;
				$permalink = get_permalink( $post_id );
				$title     = get_the_title( $post_id );
				$thumb     = get_the_post_thumbnail_url( $post_id, 'full' );
				if ( ! $thumb ) {
					$thumb = 'https://placehold.co/1280x601/0b1a13/b8f04a?text=Kratom+Feed';
				}
				$category  = kratom_feed_get_post_primary_category( $post_id );
				$likes     = kratom_feed_get_like_count( $post_id );
				$comments  = (int) get_comments_number( $post_id );
				$liked     = ! empty( $_COOKIE[ 'kf_liked_' . $post_id ] );
				?>
				<article
					class="kf-hero-carousel__slide relative aspect-[4/5] w-full shrink-0 overflow-hidden sm:aspect-[16/10] md:aspect-[1280/601]"
					data-hero-slide
					aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>"
				>
					<img
						src="<?php echo esc_url( $thumb ); ?>"
						alt="<?php echo esc_attr( $title ); ?>"
						class="absolute inset-0 h-full w-full object-cover"
						loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>"
						decoding="async"
					/>
					<div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/90 to-transparent"></div>

					<?php if ( $show_badge ) : ?>
					<div class="absolute left-5 top-5 flex items-center gap-2 rounded bg-neutral-900 px-3 py-2">
						<span class="h-2 w-2 animate-pulse rounded-full bg-red-500" aria-hidden="true"></span>
						<span class="text-xs font-bold text-red-200"><?php echo esc_html( $badge_text ); ?></span>
					</div>
					<?php endif; ?>

					<div class="absolute inset-0 flex flex-col justify-end top-1/2 -translate-y-1/2 left-[40px]">
						<div class="max-w-xl">
							<?php if ( $category ) : ?>
							<div class="mb-3 inline-flex h-7 items-center gap-2 rounded bg-neutral-900 px-2">
								<svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M9.56636 8.10031C9.53678 8.14974 9.49773 8.19283 9.45145 8.22713C9.40518 8.26143 9.35259 8.28625 9.29669 8.30017C9.2408 8.31409 9.18271 8.31684 9.12576 8.30826C9.0688 8.29968 9.0141 8.27993 8.96479 8.25016L5.2537 6.02273V10.0625C5.2537 10.1785 5.20761 10.2898 5.12556 10.3719C5.04351 10.4539 4.93223 10.5 4.8162 10.5C4.70017 10.5 4.58889 10.4539 4.50684 10.3719C4.42479 10.2898 4.3787 10.1785 4.3787 10.0625V6.02273L0.666511 8.25016C0.617226 8.28043 0.562426 8.30064 0.505281 8.3096C0.448136 8.31856 0.389782 8.3161 0.333594 8.30236C0.277406 8.28863 0.2245 8.26388 0.177936 8.22957C0.131373 8.19525 0.0920774 8.15204 0.0623227 8.10243C0.032568 8.05283 0.0129449 7.99782 0.00458899 7.94058C-0.00376696 7.88335 -0.000689814 7.82502 0.0136421 7.76898C0.027974 7.71294 0.0532762 7.6603 0.0880849 7.6141C0.122894 7.56791 0.166518 7.52907 0.216433 7.49984L3.96581 5.25L0.216433 3.00016C0.166518 2.97093 0.122894 2.93209 0.0880849 2.8859C0.0532762 2.8397 0.027974 2.78706 0.0136421 2.73102C-0.000689814 2.67498 -0.00376696 2.61665 0.00458899 2.55942C0.0129449 2.50218 0.032568 2.44717 0.0623227 2.39757C0.0920774 2.34796 0.131373 2.30475 0.177936 2.27043C0.2245 2.23612 0.277406 2.21137 0.333594 2.19763C0.389782 2.1839 0.448136 2.18144 0.505281 2.1904C0.562426 2.19936 0.617226 2.21957 0.666511 2.24984L4.3787 4.47727V0.4375C4.3787 0.321468 4.42479 0.210188 4.50684 0.128141C4.58889 0.0460937 4.70017 0 4.8162 0C4.93223 0 5.04351 0.0460937 5.12556 0.128141C5.20761 0.210188 5.2537 0.321468 5.2537 0.4375V4.47727L8.96589 2.24984C9.01517 2.21957 9.06997 2.19936 9.12712 2.1904C9.18426 2.18144 9.24262 2.1839 9.2988 2.19763C9.35499 2.21137 9.4079 2.23612 9.45446 2.27043C9.50103 2.30475 9.54032 2.34796 9.57008 2.39757C9.59983 2.44717 9.61945 2.50218 9.62781 2.55942C9.63616 2.61665 9.63309 2.67498 9.61876 2.73102C9.60442 2.78706 9.57912 2.8397 9.54431 2.8859C9.5095 2.93209 9.46588 2.97093 9.41597 3.00016L5.66659 5.25L9.41597 7.49984C9.46529 7.52938 9.50831 7.56834 9.54256 7.61451C9.57682 7.66068 9.60164 7.71314 9.61561 7.76891C9.62957 7.82468 9.63241 7.88265 9.62396 7.93951C9.61551 7.99638 9.59594 8.05102 9.56636 8.10031Z" fill="#84CC16"/>
								</svg>
								<span class="text-xs font-semibold uppercase text-lime-500"><?php echo esc_html( $category ); ?></span>
							</div>
							<?php endif; ?>

							<h2 class="text-3xl font-semibold leading-tight text-white sm:text-4xl md:text-5xl hover-underline">
								<a href="<?php echo esc_url( $permalink ); ?>" class="card-title">
									<?php echo esc_html( $title ); ?>
								</a>
							</h2>

							<div class="mt-6 flex justify-start gap-5">
								<button
									type="button"
									class="kf-eng-like flex items-center gap-2 cursor-pointer"
									data-post-id="<?php echo esc_attr( (string) $post_id ); ?>"
									aria-pressed="<?php echo $liked ? 'true' : 'false'; ?>"
									aria-label="<?php esc_attr_e( 'Like this post', 'kratom-feed' ); ?>"
								>
									<span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/90">
										<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9.04004 9.35193C10.0619 8.51559 11.4396 8.73669 12.2969 9.69666C12.3917 9.80283 12.5275 9.86365 12.6699 9.86365C12.8123 9.86365 12.9481 9.80283 13.043 9.69666C13.8965 8.74089 15.2559 8.52123 16.3047 9.35583C17.36 10.196 17.4908 11.6 16.6934 12.6058L12.6699 16.3314L8.64551 12.6058C7.8534 11.6058 7.99429 10.2078 9.04004 9.35193Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
											<defs>
											<clipPath id="bgblur_0_4796_1122_clip_path" transform="translate(20 20)"><circle cx="12.5" cy="12.5" r="12.5"/>
											</clipPath></defs>
										</svg>
									</span>
									<span class="kf-eng-like__count text-xs text-white"><?php echo esc_html( kratom_feed_format_count( $likes ) ); ?></span>
								</button>

								<a
									href="<?php echo esc_url( $permalink . '#comments' ); ?>"
									class="flex items-center gap-2"
									aria-label="<?php esc_attr_e( 'View comments', 'kratom-feed' ); ?>"
								>
									<span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/90">

										<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
											<g clip-path="url(#clip1_4796_1127)">
											<path d="M6.85938 8.14062C6.85938 7.24316 7.58691 6.51562 8.48438 6.51562H17.0156C17.9131 6.51562 18.6406 7.24316 18.6406 8.14062V14.2344C18.6406 15.1319 17.9131 15.8594 17.0156 15.8594H14.8828L12.75 18.2969L10.6172 15.8594H8.48438C7.58691 15.8594 6.85938 15.1319 6.85938 14.2344V8.14062Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M10.7188 11.5938C10.7188 11.8181 10.5369 12 10.3125 12C10.0881 12 9.90625 11.8181 9.90625 11.5938C9.90625 11.3694 10.0881 11.1875 10.3125 11.1875C10.5369 11.1875 10.7188 11.3694 10.7188 11.5938Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M13.1562 11.5938C13.1562 11.8181 12.9743 12 12.75 12C12.5257 12 12.3438 11.8181 12.3438 11.5938C12.3438 11.3694 12.5257 11.1875 12.75 11.1875C12.9743 11.1875 13.1562 11.3694 13.1562 11.5938Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M15.5938 11.5938C15.5938 11.8181 15.4118 12 15.1875 12C14.9632 12 14.7812 11.8181 14.7812 11.5938C14.7812 11.3694 14.9632 11.1875 15.1875 11.1875C15.4118 11.1875 15.5938 11.3694 15.5938 11.5938Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
											</g>
											<defs>
											<clipPath id="bgblur_0_4796_1127_clip_path" transform="translate(20 20)"><circle cx="12.5" cy="12.5" r="12"/>
											</clipPath><clipPath id="clip1_4796_1127">
											<rect width="13" height="13.8125" fill="white" transform="translate(6.25 5.5)"/>
											</clipPath>
											</defs>
										</svg>
									</span>
									<span class="text-xs text-white"><?php echo esc_html( kratom_feed_format_count( $comments ) ); ?></span>
								</a>

								<button
									type="button"
									class="kf-eng-share flex h-7 w-7 items-center justify-center rounded-full bg-white/90 cursor-pointer"
									data-share-url="<?php echo esc_url( $permalink ); ?>"
									data-share-title="<?php echo esc_attr( $title ); ?>"
									aria-label="<?php esc_attr_e( 'Share this post', 'kratom-feed' ); ?>"
								>

								<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M7.87061 14.8148L7.87061 15.3935C7.87061 16.3524 8.64789 17.1296 9.60672 17.1296L15.3938 17.1296C16.3526 17.1296 17.1299 16.3524 17.1299 15.3935L17.1299 14.8148M10.1854 10.1852L12.5002 7.87038L14.815 10.1852M12.5002 7.87038L12.5002 14.8148" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
									<defs>
									<clipPath id="bgblur_0_4796_1139_clip_path" transform="translate(20 20)"><circle cx="12.5" cy="12.5" r="12.5"/>
									</clipPath></defs>
								</svg>
								</button>

								<span
									class="flex h-7 w-7 items-center justify-center rounded-full bg-white/90 opacity-60"
									title="<?php esc_attr_e( 'Save coming soon', 'kratom-feed' ); ?>"
									aria-hidden="true"
								>
									<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
										<g clip-path="url(#clip1_4796_1135)">
										<path d="M7.37012 8.67466C7.37012 7.65943 8.24914 6.83643 9.33347 6.83643H15.7144C16.7987 6.83643 17.6777 7.65943 17.6777 8.67466V20.1636L12.5239 16.0276L7.37012 20.1636V8.67466Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
										</g>
										<defs>
										<clipPath id="bgblur_0_4796_1135_clip_path" transform="translate(20 20)"><circle cx="12.5" cy="12.5" r="12.5"/>
										</clipPath><clipPath id="clip1_4796_1135">
										<rect width="12" height="15" fill="white" transform="translate(6.25 6)"/>
										</clipPath>
										</defs>
									</svg>								
								</span>
							</div>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>

	
</section>
