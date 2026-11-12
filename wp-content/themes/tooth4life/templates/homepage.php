<?php
/**
 * Home.
 *
 * @package Tooth4Life
 */

use Tooth4Life\Components\Faq;
use Tooth4Life\Components\Video;
use Tooth4Life\Content;
use Tooth4Life\Customizer;

get_header();
$films = Content::films();
$hero = Customizer::hero();
?>
<section class="t4l-hero">
	<img class="t4l-hero-art" src="<?php echo esc_url($hero['url']); ?>" alt="<?php echo esc_attr($hero['alt']); ?>" width="2117" height="743" fetchpriority="high">
	<div class="t4l-hero-grid t4l-wrap">
		<div class="t4l-hero-copy">
			<p class="t4l-hero-service"><?php echo esc_html(Content::home('_t4l_home_kicker')); ?></p>
			<h1 class="t4l-hero-title"><?php echo Content::home_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in Content::home_title(). ?></h1>
			<p class="t4l-hero-lede"><?php echo esc_html(Content::home('_t4l_home_lede')); ?></p>
			<p class="t4l-hero-note"><?php echo esc_html(Content::home('_t4l_home_note')); ?></p>
			<p class="t4l-hero-action">
				<a class="t4l-pill" href="<?php echo esc_url(home_url('/for-parents/storage-to-treatment/')); ?>"><?php echo esc_html(Content::home('_t4l_home_button')); ?> <span aria-hidden="true">→</span></a>
			</p>
		</div>
	</div>
</section>

<?php Video::sections($films); ?>
<?php Faq::render(); ?>
<?php
get_footer();
