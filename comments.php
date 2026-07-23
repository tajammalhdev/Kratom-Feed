<?php
/**
 * Comments template — form first, then comment list.
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}

$commenter       = wp_get_current_commenter();
$req             = get_option( 'require_name_email' );
$aria_req        = $req ? ' aria-required="true" required' : '';
$consent_checked = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

$input_class = 'w-full rounded-xl border border-pg-border bg-pg-gray-light px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 outline-none transition-colors focus:border-pg-green focus:bg-white focus:ring-2 focus:ring-pg-green/20';
$label_class = 'sr-only';

$fields = array(
	'author' => sprintf(
		'<div class="grid gap-4 sm:grid-cols-2"><div class="min-w-0"><label class="%1$s" for="author">%2$s</label><input id="author" name="author" type="text" value="%3$s" placeholder="%4$s" class="%5$s" autocomplete="name"%6$s /></div>',
		esc_attr( $label_class ),
		esc_html__( 'Your name', 'kratom-feed' ),
		esc_attr( $commenter['comment_author'] ),
		esc_attr__( 'Your name *', 'kratom-feed' ),
		esc_attr( $input_class ),
		$aria_req
	),
	'email'  => sprintf(
		'<div class="min-w-0"><label class="%1$s" for="email">%2$s</label><input id="email" name="email" type="email" value="%3$s" placeholder="%4$s" class="%5$s" autocomplete="email"%6$s /></div></div>',
		esc_attr( $label_class ),
		esc_html__( 'Email address', 'kratom-feed' ),
		esc_attr( $commenter['comment_author_email'] ),
		esc_attr__( 'Email address *', 'kratom-feed' ),
		esc_attr( $input_class ),
		$aria_req
	),
	'url'    => '',
	'cookies'=> sprintf(
		'<p class="comment-form-cookies-consent mt-4 flex items-start gap-3 text-sm text-gray-500"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" class="mt-1 h-4 w-4 shrink-0 rounded border-pg-border text-pg-green focus:ring-pg-green/20"%1$s /><label for="wp-comment-cookies-consent" class="leading-relaxed">%2$s</label></p>',
		$consent_checked,
		esc_html__( 'Save my name and e-mail in this browser for the next time I comment.', 'kratom-feed' )
	),
);

$comment_field = sprintf(
	'<div class="mt-4"><label class="%1$s" for="comment">%2$s</label><textarea id="comment" name="comment" rows="5" placeholder="%3$s" class="%4$s" required></textarea></div>',
	esc_attr( $label_class ),
	esc_html__( 'Your message', 'kratom-feed' ),
	esc_attr__( 'Your message....', 'kratom-feed' ),
	esc_attr( $input_class . ' min-h-[120px] resize-y' )
);

$form_args = array(
	'fields'               => $fields,
	'comment_field'        => $comment_field,
	'title_reply'          => __( 'Get In Touch', 'kratom-feed' ),
	'title_reply_to'       => __( 'Reply to %s', 'kratom-feed' ),
	'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title mb-6 flex items-center gap-2 text-sm font-bold uppercase tracking-[0.12em] text-gray-900">',
	'title_reply_after'    => '</h3>',
	'cancel_reply_before'  => '<span class="ml-2 text-xs font-medium normal-case tracking-normal text-pg-green-dark">',
	'cancel_reply_after'   => '</span>',
	'cancel_reply_link'    => __( 'Cancel reply', 'kratom-feed' ),
	'label_submit'         => __( 'Send message', 'kratom-feed' ),
	'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s <span aria-hidden="true">→</span></button>',
	'submit_field'         => '<p class="form-submit mt-6 mb-0">%1$s %2$s</p>',
	'class_submit'         => 'inline-flex h-12 cursor-pointer items-center gap-2 rounded bg-pg-green px-6 text-sm font-semibold text-white transition-colors hover:bg-pg-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pg-green',
	'class_form'           => 'comment-form',
	'comment_notes_before' => '',
	'comment_notes_after'  => '',
	'logged_in_as'         => sprintf(
		'<p class="logged-in-as mb-4 rounded-xl border border-pg-border bg-pg-green-light/40 px-4 py-3 text-sm text-gray-600">%s</p>',
		sprintf(
			/* translators: 1: edit user link, 2: logout URL, 3: username */
			__( 'Logged in as <a class="font-semibold text-pg-green-dark underline underline-offset-2 hover:text-pg-hover" href="%1$s">%3$s</a>. <a class="underline underline-offset-2 hover:text-pg-hover" href="%2$s">Log out?</a>', 'kratom-feed' ),
			esc_url( get_edit_user_link() ),
			esc_url( wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) ),
			esc_html( wp_get_current_user()->display_name )
		)
	),
	'must_log_in'          => '<p class="must-log-in text-sm text-gray-500">' . sprintf(
		/* translators: %s: login URL */
		__( 'You must be <a class="font-semibold text-pg-green-dark underline underline-offset-2 hover:text-pg-hover" href="%s">logged in</a> to post a comment.', 'kratom-feed' ),
		esc_url( wp_login_url( get_permalink() ) )
	) . '</p>',
);
?>
<div id="comments" class="kf-comments space-y-10">
	<?php if ( comments_open() ) : ?>
	<div class="overflow-hidden rounded- border border-pg-border bg-white p-6  sm:p-8">
		<div class="mb-6 flex items-center gap-2.5">
			<span class="flex h-8 w-8 items-center justify-center  ">
				<svg class="dark-mode-invert" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.017 0L0.016982 5L0 6.40632L6.08518 8.74677C6.15121 8.77216 6.20338 8.82434 6.22877 8.89036L8.56923 14.9755L9.97559 14.9586L14.9756 0.958559L14.017 0ZM6.58355 7.33133L2.42622 5.73235L11.3805 2.5344L6.58355 7.33133ZM7.64421 8.39199L9.24319 12.5493L12.4412 3.59506L7.64421 8.39199Z" fill="#171717"></path></svg>
			</span>
			<span class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900"><?php esc_html_e( 'Get In Touch', 'kratom-feed' ); ?></span>
		</div>

		<?php
		// Hide the default title_reply since we render a custom header above.
		$form_args['title_reply']        = '';
		$form_args['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title sr-only">';
		$form_args['title_reply_after']  = '</h3>';

		comment_form( $form_args );
		?>
	</div>
	<?php endif; ?>

	<?php if ( have_comments() ) : ?>
	<div class="kf-comments__list">
		<h2 class="mb-8 text-2xl font-bold uppercase tracking-wide text-gray-900 md:text-3xl">
			<?php esc_html_e( 'Comments', 'kratom-feed' ); ?>
		</h2>

		<ol class="comment-list m-0 list-none p-0 [&_ol.children]:mt-5 [&_ol.children]:ml-4 [&_ol.children]:list-none [&_ol.children]:border-l-2 [&_ol.children]:border-pg-green-light [&_ol.children]:pl-5 sm:[&_ol.children]:ml-8 sm:[&_ol.children]:pl-6">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
					'callback'    => 'kratom_feed_comment_callback',
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text'          => __( 'Older comments', 'kratom-feed' ),
				'next_text'          => __( 'Newer comments', 'kratom-feed' ),
				'screen_reader_text' => __( 'Comments navigation', 'kratom-feed' ),
				'class'              => 'mt-8 flex items-center justify-between gap-4 text-sm font-semibold text-pg-green-dark',
			)
		);
		?>
	</div>
	<?php elseif ( ! comments_open() ) : ?>
	<p class="rounded-xl border border-pg-border bg-pg-gray-light px-4 py-3 text-sm text-gray-500"><?php esc_html_e( 'Comments are closed.', 'kratom-feed' ); ?></p>
	<?php endif; ?>
</div>
