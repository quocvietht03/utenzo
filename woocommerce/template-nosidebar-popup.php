<?php
/**
 * Template Name: Shop No Sidebar Popup
 */
global $wp_query;
$rows = intval(get_option('woocommerce_catalog_rows', 4)); 
$columns = intval(get_option('woocommerce_catalog_columns', 4)); 
$rows = $rows > 0 ? $rows : 1;
$columns = $columns > 0 ? $columns : 1;
$limit = $rows * $columns;
$query_args = utenzo_products_query_args($_GET, $limit);
$wp_query = new \WP_Query($query_args);
$current_page = isset($_GET['current_page']) && $_GET['current_page'] != '' ? absint($_GET['current_page']) : 1;
$total_page = $wp_query->max_num_pages;
$total_products = $wp_query->found_posts;
$column_product = get_field('column_product', get_the_ID());
get_header('shop');
get_template_part('framework/templates/site', 'titlebar');

?>
<div class="bt-filter-scroll-pos"></div>
<main id="bt_main" class="bt-site-main">
	<div class="bt-main-content">
		<div class="bt-main-products-ss bt-template-nosidebar-popup">
			<div class="bt-container">
				<div class="bt-products-sidebar">
					<?php get_template_part('woocommerce/sidebar', 'product'); ?>
				</div>
				<div class="bt-main-products-inner">
					<div class="bt-products-topbar">
						<div class="bt-product-action">
							<div class="bt-product-filter-toggle">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
									<path d="M14.4125 3.09564C14.3355 2.91784 14.208 2.76658 14.0458 2.66068C13.8835 2.55477 13.6937 2.49891 13.5 2.50002H2.49998C2.30644 2.5004 2.11717 2.55694 1.95512 2.66277C1.79308 2.76861 1.66523 2.91919 1.58709 3.09626C1.50894 3.27332 1.48386 3.46926 1.51488 3.6603C1.54591 3.85134 1.6317 4.02928 1.76186 4.17252L1.76686 4.17814L5.99998 8.69814V13.5C5.99994 13.681 6.04902 13.8586 6.14198 14.0139C6.23494 14.1692 6.36831 14.2963 6.52785 14.3818C6.6874 14.4672 6.86714 14.5078 7.04792 14.4991C7.2287 14.4904 7.40373 14.4329 7.55436 14.3325L9.55436 12.9988C9.69146 12.9074 9.80388 12.7837 9.88162 12.6384C9.95936 12.4932 10 12.331 9.99998 12.1663V8.69814L14.2337 4.17814L14.2387 4.17252C14.3703 4.02993 14.4569 3.85176 14.4878 3.66025C14.5187 3.46873 14.4925 3.27236 14.4125 3.09564ZM9.13623 8.16127C9.04974 8.25297 9.00107 8.37396 8.99998 8.50002V12.1663L6.99998 13.5V8.50002C7.00002 8.37305 6.95176 8.25083 6.86498 8.15814L2.49998 3.50002H13.5L9.13623 8.16127Z" fill="#212121" />
								</svg><?php esc_html_e('Filters', 'utenzo') ?>
							</div>
							<div class="bt-results-count">
								<?php
								if ($total_products > 0) {
									printf(
										__('%s Products Recommended for You', 'utenzo'),
										'<span class="highlight">' . esc_html($total_products) . '</span>'
									);
								} else {
									echo esc_html__('No results', 'utenzo');
								}
								?>
							</div>
						</div>

						<div class="bt-product-orderby">
							<div class="bt-product-sort-block">
								<span class="bt-sort-title">
									<?php echo esc_html__('Sort by:', 'utenzo'); ?>
								</span>
								<div class="bt-sort-field">
									<?php
									$sort_options = array(
										'date_high' => esc_html__('Date: newest first', 'utenzo'),
										'date_low' => esc_html__('Date: oldest first', 'utenzo'),
										'best_selling' => esc_html__('Best Selling', 'utenzo'),
										'average_rating' => esc_html__('Average Rating', 'utenzo'),
										'price_high' => esc_html__('Price: highest first', 'utenzo'),
										'price_low' => esc_html__('Price: lower first', 'utenzo')
									);
									?>
									<select name="sort_order">
										<?php foreach ($sort_options as $key => $value) { ?>
											<?php if (isset($_GET['sort_order']) && $key == $_GET['sort_order']) { ?>
												<?php if ($key == $_GET['sort_order']) { ?>
													<option value="<?php echo esc_attr($key); ?>" selected="selected">
														<?php echo esc_html($value); ?>
													</option>
												<?php } else { ?>
													<option value="<?php echo esc_attr($key); ?>">
														<?php echo esc_html($value); ?>
													</option>
												<?php } ?>
											<?php } else { ?>
												<?php if ($key == 'date_high') { ?>
													<option value="<?php echo esc_attr($key); ?>" selected="selected">
														<?php echo esc_html($value); ?>
													</option>
												<?php } else { ?>
													<option value="<?php echo esc_attr($key); ?>">
														<?php echo esc_html($value); ?>
													</option>
												<?php } ?>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="bt-product-layout <?php echo ($column_product == 4) ? 'column-4' : ''; ?>">
					<span class="bt-loading-wave"></span>
						<?php

						if ($wp_query->have_posts()) {
							woocommerce_product_loop_start();
						?>
							
							<?php
							while ($wp_query->have_posts()) {
								$wp_query->the_post();
								wc_get_template_part('content', 'product');
							}
							woocommerce_product_loop_end();

							?>

							<div class="bt-product-pagination-wrap">
								<?php echo utenzo_product_pagination($current_page, $total_page); ?>
							</div>
						<?php
						} else {
							echo '<h3 class="not-found-post">' . esc_html__('Sorry, No products found', 'utenzo') . '</h3>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
get_footer('shop');
