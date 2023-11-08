<?php

	namespace WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc;

	use WpCafe\Utils\Wpc_Utilities;
	use WpCafe_Pro\Utils\Utilities as Pro_Utilities;

	defined( "ABSPATH" ) || exit;
	/**
	 * Main functionality
	 */
	class Addon_Control {

		use \WpCafe_Pro\Traits\Singleton;

		/**
		 * Call hooks
		 */
		public function init() {
			if ( class_exists( 'WooCommerce' ) ) {
				add_action( 'wp_enqueue_scripts', [$this, 'wpc_addon_scripts'] );
				add_action( 'woocommerce_before_add_to_cart_button', [$this, 'show_customizable_options'] );
			}
		}

		/**
		 * Addons list.
		 *
		 * @param [type] $post_id get params.
		 * @param [type] $prefix get params.
		 */
		public function show_customizable_options( $post_id = false, $prefix = false ) {
			global $product;

			if ( ! $post_id ) {
				global $post;
				$post_id = $post->ID;
			}

			$supported_types = [
				'simple',
				'variable',
			];

			// right now, only supported types are simple, variable
			if ( ! in_array( $product->get_type(), $supported_types ) ) {
				return;
			}

			$wpc_addons = $this->all_addons_list( $post_id, $prefix = false );

			if ( is_array( $wpc_addons ) && count( $wpc_addons ) > 0 ) {
				echo '<div class="wpc-addons-container">';
				$counter = 0;
				foreach ( $wpc_addons as $addon ) {

					$addon['field_name'] = sanitize_title( $post_id . '-' . $addon['title'] . '-' . $counter );
					wc_get_template( 'templates/addon-start.php', ['addon' => $addon],
						'wpcafe-pro', \Wpcafe_Pro::plugin_dir() . '/core/modules/product-addons/frontend/' );

					echo Wpc_Utilities::wpc_render( $this->get_wpc_addon_html( $addon ) );
					wc_get_template( 'templates/addon-end.php', ['addon' => $addon], 'wpcafe-pro', \Wpcafe_Pro::plugin_dir() . '/core/modules/product-addons/frontend/' );

					$counter++;
				}
				$this->calculate_options_totals();
				echo '</div>';
			}
		}

		/**
		 * Merge Local and global list
		 *
		 * @param [type] $post_id get params.
		 * @param [type] $prefix get params.
		 */
		public function all_addons_list( $post_id, $prefix = false ) {
			include_once plugin_dir_path( __FILE__ ) . '/base/addons-field.php';
			include_once plugin_dir_path( __FILE__ ) . '/options/addons-field-list.php';

			$field_content = new \Product_Addons_Field_List( ['addon' => [], 'value' => [], 'option_type' => ''] );

			// Single page addons.
			$wpc_addons = Addon_Helper::get_wpc_addon_data( $post_id, $prefix );
			// Global  addons.
			$global_addons = $field_content->get_global_addon_data( $post_id );
			if ( ! empty( $global_addons ) ) {
				$wpc_addons = array_merge( $global_addons, $wpc_addons );
			}

			// Cafe multi-vendor addons exists. so it will shows both addons from
			// pro and vendor.
			if ( class_exists( 'Wpcafe_Multivendor' ) ) {
				$pro_global_addons = $field_content->get_global_addon_data( $post_id, true );
				return array_merge( $wpc_addons, $pro_global_addons );
			}

			return $wpc_addons;
		}

		public function calculate_options_totals() {
			global $product;

			$product_id   = $product->get_id();
			$product_type = $product->get_type() === 'variation' ? 'variable' : $product->get_type();

			$product_price = wc_get_price_to_display( $product );

			// price change if discount is applied.
			$is_discount_product = false;
			$discount_percentage = 0;

			$vendor_id           = get_post_field( 'post_author', $product_id );
			$discount_price_args = array(
				'product_id'    => $product->get_id(),
				'data'          => 'wpc_pro_single_page',
				'product_price' => null,
				'auth_id'       => $vendor_id,
			);
			$wpc_pro_data = Pro_Utilities::discount_price( $discount_price_args );

			if ( is_product() && ! empty( $wpc_pro_data['main_price'] ) && ! empty( $wpc_pro_data['price_afer_discount'] )
				&& $wpc_pro_data['main_price'] !== '' && $wpc_pro_data['price_afer_discount'] !== '' && $product->get_type() != 'variable'
			) {
				$is_discount_product = true;
				$discount_percentage = $wpc_pro_data['discount_percentage'];
				$product_price       = $wpc_pro_data['new_price'];
			}

			$wpc_pro_menu_settings     = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option( 'wpcafe_reservation_settings_options' );
			$wpc_pro_addon_discount_to = isset( $wpc_pro_menu_settings['wpc_pro_addon_discount_to'] ) ? $wpc_pro_menu_settings['wpc_pro_addon_discount_to'] : '';

		?>
		<div class="wpc-product-totals" data-product-type="<?php echo esc_attr( $product_type ); ?>" data-product-price="<?php echo esc_attr( $product_price ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-discount-product="<?php echo esc_attr( $is_discount_product ); ?>" data-discount-percentage="<?php echo esc_attr( $discount_percentage ); ?>"
		data-discount-applicable-to="<?php echo esc_attr( $wpc_pro_addon_discount_to ); ?>" data-decimal-number-points="<?php echo esc_attr( wc_get_price_decimals() ); ?>" >
			<div class="wpc--inner">
				<div class="wpc-total-item">
					<label><?php esc_html_e( 'Product total', 'wpcafe-pro' );?></label>
					<span class="price amount">
						<?php
							$currency_pos         = get_option( 'woocommerce_currency_pos' );
									$currency_symbol_html = get_woocommerce_currency_symbol();

									if ( $currency_pos == 'left_space' ) {
										$currency_symbol_html = $currency_symbol_html . ' ';
									} elseif ( $currency_pos == 'right_space' ) {
										$currency_symbol_html = ' ' . $currency_symbol_html;
									}

									$print_left = ( strpos( $currency_pos, 'left' ) !== false ) ? true : false;
								?>
<?php echo esc_html( $print_left ) ? $currency_symbol_html : '' ?><span class="wpc-product-total"><?php echo wc_format_decimal( $product_price, wc_get_price_decimals() ); ?></span><?php echo ! $print_left ? $currency_symbol_html : '' ?>
					</span>
				</div>
				<div class="wpc-total-item">
					<label><?php esc_html_e( 'Addons total', 'wpcafe-pro' );?></label>
					<span class=" price amount">
						<?php echo esc_html( $print_left ) ? $currency_symbol_html : '' ?><span class="wpc-options-total">0</span><?php echo ! $print_left ? $currency_symbol_html : '' ?>
					</span>
				</div>
				<div class="wpc-total-item grand-total">
					<label><?php esc_html_e( 'Totals', 'wpcafe-pro' );?></label>
					<span class=" price amount">
						<?php echo esc_html( $print_left ) ? $currency_symbol_html : '' ?><span class="wpc-grand-total"><?php echo wc_format_decimal( $product_price, wc_get_price_decimals() ); ?></span><?php echo ! $print_left ? $currency_symbol_html : '' ?>
					</span>
				</div>
			</div>
		</div>
		<?php
			}

				public function get_wpc_addon_html( $addon ) {
					ob_start();

					$method_name = 'get_wpc_addon_' . $addon['type'] . '_html';

					if ( method_exists( $this, $method_name ) ) {
						$this->$method_name( $addon );
					}

					return ob_get_clean();
				}

				public function get_wpc_addon_checkbox_html( $addon ) {
					wc_get_template( 'templates/parts/checkbox.php', ['addon' => $addon], 'wpcafe-pro', \Wpcafe_Pro::plugin_dir() . '/core/modules/product-addons/frontend/' );
				}

				public function get_wpc_addon_radio_html( $addon ) {
					wc_get_template( 'templates/parts/radio.php', ['addon' => $addon], 'wpcafe-pro', \Wpcafe_Pro::plugin_dir() . '/core/modules/product-addons/frontend/' );
				}

				public function get_wpc_addon_dropdown_html( $addon ) {
					wc_get_template( 'templates/parts/select.php', ['addon' => $addon], 'wpcafe-pro', \Wpcafe_Pro::plugin_dir() . '/core/modules/product-addons/frontend/' );
				}

				public function get_wpc_addon_text_html( $addon ) {
					wc_get_template( 'templates/parts/text.php', ['addon' => $addon], 'wpcafe-pro', \Wpcafe_Pro::plugin_dir() . '/core/modules/product-addons/frontend/' );
				}

				public function wpc_addon_scripts() {

					wp_enqueue_script( 'wpc-addons', \Wpcafe_Pro::plugin_url() . '/core/modules/product-addons/assets/js/addons.js', ['jquery'], \Wpcafe_Pro::version(), true );
					$params = [
						'ajax_url' => admin_url( 'admin-ajax.php' ),
					];
					wp_localize_script( 'wpc-addons', 'wpc_addons_params', $params );
				}

		}
