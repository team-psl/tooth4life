<?php
/**
 * One versioned stylesheet, deferred theme script, block styles left in place.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Assets
{
	public static function register(): void
	{
		add_action('wp_enqueue_scripts', [self::class, 'enqueue']);
		add_filter('style_loader_tag', [self::class, 'style_tag'], 10, 2);
		add_filter('script_loader_tag', [self::class, 'script_tag'], 10, 2);
	}

	public static function enqueue(): void
	{
		$css = get_theme_file_path('assets/app.css');
		$js = get_theme_file_path('assets/theme.js');

		wp_enqueue_style(
			'tooth4life',
			get_theme_file_uri('assets/app.css'),
			[],
			self::version($css)
		);

		if (is_readable($js)) {
			wp_enqueue_script(
				'tooth4life',
				get_theme_file_uri('assets/theme.js'),
				[],
				self::version($js),
				[
					'in_footer' => true,
					'strategy' => 'defer',
				]
			);
		}
	}

	/**
	 * Give the main sheet priority without a second preload request.
	 */
	public static function style_tag(string $html, string $handle): string
	{
		if ($handle !== 'tooth4life') {
			return $html;
		}

		return str_replace(" rel='stylesheet'", " rel='stylesheet' fetchpriority='high'", $html);
	}

	public static function script_tag(string $html, string $handle): string
	{
		if ($handle !== 'tooth4life' || strpos($html, ' defer') !== false) {
			return $html;
		}

		return str_replace('<script ', '<script defer ', $html);
	}

	private static function version(string $path): string
	{
		if (is_readable($path)) {
			return (string) filemtime($path);
		}

		return (string) wp_get_theme()->get('Version');
	}
}
