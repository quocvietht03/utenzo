<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;
?>
<div id="product-<?php echo $product->get_id(); ?>" <?php wc_product_class('', $product); ?>>
	<div class="bt-product-inner">
		<?php
		do_action('woocommerce_before_single_product_summary');
		?>

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
				do_action('utenzo_woocommerce_template_single_add_to_cart');
				?>
				<div class="bt-button-buy-now">
					<a class="button <?php echo $product->is_type('variable') ? 'disabled' : ''; ?>" data-id="<?php echo $product->get_id(); ?>"><?php echo esc_html__('Buy it now ', 'utenzo') ?></a>
				</div>
			</div>
		</div>
	</div>
</div>