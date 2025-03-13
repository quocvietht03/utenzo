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
	$site_infor = get_field('site_information', 'options') ?: '';
	$appointment = get_field('appointment', 'options');
} else {
	$site_infor = '';
	$appointment = '';
}
if (isset($_GET['offer-coupon']) && $_GET['offer-coupon'] != '') {
	session_start();
	$_SESSION['coupon'] = $_GET['offer-coupon'];
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
	<div class="bt-single-appointment">
		<div class="bt-info-appointment">
			<?php
			if (!empty($appointment['sub_heading'])) {
				echo '<span>' . $appointment['sub_heading'] . '</span>';
			}
			if (!empty($appointment['heading'])) {
				echo '<h2>' . $appointment['heading'] . '</h2>';
			}
			if (!empty($appointment['description'])) {
				echo '<p>' . $appointment['description'] . '</p>';
			}
			?>
			<div class="box-button-offer-contact">
				<?php if (!empty($appointment['button'])) { ?>
					<div class="bt-button-offer bt-button-hover">
						<a href="<?php echo esc_url($appointment['button']['url']); ?>" class="bt-button">
							<span class="bt-heading"><?php echo esc_html($appointment['button']['title']); ?></span>
							<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g id="ArrowRight" clip-path="url(#clip0_10935_2624)">
									<path id="Vector" d="M3.125 10.3455H16.875" stroke="#212121" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
									<path id="Vector_2" d="M11.25 4.72046L16.875 10.3455L11.25 15.9705" stroke="#212121" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
								</g>
								<defs>
									<clipPath id="clip0_10935_2624">
										<rect width="20" height="20" fill="white" transform="translate(0 0.345459)"></rect>
									</clipPath>
								</defs>
							</svg>
						</a>
					</div>
				<?php } ?>
				<div class="bt-contact">
					<a href="<?php echo esc_url('tel:' . preg_replace('/[^0-9]+/', '', $site_infor['site_phone'])); ?>">
						<div class="bt-contact-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
								<path d="M15.275 1.80528C15.3004 1.71004 15.3443 1.62074 15.4043 1.54249C15.4642 1.46423 15.5389 1.39856 15.6243 1.34922C15.7096 1.29988 15.8038 1.26784 15.9015 1.25494C15.9993 1.24204 16.0986 1.24853 16.1938 1.27403C18.0053 1.74656 19.658 2.69348 20.9818 4.01726C22.3056 5.34103 23.2525 6.99379 23.725 8.80528C23.7505 8.90049 23.757 8.99979 23.7441 9.09751C23.7312 9.19523 23.6992 9.28945 23.6498 9.37477C23.6005 9.4601 23.5348 9.53487 23.4566 9.5948C23.3783 9.65473 23.289 9.69864 23.1938 9.72403C23.1306 9.74086 23.0654 9.74927 23 9.74903C22.8348 9.74913 22.6741 9.69466 22.543 9.59407C22.4119 9.49348 22.3177 9.35242 22.275 9.19278C21.8693 7.63677 21.0561 6.21708 19.919 5.08003C18.782 3.94298 17.3623 3.12971 15.8063 2.72403C15.711 2.69864 15.6217 2.65473 15.5435 2.5948C15.4652 2.53487 15.3995 2.4601 15.3502 2.37478C15.3009 2.28945 15.2688 2.19523 15.2559 2.09751C15.243 1.99979 15.2495 1.90049 15.275 1.80528ZM14.8063 6.72403C16.625 7.20903 17.79 8.37403 18.275 10.1928C18.3177 10.3524 18.4119 10.4935 18.543 10.5941C18.6741 10.6947 18.8348 10.7491 19 10.749C19.0654 10.7493 19.1306 10.7409 19.1938 10.724C19.289 10.6986 19.3783 10.6547 19.4566 10.5948C19.5348 10.5349 19.6005 10.4601 19.6498 10.3748C19.6992 10.2894 19.7312 10.1952 19.7441 10.0975C19.757 9.99979 19.7505 9.90049 19.725 9.80528C19.1 7.46653 17.5325 5.89903 15.1938 5.27403C15.0986 5.24859 14.9993 5.24215 14.9016 5.25507C14.8039 5.268 14.7097 5.30005 14.6244 5.34938C14.4521 5.44901 14.3264 5.613 14.275 5.80528C14.2236 5.99756 14.2507 6.20238 14.3504 6.37468C14.45 6.54698 14.614 6.67265 14.8063 6.72403ZM24.7363 18.849C24.5221 20.4831 23.7202 21.9832 22.4805 23.069C21.2408 24.1549 19.648 24.7521 18 24.749C8.21251 24.749 0.250012 16.7865 0.250012 6.99903C0.246855 5.35159 0.843537 3.75933 1.9286 2.51968C3.01367 1.28003 4.5129 0.477784 6.14626 0.262783C6.52226 0.21709 6.90293 0.294619 7.23111 0.483727C7.55929 0.672834 7.81725 0.963312 7.96626 1.31153L10.6038 7.19903C10.7202 7.46557 10.7684 7.75692 10.744 8.04676C10.7195 8.3366 10.6232 8.61577 10.4638 8.85903C10.4477 8.88379 10.4302 8.90759 10.4113 8.93028L7.77751 12.0628C7.76152 12.0953 7.75321 12.131 7.75321 12.1672C7.75321 12.2034 7.76152 12.2391 7.77751 12.2715C8.73501 14.2315 10.79 16.2715 12.7775 17.2278C12.8107 17.2429 12.8469 17.2501 12.8834 17.2488C12.9198 17.2475 12.9555 17.2377 12.9875 17.2203L16.0738 14.5953C16.0958 14.5761 16.1192 14.5586 16.1438 14.5428C16.3859 14.3813 16.6646 14.2828 16.9544 14.2562C17.2443 14.2296 17.5362 14.2756 17.8038 14.3903L23.7088 17.0365C24.0524 17.1888 24.3378 17.4477 24.5228 17.7748C24.7079 18.1019 24.7827 18.4798 24.7363 18.8528V18.849ZM23.25 18.664C23.2542 18.6118 23.2419 18.5595 23.2147 18.5146C23.1876 18.4698 23.147 18.4346 23.0988 18.414L17.1925 15.7678C17.1602 15.7553 17.1257 15.75 17.0911 15.7522C17.0566 15.7543 17.023 15.7639 16.9925 15.7803L13.9075 18.4053C13.885 18.424 13.8613 18.4415 13.8375 18.4578C13.5859 18.6256 13.295 18.7253 12.9934 18.7472C12.6917 18.7691 12.3895 18.7125 12.1163 18.5828C9.82126 17.474 7.53376 15.2078 6.42501 12.934C6.29461 12.6624 6.23656 12.3616 6.25648 12.061C6.27641 11.7603 6.37364 11.4699 6.53876 11.2178C6.55486 11.1927 6.57283 11.1689 6.59251 11.1465L9.22501 8.01403C9.24006 7.98129 9.24784 7.94569 9.24784 7.90966C9.24784 7.87363 9.24006 7.83802 9.22501 7.80528L6.59251 1.91278C6.57514 1.86549 6.54388 1.82455 6.50284 1.79533C6.4618 1.76611 6.41289 1.74997 6.36251 1.74903H6.33376C5.06244 1.91815 3.89613 2.54421 3.05263 3.51032C2.20913 4.47642 1.7461 5.71652 1.75001 6.99903C1.75001 15.959 9.04001 23.249 18 23.249C19.2827 23.2529 20.523 22.7897 21.4891 21.9459C22.4552 21.1022 23.0812 19.9356 23.25 18.664Z" fill="#212121" />
							</svg>
						</div>
						<div class="bt-contact-content">
							<h4><?php echo esc_html__('Have any Question?', 'utenzo') ?></h4>
							<span> <?php echo esc_html($site_infor['site_phone']); ?> </span>
						</div>
					</a>
				</div>
			</div>
		</div>
		<div class="bt-form-appointment">
			<?php
			if (isset($_GET['discount']) && $_GET['discount'] != '') {
				echo '<div class="bt-offer-discount">' . $_GET['discount'] . '</div>';
			}
			do_action('utenzo_woocommerce_template_single_title');
			do_action('utenzo_woocommerce_template_single_price');
			?>
			<div class="bt-product-add-to-cart">
				<?php
				do_action('utenzo_woocommerce_template_single_add_to_cart');
				?>
			</div>
		</div>
	</div>




</div>

<?php do_action('woocommerce_after_single_product'); ?>