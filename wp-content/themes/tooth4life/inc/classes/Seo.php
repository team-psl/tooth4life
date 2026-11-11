<?php
/**
 * Titles and descriptions.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Seo
{
	public static function register(): void
	{
		add_filter('document_title_parts', [self::class, 'title']);
		add_action('wp_head', [self::class, 'meta'], 1);
	}

	/**
	 * @param array<string, string> $parts
	 * @return array<string, string>
	 */
	public static function title(array $parts): array
	{
		if (is_front_page()) {
			$parts['title'] = 'Tooth4Life';
			$parts['tagline'] = 'The Dental Stem Cells Storage Service';
		}

		return $parts;
	}

	public static function meta(): void
	{
		$description = self::description();
		if ($description !== '') {
			printf('<meta name="description" content="%s">' . "\n", esc_attr($description));
		}

		if (! is_front_page()) {
			return;
		}

		$email = Customizer::get('email');
		$schema = [
			'@context' => 'https://schema.org',
			'@type' => 'Organization',
			'name' => 'Tooth4Life',
			'url' => home_url('/'),
			'slogan' => 'The Dental Stem Cells Storage Service',
			'description' => Content::descriptions()['home'],
		];
		if ($email !== '') {
			$schema['email'] = $email;
		}

		echo '<script type="application/ld+json">';
		echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		echo '</script>' . "\n";
	}

	public static function description(): string
	{
		$descriptions = Content::descriptions();
		if (is_front_page()) {
			return $descriptions['home'];
		}

		$slug = get_post_field('post_name', get_queried_object_id());

		return is_string($slug) ? ($descriptions[$slug] ?? '') : '';
	}
}
