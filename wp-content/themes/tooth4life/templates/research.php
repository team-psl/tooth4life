<?php
/**
 * Template Name: Research
 *
 * @package Tooth4Life
 */

use Tooth4Life\Components\Cards;
use Tooth4Life\Components\Video;
use Tooth4Life\Content;
use Tooth4Life\Guides;

get_header();
$films = Content::films();
$film = $films[0] ?? null;
?>
<article class="t4l-doc t4l-research">
	<header class="t4l-doc-head t4l-wrap" id="stem-cells">
		<h1 class="t4l-title"><?php esc_html_e('Dental pulp stem cells', 'tooth4life'); ?></h1>
		<p><?php esc_html_e('Stem cells can renew themselves and become many kinds of tissue. They play a part in how we grow, how we heal, and how organs repair. One of the less obvious places they are found is inside a tooth.', 'tooth4life'); ?></p>
		<?php Guides::index('research'); ?>
		<?php if (is_array($film)) : ?>
			<div class="t4l-doc-film">
				<?php Video::render($film); ?>
			</div>
		<?php endif; ?>
	</header>

	<section class="t4l-doc-block" aria-labelledby="pulp">
		<div class="t4l-wrap">
			<h2 id="pulp"><?php esc_html_e('Inside the pulp', 'tooth4life'); ?></h2>
			<p class="t4l-doc-lead"><?php esc_html_e('Dental pulp stem cells live in that soft centre. Like other stem cells, they can divide, renew, and multiply. In research, they can be guided to become like bone cells, nerve cells, cartilage cells, and more.', 'tooth4life'); ?></p>
			<p class="t4l-doc-lead"><?php esc_html_e('Scientists are interested because of that range. It is potential, not a treatment Tooth4Life performs today.', 'tooth4life'); ?></p>
		</div>
	</section>

	<section class="t4l-doc-block" aria-labelledby="studying">
		<div class="t4l-wrap">
			<h2 id="studying"><?php esc_html_e('What researchers are studying', 'tooth4life'); ?></h2>
			<?php
			Cards::strip([
				['icon' => 'bone', 'title' => __('Bone', 'tooth4life'), 'text' => __('The cells can be guided toward cells like bone cells. Research asks whether that could help repair hard tissue in the future.', 'tooth4life')],
				['icon' => 'nerve', 'title' => __('Nerve', 'tooth4life'), 'text' => __('They can be guided toward cells like nerve cells. This is an area of regenerative research, not a clinic treatment offered here.', 'tooth4life')],
				['icon' => 'disc', 'title' => __('Cartilage', 'tooth4life'), 'text' => __('They can also be guided toward cells like cartilage cells. The interest is the range of tissues one dental sample might one day inform.', 'tooth4life')],
			]);
			?>
			<p class="t4l-doc-lead"><?php esc_html_e('A naturally shed baby tooth is the sample Tooth4Life uses, because it is usually still healthy.', 'tooth4life'); ?></p>
			<p class="t4l-doc-more"><a class="t4l-link" href="<?php echo esc_url(home_url('/research/why-tooth-stem-cells/')); ?>"><?php esc_html_e('Why baby teeth are different.', 'tooth4life'); ?></a></p>
		</div>
	</section>

	<section class="t4l-doc-block" aria-label="<?php esc_attr_e('The laboratory', 'tooth4life'); ?>">
		<div class="t4l-wrap">
			<div class="t4l-pair">
				<div>
					<h2><?php esc_html_e('Where the research sits', 'tooth4life'); ?></h2>
					<p class="t4l-doc-lead"><?php esc_html_e('Tooth4Life is based on stem cell research conducted at Dundee Dental School and Hospital, University of Dundee, UK.', 'tooth4life'); ?></p>
				</div>
				<div>
					<h2><?php esc_html_e('What the laboratory does', 'tooth4life'); ?></h2>
					<p class="t4l-doc-lead"><?php esc_html_e('When a kit arrives, scientists extract the dental stem cells and freeze them at very low temperatures. Freezing preserves them. It does not, by itself, treat a disease.', 'tooth4life'); ?></p>
					<p class="t4l-doc-lead"><?php esc_html_e('With permission, surplus cells can be sold for medical research into new drugs.', 'tooth4life'); ?></p>
				</div>
			</div>
		</div>
	</section>
</article>
<?php
get_footer();
