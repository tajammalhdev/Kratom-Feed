<?php
/**
 * Strain recommendation quiz: config, shortcode, enqueue, render.
 *
 * @package KratomFeeds
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default quiz configuration (used when theme options are empty).
 *
 * @return array
 */
function kratom_feed_quiz_defaults() {
	return array(
		'version'          => 1,
		'badge'            => __( 'Free · Takes 60 Seconds', 'kratom-feed' ),
		'title'            => __( 'Find Your Perfect Kratom', 'kratom-feed' ),
		'intro'            => __( 'Answer 5 quick questions and we\'ll match you with strain guides that fit your goals.', 'kratom-feed' ),
		'disclaimer'       => __( 'These statements have not been evaluated by the FDA. Kratom is not intended to diagnose, treat, cure, or prevent any disease. Educational information only.', 'kratom-feed' ),
		'progress_label'   => __( 'Question {current} of {total}', 'kratom-feed' ),
		'back_label'       => __( 'Back', 'kratom-feed' ),
		'next_label'       => __( 'Continue', 'kratom-feed' ),
		'gate_icon'        => '🎯',
		'gate_title'       => __( 'Your Perfect Match is Ready!', 'kratom-feed' ),
		'gate_text'        => __( 'Enter your details to see your personalized recommendation. We don\'t store this information yet — it stays in your browser for this session only.', 'kratom-feed' ),
		'gate_btn'         => __( 'See My Results →', 'kratom-feed' ),
		'gate_legal'       => __( 'No spam. By continuing you agree to our Privacy Policy.', 'kratom-feed' ),
		'gate_legal_url'   => '/privacy-policy/',
		'results_badge'    => __( 'Your Personalized Results', 'kratom-feed' ),
		'results_title'    => __( "Here's What We Recommend", 'kratom-feed' ),
		'results_sub'      => __( 'Based on your answers, here are educational guides matched to your goals.', 'kratom-feed' ),
		'primary_badge'    => __( 'Best Match For You', 'kratom-feed' ),
		'secondary_title'  => __( 'Also Consider', 'kratom-feed' ),
		'bonus_title'      => __( 'One More Thought', 'kratom-feed' ),
		'cta_primary'      => __( 'Read Guide →', 'kratom-feed' ),
		'cta_secondary'    => __( 'View Guide →', 'kratom-feed' ),
		'rec_trust'        => __( 'Educational · Research-backed · Always verify local laws', 'kratom-feed' ),
		'retake_label'     => __( '↩ Retake the Quiz', 'kratom-feed' ),
		'trust_items'      => array(
			array( 'label' => __( '🔬 3rd-Party Lab Tested', 'kratom-feed' ) ),
			array( 'label' => __( '🏆 AKA GMP Certified', 'kratom-feed' ) ),
			array( 'label' => __( '📦 Free Shipping Over $99', 'kratom-feed' ) ),
			array( 'label' => __( '✅ 30-Day Guarantee', 'kratom-feed' ) ),
		),
		'questions'        => array(
			array(
				'key'        => 'goal',
				'prompt'     => __( "What's your main goal with kratom?", 'kratom-feed' ),
				'help'       => '',
				'required'   => true,
				'show_disclaimer' => true,
				'options'    => array(
					array(
						'key'         => 'energy',
						'icon'        => '⚡',
						'label'       => __( 'Energy & Focus', 'kratom-feed' ),
						'description' => __( 'Stay sharp and productive', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'white_maeng_da', 'points' => 3 ),
							array( 'result_key' => 'white_borneo', 'points' => 2 ),
						),
					),
					array(
						'key'         => 'relaxation',
						'icon'        => '😌',
						'label'       => __( 'Relaxation & Calm', 'kratom-feed' ),
						'description' => __( 'Unwind and de-stress', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'green_malay', 'points' => 2 ),
							array( 'result_key' => 'green_bali', 'points' => 2 ),
							array( 'result_key' => 'red_bali', 'points' => 1 ),
						),
					),
					array(
						'key'         => 'comfort',
						'icon'        => '🌿',
						'label'       => __( 'Physical Comfort', 'kratom-feed' ),
						'description' => __( 'Ease and soothe', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'red_bali', 'points' => 3 ),
							array( 'result_key' => 'red_maeng_da', 'points' => 2 ),
							array( 'result_key' => 'red_borneo', 'points' => 1 ),
						),
					),
					array(
						'key'         => 'mood',
						'icon'        => '😊',
						'label'       => __( 'Mood & Motivation', 'kratom-feed' ),
						'description' => __( 'Lift your spirits', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'green_malay', 'points' => 3 ),
							array( 'result_key' => 'green_maeng_da', 'points' => 2 ),
							array( 'result_key' => 'trainwreck', 'points' => 1 ),
						),
					),
					array(
						'key'         => 'wind-down',
						'icon'        => '🌙',
						'label'       => __( 'Evening Wind-Down', 'kratom-feed' ),
						'description' => __( 'End the day peacefully', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'red_bali', 'points' => 2 ),
							array( 'result_key' => 'red_borneo', 'points' => 2 ),
							array( 'result_key' => 'red_bentuangie', 'points' => 2 ),
						),
					),
				),
			),
			array(
				'key'      => 'experience',
				'prompt'   => __( "What's your experience with kratom?", 'kratom-feed' ),
				'help'     => '',
				'required' => true,
				'options'  => array(
					array(
						'key'         => 'new',
						'icon'        => '🌱',
						'label'       => __( 'Brand New', 'kratom-feed' ),
						'description' => __( 'Never tried it before', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'white_maeng_da', 'points' => 1 ),
							array( 'result_key' => 'green_malay', 'points' => 1 ),
							array( 'result_key' => 'red_bali', 'points' => 1 ),
						),
					),
					array(
						'key'         => 'some',
						'icon'        => '🌿',
						'label'       => __( 'Some Experience', 'kratom-feed' ),
						'description' => __( 'Tried it a few times', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'green_bali', 'points' => 1 ),
							array( 'result_key' => 'green_maeng_da', 'points' => 1 ),
							array( 'result_key' => 'red_maeng_da', 'points' => 1 ),
						),
					),
					array(
						'key'         => 'experienced',
						'icon'        => '🍃',
						'label'       => __( 'Experienced', 'kratom-feed' ),
						'description' => __( 'Regular user, know what I like', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'white_borneo', 'points' => 1 ),
							array( 'result_key' => 'red_borneo', 'points' => 1 ),
							array( 'result_key' => 'trainwreck', 'points' => 1 ),
							array( 'result_key' => 'red_bentuangie', 'points' => 1 ),
						),
					),
				),
			),
			array(
				'key'      => 'format',
				'prompt'   => __( 'How do you prefer to take it?', 'kratom-feed' ),
				'help'     => __( 'This shapes format suggestions in your results.', 'kratom-feed' ),
				'required' => true,
				'options'  => array(
					array(
						'key'         => 'capsules',
						'icon'        => '💊',
						'label'       => __( 'Capsules', 'kratom-feed' ),
						'description' => __( 'Easy, pre-measured, no prep', 'kratom-feed' ),
						'weights'     => array(),
					),
					array(
						'key'         => 'powder',
						'icon'        => '🥄',
						'label'       => __( 'Powder', 'kratom-feed' ),
						'description' => __( 'Flexible dosing, best value', 'kratom-feed' ),
						'weights'     => array(),
					),
					array(
						'key'         => 'fast',
						'icon'        => '⚡',
						'label'       => __( 'Fast-Acting (Shots/Extracts)', 'kratom-feed' ),
						'description' => __( 'Maximum effect, minimum time', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'nano_shot', 'points' => 5 ),
							array( 'result_key' => 'mitragen_extract', 'points' => 3 ),
						),
					),
					array(
						'key'         => 'unsure',
						'icon'        => '🤔',
						'label'       => __( 'Not Sure — Recommend Me', 'kratom-feed' ),
						'description' => __( "We'll pick a balanced starting point", 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'green_maeng_da', 'points' => 1 ),
						),
					),
				),
			),
			array(
				'key'      => 'time',
				'prompt'   => __( 'When do you usually take kratom?', 'kratom-feed' ),
				'help'     => '',
				'required' => true,
				'options'  => array(
					array(
						'key'         => 'morning',
						'icon'        => '🌅',
						'label'       => __( 'Morning', 'kratom-feed' ),
						'description' => __( 'Start the day right', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'white_maeng_da', 'points' => 1 ),
						),
						'bonus_result' => 'white_maeng_da',
					),
					array(
						'key'         => 'afternoon',
						'icon'        => '☀️',
						'label'       => __( 'Afternoon', 'kratom-feed' ),
						'description' => __( 'Midday boost or reset', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'green_maeng_da', 'points' => 1 ),
						),
					),
					array(
						'key'         => 'evening',
						'icon'        => '🌙',
						'label'       => __( 'Evening', 'kratom-feed' ),
						'description' => __( 'Wind down after the day', 'kratom-feed' ),
						'weights'     => array(
							array( 'result_key' => 'red_bali', 'points' => 1 ),
						),
						'bonus_result' => 'red_bali',
					),
					array(
						'key'         => 'varies',
						'icon'        => '🔄',
						'label'       => __( 'Varies', 'kratom-feed' ),
						'description' => __( 'Different times each day', 'kratom-feed' ),
						'weights'     => array(),
					),
				),
			),
			array(
				'key'      => 'sensitivity',
				'prompt'   => __( 'Any digestive sensitivities?', 'kratom-feed' ),
				'help'     => '',
				'required' => true,
				'options'  => array(
					array(
						'key'         => 'sensitive',
						'icon'        => '⚠️',
						'label'       => __( 'Sensitive Stomach', 'kratom-feed' ),
						'description' => __( 'Sometimes get nausea or upset stomach', 'kratom-feed' ),
						'weights'     => array(),
					),
					array(
						'key'         => 'none',
						'icon'        => '✅',
						'label'       => __( 'No Issues', 'kratom-feed' ),
						'description' => __( 'No digestive concerns', 'kratom-feed' ),
						'weights'     => array(),
					),
				),
			),
		),
		'results'          => array(
			array(
				'key'         => 'white_maeng_da',
				'name'        => __( 'White Maeng Da', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'A classic entry point for clean energy and focus. Smooth and well-balanced for daytime use.', 'kratom-feed' ),
				'tags'        => 'Energy, Focus, Daytime',
				'cta_url'     => '/blog/',
				'cta_label'   => '',
				'caution'     => '',
				'priority'    => 10,
				'image'       => 0,
			),
			array(
				'key'         => 'white_borneo',
				'name'        => __( 'White Borneo', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'A sharper, longer-lasting white vein often preferred by experienced users for deep focus sessions.', 'kratom-feed' ),
				'tags'        => 'Energy, Deep Focus, Long Duration',
				'cta_url'     => '/blog/',
				'priority'    => 20,
			),
			array(
				'key'         => 'green_malay',
				'name'        => __( 'Green Malay', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'A gentle, well-rounded green vein — great for easing into relaxation or an uplifting mood without feeling overpowering.', 'kratom-feed' ),
				'tags'        => 'Balanced, Gentle, Mood',
				'cta_url'     => '/blog/',
				'priority'    => 15,
			),
			array(
				'key'         => 'green_bali',
				'name'        => __( 'Green Bali', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'One of the most popular greens for calm, balanced relaxation with a slight mood lift.', 'kratom-feed' ),
				'tags'        => 'Relaxation, Calm, Mood',
				'cta_url'     => '/blog/',
				'priority'    => 25,
			),
			array(
				'key'         => 'green_maeng_da',
				'name'        => __( 'Green Maeng Da', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'A popular all-around green for motivation, positivity, and a productive mindset.', 'kratom-feed' ),
				'tags'        => 'Mood, Motivation, Balanced',
				'cta_url'     => '/blog/',
				'priority'    => 5,
			),
			array(
				'key'         => 'red_bali',
				'name'        => __( 'Red Bali', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'A go-to red vein traditionally discussed for deep relaxation, evening wind-down, and physical ease.', 'kratom-feed' ),
				'tags'        => 'Relaxation, Evening, Comfort',
				'cta_url'     => '/blog/',
				'priority'    => 12,
			),
			array(
				'key'         => 'red_maeng_da',
				'name'        => __( 'Red Maeng Da', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'A potent red vein often discussed for physical comfort and tension relief.', 'kratom-feed' ),
				'tags'        => 'Comfort, Potent, Tension Relief',
				'cta_url'     => '/blog/',
				'priority'    => 22,
			),
			array(
				'key'         => 'red_borneo',
				'name'        => __( 'Red Borneo', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'Deep, long-lasting red vein effects — a staple topic for experienced users seeking serious physical ease.', 'kratom-feed' ),
				'tags'        => 'Deep Comfort, Long-Lasting, Potent',
				'cta_url'     => '/blog/',
				'priority'    => 28,
			),
			array(
				'key'         => 'red_bentuangie',
				'name'        => __( 'Red Bentuangie', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'A slow-fermented red vein with deeply calming properties — highly regarded for evening use.', 'kratom-feed' ),
				'tags'        => 'Deep Calm, Evening, Fermented',
				'cta_url'     => '/blog/',
				'priority'    => 30,
			),
			array(
				'key'         => 'trainwreck',
				'name'        => __( 'Trainwreck Blend', 'kratom-feed' ),
				'format'      => __( 'Guide', 'kratom-feed' ),
				'why'         => __( 'A multi-strain blend discussed for a broad, euphoric mood lift — best suited for experienced users.', 'kratom-feed' ),
				'tags'        => 'Mood, Euphoria, Blend, Advanced',
				'cta_url'     => '/blog/',
				'priority'    => 35,
			),
			array(
				'key'         => 'nano_shot',
				'name'        => __( 'Fast-Acting Shots', 'kratom-feed' ),
				'format'      => __( 'Shot', 'kratom-feed' ),
				'why'         => __( 'Learn how liquid shots and nano formats are commonly used when people want a faster-acting option.', 'kratom-feed' ),
				'tags'        => 'Fast-Acting, Convenient',
				'cta_url'     => '/blog/',
				'priority'    => 40,
			),
			array(
				'key'         => 'mitragen_extract',
				'name'        => __( 'Liquid Extracts', 'kratom-feed' ),
				'format'      => __( 'Extract', 'kratom-feed' ),
				'why'         => __( 'High-potency liquid extracts are often discussed for maximum effect in minimum time — approach carefully and start low.', 'kratom-feed' ),
				'tags'        => 'Extract, Potent, Advanced',
				'cta_url'     => '/blog/',
				'priority'    => 45,
			),
		),
	);
}

