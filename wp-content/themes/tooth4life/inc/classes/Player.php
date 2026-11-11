<?php
/**
 * Plays a film URL through HTML5 Video Player when that plugin is active.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Player
{
	public static function register(): void
	{
		add_filter('option_h5vp_option', [self::class, 'brand_color']);
	}

	/**
	 * Pine replaces the plugin default blue until someone picks another colour.
	 *
	 * @param mixed $value
	 * @return array<string, mixed>
	 */
	public static function brand_color($value): array
	{
		$options = is_array($value) ? $value : [];
		$current = isset($options['h5vp_player_primary_color']) ? (string) $options['h5vp_player_primary_color'] : '';
		$current_color = strtolower($current);
		if ($current_color === '' || $current_color === '#00b2ff' || $current_color === '#1c3d4e') {
			$options['h5vp_player_primary_color'] = '#1e4b9b';
		}

		return $options;
	}

	public static function html(string $url): string
	{
		$src = self::absolute_url($url);
		if ($src === '') {
			return '';
		}

		if (shortcode_exists('video_player')) {
			$markup = do_shortcode('[video_player src="' . esc_attr($src) . '" width="100%"]');
			if (is_string($markup) && ! in_array(trim($markup), ['', 'No Video Added'], true)) {
				return $markup;
			}
		}

		return self::fallback($src);
	}

	/**
	 * A full address is kept. A path is played from this site.
	 */
	public static function absolute_url(string $url): string
	{
		$url = trim($url);
		if ($url === '') {
			return '';
		}
		if (strpos($url, '//') === 0) {
			$url = (is_ssl() ? 'https:' : 'http:') . $url;
		} elseif (preg_match('#^[a-z][a-z0-9+.-]*://#i', $url) !== 1) {
			$url = home_url('/' . ltrim($url, '/'));
		}
		$src = esc_url_raw($url);
		if ($src === '' || wp_http_validate_url($src) === false) {
			return '';
		}

		return $src;
	}

	private static function fallback(string $src): string
	{
		$embed = Media::embed_url($src);
		if ($embed === '') {
			return '';
		}

		if (preg_match('/\.(mp4|webm)(\?|$)/i', $embed) === 1) {
			return '<video class="aspect-video w-full" controls playsinline preload="metadata" src="' . esc_url($embed) . '"></video>';
		}

		return '<iframe class="aspect-video w-full" src="' . esc_url($embed) . '" title="" loading="lazy" allow="fullscreen; picture-in-picture" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>';
	}
}
