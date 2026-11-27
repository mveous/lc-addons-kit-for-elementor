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
 * @since 1.3.0
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
	 * Feature rows for the comparison table.
	 *
	 * @return array[] Feature rows grouped by section.
	 */
	private function get_features() {
		return array(
			__( 'Builder and extensions', 'lc-addons-kit-for-elementor' ) => array(
				array(
					'label'       => __( 'AI Website Builder', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Connect Claude or ChatGPT to build and edit Elementor pages, headers and footers as drafts.', 'lc-addons-kit-for-elementor' ),
					'free'        => false,
					'pro'         => true,
				),
				array(
					'label'       => __( 'Popups', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Open an Elementor template as a popup using the Popup Trigger extension.', 'lc-addons-kit-for-elementor' ),
					'free'        => false,
					'pro'         => true,
				),
				array(
					'label'       => __( 'Extra features for free widgets', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Post Grid filtering and Reveal More, dynamic sources for Advanced Data Table, Countdown Timer expiry actions, Fancy Text typewriter effects, Button hover effects, Icon Box hover animations and ribbons, Image Accordion autoplay, and Image Comparison auto demo.', 'lc-addons-kit-for-elementor' ),
					'free'        => false,
					'pro'         => true,
				),
				array(
					'label'       => __( 'Dynamic tags', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Use ACF field values and WooCommerce prices in Elementor controls that support dynamic tags.', 'lc-addons-kit-for-elementor' ),
					'free'        => false,
					'pro'         => true,
				),
			),
			__( 'Core tools', 'lc-addons-kit-for-elementor' ) => array(
				array(
					'label'       => __( 'Header & Footer Builder', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Design site-wide headers and footers in Elementor, with display conditions.', 'lc-addons-kit-for-elementor' ),
					'free'        => true,
					'pro'         => true,
				),
				array(
					'label'       => __( 'Widget Manager', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Turn individual widgets on or off to keep the editor fast.', 'lc-addons-kit-for-elementor' ),
					'free'        => true,
					'pro'         => true,
				),
				array(
					'label'       => __( 'WooCommerce widgets', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Product grids, carousels, cart, checkout and single-product parts.', 'lc-addons-kit-for-elementor' ),
					'free'        => true,
					'pro'         => true,
				),
				array(
					'label'       => __( 'Form integrations', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Style forms from plugins such as Contact Form 7, Fluent Forms, Gravity Forms and WPForms.', 'lc-addons-kit-for-elementor' ),
					'free'        => true,
					'pro'         => true,
				),
			),
			__( 'Content and marketing', 'lc-addons-kit-for-elementor' ) => array(
				array(
					'label'       => __( 'Post layouts', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Blog Posts supports AJAX Load More and Infinite Scroll.', 'lc-addons-kit-for-elementor' ),
					'free'        => __( 'Post Grid and Post Timeline', 'lc-addons-kit-for-elementor' ),
					'pro'         => __( 'Adds Blog Posts, Post Carousel, Post List and Related Posts', 'lc-addons-kit-for-elementor' ),
				),
				array(
					'label'       => __( 'Marketing widgets', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Calls to action, countdowns and promotional widgets.', 'lc-addons-kit-for-elementor' ),
					'free'        => __( 'CTA Box, Countdown Timer and Pricing Table', 'lc-addons-kit-for-elementor' ),
					'pro'         => __( 'Adds Notification Bar, Evergreen Countdown, Coupon and WhatsApp Chat', 'lc-addons-kit-for-elementor' ),
				),
				array(
					'label'       => __( 'Review widgets', 'lc-addons-kit-for-elementor' ),
					'description' => __( 'Display testimonials or business reviews.', 'lc-addons-kit-for-elementor' ),
					'free'        => __( 'Testimonial and Business Reviews', 'lc-addons-kit-for-elementor' ),
					'pro'         => __( 'Adds Google Reviews and Yelp Reviews', 'lc-addons-kit-for-elementor' ),
				),
			),
		);
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
	 * Prints a table cell value: a check/cross icon for booleans, text otherwise.
	 *
	 * @param bool|string $value Cell value.
	 * @return void
	 */
	private function print_cell( $value ) {
		if ( true === $value ) {
			echo '<span class="lcake-fvp-included"><span aria-hidden="true">✓</span> ' . esc_html__( 'Included', 'lc-addons-kit-for-elementor' ) . '</span>';
		} elseif ( false === $value ) {
			echo '<span class="lcake-fvp-unavailable">' . esc_html__( 'Pro only', 'lc-addons-kit-for-elementor' ) . '</span>';
		} else {
			echo '<span class="lcake-fvp-value">' . esc_html( $value ) . '</span>';
		}
	}

	/**
	 * Renders the page.
	 *
	 * @return void
	 */
	public function render_page() {
		$pro_url     = LCAKE_EAK_PRO_URL;
		$free_count  = $this->free_widget_count();
		$pro_widgets = $this->get_pro_widgets();
		$pro_count   = array_sum( array_map( 'count', $pro_widgets ) );
		?>
		<div class="wrap lcake-fvp">
			<header class="lcake-fvp-hero">
				<div>
					<span class="lcake-fvp-badge"><?php esc_html_e( 'Feature Breakdown', 'lc-addons-kit-for-elementor' ); ?></span>
					<h1><?php esc_html_e( 'LC Kit: Free vs Pro', 'lc-addons-kit-for-elementor' ); ?></h1>
					<p><?php esc_html_e( 'Compare the widgets, builder tools, and Elementor extensions included in each edition.', 'lc-addons-kit-for-elementor' ); ?></p>
				</div>
				<a class="button lcake-fvp-cta" href="<?php echo esc_url( $pro_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Get LC Kit Pro', 'lc-addons-kit-for-elementor' ); ?> <span aria-hidden="true">→</span></a>
			</header>

			<p class="lcake-fvp-note">
				<span class="dashicons dashicons-info-outline" aria-hidden="true"></span>
				<span><strong><?php esc_html_e( 'Pro runs alongside Free.', 'lc-addons-kit-for-elementor' ); ?></strong> <?php esc_html_e( 'Some widgets require third-party plugins, such as WooCommerce or a supported form plugin.', 'lc-addons-kit-for-elementor' ); ?></span>
			</p>

			<div class="lcake-fvp-editions">
				<div class="lcake-fvp-edition">
					<span class="lcake-fvp-edition-label"><?php esc_html_e( 'Installed', 'lc-addons-kit-for-elementor' ); ?></span>
					<h2><?php esc_html_e( 'LC Kit Free', 'lc-addons-kit-for-elementor' ); ?></h2>
					<p><strong><?php echo esc_html( number_format_i18n( $free_count ) ); ?></strong> <?php esc_html_e( 'Widgets', 'lc-addons-kit-for-elementor' ); ?></p>
					<span class="lcake-fvp-shared"><?php esc_html_e( '✓ Core features included', 'lc-addons-kit-for-elementor' ); ?></span>
				</div>
				<div class="lcake-fvp-edition lcake-fvp-edition-pro">
					<span class="lcake-fvp-badge"><?php esc_html_e( 'Supercharged', 'lc-addons-kit-for-elementor' ); ?></span>
					<h2><?php esc_html_e( 'LC Kit Pro', 'lc-addons-kit-for-elementor' ); ?></h2>
					<p><strong><?php echo esc_html( number_format_i18n( $free_count + $pro_count ) ); ?></strong> <?php esc_html_e( 'Widgets', 'lc-addons-kit-for-elementor' ); ?></p>
					<?php
					/* translators: %s: number of additional Pro widgets. */
					echo esc_html( sprintf( __( '+%s Pro-exclusive widgets', 'lc-addons-kit-for-elementor' ), number_format_i18n( $pro_count ) ) );
					?>
				</div>
			</div>

			<?php
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only navigation.
			$show_widgets = isset( $_GET['view'] ) && 'widgets' === $_GET['view'];
			$page_url     = add_query_arg( 'page', self::PAGE_SLUG, admin_url( 'admin.php' ) );
			?>
			<nav class="lcake-fvp-nav" aria-label="<?php esc_attr_e( 'Comparison views', 'lc-addons-kit-for-elementor' ); ?>">
				<a href="<?php echo esc_url( $page_url ); ?>" <?php if ( ! $show_widgets ) { echo 'aria-current="page"'; } ?>><?php esc_html_e( 'Compare Features', 'lc-addons-kit-for-elementor' ); ?></a>
				<a href="<?php echo esc_url( add_query_arg( 'view', 'widgets', $page_url ) ); ?>" <?php if ( $show_widgets ) { echo 'aria-current="page"'; } ?>>
					<?php
					/* translators: %s: number of additional Pro widgets. */
					echo esc_html( sprintf( __( '%s Pro Widgets', 'lc-addons-kit-for-elementor' ), number_format_i18n( $pro_count ) ) );
					?>
				</a>
			</nav>

			<?php if ( $show_widgets ) : ?>
				<div class="lcake-fvp-groups">
					<?php foreach ( $pro_widgets as $group => $widgets ) : ?>
						<section class="lcake-fvp-panel">
							<h2><?php echo esc_html( $group ); ?></h2>
							<ul>
								<?php foreach ( $widgets as $widget ) : ?>
									<li><?php echo esc_html( $widget ); ?></li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<?php foreach ( $this->get_features() as $section => $features ) : ?>
					<section class="lcake-fvp-panel">
						<h2><?php echo esc_html( $section ); ?></h2>
						<table class="lcake-fvp-table" role="table">
							<caption class="screen-reader-text"><?php echo esc_html( $section ); ?></caption>
							<thead role="rowgroup">
								<tr role="row">
									<th role="columnheader" scope="col"><?php esc_html_e( 'Feature', 'lc-addons-kit-for-elementor' ); ?></th>
									<th role="columnheader" scope="col"><?php esc_html_e( 'LC Kit Free', 'lc-addons-kit-for-elementor' ); ?></th>
									<th role="columnheader" scope="col"><?php esc_html_e( 'LC Kit Pro', 'lc-addons-kit-for-elementor' ); ?></th>
								</tr>
							</thead>
							<tbody role="rowgroup">
								<?php foreach ( $features as $feature ) : ?>
									<tr role="row">
										<th role="rowheader" scope="row">
											<span class="lcake-fvp-feature"><?php echo esc_html( $feature['label'] ); ?></span>
											<span class="lcake-fvp-desc"><?php echo esc_html( $feature['description'] ); ?></span>
										</th>
										<td role="cell">
											<span class="lcake-fvp-mobile-label" aria-hidden="true"><?php esc_html_e( 'LC Kit Free', 'lc-addons-kit-for-elementor' ); ?></span>
											<?php $this->print_cell( $feature['free'] ); ?>
										</td>
										<td role="cell" class="lcake-fvp-pro-cell">
											<span class="lcake-fvp-mobile-label" aria-hidden="true"><?php esc_html_e( 'LC Kit Pro', 'lc-addons-kit-for-elementor' ); ?></span>
											<?php $this->print_cell( $feature['pro'] ); ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</section>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php
	}
}
