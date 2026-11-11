<?php
/**
 * Tooth4Life theme bootstrap.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
	exit;
}

require get_template_directory() . '/inc/autoload.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/custom-fields.php';

Tooth4Life\Theme::boot();
