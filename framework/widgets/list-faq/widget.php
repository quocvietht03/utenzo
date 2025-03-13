<?php

namespace UtenzoElementorWidgets\Widgets\ListFaq;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;


class Widget_ListFaq extends Widget_Base
{

    public function get_name()
    {
        return 'bt-list-faq';
    }

    public function get_title()
    {
        return __('List FAQ', 'utenzo');
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

        $repeater = new Repeater();


        $repeater->add_control(
            'faq_title',
            [
                'label' => __('Text', 'utenzo'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('FAQ title', 'utenzo'),
            ]
        );

        $repeater->add_control(
            'faq_content',
            [
                'label' => __('Content', 'utenzo'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => __('FAQ content', 'utenzo'),
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => __('List Faq', 'utenzo'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'faq_title' => __('FAQ title 01', 'utenzo'),
                        'faq_content' => 'we provide high-quality, eco-friendly cleaning supplies and equipment for every job. Our products are safe for your family and the environment. If you have specific preferences or sensitivities, let us know, and we will gladly accommodate your requests to ensure your satisfaction!'
                    ],
                    [
                        'faq_title' => __('FAQ title 02', 'utenzo'),
                        'faq_content' => 'we provide high-quality, eco-friendly cleaning supplies and equipment for every job. Our products are safe for your family and the environment. If you have specific preferences or sensitivities, let us know, and we will gladly accommodate your requests to ensure your satisfaction!'
                    ],
                    [
                        'faq_title' => __('FAQ title 03', 'utenzo'),
                        'faq_content' => 'we provide high-quality, eco-friendly cleaning supplies and equipment for every job. Our products are safe for your family and the environment. If you have specific preferences or sensitivities, let us know, and we will gladly accommodate your requests to ensure your satisfaction!'
                    ],
                ],
                'title_field' => '{{{ faq_title }}}',
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
            'list_border',
            [
                'label' => __('Border Width', 'utenzo'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'border-bottom: {{SIZE}}{{UNIT}} solid #E4E4E4;',
                ],
            ]
        );
        $this->add_control(
            'list_border_color',
            [
                'label' => __('Border Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '#E4E4E4',
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'list_gap',
            [
                'label' => __('Space Between', 'utenzo'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'padding-top: {{SIZE}}{{UNIT}};padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'utenzo'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_style',
            [
                'label' => esc_html__('Title', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'list_title_color',
            [
                'label' => __('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-title h3' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'list_title_hover_color',
            [
                'label' => __('Color Hover', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-title:hover h3' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-item-title.active h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_title_typography',
                'label' => __('Typography', 'utenzo'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-item-title h3 ',
            ]
        );
        $this->add_control(
            'content_style',
            [
                'label' => esc_html__('content', 'utenzo'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'list_content_color',
            [
                'label' => __('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_content_typography',
                'label' => __('Typography', 'utenzo'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-item-content',
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

?>
        <div class="bt-elwg-list-faq--default">
            <div class="bt-elwg-list-faq-inner">
                <?php foreach ($settings['list'] as $index => $item): ?>
                    <div class="item-faq">
                        <div class="item-faq-inner">
                            <div class="bt-item-title">
                                <?php if (!empty($item['faq_title'])): ?>
                                    <h3> <?php echo esc_html($item['faq_title']) ?> </h3>
                                <?php endif; ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="plus" width="18" height="18" viewBox="0 0 160 160">
                                    <rect class="vertical-line" x="70" width="15" height="160" rx="7" ry="7" />
                                    <rect class="horizontal-line" y="70" width="160" height="15" rx="7" ry="7" />
                                </svg>
                            </div>
                            <?php if (!empty($item['faq_content'])): ?>
                                <div class="bt-item-content">
                                    <?php echo esc_html($item['faq_content']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<?php }

    protected function content_template() {}
}
