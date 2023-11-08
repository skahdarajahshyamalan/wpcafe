<?php

use WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\Addon_Helper;

$required   	= ! empty( $addon['required'] ) ? $addon['required'] : '';
$field_name 	= ! empty( $addon['field_name'] ) ? $addon['field_name'] : '';
$place_holder 	= ! empty( $addon['place_holder'] ) ? $addon['place_holder'] : '';
$char_min 		= ! empty( $addon['char_min'] ) ? $addon['char_min'] : 0;
$char_max 		= ! empty( $addon['char_max'] ) ? $addon['char_max'] : 0;

if ( $required ) { ?>
	<div class="wpc-addon-required-block" data-field_type="text">
	<?php
	}

	foreach ( $addon['options'] as $i => $opt ) {
		$opt_data   		= Addon_Helper::get_front_option_data( $opt );
		$opt_label  		= $opt_data['opt_label'];
		$price_type 		= $opt_data['price_type'];
		$price      		= $opt_data['price'];
		$price_display 	   	= $opt_data['price_display'];
		$price_for_display 	= $opt_data['price_for_display'];
		$opt_default  		= $opt_data['default'];
		?>
		<div class="wpc-addon-wrap wpc-addon-text-wrap wpc-addon-<?php echo sanitize_title( $field_name ) . '-' . $i; ?>">
			<label>
				<textarea cols="40" rows="2" <?php echo ( $required ) ? 'required' : ''; ?> class="wpc-addon-field wpc-addon-text"
					name="wpc_addon-<?php echo sanitize_title( $field_name ); ?>[]" value="<?php echo sanitize_title( $opt_label ); ?>"
					data-price-type="<?php echo esc_attr( $price_type ); ?>" data-price="<?php echo esc_attr( $price_display ); ?>"
					data-label="<?php echo esc_attr( wptexturize( $opt_label ) ); ?>" placeholder="<?php echo esc_attr( $place_holder ); ?>"
					minlength="<?php echo esc_attr( $char_min ); ?>" maxlength="<?php echo intval($char_max) > 0 ? esc_attr( $char_max ) : '' ; ?>"></textarea>
			</label>
		</div>
		<?php
	}

if ( $required ) { ?>
	</div>
	<?php
}