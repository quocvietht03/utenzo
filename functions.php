<?php
/* Register Sidebar */
if (!function_exists('utenzo_register_sidebar')) {
	function utenzo_register_sidebar()
	{
		register_sidebar(array(
			'name' => esc_html__('Main Sidebar', 'utenzo'),
			'id' => 'main-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="wg-title">',
			'after_title' => '</h4>',
		));
	}
	add_action('widgets_init', 'utenzo_register_sidebar');
}

/* Add Support Upload Image Type SVG */
function utenzo_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'utenzo_mime_types');

/* Get icon SVG HTML */
function utenzo_get_icon_svg_html($icon_file_name) {

	if (!empty($icon_file_name)) {
		$file_path = CLEANIRA_IMG_DIR . $icon_file_name . '.svg';
		$file = file_get_contents($file_path);
		if ($file !== false) {
			return $file;
		} else {
			return 'Error: File does not exist.';
		}
	} else {
		return 'Error: Invalid file name or file name is missing.';
	}
}

/* Register Default Fonts */
if (!function_exists('utenzo_fonts_url')) {
	function utenzo_fonts_url()
	{
		global $utenzo_options;
		$base_font = 'Urbanist';
		$head_font = 'Urbanist';

		$font_url = '';
		if ('off' !== _x('on', 'Google font: on or off', 'utenzo')) {
			$font_url = add_query_arg('family', urlencode($base_font . ':400,400i,600,700|' . $head_font . ':400,400i,500,600,700'), "//fonts.googleapis.com/css");
		}
		return $font_url;
	}
}
/* Enqueue Script */
if (!function_exists('utenzo_enqueue_scripts')) {
	function utenzo_enqueue_scripts()
	{
		global $utenzo_options;

		if (is_singular('product')) {
			wp_enqueue_script('slick-slider', get_template_directory_uri() . '/assets/libs/slick/slick.min.js', array('jquery'), '', true);
			wp_enqueue_style('slick-slider', get_template_directory_uri() . '/assets/libs/slick/slick.css', array(), false);

			wp_enqueue_script('zoom-master', get_template_directory_uri() . '/assets/libs/zoom-master/jquery.zoom.min.js', array('jquery'), '', true);
		}
		if (is_archive('product')) {
			wp_enqueue_script('nouislider-script', get_template_directory_uri() . '/assets/libs/nouislider/nouislider.min.js', array('jquery'), '', true);
			wp_enqueue_style('nouislider-style', get_template_directory_uri() . '/assets/libs/nouislider/nouislider.min.css', array(), false);
		}
		if (class_exists('WooCommerce')) {
			wp_enqueue_script('wc-cart-fragments');
		}
		if (is_singular('post') && comments_open()) {
			wp_enqueue_script('jquery-validate', get_template_directory_uri() . '/assets/libs/jquery-validate/jquery.validate.min.js', array('jquery'), '', true);
		}
		wp_enqueue_script('select2', get_template_directory_uri() . '/assets/libs/select2/select2.min.js', array('jquery'), '', true);
		wp_enqueue_style('select2', get_template_directory_uri() . '/assets/libs/select2/select2.min.css', array(), false);

		/* Fonts */
		// wp_enqueue_style('utenzo-fonts', utenzo_fonts_url(), false);
		wp_enqueue_style('utenzo-main', get_template_directory_uri() . '/assets/css/main.css',  array(), false);
		wp_enqueue_style('utenzo-style', get_template_directory_uri() . '/style.css',  array(), false);
		wp_enqueue_script('utenzo-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '', true);
		if (function_exists('get_field')) {
			$dev_mode = get_field('dev_mode', 'options');
			/* Load custom style */
			$custom_style = '';

			$custom_style = get_field('custom_css_code', 'options');
			if ($dev_mode && !empty($custom_style)) {
				wp_add_inline_style('utenzo-style', $custom_style);
			}

			/* Custom script */
			$custom_script = '';
			$custom_script = get_field('custom_js_code', 'options');
			if ($dev_mode && !empty($custom_script)) {
				wp_add_inline_script('utenzo-main', $custom_script);
			}
		}
		/* Options to script */
		$js_options = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'user_info' => wp_get_current_user(),
		);
		wp_localize_script('utenzo-main', 'AJ_Options', $js_options);
		wp_enqueue_script('utenzo-main');
	}
	add_action('wp_enqueue_scripts', 'utenzo_enqueue_scripts');
}

/* Add Stylesheet And Script Backend */
if (!function_exists('utenzo_enqueue_admin_scripts')) {
	function utenzo_enqueue_admin_scripts()
	{
		wp_enqueue_script('utenzo-admin-main', get_template_directory_uri() . '/assets/js/admin-main.js', array('jquery'), '', true);
		wp_enqueue_style('utenzo-admin-main', get_template_directory_uri() . '/assets/css/admin-main.css', array(), false);
	}
	add_action('admin_enqueue_scripts', 'utenzo_enqueue_admin_scripts');
}

/**
 * Define theme path
 */
define('CLEANIRA_IMG_DIR', get_template_directory_uri() . '/assets/images/');

/**
 * Theme install
 */
require_once get_template_directory() . '/install/plugin-required.php';
require_once get_template_directory() . '/install/import-pack/import-functions.php';

/* CPT Load */
require_once get_template_directory() . '/framework/cpt-service.php';
require_once get_template_directory() . '/framework/cpt-testimonial.php';
/* ACF Options */
require_once get_template_directory() . '/framework/acf-options.php';

/* Template functions */
require_once get_template_directory() . '/framework/template-helper.php';

/* Post Functions */
require_once get_template_directory() . '/framework/templates/post-helper.php';

/* Block Load */
require_once get_template_directory() . '/framework/block-load.php';

/* Widgets Load */
require_once get_template_directory() . '/framework/widget-load.php';

/* Woocommerce functions */
if (class_exists('Woocommerce')) {
	require_once get_template_directory() . '/woocommerce/shop-helper.php';
}

if (function_exists('get_field')) {

	function utenzo_body_class($classes)
	{
		$effect_load_heading = get_field('effect_load_heading', 'options');
		$button_hover = get_field('effect_button_hover', 'options');

		if ($effect_load_heading) {
			$classes[] = 'bt-effect-heading-enable';
		}
		if ($button_hover) {
			$classes[] = 'bt-button-hover-enable';
		}
		return $classes;
	}
	add_filter('body_class', 'utenzo_body_class');
}

/* Custom number posts per page */
add_action('pre_get_posts', 'bt_custom_posts_per_page');
function bt_custom_posts_per_page($query)
{
	if ($query->is_post_type_archive('service') && $query->is_main_query() && ! is_admin()) {
		$query->set('posts_per_page', 3);
	}
};
/* Custom search posts */
function bt_custom_search_filter( $query ) {
    if ( $query->is_search() && !is_admin() ) {
        if ( !is_post_type_archive( 'product' ) && !is_tax( 'product_cat' ) && !is_singular( 'product' ) ) {
            $query->set( 'post_type', 'post' );
        }
    }
}
add_action( 'pre_get_posts', 'bt_custom_search_filter' );