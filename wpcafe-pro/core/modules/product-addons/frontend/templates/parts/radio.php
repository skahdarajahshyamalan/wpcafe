<?php

use WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\Addon_Helper;

$auto_counter  = 0;
$required      = ! empty( $addon['required'] ) ?  'required' : false;
$field_name    = ! empty( $addon['field_name'] ) ? $addon['field_name'] : '';
?>

<?php 
if( $required == false ) { ?>
	<!-- add none option to unselect other radio option -->
	<div class="wpc-addon-wrap wpc-addon-<?php echo sanitize_title( $field_name ); ?>">
		<label>
			<input type="radio" class="wpc-addon-field wpc-addon-radio" name="wpc_addon-<?php echo sanitize_title( $field_name ); ?>[]" data-price="0" value="" />
			<span class="wpc-veriation-attribute">
				<?php esc_html_e( ' None', 'wpcafe-pro' ); ?>
			</span>
		</label>
	</div>
	<?php 
	} else {
		?>
		<div class="wpc-addon-required-block" data-field_type="radio">
		<?php
	}
?>

<?php
	foreach ( $addon['options'] as $i => $opt ) {
		$auto_counter++;
		$opt_data   		= Addon_Helper::get_front_option_data( $opt );
		$opt_label  		= $opt_data['opt_label'];
		$price_type 		= $opt_data['price_type'];
		$price     	 		= $opt_data['price'];
		$price_display 	   	= $opt_data['price_display'];
		$price_for_display 	= $opt_data['price_for_display'];
		$opt_default  		= $opt_data['default'];
		?>
		<div class="wpc-addon-wrap wpc-addon-<?php echo sanitize_title( $field_name ); ?>">
			<label>
				<input type="radio" <?php echo esc_attr( $required ); ?> class="wpc-addon-field wpc-addon-radio" 
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
?>
