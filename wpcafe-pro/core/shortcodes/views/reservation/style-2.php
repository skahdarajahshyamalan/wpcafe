<?php

$cancellation_form_template = \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/cancellation-form-template.php";

// check if reservation food
$reservation_submit_class   = empty( $atts['reservation_food'] ) ? 'confirm_booking_btn' : 'save_reservaion_data';
if ( isset($atts['form_style']) ) {
		switch ( $atts['form_style'] ) {
				case "1":
						$view = "no";
						$column_lg = "wpc-col-lg-6";
						$column_md = "wpc-col-md-12";
						break;
				default:
						$view = "no";
						break;
		}
}

$wpc_allow_cancellation = !empty( $settings['wpc_allow_cancellation'] ) ? $settings['wpc_allow_cancellation'] : "off";
$wpc_late_bookings   = isset($settings['wpc_late_bookings']) && $settings['wpc_late_bookings'] !== "1"  ? $settings['wpc_late_bookings'] : "";
$multi_schedule      = !empty($settings['reser_multi_schedule']) ? $settings['reser_multi_schedule'] : "off";
$multi_sch_class     = $multi_schedule == "on" ? "wpc-multi-reservation-msg" : "";
$booking_style_name  = "cafe_pro_reserve_style_2";
?>
<!-- Form markup -->
<div class="wpc-reservation-pro-wrap wpc-food-tab-wrapper inline-reservation">
		<?php if ( empty( $atts['reservation_food'] ) ) : ?>
			<ul class="wpc-nav wpc-d-none">
				<li>
					<a href='#' class='wpc-tab-a wpc-active' data-id='wpc-tab-1'>
							<span><?php echo esc_html__('Book A Table', 'wpcafe-pro'); ?></span>
					</a>
				</li>
				<?php if ( !empty( $settings['wpc_allow_cancellation'] ) && $settings['wpc_allow_cancellation'] !=="off" && file_exists( $cancellation_form_template ) ) {  ?>
				<li>
					<a href='#' class='wpc-tab-a' data-id='wpc-tab-2'>
							<span><?php echo esc_html__('Request Cancellation', 'wpcafe-pro'); ?></span>
					</a>
				</li>
					<?php } ?>
			</ul>
		<?php endif; ?>

		<div class="wpc-tab-content wpc-widget-wrapper reservation_section">

				<div class='wpc-tab tab-active' data-id='wpc-tab-1' id="wpc-multi-step-reservation">
						<?php
								// Reservation tab pan section.
								include \WpCafe_Pro::plugin_dir() . "core/shortcodes/views/reservation/tab-pane.php";
						?>
						<div class='wpc-reservation-form <?php echo esc_attr($cancellation_option) ?>'
								data-reservation_status='<?php echo json_encode( $booking_status ) ?>'>
								<div class='late_booking' data-late_booking="<?php echo esc_html($late_one.$late_two.$late_three.$late_four.$late_five);?>"></div>
								<div class='wpc_error_message' data-time_compare="<?php echo esc_html__('Booking end time must be after start time','wpcafe-pro')?>"></div>
								<div class='wpc_success_message <?php esc_attr_e($multi_sch_class)?>' data-start="<?php echo esc_html__("Start time","wpcafe-pro");?>" data-end="<?php echo esc_html__("End time","wpcafe-pro");?>"
								data-schedule="<?php echo esc_html__("Schedule","wpcafe-pro");?>" data-late_booking = "<?php echo ( $wpc_late_bookings !=="" ) ?
								sprintf(esc_html__("You can book up until %s minutes before closing time","wpcafe-pro") , $wpc_late_bookings ) : "" ?>"></div>
								<div class='wpc_calender_view' data-view="<?php echo esc_html($view);?>"></div>
								<div class='date_missing' data-date_missing="<?php echo esc_html__("Please select a date first","wpcafe-pro");?>"></div>
								<div class='form_style' data-form_style="pro-<?php echo esc_attr( $style )?>"  data-form_type="pro"></div>

								<form method='post' class='wpc_reservation_table'>
										<input type='hidden' name='wpc_action' value='wpc_reservation' />
												<div class="wpc_reservation_form">
														<!-- fieldset -->
														<div class="wpc-field-set">
															<div class="field-wrap">
																<!-- branch list -->
																<?php
																if (file_exists( \WpCafe_Pro::plugin_dir() .'core/shortcodes/views/reservation/reservation-details.php') ) {
																		include \WpCafe_Pro::plugin_dir() .'core/shortcodes/views/reservation/reservation-details.php';
																}
																?>
																<button type="button" name="next" class="wpc-form-next action-button wpc-btn" value="<?php echo esc_html__('Next', 'wpcafe-pro'); ?>">
																	<?php echo esc_html__('next','wpcafe-pro'); ?>
																	<svg width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
																		<path d="M0 8.39453L20 8.39453" stroke="#EDE2D7" stroke-width="2"/>
																		<path d="M13.2849 0.863397C13.8994 2.66283 16.0499 6.70935 19.7365 8.5" stroke="#EDE2D7" stroke-width="2"/>
																		<path d="M13.2849 16.1366C13.8994 14.3372 16.0499 10.2907 19.7365 8.5" stroke="#EDE2D7" stroke-width="2"/>
																	</svg>
																</button>
																
															</div>
														</div>
														<!-- field set -->
														<div class="wpc-field-set">
																<div class="field-wrap">
																	<?php

																		if ( file_exists( \WpCafe_Pro::plugin_dir() . 'core/shortcodes/views/reservation/reservation-personal-info.php' ) ) {
																				include \WpCafe_Pro::plugin_dir() .'core/shortcodes/views/reservation/reservation-personal-info.php';
																		}

																		?>

																		<div class='wpc-reservation-field wpc-webhook'>
																			<input type='hidden' placeholder='<?php echo esc_html__('Webhook url', 'wpcafe'); ?>' name='wpc_webhook' class='wpc-form-control wpc_webhook' id='wpc-webhook' value='<?php echo esc_html($fluent_crm_webhook); ?>'>
																		</div>

																		<button type="button" name="previous" class="wpc-form-previous action-button wpc-btn" value="<?php echo esc_html__('Previous', 'wpcafe-pro'); ?>">
																			<svg width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
																				<path d="M21 8.39551L1 8.39551" stroke="#EDE2D7" stroke-width="2"/>
																				<path d="M7.71509 0.863397C7.10065 2.66283 4.95011 6.70935 1.26347 8.5" stroke="#EDE2D7" stroke-width="2"/>
																				<path d="M7.71509 16.1366C7.10065 14.3372 4.95011 10.2907 1.26347 8.5" stroke="#EDE2D7" stroke-width="2"/>
																			</svg>
																			<?php echo esc_html__('Previous','wpcafe-pro'); ?>
																		</button>

																		<input type='hidden' value='reservation_form_first_step' class='reservation_form_first_step' />
																		<button type='button' class='reservation_form_submit wpc-btn wpc-form-next'>
																			<?php echo esc_html__( 'Next' , 'wpcafe-pro' ); ?>
																			<svg width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
																				<path d="M0 8.39453L20 8.39453" stroke="#EDE2D7" stroke-width="2"/>
																				<path d="M13.2849 0.863397C13.8994 2.66283 16.0499 6.70935 19.7365 8.5" stroke="#EDE2D7" stroke-width="2"/>
																				<path d="M13.2849 16.1366C13.8994 14.3372 16.0499 10.2907 19.7365 8.5" stroke="#EDE2D7" stroke-width="2"/>
																			</svg>
																		</button>
																</div>
														</div>
														<!-- field set -->
														<div class="wpc-field-set">
																<div class="field-wrap">
																	<div class="wpc-reservation-form wpc_reservation_info">
																			<!-- Reservation details -->
																			<?php
																					if ( file_exists( \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/reservation-detials.php" ) ) {
																							include \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/reservation-detials.php";
																					}
																			?>
																			<button type="button" name="previous" class="wpc-form-previous action-button wpc-btn" value="<?php echo esc_html__('Previous', 'wpcafe-pro'); ?>">
																				<svg width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
																					<path d="M21 8.39551L1 8.39551" stroke="#EDE2D7" stroke-width="2"/>
																					<path d="M7.71509 0.863397C7.10065 2.66283 4.95011 6.70935 1.26347 8.5" stroke="#EDE2D7" stroke-width="2"/>
																					<path d="M7.71509 16.1366C7.10065 14.3372 4.95011 10.2907 1.26347 8.5" stroke="#EDE2D7" stroke-width="2"/>
																				</svg>
																				<?php echo esc_html__('Previous', 'wpcafe-pro'); ?>																				
																			</button>
																			<button class='<?php esc_attr_e( $reservation_submit_class )?> wpc-btn wpc-form-next' data-id='reservation_form_second_step'><?php echo esc_html( $booking_button_text ); ?></button>


																	</div>
																	<div class="wpc-reservation-success">
																			<?php
																					$image_source = "images/reservation-success.png";
																					$img_class = '';
																					if( !empty( $atts['reservation_food'] ) ) {
																							$image_source = "images/reservation-food.png";
																							$img_class = 'resev-success-img';
																					}
																			?>
																			<div class="image_block <?php echo esc_attr($img_class); ?>">
																					<img src="<?php echo esc_url( \Wpcafe_Pro::assets_url() . $image_source ) ?>" alt="success"/>
																			</div>
																			<h3 class="message_block_one success-title1">
																					<?php echo esc_html__("Awesome","wpcafe-pro")?>
																			</h3>
																			<!-- Check Food with reservation  -->
																			<?php
																			if( empty( $atts['reservation_food'] ) ) {
																					?>
																					<h4 class="message_block_two success-title2">
																							<?php echo esc_html__("Your reservation has been received. Check your email for details.","wpcafe-pro")?>
																					</h4>
																					<p class="booking_details" data-booking_details="<?php echo esc_attr__("Your Invoice no.", "wpcafe-pro"); ?>"></p>

																					<button class="wpc-another-reservation action-button wpc-btn" name="another_reservation"><i class="dashicons dashicons-image-rotate"></i><?php echo esc_html__('Book Again', 'wpcafe-pro'); ?></button>
																					<?php
																			} else {
																					?>
																					<p>
																							<?php echo esc_html__('Your reservation is almost complete. Select foods from the menu to complete reservation', 'wpcafe-pro');?>
																					</p>
																					<div class="wpc-reservation-btn-wrap">
																							<a href="" class="wpc-btn menu-select">
																											<?php echo esc_html__('Select Menu','wpcafe-pro') ?>
																							</a>
																							<?php if(class_exists('WooCommerce')){ ?>
																									<a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="wpc-btn checout-btn">
																											<?php echo esc_html__('Go to Checkout','wpcafe-pro') ?>
																									</a>
																							<?php } ?>
																					</div>
																					<?php
																			}
																			?>
																	</div>
																</div>
														</div>
												</div>
								</form>
						</div>

				</div><!-- Tab pane 1 end -->

				<div class='wpc-tab' data-id='wpc-tab-2'>
						<?php
								if ( $wpc_allow_cancellation == "on" && empty( $atts['reservation_food'] )) {
										?>
										<div class='wpc_cancell_log_message'></div>
										<?php
										include \Wpcafe::core_dir() ."shortcodes/views/reservation/cancellation-form-template.php";
								}

						?>
				</div><!-- Tab pane 1 end -->

		</div><!-- Tab content-->
</div>


<!-- food menu -->
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
		<div class="reserv-with-food-menu-wrap">
				<span class="wpc-food-menu-close">  x  </span>
				<?php
						echo do_shortcode( $shortcode );
				?>
				<a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="wpc-btn wpc-checkout-btn">
						<?php echo esc_html__('Go to Checkout','wpcafe-pro') ?>
				</a>
		</div>
		<?php
}
?>
