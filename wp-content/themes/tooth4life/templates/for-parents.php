<?php
/**
 * Template Name: For Parents
 *
 * @package Tooth4Life
 */

use Tooth4Life\Components\Cards;
use Tooth4Life\Components\Video;
use Tooth4Life\Content;
use Tooth4Life\Guides;

get_header();
$films = Content::films();
$baby = $films[1] ?? $films[0];
$collection = $films[2] ?? $films[0];
?>
<article class="t4l-doc">
	<header class="t4l-doc-head t4l-wrap">
		<h1 class="t4l-title"><?php esc_html_e('A wobbly tooth can be collected at home.', 'tooth4life'); ?></h1>
		<p><?php esc_html_e('The Tooth4Life collection takes a few moments. You use the kit the company provides, then post the tooth. The laboratory extracts the dental stem cells and freezes them.', 'tooth4life'); ?></p>
		<?php Guides::index('for-parents'); ?>
	</header>

	<section id="baby-teeth" class="t4l-doc-block" aria-labelledby="baby-teeth-title">
		<div class="t4l-wrap">
			<h2 id="baby-teeth-title"><?php esc_html_e('Why a baby tooth is the better sample', 'tooth4life'); ?></h2>
			<p class="t4l-doc-lead"><?php esc_html_e('Children are born without teeth. The first set comes in and is later replaced. A baby tooth falls out on its own, without disease, trauma, or a dental procedure.', 'tooth4life'); ?></p>
			<p class="t4l-doc-lead"><?php esc_html_e('When it comes out it is usually healthy, untouched by decay, and free from inflammation. That makes a clean tissue sample.', 'tooth4life'); ?></p>
			<div class="t4l-pair">
				<div>
					<h3><?php esc_html_e('Baby tooth', 'tooth4life'); ?></h3>
					<ul>
						<li><?php esc_html_e('Loosens gently and sheds naturally.', 'tooth4life'); ?></li>
						<li><?php esc_html_e('Usually healthy, and free from inflammation.', 'tooth4life'); ?></li>
						<li><?php esc_html_e('A strong starting sample for storing dental stem cells.', 'tooth4life'); ?></li>
					</ul>
				</div>
				<div>
					<h3><?php esc_html_e('Adult tooth', 'tooth4life'); ?></h3>
					<ul>
						<li><?php esc_html_e('Often removed because of decay, infection, or gum disease.', 'tooth4life'); ?></li>
						<li><?php esc_html_e('Those conditions can damage the pulp and reduce the quality of the cells.', 'tooth4life'); ?></li>
						<li><?php esc_html_e('Usually the least favoured tissue for this kind of collection.', 'tooth4life'); ?></li>
					</ul>
				</div>
			</div>
			<div class="t4l-doc-film">
				<?php Video::render($baby); ?>
			</div>
		</div>
	</section>

	<section id="collection" class="t4l-doc-block" aria-labelledby="collection-title">
		<div class="t4l-wrap">
			<h2 id="collection-title"><?php esc_html_e('How the collection works', 'tooth4life'); ?></h2>
			<p class="t4l-doc-lead"><?php esc_html_e('It is safe, and it happens at home. The child hands the tooth to a parent. The parent does the rest.', 'tooth4life'); ?></p>
			<div class="t4l-doc-film">
				<?php Video::render($collection); ?>
			</div>
			<div class="t4l-doc-steps">
				<?php Cards::steps(Content::collection_steps()); ?>
			</div>
		</div>
	</section>

	<section class="t4l-doc-block" aria-labelledby="arranging">
		<div class="t4l-wrap">
			<h2 id="arranging"><?php esc_html_e('What you are arranging', 'tooth4life'); ?></h2>
			<?php
			Cards::strip([
				['icon' => 'shield', 'title' => __('A storage service', 'tooth4life'), 'text' => __('Tooth4Life isolates and stores stem cells from a milk tooth on a subscription. Contact the team to ask for a collection kit.', 'tooth4life')],
				['icon' => 'freeze', 'title' => __('Cells kept frozen', 'tooth4life'), 'text' => __('The laboratory freezes the cells at very low temperatures so they can be preserved for the future.', 'tooth4life')],
				['icon' => 'lock', 'title' => __('Not a treatment today', 'tooth4life'), 'text' => __('Personalised treatments may become possible as medical science advances. Storing the cells is not a promise that a therapy is available now.', 'tooth4life')],
			]);
			?>
		</div>
	</section>
</article>
<?php
get_footer();
