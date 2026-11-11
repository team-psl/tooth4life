<?php
/**
 * Private content types edited in wp-admin and rendered by components.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Post_Types
{
	public static function register(): void
	{
		add_action('init', [self::class, 'register_types']);
		add_filter('manage_t4l_film_posts_columns', [self::class, 'film_columns']);
		add_action('manage_t4l_film_posts_custom_column', [self::class, 'film_column'], 10, 2);
	}

	public static function register_types(): void
	{
		self::register_one('t4l_film', 'Film', 'Films', 'films', ['title', 'excerpt', 'page-attributes'], 'dashicons-format-video');
		self::register_one('t4l_faq', 'FAQ', 'FAQs', 'faqs', ['title', 'editor', 'page-attributes']);
		self::register_one('t4l_testimonial', 'Testimonial', 'Testimonials', 'testimonials', ['title', 'editor', 'page-attributes']);
	}

	/**
	 * @param array<string, string> $columns
	 * @return array<string, string>
	 */
	public static function film_columns(array $columns): array
	{
		$next = [];
		foreach ($columns as $key => $label) {
			$next[$key] = $label;
			if ($key === 'title') {
				$next['t4l_stream'] = 'Streaming URL';
			}
		}

		return $next;
	}

	public static function film_column(string $column, int $post_id): void
	{
		if ($column !== 't4l_stream') {
			return;
		}

		$url = (string) get_post_meta($post_id, '_t4l_video_url', true);
		if ($url === '') {
			esc_html_e('Not set', 'tooth4life');
			return;
		}

		echo esc_html($url);
	}

	/**
	 * @param array<int, string> $supports
	 */
	private static function register_one(string $type, string $single, string $plural, string $rest, array $supports, string $icon = 'dashicons-media-text'): void
	{
		register_post_type($type, [
			'labels' => [
				'name' => $plural,
				'singular_name' => $single,
				'add_new_item' => 'Add ' . $single,
				'edit_item' => 'Edit ' . $single,
				'menu_name' => $plural,
			],
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'has_archive' => false,
			'menu_icon' => $icon,
			'capability_type' => 'post',
			'map_meta_cap' => true,
			'supports' => $supports,
			'rewrite' => false,
			'rest_base' => $rest,
		]);
	}
}
