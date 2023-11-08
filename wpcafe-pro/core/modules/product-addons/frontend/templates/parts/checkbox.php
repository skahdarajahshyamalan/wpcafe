<?php

use WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\Addon_Helper;

$required   = ! empty( $addon['required'] ) ? $addon['required'] : '';
$field_name = ! empty( $addon['field_name'] ) ? $addon['field_name'] : '';

if ( $required ) { ?>
	<div class="wpc-addon-required-block" data-field_type="checkbox">
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
		<div class="wpc-addon-wrap wpc-addon-checkbox-wrap wpc-addon-<?php echo sanitize_title( $field_name ) . '-' . $i; ?>">
			<label>
				<input type="checkbox" <?php echo ( $required ) ? 'required' : '' ; ?> class="wpc-addon-field wpc-addon-checkbox"
					name="wpc_addon-<?php echo sanitize_title( $field_name ); ?>[]" value="<?php echo sanitize_title( $opt_label ); ?>"
					data-price-type="<?php echo esc_attr( $price_type ); ?>" data-price="<?php echo esc_attr( $price_display ); ?>"
					data-label="<?php echo esc_attr( wptexturize( $opt_label ) ); ?>" <?php checked( 1, $opt_default ); ?> />
					<span class="wpc-veriation-attribute">
					<?php echo wptexturize( $opt_label . ' ' . $price_for_display ); ?>
				</span>
			</label>
		</div>
		<?php
	}

if ( $required ) { ?>
	</div>
	<?php
}