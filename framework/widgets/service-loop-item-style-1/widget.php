<?php

namespace UtenzoElementorWidgets\Widgets\ServiceLoopItemStyle1;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ServiceLoopItemStyle1 extends Widget_Base
{


	public function get_name()
	{
		return 'bt-service-loop-item-style-1';
	}

	public function get_title()
	{
		return __('Service Loop Item - Style 1', 'utenzo');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['utenzo'];
	}

	protected function register_layout_section_controls()
	{
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Content', 'utenzo'),
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __('Icon Size', 'utenzo'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 80,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 170,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-post--content .bt-post--icon' => 'height: {{SIZE}}px;',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function register_style_section_controls()
	{

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__('Box', 'utenzo'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __('Border Radius', 'utenzo'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'show_img_overlay',
			[
				'label' => esc_html__('Show Image Overlay', 'utenzo'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'utenzo'),
				'label_off' => esc_html__('Hide', 'utenzo'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'color_overlay',
			[
				'label' => esc_html__('Color Overlay Image', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '#C1DCFB',
				'selectors' => [
					'{{WRAPPER}} .bt-post .bt-image-overlay path' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'show_img_overlay' => 'yes',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Content', 'utenzo'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'background_content',
			[
				'label' => esc_html__('Background Content', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .bt-post' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_style',
			[
				'label' => esc_html__('Title', 'utenzo'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--content .bt-post--title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__('Color Hover', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--content .bt-post--title:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__('Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-post--content .bt-post--title',
			]
		);

		$this->add_control(
			'service_type_style',
			[
				'label' => esc_html__('Service Types', 'utenzo'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'service_type_icon_color',
			[
				'label' => esc_html__('Icon Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '#2D77DC',
				'selectors' => [
					'{{WRAPPER}} .bt-post--service-item .bt-icon-service path' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'service_type_color',
			[
				'label' => esc_html__('Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '#2D77DC',
				'selectors' => [
					'{{WRAPPER}} .bt-post--service-item' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'service_type_typography',
				'label' => esc_html__('Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-post--services-type .bt-post--service-item',
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_layout_section_controls();
		$this->register_style_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$post_id = get_the_ID();

		$image_template_3 = get_field('image_template_3', $post_id);
		$service_types = get_field('service_types', $post_id);
?>
		<div class="bt-elwg-service-loop-item--style-1 bt-image-effect">
			<article <?php post_class('bt-post'); ?>>
				<?php if ($settings['show_img_overlay']) { ?>
					<svg class="bt-image-overlay" xmlns="http://www.w3.org/2000/svg" width="195" height="138" viewBox="0 0 195 138" fill="none">
						<path
							d="M194.198 0.710938H0.194824C3.32545 16.8914 14.6147 30.7184 27.9909 39.2499C35.4855 43.957 43.8338 47.2912 52.3718 49.2524C60.9099 51.3118 70.0172 51.1156 78.2706 54.0575C83.7729 56.0188 87.1881 59.451 90.6034 64.1581C93.0699 67.4923 95.6313 70.238 99.2363 72.1993C106.731 76.2199 112.043 75.4354 122.669 74.4548C132.63 73.4741 143.16 76.5141 152.172 81.4173C160.331 85.8302 165.738 91.6159 170.387 99.8533C175.51 109.071 175.794 120.054 181.961 128.684C186.23 134.568 189.74 136.823 194.293 138V0.710938H194.198Z"
							fill="#C1DCFB" />
					</svg>
				<?php } ?>

				<div class="bt-post--inner">
					<div class="bt-post--infor">
						<div class="bt-post--left">
							<div class="bt-post--content">
								<?php
								if (!empty($image_template_3)) {
									echo '<a href="' . get_the_permalink($post_id) . '">';
									echo '<img class="bt-post--icon" src="' . $image_template_3['url'] . '" />';
									echo '</a>';
								}
								?>
								<?php echo utenzo_post_title_render(); ?>
							</div>
						</div>

						<?php if (!empty($service_types)) { ?>
							<div class="bt-post--right">
								<div class="bt-post--services-type">
									<?php foreach ($service_types as $key => $service) {
										echo '<div class="bt-post--service-item">
										<svg class="bt-icon-service" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M21.0168 6.31052C21.3976 6.71054 21.382 7.34352 20.982 7.72431L9.42642 18.7243C9.22908 18.9122 8.96364 19.0114 8.69148 18.999C8.41931 18.9866 8.16398 18.8637 7.98452 18.6587L3.54007 13.5818C3.17629 13.1662 3.21826 12.5344 3.63381 12.1707C4.04936 11.8069 4.68113 11.8488 5.04491 12.2644L8.80262 16.5568L19.603 6.2757C20.003 5.89491 20.636 5.9105 21.0168 6.31052Z" fill="#2D77DC"/>
										</svg>
										' . $service['name'] . '
										</div>';
									} ?>
								</div>
							</div>
						<?php } ?>

					</div>

				</div>
			</article>
		</div>
<?php
	}

	protected function content_template() {}
}
