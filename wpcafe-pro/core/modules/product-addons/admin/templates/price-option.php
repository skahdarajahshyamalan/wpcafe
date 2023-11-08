<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$option_label             = ! empty( $option['label'] ) ? $option['label'] : '';
$option_price_type        = ! empty( $option['price_type'] ) ? $option['price_type'] : 'flat_fee';
$option_price             = ! empty( $option['price'] ) ? $option['price'] : '';
$option_default           = isset( $option['default'] ) ? $option['default'] : 0;
?>

<div class="wpc-pro-pao-option-row">
	<div class="wpc-pro-pao-option-sort-wrap <?php echo ( $pao_type != 'text' ) ? 'show_block' : 'hide_block'; ?>">
		<span class="wpc-pro-pao-option-sort-handle dashicons dashicons-menu"></span>
	</div>

	<div class="wpc-pro-pao-option-label <?php echo ( $pao_type != 'text' ) ? 'show_block' : 'hide_block'; ?>">
		<input type="text" class="wpc-settings-input" name="wpc_pro_pao_option_label[<?php echo $counter; ?>][]" value="<?php echo esc_attr( $option_label ); ?>" placeholder="<?php esc_html_e( 'Option name', 'wpcafe-pro' ); ?>" />
	</div>

	<div class="wpc-pro-pao-option-price-type">
		<?php
			$price_type_arr = [
				'quantity_based' => esc_html__( 'Quantity Based', 'wpcafe-pro' ),
			];
		?>
		<select name="wpc_pro_pao_option_price_type[<?php echo esc_attr($counter); ?>][]" class="wpc-settings-input wpc-pro-pao-option-price-type">
			<?php
			foreach ( $price_type_arr as $key => $value ) {
			?>
			<option <?php selected( $key, $option_price_type ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
			<?php
			}
			?>
		</select>
	</div>

	<div class="wpc-pro-pao-option-price">
		<input type="text" name="wpc_pro_pao_option_price[<?php echo esc_attr($counter); ?>][]" class="wpc-settings-input wc_input_price wpc_pro_pao_opt_price"
			value="<?php echo esc_attr( wc_format_localized_price( $option_price ) ); ?>" placeholder="0" />
	</div>

	<div class="wpc-label-item wpc-pro-pao-option-default-wrap <?php echo ( $pao_type != 'text' ) ? 'show_block' : 'hide_block'; ?>">
		<div class="wpc-meta wpc-pro-pao-option-default">
			<input type="radio" class="" id="wpc_pro_pao_option_default_<?php echo esc_attr( $counter ); ?>_<?php echo esc_attr( $option_index ); ?>"  name="wpc_pro_pao_option_default[<?php echo esc_attr( $counter ); ?>][]" value="<?php echo esc_attr( $option_index ); ?>" <?php checked( 1, $option_default ); ?> />
			<label for="wpc_pro_pao_option_default_<?php echo esc_attr( $counter ); ?>_<?php echo esc_attr( $option_index ); ?>"><?php esc_html_e( 'Default selected', 'wpcafe-pro' ); ?></label>
		</div>
	</div>
<?php if( intval( $option_index ) !== 0 ): ?>
	<button type="button" class="wpc-pro-pao-option-remove button" style="display: <?php echo ( $pao_type != 'text' ) ? 'block' : 'none'; ?>;">
		<i class="dashicons dashicons-no-alt"></i>
	</button>
<?php endif; ?>
</div>
