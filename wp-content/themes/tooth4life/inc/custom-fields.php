<?php
/**
 * Registered meta and meta boxes. Values are sanitized on save.
 *
 * @package Tooth4Life
 */

declare(strict_types=1);

namespace Tooth4Life;

if (! defined('ABSPATH')) {
	exit;
}

final class Custom_Fields
{
	public static function register(): void
	{
		add_action('init', [self::class, 'register_meta']);
		add_action('add_meta_boxes', [self::class, 'boxes']);
		add_action('save_post_t4l_film', [self::class, 'save_film']);
		add_action('save_post_t4l_testimonial', [self::class, 'save_testimonial']);
	}

	public static function register_meta(): void
	{
		$auth = static fn (): bool => current_user_can('edit_posts');

		register_post_meta('t4l_film', '_t4l_video_url', [
			'type' => 'string',
			'single' => true,
			'show_in_rest' => true,
			'auth_callback' => $auth,
			'sanitize_callback' => [self::class, 'sanitize_href'],
		]);
		foreach (['_t4l_kicker', '_t4l_duration', '_t4l_setting_key', '_t4l_href_label'] as $key) {
			register_post_meta('t4l_film', $key, [
				'type' => 'string',
				'single' => true,
				'show_in_rest' => true,
				'auth_callback' => $auth,
				'sanitize_callback' => 'sanitize_text_field',
			]);
		}
		register_post_meta('t4l_film', '_t4l_href', [
			'type' => 'string',
			'single' => true,
			'show_in_rest' => true,
			'auth_callback' => $auth,
			'sanitize_callback' => [self::class, 'sanitize_href'],
		]);
		register_post_meta('t4l_testimonial', '_t4l_role', [
			'type' => 'string',
			'single' => true,
			'show_in_rest' => true,
			'auth_callback' => $auth,
			'sanitize_callback' => 'sanitize_text_field',
		]);

		$page_auth = static fn (): bool => current_user_can('edit_pages');
		foreach (Content::home_fields() as $key => $field) {
			register_post_meta('page', $key, [
				'type' => 'string',
				'single' => true,
				'show_in_rest' => true,
				'auth_callback' => $page_auth,
				'sanitize_callback' => $field['multiline'] ? 'sanitize_textarea_field' : 'sanitize_text_field',
			]);
		}
	}

	public static function boxes(): void
	{
		add_meta_box('t4l_film_details', 'Film details', [self::class, 'film_box'], 't4l_film', 'normal', 'high');
		add_meta_box('t4l_testimonial_role', 'Attribution', [self::class, 'testimonial_box'], 't4l_testimonial', 'side');
	}

	public static function film_box(\WP_Post $post): void
	{
		wp_nonce_field('t4l_save_film', 't4l_film_nonce');
		$fields = [
			'_t4l_video_url' => 'Streaming URL',
			'_t4l_kicker' => 'Label',
			'_t4l_duration' => 'Duration',
			'_t4l_href' => 'Related page',
			'_t4l_href_label' => 'Related link label',
		];
		echo '<div class="t4l-fields">';
		echo '<p>The streaming address can be a full URL, or a path such as <code>/wp-content/uploads/2026/11/what-is-stem-cell.mp4</code>. A path uses this site’s address. HTML5 Video Player plays YouTube, Vimeo, MP4, WebM, and HLS (.m3u8). Order sets the list position. The excerpt is the line under the title.</p>';
		foreach ($fields as $key => $label) {
			$value = (string) get_post_meta($post->ID, $key, true);
			$placeholder = $key === '_t4l_href' ? ' placeholder="/research/stem-cell-science/"' : '';
			printf(
				'<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" type="text" id="%1$s" name="%1$s" value="%3$s"%4$s></p>',
				esc_attr($key),
				esc_html($label),
				esc_attr($value),
				$placeholder
			);
		}
		echo '<p>Related page can be a path, such as <code>/research/stem-cell-science/</code>, or a full URL.</p>';
		$key = (string) get_post_meta($post->ID, '_t4l_setting_key', true);
		echo '<p><label for="_t4l_setting_key"><strong>Points under the film</strong></label><br>';
		echo '<select class="widefat" id="_t4l_setting_key" name="_t4l_setting_key">';
		$sets = [
			'' => 'Automatic, from the film order',
			'video_stem_cells' => 'The science',
			'video_baby_teeth' => 'Baby teeth',
			'video_collection' => 'Collection',
		];
		foreach ($sets as $set => $set_label) {
			printf(
				'<option value="%1$s"%2$s>%3$s</option>',
				esc_attr($set),
				selected($key, $set, false),
				esc_html($set_label)
			);
		}
		echo '</select></p>';
		echo '<p>Automatic uses the first film for the science points, the second for baby teeth, and the third for collection.</p>';
		echo '</div>';
	}

