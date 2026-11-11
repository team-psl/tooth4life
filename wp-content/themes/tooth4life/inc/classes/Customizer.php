<?php
/**
 * Contact settings and film URL fallbacks.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Customizer
{
	/**
	 * @return array<string, string>
	 */
	public static function defaults(): array
	{
		return [
			'email' => 'enquiry@tooth4life.co.uk',
			'phone' => '',
			'whatsapp' => '',
			'video_stem_cells' => '',
			'video_baby_teeth' => '',
			'video_collection' => '',
		];
	}

	/**
	 * @return array{url: string, alt: string}
	 */
	public static function hero(): array
	{
		$id = (int) get_theme_mod('tooth4life_hero_image', 0);
		if ($id > 0) {
			$url = wp_get_attachment_image_url($id, 'large');
			if (is_string($url) && $url !== '') {
				$alt = (string) get_post_meta($id, '_wp_attachment_image_alt', true);

				return [
					'url' => $url,
					'alt' => $alt !== '' ? $alt : __('Hero image', 'tooth4life'),
				];
			}
		}

		return [
			'url' => get_theme_file_uri('assets/hero.png'),
			'alt' => __('Illustration of a tooth with a light ring and small cell-like shapes.', 'tooth4life'),
		];
	}

	public static function get(string $key): string
	{
		$defaults = self::defaults();
		$default = $defaults[$key] ?? '';
		$value = get_theme_mod('tooth4life_' . $key, $default);

		return is_string($value) ? trim($value) : $default;
	}

	public static function register(): void
	{
		add_action('customize_register', [self::class, 'customize']);
	}

	public static function customize(\WP_Customize_Manager $wp_customize): void
	{
		$wp_customize->add_section('tooth4life', [
			'title' => __('Tooth4Life', 'tooth4life'),
			'description' => __('Email is public. Phone and WhatsApp stay hidden until a number is saved. The hero image is the home page illustration. Film URLs here are used when a film has no URL of its own.', 'tooth4life'),
			'priority' => 30,
		]);

		$fields = [
			'email' => [__('Email', 'tooth4life'), 'sanitize_email'],
			'phone' => [__('Phone', 'tooth4life'), 'sanitize_text_field'],
			'whatsapp' => [__('WhatsApp number, with country code', 'tooth4life'), 'sanitize_text_field'],
			'video_stem_cells' => [__('Fallback URL: What are stem cells?', 'tooth4life'), [Custom_Fields::class, 'sanitize_href']],
			'video_baby_teeth' => [__('Fallback URL: Why baby teeth are valuable', 'tooth4life'), [Custom_Fields::class, 'sanitize_href']],
			'video_collection' => [__('Fallback URL: How collection works', 'tooth4life'), [Custom_Fields::class, 'sanitize_href']],
		];

		$wp_customize->add_setting('tooth4life_hero_image', [
			'default' => 0,
			'type' => 'theme_mod',
			'sanitize_callback' => 'absint',
			'transport' => 'refresh',
		]);
		$wp_customize->add_control(new \WP_Customize_Media_Control($wp_customize, 'tooth4life_hero_image', [
			'label' => __('Hero image', 'tooth4life'),
			'description' => __('Replace the home page illustration. Clear it to use the theme image again.', 'tooth4life'),
			'section' => 'tooth4life',
			'mime_type' => 'image',
		]));

		foreach ($fields as $key => $field) {
			$wp_customize->add_setting('tooth4life_' . $key, [
				'default' => self::defaults()[$key],
				'type' => 'theme_mod',
				'sanitize_callback' => $field[1],
				'transport' => 'refresh',
			]);
			$wp_customize->add_control('tooth4life_' . $key, [
				'label' => $field[0],
				'section' => 'tooth4life',
				'type' => 'text',
			]);
		}
	}
}