/**
 * Resolve a theme option with fallback.
 *
 * @param string $key     Option key.
 * @param mixed  $default Default.
 * @return mixed
 */
function kratom_feed_quiz_option( $key, $default = '' ) {
	if ( ! function_exists( 'carbon_get_theme_option' ) ) {
		return $default;
	}
	$value = carbon_get_theme_option( $key );
	if ( null === $value || false === $value || '' === $value ) {
		return $default;
	}
	return $value;
}

/**
 * Build normalized quiz config for front-end JSON.
 *
 * @return array
 */
function kratom_feed_get_quiz_config() {
	$defaults = kratom_feed_quiz_defaults();

	$trust_raw = kratom_feed_quiz_option( 'quiz_trust_items', array() );
	$trust     = array();
	if ( is_array( $trust_raw ) && $trust_raw ) {
		foreach ( $trust_raw as $item ) {
			$label = isset( $item['label'] ) ? trim( (string) $item['label'] ) : '';
			if ( $label ) {
				$trust[] = array( 'label' => $label );
			}
		}
	}
	if ( ! $trust ) {
		$trust = $defaults['trust_items'];
	}

	$questions_raw = kratom_feed_quiz_option( 'quiz_questions', array() );
	$questions     = kratom_feed_normalize_quiz_questions( $questions_raw );
	if ( ! $questions ) {
		$questions = $defaults['questions'];
	}

	$results_raw = kratom_feed_quiz_option( 'quiz_results', array() );
	$results     = kratom_feed_normalize_quiz_results( $results_raw );
	if ( ! $results ) {
		$results = array();
		foreach ( $defaults['results'] as $row ) {
			$results[ $row['key'] ] = kratom_feed_normalize_quiz_result_row( $row );
		}
	}

	return array(
		'version'         => 1,
		'badge'           => (string) kratom_feed_quiz_option( 'quiz_badge', $defaults['badge'] ),
		'title'           => (string) kratom_feed_quiz_option( 'quiz_title', $defaults['title'] ),
		'intro'           => (string) kratom_feed_quiz_option( 'quiz_intro', $defaults['intro'] ),
		'disclaimer'      => (string) kratom_feed_quiz_option( 'quiz_disclaimer', $defaults['disclaimer'] ),
		'progressLabel'   => (string) kratom_feed_quiz_option( 'quiz_progress_label', $defaults['progress_label'] ),
		'backLabel'       => (string) kratom_feed_quiz_option( 'quiz_back_label', $defaults['back_label'] ),
		'nextLabel'       => (string) kratom_feed_quiz_option( 'quiz_next_label', $defaults['next_label'] ),
		'gateIcon'        => (string) kratom_feed_quiz_option( 'quiz_gate_icon', $defaults['gate_icon'] ),
		'gateTitle'       => (string) kratom_feed_quiz_option( 'quiz_gate_title', $defaults['gate_title'] ),
		'gateText'        => (string) kratom_feed_quiz_option( 'quiz_gate_text', $defaults['gate_text'] ),
		'gateBtn'         => (string) kratom_feed_quiz_option( 'quiz_gate_btn', $defaults['gate_btn'] ),
		'gateLegal'       => (string) kratom_feed_quiz_option( 'quiz_gate_legal', $defaults['gate_legal'] ),
		'gateLegalUrl'    => (string) kratom_feed_quiz_option( 'quiz_gate_legal_url', $defaults['gate_legal_url'] ),
		'resultsBadge'    => (string) kratom_feed_quiz_option( 'quiz_results_badge', $defaults['results_badge'] ),
		'resultsTitle'    => (string) kratom_feed_quiz_option( 'quiz_results_title', $defaults['results_title'] ),
		'resultsSub'      => (string) kratom_feed_quiz_option( 'quiz_results_sub', $defaults['results_sub'] ),
		'primaryBadge'    => (string) kratom_feed_quiz_option( 'quiz_primary_badge', $defaults['primary_badge'] ),
		'secondaryTitle'  => (string) kratom_feed_quiz_option( 'quiz_secondary_title', $defaults['secondary_title'] ),
		'bonusTitle'      => (string) kratom_feed_quiz_option( 'quiz_bonus_title', $defaults['bonus_title'] ),
		'ctaPrimary'      => (string) kratom_feed_quiz_option( 'quiz_cta_primary', $defaults['cta_primary'] ),
		'ctaSecondary'    => (string) kratom_feed_quiz_option( 'quiz_cta_secondary', $defaults['cta_secondary'] ),
		'recTrust'        => (string) kratom_feed_quiz_option( 'quiz_rec_trust', $defaults['rec_trust'] ),
		'retakeLabel'     => (string) kratom_feed_quiz_option( 'quiz_retake_label', $defaults['retake_label'] ),
		'trustItems'      => $trust,
		'questions'       => $questions,
		'results'         => $results,
		'i18n'            => array(
			'selectOption' => __( 'Please select an option to continue.', 'kratom-feed' ),
			'emailRequired'=> __( 'Please enter a valid email address.', 'kratom-feed' ),
		),
	);
}

