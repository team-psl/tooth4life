<?php
/**
 * Page copy, films, and FAQs. Films and FAQs prefer records in the admin.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Content
{
	/**
	 * @return array<string, string>
	 */
	public static function descriptions(): array
	{
		return [
			'home' => 'Tooth4Life isolates and stores stem cells from children’s milk teeth for possible future personalised treatment. Based on research at the University of Dundee.',
			'for-parents' => 'How parents collect a naturally shed baby tooth at home, and why baby teeth are a healthier stem cell sample than adult teeth.',
			'research' => 'What dental pulp stem cells are, what scientists hope they can become, and how Tooth4Life preserves them.',
			'about' => 'Tooth4Life is a life science company that stores stem cells from milk teeth, based on research at Dundee Dental School and Hospital.',
			'contact-us' => 'Contact Tooth4Life about storing stem cells from your child’s milk tooth.',
		];
	}

	/**
	 * @return array{badge: string, points: array<int, array{icon: string, title: string, text: string}>}
	 */
	public static function film_card(string $key, string $slug = '', int $index = 0): array
	{
		$cards = [
			'video_stem_cells' => [
				'badge' => __('The science', 'tooth4life'),
				'points' => [
					['icon' => 'cell', 'title' => __('Where they live', 'tooth4life'), 'text' => __('Dental pulp stem cells live in the soft centre of a tooth. Like other stem cells, they can divide, renew, and multiply.', 'tooth4life')],
					['icon' => 'lab', 'title' => __('In research', 'tooth4life'), 'text' => __('In the laboratory they can be guided toward cells like bone, nerve, and cartilage. Scientists are interested because of that range.', 'tooth4life')],
					['icon' => 'shield', 'title' => __('Not a treatment', 'tooth4life'), 'text' => __('That is potential, not a treatment Tooth4Life performs today. Storing the cells is not a promise that a therapy is available now.', 'tooth4life')],
				],
			],
			'video_baby_teeth' => [
				'badge' => __('Baby teeth', 'tooth4life'),
				'points' => [
					['icon' => 'tooth', 'title' => __('Still healthy', 'tooth4life'), 'text' => __('A baby tooth falls out on its own, without disease, trauma, or a dental procedure. When it comes out it is usually healthy and free from inflammation.', 'tooth4life')],
					['icon' => 'shield', 'title' => __('A poorer sample', 'tooth4life'), 'text' => __('An adult tooth is often removed because of decay, infection, or gum disease. Those conditions can damage the pulp and reduce the quality of the cells.', 'tooth4life')],
					['icon' => 'check', 'title' => __('The sample we keep', 'tooth4life'), 'text' => __('Tooth4Life uses a naturally shed milk tooth because it is usually still healthy. That makes a clean tissue sample.', 'tooth4life')],
				],
			],
			'video_collection' => [
				'badge' => __('Collection', 'tooth4life'),
				'points' => [
					['icon' => 'home', 'title' => __('At home', 'tooth4life'), 'text' => __('Collection happens at home. Once the tooth has fallen out, the child hands it to a parent, who places it in the pot of storage solution and closes the lid.', 'tooth4life')],
					['icon' => 'post', 'title' => __('Next-day post', 'tooth4life'), 'text' => __('The packed kit goes to a Post Office or drop-off point for next-day delivery. The collection itself takes only a few moments.', 'tooth4life')],
					['icon' => 'store', 'title' => __('Frozen', 'tooth4life'), 'text' => __('At the laboratory, scientists extract the dental stem cells and freeze them at very low temperatures. Freezing preserves them. It does not, by itself, treat a disease.', 'tooth4life')],
				],
			],
		];

		$key = self::card_key($key, $slug, $index);

		return $cards[$key] ?? [
			'badge' => '',
			'points' => [],
		];
	}

	/**
	 * A saved key wins. Otherwise the film title or its place in the list picks the points.
	 */
	private static function card_key(string $key, string $slug, int $index): string
	{
		$known = ['video_stem_cells', 'video_baby_teeth', 'video_collection'];
		if (in_array($key, $known, true)) {
			return $key;
		}
		$slug = sanitize_title($slug);
		if (strpos($slug, 'baby') !== false || strpos($slug, 'teeth') !== false) {
			return 'video_baby_teeth';
		}
		if (strpos($slug, 'collect') !== false) {
			return 'video_collection';
		}
		if (strpos($slug, 'stem') !== false) {
			return 'video_stem_cells';
		}

		return $known[$index] ?? '';
	}

	/**
	 * @return array<int, array<string, string>>
	 */
	public static function films(): array
	{
		$posts = get_posts([
			'post_type' => 't4l_film',
			'post_status' => 'publish',
			'numberposts' => 6,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'suppress_filters' => true,
		]);

		if ($posts === []) {
			return self::fallback_films();
		}

		$films = [];
		foreach ($posts as $post) {
			if (! $post instanceof \WP_Post) {
				continue;
			}
			$key = (string) get_post_meta($post->ID, '_t4l_setting_key', true);
			$url = (string) get_post_meta($post->ID, '_t4l_video_url', true);
			if ($url === '' && $key !== '') {
				$url = Customizer::get($key);
			}
			$films[] = [
				'id' => (string) $post->ID,
				'key' => $key,
				'slug' => $post->post_name,
				'title' => get_the_title($post),
				'intro' => $post->post_excerpt,
				'kicker' => (string) get_post_meta($post->ID, '_t4l_kicker', true),
				'duration' => (string) get_post_meta($post->ID, '_t4l_duration', true),
				'url' => $url,
				'href' => self::page_link((string) get_post_meta($post->ID, '_t4l_href', true)),
				'href_label' => (string) get_post_meta($post->ID, '_t4l_href_label', true),
			];
		}

		return $films !== [] ? $films : self::fallback_films();
	}

	/**
	 * A stored path becomes a link on this site. A full address is left as entered.
	 */
	private static function page_link(string $value): string
	{
		$value = trim($value);
		if ($value === '') {
			return '';
		}
		if (preg_match('#^[a-z][a-z0-9+.-]*://#i', $value) === 1) {
			return $value;
		}

		return home_url('/' . ltrim($value, '/'));
	}

	/**
	 * @return array<int, array<string, string>>
	 */
	public static function fallback_films(): array
	{
		return [
			[
				'id' => 'stem-cells',
				'key' => 'video_stem_cells',
				'kicker' => '01',
				'duration' => '60–90 sec',
				'title' => 'What are stem cells?',
				'intro' => 'Stem cells renew themselves and can become many kinds of tissue. A particular group of them lives in the soft centre of a tooth.',
				'url' => Customizer::get('video_stem_cells'),
				'href' => home_url('/research/stem-cell-science/'),
				'href_label' => 'Read the science',
			],
			[
				'id' => 'baby-teeth',
				'key' => 'video_baby_teeth',
				'kicker' => '02',
				'duration' => '60–90 sec',
				'title' => 'Why baby teeth are valuable',
				'intro' => 'A baby tooth usually falls out on its own, while it is still healthy. An adult tooth is often removed because something has already gone wrong.',
				'url' => Customizer::get('video_baby_teeth'),
				'href' => home_url('/research/why-tooth-stem-cells/'),
				'href_label' => 'For parents',
			],
			[
				'id' => 'collection',
				'key' => 'video_collection',
				'kicker' => '03',
				'duration' => '60–90 sec',
				'title' => 'How collection works',
				'intro' => 'When the tooth comes out, it goes into a Tooth4Life kit, then to the laboratory, where the dental stem cells are extracted and frozen.',
				'url' => Customizer::get('video_collection'),
				'href' => home_url('/for-parents/storage-to-treatment/'),
				'href_label' => 'See the steps',
			],
		];
	}

	/**
	 * @return array<int, array{title: string, text: string}>
	 */
	public static function reasons(): array
	{
		return [
			[
				'icon' => 'shield',
				'title' => 'A sample that is still healthy',
				'text' => 'Baby teeth loosen and fall out on their own, usually before decay or infection has damaged the pulp.',
			],
			[
				'icon' => 'heart',
				'title' => 'Kept for your child',
				'text' => 'The laboratory extracts the dental stem cells and freezes them. As medical science advances, they may one day be used in personalised treatments.',
			],
			[
				'icon' => 'lock',
				'title' => 'Research only with permission',
				'text' => 'With permission, surplus cells can be sold for medical research into new drugs. That does not happen unless you allow it.',
			],
		];
	}

	/**
	 * @return array<int, array{title: string, text: string}>
	 */
	public static function home_steps(): array
	{
		return [
			['icon' => 'tooth', 'title' => 'The tooth falls out', 'text' => 'A wobbly milk tooth loosens on its own. Collection happens at home.'],
			['icon' => 'kit', 'title' => 'It goes in the kit', 'text' => 'A parent places the tooth in the pot of storage solution and closes the lid.'],
			['icon' => 'post', 'title' => 'You post it', 'text' => 'The packed kit goes to a Post Office or drop-off point for next-day delivery.'],
			['icon' => 'store', 'title' => 'The lab freezes the cells', 'text' => 'Tooth4Life extracts the dental stem cells and freezes them at very low temperatures.'],
		];
	}

	/**
	 * @return array<int, array{title: string, text: string}>
	 */
	public static function collection_steps(): array
	{
		return [
			['icon' => 'tooth', 'title' => 'A wobbly tooth', 'text' => 'The tooth loosens over time. Collection starts when it is ready to come out on its own.'],
			['icon' => 'family', 'title' => 'Passed to a parent', 'text' => 'Once it has fallen out, the child hands the tooth over. That small tooth holds dental stem cells.'],
			['icon' => 'kit', 'title' => 'Into the pot', 'text' => 'Place the tooth in the pot of storage solution supplied in the Tooth4Life kit. Close the lid tightly.'],
			['icon' => 'post', 'title' => 'Next-day post', 'text' => 'Pack the kit and take it to a Post Office or designated drop-off for next-day delivery.'],
			['icon' => 'store', 'title' => 'Frozen for the future', 'text' => 'At the Tooth4Life laboratory, scientists extract the dental stem cells and freeze them so they can be preserved.'],
		];
	}

	/**
	 * Lines from the films. Not presented as customer reviews.
	 *
	 * @return array<int, array{quote: string, source: string}>
	 */
	public static function film_lines(): array
	{
		$saved = self::testimonials();
		if ($saved !== []) {
			return $saved;
		}

		return [
			[
				'quote' => 'Inside every tooth, there is a tiny reservoir of these remarkable cells.',
				'source' => 'What are stem cells?',
			],
			[
				'quote' => 'Baby teeth fall out on their own, without disease, trauma, or a dental procedure.',
				'source' => 'Why baby teeth are valuable',
			],
			[
				'quote' => 'A small step today, with the potential to change their tomorrow.',
				'source' => 'How collection works',
			],
		];
	}

	/**
	 * @return array<int, array{quote: string, source: string}>
	 */
	public static function testimonials(): array
	{
		$posts = get_posts([
			'post_type' => 't4l_testimonial',
			'post_status' => 'publish',
			'numberposts' => 6,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'suppress_filters' => true,
		]);

		$items = [];
		foreach ($posts as $post) {
			if (! $post instanceof \WP_Post) {
				continue;
			}
			$role = (string) get_post_meta($post->ID, '_t4l_role', true);
			$items[] = [
				'quote' => wp_strip_all_tags($post->post_content),
				'source' => trim(get_the_title($post) . ($role !== '' ? ' · ' . $role : '')),
			];
		}

		return $items;
	}

	/**
	 * @return array<int, array{question: string, answer: string}>
	 */
	public static function faqs(): array
	{
		$posts = get_posts([
			'post_type' => 't4l_faq',
			'post_status' => 'publish',
			'numberposts' => 12,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'suppress_filters' => true,
		]);

		if ($posts === []) {
			return self::fallback_faqs();
		}

		$items = [];
		foreach ($posts as $post) {
			if (! $post instanceof \WP_Post) {
				continue;
			}
			$items[] = [
				'question' => get_the_title($post),
				'answer' => wp_strip_all_tags($post->post_content),
			];
		}

		return $items !== [] ? $items : self::fallback_faqs();
	}

	/**
	 * @return array<int, array{question: string, answer: string}>
	 */
	public static function fallback_faqs(): array
	{
		return [
			[
				'question' => 'Is this a treatment I can book today?',
				'answer' => 'No. Tooth4Life stores dental stem cells from a milk tooth. Personalised treatments may become possible as medical science advances. Storage is not a promise that a therapy is available now.',
			],
			[
				'question' => 'Which tooth should we send?',
				'answer' => 'A naturally shed baby tooth. It is usually still healthy. An adult tooth removed for decay, infection, or gum disease is a poorer sample, because those conditions can damage the pulp.',
			],
			[
				'question' => 'What do we do at home?',
				'answer' => 'When the tooth comes out, place it in the pot of storage solution in the Tooth4Life kit, close the lid, pack it, and take it to a Post Office or drop-off for next-day delivery.',
			],
			[
				'question' => 'Can the cells be used for research?',
				'answer' => 'Only with your permission. Surplus cells can then be sold for medical research into new drugs. Nothing is passed on for research unless you allow it.',
			],
		];
	}

	/**
	 * @return array<int, array{label: string, detail: string}>
	 */
	public static function credits(): array
	{
		return [
			[
				'label' => 'Brand',
				'detail' => 'The Tooth4Life name and icon follow the identity on tooth4life.co.uk.',
			],
			[
				'label' => 'Illustrations',
				'detail' => 'This theme does not embed third-party medical photographs.',
			],
			[
				'label' => 'Films',
				'detail' => 'Page copy follows the Tooth4Life film scripts on stem cells, baby teeth, and collection.',
			],
		];
	}

	public static function extra(): void
	{
		$designed = [
			'templates/for-parents.php',
			'templates/research.php',
			'templates/about.php',
			'templates/contact.php',
		];
		if (! is_front_page() && ! is_page_template($designed)) {
			return;
		}

		$post = get_post();
		if (! $post instanceof \WP_Post || trim($post->post_content) === '') {
			return;
		}

		echo '<div class="entry-content t4l-wrap py-12 text-lg leading-relaxed text-muted">';
		the_content();
		echo '</div>';
	}
}
