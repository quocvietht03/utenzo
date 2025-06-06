<?php
if (! isset($content_width)) $content_width = 900;
if (is_singular()) wp_enqueue_script("comment-reply");

if (! function_exists('utenzo_setup')) {
	function utenzo_setup()
	{
		/* Load textdomain */
		load_theme_textdomain('utenzo', get_template_directory() . '/languages');

		/* Add custom logo */
		add_theme_support('custom-logo');

		/* Add RSS feed links to <head> for posts and comments. */
		add_theme_support('automatic-feed-links');

		/* Enable support for Post Thumbnails, and declare sizes. */
		add_theme_support('post-thumbnails');

		/* Enable support for Title Tag */
		add_theme_support("title-tag");

		/* This theme uses wp_nav_menu() in locations. */
		register_nav_menus(array(
			'primary_menu'   => esc_html__('Primary Menu', 'utenzo'),

		));

		/* This theme styles the visual editor to resemble the theme style, specifically font, colors, icons, and column width. */
		add_editor_style('editor-style.css');

		/* Switch default core markup for search form, comment form, and comments to output valid HTML5. */
		add_theme_support('html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		));

		add_theme_support('wp-block-styles');
		add_theme_support('responsive-embeds');
		add_theme_support('custom-header');
		add_theme_support('align-wide');

		/* This theme allows users to set a custom background. */
		add_theme_support('custom-background', apply_filters('utenzo_custom_background_args', array(
			'default-color' => 'f5f5f5',
		)));

		/* Add support for featured content. */
		add_theme_support('featured-content', array(
			'featured_content_filter' => 'utenzo_get_featured_posts',
			'max_posts' => 6,
		));

		/* This theme uses its own gallery styles. */
		add_filter('use_default_gallery_style', '__return_false');

		/* Add support woocommerce */
		add_theme_support('woocommerce');
	}
}
add_action('after_setup_theme', 'utenzo_setup');

/* Logo */
if (!function_exists('utenzo_logo')) {
	function utenzo_logo($url = '', $height = 30)
	{
		if (!$url) {
			$url = get_template_directory_uri() . '/assets/images/site-logo.svg';
		}
		echo '<a href="' . esc_url(home_url('/')) . '"><img class="logo" style="height: ' . esc_attr($height) . 'px; width: auto;" src="' . esc_url($url) . '" alt="' . esc_attr__('Logo', 'utenzo') . '"/></a>';
	}
}

/* Nav Menu */
if (!function_exists('utenzo_nav_menu')) {
	function utenzo_nav_menu($menu_slug = '', $theme_location = '', $container_class = '')
	{
		if (has_nav_menu($theme_location) || $menu_slug) {
			wp_nav_menu(array(
				'menu'				=> $menu_slug,
				'container_class' 	=> $container_class,
				'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'theme_location'  	=> $theme_location
			));
		} else {
			wp_page_menu(array(
				'menu_class'  => $container_class
			));
		}
	}
}
/* Page title */
if (!function_exists('utenzo_page_title')) {
	function utenzo_page_title()
	{
		ob_start();
		if (is_front_page()) {
			esc_html_e('Home', 'utenzo');
		} elseif (is_home()) {
			esc_html_e('Blog', 'utenzo');
		} elseif (is_search()) {
			esc_html_e('Search', 'utenzo');
		} elseif (is_404()) {
			esc_html_e('404', 'utenzo');
		} elseif (is_page()) {
			echo get_the_title();
		} elseif (is_archive()) {
			if (is_category()) {
				single_cat_title();
			} elseif (get_post_type() == 'product') {
				if (wc_get_page_id('shop')) {
					echo get_the_title(wc_get_page_id('shop'));
				} else {
					single_term_title();
				}
			} elseif (is_tag()) {
				single_tag_title();
			} elseif (is_author()) {
				printf(__('Author: %s', 'utenzo'), '<span class="vcard">' . get_the_author() . '</span>');
			} elseif (is_day()) {
				printf(__('Day: %s', 'utenzo'), '<span>' . get_the_date(get_option('date_format')) . '</span>');
			} elseif (is_month()) {
				printf(__('Month: %s', 'utenzo'), '<span>' . get_the_date(get_option('date_format')) . '</span>');
			} elseif (is_year()) {
				printf(__('Year: %s', 'utenzo'), '<span>' . get_the_date(get_option('date_format')) . '</span>');
			} elseif (is_tax('post_format', 'post-format-aside')) {
				esc_html_e('Aside', 'utenzo');
			} elseif (is_tax('post_format', 'post-format-gallery')) {
				esc_html_e('Gallery', 'utenzo');
			} elseif (is_tax('post_format', 'post-format-image')) {
				esc_html_e('Image', 'utenzo');
			} elseif (is_tax('post_format', 'post-format-video')) {
				esc_html_e('Video', 'utenzo');
			} elseif (is_tax('post_format', 'post-format-quote')) {
				esc_html_e('Quote', 'utenzo');
			} elseif (is_tax('post_format', 'post-format-link')) {
				esc_html_e('Link', 'utenzo');
			} elseif (is_tax('post_format', 'post-format-status')) {
				esc_html_e('Status', 'utenzo');
			} elseif (is_tax('post_format', 'post-format-audio')) {
				esc_html_e('Audio', 'utenzo');
			} elseif (is_tax('post_format', 'post-format-chat')) {
				esc_html_e('Chat', 'utenzo');
			} else {
				echo get_the_title(wc_get_page_id('shop'));
			}
		} else {
			echo get_the_title();
		}

		return ob_get_clean();
	}
}

/* Page breadcrumb */
if (!function_exists('utenzo_page_breadcrumb')) {
	function utenzo_page_breadcrumb($home_text = 'Home', $delimiter = '-')
	{
		global $post;

		if (is_front_page()) {
			echo '<a class="bt-home" href="' . esc_url(home_url('/')) . '">' . $home_text . '</a> <span class="bt-deli first">' . $delimiter . '</span> ' . esc_html('Front Page', 'utenzo');
		} elseif (is_home()) {
			echo  '<a class="bt-home" href="' . esc_url(home_url('/')) . '">' . $home_text . '</a> <span class="bt-deli first">' . $delimiter . '</span> ' . esc_html('Blog', 'utenzo');
		} else {
			echo '<a class="bt-home" href="' . esc_url(home_url('/')) . '">' . $home_text . '</a> <span class="bt-deli first">' . $delimiter . '</span> ';
		}

		if (is_category()) {
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' <span class="bt-deli">' . $delimiter . '</span> ');
			echo '<span class="current">' . single_cat_title(esc_html__('Archive by category: ', 'utenzo'), false) . '</span>';
		} elseif (is_tag()) {
			echo '<span class="current">' . single_tag_title(esc_html__('Posts tagged: ', 'utenzo'), false) . '</span>';
		} elseif (is_post_type_archive()) {
			echo post_type_archive_title('<span class="current">', '</span>');
		} elseif (is_tax()) {
			echo '<span class="current">' . single_term_title(esc_html__('Archive by taxonomy: ', 'utenzo'), false) . '</span>';
		} elseif (is_search()) {
			echo '<span class="current">' . esc_html__('Search results for: ', 'utenzo') . get_search_query() . '</span>';
		} elseif (is_day()) {
			echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . ' ' . get_the_time('Y') . '</a> <span class="bt-deli">' . $delimiter . '</span> ';
			echo '<span class="current">' . get_the_time('d') . '</span>';
		} elseif (is_month()) {
			echo '<span class="current">' . get_the_time('F') . ' ' . get_the_time('Y') . '</span>';
		} elseif (is_single() && !is_attachment()) {
			if (get_post_type() != 'post') {
				if (get_post_type() == 'product') {
					$terms = get_the_terms(get_the_ID(), 'product_cat', '', '');
					if (!empty($terms) && !is_wp_error($terms)) {
						//the_terms(get_the_ID(), 'product_cat', '', ', ');
						$shop_page_url = get_permalink(wc_get_page_id('shop'));
						$first_term = reset($terms); // Get first term
						$category_url = $shop_page_url . '?product_cat=' . $first_term->slug;
						echo '<a href="' . esc_url($category_url) . '">' . esc_html($first_term->name) . '</a>';
						echo ' <span class="bt-deli">' . $delimiter . '</span> ' . '<span class="current">' . get_the_title() . '</span>';
					} else {
						echo '<span class="current">' . get_the_title() . '</span>';
					}
				} else {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					if ($post_type->rewrite) {
						echo '<a href="' . esc_url(home_url('/')) . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
						echo ' <span class="bt-deli">' . $delimiter . '</span> ';
					}
					echo '<span class="current">' . get_the_title() . '</span>';
				}
			} else {
				$cat = get_the_category();
				$cat = $cat[0];
				$cats = get_category_parents($cat, TRUE, ' <span class="bt-deli">' . $delimiter . '</span> ');
				echo '' . $cats;
				echo '<span class="current">' . get_the_title() . '</span>';
			}
		} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			if ($post_type) echo '<span class="current">' . $post_type->labels->name . '</span>';
		} elseif (is_attachment()) {
			$parent = get_post($post->post_parent);
			echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
			echo ' <span class="bt-deli">' . $delimiter . '</span> ' . '<span class="current">' . get_the_title() . '</span>';
		} elseif (is_page() && !is_front_page() && !$post->post_parent) {
			echo '<span class="current">' . get_the_title() . '</span>';
		} elseif (is_page() && !is_front_page() && $post->post_parent) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for ($i = 0; $i < count($breadcrumbs); $i++) {
				echo '' . $breadcrumbs[$i];
				if ($i != count($breadcrumbs) - 1)
					echo ' <span class="bt-deli">' . $delimiter . '</span> ';
			}
			echo ' <span class="bt-deli">' . $delimiter . '</span> ' . '<span class="current">' . get_the_title() . '</span>';
		} elseif (is_author()) {
			global $author;
			$userdata = get_userdata($author);
			echo '<span class="current">' . esc_html__('Articles posted by ', 'utenzo') . $userdata->display_name . '</span>';
		} elseif (is_404()) {
			echo '<span class="current">' . esc_html__('Error 404', 'utenzo') . '</span>';
		}

		if (get_query_var('paged')) {
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo '<span class="bt-pages"> (';
			echo ' <span class="bt-deli">' . $delimiter . '</span> ' . esc_html__('Page', 'utenzo') . ' ' . get_query_var('paged');
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ')</span>';
		}
	}
}

