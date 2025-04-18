<?php
defined('ABSPATH') || exit;

global $product;

?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('bt-layout-product-2', $product); ?>>
    <div class="bt-product-inner">
        <?php

        /**
         * Hook: woocommerce_before_single_product_summary.
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        //   do_action('woocommerce_before_single_product_summary');
        ?>
        <div class="images bt-gallery-product">
            <?php
            $attachment_ids = $product->get_gallery_image_ids();
            $featured_image_id = $product->get_image_id();

            if ($featured_image_id) {
                $image_url = wp_get_attachment_image_url($featured_image_id, 'full');
                echo '<a href="' . esc_url($image_url) . '" class="bt-gallery-product--image elementor-clickable" data-elementor-lightbox-slideshow="bt-gallery-ins" data-elementor-lightbox-index="' . esc_attr($featured_image_id) . '">';
                echo '<div class="bt-cover-image">';
                echo wp_get_attachment_image($featured_image_id, 'full', false, array(
                    'class' => 'wp-post-image',
                    'title' => get_post_field('post_title', $featured_image_id),
                    'alt' => get_post_meta($featured_image_id, '_wp_attachment_image_alt', true)
                ));
                echo '</div>';
                echo '</a>';
            }

            if ($attachment_ids) {
                foreach ($attachment_ids as $attachment_id) {
                    $image_url = wp_get_attachment_image_url($attachment_id, 'full');
                    echo '<a href="' . esc_url($image_url) . '" class="bt-gallery-product--image elementor-clickable" data-elementor-lightbox-slideshow="bt-gallery-ins" data-elementor-lightbox-index="' . esc_attr($attachment_id) . '">';
                    echo '<div class="bt-cover-image">';
                    echo wp_get_attachment_image($attachment_id, 'full', false, array(
                        'class' => 'gallery-image',
                        'title' => get_post_field('post_title', $attachment_id),
                        'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true)
                    ));
                    echo '</div>';
                    echo '</a>';
                }
            }
            ?>
        </div>
        <div class="summary entry-summary">
            <div class="woocommerce-product-rating-sold">
                <?php
                do_action('utenzo_woocommerce_shop_loop_item_label');
                do_action('utenzo_woocommerce_template_single_rating');
                ?>
            </div>
            <?php
            do_action('utenzo_woocommerce_template_single_title');
            ?>
            <div class="woocommerce-product-price-wrap">
                <?php
                do_action('utenzo_woocommerce_template_single_price');
                do_action('utenzo_woocommerce_show_product_loop_sale_flash');

                ?>
            </div>
            <div class="bt-product-excerpt-add-to-cart">
                <?php
                do_action('utenzo_woocommerce_template_single_excerpt');
                do_action('utenzo_woocommerce_template_single_countdown');
                do_action('utenzo_woocommerce_template_single_add_to_cart');
                do_action('utenzo_woocommerce_template_single_more_information');
                ?>
            </div>
            <?php do_action('utenzo_woocommerce_template_single_meta');
            do_action('utenzo_woocommerce_template_single_safe_checkout');
            do_action('utenzo_woocommerce_template_single_toggle');
            ?>

        </div>
    </div>

    <?php

    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action('utenzo_woocommerce_template_related_products');
    ?>


</div>