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

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
if (function_exists('get_field')) {
	$more_information = get_field('more_information', 'options');
	$safe_checkout = get_field('safe_checkout', 'options');
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
	<div class="bt-product-inner">
		<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
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
				<?php if ($product->is_in_stock()) { ?>
					<div class="bt-button-buy-now">
						<a class="button <?php echo $product->is_type('variable') ? 'disabled' : ''; ?>" data-id="<?php echo get_the_ID(); ?>"><?php echo esc_html__('Buy it now ', 'utenzo') ?></a>
					</div>
				<?php } ?>
				<?php if ($more_information && $more_information['enable_more_information']) {
					$delivery_return = $more_information['delivery_return'];
					$ask_question = $more_information['ask_question'];
					if (!empty($delivery_return['icon'])) {
						$icon_delivery_svg = file_get_contents($delivery_return['icon']['url']);
					} else {
						$icon_delivery_svg = '';
					}
					if (!empty($ask_question['icon'])) {
						$icon_ask_svg = file_get_contents($ask_question['icon']['url']);
					} else {
						$icon_ask_svg = '';
					}
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
				<?php } ?>
			</div>
			<?php do_action('utenzo_woocommerce_template_single_meta'); ?>
			<?php if ($safe_checkout && $safe_checkout['enable_safe_checkout']) {
				$heading = $safe_checkout['heading'];
				$gallary_safe = $safe_checkout['list_safe'];
			?>
				<div class="bt-safe-checkout">
					<span><?php echo esc_html($heading); ?></span>
					<?php
					if (!empty($gallary_safe)) {
						echo '<ul class="bt-safe-checkout-list">';
						foreach ($gallary_safe as $item) {
							$image_url = wp_get_attachment_image_url($item['ID'], 'full'); // Assuming 'ID' is the key for the image ID
							echo '<li><img src="' . esc_url($image_url) . '" alt="' . esc_attr($item['alt']) . '"></li>'; // Assuming 'alt' is the key for the image alt text
						}
						echo '</ul>';
					}
					?>
				</div>
			<?php } ?>
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

<?php do_action('woocommerce_after_single_product'); ?>