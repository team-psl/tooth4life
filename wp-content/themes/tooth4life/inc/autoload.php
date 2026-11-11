<?php
/**
 * Class map for the theme. Components stay in their own folders.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
	exit;
}

spl_autoload_register(
	static function (string $class): void {
		$map = [
			'Tooth4Life\\Theme' => '/inc/classes/Theme.php',
			'Tooth4Life\\Setup' => '/inc/classes/Setup.php',
			'Tooth4Life\\Assets' => '/inc/classes/Assets.php',
			'Tooth4Life\\Security' => '/inc/classes/Security.php',
			'Tooth4Life\\Seo' => '/inc/classes/Seo.php',
			'Tooth4Life\\Customizer' => '/inc/classes/Customizer.php',
			'Tooth4Life\\Navigation' => '/inc/classes/Navigation.php',
			'Tooth4Life\\Content' => '/inc/classes/Content.php',
			'Tooth4Life\\Media' => '/inc/classes/Media.php',
			'Tooth4Life\\Player' => '/inc/classes/Player.php',
			'Tooth4Life\\Brand' => '/inc/classes/Brand.php',
			'Tooth4Life\\Demo' => '/inc/classes/Demo.php',
			'Tooth4Life\\Guides' => '/inc/classes/Guides.php',
			'Tooth4Life\\Components\\Hero' => '/components/hero/Hero.php',
			'Tooth4Life\\Components\\Icon' => '/components/icon/Icon.php',
			'Tooth4Life\\Components\\Slider' => '/components/slider/Slider.php',
			'Tooth4Life\\Components\\Cards' => '/components/cards/Cards.php',
			'Tooth4Life\\Components\\Video' => '/components/cards/Video.php',
			'Tooth4Life\\Components\\Testimonials' => '/components/testimonials/Testimonials.php',
			'Tooth4Life\\Components\\Faq' => '/components/faq/Faq.php',
			'Tooth4Life\\Components\\Call_To_Action' => '/components/call-to-action/Call_To_Action.php',
		];

		if (! isset($map[$class])) {
			return;
		}

		$file = get_template_directory() . $map[$class];
		if (is_readable($file)) {
			require $file;
		}
	}
);
