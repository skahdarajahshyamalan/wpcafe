<?php

use WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\Addon_Helper;

$auto_counter  = 0;
$field_name    = ! empty( $addon['field_name'] ) ? $addon['field_name'] : '';
$required      = ! empty( $addon['required'] ) ? 'required' : false;

$default_option_title = $required ? esc_html__( 'Select an option', 'wpcafe-pro' ) : esc_html__( 'None', 'wpcafe-pro' );

if ( $required ) { ?>
	<div class="wpc-addon-required-block" data-field_type="dropdown">
	<?php
	}
?>

<div class="wpc-addon-wrap wpc-addon-<?php echo sanitize_title( $field_name ); ?>">
	<select name="wpc_addon-<?php echo sanitize_title( $field_name ); ?>" class="wpc-addon-field wpc-addon-select" <?php echo esc_attr($required); ?> id="wpc_addon-<?php echo sanitize_title( $field_name ); ?>">
	<option value="" data-price="0"><?php echo esc_html($default_option_title);  ?></option>

		<?php 
		foreach ( $addon['options'] as $i => $opt ) {
			$auto_counter++;
			$opt_data   		= Addon_Helper::get_front_option_data( $opt );
			$opt_label  		= $opt_data['opt_label'];
			$price_type 		= $opt_data['price_type'];
			$price      		= $opt_data['price'];
			$price_display 	   	= $opt_data['price_display'];
			$price_for_display 	= $opt_data['price_for_display'];
			$opt_default  		= $opt_data['default'];
			?>
			<option value="<?php echo sanitize_title( $opt_label ) . '-' . $auto_counter; ?>" <?php selected( 1, $opt_default ); ?> data-label="<?php echo esc_attr( wptexturize( $opt_label ) ); ?>"
				data-price-type="<?php echo esc_attr( $price_type ); ?>" data-price="<?php echo esc_attr( $price_display ); ?>" ><?php echo wptexturize( $opt_label ) . ' ' . $price_for_display; ?>
			</option>
		<?php 
		} 
		?>

	</select>
</div>

<?php
if ( $required ) { ?>
	</div>
	<?php
}