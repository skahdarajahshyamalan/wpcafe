<?php

namespace WpCafe_Pro\Core\Modules\Food_Menu;

use WpCafe\Utils\Wpc_Utilities;
use WpCafe_Pro\Utils\Utilities as Pro_Utilities;

defined( 'ABSPATH' ) || exit;

class Hooks {

	use \WpCafe_Pro\Traits\Singleton;

	public function init() {
		// add image control in food location
		add_action( 'wpcafe_location_add_form_fields', [$this, 'location_taxonomy_add_new_meta_field'], 10, 1 );
		add_action( 'wpcafe_location_edit_form_fields', [$this, 'location_taxonomy_edit_meta_field'], 10, 1 );
		add_action( 'edited_wpcafe_location', [$this, 'taxonomy_save_meta_field'], 10, 1 );
		add_action( 'create_wpcafe_location', [$this, 'taxonomy_save_meta_field'], 10, 1 );

		//Displaying Additional Columns
		add_filter( 'manage_edit-wpcafe_location_columns', [$this, 'wpc_custom_fields_list_title'] );
		add_action( 'manage_wpcafe_location_custom_column', [$this, 'wpc_custom_fields_list_diplay'], 10, 3 );

		$settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
		if (  ( ! empty( $settings['wpcafe_allow_cart'] ) && $settings['wpcafe_allow_cart'] == "on" ) && class_exists( 'woocommerce' ) ) {
			//add quantity picker in mini-cart
			add_filter( 'woocommerce_widget_cart_item_quantity', [$this, 'add_minicart_quantity_fields'], 10, 3 );
		}

		// elementor widget live search
		add_filter( 'elementor/control/search_control', [$this, 'widget_search_control'], 10, 1 );
		add_filter( 'elementor/control/search_data', [$this, 'widget_search_data'], 10, 3 );

		// add variation popup
		add_action( 'wp_head', [$this, 'variation_popup_markup'] );

		// add order type in order table
		add_filter( 'manage_edit-shop_order_columns', [$this, 'show_order_type_column'] );
		add_action( 'manage_shop_order_posts_custom_column', [$this, 'show_order_type_data'] );

		// QR code action
		add_filter( 'woocommerce_add_cart_item_data', [$this, 'save_custom_data_in_cart_object'], 20, 2 );
		add_filter( 'woocommerce_get_item_data', [$this, 'display_cart_item_custom_meta_data'], 10, 2 );
		add_action( 'woocommerce_checkout_create_order_line_item', [$this, 'save_cart_item_custom_meta_as_order_item_meta'], 10, 4 );

	}

	/***
	 * Set custom data as custom cart data in the cart item
	 */
	public function save_custom_data_in_cart_object( $cart_item_data, $product_id ) {

		if ( ! empty( $_POST['tableid'] ) ) {
			// Set the data as custom cart data for the cart item.
			$cart_item_data['tableId'] = esc_attr( $_POST['tableid'] );
		}
		if ( ! empty( $_GET['tableId'] ) ) {
			// Set the data as custom cart data for the cart item.
			$cart_item_data['tableId'] = esc_attr( $_GET['tableId'] );
		}

		return $cart_item_data;
	}

