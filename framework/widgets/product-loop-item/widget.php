<?php

namespace UtenzoElementorWidgets\Widgets\ProductLoopItem;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductLoopItem extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-loop-item';
	}

	public function get_title()
	{
		return __('Product Loop Item', 'utenzo');
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
			'section_layout',
			[
				'label' => __('Layout', 'utenzo'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout_style',
			[
				'label' => __('Layout Style', 'utenzo'),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __('Default', 'utenzo'),
					'layout-1' => __('Layout 1', 'utenzo'),
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_section_controls() {}

	protected function register_controls()
	{
		$this->register_layout_section_controls();
		$this->register_style_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		global $product;

		if (empty($product) || ! $product->is_visible()) {
			return;
		}
?>
		<div class="bt-elwg-product-loop-item <?php echo esc_attr($settings['layout_style']); ?>">
			<?php wc_get_template_part('content', 'product'); ?>
		</div>
<?php
	}

	protected function content_template() {}
}
