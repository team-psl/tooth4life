<?php
/**
 * Quoted lines. Real testimonials replace the film lines when published.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life\Components;

if (! defined('ABSPATH')) {
	exit;
}

final class Testimonials
{
	/**
	 * @param array<int, array{quote: string, source: string}> $items
	 */
	public static function render(array $items, string $heading): void
	{
		if ($items === []) {
			return;
		}
		?>
		<section aria-labelledby="t4l-lines">
			<div class="t4l-wrap py-8 md:py-10">
				<h2 id="t4l-lines" class="text-2xl font-semibold text-ink"><?php echo esc_html($heading); ?></h2>
				<div class="t4l-cols mt-6">
					<?php foreach ($items as $item) : ?>
						<figure>
							<blockquote class="text-lg leading-snug text-ink"><?php echo esc_html($item['quote']); ?></blockquote>
							<figcaption class="mt-3 text-sm text-muted"><?php echo esc_html($item['source']); ?></figcaption>
						</figure>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
