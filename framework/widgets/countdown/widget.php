<?php

namespace UtenzoElementorWidgets\Widgets\CountDown;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use DateTime;
use DateTimeZone;

class Widget_CountDown extends Widget_Base
{
	public function get_name()
	{
		return 'bt-countdown';
	}

	public function get_title()
	{
		return __('BT Countdown', 'utenzo');
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
			'countdown_date',
			[
				'label' => __('Countdown Date', 'utenzo'),
				'type' => Controls_Manager::DATE_TIME,
				'default' => date('Y-m-d H:i', strtotime('+1 month')),
				'description' => __('Set the date and time for the countdown', 'utenzo'),
			]
		);

		$this->add_control(
			'show_infinity_date',
			[
				'label' => __('Show Infinity Date', 'utenzo'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __('Show', 'utenzo'),
				'label_off' => __('Hide', 'utenzo'),
				'return_value' => 'yes',
				'default' => 'no',
				'description' => __('Enable to display an infinite countdown timer that never expires', 'utenzo'),
			]
		);

		$this->add_control(
			'infinity_date',
			[
				'label' => __('Days from Now', 'utenzo'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 365,
				'default' => 12,
				'condition' => [
					'show_infinity_date' => 'yes',
				],
				'description' => __('Set the number of days from today for the countdown timer (1-365 days)', 'utenzo'),
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_content_section_controls()
	{
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Content', 'utenzo'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'digits_color',
			[
				'label' => __('Digits Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-countdown--digits' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'digits_typography',
				'selector' => '{{WRAPPER}} .bt-countdown--digits',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __('Label Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-countdown--label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .bt-countdown--label',
			]
		);

		$this->add_control(
			'delimiter_color',
			[
				'label' => __('Delimiter Color', 'utenzo'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-delimiter' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'delimiter_typography',
				'selector' => '{{WRAPPER}} .bt-delimiter',
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
		 $date_countdown = $settings['countdown_date'];
		if ($settings['show_infinity_date'] === 'yes') {
			$current_date = new DateTime('now', new DateTimeZone(wp_timezone_string()));
			$current_timestamp = strtotime($current_date->format('Y-m-d H:i:s'));
			// Get the last saved countdown date from options
			$last_countdown = get_option('utenzo_countdown_date');
			// Get the last saved infinity_date from options
			$last_infinity_date = get_option('utenzo_infinity_date');
			// If infinity_date changed or there's no saved date or the saved date has passed
			if ((int)$settings['infinity_date'] !== (int)$last_infinity_date || !$last_countdown || strtotime($last_countdown) <= $current_timestamp) {
				// Create new countdown date by adding infinity days
				$current_date->modify('+' . ((int)$settings['infinity_date'] * 24) . ' hours');
				$date_countdown = $current_date->format('Y-m-d H:i:s');
				// Save the new countdown date and infinity_date
				update_option('utenzo_countdown_date', $date_countdown);
				update_option('utenzo_infinity_date', $settings['infinity_date']);
			} else {
				// Use the saved countdown date
				$date_countdown = $last_countdown;
			}
		}
?>
		<div class="bt-elwg-countdown--default">
			<div class="bt-countdown bt-countdown-js" data-time="<?php echo esc_attr($date_countdown); ?>">
				<div class="bt-countdown--item">
					<span class="bt-countdown--digits bt-countdown-days">--</span>
					<span class="bt-countdown--label"><?php _e('Days', 'utenzo'); ?></span>
				</div>
				<div class="bt-delimiter">:</div>
				<div class="bt-countdown--item">
					<span class="bt-countdown--digits bt-countdown-hours">--</span>
					<span class="bt-countdown--label"><?php _e('Hours', 'utenzo'); ?></span>
				</div>
				<div class="bt-delimiter">:</div>
				<div class="bt-countdown--item">
					<span class="bt-countdown--digits bt-countdown-mins">--</span>
					<span class="bt-countdown--label"><?php _e('Mins', 'utenzo'); ?></span>
				</div>
				<div class="bt-delimiter">:</div>
				<div class="bt-countdown--item">
					<span class="bt-countdown--digits bt-countdown-secs">--</span>
					<span class="bt-countdown--label"><?php _e('Secs', 'utenzo'); ?></span>
				</div>
			</div>
		</div>
<?php
	}

	protected function content_template() {}
}
