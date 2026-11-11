<?php
/**
 * Site header.
 *
 * @package Tooth4Life
 */

use Tooth4Life\Brand;
use Tooth4Life\Customizer;
use Tooth4Life\Navigation;

if (! defined('ABSPATH')) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class('flex min-h-dvh flex-col bg-paper font-sans text-ink antialiased'); ?>>
<?php wp_body_open(); ?>
<a class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:inline-flex focus:min-h-12 focus:items-center focus:bg-ink focus:px-4 focus:text-white" href="#content"><?php esc_html_e('Skip to content', 'tooth4life'); ?></a>
<header class="t4l-header">
	<div class="t4l-bar t4l-wrap">
		<a class="t4l-logo" href="<?php echo esc_url(home_url('/')); ?>">
			<?php Brand::mark('h-10 w-auto shrink-0'); ?>
			<span class="t4l-wordmark">
				<span class="text-xl font-semibold leading-none text-ink">Tooth4Life</span>
				<span class="t4l-tagline"><?php esc_html_e('The Dental Stem Cells Storage Service', 'tooth4life'); ?></span>
			</span>
		</a>
		<?php $email_url = ($email = Customizer::get('email')) !== '' ? 'mailto:' . $email : home_url('/contact-us/'); ?>
		<nav class="t4l-nav t4l-nav-bar" aria-label="<?php esc_attr_e('Primary', 'tooth4life'); ?>">
			<?php Navigation::render(); ?>
		</nav>
		<div class="t4l-tools">
			<a class="t4l-pill" href="<?php echo esc_url($email_url); ?>">
				<span class="lg:hidden"><?php esc_html_e('Email us', 'tooth4life'); ?></span>
				<span class="hidden lg:inline"><?php esc_html_e('Email the team', 'tooth4life'); ?></span>
			</a>
			<details class="t4l-menu">
				<summary class="t4l-burger">
					<span class="t4l-burger-lines" aria-hidden="true"></span>
					<span class="sr-only"><?php esc_html_e('Menu', 'tooth4life'); ?></span>
				</summary>
				<div class="t4l-nav">
					<nav aria-label="<?php esc_attr_e('Primary', 'tooth4life'); ?>">
						<?php Navigation::render(); ?>
					</nav>
				</div>
			</details>
		</div>
	</div>
</header>
<main id="content" class="flex flex-1 flex-col">
