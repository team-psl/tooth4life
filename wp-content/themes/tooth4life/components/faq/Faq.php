<?php
/**
 * Questions, using native disclosure so no script is required.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life\Components;

use Tooth4Life\Content;

if (! defined('ABSPATH')) {
	exit;
}

final class Faq
{
	public static function render(): void
	{
		$items = Content::faqs();
		if ($items === []) {
			return;
		}
		?>
		<section class="t4l-faq" aria-labelledby="t4l-faq">
			<div class="t4l-wrap">
				<h2 id="t4l-faq"><?php echo esc_html(Content::home('_t4l_home_faq_title')); ?></h2>
				<div class="t4l-faq-list">
					<?php foreach ($items as $item) : ?>
						<details>
							<summary>
								<span><?php echo esc_html($item['question']); ?></span>
								<span class="t4l-faq-mark" aria-hidden="true"></span>
							</summary>
							<p><?php echo esc_html($item['answer']); ?></p>
						</details>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
