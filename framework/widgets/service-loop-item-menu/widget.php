<?php

namespace UtenzoElementorWidgets\Widgets\ServiceLoopItemMenu;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ServiceLoopItemMenu extends Widget_Base
{


	public function get_name()
	{
		return 'bt-service-loop-item-menu';
	}

	public function get_title()
	{
		return __('Service Loop Item - Menu', 'utenzo');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['utenzo'];
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
					'{{WRAPPER}} .bt-post--title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .bt-post--title:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__('Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-post--title',
			]
		);

		$this->add_control(
			'service_excerpt',
			[
				'label' => esc_html__('Service Excerpt', 'utenzo'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'service_excerpt_color',
			[
				'label' => esc_html__('Excerpt Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-post--excerpt' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'service_excerpt_typography',
				'label' => esc_html__('Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-post--excerpt',
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_style_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
?>
		<div class="bt-elwg-service-loop-item--menu bt-image-effect">
			<article <?php post_class('bt-post'); ?>>
				<div class="bt-post--inner">
					<?php echo utenzo_post_title_render(); ?>
					<div class="bt-post--excerpt">
						<?php echo get_the_excerpt(); ?>
					</div>
				</div>
			</article>
		</div>
<?php
	}

	protected function content_template() {}
}
