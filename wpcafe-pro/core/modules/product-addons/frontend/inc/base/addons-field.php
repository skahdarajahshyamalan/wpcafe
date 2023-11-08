<?php

defined('ABSPATH') || exit;

/**
 * Product_Addon_Field
 */
abstract class Product_Addons_Field {
	public $addon;
	public $value;
	public $option_type;

	public function __construct( $args ) {
		$this->addon = $args['addon'];
		$this->value = $args['value'];
		$this->option_type = $args['option_type'];
	}

	public function get_cart_item_content() {
		return false;
	}

	public function get_global_addon_data( $product_id = null ) {
		return [];
	}

	public function validate_value() {
		return true;
	}

	public function get_addon_field_name() {
		return 'wpc_addon-' . sanitize_title( $this->addon['field_name'] );
	}

	public function get_field_option_label( $option ) {
		return ! empty( $option['label'] ) ? sanitize_text_field( $this->addon['name'] ) . ' - ' . sanitize_text_field( $option['label'] ) : sanitize_text_field( $this->addon['name'] );
	}

	public function get_field_option_price( $option ) {
		return $option['price'];
	}	
}