/* Display navigation to next/previous post */
if (! function_exists('utenzo_post_nav')) {
	function utenzo_post_nav()
	{
		$previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
		$next     = get_adjacent_post(false, '', false);
		if (! $next && ! $previous) {
			return;
		}
?>
		<nav class="bt-post-nav clearfix">
			<?php
			previous_post_link('<div class="bt-post-nav--item bt-prev"><span>' . esc_html__('Previous', 'utenzo') . '</span><h3>%link</h3></div>');
			next_post_link('<div class="bt-post-nav--item bt-next"><span>' . esc_html__('Next', 'utenzo') . '</span><h3>%link</h3></div>');
			?>
		</nav>
	<?php
	}
}

/* Display paginate links */
if (! function_exists('utenzo_paginate_links')) {
	function utenzo_paginate_links($wp_query)
	{
		if ($wp_query->max_num_pages <= 1) {
			return;
		}
	?>
		<nav class="bt-pagination" role="navigation">
			<?php
			$big = 999999999; // need an unlikely integer
			echo paginate_links(array(
				'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format' => '?paged=%#%',
				'current' => max(1, get_query_var('paged')),
				'total' => $wp_query->max_num_pages,
				'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
  <path d="M0.839282 12.4903C0.630446 12.2842 0.611461 11.9616 0.782327 11.7343L0.839282 11.6692L5.91327 6.6604L0.839282 1.65162C0.630446 1.44548 0.611461 1.1229 0.782327 0.895592L0.839282 0.830468C1.04812 0.624326 1.37491 0.605586 1.6052 0.774247L1.67117 0.830468L7.16137 6.24982C7.3702 6.45596 7.38919 6.77854 7.21832 7.00585L7.16137 7.07098L1.67117 12.4903C1.44145 12.7171 1.069 12.7171 0.839282 12.4903Z" fill="#212121"></path>
</svg>' . esc_html__('Prev', 'utenzo'),
				'next_text' => esc_html__('Next', 'utenzo') . '<svg xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
  <path d="M0.839282 12.4903C0.630446 12.2842 0.611461 11.9616 0.782327 11.7343L0.839282 11.6692L5.91327 6.6604L0.839282 1.65162C0.630446 1.44548 0.611461 1.1229 0.782327 0.895592L0.839282 0.830468C1.04812 0.624326 1.37491 0.605586 1.6052 0.774247L1.67117 0.830468L7.16137 6.24982C7.3702 6.45596 7.38919 6.77854 7.21832 7.00585L7.16137 7.07098L1.67117 12.4903C1.44145 12.7171 1.069 12.7171 0.839282 12.4903Z" fill="#212121"></path>
</svg>',
			));
			?>
		</nav>
	<?php
	}
}

