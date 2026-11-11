<?php
/**
 * Logo mark. Uses the real Tooth4Life icon unless a custom logo is set.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Brand
{
	public static function mark(string $class = 'h-12 w-auto'): void
	{
		$logo_id = (int) get_theme_mod('custom_logo');
		if ($logo_id > 0) {
			echo wp_get_attachment_image($logo_id, 'full', false, [
				'class' => $class,
				'alt' => '',
				'decoding' => 'async',
			]);
			return;
		}

		printf(
			'<img class="%1$s" src="%2$s" alt="" width="46" height="52" decoding="async">',
			esc_attr($class),
			esc_url(get_theme_file_uri('assets/tooth4life-icon.svg'))
		);
	}
}
