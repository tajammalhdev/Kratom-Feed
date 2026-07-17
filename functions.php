<?php
/**
 * Kratom Feed Theme Functions
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KRATOM_FEED_VERSION', '1.0.0' );
define( 'KRATOM_FEED_DIR', get_template_directory() );
define( 'KRATOM_FEED_URI', get_template_directory_uri() );

/**
 * Theme prefix helper.
 */
function kratom_feed_prefix() {
	return 'kratom-feed';
}

/**
 * Theme setup.
 */
function kratom_feed_setup() {
	load_theme_textdomain( 'kratom-feed', KRATOM_FEED_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );

	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Navigation', 'kratom-feed' ),
		'footer'  => esc_html__( 'Footer Menu', 'kratom-feed' ),
	) );

	$GLOBALS['content_width'] = 1320;
}
add_action( 'after_setup_theme', 'kratom_feed_setup' );

/**
 * Register widget areas.
 */
function kratom_feed_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'kratom-feed' ),
		'id'            => 'sidebar-blog',
		'description'   => esc_html__( 'Sidebar on archive and single posts.', 'kratom-feed' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s mb-8">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="text-xs font-bold uppercase tracking-[0.15em] text-pg-lime mb-4">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'kratom_feed_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kratom_feed_scripts() {
	$prefix = kratom_feed_prefix();

	wp_enqueue_style(
		$prefix . '-fonts',
		'https://fonts.googleapis.com/css2?family=Geist:ital,wght@0,100..900;1,100..900&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		$prefix . '-style',
		KRATOM_FEED_URI . '/assets/css/tailwind.css',
		array( $prefix . '-fonts', 'swiper' ),
		KRATOM_FEED_VERSION
	);

	wp_enqueue_style(
		'swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
		array(),
		'11'
	);

	wp_enqueue_script(
		'swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
		array(),
		'11',
		true
	);

	wp_enqueue_script(
		$prefix . '-main',
		KRATOM_FEED_URI . '/assets/js/main.js',
		array( 'swiper' ),
		KRATOM_FEED_VERSION,
		true
	);

	wp_localize_script(
		$prefix . '-main',
		'kratomFeed',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'kratom_feed_like' ),
			'i18n'    => array(
				'copied' => __( 'Link copied', 'kratom-feed' ),
				'liked'  => __( 'Liked', 'kratom-feed' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'kratom_feed_scripts' );

/**
 * AJAX: like a post (cookie-gated, anonymous).
 */
function kratom_feed_ajax_like_post() {
	check_ajax_referer( 'kratom_feed_like', 'nonce' );

	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	if ( ! $post_id || 'publish' !== get_post_status( $post_id ) ) {
		wp_send_json_error( array( 'message' => 'Invalid post.' ), 400 );
	}

	$cookie_key = 'kf_liked_' . $post_id;
	if ( ! empty( $_COOKIE[ $cookie_key ] ) ) {
		wp_send_json_success(
			array(
				'count'   => kratom_feed_get_like_count( $post_id ),
				'liked'   => true,
				'already' => true,
			)
		);
	}

	$count = kratom_feed_get_like_count( $post_id ) + 1;
	update_post_meta( $post_id, 'kf_like_count', $count );

	setcookie( $cookie_key, '1', time() + YEAR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );

	wp_send_json_success(
		array(
			'count'   => $count,
			'liked'   => true,
			'already' => false,
			'label'   => kratom_feed_format_count( $count ),
		)
	);
}
add_action( 'wp_ajax_kratom_feed_like_post', 'kratom_feed_ajax_like_post' );
add_action( 'wp_ajax_nopriv_kratom_feed_like_post', 'kratom_feed_ajax_like_post' );

/**
 * Admin: Featured column on posts list.
 *
 * @param array $columns Columns.
 * @return array
 */
function kratom_feed_posts_featured_column( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['kf_featured'] = __( 'Featured', 'kratom-feed' );
		}
	}
	return $new;
}
add_filter( 'manage_post_posts_columns', 'kratom_feed_posts_featured_column' );

