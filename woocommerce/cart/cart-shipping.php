<?php

/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 */

defined('ABSPATH') || exit;

$formatted_destination    = isset($formatted_destination) ? $formatted_destination : WC()->countries->get_formatted_address($package['destination'], ', ');
$has_calculated_shipping  = ! empty($has_calculated_shipping);
$show_shipping_calculator = ! empty($show_shipping_calculator);
$calculator_text          = '';
?>
<tr class="woocommerce-shipping-totals shipping">
	<th><?php echo wp_kses_post($package_name); ?></th>
	<td data-title="<?php echo esc_attr($package_name); ?>">
		<?php if (! empty($available_methods) && is_array($available_methods)) : ?>
			<ul id="shipping_method" class="woocommerce-shipping-methods">
				<?php
				// Check if free shipping is available
				$has_free_shipping = false;
				foreach ($available_methods as $method) {
					if ($method->method_id === "free_shipping") {
						$has_free_shipping = true;
						break;
					}
				}
				if ($has_free_shipping) {
					echo '<li><label class="bt-free-shipping">' . esc_html__('Free Shipping', 'utenzo') . '</label></li>'; // Translators: 'Free Shipping'
				} else {
					foreach ($available_methods as $method) :
				?>
						<li>
							<?php
							if (1 < count($available_methods)) {
								printf('<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s />', $index, esc_attr(sanitize_title($method->id)), esc_attr($method->id), checked($method->id, $chosen_method, false)); // WPCS: XSS ok.
							} else {
								printf('<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />', $index, esc_attr(sanitize_title($method->id)), esc_attr($method->id)); // WPCS: XSS ok.
							}
							printf('<label for="shipping_method_%1$s_%2$s">%3$s</label>', $index, esc_attr(sanitize_title($method->id)), wc_cart_totals_shipping_method_label($method)); // WPCS: XSS ok.
							do_action('woocommerce_after_shipping_rate', $method, $index);
							?>
						</li>
				<?php endforeach;
				}
				?>
			</ul>
			<?php if (is_cart()) : ?>
				<p class="woocommerce-shipping-destination">
					<?php
					if ($formatted_destination) {
						// Translators: $s shipping destination.
						printf(esc_html__('Shipping to %s.', 'utenzo') . ' ', '<strong>' . esc_html($formatted_destination) . '</strong>');
						$calculator_text = esc_html__('Change address', 'utenzo');
					} else {
						echo wp_kses_post(apply_filters('woocommerce_shipping_estimate_html', __('Shipping options will be updated during checkout.', 'utenzo')));
					}
					?>
				</p>
			<?php endif; ?>
		<?php
		elseif (! $has_calculated_shipping || ! $formatted_destination) :
			if (is_cart() && 'no' === get_option('woocommerce_enable_shipping_calc')) {
				echo wp_kses_post(apply_filters('woocommerce_shipping_not_enabled_on_cart_html', __('Shipping costs are calculated during checkout.', 'utenzo')));
			} else {
				echo wp_kses_post(apply_filters('woocommerce_shipping_may_be_available_html', __('Enter your address to view shipping options.', 'utenzo')));
			}
		elseif (! is_cart()) :
			echo wp_kses_post(apply_filters('woocommerce_no_shipping_available_html', __('There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'utenzo')));
		else :
			echo wp_kses_post(
				/**
				 * Provides a means of overriding the default 'no shipping available' HTML string.
				 *
				 * @since 3.0.0
				 *
				 * @param string $html                  HTML message.
				 * @param string $formatted_destination The formatted shipping destination.
				 */
				apply_filters(
					'woocommerce_cart_no_shipping_available_html',
					// Translators: $s shipping destination.
					sprintf(esc_html__('No shipping options were found for %s.', 'utenzo') . ' ', '<strong>' . esc_html($formatted_destination) . '</strong>'),
					$formatted_destination
				)
			);
			$calculator_text = esc_html__('Enter a different address', 'utenzo');
		endif;
		?>

		<?php if ($show_package_details) : ?>
			<?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html($package_details) . '</small></p>'; ?>
		<?php endif; ?>

		<?php if ($show_shipping_calculator) : ?>
			<?php woocommerce_shipping_calculator($calculator_text); ?>
		<?php endif; ?>
	</td>
</tr>