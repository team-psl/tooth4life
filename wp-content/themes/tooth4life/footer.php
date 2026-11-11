<?php
/**
 * Site footer.
 *
 * @package Tooth4Life
 */

use Tooth4Life\Brand;
use Tooth4Life\Components\Call_To_Action;
use Tooth4Life\Content;
use Tooth4Life\Navigation;

if (! defined('ABSPATH')) {
	exit;
}

Content::extra();
Call_To_Action::band();
?>
</main>
<footer class="t4l-footer">
	<div class="t4l-foot t4l-wrap">
		<div class="t4l-foot-main">
			<a class="t4l-logo" href="<?php echo esc_url(home_url('/')); ?>">
				<?php Brand::mark('h-9 w-auto shrink-0'); ?>
				<span class="t4l-wordmark">
					<span class="text-lg font-semibold leading-none text-ink">Tooth4Life</span>
					<span class="t4l-tagline"><?php esc_html_e('The Dental Stem Cells Storage Service', 'tooth4life'); ?></span>
				</span>
			</a>
			<nav aria-label="<?php esc_attr_e('Footer', 'tooth4life'); ?>">
				<ul class="flex flex-wrap gap-x-5">
					<?php foreach (Navigation::items() as $item) : ?>
						<?php
						if ($item['slug'] === 'home') {
							continue;
						}
						$href = $item['url'] !== '' ? $item['url'] : ($item['children'][0]['url'] ?? '');
						if ($href === '') {
							continue;
						}
						?>
						<li><a class="inline-flex min-h-11 items-center text-sm text-ink/80 hover:text-ink" href="<?php echo esc_url($href); ?>"><?php echo esc_html($item['label']); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>
		<div class="t4l-dundee">
			<img src="<?php echo esc_url(get_theme_file_uri('assets/university-of-dundee.jpg')); ?>" alt="<?php esc_attr_e('University of Dundee', 'tooth4life'); ?>" width="307" height="141">
			<div>
				<p><?php esc_html_e('Based on stem cell research conducted at Dundee Dental School and Hospital, University of Dundee, UK.', 'tooth4life'); ?></p>
				<p class="t4l-dundee-note"><?php esc_html_e('This site explains the storage service. It does not give a diagnosis, and it does not guarantee a future treatment.', 'tooth4life'); ?></p>
			</div>
		</div>
		<p class="t4l-copy"><?php echo esc_html(sprintf(__('© %s Tooth4Life. All rights reserved.', 'tooth4life'), wp_date('Y'))); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
