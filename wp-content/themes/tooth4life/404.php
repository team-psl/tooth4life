<?php
/**
 * Missing page.
 *
 * @package Tooth4Life
 */

get_header();
?>
<section class="t4l-wrap py-20 md:py-28">
	<p class="text-sm text-muted">404</p>
	<h1 class="t4l-title mt-3">That page is not on this site.</h1>
	<p class="mt-4 text-lg text-muted">The pages are Home, For Parents, Research, About, and Contact Us.</p>
	<p class="mt-6"><a class="t4l-link" href="<?php echo esc_url(home_url('/')); ?>">Back to Home</a></p>
</section>
<?php
get_footer();
