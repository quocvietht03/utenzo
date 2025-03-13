<?php

namespace UtenzoElementorWidgets\Widgets\OfferBox;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

class Widget_OfferBox extends Widget_Base
{

    public function get_name()
    {
        return 'bt-offer-box';
    }

    public function get_title()
    {
        return __('Offer Box', 'utenzo');
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

    protected function register_layout_section_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'utenzo'),
            ]
        );

        $this->add_control(
            'offer_reverse',
            [
                'label' => __('Enable Reverse', 'utenzo'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'utenzo'),
                'label_off' => __('No', 'utenzo'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'offer_image',
            [
                'label' => esc_html__('Image', 'utenzo'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'offer_discount',
            [
                'label' => __('Discount', 'utenzo'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('', 'utenzo'),
            ]
        );
        $this->add_control(
            'offer_heading',
            [
                'label' => __('Heading', 'utenzo'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('', 'utenzo'),
            ]
        );
        $this->add_control(
            'offer_description',
            [
                'label' => __('Description', 'utenzo'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => __('', 'utenzo'),
            ]
        );
        $this->add_control(
            'offer_coupon',
            [
                'label' => __('Coupon', 'utenzo'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('', 'utenzo'),
            ]
        );
        $this->add_control(
            'offer_button',
            [
                'label' => __('Button', 'utenzo'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('Book now', 'utenzo'),
            ]
        );
        $this->add_control(
            'offer_button_link',
            [
                'label' => __('Button Link', 'utenzo'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => '',
            ]
        );
        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('General', 'utenzo'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'offer_box_color',
            [
                'label' => __('Line Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer .bt-line > *' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer .bt-line:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer .bt-line:after' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'offer_bg_infor',
            [
                'label' => __('Background Info', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--infor' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_Content',
            [
                'label' => esc_html__('Content', 'utenzo'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'offer_discount_style',
            [
                'label' => esc_html__('Discount', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'offer_discount_color',
            [
                'label' => __('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--discount' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'offer_discount_typography',
                'label' => __('Typography', 'utenzo'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--discount',
            ]
        );

        $this->add_control(
            'offer_heading_style',
            [
                'label' => esc_html__('Heading', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'offer_heading_color',
            [
                'label' => __('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'offer_heading_typography',
                'label' => __('Typography', 'utenzo'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--heading ',
            ]
        );
        $this->add_control(
            'offer_description_style',
            [
                'label' => esc_html__('Description', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'offer_description_color',
            [
                'label' => __('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--description' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'offer_description_typography',
                'label' => __('Typography', 'utenzo'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--description ',
            ]
        );
        $this->add_control(
            'offer_button_style',
            [
                'label' => esc_html__('Button', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'offer_button_color',
            [
                'label' => __('Text Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--button a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--button a svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'offer_button_bg',
            [
                'label' => __('Background Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--button a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'offer_button_typography',
                'label' => __('Typography', 'utenzo'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-elwg-offer-box--default .bt-offer--button a',
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
        $link = !empty($settings['offer_button_link']) ? 
        $settings['offer_button_link'] . (!empty($settings['offer_coupon']) ? '?offer-coupon=' . $settings['offer_coupon'] . '&discount=' .$settings['offer_discount'] : '') 
        : '';
?>
        <div class="bt-elwg-offer-box--default">
            <div class="bt-offer <?php echo esc_attr(($settings['offer_reverse'] === 'yes') ? 'bt-reverse' : ''); ?>">
                <div class="bt-offer--image">
                    <?php if (!empty($settings['offer_image']['url'])) : ?>
                        <img src="<?php echo esc_url($settings['offer_image']['url']); ?>">
                    <?php endif; ?>
                    <div class="bt-line">
                        <div class="bt-line-top"></div>
                        <div class="bt-line-left"></div>
                        <div class="bt-line-right"></div>
                        <div class="bt-line-bottom"></div>
                    </div>
                </div>
                <div class="bt-offer--infor">
                    <div class="bt-offer--inner">
                        <?php if (!empty($settings['offer_discount'])) : ?>
                            <h2 class="bt-offer--discount"><?php echo esc_html($settings['offer_discount']); ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($settings['offer_heading'])) : ?>
                            <h3 class="bt-offer--heading"><?php echo esc_html($settings['offer_heading']); ?></h3>
                        <?php endif; ?>

                        <?php if (!empty($settings['offer_description'])) : ?>
                            <p class="bt-offer--description"><?php echo esc_html($settings['offer_description']); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($settings['offer_button']) && !empty($settings['offer_button_link'])) : ?>
                            <div class="bt-offer--button bt-button-hover">
                                <a href="<?php echo esc_url($link); ?>" class="bt-primary-btn bt-button">
                                    <span class="bt-heading"><?php echo esc_html($settings['offer_button']); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <g clip-path="url(#clip0_10966_1169)">
                                            <path d="M3.125 10H16.875" stroke="#212121" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M11.25 4.375L16.875 10L11.25 15.625" stroke="#212121" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_10966_1169">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="bt-line">
                        <div class="bt-line-top"></div>
                        <div class="bt-line-left"></div>
                        <div class="bt-line-right"></div>
                        <div class="bt-line-bottom"></div>
                    </div>
                </div>
            </div>
        </div>
<?php }

    protected function content_template() {}
}
