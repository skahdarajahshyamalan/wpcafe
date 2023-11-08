<?php

use WpCafe\Core\Base\Wpc_Settings_Field as Settings;

defined('ABSPATH') || exit;

interface Check{
	public function get_checkbox_options_data();
}

interface Text{
	public function get_text_options_data();
}
interface Select{
	public function get_select_options_data();
}

class Product_Addons_Field_List extends Product_Addons_Field implements Check, Select, Text{

	public function validate_value() {
		if ( ! empty( $this->addon['required'] ) ) {
			if ( ! $this->value || sizeof( $this->value ) == 0 ) {
				return new WP_Error( 'error', sprintf( esc_html__( '"%s" is a required field.', 'wpcafe-pro' ), $this->addon['name'] ) );
			}
		}

		return true;
	}

	public function get_checkbox_options_data(){
		$cart_item_data = [];
		$value          = $this->value;

		if ( empty( $value ) ) {
			return false;
		}

		if ( ! is_array( $value ) ) {
			$value = array( $value );
		}

		if ( is_array( current( $value ) ) ) {
			$value = current( $value );
		}

		foreach ( $this->addon['options'] as $option ) {
			if ( in_array( strtolower( sanitize_title( $option['label'] ) ), array_map( 'strtolower', array_values( $value ) ) ) ) {
				$cart_item_data[] = array(
					'name'       => sanitize_text_field( $this->addon['title'] ),
					'value'      => $option['label'],
					'price'      => floatval( sanitize_text_field( $this->get_field_option_price( $option ) ) ),
					'field_name' => $this->addon['field_name'],
					'field_type' => $this->addon['type'],
					'price_type' => $option['price_type'],
				);
			}
		}

		return $cart_item_data;
	}

	public function get_select_options_data(){
		$cart_item_data = [];

		if ( empty( $this->value ) ) {
			return false;
		}

		$chosen_option = '';
		$loop          = 0;

		foreach ( $this->addon['options'] as $option ) {
			$loop++;
			if ( sanitize_title( $option['label'] . '-' . $loop ) == $this->value ) {
				$chosen_option = $option;
				break;
			}
		}

		if ( ! $chosen_option ) {
			return false;
		}

		$cart_item_data[] = array(
			'name'  => sanitize_text_field( $this->addon['title'] ),
			'value' => $chosen_option['label'],
			'price' => floatval( sanitize_text_field( $this->get_field_option_price( $chosen_option ) ) ),
			'field_name' => $this->addon['field_name'],
			'field_type' => $this->addon['type'],
			'price_type' => $chosen_option['price_type'],
		);

		return $cart_item_data;
	}

	public function get_text_options_data(){
		$cart_item_data = [];
		$value          = $this->value;

		if ( empty( $value ) ) {
			return false;
		}

		if ( ! is_array( $value ) ) {
			$value = array( $value );
		}

		if ( is_array( current( $value ) ) ) {
			$value = current( $value );
		}

		foreach ( $this->addon['options'] as $option ) {
			if ( !empty( $value[0] ) ) {
				$cart_item_data[] = array(
					'name'       => sanitize_text_field( $this->addon['title'] ),
					'value'      => $value[0],
					'price'      => floatval( sanitize_text_field( $this->get_field_option_price( $option ) ) ),
					'field_name' => $this->addon['field_name'],
					'field_type' => $this->addon['type'],
					'price_type' => $option['price_type'],
				);
			}
		}

		return $cart_item_data;
	}

	public function get_cart_item_content() {

		if ( $this->option_type == "dropdown" ) {
			$cart_item_data = $this->get_select_options_data();
		} else if ( $this->option_type == "checkbox" || $this->option_type == "radio" ) {
			$cart_item_data = $this->get_checkbox_options_data();
		} else if ( $this->option_type == "text" ) {
			$cart_item_data = $this->get_text_options_data();
		}

		return $cart_item_data;
	}

	/**
	 * Check global addon is applicable to this product. If yes, returns global addons array
	 *
	 * @param [type] $product_id get id.
	 * @return array
	 */
	public function get_global_addon_data( $product_id = null , $both_addons = false ) {
		if ( class_exists('Wpcafe_Multivendor') && false == $both_addons ) {
				$vendor_id = \Wpcafe_Multivendor\Core\Hooks\Hooks::instance()->get_vendor_id( $product_id );
				$vendor_settings = get_user_meta( $vendor_id , 'dokan_wpcafe_settings', true );
				$allow_vendor_product_add_ons = !empty( $vendor_settings['allow_vendor_product_add_ons'] ) &&  $vendor_settings['allow_vendor_product_add_ons'] =="on" ? "checked" : "";
				if ( 'checked' == $allow_vendor_product_add_ons ) {
					$settings = Settings::instance()->get_settings_option( 'wpcafe_product_addons_'.$vendor_id );
				}else{
					$settings = array();
				}
		}
		else if( class_exists('Wpcafe_Multivendor') && $both_addons ){
			$settings = Settings::instance()->get_settings_option( 'wpcafe_product_addons' );
		}
		else {
			$settings = Settings::instance()->get_settings_option( 'wpcafe_product_addons' );
		}

		$is_global_addon_product = false;
		$global_addon_data 		 = [];

		$wpc_pro_discount_product   = isset($settings['addons_menu']) ? $settings['addons_menu'] : [];
		$wpc_pro_discount_cat       = isset($settings['addons_categories']) ? $settings['addons_categories'] : [];

		if ( in_array( $product_id, $wpc_pro_discount_product ) || ( class_exists( 'Wpcafe_Multivendor' ) && ( ! is_admin() ) ) ) {
			$is_global_addon_product = true;
		} else {
			// get cat id.
			$wpc_pro_terms = get_the_terms($product_id, 'product_cat');
			if ( is_array( $wpc_pro_terms ) ) {
					foreach ( $wpc_pro_terms as $term ) {
						if ( in_array( $term->term_id, $wpc_pro_discount_cat ) ) {
							$is_global_addon_product = true;
						}
					}
			}
		}

		if ( $is_global_addon_product ) {
			$global_addon_data = \WpCafe_Pro\Core\Modules\Product_Addons\Admin\Hooks::instance()->process_addon_data( $settings );
		}

		return $global_addon_data;
	}

}
