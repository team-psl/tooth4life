<?php
/**
 * Template Name: Contact
 *
 * @package Tooth4Life
 */

use Tooth4Life\Customizer;

get_header();
$email = Customizer::get('email');
?>
<article class="t4l-wrap grid gap-10 py-10 md:py-14 lg:grid-cols-2 lg:gap-16">
	<header>
		<h1 class="t4l-title">Talk to Tooth4Life directly.</h1>
		<p class="mt-5 max-w-xl text-lg leading-relaxed text-muted">If you are interested in storing stem cells from your child’s milk tooth, or you would like to know more about this service, contact the team. There is no form to fill in.</p>
		<?php if ($email !== '') : ?>
			<p class="mt-8 text-2xl text-ink"><a class="underline underline-offset-4" href="<?php echo esc_url('mailto:' . $email); ?>"><?php echo esc_html($email); ?></a></p>
		<?php endif; ?>
	</header>
	<section aria-labelledby="what-to-include">
		<h2 id="what-to-include" class="text-2xl font-semibold text-ink">Useful things to include</h2>
		<ul class="t4l-steps mt-4">
			<li>Your name, and the best way to reply.</li>
			<li>Your child’s first name and age.</li>
			<li>Whether a tooth is loose, or has just come out.</li>
			<li>If you want a collection kit sent to you.</li>
		</ul>
	</section>
</article>
<?php
get_footer();