/**
 * Normalize questions from Carbon complex field.
 *
 * @param array $raw Raw questions.
 * @return array
 */
function kratom_feed_normalize_quiz_questions( $raw ) {
	if ( empty( $raw ) || ! is_array( $raw ) ) {
		return array();
	}

	$out = array();
	foreach ( $raw as $q ) {
		$key = isset( $q['key'] ) ? sanitize_key( $q['key'] ) : '';
		if ( ! $key ) {
			continue;
		}
		$options = array();
		if ( ! empty( $q['options'] ) && is_array( $q['options'] ) ) {
			foreach ( $q['options'] as $opt ) {
				$okey = isset( $opt['key'] ) ? sanitize_key( $opt['key'] ) : '';
				if ( ! $okey ) {
					continue;
				}
				$weights = array();
				if ( ! empty( $opt['weights'] ) && is_array( $opt['weights'] ) ) {
					foreach ( $opt['weights'] as $w ) {
						$rk = isset( $w['result_key'] ) ? sanitize_key( $w['result_key'] ) : '';
						if ( ! $rk ) {
							continue;
						}
						$weights[] = array(
							'result_key' => $rk,
							'points'     => isset( $w['points'] ) ? (float) $w['points'] : 1,
						);
					}
				}
				$options[] = array(
					'key'          => $okey,
					'icon'         => isset( $opt['icon'] ) ? (string) $opt['icon'] : '',
					'label'        => isset( $opt['label'] ) ? (string) $opt['label'] : $okey,
					'description'  => isset( $opt['description'] ) ? (string) $opt['description'] : '',
					'weights'      => $weights,
					'bonus_result' => ! empty( $opt['bonus_result'] ) ? sanitize_key( $opt['bonus_result'] ) : '',
				);
			}
		}
		if ( ! $options ) {
			continue;
		}
		$out[] = array(
			'key'             => $key,
			'prompt'          => isset( $q['prompt'] ) ? (string) $q['prompt'] : '',
			'help'            => isset( $q['help'] ) ? (string) $q['help'] : '',
			'required'        => ! empty( $q['required'] ),
			'show_disclaimer' => ! empty( $q['show_disclaimer'] ),
			'options'         => $options,
		);
	}
	return $out;
}

