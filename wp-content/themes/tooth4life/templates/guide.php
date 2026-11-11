<?php
/**
 * Template Name: Guide
 *
 * A subpage under For Parents or Research.
 *
 * @package Tooth4Life
 */

use Tooth4Life\Guides;

get_header();
$slug = (string) get_post_field('post_name', get_queried_object_id());
$guide = Guides::find($slug);
if ($guide === null) {
	echo '<article class="t4l-doc"><header class="t4l-doc-head t4l-wrap"><h1 class="t4l-title">' . esc_html(get_the_title()) . '</h1></header></article>';
	get_footer();
	return;
}
$parent_url = home_url('/' . $guide['parent'] . '/');
$parent_label = $guide['parent'] === 'research' ? __('Research', 'tooth4life') : __('For Parents', 'tooth4life');
?>
<article class="t4l-doc t4l-guide">
	<header class="t4l-doc-head t4l-wrap">
		<p class="t4l-kicker"><a href="<?php echo esc_url($parent_url); ?>"><?php echo esc_html($parent_label); ?></a></p>
		<h1 class="t4l-title"><?php echo esc_html($guide['title']); ?></h1>
		<p><?php echo esc_html($guide['lead']); ?></p>
	</header>
	<?php foreach ($guide['sections'] as $section) : ?>
		<?php Guides::section($section); ?>
	<?php endforeach; ?>
	<div class="t4l-doc-block">
		<div class="t4l-wrap">
			<?php Guides::siblings($slug); ?>
		</div>
	</div>
</article>
<?php
get_footer();