/* Display navigation to next/previous set of posts */
if (! function_exists('utenzo_paging_nav')) {
	function utenzo_paging_nav()
	{
		if ($GLOBALS['wp_query']->max_num_pages < 2) {
			return;
		}

		$paged        = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
		$pagenum_link = html_entity_decode(get_pagenum_link());
		$query_args   = array();
		$url_parts    = explode('?', $pagenum_link);

		if (isset($url_parts[1])) {
			wp_parse_str($url_parts[1], $query_args);
		}

		$pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
		$pagenum_link = trailingslashit($pagenum_link) . '%_%';

		$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

	?>
		<nav class="bt-pagination" role="navigation">
			<?php
			echo paginate_links(array(
				'base'     => $pagenum_link,
				'format'   => $format,
				'total'    => $GLOBALS['wp_query']->max_num_pages,
				'current'  => $paged,
				'mid_size' => 1,
				'add_args' => array_map('urlencode', $query_args),
				'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
  <path d="M0.839282 12.4903C0.630446 12.2842 0.611461 11.9616 0.782327 11.7343L0.839282 11.6692L5.91327 6.6604L0.839282 1.65162C0.630446 1.44548 0.611461 1.1229 0.782327 0.895592L0.839282 0.830468C1.04812 0.624326 1.37491 0.605586 1.6052 0.774247L1.67117 0.830468L7.16137 6.24982C7.3702 6.45596 7.38919 6.77854 7.21832 7.00585L7.16137 7.07098L1.67117 12.4903C1.44145 12.7171 1.069 12.7171 0.839282 12.4903Z" fill="#212121"/>
</svg>' . esc_html__('Prev', 'utenzo'),
				'next_text' => esc_html__('Next', 'utenzo') . '<svg xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
  <path d="M0.839282 12.4903C0.630446 12.2842 0.611461 11.9616 0.782327 11.7343L0.839282 11.6692L5.91327 6.6604L0.839282 1.65162C0.630446 1.44548 0.611461 1.1229 0.782327 0.895592L0.839282 0.830468C1.04812 0.624326 1.37491 0.605586 1.6052 0.774247L1.67117 0.830468L7.16137 6.24982C7.3702 6.45596 7.38919 6.77854 7.21832 7.00585L7.16137 7.07098L1.67117 12.4903C1.44145 12.7171 1.069 12.7171 0.839282 12.4903Z" fill="#212121"/>
</svg>',
			));
			?>
		</nav>
	<?php
	}
}
/**
 * Back to top button
 * 
 * Adds a back to top button to the footer
 */
