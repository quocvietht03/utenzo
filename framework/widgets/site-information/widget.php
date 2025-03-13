<?php

namespace UtenzoElementorWidgets\Widgets\SiteInformation;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Widget_SiteInformation extends Widget_Base
{

	public function get_name()
	{
		return 'bt-site-information';
	}

	public function get_title()
	{
		return __('Site Information', 'utenzo');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['utenzo'];
	}

	protected function register_content_section_controls()
	{
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Content', 'utenzo'),
			]
		);

		$this->add_control(
			'list',
			[
				'label' => esc_html__('Show Elements', 'utenzo'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => [
					'phone'  => esc_html__('Phone', 'utenzo'),
					'email' => esc_html__('Email', 'utenzo'),
					'address' => esc_html__('Address', 'utenzo'),
					'time' => esc_html__('Opening Times', 'utenzo'),
				],
				'default' => ['phone', 'email'],
			]
		);

		$this->end_controls_section();
	}

	protected function register_layout_section_controls()
	{
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'utenzo'),
			]
		);

		$this->add_control(
			'style',
			[
				'label' => esc_html__('Layout Style', 'utenzo'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'column' => [
						'title' => esc_html__('Block', 'utenzo'),
						'icon' => 'eicon-editor-list-ul',
					],
					'row' => [
						'title' => esc_html__('Inline', 'utenzo'),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'default' => 'row',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-site-infor' => 'flex-direction: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => __('Space Between', 'utenzo'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-site-infor' => 'column-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .bt-elwg-site-infor--item:not(:last-child)::before' => 'right: calc( ({{SIZE}}{{UNIT}} / 2) * -1 )',
				],

				'condition' => [
					'style' => 'row',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label' => __('Space Between', 'utenzo'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 4,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-site-infor' => 'row-gap: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'style' => 'column',
				],
			]
		);

		$this->add_responsive_control(
			'separator',
			[
				'label'    => __('Separator', 'utenzo'),
				'type'     => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'utenzo'),
				'label_off' => __('Hide', 'utenzo'),
				'default'  => '',
				'condition' => [
					'style' => 'row',
				],
			]
		);

		$this->end_controls_section();
	}

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
			'icon_show',
			[
				'label' => __('Icon Show', 'utenzo'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'utenzo'),
				'label_off' => __('Hide', 'utenzo'),
				'default' => 'yes',
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label' => __('Icon Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-site-infor--item svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-site-infor--item svg path' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'icon_show' => 'yes',
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
					'{{WRAPPER}} .bt-elwg-site-infor--item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label' => __('Color Hover', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-site-infor--item a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __('Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-elwg-site-infor--item',
			]
		);

		$this->add_control(
			'separator_style',
			[
				'label' => __('Separator', 'utenzo'),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => __('Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-site-infor--item:not(:last-child)::before' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'separator_width',
			[
				'label' => __('Width', 'utenzo'),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => ['px',],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-site-infor--item:not(:last-child)::before' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'separator' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_content_section_controls();
		$this->register_layout_section_controls();
		$this->register_style_content_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		if (isset($settings['separator']) && $settings['separator'] == 'yes') {
			$separator = 'separator';
		} else {
			$separator = '';
		}

		if (isset($settings['separator_tablet']) && $settings['separator_tablet'] == 'yes') {
			$separator_tb = '';
		} else {
			$separator_tb = 'separator-tb-hide';
		}

		if (isset($settings['separator_mobile']) && $settings['separator_mobile'] == 'yes') {
			$separator_mb = '';
		} else {
			$separator_mb = 'separator-mb-hide';
		}

		$classes  = implode(' ', [$separator, $separator_tb, $separator_mb]);


		if (empty($settings['list'])) {
			return;
		}
?>

		<div class="bt-elwg-site-infor bt-elwg-site-infor--default <?php echo esc_attr($classes);
																	if ($settings['icon_show'] != 'yes') {
																		echo ' bt-no-show-icon';
																	} ?>">
			<?php get_template_part('framework/templates/site-information', 'style', array('layout' => 'default', 'data' => $settings['list'])); ?>
		</div>

<?php }

	protected function content_template() {}
}
