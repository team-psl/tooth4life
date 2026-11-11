<?php
/**
 * Page introduction.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life\Components;

use Tooth4Life\Components\Call_To_Action;

if (! defined('ABSPATH')) {
	exit;
}

final class Hero
{
	/**
	 * @param array{kicker?: string, title?: string, text?: array<int, string>, actions?: bool} $args
	 */
	public static function render(array $args): void
	{
		$kicker = (string) ($args['kicker'] ?? '');
		$title = (string) ($args['title'] ?? '');
		$text = is_array($args['text'] ?? null) ? $args['text'] : [];
		?>
		<div class="max-w-2xl">
			<?php if ($kicker !== '') : ?>
				<p class="t4l-kicker"><?php echo esc_html($kicker); ?></p>
			<?php endif; ?>
			<?php if ($title !== '') : ?>
				<h1 class="t4l-title"><?php echo esc_html($title); ?></h1>
			<?php endif; ?>
			<?php foreach ($text as $paragraph) : ?>
				<?php if (! is_string($paragraph) || $paragraph === '') {
					continue;
				} ?>
				<p class="mt-5 text-lg leading-relaxed text-muted"><?php echo esc_html($paragraph); ?></p>
			<?php endforeach; ?>
			<?php if (! empty($args['actions'])) : ?>
				<div class="mt-8">
					<?php Call_To_Action::actions('light'); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
