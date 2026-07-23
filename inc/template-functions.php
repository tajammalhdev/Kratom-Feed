<?php
/**
 * Template Functions
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get theme option with fallback.
 */
function kratom_feed_get_option( $key, $default = '' ) {
	if ( ! function_exists( 'carbon_get_theme_option' ) ) {
		return $default;
	}
	$value = carbon_get_theme_option( $key );
	return ( $value !== '' && $value !== null && $value !== false ) ? $value : $default;
}

/**
 * Get attachment image URL from Carbon image field.
 */
function kratom_feed_image_url( $image_id, $size = 'large' ) {
	if ( ! $image_id ) {
		return '';
	}
	$src = wp_get_attachment_image_src( $image_id, $size );
	return $src ? $src[0] : '';
}

/**
 * Allowed HTML for inline SVG icons from theme options.
 *
 * @return array
 */
function kratom_feed_allowed_svg_html() {
	$svg_attrs = array(
		'class'             => true,
		'id'                => true,
		'width'             => true,
		'height'            => true,
		'viewbox'           => true,
		'fill'              => true,
		'stroke'            => true,
		'stroke-width'      => true,
		'stroke-linecap'    => true,
		'stroke-linejoin'   => true,
		'stroke-dasharray'  => true,
		'stroke-dashoffset' => true,
		'xmlns'             => true,
		'xmlns:xlink'       => true,
		'aria-hidden'       => true,
		'role'              => true,
		'focusable'         => true,
		'style'             => true,
		'transform'         => true,
		'opacity'           => true,
		'clip-rule'         => true,
		'fill-rule'         => true,
		'fill-opacity'      => true,
		'stroke-opacity'    => true,
		'd'                 => true,
		'cx'                => true,
		'cy'                => true,
		'r'                 => true,
		'rx'                => true,
		'ry'                => true,
		'x'                 => true,
		'y'                 => true,
		'x1'                => true,
		'y1'                => true,
		'x2'                => true,
		'y2'                => true,
		'points'            => true,
		'dx'                => true,
		'dy'                => true,
		'offset'            => true,
		'stop-color'        => true,
		'stop-opacity'      => true,
		'gradientunits'     => true,
		'gradienttransform' => true,
		'xlink:href'        => true,
		'href'              => true,
		'clip-path'         => true,
		'mask'              => true,
		'filter'            => true,
	);

	return array(
		'svg'      => $svg_attrs,
		'g'        => $svg_attrs,
		'path'     => $svg_attrs,
		'circle'   => $svg_attrs,
		'ellipse'  => $svg_attrs,
		'rect'     => $svg_attrs,
		'line'     => $svg_attrs,
		'polyline' => $svg_attrs,
		'polygon'  => $svg_attrs,
		'defs'     => $svg_attrs,
		'clippath' => $svg_attrs,
		'mask'     => $svg_attrs,
		'use'      => $svg_attrs,
		'symbol'   => $svg_attrs,
		'title'    => array(),
		'desc'     => array(),
		'lineargradient' => $svg_attrs,
		'radialgradient' => $svg_attrs,
		'stop'     => $svg_attrs,
	);
}

/**
 * Sanitize SVG markup for safe front-end output.
 *
 * @param string $svg Raw SVG markup.
 * @return string
 */
function kratom_feed_sanitize_svg( $svg ) {
	$svg = trim( (string) $svg );
	if ( '' === $svg || false === stripos( $svg, '<svg' ) ) {
		return '';
	}
	// Strip XML/DOCTYPE and scripts.
	$svg = preg_replace( '/<\?xml.*?\?>/i', '', $svg );
	$svg = preg_replace( '/<!DOCTYPE.*?>/i', '', $svg );
	$svg = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $svg );
	$svg = preg_replace( '/on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $svg );
	return wp_kses( $svg, kratom_feed_allowed_svg_html() );
}

/**
 * Resolve a header CTA icon from upload ID and/or pasted SVG code.
 * Upload wins when present; SVG file contents are inlined when possible.
 *
 * @param int|string $attachment_id Carbon image field value.
 * @param string     $svg_code      Pasted SVG markup.
 * @param string     $class         Optional CSS class on wrapper/img.
 * @return string HTML (escaped/sanitized) or empty string.
 */
