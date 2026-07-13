<?php
/**
 * Builder: Trust & Reviews
 *
 * @package KratomFeeds
 * @var array $section_data
 */

$before    = $section_data['heading_before'] ?? 'Best Place to buy';
$highlight = $section_data['heading_highlight'] ?? 'Kratom Online';
$after     = $section_data['heading_after'] ?? 'in Canada';
$subtitle  = $section_data['subtitle'] ?? '';
$rating    = $section_data['rating'] ?? kratom_feed_get_option( 'google_rating', '4.8' );
$reviews_l = $section_data['reviews_label'] ?? kratom_feed_get_option( 'review_count', '1000 reviews' );
$reviews   = $section_data['reviews'] ?? array();
if ( empty( $reviews ) ) {
	return;
}
?>
<section class="bg-white py-14 lg:py-20">
	<div class="pg-container">
		<div class="flex flex-col gap-14 xl:flex-row xl:items-center xl:justify-between xl:gap-0">
			<div class="flex flex-col gap-4">
				<h1 class="text-[32px] font-semibold leading-tight text-gray-900 lg:text-5xl xl:max-w-lg">
					<?php echo esc_html( $before ); ?>
					<span class="text-green-500"><?php echo esc_html( $highlight ); ?></span>
					<?php echo esc_html( $after ); ?>
				</h1>
				<?php if ( $subtitle ) : ?>
					<p class="max-w-md text-sm text-neutral-500 lg:text-base"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<div class="xl:w-[35rem]">
				<div class="flex flex-col items-center gap-4 rounded-3xl bg-neutral-100 p-10 lg:p-14">
					<div class="flex items-center justify-center gap-3 rounded-full border border-neutral-200 bg-white px-4 py-3">
						<div class="flex items-center gap-2">
							<svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M21.8055 10.0415H21V10H12V14H17.6515C16.827 16.3285 14.6115 18 12 18C8.6865 18 6 15.3135 6 12C6 8.6865 8.6865 6 12 6C13.5295 6 14.921 6.577 15.9805 7.5195L18.809 4.691C17.023 3.0265 14.634 2 12 2C6.4775 2 2 6.4775 2 12C2 17.5225 6.4775 22 12 22C17.5225 22 22 17.5225 22 12C22 11.3295 21.931 10.675 21.8055 10.0415Z" fill="#FFC107"/><path d="M3.15295 7.3455L6.43845 9.755C7.32745 7.554 9.48045 6 12 6C13.5295 6 14.921 6.577 15.9805 7.5195L18.809 4.691C17.023 3.0265 14.634 2 12 2C8.15895 2 4.82795 4.1685 3.15295 7.3455Z" fill="#FF3D00"/><path d="M12 22C14.583 22 16.93 21.0115 18.7045 19.404L15.6095 16.785C14.5718 17.5742 13.3038 18.001 12 18C9.39903 18 7.19053 16.3415 6.35853 14.027L3.09753 16.5395C4.75253 19.778 8.11353 22 12 22Z" fill="#4CAF50"/><path d="M21.8055 10.0415H21V10H12V14H17.6515C17.2571 15.1082 16.5467 16.0766 15.608 16.7855L15.6095 16.7845L18.7045 19.4035C18.4855 19.6025 22 17 22 12C22 11.3295 21.931 10.675 21.8055 10.0415Z" fill="#1976D2"/></svg>
							<span class="font-medium text-gray-900">Google</span>
						</div>
						<div class="flex items-center gap-2 border-l border-neutral-200 pl-3">
							<svg class="h-[18px] w-[19px]" viewBox="0 0 19 18" fill="none" aria-hidden="true"><path d="M17.8203 8.4359L14.2969 11.5109L15.3524 16.089C15.4082 16.3284 15.3923 16.5788 15.3065 16.8092C15.2208 17.0395 15.0691 17.2394 14.8703 17.384C14.6716 17.5285 14.4346 17.6113 14.1891 17.6219C13.9436 17.6326 13.7004 17.5706 13.4899 17.4437L9.49689 15.0218L5.51252 17.4437C5.30202 17.5706 5.05881 17.6326 4.81328 17.6219C4.56775 17.6113 4.33079 17.5285 4.13204 17.384C3.9333 17.2394 3.78157 17.0395 3.69584 16.8092C3.6101 16.5788 3.59416 16.3284 3.65002 16.089L4.70392 11.5156L1.1797 8.4359C0.993305 8.27514 0.858519 8.06292 0.792249 7.82586C0.725978 7.5888 0.731173 7.33745 0.807183 7.10333C0.883193 6.86921 1.02663 6.66275 1.21952 6.50982C1.4124 6.3569 1.64614 6.26433 1.89142 6.24372L6.53674 5.84137L8.35002 1.51637C8.44471 1.28943 8.60443 1.09558 8.80907 0.95923C9.01371 0.822878 9.25411 0.750122 9.50002 0.750122C9.74592 0.750122 9.98633 0.822878 10.191 0.95923C10.3956 1.09558 10.5553 1.28943 10.65 1.51637L12.4688 5.84137L17.1125 6.24372C17.3578 6.26433 17.5915 6.3569 17.7844 6.50982C17.9773 6.66275 18.1207 6.86921 18.1968 7.10333C18.2728 7.33745 18.278 7.5888 18.2117 7.82586C18.1454 8.06292 18.0106 8.27514 17.8242 8.4359H17.8203Z" fill="#EAB308"/></svg>
							<strong class="text-gray-900"><?php echo esc_html( $rating ); ?></strong>
						</div>
					</div>
					<span class="text-sm text-neutral-500 lg:text-base"><?php esc_html_e( 'Based on', 'kratom-feed' ); ?> <a href="#" class="text-green-500 underline"><?php echo esc_html( $reviews_l ); ?></a></span>
				</div>
			</div>
		</div>

		<div id="reviews-carousel" class="pt-16 lg:pt-20">
			<div class="overflow-hidden">
				<div class="reviews-track flex gap-4 transition-transform duration-500">
					<?php foreach ( $reviews as $review ) : ?>
					<div class="review-slide min-w-full flex-shrink-0 sm:min-w-[calc(50%-0.5rem)] lg:min-w-[calc(33.333%-0.67rem)]">
						<div class="pg-review-card">
							<div class="flex items-center gap-2">
								<?php if ( ! empty( $review['avatar'] ) ) : ?>
									<img src="<?php echo esc_url( kratom_feed_image_url( $review['avatar'], 'thumbnail' ) ); ?>" alt="<?php echo esc_attr( $review['name'] ?? '' ); ?>" class="h-8 w-8 rounded-full object-cover" width="32" height="32" />
								<?php endif; ?>
								<span class="font-semibold text-gray-900"><?php echo esc_html( $review['name'] ?? '' ); ?></span>
								<div class="ml-auto flex items-center gap-1 rounded-sm border border-neutral-200 px-2 py-1">
									<svg class="h-4 w-4" viewBox="0 0 19 18" fill="none" aria-hidden="true"><path d="M17.8203 8.4359L14.2969 11.5109L15.3524 16.089C15.4082 16.3284 15.3923 16.5788 15.3065 16.8092C15.2208 17.0395 15.0691 17.2394 14.8703 17.384C14.6716 17.5285 14.4346 17.6113 14.1891 17.6219C13.9436 17.6326 13.7004 17.5706 13.4899 17.4437L9.49689 15.0218L5.51252 17.4437C5.30202 17.5706 5.05881 17.6326 4.81328 17.6219C4.56775 17.6113 4.33079 17.5285 4.13204 17.384C3.9333 17.2394 3.78157 17.0395 3.69584 16.8092C3.6101 16.5788 3.59416 16.3284 3.65002 16.089L4.70392 11.5156L1.1797 8.4359C0.993305 8.27514 0.858519 8.06292 0.792249 7.82586C0.725978 7.5888 0.731173 7.33745 0.807183 7.10333C0.883193 6.86921 1.02663 6.66275 1.21952 6.50982C1.4124 6.3569 1.64614 6.26433 1.89142 6.24372L6.53674 5.84137L8.35002 1.51637C8.44471 1.28943 8.60443 1.09558 8.80907 0.95923C9.01371 0.822878 9.25411 0.750122 9.50002 0.750122C9.74592 0.750122 9.98633 0.822878 10.191 0.95923C10.3956 1.09558 10.5553 1.28943 10.65 1.51637L12.4688 5.84137L17.1125 6.24372C17.3578 6.26433 17.5915 6.3569 17.7844 6.50982C17.9773 6.66275 18.1207 6.86921 18.1968 7.10333C18.2728 7.33745 18.278 7.5888 18.2117 7.82586C18.1454 8.06292 18.0106 8.27514 17.8242 8.4359H17.8203Z" fill="#EAB308"/></svg>
									<span class="text-sm font-medium">5</span>
								</div>
							</div>
							<p class="flex-1 text-sm leading-relaxed text-gray-600"><?php echo esc_html( $review['text'] ?? '' ); ?></p>
							<?php if ( ! empty( $review['date'] ) ) : ?>
								<time class="text-xs text-neutral-400"><?php echo esc_html( $review['date'] ); ?></time>
							<?php endif; ?>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php if ( count( $reviews ) > 1 ) : ?>
			<div class="mt-6 flex justify-center gap-2">
				<button type="button" class="review-prev flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 text-neutral-500 transition-colors hover:bg-neutral-100 cursor-pointer" aria-label="<?php esc_attr_e( 'Previous review', 'kratom-feed' ); ?>"><</button>
				<button type="button" class="review-next flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 text-neutral-500 transition-colors hover:bg-neutral-100 cursor-pointer" aria-label="<?php esc_attr_e( 'Next review', 'kratom-feed' ); ?>">></button>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>

