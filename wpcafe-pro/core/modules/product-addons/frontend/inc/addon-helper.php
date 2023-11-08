<?php

namespace WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc;

use WpCafe\Core\Base\Wpc_Settings_Field as Settings;

defined( "ABSPATH" ) || exit;
/**
 * Addons helper function
 */
class Addon_Helper {

	use \WpCafe_Pro\Traits\Singleton;

	public static function get_wpc_addon_data( $post_id, $prefix = false, $inc_parent = true, $inc_global = true ) {
		if ( ! $post_id ) {
			return [];
		}

		$addons    		= [];
		$raw_wpc_addons = [];
		$parent_id 		= wp_get_post_parent_id( $post_id );

		$product    = wc_get_product( $post_id );
		if ( ! $product ) {
			return [];
		}

		$wpc_addons = array_filter( (array) $product->get_meta( '_wpc_pro_pao_data' ) );

		$raw_wpc_addons[100]['product'] = apply_filters( 'get_wpc_paos_fields', $wpc_addons, $post_id );

		if ( $inc_parent && $parent_id ) {
			$raw_wpc_addons[100]['parent'] = apply_filters( 'get_wpc_parent_paos_fields', self::get_wpc_addon_data( $parent_id, $parent_id . '-', false, false ), $post_id, $parent_id );
		}


		foreach ( $raw_wpc_addons as $indi_addon ) {
			if ( $indi_addon ) {
				foreach ( $indi_addon as $addon ) {
					$addons = array_merge( $addons, $addon );
				}
			}
		}

		if ( ! $prefix ) {
			$prefix = "{$post_id}-";
		}

		$max_addon_name_length = 45 - strlen( $prefix );
		if ( $max_addon_name_length < 0 ) {
			$max_addon_name_length = 0;
		}

		$wpc_addon_field_counter = 0;

		foreach ( $addons as $addon_key => $addon ) {
			if ( empty( $addon['title'] ) ) {
				unset( $addons[ $addon_key ] );
				continue;
			}
			if ( empty( $addons[ $addon_key ]['field_name'] ) ) {
				$addon_name = substr( $addon['title'], 0, $max_addon_name_length );
				$addons[ $addon_key ]['field_name'] = sanitize_title( $prefix . $addon_name . '-' . $wpc_addon_field_counter );
				$wpc_addon_field_counter++;
			}
		}

		return $addons;
	}

	public static function get_wpc_addon_price_for_display( $price, $cart_item = null ) {
		$product = ! empty( $GLOBALS['product'] ) && is_object( $GLOBALS['product'] ) ? clone $GLOBALS['product'] : null;

		if ( $price === '' || $price == '0' ) {
			return;
		}

		$neg = false;

		if ( $price < 0 ) {
			$neg = true;
			$price *= -1;
		}

		if ( ( is_cart() || is_checkout() ) && null !== $cart_item ) {
			$product = wc_get_product( $cart_item->get_id() );
		}

		if ( is_object( $product ) ) {
			if ( function_exists( 'wc_get_price_excluding_tax' ) ) {
				$html_price = self::get_wpc_addon_tax_display_mode() === 'incl' ? 
					wc_get_price_including_tax( $product, [ 'qty' => 1, 'price' => $price ] ) : wc_get_price_excluding_tax( $product, [ 'qty' => 1, 'price' => $price ] );

				if ( ( is_cart() || is_checkout() ) && ! empty( WC()->customer ) && WC()->customer->get_is_vat_exempt() && ! wc_prices_include_tax() ) {
					$html_price = wc_get_price_excluding_tax( $product, [ 'qty' => 1, 'price' => $price ] );
				}
			} else {
				$html_price = self::get_wpc_addon_tax_display_mode() === 'incl' ? $product->get_price_including_tax( 1, $price ) : $product->get_price_excluding_tax( 1, $price );
			}
		} else {
			$html_price = $price;
		}

		if ( $neg ) {
			$html_price = '-' . $html_price;
		}

		return $html_price;
	}

	public static function get_wpc_addon_tax_display_mode() {
		if ( is_cart() || is_checkout() ) {
			return get_option( 'woocommerce_tax_display_cart' );
		}

		return get_option( 'woocommerce_tax_display_shop' );
	}

	public static function get_front_option_data( $opt = [] ) {
		$opt_label    		= ( $opt['label'] === '0' ) || ! empty( $opt['label'] ) ? $opt['label'] : '';
		$price_type   		= ! empty( $opt['price_type'] ) ? $opt['price_type'] : '';
		$price       	 	= ! empty( $opt['price'] ) ? $opt['price'] : '';
		$price_display 	   	= self::get_wpc_addon_price_for_display( $price );
		$price_prefix 		= $price > 0 ? '+' : '';
		$price_for_display 	= '(' . $price_prefix . wc_price( $price_display ) . ')';
		$opt_default  		= ( isset ( $opt['default'] ) && $opt['default'] == 1 ) ? 1 : 0;

		return [
			'opt_label' 		=> $opt_label,
			'price_type' 		=> $price_type,
			'price' 			=> $price,
			'price_display' 	=> $price_display,
			'price_for_display' => $price_for_display,
			'default' 			=> $opt_default,
		];
	}
}
