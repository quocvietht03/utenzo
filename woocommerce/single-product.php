<?php

/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

get_header('shop');

$product_id = get_the_ID();
$product = wc_get_product($product_id);
$product_type = $product->get_type();
?>
<div class="bt-product-breadcrumb">
	<div class="bt-container">
		<?php
		$home_text = 'Homepage';
		$delimiter = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
							<path opacity="0.5" d="M4.12922 10.3724C3.97259 10.2178 3.95835 9.97591 4.0865 9.80543L4.12922 9.75658L7.93471 6L4.12922 2.24342C3.97259 2.08881 3.95835 1.84688 4.0865 1.67639L4.12922 1.62755C4.28584 1.47294 4.53094 1.45889 4.70365 1.58539L4.75314 1.62755L8.87078 5.69207C9.02741 5.84667 9.04165 6.08861 8.9135 6.25909L8.87078 6.30793L4.75314 10.3724C4.58085 10.5425 4.30151 10.5425 4.12922 10.3724Z" fill="#212121"/>
						</svg>';
		echo utenzo_page_breadcrumb($home_text, $delimiter);
		?>
	</div>
</div>

<main id="bt_main" class="bt-site-main">
	<div class="bt-main-content">
		<div class="bt-main-product-ss">
			<div class="bt-container">

				<?php while (have_posts()) : ?>
					<?php the_post();
					wc_get_template_part('content', 'single-product');
					?>

				<?php endwhile; // end of the loop. 
				?>

			</div>
		</div>
	</div>
</main>
<?php
get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