if (!function_exists('utenzo_back_to_top')) {
	function utenzo_back_to_top()
	{
	?>
		<a href="#" class="bt-back-to-top">
			<svg width="512" height="512" x="0" y="0" viewBox="0 0 128 128" xml:space="preserve">
				<g>
					<path d="M64 104a3.988 3.988 0 0 1-2.828-1.172l-40-40c-1.563-1.563-1.563-4.094 0-5.656s4.094-1.563 5.656 0L64 94.344l37.172-37.172c1.563-1.563 4.094-1.563 5.656 0s1.563 4.094 0 5.656l-40 40A3.988 3.988 0 0 1 64 104zm2.828-33.172 40-40c1.563-1.563 1.563-4.094 0-5.656s-4.094-1.563-5.656 0L64 62.344 26.828 25.172c-1.563-1.563-4.094-1.563-5.656 0s-1.563 4.094 0 5.656l40 40C61.953 71.609 62.977 72 64 72s2.047-.391 2.828-1.172z" fill="#ffffff" opacity="1" data-original="#000000" class=""></path>
				</g>
			</svg>
		</a>
		<?php
	}
}
add_action('wp_footer', 'utenzo_back_to_top', 99);

/**
 * popup newlsetter form
 * 
 * Adds a back to top button to the footer
 */