function kratom_feed_get_cta_icon_html( $attachment_id, $svg_code = '', $class = 'kf-sf-cta__icon h-4 w-4 shrink-0' ) {
	$attachment_id = absint( $attachment_id );

	if ( $attachment_id ) {
		$path = get_attached_file( $attachment_id );
		$mime = get_post_mime_type( $attachment_id );

		if ( $path && is_readable( $path ) && ( 'image/svg+xml' === $mime || 'image/svg' === $mime || preg_match( '/\.svg$/i', $path ) ) ) {
			$contents = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$sanitized = kratom_feed_sanitize_svg( $contents );
			if ( $sanitized ) {
				if ( $class && false === strpos( $sanitized, 'class=' ) ) {
					$sanitized = preg_replace( '/<svg\b/i', '<svg class="' . esc_attr( $class ) . '"', $sanitized, 1 );
				}
				return $sanitized;
			}
		}

		$url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
		if ( $url ) {
			$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
			return sprintf(
				'<img src="%s" alt="%s" class="%s" width="16" height="16" loading="lazy" decoding="async" />',
				esc_url( $url ),
				esc_attr( $alt ? $alt : '' ),
				esc_attr( $class )
			);
		}
	}

	$from_code = kratom_feed_sanitize_svg( $svg_code );
	if ( $from_code ) {
		if ( $class && false === strpos( $from_code, 'class=' ) ) {
			$from_code = preg_replace( '/<svg\b/i', '<svg class="' . esc_attr( $class ) . '"', $from_code, 1 );
		}
		return $from_code;
	}

	return '';
}

/**
 * Custom logo HTML for the header (no wrapping link — parent already links home).
 * Always outputs an <img>. SVG logos often lack size meta in WordPress, so we force
 * width/height attributes and rely on CSS for display size.
 *
 * @param string $class CSS classes for the logo element.
 * @return string
 */
function kratom_feed_get_custom_logo_html( $class = 'custom-logo h-9 w-auto max-h-9' ) {
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( ! $logo_id ) {
		return '';
	}

	$url = wp_get_attachment_image_url( $logo_id, 'full' );
	if ( ! $url ) {
		return '';
	}

	$alt = get_post_meta( $logo_id, '_wp_attachment_image_alt', true );
	if ( ! $alt ) {
		$alt = get_bloginfo( 'name' );
	}

	return sprintf(
		'<img src="%s" alt="%s" class="%s" width="180" height="36" decoding="async" />',
		esc_url( $url ),
		esc_attr( $alt ),
		esc_attr( $class )
	);
}

/**
 * Render page builder for current post.
 */
function kratom_feed_render_page_builder( $post_id = null ) {
	echo kratom_feed_get_builder_content( $post_id ?: get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Render builder sections for a post.
 *
 * @param int $post_id Page ID.
 * @return string
 */
function kratom_feed_render_builder_sections_for_post( $post_id ) {
	$post_id = absint( $post_id );
	if ( ! $post_id || ! function_exists( 'carbon_get_post_meta' ) ) {
		return '';
	}

	$sections = carbon_get_post_meta( $post_id, 'sections' );
	if ( empty( $sections ) || ! is_array( $sections ) ) {
		return '';
	}

	ob_start();
	echo '<div class="kf-builder  py-6 sm:py-8 lg:py-10">';
	foreach ( $sections as $section ) {
		$section_type = isset( $section['_type'] ) ? $section['_type'] : '';
		if ( ! $section_type ) {
			continue;
		}

		$template_path = locate_template( "template-parts/builder/{$section_type}.php" );
		if ( ! $template_path ) {
			continue;
		}

		$section_data = $section;
		include $template_path;
	}
	echo '</div>';

	return ob_get_clean();
}

/**
 * Get builder content with fallback to post content.
 *
 * @param int $post_id Page ID.
 * @return string
 */
function kratom_feed_get_builder_content( $post_id ) {
	$post_id = absint( $post_id );
	if ( ! $post_id ) {
		return '';
	}

	if ( function_exists( 'carbon_get_post_meta' ) && carbon_get_post_meta( $post_id, 'use_page_builder' ) ) {
		$output = kratom_feed_render_builder_sections_for_post( $post_id );
		if ( '' !== $output ) {
			return $output;
		}
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}

	return apply_filters( 'the_content', $post->post_content );
}

/**
 * Resolve posts for hero featured section.
 *
 * @param array $association Carbon association value.
 * @return WP_Post[]
 */
function kratom_feed_resolve_featured_posts( $association = array() ) {
	$ids = array();
	if ( ! empty( $association ) && is_array( $association ) ) {
		foreach ( $association as $item ) {
			if ( ! empty( $item['id'] ) ) {
				$ids[] = (int) $item['id'];
			}
		}
		$ids = array_values( array_unique( $ids ) );
	}

	if ( $ids ) {
		return get_posts(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'post__in'            => $ids,
				'orderby'             => 'post__in',
				'posts_per_page'      => count( $ids ),
				'ignore_sticky_posts' => true,
			)
		);
	}

	return get_posts(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 12,
			'ignore_sticky_posts' => true,
			'meta_query'          => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_is_featured',
					'value'   => 'yes',
					'compare' => '=',
				),
			),
		)
	);
}

/**
 * Compact number for engagement counts (e.g. 2200 -> 2.2k).
 *
 * @param int $number Count.
 * @return string
 */
