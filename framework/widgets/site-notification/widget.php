<?php

namespace UtenzoElementorWidgets\Widgets\ProductTestimonial;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_BBorder;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductTestimonial extends Widget_Base
{

    public function get_name()
    {
        return 'bt-product-testimonial';
    }

    public function get_title()
    {
        return __('Product Testimonial', 'utenzo');
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
            'post_status' => 'publish',
            'posts_per_page' => -1,
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
            'section_slider',
            [
                'label' => __('Slider', 'utenzo'),
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
        $this->add_control(
            'slider_autoplay_delay',
            [
                'label' => __('Autoplay Delay', 'utenzo'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'description' => __('Delay between slides in milliseconds', 'utenzo'),
                'condition' => [
                    'slider_autoplay' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'slider_speed',
            [
                'label' => __('Slider Speed', 'utenzo'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1000,
                'min' => 100,
                'max' => 5000,
                'step' => 100,
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

    protected function register_style_section_controls() {
        // Content Style Section
        $this->start_controls_section(
            'section_content_style',
            [
                'label' => __('Content Style', 'utenzo'),
                'tab' => Controls_Manager::TAB_STYLE,
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

?>
        <div class="bt-elwg-product-testimonial--default" data-slider-settings='<?php echo json_encode($slider_settings); ?>'>
            <div class="bt-product-testimonial">
                <div class="swiper bt-product-testimonial--content js-testimonial-content">
                    <div class="swiper-wrapper">
                        <?php if (!empty($settings['testimonial_items'])) : ?>
                            <?php foreach ($settings['testimonial_items'] as $item) : ?>
                                <div class="swiper-slide">
                                    <div class="bt-product-testimonial--item">
                                        <?php if (!empty($item['testimonial_text'])) : ?>
                                            <div class="bt-product-testimonial--text"><?php echo esc_html($item['testimonial_text']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
<?php
    }



    protected function content_template() {}
}
