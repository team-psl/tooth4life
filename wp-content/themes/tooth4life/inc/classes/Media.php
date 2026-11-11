<?php
/**
 * Turns a public video URL into a player source.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Media
{
	public static function embed_url(string $url): string
	{
		$url = trim($url);
		if ($url === '') {
			return '';
		}

		$parts = wp_parse_url($url);
		if (! is_array($parts) || empty($parts['host']) || ! is_string($parts['host'])) {
			return '';
		}

		$host = strtolower($parts['host']);
		$path = is_string($parts['path'] ?? null) ? $parts['path'] : '';

		if (strpos($host, 'youtu.be') !== false) {
			$id = trim($path, '/');
			return preg_match('/^[A-Za-z0-9_-]{6,}$/', $id) === 1
				? 'https://www.youtube-nocookie.com/embed/' . $id
				: '';
		}

		if (strpos($host, 'youtube.com') !== false || strpos($host, 'youtube-nocookie.com') !== false) {
			if (preg_match('#/(?:embed|shorts)/([A-Za-z0-9_-]{6,})#', $path, $matches) === 1) {
				return 'https://www.youtube-nocookie.com/embed/' . $matches[1];
			}
			$query = [];
			parse_str(is_string($parts['query'] ?? null) ? $parts['query'] : '', $query);
			$id = $query['v'] ?? '';
			return is_string($id) && preg_match('/^[A-Za-z0-9_-]{6,}$/', $id) === 1
				? 'https://www.youtube-nocookie.com/embed/' . $id
				: '';
		}

		if (strpos($host, 'vimeo.com') !== false && preg_match('#/(\d+)(?:/([A-Za-z0-9]+))?#', $path, $matches) === 1) {
			$src = 'https://player.vimeo.com/video/' . $matches[1];
			if (! empty($matches[2])) {
				$src .= '?h=' . rawurlencode($matches[2]);
			}
			return $src;
		}

		if (preg_match('/\.mp4(\?|$)/i', $url) === 1) {
			return (string) esc_url_raw($url);
		}

		return '';
	}

	public static function phone_href(string $phone): string
	{
		$clean = preg_replace('/[^0-9+]/', '', $phone) ?? '';

		return $clean !== '' ? 'tel:' . $clean : '';
	}

	public static function whatsapp_href(string $number): string
	{
		$digits = preg_replace('/\D+/', '', $number) ?? '';

		return $digits !== '' ? 'https://wa.me/' . $digits : '';
	}
}
