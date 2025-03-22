<?php

namespace UtenzoElementorWidgets\Widgets\TikTokShopSlider;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_TikTokShopSlider extends Widget_Base
{

    public function get_name()
    {
        return 'bt-tiktok-shop-slider';
    }

    public function get_title()
    {
        return __('TikTok Shop Slider', 'utenzo');
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
    protected function get_supported_ids()
    {
        $supported_ids = [];

        $wp_query = new \WP_Query(array(
            'post_type' => 'product',
            'post_status' => 'publish'
        ));

        if ($wp_query->have_posts()) {
            while ($wp_query->have_posts()) {
                $wp_query->the_post();
                $supported_ids[get_the_ID()] = get_the_title();
            }
        }

        return $supported_ids;
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
            'tiktok_image',
            [
                'label' => __('Image', 'utenzo'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $repeater->add_control(
            'id_product',
            [
                'label' => __('Select Product', 'utenzo'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_supported_ids(),
                'label_block' => true,
                'multiple' => false,
            ]
        );
        $repeater->add_control(
            'tiktok_shop_url',
            [
                'label' => esc_html__('TikTok Shop URL', 'utenzo'),
                'type' => Controls_Manager::URL,
                'options' => ['url', 'is_external', 'nofollow'],
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'label_block' => true,
                'description' => __('Enter your TikTok Shop product URL', 'utenzo'),
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => __('List Products', 'utenzo'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tiktok_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'tiktok_shop_url' => [
                            'url' => '#',
                            'is_external' => true,
                            'nofollow' => true,
                        ]
                    ],
                    [
                        'tiktok_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'tiktok_shop_url' => [
                            'url' => '#',
                            'is_external' => true,
                            'nofollow' => true,
                        ]
                    ],
                    [
                        'tiktok_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'tiktok_shop_url' => [
                            'url' => '#',
                            'is_external' => true,
                            'nofollow' => true,
                        ]
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

        $this->add_responsive_control(
            'image_ratio',
            [
                'label' => __('Image Ratio', 'utenzo'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1.3,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.3,
                        'max' => 2,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-tiktok-shop-slider .bt-cover-image' =>'padding-bottom: calc( {{SIZE}} * 100% );',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {

        $this->start_controls_section(
            'section_style_box',
            [
                'label' => esc_html__('Box Style', 'utenzo'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'box_overflow',
            [
                'label' => __('Overflow', 'utenzo'),
                'type' => Controls_Manager::SELECT,
                'default' => 'hidden',
                'options' => [
                    'visible' => __('Visible', 'utenzo'),
                    'hidden' => __('Hidden', 'utenzo'),
                    'scroll' => __('Scroll', 'utenzo'),
                    'auto' => __('Auto', 'utenzo'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-tiktok-shop-slider--default' => 'overflow: {{VALUE}}',
                ],
            ]
        );

        // Box Border
        $this->add_control(
            'box_border_color',
            [
                'label' => __('Border Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-tiktok-shop--wrap' => 'border-color: {{VALUE}}',
                ],
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
                    '{{WRAPPER}} .bt-tiktok-shop--wrap' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
                    '{{WRAPPER}} .bt-tiktok-shop--wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bt-tiktok-shop--wrap bt-tiktok-shop--product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                ],
            ]
        );

        // Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .bt-tiktok-shop--wrap',
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
            'slider_autoplay',
            [
                'label' => __('Slider Autoplay', 'utenzo'),
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
            ]
        );
        $this->add_control(
            'slider_speed',
            [
                'label' => __('Slider Speed', 'utenzo'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1000,
                'min' => 100,
                'step' => 100,
            ]
        );
        $this->add_responsive_control(
            'slider_spacebetween',
            [
                'label' => __('Slider SpaceBetween', 'utenzo'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => 30,
                'tablet_default' => 20,
                'mobile_default' => 20,
            ]
        );
        $this->add_control(
            'slider_arrows',
            [
                'label' => __('Show Arrows', 'utenzo'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'utenzo'),
                'label_off' => __('No', 'utenzo'),
                'default' => 'yes',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_arrows',
            [
                'label' => esc_html__('Navigation Arrows', 'utenzo'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_size',
            [
                'label' => __('Size', 'utenzo'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-nav svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color',
            [
                'label' => __('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav svg path' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'arrows_bg_color',
            [
                'label' => __('Background Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.5)',
                'selectors' => [
                    '{{WRAPPER}} .bt-nav' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'arrows_border_radius',
            [
                'label' => __('Border Radius', 'utenzo'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bt-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
    private function render_attributes($attrs)
    {
        $output = [];
        foreach ($attrs as $key => $value) {
            $output[] = $key . '="' . esc_attr($value) . '"';
        }
        return implode(' ', $output);
    }

    private function get_image_url($image, $size)
    {
        if (!empty($image['id'])) {
            $attachment = wp_get_attachment_image_src($image['id'], $size);
            return $attachment ? $attachment[0] : '';
        }
        return $image['url'] ?? '';
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Early return if no items
        if (empty($settings['list'])) return;

        // Extract slider settings
        $is_auto = $settings['slider_autoplay'] === 'yes';
        $slider_settings = [
            'desktop' => [
                'item' => $settings['slider_item']['size'] ?? $settings['slider_item'],
                'space' => $settings['slider_spacebetween']['size'] ?? $settings['slider_spacebetween']
            ],
            'tablet' => [
                'item' => $settings['slider_item_tablet'] ?? 4,
                'space' => $settings['slider_spacebetween_tablet'] ?? 20
            ],
            'mobile' => [
                'item' => $settings['slider_item_mobile'] ?? 2,
                'space' => $settings['slider_spacebetween_mobile'] ?? 10
            ]
        ];

        // Prepare slider attributes
        $slider_attrs = [
            'class' => 'bt-elwg-tiktok-shop-slider--default swiper',
            'data-item' => $slider_settings['desktop']['item'],
            'data-item-tablet' => $slider_settings['tablet']['item'],
            'data-item-mobile' => $slider_settings['mobile']['item'],
            'data-speed' => $settings['slider_speed'],
            'data-spacebetween' => $slider_settings['desktop']['space'],
            'data-spacebetween-tablet' => $slider_settings['tablet']['space'],
            'data-spacebetween-mobile' => $slider_settings['mobile']['space'],
            'data-autoplay' => $is_auto ? 'true' : 'false'
        ];

        // Render slider
        echo '<div ' . $this->render_attributes($slider_attrs) . '>';
        echo '<ul class="bt-tiktok-shop-slider swiper-wrapper">';

        foreach ($settings['list'] as $index => $item) {
            $item_key = 'product_button_url_' . $index;
            if (!empty($item['tiktok_shop_url']['url'])) {
                $this->add_link_attributes($item_key, $item['tiktok_shop_url']);
            }

            $image_url = $this->get_image_url($item['tiktok_image'], $settings['thumbnail_size']);
            $product = wc_get_product($item['id_product']);
            echo '<li class="bt-tiktok-shop--item swiper-slide">';
            echo '<a class="bt-tiktok-shop--wrap" ' . $this->get_render_attribute_string($item_key) . '>';
            echo '<div class="bt-cover-image">';
            if ($image_url) {
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product ? $product->get_name() : __('TikTok Shop Product', 'utenzo')) . '">';
            }
            echo '</div>';
            if ($product) {
                $product_image = $product->get_image('medium');
                $product_name = esc_html($product->get_name());
                $product_price = $product->get_price_html();
                
                echo '<div class="bt-tiktok-shop--product">';
                if ($product_image) {
                    echo '<div class="bt-product-thumb">' . $product_image . '</div>';
                }
                echo '<div class="bt-product-info">'
                    . '<span class="bt-product-name">' . $product_name . '</span>'
                    . '<span class="bt-product-price">' . $product_price . '</span>'
                    . '</div>'
                    . '</div>';
            }
            echo '</a></li>';
        }

        echo '</ul>';
        echo '<div class="bt-swiper-navigation">';
        if ($settings['slider_arrows'] === 'yes') {
            echo '<div class="bt-nav bt-button-prev">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">';
            echo '<path d="M15.5307 18.9698C15.6004 19.0395 15.6557 19.1222 15.6934 19.2132C15.7311 19.3043 15.7505 19.4019 15.7505 19.5004C15.7505 19.599 15.7311 19.6965 15.6934 19.7876C15.6557 19.8786 15.6004 19.9614 15.5307 20.031C15.461 20.1007 15.3783 20.156 15.2873 20.1937C15.1962 20.2314 15.0986 20.2508 15.0001 20.2508C14.9016 20.2508 14.804 20.2314 14.7129 20.1937C14.6219 20.156 14.5392 20.1007 14.4695 20.031L6.96948 12.531C6.89974 12.4614 6.84443 12.3787 6.80668 12.2876C6.76894 12.1966 6.74951 12.099 6.74951 12.0004C6.74951 11.9019 6.76894 11.8043 6.80668 11.7132C6.84443 11.6222 6.89974 11.5394 6.96948 11.4698L14.4695 3.96979C14.6102 3.82906 14.8011 3.75 15.0001 3.75C15.1991 3.75 15.39 3.82906 15.5307 3.96979C15.6715 4.11052 15.7505 4.30139 15.7505 4.50042C15.7505 4.69944 15.6715 4.89031 15.5307 5.03104L8.56041 12.0004L15.5307 18.9698Z" fill="currentColor"/>';
            echo '</svg>';
            echo '</div>';
            
            echo '<div class="bt-nav bt-button-next">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">';
            echo '<path d="M17.0306 12.531L9.53055 20.031C9.46087 20.1007 9.37815 20.156 9.2871 20.1937C9.19606 20.2314 9.09847 20.2508 8.99993 20.2508C8.90138 20.2508 8.8038 20.2314 8.71276 20.1937C8.62171 20.156 8.53899 20.1007 8.4693 20.031C8.39962 19.9614 8.34435 19.8786 8.30663 19.7876C8.26892 19.6965 8.24951 19.599 8.24951 19.5004C8.24951 19.4019 8.26892 19.3043 8.30663 19.2132C8.34435 19.1222 8.39962 19.0395 8.4693 18.9698L15.4396 12.0004L8.4693 5.03104C8.32857 4.89031 8.24951 4.69944 8.24951 4.50042C8.24951 4.30139 8.32857 4.11052 8.4693 3.96979C8.61003 3.82906 8.80091 3.75 8.99993 3.75C9.19895 3.75 9.38982 3.82906 9.53055 3.96979L17.0306 11.4698C17.1003 11.5394 17.1556 11.6222 17.1933 11.7132C17.2311 11.8043 17.2505 11.9019 17.2505 12.0004C17.2505 12.099 17.2311 12.1966 17.1933 12.2876C17.1556 12.3787 17.1003 12.4614 17.0306 12.531Z" fill="currentColor"/>';
            echo '</svg>';
            echo '</div>';
        }
        echo '</div>';
    }



    protected function content_template() {}
}
