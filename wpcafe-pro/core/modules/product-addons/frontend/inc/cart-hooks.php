<?php

namespace WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc;

use Exception;
use WpCafe_Pro\Utils\Utilities as Pro_Utilities;
use WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;

defined('ABSPATH') || exit;
/**
 * Addons adding to cart and price update
 */
class CartHooks {

  use \WpCafe_Pro\Traits\Singleton;

	/**
	 * Addons adding to cart and price update
	 */
  public function init() {
		add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_cart_item_data' ], 20, 2 );
		add_filter( 'woocommerce_add_cart_item', [ $this, 'add_cart_item' ], 20 );
		add_filter( 'woocommerce_get_item_data', [ $this, 'get_item_data' ], 10, 2 );
		add_filter( 'woocommerce_get_cart_item_from_session', [$this,'get_cart_item_from_session'], 20, 2 );
		add_filter('woocommerce_hidden_order_itemmeta', [$this, 'hide_order_itemmeta_to_show'], 10, 1);

		add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'order_line_item' ], 10, 3 );
		add_action( 'woocommerce_after_cart_item_quantity_update', [ $this, 'update_price_on_quantity_update' ], 20, 4 );

    }

	/**
	 * Updating cart price and adding addons price
	 */
	public function add_cart_item( $cart_item_data ) {
		$prices = array(
			'price'         => (float) $cart_item_data['data']->get_price( 'edit' ),
			'regular_price' => (float) $cart_item_data['data']->get_regular_price( 'edit' ),
			'sale_price'    => (float) $cart_item_data['data']->get_sale_price( 'edit' ),
		);

		return $this->update_product_price( $cart_item_data, $cart_item_data['quantity'], $prices );
	}

	/**
	 * Get cart item from session.
	 */
	public function get_cart_item_from_session( $cart_item, $values ) {
		
		if ( ! empty( $values['wpc_addons'] ) ) {
			$prices              = array(
				'price'         => (float) $cart_item['data']->get_price( 'edit' ),
				'regular_price' => (float) $cart_item['data']->get_regular_price( 'edit' ),
				'sale_price'    => (float) $cart_item['data']->get_sale_price( 'edit' ),
			);
			$cart_item['wpc_addons'] = $values['wpc_addons'];
			$cart_item           = $this->update_product_price( $cart_item, $cart_item['quantity'], $prices );
		}

		return $cart_item;
	}

	/**
	 * Adding addons data with cart item
	 */
    public function add_cart_item_data( $cart_item_data, $product_id ) {
		if ( isset( $_POST ) && ! empty( $product_id ) ) {
			$post_data = $_POST;
		} else {
			return;
		}

		include_once plugin_dir_path( __FILE__ ) . '/base/addons-field.php';
		include_once plugin_dir_path( __FILE__ ) . '/options/addons-field-list.php';

		$field_content = new \Product_Addons_Field_List( [ 'addon' => [], 'value' => [] , 'option_type' => ''] );
		$global_addons = $field_content->get_global_addon_data( $product_id );
		$wpc_addons = \WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\Addon_Helper::get_wpc_addon_data( $product_id );

		$vendor_global_addons = array();
		if( class_exists('Wpcafe_Multivendor') ) {
			// get pro global addons if multi-vendor active.
			$vendor_global_addons = $field_content->get_global_addon_data( $product_id ,true );
		}

		if ( ! empty( $global_addons ) ) {
			$wpc_addons = array_merge( $global_addons, $wpc_addons, $vendor_global_addons );
		}
		else if( class_exists('Wpcafe_Multivendor') && empty( $global_addons ) ) {
			// get pro global addons if multi-vendor active.
			$wpc_addons = array_merge(  $wpc_addons, $vendor_global_addons );
		}


		if ( empty( $cart_item_data['wpc_addons'] ) ) {
			$cart_item_data['wpc_addons'] = array();
		}

		if ( is_array( $wpc_addons ) && ! empty( $wpc_addons ) ) {

			$counter = 0;
			foreach ( $wpc_addons as $key => $wpc_addon ) {
				$wpc_addon['field_name'] = sanitize_title( $product_id . '-' . $wpc_addon['title'] . '-' . $counter );

				$value = wp_unslash( isset( $post_data[ 'wpc_addon-' . $wpc_addon['field_name'] ] ) ? $post_data[ 'wpc_addon-' . $wpc_addon['field_name'] ] : '' );

				$field_content  = new \Product_Addons_Field_List( [ 'addon' => $wpc_addon, 'value' => $value , 'option_type' => $wpc_addon['type']] );
				$data 			= $field_content->get_cart_item_content();

				if ( is_wp_error( $data ) ) {
					throw new Exception( $data->get_error_message() );
				} elseif ( $data ) {
					$cart_item_data['wpc_addons'] = array_merge( $cart_item_data['wpc_addons'], $data );
				}

				$counter++;
			}

		}

        return $cart_item_data;
    }

	public function get_item_data( $other_data, $cart_item ) {

		$addons_price = 0;

		if ( !empty( $cart_item['wpc_addons'] ) ) {
			$extra_data = [];
			foreach ( $cart_item['wpc_addons'] as $addon ) {
				$name 		  	= $addon['name'];
				$value 			= $addon['value'];
				$display 		= isset( $addon['display'] ) ? $addon['display'] : '';

				$field_name 	= $addon['field_name'];
				$addons_price 	+= absint( $addon['price'] );

				// for checkbox 1 row will be added, so concatenate all value to show in single line
				if( $addon['field_type'] == 'checkbox' && isset( $extra_data[ $field_name ] ) ) {
					$value = $extra_data[ $field_name ]['value'] . ', ' . $value;
				}

				$extra_data[ $field_name ] = [
					'name'    => $name,
					'value'   => $value,
					'display' => $display,
				];
			}

			if ( !empty( $extra_data ) ) {
				foreach( $extra_data as $field_name => $field_data ) {
					$other_data[] = $field_data;
				}
			}
		}

		// Price calculation
		$vendor_id = get_post_field( 'post_author', $cart_item['product_id'] );

		$product       = wc_get_product( $cart_item['product_id'] );
		$product_price = null;

		if ( $product->get_type() == 'variable' ) {
			$variation_id  = $cart_item['variation_id'];
			$product_price = get_post_meta( $variation_id, '_price', true );
		} else {
			$product_price = Wpc_Utilities::menu_price_by_tax( $product );
		}

		$discount_price_args = array(
			'product_id'    => $cart_item['product_id'],
			'data'          => '',
			'product_price' => $product_price,
			'auth_id'       => $vendor_id,
			'addons_price'	=> $addons_price,
		);
		$wpc_pro_data   = Pro_Utilities::discount_price( $discount_price_args );
	
		if ( !empty( $wpc_pro_data['main_price'] ) &&
			$wpc_pro_data['main_price'] !== '' && $wpc_pro_data['price_afer_discount'] !== ''
			&& !empty($wpc_pro_data['main_price']) && !empty($wpc_pro_data['price_afer_discount'])
		) {
			$other_data[] = [
				'name'     => esc_html__( "Main price", 'wpcafe-pro' ),
				'display'  => wc_price( $wpc_pro_data['main_price'] ),
			];

			if ( $addons_price > 0 )  {
				$discount_applied_on = ( $wpc_pro_data['discount_applied_on'] == 'options_total' ) ? 'product and addons' : 'product';
				$discount_final_msg = esc_html__('Total price after ', 'wpcafe-pro') . $wpc_pro_data['discount_percentage'] . esc_html__('% discount on ', 'wpcafe-pro') . $discount_applied_on;

				// will create an array to put in $other_data
				$other_data[] = [
					'name'     => esc_html__( 'Addons price', 'wpcafe-pro' ),
					'display'  => wc_price( $addons_price ),
				];

				$addon_data = [
					'name'     => ( is_cart() || is_checkout() ) ? esc_html($discount_final_msg) : esc_html__( 'After ', 'wpcafe-pro') . $wpc_pro_data['discount_percentage'] . esc_html__('% discount', 'wpcafe-pro' ),
					'display'  => wc_price( $wpc_pro_data['new_price'] ) . '+' . wc_price( $wpc_pro_data['addons_new_price'] ),
				];
				
				$other_data[] = $addon_data;
			}
		}

		return $other_data;
	}

	public function order_line_item( $item, $cart_item_key, $values ) {

		if ( ! empty( $values['wpc_addons'] ) ) {

			$addons_total_price = 0;
			$extra_data = [];
			foreach ( $values['wpc_addons'] as $addon ) {
				$key           = $addon['name'];
				$field_name    = $addon['field_name'];
				$value         = $addon['value'];
				$price_type    = $addon['price_type'];
				$product       = $item->get_product();
				$product_price = $product->get_price();

				if ( $addon['price']  ) {
					$addon_price = $addon['price'];	
					$addons_total_price += $addon_price;
				}

				$price = html_entity_decode(
					strip_tags( wc_price( \WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\Addon_Helper::get_wpc_addon_price_for_display( $addon_price, $values['data'] ) ) ),
					ENT_QUOTES, get_bloginfo( 'charset' )
				);

				if ( $addon['price'] ) {
					$value .= ' (' . $price . ')';
				}	

				if( $addon['field_type'] == 'checkbox' && isset( $extra_data[ $field_name ] ) ) {
					$value = $extra_data[ $field_name ]['value'] . ', ' . $value;
				}

				if ( $addon['field_type'] === 'custom_price' ) {
					$value = $addon['price'];
				}

				$extra_data[ $field_name ] = [
					'key'    => $key,
					'value'   => $value,
				];

			}

			if ( !empty( $extra_data ) ) {
				foreach( $extra_data as $field_data ) {
					$item->add_meta_data( $field_data['key'], $field_data['value'] );
				}
			}

			// add addons total price as order item meta to access from thank you and order edit page
			if ( $addons_total_price > 0 ) {
				$item->add_meta_data( '_addons_price', $addons_total_price );
			}
		}
	}

	/**
	 * Calculate price based on addons type
	 */
	public function price_calculation( $cart_item , $prices ){
		$price         = $prices['price'];
		$regular_price = $prices['regular_price'];
		$sale_price    = $prices['sale_price'];
		$addons_price  = 0;

		foreach ( $cart_item['wpc_addons'] as $addon ) {
			$price_type  = $addon['price_type'];
			$addon_price = $addon['price'];

			$addons_price += $addon_price;
			switch ( $price_type ) {
				case 'quantity_based':
					$price         += (float) $addon_price;
					$regular_price += (float) $addon_price;
					$sale_price    += (float) $addon_price;
					break;
				default:
					$price         += (float) $addon_price;
					$regular_price += (float) $addon_price;
					$sale_price    += (float) $addon_price;
					break;
			}
		}

		// If discount exist
		$settings = get_option('wpcafe_reservation_settings_options');

		if ( !empty($settings) && !empty($settings['wpc_pro_discount_percentage']) 
		&& $settings['wpc_pro_discount_percentage'] !=='0' ) {

			$vendor_id = get_post_field( 'post_author', $cart_item['product_id'] );

			$discount_price_args = array(
				'product_id'    => $cart_item['product_id'],
				'data'          => 'wpc_pro_cart',
				'product_price' => $price,
				'auth_id'       => $vendor_id,
				'addons_price'  => $addons_price,
			);

			$discount_price_data = Pro_Utilities::discount_price( $discount_price_args );

			if ( !empty( $discount_price_data['new_price'] ) ) {
				$price = (float) ( $discount_price_data['new_price'] + $discount_price_data['addons_new_price'] );
			} else {
				$price = (float) ( $price + $addons_price );
			}

		}

		return [ 'price' => $price , 'regular_price' => $regular_price 
		, 'sale_price' => $sale_price, 'addons_price' => $addons_price ];
	}


	public function update_product_price( $cart_item_data, $qty, $prices ) {

		if ( ! empty( $cart_item_data['wpc_addons'] ) ) {

			$cart_item_data['wpc_addons_price_before_calc']         = (float) $prices['price'];
			$cart_item_data['wpc_addons_regular_price_before_calc'] = (float) $prices['regular_price'];
			$cart_item_data['wpc_addons_sale_price_before_calc']    = (float) $prices['sale_price'];

			$result_data = $this->price_calculation( $cart_item_data , $prices );

			$updated_product_prices = [
				'price'         => $result_data['price'],
				'regular_price' => $result_data['regular_price'],
				'sale_price'    => $result_data['sale_price'],
			];

			$cart_item_data['data']->set_price( $updated_product_prices['price'] );

			$has_regular_price = is_numeric( $cart_item_data['data']->get_regular_price( 'edit' ) );
			if ( $has_regular_price ) {
				$cart_item_data['data']->set_regular_price( $updated_product_prices['regular_price'] );
			}

			$has_sale_price = is_numeric( $cart_item_data['data']->get_sale_price( 'edit' ) );
			if ( $has_sale_price ) {
				$cart_item_data['data']->set_sale_price( $updated_product_prices['sale_price'] );
			}

		}

		return $cart_item_data;
	}

	public function update_price_on_quantity_update( $cart_item_key, $qty, $old_qty, $cart ) {
		$cart_item_data = $cart->get_cart_item( $cart_item_key );

		if ( ! empty( $cart_item_data['addons'] ) ) {
			$prices = [
				'price'         => $cart_item_data['wpc_addons_price_before_calc'],
				'regular_price' => $cart_item_data['wpc_addons_regular_price_before_calc'],
				'sale_price'    => $cart_item_data['wpc_addons_sale_price_before_calc'],
			];

			return $this->update_product_price( $cart_item_data, $qty, $prices );
		}
	}

	/**
     * Hide item specific meta so that they won't show in order update page
     *
     * @param [type] $item_hidden_metas
     * @return array
     */
    public function hide_order_itemmeta_to_show( $item_hidden_metas ) {

        array_push( $item_hidden_metas, '_addons_price' );

        return $item_hidden_metas;
    }
}