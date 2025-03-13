<?php

namespace UtenzoElementorWidgets\Widgets\MiniCart;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_MiniCart extends Widget_Base
{

	public function get_name()
	{
		return 'bt-mini-cart';
	}

	public function get_title()
	{
		return __('Mini Cart', 'utenzo');
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
			'cart_mini_icon',
			[
				'label' => esc_html__('Icon Cart', 'utenzo'),
				'type' => Controls_Manager::MEDIA,
				'media_types' => ['svg'],
			]
		);


		$this->end_controls_section();
	}


	protected function register_style_content_section_controls()
	{

		$this->start_controls_section(
			'section_style_content_cart',
			[
				'label' => esc_html__('Content Cart', 'utenzo'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_cart',
			[
				'label' => __('Icon', 'utenzo'),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_responsive_control(
			'icon_cart_size',
			[
				'label' => __('Icon size', 'utenzo'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart a svg ' => 'width: {{SIZE}}px;height:{{SIZE}}px;',
				],
			]
		);
		$this->add_control(
			'icon_cart_color',
			[
				'label' => __('Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart a svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'icon_cart_background',
			[
				'label' => __('Background', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart a' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'number_cart',
			[
				'label' => __('Number Cart', 'utenzo'),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'number_cart_color',
			[
				'label' => __('Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'number_cart_background',
			[
				'label' => __('Background', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart span' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'number_cart_typography',
				'label' => __('number_cart Typography', 'utenzo'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-elwg-mini-cart--default .bt-mini-cart span',
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
		$icon_cart = $settings['cart_mini_icon']['url'];

?>
		<div class="bt-elwg-mini-cart--default">
			<div class="bt-mini-cart">
				<a class="bt-toggle-btn" href="<?php echo esc_url(wc_get_cart_url()) ?>">
					<?php if (!empty($icon_cart) && 'svg' === pathinfo($icon_cart, PATHINFO_EXTENSION)) {
						echo file_get_contents($icon_cart);
					} else { ?>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<g clip-path="url(#clip0_10940_3361)">
								<path d="M20.0547 4.13867H3.55469C3.14047 4.13867 2.80469 4.47446 2.80469 4.88867V18.3887C2.80469 18.8029 3.14047 19.1387 3.55469 19.1387H20.0547C20.4689 19.1387 20.8047 18.8029 20.8047 18.3887V4.88867C20.8047 4.47446 20.4689 4.13867 20.0547 4.13867Z" stroke="#212121" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M2.80469 7.13867H20.8047" stroke="#212121" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M15.5547 10.1387C15.5547 11.1332 15.1596 12.0871 14.4563 12.7903C13.7531 13.4936 12.7992 13.8887 11.8047 13.8887C10.8101 13.8887 9.8563 13.4936 9.15304 12.7903C8.44978 12.0871 8.05469 11.1332 8.05469 10.1387" stroke="#212121" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							</g>
							<defs>
								<clipPath id="clip0_10940_3361">
									<rect width="24" height="24" fill="white" transform="translate(-0.195312 -0.361328)" />
								</clipPath>
							</defs>
						</svg>
					<?php } ?>
					<span class="cart_total"><?php echo WC()->cart->get_cart_contents_count(); ?></span></a>
			</div>
		</div>
<?php
	}

	protected function content_template() {}
}
