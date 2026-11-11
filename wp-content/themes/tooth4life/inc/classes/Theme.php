<?php
/**
 * Boots every theme module.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Theme
{
	public static function boot(): void
	{
		Setup::register();
		Assets::register();
		Player::register();
		Security::register();
		Seo::register();
		Customizer::register();
		Navigation::register();
		Post_Types::register();
		Custom_Fields::register();
		Demo::register();
	}
}
