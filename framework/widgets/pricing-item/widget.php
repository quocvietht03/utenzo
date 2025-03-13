<?php
namespace UtenzoElementorWidgets\Widgets\PricingItem;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_PricingItem extends Widget_Base
{

    public function get_name()
    {
        return 'bt-pricing-item';
    }

    public function get_title()
    {
        return __('Pricing Item', 'utenzo');
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
            'section_content',
            [
                'label' => __('Content', 'utenzo'),
            ]
        );

        $this->add_control(
            'select_best_value',
            [
                'label' => esc_html__('Best Value', 'utenzo'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'utenzo'),
                    'one_time' => esc_html__('One-Time Cleaning', 'utenzo'),
                    'weekly' => esc_html__('Weekly Cleaning', 'utenzo'),
                    'bi_weekly' => esc_html__('Bi-Weekly Cleaning', 'utenzo'),
                    'monthly' => esc_html__('Monthly Cleaning', 'utenzo'),
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'utenzo'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Book Now', 'utenzo'),
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label' => esc_html__('Link', 'utenzo'),
                'type' => Controls_Manager::URL,
                'options' => false,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'list_pricing',
            [
                'label' => esc_html__('Pricing', 'utenzo'),
                'type' => Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'pricing_title',
                        'label' => esc_html__('Title', 'utenzo'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('Bedroom Apartment', 'utenzo'),
                        'label_block' => true
                    ],
                    [
                        'name' => 'pricing_one_time',
                        'label' => esc_html__('One-Time Cleaning - Pricing', 'utenzo'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('$50 - $100', 'utenzo'),
                        'label_block' => true
                    ],
                    [
                        'name' => 'pricing_weekly',
                        'label' => esc_html__('Weekly Cleaning - Pricing', 'utenzo'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('$50 - $100', 'utenzo'),
                        'label_block' => true
                    ],
                    [
                        'name' => 'pricing_bi_weekly',
                        'label' => esc_html__('Bi-Weekly Cleaning - Pricing', 'utenzo'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('$50 - $100', 'utenzo'),
                        'label_block' => true
                    ],
                    [
                        'name' => 'pricing_monthly',
                        'label' => esc_html__('Monthly Cleaning - Pricing', 'utenzo'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('$50 - $100', 'utenzo'),
                        'label_block' => true
                    ],
                ],
                'title_field' => '{{{ pricing_title }}}',
                'default' => [
                    [
                        'list_title' => esc_html__('Bedroom Apartment', 'utenzo'),
                    ],
                    [
                        'list_title' => esc_html__('Bedroom Apartment #2', 'utenzo'),
                    ],
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Content', 'utenzo'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => esc_html__('Background Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-pricing-item--default' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'utenzo'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-pricing-item--default' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'name_heading',
            [
                'label' => __('Name', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-pricing-head--item .bt-pricing-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .bt-pricing-head--item .bt-pricing-name, {{WRAPPER}} .bt-pricing-mobile .bt-pricing-name',
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'label' => __('Title', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-pricing-body--item .bt-pricing-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bt-pricing-body--item .bt-title-mb' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bt-pricing-body--item .bt-pricing-title,{{WRAPPER}} .bt-pricing-body--item .bt-title-mb',
            ]
        );

        $this->add_control(
            'price_heading',
            [
                'label' => __('Price', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-pricing-body--item .bt-pricing-value' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bt-content-mb .bt-pricing-value span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .bt-pricing-body--item .bt-pricing-value,{{WRAPPER}} .bt-content-mb .bt-pricing-value span',
            ]
        );

        $this->add_control(
            'button_heading',
            [
                'label' => __('Button', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .bt-pricing-body--item .bt-pricing-value .bt-button',
            ]
        );

        $this->start_controls_tabs(
            'style_tabs'
        );

        $this->start_controls_tab(
            'style_normal_tab',
            [
                'label' => esc_html__('Normal', 'utenzo'),
            ]
        );
        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__('Background Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-pricing-body--item .bt-pricing-value .bt-button' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .bt-content-mb .bt-button' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-pricing-body--item .bt-pricing-value .bt-button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bt-content-mb .bt-button' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_hover_tab',
            [
                'label' => esc_html__('Hover', 'utenzo'),
            ]
        );
        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-pricing-body--item .bt-pricing-value .bt-button:hover' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .bt-content-mb .bt-button:hover' => 'background: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-pricing-body--item .bt-pricing-value .bt-button:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bt-content-mb .bt-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tab();

        $this->end_controls_section();

    }

    protected function register_controls()
    {
        $this->register_layout_section_controls();
        $this->register_style_section_controls();
    }
    public function pricing_item_best_value()
    {
        ?>
        <div class="bt-best-value">
            <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_10935_2902)">
                    <path d="M12.5 1.70508L11.25 7.95508L16.25 9.83008L7.5 19.2051L8.75 12.9551L3.75 11.0801L12.5 1.70508Z"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </g>
                <defs>
                    <clipPath id="clip0_10935_2902">
                        <rect width="20" height="20" fill="white" transform="translate(0 0.455078)" />
                    </clipPath>
                </defs>
            </svg>
            <span><?php echo esc_html__('Best Value', 'utenzo'); ?></span>
        </div>
        <?php
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $pricing_list = $settings['list_pricing'];
        $button_text = $settings['button_text'];
        $button_url = $settings['button_url'];
        $url = $button_url['url'];
        ?>
        <div class="bt-elwg-pricing-item--default">
            <div class="bt-pricing-head">
                <div class="bt-col bt-pricing-head--item">
                    <h3 class="bt-pricing-name"><?php echo esc_html__('Bed Rooms', 'utenzo'); ?></h3>
                </div>
                <div class="bt-col bt-pricing-head--item">
                    <?php
                    if ($settings['select_best_value'] == 'one_time') {
                        $this->pricing_item_best_value();
                    }
                    ?>
                    <h3 class="bt-pricing-name"><?php echo esc_html__('One-Time Cleaning', 'utenzo'); ?></h3>
                </div>
                <div class="bt-col bt-pricing-head--item">
                    <?php
                    if ($settings['select_best_value'] == 'weekly') {
                        $this->pricing_item_best_value();
                    }
                    ?>
                    <h3 class="bt-pricing-name"><?php echo esc_html__('Weekly Cleaning', 'utenzo'); ?></h3>
                </div>
                <div class="bt-col bt-pricing-head--item">
                    <?php
                    if ($settings['select_best_value'] == 'bi_weekly') {
                        $this->pricing_item_best_value();
                    }
                    ?>
                    <h3 class="bt-pricing-name"><?php echo esc_html__('Bi-Weekly Cleaning', 'utenzo'); ?></h3>
                </div>
                <div class="bt-col bt-pricing-head--item">
                    <?php
                    if ($settings['select_best_value'] == 'monthly') {
                        $this->pricing_item_best_value();
                    }
                    ?>
                    <h3 class="bt-pricing-name"><?php echo esc_html__('Monthly Cleaning', 'utenzo'); ?></h3>
                </div>
            </div>
            <div class="bt-pricing-body">
                <?php
                if (!empty($pricing_list)) {
                    foreach ($pricing_list as $key => $item) {
                        ?>
                        <div class="bt-pricing-body--item">
                            <div class="bt-col bt-pricing-title"><?php echo esc_html($item['pricing_title']); ?></div>
                            <div class="bt-col bt-pricing-value">
                                <?php
                                echo esc_html($item['pricing_one_time']);
                                ?>
                            </div>
                            <div class="bt-col bt-pricing-value">
                                <?php
                                echo esc_html($item['pricing_weekly']);
                                ?>
                            </div>
                            <div class="bt-col bt-pricing-value">
                                <?php
                                echo esc_html($item['pricing_bi_weekly']);
                                ?>
                            </div>
                            <div class="bt-col bt-pricing-value">
                                <?php
                                echo esc_html($item['pricing_monthly']);
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php if (!empty($button_text) && !empty($url)) { ?>
                    <div class="bt-pricing-body--item">
                        <div class="bt-col bt-pricing-title"></div>
                        <div class="bt-col bt-pricing-value bt-button-hover">
                            <a class="bt-button" href="<?php echo esc_url($url); ?>"><span class="bt-heading"><?php echo esc_html($button_text); ?></span></a>
                        </div>
                        <div class="bt-col bt-pricing-value bt-button-hover">
                            <a class="bt-button" href="<?php echo esc_url($url); ?>"><span class="bt-heading"><?php echo esc_html($button_text); ?></span></a>
                        </div>
                        <div class="bt-col bt-pricing-value bt-button-hover">
                            <a class="bt-button" href="<?php echo esc_url($url); ?>"><span class="bt-heading"><?php echo esc_html($button_text); ?></span></a>
                        </div>
                        <div class="bt-col bt-pricing-value bt-button-hover">
                            <a class="bt-button" href="<?php echo esc_url($url); ?>"><span class="bt-heading"><?php echo esc_html($button_text); ?></span></a>
                        </div>
                    </div>
                <?php } ?>

            </div>
            <div class="bt-pricing-mobile">
                <div class="bt-col-mb">
                    <h3 class="bt-pricing-name">
                        <?php
                        if ($settings['select_best_value'] == 'one_time') {
                            $this->pricing_item_best_value();
                        }
                        echo esc_html__('One-Time Cleaning', 'utenzo');
                        ?>
                    </h3>
                    <div class="bt-content-mb">
                        <?php
                        foreach ($pricing_list as $key => $item) {
                            ?>
                            <div class="bt-pricing-body--item">
                                <div class="bt-col bt-pricing-value">
                                    <?php
                                    if (!empty($item['pricing_title']))
                                        echo '<div class="bt-title-mb">' . $item['pricing_title'] . '</div>';
                                    ?>
                                    <span><?php echo esc_html($item['pricing_one_time']); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        if (!empty($button_text) && !empty($url)) { ?>
                            <a class="bt-button" href="<?php echo esc_url($url); ?>"><?php echo esc_html($button_text); ?></a>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="bt-col-mb">
                    <h3 class="bt-pricing-name">
                        <?php
                        if ($settings['select_best_value'] == 'weekly') {
                            $this->pricing_item_best_value();
                        }
                        echo esc_html__('Weekly Cleaning', 'utenzo');
                        ?>
                    </h3>
                    <div class="bt-content-mb">
                        <?php
                        foreach ($pricing_list as $key => $item) {
                            ?>
                            <div class="bt-pricing-body--item">
                                <div class="bt-col bt-pricing-value">
                                    <?php
                                    if (!empty($item['pricing_title']))
                                        echo '<div class="bt-title-mb">' . $item['pricing_title'] . '</div>';
                                    ?>
                                    <span><?php echo esc_html($item['pricing_weekly']); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        if (!empty($button_text) && !empty($url)) { ?>
                            <a class="bt-button" href="<?php echo esc_url($url); ?>"><?php echo esc_html($button_text); ?></a>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="bt-col-mb">
                    <h3 class="bt-pricing-name">
                        <?php
                        if ($settings['select_best_value'] == 'bi_weekly') {
                            $this->pricing_item_best_value();
                        }
                        echo esc_html__('Bi-Weekly Cleaning', 'utenzo');
                        ?>
                    </h3>
                    <div class="bt-content-mb">
                        <?php
                        foreach ($pricing_list as $key => $item) {
                            ?>
                            <div class="bt-pricing-body--item">
                                <div class="bt-col bt-pricing-value">
                                    <?php
                                    if (!empty($item['pricing_title']))
                                        echo '<div class="bt-title-mb">' . $item['pricing_title'] . '</div>';
                                    ?>
                                    <span><?php echo esc_html($item['pricing_bi_weekly']); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        if (!empty($button_text) && !empty($url)) { ?>
                            <a class="bt-button" href="<?php echo esc_url($url); ?>"><?php echo esc_html($button_text); ?></a>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="bt-col-mb">
                    <h3 class="bt-pricing-name">
                        <?php
                        if ($settings['select_best_value'] == 'monthly') {
                            $this->pricing_item_best_value();
                        }
                        echo esc_html__('Monthly Cleaning', 'utenzo');
                        ?>
                    </h3>
                    <div class="bt-content-mb">
                        <?php
                        foreach ($pricing_list as $key => $item) {
                            ?>
                            <div class="bt-pricing-body--item">
                                <div class="bt-col bt-pricing-value">
                                    <?php
                                    if (!empty($item['pricing_title']))
                                        echo '<div class="bt-title-mb">' . $item['pricing_title'] . '</div>';
                                    ?>
                                    <span><?php echo esc_html($item['pricing_monthly']); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        if (!empty($button_text) && !empty($url)) { ?>
                            <a class="bt-button" href="<?php echo esc_url($url); ?>"><?php echo esc_html($button_text); ?></a>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    protected function content_template()
    {

    }
}

