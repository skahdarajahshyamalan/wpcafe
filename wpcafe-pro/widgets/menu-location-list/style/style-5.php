<?php

		use WpCafe\Utils\Wpc_Utilities;

		$location_latitude  = '37.4224428';
		$location_longitude = '-122.0842467';
		$default_address    = '';

		global $wp;
		$redirect_url = home_url( $wp->request );

		$locations_html = esc_html__( 'No store is added yet.', 'wpcafe-pro' );
		$all_locations  = Wpc_Utilities::get_location_data( '', '', 'id', $location_limit );

if ( empty( $ajax_response ) ) {
?>

		<div class="wpc_loc_address_wrap">
			<div class="wpc_loc_form">
					<input id="wpc_loc_address" class="wpc_loc_address" type="text" name="wpc_loc_address" value="<?php echo esc_attr( $default_address ); ?>" placeholder="<?php echo esc_html__('Enter your address', 'wpcafe-pro'); ?>">
					<!-- search result -->
					<div class="near_location"></div>
					<a href="#" id="wpc_loc_my_position" class="wpc_loc_my_position">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10 16.75C13.7279 16.75 16.75 13.7279 16.75 10C16.75 6.27208 13.7279 3.25 10 3.25C6.27208 3.25 3.25 6.27208 3.25 10C3.25 13.7279 6.27208 16.75 10 16.75Z" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M9.9998 12.7C11.491 12.7 12.6998 11.4912 12.6998 10C12.6998 8.50888 11.491 7.30005 9.9998 7.30005C8.50864 7.30005 7.2998 8.50888 7.2998 10C7.2998 11.4912 8.50864 12.7 9.9998 12.7Z" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M10 2.8V1" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M2.8 10H1" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M10 17.2V19" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M17.2002 10H19.0002" stroke="#DA1212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>

							<?php echo esc_html__( 'Find me', 'wpcafe-pro' ); ?>
					</a>
					<div class="wpc_button_wrapper">
							<button aria-label="<?php echo esc_html__( 'Search location button', 'wpcafe-pro' ); ?>" class="button button-success wpc_loc_address_search">
									<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M8.6 16.2C12.7974 16.2 16.2 12.7974 16.2 8.6C16.2 4.40264 12.7974 1 8.6 1C4.40264 1 1 4.40264 1 8.6C1 12.7974 4.40264 16.2 8.6 16.2Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M17.0004 16.9999L15.4004 15.3999" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
							</button>
					</div>
			</div>

			<!-- pickup/ delivery button -->
			<div class="wpc_btn_group">
					<button class="button button-success wpc_opt_delivery_pickup wpc_opt_delivery active" data-opt="Delivery"><?php echo esc_html__('Delivery', 'wpcafe-pro'); ?></button>
					<button class="button button-success wpc_opt_delivery_pickup wpc_opt_pickup" data-opt="Pickup"><?php echo esc_html__('Pickup', 'wpcafe-pro'); ?></button>
			</div>
		</div>
<?php } ?>
		<div class="wpc_map_and_result_wrapper <?php empty( $ajax_response ) ? 'wpc_map_loading' : '' ; ?>">
						<?php

							if ( file_exists( \Wpcafe_Pro::plugin_dir() . "/widgets/menu-location-list/style/part/location.php" )) {
									include \Wpcafe_Pro::plugin_dir() . "/widgets/menu-location-list/style/part/location.php";
							}

						?>
					<div class="wpc-front-map" data-lat="<?php echo esc_attr( $location_latitude ); ?>" data-long="<?php echo esc_attr( $location_longitude ); ?>" data-zoom="14" data-radius="25"  data-redirect_url="<?php echo esc_url( $redirect_url ); ?>">
							<div id="wpc-front-map-container"></div>
					</div>
		</div>
		<div class="wpc_loader_wrapper">
				<div class="loder-dot dot-a"></div>
				<div class="loder-dot dot-b"></div>
				<div class="loder-dot dot-c"></div>
				<div class="loder-dot dot-d"></div>
				<div class="loder-dot dot-e"></div>
				<div class="loder-dot dot-f"></div>
				<div class="loder-dot dot-g"></div>
				<div class="loder-dot dot-h"></div>
		</div>

