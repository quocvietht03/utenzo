<?php
namespace UtenzoElementorWidgets\Widgets\ServiceLoopItem;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ServiceLoopItem extends Widget_Base {


	public function get_name() {
		return 'bt-service-loop-item';
	}

	public function get_title() {
		return __( 'Service Loop Item', 'utenzo' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'utenzo' ];
	}

	protected function register_style_section_controls() {
		$this->start_controls_section(
			'section_style_box',
			[
				'label' => esc_html__( 'Box', 'utenzo' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'img_border_radius',
			[
				'label' => __( 'Border Radius', 'utenzo' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bt-post--inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'background_box',[
				'label'     => esc_html__( 'Background Box', 'utenzo' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--inner' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'image_style',[
				'label' => esc_html__( 'Image', 'utenzo' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'overlay_image',[
				'label'     => esc_html__( 'Overlay Image Color', 'utenzo' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--image svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab( 'thumbnail_tab_normal',
			[
				'label' => __( 'Normal', 'utenzo' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),[
				'name' => 'thumbnail_filters',
				'selector' => '{{WRAPPER}} .bt-post--image img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'thumbnail_tab_hover',[
				'label' => __( 'Hover', 'utenzo' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),[
				'name'     => 'thumbnail_hover_filters',
				'selector' => '{{WRAPPER}} .bt-post:hover .bt-post--image img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',[
				'label' => esc_html__( 'Content', 'utenzo' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'title_style',[
				'label' => esc_html__( 'Title', 'utenzo' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_color',[
				'label'     => esc_html__( 'Color', 'utenzo' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color_hover',[
				'label'     => esc_html__( 'Color Hover', 'utenzo' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bt-post--title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'utenzo' ),
				'default'  => '',
				'selector' => '{{WRAPPER}} .bt-post--title',
			]
		);

		$this->add_control(
			'service_types_style',[
				'label' => esc_html__( 'Service Types', 'utenzo' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'service_types_icon_color',[
				'label'     => esc_html__( 'Icon Color', 'utenzo' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bt-type-item svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'service_types_color',[
				'label'     => esc_html__( 'Color', 'utenzo' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bt-type-item' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'service_types_typography',
				'label'    => esc_html__( 'Typography', 'utenzo' ),
				'default'  => '',
				'selector' => '{{WRAPPER}} .bt-type-item',
			]
		);


		$this->end_controls_section();

	}

	protected function register_controls() {
		$this->register_style_section_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
			<div class="bt-elwg-service-loop-item--default bt-image-effect">
				<?php get_template_part( 'framework/templates/service', 'style'); ?>
	    	</div>
		<?php
	}

	protected function content_template() {

	}
}
