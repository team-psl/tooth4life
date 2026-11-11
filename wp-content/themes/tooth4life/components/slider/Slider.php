<?php
/**
 * A short still-life slider. Buttons work without a script; the script only swaps slides.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life\Components;

if (! defined('ABSPATH')) {
	exit;
}

final class Slider
{
	/**
	 * @param array<int, array{src: string, alt: string, caption: string}> $slides
	 */
	public static function render(array $slides): void
	{
		$slides = array_values(array_filter($slides, static fn (array $slide): bool => ($slide['src'] ?? '') !== ''));
		if ($slides === []) {
			return;
		}
		?>
		<div class="t4l-slider" data-slider>
			<div>
				<?php foreach ($slides as $index => $slide) : ?>
					<figure class="t4l-slide" data-slide <?php echo $index === 0 ? '' : 'hidden'; ?>>
						<img class="aspect-[4/3] w-full object-cover" src="<?php echo esc_url($slide['src']); ?>" alt="<?php echo esc_attr($slide['alt']); ?>" <?php echo $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?> width="1200" height="900">
						<figcaption class="px-4 py-3 text-sm leading-relaxed text-muted"><?php echo esc_html($slide['caption']); ?></figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
			<div class="mt-3 flex items-center justify-between gap-3">
				<div class="flex" role="group" aria-label="<?php esc_attr_e('Showcase images', 'tooth4life'); ?>">
					<?php foreach ($slides as $index => $slide) : ?>
						<button class="inline-flex h-11 w-11 items-center justify-center" type="button" data-dot <?php echo $index === 0 ? 'aria-current="true"' : ''; ?> aria-label="<?php echo esc_attr(sprintf(__('Show image %d', 'tooth4life'), $index + 1)); ?>">
							<span class="h-2 w-2 rounded-full bg-line"></span>
						</button>
					<?php endforeach; ?>
				</div>
				<div class="flex gap-2">
					<button class="inline-flex min-h-12 items-center text-sm text-ink underline underline-offset-4" type="button" data-prev><?php esc_html_e('Previous', 'tooth4life'); ?></button>
					<button class="inline-flex min-h-12 items-center text-sm text-ink underline underline-offset-4" type="button" data-next><?php esc_html_e('Next', 'tooth4life'); ?></button>
				</div>
			</div>
		</div>
		<?php
	}
}
