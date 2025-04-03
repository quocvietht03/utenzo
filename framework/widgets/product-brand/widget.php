<?php

namespace UtenzoElementorWidgets\Widgets\ProductBrand;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductBrand extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-brand';
	}

	public function get_title()
	{
		return __('Product Brand', 'utenzo');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['utenzo'];
	}

	public function get_script_depends()
	{
		return ['elementor-widgets'];
	}

	public function get_supported_taxonomies()
	{
		$supported_taxonomies = [];

		$brands = get_terms(array(
			'taxonomy' => 'product_brand',
			'hide_empty' => false,
		));
		if (!empty($brands)  && !is_wp_error($brands)) {
			foreach ($brands as $brand) {
				$supported_taxonomies[$brand->term_id] = $brand->name;
			}
		}

		return $supported_taxonomies;
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
			'show_empty_brands',
			[
				'label' => __('Show Empty Brands', 'utenzo'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Yes', 'utenzo'),
				'label_off' => __('No', 'utenzo'),
				'default' => 'no',
				'description' => __('Show brands that have no products.', 'utenzo'),
			]
		);
		$this->add_control(
			'brand_number',
			[
				'label' => __('Brand Number', 'utenzo'),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
			]
		);
		$this->add_responsive_control(
			'columns',
			[
				'label' => __('Columns', 'utenzo'),
				'type' => Controls_Manager::SELECT,
				'default' => '5',
				'options' => [
					'1' => __('1', 'utenzo'),
					'2' => __('2', 'utenzo'),
					'3' => __('3', 'utenzo'),
					'4' => __('4', 'utenzo'),
					'5' => __('5', 'utenzo'),
					'6' => __('6', 'utenzo'),
				],
				'prefix_class' => 'elementor-grid%s-',
				'selectors' => [
					'{{WRAPPER}} .bt-product-brand' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);
		$this->add_responsive_control(
			'gap',
			[
				'label' => __('Gap', 'utenzo'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-product-brand' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
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
			'brand',
			[
				'label' => __('Brands', 'utenzo'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_taxonomies(),
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_query_exclude',
			[
				'label' => __('Exclude', 'utenzo'),
			]
		);

		$this->add_control(
			'brand_exclude',
			[
				'label' => __('Brands', 'utenzo'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_supported_taxonomies(),
				'label_block' => true,
				'multiple' => true,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_section_controls()
	{
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Content', 'utenzo'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'svg_color',
			[
				'label' => __('SVG Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-product-brand--item svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .bt-product-brand--item svg path' => 'fill: {{VALUE}};',
				],
				'description' => __('Set color for SVG icons', 'utenzo'),
			]
		);

		$this->add_control(
			'svg_hover_color',
			[
				'label' => __('SVG Hover Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-product-brand--item:hover svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .bt-product-brand--item:hover svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_background',
			[
				'label' => __('Item Background', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '#f6f6f6',
				'selectors' => [
					'{{WRAPPER}} .bt-product-brand--item' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'hover_background',
			[
				'label' => __('Hover Background', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-product-brand--item:hover' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'item_border_radius',
			[
				'label' => __('Border Radius', 'utenzo'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-product-brand--item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .bt-product-brand--item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .bt-product-brand--item',
			]
		);



		$this->end_controls_section();
	}
	protected function register_controls()
	{
		$this->register_layout_section_controls();
		$this->register_query_section_controls();
		$this->register_style_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$args = array(
			'taxonomy' => 'product_brand',
			'hide_empty' => $settings['show_empty_brands'] !== 'yes',
			'number' => $settings['brand_number'],
			'exclude' => !empty($settings['brand_exclude']) ? $settings['brand_exclude'] : array(),
			'include' => !empty($settings['brand']) ? $settings['brand'] : array()
		);

		$brands = get_terms($args);

		if (!empty($brands) && !is_wp_error($brands)) {
?>
			<div class="bt-elwg-product-brand--default">
				<div class="bt-product-brand">
					<?php
					foreach ($brands as $brand) {
						$thumbnail_id = get_term_meta($brand->term_id, 'thumbnail_id', true);
						$image = wp_get_attachment_url($thumbnail_id);
						$brand_url = get_term_link($brand->term_id, 'product_brand');
						$is_svg = false;
						
						// Check if the image is SVG
						if ($image && pathinfo($image, PATHINFO_EXTENSION) === 'svg') {
							$is_svg = true;
						}
					?>
						<div class="bt-product-brand--item">
							<a href="<?php echo esc_url($brand_url); ?>" class="bt-product-brand--link">
								<?php if ($image) : ?>
									<div class="bt-product-brand--image">
										<?php if ($is_svg) : 
											// Output SVG content
											$svg_content = file_get_contents($image);
											echo $svg_content;
										else : ?>
											<img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($brand->name); ?>">
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</a>
						</div>
					<?php
					}
					?>
				</div>
			</div>

<?php
		} else {
			get_template_part('framework/templates/post', 'none');
		}
	}

	protected function content_template() {}
}
