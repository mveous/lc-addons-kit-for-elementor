<?php
/**
 * "Free vs Pro" admin page.
 *
 * @package LC_Addons_Kit_For_Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds an LC Kit > Free vs Pro page comparing the free plugin with
 * LC Addons Kit Pro. Shown only while Pro is not active.
 *
 * @since 1.2.2
 */
class LCAKE_Kit_Free_Vs_Pro {

	const PAGE_SLUG   = 'lcake-free-vs-pro';
	const PARENT_SLUG = 'lcake_menu';

	/**
	 * Admin page hook suffix.
	 *
	 * @var string
	 */
	private $page_hook = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( defined( 'LCAKE_PRO_VERSION' ) ) {
			return;
		}

		// Just before the "Get Pro" link (priority 999), so it is the second-last item.
		add_action( 'admin_menu', array( $this, 'add_page' ), 998 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Registers the submenu page.
	 *
	 * @return void
	 */
	public function add_page() {
		$this->page_hook = add_submenu_page(
			self::PARENT_SLUG,
			esc_html__( 'LC Kit: Free vs Pro', 'lc-addons-kit-for-elementor' ),
			esc_html__( 'Free vs Pro', 'lc-addons-kit-for-elementor' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Loads the page stylesheet on this page only.
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( $this->page_hook !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'lcake-kit-free-vs-pro',
			LCAKE_EAK_URL . 'admin/css/free-vs-pro.css',
			array( 'dashicons' ),
			LCAKE_EAK_VERSION
		);
	}

	/**
	 * Number of widgets bundled with the free plugin.
	 *
	 * @return int
	 */
	private function free_widget_count() {
		$count = 0;
		foreach ( array( 'lc-kit', 'lc-header-footer' ) as $folder ) {
			$path   = LCAKE_EAK_PATH . 'includes/widgets/' . $folder . '/';
			$count += count( (array) glob( $path . '*.php' ) );
			$count += count( (array) glob( $path . '*/*.php' ) );
		}
		return $count;
	}

	/**
	 * Feature rows for the comparison table, grouped by section.
	 *
	 * Cell values: true = included, false = not included, int = widget count,
	 * string = short text.
	 *
	 * @param int $free_count Free widget count.
	 * @param int $pro_count  Total widget count with Pro.
	 * @return array<string, array[]>
	 */
	private function get_features( $free_count, $pro_count ) {
		$sections = array(
			__( 'General', 'lc-addons-kit-for-elementor' ) => array(
				array( __( 'Total Elementor widgets', 'lc-addons-kit-for-elementor' ), $free_count, $pro_count ),
				array( __( 'Widget Manager (turn unused widgets off)', 'lc-addons-kit-for-elementor' ), true, true ),
				array( __( 'Header & Footer Builder (with display conditions)', 'lc-addons-kit-for-elementor' ), true, true ),
				array( __( 'WooCommerce widgets (product grids, cart & checkout)', 'lc-addons-kit-for-elementor' ), true, true ),
				array( __( 'Form styling (Contact Form 7, WPForms, Gravity Forms & more)', 'lc-addons-kit-for-elementor' ), true, true ),
			),
			__( 'Content & Marketing', 'lc-addons-kit-for-elementor' ) => array(
				array( __( 'Post layouts (Post Grid, Timeline, Blog Posts, Carousel & more)', 'lc-addons-kit-for-elementor' ), 2, 6 ),
				array( __( 'Marketing widgets (CTA, countdowns, coupons & notification bar)', 'lc-addons-kit-for-elementor' ), 3, 7 ),
				array( __( 'Review widgets (testimonials, Google & Yelp reviews)', 'lc-addons-kit-for-elementor' ), 2, 4 ),
			),
			__( 'Advanced Features', 'lc-addons-kit-for-elementor' ) => array(
				array( __( 'AI Website Builder (build pages with Claude or ChatGPT)', 'lc-addons-kit-for-elementor' ), false, true ),
				array( __( 'Popups (open any template with Popup Trigger)', 'lc-addons-kit-for-elementor' ), false, true ),
				array( __( 'Dynamic tags (ACF fields & WooCommerce prices)', 'lc-addons-kit-for-elementor' ), false, true ),
				array( __( 'Extra options for free widgets (filters, hover effects, autoplay & more)', 'lc-addons-kit-for-elementor' ), false, true ),
			),
		);

		$widget_rows = array();
		foreach ( $this->get_pro_widgets() as $group => $widgets ) {
			$widget_rows[] = array(
				/* translators: 1: widget category, 2: list of widget names. */
				sprintf( __( '%1$s (%2$s)', 'lc-addons-kit-for-elementor' ), $group, wp_sprintf( '%l', $widgets ) ),
				false,
				count( $widgets ),
			);
		}
		$sections[ __( 'Pro Widgets', 'lc-addons-kit-for-elementor' ) ] = $widget_rows;

		return $sections;
	}

	/**
	 * Pro-only widgets grouped by category.
	 *
	 * @return array<string, string[]>
	 */
	private function get_pro_widgets() {
		return array(
			__( 'Posts & Query', 'lc-addons-kit-for-elementor' ) => array(
					__( 'Blog Posts', 'lc-addons-kit-for-elementor' ),
					__( 'Post Carousel', 'lc-addons-kit-for-elementor' ),
					__( 'Post List', 'lc-addons-kit-for-elementor' ),
					__( 'Related Posts', 'lc-addons-kit-for-elementor' ),
			),
			__( 'Content', 'lc-addons-kit-for-elementor' )       => array(
					__( 'Advanced Accordion', 'lc-addons-kit-for-elementor' ),
					__( 'Advanced Tabs', 'lc-addons-kit-for-elementor' ),
					__( 'Toggle', 'lc-addons-kit-for-elementor' ),
					__( 'Timeline', 'lc-addons-kit-for-elementor' ),
					__( 'Comparison Table', 'lc-addons-kit-for-elementor' ),
					__( 'Pricing Grid', 'lc-addons-kit-for-elementor' ),
					__( 'Price Menu', 'lc-addons-kit-for-elementor' ),
					__( 'Progress Circle', 'lc-addons-kit-for-elementor' ),
					__( 'Chart', 'lc-addons-kit-for-elementor' ),
			),
			__( 'Media', 'lc-addons-kit-for-elementor' )         => array(
					__( 'Advanced Slider', 'lc-addons-kit-for-elementor' ),
					__( 'Masonry Gallery', 'lc-addons-kit-for-elementor' ),
					__( 'Logo Carousel', 'lc-addons-kit-for-elementor' ),
					__( 'Google Maps Pro', 'lc-addons-kit-for-elementor' ),
			),
			__( 'Creative', 'lc-addons-kit-for-elementor' )      => array(
					__( 'Image Hotspot', 'lc-addons-kit-for-elementor' ),
					__( 'Image Hover', 'lc-addons-kit-for-elementor' ),
					__( 'Interactive Card', 'lc-addons-kit-for-elementor' ),
					__( 'Interactive Links', 'lc-addons-kit-for-elementor' ),
					__( 'Stacked Cards', 'lc-addons-kit-for-elementor' ),
					__( 'Circle Menu', 'lc-addons-kit-for-elementor' ),
					__( 'Unfold', 'lc-addons-kit-for-elementor' ),
					__( 'Table of Contents', 'lc-addons-kit-for-elementor' ),
			),
			__( 'Marketing & Social', 'lc-addons-kit-for-elementor' ) => array(
					__( 'Notification Bar', 'lc-addons-kit-for-elementor' ),
					__( 'Evergreen Countdown', 'lc-addons-kit-for-elementor' ),
					__( 'Coupon', 'lc-addons-kit-for-elementor' ),
					__( 'WhatsApp Chat', 'lc-addons-kit-for-elementor' ),
					__( 'Google Reviews', 'lc-addons-kit-for-elementor' ),
					__( 'Yelp Reviews', 'lc-addons-kit-for-elementor' ),
					__( 'Advanced Search', 'lc-addons-kit-for-elementor' ),
			),
		);
	}

	/**
	 * Prints a table cell value.
	 *
	 * @param bool|int|string $value Cell value.
	 * @return void
	 */
	private function print_cell( $value ) {
		if ( true === $value ) {
			echo '<svg class="lcake-fvp-check" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="12"/><path d="M7 12.5l3.2 3.2L17 9" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
			echo '<span class="screen-reader-text">' . esc_html__( 'Included', 'lc-addons-kit-for-elementor' ) . '</span>';
		} elseif ( false === $value ) {
			echo '<span class="lcake-fvp-dash" aria-hidden="true"></span>';
			echo '<span class="screen-reader-text">' . esc_html__( 'Not included', 'lc-addons-kit-for-elementor' ) . '</span>';
		} elseif ( is_int( $value ) ) {
			echo '<span class="lcake-fvp-value">' . esc_html( number_format_i18n( $value ) ) . '</span>';
		} else {
			echo '<span class="lcake-fvp-value">' . esc_html( $value ) . '</span>';
		}
	}

	/**
	 * Prints a feature label, showing any trailing "(details)" as muted text.
	 *
	 * @param string $label Feature label.
	 * @return void
	 */
	private function print_feature( $label ) {
		if ( preg_match( '/^(.+?)\s*\((.+)\)$/u', $label, $m ) ) {
			echo '<span class="lcake-fvp-name">' . esc_html( $m[1] ) . '</span>';
			echo '<span class="lcake-fvp-detail">' . esc_html( $m[2] ) . '</span>';
		} else {
			echo '<span class="lcake-fvp-name">' . esc_html( $label ) . '</span>';
		}
	}

	/**
	 * Renders the page.
	 *
	 * @return void
	 */
	public function render_page() {
		$free_count = $this->free_widget_count();
		$extra      = array_sum( array_map( 'count', $this->get_pro_widgets() ) );
		$pro_count  = $free_count + $extra;
		$pro_url    = LCAKE_EAK_PRO_URL;
		?>
		<div class="wrap lcake-fvp">
			<header class="lcake-fvp-header">
				<div class="lcake-fvp-intro">
					<span class="lcake-fvp-eyebrow"><?php esc_html_e( 'Compare plans', 'lc-addons-kit-for-elementor' ); ?></span>
					<h1><?php esc_html_e( 'LC Kit Free vs Pro', 'lc-addons-kit-for-elementor' ); ?></h1>
					<p><?php esc_html_e( 'See what you get today and what Pro adds to your Elementor workflow.', 'lc-addons-kit-for-elementor' ); ?></p>
				</div>
				<a class="lcake-fvp-cta" href="<?php echo esc_url( $pro_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'View Pro Pricing', 'lc-addons-kit-for-elementor' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</header>
			<hr class="wp-header-end">

			<ul class="lcake-fvp-stats">
				<li>
					<strong><?php echo esc_html( number_format_i18n( $pro_count ) ); ?></strong>
					<span><?php esc_html_e( 'Widgets in Pro', 'lc-addons-kit-for-elementor' ); ?></span>
				</li>
				<li>
					<strong>+<?php echo esc_html( number_format_i18n( $extra ) ); ?></strong>
					<span><?php esc_html_e( 'Pro-only widgets', 'lc-addons-kit-for-elementor' ); ?></span>
				</li>
				<li>
					<strong><?php esc_html_e( 'AI', 'lc-addons-kit-for-elementor' ); ?></strong>
					<span><?php esc_html_e( 'Website Builder', 'lc-addons-kit-for-elementor' ); ?></span>
				</li>
			</ul>

			<div class="lcake-fvp-card">
				<table class="lcake-fvp-table">
					<caption class="screen-reader-text"><?php esc_html_e( 'LC Kit Free vs Pro feature comparison', 'lc-addons-kit-for-elementor' ); ?></caption>
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Feature', 'lc-addons-kit-for-elementor' ); ?></th>
							<th scope="col" class="lcake-fvp-col">
								<?php esc_html_e( 'Free', 'lc-addons-kit-for-elementor' ); ?>
								<span class="lcake-fvp-tag"><?php esc_html_e( 'Installed', 'lc-addons-kit-for-elementor' ); ?></span>
							</th>
							<th scope="col" class="lcake-fvp-col lcake-fvp-pro">
								<?php esc_html_e( 'Pro', 'lc-addons-kit-for-elementor' ); ?>
								<span class="lcake-fvp-tag"><?php esc_html_e( 'Recommended', 'lc-addons-kit-for-elementor' ); ?></span>
							</th>
						</tr>
					</thead>
					<?php foreach ( $this->get_features( $free_count, $pro_count ) as $section => $rows ) : ?>
						<tbody>
							<tr class="lcake-fvp-section">
								<th scope="colgroup" colspan="2"><?php echo esc_html( $section ); ?></th>
								<td class="lcake-fvp-pro"></td>
							</tr>
							<?php foreach ( $rows as $row ) : ?>
								<tr>
									<th scope="row"><?php $this->print_feature( $row[0] ); ?></th>
									<td class="lcake-fvp-col"><?php $this->print_cell( $row[1] ); ?></td>
									<td class="lcake-fvp-col lcake-fvp-pro"><?php $this->print_cell( $row[2] ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					<?php endforeach; ?>
					<tfoot>
						<tr>
							<th scope="row"><?php esc_html_e( 'Ready to unlock everything?', 'lc-addons-kit-for-elementor' ); ?></th>
							<td class="lcake-fvp-col"><span class="lcake-fvp-current"><?php esc_html_e( 'Current plan', 'lc-addons-kit-for-elementor' ); ?></span></td>
							<td class="lcake-fvp-col lcake-fvp-pro">
								<a class="lcake-fvp-upgrade" href="<?php echo esc_url( $pro_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Get Pro', 'lc-addons-kit-for-elementor' ); ?></a>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>

			<p class="lcake-fvp-footnote"><?php esc_html_e( 'Pro runs alongside Free. Some widgets require third-party plugins, such as WooCommerce or a supported form plugin.', 'lc-addons-kit-for-elementor' ); ?></p>
		</div>
		<?php
	}
}
