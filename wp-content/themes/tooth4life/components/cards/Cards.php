<?php
/**
 * Repeatable content blocks: reasons, steps, film index, comparisons.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life\Components;

if (! defined('ABSPATH')) {
	exit;
}

final class Cards
{
	/**
	 * @param array<int, array<string, string>> $films
	 */
	public static function film_index(array $films): void
	{
		?>
		<ol class="divide-y divide-line border-y border-line">
			<?php foreach ($films as $film) : ?>
				<li class="py-5">
					<?php if (! empty($film['duration'])) : ?>
						<p class="text-sm text-muted"><?php echo esc_html((string) $film['duration']); ?></p>
					<?php endif; ?>
					<h2 class="mt-2 font-serif text-2xl text-ink"><?php echo esc_html((string) ($film['title'] ?? '')); ?></h2>
					<p class="mt-2 text-sm leading-relaxed text-muted"><?php echo esc_html((string) ($film['intro'] ?? '')); ?></p>
					<?php if (! empty($film['href'])) : ?>
						<a class="mt-3 inline-flex min-h-12 items-center text-sm text-ink underline underline-offset-4" href="<?php echo esc_url((string) $film['href']); ?>"><?php echo esc_html((string) ($film['href_label'] ?? 'Read more')); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
		<?php
	}

	/**
	 * Three short points. Used once, on the home page.
	 *
	 * @param array<int, array{title: string, text: string, icon?: string}> $items
	 */
	public static function strip(array $items): void
	{
		echo '<ol class="t4l-know">';
		foreach ($items as $item) {
			?>
			<li>
				<?php self::know_icon((string) ($item['icon'] ?? '')); ?>
				<h3><?php echo esc_html($item['title']); ?></h3>
				<p><?php echo esc_html($item['text']); ?></p>
			</li>
			<?php
		}
		echo '</ol>';
	}

	private static function know_icon(string $name): void
	{
		$paths = [
			'shield' => '<path d="M12 3.6 6.8 5.5v4.6c0 3.1 2.1 5.3 5.2 6.2 3.1-.9 5.2-3.1 5.2-6.2V5.5L12 3.6Z"/>',
			'heart' => '<path d="M12 18.4s-5.6-3.4-7.4-6.6C3.2 9.6 4 7 6.4 6.3c1.5-.5 3 .1 3.9 1.3.9-1.2 2.4-1.8 3.9-1.3 2.4.7 3.2 3.3 1.8 5.5-1.8 3.2-7.4 6.6-7.4 6.6Z"/>',
			'lock' => '<path d="M8.2 10.2V8.4a3.8 3.8 0 0 1 7.6 0v1.8"/><path d="M7.2 10.2h9.6V18H7.2v-7.8Z"/>',
			'freeze' => '<path d="M12 4.2v15.6M12 12l6.4-3.7M12 12 5.6 8.3M12 12l6.4 3.7M12 12 5.6 15.7"/>',
			'bone' => '<path d="M16.6 9.6c.6-.6 1.5 0 2.2 0a2.2 2.2 0 1 0 0-4.4c-.3 0-.5.2-.5.5a2.2 2.2 0 1 0-4.4 0c0 .7.6 1.6 0 2.2l-6.2 6.2c-.6.6-1.5 0-2.2 0a2.2 2.2 0 0 0 0 4.4c.3 0 .5.2.5.5a2.2 2.2 0 1 0 4.4 0c0-.7-.6-1.6 0-2.2Z"/>',
			'nerve' => '<path d="M12 3.6v6.2M12 14.2v6.2M12 9.8 6.4 15.6M12 9.8l5.6 5.8"/><circle cx="12" cy="9.8" r="1.35" fill="currentColor" stroke="none"/>',
			'disc' => '<circle cx="12" cy="12" r="4.4"/><circle cx="12" cy="12" r="1.3"/>',
			'tooth' => '<path d="M12 3.8c2.4 0 4 1.6 4.6 3.6.5 1.7.2 3.4-.4 5-.6 1.5-1.4 2.8-1.7 4.1-.3 1.1-1 2.3-2.5 2.3s-2.2-1.2-2.5-2.3c-.3-1.3-1.1-2.6-1.7-4.1-.6-1.6-.9-3.3-.4-5C8 5.4 9.6 3.8 12 3.8Z"/>',
		];
		$d = $paths[$name] ?? $paths['shield'];
		echo '<span class="t4l-know-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round">' . $d . '</svg></span>';
	}

	public static function numbered(array $items): void
	{
		echo '<ol class="t4l-cols">';
		foreach ($items as $item) {
			?>
			<li>
				<h3 class="text-lg font-semibold text-ink"><?php echo esc_html($item['title']); ?></h3>
				<p class="mt-2 leading-relaxed text-muted"><?php echo esc_html($item['text']); ?></p>
			</li>
			<?php
		}
		echo '</ol>';
	}

	/**
	 * @param array<int, array{title: string, text: string, icon?: string}> $steps
	 */
	public static function steps(array $steps): void
	{
		echo '<ol class="t4l-steps">';
		foreach ($steps as $step) {
			?>
			<li>
				<h3 class="text-lg font-semibold text-ink"><?php echo esc_html($step['title']); ?></h3>
				<p class="mt-1 leading-relaxed text-muted"><?php echo esc_html($step['text']); ?></p>
			</li>
			<?php
		}
		echo '</ol>';
	}

	/**
	 * @param array{title: string, items: array<int, string>} $left
	 * @param array{title: string, items: array<int, string>} $right
	 */
	public static function comparison(array $left, array $right): void
	{
		echo '<div class="t4l-cols t4l-cols-2">';
		self::column($left);
		self::column($right);
		echo '</div>';
	}

	/**
	 * @param array{title: string, items: array<int, string>} $column
	 */
	private static function column(array $column): void
	{
		?>
		<section>
			<h3 class="text-xl font-semibold text-ink"><?php echo esc_html($column['title']); ?></h3>
			<ul class="mt-3 list-disc space-y-2 pl-5 leading-relaxed text-muted">
				<?php foreach ($column['items'] as $item) : ?>
					<li><?php echo esc_html($item); ?></li>
				<?php endforeach; ?>
			</ul>
		</section>
		<?php
	}
}
