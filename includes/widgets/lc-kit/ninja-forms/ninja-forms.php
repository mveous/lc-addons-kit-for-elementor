<?php
/**
 * Ninja Forms Widget
 *
 * @package LC_Elementor_Addons_Kit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ninja Forms Widget.
 *
 * Elementor widget that integrates with Ninja Forms.
 */
class LCAKE_Kit_Ninja_Forms extends \Elementor\Widget_Base {

	public function get_required_dependencies() {
		return array(
			array(
				'type'  => 'plugin',
				'class' => 'Ninja_Forms',
				'name'  => 'Ninja Forms',
			),
		);
	}

	public function get_name() {
		return 'lcake-kit-ninja-forms';
	}

	public function get_categories() {
		return array( 'lcake-page-kit' );
	}

	public function get_title() {
		return esc_html__( 'Ninja Forms', 'lc-addons-kit-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal lcake-mveous-badge';
	}

	public function get_style_depends() {
		return array( 'lcake-kit-ninja-forms-css' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'lc-addons-kit-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'form_id',
			array(
				'label'   => esc_html__( 'Select Form', 'lc-addons-kit-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => LCAKE_Kit_Utils::lcake_get_ninja_form(),
				'default' => 0,
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'Ninja_Forms' ) ) {
			LCAKE_Kit_Utils::plugin_inactive_notice( 'Ninja Forms' );
			return;
		}

		$settings = $this->get_settings_for_display();

		if ( empty( $settings['form_id'] ) ) {
			return;
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$form_title = 'Ninja Form';
			if ( function_exists( 'Ninja_Forms' ) ) {
				$form = Ninja_Forms()->form( $settings['form_id'] )->get();
				if ( $form ) {
					$form_title = $form->get_setting( 'title' );
				}
			}
			?>
			<div class="lcake-ninja-forms-placeholder">
				<span class="dashicons dashicons-feedback lcake-ninja-forms-placeholder__icon"></span>
				<h4 class="lcake-ninja-forms-placeholder__title"><?php echo esc_html( $form_title ); ?></h4>
				<p class="lcake-ninja-forms-placeholder__text"><?php esc_html_e( 'Ninja Forms uses custom dynamic scripts and will display properly on your live site.', 'lc-addons-kit-for-elementor' ); ?></p>
			</div>
			<?php
			return;
		}
		?>
		<div class="lcake-ninja-forms">
			<?php echo do_shortcode( '[ninja_form id="' . (int) $settings['form_id'] . '"]' ); ?>
		</div>
		<?php
	}
}
