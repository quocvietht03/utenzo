<?php

namespace UtenzoElementorWidgets\Widgets\ServicesList;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ServicesList extends Widget_Base
{

	public function get_name()
	{
		return 'bt-services-list';
	}

	public function get_title()
	{
		return __('Services List', 'utenzo');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['utenzo'];
	}

	protected function get_supported_ids()
	{
		$supported_ids = [];

		$wp_query = new \WP_Query(array(
			'post_type' => 'service',
			'post_status' => 'publish',
			'posts_per_page' => -1
		));

		if ($wp_query->have_posts()) {
			while ($wp_query->have_posts()) {
				$wp_query->the_post();
				$supported_ids[get_the_ID()] = get_the_title();
			}
		}

		return $supported_ids;
	}

	public function get_supported_taxonomies()
	{
		$supported_taxonomies = [];

		$categories = get_terms(array(
			'taxonomy' => 'service_categories',
			'hide_empty' => false,
		));
		if (!empty($categories)  && !is_wp_error($categories)) {
			foreach ($categories as $category) {
				$supported_taxonomies[$category->term_id] = $category->name;
			}
		}

		return $supported_taxonomies;
	}

	protected function register_query_section_controls()
	{
		$this->start_controls_section(
			'section_query',
			[
				'label' => __('Query', 'utenzo'),
			]
		);

		$this->start_controls_tabs('tabs_query');

		$this->start_controls_tab(
			'tab_query_include',
			[
				'label' => __('Include', 'utenzo'),
			]
		);

		$this->add_control(
			'ids',
			[
				'label' => __('Ids', 'utenzo'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_ids(),
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->add_control(
			'category',
			[
				'label' => __('Category', 'utenzo'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_taxonomies(),
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->end_controls_tab();


		$this->start_controls_tab(
			'tab_query_exnlude',
			[
				'label' => __('Exclude', 'utenzo'),
			]
		);

		$this->add_control(
			'ids_exclude',
			[
				'label' => __('Ids', 'utenzo'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_ids(),
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->add_control(
			'category_exclude',
			[
				'label' => __('Category', 'utenzo'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_taxonomies(),
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->add_control(
			'offset',
			[
				'label' => __('Offset', 'utenzo'),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'description' => __('Use this setting to skip over posts (e.g. \'2\' to skip over 2 posts).', 'utenzo'),
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'orderby',
			[
				'label' => __('Order By', 'utenzo'),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => [
					'post_date' => __('Date', 'utenzo'),
					'post_title' => __('Title', 'utenzo'),
					'menu_order' => __('Menu Order', 'utenzo'),
					'rand' => __('Random', 'utenzo'),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __('Order', 'utenzo'),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __('ASC', 'utenzo'),
					'desc' => __('DESC', 'utenzo'),
				],
			]
		);
		$this->add_control(
			'posts_per_page',
			[
				'label' => __('Posts Per Page', 'utenzo'),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);
		$this->add_control(
			'show_pagination',
			[
				'label' => __('Pagination', 'utenzo'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'utenzo'),
				'label_off' => __('Hide', 'utenzo'),
				'default' => '',
			]
		);
		$this->end_controls_section();
	}

	protected function register_style_section_controls()
	{

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Items', 'utenzo'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'section_ratio',
			[
				'label' => __('Space Between', 'utenzo'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-service-list-item' => 'margin-bottom: {{SIZE}}px;',
				],
			]
		);
		$this->add_control(
			'section_bg_color',
			[
				'label' => __('Background Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-service-list .bt-service-list-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Image Style
		$this->add_control(
			'img_style',
			[
				'label' => __('Image', 'utenzo'),
				'type' => Controls_Manager::HEADING,
			]
		);
		// Title Style
		$this->add_control(
			'title_style',
			[
				'label' => __('Title', 'utenzo'),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => __('Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_color_hover',
			[
				'label' => __('Color Hover', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __('Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-post--title',
			]
		);

		// Content Style
		$this->add_control(
			'content_style',
			[
				'label' => __('Content', 'utenzo'),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'content_color',
			[
				'label' => __('Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-service-types .bt-type-item' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'content_bg_color',
			[
				'label' => __('Background Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--content' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => __('Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-service-types .bt-type-item',
			]
		);

		// Button Style
		$this->add_control(
			'button_style',
			[
				'label' => __('Button', 'utenzo'),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'button_color',
			[
				'label' => __('Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--button-booknow a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_bg_color',
			[
				'label' => __('Background Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--button-booknow a' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __('Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-post--button-booknow a',
			]
		);
		$this->add_control(
			'button_icon_size',
			[
				'label' => esc_html__('Icon Size', 'utenzo'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-post--button-booknow a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__('Pagination', 'utenzo'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label' => __('Pagination Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-pagination .page-numbers:not(.current)' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-pagination .page-numbers:not(.current) svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_color_hover',
			[
				'label' => __('Pagination Hover Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-pagination .page-numbers:not(.current):hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-pagination .page-numbers:not(.current):hover svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_color_current',
			[
				'label' => __('Color Current', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-pagination .page-numbers.current' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'label' => __('Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-pagination .page-numbers',
			]
		);

		$this->add_responsive_control(
			'pagination_space',
			[
				'label' => __('Space', 'utenzo'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 60,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_query_section_controls();

		$this->register_style_section_controls();
	}

	public function query_posts()
	{
		$settings = $this->get_settings_for_display();

		$args = [
			'post_type' => 'service',
			'post_status' => 'publish',
			'posts_per_page' => $settings['posts_per_page'],
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
		];
		if ($settings['show_pagination'] == 'yes') {
			$args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}

		if (!empty($settings['ids'])) {
			$args['post__in'] = $settings['ids'];
		}

		if (!empty($settings['ids_exclude'])) {
			$args['post__not_in'] = $settings['ids_exclude'];
		}

		if (!empty($settings['category'])) {
			$args['tax_query'] = array(
				array(
					'taxonomy' 		=> 'service_categories',
					'terms' 		=> $settings['category'],
					'field' 		=> 'term_id',
					'operator' 		=> 'IN'
				)
			);
		}

		if (!empty($settings['category_exclude'])) {
			$args['tax_query'] = array(
				array(
					'taxonomy' 		=> 'service_categories',
					'terms' 		=> $settings['category_exclude'],
					'field' 		=> 'term_id',
					'operator' 		=> 'NOT IN'
				)
			);
		}

		if (0 !== absint($settings['offset'])) {
			$args['offset'] = $settings['offset'];
		}

		return $query = new \WP_Query($args);
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$query = $this->query_posts();
		$placeholder_img_url = \Elementor\Utils::get_placeholder_image_src();

?>
		<div class="bt-elwg-service-list">
			<?php
			if ($query->have_posts()) {
			?>
				<div class="bt-service-list">
					<?php
					while ($query->have_posts()) : $query->the_post();
						$img_1 = get_field('image_template_1', get_the_ID());
						$img_1_url = !empty($img_1['url']) ? $img_1['url'] : $placeholder_img_url;
						$service_types = get_field('service_types', get_the_ID());
					?>
						<div class="bt-service-list-item bubble-container">
							<a href="<?php the_permalink(); ?>" class="bt-post--img">
								<img src="<?php echo esc_url($img_1_url) ?>" alt="">
							</a>
							<div class="bt-post--content">
								<?php echo utenzo_post_title_render(); ?>
								<?php if (!empty($service_types)): ?>
									<ul class="bt-service-types">
										<?php foreach ($service_types as $type): ?>
											<li class="bt-type-item"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M21.0573 7.00193C21.4381 7.40195 21.4225 8.03492 21.0225 8.41571L9.46695 19.4157C9.26961 19.6036 9.00417 19.7028 8.732 19.6904C8.45984 19.678 8.2045 19.5551 8.02505 19.3501L3.5806 14.2732C3.21682 13.8576 3.25879 13.2258 3.67434 12.8621C4.08989 12.4983 4.72166 12.5403 5.08544 12.9558L8.84314 17.2483L19.6435 6.9671C20.0436 6.58631 20.6765 6.60191 21.0573 7.00193Z" fill="#2D77DC" />
												</svg><?php echo esc_html($type['name']) ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>

							<!-- Small, medium, and large bubbles -->
							<?php
							for ($i = 1; $i <= 10; $i++):
								switch ($i) {
									case 1:
									case 4:
									case 7:
										$bubble_size = 'small';
										break;
									case 2:
									case 5:
									case 8:
										$bubble_size = 'large';
										break;
									default:
										$bubble_size = 'medium';
										break;
								}
							?>
								<img class="bubble <?php echo esc_attr($bubble_size) ?>" src="<?php echo CLEANIRA_IMG_DIR . 'img-bubble-white.png'; ?>" alt="">
							<?php endfor; ?>
						</div>
					<?php
					endwhile;
					?>
				</div>
			<?php
				if ($settings['show_pagination'] == 'yes') {
					utenzo_paginate_links($query);
				}
			} else {
				get_template_part('framework/templates/post', 'none');
			}
			?>
		</div>
<?php
		wp_reset_postdata();
	}

	protected function content_template() {}
}
