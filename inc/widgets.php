<?php
/**
 * Themed sidebar widgets — classic widgets + block widgets (WP 5.8+).
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register themed replacements for classic core widgets.
 */
function kratom_feed_register_widgets() {
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Recent_Comments' );

	register_widget( 'Kratom_Feed_Widget_Search' );
	register_widget( 'Kratom_Feed_Widget_Recent_Posts' );
	register_widget( 'Kratom_Feed_Widget_Recent_Comments' );
}
add_action( 'widgets_init', 'kratom_feed_register_widgets', 20 );

/**
 * Whether the blog sidebar is currently rendering.
 *
 * @return bool
 */
function kratom_feed_is_blog_sidebar_rendering() {
	global $_sidebar_being_rendered;
	return isset( $_sidebar_being_rendered ) && 'sidebar-blog' === $_sidebar_being_rendered;
}

/**
 * Search placeholder from theme options.
 *
 * @return string
 */
function kratom_feed_sidebar_search_placeholder() {
	if ( function_exists( 'kratom_feed_get_option' ) ) {
		return (string) kratom_feed_get_option( 'header_search_placeholder', __( 'What Are You Looking For?', 'kratom-feed' ) );
	}
	return __( 'What Are You Looking For?', 'kratom-feed' );
}

/**
 * Themed search form markup (shared by classic + block widgets).
 *
 * @param string $field_id    Input id.
 * @param string $placeholder Placeholder text.
 * @param string $title       Optional heading (defaults to Search).
 * @return string
 */
function kratom_feed_get_sidebar_search_form_html( $field_id, $placeholder = '', $title = '' ) {
	if ( '' === $placeholder ) {
		$placeholder = kratom_feed_sidebar_search_placeholder();
	}
	if ( '' === $title ) {
		$title = __( 'Search', 'kratom-feed' );
	}

	ob_start();
	?>
	<div class="kf-sidebar-search">
		<div class="mb-3 flex items-center gap-2">
			<svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1" fill="none" xmlns="http://www.w3.org/2000/svg" color="#42b251"><path d="M17 17L21 21" stroke="#42b251" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M3 11C3 15.4183 6.58172 19 11 19C13.213 19 15.2161 18.1015 16.6644 16.6493C18.1077 15.2022 19 13.2053 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11Z" stroke="#42b251" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path></svg>
			<span class="text-base font-bold text-neutral-900"><?php echo esc_html( $title ); ?></span>
		</div>

		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-stretch gap-2">
			<label class="sr-only" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $title ); ?></label>
			<input
				type="search"
				id="<?php echo esc_attr( $field_id ); ?>"
				name="s"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				class="sidebar-search-input min-w-0 flex-1 rounded-md border border-neutral-300 bg-white px-3 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 outline-none transition-colors focus:border-pg-green focus:ring-2 focus:ring-pg-green/15"
			/>
			<button
				type="submit"
				class="inline-flex shrink-0 items-center justify-center rounded-md bg-neutral-400 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-pg-hover"
			>
				<?php esc_html_e( 'Search', 'kratom-feed' ); ?>
			</button>
		</form>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Themed recent posts list HTML.
 *
 * @param int $number Number of posts.
 * @return string
 */
function kratom_feed_get_sidebar_recent_posts_html( $number = 3 ) {
	$number = max( 1, (int) $number );

	$query = new WP_Query(
		array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'post__not_in'        => is_singular( 'post' ) ? array( get_the_ID() ) : array(),
		)
	);

	if ( ! $query->have_posts() ) {
		return '';
	}

	ob_start();
	?>
	<ul class="kf-sidebar-posts m-0 list-none p-0">
		<?php
		foreach ( $query->posts as $recent_post ) :
			$post_id    = $recent_post->ID;
			$permalink  = get_permalink( $post_id );
			$post_title = get_the_title( $post_id );
			$thumbnail  = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
			$date       = get_the_date( 'M j, Y', $post_id );
			$read_time  = function_exists( 'kratom_feed_reading_time' ) ? kratom_feed_reading_time( $post_id ) : '';
			?>
			<li class="border-b border-neutral-300 py-5 first:pt-0 last:border-b-0 last:pb-0">
				<a href="<?php echo esc_url( $permalink ); ?>" class="group flex items-center gap-4">
					<div class="min-w-0 flex-1">
						<h3 class="line-clamp-2 text-base font-semibold leading-tight text-neutral-900 transition-colors group-hover:text-pg-hover">
							<?php echo esc_html( $post_title ); ?>
						</h3>
						<div class="mt-2 flex flex-wrap items-center gap-1 text-[11px] font-medium text-neutral-500">
							<span>• <?php echo esc_html( $date ); ?></span>
							<?php if ( $read_time ) : ?>
							<span>• <?php echo esc_html( $read_time ); ?></span>
							<?php endif; ?>
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
	<?php
	wp_reset_postdata();

	return (string) ob_get_clean();
}

