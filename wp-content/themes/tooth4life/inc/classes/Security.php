<?php
/**
 * Theme-level hardening. Output escaping stays at the template boundary.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Security
{
	public static function register(): void
	{
		add_action('init', [self::class, 'cleanup']);
		add_filter('xmlrpc_enabled', '__return_false');
		add_filter('the_generator', '__return_empty_string');
		add_filter('rest_endpoints', [self::class, 'hide_user_routes']);
		add_action('send_headers', [self::class, 'headers']);
		add_action('template_redirect', [self::class, 'block_author_enum']);
	}

	public static function cleanup(): void
	{
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'wp_shortlink_wp_head');
		remove_action('wp_head', 'rest_output_link_wp_head');
		remove_action('wp_head', 'wp_oembed_add_discovery_links');
	}

	/**
	 * @param array<string, mixed> $endpoints
	 * @return array<string, mixed>
	 */
	public static function hide_user_routes(array $endpoints): array
	{
		if (is_user_logged_in()) {
			return $endpoints;
		}

		unset($endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)']);

		return $endpoints;
	}

	public static function headers(): void
	{
		if (is_admin() || headers_sent()) {
			return;
		}

		header('X-Content-Type-Options: nosniff');
		header('X-Frame-Options: SAMEORIGIN');
		header('Referrer-Policy: strict-origin-when-cross-origin');
		header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
	}

	public static function block_author_enum(): void
	{
		if (! is_author() && ! isset($_GET['author'])) {
			return;
		}

		wp_safe_redirect(home_url('/'), 301);
		exit;
	}
}
