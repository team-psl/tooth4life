<?php
/**
 * Single post.
 *
 * @package Tooth4Life
 */

get_header();
?>
<article class="entry-content t4l-wrap py-16 md:py-24">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : ?>
			<?php the_post(); ?>
			<h1 class="font-serif text-4xl text-ink"><?php the_title(); ?></h1>
			<div class="mt-8 text-lg leading-relaxed text-muted"><?php the_content(); ?></div>
		<?php endwhile; ?>
	<?php endif; ?>
</article>
<?php
get_footer();
