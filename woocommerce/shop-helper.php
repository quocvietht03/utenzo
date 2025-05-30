<?php
// WooCommerce custom hooks
add_action('utenzo_woocommerce_template_loop_product_link_open', 'woocommerce_template_loop_product_link_open', 10);
add_action('utenzo_woocommerce_template_loop_product_link_close', 'woocommerce_template_loop_product_link_close', 5);
add_action('utenzo_woocommerce_show_product_loop_sale_flash', 'woocommerce_show_product_loop_sale_flash', 10);

add_action('utenzo_woocommerce_template_loop_product_title', 'woocommerce_template_loop_product_title', 10);
add_action('utenzo_woocommerce_template_loop_rating', 'woocommerce_template_loop_rating', 5);
add_action('utenzo_woocommerce_template_loop_price', 'woocommerce_template_loop_price', 10);
add_action('utenzo_woocommerce_template_loop_add_to_cart', 'woocommerce_template_loop_add_to_cart', 10);

add_action('utenzo_woocommerce_template_single_title', 'woocommerce_template_single_title', 5);
add_action('utenzo_woocommerce_template_single_rating', 'woocommerce_template_single_rating', 10);
add_action('utenzo_woocommerce_template_single_price', 'woocommerce_template_single_price', 10);
add_action('utenzo_woocommerce_template_single_excerpt', 'woocommerce_template_single_excerpt', 20);
add_action('utenzo_woocommerce_template_single_add_to_cart', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_meta', 'woocommerce_template_single_meta');
add_action('woocommerce_single_product_meta', 'custom_woocommerce_single_product_meta');

add_action('utenzo_woocommerce_template_single_sharing', 'woocommerce_template_single_sharing', 50);
add_action('utenzo_checkout_review', 'woocommerce_order_review', 10);
add_action('utenzo_checkout_order', 'woocommerce_checkout_payment', 20);
add_action('utenzo_woocommerce_template_cross_sell', 'woocommerce_cross_sell_display', 50);
add_filter('woocommerce_product_description_heading', '__return_null');

add_action('utenzo_woocommerce_template_single_meta', 'utenzo_woocommerce_single_product_meta', 40);
add_action('utenzo_woocommerce_template_related_products', 'woocommerce_output_related_products', 20);
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);

add_action('utenzo_woocommerce_template_upsell_products', 'woocommerce_upsell_display', 20);
function register_product_taxonomy()
{
    $labels = array(
        'name' => _x('Materials', 'taxonomy general name', 'utenzo'),
        'singular_name' => _x('Material', 'taxonomy singular name', 'utenzo'),
        'search_items' => __('Search Materials', 'utenzo'),
        'all_items' => __('All Materials', 'utenzo'),
        'parent_item' => __('Parent Material', 'utenzo'),
        'parent_item_colon' => __('Parent Material:', 'utenzo'),
        'edit_item' => __('Edit Material', 'utenzo'),
        'update_item' => __('Update Material', 'utenzo'),
        'add_new_item' => __('Add New Material', 'utenzo'),
        'new_item_name' => __('New Material Name', 'utenzo'),
        'menu_name' => __('Materials', 'utenzo'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => false,
        'query_var' => true,
        'show_in_rest' => true,
        'publicly_queryable' => false,
    );

    register_taxonomy('product_material', array('product'), $args);
}

add_action('init', 'register_product_taxonomy');

function utenzo_woocommerce_single_product_meta()
{
    global $product;

    echo '<ul class="bt-product-meta">';

    $sku = $product->get_sku();
    if ($sku) {
        echo '<li class="sku"><span>SKU:</span> ' . esc_html($sku) . '</li>';
    }

    $post = get_post($product->get_id());
    $author_id = $post->post_author;
    $author = get_the_author_meta('display_name', $author_id);
    if ($author) {
        echo '<li class="vendor"><span>Vendor:</span> ' . esc_html($author) . '</li>';
    }

    $availability = $product->is_in_stock() ? 'In stock' : 'Out of stock';
    echo '<li class="availability"><span>Availability:</span> ' . esc_html($availability) . '</li>';

    $categories = wc_get_product_category_list($product->get_id());
    if ($categories) {
        echo '<li class="categories"><span>Categories:</span> ' . wp_kses_post($categories) . '</li>';
    }
    echo '</ul>';
}


// custom product loop image
add_action('utenzo_woocommerce_template_loop_product_thumbnail', 'bt_woocommerce_template_loop_product_thumbnail', 10);

function bt_woocommerce_template_loop_product_thumbnail()
{
    global $product;
    $image_size = apply_filters('single_product_archive_thumbnail_size', 'woocommerce_thumbnail');
    $image_id = $product->get_image_id();
    $image_url = wp_get_attachment_image_url($image_id, $image_size);

    // Get gallery images
    $gallery_image_ids = $product->get_gallery_image_ids();

    if ($image_url) {
        echo '<div class="product-images-wrapper">';

        // Always show main image
        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '" class="woocommerce-loop-product__image main-image" />';

        // If there are gallery images, show the first one
        if (!empty($gallery_image_ids)) {
            $second_image_url = wp_get_attachment_image_url($gallery_image_ids[0], $image_size);
            echo '<img src="' . esc_url($second_image_url) . '" alt="' . esc_attr($product->get_name()) . '" class="woocommerce-loop-product__image secondary-image" />';
        } else {
            // If no gallery images, show main image again
            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '" class="woocommerce-loop-product__image secondary-image" />';
        }

        echo '</div>';
    } else {
        echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('Placeholder', 'utenzo') . '" class="woocommerce-loop-product__image" />';
    }
}

add_action('woocommerce_cart_updated', 'utenzo_redirect_after_add_to_cart');

function utenzo_redirect_after_add_to_cart()
{
    if (WC()->session->get('redirect_after_add_to_cart')) {
        WC()->session->__unset('redirect_after_add_to_cart');
        wp_redirect(wc_get_cart_url());
        exit();
    }
}

add_filter('get_terms', 'utenzo_exclude_hidden_category', 10, 3);

function utenzo_exclude_hidden_category($terms, $taxonomies, $args)
{
    if (in_array('product_cat', $taxonomies)) {
        $exclude = array('uncategorized');
        foreach ($terms as $key => $term) {
            if (is_object($term) && isset($term->slug) && in_array($term->slug, $exclude)) {
                unset($terms[$key]);
            }
        }
    }
    return $terms;
}

// WooCommerce percentage flash
add_filter('woocommerce_sale_flash', 'utenzo_woocommerce_percentage_sale', 10, 3);

function utenzo_woocommerce_percentage_sale($html, $post, $product)
{
    if ($product->is_type('variable')) {
        $percentages = array();

        // Get all variation prices
        $prices = $product->get_variation_prices();

        // Loop through variation prices
        foreach ($prices['price'] as $key => $price) {
            // Only on sale variations
            if ($prices['regular_price'][$key] !== $price) {
                // Calculate and set in the array the percentage for each variation on sale
                $percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
            }
        }
        // We keep the highest value
        $percentage = max($percentages) . '%';
    } elseif ($product->is_type('grouped')) {
        $percentages = array();

        foreach ($product->get_children() as $child_id) {
            $child = wc_get_product($child_id);
            if (!empty($child->get_sale_price())) {
                $regular_price = $child->get_regular_price();
                $sale_price = $child->get_sale_price();
                $percentages[] = round(100 - ($sale_price / $regular_price * 100));
            }
        }

        $percentage = max($percentages) . '%';
    } else {
        $regular_price = (float) $product->get_regular_price();
        $sale_price = (float) $product->get_sale_price();

        $percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';
    }

    if (is_product() && is_single()) {
        return '<span class="onsale">-' . $percentage . ' ' . esc_html__('off', 'utenzo') . '</span>';
    } else {
        return '<span class="onsale">-' . $percentage . '</span>';
    }
}

add_filter('woocommerce_pagination_args', 'utenzo_woocommerce_pagination_args');

function utenzo_woocommerce_pagination_args()
{
    $total = isset($total) ? $total : wc_get_loop_prop('total_pages');
    $current = isset($current) ? $current : wc_get_loop_prop('current_page');
    $base = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
    $format = isset($format) ? $format : '';

    if ($total <= 1) {
        return;
    }

    return array(
        'base' => $base,
        'format' => $format,
        'total' => $total,
        'current' => $current,
        'mid_size' => 1,
        'add_args' => false,
        'prev_text' => '<svg width="19" height="16" viewBox="0 0 19 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9.71889 15.782L10.4536 15.0749C10.6275 14.9076 10.6275 14.6362 10.4536 14.4688L4.69684 8.92851L17.3672 8.92852C17.6131 8.92852 17.8125 8.73662 17.8125 8.49994L17.8125 7.49994C17.8125 7.26326 17.6131 7.07137 17.3672 7.07137L4.69684 7.07137L10.4536 1.53101C10.6275 1.36366 10.6275 1.0923 10.4536 0.924907L9.71889 0.2178C9.545 0.0504438 9.26304 0.0504438 9.08911 0.2178L1.31792 7.69691C1.14403 7.86426 1.14403 8.13562 1.31792 8.30301L9.08914 15.782C9.26304 15.9494 9.545 15.9494 9.71889 15.782Z"/>
                    </svg> ' . esc_html__('Prev', 'utenzo'),
        'next_text' => esc_html__('Next', 'utenzo') . '<svg width="19" height="16" viewBox="0 0 19 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.28111 0.217951L8.54638 0.925058C8.37249 1.09242 8.37249 1.36377 8.54638 1.53117L14.3032 7.07149L1.63283 7.07149C1.38691 7.07149 1.18752 7.26338 1.18752 7.50006L1.18752 8.50006C1.18752 8.73674 1.38691 8.92863 1.63283 8.92863L14.3032 8.92863L8.54638 14.469C8.37249 14.6363 8.37249 14.9077 8.54638 15.0751L9.28111 15.7822C9.455 15.9496 9.73696 15.9496 9.91089 15.7822L17.6821 8.30309C17.856 8.13574 17.856 7.86438 17.6821 7.69699L9.91086 0.217952C9.73696 0.0505587 9.455 0.0505586 9.28111 0.217951Z"/>
                  </svg>',
    );
}

// WooCommerce ralated params
add_filter('woocommerce_output_related_products_args', 'utenzo_woocommerce_related_products_args', 20);

function utenzo_woocommerce_related_products_args($args)
{
    if (function_exists('get_field')) {
        $related_posts = get_field('product_related_posts', 'options');
        $args['posts_per_page'] = !empty($related_posts['number_posts']) ? $related_posts['number_posts'] : 4;
    } else {
        $args['posts_per_page'] = 4;
    }

    $args['columns'] = 4;

    return $args;
}

/* Remove After Single Product Summary */
function utenzo_remove_after_single_product_summary() {
    if (function_exists('get_field')) {
        $enable_related_product = get_field('enable_related_product', 'options');
        
        if (!$enable_related_product) {
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
        }
    }
}
add_action('init', 'utenzo_remove_after_single_product_summary');

/* Sold Product */
function utenzo_woocommerce_item_sold($product_id)
{
    global $post;
    $args = array(
        'status' => 'completed',
        'limit' => -1,
    );
    $orders = wc_get_orders($args);

    $total_quantity_sold = 0;
    if (!empty($orders)) {
        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                if ($item->get_product_id() == $product_id) {
                    $total_quantity_sold += $item->get_quantity();
                }
            }
        }
    }
    $quantity_sold_option = get_post_meta($post->ID, '_product_sold', true);
    if ($quantity_sold_option != '' && $quantity_sold_option > 0) {
        $total_quantity_sold = $quantity_sold_option;
    }
    echo '<div class="woocommerce-loop-product__sold">';

    echo '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
  <path d="M17.106 9.80077L8.35603 19.1758C8.26331 19.2747 8.14091 19.3408 8.00731 19.3641C7.87372 19.3874 7.73617 19.3666 7.61543 19.3049C7.49468 19.2432 7.3973 19.1438 7.33796 19.0219C7.27863 18.8999 7.26057 18.762 7.2865 18.6289L8.43181 12.9L3.92947 11.2094C3.83277 11.1732 3.74655 11.1136 3.67849 11.036C3.61043 10.9584 3.56266 10.8651 3.53944 10.7645C3.51623 10.6639 3.5183 10.5591 3.54546 10.4595C3.57262 10.3599 3.62403 10.2686 3.69509 10.1937L12.4451 0.818744C12.5378 0.719788 12.6602 0.653675 12.7938 0.630383C12.9274 0.60709 13.065 0.627882 13.1857 0.68962C13.3064 0.751359 13.4038 0.850694 13.4632 0.972636C13.5225 1.09458 13.5406 1.23251 13.5146 1.36562L12.3662 7.10077L16.8685 8.78906C16.9645 8.82547 17.05 8.88496 17.1176 8.96228C17.1851 9.0396 17.2326 9.13236 17.2557 9.23237C17.2789 9.33237 17.2771 9.43655 17.2504 9.53569C17.2238 9.63482 17.1731 9.72587 17.1029 9.80077H17.106Z" fill="#C72929"/>
</svg>' . esc_html($total_quantity_sold);
    if ($total_quantity_sold > 1) {
        echo esc_html__(' items sold', 'utenzo');
    } else {
        echo esc_html__(' item sold', 'utenzo');
    }
    echo '</div>';
}

add_action('utenzo_woocommerce_shop_loop_item_sold', 'utenzo_woocommerce_item_sold', 10, 2);

/* Add Sold Product affer Quanty Single Product */
function utenzo_display_sold_after_rating()
{
    global $product;
    utenzo_woocommerce_item_sold($product->get_id());
}

add_action('utenzo_woocommerce_template_single_rating', 'utenzo_display_sold_after_rating', 15);
/* custom the "Additional information" tab title */
add_filter('woocommerce_product_tabs', 'utenzo_woocommerce_custom_additional_information_tab_title');

function utenzo_woocommerce_custom_additional_information_tab_title($tabs)
{
    if (isset($tabs['additional_information'])) {
        global $product;
        $mobile_text = '<span class="mobile-text">' . esc_html__('Information', 'utenzo') . '</span>';
        $tabs['additional_information']['title'] = sprintf(
            esc_html__('Additional Information', 'utenzo')
        ) . ' ' . $mobile_text;
    }
    return $tabs;
}

/* Custom the "Review" tab title */
add_filter('woocommerce_product_tabs', 'utenzo_woocommerce_custom_reviews_tab_title');

function utenzo_woocommerce_custom_reviews_tab_title($tabs)
{
    if (isset($tabs['reviews'])) {
        global $product;
        $mobile_text = '<span class="mobile-text">' . esc_html__('Reviews', 'utenzo') . '</span>';
        $tabs['reviews']['title'] = sprintf(
            esc_html__('Customer Reviews', 'utenzo')
        ) . ' ' . $mobile_text;
    }
    return $tabs;
}

