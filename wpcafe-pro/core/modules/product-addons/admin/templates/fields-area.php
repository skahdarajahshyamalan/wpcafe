<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="wpc_pro_pao_content" class="panel woocommerce_options_panel">
	<div class="wpc-pro-pao-header">
		<p><strong><?php esc_html_e( 'Addon Fields', 'wpcafe-pro' ); ?></strong></p>
	</div>

	<div class="wpc_pro_pao_main_block">
		<?php
		$counter = 0;
		foreach ( $product_paos as $pao ) {
			include( dirname( __FILE__ ) . '/single-field.php' );
			$counter++;
		}

		// show default single field if no global addon is added yet
		if ( isset( $addon_section ) && empty( $product_paos ) ) {
			include( dirname( __FILE__ ) . '/single-field.php' );
			$counter = 1;
		}
		?>
	</div>

	<div class="wpc-pro-pao-actions">
		<div class="wpc-row">
			<div class="wpc-col-md-9">
				<button type="button" class="wpc-btn wpc_pro_pao_add_fields" data-next_pao_index="<?php echo esc_attr( $counter ); ?>"><?php esc_html_e( 'Add New Field', 'wpcafe-pro' ); ?></button>
			</div>
			<div class="wpc-col-md-3">
				<?php
				if ( !isset( $addon_section ) ) {
					$linked_url = esc_url( admin_url() . 'admin.php?page=wpc_product_addons' );
					?>
					<a href="<?php echo esc_url( $linked_url ); ?>"><?php esc_html_e( 'Global addons', 'wpcafe-pro' ) ?></a>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
