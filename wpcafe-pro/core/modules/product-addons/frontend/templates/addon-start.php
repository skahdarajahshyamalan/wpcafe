<?php

use WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Inc\Addon_Helper;

global $product;
$product_title = $product->get_name();

$addon_type = !empty( $addon['type'] ) ? $addon['type'] : '';
$title_format = !empty( $addon['title_format'] ) ? $addon['title_format'] : '';
$name = !empty( $addon['title'] ) ? $addon['title'] : '';
$required = !empty( $addon['required'] ) ? 'required' : false;
$desc_enable = isset( $addon['desc_enable'] ) ? $addon['desc_enable'] : false;
$description = !empty( $addon['desc'] ) ? $addon['desc'] : '';

$price_display = '';
$addon_price_type = !empty( $addon['price_type'] ) ? $addon['price_type'] : '';
$addon_price = !empty( $addon['price'] ) ? $addon['price'] : '';
?>

<?php
if ( $addon_type == 'text' && isset( $addon['options'] ) ) {
	foreach ( $addon['options'] as $i => $opt ) {
		$opt_data 				  = Addon_Helper::get_front_option_data( $opt );
		$opt_label_header		  = $opt_data['opt_label'];
		$price_header			  = $opt_data['price'];
		$price_for_display_header = $opt_data['price_for_display'];
	}
}
?>

<div class="wpc-inner-addon-container wpc-addon <?php echo esc_attr( $required ); ?> wpc-addon-<?php echo sanitize_title( $name ); ?>" data-product-name="<?php echo esc_attr( $product_title ); ?>">
	<?php if ( $title_format === 'heading' ) { ?>
	<h4 class="wpc-addon-name" data-addon-name="<?php echo esc_attr( wptexturize( $name ) ); ?>"><?php echo wptexturize( $name ); ?> <?php echo esc_attr($required) ? '<em class="required" title="' . esc_attr__( 'Required field', 'wpcafe-pro' ) . '">*</em>&nbsp;' : ''; ?>
		<?php if ( $required ) { ?>
			<span class="wpc-reqired-text"><?php echo esc_html__( '(This field is required)', 'wpcafe-pro' ); ?></span>
		<?php } ?>
		<?php echo wp_kses_post( $price_display ); ?>

		<?php if ( $addon_type == 'text' && !empty( $price_header) ) { ?>
			<span class="wpc-veriation-attribute">
			<?php echo wptexturize( $opt_label_header. ' ' . $price_for_display_header); ?>
			</span>
		<?php } ?>
	</h4>
	<?php } else if ( $title_format === 'label' ) { ?>
	<label for="<?php echo 'wpc_addon-' . esc_attr( wptexturize( $addon['title'] ) ); ?>" class="wpc-addon-name" data-addon-name="<?php echo esc_attr( wptexturize( $name ) ); ?>"><?php echo wptexturize( $name ); ?> <?php echo esc_attr($required) ? '<em class="required" title="' . esc_attr__( 'Required field', 'wpcafe-pro' ) . '">*</em>&nbsp;' : ''; ?>
		<?php if ( $required ) { ?>
			<span class="wpc-reqired-text"><?php echo esc_html__( '(This field is required)', 'wpcafe-pro' ); ?></span>
		<?php } ?>
		<?php echo wp_kses_post( $price_display ); ?>

		<?php if ( $addon_type == 'text' && !empty( $price_header) ) { ?>
			<span class="wpc-veriation-attribute">
				<?php echo wptexturize( $opt_label_header. ' ' . $price_for_display_header); ?>
			</span>
		<?php } ?>
	</label>
	<?php } ?>

	<?php if ( $desc_enable ) { ?>
		<p><?php echo esc_html( $description ); ?></p>
	<?php } ?>