// Add meta box for review title
function utenzo_add_review_title_meta_box()
{
    add_meta_box(
        'review_title_meta_box',
        __('Review Title', 'utenzo'),
        'utenzo_render_review_title_meta_box',
        'comment',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes_comment', 'utenzo_add_review_title_meta_box');

// Render meta box content
function utenzo_render_review_title_meta_box($comment)
{
    $review_title = get_comment_meta($comment->comment_ID, 'review_title', true);
    wp_nonce_field('utenzo_review_title_update', 'review_title_nonce');
?>
    <p>
        <label for="review_title"><?php esc_html_e('Review Title', 'utenzo'); ?></label><br>
        <input type="text" id="review_title" name="review_title" value="<?php echo esc_attr($review_title); ?>" size="30" maxlength="100">
    </p>
    <?php
}

// Save review title from both admin and frontend
function utenzo_save_review_title($comment_id)
{
    // For admin form submission
    if (isset($_POST['review_title_nonce']) && wp_verify_nonce($_POST['review_title_nonce'], 'utenzo_review_title_update')) {
        if (isset($_POST['review_title'])) {
            $review_title = sanitize_text_field($_POST['review_title']);
            update_comment_meta($comment_id, 'review_title', $review_title);
        }
    }
    // For frontend form submission
    elseif (isset($_POST['comment_post_ID'])) {
        if (isset($_POST['review_title'])) {
            $review_title = sanitize_text_field($_POST['review_title']);
            update_comment_meta($comment_id, 'review_title', $review_title);
        }
    }
}

add_action('comment_post', 'utenzo_save_review_title');
add_action('edit_comment', 'utenzo_save_review_title');

/* auto update mini cart */
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_icon_add_to_cart_fragment');
if (!function_exists('woocommerce_icon_add_to_cart_fragment')) {
    function woocommerce_icon_add_to_cart_fragment($fragments)
    {
        global $woocommerce;
        ob_start();
    ?>
        <span class="cart_total"><?php echo esc_html($woocommerce->cart->cart_contents_count); ?></span>
    <?php
        $fragments['span.cart_total'] = ob_get_clean();
        return $fragments;
    }
}

/* Create Product Wishlist Page And Compare Page */
function utenzo_product_create_pages_support()
{
    $product_wishlist_page = get_posts(array(
        'title' => 'Products Wishlist',
        'post_type' => 'page',
        'post_status' => 'any'
    ));

    if (count($product_wishlist_page) == 0) {
        wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Products Wishlist',
            'post_content' => 'Products Wishlist Page.',
            'post_name' => 'products-wishlist',
        ));
    }

    $product_compare_page = get_posts(array(
        'title' => 'Products Compare',
        'post_type' => 'page',
        'post_status' => 'any'
    ));

    if (count($product_compare_page) == 0) {
        wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Products Compare',
            'post_content' => 'Products Compare Page.',
            'post_name' => 'products-compare',
        ));
    }
}

add_action('init', 'utenzo_product_create_pages_support', 1);

function utenzo_get_products_by_rating($rating)
{
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => '_wc_average_rating',
                'value' => $rating,
                'compare' => '=',
                'type' => 'NUMERIC',
            ],
        ],
    ];

    $query = new WP_Query($args);
    return '(' . $query->found_posts . ')';
}