/**
 * Themed recent comments list HTML.
 *
 * @param int $number Number of comments.
 * @return string
 */
function kratom_feed_get_sidebar_recent_comments_html( $number = 4 ) {
	$number = max( 1, (int) $number );

	$comments = get_comments(
		array(
			'number'      => $number,
			'status'      => 'approve',
			'post_status' => 'publish',
			'type'        => 'comment',
		)
	);

	if ( empty( $comments ) ) {
		return '';
	}

	ob_start();
	?>
	<ul class="kf-sidebar-comments m-0 list-none p-0">
		<?php foreach ( $comments as $comment ) : ?>
			<?php
			$comment_link = get_comment_link( $comment );
			$author_name  = get_comment_author( $comment );
			$comment_date = get_comment_date( 'M j, Y', $comment );
			$excerpt      = wp_trim_words( wp_strip_all_tags( $comment->comment_content ), 14, '…' );
			$post_title   = get_the_title( $comment->comment_post_ID );
			$avatar       = get_avatar(
				$comment,
				64,
				'',
				'',
				array(
					'class' => 'h-full w-full object-cover',
				)
			);
			?>
			<li class="border-b border-neutral-300 py-5 first:pt-0 last:border-b-0 last:pb-0">
				<a href="<?php echo esc_url( $comment_link ); ?>" class="group flex items-start gap-4">
					<span class="h-12 w-12 shrink-0 overflow-hidden rounded-full bg-pg-gray-light ring-2 ring-pg-green-light">
						<?php echo $avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<div class="min-w-0 flex-1">
						<p class="line-clamp-2 text-sm font-semibold leading-snug text-neutral-900 transition-colors group-hover:text-pg-hover">
							<?php echo esc_html( $excerpt ); ?>
						</p>
						<div class="mt-2 flex flex-wrap items-center gap-1 text-[11px] font-medium text-neutral-500">
							<span>• <?php echo esc_html( $author_name ); ?></span>
							<span>• <?php echo esc_html( $comment_date ); ?></span>
						</div>
						<?php if ( $post_title ) : ?>
						<p class="mt-1.5 line-clamp-1 text-[11px] font-medium text-neutral-400">
							<?php
							printf(
								/* translators: %s: post title */
								esc_html__( 'on %s', 'kratom-feed' ),
								esc_html( $post_title )
							);
							?>
						</p>
						<?php endif; ?>
					</div>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
	return (string) ob_get_clean();
}

/**
 * Style block-based Search / Latest Posts / Latest Comments in Blog Sidebar.
 *
 * @param string   $block_content Rendered block HTML.
 * @param array    $block         Parsed block.
 * @param WP_Block $instance      Block instance.
 * @return string
 */
function kratom_feed_render_sidebar_blocks( $block_content, $block, $instance = null ) {
	if ( is_admin() || empty( $block['blockName'] ) ) {
		return $block_content;
	}

	$attrs     = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
	$in_sidebar = kratom_feed_is_blog_sidebar_rendering();

	// Always theme Search blocks on the frontend (widget Search uses the block editor).
	if ( 'core/search' === $block['blockName'] ) {
		$field_id    = 'sidebar-search-' . uniqid();
		$placeholder = ! empty( $attrs['placeholder'] ) ? $attrs['placeholder'] : kratom_feed_sidebar_search_placeholder();
		$label       = ! empty( $attrs['label'] ) ? $attrs['label'] : __( 'Search', 'kratom-feed' );

		return kratom_feed_get_sidebar_search_form_html( $field_id, $placeholder, $label );
	}

	if ( ! $in_sidebar ) {
		return $block_content;
	}

	switch ( $block['blockName'] ) {
		case 'core/latest-posts':
			$number = ! empty( $attrs['postsToShow'] ) ? absint( $attrs['postsToShow'] ) : 3;
			$html   = kratom_feed_get_sidebar_recent_posts_html( $number );
			return $html ? $html : $block_content;

		case 'core/latest-comments':
			$number = ! empty( $attrs['commentsToShow'] ) ? absint( $attrs['commentsToShow'] ) : 4;
			$html   = kratom_feed_get_sidebar_recent_comments_html( $number );
			return $html ? $html : $block_content;
	}

	return $block_content;
}
add_filter( 'render_block', 'kratom_feed_render_sidebar_blocks', 10, 3 );

