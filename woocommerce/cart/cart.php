<?php

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined('ABSPATH') || exit;
session_start();
$coupon = '';
if (isset($_SESSION['coupon'])) {
	$coupon = $_SESSION['coupon'];
}
do_action('woocommerce_before_cart');

?>
<div class="bt-cart-content" data-coupon="<?php echo esc_attr($coupon); ?>">
	<?php
	if (function_exists('get_field')) {
		$time_promotion = get_field('time_promotion', 'option');
	} else {
		$time_promotion = '';
	}
	$free_shipping_threshold = utenzo_get_free_shipping_minimum_amount();
	$cart_total = WC()->cart->get_cart_contents_total();
	$currency_symbol = get_woocommerce_currency_symbol();
	if ($cart_total < $free_shipping_threshold) {
		$amount_left = $free_shipping_threshold - $cart_total;

		$percentage = ($cart_total / $free_shipping_threshold) * 100;
		$message = sprintf(
			__('<p class="bt-buy-more">Buy <span>%1$s%2$.2f</span> more to get <span>Freeship</span></p>', 'utenzo'),
			$currency_symbol,
			$amount_left
		);
	} else {
		$percentage = 100;
		$message = __('<p class="bt-congratulation"> Congratulations! You have free shipping!</p>', 'utenzo');
	}
	if ($time_promotion && $time_promotion['promotion'] === true) {
		$promotion_time = $time_promotion['time'];
		if (!empty($promotion_time)) {
			echo '<div class="bt-time-promotion"><span class="bt-icon">🔥</span>' . sprintf(
				__('Your cart will expire in <span id="countdown" data-time="%s">%s</span> minutes! Please checkout now before your items sell out!', 'utenzo'),
				$promotion_time,
				$promotion_time
			) . '</div>';
		}
	}
	if ($free_shipping_threshold > 0) {
	?>
		<div class="bt-progress-content <?php echo esc_attr(utenzo_is_appointment_in_cart() ? 'is_appointment' : ''); ?>">
			<?php echo '<div id="bt-free-shipping-message">' . $message . '</div>'; ?>
			<div class="bt-progress-container-cart">
				<div class="bt-progress-bar" data-width="<?php echo esc_attr($percentage) ?>">
					<div class="bt-icon-shipping">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<g id="Truck" clip-path="url(#clip0_2134_37062)">
								<path id="Vector" d="M14.375 6.25H17.7016C17.8261 6.24994 17.9478 6.28709 18.0511 6.35669C18.1544 6.42629 18.2345 6.52517 18.2812 6.64063L19.375 9.375" stroke="#C1DCFB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path id="Vector_2" d="M1.875 11.25H14.375" stroke="#C1DCFB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path id="Vector_3" d="M15 16.875C16.0355 16.875 16.875 16.0355 16.875 15C16.875 13.9645 16.0355 13.125 15 13.125C13.9645 13.125 13.125 13.9645 13.125 15C13.125 16.0355 13.9645 16.875 15 16.875Z" stroke="#C1DCFB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path id="Vector_4" d="M6.25 16.875C7.28553 16.875 8.125 16.0355 8.125 15C8.125 13.9645 7.28553 13.125 6.25 13.125C5.21447 13.125 4.375 13.9645 4.375 15C4.375 16.0355 5.21447 16.875 6.25 16.875Z" stroke="#C1DCFB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path id="Vector_5" d="M13.125 15H8.125" stroke="#C1DCFB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path id="Vector_6" d="M14.375 9.375H19.375V14.375C19.375 14.5408 19.3092 14.6997 19.1919 14.8169C19.0747 14.9342 18.9158 15 18.75 15H16.875" stroke="#C1DCFB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path id="Vector_7" d="M4.375 15H2.5C2.33424 15 2.17527 14.9342 2.05806 14.8169C1.94085 14.6997 1.875 14.5408 1.875 14.375V5.625C1.875 5.45924 1.94085 5.30027 2.05806 5.18306C2.17527 5.06585 2.33424 5 2.5 5H14.375V13.232" stroke="#C1DCFB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							</g>
							<defs>
								<clipPath id="clip0_2134_37062">
									<rect width="20" height="20" fill="white" />
								</clipPath>
							</defs>
						</svg>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
		<?php do_action('woocommerce_before_cart_table'); ?>

		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>

					<th class="product-thumbnail"><?php esc_html_e('Products', 'utenzo'); ?></th>
					<th class="product-name"></th>
					<th class="product-price"><?php esc_html_e('Price', 'utenzo'); ?></th>
					<th class="product-quantity"><?php esc_html_e('Quantity', 'utenzo'); ?></th>
					<th class="product-subtotal"><?php esc_html_e('Total Price', 'utenzo'); ?></th>
					<th class="product-remove"><span class="screen-reader-text"><?php esc_html_e('Remove item', 'utenzo'); ?></span></th>
				</tr>
			</thead>
			<tbody>
				<?php do_action('woocommerce_before_cart_contents'); ?>

				<?php
				foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
					$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
					$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
					$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

					if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
						$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
				?>
						<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
							<td class="product-thumbnail">
								<?php
								$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

								if (! $product_permalink) {
									echo wp_kses_post($thumbnail);
								} else {
									printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
								}
								?>
							</td>

							<td class="product-name" data-title="<?php esc_attr_e('Product', 'utenzo'); ?>">
								<?php
								if (! $product_permalink) {
									echo wp_kses_post($product_name . '&nbsp;');
								} else {
									/**
									 * This filter is documented above.
									 *
									 * @since 2.1.0
									 */
									echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
								}

								do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

								// Meta data.
								echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

								// Backorder notification.
								if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
									echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'utenzo') . '</p>', $product_id));
								}
								?>
							</td>

							<td class="product-price" data-title="<?php esc_attr_e('Price', 'utenzo'); ?>">
								<?php
								echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
								?>
							</td>

							<td class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'utenzo'); ?>">
								<?php
								if ($_product->is_sold_individually()) {
									$min_quantity = 1;
									$max_quantity = 1;
								} else {
									$min_quantity = 0;
									$max_quantity = $_product->get_max_purchase_quantity();
								}

								$product_quantity = woocommerce_quantity_input(
									array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $max_quantity,
										'min_value'    => $min_quantity,
										'product_name' => $product_name,
									),
									$_product,
									false
								);

								echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
								?>
							</td>

							<td class="product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'utenzo'); ?>">
								<?php
								echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
								?>
							</td>
							<td class="product-remove">
								<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
  <path d="M9.64052 9.10965C9.67536 9.14449 9.703 9.18586 9.72186 9.23138C9.74071 9.2769 9.75042 9.32569 9.75042 9.37496C9.75042 9.42424 9.74071 9.47303 9.72186 9.51855C9.703 9.56407 9.67536 9.60544 9.64052 9.64028C9.60568 9.67512 9.56432 9.70276 9.51879 9.72161C9.47327 9.74047 9.42448 9.75017 9.37521 9.75017C9.32594 9.75017 9.27714 9.74047 9.23162 9.72161C9.1861 9.70276 9.14474 9.67512 9.1099 9.64028L6.00021 6.53012L2.89052 9.64028C2.82016 9.71064 2.72472 9.75017 2.62521 9.75017C2.5257 9.75017 2.43026 9.71064 2.3599 9.64028C2.28953 9.56991 2.25 9.47448 2.25 9.37496C2.25 9.27545 2.28953 9.18002 2.3599 9.10965L5.47005 5.99996L2.3599 2.89028C2.28953 2.81991 2.25 2.72448 2.25 2.62496C2.25 2.52545 2.28953 2.43002 2.3599 2.35965C2.43026 2.28929 2.5257 2.24976 2.62521 2.24976C2.72472 2.24976 2.82016 2.28929 2.89052 2.35965L6.00021 5.46981L9.1099 2.35965C9.18026 2.28929 9.2757 2.24976 9.37521 2.24976C9.47472 2.24976 9.57016 2.28929 9.64052 2.35965C9.71089 2.43002 9.75042 2.52545 9.75042 2.62496C9.75042 2.72448 9.71089 2.81991 9.64052 2.89028L6.53036 5.99996L9.64052 9.10965Z" fill="#212121"/>
