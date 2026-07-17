<?php
/**
 * Strain recommendation quiz markup.
 *
 * @package KratomFeeds
 *
 * @var array  $quiz_config
 * @var string $quiz_instance
 * @var string $quiz_class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$config   = isset( $quiz_config ) && is_array( $quiz_config ) ? $quiz_config : kratom_feed_get_quiz_config();
$instance = isset( $quiz_instance ) ? $quiz_instance : wp_unique_id( 'kf-quiz-' );
$class    = isset( $quiz_class ) ? trim( (string) $quiz_class ) : '';
$questions = isset( $config['questions'] ) ? $config['questions'] : array();
$total     = count( $questions );
$legal_url = ! empty( $config['gateLegalUrl'] ) ? $config['gateLegalUrl'] : '/privacy-policy/';
if ( $legal_url && '#' !== $legal_url[0] && ! preg_match( '#^https?://#i', $legal_url ) ) {
	$legal_url = home_url( $legal_url );
}

$progress_text = str_replace(
	array( '{current}', '{total}' ),
	array( '1', (string) max( 1, $total ) ),
	isset( $config['progressLabel'] ) ? $config['progressLabel'] : 'Question {current} of {total}'
);

$json = wp_json_encode( $config );
if ( ! $json ) {
	$json = '{}';
}
?>
<section
	id="<?php echo esc_attr( $instance ); ?>"
	class="kf-quiz <?php echo esc_attr( $class ); ?>"
	data-kf-quiz
	aria-labelledby="<?php echo esc_attr( $instance ); ?>-title"
>
	<script type="application/json" class="kf-quiz__config"><?php echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON from wp_json_encode. ?></script>

	<div class="kf-quiz__steps-wrap" data-quiz-panel="steps">
		<header class="kf-quiz__hero bg-pg-green px-6 py-14 text-center text-white sm:py-16">
			<div class="mx-auto max-w-xl">
				<?php if ( ! empty( $config['badge'] ) ) : ?>
				<span class="mb-4 inline-block rounded-full border border-white/30 bg-white/15 px-3.5 py-1.5 text-[13px] font-semibold">
					<?php echo esc_html( $config['badge'] ); ?>
				</span>
				<?php endif; ?>
				<h1 id="<?php echo esc_attr( $instance ); ?>-title" class="m-0 text-3xl font-bold leading-tight sm:text-4xl">
					<?php echo esc_html( $config['title'] ); ?>
				</h1>
				<?php if ( ! empty( $config['intro'] ) ) : ?>
				<p class="mt-3 mb-0 text-base leading-relaxed text-white/85 sm:text-lg">
					<?php echo esc_html( $config['intro'] ); ?>
				</p>
				<?php endif; ?>
			</div>
		</header>

		<div class="mx-auto max-w-[680px] px-6 py-10 sm:py-14">
			<div class="mb-9" aria-live="polite">
				<div class="mb-2 h-1.5 overflow-hidden rounded bg-neutral-200" role="progressbar" aria-valuemin="1" aria-valuemax="<?php echo esc_attr( (string) max( 1, $total ) ); ?>" aria-valuenow="1" data-quiz-progressbar>
					<div class="kf-quiz__progress-fill h-full w-[20%] rounded bg-pg-green transition-[width] duration-300" data-quiz-progress></div>
				</div>
				<p class="m-0 text-[13px] text-neutral-400" data-quiz-progress-text><?php echo esc_html( $progress_text ); ?></p>
			</div>

			<div class="sr-only" aria-live="assertive" data-quiz-status></div>

			<?php foreach ( $questions as $index => $question ) : ?>
				<?php
				$step     = $index + 1;
				$is_first = 0 === $index;
				$qid      = $instance . '-q-' . $question['key'];
				?>
				<div
					class="kf-quiz__step <?php echo $is_first ? 'is-active' : ''; ?>"
					data-quiz-step="<?php echo esc_attr( (string) $step ); ?>"
					data-question-key="<?php echo esc_attr( $question['key'] ); ?>"
					<?php echo $is_first ? '' : 'hidden'; ?>
				>
					<h2 id="<?php echo esc_attr( $qid ); ?>" class="m-0 text-xl font-bold leading-snug text-neutral-900 sm:text-[26px]">
						<?php echo esc_html( $question['prompt'] ); ?>
					</h2>

					<?php if ( ! empty( $question['show_disclaimer'] ) && ! empty( $config['disclaimer'] ) ) : ?>
					<p class="mt-2 mb-0 text-[11px] leading-relaxed text-neutral-400"><?php echo esc_html( $config['disclaimer'] ); ?></p>
					<?php elseif ( ! empty( $question['help'] ) ) : ?>
					<p class="mt-2 mb-0 text-sm text-neutral-500"><?php echo esc_html( $question['help'] ); ?></p>
					<?php endif; ?>

					<div class="mt-6 grid gap-3" role="radiogroup" aria-labelledby="<?php echo esc_attr( $qid ); ?>">
						<?php foreach ( $question['options'] as $opt_i => $option ) : ?>
							<?php $oid = $qid . '-opt-' . $option['key']; ?>
							<button
								type="button"
								class="kf-quiz__opt group grid w-full grid-cols-[48px_1fr] items-center gap-x-3 rounded-[10px] border-2 border-neutral-200 bg-white px-5 py-4 text-left transition-colors hover:border-pg-green hover:bg-[#f9fcf6] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pg-green"
								role="radio"
								aria-checked="false"
								id="<?php echo esc_attr( $oid ); ?>"
								data-quiz-option
								data-option-key="<?php echo esc_attr( $option['key'] ); ?>"
							>
								<span class="row-span-2 flex items-center justify-center text-2xl" aria-hidden="true"><?php echo esc_html( $option['icon'] ); ?></span>
								<span class="text-base font-bold leading-tight text-neutral-900"><?php echo esc_html( $option['label'] ); ?></span>
								<?php if ( ! empty( $option['description'] ) ) : ?>
								<span class="text-[13px] leading-snug text-neutral-500"><?php echo esc_html( $option['description'] ); ?></span>
								<?php endif; ?>
							</button>
						<?php endforeach; ?>
					</div>

					<div class="mt-8 flex flex-wrap items-center justify-between gap-3">
						<button
							type="button"
							class="kf-quiz__nav-btn rounded-md border border-neutral-300 px-4 py-2.5 text-sm font-semibold text-neutral-600 transition-colors hover:border-pg-green hover:text-pg-green disabled:cursor-not-allowed disabled:opacity-40"
							data-quiz-back
							<?php echo $is_first ? 'disabled' : ''; ?>
						>
							<?php echo esc_html( $config['backLabel'] ); ?>
						</button>
						<button
							type="button"
							class="kf-quiz__nav-btn rounded-md bg-pg-green px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-pg-green-dark disabled:cursor-not-allowed disabled:opacity-40"
							data-quiz-next
							disabled
						>
							<?php echo esc_html( $config['nextLabel'] ); ?>
						</button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="kf-quiz__gate min-h-[60vh] bg-[#f7f7f5]" data-quiz-panel="gate" hidden>
		<div class="flex min-h-[60vh] items-center justify-center px-6 py-14">
			<div class="w-full max-w-[520px] rounded-2xl bg-white px-8 py-12 text-center shadow-[0_4px_32px_rgba(0,0,0,0.08)] sm:px-10">
				<?php if ( ! empty( $config['gateIcon'] ) ) : ?>
				<div class="mb-4 text-5xl" aria-hidden="true"><?php echo esc_html( $config['gateIcon'] ); ?></div>
				<?php endif; ?>
				<h2 class="m-0 text-[28px] font-bold text-neutral-900"><?php echo esc_html( $config['gateTitle'] ); ?></h2>
				<p class="mt-3 mb-7 text-base leading-relaxed text-neutral-600"><?php echo esc_html( $config['gateText'] ); ?></p>
				<form class="kf-quiz__gate-form text-left" data-quiz-gate-form novalidate>
					<div class="mb-4 grid gap-3">
						<label class="sr-only" for="<?php echo esc_attr( $instance ); ?>-fname"><?php esc_html_e( 'First name', 'kratom-feed' ); ?></label>
						<input
							type="text"
							id="<?php echo esc_attr( $instance ); ?>-fname"
							name="first_name"
							autocomplete="given-name"
							placeholder="<?php esc_attr_e( 'First Name', 'kratom-feed' ); ?>"
							class="w-full rounded-lg border-2 border-neutral-200 px-4 py-3.5 text-base outline-none transition-colors focus:border-pg-green"
						/>
						<label class="sr-only" for="<?php echo esc_attr( $instance ); ?>-email"><?php esc_html_e( 'Email address', 'kratom-feed' ); ?></label>
						<input
							type="email"
							id="<?php echo esc_attr( $instance ); ?>-email"
							name="email"
							required
							autocomplete="email"
							placeholder="<?php esc_attr_e( 'Email Address *', 'kratom-feed' ); ?>"
							class="w-full rounded-lg border-2 border-neutral-200 px-4 py-3.5 text-base outline-none transition-colors focus:border-pg-green"
						/>
					</div>
					<button type="submit" class="w-full rounded-lg bg-pg-green px-8 py-4 text-base font-bold text-white transition-colors hover:bg-pg-green-dark" data-quiz-gate-submit>
						<?php echo esc_html( $config['gateBtn'] ); ?>
					</button>
					<?php if ( ! empty( $config['gateLegal'] ) ) : ?>
					<p class="mt-3 mb-0 text-center text-xs leading-relaxed text-neutral-400">
						<?php echo esc_html( $config['gateLegal'] ); ?>
						<?php if ( $legal_url ) : ?>
						<a class="underline hover:text-pg-green" href="<?php echo esc_url( $legal_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Privacy Policy', 'kratom-feed' ); ?></a>
						<?php endif; ?>
					</p>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</div>

	<div class="kf-quiz__results" data-quiz-panel="results" hidden>
		<header class="bg-pg-green px-6 py-12 text-center text-white">
			<div class="mx-auto max-w-xl">
				<?php if ( ! empty( $config['resultsBadge'] ) ) : ?>
				<span class="mb-3 inline-block rounded-full border border-white/30 bg-white/15 px-3.5 py-1.5 text-[13px] font-semibold">
					<?php echo esc_html( $config['resultsBadge'] ); ?>
				</span>
				<?php endif; ?>
				<h2 class="m-0 text-[26px] font-bold sm:text-4xl" data-quiz-results-title><?php echo esc_html( $config['resultsTitle'] ); ?></h2>
				<p class="mt-2 mb-0 text-base text-white/85" data-quiz-results-sub><?php echo esc_html( $config['resultsSub'] ); ?></p>
			</div>
		</header>

		<div class="mx-auto max-w-[720px] px-6 py-10 sm:py-14">
			<article class="mb-5 rounded-xl border-2 border-pg-green bg-white p-7" data-quiz-primary>
				<span class="mb-4 inline-block rounded bg-pg-green px-3 py-1 text-xs font-bold uppercase tracking-wide text-white">
					<?php echo esc_html( $config['primaryBadge'] ); ?>
				</span>
				<div class="grid items-center gap-6 sm:grid-cols-[1fr_auto]">
					<div>
						<h3 class="m-0 text-[22px] font-bold text-neutral-900" data-quiz-primary-name></h3>
						<span class="mt-1.5 inline-block rounded bg-[#f0f7e6] px-2.5 py-0.5 text-xs font-semibold text-pg-green" data-quiz-primary-format></span>
						<p class="mt-2 mb-0 text-[15px] leading-relaxed text-neutral-600" data-quiz-primary-why></p>
						<div class="mt-3 flex flex-wrap gap-1.5" data-quiz-primary-tags></div>
						<p class="mt-3 mb-0 hidden text-sm text-amber-700" data-quiz-primary-caution></p>
					</div>
					<div class="flex min-w-[140px] flex-col items-stretch gap-2 sm:items-center">
						<a href="#" class="inline-block rounded-lg bg-pg-green px-6 py-3.5 text-center text-[15px] font-bold text-white transition-colors hover:bg-pg-green-dark" data-quiz-primary-cta>
							<?php echo esc_html( $config['ctaPrimary'] ); ?>
						</a>
						<span class="text-center text-[11px] leading-snug text-neutral-400"><?php echo esc_html( $config['recTrust'] ); ?></span>
					</div>
				</div>
			</article>

			<article class="mb-5 rounded-xl border border-neutral-200 bg-neutral-50 px-7 py-6" data-quiz-secondary>
				<p class="mb-4 text-sm font-semibold uppercase tracking-wide text-neutral-400"><?php echo esc_html( $config['secondaryTitle'] ); ?></p>
				<div class="grid items-center gap-6 sm:grid-cols-[1fr_auto]">
					<div>
						<h4 class="m-0 text-lg font-bold text-neutral-900" data-quiz-secondary-name></h4>
						<span class="mt-1.5 inline-block rounded bg-[#f0f7e6] px-2.5 py-0.5 text-xs font-semibold text-pg-green" data-quiz-secondary-format></span>
						<p class="mt-2 mb-0 text-[15px] leading-relaxed text-neutral-600" data-quiz-secondary-why></p>
					</div>
					<a href="#" class="inline-block rounded-lg border-2 border-pg-green px-5 py-3 text-center text-sm font-semibold text-pg-green transition-colors hover:bg-pg-green hover:text-white" data-quiz-secondary-cta>
						<?php echo esc_html( $config['ctaSecondary'] ); ?>
					</a>
				</div>
			</article>

			<article class="mb-8 rounded-xl border border-[#f0e8c0] bg-[#fffbf0] px-7 py-6" data-quiz-bonus hidden>
				<p class="mb-4 text-sm font-semibold uppercase tracking-wide text-neutral-400"><?php echo esc_html( $config['bonusTitle'] ); ?></p>
				<div class="grid items-center gap-6 sm:grid-cols-[1fr_auto]">
					<div>
						<h4 class="m-0 text-lg font-bold text-neutral-900" data-quiz-bonus-name></h4>
						<span class="mt-1.5 inline-block rounded bg-[#f0f7e6] px-2.5 py-0.5 text-xs font-semibold text-pg-green" data-quiz-bonus-format></span>
						<p class="mt-2 mb-0 text-[15px] leading-relaxed text-neutral-600" data-quiz-bonus-why></p>
					</div>
					<a href="#" class="inline-block rounded-lg border-2 border-pg-green px-5 py-3 text-center text-sm font-semibold text-pg-green transition-colors hover:bg-pg-green hover:text-white" data-quiz-bonus-cta>
						<?php echo esc_html( $config['ctaSecondary'] ); ?>
					</a>
				</div>
			</article>

			<?php if ( ! empty( $config['trustItems'] ) ) : ?>
			<ul class="mb-8 grid list-none grid-cols-1 gap-3 p-0 sm:grid-cols-2 lg:grid-cols-4">
				<?php foreach ( $config['trustItems'] as $item ) : ?>
				<li class="rounded-lg border border-neutral-200 bg-white px-3 py-3.5 text-center text-[13px] font-semibold text-neutral-700">
					<?php echo esc_html( $item['label'] ); ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

			<div class="text-center">
				<button type="button" class="border-0 bg-transparent text-sm text-neutral-400 underline hover:text-pg-green" data-quiz-retake>
					<?php echo esc_html( $config['retakeLabel'] ); ?>
				</button>
			</div>
		</div>
	</div>
</section>
