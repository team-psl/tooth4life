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
			<p class="t4l-hero-service"><?php esc_html_e('The Dental Stem Cells Storage Service', 'tooth4life'); ?></p>
			<h1 class="t4l-hero-title">Stem cells from a <em>milk tooth</em></h1>
			<p class="t4l-hero-lede">Tooth4Life isolates and stores stem cells from milk teeth, the baby teeth children shed naturally. Personalised treatment is only a possibility as medical science advances.</p>
			<p class="t4l-hero-note">The service is based on stem cell research at Dundee Dental School and Hospital, University of Dundee.</p>
			<p class="t4l-hero-action">
				<a class="t4l-pill" href="<?php echo esc_url(home_url('/for-parents/storage-to-treatment/')); ?>"><?php esc_html_e('How collection works', 'tooth4life'); ?> <span aria-hidden="true">→</span></a>
			</p>
		</div>
	</div>
</section>

<?php Video::sections($films); ?>
<?php Faq::render(); ?>
<?php
get_footer();
