<?php
/**
 * Direct contact actions and the closing band. No form.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life\Components;

use Tooth4Life\Customizer;
use Tooth4Life\Media;

if (! defined('ABSPATH')) {
	exit;
}

final class Call_To_Action
{
	public static function actions(string $tone = 'light', bool $include_email = true): void
	{
		$email = Customizer::get('email');
		$phone = Customizer::get('phone');
		$phone_href = Media::phone_href($phone);
		$whatsapp_href = Media::whatsapp_href(Customizer::get('whatsapp'));
		unset($tone);
		?>
		<div class="flex flex-wrap gap-x-6 gap-y-2">
			<?php if ($include_email && $email !== '') : ?>
				<a class="t4l-pill" href="<?php echo esc_url('mailto:' . $email); ?>"><?php esc_html_e('Email the team', 'tooth4life'); ?></a>
			<?php endif; ?>
			<?php if ($phone_href !== '') : ?>
				<a class="t4l-link" href="<?php echo esc_url($phone_href); ?>"><?php echo esc_html(sprintf(__('Call %s', 'tooth4life'), $phone)); ?></a>
			<?php endif; ?>
			<?php if ($whatsapp_href !== '') : ?>
				<a class="t4l-link" href="<?php echo esc_url($whatsapp_href); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('WhatsApp', 'tooth4life'); ?></a>
			<?php endif; ?>
		</div>
		<?php
	}

	public static function band(): void
	{
		if (is_page_template('templates/contact.php')) {
			return;
		}
		?>
		<section class="t4l-ask" aria-labelledby="ask-tooth4life">
			<div class="t4l-ask-inner t4l-wrap">
				<h2 id="ask-tooth4life">Ask about storing a milk tooth.</h2>
				<p>If you are interested in storing stem cells from your child’s milk tooth, or you want to know more, write to the Tooth4Life team.</p>
				<?php self::actions('light'); ?>
			</div>
		</section>
		<?php
	}
}