if (!function_exists('utenzo_popup_newslleter_form')) {
	function utenzo_popup_newslleter_form()
	{
		if (function_exists('get_field')) {
			$enable_newsletter_popup = get_field('enable_newsletter_popup', 'options');
			$newsletter_popup_setting = get_field('newsletter_popup_setting', 'options');
			$heading = !empty($newsletter_popup_setting['heading']) ? $newsletter_popup_setting['heading'] : esc_html__('Subscribe to get 10% OFF', 'utenzo');
			$subheading = !empty($newsletter_popup_setting['sub_heading']) ? $newsletter_popup_setting['sub_heading'] : esc_html__('Subscribe to our newsletter and receive 10% off your first purchase', 'utenzo');
			$note = !empty($newsletter_popup_setting['note_bottom']) ? $newsletter_popup_setting['note_bottom'] : '';
			$image_newsletter = !empty($newsletter_popup_setting['image_newsletter']['url']) ? $newsletter_popup_setting['image_newsletter']['url'] : '';
		} else {
			$enable_newsletter_popup = false;
			$heading = esc_html__('Subscribe to get 10% OFF', 'utenzo');
			$subheading = esc_html__('Subscribe to our newsletter and receive 10% off your first purchase', 'utenzo');
			$note = '';
			$image_newsletter = '';
		}
		if ($enable_newsletter_popup) {
		?>
			<div id="bt-newsletter-popup" class="bt-newsletter-popup">
				<div class="bt-newsletter-overlay"></div>
				<div class="bt-newsletter-popup-content">
					<span class="bt-close-popup"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M9.41183 8L15.6952 1.71665C15.7905 1.62455 15.8666 1.51437 15.9189 1.39255C15.9713 1.27074 15.9988 1.13972 16 1.00714C16.0011 0.874567 15.9759 0.743089 15.9256 0.620381C15.8754 0.497673 15.8013 0.386193 15.7076 0.292444C15.6138 0.198695 15.5023 0.124556 15.3796 0.0743523C15.2569 0.0241486 15.1254 -0.00111435 14.9929 3.76988e-05C14.8603 0.00118975 14.7293 0.0287337 14.6074 0.0810623C14.4856 0.133391 14.3755 0.209456 14.2833 0.30482L8 6.58817L1.71665 0.30482C1.52834 0.122941 1.27612 0.0223015 1.01433 0.0245764C0.752534 0.0268514 0.502106 0.131859 0.316983 0.316983C0.131859 0.502107 0.0268514 0.752534 0.0245764 1.01433C0.0223015 1.27612 0.122941 1.52834 0.30482 1.71665L6.58817 8L0.30482 14.2833C0.209456 14.3755 0.133391 14.4856 0.0810623 14.6074C0.0287337 14.7293 0.00118975 14.8603 3.76988e-05 14.9929C-0.00111435 15.1254 0.0241486 15.2569 0.0743523 15.3796C0.124556 15.5023 0.198695 15.6138 0.292444 15.7076C0.386193 15.8013 0.497673 15.8754 0.620381 15.9256C0.743089 15.9759 0.874567 16.0011 1.00714 16C1.13972 15.9988 1.27074 15.9713 1.39255 15.9189C1.51437 15.8666 1.62455 15.7905 1.71665 15.6952L8 9.41183L14.2833 15.6952C14.4226 15.8358 14.6006 15.9317 14.7945 15.9708C14.9885 16.0098 15.1898 15.9902 15.3726 15.9145C15.5554 15.8388 15.7115 15.7104 15.8211 15.5456C15.9306 15.3808 15.9886 15.1871 15.9877 14.9893C15.9878 14.8581 15.9619 14.7283 15.9117 14.6072C15.8615 14.4861 15.7879 14.376 15.6952 14.2833L9.41183 8Z" fill="#0C2C48" />
						</svg></span>
					<div class="bt-newsletter-popup-image">
						<?php if (!empty($image_newsletter)): ?>
							<img src="<?php echo esc_url($image_newsletter); ?>" alt="<?php echo esc_attr__('Newsletter', 'utenzo'); ?>">
						<?php endif; ?>
					</div>
					<div class="bt-newsletter-popup-info">
						<h3 class="bt-title"><?php echo esc_html($heading); ?></h3>
						<p class="bt-subtitle"><?php echo esc_html($subheading); ?></p>
						<?php
						echo do_shortcode('[newsletter_form button_label="Subscribe"]
					[newsletter_field name="last_name" placeholder="Your name"]
					[newsletter_field name="email" placeholder="Your e-mail"]
					[/newsletter_form]');
						?>
						<?php if (!empty($note)):
							echo '<div class="bt-newsletter-note">' . $note . '</div>';
						endif; ?>
					</div>
				</div>
			</div>
<?php
		}
	}
	add_action('wp_footer', 'utenzo_popup_newslleter_form');
}

/* Hook add Field Loop Caroucel Elementor */
add_action('elementor/element/loop-carousel/section_carousel_pagination/before_section_end', function ($element) {
	$element->add_control(
		'enable_pagination_mobile',
		[
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label' => esc_html__('Mobile-Only Pagination', 'utenzo'),
			'default' => 'no',
			'label_on' => esc_html__('Yes', 'utenzo'),
			'label_off' => esc_html__('No', 'utenzo'),
			'return_value' => 'yes',
			'separator' => 'before'
		]
	);
});
add_action('elementor/element/loop-carousel/section_navigation_settings/before_section_end', function ($element) {
	$element->add_control(
		'enable_hidden_arrow_mobile',
		[
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label' => esc_html__('Hidden Arrow Mobile', 'utenzo'),
			'default' => 'no',
			'label_on' => esc_html__('Yes', 'utenzo'),
			'label_off' => esc_html__('No', 'utenzo'),
			'return_value' => 'yes',
			'separator' => 'before'
		]
	);
});

// Hook for frontend render
function utenzo_widget_loop_carousel_custom($widget_content, $widget)
{
	if ('loop-carousel' === $widget->get_name()) {
		$settings = $widget->get_settings();
		$enable_pagination_mobile = isset($settings['enable_pagination_mobile']) ? $settings['enable_pagination_mobile'] : '';

		if ($enable_pagination_mobile == 'yes') {
			// Add class for both frontend
			$widget->add_render_attribute('_wrapper', 'class', 'bt-enable-pagination-mobile');
			// Add editor class
			if (\Elementor\Plugin::$instance->editor->is_edit_mode() && strpos($widget_content, 'swiper-pagination') !== false) {
				$widget_content = str_replace('swiper-pagination', 'swiper-pagination bt-show-pagination-mobile', $widget_content);
			}
		}
		$enable_hidden_arrow_mobile = isset($settings['enable_hidden_arrow_mobile']) ? $settings['enable_hidden_arrow_mobile'] : '';
		if ($enable_hidden_arrow_mobile == 'yes') {
			// Add class for both frontend
			$widget->add_render_attribute('_wrapper', 'class', 'bt-enable-hidden-arrow-mobile');
			// Add editor class
			if (\Elementor\Plugin::$instance->editor->is_edit_mode() && strpos($widget_content, 'elementor-swiper-button') !== false) {
				$widget_content = str_replace('elementor-swiper-button', 'bt-hinden-arrow-mobile elementor-swiper-button', $widget_content);
			}
		}
	}
	return $widget_content;
}
add_filter('elementor/widget/render_content', 'utenzo_widget_loop_carousel_custom', 10, 2);