function kratom_feed_format_count( $number ) {
	$number = absint( $number );
	if ( $number >= 1000000 ) {
		return rtrim( rtrim( number_format( $number / 1000000, 1 ), '0' ), '.' ) . 'm';
	}
	if ( $number >= 1000 ) {
		return rtrim( rtrim( number_format( $number / 1000, 1 ), '0' ), '.' ) . 'k';
	}
	return (string) $number;
}

/**
 * Get like count for a post.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function kratom_feed_get_like_count( $post_id ) {
	return absint( get_post_meta( absint( $post_id ), 'kf_like_count', true ) );
}

/**
 * Primary category label for a post.
 */
function kratom_feed_get_post_primary_category( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$cats    = get_the_category( $post_id );
	return ! empty( $cats ) ? $cats[0]->name : '';
}

/**
 * Format a number for compact display (e.g. 4200 -> 4.2k).
 */
function kratom_feed_compact_number( $number ) {
	$number = absint( $number );
	if ( $number >= 1000000 ) {
		return rtrim( rtrim( number_format( $number / 1000000, 1 ), '0' ), '.' ) . 'm';
	}
	if ( $number >= 1000 ) {
		return rtrim( rtrim( number_format( $number / 1000, 1 ), '0' ), '.' ) . 'k';
	}
	return (string) $number;
}

/**
 * Blog categories for Carbon select fields.
 *
 * @return array<string, string>
 */
function kratom_feed_get_blog_categories() {
	$choices = array( '' => __( 'All categories', 'kratom-feed' ) );
	$terms   = get_categories( array( 'hide_empty' => false ) );
	foreach ( $terms as $term ) {
		$choices[ (string) $term->term_id ] = $term->name;
	}
	return $choices;
}

/**
 * Query posts for the Posts by Category builder section.
 *
 * @param string|int $category_id Category term ID or empty.
 * @param int        $count       Number of posts.
 * @return WP_Post[]
 */
function kratom_feed_query_posts_by_category( $category_id = '', $count = 4 ) {
	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => max( 1, absint( $count ) ),
		'ignore_sticky_posts' => true,
	);
	if ( $category_id ) {
		$args['cat'] = absint( $category_id );
	}
	return get_posts( $args );
}

/**
 * Estimated reading time in minutes.
 */
function kratom_feed_reading_time( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	return sprintf(
		/* translators: %d: number of minutes */
		_n( '%d min read', '%d min read', $minutes, 'kratom-feed' ),
		$minutes
	);
}

/**
 * Build hierarchical nav tree for header drawer (WordPress menu or fallback).
 *
 * @return array<int, array{label:string,url:string,children:array}>
 */
function kratom_feed_get_nav_tree() {
	$tree = array();

	if ( has_nav_menu( 'primary' ) ) {
		$locations = get_nav_menu_locations();
		$menu_id   = $locations['primary'] ?? 0;
		$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : false;

		if ( $items ) {
			$by_parent = array();
			foreach ( $items as $item ) {
				$parent = (int) $item->menu_item_parent;
				if ( ! isset( $by_parent[ $parent ] ) ) {
					$by_parent[ $parent ] = array();
				}
				$by_parent[ $parent ][] = $item;
			}

			$build = function ( $parent_id ) use ( &$build, $by_parent ) {
				$branch = array();
				if ( empty( $by_parent[ $parent_id ] ) ) {
					return $branch;
				}
				foreach ( $by_parent[ $parent_id ] as $item ) {
					$branch[] = array(
						'label'    => $item->title,
						'url'      => $item->url,
						'children' => $build( (int) $item->ID ),
					);
				}
				return $branch;
			};

			return $build( 0 );
		}
	}

	return array(
		array(
			'label'    => __( 'Kratom 101', 'kratom-feed' ),
			'url'      => home_url( '/category/guides/' ),
			'children' => array(
				array( 'label' => __( 'What Is Kratom?', 'kratom-feed' ), 'url' => home_url( '/category/guides/' ), 'children' => array() ),
				array( 'label' => __( 'Kratom Dosage', 'kratom-feed' ), 'url' => home_url( '/category/dosage/' ), 'children' => array() ),
				array( 'label' => __( 'Kratom Guides', 'kratom-feed' ), 'url' => home_url( '/category/guides/' ), 'children' => array() ),
			),
		),
		array(
			'label'    => __( 'Strains', 'kratom-feed' ),
			'url'      => home_url( '/category/strains/' ),
			'children' => array(
				array( 'label' => __( 'Red Vein', 'kratom-feed' ), 'url' => home_url( '/category/red-vein/' ), 'children' => array() ),
				array( 'label' => __( 'Green Vein', 'kratom-feed' ), 'url' => home_url( '/category/green-vein/' ), 'children' => array() ),
				array( 'label' => __( 'White Vein', 'kratom-feed' ), 'url' => home_url( '/category/white-vein/' ), 'children' => array() ),
			),
		),
		array(
			'label'    => __( 'Vendor Reviews', 'kratom-feed' ),
			'url'      => home_url( '/category/reviews/' ),
			'children' => array(
				array( 'label' => __( 'All Reviews', 'kratom-feed' ), 'url' => home_url( '/category/reviews/' ), 'children' => array() ),
			),
		),
		array( 'label' => __( 'Research', 'kratom-feed' ), 'url' => home_url( '/category/research/' ), 'children' => array() ),
		array( 'label' => __( 'News', 'kratom-feed' ), 'url' => home_url( '/category/news/' ), 'children' => array() ),
		array( 'label' => __( 'About', 'kratom-feed' ), 'url' => get_permalink( get_page_by_path( 'about' ) ) ?: home_url( '/about/' ), 'children' => array() ),
	);
}

