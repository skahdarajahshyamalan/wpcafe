<div class="wpc-reservation-pro-wrap wpc-food-tab-wrapper reserv-with-food-wrap">
		<div class='wpc-tab tab-active' data-id='wpc-tab-1' id="wpc-multi-step-reservation">
		<?php
				$reservation_submit_class   = empty( $atts['reservation_food'] ) ? 'reservation_form_submit' : 'save_reservaion_data';
				// Reservation tab pan section.
				include \WpCafe_Pro::plugin_dir() . "core/shortcodes/views/reservation/tab-pane.php";
		?>
		<div class='wpc-reservation-form <?php echo esc_attr($cancellation_option) ?>'
								data-reservation_status='<?php echo json_encode( $booking_status ) ?>'>
								<div class='late_booking' data-late_booking="<?php echo esc_html($late_one.$late_two.$late_three.$late_four.$late_five);?>"></div>
								<div class='wpc_error_message' data-time_compare="<?php echo esc_html__('Booking end time must be after start time','wpcafe-pro')?>"></div>
								<div class='wpc_success_message' data-start="<?php echo esc_html__("Start time","wpcafe-pro");?>" data-end="<?php echo esc_html__("End time","wpcafe-pro");?>" data-schedule="<?php echo esc_html__("Schedule","wpcafe-pro");?>" data-late_booking = "<?php echo ( $wpc_late_bookings !=="" ) ?  sprintf( esc_html__("You can book up until %s minutes before closing time","wpcafe-pro") , $wpc_late_bookings  ) : "" ?>"></div>
								<div class='wpc_calender_view' data-view="<?php echo esc_html($view);?>"></div>
								<div class='date_missing' data-date_missing="<?php echo esc_html__("Please select a date first","wpcafe-pro");?>"></div>
								<div class='form_style' data-form_style="pro-<?php echo esc_attr( $style )?>"  data-form_type="pro"></div>

								<form method='post' class='wpc_reservation_table'>
										<input type='hidden' name='wpc_action' value='wpc_reservation' />
												<div class="wpc_reservation_form">
														<!-- fieldset -->
														<div class="wpc-field-set">
																<h3 class="reservation-form-title"><?php echo esc_html__('Reservation Details :', 'wpcafe-pro'); ?></h3>
																<?php
																if (file_exists( \WpCafe_Pro::plugin_dir() .'core/shortcodes/views/reservation/reservation-details.php') ) {
																		include \WpCafe_Pro::plugin_dir() .'core/shortcodes/views/reservation/reservation-details.php';
																}
															?>
																<h3 class="reservation-form-title personal-details-title"><?php echo esc_html__('Personal Details :', 'wpcafe-pro'); ?></h3>

																<?php
																if (file_exists( \WpCafe_Pro::plugin_dir() .'core/shortcodes/views/reservation/reservation-personal-info.php') ) {
																		include \WpCafe_Pro::plugin_dir() .'core/shortcodes/views/reservation/reservation-personal-info.php';
																}
																?>

																<input type='hidden' value='reservation_form_first_step' class='reservation_form_first_step' />
																<button type='button' class='<?php esc_attr_e( $reservation_submit_class )?> reservation_form_submit wpc-btn wpc-form-next'><?php echo esc_html__( 'Next Step' , 'wpcafe-pro' ); ?></button>

														</div>
														<!-- field set -->
														<div class="wpc-field-set">

														<?php

																$data = '';
																foreach ($atts as $key=> $value){
																		if($key === 0){
																				$data .= $value ." ";
																		}else{
																				$data.= $key."=". $value . " ";
																		}
																}
																$shortcode = '['. $data. ']';

																if ( !empty( $atts['reservation_food'] ) ) { 
																		?>
																		<div class="reserv-with-food-menu-wrapper">
																				<?php 
																						echo do_shortcode( $shortcode);
																				?>

																		</div>
																		<?php
																}
																?>

																<input type="button" name="previous" class="wpc-form-previous action-button wpc-btn" value="<?php echo esc_html__('Previous', 'wpcafe-pro'); ?>" />

																<input type='hidden' value='reservation_form_first_step' class='reservation_form_first_step' />
																<button type='button' class='reservation_form_submit wpc-btn wpc-form-next'><?php echo esc_html__( 'Next Step' , 'wpcafe-pro' ); ?></button>
														</div>
														<!-- field set -->
														<div class="wpc-field-set">
																<div class="wpc-reservation-form wpc_reservation_info">
																		<!-- booking details -->
																		<h3 class="reservation-form-title"><?php echo esc_html__('Reservation Details :', 'wpcafe-pro'); ?></h3>

																		<?php
																				if ( file_exists( \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/reservation-detials.php" ) ) {
																						include \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/reservation-detials.php";
																				}
																		?>
																		<input type="button" name="previous" class="wpc-form-previous action-button wpc-btn" value="<?php echo esc_html__('Previous', 'wpcafe-pro'); ?>" />
																		<?php if(class_exists('WooCommerce')){ ?>
																				<a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="wpc-btn checout-btn">
																						<?php echo esc_html__('Checkout to Confirm Your Reservation','wpcafe-pro') ?>
																				</a>
																		<?php } ?>
																</div>
														</div>
												</div>
								</form>
						</div>
		</div>
</div>
