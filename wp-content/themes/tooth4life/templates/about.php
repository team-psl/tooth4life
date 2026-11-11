<?php
/**
 * Template Name: About
 *
 * @package Tooth4Life
 */

use Tooth4Life\Components\Cards;

get_header();
?>
<article class="t4l-doc">
	<header class="t4l-doc-head t4l-wrap">
		<h1 class="t4l-title"><?php esc_html_e('A storage service for stem cells in milk teeth.', 'tooth4life'); ?></h1>
		<p><?php esc_html_e('Tooth4Life is a life science company offering a subscription service to isolate and store stem cells from milk teeth, which can be used later for personalised treatment of medical conditions.', 'tooth4life'); ?></p>
	</header>

	<section class="t4l-doc-block" aria-labelledby="the-service">
		<div class="t4l-wrap">
			<h2 id="the-service"><?php esc_html_e('The service', 'tooth4life'); ?></h2>
			<p class="t4l-doc-lead"><?php esc_html_e('Parents send a naturally shed baby tooth in a collection kit. At the Tooth4Life laboratory, the dental stem cells are extracted and frozen at very low temperatures so they can be preserved for that child.', 'tooth4life'); ?></p>
			<p class="t4l-doc-lead"><?php esc_html_e('As medical science advances, these cells may one day be used in personalised treatments. Saving them is a step taken now, for a future that is still being built.', 'tooth4life'); ?></p>
		</div>
	</section>

	<section class="t4l-doc-block" aria-labelledby="dundee">
		<div class="t4l-wrap">
			<h2 id="dundee"><?php esc_html_e('The science it comes from', 'tooth4life'); ?></h2>
			<div class="t4l-uni">
				<img src="<?php echo esc_url(get_theme_file_uri('assets/university-of-dundee.jpg')); ?>" alt="<?php esc_attr_e('University of Dundee', 'tooth4life'); ?>" width="307" height="141">
				<div>
					<p class="t4l-doc-lead"><?php esc_html_e('Tooth4Life is based on stem cell research conducted at Dundee Dental School and Hospital, University of Dundee, UK.', 'tooth4life'); ?></p>
					<p class="t4l-doc-lead"><?php esc_html_e('Dundee Dental School and Hospital is part of the University of Dundee. That research is the scientific basis of the storage service. Parents do not send the tooth to the university. Collection happens at home, and the kit goes to the Tooth4Life laboratory.', 'tooth4life'); ?></p>
					<p class="t4l-doc-lead"><?php esc_html_e('With permission, the company can sell surplus cells for medical research into new drugs. Cells are not passed on for research unless that permission is given.', 'tooth4life'); ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="t4l-doc-block" aria-labelledby="stored-for">
		<div class="t4l-wrap">
			<h2 id="stored-for"><?php esc_html_e('What a stored tooth is for', 'tooth4life'); ?></h2>
			<?php
			Cards::strip([
				['icon' => 'heart', 'title' => __('Your child', 'tooth4life'), 'text' => __('The cells are preserved so they may be available for personalised treatment if future medicine can use them.', 'tooth4life')],
				['icon' => 'disc', 'title' => __('The wider field', 'tooth4life'), 'text' => __('A healthy baby tooth is also a useful sample for studying healing and regeneration. Research use of surplus cells needs your permission.', 'tooth4life')],
				['icon' => 'tooth', 'title' => __('A small act', 'tooth4life'), 'text' => __('One naturally shed tooth. A kit, a post box, and a laboratory.', 'tooth4life')],
			]);
			?>
			<p class="t4l-doc-more"><a class="t4l-link" href="<?php echo esc_url(home_url('/for-parents/storage-to-treatment/')); ?>"><?php esc_html_e('How parents collect a tooth', 'tooth4life'); ?></a></p>
		</div>
	</section>
</article>
<?php
get_footer();