	public static function testimonial_box(\WP_Post $post): void
	{
		wp_nonce_field('t4l_save_testimonial', 't4l_testimonial_nonce');
		$value = (string) get_post_meta($post->ID, '_t4l_role', true);
		echo '<p><label for="_t4l_role"><strong>Role</strong></label><br>';
		printf(
			'<input class="widefat" type="text" id="_t4l_role" name="_t4l_role" value="%s">',
			esc_attr($value)
		);
		echo '</p><p>The quote is the main content. The title is the person’s name. Publish only quotes you have permission to use.</p>';
	}

	/**
	 * A site path or a full web address. A path is stored as entered.
	 *
	 * @param mixed $value
	 */
	public static function sanitize_href($value): string
	{
		if (! is_string($value)) {
			return '';
		}
		$value = trim(sanitize_text_field(wp_unslash($value)));
		if ($value === '') {
			return '';
		}
		if (preg_match('#^[a-z][a-z0-9+.-]*://#i', $value) === 1) {
			return esc_url_raw($value);
		}
		$path = '/' . ltrim($value, '/');
		$parts = wp_parse_url($path);
		if (! is_array($parts) || empty($parts['path']) || ! is_string($parts['path'])) {
			return '';
		}
		$clean = '/' . ltrim($parts['path'], '/');
		if (! empty($parts['query']) && is_string($parts['query'])) {
			$clean .= '?' . $parts['query'];
		}
		if (! empty($parts['fragment']) && is_string($parts['fragment'])) {
			$clean .= '#' . $parts['fragment'];
		}

		return $clean;
	}

	public static function save_film(int $post_id): void
	{
		if (! self::can_save($post_id, 't4l_film_nonce', 't4l_save_film')) {
			return;
		}

		$map = [
			'_t4l_video_url' => [self::class, 'sanitize_href'],
			'_t4l_kicker' => 'sanitize_text_field',
			'_t4l_duration' => 'sanitize_text_field',
			'_t4l_href' => [self::class, 'sanitize_href'],
			'_t4l_href_label' => 'sanitize_text_field',
		];
		foreach ($map as $key => $sanitizer) {
			if (! isset($_POST[$key]) || ! is_string($_POST[$key])) {
				continue;
			}
			$raw = wp_unslash($_POST[$key]);
			update_post_meta($post_id, $key, call_user_func($sanitizer, $raw));
		}
		if (isset($_POST['_t4l_setting_key']) && is_string($_POST['_t4l_setting_key'])) {
			$choice = sanitize_text_field(wp_unslash($_POST['_t4l_setting_key']));
			$allowed = ['', 'video_stem_cells', 'video_baby_teeth', 'video_collection'];
			update_post_meta($post_id, '_t4l_setting_key', in_array($choice, $allowed, true) ? $choice : '');
		}
	}

	public static function save_testimonial(int $post_id): void
	{
		if (! self::can_save($post_id, 't4l_testimonial_nonce', 't4l_save_testimonial')) {
			return;
		}
		if (! isset($_POST['_t4l_role']) || ! is_string($_POST['_t4l_role'])) {
			return;
		}
		update_post_meta($post_id, '_t4l_role', sanitize_text_field(wp_unslash($_POST['_t4l_role'])));
	}

	private static function can_save(int $post_id, string $field, string $action): bool
	{
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return false;
		}
		if (! isset($_POST[$field]) || ! is_string($_POST[$field])) {
			return false;
		}
		$nonce = sanitize_text_field(wp_unslash($_POST[$field]));
		if (! wp_verify_nonce($nonce, $action)) {
			return false;
		}

		return current_user_can('edit_post', $post_id);
	}
}
