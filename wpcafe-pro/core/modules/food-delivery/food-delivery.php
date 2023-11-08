<?php

namespace WpCafe_Pro\Core\Modules\Food_Delivery;

defined( "ABSPATH" ) || exit;

use WC_Session_Handler;
use WpCafe_Pro\Traits\Singleton;
/**
* Food Delivery Module
*/
class Food_Delivery {

		use Singleton;

		public function init() {

				if ( !class_exists('Woocommerce') ) {
						return true;
				}

				// Food order type hooks
				\WpCafe_Pro\Core\Modules\Food_Delivery\Order_Type\Order_Type::instance()->init();
				// Order amount
				\WpCafe_Pro\Core\Modules\Food_Delivery\Order_Amount::instance()->init();

				// remove shipping if order type is "Pickup"
				add_action( 'woocommerce_checkout_update_order_review', [$this,'disable_shipping_calc_on_cart'] );

				if (!empty($_COOKIE['wpcpro_delivery']) && $_COOKIE['wpcpro_delivery'] =="Pickup" ) {
						add_filter( 'woocommerce_cart_ready_to_calc_shipping', [$this,'remove_shipping_calc_on_cart'], 99 );
				}

				add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_assets'] );

				add_action( 'wp_enqueue_scripts', [$this, 'frontend_enqueue_assets'] );
				add_filter( 'woocommerce_available_shipping_methods', [$this,'tl_shipping_on_price'], 10, 1 );

		}

		/**
		 * map js script for map functionalities
		 * @return string
		 */
		public function map_js() {
				$map_js   = 'https://maps.google.com/maps/api/js?libraries=places';

				$settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();

				if ( $settings ) {
						$api_key = !empty( $settings['google_api_key'] ) ? $settings['google_api_key'] : '';
						$map_js  = $map_js . '&key=' . $api_key;
				}

				return $map_js;
		}

		/**
		 * Admin part assets.
		 *
		 * @return void
		 */
		public function admin_enqueue_assets() {
				// for map.
				wp_enqueue_script( 'wpc-pro-map-admin', $this->map_js(), ['jquery'], \Wpcafe_Pro::version(), true );
				// delivery js.
				wp_enqueue_script( 'wpc-delivery-admin', \Wpcafe_Pro::core_url() . 'modules/food-delivery/assets/js/delivery-admin.js', ['jquery', 'wpc-pro-map-admin'], \Wpcafe_Pro::version(), true );
		}

		/**
		 * Frontend part assets
		 *
		 * @return void
		 */
		public function frontend_enqueue_assets() {
				$form_data = array();

				wp_enqueue_style( 'wpc-delivery', \Wpcafe_Pro::core_url() . 'modules/food-delivery/assets/css/delivery-map.css', [], \Wpcafe_Pro::version(), 'all' );

				wp_enqueue_script( 'wpc-pro-map-public', $this->map_js(), ['jquery'], \Wpcafe_Pro::version(), true );
				wp_enqueue_script( 'wpc-delivery-public', \Wpcafe_Pro::core_url() . 'modules/food-delivery/assets/js/delivery-public.js', ['jquery', 'wpc-pro-map-public'], \Wpcafe_Pro::version(), true );

				$settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
				$form_data['address_validation'] = !empty($settings['address_validation']) ? $settings['address_validation'] : '';

				$translation_data = array(
						'ajax_url'              => admin_url( 'admin-ajax.php' ),
						'location_map_nonce'    => wp_create_nonce( 'location_map_nonce' ),
						'form_data'             => $form_data,
						'location_icon'         => \Wpcafe_Pro::assets_url() . 'images/location-icon.png',
					);

				wp_localize_script( 'wpc-delivery-public', 'wpc_pro_delivery_obj', $translation_data);
		}

		/**
		 * Disable shipping
		 */
		public function disable_shipping_calc_on_cart( $posted_data ) {
				$vars = explode('&', $posted_data);
				$post = array();
				foreach ($vars as $k => $value){
						$v = explode('=', urldecode($value));
						$post[$v[0]] = $v[1];
				}

				if ( !empty($post['wpc_pro_order_time']) && $post['wpc_pro_order_time'] == "Pickup" ) {
						add_filter( 'woocommerce_cart_ready_to_calc_shipping', [$this,'remove_shipping_calc_on_cart'], 99 );
				}

		}

		/**
		 * Disable shipping on cart and checkout.
		 */
		function remove_shipping_calc_on_cart( $show_shipping ) {
				if( is_cart() || is_checkout() ) {
						return false;
				}

				return $show_shipping;
		}

}