/**
 * Primary nav fallback links (legacy helper).
 */
function kratom_feed_primary_nav_fallback() {
	foreach ( kratom_feed_get_nav_tree() as $link ) {
		if ( empty( $link['url'] ) ) {
			continue;
		}
		printf(
			'<a href="%s" class="whitespace-nowrap text-[15px] font-medium text-gray-900 transition-colors hover:text-black cursor-pointer">%s</a>',
			esc_url( $link['url'] ),
			esc_html( $link['label'] )
		);
	}
}

/**
 * Render primary navigation (inline header links).
 */
function kratom_feed_primary_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		return;
	}
	kratom_feed_primary_nav_fallback();
}

/**
 * Custom comment markup for Tailwind-styled comment lists.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    wp_list_comments() args.
 * @param int        $depth   Current nesting depth.
 */
function kratom_feed_comment_callback( $comment, $args, $depth ) {
	$tag         = ( 'div' === $args['style'] ) ? 'div' : 'li';
	$avatar_size = isset( $args['avatar_size'] ) ? (int) $args['avatar_size'] : 48;
	if ( $depth > 1 ) {
		$avatar_size = max( 36, (int) round( $avatar_size * 0.75 ) );
	}
	?>
	<<?php echo esc_html( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $depth > 1 ? 'kf-comment kf-comment--reply' : 'kf-comment', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="flex gap-4  bg-white p-4  sm:p-5">
			<div class="shrink-0">
				<?php
				echo get_avatar(
					$comment,
					$avatar_size,
					'',
					'',
					array(
						'class' => 'object-cover ',
					)
				);
				?>
			</div>

			<div class="min-w-0 flex-1">
				<header class="mb-2 flex flex-wrap items-baseline gap-x-2 gap-y-1">
					<?php
					printf(
						'<cite class="not-italic text-sm font-bold text-gray-900 [&_a]:text-gray-900 [&_a]:no-underline hover:[&_a]:text-pg-green-dark">%s</cite>',
						get_comment_author_link( $comment )
					);
					?>
					<a
						href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>"
						class="text-xs font-medium uppercase tracking-wide text-gray-400 transition-colors hover:text-pg-green-dark"
					>
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php echo esc_html( get_comment_date( '', $comment ) ); ?>
						</time>
					</a>
				</header>

				<?php if ( '0' === (string) $comment->comment_approved ) : ?>
				<p class="mb-2 rounded-lg bg-amber-50 px-3 py-2 text-sm font-medium text-amber-800">
					<?php esc_html_e( 'Your comment is awaiting moderation.', 'kratom-feed' ); ?>
				</p>
				<?php endif; ?>

				<div class="text-[15px] leading-relaxed text-gray-600 [&_a]:font-medium [&_a]:text-pg-green-dark [&_a]:underline [&_a]:underline-offset-2 hover:[&_a]:text-pg-green [&_p]:mb-3 [&_p:last-child]:mb-0">
					<?php comment_text(); ?>
				</div>

				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below'  => 'div-comment',
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'before'     => '<div class="reply mt-3">',
							'after'      => '</div>',
							'reply_text' => __( 'Reply', 'kratom-feed' ),
						)
					)
				);
				?>
			</div>
		</article>
	<?php
	// Closing </li>/</div> is handled by Walker_Comment::end_el after nested children.
}

/**
 * Style WordPress comment reply links with Tailwind classes.
 *
 * @param string $link Reply link HTML.
 * @return string
 */
function kratom_feed_comment_reply_link_class( $link ) {
	if ( ! $link ) {
		return $link;
	}
	return preg_replace(
		'/class=[\'"]([^\'"]*)[\'"]/',
		'class="$1 text-xs font-bold uppercase tracking-[0.12em] text-pg-green-dark underline underline-offset-4 transition-colors hover:text-pg-green"',
		$link,
		1
	);
}
add_filter( 'comment_reply_link', 'kratom_feed_comment_reply_link_class' );