/**
 * Backup: restyle Search / Latest Posts / Latest Comments from block widgets.
 *
 * @param string          $content  Widget HTML.
 * @param array           $instance Widget instance.
 * @param WP_Widget_Block $widget   Widget object.
 * @return string
 */
function kratom_feed_filter_widget_block_content( $content, $instance, $widget ) {
	if ( is_admin() || ! is_string( $content ) ) {
		return $content;
	}

	// Already themed.
	if ( false !== strpos( $content, 'kf-sidebar-search' ) || false !== strpos( $content, 'kf-sidebar-posts' ) || false !== strpos( $content, 'kf-sidebar-comments' ) ) {
		return $content;
	}

	$title = '';
	if ( preg_match( '/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', $content, $match ) ) {
		$title = wp_strip_all_tags( $match[1] );
	}

	if ( false !== strpos( $content, 'wp-block-search' ) ) {
		if ( ! $title && preg_match( '/wp-block-search__label[^>]*>(.*?)<\//is', $content, $match ) ) {
			$title = wp_strip_all_tags( $match[1] );
		}
		return kratom_feed_get_sidebar_search_form_html(
			'sidebar-search-widget-' . uniqid(),
			'',
			$title ? $title : __( 'Search', 'kratom-feed' )
		);
	}

	if ( false !== strpos( $content, 'wp-block-latest-posts' ) ) {
		$html = '';
		if ( $title ) {
			$html .= '<h2 class="mb-4 text-sm font-bold uppercase tracking-[0.14em] text-neutral-900">' . esc_html( $title ) . '</h2>';
		}
		$list = kratom_feed_get_sidebar_recent_posts_html( 3 );
		return $list ? $html . $list : $content;
	}

	if ( false !== strpos( $content, 'wp-block-latest-comments' ) ) {
		$html = '';
		if ( $title ) {
			$html .= '<h2 class="mb-4 text-sm font-bold uppercase tracking-[0.14em] text-neutral-900">' . esc_html( $title ) . '</h2>';
		}
		$list = kratom_feed_get_sidebar_recent_comments_html( 4 );
		return $list ? $html . $list : $content;
	}

	return $content;
}
add_filter( 'widget_block_content', 'kratom_feed_filter_widget_block_content', 20, 3 );

/**
 * Themed Search widget (classic).
 */
class Kratom_Feed_Widget_Search extends WP_Widget_Search {

	/**
	 * Output the widget.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Search', 'kratom-feed' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];

		echo kratom_feed_get_sidebar_search_form_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$this->id . '-field',
			'',
			$title ? $title : __( 'Search', 'kratom-feed' )
		);

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget'];
	}
}

/**
 * Themed Recent Posts widget (classic).
 */
class Kratom_Feed_Widget_Recent_Posts extends WP_Widget_Recent_Posts {

	/**
	 * Output the widget.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts', 'kratom-feed' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3;
		$html   = kratom_feed_get_sidebar_recent_posts_html( $number ? $number : 3 );

		if ( ! $html ) {
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];

		if ( $title ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget'];
	}
}

/**
 * Themed Recent Comments widget (classic).
 */
class Kratom_Feed_Widget_Recent_Comments extends WP_Widget_Recent_Comments {

	/**
	 * Output the widget.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Widget settings.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Comments', 'kratom-feed' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 4;
		$html   = kratom_feed_get_sidebar_recent_comments_html( $number ? $number : 4 );

		if ( ! $html ) {
			return;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];

		if ( $title ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget'];
	}
}
