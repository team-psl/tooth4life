<?php
/**
 * Dashicons that ship with WordPress. The video plugin uses the same set.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life\Components;

if (! defined('ABSPATH')) {
	exit;
}

final class Icon
{
	private const MAP = [
		'heart' => 'dashicons-heart',
		'shield' => 'dashicons-shield',
		'lock' => 'dashicons-lock',
		'mail' => 'dashicons-email',
		'film' => 'dashicons-video-alt3',
		'home' => 'dashicons-admin-home',
		'lab' => 'dashicons-lightbulb',
		'post' => 'dashicons-location',
		'kit' => 'dashicons-archive',
		'tooth' => 'dashicons-arrow-down-alt',
		'family' => 'dashicons-groups',
		'quote' => 'dashicons-format-quote',
		'check' => 'dashicons-yes-alt',
		'store' => 'dashicons-saved',
		'bone' => 'dashicons-grid-view',
		'nerve' => 'dashicons-share',
		'cartilage' => 'dashicons-image-filter',
		'school' => 'dashicons-welcome-learn-more',
		'help' => 'dashicons-editor-help',
		'warning' => 'dashicons-warning',
		'down' => 'dashicons-arrow-down-alt2',
		'up' => 'dashicons-arrow-up-alt2',
	];

	public static function render(string $name): void
	{
		$icon = self::MAP[$name] ?? 'dashicons-marker';
		echo '<span class="t4l-icon dashicons ' . esc_attr($icon) . '" aria-hidden="true"></span>';
	}

	public static function badge(string $name): void
	{
		echo '<span class="t4l-badge">';
		self::render($name);
		echo '</span>';
	}
}