/* Field Product */
function utenzo_product_field_radio_html($slug = '', $field_title = '', $field_value = '')
{
    if (empty($slug)) {
        return;
    }

    $terms = get_terms(array(
        'taxonomy' => $slug,
        'hide_empty' => true
    ));

    $field_title_default = !empty($field_title) ? $field_title : 'Choose';

    if (!empty($terms) && !is_wp_error($terms)) {
    ?>
        <div class="bt-form-field bt-field-type-radio <?php echo 'bt-field-' . $slug; ?>" data-name="<?php echo esc_attr($slug); ?>">
            <div class="bt-field-title"><?php echo esc_html($field_title_default) ?></div>
            <?php foreach ($terms as $term) { ?>
                <?php if ($term->slug == $field_value) { ?>
                    <div class="item-radio">
                        <input type="radio" name="<?php echo esc_attr($slug); ?>" id="<?php echo esc_attr($term->slug); ?>" value="<?php echo esc_attr($term->slug); ?>" checked>
                        <label for="<?php echo esc_attr($term->slug); ?>" data-slug="<?php echo esc_attr($term->slug); ?>"> <?php echo esc_html($term->name); ?> </label>
                        <span class="bt-count"><?php echo '(' . $term->count . ')'; ?></span>
                    </div>
                <?php } else { ?>
                    <div class="item-radio">
                        <input type="radio" name="<?php echo esc_attr($slug); ?>" id="<?php echo esc_attr($term->slug); ?>" value="<?php echo esc_attr($term->slug); ?>">
                        <label for="<?php echo esc_attr($term->slug); ?>" data-slug="<?php echo esc_attr($term->slug); ?>"> <?php echo esc_html($term->name); ?> </label>
                        <span class="bt-count"><?php echo '(' . $term->count . ')'; ?></span>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php
    }
}

function utenzo_product_field_multiple_html($slug = '', $field_title = '', $field_value = '')
{
    if (empty($slug)) {
        return;
    }

    $terms = get_terms(array(
        'taxonomy' => $slug,
        'hide_empty' => true
    ));

    if (!empty($terms) && !is_wp_error($terms)) {
    ?>
        <div class="bt-form-field bt-field-type-multi" data-name="<?php echo esc_attr($slug); ?>">
            <?php
            if (!empty($field_title)) {
                echo '<div class="bt-field-title">' . $field_title . '</div>';
            }
            ?>

            <div class="bt-field-list">
                <?php foreach ($terms as $term) { ?>
                    <div class="<?php echo (str_contains($field_value, $term->slug)) ? 'bt-field-item checked' : 'bt-field-item' ?>">
                        <a href="#" data-slug="<?php echo esc_attr($term->slug); ?>">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M28.1489 8.44723C28.6566 8.98059 28.6358 9.82456 28.1025 10.3323L12.6951 24.9989C12.4319 25.2494 12.078 25.3817 11.7151 25.3652C11.3522 25.3486 11.0118 25.1848 10.7725 24.9114L4.8466 18.1422C4.36156 17.5882 4.41752 16.7458 4.97159 16.2607C5.52565 15.7757 6.36802 15.8317 6.85306 16.3857L11.8633 22.109L26.2639 8.4008C26.7972 7.89308 27.6412 7.91387 28.1489 8.44723Z" fill="white" />
                                </svg>
                            </span>
                            <?php echo esc_html($term->name); ?>
                            <div class="bt-count"><?php echo '(' . $term->count . ')'; ?></div>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <input type="hidden" name="<?php echo esc_attr($slug); ?>" value="<?php echo esc_attr($field_value); ?>">
        </div>
    <?php
    }
}

function utenzo_product_field_multiple_color_html($slug = '', $field_title = '', $field_value = '')
{
    if (empty($slug)) {
        return;
    }

    $terms = get_terms(array(
        'taxonomy' => $slug,
        'hide_empty' => true
    ));

    if (!empty($terms) && !is_wp_error($terms)) {
    ?>
        <div class="bt-form-field bt-field-type-multi bt-field-color" data-name="<?php echo esc_attr($slug); ?>">
            <?php
            if (!empty($field_title)) {
                echo '<div class="bt-field-title">' . $field_title . '</div>';
            }
            ?>

            <div class="bt-field-list">
                <?php
                foreach ($terms as $term) {
                    $term_id = $term->term_id;
                    $color = get_field('color', 'pa_color_' . $term_id);
                    if (!$color) {
                        $color = $term->slug;
                    }
                ?>
                    <div class="<?php echo (str_contains($field_value, $term->slug)) ? 'bt-field-item checked' : 'bt-field-item' ?>">
                        <a href="#" data-slug="<?php echo esc_attr($term->slug); ?>">
                            <span style="background:<?php echo esc_attr($color); ?>">
                            </span>
                            <?php echo esc_html($term->name); ?>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <input type="hidden" name="<?php echo esc_attr($slug); ?>" value="<?php echo esc_attr($field_value); ?>">
        </div>
    <?php
    }
}

function utenzo_product_field_price_slider($field_title = '', $field_min_value = '', $field_max_value = '')
{
    $prices = utenzo_highest_and_lowest_product_price();
    $currency_symbol = get_woocommerce_currency_symbol();
    if ($prices['lowest_price'] == $prices['highest_price']) {
        return;
    }

    $start_min_value = !empty($field_min_value) ? $field_min_value : $prices['lowest_price'];
    $start_max_value = !empty($field_max_value) ? $field_max_value : $prices['highest_price'];

    ?>
    <div class="bt-form-field bt-field-price" data-name="product_price">
        <?php
        if (!empty($field_title)) {
            echo '<div class="bt-field-title">' . $field_title . '</div>';
        }
        ?>
        <div id="bt-price-slider" data-range-min="<?php echo intval($prices['lowest_price']); ?>" data-range-max="<?php echo intval($prices['highest_price']); ?>" data-start-min="<?php echo intval($start_min_value); ?>" data-start-max="<?php echo intval($start_max_value); ?>"></div>
        <div class="bt-field-price-options">
            <div class="bt-field-min-price">
                <label for="bt-min-price"><?php esc_html_e('Min price', 'utenzo') ?></label>
                <input type="number" id="bt-min-price" name="min_price" value="" placeholder="<?php echo esc_attr($start_min_value); ?>">
                <span class="bt-currency"><?php echo esc_html($currency_symbol); ?></span>
            </div>
            <div class="bt-field-max-price">
                <label for="bt-max-price"><?php esc_html_e('Max price', 'utenzo') ?></label>
                <input type="number" id="bt-max-price" name="max_price" value="" placeholder="<?php echo esc_attr($start_max_value); ?>">
                <span class="bt-currency"><?php echo esc_html($currency_symbol); ?></span>
            </div>
        </div>
    </div>
<?php
}

function utenzo_product_field_rating($slug = '', $field_title = '', $field_value = '')
{
    if (empty($slug)) {
        return;
    }

    $field_title_default = !empty($field_title) ? $field_title : 'Choose';
?>
    <div class="bt-form-field bt-field-type-rating <?php echo 'bt-field-' . $slug; ?>" data-name="<?php echo esc_attr($slug); ?>">
        <div class="bt-field-title"><?php echo esc_html($field_title_default) ?></div>
        <?php
        for ($rating = 5; $rating >= 1; $rating--) {
            $stars = str_repeat('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
  <path d="M14.6431 7.17815L11.8306 9.60502L12.6875 13.2344C12.7347 13.4314 12.7226 13.638 12.6525 13.8281C12.5824 14.0182 12.4575 14.1833 12.2937 14.3025C12.1298 14.4217 11.9343 14.4896 11.7319 14.4977C11.5294 14.5059 11.3291 14.4538 11.1562 14.3481L7.99996 12.4056L4.84184 14.3481C4.66898 14.4532 4.4689 14.5048 4.2668 14.4963C4.06469 14.4879 3.8696 14.4199 3.70609 14.3008C3.54257 14.1817 3.41795 14.0169 3.3479 13.8272C3.27786 13.6374 3.26553 13.4312 3.31246 13.2344L4.17246 9.60502L1.35996 7.17815C1.20702 7.04597 1.09641 6.87166 1.04195 6.67699C0.987486 6.48232 0.99158 6.27592 1.05372 6.08356C1.11586 5.89121 1.23329 5.72142 1.39135 5.59541C1.54941 5.4694 1.7411 5.39274 1.94246 5.37502L5.62996 5.07752L7.05246 1.63502C7.12946 1.44741 7.26051 1.28693 7.42894 1.17398C7.59738 1.06104 7.7956 1.00073 7.9984 1.00073C8.2012 1.00073 8.39942 1.06104 8.56785 1.17398C8.73629 1.28693 8.86734 1.44741 8.94434 1.63502L10.3662 5.07752L14.0537 5.37502C14.2555 5.39209 14.4477 5.46831 14.6064 5.59415C14.765 5.71999 14.883 5.88984 14.9455 6.08243C15.008 6.27502 15.0123 6.48178 14.9579 6.6768C14.9034 6.87183 14.7926 7.04644 14.6393 7.17877L14.6431 7.17815Z" fill="#212121"/>
</svg>', $rating) . str_repeat('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
  <path d="M14.9483 6.07866C14.8858 5.88649 14.7678 5.71712 14.6092 5.59189C14.4506 5.46665 14.2585 5.39116 14.0571 5.37491L10.3696 5.07741L8.9458 1.63429C8.86881 1.44667 8.73776 1.28619 8.56932 1.17325C8.40088 1.06031 8.20267 1 7.99987 1C7.79707 1 7.59885 1.06031 7.43041 1.17325C7.26197 1.28619 7.13093 1.44667 7.05393 1.63429L5.63143 5.07679L1.94205 5.37491C1.74029 5.39198 1.54805 5.4682 1.38941 5.59404C1.23078 5.71988 1.11281 5.88974 1.05028 6.08232C0.987751 6.27491 0.983448 6.48167 1.03791 6.67669C1.09237 6.87172 1.20317 7.04633 1.35643 7.17866L4.16893 9.60554L3.31205 13.2343C3.26413 13.4314 3.27586 13.6384 3.34577 13.8288C3.41567 14.0193 3.54058 14.1847 3.70465 14.304C3.86873 14.4234 4.06456 14.4913 4.26729 14.4991C4.47002 14.5069 4.67051 14.4544 4.8433 14.348L7.99955 12.4055L11.1577 14.348C11.3305 14.4531 11.5306 14.5047 11.7327 14.4962C11.9348 14.4878 12.1299 14.4198 12.2934 14.3007C12.4569 14.1816 12.5816 14.0168 12.6516 13.8271C12.7217 13.6373 12.734 13.431 12.6871 13.2343L11.8271 9.60491L14.6396 7.17804C14.7941 7.04593 14.9059 6.87094 14.9608 6.67523C15.0158 6.47952 15.0114 6.27189 14.9483 6.07866ZM13.9896 6.42054L10.9458 9.04554C10.8764 9.10537 10.8248 9.18312 10.7965 9.27031C10.7683 9.3575 10.7646 9.45076 10.7858 9.53992L11.7158 13.4649C11.7182 13.4703 11.7184 13.4765 11.7165 13.482C11.7145 13.4876 11.7105 13.4922 11.7052 13.4949C11.6939 13.5037 11.6908 13.5018 11.6814 13.4949L8.26143 11.3918C8.18266 11.3434 8.09201 11.3177 7.99955 11.3177C7.90709 11.3177 7.81644 11.3434 7.73768 11.3918L4.31768 13.4962C4.3083 13.5018 4.3058 13.5037 4.29393 13.4962C4.28865 13.4935 4.28461 13.4889 4.28263 13.4833C4.28066 13.4777 4.2809 13.4716 4.2833 13.4662L5.2133 9.54117C5.2345 9.45201 5.23078 9.35875 5.20257 9.27156C5.17435 9.18437 5.12272 9.10662 5.0533 9.04679L2.00955 6.42179C2.00205 6.41554 1.99518 6.40991 2.00143 6.39054C2.00768 6.37116 2.01268 6.37366 2.02205 6.37241L6.01705 6.04991C6.10868 6.04206 6.19637 6.00908 6.27047 5.9546C6.34457 5.90013 6.40221 5.82628 6.43705 5.74116L7.9758 2.01554C7.9808 2.00491 7.98268 1.99991 7.99768 1.99991C8.01268 1.99991 8.01455 2.00491 8.01955 2.01554L9.56205 5.74116C9.59722 5.82631 9.65523 5.90008 9.72967 5.95434C9.80412 6.00861 9.89211 6.04125 9.98393 6.04866L13.9789 6.37116C13.9883 6.37116 13.9939 6.37116 13.9996 6.38929C14.0052 6.40741 13.9996 6.41429 13.9896 6.42054Z" fill="#212121"/>
</svg>', 5 - $rating);
        ?>
            <?php if ($rating == $field_value) { ?>
                <div class="item-rating">
                    <span class="check-rating"></span>
                    <input type="radio" name="<?php echo esc_attr($slug); ?>" id="<?php echo 'rating' . $rating ?>" value="<?php echo esc_attr($rating); ?>" checked>
                    <?php
                    echo '<label class="bt-star" for="rating' . $rating . '">' . $stars . '</label>';
                    ?>
                    <span class="bt-count"><?php echo utenzo_get_products_by_rating($rating) ?></span>
                </div>
            <?php } else { ?>
                <div class="item-rating">
                    <span class="check-rating"></span>
                    <input type="radio" name="<?php echo esc_attr($slug); ?>" id="<?php echo 'rating' . $rating ?>" value="<?php echo esc_attr($rating); ?>">
                    <?php
                    echo '<label class="bt-star" for="rating' . $rating . '">' . $stars . '</label>';
                    ?>
                    <span class="bt-count"><?php echo utenzo_get_products_by_rating($rating) ?></span>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
<?php
}

function utenzo_highest_and_lowest_product_price()
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );

    $query = new WP_Query($args);

    $prices = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $sale_price = get_post_meta(get_the_ID(), '_sale_price', true);
            if (!empty($sale_price)) {
                $prices[] = floatval($sale_price);
            } else {
                $regular_price = get_post_meta(get_the_ID(), '_regular_price', true);
                if (!empty($regular_price)) {
                    $prices[] = floatval($regular_price);
                }
            }
        }

        if (!empty($prices)) {
            $highest_price = ceil(max($prices));
            $lowest_price = floor(min($prices));
            return array(
                'highest_price' => $highest_price,
                'lowest_price' => $lowest_price
            );
        }
    }

    wp_reset_postdata();

    return array(
        'highest_price' => 0,
        'lowest_price' => 0
    );
}

function utenzo_product_pagination($current_page, $total_page)
{
    if (1 >= $total_page) {
        return;
    }

    ob_start();
?>
    <nav class="bt-pagination bt-product-pagination" role="navigation">
        <?php if (1 != $current_page) { ?>
            <a class="prev page-numbers" href="#" data-page="<?php echo esc_attr($current_page - 1); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
                    <path d="M0.839282 12.4903C0.630446 12.2842 0.611461 11.9616 0.782327 11.7343L0.839282 11.6692L5.91327 6.6604L0.839282 1.65162C0.630446 1.44548 0.611461 1.1229 0.782327 0.895592L0.839282 0.830468C1.04812 0.624326 1.37491 0.605586 1.6052 0.774247L1.67117 0.830468L7.16137 6.24982C7.3702 6.45596 7.38919 6.77854 7.21832 7.00585L7.16137 7.07098L1.67117 12.4903C1.44145 12.7171 1.069 12.7171 0.839282 12.4903Z" fill="#212121"></path>
                </svg> <?php echo esc_html__('Prev', 'utenzo'); ?></a>
        <?php } ?>

        <?php
        for ($i = 1; $i <= $total_page; $i++) {
            if (7 > $total_page) {
                if ($i == $current_page) {
                    echo '<span class="page-numbers current">' . $i . '</span>';
                } else {
                    echo '<a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a>';
                }
            } else {
                if ($i == $current_page) {
                    echo '<span class="page-numbers current">' . $i . '</span>';
                }

                if (5 > $current_page) {
                    if ($i != $current_page && $i < $current_page + 3) {
                        echo '<a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a>';
                    }

                    if ($i == $current_page + 3) {
                        echo '<span class="page-numbers dots">...</span>';
                    }

                    if ($i == $total_page) {
                        echo '<a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a>';
                    }
                }

                if ($total_page - 4 < $current_page) {
                    if ($i != $current_page && $i > $current_page - 3) {
                        echo '<a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a>';
                    }

                    if ($i == $current_page - 3) {
                        echo '<span class="page-numbers dots">...</span>';
                    }

                    if ($i == 1) {
                        echo '<a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a>';
                    }
                }

                if ($total_page - 4 >= $current_page && 5 <= $current_page) {
                    if ($i != $current_page && $i > $current_page - 3 && $i < $current_page + 3) {
                        echo '<a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a>';
                    }

                    if ($i == $current_page - 3 || $i == $current_page + 3) {
                        echo '<span class="page-numbers dots">...</span>';
                    }

                    if ($i == 1) {
                        echo '<a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a>';
                    }

                    if ($i == $total_page) {
                        echo '<a class="page-numbers" href="#" data-page="' . $i . '">' . $i . '</a>';
                    }
                }
            }
        }
        ?>

        <?php if ($total_page != $current_page) { ?>
            <a class="next page-numbers" href="#" data-page="<?php echo esc_attr($current_page + 1); ?>"><?php echo esc_html__('Next', 'utenzo'); ?><svg xmlns="http://www.w3.org/2000/svg" width="8" height="13" viewBox="0 0 8 13" fill="none">
                    <path d="M0.839282 12.4903C0.630446 12.2842 0.611461 11.9616 0.782327 11.7343L0.839282 11.6692L5.91327 6.6604L0.839282 1.65162C0.630446 1.44548 0.611461 1.1229 0.782327 0.895592L0.839282 0.830468C1.04812 0.624326 1.37491 0.605586 1.6052 0.774247L1.67117 0.830468L7.16137 6.24982C7.3702 6.45596 7.38919 6.77854 7.21832 7.00585L7.16137 7.07098L1.67117 12.4903C1.44145 12.7171 1.069 12.7171 0.839282 12.4903Z" fill="#212121"></path>
                </svg></a>
        <?php } ?>
    </nav>
    <?php
    return ob_get_clean();
}

function utenzo_products_query_args($params = array(), $limit = 9)
{
    $query_args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $limit
    );

    if (isset($params['current_page']) && $params['current_page'] != '') {
        $query_args['paged'] = absint($params['current_page']);
    }

    if (isset($params['search_keyword']) && $params['search_keyword'] != '') {
        $query_args['s'] = $params['search_keyword'];
    }

    if (isset($params['sort_order']) && $params['sort_order'] != '') {
        if ($params['sort_order'] == 'date_high' || $params['sort_order'] == 'date_low') {
            $query_args['orderby'] = 'date';

            if ($params['sort_order'] == 'date_high') {
                $query_args['order'] = 'DESC';
            } else {
                $query_args['order'] = 'ASC';
            }
        }
        if ($params['sort_order'] == 'price_high' || $params['sort_order'] == 'price_low') {
            $query_args['meta_key'] = '_price';
            $query_args['orderby'] = 'meta_value_num';

            if ($params['sort_order'] == 'price_high') {
                $query_args['order'] = 'DESC';
            } else {
                $query_args['order'] = 'ASC';
            }
        }
        if ($params['sort_order'] == 'best_selling') {
            $query_args['meta_key'] = 'total_sales';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = 'DESC';
        }
        if ($params['sort_order'] == 'average_rating') {
            $query_args['meta_key'] = '_wc_average_rating';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = 'DESC';
        }
    }

    $query_tax = array();

    if (isset($params['product_cat']) && $params['product_cat'] != '') {
        $query_tax[] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => explode(',', $params['product_cat'])
        );
    }
    if (isset($params['product_brand']) && $params['product_brand'] != '') {
        $query_tax[] = array(
            'taxonomy' => 'product_brand',
            'field' => 'slug',
            'terms' => explode(',', $params['product_brand'])
        );
    }
    if (isset($params['product_material']) && $params['product_material'] != '') {
        $query_tax[] = array(
            'taxonomy' => 'product_material',
            'field' => 'slug',
            'terms' => explode(',', $params['product_material'])
        );
    }
    if (isset($params['pa_color']) && $params['pa_color'] != '') {
        $query_tax[] = array(
            'taxonomy' => 'pa_color',
            'field' => 'slug',
            'terms' => explode(',', $params['pa_color'])
        );
    }
    if (!empty($query_tax)) {
        $query_args['tax_query'] = $query_tax;
    }

    $query_meta = array();

    if (isset($params['min_price']) && $params['min_price'] != '' && isset($params['max_price']) && $params['max_price'] != '') {
        $min_price = $params['min_price'];
        $max_price = $params['max_price'];

        $query_meta['price_clause'] = array(
            array(
                'key' => '_price',
                'value' => array($min_price, $max_price),
                'compare' => 'BETWEEN',
                'type' => 'DECIMAL'
            ),
        );
    }
    if (isset($params['product_rating']) && $params['product_rating'] != '') {
        $query_meta['rating_clause'] = array(
            array(
                'key' => '_wc_average_rating',
                'value' => $params['product_rating'],
                'compare' => '=',
                'type' => 'NUMERIC'
            ),
        );
    }

    if (!empty($query_meta)) {
        $query_args['meta_query'] = $query_meta;
        $query_args['relation'] = 'AND';
    }

    return $query_args;
}

function utenzo_products_filter()
{
    $rows = intval(get_option('woocommerce_catalog_rows', 2));
    $columns = intval(get_option('woocommerce_catalog_columns', 4));
    $rows = $rows > 0 ? $rows : 1;
    $columns = $columns > 0 ? $columns : 1;
    $limit = $rows * $columns;
    $query_args = utenzo_products_query_args($_POST, $limit);
    $wp_query = new \WP_Query($query_args);
    $current_page = isset($_POST['current_page']) && $_POST['current_page'] != '' ? absint($_POST['current_page']) : 1;
    $total_page = $wp_query->max_num_pages;

    $paged = !empty($wp_query->query_vars['paged']) ? $wp_query->query_vars['paged'] : 1;

    $total_products = $wp_query->found_posts;

    // Update Results Block
    ob_start();
    if ($total_products > 0) {
        $product_text = ($total_products == 1) ? __('%s Product Found', 'utenzo') : __('%s Products Found', 'utenzo');
        printf(
            $product_text,
            '<span class="highlight">' . esc_html($total_products) . '</span>'
        );
    } else {
        echo esc_html__('No results', 'utenzo');
    }
    $output['results'] = ob_get_clean();
    // update button Results
    $product_text = ($total_products == 1) ? __('Show %s Product', 'utenzo') : __('Show %s Products', 'utenzo');
    printf($total_products > 0 ? $product_text : esc_html__('No products found', 'utenzo'), $total_products);
    $output['button-results'] = ob_get_clean();
    // Update Loop Post
    if ($wp_query->have_posts()) {
        ob_start();
        global $is_ajax_filter_product;
        $is_ajax_filter_product = true;
        while ($wp_query->have_posts()) {
            $wp_query->the_post();

            wc_get_template_part('content', 'product');
        }
        $is_ajax_filter_product = false;
        $output['items'] = ob_get_clean();
        $output['pagination'] = utenzo_product_pagination($current_page, $total_page);
    } else {
        $output['items'] = '<h3 class="not-found-post">' . esc_html__('Sorry, No products found', 'utenzo') . '</h3>';
        $output['pagination'] = '';
    }

    wp_reset_postdata();

    wp_send_json_success($output);

    die();
}

add_action('wp_ajax_utenzo_products_filter', 'utenzo_products_filter');
add_action('wp_ajax_nopriv_utenzo_products_filter', 'utenzo_products_filter');

function utenzo_products_compare()
{
    $productcompare = '';
    $product_ids = array();
    $ex_items = count($product_ids) < 3 ? 3 - count($product_ids) : 0;
    $productcompare = isset($_POST['compare_data']) ? $_POST['compare_data'] : '';
    if (!empty($productcompare)) {
        $product_ids = explode(',', $productcompare);
    }
    $ex_items = count($product_ids) < 3 ? 3 - count($product_ids) : 0;
    ob_start();
    if (!empty($product_ids)) {
        $compare_settings = get_field('compare', 'options');
        if (!empty($compare_settings['fields_to_show_compare'])) {
            $fields_show_compare = $compare_settings['fields_to_show_compare'];
        } else {
            $fields_show_compare = array('price', 'rating', 'stock_status', 'weight', 'dimensions', 'color', 'size');
        }
    ?>
        <div class="bt-table-title">
            <h2><?php esc_html_e('Compare products', 'utenzo') ?></h2>
        </div>
        <div class="bt-wrap-compare">
            <div class="bt-table-compare">
                <div class="bt-table--head">
                    <?php
                    if (!empty($fields_show_compare)) {
                        echo '<div class="bt-table--col">' . esc_html__('Thumbnail', 'utenzo') . '</div>';
                        echo '<div class="bt-table--col">' . esc_html__('Product Name', 'utenzo') . '</div>';
                        if (in_array('short_desc', $fields_show_compare)) {
                            echo '<div class="bt-table--col">' . esc_html__('Short Description', 'utenzo') . '</div>';
                        }
                        if (in_array('price', $fields_show_compare)) {
                            echo '<div class="bt-table--col">' . esc_html__('Price', 'utenzo') . '</div>';
                        }
                        if (in_array('rating', $fields_show_compare)) {
                            echo '<div class="bt-table--col">' . esc_html__('Rating', 'utenzo') . '</div>';
                        }
                        if (in_array('brand', $fields_show_compare)) {
                            echo '<div class="bt-table--col">' . esc_html__('Brand', 'utenzo') . '</div>';
                        }
                        if (in_array('stock_status', $fields_show_compare)) {
                            echo '<div class="bt-table--col">' . esc_html__('Availability', 'utenzo') . '</div>';
                        }
                        if (in_array('sku', $fields_show_compare)) {
                            echo '<div class="bt-table--col">' . esc_html__('SKU', 'utenzo') . '</div>';
                        }
                        if (in_array('weight', $fields_show_compare)) {
                            echo '<div class="bt-table--col">' . esc_html__('Weight', 'utenzo') . '</div>';
                        }
                        if (in_array('dimensions', $fields_show_compare)) {
                            echo '<div class="bt-table--col">' . esc_html__('Dimensions', 'utenzo') . '</div>';
                        }
                        if (in_array('color', $fields_show_compare)) {
                            echo '<div class="bt-table--col bt-head-color">' . esc_html__('color', 'utenzo') . '</div>';
                        }
                        if (in_array('size', $fields_show_compare)) {
                            echo '<div class="bt-table--col">' . esc_html__('Size', 'utenzo') . '</div>';
                        }
                    }
                    ?>
                    <div class="bt-table--col"></div>
                </div>
                <div class="bt-table--body">
                    <?php
                    foreach ($product_ids as $key => $id) {
                        $product = wc_get_product($id);
                        if ($product) {
                            $product_url = get_permalink($id);
                            $product_name = $product->get_name();
                            $product_image = wp_get_attachment_image_src($product->get_image_id(), 'large');
                            if (!$product_image) {
                                $product_image_url = wc_placeholder_img_src();
                            } else {
                                $product_image_url = $product_image[0];
                            }
                            $product_price = $product->get_price_html();
                            $stock_status = $product->get_stock_status();
                            if ($stock_status == 'onbackorder') {
                                $stock_status_custom = '<p class="stock on-backorder">' . __('On Backorder', 'utenzo') . '</p>';
                            } elseif ($product->is_in_stock()) {
                                $stock_status_custom = '<p class="stock in-stock">' . __('In Stock', 'utenzo') . '</p>';
                            } else {
                                $stock_status_custom = '<p class="stock out-of-stock">' . __('Out of Stock', 'utenzo') . '</p>';
                            }
                            $brand = wp_get_post_terms($id, 'product_brand', ['fields' => 'names']);
                            $brand_list = !empty($brand) ? implode(', ', $brand) : '';

                            $brands = wp_get_post_terms($id, 'product_brand', ['fields' => 'all']);
                            $brand_links = [];
                            foreach ($brands as $brand) {
                                $brand_links[] = '<a href="' . get_term_link($brand) . '">' . esc_html($brand->name) . '</a>';
                            }
                            $brand_list = !empty($brand_links) ? implode(', ', $brand_links) : '';

                    ?>
                            <div class="bt-table--row">
                                <div class="bt-table--col bt-thumb">
                                    <div class="bt-remove-item" data-id="<?php echo esc_attr($id) ?>">
                                        <div class="bt-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M9.41183 8L15.6952 1.71665C15.7905 1.62455 15.8666 1.51437 15.9189 1.39255C15.9713 1.27074 15.9988 1.13972 16 1.00714C16.0011 0.874567 15.9759 0.743089 15.9256 0.620381C15.8754 0.497673 15.8013 0.386193 15.7076 0.292444C15.6138 0.198695 15.5023 0.124556 15.3796 0.0743523C15.2569 0.0241486 15.1254 -0.00111435 14.9929 3.76988e-05C14.8603 0.00118975 14.7293 0.0287337 14.6074 0.0810623C14.4856 0.133391 14.3755 0.209456 14.2833 0.30482L8 6.58817L1.71665 0.30482C1.52834 0.122941 1.27612 0.0223015 1.01433 0.0245764C0.752534 0.0268514 0.502106 0.131859 0.316983 0.316983C0.131859 0.502107 0.0268514 0.752534 0.0245764 1.01433C0.0223015 1.27612 0.122941 1.52834 0.30482 1.71665L6.58817 8L0.30482 14.2833C0.209456 14.3755 0.133391 14.4856 0.0810623 14.6074C0.0287337 14.7293 0.00118975 14.8603 3.76988e-05 14.9929C-0.00111435 15.1254 0.0241486 15.2569 0.0743523 15.3796C0.124556 15.5023 0.198695 15.6138 0.292444 15.7076C0.386193 15.8013 0.497673 15.8754 0.620381 15.9256C0.743089 15.9759 0.874567 16.0011 1.00714 16C1.13972 15.9988 1.27074 15.9713 1.39255 15.9189C1.51437 15.8666 1.62455 15.7905 1.71665 15.6952L8 9.41183L14.2833 15.6952C14.4226 15.8358 14.6006 15.9317 14.7945 15.9708C14.9885 16.0098 15.1898 15.9902 15.3726 15.9145C15.5554 15.8388 15.7115 15.7104 15.8211 15.5456C15.9306 15.3808 15.9886 15.1871 15.9877 14.9893C15.9878 14.8581 15.9619 14.7283 15.9117 14.6072C15.8615 14.4861 15.7879 14.376 15.6952 14.2833L9.41183 8Z" fill="#0C2C48" />
                                            </svg>
                                        </div>
                                    </div>
                                    <a href="<?php echo esc_url($product_url); ?>">
                                        <img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_name); ?>">
                                    </a>
                                </div>
                                <div class="bt-table--col bt-name">
                                    <h3><a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_name); ?></a></h3>
                                </div>
                                <?php if (!empty($fields_show_compare)) {
                                    if (in_array('short_desc', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-description">
                                            <?php echo '<p>' . wp_trim_words($product->get_short_description(), 20) . '</p>'; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (in_array('price', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-price">
                                            <?php echo '<p>' . $product_price . '</p>'; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (in_array('rating', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-rating woocommerce">
                                            <div class="bt-product-rating">
                                                <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                                                <?php if ($product->get_rating_count()): ?>
                                                    <div class="bt-product-rating--count">
                                                        (<?php echo esc_html($product->get_rating_count()); ?>)
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (in_array('brand', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-brand">
                                            <?php echo '<p>' . $brand_list . '</p>'; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (in_array('stock_status', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-stock">
                                            <?php echo wp_kses_post($stock_status_custom); ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (in_array('sku', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-sku">
                                            <?php echo '<p>' . $product->get_sku() . '</p>'; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (in_array('weight', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-weight">
                                            <?php echo '<p>' . $product->get_weight() . ' ' . get_option('woocommerce_weight_unit') . '</p>'; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (in_array('dimensions', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-dimensions">
                                            <?php echo '<p>' . wc_format_dimensions($product->get_dimensions(false)) . '</p>'; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (in_array('color', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-color">
                                            <?php
                                            $colors = wp_get_post_terms($id, 'pa_color', ['fields' => 'ids']);
                                            $count = 0;
                                            foreach ($colors as $color_id) {
                                                if ($count >= 6) break; // Only show max 6 colors
                                                $color_value = get_field('color', 'pa_color_' . $color_id);
                                                $color = get_term($color_id, 'pa_color');
                                                if (!$color_value) {
                                                    $color_value = $color->slug;
                                                }
                                                echo '<div class="bt-item-color"><span style="background-color: ' . esc_attr($color_value) . ';"></span>' . esc_html($color->name) . '</div>';
                                                $count++;
                                            }
                                            ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (in_array('size', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-size">
                                            <?php
                                            $sizes = wp_get_post_terms($id, 'pa_size', ['fields' => 'names']);
                                            echo '<p>' . (!empty($sizes) ? implode(', ', $sizes) : 'N/A') . '</p>';
                                            ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="bt-table--col bt-add-to-cart">
                                    <?php
                                    $product = wc_get_product($id);
                                    if ($product->is_type('simple')) {
                                    ?>
                                        <a href="?add-to-cart=<?php echo esc_attr($id); ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr($id); ?>" data-quantity="1" class="bt-button product_type_simple add_to_cart_button ajax_add_to_cart bt-button-hover" data-product_id="<?php echo esc_attr($id); ?>" data-product_sku="" rel="nofollow"><?php echo esc_html__('Add to cart', 'utenzo') ?></a>
                                    <?php
                                    } else {
                                    ?>
                                        <a href="<?php echo esc_url(get_permalink($id)); ?>" class="bt-button bt-button-hover"><?php echo esc_html__('View Product', 'utenzo') ?></a>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <?php
                    if ($ex_items > 0) {
                        for ($i = 0; $i < $ex_items; $i++) {
                    ?>
                            <div class="bt-table--row bt-product-add-compare">
                                <div class="bt-table--col bt-thumb">
                                    <div class="bt-cover-image">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" fill="currentColor">
                                            <path d="M256 512a25 25 0 0 1-25-25V25a25 25 0 0 1 50 0v462a25 25 0 0 1-25 25z"></path>
                                            <path d="M487 281H25a25 25 0 0 1 0-50h462a25 25 0 0 1 0 50z"></path>
                                        </svg>
                                        <span> <?php echo __('Add Product To Compare', 'utenzo'); ?></span>
                                    </div>
                                </div>
                                <div class="bt-table--col bt-name"></div>
                                <?php if (!empty($fields_show_compare)) {
                                    if (in_array('short_desc', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-description"></div>
                                    <?php } ?>
                                    <?php if (in_array('price', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-price"></div>
                                    <?php } ?>
                                    <?php if (in_array('rating', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-rating woocommerce"></div>
                                    <?php } ?>
                                    <?php if (in_array('brand', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-brand"></div>
                                    <?php } ?>
                                    <?php if (in_array('stock_status', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-stock"></div>
                                    <?php } ?>
                                    <?php if (in_array('sku', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-sku"></div>
                                    <?php } ?>
                                    <?php if (in_array('weight', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-weight"></div>
                                    <?php } ?>
                                    <?php if (in_array('dimensions', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-dimensions"></div>
                                    <?php } ?>
                                    <?php if (in_array('color', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-color"></div>
                                    <?php } ?>
                                    <?php if (in_array('size', $fields_show_compare)) { ?>
                                        <div class="bt-table--col bt-size"></div>
                                    <?php } ?>
                                <?php } ?>
                                <div class="bt-table--col"></div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php
        $count = count($product_ids);
        $output['count'] = $count;
    } else {
    ?>
        <div class="bt-table-title">
            <h2><?php esc_html_e('Compare products', 'utenzo') ?></h2>
        </div>
        <div class="bt-table-compare">
            <div class="bt-table--head">
                <div class="bt-table--col"><?php esc_html_e('Thumbnail', 'utenzo') ?></div>
                <div class="bt-table--col"><?php esc_html_e('Product Name', 'utenzo') ?></div>
                <div class="bt-table--col"><?php esc_html_e('Price', 'utenzo') ?></div>
                <div class="bt-table--col"><?php esc_html_e('Stock status', 'utenzo') ?></div>
                <div class="bt-table--col"><?php esc_html_e('Rating', 'utenzo') ?></div>
                <div class="bt-table--col"><?php esc_html_e('Brand', 'utenzo') ?></div>
                <div class="bt-table--col"></div>
            </div>
            <div class="bt-table--body">
                <?php
                if ($ex_items > 0) {
                    for ($i = 0; $i < $ex_items; $i++) {
                ?>
                        <div class="bt-table--row bt-product-add-compare">
                            <div class="bt-table--col bt-thumb">
                                <div class="bt-cover-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" fill="currentColor">
                                        <path d="M256 512a25 25 0 0 1-25-25V25a25 25 0 0 1 50 0v462a25 25 0 0 1-25 25z"></path>
                                        <path d="M487 281H25a25 25 0 0 1 0-50h462a25 25 0 0 1 0 50z"></path>
                                    </svg>
                                    <span> <?php echo __('Add Product To Compare', 'utenzo'); ?></span>
                                </div>
                            </div>
                            <div class="bt-table--col bt-name">

                            </div>
                            <div class="bt-table--col bt-price">

                            </div>
                            <div class="bt-table--col bt-stock">
                            </div>
                            <div class="bt-table--col bt-rating">
                            </div>
                            <div class="bt-table--col bt-brand">
                            </div>
                            <div class="bt-table--col bt-add-to-cart">

                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
    $output['product'] = ob_get_clean();

    wp_send_json_success($output);
    die();
}

add_action('wp_ajax_utenzo_products_compare', 'utenzo_products_compare');
add_action('wp_ajax_nopriv_utenzo_products_compare', 'utenzo_products_compare');

function utenzo_products_wishlist()
{
    if (isset($_POST['productwishlist_data']) && !empty($_POST['productwishlist_data'])) {
        $product_ids = explode(',', $_POST['productwishlist_data']);
        $output['count'] = count($product_ids);

        ob_start();
        foreach ($product_ids as $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                $product_price = $product->get_price_html();
                $stock_status = $product->is_in_stock() ? __('In Stock', 'utenzo') : __('Out of Stock', 'utenzo');
        ?>
                <div class="bt-table--row bt-product-item">
                    <div class="bt-table--col bt-product-remove">
                        <a href="#" data-id="<?php echo esc_attr($product_id); ?>" class="bt-product-remove-wishlist">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M9.64052 9.10965C9.67536 9.14449 9.703 9.18586 9.72186 9.23138C9.74071 9.2769 9.75042 9.32569 9.75042 9.37496C9.75042 9.42424 9.74071 9.47303 9.72186 9.51855C9.703 9.56407 9.67536 9.60544 9.64052 9.64028C9.60568 9.67512 9.56432 9.70276 9.51879 9.72161C9.47327 9.74047 9.42448 9.75017 9.37521 9.75017C9.32594 9.75017 9.27714 9.74047 9.23162 9.72161C9.1861 9.70276 9.14474 9.67512 9.1099 9.64028L6.00021 6.53012L2.89052 9.64028C2.82016 9.71064 2.72472 9.75017 2.62521 9.75017C2.5257 9.75017 2.43026 9.71064 2.3599 9.64028C2.28953 9.56991 2.25 9.47448 2.25 9.37496C2.25 9.27545 2.28953 9.18002 2.3599 9.10965L5.47005 5.99996L2.3599 2.89028C2.28953 2.81991 2.25 2.72448 2.25 2.62496C2.25 2.52545 2.28953 2.43002 2.3599 2.35965C2.43026 2.28929 2.5257 2.24976 2.62521 2.24976C2.72472 2.24976 2.82016 2.28929 2.89052 2.35965L6.00021 5.46981L9.1099 2.35965C9.18026 2.28929 9.2757 2.24976 9.37521 2.24976C9.47472 2.24976 9.57016 2.28929 9.64052 2.35965C9.71089 2.43002 9.75042 2.52545 9.75042 2.62496C9.75042 2.72448 9.71089 2.81991 9.64052 2.89028L6.53036 5.99996L9.64052 9.10965Z" fill="#C72929" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" fill="#C72929">
                                <path d="M493.815 70.629c-11.001-1.003-20.73 7.102-21.733 18.102l-2.65 29.069C424.473 47.194 346.429 0 256 0 158.719 0 72.988 55.522 30.43 138.854c-5.024 9.837-1.122 21.884 8.715 26.908 9.839 5.024 21.884 1.123 26.908-8.715C102.07 86.523 174.397 40 256 40c74.377 0 141.499 38.731 179.953 99.408l-28.517-20.367c-8.989-6.419-21.48-4.337-27.899 4.651-6.419 8.989-4.337 21.479 4.651 27.899l86.475 61.761c12.674 9.035 30.155.764 31.541-14.459l9.711-106.53c1.004-11.001-7.1-20.731-18.1-21.734zM472.855 346.238c-9.838-5.023-21.884-1.122-26.908 8.715C409.93 425.477 337.603 472 256 472c-74.377 0-141.499-38.731-179.953-99.408l28.517 20.367c8.989 6.419 21.479 4.337 27.899-4.651 6.419-8.989 4.337-21.479-4.651-27.899l-86.475-61.761c-12.519-8.944-30.141-.921-31.541 14.459L.085 419.637c-1.003 11 7.102 20.73 18.101 21.733 11.014 1.001 20.731-7.112 21.733-18.102l2.65-29.069C87.527 464.806 165.571 512 256 512c97.281 0 183.012-55.522 225.57-138.854 5.024-9.837 1.122-21.884-8.715-26.908z"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="bt-table--col bt-product-thumb">
                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="bt-thumb">
                            <?php echo wp_kses_post($product->get_image('medium')); ?>
                        </a>
                    </div>
                    <div class="bt-table--col bt-product-title">
                        <h3 class="bt-title">
                            <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                                <?php echo esc_html($product->get_name()); ?>
                            </a>
                        </h3>
                        <?php
                        if ($product_price) {
                            echo '<span class="bt-price-mobile">' . $product_price . '</span>';
                        }
                        ?>
                    </div>
                    <div class="bt-table--col bt-product-price<?php echo !$product->is_type('simple') ? ' bt-type-variable' : ''; ?>">
                        <?php
                        if ($product_price) {
                            echo '<span>' . $product_price . '</span>';
                        }
                        ?>
                    </div>
                    <div class="bt-table--col bt-product-stock">
                        <span><?php echo esc_html($stock_status); ?></span>
                    </div>
                    <div class="bt-table--col bt-product-add-to-cart">
                        <?php
                        $product = wc_get_product($product_id);
                        if ($product->is_type('simple')) {
                        ?>
                            <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr($product_id); ?>" data-quantity="1" class="bt-button product_type_simple add_to_cart_button ajax_add_to_cart bt-button-hover" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="" rel="nofollow"><?php echo esc_html__('Add to cart', 'utenzo') ?></a>
                        <?php
                        } else {
                        ?>
                            <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="bt-button bt-button-hover"><?php echo esc_html__('View Product', 'utenzo') ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
    <?php
            }
        }
        $output['items'] = ob_get_clean();
    } else {
        $output['count'] = 0;
        $output['items'] = '<div class="bt-no-results">' . __('No products found! ', 'utenzo') . '<a href="/shop/">' . __('Back to Shop', 'utenzo') . '</a></div>';
    }

    wp_send_json_success($output);

    die();
}

add_action('wp_ajax_utenzo_products_wishlist', 'utenzo_products_wishlist');
add_action('wp_ajax_nopriv_utenzo_products_wishlist', 'utenzo_products_wishlist');

function utenzo_products_quick_view()
{
    if (!isset($_POST['productid'])) {
        wp_send_json_error('Product ID is required');
        die();
    }

    $product_id = intval($_POST['productid']);
    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error('Product not found');
        die();
    }

    ob_start();
    ?>
    <div class="bt-quickview-title">
        <h2><?php esc_html_e('Quick View', 'utenzo') ?></h2>
    </div>
    <div class="bt-quickview-wrap woocommerce">
        <?php
        if ($product_id) {
            $args = array(
                'p' => $product_id,
                'post_type' => 'product'
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    wc_get_template_part('content', 'quick-view-product');
                }
                wp_reset_postdata();
            }
        }
        ?>
    </div>
<?php
    $output['product'] = ob_get_clean();

    wp_send_json_success($output);
}

add_action('wp_ajax_utenzo_products_quick_view', 'utenzo_products_quick_view');
add_action('wp_ajax_nopriv_utenzo_products_quick_view', 'utenzo_products_quick_view');

/* get price freeship */
function utenzo_get_free_shipping_minimum_amount()
{
    $shipping_zones = WC_Shipping_Zones::get_zones();

    foreach ($shipping_zones as $zone) {
        $shipping_methods = $zone['shipping_methods'];

        foreach ($shipping_methods as $method) {
            if ($method->id === 'free_shipping') {
                if (isset($method->min_amount)) {
                    return $method->min_amount;
                }
            }
        }
    }
    return 0;
}

function utenzo_get_free_shipping()
{
    $free_shipping_threshold = utenzo_get_free_shipping_minimum_amount();
    $cart_total = WC()->cart->get_cart_contents_total();
    $currency_symbol = get_woocommerce_currency_symbol();
    if ($cart_total < $free_shipping_threshold) {
        $amount_left = $free_shipping_threshold - $cart_total;
        $output['percentage'] = ($cart_total / $free_shipping_threshold) * 100;
        $output['message'] = sprintf(
            __('<p class="bt-buy-more">Buy <span>%1$s%2$.2f</span> more to get <span>Freeship</span></p>', 'utenzo'),
            $currency_symbol,
            $amount_left
        );
    } else {
        $output['message'] = __('<p class="bt-congratulation">Congratulations! You have free shipping!</p>', 'utenzo');
        $output['percentage'] = 100;
    }
?>
<?php
    wp_send_json_success($output);
}

add_action('wp_ajax_utenzo_get_free_shipping', 'utenzo_get_free_shipping');
add_action('wp_ajax_nopriv_utenzo_get_free_shipping', 'utenzo_get_free_shipping');

add_filter('woocommerce_cross_sells_total', 'bt_limit_cross_sells_display');
add_filter('woocommerce_cross_sells_columns', 'bt_set_cross_sells_columns');
add_filter('woocommerce_product_cross_sells_products_heading', 'bt_custom_cross_sells_title');

function bt_custom_cross_sells_title($title)
{
    $heading = get_field('heading_cross_sells', 'options');
    return !empty($heading) ? esc_html($heading) : esc_html__('You may be interested in…', 'utenzo');
}

function bt_limit_cross_sells_display($limit)
{
    return 4;  // Limit to 4 products
}

function bt_set_cross_sells_columns($columns)
{
    return 4;  // Set columns to 2
}

/* add button wishlist and compare */
function utenzo_display_button_wishlist_compare()
{
    global $product;
?>
    <div class="bt-product-icon-btn">
        <a class="bt-icon-btn bt-product-compare-btn" href="#" data-id="<?php echo esc_attr($product->get_id()); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10.5 14.2504C10.3011 14.2504 10.1103 14.3295 9.96968 14.4701C9.82903 14.6108 9.75001 14.8015 9.75001 15.0004V17.6901L7.09876 15.0379C6.7493 14.6907 6.47224 14.2776 6.28364 13.8224C6.09503 13.3673 5.99862 12.8793 6.00001 12.3867V8.90669C6.707 8.72415 7.32315 8.29002 7.73296 7.68568C8.14277 7.08135 8.3181 6.3483 8.2261 5.62394C8.13409 4.89958 7.78106 4.23364 7.23318 3.75095C6.6853 3.26826 5.98019 3.00195 5.25001 3.00195C4.51983 3.00195 3.81471 3.26826 3.26683 3.75095C2.71895 4.23364 2.36592 4.89958 2.27392 5.62394C2.18191 6.3483 2.35725 7.08135 2.76706 7.68568C3.17687 8.29002 3.79301 8.72415 4.50001 8.90669V12.3876C4.49826 13.0773 4.63324 13.7606 4.89715 14.3978C5.16105 15.035 5.54864 15.6136 6.03751 16.1001L8.6897 18.7504H6.00001C5.8011 18.7504 5.61033 18.8295 5.46968 18.9701C5.32903 19.1108 5.25001 19.3015 5.25001 19.5004C5.25001 19.6994 5.32903 19.8901 5.46968 20.0308C5.61033 20.1714 5.8011 20.2504 6.00001 20.2504H10.5C10.6989 20.2504 10.8897 20.1714 11.0303 20.0308C11.171 19.8901 11.25 19.6994 11.25 19.5004V15.0004C11.25 14.8015 11.171 14.6108 11.0303 14.4701C10.8897 14.3295 10.6989 14.2504 10.5 14.2504ZM3.75001 6.00044C3.75001 5.70377 3.83798 5.41376 4.0028 5.16709C4.16763 4.92041 4.40189 4.72815 4.67598 4.61462C4.95007 4.50109 5.25167 4.47138 5.54264 4.52926C5.83361 4.58714 6.10089 4.73 6.31067 4.93978C6.52045 5.14956 6.66331 5.41683 6.72119 5.70781C6.77906 5.99878 6.74936 6.30038 6.63583 6.57447C6.5223 6.84855 6.33004 7.08282 6.08336 7.24764C5.83669 7.41247 5.54668 7.50044 5.25001 7.50044C4.85218 7.50044 4.47065 7.3424 4.18935 7.0611C3.90804 6.7798 3.75001 6.39827 3.75001 6.00044ZM19.5 15.0942V11.6142C19.5018 10.9245 19.3668 10.2413 19.1029 9.60404C18.839 8.96681 18.4514 8.38822 17.9625 7.90169L15.3103 5.25044H18C18.1989 5.25044 18.3897 5.17142 18.5303 5.03077C18.671 4.89012 18.75 4.69935 18.75 4.50044C18.75 4.30153 18.671 4.11076 18.5303 3.97011C18.3897 3.82946 18.1989 3.75044 18 3.75044H13.5C13.3011 3.75044 13.1103 3.82946 12.9697 3.97011C12.829 4.11076 12.75 4.30153 12.75 4.50044V9.00044C12.75 9.19935 12.829 9.39012 12.9697 9.53077C13.1103 9.67142 13.3011 9.75044 13.5 9.75044C13.6989 9.75044 13.8897 9.67142 14.0303 9.53077C14.171 9.39012 14.25 9.19935 14.25 9.00044V6.31075L16.9013 8.96294C17.2507 9.31018 17.5278 9.72333 17.7164 10.1784C17.905 10.6335 18.0014 11.1216 18 11.6142V15.0942C17.293 15.2767 16.6769 15.7109 16.2671 16.3152C15.8572 16.9195 15.6819 17.6526 15.7739 18.3769C15.8659 19.1013 16.219 19.7672 16.7668 20.2499C17.3147 20.7326 18.0198 20.9989 18.75 20.9989C19.4802 20.9989 20.1853 20.7326 20.7332 20.2499C21.2811 19.7672 21.6341 19.1013 21.7261 18.3769C21.8181 17.6526 21.6428 16.9195 21.233 16.3152C20.8232 15.7109 20.207 15.2767 19.5 15.0942ZM18.75 19.5004C18.4533 19.5004 18.1633 19.4125 17.9167 19.2476C17.67 19.0828 17.4777 18.8486 17.3642 18.5745C17.2507 18.3004 17.221 17.9988 17.2788 17.7078C17.3367 17.4168 17.4796 17.1496 17.6893 16.9398C17.8991 16.73 18.1664 16.5871 18.4574 16.5293C18.7483 16.4714 19.0499 16.5011 19.324 16.6146C19.5981 16.7282 19.8324 16.9204 19.9972 17.1671C20.162 17.4138 20.25 17.7038 20.25 18.0004C20.25 18.3983 20.092 18.7798 19.8107 19.0611C19.5294 19.3424 19.1478 19.5004 18.75 19.5004Z" />
            </svg>
        </a>
        <a class="bt-icon-btn bt-product-wishlist-btn" href="#" data-id="<?php echo esc_attr($product->get_id()); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M16.6875 3C14.7516 3 13.0566 3.8325 12 5.23969C10.9434 3.8325 9.24844 3 7.3125 3C5.77146 3.00174 4.29404 3.61468 3.20436 4.70436C2.11468 5.79404 1.50174 7.27146 1.5 8.8125C1.5 15.375 11.2303 20.6869 11.6447 20.9062C11.7539 20.965 11.876 20.9958 12 20.9958C12.124 20.9958 12.2461 20.965 12.3553 20.9062C12.7697 20.6869 22.5 15.375 22.5 8.8125C22.4983 7.27146 21.8853 5.79404 20.7956 4.70436C19.706 3.61468 18.2285 3.00174 16.6875 3ZM12 19.3875C10.2881 18.39 3 13.8459 3 8.8125C3.00149 7.66921 3.45632 6.57317 4.26475 5.76475C5.07317 4.95632 6.16921 4.50149 7.3125 4.5C9.13594 4.5 10.6669 5.47125 11.3062 7.03125C11.3628 7.16881 11.4589 7.28646 11.5824 7.36926C11.7059 7.45207 11.8513 7.49627 12 7.49627C12.1487 7.49627 12.2941 7.45207 12.4176 7.36926C12.5411 7.28646 12.6372 7.16881 12.6937 7.03125C13.3331 5.46844 14.8641 4.5 16.6875 4.5C17.8308 4.50149 18.9268 4.95632 19.7353 5.76475C20.5437 6.57317 20.9985 7.66921 21 8.8125C21 13.8384 13.71 18.3891 12 19.3875Z" />
            </svg>
        </a>
    </div>
    <?php
}

add_action('woocommerce_after_add_to_cart_button', 'utenzo_display_button_wishlist_compare');

add_filter('woocommerce_product_single_add_to_cart_text', 'utenzo_custom_add_to_cart_text', 10, 2);

function utenzo_custom_add_to_cart_text($text, $product)
{
    if ($product->is_type('simple')) {
        $price = $product->get_price();
        $formatted_price = wc_price($price);
        $formatted_price = strip_tags($formatted_price);
        $text = sprintf(__('Add to cart - %s', 'utenzo'), $formatted_price);
    }
    return $text;
}

// WooCommerce custom field
add_action('woocommerce_product_options_advanced', 'utenzo_woocommerce_custom_field');

function utenzo_woocommerce_custom_field()
{
    global $post, $product_object;
    // Add layout selector
    $layout_options = array(
        'layout-product-1' => __('Layout 1', 'utenzo'),
        'layout-product-2' => __('Layout 2', 'utenzo')
    );

    // Save layout value or use default layout-1
    $layout_value = get_post_meta($post->ID, '_layout_product', true);
    if (empty($layout_value)) {
        $layout_value = 'layout-product-1';
    }

    woocommerce_wp_select(array(
        'id' => '_layout_product',
        'label' => __('Product Layout', 'utenzo'),
        'description' => '',
        'options' => $layout_options,
        'value' => $layout_value
    ));
    // add product sold
    woocommerce_wp_text_input(array(
        'id' => '_product_sold',
        'label' => __('Product Sold', 'utenzo'),
        'description' => '',
        'type' => 'number',
        'custom_attributes' => array(
            'min' => 0
        )
    ));
    // Add product label selector
    $label_options = array(
        '' => __('Select Label', 'utenzo'),
        'best-seller' => __('Best Seller', 'utenzo'),
        'featured' => __('Featured', 'utenzo'),
        'new-arrival' => __('New Arrival', 'utenzo'),
        'on-sale' => __('On Sale', 'utenzo'),
        'trending' => __('Trending', 'utenzo'),
        'hot-deal' => __('Hot Deal', 'utenzo'),
        'pre-order' => __('Pre-Order', 'utenzo'),
        'exclusive' => __('Exclusive', 'utenzo'),
        'top-rated' => __('Top Rated', 'utenzo')
    );

    $label_value = get_post_meta($post->ID, '_label', true);

    woocommerce_wp_select(array(
        'id' => '_label',
        'label' => __('Product Label', 'utenzo'),
        'description' => '',
        'options' => $label_options,
        'value' => $label_value
    ));

    // Only show checkbox for simple and variable products
    if ($product_object && ($product_object->is_type('simple') || $product_object->is_type('variable'))) {
        woocommerce_wp_text_input(array(
            'id' => '_product_start_datetime',
            'label' => __('Date Start Sale', 'utenzo'),
            'description' => __("Set the date and time when this product's sale price will expire.", 'utenzo'),
            'type' => 'datetime-local',
            'custom_attributes' => array(
                'step' => '60',  // 1 minute steps
                'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}'  // YYYY-MM-DDThh:mm format
            )
        ));
        woocommerce_wp_text_input(array(
            'id' => '_product_datetime',
            'label' => __('Product Date & Time Sale', 'utenzo'),
            'description' => __("Set the date and time when this product's sale price will expire.", 'utenzo'),
            'type' => 'datetime-local',
            'custom_attributes' => array(
                'step' => '60',  // 1 minute steps
                'pattern' => '[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}'  // YYYY-MM-DDThh:mm format
            )
        ));
        woocommerce_wp_checkbox(array(
            'id' => '_disable_sale_price',
            'label' => __('Disable Sale Price', 'utenzo'),
            'description' => __('Check this box to automatically remove the sale price when the sale period ends. The product will return to its regular price.', 'utenzo')
        ));
        woocommerce_wp_checkbox(array(
            'id' => '_enable_percentage_sold',
            'label' => __('Enable Percentage Sold', 'utenzo'),
            'description' => __('Check this box to display the percentage of products sold.', 'utenzo')
        ));
        woocommerce_wp_text_input(array(
            'id' => '_product_sold_sale',
            'label' => __('Product Sold Sale', 'utenzo'),
            'description' => '',
            'type' => 'number',
            'custom_attributes' => array(
                'min' => 0
            )
        ));
        woocommerce_wp_text_input(array(
            'id' => '_product_stock_sale',
            'label' => __('Product Stock Sale', 'utenzo'),
            'description' => '',
            'type' => 'number',
            'custom_attributes' => array(
                'min' => 0
            )
        ));
    }
}

add_action('woocommerce_process_product_meta', 'utenzo_woocommerce_custom_field_save');

function utenzo_woocommerce_custom_field_save($post_id)
{
    if (isset($_POST['_label'])) {
        $label = sanitize_text_field($_POST['_label']);
        update_post_meta($post_id, '_label', $label);
    }
    if (isset($_POST['_layout_product'])) {
        $layout = sanitize_text_field($_POST['_layout_product']);
        update_post_meta($post_id, '_layout_product', $layout);
    }
    if (isset($_POST['_product_sold'])) {
        $product_sold = intval($_POST['_product_sold']);
        update_post_meta($post_id, '_product_sold', $product_sold);
    }
    if (isset($_POST['_product_datetime'])) {
        $datetime = sanitize_text_field($_POST['_product_datetime']);
        update_post_meta($post_id, '_product_datetime', $datetime);
        if (empty(get_post_meta($post_id, '_product_start_datetime', true))) {
            update_post_meta($post_id, '_product_start_datetime', current_time('Y-m-d\TH:i'));
        }
    }
    $disable_sale_price = isset($_POST['_disable_sale_price']) ? 'yes' : 'no';
    update_post_meta($post_id, '_disable_sale_price', $disable_sale_price);
    $enable_percentage_sold = isset($_POST['_enable_percentage_sold']) ? 'yes' : 'no';
    update_post_meta($post_id, '_enable_percentage_sold', $enable_percentage_sold);
    if (isset($_POST['_product_sold_sale'])) {
        $product_sold_sale = intval($_POST['_product_sold_sale']);
        update_post_meta($post_id, '_product_sold_sale', $product_sold_sale);
    }
    if (isset($_POST['_product_stock_sale'])) {
        $product_stock_sale = intval($_POST['_product_stock_sale']);
        update_post_meta($post_id, '_product_stock_sale', $product_stock_sale);
    }
}

function utenzo_convert_title_to_slug($title)
{
    $slug = strtolower($title);
    $slug = str_replace(' ', '-', $slug);
    $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');

    return $slug;
}

add_action('utenzo_woocommerce_shop_loop_item_label', 'utenzo_woocommerce_product_label', 10);

function utenzo_woocommerce_product_label()
{
    global $product;
    $label = get_post_meta($product->get_id(), '_label', true);

    if (!empty($label)) {
        echo '<div class="woocommerce-product-label ' . esc_attr(utenzo_convert_title_to_slug($label)) . '">' . esc_html($label) . '</div>';
    }
}
// hook button buy now after add to cart
add_action('woocommerce_after_add_to_cart_button', 'utenzo_display_button_buy_now');
function utenzo_display_button_buy_now()
{
    global $product;
    if ($product->is_type('simple')) {
        if ($product->is_in_stock() && $product->is_purchasable()) {
            echo '<div class="bt-button-buy-now">';
            echo '<a class="button" data-id="' . get_the_ID() . '">' . esc_html__('Buy it now ', 'utenzo') . '</a>';
            echo '</div>';
        }
    } else if ($product->is_type('variable')) {
        if ($product->is_type('variable')) {
            $variations = $product->get_available_variations();
            if (!empty($variations)) {
                echo '<div class="bt-button-buy-now">';
                echo '<a class="button ' . ($product->is_type('variable') ? 'disabled' : '') . '" data-id="' . get_the_ID() . '">' . esc_html__('Buy it now ', 'utenzo') . '</a>';
                echo '</div>';
            }
        }
    } else if ($product->is_type('grouped')) {
        if ($product->is_type('grouped')) {
            $children = $product->get_children();
            if (!empty($children)) {
                echo '<div class="bt-button-buy-now">';
                echo '<a class="button disabled" data-id="' . get_the_ID() . '">' . esc_html__('Buy it now ', 'utenzo') . '</a>';
                echo '</div>';
            }
        }
    }
}

/* ajax by now product */
add_action('wp_ajax_utenzo_products_buy_now', 'utenzo_products_buy_now');
add_action('wp_ajax_nopriv_utenzo_products_buy_now', 'utenzo_products_buy_now');

function utenzo_products_buy_now()
{
    if (isset($_POST['product_id_grouped']) && !empty($_POST['product_id_grouped'])) {
        $product_data = explode(',', $_POST['product_id_grouped']);
        // Loop through product data and add to cart
        foreach ($product_data as $item) {
            $item_data = explode(':', $item);
            $product_id = intval($item_data[0]);
            $quantity = isset($item_data[1]) ? intval($item_data[1]) : 1;

            WC()->cart->add_to_cart($product_id, $quantity);
        }
        $redirect_url = wc_get_checkout_url();
        wp_send_json_success(array('redirect_url' => $redirect_url));
        wp_die();
    } else if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $variation = $variation_id > 0 ? array() : null;

        $product = wc_get_product($product_id);

        if ($product) {
            if ($variation_id > 0 && $product->is_type('variable')) {
                $variation_product = wc_get_product($variation_id);
                if ($variation_product) {
                    $attributes = $variation_product->get_attributes();
                    foreach ($attributes as $attribute_name => $attribute_value) {
                        $variation[$attribute_name] = $attribute_value;
                    }
                }
            }

            WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);

            $redirect_url = wc_get_checkout_url();
            wp_send_json_success(array('redirect_url' => $redirect_url));
            wp_die();
        }
    }
}

/* Product share */
if (!function_exists('utenzo_product_share_render')) {
    function utenzo_product_share_render()
    {
        $social_item = array();
        $social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-linkedin" data-toggle="tooltip" title="' . esc_attr__('Linkedin', 'utenzo') . '" href="https://www.linkedin.com/shareArticle?url=' . get_the_permalink() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                            <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/>
                          </svg>
                        </a>
                      </li>';
        $social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-facebook" data-toggle="tooltip" title="' . esc_attr__('Facebook', 'utenzo') . '" href="https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512">
                            <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                          </svg>
                        </a>
                      </li>';
        $social_item[] = '<li>
                      <a target="_blank" data-btIcon="fa fa-twitter" data-toggle="tooltip" title="' . esc_attr__('Twitter', 'utenzo') . '" href="https://twitter.com/share?url=' . get_the_permalink() . '">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                          <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                        </svg>
                      </a>
                    </li>';
        $social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-pinterest" data-toggle="tooltip" title="' . esc_attr__('Pinterest', 'utenzo') . '" href="https://pinterest.com/pin/create/button/?url=' . get_the_permalink() . '&media=' . wp_get_attachment_url(get_post_thumbnail_id()) . '&description=' . get_the_title() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="16" viewBox="0 0 13 16" fill="none">
                            <path d="M6.53967 0C3.2506 0 0 2.19271 0 5.74145C0 7.99827 1.26947 9.28056 2.03884 9.28056C2.3562 9.28056 2.53893 8.39578 2.53893 8.14574C2.53893 7.8476 1.77918 7.21287 1.77918 5.97226C1.77918 3.39486 3.74108 1.5676 6.28001 1.5676C8.4631 1.5676 10.0788 2.80821 10.0788 5.08748C10.0788 6.78972 9.39597 9.98261 7.18402 9.98261C6.3858 9.98261 5.70298 9.40558 5.70298 8.57851C5.70298 7.36675 6.54929 6.19345 6.54929 4.94322C6.54929 2.82103 3.53912 3.20572 3.53912 5.7703C3.53912 6.30886 3.60644 6.90512 3.84686 7.3956C3.40448 9.2998 2.50046 12.1369 2.50046 14.0988C2.50046 14.7046 2.58702 15.3009 2.64472 15.9068C2.75371 16.0286 2.69922 16.0158 2.86591 15.9549C4.4816 13.7429 4.42389 13.3102 5.1548 10.4154C5.5491 11.1655 6.56852 11.5694 7.37636 11.5694C10.7808 11.5694 12.31 8.25152 12.31 5.26059C12.31 2.07731 9.55946 0 6.53967 0Z" fill="#212121"/>
                          </svg>
                        </a>
                      </li>';

        ob_start();
        if (is_singular('product')) {
    ?>
            <div class="bt-product-share">
                <?php if (!empty($social_item)) {
                    echo '<div class="button-share"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
<path d="M13.7526 12.5005C13.3357 12.5004 12.923 12.5839 12.5389 12.7462C12.1549 12.9084 11.8073 13.1461 11.5167 13.4451L7.91513 11.1302C8.19843 10.4038 8.19843 9.59734 7.91513 8.87086L11.5167 6.55602C12.0577 7.11017 12.7851 7.44372 13.5581 7.49198C14.331 7.54024 15.0943 7.29978 15.7 6.81723C16.3057 6.33467 16.7107 5.64439 16.8364 4.88023C16.9621 4.11606 16.7995 3.33245 16.3803 2.68131C15.9611 2.03017 15.315 1.5579 14.5673 1.35606C13.8196 1.15421 13.0236 1.23718 12.3337 1.58887C11.6437 1.94057 11.1089 2.53592 10.8329 3.25953C10.557 3.98313 10.5596 4.78342 10.8401 5.50524L7.23857 7.82008C6.80487 7.37483 6.24824 7.06902 5.63985 6.94175C5.03145 6.81449 4.39892 6.87154 3.82312 7.10563C3.24731 7.33971 2.7544 7.74019 2.40738 8.25586C2.06036 8.77154 1.875 9.37898 1.875 10.0005C1.875 10.6221 2.06036 11.2296 2.40738 11.7452C2.7544 12.2609 3.24731 12.6614 3.82312 12.8955C4.39892 13.1296 5.03145 13.1866 5.63985 13.0593C6.24824 12.9321 6.80487 12.6263 7.23857 12.181L10.8401 14.4959C10.5989 15.1181 10.5632 15.8013 10.7382 16.4454C10.9133 17.0894 11.29 17.6605 11.8132 18.075C12.3363 18.4894 12.9784 18.7255 13.6454 18.7487C14.3124 18.7718 14.9694 18.5808 15.52 18.2036C16.0706 17.8264 16.4859 17.2828 16.7053 16.6524C16.9246 16.0221 16.9363 15.338 16.7387 14.7005C16.5412 14.063 16.1447 13.5055 15.6074 13.1096C15.07 12.7137 14.4201 12.5003 13.7526 12.5005ZM13.7526 2.50055C14.1235 2.50055 14.486 2.61052 14.7943 2.81654C15.1027 3.02257 15.343 3.31541 15.4849 3.65802C15.6268 4.00063 15.6639 4.37763 15.5916 4.74134C15.5193 5.10506 15.3407 5.43915 15.0785 5.70137C14.8162 5.9636 14.4821 6.14217 14.1184 6.21452C13.7547 6.28687 13.3777 6.24974 13.0351 6.10782C12.6925 5.96591 12.3997 5.72559 12.1936 5.41724C11.9876 5.1089 11.8776 4.74639 11.8776 4.37555C11.8776 3.87827 12.0752 3.40135 12.4268 3.04972C12.7784 2.69809 13.2553 2.50055 13.7526 2.50055ZM5.00263 11.8755C4.63179 11.8755 4.26928 11.7656 3.96093 11.5596C3.65259 11.3535 3.41227 11.0607 3.27036 10.7181C3.12844 10.3755 3.09131 9.99847 3.16366 9.63475C3.236 9.27104 3.41458 8.93695 3.6768 8.67472C3.93903 8.4125 4.27312 8.23392 4.63683 8.16158C5.00055 8.08923 5.37755 8.12636 5.72016 8.26827C6.06277 8.41019 6.35561 8.65051 6.56163 8.95885C6.76766 9.2672 6.87763 9.62971 6.87763 10.0005C6.87763 10.4978 6.68008 10.9747 6.32845 11.3264C5.97682 11.678 5.49991 11.8755 5.00263 11.8755ZM13.7526 17.5005C13.3818 17.5005 13.0193 17.3906 12.7109 17.1846C12.4026 16.9785 12.1623 16.6857 12.0204 16.3431C11.8784 16.0005 11.8413 15.6235 11.9137 15.2598C11.986 14.896 12.1646 14.5619 12.4268 14.2997C12.689 14.0375 13.0231 13.8589 13.3868 13.7866C13.7505 13.7142 14.1275 13.7514 14.4702 13.8933C14.8128 14.0352 15.1056 14.2755 15.3116 14.5839C15.5177 14.8922 15.6276 15.2547 15.6276 15.6255C15.6276 16.1228 15.4301 16.5997 15.0785 16.9514C14.7268 17.303 14.2499 17.5005 13.7526 17.5005Z" fill="#212121"/>
</svg>' . esc_html__('Share', 'utenzo') . '</div><ul>' . implode(' ', $social_item) . '</ul>';
                } ?>
            </div>
        <?php
        }

        return ob_get_clean();
    }
}

add_action('wp_ajax_utenzo_remove_section', 'utenzo_remove_section');
add_action('wp_ajax_nopriv_utenzo_remove_section', 'utenzo_remove_section');

function utenzo_remove_section()
{
    session_start();
    if (isset($_SESSION['coupon'])) {
        unset($_SESSION['coupon']);
    }
}

// search live product header
function utenzo_search_live()
{
    $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
    $category_slug = isset($_POST['category_slug']) ? sanitize_text_field($_POST['category_slug']) : '';

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        's' => $search_term,
        'tax_query' => array()
    );

    if (!empty($category_slug)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $category_slug
        );
    }

    $query = new WP_Query($args);
    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());
            if (!$product) {
                continue;
            }
            $product_price = $product->get_price_html();
        ?>
            <div class="bt-product-item">
                <div class="bt-product-thumb">
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="bt-thumb">
                        <?php echo wp_kses_post($product->get_image('medium')); ?>
                    </a>
                    <div class="bt-product-title">
                        <h3 class="bt-title">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php echo esc_html($product->get_name()); ?>
                            </a>
                        </h3>
                        <?php if ($product_price) : ?>
                            <span><?php echo wp_kses_post($product_price); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="bt-product-add-to-cart">
                    <?php if ($product->is_type('simple')) { ?>
                        <a href="?add-to-cart=<?php echo esc_attr(get_the_ID()); ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr(get_the_ID()); ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart bt-btn-add-to-cart" data-product_id="<?php echo esc_attr(get_the_ID()); ?>" data-product_sku="" rel="nofollow"><?php echo esc_html__('Add to cart', 'utenzo') ?></a>
                    <?php } else { ?>
                        <a href="<?php echo esc_url(get_permalink(get_the_ID())); ?>" class="bt-button bt-button-hover bt-btn-view-product"><?php echo esc_html__('View Product', 'utenzo') ?></a>
                    <?php } ?>
                </div>
            </div>
        <?php
        }
        wp_reset_postdata();
        $output['items'] = ob_get_clean();
    } else {
        $output['items'] = '<div class="bt-no-results">' . esc_html__('No products found!', 'utenzo') . '</div>';
    }

    wp_send_json_success($output);

    die();
}

add_action('wp_ajax_utenzo_search_live', 'utenzo_search_live');
add_action('wp_ajax_nopriv_utenzo_search_live', 'utenzo_search_live');

/* query id elementor Popular Products */
function bt_custom_popular_products_query($query)
{
    if (!isset($query)) {
        return;
    }

    $query->set('post_type', 'product');

    // Get popular products selection from ACF
    $top_popular_products = get_field('top_popular_product', 'options');

    // Check if specific products are selected
    if (!empty($top_popular_products) && is_array($top_popular_products)) {
        // Get IDs of selected products
        $product_ids = array();
        foreach ($top_popular_products as $product) {
            if (is_object($product) && isset($product->ID)) {
                $product_ids[] = $product->ID;
            } elseif (is_numeric($product)) {
                $product_ids[] = $product;
            }
        }

        if (!empty($product_ids)) {
            // Use the selected products with specific ordering
            $query->set('post__in', $product_ids);
            $query->set('orderby', 'post__in'); // Maintain the order from ACF
            // Don't use meta_key sorting when we have specific products
        } else {
            // Fall back to popularity sorting if no valid IDs
            $query->set('meta_key', 'total_sales');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'desc');
        }
    } else {
        // Default sorting by popularity when no products are selected
        $query->set('meta_key', 'total_sales');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'desc');
    }
}

add_action('elementor/query/bt_popular_products', 'bt_custom_popular_products_query');
/* query id elementor Featured Products */
function bt_custom_featured_products_query($query)
{
    if (!isset($query)) {
        return;
    }

    $query->set('post_type', 'product');

    // Get popular products selection from ACF
    $featured_products = get_field('featured_products', 'options');

    // Check if specific products are selected
    if (!empty($featured_products) && is_array($featured_products)) {
        // Get IDs of selected products
        $product_ids = array();
        foreach ($featured_products as $product) {
            if (is_object($product) && isset($product->ID)) {
                $product_ids[] = $product->ID;
            } elseif (is_numeric($product)) {
                $product_ids[] = $product;
            }
        }

        if (!empty($product_ids)) {
            // Use the selected products with specific ordering
            $query->set('post__in', $product_ids);
            $query->set('orderby', 'post__in'); // Maintain the order from ACF
            // Don't use meta_key sorting when we have specific products
        } else {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                ),
            ));
        }
    } else {
        $query->set('tax_query', array(
            array(
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
            ),
        ));
    }
}

add_action('elementor/query/bt_featured_products', 'bt_custom_featured_products_query');

// Add multiple to cart ajax widget hotspot
function utenzo_add_multiple_to_cart()
{
    if (!isset($_POST['product_ids']) || empty($_POST['product_ids'])) {
        wp_send_json_error(__('No products selected', 'utenzo'));
        return;
    }

    $product_ids = $_POST['product_ids'];
    $added_count = 0;
    $cart_count = 0;

    foreach ($product_ids as $product_id) {
        $product_id = absint($product_id);
        if ($product_id > 0) {
            $added = WC()->cart->add_to_cart($product_id);
            if ($added) {
                $added_count++;
            }
        }
    }

    if ($added_count > 0) {
        $cart_count = WC()->cart->get_cart_contents_count();
        wp_send_json_success(array(
            'message' => sprintf(__('%d products added to cart', 'utenzo'), $added_count),
            'cart_count' => $cart_count
        ));
    } else {
        wp_send_json_error(__('Failed to add products to cart', 'utenzo'));
    }
}

add_action('wp_ajax_utenzo_add_multiple_to_cart', 'utenzo_add_multiple_to_cart');
add_action('wp_ajax_nopriv_utenzo_add_multiple_to_cart', 'utenzo_add_multiple_to_cart');

// AJAX handler for loading recently viewed products
function utenzo_load_recently_viewed_products()
{
    if (!isset($_POST['recently_viewed']) || !is_array($_POST['recently_viewed'])) {
        wp_send_json_error();
        return;
    }

    $recently_viewed_ids = array_map('intval', $_POST['recently_viewed']);
    $recently_viewed_products = array();

    // Skip first ID and get next 4 products
    $count = 0;
    foreach ($recently_viewed_ids as $product_id) {
        if ($count > 0 && $count <= 4) {  // Skip first item and limit to 4 items
            $product = wc_get_product($product_id);
            if ($product && $product->is_visible()) {
                $recently_viewed_products[] = $product;
            }
        }
        $count++;
    }

    ob_start();
    if (!empty($recently_viewed_products)) {
        echo '<div class="woocommerce-loop-products products columns-4">';
        foreach ($recently_viewed_products as $recent_product) {
            $post_object = get_post($recent_product->get_id());
            setup_postdata($GLOBALS['post'] = &$post_object);
            wc_get_template_part('content', 'product');
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="no-products">' . esc_html__('No recently viewed products.', 'utenzo') . '</p>';
    }
    $content = ob_get_clean();

    wp_send_json_success($content);
}

add_action('wp_ajax_load_recently_viewed', 'utenzo_load_recently_viewed_products');
add_action('wp_ajax_nopriv_load_recently_viewed', 'utenzo_load_recently_viewed_products');

// Hook the function to run after checkout is completed
add_action('woocommerce_thankyou', 'utenzo_after_checkout_product', 10, 1);
// Add a flag to prevent duplicate processing
add_action('woocommerce_checkout_order_processed', 'mark_order_as_processed', 10, 1);

function mark_order_as_processed($order_id)
{
    update_post_meta($order_id, '_utenzo_order_processed', 'no');
}
function utenzo_after_checkout_product($order_id)
{
    // Check if this order has already been processed
    if (get_post_meta($order_id, '_utenzo_order_processed', true) === 'yes') {
        return;
    }
    $order = wc_get_order($order_id);
    $items = $order->get_items();
    $order_time = $order->get_date_created();
    if (!$order_time) {
        return;
    }
    $order_time->setTimezone(new DateTimeZone(wp_timezone_string()));

    foreach ($items as $item) {
        $item_product_id = $item->get_product_id();
        $product = wc_get_product($item_product_id);
        $sale_date_start = get_post_meta($item_product_id, '_product_start_datetime', true);
        $sale_date = get_post_meta($item_product_id, '_product_datetime', true);
        $sale_date_start = new DateTime($sale_date_start, new DateTimeZone(wp_timezone_string()));
        $sale_date = new DateTime($sale_date, new DateTimeZone(wp_timezone_string()));
        if (empty($sale_date_start) || empty($sale_date)) {
            return;
        }
        if ($order_time < $sale_date_start || $order_time > $sale_date) {
            continue;
        }
        $enable_percentage_sold = get_post_meta($item_product_id, '_enable_percentage_sold', true);

        if ($enable_percentage_sold === 'yes') {
            $quantity = $item->get_quantity();
            $current_sold = absint(get_post_meta($item_product_id, '_product_sold_sale', true));
            $new_sold = $current_sold + $quantity;
            update_post_meta($item_product_id, '_product_sold_sale', $new_sold);
            $product_sold_sale = get_post_meta($item_product_id, '_product_sold_sale', true);
            $product_stock_sale = get_post_meta($item_product_id, '_product_stock_sale', true);
            $disable_sale = get_post_meta($item_product_id, '_disable_sale_price', true);
            if ($product_sold_sale >= $product_stock_sale) {
                if ($disable_sale === 'yes') {
                    // Handle variable products
                    if ($product->is_type('variable')) {
                        // Remove sale price for each variation
                        foreach ($product->get_available_variations() as $variation) {
                            $variation_id = $variation['variation_id'];
                            $variation_obj = wc_get_product($variation_id);
                            update_post_meta($variation_id, '_sale_price', '');
                            $variation_obj->set_sale_price('');
                            $variation_obj->save();
                        }
                    } else {
                        // Handle simple, grouped, external products
                        update_post_meta($product->get_id(), '_sale_price', '');
                        $product->set_sale_price('');
                    }
                }

                // Update common metadata for all product types
                update_post_meta($item_product_id, '_product_datetime', '');
                update_post_meta($item_product_id, '_disable_sale_price', 'no');
                update_post_meta($item_product_id, '_enable_percentage_sold', 'no');
                update_post_meta($item_product_id, '_product_start_datetime', '');
                update_post_meta($item_product_id, '_product_sold_sale', '');
                update_post_meta($item_product_id, '_product_stock_sale', '');
                $product->save();
            }
        }
    }
    // Mark this order as processed
    update_post_meta($order_id, '_utenzo_order_processed', 'yes');
}
/* hook check sale date countdown product */
function utenzo_check_sale_date_countdown()
{
    global $product;
    // Get sale end date from product meta
    $sale_date = get_post_meta($product->get_id(), '_product_datetime', true);
    $disable_sale = get_post_meta($product->get_id(), '_disable_sale_price', true);

    if (!empty($sale_date)) {
        $sale_timestamp = strtotime($sale_date);
        $current_timestamp = current_time('timestamp');
        // If sale date has passed and disable sale is checked
        if ($current_timestamp > $sale_timestamp) {
            if ($disable_sale === 'yes') {
                // Handle variable products
                if ($product->is_type('variable')) {
                    // Remove sale price for each variation
                    foreach ($product->get_available_variations() as $variation) {
                        $variation_id = $variation['variation_id'];
                        $variation_obj = wc_get_product($variation_id);
                        update_post_meta($variation_id, '_sale_price', '');
                        $variation_obj->set_sale_price('');
                        $variation_obj->save();
                    }
                } else {
                    // Handle simple, grouped, external products
                    update_post_meta($product->get_id(), '_sale_price', '');
                    $product->set_sale_price('');
                }
            }

            // Update common metadata for all product types
            update_post_meta($product->get_id(), '_product_datetime', '');
            update_post_meta($product->get_id(), '_disable_sale_price', 'no');
            update_post_meta($product->get_id(), '_enable_percentage_sold', 'no');
            update_post_meta($product->get_id(), '_product_start_datetime', '');
            update_post_meta($product->get_id(), '_product_sold_sale', '');
            update_post_meta($product->get_id(), '_product_stock_sale', '');
            $product->save();
        }
    }
}

add_action('woocommerce_before_single_product', 'utenzo_check_sale_date_countdown', 25);

// Add countdown to single product
add_action('utenzo_woocommerce_template_single_countdown', 'utenzo_woocommerce_single_product_countdown', 40);

function utenzo_woocommerce_single_product_countdown()
{
    global $product;
    $time = get_post_meta($product->get_id(), '_product_datetime', true);
    $stock_status = $product->get_stock_status();

    if ($time && $stock_status != 'outofstock') {
        $time = strtotime($time);
        $time = date('Y-m-d H:i:s', $time);
        ?>
        <div class="bt-countdown-product-sale">
            <span class="bt-heading"><?php echo esc_html__('Hurry Up! Offer ends in:', 'utenzo'); ?></span>
            <div class="bt-countdown bt-countdown-product-js"
                data-idproduct="<?php echo esc_attr($product->get_id()); ?>"
                data-time="<?php echo esc_attr($time); ?>">

                <div class="bt-countdown--item">
                    <span class="bt-countdown--digits bt-countdown-days">--</span>
                    <span class="bt-countdown--label"><?php _e('Days', 'utenzo'); ?></span>
                </div>

                <div class="bt-delimiter">:</div>

                <div class="bt-countdown--item">
                    <span class="bt-countdown--digits bt-countdown-hours">--</span>
                    <span class="bt-countdown--label"><?php _e('Hours', 'utenzo'); ?></span>
                </div>

                <div class="bt-delimiter">:</div>

                <div class="bt-countdown--item">
                    <span class="bt-countdown--digits bt-countdown-mins">--</span>
                    <span class="bt-countdown--label"><?php _e('Mins', 'utenzo'); ?></span>
                </div>

                <div class="bt-delimiter">:</div>

                <div class="bt-countdown--item">
                    <span class="bt-countdown--digits bt-countdown-secs">--</span>
                    <span class="bt-countdown--label"><?php _e('Secs', 'utenzo'); ?></span>
                </div>
            </div>
        </div>
        <?php

        $enable_percentage_sold = get_post_meta($product->get_id(), '_enable_percentage_sold', true);
        if ($enable_percentage_sold === 'yes') {
            $product_sold_sale = get_post_meta($product->get_id(), '_product_sold_sale', true);
            $product_stock_sale = get_post_meta($product->get_id(), '_product_stock_sale', true);
            $percentage = min(100, max(0, ($product_sold_sale / $product_stock_sale) * 100));
            $remaining_products = $product_stock_sale - $product_sold_sale;
        ?>
            <div class="bt-product-percentage-sold">
                <span class="bt-heading"><?php echo esc_html__('Sold It:', 'utenzo'); ?></span>
                <div class="bt-product-stock">
                    <div class="bt-progress">
                        <div class="bt-progress-bar-sold" data-width="<?php echo esc_attr($percentage); ?>"></div>
                    </div>
                    <span class="bt-quantity_sold">
                        <?php printf(esc_html__('%d%% Sold', 'utenzo'), $percentage); ?> -
                    </span>
                    <span class="bt-stock-text">
                        <?php printf(esc_html__('Only %d item(s) left in stock!', 'utenzo'), $remaining_products); ?>
                    </span>
                </div>
            </div>

    <?php
        }
    }
}

// Add more information single product
add_action('utenzo_woocommerce_template_single_more_information', 'utenzo_woocommerce_single_product_more_information', 40);
function utenzo_woocommerce_single_product_more_information()
{
    // Check if ACF function exists
    if (!function_exists('get_field')) {
        return;
    }

    $more_information = get_field('more_information', 'options');

    // Validate more information settings
    if (empty($more_information) || empty($more_information['enable_more_information'])) {
        return;
    }

    $delivery_return = !empty($more_information['delivery_return']) ? $more_information['delivery_return'] : array();
    $ask_question = !empty($more_information['ask_question']) ? $more_information['ask_question'] : array();

    // Get SVG icons
    $icon_delivery_svg = !empty($delivery_return['icon']) ? file_get_contents($delivery_return['icon']['url']) : '';
    $icon_ask_svg = !empty($ask_question['icon']) ? file_get_contents($ask_question['icon']['url']) : '';
    ?>
    <div class="bt-more-information">
        <?php
        $list_info = $more_information['list_info'];
        if ($list_info):
            echo '<ul class="bt-list-info">';
            foreach ($list_info as $item):
                if (!empty($item['icon'])) {
                    $icon_svg = file_get_contents($item['icon']['url']);
                } else {
                    $icon_svg = '';
                }
                echo '<li>';
                if (!empty($item['link_text'])) {
                    echo '<a href="' . esc_url($item['link_text']) . '">';
                }
                if (!empty($icon_svg)) {
                    echo '<div class="bt-icon">' . $icon_svg . '</div>';
                }
                if (!empty($item['text'])) {
                    echo '<p>' . $item['text'] . '</p>';
                }
                if (!empty($item['link_text'])) {
                    echo '</a>';
                }
                echo '</li>';
            endforeach;
            echo '</ul>';
        endif;
        ?>
        <div class="bt-policy-share">
            <ul>
                <li>
                    <?php if (!empty($icon_delivery_svg)): ?>
                        <?php echo '<div class="bt-icon">' . $icon_delivery_svg . '</div>'; ?>
                    <?php endif; ?>

                    <?php
                    $title_and_link = $delivery_return['title_and_link'];
                    if ($title_and_link):
                        $link_title = $title_and_link['title'];
                        $link_url = $title_and_link['url'];
                    ?>
                        <div class="bt-link">
                            <a href="<?php echo esc_url($link_url); ?>">
                                <?php echo esc_html($link_title); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </li>
                <li>
                    <?php if (!empty($icon_ask_svg)): ?>
                        <?php echo '<div class="bt-icon">' . $icon_ask_svg . '</div>'; ?>
                    <?php endif; ?>

                    <?php
                    $title_and_link = $ask_question['title_and_link'];
                    if ($title_and_link):
                        $link_title = $title_and_link['title'];
                        $link_url = $title_and_link['url'];
                    ?>
                        <div class="bt-link">
                            <a href="<?php echo esc_url($link_url); ?>">
                                <?php echo esc_html($link_title); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </li>
            </ul>
            <?php echo utenzo_product_share_render(); ?>
        </div>
    </div>
<?php
}
// add safe checkout single product
add_action('utenzo_woocommerce_template_single_safe_checkout', 'utenzo_woocommerce_single_product_safe_checkout', 40);
function utenzo_woocommerce_single_product_safe_checkout()
{
    // Check if ACF function exists
    if (!function_exists('get_field')) {
        return;
    }

    $safe_checkout = get_field('safe_checkout', 'options');

    // Validate safe checkout settings
    if (empty($safe_checkout) || empty($safe_checkout['enable_safe_checkout'])) {
        return;
    }

    $heading = !empty($safe_checkout['heading']) ? $safe_checkout['heading'] : '';
    $gallery_safe = !empty($safe_checkout['list_safe']) ? $safe_checkout['list_safe'] : array();

?>
    <div class="bt-safe-checkout">
        <?php if (!empty($heading)) : ?>
            <span><?php echo esc_html($heading); ?></span>
        <?php endif; ?>

        <?php if (!empty($gallery_safe)) : ?>
            <ul class="bt-safe-checkout-list">
                <?php foreach ($gallery_safe as $item) : ?>
                    <?php
                    if (!empty($item['ID'])) {
                        $image_url = wp_get_attachment_image_url($item['ID'], 'full');
                        $image_alt = !empty($item['alt']) ? $item['alt'] : '';
                    ?>
                        <li>
                            <img src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($image_alt); ?>">
                        </li>
                    <?php
                    }
                    ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php
}

// Customize WooCommerce product tabs to toggle with slide animation on single product page
add_action('utenzo_woocommerce_template_single_toggle', 'utenzo_woocommerce_single_product_toggle', 10);
function utenzo_woocommerce_single_product_toggle()
{
    $product_tabs = apply_filters('woocommerce_product_tabs', array()); ?>
    <div class="woocommerce-tabs bt-product-toggle bt-product-toggle-js">
        <?php foreach ($product_tabs as $key => $product_tab) : ?>
            <div class="bt-item">
                <div class="bt-item-inner">
                    <div class="bt-item-title <?php echo esc_attr($key === 'description' ? 'active' : ''); ?>">
                        <h3> <?php echo wp_kses_post(apply_filters('woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key)); ?> </h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="plus" width="18" height="18" viewBox="0 0 160 160">
                            <rect class="vertical-line" x="70" width="15" height="160" rx="7" ry="7" />
                            <rect class="horizontal-line" y="70" width="160" height="15" rx="7" ry="7" />
                        </svg>
                    </div>
                    <?php
                    echo '<div class="bt-item-content"' . (($key === 'description') ? ' style="display:block;"' : '') . ' id="tab-' . esc_attr($key) . '">';
                    if (isset($product_tab['callback'])) {
                        call_user_func($product_tab['callback'], $key, $product_tab);
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php
}

/**
 * Add or modify placeholders for existing WooCommerce checkout fields
 */

add_filter('woocommerce_checkout_fields', 'utenzo_checkout_field_placeholders');

function utenzo_checkout_field_placeholders($fields)
{
    // Billing fields placeholders
    $fields['billing']['billing_first_name']['placeholder'] = 'First Name *';
    $fields['billing']['billing_last_name']['placeholder'] = 'Last Name *';
    $fields['billing']['billing_company']['placeholder'] = 'Company name';
    $fields['billing']['billing_address_1']['placeholder'] = 'Street,...';
    $fields['billing']['billing_city']['placeholder'] = 'Town/City *';
    $fields['billing']['billing_postcode']['placeholder'] = 'Postal Code *';
    $fields['billing']['billing_state']['placeholder'] = 'Select state/province';
    $fields['billing']['billing_phone']['placeholder'] = 'Phone Number *';
    $fields['billing']['billing_email']['placeholder'] = 'Email Address *';

    // Shipping fields placeholders
    $fields['shipping']['shipping_first_name']['placeholder'] = 'First Name *';
    $fields['shipping']['shipping_last_name']['placeholder'] = 'Last Name *';
    $fields['shipping']['shipping_company']['placeholder'] = 'Company name';
    $fields['shipping']['shipping_address_1']['placeholder'] = 'Street,...';
    $fields['shipping']['shipping_city']['placeholder'] = 'Town/City *';
    $fields['shipping']['shipping_postcode']['placeholder'] = 'Postal Code *';

    // Order fields placeholders
    $fields['order']['order_comments']['placeholder'] = 'Write note...';

    return $fields;
}
/* hook redirect after logout */
add_action('wp_logout', 'utenzo_redirect_after_logout');
function utenzo_redirect_after_logout()
{
    if (class_exists('WooCommerce')) {
        wp_redirect(wc_get_page_permalink('myaccount'));
        exit();
    }
}

// Add gallery field to variable product attributes
add_action('woocommerce_product_after_variable_attributes', 'add_variation_gallery_field', 10, 3);

function add_variation_gallery_field($loop, $variation_data, $variation)
{
    // Get saved gallery images
    $variation_gallery = get_post_meta($variation->ID, '_variation_gallery', true);
    $gallery_images = $variation_gallery ? explode(',', $variation_gallery) : array();
?>
    <div class="form-row form-row-full variation-gallery-wrapper">
        <h4><?php esc_html_e('Variation Gallery Images', 'utenzo'); ?></h4>
        <div class="variation-gallery-images">
            <?php
            if (!empty($gallery_images)) {
                foreach ($gallery_images as $image_id) {
                    $image = wp_get_attachment_image_src($image_id, 'thumbnail');
                    if ($image) {
            ?>
                        <div class="image" data-id="<?php echo esc_attr($image_id); ?>">
                            <img src="<?php echo esc_url($image[0]); ?>" />
                            <a href="#" class="delete-variation-gallery-image">×</a>
                        </div>
            <?php
                    }
                }
            }
            ?>
        </div>
        <input type="hidden" name="variation_gallery[<?php echo esc_attr($loop); ?>]" class="variation-gallery-ids" value="<?php echo esc_attr($variation_gallery); ?>" />
        <button type="button" class="button add-variation-gallery-image"><?php esc_html_e('Add Gallery Images', 'utenzo'); ?></button>
    </div>
<?php
}

// Save variation gallery data
add_action('woocommerce_save_product_variation', 'save_variation_gallery', 10, 2);

function save_variation_gallery($variation_id, $loop)
{
    if (isset($_POST['variation_gallery'][$loop])) {
        update_post_meta($variation_id, '_variation_gallery', wc_clean($_POST['variation_gallery'][$loop]));
    }
}

// ajax load product gallery variation
function utenzo_load_product_gallery111()
{
    // Get variation ID from AJAX request
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;

    if ($variation_id) {
        $variation_gallery = get_post_meta($variation_id, '_variation_gallery', true);
        $gallery_images = $variation_gallery ? explode(',', $variation_gallery) : array();
    }
}
function utenzo_load_product_gallery()
{
    if (!isset($_POST['variation_id'])) {
        return;
    }
    // Get product gallery images
    $variation_id = intval($_POST['variation_id']);
    $variation_gallery = get_post_meta($variation_id, '_variation_gallery', true);
    $gallery_images = $variation_gallery ? explode(',', $variation_gallery) : array();
    $variation = wc_get_product($variation_id);
    $variation_image_id = $variation->get_image_id();
    ob_start();
    echo '<div class="woocommerce-product-gallery__slider">';

    // Add main product image variation

    if ($variation_image_id) {
        $full_size_image = wp_get_attachment_image_src($variation_image_id, 'full');
        $attributes = array(
            'title' => get_post_field('post_title', $variation_image_id),
            'data-caption' => get_post_field('post_excerpt', $variation_image_id),
            'data-src' => $full_size_image[0],
            'data-large_image' => $full_size_image[0],
            'data-large_image_width' => $full_size_image[1],
            'data-large_image_height' => $full_size_image[2],
        );

        $html = '<div data-thumb="' . esc_url(wp_get_attachment_image_url($variation_image_id, 'full')) . '" class="woocommerce-product-gallery__image woocommerce-product-zoom__image">';
        $html .= wp_get_attachment_image($variation_image_id, 'shop_single', false, $attributes);
        $html .= '</div>';

        echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $variation_image_id);
    }

    // Add gallery images
    if ($gallery_images) {
        foreach ($gallery_images as $gallery_image_id) {
            $thumbnail = wp_get_attachment_image_src($gallery_image_id, 'shop_thumbnail');
            $full_size = wp_get_attachment_image_src($gallery_image_id, 'full');
            $attributes = [
                'title' => get_post_field('post_title', $gallery_image_id),
                'data-caption' => get_post_field('post_excerpt', $gallery_image_id),
                'data-src' => $full_size[0] ?? '',
                'data-large_image' => $full_size[0] ?? '',
                'data-large_image_width' => $full_size[1] ?? '',
                'data-large_image_height' => $full_size[2] ?? '',
            ];
            $html = '<div data-thumb="' . esc_url(wp_get_attachment_image_url($gallery_image_id, 'full')) . '" class="woocommerce-product-gallery__image woocommerce-product-zoom__image">';
            $html .= wp_get_attachment_image($gallery_image_id, 'shop_single', false, $attributes);
            $html .= '</div>';
            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $gallery_image_id);
        }
    }
    echo '</div>';
    echo '<div class="woocommerce-product-gallery__slider-nav">';
    if ($gallery_images) {
        // Add main product image variation
        if ($variation_image_id) {
            $full_size_image = wp_get_attachment_image_src($variation_image_id, 'full');
            $thumbnail       = wp_get_attachment_image_src($variation_image_id, 'shop_thumbnail');
            $attributes      = array(
                'title'                   => get_post_field('post_title', $variation_image_id),
                'data-caption'            => get_post_field('post_excerpt', $variation_image_id),
                'data-src'                => $full_size_image[0] ?? '',
                'data-large_image'        => $full_size_image[0] ?? '',
                'data-large_image_width'  => $full_size_image[1] ?? '',
                'data-large_image_height' => $full_size_image[2] ?? '',
            );

            $html  = '<div data-thumb="' . esc_url($thumbnail[0] ?? wc_placeholder_img_src()) . '" class="woocommerce-product-gallery__image">';
            $html .= wp_get_attachment_image($variation_image_id, 'shop_thumbnail', false, $attributes);
            $html .= '</div>';

            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $variation_image_id);
        }
        // Add gallery images
        foreach ($gallery_images as $gallery_image_id) {
            $full_size_image = wp_get_attachment_image_src($gallery_image_id, 'full');
            $thumbnail       = wp_get_attachment_image_src($gallery_image_id, 'shop_thumbnail');
            $attributes      = array(
                'title'                   => get_post_field('post_title', $gallery_image_id),
                'data-caption'            => get_post_field('post_excerpt', $gallery_image_id),
                'data-src'                => $full_size_image[0] ?? '',
                'data-large_image'        => $full_size_image[0] ?? '',
                'data-large_image_width'  => $full_size_image[1] ?? '',
                'data-large_image_height' => $full_size_image[2] ?? '',
            );

            $html  = '<div data-thumb="' . esc_url($thumbnail[0] ?? wc_placeholder_img_src()) . '" class="woocommerce-product-gallery__image">';
            $html .= wp_get_attachment_image($gallery_image_id, 'shop_thumbnail', false, $attributes);
            $html .= '</div>';

            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $gallery_image_id);
        }
    }
    echo '</div>';

    $output['gallery'] = ob_get_clean();
    // load gallery product layout 02    
    ob_start();
    if ($variation_image_id) {
        $image_url = wp_get_attachment_image_url($variation_image_id, 'full');
        echo '<a href="' . esc_url($image_url) . '" class="bt-gallery-product--image elementor-clickable show" data-elementor-lightbox-slideshow="bt-gallery-ins">';
        echo '<div class="bt-cover-image">';
        echo wp_get_attachment_image($variation_image_id, 'full', false, array(
            'class' => 'wp-post-image',
            'title' => get_post_field('post_title', $variation_image_id),
            'alt' => get_post_meta($variation_image_id, '_wp_attachment_image_alt', true)
        ));
        echo '</div>';
        echo '</a>';
    }

    // Add gallery images
    if ($gallery_images) {
        foreach ($gallery_images as $index => $gallery_image_id) {
            $image_url = wp_get_attachment_image_url($gallery_image_id, 'full');
            $show_class = $index < 3 ? ' show' : '';
            echo '<a href="' . esc_url($image_url) . '" class="bt-gallery-product--image elementor-clickable' . $show_class . '" data-elementor-lightbox-slideshow="bt-gallery-ins">';
            echo '<div class="bt-cover-image">';
            echo wp_get_attachment_image($gallery_image_id, 'full', false, array(
                'class' => 'gallery-image',
                'title' => get_post_field('post_title', $gallery_image_id),
                'alt' => get_post_meta($gallery_image_id, '_wp_attachment_image_alt', true)
            ));
            echo '</div>';
            echo '</a>';
        }
    }
    $itemgallery = count($gallery_images);
    $output['gallery-layout02'] = ob_get_clean();
    $output['itemgallery'] = $itemgallery;
    wp_send_json_success($output);
}
add_action('wp_ajax_utenzo_load_product_gallery', 'utenzo_load_product_gallery');
add_action('wp_ajax_nopriv_utenzo_load_product_gallery', 'utenzo_load_product_gallery');

// ajax get price variation
function utenzo_get_variation_price()
{
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : '';
    if ($variation_id) {
        $variation = wc_get_product($variation_id);
        $price_html = $variation->get_price_html();
        $output['price'] = $price_html;
        wp_send_json_success($output);
    }
}
add_action('wp_ajax_utenzo_get_variation_price', 'utenzo_get_variation_price');
add_action('wp_ajax_nopriv_utenzo_get_variation_price', 'utenzo_get_variation_price');

// ajax load product toast
function utenzo_load_product_toast()
{
    // Verify product_id is set
    if (!isset($_POST['idproduct'])) {
        wp_send_json_error('Product ID not provided');
        return;
    }

    // Sanitize inputs
    $product_id = absint($_POST['idproduct']);
    $status = sanitize_text_field(isset($_POST['status']) ? $_POST['status'] : 'add');
    $tools = sanitize_text_field(isset($_POST['tools']) ? $_POST['tools'] : 'wishlist');

    // Verify product exists
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error('Invalid product ID');
        return;
    }
    // Get wishlist URL
    $wishlist_url = home_url('/products-wishlist/');
    if (function_exists('get_field')) {
        $wishlist = get_field('wishlist', 'options');
        if (!empty($wishlist['page_wishlist'])) {
            $wishlist_url = get_permalink($wishlist['page_wishlist']);
        }
    }

    // Get compare URL
    $compare_url = home_url('/products-compare/');
    if (function_exists('get_field')) {
        $compare = get_field('compare', 'options');
        if (!empty($compare['page_compare'])) {
            $compare_url = get_permalink($compare['page_compare']);
        }
    }

    // Get cart URL
    $cart_url = home_url('/cart/');
    if (function_exists('wc_get_page_id')) {
        $cart_page_id = wc_get_page_id('cart');
        if ($cart_page_id > 0) {
            $cart_url = get_permalink($cart_page_id);
        }
    }
    // Get checkout URL
    $checkout_url = home_url('/checkout/');
    if (function_exists('wc_get_page_id')) {
        $checkout_page_id = wc_get_page_id('checkout');
        if ($checkout_page_id > 0) {
            $checkout_url = get_permalink($checkout_page_id);
        }
    }
    ob_start();
?>
    <div class="bt-product-toast">
        <div class="bt-product-toast--close">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M9.41183 8L15.6952 1.71665C15.7905 1.62455 15.8666 1.51437 15.9189 1.39255C15.9713 1.27074 15.9988 1.13972 16 1.00714C16.0011 0.874567 15.9759 0.743089 15.9256 0.620381C15.8754 0.497673 15.8013 0.386193 15.7076 0.292444C15.6138 0.198695 15.5023 0.124556 15.3796 0.0743523C15.2569 0.0241486 15.1254 -0.00111435 14.9929 3.76988e-05C14.8603 0.00118975 14.7293 0.0287337 14.6074 0.0810623C14.4856 0.133391 14.3755 0.209456 14.2833 0.30482L8 6.58817L1.71665 0.30482C1.52834 0.122941 1.27612 0.0223015 1.01433 0.0245764C0.752534 0.0268514 0.502106 0.131859 0.316983 0.316983C0.131859 0.502107 0.0268514 0.752534 0.0245764 1.01433C0.0223015 1.27612 0.122941 1.52834 0.30482 1.71665L6.58817 8L0.30482 14.2833C0.209456 14.3755 0.133391 14.4856 0.0810623 14.6074C0.0287337 14.7293 0.00118975 14.8603 3.76988e-05 14.9929C-0.00111435 15.1254 0.0241486 15.2569 0.0743523 15.3796C0.124556 15.5023 0.198695 15.6138 0.292444 15.7076C0.386193 15.8013 0.497673 15.8754 0.620381 15.9256C0.743089 15.9759 0.874567 16.0011 1.00714 16C1.13972 15.9988 1.27074 15.9713 1.39255 15.9189C1.51437 15.8666 1.62455 15.7905 1.71665 15.6952L8 9.41183L14.2833 15.6952C14.4226 15.8358 14.6006 15.9317 14.7945 15.9708C14.9885 16.0098 15.1898 15.9902 15.3726 15.9145C15.5554 15.8388 15.7115 15.7104 15.8211 15.5456C15.9306 15.3808 15.9886 15.1871 15.9877 14.9893C15.9878 14.8581 15.9619 14.7283 15.9117 14.6072C15.8615 14.4861 15.7879 14.376 15.6952 14.2833L9.41183 8Z" fill="#0C2C48" />
            </svg>
        </div>
        <div class="bt-product-toast--content">
            <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="bt-product-toast--image">
                <?php echo wp_kses_post($product->get_image('medium')); ?>
            </a>
            <div class="bt-product-toast--info">
                <p class="bt-product-toast--title">
                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                        <?php echo esc_html($product->get_name()); ?>
                    </a>
                    <?php
                    $message = '';
                    if ($tools === 'wishlist') {
                        $message = $status === 'add'
                            ? esc_html__('has been added to your wishlist.', 'utenzo')
                            : esc_html__('has been removed from your wishlist.', 'utenzo');
                    } elseif ($tools === 'compare') {
                        $message = $status === 'add'
                            ? esc_html__('has been added to your compare.', 'utenzo')
                            : esc_html__('has been removed from your compare.', 'utenzo');
                    } else {
                        $message = $status === 'add'
                            ? esc_html__('has been added to your cart.', 'utenzo')
                            : esc_html__('has been removed from your cart.', 'utenzo');
                    }
                    echo wp_kses_post($message);
                    ?>
                </p>
            </div>
        </div>
        <div class="bt-product-toast--button<?php echo esc_attr($tools === 'cart' ? ' bt-button-cart' : ''); ?>">
            <?php
            if ($tools === 'wishlist') {
                echo '<a href="' . esc_url($wishlist_url) . '" class="bt-btn bt-button-hover">' . esc_html__('View Wishlist', 'utenzo') . '</a>';
            } else if ($tools === 'compare') {
                echo '<a href="' . esc_url($compare_url) . '" class="bt-btn bt-button-hover">' . esc_html__('View Compare', 'utenzo') . '</a>';
            } else {
                echo '<a href="' . esc_url($cart_url) . '" class="bt-btn bt-button-hover">' . esc_html__('View Cart', 'utenzo') . '</a>';
                echo '<a href="' . esc_url($checkout_url) . '" class="bt-btn bt-button-hover">' . esc_html__('Checkout', 'utenzo') . '</a>';
            }
            ?>
        </div>
    </div>
<?php
    $output = array(
        'toast' => ob_get_clean()
    );
    wp_send_json_success($output);
}
add_action('wp_ajax_utenzo_load_product_toast', 'utenzo_load_product_toast');
add_action('wp_ajax_nopriv_utenzo_load_product_toast', 'utenzo_load_product_toast');