/**
 * Normalize a single result row.
 *
 * @param array $row Raw row.
 * @return array
 */
function kratom_feed_normalize_quiz_result_row( $row ) {
	$key = isset( $row['key'] ) ? sanitize_key( $row['key'] ) : '';
	$tags_raw = isset( $row['tags'] ) ? (string) $row['tags'] : '';
	$tags     = array_values(
		array_filter(
			array_map(
				'trim',
				preg_split( '/[,|]/', $tags_raw ) ?: array()
			)
		)
	);

	$image_id  = isset( $row['image'] ) ? absint( $row['image'] ) : 0;
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';

	$article_url = '';
	if ( ! empty( $row['article'] ) && is_array( $row['article'] ) ) {
		$first = reset( $row['article'] );
		if ( ! empty( $first['id'] ) ) {
			$article_url = get_permalink( (int) $first['id'] );
		}
	}

	$cta_url = isset( $row['cta_url'] ) ? trim( (string) $row['cta_url'] ) : '';
	if ( ! $cta_url && $article_url ) {
		$cta_url = $article_url;
	}
	if ( $cta_url && '#' !== $cta_url[0] && ! preg_match( '#^https?://#i', $cta_url ) ) {
		$cta_url = home_url( $cta_url );
	}

	return array(
		'key'       => $key,
		'name'      => isset( $row['name'] ) ? (string) $row['name'] : $key,
		'format'    => isset( $row['format'] ) ? (string) $row['format'] : '',
		'why'       => isset( $row['why'] ) ? (string) $row['why'] : '',
		'tags'      => $tags,
		'ctaUrl'    => $cta_url,
		'ctaLabel'  => isset( $row['cta_label'] ) ? (string) $row['cta_label'] : '',
		'caution'   => isset( $row['caution'] ) ? (string) $row['caution'] : '',
		'priority'  => isset( $row['priority'] ) ? absint( $row['priority'] ) : 100,
		'imageUrl'  => $image_url ? $image_url : '',
	);
}

