<?php
/**
 * Demo pages, films, and FAQs. Safe to run more than once.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Demo
{
	public static function register(): void
	{
		add_action('admin_menu', [self::class, 'menu']);
		add_action('admin_notices', [self::class, 'notice']);
	}

	/**
	 * @return array{created: array<int, string>, existing: array<int, string>, errors: array<int, string>}
	 */
	public static function install(): array
	{
		$report = ['created' => [], 'existing' => [], 'errors' => []];
		$front_id = 0;
		$pages = [];

		foreach (self::pages() as $page) {
			$existing = self::find_page($page['slug']);
			if ($existing instanceof \WP_Post) {
				$id = (int) $existing->ID;
				if ($existing->post_status === 'trash') {
					wp_untrash_post($id);
				}
				wp_update_post(['ID' => $id, 'post_status' => 'publish']);
				$report['existing'][] = $page['slug'];
			} else {
				$inserted = wp_insert_post([
					'post_type' => 'page',
					'post_status' => 'publish',
					'post_title' => $page['title'],
					'post_name' => $page['slug'],
					'post_content' => '',
					'comment_status' => 'closed',
					'ping_status' => 'closed',
				], true);
				if (is_wp_error($inserted) || $inserted === 0) {
					$report['errors'][] = $page['slug'];
					continue;
				}
				$id = (int) $inserted;
				$report['created'][] = $page['slug'];
			}

			update_post_meta($id, '_wp_page_template', $page['template'] !== '' ? $page['template'] : 'default');
			$pages[] = ['id' => $id, 'title' => $page['title']];
			if (! empty($page['front'])) {
				$front_id = $id;
			}
		}

		if ($front_id > 0) {
			update_option('show_on_front', 'page');
			update_option('page_on_front', $front_id);
		}

		$tagline = (string) get_option('blogdescription');
		if ($tagline === '' || $tagline === 'Just another WordPress site') {
			update_option('blogdescription', 'The Dental Stem Cells Storage Service');
		}
		$blogname = (string) get_option('blogname');
		if ($blogname === '' || $blogname === 'WordPress' || strcasecmp($blogname, 'Tooth4Life') === 0) {
			update_option('blogname', 'Tooth4Life');
		}

		self::ensure_guides();
		self::ensure_permalinks();
		self::sync_menu($pages);
		self::seed_films();
		self::seed_faqs();
		update_option('tooth4life_demo_installed', time(), false);
		flush_rewrite_rules(false);

		return $report;
	}

	/**
	 * @return array<int, array{title: string, slug: string, template: string, front?: bool}>
	 */
	private static function pages(): array
	{
		return [
			['title' => 'Home', 'slug' => 'home', 'template' => '', 'front' => true],
			['title' => 'For Parents', 'slug' => 'for-parents', 'template' => 'templates/for-parents.php'],
			['title' => 'Research', 'slug' => 'research', 'template' => 'templates/research.php'],
			['title' => 'About', 'slug' => 'about', 'template' => 'templates/about.php'],
			['title' => 'Contact Us', 'slug' => 'contact-us', 'template' => 'templates/contact.php'],
		];
	}

	/**
	 * @return array<int, array{title: string, slug: string, parent: string}>
	 */
	private static function guides(): array
	{
		$pages = [];
		foreach (Guides::all() as $slug => $page) {
			$pages[] = [
				'title' => $page['label'],
				'slug' => $slug,
				'parent' => $page['parent'],
			];
		}

		return $pages;
	}

	private static function ensure_guides(): void
	{
		foreach (self::guides() as $page) {
			$parent = self::find_page($page['parent']);
			if (! $parent instanceof \WP_Post) {
				continue;
			}
			$existing = self::find_page($page['parent'] . '/' . $page['slug']);
			if (! $existing instanceof \WP_Post) {
				$existing = self::find_page($page['slug']);
			}
			if ($existing instanceof \WP_Post) {
				$id = (int) $existing->ID;
				if ($existing->post_status === 'trash') {
					wp_untrash_post($id);
				}
				wp_update_post([
					'ID' => $id,
					'post_parent' => (int) $parent->ID,
					'post_status' => 'publish',
					'post_title' => $page['title'],
				]);
			} else {
				$inserted = wp_insert_post([
					'post_type' => 'page',
					'post_status' => 'publish',
					'post_title' => $page['title'],
					'post_name' => $page['slug'],
					'post_parent' => (int) $parent->ID,
					'post_content' => '',
					'comment_status' => 'closed',
					'ping_status' => 'closed',
				], true);
				if (is_wp_error($inserted) || $inserted === 0) {
					continue;
				}
				$id = (int) $inserted;
			}
			update_post_meta($id, '_wp_page_template', 'templates/guide.php');
		}
	}

	private static function find_page(string $slug): ?\WP_Post
	{
		$found = get_page_by_path($slug);
		if ($found instanceof \WP_Post) {
			return $found;
		}
		$posts = get_posts([
			'name' => $slug,
			'post_type' => 'page',
			'post_status' => ['publish', 'draft', 'pending', 'private', 'trash'],
			'posts_per_page' => 1,
			'suppress_filters' => true,
		]);
		$post = $posts[0] ?? null;

		return $post instanceof \WP_Post ? $post : null;
	}

	/**
	 * @param array<int, array{id: int, title: string}> $pages
	 */
	private static function sync_menu(array $pages): void
	{
		$menu = wp_get_nav_menu_object('Tooth4Life');
		if ($menu instanceof \WP_Term) {
			$menu_id = (int) $menu->term_id;
			$items = wp_get_nav_menu_items($menu_id);
			if (is_array($items)) {
				foreach ($items as $item) {
					if ($item instanceof \WP_Post) {
						wp_delete_post((int) $item->ID, true);
					}
				}
			}
		} else {
			$created = wp_create_nav_menu('Tooth4Life');
			if (is_wp_error($created)) {
				return;
			}
			$menu_id = (int) $created;
		}

		$position = 1;
		foreach ($pages as $page) {
			wp_update_nav_menu_item($menu_id, 0, [
				'menu-item-title' => $page['title'],
				'menu-item-object' => 'page',
				'menu-item-object-id' => $page['id'],
				'menu-item-type' => 'post_type',
				'menu-item-status' => 'publish',
				'menu-item-position' => $position,
			]);
			$position++;
		}

		$locations = get_theme_mod('nav_menu_locations');
		if (! is_array($locations)) {
			$locations = [];
		}
		$locations['primary'] = $menu_id;
		set_theme_mod('nav_menu_locations', $locations);
	}

	private static function seed_films(): void
	{
		$films = [
			[
				'slug' => 'what-are-stem-cells',
				'title' => 'What are stem cells?',
				'excerpt' => 'Stem cells renew themselves and can become many kinds of tissue. A particular group of them lives in the soft centre of a tooth.',
				'order' => 1,
				'meta' => [
					'_t4l_kicker' => '01',
					'_t4l_duration' => '60–90 sec',
					'_t4l_setting_key' => 'video_stem_cells',
					'_t4l_href' => home_url('/research/stem-cell-science/'),
					'_t4l_href_label' => 'Read the science',
				],
			],
			[
				'slug' => 'why-baby-teeth',
				'title' => 'Why baby teeth are valuable',
				'excerpt' => 'A baby tooth usually falls out on its own, while it is still healthy. An adult tooth is often removed because something has already gone wrong.',
				'order' => 2,
				'meta' => [
					'_t4l_kicker' => '02',
					'_t4l_duration' => '60–90 sec',
					'_t4l_setting_key' => 'video_baby_teeth',
					'_t4l_href' => home_url('/research/why-tooth-stem-cells/'),
					'_t4l_href_label' => 'For parents',
				],
			],
			[
				'slug' => 'how-collection-works',
				'title' => 'How collection works',
				'excerpt' => 'When the tooth comes out, it goes into a Tooth4Life kit, then to the laboratory, where the dental stem cells are extracted and frozen.',
				'order' => 3,
				'meta' => [
					'_t4l_kicker' => '03',
					'_t4l_duration' => '60–90 sec',
					'_t4l_setting_key' => 'video_collection',
					'_t4l_href' => home_url('/for-parents/storage-to-treatment/'),
					'_t4l_href_label' => 'See the steps',
				],
			],
		];

		foreach ($films as $film) {
			self::seed_post('t4l_film', $film['slug'], $film['title'], $film['excerpt'], $film['order'], $film['meta']);
		}
	}

	private static function seed_faqs(): void
	{
		$order = 1;
		foreach (Content::fallback_faqs() as $faq) {
			$slug = sanitize_title($faq['question']);
			self::seed_post('t4l_faq', $slug, $faq['question'], $faq['answer'], $order, []);
			$order++;
		}
	}

	/**
	 * @param array<string, string> $meta
	 */
	private static function seed_post(string $type, string $slug, string $title, string $body, int $order, array $meta): void
	{
		$existing = get_posts([
			'name' => $slug,
			'post_type' => $type,
			'post_status' => ['publish', 'draft', 'pending', 'private', 'trash'],
			'posts_per_page' => 1,
			'suppress_filters' => true,
		]);
		if ($existing !== []) {
			return;
		}

		$id = wp_insert_post([
			'post_type' => $type,
			'post_status' => 'publish',
			'post_title' => $title,
			'post_name' => $slug,
			'post_excerpt' => $type === 't4l_film' ? $body : '',
			'post_content' => $type === 't4l_film' ? '' : $body,
			'menu_order' => $order,
		], true);
		if (is_wp_error($id) || $id === 0) {
			return;
		}
		foreach ($meta as $key => $value) {
			update_post_meta((int) $id, $key, $value);
		}
	}

	private static function ensure_permalinks(): void
	{
		if ((string) get_option('permalink_structure') === '') {
			update_option('permalink_structure', '/%postname%/');
		}
		if (! function_exists('insert_with_markers')) {
			require_once ABSPATH . 'wp-admin/includes/misc.php';
		}
		$htaccess = ABSPATH . '.htaccess';
		$existing = is_readable($htaccess) ? (string) file_get_contents($htaccess) : '';
		if (strpos($existing, 'RewriteEngine On') !== false) {
			return;
		}
		insert_with_markers($htaccess, 'WordPress', [
			'<IfModule mod_rewrite.c>',
			'RewriteEngine On',
			'RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]',
			'RewriteBase /',
			'RewriteRule ^index\.php$ - [L]',
			'RewriteCond %{REQUEST_FILENAME} !-f',
			'RewriteCond %{REQUEST_FILENAME} !-d',
			'RewriteRule . /index.php [L]',
			'</IfModule>',
		]);
	}

	public static function notice(): void
	{
		if (! current_user_can('manage_options') || get_option('tooth4life_demo_installed')) {
			return;
		}
		$screen = function_exists('get_current_screen') ? get_current_screen() : null;
		if ($screen instanceof \WP_Screen && $screen->id === 'appearance_page_tooth4life-demo') {
			return;
		}
		echo '<div class="notice notice-info"><p>';
		echo esc_html__('The Tooth4Life demo is not installed yet.', 'tooth4life');
		echo ' <a href="' . esc_url(admin_url('themes.php?page=tooth4life-demo')) . '">';
		echo esc_html__('Install the demo', 'tooth4life');
		echo '</a></p></div>';
	}

	public static function menu(): void
	{
		add_theme_page(
			__('Tooth4Life demo', 'tooth4life'),
			__('Tooth4Life demo', 'tooth4life'),
			'manage_options',
			'tooth4life-demo',
			[self::class, 'page']
		);
	}

	public static function page(): void
	{
		if (! current_user_can('manage_options')) {
			wp_die(esc_html__('You do not have permission to install the demo.', 'tooth4life'));
		}

		$report = null;
		if (isset($_POST['tooth4life_install_demo'])) {
			check_admin_referer('tooth4life_install_demo');
			$report = self::install();
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e('Tooth4Life demo', 'tooth4life'); ?></h1>
			<p><?php esc_html_e('Creates the five pages, the primary menu, three films, and the FAQ entries. Existing pages and films are kept. Templates and the menu are refreshed.', 'tooth4life'); ?></p>
			<p><?php esc_html_e('Custom and marketplace plugins live in wp-content/plugins on your machine, mounted into WordPress.', 'tooth4life'); ?></p>
			<?php if (is_array($report)) : ?>
				<div class="notice notice-success"><p><?php echo esc_html(sprintf('Demo ready. Created %1$d pages, refreshed %2$d.', count($report['created']), count($report['existing']))); ?></p></div>
			<?php endif; ?>
			<form method="post">
				<?php wp_nonce_field('tooth4life_install_demo'); ?>
				<p><button type="submit" class="button button-primary" name="tooth4life_install_demo" value="1"><?php esc_html_e('Install demo pages', 'tooth4life'); ?></button></p>
			</form>
		</div>
		<?php
	}
}
