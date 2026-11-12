<?php
/**
 * Primary navigation.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Navigation
{
	public static function register(): void
	{
		add_filter('nav_menu_link_attributes', [self::class, 'link_attributes'], 10, 2);
		add_action('template_redirect', [self::class, 'redirect_sections']);
	}

	/**
	 * @return array<int, array{label: string, slug: string, url: string, children?: array<int, array{label: string, slug: string, url: string, summary?: string}>}>
	 */
	public static function items(): array
	{
		return [
			['label' => __('Home', 'tooth4life'), 'slug' => 'home', 'url' => home_url('/')],
			['label' => __('For Parents', 'tooth4life'), 'slug' => 'for-parents', 'url' => '', 'children' => Guides::nav('for-parents')],
			['label' => __('Research', 'tooth4life'), 'slug' => 'research', 'url' => '', 'children' => Guides::nav('research')],
			['label' => __('About', 'tooth4life'), 'slug' => 'about', 'url' => self::page_url('about')],
			['label' => __('Contact Us', 'tooth4life'), 'slug' => 'contact-us', 'url' => self::page_url('contact-us')],
		];
	}

	public static function render(): void
	{
		echo '<ul class="nav-primary">';
		foreach (self::items() as $item) {
			$children = $item['children'] ?? [];
			$exact = $item['url'] !== '' && self::is_exact($item['slug']);
			$section = $exact;
			foreach ($children as $child) {
				if (self::is_exact($child['slug'])) {
					$section = true;
				}
			}
			$classes = [];
			if ($children !== []) {
				$classes[] = 't4l-has-sub';
			}
			if ($section) {
				$classes[] = 'is-section';
			}
			echo '<li' . ($classes !== [] ? ' class="' . esc_attr(implode(' ', $classes)) . '"' : '') . '>';
			if ($children !== []) {
				echo '<button type="button" class="t4l-sub-toggle" aria-haspopup="true" aria-expanded="false">';
				echo esc_html($item['label']);
				echo '<span class="t4l-caret" aria-hidden="true"></span>';
				echo '</button>';
			} else {
				printf(
					'<a href="%1$s"%2$s>%3$s</a>',
					esc_url($item['url']),
					$exact ? ' aria-current="page"' : '',
					esc_html($item['label'])
				);
			}
			if ($children !== []) {
				echo '<ul class="t4l-sub">';
				foreach ($children as $child) {
					printf(
						'<li><a href="%1$s"%2$s>%3$s</a></li>',
						esc_url($child['url']),
						self::is_exact($child['slug']) ? ' aria-current="page"' : '',
						esc_html($child['label'])
					);
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}

	public static function redirect_sections(): void
	{
		if (is_page('for-parents')) {
			wp_safe_redirect(home_url('/for-parents/stored-cells/'), 301);
			exit;
		}
		if (is_page('research')) {
			wp_safe_redirect(home_url('/research/stem-cell-science/'), 301);
			exit;
		}
	}

	public static function page_url(string $slug): string
	{
		$page = Setup::published_page($slug);
		if ($page instanceof \WP_Post) {
			return (string) get_permalink($page);
		}

		return home_url('/' . $slug . '/');
	}

	private static function is_exact(string $slug): bool
	{
		if ($slug === 'home') {
			return is_front_page();
		}
		if (is_page($slug)) {
			return true;
		}
		$post = get_queried_object();

		return $post instanceof \WP_Post && Setup::designed_slug($post) === $slug;
	}

	/**
	 * @param array<string, mixed> $args
	 */
	public static function fallback(array $args): void
	{
		unset($args);
		self::render();
	}

	/**
	 * @param array<string, string> $atts
	 */
	public static function link_attributes(array $atts, \WP_Post $item): array
	{
		$classes = is_array($item->classes) ? $item->classes : [];
		if (in_array('current-menu-item', $classes, true) || in_array('current_page_item', $classes, true)) {
			$atts['aria-current'] = 'page';
		}

		return $atts;
	}
}
