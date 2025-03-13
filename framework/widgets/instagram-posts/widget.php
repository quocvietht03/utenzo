<?php
namespace UtenzoElementorWidgets\Widgets\InstagramPosts;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Widget_InstagramPosts extends Widget_Base {

	public function get_name() {
		return 'bt-instagram-posts';
	}

	public function get_title() {
		return __( 'Instagram Posts', 'utenzo' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'utenzo' ];
	}

	protected function register_content_section_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'utenzo' ),
			]
		);

		$this->add_control(
			'heading',
			[
				'label' => esc_html__( 'Heading', 'utenzo' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'gallery',
			[
				'label' => esc_html__( 'Add Images', 'utenzo' ),
				'type' => Controls_Manager::GALLERY,
				'show_label' => false,
				'default' => [],
			]
		);

		$this->add_control(
			'follow_link_text',
			[
				'label' => esc_html__( 'Follow Link Text', 'utenzo' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'follow_link_url',
			[
				'label' => esc_html__( 'Follow Link Url', 'utenzo' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '',
			]
		);

		$this->end_controls_section();
	}

	protected function register_layout_section_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'utenzo' ),
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __( 'Columns', 'utenzo' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_content_section_controls() {

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'utenzo' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => __( 'heading Color', 'utenzo' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-ins-posts--head' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'label' => __( 'Heading Typography', 'utenzo' ),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-ins-posts--head',
			]
		);

		$this->add_control(
			'follow_link_color',
			[
				'label' => __( 'Follow Link Color', 'utenzo' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-ins-posts--link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'follow_link_color_hover',
			[
				'label' => __( 'Follow Link Color Hover', 'utenzo' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-ins-posts--link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'follow_link_typography',
				'label' => __( 'Follow Link Typography', 'utenzo' ),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-ins-posts--link',
			]
		);

		$this->end_controls_section();

	}

	protected function register_controls() {
		$this->register_content_section_controls();
		$this->register_layout_section_controls();
		$this->register_style_content_section_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		?>
		<div class="bt-elwg-instagram-posts">
			<?php
				if(!empty($settings['heading'])) {
					echo '<h3 class="bt-ins-posts--head">' . $settings['heading'] . '</h3>';
				}

				if(!empty($settings['gallery'])) {
			 	?>
					<div class="bt-ins-posts--gallery <?php echo 'bt-cols--' . esc_attr($settings['columns']); ?>">
						<?php foreach ($settings['gallery'] as $item) { ?>
			        <div class="bt-ins-posts--image">
			          <div class="bt-cover-image">
									<?php echo '<img src="' . esc_url($item['url']) . '" alt="' . get_the_title($item['id']) . '">'; ?>
			          </div>
			        </div>
						<?php } ?>
					</div>
				<?php
				}

				if(!empty($settings['follow_link_text'])) {
					echo '<a href="' . esc_url($settings['follow_link_url']) . '" class="bt-ins-posts--link" target="_blank">' . $settings['follow_link_text'] . '</a>';
				}
		 	?>
		</div>
		<?php
	}

	protected function content_template() {

	}
}
