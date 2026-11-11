<?php
/**
 * Film player and the homepage list that switches it.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life\Components;

use Tooth4Life\Player;

if (! defined('ABSPATH')) {
	exit;
}

final class Video
{
	/**
	 * @param array<string, string> $film
	 */
	public static function render(array $film): void
	{
		$html = Player::html((string) ($film['url'] ?? ''));
		if ($html !== '') {
			echo '<div class="t4l-player overflow-hidden">';
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- player markup is escaped by the plugin or Player::fallback().
			echo '</div>';
			return;
		}

		self::still($film);
	}

	/**
	 * One films section: a heading, then each film as its own card.
	 *
	 * @param array<int, array<string, string>> $films
	 */
	public static function sections(array $films): void
	{
		if ($films === []) {
			return;
		}
		?>
		<section class="t4l-films-section t4l-after-hero" aria-labelledby="films">
			<div class="t4l-wrap">
				<header class="t4l-films-head">
					<h2 id="films"><?php esc_html_e('Three films', 'tooth4life'); ?></h2>
					<p><?php esc_html_e('What stem cells are, why a baby tooth is the better sample, and how collection works at home.', 'tooth4life'); ?></p>
				</header>
				<div class="t4l-feature-list">
					<?php foreach ($films as $index => $film) : ?>
						<?php
						$anchor = self::anchor($film);
						$title_id = $anchor . '-title';
						$card = \Tooth4Life\Content::film_card((string) ($film['key'] ?? ''), (string) ($film['slug'] ?? ''), $index);
						$badge = $card['badge'];
						$points = $card['points'];
						?>
						<article class="t4l-feature<?php echo $index % 2 === 1 ? ' is-flip' : ''; ?> scroll-mt-24" id="<?php echo esc_attr($anchor); ?>" aria-labelledby="<?php echo esc_attr($title_id); ?>">
							<div class="t4l-feature-stage">
								<div class="t4l-feature-media">
									<?php self::render($film); ?>
								</div>
								<div class="t4l-feature-copy">
									<?php if ($badge !== '') : ?>
										<p class="t4l-feature-pill"><?php echo esc_html($badge); ?></p>
									<?php endif; ?>
									<h3 id="<?php echo esc_attr($title_id); ?>"><?php echo esc_html((string) ($film['title'] ?? '')); ?></h3>
									<?php if (! empty($film['intro'])) : ?>
										<p><?php echo esc_html((string) $film['intro']); ?></p>
									<?php endif; ?>
									<?php if (! empty($film['href'])) : ?>
										<a class="t4l-feature-link" href="<?php echo esc_url((string) $film['href']); ?>">
											<?php echo esc_html((string) ($film['href_label'] ?? __('Read more', 'tooth4life'))); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
							<?php if ($points !== []) : ?>
								<ul class="t4l-feature-facts">
									<?php foreach ($points as $point) : ?>
										<li>
											<strong><?php echo esc_html($point['title']); ?></strong>
											<span><?php echo esc_html($point['text']); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}

	/**
	 * One player, with a list of films underneath or beside it.
	 * Each item links to its panel, so the list works without JavaScript.
	 *
	 * @param array<int, array<string, string>> $films
	 */
	public static function playlist(array $films): void
	{
		if ($films === []) {
			return;
		}
		?>
		<div class="t4l-showcase mt-6 grid items-start gap-4 lg:grid-cols-12 lg:gap-6">
			<div class="lg:col-span-7">
				<?php foreach ($films as $film) : ?>
					<?php $anchor = self::anchor($film); ?>
					<div class="t4l-panel scroll-mt-24" id="<?php echo esc_attr($anchor); ?>">
						<?php self::render($film); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<ol class="t4l-films grid gap-3 lg:col-span-5">
				<?php foreach ($films as $film) : ?>
					<?php $anchor = self::anchor($film); ?>
					<li>
						<a class="t4l-film-link block px-4 py-4" href="#<?php echo esc_attr($anchor); ?>">
							<?php $meta = self::meta_line($film); ?>
							<?php if ($meta !== '') : ?>
								<span class="block text-sm text-muted"><?php echo esc_html($meta); ?></span>
							<?php endif; ?>
							<span class="<?php echo $meta !== '' ? 'mt-2 ' : ''; ?>block font-serif text-2xl text-ink"><?php echo esc_html((string) ($film['title'] ?? '')); ?></span>
							<?php if (! empty($film['intro'])) : ?>
								<span class="mt-2 block text-sm leading-relaxed text-muted"><?php echo esc_html((string) $film['intro']); ?></span>
							<?php endif; ?>
						</a>
						<?php if (! empty($film['href'])) : ?>
							<a class="mb-4 ml-4 inline-flex min-h-12 items-center text-sm text-ink underline underline-offset-4" href="<?php echo esc_url((string) $film['href']); ?>"><?php echo esc_html((string) ($film['href_label'] ?? 'Read more')); ?></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
		<?php
	}

	/**
	 * @param array<string, string> $film
	 */
	private static function still(array $film): void
	{
		$title = (string) ($film['title'] ?? '');
		$duration = (string) ($film['duration'] ?? '');
		?>
		<div class="flex aspect-video flex-col justify-end bg-[#eceae4] p-6">
			<?php if ($duration !== '') : ?>
				<p class="text-sm text-muted"><?php echo esc_html($duration); ?></p>
			<?php endif; ?>
			<p class="mt-2 max-w-sm text-2xl text-ink"><?php echo esc_html($title); ?></p>
			<?php if (current_user_can('edit_posts')) : ?>
				<p class="mt-3 text-xs text-muted"><?php esc_html_e('Add a streaming URL on this film in Films.', 'tooth4life'); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @param array<string, string> $film
	 */
	private static function anchor(array $film): string
	{
		$id = (string) ($film['id'] ?? $film['key'] ?? 'film');
		$id = sanitize_html_class($id);

		return 't4l-film-' . ($id !== '' ? $id : 'film');
	}

	/**
	 * @param array<string, string> $film
	 */
	private static function meta_line(array $film): string
	{
		$kicker = trim((string) ($film['kicker'] ?? ''));
		if (preg_match('/^(film\s*)?\d+$/i', $kicker) === 1) {
			$kicker = '';
		}
		$duration = trim((string) ($film['duration'] ?? ''));
		$parts = array_filter([$kicker, $duration], static fn (string $part): bool => $part !== '');

		return implode(' · ', $parts);
	}
}
