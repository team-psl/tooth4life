<?php
/**
 * Archive fallback.
 *
 * @package Tooth4Life
 */

get_header();
?>
<section class="t4l-wrap py-16 md:py-24">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : ?>
			<?php the_post(); ?>
			<article class="border-b border-line py-8">
				<h2 class="font-serif text-3xl"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry-content mt-4 text-muted"><?php the_excerpt(); ?></div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<h1 class="font-serif text-4xl">Nothing here yet.</h1>
	<?php endif; ?>
</section>
<?php
get_footer();
