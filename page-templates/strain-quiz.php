<?php
/**
 * Template Name: Strain Quiz
 * Description: Full-page kratom recommendation quiz powered by Theme Options → Quiz.
 *
 * @package KratomFeeds
 */

get_header();
?>
<main id="main-content">
	<?php
	while ( have_posts() ) :
		the_post();
		echo kratom_feed_render_quiz(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	endwhile;
	?>
</main>
<?php
get_footer();
