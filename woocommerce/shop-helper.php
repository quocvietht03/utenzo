<?php
// WooCommerce custom hooks
add_action('utenzo_woocommerce_template_loop_product_link_open', 'woocommerce_template_loop_product_link_open', 10);
add_action('utenzo_woocommerce_template_loop_product_link_close', 'woocommerce_template_loop_product_link_close', 5);
add_action('utenzo_woocommerce_show_product_loop_sale_flash', 'woocommerce_show_product_loop_sale_flash', 10);
add_action('utenzo_woocommerce_template_loop_product_thumbnail', 'woocommerce_template_loop_product_thumbnail', 10);
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
add_action('utenzo_woocommerce_shop_loop_item_subtitle', 'utenzo_woocommerce_template_loop_subtitle', 10, 2);
add_filter('woocommerce_product_description_heading', '__return_null');

add_action('utenzo_woocommerce_template_single_meta', 'utenzo_woocommerce_single_product_meta', 40);

function utenzo_woocommerce_single_product_meta()
{
  global $product;

  echo '<ul class="product-meta">';

  $sku = $product->get_sku();
  if ($sku) {
    echo '<li class="sku"><span>SKU:</span> ' . esc_html($sku) . '</li>';
  }

  $author_id = $product->get_post_data()->post_author;
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

remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

add_action('woocommerce_add_to_cart', 'utenzo_redirect_form_appointment', 20, 0);
function utenzo_redirect_form_appointment()
{
  if (isset($_POST['pickup_date']) && $_POST['pickup_date'] != '') {
    WC()->session->set('redirect_after_add_to_cart', true);
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

function utenzo_woocommerce_template_loop_subtitle()
{
  $subtitle = get_post_meta(get_the_ID(), '_subtitle', true);

  if (!empty($subtitle)) {
    echo '<span class="woocommerce-loop-product__subtitle">' . $subtitle . '</span>';
  }

  return;
}

add_action('woocommerce_single_product_summary', 'utenzo_woocommerce_template_single_subtitle', 3);
function utenzo_woocommerce_template_single_subtitle()
{
  $subtitle = get_post_meta(get_the_ID(), '_subtitle', true);

  if (!empty($subtitle)) {
    echo '<span class="woocommerce-product-subtitle">' . $subtitle . '</span>';
  }

  return;
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

  return '<span class="onsale">' . $percentage . '</span>';
}

add_filter('woocommerce_pagination_args', 'utenzo_woocommerce_pagination_args');
function utenzo_woocommerce_pagination_args()
{
  $total   = isset($total) ? $total : wc_get_loop_prop('total_pages');
  $current = isset($current) ? $current : wc_get_loop_prop('current_page');
  $base    = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
  $format  = isset($format) ? $format : '';

  if ($total <= 1) {
    return;
  }

  return array(
    'base'     => $base,
    'format'   => $format,
    'total'    => $total,
    'current'  => $current,
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
if (function_exists('get_field')) {
  $enable_related_posts = get_field('enable_related_posts', 'options');
  if (!$enable_related_posts) {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
  }
}

add_action('woocommerce_process_product_meta', 'utenzo_woocommerce_custom_field_save');
function utenzo_woocommerce_custom_field_save($post_id)
{
  $subtitle = $_POST['_subtitle'];
  if (!empty($subtitle)) {
    update_post_meta($post_id, '_subtitle', esc_attr($subtitle));
  } else {
    update_post_meta($post_id, '_subtitle', '');
  }
}
/* Sold Product */
function utenzo_woocommerce_item_sold($product_id)
{
  $args = array(
    'status' => 'completed',
    'limit'  => -1,
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

/* Remove the additional information tab */
add_filter('woocommerce_product_tabs', 'utenzo_woocommerce_remove_additional_information_tabs', 98);

function utenzo_woocommerce_remove_additional_information_tabs($tabs)
{
  unset($tabs['additional_information']);
  return $tabs;
}
/* Add additional information to the bottom of the description */
add_filter('the_content', 'utenzo_woocommerce_add_additional_information');
function utenzo_woocommerce_add_additional_information($content)
{
  global $product;
  if (is_product()) {
    ob_start();
    do_action('woocommerce_product_additional_information', $product);
    $additional_info_content = ob_get_clean();
    if (!empty($additional_info_content)) {
      $content .= '<h3>' . esc_html__('Additional Information:', 'utenzo') . '</h3>' . $additional_info_content;
    }
  }
  return $content;
}

/* Custom the "Review" tab title */
add_filter('woocommerce_product_tabs', 'utenzo_woocommerce_custom_reviews_tab_title');
function utenzo_woocommerce_custom_reviews_tab_title($tabs)
{
  if (isset($tabs['reviews'])) {
    global $product;
    $review_count = $product->get_review_count();
    $tabs['reviews']['title'] = esc_html__('Reviews', 'utenzo');
  }
  return $tabs;
}
/* add tax brand product */
function utenzo_create_brand_taxonomy()
{
  $args = array(
    'hierarchical' => true,
    'labels' => array(
      'name' => __('Brands', 'utenzo'),
      'singular_name' => __('Brand', 'utenzo'),
      'search_items' => __('Search Brands', 'utenzo'),
      'all_items' => __('All Brands', 'utenzo'),
      'parent_item' => __('Parent Brand', 'utenzo'),
      'parent_item_colon' => __('Parent Brand:', 'utenzo'),
      'edit_item' => __('Edit Brand', 'utenzo'),
      'update_item' => __('Update Brand', 'utenzo'),
      'add_new_item' => __('Add New Brand', 'utenzo'),
      'new_item_name' => __('New Brand Name', 'utenzo'),
      'menu_name' => __('Brands', 'utenzo'),
    ),
    'rewrite' => array(
      'slug' => 'product-brand',
      'with_front' => false,
      'hierarchical' => true,
    ),
  );

  register_taxonomy('product_brand', 'product', $args);
}

add_action('init', 'utenzo_create_brand_taxonomy', 0);

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
/* Create Product Wishlist Page */
function utenzo_product_create_pages_support()
{
  $product_wishlist_page = get_posts(array(
    'title' => 'Products Wishlist',
    'post_type' => 'page',
    'post_status'    => 'any'
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
}
add_action('init', 'utenzo_product_create_pages_support', 1);

function utenzo_get_products_by_rating($rating)
{
  $args = [
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => [
      [
        'key'     => '_wc_average_rating',
        'value'   => $rating,
        'compare' => '=',
        'type'    => 'NUMERIC',
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

  if (!empty($terms)) { ?>
    <div class="bt-form-field bt-field-type-radio <?php echo 'bt-field-' . $slug; ?>">
      <div class="bt-field-title"><?php echo esc_html($field_title_default) ?></div>
      <?php foreach ($terms as $term) { ?>
        <?php if ($term->slug == $field_value) { ?>
          <div class="item-radio">
            <input type="radio" name="<?php echo esc_attr($slug); ?>" id="<?php echo esc_attr($term->slug); ?>" value="<?php echo esc_attr($term->slug); ?>" checked>
            <label for="<?php echo esc_attr($term->slug); ?>"> <?php echo esc_html($term->name); ?> </label>
            <span class="bt-count"><?php echo '(' . $term->count . ')'; ?></span>
          </div>
        <?php } else { ?>
          <div class="item-radio">
            <input type="radio" name="<?php echo esc_attr($slug); ?>" id="<?php echo esc_attr($term->slug); ?>" value="<?php echo esc_attr($term->slug); ?>">
            <label for="<?php echo esc_attr($term->slug); ?>"> <?php echo esc_html($term->name); ?> </label>
            <span class="bt-count"><?php echo '(' . $term->count . ')'; ?></span>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  <?php }
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

  if (!empty($terms)) {
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
  <div class="bt-form-field bt-field-price">
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
  <div class="bt-form-field bt-field-type-rating <?php echo 'bt-field-' . $slug; ?>">
    <div class="bt-field-title"><?php echo esc_html($field_title_default) ?></div>
    <?php for ($rating = 5; $rating >= 1; $rating--) {
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
          echo '<label class="bt-star" for="rating' . $rating . '">' . $stars . '<span>' . esc_html__('& up', 'utenzo') . '</span></label>';
          ?>
          <span class="bt-count"><?php echo utenzo_get_products_by_rating($rating) ?></span>
        </div>
      <?php } else { ?>
        <div class="item-rating">
          <span class="check-rating"></span>
          <input type="radio" name="<?php echo esc_attr($slug); ?>" id="<?php echo 'rating' . $rating ?>" value="<?php echo esc_attr($rating); ?>">
          <?php
          echo '<label class="bt-star" for="rating' . $rating . '">' . $stars . '<span>' . esc_html__('& up', 'utenzo') . '</span></label>';
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
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'publish'
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
        'lowest_price'  => $lowest_price
      );
    }
  }

  wp_reset_postdata();

  return array(
    'highest_price' => 0,
    'lowest_price'  => 0
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
  $query_tax[] = array(
    'taxonomy' => 'product_type',
    'field' => 'slug',
    'terms' => 'redq_rental',
    'operator' => 'NOT IN'
  );

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
        'type'    => 'NUMERIC'
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
    printf(
      __('%s Products Recommended for You', 'utenzo'),
      '<span class="highlight">' . esc_html($total_products) . '</span>'
    );
  } else {
    echo esc_html__('No results', 'utenzo');
  }
  $output['results'] = ob_get_clean();

  // Update Loop Post
  if ($wp_query->have_posts()) {
    ob_start();
    while ($wp_query->have_posts()) {
      $wp_query->the_post();
      wc_get_template_part('content', 'product');
    }

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
        foreach ($product_ids as $key => $id) {
          $product = wc_get_product($id);
          if ($product) {
            $product_url = get_permalink($id);
            $product_name = $product->get_name();
            $product_image = wp_get_attachment_image_src($product->get_image_id(), 'medium');
            if (!$product_image) {
              $product_image_url = wc_placeholder_img_src();
            } else {
              $product_image_url = $product_image[0];
            }
            $product_price = $product->get_price_html();
            $stock_status = $product->is_in_stock() ? __('In Stock', 'utenzo') : __('Out of Stock', 'utenzo');
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                      <path d="M9.64052 9.10965C9.67536 9.14449 9.703 9.18586 9.72186 9.23138C9.74071 9.2769 9.75042 9.32569 9.75042 9.37496C9.75042 9.42424 9.74071 9.47303 9.72186 9.51855C9.703 9.56407 9.67536 9.60544 9.64052 9.64028C9.60568 9.67512 9.56432 9.70276 9.51879 9.72161C9.47327 9.74047 9.42448 9.75017 9.37521 9.75017C9.32594 9.75017 9.27714 9.74047 9.23162 9.72161C9.1861 9.70276 9.14474 9.67512 9.1099 9.64028L6.00021 6.53012L2.89052 9.64028C2.82016 9.71064 2.72472 9.75017 2.62521 9.75017C2.5257 9.75017 2.43026 9.71064 2.3599 9.64028C2.28953 9.56991 2.25 9.47448 2.25 9.37496C2.25 9.27545 2.28953 9.18002 2.3599 9.10965L5.47005 5.99996L2.3599 2.89028C2.28953 2.81991 2.25 2.72448 2.25 2.62496C2.25 2.52545 2.28953 2.43002 2.3599 2.35965C2.43026 2.28929 2.5257 2.24976 2.62521 2.24976C2.72472 2.24976 2.82016 2.28929 2.89052 2.35965L6.00021 5.46981L9.1099 2.35965C9.18026 2.28929 9.2757 2.24976 9.37521 2.24976C9.47472 2.24976 9.57016 2.28929 9.64052 2.35965C9.71089 2.43002 9.75042 2.52545 9.75042 2.62496C9.75042 2.72448 9.71089 2.81991 9.64052 2.89028L6.53036 5.99996L9.64052 9.10965Z" fill="#212121" />
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
              <div class="bt-table--col bt-price">
                <?php echo '<p>' . $product_price . '</p>'; ?>
              </div>
              <div class="bt-table--col bt-stock">
                <?php echo '<p>' . $stock_status . '</p>'; ?>
              </div>
              <div class="bt-table--col bt-rating woocommerce">
                <div class="bt-product-rating">
                  <?php echo wc_get_rating_html($product->get_average_rating());  ?>
                  <?php if ($product->get_rating_count()): ?>
                    <div class="bt-product-rating--count">
                      (<?php echo esc_html($product->get_rating_count()); ?>)
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="bt-table--col bt-brand">
                <?php echo '<p>' . $brand_list . '</p>'; ?>
              </div>
              <div class="bt-table--col bt-add-to-cart">
                <a href="?add-to-cart=<?php echo esc_attr($id); ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr($id); ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr($id); ?>" data-product_sku="" rel="nofollow"><?php echo esc_html__('Add to cart', 'utenzo') ?></a>
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
          </div>
          <div class="bt-table--col bt-product-price">
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
            <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>" aria-describedby="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr($product_id); ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku="" rel="nofollow"><?php echo esc_html__('Add to cart', 'utenzo') ?></a>
          </div>
        </div>
  <?php }
    }
    $output['items'] = ob_get_clean();
  } else {
    $output['items'] = '<div class="bt-no-results">' . __('No products found! ', 'utenzo') . '<a href="/shop/">' . __('Back to Shop', 'utenzo') . '</a></div>';
  }

  wp_send_json_success($output);

  die();
}
add_action('wp_ajax_utenzo_products_wishlist', 'utenzo_products_wishlist');
add_action('wp_ajax_nopriv_utenzo_products_wishlist', 'utenzo_products_wishlist');

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
  if(utenzo_is_appointment_in_cart()){
    $output['is_appointment'] = true;
  }else{
    $output['is_appointment'] = false;
  }
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
  return esc_html__('Related Products', 'utenzo');
}
function bt_limit_cross_sells_display($limit)
{
  return 4; // Limit to 4 products
}

function bt_set_cross_sells_columns($columns)
{
  return 4; // Set columns to 2
}
/* add button wishlist and compare */
function utenzo_display_button_wishlist_compare()
{
  global $product;
?>
  <div class="bt-product-icon-btn">
    <a class="bt-icon-btn bt-product-compare-btn" href="#" data-id="<?php echo esc_attr($product->get_id()); ?>">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 20 18" fill="none">
        <path d="M8.50001 11.2504C8.3011 11.2504 8.11033 11.3295 7.96968 11.4701C7.82903 11.6108 7.75001 11.8015 7.75001 12.0004V14.6901L5.09876 12.0379C4.7493 11.6907 4.47224 11.2776 4.28364 10.8224C4.09503 10.3673 3.99862 9.87933 4.00001 9.38669V5.90669C4.707 5.72415 5.32315 5.29002 5.73296 4.68568C6.14277 4.08135 6.3181 3.3483 6.2261 2.62394C6.13409 1.89958 5.78106 1.23364 5.23318 0.750949C4.6853 0.268258 3.98019 0.00195312 3.25001 0.00195312C2.51983 0.00195312 1.81471 0.268258 1.26683 0.750949C0.718953 1.23364 0.365924 1.89958 0.273918 2.62394C0.181912 3.3483 0.357247 4.08135 0.767056 4.68568C1.17687 5.29002 1.79301 5.72415 2.50001 5.90669V9.38763C2.49826 10.0773 2.63324 10.7606 2.89715 11.3978C3.16105 12.035 3.54864 12.6136 4.03751 13.1001L6.6897 15.7504H4.00001C3.8011 15.7504 3.61033 15.8295 3.46968 15.9701C3.32903 16.1108 3.25001 16.3015 3.25001 16.5004C3.25001 16.6994 3.32903 16.8901 3.46968 17.0308C3.61033 17.1714 3.8011 17.2504 4.00001 17.2504H8.50001C8.69892 17.2504 8.88969 17.1714 9.03034 17.0308C9.17099 16.8901 9.25001 16.6994 9.25001 16.5004V12.0004C9.25001 11.8015 9.17099 11.6108 9.03034 11.4701C8.88969 11.3295 8.69892 11.2504 8.50001 11.2504ZM1.75001 3.00044C1.75001 2.70377 1.83798 2.41376 2.0028 2.16709C2.16763 1.92041 2.40189 1.72815 2.67598 1.61462C2.95007 1.50109 3.25167 1.47138 3.54264 1.52926C3.83361 1.58714 4.10089 1.73 4.31067 1.93978C4.52045 2.14956 4.66331 2.41683 4.72119 2.70781C4.77906 2.99878 4.74936 3.30038 4.63583 3.57447C4.5223 3.84855 4.33004 4.08282 4.08336 4.24764C3.83669 4.41247 3.54668 4.50044 3.25001 4.50044C2.85218 4.50044 2.47065 4.3424 2.18935 4.0611C1.90804 3.7798 1.75001 3.39827 1.75001 3.00044ZM17.5 12.0942V8.61419C17.5018 7.92448 17.3668 7.24127 17.1029 6.60404C16.839 5.96681 16.4514 5.38822 15.9625 4.90169L13.3103 2.25044H16C16.1989 2.25044 16.3897 2.17142 16.5303 2.03077C16.671 1.89012 16.75 1.69935 16.75 1.50044C16.75 1.30153 16.671 1.11076 16.5303 0.970111C16.3897 0.829458 16.1989 0.750441 16 0.750441H11.5C11.3011 0.750441 11.1103 0.829458 10.9697 0.970111C10.829 1.11076 10.75 1.30153 10.75 1.50044V6.00044C10.75 6.19935 10.829 6.39012 10.9697 6.53077C11.1103 6.67142 11.3011 6.75044 11.5 6.75044C11.6989 6.75044 11.8897 6.67142 12.0303 6.53077C12.171 6.39012 12.25 6.19935 12.25 6.00044V3.31075L14.9013 5.96294C15.2507 6.31018 15.5278 6.72333 15.7164 7.17843C15.905 7.63354 16.0014 8.12155 16 8.61419V12.0942C15.293 12.2767 14.6769 12.7109 14.2671 13.3152C13.8572 13.9195 13.6819 14.6526 13.7739 15.3769C13.8659 16.1013 14.219 16.7672 14.7668 17.2499C15.3147 17.7326 16.0198 17.9989 16.75 17.9989C17.4802 17.9989 18.1853 17.7326 18.7332 17.2499C19.2811 16.7672 19.6341 16.1013 19.7261 15.3769C19.8181 14.6526 19.6428 13.9195 19.233 13.3152C18.8232 12.7109 18.207 12.2767 17.5 12.0942ZM16.75 16.5004C16.4533 16.5004 16.1633 16.4125 15.9167 16.2476C15.67 16.0828 15.4777 15.8486 15.3642 15.5745C15.2507 15.3004 15.221 14.9988 15.2788 14.7078C15.3367 14.4168 15.4796 14.1496 15.6893 13.9398C15.8991 13.73 16.1664 13.5871 16.4574 13.5293C16.7483 13.4714 17.0499 13.5011 17.324 13.6146C17.5981 13.7282 17.8324 13.9204 17.9972 14.1671C18.162 14.4138 18.25 14.7038 18.25 15.0004C18.25 15.3983 18.092 15.7798 17.8107 16.0611C17.5294 16.3424 17.1478 16.5004 16.75 16.5004Z" fill="#212121" />
      </svg>
    </a>
    <a class="bt-icon-btn bt-product-wishlist-btn" href="#" data-id="<?php echo esc_attr($product->get_id()); ?>">
      <svg width="25" height="25" viewBox="0 0 25 25" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.9916 18.8679C14.3066 19.4224 13.4266 19.7282 12.5129 19.7282C11.6004 19.7282 10.7179 19.4236 10.0054 18.8512C5.51289 15.2466 2.65289 13.3342 2.50414 9.41186C2.34789 5.26103 7.12289 3.74375 10.0504 7.15438C10.6429 7.84341 11.5329 8.2385 12.4929 8.2385C13.4616 8.2385 14.3579 7.83864 14.9516 7.14128C17.8154 3.78539 22.7179 5.21462 22.4941 9.53324C22.2941 13.3747 19.3241 15.3585 14.9916 18.8679ZM12.9841 5.72634C12.8616 5.87033 12.6766 5.94292 12.4929 5.94292C12.3129 5.94292 12.1341 5.87271 12.0141 5.73348C7.58539 0.574693 -0.234601 3.14396 0.0053982 9.49159C0.196648 14.5433 3.95664 17.0471 8.38414 20.5994C9.56788 21.549 11.0404 22.0238 12.5129 22.0238C13.9891 22.0238 15.4641 21.5466 16.6454 20.5898C21.0241 17.0424 24.7366 14.5552 24.9904 9.64154C25.3279 3.1523 17.4016 0.546134 12.9841 5.72634Z" />
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
add_action('wp_ajax_utenzo_products_buy_now', 'utenzo_products_buy_now');
add_action('wp_ajax_nopriv_utenzo_products_buy_now', 'utenzo_products_buy_now');

function utenzo_products_buy_now()
{

  if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $product = wc_get_product($product_id);

    if ($product) {
      WC()->cart->add_to_cart($product_id);

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
    if (is_singular('product')) { ?>
      <div class="bt-product-share">
        <?php if (!empty($social_item)) {
          echo '<div class="button-share"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
<path d="M13.7526 12.5005C13.3357 12.5004 12.923 12.5839 12.5389 12.7462C12.1549 12.9084 11.8073 13.1461 11.5167 13.4451L7.91513 11.1302C8.19843 10.4038 8.19843 9.59734 7.91513 8.87086L11.5167 6.55602C12.0577 7.11017 12.7851 7.44372 13.5581 7.49198C14.331 7.54024 15.0943 7.29978 15.7 6.81723C16.3057 6.33467 16.7107 5.64439 16.8364 4.88023C16.9621 4.11606 16.7995 3.33245 16.3803 2.68131C15.9611 2.03017 15.315 1.5579 14.5673 1.35606C13.8196 1.15421 13.0236 1.23718 12.3337 1.58887C11.6437 1.94057 11.1089 2.53592 10.8329 3.25953C10.557 3.98313 10.5596 4.78342 10.8401 5.50524L7.23857 7.82008C6.80487 7.37483 6.24824 7.06902 5.63985 6.94175C5.03145 6.81449 4.39892 6.87154 3.82312 7.10563C3.24731 7.33971 2.7544 7.74019 2.40738 8.25586C2.06036 8.77154 1.875 9.37898 1.875 10.0005C1.875 10.6221 2.06036 11.2296 2.40738 11.7452C2.7544 12.2609 3.24731 12.6614 3.82312 12.8955C4.39892 13.1296 5.03145 13.1866 5.63985 13.0593C6.24824 12.9321 6.80487 12.6263 7.23857 12.181L10.8401 14.4959C10.5989 15.1181 10.5632 15.8013 10.7382 16.4454C10.9133 17.0894 11.29 17.6605 11.8132 18.075C12.3363 18.4894 12.9784 18.7255 13.6454 18.7487C14.3124 18.7718 14.9694 18.5808 15.52 18.2036C16.0706 17.8264 16.4859 17.2828 16.7053 16.6524C16.9246 16.0221 16.9363 15.338 16.7387 14.7005C16.5412 14.063 16.1447 13.5055 15.6074 13.1096C15.07 12.7137 14.4201 12.5003 13.7526 12.5005ZM13.7526 2.50055C14.1235 2.50055 14.486 2.61052 14.7943 2.81654C15.1027 3.02257 15.343 3.31541 15.4849 3.65802C15.6268 4.00063 15.6639 4.37763 15.5916 4.74134C15.5193 5.10506 15.3407 5.43915 15.0785 5.70137C14.8162 5.9636 14.4821 6.14217 14.1184 6.21452C13.7547 6.28687 13.3777 6.24974 13.0351 6.10782C12.6925 5.96591 12.3997 5.72559 12.1936 5.41724C11.9876 5.1089 11.8776 4.74639 11.8776 4.37555C11.8776 3.87827 12.0752 3.40135 12.4268 3.04972C12.7784 2.69809 13.2553 2.50055 13.7526 2.50055ZM5.00263 11.8755C4.63179 11.8755 4.26928 11.7656 3.96093 11.5596C3.65259 11.3535 3.41227 11.0607 3.27036 10.7181C3.12844 10.3755 3.09131 9.99847 3.16366 9.63475C3.236 9.27104 3.41458 8.93695 3.6768 8.67472C3.93903 8.4125 4.27312 8.23392 4.63683 8.16158C5.00055 8.08923 5.37755 8.12636 5.72016 8.26827C6.06277 8.41019 6.35561 8.65051 6.56163 8.95885C6.76766 9.2672 6.87763 9.62971 6.87763 10.0005C6.87763 10.4978 6.68008 10.9747 6.32845 11.3264C5.97682 11.678 5.49991 11.8755 5.00263 11.8755ZM13.7526 17.5005C13.3818 17.5005 13.0193 17.3906 12.7109 17.1846C12.4026 16.9785 12.1623 16.6857 12.0204 16.3431C11.8784 16.0005 11.8413 15.6235 11.9137 15.2598C11.986 14.896 12.1646 14.5619 12.4268 14.2997C12.689 14.0375 13.0231 13.8589 13.3868 13.7866C13.7505 13.7142 14.1275 13.7514 14.4702 13.8933C14.8128 14.0352 15.1056 14.2755 15.3116 14.5839C15.5177 14.8922 15.6276 15.2547 15.6276 15.6255C15.6276 16.1228 15.4301 16.5997 15.0785 16.9514C14.7268 17.303 14.2499 17.5005 13.7526 17.5005Z" fill="#212121"/>
</svg>' . esc_html__('Share', 'utenzo') . '</div><ul>' . implode(' ', $social_item) . '</ul>';
        } ?>
      </div>
<?php }

    return ob_get_clean();
  }
}

function utenzo_free_shipping_appointment($rates, $package)
{
  if (!function_exists('get_field')) {
    return $rates;
  }
  $site_infor = get_field('site_information', 'options') ?: '';
  $appointment_id = '';
  if (!empty($site_infor['page_book_now'])) {
    $appointment_id = url_to_postid($site_infor['page_book_now']);
  }
  if (empty($appointment_id)) {
    return $rates;
  }
  $has_appointment = false;
  foreach (WC()->cart->get_cart() as $cart_item) {
    if ((int) $cart_item['product_id'] === (int) $appointment_id) {
      $has_appointment = true;
      break;
    }
  }
  if ($has_appointment) {
    foreach ($rates as $rate_id => $rate) {
      $rates[$rate_id]->cost = 0;
      $rates[$rate_id]->label = __('Free Shipping', 'utenzo');
      if (!empty($rates[$rate_id]->taxes)) {
        foreach ($rates[$rate_id]->taxes as $tax_id => $tax) {
          $rates[$rate_id]->taxes[$tax_id] = 0;
        }
      }
    }
  }

  return $rates;
}
add_filter('woocommerce_package_rates', 'utenzo_free_shipping_appointment', 10, 2);

function utenzo_is_appointment_in_cart()
{
  if (!function_exists('get_field')) {
    return false;
  }
  $site_infor = get_field('site_information', 'options') ?: '';
  $appointment_id = '';
  if (!empty($site_infor['page_book_now'])) {
    $appointment_id = url_to_postid($site_infor['page_book_now']);
  }
  if (empty($appointment_id)) {
    return false;
  }

  foreach (WC()->cart->get_cart() as $cart_item) {
    if ((int) $cart_item['product_id'] === (int) $appointment_id) {
      return true;
    }
  }

  return false;
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