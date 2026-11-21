<?php
/**
 * WooCommerce Cart Widget
 *
 * @package LC_Elementor_Addons_Kit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Woo Cart Page Widget.
 *
 * Elementor widget that displays a Woo Cart Page.
 */
class LCAKE_Kit_Woo_Cart extends \Elementor\Widget_Base {

	public function get_required_dependencies() {
		return array(
			array(
				'type'  => 'plugin',
				'class' => 'WooCommerce',
				'name'  => 'WooCommerce',
			),
		);
	}

	public function get_name() {
		return 'lcake-kit-woo-cart';
	}

	public function get_categories() {
		return array( 'lcake-page-kit' );
	}

	public function get_title() {
		return esc_html__( 'Woo Cart Page', 'lc-addons-kit-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-woo-cart lcake-mveous-badge';
	}

	public function get_style_depends() {
		return array( 'lcake-kit-woo-cart-css' );
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
			'notice',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					'<div class="lcake-editor-notice"><strong class="lcake-editor-notice__title"><span class="dashicons dashicons-info"></span>%1$s</strong>%2$s</div>',
					esc_html__( 'Woo Cart Page', 'lc-addons-kit-for-elementor' ),
					sprintf(
						/* translators: %s: shortcode name. */
						esc_html__( 'This widget displays the native WooCommerce cart page content dynamically using the %s shortcode.', 'lc-addons-kit-for-elementor' ),
						'<code>[woocommerce_cart]</code>'
					)
				),
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! LCAKE_Kit_Utils::is_woo_active() ) {
			LCAKE_Kit_Utils::woo_inactive_notice();
			return;
		}
		?>
		<div class="lcake-woo-cart">
			<?php echo do_shortcode( '[woocommerce_cart]' ); ?>
		</div>
		<?php
	}
}
