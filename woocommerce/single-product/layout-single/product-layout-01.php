<?php
defined('ABSPATH') || exit;

global $product;
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
	<div class="bt-product-inner bt-single-product">
		<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action('woocommerce_before_single_product_summary');
		?>

		<div class="summary entry-summary ">
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
			do_action('utenzo_woocommerce_template_upsell_products');
			do_action('utenzo_woocommerce_template_single_safe_checkout');
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

	do_action('woocommerce_after_single_product_summary');
	?>


</div>