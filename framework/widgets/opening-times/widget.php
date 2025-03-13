<?php
namespace UtenzoElementorWidgets\Widgets\OpeningTime;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_OpeningTime extends Widget_Base
{


    public function get_name()
    {
        return 'bt-opening-time';
    }

    public function get_title()
    {
        return __('Opening Time', 'utenzo');
    }

    public function get_icon()
    {
        return 'eicon-posts-ticker';
    }

    public function get_categories()
    {
        return ['utenzo'];
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
			'label_style',[
				'label' => esc_html__( 'Label', 'utenzo' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-opening-times--label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
                'label' => esc_html__('Typography', 'utenzo'),
				'selector' => '{{WRAPPER}} .bt-opening-times--label',
			]
		);

        $this->add_control(
			'value_style',[
				'label' => esc_html__( 'Value', 'utenzo' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__('Color', 'utenzo'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-hour' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'value_typography',
                'label' => esc_html__('Typography', 'utenzo'),
				'selector' => '{{WRAPPER}} .bt-hour',
			]
		);

        $this->end_controls_section();

    }

    protected function register_controls()
    {
        $this->register_style_section_controls();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (function_exists('get_field')) {
        $theme_options = get_fields('option');
        }
        $site_information = '';
        $opening_hours = '';

        if (!empty($theme_options)) {
            $site_information = $theme_options['site_information'];
            $opening_hours = $site_information['opening_hours'];
        } else {
            $site_information = '';
            $opening_hours = '';
        }
        ?>
        <div class="bt-opening-times--default">
            <div class="bt-opening-times--label"><?php echo esc_html__('Opening Times:', 'utenzo'); ?></div>
            <?php
            if (!empty($opening_hours)) {
                echo '<div class="bt-opening-times--list">';
                foreach ($opening_hours as $key => $item) {
                    echo '<div class="bt-hour">' . $item['hours'] . '</div>';
                }
                echo '</div>';
            }
            ?>
        </div>
        <?php
    }

    protected function content_template()
    {

    }
}