/**
 * Admin: Featured column content.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function kratom_feed_posts_featured_column_content( $column, $post_id ) {
	if ( 'kf_featured' !== $column ) {
		return;
	}
	$featured = function_exists( 'carbon_get_post_meta' ) && carbon_get_post_meta( $post_id, 'is_featured' );
	echo $featured ? '<span class="dashicons dashicons-star-filled" style="color:#b8f04a" title="Featured"></span>' : '&mdash;';
}
add_action( 'manage_post_posts_custom_column', 'kratom_feed_posts_featured_column_content', 10, 2 );

/**
 * Boot Carbon Fields.
 */
function kratom_feed_load_carbon_fields() {
	if ( ! file_exists( KRATOM_FEED_DIR . '/vendor/autoload.php' ) ) {
		return;
	}
	require_once KRATOM_FEED_DIR . '/vendor/autoload.php';
	\Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'after_setup_theme', 'kratom_feed_load_carbon_fields', 5 );

require_once KRATOM_FEED_DIR . '/inc/template-functions.php';

if ( file_exists( KRATOM_FEED_DIR . '/vendor/autoload.php' ) ) {
	require_once KRATOM_FEED_DIR . '/inc/carbon-fields.php';
}

/**
 * Theme activation: flush rewrite rules.
 */
function kratom_feed_activate() {
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'kratom_feed_activate' );

/**
 * Allow SVG uploads for users who can upload files.
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
function kratom_feed_allow_svg_uploads( $mimes ) {
	if ( current_user_can( 'upload_files' ) ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'kratom_feed_allow_svg_uploads' );

/**
 * Fix SVG filetype detection so WordPress accepts .svg uploads.
 *
 * @param array        $data     File data from wp_check_filetype_and_ext.
 * @param string       $file     Full path to the file.
 * @param string       $filename File name.
 * @param array|null   $mimes    Mime types keyed by extension.
 * @return array
 */
function kratom_feed_fix_svg_mime_type( $data, $file, $filename, $mimes ) {
	if ( ! current_user_can( 'upload_files' ) ) {
		return $data;
	}

	$filetype = wp_check_filetype( $filename, $mimes );
	$ext      = isset( $filetype['ext'] ) ? strtolower( (string) $filetype['ext'] ) : '';

	if ( ! $ext ) {
		$ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
	}

	if ( 'svg' !== $ext && 'svgz' !== $ext ) {
		return $data;
	}

	$data['ext']  = 'svg';
	$data['type'] = 'image/svg+xml';

	if ( empty( $data['proper_filename'] ) ) {
		$data['proper_filename'] = $filename;
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'kratom_feed_fix_svg_mime_type', 10, 4 );

/**
 * Show SVG previews in the media library / Carbon file fields.
 *
 * @param array          $response   Attachment response for JS.
 * @param WP_Post        $attachment Attachment post.
 * @param array|false    $meta       Attachment meta.
 * @return array
 */
function kratom_feed_svg_media_preview( $response, $attachment, $meta ) {
	if ( 'image/svg+xml' !== $response['mime'] && 'image/svg+xml' !== get_post_mime_type( $attachment ) ) {
		return $response;
	}

	$url = wp_get_attachment_url( $attachment->ID );
	if ( ! $url ) {
		return $response;
	}

	$response['sizes'] = array(
		'full' => array(
			'url'         => $url,
			'width'       => isset( $meta['width'] ) ? (int) $meta['width'] : 150,
			'height'      => isset( $meta['height'] ) ? (int) $meta['height'] : 150,
			'orientation' => 'portrait',
		),
	);

	if ( empty( $response['url'] ) ) {
		$response['url'] = $url;
	}
	if ( empty( $response['icon'] ) ) {
		$response['icon'] = $url;
	}

	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'kratom_feed_svg_media_preview', 10, 3 );

