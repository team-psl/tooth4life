<?php
/**
 * Theme supports so classic content, blocks, and embeds render.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Setup
{
	public static function register(): void
	{
		add_action('after_setup_theme', [self::class, 'supports']);
	}

	public static function supports(): void
	{
		load_theme_textdomain('tooth4life', get_template_directory() . '/languages');

		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_theme_support('responsive-embeds');
		add_theme_support('align-wide');
		add_theme_support('wp-block-styles');
		add_theme_support('appearance-tools');
		add_theme_support('editor-styles');
		add_editor_style('assets/editor.css');
		add_action('init', [self::class, 'assign_templates']);
		add_action('save_post_page', [self::class, 'assign_saved_template'], 20, 2);
		add_filter('template_include', [self::class, 'use_designed_template']);
		add_action('template_redirect', [self::class, 'redirect_recreated_page']);
		add_action('enqueue_block_editor_assets', [self::class, 'editor_notice']);
		add_action('load-post.php', [self::class, 'drop_identical_autosave']);
		add_filter('show_admin_bar', [self::class, 'preview_admin_bar']);
		add_filter('upgrader_source_selection', [self::class, 'protect_local_theme'], 9, 4);
		add_theme_support('html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		]);
		add_theme_support('custom-logo', [
			'height' => 120,
			'width' => 120,
			'flex-height' => true,
			'flex-width' => true,
		]);

		register_nav_menus([
			'primary' => __('Primary', 'tooth4life'),
		]);
	}

	/**
	 * Designed pages keep their template if one is already chosen.
	 * A recreated page, including one WordPress named about-2, still gets it.
	 */
	public static function assign_templates(): void
	{
		if ((string) get_option('tooth4life_templates_v2') === '1') {
			return;
		}

		foreach (self::published_pages() as $page) {
			self::assign_saved_template((int) $page->ID, $page);
		}
		update_option('tooth4life_templates_v2', '1', false);
	}

	public static function assign_saved_template(int $post_id, \WP_Post $post): void
	{
		if ($post->post_type !== 'page' || $post->post_status === 'auto-draft') {
			return;
		}
		if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
			return;
		}

		$template = self::template_for_slug(self::designed_slug($post));
		if ($template === '' || (string) get_page_template_slug($post_id) !== '') {
			return;
		}

		update_post_meta($post_id, '_wp_page_template', $template);
	}

	public static function use_designed_template(string $template): string
	{
		if (! is_singular('page')) {
			return $template;
		}
		$post = get_queried_object();
		if (! $post instanceof \WP_Post) {
			return $template;
		}
		$chosen = (string) get_page_template_slug($post);
		if ($chosen !== '' && $chosen !== 'default') {
			return $template;
		}
		$file = self::template_for_slug(self::designed_slug($post));
		if ($file === '') {
			return $template;
		}
		$path = get_theme_file_path($file);

		return is_readable($path) ? $path : $template;
	}

	public static function redirect_recreated_page(): void
	{
		if (! is_404()) {
			return;
		}
		$base = self::slug_base((string) get_query_var('pagename'));
		if ($base === '' || self::template_for_slug($base) === '') {
			return;
		}
		$page = self::published_page($base);
		if (! $page instanceof \WP_Post || $page->post_name === $base) {
			return;
		}
		$target = (string) get_permalink($page);
		if ($target === '') {
			return;
		}
		wp_safe_redirect($target, 301);
		exit;
	}

	public static function designed_slug(\WP_Post $post): string
	{
		$slug = self::slug_base($post->post_name);
		if ($slug !== '' && self::template_for_slug($slug) !== '') {
			return $slug;
		}
		$titles = [
			'about' => 'about',
			'contact us' => 'contact-us',
			'contact' => 'contact-us',
			'for parents' => 'for-parents',
			'research' => 'research',
		];
		$title = strtolower(trim(html_entity_decode($post->post_title, ENT_QUOTES, 'UTF-8')));

		return $titles[$title] ?? '';
	}

	public static function published_page(string $slug): ?\WP_Post
	{
		$exact = get_page_by_path($slug);
		if ($exact instanceof \WP_Post && $exact->post_status === 'publish') {
			return $exact;
		}
		foreach (self::published_pages() as $page) {
			if (self::designed_slug($page) === $slug) {
				return $page;
			}
		}

		return null;
	}

	/**
	 * @return array<int, \WP_Post>
	 */
	private static function published_pages(): array
	{
		$pages = get_posts([
			'post_type' => 'page',
			'post_status' => 'publish',
			'posts_per_page' => 100,
			'orderby' => 'date',
			'order' => 'DESC',
			'suppress_filters' => true,
		]);
		$found = [];
		foreach ($pages as $page) {
			if ($page instanceof \WP_Post) {
				$found[] = $page;
			}
		}

		return $found;
	}

	private static function slug_base(string $slug): string
	{
		$slug = trim($slug, '/');
		if ($slug === '') {
			return '';
		}
		$bare = $slug;
		if (preg_match('/^(.*)-\d+$/', $slug, $match) === 1) {
			$bare = $match[1];
		}
		if (self::template_for_slug($slug) !== '') {
			return $slug;
		}
		if ($bare !== $slug && self::template_for_slug($bare) !== '') {
			return $bare;
		}

		return '';
	}

	/**
	 * The local theme folder is mounted into Docker. Replacing it from a zip
	 * deletes the project files and then cannot remove the mount.
	 *
	 * @param string|\WP_Error $source
	 * @param array<string, mixed> $hook_extra
	 * @return string|\WP_Error
	 */
	public static function protect_local_theme($source, $remote_source, $upgrader, array $hook_extra)
	{
		unset($remote_source, $upgrader);
		if (is_wp_error($source) || wp_get_environment_type() !== 'local') {
			return $source;
		}
		if (($hook_extra['type'] ?? '') !== 'theme') {
			return $source;
		}
		$folder = basename(untrailingslashit((string) $source));
		if ($folder !== 'tooth4life') {
			return $source;
		}

		return new \WP_Error(
			't4l_local_theme',
			__('This local site reads the theme from the project folder, so a zip upload cannot replace it. The theme here is already current. Upload the zip only on the other WordPress site.', 'tooth4life')
		);
	}

	public static function drop_identical_autosave(): void
	{
		$post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
		if ($post_id <= 0) {
			return;
		}
		$autosave = wp_get_post_autosave($post_id);
		$post = get_post($post_id);
		if (! $autosave instanceof \WP_Post || ! $post instanceof \WP_Post) {
			return;
		}
		if ($autosave->post_content === $post->post_content && $autosave->post_title === $post->post_title) {
			wp_delete_post_revision((int) $autosave->ID);
		}
	}

	public static function preview_admin_bar(bool $show): bool
	{
		if (isset($_GET['t4l_preview']) && $_GET['t4l_preview'] === '1') {
			return false;
		}

		return $show;
	}

	public static function editor_notice(): void
	{
		$post = get_post();
		if (! $post instanceof \WP_Post || $post->post_type !== 'page') {
			return;
		}

		$designed = [
			'templates/about.php',
			'templates/contact.php',
			'templates/for-parents.php',
			'templates/research.php',
			'templates/guide.php',
		];
		$template = (string) get_page_template_slug($post);
		$is_front = (int) get_option('page_on_front') === (int) $post->ID;
		$is_designed = in_array($template, $designed, true);
		if (! $is_front && ! $is_designed) {
			return;
		}

		$message = $is_front
			? __('Edit the homepage text in the settings sidebar, then save. Film titles are edited on each Film, and questions on each FAQ.', 'tooth4life')
			: __('This page layout comes from the theme. Blocks you add here appear at the bottom of the page.', 'tooth4life');
		$preview = add_query_arg('t4l_preview', '1', (string) get_permalink($post));
		$fields = [];
		$defaults = [];
		if ($is_front) {
			foreach (Content::home_fields() as $key => $field) {
				$fields[] = [
					'key' => $key,
					'label' => $field['label'],
					'multiline' => $field['multiline'],
				];
				$defaults[$key] = $field['default'];
			}
		}

		$script = 'wp.domReady(function () {'
			. 'wp.data.dispatch("core/notices").createNotice("info", ' . wp_json_encode($message) . ', { isDismissible: true, id: "t4l-layout" });'
			. 'var url=' . wp_json_encode($preview) . ';'
			. 'if(!document.getElementById("t4l-preview-style")){'
			. 'var style=document.createElement("style");'
			. 'style.id="t4l-preview-style";'
			. 'style.textContent=".editor-resizable-editor{position:relative;}#t4l-page-preview{position:absolute;inset:0;z-index:2;width:100%;height:100%;border:0;background:#fff;}.editor-resizable-editor__resize-handle{z-index:6;}";'
			. 'document.head.appendChild(style);'
			. '}'
			. 'var mount=function(){'
			. 'var slot=document.querySelector(".editor-resizable-editor")||document.querySelector(".editor-visual-editor")||document.querySelector(".edit-post-visual-editor");'
			. 'if(!slot){return;}'
			. 'var preview=document.getElementById("t4l-page-preview");'
			. 'if(preview&&preview.parentElement===slot){return;}'
			. 'if(!preview){'
			. 'preview=document.createElement("iframe");'
			. 'preview.id="t4l-page-preview";'
			. 'preview.title="Page preview";'
			. 'preview.src=url;'
			. '}'
			. 'slot.appendChild(preview);'
			. '};'
			. 'mount();'
			. 'new MutationObserver(mount).observe(document.body,{childList:true,subtree:true});'
			. 'var saving=false;'
			. 'wp.data.subscribe(function(){'
			. 'var now=wp.data.select("core/editor").isSavingPost();'
			. 'var ok=wp.data.select("core/editor").didPostSaveRequestSucceed();'
			. 'if(saving&&!now&&ok){var frame=document.getElementById("t4l-page-preview");if(frame){frame.src=url+"&t="+Date.now();}}'
			. 'saving=now;'
			. '});';
		if ($is_front) {
			$script .= 'var fields=' . wp_json_encode($fields) . ';'
				. 'var defaults=' . wp_json_encode($defaults) . ';'
				. '(function(wp){'
				. 'var Panel=(wp.editor&&wp.editor.PluginDocumentSettingPanel)||(wp.editPost&&wp.editPost.PluginDocumentSettingPanel);'
				. 'if(!Panel||!wp.plugins){return;}'
				. 'var el=wp.element.createElement;'
				. 'function HomeText(){'
				. 'var meta=wp.data.useSelect(function(select){return select("core/editor").getEditedPostAttribute("meta")||{};},[]);'
				. 'var editPost=wp.data.useDispatch("core/editor").editPost;'
				. 'return el(Panel,{name:"t4l-home-text",title:"Homepage text"},'
				. '[el("p",{key:"help",style:{marginTop:0}},"Change a field, then click Save. The preview updates after the save.")].concat(fields.map(function(field){'
				. 'var Control=field.multiline?wp.components.TextareaControl:wp.components.TextControl;'
				. 'var stored=meta[field.key];'
				. 'var value=(typeof stored==="string"&&stored!=="")?stored:defaults[field.key];'
				. 'return el(Control,{key:field.key,label:field.label,value:value,onChange:function(next){var update={};update[field.key]=next;editPost({meta:update});}});'
				. '})));'
				. '}'
				. 'wp.plugins.registerPlugin("t4l-home-text",{render:HomeText});'
				. 'wp.data.dispatch("core/edit-post").openGeneralSidebar("edit-post/document");'
				. 'if(wp.data.select("core/edit-post").isEditorPanelOpened&&!wp.data.select("core/edit-post").isEditorPanelOpened("t4l-home-text")){wp.data.dispatch("core/edit-post").toggleEditorPanelOpened("t4l-home-text");}'
				. '})(window.wp);';
		}
		$script .= '});';

		wp_add_inline_script('wp-edit-post', $script);
	}

	private static function template_for_slug(string $slug): string
	{
		$map = [
			'about' => 'templates/about.php',
			'contact-us' => 'templates/contact.php',
			'for-parents' => 'templates/for-parents.php',
			'research' => 'templates/research.php',
		];
		if (isset($map[$slug])) {
			return $map[$slug];
		}
		if (Guides::find($slug) !== null) {
			return 'templates/guide.php';
		}

		return '';
	}
}
