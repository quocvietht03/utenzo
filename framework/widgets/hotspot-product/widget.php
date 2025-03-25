<?php

namespace UtenzoElementorWidgets\Widgets\HotspotProduct;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_BBorder;
use Elementor\Group_Control_Box_Shadow;

class Widget_HotspotProduct extends Widget_Base
{

    public function get_name()
    {
        return 'bt-hotspot-product';
    }

    public function get_title()
    {
        return __('Hotspot Product', 'utenzo');
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
        $this->add_control(
            'hotspot_image',
            [
                'label' => __('Image', 'utenzo'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
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
 
        $repeater = new Repeater();
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
            'hotspot_position_x',
            [
                'label' => __('X Position', 'utenzo'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
            ]
        );

        $repeater->add_control(
            'hotspot_position_y',
            [
                'label' => __('Y Position', 'utenzo'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
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
                        'hotspot_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'hotspot_url' => [
                            'url' => '#',
                            'is_external' => true,
                            'nofollow' => true,
                        ]
                    ],
                    [
                        'hotspot_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'hotspot_url' => [
                            'url' => '#',
                            'is_external' => true,
                            'nofollow' => true,
                        ]
                    ],
                    [
                        'hotspot_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'hotspot_url' => [
                            'url' => '#',
                            'is_external' => true,
                            'nofollow' => true,
                        ]
                    ],
                ],
            ]
        );

     


        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {

    
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

    }

    protected function register_controls()
    {
        $this->register_layout_section_controls();
        $this->register_style_section_controls();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

            <div class="hotspot-image-wrapper">
            <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@homesgiadinh/video/7467942849229671688" data-video-id="7467942849229671688" style="max-width: 605px;min-width: 325px;" > <section> <a target="_blank" title="@homesgiadinh" href="https://www.tiktok.com/@homesgiadinh?refer=embed">@homesgiadinh</a> Decor bàn làm việc với mô hình ngộ không bay <a title="decor" target="_blank" href="https://www.tiktok.com/tag/decor?refer=embed">#decor</a><a title="decoration" target="_blank" href="https://www.tiktok.com/tag/decoration?refer=embed">#decoration</a> <a title="yeucongnghe" target="_blank" href="https://www.tiktok.com/tag/yeucongnghe?refer=embed">#yeucongnghe</a><a title="ngokhong" target="_blank" href="https://www.tiktok.com/tag/ngokhong?refer=embed">#ngokhong</a><a title="tonngokhong" target="_blank" href="https://www.tiktok.com/tag/tonngokhong?refer=embed">#tonngokhong</a> <a target="_blank" title="♬ Ôm Nhiều Mộng Mơ (Đại Mèo Remix) - Phát Huy T4, TLong" href="https://www.tiktok.com/music/Ôm-Nhiều-Mộng-Mơ-Đại-Mèo-Remix-7049583855728478978?refer=embed">♬ Ôm Nhiều Mộng Mơ (Đại Mèo Remix) - Phát Huy T4, TLong</a> </section> </blockquote> <script async src="https://www.tiktok.com/embed.js"></script>
       
                <?php if (!empty($settings['hotspot_items'])) : ?>
                    <div class="hotspot-points">
                        <?php foreach ($settings['hotspot_items'] as $item) : ?>
                            <?php 
                            $product = wc_get_product($item['id_product']);
                            if ($product) : 
                                $x_position = $item['hotspot_position_x']['size'] ?? 50;
                                $y_position = $item['hotspot_position_y']['size'] ?? 50;
                            ?>
                                <div class="hotspot-point" 
                                    style="left: <?php echo esc_attr($x_position); ?>%; 
                                           top: <?php echo esc_attr($y_position); ?>%;"
                                    data-product-id="<?php echo esc_attr($item['id_product']); ?>">
                                    <div class="hotspot-marker"></div>
                                    <div class="hotspot-product-info">
                                        <?php echo get_the_post_thumbnail($item['id_product'], 'thumbnail'); ?>
                                        <h4><?php echo esc_html($product->get_name()); ?></h4>
                                        <p class="price"><?php echo $product->get_price_html(); ?></p>
                                        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn">
                                            <?php esc_html_e('View Product', 'utenzo'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php
    }



    protected function content_template() {}
}
