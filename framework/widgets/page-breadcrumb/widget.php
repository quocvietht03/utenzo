<?php

namespace UtenzoElementorWidgets\Widgets\PageBreadcrumb;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_PageBreadcrumb extends Widget_Base
{

	public function get_name()
	{
		return 'bt-page-breadcrumb';
	}

	public function get_title()
	{
		return __('Page Breadcrumb', 'utenzo');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['utenzo'];
	}

	protected function register_content_section_controls() {}

	protected function register_style_content_section_controls()
	{

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Content', 'utenzo'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __('Icon Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-page-breadcrumb svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __('Text Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-page-breadcrumb' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label' => __('Text Color Hover', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-page-breadcrumb' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __('Text Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-page-breadcrumb',
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_content_section_controls();
		$this->register_style_content_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

?>
		<div class="bt-elwg-page-breadcrumb">
			<div class="bt-page-breadcrumb">
				<?php
				$home_text = esc_html__('Home', 'utenzo');
				$delimiter = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" viewBox="0 0 13 12" fill="none">
<path opacity="0.5" d="M4.12922 10.3724C3.97259 10.2178 3.95835 9.97591 4.0865 9.80543L4.12922 9.75658L7.93471 6L4.12922 2.24342C3.97259 2.08881 3.95835 1.84688 4.0865 1.67639L4.12922 1.62755C4.28584 1.47294 4.53094 1.45889 4.70365 1.58539L4.75314 1.62755L8.87078 5.69207C9.02741 5.84667 9.04165 6.08861 8.9135 6.25909L8.87078 6.30793L4.75314 10.3724C4.58085 10.5425 4.30151 10.5425 4.12922 10.3724Z" fill="#212121"/>
</svg>';
				echo utenzo_page_breadcrumb($home_text, $delimiter);
				?>
			</div>
		</div>
<?php
	}

	protected function content_template() {}
}