	/**
	 *  Display custom cart item meta data (in cart and checkout)
	 */
	public function display_cart_item_custom_meta_data( $item_data, $cart_item ) {

		$meta_key = esc_html__( 'Table Name', 'wpcafe-pro' );
		if ( ! empty( $cart_item['tableId'] ) ) {
			$item_data[] = array(
				'key'   => $meta_key,
				'value' => $cart_item['tableId'],
			);
		?>
		<!-- // hiding order type , food location for QR code scanner -->
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var ordering_section  = jQuery(".wpc_pro_order_time");
				var location_field    = jQuery("#wpc_location_field");
				if( ordering_section.length > 0 ){
					ordering_section.empty();
				}
				if( location_field.length > 0 ){
					location_field.empty();
				}
			})
		</script>
		<?php

		}
		return $item_data;
	}

	/**
	 *
	 * Save cart item custom meta as order item meta data and display it everywhere on orders and email notifications.
	 */
	public function save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {
		$meta_key = esc_html__( 'Table Name', 'wpcafe-pro' );
		if ( isset( $values['tableId'] ) ) {
			$item->update_meta_data( $meta_key, $values['tableId'] );
		}
	}

	/**
	 * Show Order Type
	 */
	public function show_order_type_column( $columns ) {
		$columns['wpc_pro_order_time'] = esc_html__( 'Order Type', 'wpcafe-pro' );
		// Table name from QR code scanning.
		$columns['tableId'] = esc_html__( 'Table Name', 'wpcafe-pro' );

		return $columns;
	}

	/**
	 * Show Order Type
	 */
	public function show_order_type_data( $column ) {
		if ( class_exists( 'Woocommerce' ) ) {
			global $post;
			$order = wc_get_order( $post->ID );
			if ( 'wpc_pro_order_time' === $column ) {
				echo esc_html( $order->get_meta( 'wpc_pro_order_time' ) );
					if ( $order->get_meta( 'wpc_pro_order_time' ) !== "" ) {
					?>
						<div><?php echo esc_html__( "Date / Time: " ); ?></div>
					<?php
						if ( $order->get_meta( 'wpc_pro_order_time' ) == "Delivery" ) {

						if ( ! empty( $order->get_meta( 'wpc_pro_delivery_date' ) ) ) {
							?>
								<div><?php echo esc_html( $order->get_meta( 'wpc_pro_delivery_date' ) ); ?></div>
								<?php
									}
									if ( ! empty( $order->get_meta( 'wpc_pro_delivery_time' ) ) ) {
									?>
								<div><?php echo esc_html( $order->get_meta( 'wpc_pro_delivery_time' ) ); ?></div>
								<?php
								}
							}

							if ( $order->get_meta( 'wpc_pro_order_time' ) == "Pickup" ) {

								if ( ! empty( $order->get_meta( 'wpc_pro_pickup_date' ) ) ) {
									?>
										<div><?php echo esc_html( $order->get_meta( 'wpc_pro_pickup_date' ) ); ?></div>
									<?php
								}
								if ( ! empty( $order->get_meta( 'wpc_pro_pickup_time' ) ) ) {
									?>
										<div><?php echo esc_html( $order->get_meta( 'wpc_pro_pickup_time' ) ); ?></div>
									<?php
								}
							}
							} else {
								echo "---";
							}
						}
						if ( 'tableId' === $column ) {
							$order_items = $order->get_items();
							foreach ( $order_items as $item_id => $item_data ) {
								$qr_name = $item_data->get_meta( esc_html__( 'Table Name', 'wpcafe-pro' ) );
								$qr_name = str_replace( '_', ' ', $qr_name );
								if ( $qr_name !== '' ) {
								?>
									<div><?php echo esc_html( $qr_name ); ?></div>
								<?php
						}
					}
			}
		}
	}

	/**
	 * Category new field for set priority
	 *
	 */
	public function location_taxonomy_add_new_meta_field() {
		?>
			<!-- Address -->
			<div class="form-field term-group">
				<label for="address"><?php esc_html_e( 'Address', 'wpcafe-pro' );?></label>
				<textarea type="number" name="address" id="address" rows="5" cols="5"></textarea>
				<p class="description"><?php esc_html_e( 'Food location address. Note: From this address, latitude and longitude fields will be populated. After updating address, click the "Address Position" button to auto populate the latitude and longitude fields.', 'wpcafe-pro' );?></p>
			</div>
			<!-- Email -->
			<div class="form-field term-group">
				<label for="location_email"><?php esc_html_e( 'Email', 'wpcafe-pro' );?></label>
				<input type="email" id="location_email" name="location_email" value="">
				<p><?php esc_html_e( 'Email of the location', 'wpcafe-pro' );?></p>
			</div>
			<!-- Image -->
			<div class="form-field term-group">
				<label for="location_image"><?php esc_html_e( 'Image', 'wpcafe-pro' );?></label>
				<input type="hidden" id="location_image" name="location_image" class="custom_media_url" value="">
				<div id="category-image-wrapper"></div>
				<p>
					<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'wpcafe-pro' );?>" />
					<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'wpcafe-pro' );?>" />
				</p>
			</div>
			<!-- Latitude -->
			<div class="form-field term-group">
				<label for="location_latitude"><?php esc_html_e( 'Latitude', 'wpcafe-pro' );?></label>
				<input type="text" id="location_latitude" name="location_latitude" value="">
				<p><?php esc_html_e( 'Latitude of the location', 'wpcafe-pro' );?></p>
			</div>
			<!-- Longitude -->
			<div class="form-field term-group">
				<label for="location_longitude"><?php esc_html_e( 'Longitude', 'wpcafe-pro' );?></label>
				<input type="text" id="location_longitude" name="location_longitude" value="">
				<p><?php esc_html_e( 'Longitude of the location', 'wpcafe-pro' );?></p>
			</div>

			<!-- Location map -->
			<div class="form-field term-group">
				<label for="location_map"><?php // esc_html_e('Location Map', 'wpcafe-pro'); ?></label>
				<?php
				$settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
				$api_key  = isset( $settings['google_api_key'] ) ? isset( $settings['google_api_key'] ) : '';
				if ( empty( $api_key ) ) {
					$settings_page_url = Pro_Utilities::kses( '<a href="' . esc_url( admin_url() . 'admin.php?page=cafe_settings' ) . '" target="_blank" >' . esc_html__( 'Settings', 'wpcafe-pro' ) . '</a>', 'wpcafe-pro' );
				?>
					<p class="location-map-api-msg"><?php echo esc_html__( 'Google Api Key is empty. Please fill the api key field from ', 'wpcafe-pro' ) . $settings_page_url; ?> </p>
					<?php
						} else {
					?>
					<a href="#" id="wpc-location-map-position" class="button button-primary"><?php esc_html_e( 'Address Map Position', 'wpcafe-pro' );?></a>
					<p><?php esc_html_e( 'From address field value, Position will show in map.', 'wpcafe-pro' );?></p>
					<div class="wpc-location-map" data-lat="37.4224428" data-long="-122.0842467" data-zoom="14">
							<div id="wpc-location-map-container"></div>
					</div>
					<?php } ?>
			</div>
		</tr>
		<?php
	}

	/**
	 * Category edit field for set priority
	 */
	public function location_taxonomy_edit_meta_field( $term ) {
		?>
		<!-- Address -->
		<tr class="form-field term-group-wrap">
				<th scope="row">
						<label for="address"><?php esc_html_e( 'Address', 'wpcafe-pro' );?></label>
				</th>
				<td>
						<textarea type="address" id="address" name="address"
						rows="5" cols="5"><?php esc_attr_e( get_term_meta( $term->term_id, 'address', true ) );?></textarea>
						<p class="description"><?php esc_html_e( 'Food location address. Note: From this address, latitude and longitude fields will be populated. After updating address, click the "Address Position" button to auto populate the latitude and longitude fields.', 'wpcafe-pro' );?></p>
				</td>
		</tr>
		<!-- Email -->
		<tr class="form-field term-group-wrap">
				<th scope="row">
						<label for="location_email"><?php esc_html_e( 'Email', 'wpcafe-pro' );?></label>
				</th>
				<td>
						<?php $location_email = get_term_meta( $term->term_id, 'location_email', true );?>
						<input type="email" id="location_email" name="location_email" value="<?php echo esc_attr( $location_email ); ?>">
						<p><?php esc_html_e( 'Email of the location', 'wpcafe-pro' );?></p>
				</td>
		</tr>
		<!-- Image -->
		<tr class="form-field term-group-wrap">
				<th scope="row">
						<label for="location_image"><?php esc_html_e( 'Image', 'wpcafe-pro' );?></label>
				</th>
				<td>
						<?php $image_id = get_term_meta( $term->term_id, 'location_image', true );?>
						<input type="hidden" id="location_image" name="location_image" value="<?php echo esc_attr( $image_id ); ?>">
						<div id="category-image-wrapper">
								<?php if ( $image_id ) {?>
		<?php
			$loc_image = wp_get_attachment_image_src( $image_id, 'thumbnail' );
						( print_r( $loc_image, TRUE ) );
						echo wp_get_attachment_image( $image_id, 'thumbnail' );
					?>
		<?php }?>
				</div>
				<p>
						<input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'wpcafe-pro' );?>" />
						<input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'wpcafe-pro' );?>" />
				</p>
			</td>
		</tr>

		<!-- Latitude -->
		<tr class="form-field term-group-wrap">
				<th scope="row">
						<label for="location_latitude"><?php esc_html_e( 'Latitude', 'wpcafe-pro' );?></label>
				</th>
				<td>
						<?php
							$location_latitude = get_term_meta( $term->term_id, 'location_latitude', true );
									if ( empty( $location_latitude ) ) {
										$location_latitude = '37.4224428';
									}
								?>
						<input type="text" id="location_latitude" name="location_latitude" value="<?php echo esc_attr( $location_latitude ); ?>">
						<p><?php esc_html_e( 'Latitude of the location', 'wpcafe-pro' );?></p>
				</td>
		</tr>
		<!-- Longitude -->
		<tr class="form-field term-group-wrap">
				<th scope="row">
						<label for="location_longitude"><?php esc_html_e( 'Longitude', 'wpcafe-pro' );?></label>
				</th>
				<td>
						<?php
							$location_longitude = get_term_meta( $term->term_id, 'location_longitude', true );
									if ( empty( $location_longitude ) ) {
										$location_longitude = '-122.0842467';
									}
								?>
						<input type="text" id="location_longitude" name="location_longitude" value="<?php echo esc_attr( $location_longitude ); ?>">
						<p><?php esc_html_e( 'Longitude of the location', 'wpcafe-pro' );?></p>
				</td>
		</tr>
		<!-- Location map -->
		<tr class="form-field term-group-wrap">
				<th scope="row">
						<label for="location_map"><?php esc_html_e( 'Location Map', 'wpcafe-pro' );?></label>
				</th>
				<td>
						<?php
							$settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
									$api_key  = isset( $settings['google_api_key'] ) ? isset( $settings['google_api_key'] ) : '';
									if ( empty( $api_key ) ) {
										$settings_page_url = Pro_Utilities::kses( '<a href="' . esc_url( admin_url() . 'admin.php?page=cafe_settings' ) . '" target="_blank" >' . esc_html__( 'Settings', 'wpcafe-pro' ) . '</a>', 'wpcafe-pro' );
									?>
										<p class="location-map-api-msg"><?php echo esc_html__( 'Google Api Key is empty. Please fill the api key field from ', 'wpcafe-pro' ) . $settings_page_url; ?> </p>
										<?php
											} else {
													?>
										<a href="#" id="wpc-location-map-position" class="button button-primary"><?php esc_html_e( 'Address Position', 'wpcafe-pro' );?></a>
										<p><?php esc_html_e( 'From address field value, Position will show in map.', 'wpcafe-pro' );?></p>
										<div class="wpc-location-map" data-lat="<?php echo esc_attr( $location_latitude ); ?>" data-long="<?php echo esc_attr( $location_longitude ); ?>" data-zoom="12">
												<div id="wpc-location-map-container"></div>
										</div>
										<?php
											}
												?>
				</td>
		</tr>
		<?php
	}

	/**
	 * save field
	 */
	public function taxonomy_save_meta_field( $term_id ) {
		$location_input = array(
			'location_image'     => array(),
			'location_email'     => FILTER_VALIDATE_EMAIL,
			'address'            => array(),
			'location_latitude'  => array(),
			'location_longitude' => array(),
		);

		$location_input = filter_input_array( INPUT_POST, $location_input );

		if ( count( $location_input ) > 0 ) {
			foreach ( $location_input as $key => $value ) {
				update_term_meta( $term_id, $key, $value, false );
			}
		}
	}

	/**
	 * Column added to location taxonomy admin screen.
	 */
	public function wpc_custom_fields_list_title( $columns ) {
		$columns['location_image']     = esc_html__( 'Image', 'wpcafe-pro' );
		$columns['location_email']     = esc_html__( 'Email', 'wpcafe-pro' );
		$columns['location_latitude']  = esc_html__( 'Lat', 'wpcafe-pro' );
		$columns['location_longitude'] = esc_html__( 'Long', 'wpcafe-pro' );

		return $columns;
	}

	/**
	 * Location column value added to product category admin screen.
	 */
	public function wpc_custom_fields_list_diplay( $columns, $column, $id ) {
		// Get the image ID for the category
		switch ( $column ) {
		case 'location_image':
			$image_id = get_term_meta( $id, 'location_image', true );
			echo wp_get_attachment_image( $image_id );
			break;
		case 'location_email':
			$location_email = get_term_meta( $id, 'location_email', true );
			echo Wpc_Utilities::wpc_render( $location_email );
			break;
		case 'location_latitude':
			$location_latitude = get_term_meta( $id, 'location_latitude', true );
			echo Wpc_Utilities::wpc_render( $location_latitude );

			break;
		case 'location_longitude':
			$location_longitude = get_term_meta( $id, 'location_longitude', true );
			echo Wpc_Utilities::wpc_render( $location_longitude );

			break;
		}
	}

	/**
	 * Add quantity picker in mini cart
	 */
	public function add_minicart_quantity_fields( $html, $cart_item, $cart_item_key ) {
		$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $cart_item['data'] ), $cart_item, $cart_item_key );
		?>
		<?php
		return woocommerce_quantity_input( array( 'input_value' => $cart_item['quantity'] ), $cart_item['data'], false ) . $product_price;
	}

	/**
	 * Elementor widget search control function
	 */
	public function widget_search_control( $args ) {
		$data           = [];
		$search_control = array(
			'name'      => 'wpc_search_show',
			'parameter' => array(
				'label'        => esc_html__( 'Show Search', 'wpcafe-pro' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wpcafe-pro' ),
				'label_off'    => esc_html__( 'Hide', 'wpcafe-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			),
		);
		$data['search_control'] = $search_control;

		return $data;
	}

	/**
	 * Liver search markup for free when pro active
	 */
	public function widget_search_data( $settings, $unique_id, $template_name ) {
		$wpc_search_show = isset( $settings['wpc_search_show'] ) ? $settings['wpc_search_show'] : "no";
		$wpc_menu_cat    = [];
		$ajax_template   = 'list_template';
		if ( $template_name == 'wpc-food-menu-tab' ) {
			$ajax_template  = 'tab_template';
			$food_menu_tabs = $settings["food_menu_tabs"];
			$style          = $settings["food_tab_menu_style"];
			foreach ( $food_menu_tabs as $key => $value ) {
				if ( isset( $value['post_cats'][0] ) ) {
					array_push( $wpc_menu_cat, $value['post_cats'][0] );
				}
			}
		} else {
			$style         = $settings["food_menu_style"];
			$wpc_menu_cat  = $settings["wpc_menu_cat"];
			$ajax_template = 'list_template';
		}
		if ( $wpc_search_show == 'yes' ) {
			// live search
			$template_path = \Wpcafe::plugin_dir() . "/widgets/{$template_name}/style/{$style}.php";

			$widget_arr = array(
				'show_thumbnail'   => $settings['show_thumbnail'],
				'wpc_menu_order'   => $settings['wpc_menu_order'],
				'show_item_status' => $settings['show_item_status'],
				'wpc_menu_count'   => $settings['wpc_menu_count'],
				'wpc_show_desc'    => $settings['wpc_show_desc'],
				'wpc_desc_limit'   => $settings['wpc_desc_limit'],
				'title_link_show'  => $settings['title_link_show'],
				'wpc_cart_button'  => $settings["wpc_cart_button_show"],
				'unique_id'        => $unique_id,
			);

			$live_search_args = array(
				'no_of_product'    => $settings['wpc_menu_count'],
				'wpc_cat_arr'      => $wpc_menu_cat,
				'wpc_cart_button'  => $settings["wpc_cart_button_show"],
				'template'         => $ajax_template,
				'template_path'    => $template_path,
				'widget_arr'       => $widget_arr,
				'search_alignment' => 'center',
			);

			echo \WpCafe_Pro\Core\Template\Food_Menu::instance()->live_search_markup( $live_search_args );

		}
	}

	/**
	 * Variation popup modal markup
	 */
	public static function variation_popup_markup() {
		?>
		<div class="wpc-product-popup-content" id="popup_wrapper">
			<div class="wpc-popup-wrap" id="product_popup">
			<div class="wpc-popup-wrap-inner">
					<button class="wpc-close wpc-btn"> <i>x</i></button>
					<div class="wpc_variation_popup_content"> </div>
			</div>
			</div>
		</div>
		<?php
	}

}