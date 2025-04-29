<?php
/*
 * Single Service
 */

get_header();
get_template_part('framework/templates/site', 'titlebar');
$post_id = get_the_ID();
if (function_exists('get_field')) {
	$top_service = get_field('top_services', 'options');
	$why_choose_us = get_field('why_choose_us', 'options');
	$template_section = get_field('template_section', 'options');
}
?>
<main id="bt_main" class="bt-site-main">
	<div class="bt-main-content-ss">
		<div class="bt-container">
			<?php while (have_posts()) : the_post(); ?>
				<div class="bt-main-post-row">
					<div class="bt-sidebar-col">
						<div class="bt-sidebar-wrap">

							<div class="bt-sidebar-block bt-top-service-block">

								<ul class="bt-service-list">
									<?php
									$args = array(
										'post_type' => 'service',
										'posts_per_page' => -1,
										'posts_per_page'  => !empty($top_service['number_posts']) ? $top_service['number_posts'] : -1,
									);
									$popular_services = $top_service['popular_service'];
									if ($popular_services) {
										$popular_service_ids = array();
										foreach ($popular_services as $post) {
											$popular_service_ids[] = $post->ID;
										}
										$args['post__in'] = $popular_service_ids;
									}
									$query = new WP_Query($args);
									if ($query->have_posts()) {
										while ($query->have_posts()) {
											$query->the_post();
									?>
											<li class="bt-service-list--item <?php if ($post_id == get_the_ID()) {
																					echo 'active';
																				} ?>">
												<?php echo utenzo_post_title_render(); ?>
											</li>
									<?php
										}

										wp_reset_postdata();
									}
									?>
								</ul>
							</div>
							<div class="bt-sidebar-block bt-why-choose-us-block">
								<div class="bt-why-choose-us">
									<?php
									if (!empty($why_choose_us['heading'])) {
										echo '<h3 class="bt-why-choose-us--head">' . $why_choose_us['heading'] . '</h3>';
									}
									?>
									<ul class="bt-why-choose-us--list">
										<?php
										if (!empty($why_choose_us['list_choose_us'])) {
											foreach ($why_choose_us['list_choose_us'] as $item) { ?>
												<li class="bt-why-choose-us--inner">
													<?php
													if (!empty($item['icon']['url'])) {
														echo '<img src=' . $item['icon']['url'] . ' />';
													}
													?>
													<div class="bt-title"><?php echo esc_html($item['title']) ?></div>
												</li>
										<?php }
										}
										?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="bt-main-post-col">
						<div class="bt-post ">
							<?php echo utenzo_single_post_title_render(); ?>
							<div class="bt-post--content">
								<?php the_content(); ?>
							</div>
						</div>
					</div>


				</div>
			<?php endwhile; ?>
		</div>
	</div>
	<?php
	if (!empty($template_section['template_section_bottom_service'])) {
		foreach ($template_section['template_section_bottom_service'] as $key => $item) {
			$id_template = $item->ID;
			echo do_shortcode('[elementor-template id="' . esc_attr($id_template) . '"]');
		}
	}
	?>
</main><!-- #main -->

<?php get_footer(); ?>