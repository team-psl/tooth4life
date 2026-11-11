<?php
/**
 * Theme supports so classic content, blocks, and embeds render.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Setup
{
	public static function register(): void
	{
		add_action('after_setup_theme', [self::class, 'supports']);
	}

	public static function supports(): void
	{
		load_theme_textdomain('tooth4life', get_template_directory() . '/languages');

		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_theme_support('responsive-embeds');
		add_theme_support('align-wide');
		add_theme_support('wp-block-styles');
		add_theme_support('appearance-tools');
		add_theme_support('editor-styles');
		add_editor_style('assets/editor.css');
		add_theme_support('html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		]);
		add_theme_support('custom-logo', [
			'height' => 120,
			'width' => 120,
			'flex-height' => true,
			'flex-width' => true,
		]);

		register_nav_menus([
			'primary' => __('Primary', 'tooth4life'),
		]);
	}
}
