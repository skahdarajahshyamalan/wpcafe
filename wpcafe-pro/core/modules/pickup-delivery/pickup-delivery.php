<?php

namespace WpCafe_Pro\Core\Modules\Pickup_Delivery;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pickup Delivery action.
 */
class Pickup_Delivery {

	private static $instance;

	/**
	 * Pickup Delivery action.
	 */
	public function __construct() {

	}

	public function init() {
		add_action( 'wp_enqueue_scripts', [$this, 'delivery_enqueue_assets'] );
		add_action( 'init', [$this, 'add_default_product_tags'], 99999 );

		$callback = ['get_food_by_location_type'];

		if ( ! empty( $callback ) ) {
			foreach ( $callback as $key => $value ) {
				add_action( 'wp_ajax_' . $value, [$this, $value] );
				add_action( 'wp_ajax_nopriv_' . $value, [$this, $value] );
			}
		}

	}

	public static function instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**add scripts and styles */
	public function delivery_enqueue_assets() {

		wp_enqueue_style( 'pickup-delivery-style', \Wpcafe_Pro::core_url() . 'modules/pickup-delivery/assets/css/style.css', [], \Wpcafe_Pro::version(), 'all' );

		wp_enqueue_script( 'pickup-delivery-scripts', esc_url( \Wpcafe_Pro::core_url() . 'modules/pickup-delivery/assets/js/scripts.js' ), array( 'jquery' ), \Wpcafe_Pro::version(), false );

		// localize for frontend
		wp_localize_script( 'pickup-delivery-scripts', 'pickup_delivery_obj', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		] );

	}
	/**
	 * Food Delivery action.
	 */
	public function get_food_by_location_type() {

		$search_html 				= '';
		$post_arr    				= filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

		$template_name 			= ( isset( $post_arr['template_name'] ) ) ? $post_arr['template_name'] : '';
		$pageurl       			= ( isset( $post_arr['pageurl'] ) ) ? $post_arr['pageurl'] : '';
		$search_value  			= ( isset( $post_arr['search_value'] ) ) ? $post_arr['search_value'] : '';
		$location_arr  			= ( isset( $post_arr['location_arr'] ) ) ? $post_arr['location_arr'] : '';
		$tag_arr  					= ( isset( $post_arr['tag_arr'] ) ) ? $post_arr['tag_arr'] : '';
		$cat_arr  					= ( isset( $post_arr['cat_arr'] ) ) ? $post_arr['cat_arr'] : '';
		$single_product_id 	= ( isset( $post_arr['single_product_id'] ) ) ? $post_arr['single_product_id'] : '';
		$product_price 			= ( isset( $post_arr['product_price'] ) ) ? $post_arr['product_price'] : '';
		$product_min_price 	= ( isset( $post_arr['product_min_price'] ) ) ? $post_arr['product_min_price'] : '';

		// search result for products
		$product_search = \WpCafe_Pro\Core\Modules\Food_Menu\Helper::get_product_html( $search_value, $location_arr, $tag_arr, $cat_arr );

		// search result for locations
		$search_html = \WpCafe_Pro\Core\Modules\Food_Menu\Helper::get_search_html( $search_value );

		//products array depending on search result
		$products = \WpCafe_Pro\Core\Modules\Food_Menu\Helper::get_products( $location_arr, $tag_arr, $cat_arr, $single_product_id, $product_price, $product_min_price );

		// return the json success with search data and products
		wp_send_json_success( ['success' => 1, 'message' => 'success',
		'data'          => [
		'search_html'   => $search_html,
		'template_name' => $template_name,
		'pageurl'       => $pageurl,
		'products'      => $products,
		'product_search'=> $product_search,
		]] );

		wp_die();

	}

	// Delivery and Pickup  Added Tags
	public function add_default_product_tags() {
		$org_term = term_exists( 'Pickup', 'product_tag' );
		if ( $org_term === null ) {
			wp_insert_term(
				'Pickup',
				'product_tag',
				[
					'description' => 'Product for Pickup',
					'slug'        => 'pickup',
					'parent'      => 0
				]
			);
		}

		$product_term = term_exists( 'Delivery', 'product_tag' );

		if ( $product_term === null ) {
			wp_insert_term(
				'Delivery',
				'product_tag',
				[
					'description' => 'Product for Delivery',
					'slug'        => 'delivery',
					'parent'      => 0
				]
			);
		}
	}

}