/**
 * Normalize results map keyed by result key.
 *
 * @param array $raw Raw results.
 * @return array
 */
function kratom_feed_normalize_quiz_results( $raw ) {
	if ( empty( $raw ) || ! is_array( $raw ) ) {
		return array();
	}
	$out = array();
	foreach ( $raw as $row ) {
		$normalized = kratom_feed_normalize_quiz_result_row( $row );
		if ( empty( $normalized['key'] ) ) {
			continue;
		}
		$out[ $normalized['key'] ] = $normalized;
	}
	return $out;
}

/**
 * Whether the current request should load quiz assets.
 *
 * @return bool
 */
function kratom_feed_should_enqueue_quiz() {
	if ( is_page_template( 'page-templates/strain-quiz.php' ) ) {
		return true;
	}

	if ( is_singular( 'page' ) ) {
		$post = get_post();
		if ( $post && has_shortcode( $post->post_content, 'kratom_quiz' ) ) {
			return true;
		}
	}

	return (bool) apply_filters( 'kratom_feed_enqueue_quiz', false );
}

/**
 * Enqueue quiz assets when needed.
 */
function kratom_feed_enqueue_quiz_assets() {
	if ( ! kratom_feed_should_enqueue_quiz() ) {
		return;
	}

	$prefix = kratom_feed_prefix();
	wp_enqueue_script(
		$prefix . '-strain-quiz',
		KRATOM_FEED_URI . '/assets/js/strain-quiz.js',
		array(),
		KRATOM_FEED_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'kratom_feed_enqueue_quiz_assets', 20 );

/**
 * Force enqueue from shortcode / render.
 */
function kratom_feed_force_enqueue_quiz() {
	$prefix = kratom_feed_prefix();
	if ( ! wp_script_is( $prefix . '-strain-quiz', 'enqueued' ) ) {
		wp_enqueue_script(
			$prefix . '-strain-quiz',
			KRATOM_FEED_URI . '/assets/js/strain-quiz.js',
			array(),
			KRATOM_FEED_VERSION,
			true
		);
	}
}

/**
 * Render the quiz markup.
 *
 * @param array $args Optional overrides.
 * @return string
 */
function kratom_feed_render_quiz( $args = array() ) {
	kratom_feed_force_enqueue_quiz();

	$config   = kratom_feed_get_quiz_config();
	$instance = wp_unique_id( 'kf-quiz-' );
	$args     = wp_parse_args(
		$args,
		array(
			'class' => '',
		)
	);

	ob_start();
	$quiz_config   = $config;
	$quiz_instance = $instance;
	$quiz_class    = $args['class'];
	include locate_template( 'template-parts/quiz/quiz.php' );
	return ob_get_clean();
}

/**
 * Shortcode: [kratom_quiz]
 *
 * @param array $atts Attributes.
 * @return string
 */
function kratom_feed_quiz_shortcode( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'class' => '',
		),
		$atts,
		'kratom_quiz'
	);
	return kratom_feed_render_quiz( $atts );
}
add_shortcode( 'kratom_quiz', 'kratom_feed_quiz_shortcode' );