</svg></a>',
										esc_url(wc_get_cart_remove_url($cart_item_key)),
										/* translators: %s is the product name */
										esc_attr(sprintf(__('Remove %s from cart', 'utenzo'), wp_strip_all_tags($product_name))),
										esc_attr($product_id),
										esc_attr($_product->get_sku())
									),
									$cart_item_key
								);
								?>
							</td>
						</tr>
				<?php
					}
				}
				?>

				<?php do_action('woocommerce_cart_contents'); ?>

				<tr>
					<td colspan="6" class="actions">

						<?php if (wc_coupons_enabled()) { ?>
							<div class="coupon">
								<label for="coupon_code" class="screen-reader-text"><?php esc_html_e('Coupon:', 'utenzo'); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Add voucher discount', 'utenzo'); ?>" /> <button type="submit" class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="apply_coupon" value="<?php esc_attr_e('Apply Code', 'utenzo'); ?>"><?php esc_html_e('Apply Code', 'utenzo'); ?></button>
								<?php do_action('woocommerce_cart_coupon'); ?>
							</div>
						<?php } ?>

						<button type="submit" class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="update_cart" value="<?php esc_attr_e('Update cart', 'utenzo'); ?>"><?php esc_html_e('Update cart', 'utenzo'); ?></button>

						<?php do_action('woocommerce_cart_actions'); ?>

						<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
					</td>
				</tr>

				<?php do_action('woocommerce_after_cart_contents'); ?>
			</tbody>
		</table>
		<?php do_action('woocommerce_after_cart_table'); ?>
	</form>
</div>
<?php do_action('woocommerce_before_cart_collaterals'); ?>


<div class="cart-collaterals <?php echo esc_attr(utenzo_is_appointment_in_cart() ? 'is_appointment' : ''); ?>">
	<?php
	/**
	 * Cart collaterals hook.
	 *
	 * @hooked woocommerce_cross_sell_display
	 * @hooked woocommerce_cart_totals - 10
	 */
	do_action('woocommerce_cart_collaterals');
	?>
</div>
<?php do_action('utenzo_woocommerce_template_cross_sell') ?>
<?php do_action('woocommerce_after_cart'); ?>