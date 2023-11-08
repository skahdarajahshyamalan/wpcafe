<?php
namespace WpCafe_Pro\Core\Enqueue;

defined( "ABSPATH" ) || exit;

use WpCafe\Utils\Wpc_Utilities;
use WpCafe_Pro\Traits\Singleton;
use WpCafe_Pro\Utils\Utilities;

/**
 * Enqueue all css and js file class
 */
class Enqueue {

	use Singleton;

	/**
	 * Main calling function
	 */
	public function init() {
			// backend asset
			add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_assets'] );
			// frontend asset
			add_action( 'wp_enqueue_scripts', [$this, 'frontend_enqueue_assets'] );
			// enqueue editor js.
			add_action( 'elementor/frontend/before_enqueue_scripts', [$this, 'elementor_js_pro'] );
	}

	/**
	 * all js files function
	 */
	public function admin_get_scripts() {
			// js
			return [
					'select2'     => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/select2.min.js',
							'version' => \Wpcafe_Pro::version(),
					],
					'wpc-pro-admin' => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/wpc-pro-admin.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => [ 'jquery' ],
					],

					'jquery-timepicker' => [
							'src'     => \Wpcafe::assets_url() . 'js/jquery.timepicker.min.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => ['jquery'],
					],
					'wpc-pro-image-media' => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/image-upload.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => ['jquery'],
					],
					'wpc-pro-common' => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/common.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => ['jquery'],
					],
					'wpc-pro-qr-code' => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/qr-code.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => ['jquery'],
					],
					'wpc-pro-generate-qr' => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/wpc-pro-generate-qr.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => ['jquery', 'wpc-pro-qr-code'],
					],

			];
	}


	/**
	 * all css files function
	 */
	public function admin_get_styles() {
			return [
					'select2'     => [
							'src'     => \Wpcafe_Pro::assets_url() . 'css/select2.min.css',
							'version' => \Wpcafe_Pro::version(),
					],
					'wpc-pro-admin' => [
							'src'     => \Wpcafe_Pro::assets_url() . 'css/wpc-admin-pro.css',
							'version' => \Wpcafe_Pro::version(),
					],
			];
	}

	/**
	 * Enqueue admin js and css function
	 */
	public function admin_enqueue_assets() {
			if ( is_admin() ){
					$settings       = get_option( 'wpcafe_reservation_settings_options' );

					// load script in all admin pages
					wp_enqueue_script( 'wpc-pro-order-notify', \Wpcafe_Pro::assets_url() . 'js/order-notifications.js', 
					['jquery', 'heartbeat'], \Wpcafe_Pro::version(), true );

					$form_obj = array(
							'ajax_url'             => admin_url( 'admin-ajax.php' ),
							'wpc_pro_order_notify' => !empty( $settings["wpc_pro_order_notify"] ) ? $settings['wpc_pro_order_notify'] : '',
							'wpc_pro_sound_notify' => !empty( $settings["wpc_pro_sound_notify"] ) ? $settings['wpc_pro_sound_notify'] : '',
							'wpc_pro_sound_repeat' => !empty( $settings["wpc_pro_sound_repeat"] ) ? $settings['wpc_pro_sound_repeat'] : '',
							'repeat_interval_time' => !empty( $settings["repeat_interval_time"] ) ? $settings['repeat_interval_time'] : '',
							'order_notification'=> wp_create_nonce( 'order_notification' ),
							'last_order_id'     => \WpCafe_Pro\Core\Modules\Order_Notifications\Hooks::get_last_order_id(),
							'audio_url'         => !empty( $settings["sound_media_file"] ) ? wp_get_attachment_url($settings['sound_media_file']) : \Wpcafe_Pro::assets_url() . 'music/ding_dong.mp3',
					);

					$screen         = get_current_screen();
					$admin_page_arr = Wpc_Utilities::admin_page_array();

					// load js only wpcafe page

					if ( in_array( $screen->id , $admin_page_arr ) ) {

							$scripts = $this->admin_get_scripts();

							// js
							if ( !did_action( 'wp_enqueue_media' ) ) {
									wp_enqueue_media();
							}

							//enqueue script
							wp_enqueue_script( 'thickbox' );

							foreach ( $scripts as $key => $value ) {
									$deps       = isset( $value['deps'] ) ? $value['deps'] : [];
									$version    = !empty( $value['version'] ) ? $value['version'] : false;
									wp_enqueue_script( $key, $value['src'], $deps, $version, true );
							}

							$enable_license = ( !empty( $settings["license"] ) ? "yes" : "no" );

							$form_obj['license_module']     = $enable_license;
							$form_obj['reser_date_format']  = get_option('date_format');

							// enqueue style
							wp_enqueue_style( 'thickbox' );
							$styles = $this->admin_get_styles();
							foreach ( $styles as $key => $value ) {
									$deps       = isset( $value['deps'] ) ? $value['deps'] : false;
									$version    = !empty( $value['version'] ) ? $value['version'] : false;
									wp_enqueue_style( $key, $value['src'], $deps, $version, 'all' );
							}
					}

					wp_localize_script( 'wpc-pro-admin', 'admin_object', $form_obj );
					wp_localize_script( 'wpc-pro-order-notify', 'admin_object', $form_obj );

			}
	}

	/**
	 * Make obj to send localize script
	 */
	public function settings_obj() {
			$form_data = [];
			$settings  = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();

			if ( $settings ) {
					$date_format  = get_option('date_format');

					$wpc_pickup_exception_date               = ( isset( $settings['wpc_pickup_exception_date'] ) ? $settings['wpc_pickup_exception_date'] : [] );
					$wpc_delivery_exception_date             = ( isset( $settings['wpc_delivery_exception_date'] ) ? $settings['wpc_delivery_exception_date'] : [] );

					if ( count($wpc_pickup_exception_date) > 0 ) {
							foreach ($wpc_pickup_exception_date as $key => $value) {
									$wpc_pickup_exception_date[$key] = date( $date_format , strtotime($value));
							}
					}
					if ( count($wpc_delivery_exception_date) > 0 ) {
							foreach ($wpc_delivery_exception_date as $key => $value) {
									$wpc_delivery_exception_date[$key] = date( $date_format , strtotime($value));
							}
					}

					$wpc_pickup_holiday                      = ( isset( $settings['wpc_pickup_holiday'] ) ? $settings['wpc_pickup_holiday'] : '' );
					$wpc_delivery_holiday                    = ( isset( $settings['wpc_delivery_holiday'] ) ? $settings['wpc_delivery_holiday'] : '' );
					$wpc_pickup_weekly_schedule              = ( isset( $settings['wpc_pickup_weekly_schedule'] ) ? $settings['wpc_pickup_weekly_schedule'] : '' );
					$wpc_delivery_schedule                   = ( isset( $settings['wpc_delivery_schedule'] ) ? $settings['wpc_delivery_schedule'] : '' );
					$wpc_pickup_weekly_schedule_start_time   = ( isset( $settings['wpc_pickup_weekly_schedule_start_time'] ) ? $settings['wpc_pickup_weekly_schedule_start_time'] : '' );
					$wpc_pickup_weekly_schedule_end_time     = ( isset( $settings['wpc_pickup_weekly_schedule_end_time'] ) ? $settings['wpc_pickup_weekly_schedule_end_time'] : '' );
					$wpc_delivery_weekly_schedule_start_time = ( isset( $settings['wpc_delivery_weekly_schedule_start_time'] ) ? $settings['wpc_delivery_weekly_schedule_start_time'] : '' );
					$wpc_delivery_weekly_schedule_end_time   = ( isset( $settings['wpc_delivery_weekly_schedule_end_time'] ) ? $settings['wpc_delivery_weekly_schedule_end_time'] : '' );
					$pickup_time_interval                    = ( isset( $settings['pickup_time_interval'] ) ? $settings['pickup_time_interval'] : 15 );
					$delivery_time_interval                  = ( isset( $settings['delivery_time_interval'] ) ? $settings['delivery_time_interval'] : 15 );
					$delivery_preparing_date                 = isset( $settings['order_prepare_days'] ) ?  Utilities::preparing_date( $settings['order_prepare_days'] ) : Utilities::preparing_date('0');
					$wpc_pro_allow_pickup_time               = !empty( $settings['wpc_pro_allow_pickup_time'] ) ?  $settings['wpc_pro_allow_pickup_time'] : '';
					$wpc_pro_allow_delivery_time             = !empty( $settings['wpc_pro_allow_delivery_time'] ) ?  $settings['wpc_pro_allow_delivery_time'] : '';
					$pickup_preparing_date                   = Utilities::preparing_date('0');

					$form_data = [
							'wpc_pickup_exception_date'               => $wpc_pickup_exception_date,
							'wpc_delivery_exception_date'             => $wpc_delivery_exception_date,
							'wpc_pickup_holiday'                      => $wpc_pickup_holiday,
							'wpc_delivery_holiday'                    => $wpc_delivery_holiday,
							'wpc_pickup_weekly_schedule'              => $wpc_pickup_weekly_schedule,
							'wpc_delivery_schedule'                   => $wpc_delivery_schedule,
							'wpc_pickup_weekly_schedule_start_time'   => $wpc_pickup_weekly_schedule_start_time,
							'wpc_pickup_weekly_schedule_end_time'     => $wpc_pickup_weekly_schedule_end_time,
							'wpc_delivery_weekly_schedule_start_time' => $wpc_delivery_weekly_schedule_start_time,
							'wpc_delivery_weekly_schedule_end_time'   => $wpc_delivery_weekly_schedule_end_time,
							'pickup_time_interval'                    => $pickup_time_interval,
							'delivery_time_interval'                  => $delivery_time_interval,
							'delivery_preparing_date'                 => $delivery_preparing_date,
							'pickup_preparing_date'                   => $pickup_preparing_date,
							'wpc_pro_allow_pickup_time'               => $wpc_pro_allow_pickup_time,
							'wpc_pro_allow_delivery_time'             => $wpc_pro_allow_delivery_time,
					];
			}

			return $form_data;
	}

	/**
	 * all js files function
	 *
	 * @param $var
	 */
	public function frontend_get_scripts() {
			$widgets_deps = ['jquery'];
			if (class_exists('Woocommerce')) {
					$widgets_deps[] = 'wc-single-product';
			}

			$map_js  = 'https://maps.google.com/maps/api/js?libraries=places';

			$settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
			if ( $settings ) {
					$api_key = !empty( $settings['google_api_key'] ) ? $settings['google_api_key'] : 'AIzaSyBRiJpfKRV-hDFuQ6ynEAStJVO09g5Ecd4';
					$map_js  = $map_js . '&key=' . $api_key;
					$map_js  .= '&callback=Function.prototype';
			}

			return [
					'jquery-timepicker' => [
							'src'     => \Wpcafe::assets_url() . 'js/jquery.timepicker.min.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => ['jquery'],
					],
					'swiper-bundle'  => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/swiper-bundle.min.js',
							'version' => \Wpcafe_Pro::version(),
					],
					'jquery-mCustomScrollbar-concat-min'  => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/jquery.mCustomScrollbar.concat.min.js',
							'version' => \Wpcafe_Pro::version(),
					],
					'wpc-pro-widgets-modal-script'   => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/widgets.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => $widgets_deps
					],
					'select2'     => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/select2.min.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => ['jquery']
					],
					'wpc-pro-map-public' => [
							'src'     => $map_js,
							'version' => \Wpcafe_Pro::version(),
					],
					'wpc-pro-public'             => [
							'src'     => \Wpcafe_Pro::assets_url() . 'js/wpc-pro-public.js',
							'version' => \Wpcafe_Pro::version(),
							'deps'    => ['jquery', 'wpc-pro-widgets-modal-script', 'wpc-public', 'jquery-timepicker', 'wpc-pro-map-public'],
					],
			];
	}

	/**
	 * all css files function
	 *
	 * @param Type $var
	 */
	public function frontend_get_styles() {
			$enequeue =  [
					'jquery-timepicker' => [
							'src'     => \Wpcafe_Pro::assets_url() . 'css/jquery.timepicker.min.css',
							'version' => \Wpcafe_Pro::version(),
					],
					'swiper-bundle'             => [
							'src'     => \Wpcafe_Pro::assets_url() . 'css/swiper-bundle.min.css',
							'version' => \Wpcafe_Pro::version(),
					],
					'jquery-mCustomScrollbar-min'             => [
							'src'     => \Wpcafe_Pro::assets_url() . 'css/jquery.mCustomScrollbar.min.css',
							'version' => \Wpcafe_Pro::version(),
					],

			];

			// divi builder support css load
			if(class_exists( 'ET_Builder_Plugin')){
					$enequeue['wpc-public-pro'] =[
							'src'     => \Wpcafe_Pro::assets_url() . 'css/wpc-divi-builder-support.css',
							'version' => \Wpcafe_Pro::version(),
					];
			}else{
					$enequeue['wpc-public-pro'] =[
							'src'     => \Wpcafe_Pro::assets_url() . 'css/wpc-public-pro.css',
							'version' => \Wpcafe_Pro::version(),
					];
			}

			return $enequeue;
	}

	/**
	 * Enqueue frontend js and css function
	 *
	 * @param  $var
	 */
	public function frontend_enqueue_assets() {
			// js
			$scripts = $this->frontend_get_scripts();

			if (class_exists('Woocommerce')) {

					wp_enqueue_script('wc-add-to-cart-variation');
					wp_enqueue_script('wc-single-product');

					wp_enqueue_script('flexslider');
					wp_enqueue_script('zoom');
			}

			foreach ( $scripts as $key => $value ) {
					$deps       = isset( $value['deps'] ) ? $value['deps'] : false;
					$version    = !empty( $value['version'] ) ? $value['version'] : false;
					wp_enqueue_script( $key, $value['src'], $deps, $version, true );
			}

			$styles = $this->admin_get_styles();
			foreach ( $styles as $key => $value ) {
				$deps       = isset( $value['deps'] ) ? $value['deps'] : false;
				$version    = !empty( $value['version'] ) ? $value['version'] : false;
				wp_enqueue_style( $key, $value['src'], $deps, $version, 'all' );
			}

			// css
			$styles = $this->frontend_get_styles();
			foreach ( $styles as $key => $value ) {
					$deps       = isset( $value['deps'] ) ? $value['deps'] : false;
					$version    = !empty( $value['version'] ) ? $value['version'] : false;
					wp_enqueue_style( $key, $value['src'], $deps, $version, 'all' );
			}

			// reservation field
			$reservation_field_arr = \WpCafe_Pro\Core\Modules\Reservation\Hooks::instance()->reservation_field_array();

			// localize for frontend
			wp_localize_script( 'wpc-pro-public', 'wpc_obj', [
					'ajax_url'              => admin_url( 'admin-ajax.php' ),
					'settings_options'      => $this->settings_obj(),
					'reservation_field_arr' => $reservation_field_arr,
			] );
	}

	/**
	 * Elementor js loaded
	 */
	public function elementor_js_pro() {
			wp_enqueue_script( 'wpc-pro-elementor', \Wpcafe_Pro::assets_url() . 'js/elementor.js', ['elementor-frontend'], \Wpcafe_Pro::version(), true );
	}

}
