<?php

defined( 'ABSPATH' ) || exit;

/**
 * Global helper class.
 *
 * @since 1.0.0
 */

class LCAKE_Kit_Utils {


	/**
	 * Returns an array of allowed HTML tags and attributes for wp_kses().
	 * 
	 * Allows standard formatting tags (a, b, strong, i, em, span, div, p, etc.)
	 * and safe attributes like class, style, href, title.
	 *
	 * @since 1.0.0
	 * @return array Array of allowed tags and attributes.
	 */
	public static function get_kses_array() {
		return array(
			'a'                             => array(
				'class'  => array(),
				'href'   => array(),
				'rel'    => array(),
				'title'  => array(),
				'target' => array(),
				'style'  => array(),
			),
			'abbr'                          => array(
				'title' => array(),
			),
			'b'                             => array(
				'class' => array(),
			),
			'blockquote'                    => array(
				'cite' => array(),
			),
			'cite'                          => array(
				'title' => array(),
			),
			'code'                          => array(),
			'pre'                           => array(),
			'del'                           => array(
				'datetime' => array(),
				'title'    => array(),
			),
			'dd'                            => array(),
			'div'                           => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'dl'                            => array(),
			'dt'                            => array(),
			'em'                            => array(),
			'strong'                        => array(),
			'h1'                            => array(
				'class' => array(),
			),
			'h2'                            => array(
				'class' => array(),
			),
			'h3'                            => array(
				'class' => array(),
			),
			'h4'                            => array(
				'class' => array(),
			),
			'h5'                            => array(
				'class' => array(),
			),
			'h6'                            => array(
				'class' => array(),
			),
			'i'                             => array(
				'class' => array(),
			),
			'img'                           => array(
				'alt'     => array(),
				'class'   => array(),
				'height'  => array(),
				'src'     => array(),
				'width'   => array(),
				'style'   => array(),
				'title'   => array(),
				'srcset'  => array(),
				'loading' => array(),
				'sizes'   => array(),
			),
			'figure'                        => array(
				'class' => array(),
			),
			'li'                            => array(
				'class' => array(),
			),
			'ol'                            => array(
				'class' => array(),
			),
			'p'                             => array(
				'class' => array(),
			),
			'q'                             => array(
				'cite'  => array(),
				'title' => array(),
			),
			'span'                          => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'iframe'                        => array(
				'width'       => array(),
				'height'      => array(),
				'scrolling'   => array(),
				'frameborder' => array(),
				'allow'       => array(),
				'src'         => array(),
			),
			'strike'                        => array(),
			'br'                            => array(),
			'table'                         => array(),
			'thead'                         => array(),
			'tbody'                         => array(),
			'tfoot'                         => array(),
			'tr'                            => array(),
			'th'                            => array(
				'class'   => true,
				'colspan' => true,
				'rowspan' => true,
				'style'   => true,
				'id'      => true,
			),
			'td'                            => array(
				'class'   => true,
				'colspan' => true,
				'rowspan' => true,
				'style'   => true,
				'id'      => true,
			),
			'caption'                       => array(),
			'col'                           => array(
				'span'  => true,
				'style' => true,
			),
			'colgroup'                      => array(
				'span'  => true,
				'style' => true,
			),
			'strong'                        => array(),
			'data-wow-duration'             => array(),
			'data-wow-delay'                => array(),
			'data-wallpaper-options'        => array(),
			'data-stellar-background-ratio' => array(),
			'ul'                            => array(
				'class' => array(),
			),
			'svg'                           => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case! 'preserveaspectratio'=> true,
			),
			'g'                             => array( 'fill' => true ),
			'title'                         => array( 'title' => true ),
			'path'                          => array(
				'd'    => true,
				'fill' => true,
			),
			'input'                         => array(
				'class' => array(),
				'type'  => array(),
				'value' => array(),
			),
		);
	}

	/**
	 * Sanitizes HTML content using the plugin's predefined allowed tags.
	 *
	 * @since 1.0.0
	 * @param string $raw The raw HTML input string.
	 * @return string Sanitized HTML string.
	 */
	public static function kses( $raw ) {

		$allowed_tags = self::get_kses_array();

		if ( function_exists( 'wp_kses' ) ) { // WP is here
			return wp_kses( $raw, $allowed_tags );
		} else {
			return $raw;
		}
	}

	/**
	 * Replaces curly braces with span tags for highlighted text.
	 * 
	 * Example: "Hello {world}" becomes "Hello <span>world</span>".
	 *
	 * @since 1.0.0
	 * @param string $text The input text containing curly braces.
	 * @return string The processed text with span tags.
	 */
	public static function kspan( $text ) {
		return str_replace( array( '{', '}' ), array( '<span>', '</span>' ), $text );
	}

	public static function lcake_get_ninja_form() {
		$options = array();

		if ( class_exists( 'Ninja_Forms' ) ) {
			$contact_forms = Ninja_Forms()->form()->get_forms();

			if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

				$options[0] = esc_html__( 'Select Ninja Form', 'lc-addons-kit-for-elementor' );

				foreach ( $contact_forms as $form ) {
					$options[ $form->get_id() ] = $form->get_setting( 'title' );
				}
			}
		} else {
			$options[0] = esc_html__( 'Create a Form First', 'lc-addons-kit-for-elementor' );
		}

		return $options;
	}

	public static function render_elementor_content_css( $content_id ) {
		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$css_file = new \Elementor\Core\Files\CSS\Post( $content_id );
			$css_file->enqueue();
		}
	}

	/**
	 * Force-enqueues the default style/script dependencies for every widget
	 * used inside a given Elementor document (e.g. a header/footer template).
	 *
	 * render_elementor_content_css() only enqueues that document's *custom*
	 * per-post CSS (Style tab overrides) — it does not load a widget's
	 * default stylesheet (e.g. widget-icon-list.min.css). Elementor normally
	 * loads those default files only when it scans the page being rendered
	 * through the main WP loop, which never happens for our header/footer
	 * templates since they're injected via get_header/get_footer instead.
	 * This walks the document's own element tree and enqueues each used
	 * widget's declared dependencies directly, so it stays correct for any
	 * widget added to the header/footer in future — not just icon-list.
	 *
	 * @since 1.3.0
	 * @param int $content_id The Elementor document/template post ID.
	 * @return void
	 */
	public static function enqueue_elementor_widget_depends( $content_id ) {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		$document = \Elementor\Plugin::$instance->documents->get( $content_id );
		if ( ! $document ) {
			return;
		}

		self::enqueue_widget_depends_recursive( $document->get_elements_data() );
	}

	/**
	 * Walks an Elementor element tree and enqueues every widget's declared
	 * style and script dependencies.
	 *
	 * @since 1.3.0
	 * @param array $elements Elementor elements data (nested via 'elements').
	 * @return void
	 */
	private static function enqueue_widget_depends_recursive( $elements ) {
		if ( empty( $elements ) || ! is_array( $elements ) ) {
			return;
		}

		$widgets_manager = \Elementor\Plugin::$instance->widgets_manager;

		foreach ( $elements as $element ) {
			if ( ! empty( $element['widgetType'] ) ) {
				$widget = $widgets_manager->get_widget_types( $element['widgetType'] );
				if ( $widget ) {
					foreach ( (array) $widget->get_style_depends() as $style_handle ) {
						wp_enqueue_style( $style_handle );
					}
					foreach ( (array) $widget->get_script_depends() as $script_handle ) {
						wp_enqueue_script( $script_handle );
					}
				}
			}

			if ( ! empty( $element['elements'] ) ) {
				self::enqueue_widget_depends_recursive( $element['elements'] );
			}
		}
	}

	public static function render_elementor_content( $content_id ) {
		$elementor_instance = \Elementor\Plugin::instance();
		$has_css            = false;

		/**
		 * CSS Print Method Internal and Exteral option support for Header and Footer Builder.
		 */
		if ( ( 'internal' === get_option( 'elementor_css_print_method' ) ) || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$has_css = true;
		}

		return $elementor_instance->frontend->get_builder_content_for_display( $content_id, $has_css );
	}

	public static function render( $content ) {
		if ( stripos( $content, 'lcake-has-license' ) !== false ) {
			return null;
		}

		return $content;
	}

	public static function esc_options( $str, $options = array(), $default = '' ) {
		if ( ! in_array( $str, $options ) ) {
			return $default;
		}

		return $str;
	}

	public static function get_attachment_image_html( $settings, $image_key, $image_size_key = null, $image_attr = array() ) {
		if ( ! $image_key ) {
			$image_key = $image_size_key;
		}

		$image = $settings[ $image_key ];

		$size = $image_size_key;

		$html = '';
		if ( ! empty( $image['id'] ) && -1 !== (int) $image['id'] && get_post( $image['id'] ) ) {
			$html .= wp_get_attachment_image( $image['id'], $size, false, $image_attr );
		} else {
			$html .= sprintf(
				'<img src="%s" title="%s" alt="%s" class="%s" />',
				esc_attr( $image['url'] ),
				\Elementor\Control_Media::get_image_title( $image ),
				\Elementor\Control_Media::get_image_alt( $image ),
				( isset( $image_attr['class'] ) ? esc_attr( $image_attr['class'] ) : '' )
			);
		}

		$html = preg_replace( array( '/max-width:[^"]*;/', '/width:[^"]*;/', '/height:[^"]*;/' ), '', $html );

		return $html;
	}

	public static function swiper_class() {
		return 'lcake-main-swiper swiper';
	}

	public static function remove_special_chars( $string ) {
		return preg_replace( '/[^A-Za-z0-9 ]/', '', (string) $string );
	}

	public static function is_woo_active() {
		return class_exists( 'WooCommerce' );
	}

	public static function get_woo_product( $product_id = 0 ) {
		if ( ! self::is_woo_active() ) {
			return false;
		}

		if ( ! empty( $product_id ) ) {
			return wc_get_product( $product_id );
		}

		global $product;
		if ( $product instanceof \WC_Product ) {
			return $product;
		}

		$queried_id = get_the_ID();
		if ( $queried_id && 'product' === get_post_type( $queried_id ) ) {
			return wc_get_product( $queried_id );
		}

		return false;
	}

	public static function get_woo_product_options() {
		$options = array( 0 => esc_html__( 'Current Product (auto)', 'lc-addons-kit-for-elementor' ) );

		if ( ! self::is_woo_active() ) {
			return $options;
		}

		$products = get_posts(
			array(
				'post_type'      => 'product',
				'posts_per_page' => 200,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		foreach ( $products as $product ) {
			$options[ $product->ID ] = $product->post_title;
		}

		return $options;
	}

	public static function woo_inactive_notice() {
		self::plugin_inactive_notice( 'WooCommerce' );
	}

	public static function plugin_inactive_notice( $plugin_name ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		echo '<p class="lcake-plugin-notice">' . sprintf(
			/* translators: %s: plugin name */
			esc_html__( '%s needs to be installed & activated to use this widget.', 'lc-addons-kit-for-elementor' ),
			esc_html( $plugin_name )
		) . '</p>';
	}

	public static function lcake_get_gravity_forms() {
		$options = array( 0 => esc_html__( 'Select Form', 'lc-addons-kit-for-elementor' ) );

		if ( class_exists( 'GFAPI' ) ) {
			$forms = \GFAPI::get_forms();
			foreach ( $forms as $form ) {
				$options[ $form['id'] ] = $form['title'];
			}
		}

		return $options;
	}

	public static function lcake_get_wpforms() {
		$options = array( 0 => esc_html__( 'Select Form', 'lc-addons-kit-for-elementor' ) );

		if ( function_exists( 'wpforms' ) ) {
			$forms = get_posts(
				array(
					'post_type'      => 'wpforms',
					'posts_per_page' => 200,
				)
			);
			foreach ( $forms as $form ) {
				$options[ $form->ID ] = $form->post_title;
			}
		}

		return $options;
	}

	public static function lcake_get_fluent_forms() {
		$options = array( 0 => esc_html__( 'Select Form', 'lc-addons-kit-for-elementor' ) );

		if ( defined( 'FLUENTFORM' ) || function_exists( 'wpFluentForm' ) ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'fluentform_forms';
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) === $table_name ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$forms = $wpdb->get_results( $wpdb->prepare( 'SELECT id, title FROM `%1s` ORDER BY id DESC', $table_name ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder -- table name is built from $wpdb->prefix + hardcoded string; cannot be auto-quoted.
				if ( ! empty( $forms ) ) {
					foreach ( $forms as $form ) {
						$options[ $form['id'] ] = $form['title'];
					}
				}
			}
		}

		return $options;
	}

	public static function lcake_get_weforms() {
		$options = array( 0 => esc_html__( 'Select Form', 'lc-addons-kit-for-elementor' ) );

		if ( class_exists( 'WeForms' ) ) {
			$forms = get_posts(
				array(
					'post_type'      => 'wpuf_contact_form',
					'posts_per_page' => 200,
				)
			);
			foreach ( $forms as $form ) {
				$options[ $form->ID ] = $form->post_title;
			}
		}

		return $options;
	}

	/**
	 * @return array Public post type slug => label, excluding attachments — the
	 *               `post_type` SELECT options shared by every post-query widget (Post
	 *               Grid, Post List, Post Carousel, Blog Posts, Post Timeline).
	 */
	public static function get_public_post_type_options() {
		$options = array();

		foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $post_type ) {
			if ( 'attachment' === $post_type->name ) {
				continue;
			}
			$options[ $post_type->name ] = $post_type->label;
		}

		return $options;
	}

	/**
	 * Registers the "Columns" and "Rows" repeater controls shared by the Data Table and
	 * Advanced Data Table widgets — same column_label/comma-separated-row_cells shape in
	 * both, differing only in each widget's own sample defaults.
	 *
	 * @param \Elementor\Widget_Base $widget
	 * @param array  $columns_default          Default `columns` repeater rows.
	 * @param array  $rows_default             Default `rows` repeater rows.
	 * @param string $row_cells_field_default  Default value of a freshly added row's
	 *                                         `row_cells` field (shown in the editor
	 *                                         before the site owner fills it in).
	 */
	public static function add_table_columns_rows_controls( \Elementor\Widget_Base $widget, array $columns_default, array $rows_default, $row_cells_field_default = '' ) {
		$col_repeater = new \Elementor\Repeater();
		$col_repeater->add_control(
			'column_label',
			array(
				'label'   => esc_html__( 'Column Label', 'lc-addons-kit-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Column', 'lc-addons-kit-for-elementor' ),
			)
		);

		$widget->add_control(
			'columns',
			array(
				'label'       => esc_html__( 'Columns', 'lc-addons-kit-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $col_repeater->get_controls(),
				'default'     => $columns_default,
				'title_field' => '{{{ column_label }}}',
			)
		);

		$row_repeater = new \Elementor\Repeater();
		$row_repeater->add_control(
			'row_cells',
			array(
				'label'       => esc_html__( 'Row Cells (comma separated)', 'lc-addons-kit-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $row_cells_field_default,
				'label_block' => true,
			)
		);

		$widget->add_control(
			'rows',
			array(
				'label'       => esc_html__( 'Rows', 'lc-addons-kit-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $row_repeater->get_controls(),
				'default'     => $rows_default,
				'title_field' => '{{{ row_cells }}}',
			)
		);
	}

	/**
	 * @param array $row One `rows` repeater item ({row_cells: 'a, b, c'}).
	 * @return string[] Trimmed cell values, in column order.
	 */
	public static function split_table_row_cells( array $row ) {
		return array_map( 'trim', explode( ',', $row['row_cells'] ?? '' ) );
	}

	/**
	 * A helper method to register and optionally enqueue a list of styles or scripts.
	 *
	 * @since 1.0.0
	 * @param array  $scripts  An associative array of script/style data arrays.
	 * @param string $file     The type of file ('script' for JS, 'style' for CSS).
	 * @param string $base_url Base URL assets are relative to. Defaults to this plugin's
	 *                         own URL for backward compatibility; pass another plugin's
	 *                         URL (e.g. a Pro add-on's `plugin_dir_url()`) to reuse this
	 *                         helper for that plugin's own assets instead of duplicating
	 *                         this loop.
	 * @param string $version  Asset version for cache-busting. Defaults to this plugin's
	 *                         version; pass the other plugin's version with $base_url.
	 * @since 1.3.0 Added the `$base_url` and `$version` parameters.
	 * @return void
	 */
	public static function lcake_file_enqueue( $scripts, $file, $base_url = null, $version = null ) {
		$register_func = 'wp_register_' . $file;
		$enqueue_func  = 'wp_enqueue_' . $file;
		$folder        = ( 'script' === $file ) ? 'js' : 'css';
		$base_url      = null === $base_url ? LCAKE_EAK_URL : $base_url;
		$version       = null === $version ? LCAKE_EAK_VERSION : $version;

		foreach ( $scripts as $handle => $data ) {
			$path = isset( $data['path'] ) && '' !== $data['path'] ? $data['path'] : 'assets/' . $folder;
			$url  = $base_url . $path . '/' . $data['file'];

			$deps               = $data['deps'] ?? array();
			$in_footer_or_media = 'script' === $file ? true : ( $data['media'] ?? 'all' );

			$register_func( $handle, $url, $deps, $version, $in_footer_or_media );

			if ( ! empty( $data['enqueue'] ) ) {
				$enqueue_func( $handle );
			}
		}
	}
}
