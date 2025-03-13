<?php

namespace UtenzoElementorWidgets\Widgets\BrandSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_BrandSlider extends Widget_Base
{

    public function get_name()
    {
        return 'bt-brand-slider';
    }

    public function get_title()
    {
        return __('Brand Slider', 'utenzo');
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
        return ['swiper-slider', 'elementor-widgets'];
    }

    protected function register_layout_section_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'utenzo'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'brand_image',
            [
                'label' => __('Image', 'utenzo'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'brand_image_width',
            [
                'label' => __('Image Width', 'utenzo'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 150,
                        'step' => 1,
                    ],
                ],
            ]
        );
        $repeater->add_control(
            'brand_button_url',
            [
                'label' => esc_html__('Button Url', 'utenzo'),
                'type' => Controls_Manager::URL,
                'options' => ['url', 'is_external', 'nofollow'],
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                    'custom_attributes' => '',
                ],
                'label_block' => true,
            ]
        );


        $this->add_control(
            'list',
            [
                'label' => __('List Brands', 'utenzo'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'brand_image' => Utils::get_placeholder_image_src(),
                        'brand_button_url' => '#'
                    ],
                    [
                        'brand_image' => Utils::get_placeholder_image_src(),
                        'brand_button_url' => '#'
                    ],
                    [
                        'brand_image' => Utils::get_placeholder_image_src(),
                        'brand_button_url' => '#'
                    ],
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'label' => __('Image Size', 'utenzo'),
                'show_label' => true,
                'default' => 'medium_large',
                'exclude' => ['custom'],
            ]
        );
        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {

        $this->start_controls_section(
            'section_style_box',
            [
                'label' => esc_html__('Box', 'utenzo'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_border_width',
            [
                'label' => __('Border Width', 'utenzo'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-brand--inner' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label' => __('Border Radius', 'utenzo'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-brand--inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => __('Padding', 'utenzo'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-brand--inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .bt-brand--inner',
            ]
        );

        $this->add_control(
            'box_bg_color',
            [
                'label' => __('Background Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-brand--inner' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'box_border_color',
            [
                'label' => __('Border Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-brand--inner' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_slider',
            [
                'label' => esc_html__('Slider', 'utenzo'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'slider_auto',
            [
                'label' => __('Slider Item Auto', 'utenzo'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'utenzo'),
                'label_off' => __('No', 'utenzo'),
                'default' => 'no',
            ]
        );
        $this->add_responsive_control(
            'slider_item',
            [
                'label' => __('Slider Item', 'utenzo'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'condition' => [
                    'slider_auto!' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'slider_speed',
            [
                'label' => __('Slider Speed', 'utenzo'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
            ]
        );
        $this->add_control(
            'slider_spacebetween',
            [
                'label' => __('Slider SpaceBetween', 'utenzo'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
            ]
        );
        $this->add_control(
            'slider_blur',
            [
                'label' => __('Slider Blur', 'utenzo'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'utenzo'),
                'label_off' => __('Hide', 'utenzo'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'slider_blur_background',
            [
                'label' => __('Background Blur', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-brand-slider--default::before' => 'background: linear-gradient(to right, {{VALUE}} 0%, rgba(255, 255, 255, 0) 100%);',
                    '{{WRAPPER}} .bt-elwg-brand-slider--default::after' => 'background: linear-gradient(to right, {{VALUE}} 0%, rgba(255, 255, 255, 0) 100%);',
                ],
                'condition' => [
                    'slider_blur' => 'yes',
                ],
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
        if (empty($settings['list'])) {
            return;
        }
        if ($settings['slider_auto'] === 'yes') {
            $slider_item_desktop = 'auto';
            $slider_item_tablet = 'auto';
            $slider_item_mobile = 'auto';
        } else {
            $slider_item_desktop = $settings['slider_item']['size'] ?? $settings['slider_item'];
            $slider_item_tablet = $settings['slider_item_tablet'] ?? 4;
            $slider_item_mobile = $settings['slider_item_mobile'] ?? 2;
        }
        $slider_speed = $settings['slider_speed'];
        $slider_space_between = $settings['slider_spacebetween'];
        $slider_blur = $settings['slider_blur'] === 'yes' ? true : false;
?>
        <div class="bt-elwg-brand-slider--default swiper <?php if ($slider_blur) {
                                                                echo 'bt-slider-blur';
                                                            } ?>" data-item="<?php echo esc_attr($slider_item_desktop) ?>" data-item-tablet="<?php echo !empty($slider_item_tablet) ? $slider_item_tablet : 4; ?>" data-item-mobile="<?php echo !empty($slider_item_mobile) ? $slider_item_mobile : 2; ?>" data-speed="<?php echo esc_attr($slider_speed) ?>" data-spacebetween="<?php echo esc_attr($slider_space_between) ?>">
            <ul class="bt-brand-slider swiper-wrapper">
                <?php
                foreach ($settings['list'] as $index => $item) {
                    $item_key = 'brand_button_url_' . $index;
                    if (!empty($item['brand_button_url']['url'])) {
                        $this->add_link_attributes($item_key, $item['brand_button_url']);
                    }
                ?>
                    <li class="bt-brand--item swiper-slide">
                        <a class="bt-brand--image" <?php echo wp_kses_post($this->get_render_attribute_string($item_key)) ?>>
                            <div class="bt-brand--inner" <?php echo (!empty($item['brand_image_width']['size']) ? 'style="width:' . esc_attr($item['brand_image_width']['size']) . 'px;"' : '') ?> >
                                <?php
                                $attachment = wp_get_attachment_image_src($item['brand_image']['id'], $settings['thumbnail_size']);
                                if (!empty($attachment)) {
                                    echo '<img src="' . esc_url($attachment[0]) . '" alt="">';
                                } else {
                                    echo '<img src="' . esc_url($item['brand_image']['url']) . '" alt="">';
                                }
                                ?>
                            </div>
                        </a>
                    </li>
                <?php }
                ?>
            </ul>
        </div>
<?php
    }

    protected function content_template() {}
}
