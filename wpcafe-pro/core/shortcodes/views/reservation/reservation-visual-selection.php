<?php
    $view = "no";
?>

<div class='wpc-reservation-form <?php echo esc_attr($cancellation_option) ?>' 
data-reservation_status='<?php echo json_encode( $booking_status ) ?>'>
    <div class='late_booking' data-late_booking="<?php echo esc_html($late_one.$late_two.$late_three.$late_four.$late_five);?>"></div>
    <div class='wpc_cancell_log_message'></div>
    <div class='wpc_error_message' data-time_compare="<?php echo esc_html__('Booking end time must be after start time','wpcafe-pro')?>"></div>
    <div class='wpc_success_message' data-start="<?php echo esc_html__("Start time","wpcafe-pro");?>" data-end="<?php echo esc_html__("End time","wpcafe-pro");?>" data-schedule="<?php echo esc_html__("Schedule","wpcafe-pro");?>" data-late_booking = "<?php echo ( $wpc_late_bookings !=="" ) 
    ?  sprintf( esc_html__("You can book up until %s minutes before closing time","wpcafe-pro") , $wpc_late_bookings  ) : "" ?>"></div>
    <div class='wpc_calender_view' data-view="<?php echo esc_html($view);?>"></div>
    <div class='date_missing' data-date_missing="<?php echo esc_html__("Please select a date first","wpcafe-pro");?>"></div>
    <div class="form_style" data-form_style="free-<?php echo esc_attr( $style )?>" data-form_type="free"></div>
    <div class='wpc_reservation_form_two' style='display:none;'>
        <div class='wpc_reservation_form_two'>
            <form method='post' class=' wpc_reservation_table'>
                <div class='wpc-reservation-form'>
                    <div class='wpc-row'>
                        <div class='wpc-col-lg-12'>
                            <div class='wpc_reservation_form wpc_reservation_user_info'>
                                <!-- Reservation booking detials -->
                                <?php
                                    if ( file_exists( \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/reservation-detials.php" ) ) {
                                        include_once \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/reservation-detials.php";
                                    }
                                ?>
                                <button class='confirm_booking_btn wpc-btn' data-id='reservation_form_second_step'><?php echo esc_html( $booking_button_text ); ?></button>
                                <button class='edit_booking_btn wpc-btn' data-id='edit_booking_btn'><?php echo esc_html__('Edit Booking', 'wpcafe-pro'); ?></button>
                                <button class="wpc-another-reservation-free action-button wpc-btn" name="another_reservation_free"><i class="dashicons dashicons-image-rotate"></i><?php echo esc_html__('Book Again', 'wpcafe-pro'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
 
    <form method='post' class='wpc_reservation_table'>
        <input type='hidden' name='wpc_action' value='wpc_reservation' />

        <div class='wpc_reservation_form_one'>
            <div class="wpc-row">
                <div class='wpc-col-lg-12'>
                    <div class="wpc_reservation_form">
                        <!-- date -->
                        <div class='wpc-row'>
                            <div class='wpc-col-lg-12'>
                                <div class='wpc-reservation-field'>
                                    <label for='wpc_booking_date'><?php echo esc_html__('Date', 'wpcafe-pro'); ?><small class='wpc_required'>*</small></label>
                                    <input type='text' placeholder='<?php echo esc_html__('Booking date here', 'wpcafe-pro'); ?>' name='wpc_booking_date' class='wpc-form-control' id='wpc_booking_date' value='' required aria-required='true' />
                                </div>
                            </div> 
                        </div>
                          <!-- from to -->
                          <div class="wpc-row">
                            <div class='wpc-col-lg-6'>
                                <?php if( $show_form_field == 'on'): ?>
                                    <div class='wpc-reservation-field time'>
                                        <label for='wpc_from_time'><?php echo esc_html( $from_field_label); ?>
                                            <?php if ( $required_from_field == 'on') : ?>
                                                <small class='wpc_required'>*</small>
                                            <?php endif; ?> 
                                        </label>
                                        <input type='text' name='wpc_from_time' placeholder='<?php echo esc_html__('Start time here', 'wpcafe-pro'); ?>' class='wpc-form-control' id='wpc_from_time' value='' <?php echo ( $required_from_field == 'on' ) ? 'required aria-required="true"' : '' ?>  >
                                        <span class="dashicons dashicons-clock"></span>

                                    </div>
                                <?php endif;?>
                            </div>
                            <div class='wpc-col-lg-6'>
                                <?php if( $show_to_field == 'on' ): ?>
                                    <div class='wpc-reservation-field time'>
                                        <label for='wpc_to_time'><?php echo esc_html( $to_field_label); ?>
                                            <?php if ( $required_to_field == 'on') : ?>
                                                <small class='wpc_required'>*</small>
                                            <?php endif; ?>
                                        </label>
                                        <input type='text' name='wpc_to_time' placeholder='<?php echo esc_html__('End time here', 'wpcafe-pro'); ?>' class='wpc-form-control' id='wpc_to_time' value='' <?php echo ( $required_to_field == 'on' ) ? 'required aria-required="true"' : '' ?> >
                                        <span class="dashicons dashicons-clock"></span>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                        

                        <!-- visual table -->
                        <input type='hidden' name='wpc_visual_selection' class='wpc_visual_selection' value='1' />
                        <input type='hidden' name='wpc_schedule_slug' class='wpc_schedule_slug' value='' />
                        <input type='hidden' name='wpc_booked_ids' class='wpc_booked_ids' value='' />
                        <input type='hidden' name='wpc_booked_table_ids' class='wpc_booked_table_ids' value='' />
                        <input type='hidden' name='wpc_obj_names' class='wpc_obj_names' value='' />
                        <input type='hidden' name='wpc_intersected_data' class='wpc_intersected_data' value='' />
                        <input type='hidden' name='wpc_mapping_data' class='wpc_mapping_data' value='' />
                        <div id="table-layout-map"></div>

                      

                        <?php 
                        // branch 
                        if ( isset($show_branches) && "yes" == $show_branches ) { 
                            ?>
                            <div class="wpc-row">
                                <div class="wpc-col-lg-12 wpc-align-self-center">
                                    <div class='wpc-reservation-field branch'>
                                        <label for='wpc-branch'><?php echo esc_html__('Which branch of our restaurant', 'wpcafe-pro'); echo ( $require_branch == "required" ) ? "<small class='wpc_required'>*</small>" : "" ?></label>
                                        <select name='wpc_branch' id='wpc-branch' class='wpc-form-control' <?php echo esc_attr($require_branch == "required" ? "required" : ""); ?>>
                                            <?php foreach( $wpc_location_arr as $key=>$branch ) {?>
                                                <option value="<?php echo esc_attr( $key ); ?>" <?php echo count($wpc_location_arr) <= 2? "selected='selected'" : "" ?> ><?php echo esc_html( $branch ); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- name email phone -->
                        <div class="wpc-row">
                            <div class='wpc-col-md-4'>
                                <div class='wpc-reservation-field name'>
                                    <label for='wpc-name'><?php echo esc_html__('Your Name', 'wpcafe-pro'); ?><small class='wpc_required'>*</small></label>
                                    <input type='text' name='wpc_name' placeholder='<?php echo esc_html__('Name here', 'wpcafe-pro'); ?>' id='wpc-name' class='wpc-form-control' value='' required aria-required='true'>
                                    <div class="wpc-name wpc_danger_text"></div>
                                </div>
                            </div>
                            <div class='wpc-col-md-4'>
                                <div class='wpc-reservation-field email'>
                                    <label for='wpc-email'><?php echo esc_html__('Your Email', 'wpcafe-pro'); ?><small class='wpc_required'>*</small></label>
                                    <input type='email' name='wpc_email' placeholder='<?php echo esc_html__('Email here', 'wpcafe-pro'); ?>' class='wpc-form-control' id='wpc-email' value='' required aria-required='true'>
                                    <div class="wpc-email wpc_danger_text"></div>
                                </div>
                            </div>
                            <div class='wpc-col-md-4'>
                                <div class='wpc-reservation-field phone'>
                                    <label for='wpc-phone'><?php echo esc_html__('How can we contact you?', 'wpcafe-pro');
                                        echo ( isset($phone_required) && $phone_required == "required" ) ? "<small class='wpc_required'>*</small>" : "" ?>
                                    </label>
                                    <input type='tel' placeholder='<?php echo esc_html__('Phone Number here', 'wpcafe-pro'); ?>' <?php echo ( isset($phone_required) && $phone_required == "required" ) ? esc_attr("required") : ""; ?> name='wpc_phone' class='wpc-form-control' id='wpc-phone' value=''>
                                    <div class="wpc-phone wpc_danger_text"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class='wpc-select party wpc-reservation-field'>
                            <input type='number' min="<?php echo esc_attr( $wpc_min_guest_no ); ?>" max="<?php echo esc_attr( $wpc_max_guest_no ); ?>" name='wpc_guest_count' id='wpc-party' class='wpc_guest_count' value='0' required readonly style="display: none;" />
                            <div class="wpc-party wpc_danger_text"></div>
                        </div>
                        
                        <div class='wpc-reservation-fieldarea message wpc-reservation-field'>
                            <label for='wpc-message'><?php echo esc_html__('Additional Information', 'wpcafe-pro'); ?></label>
                            <textarea name='wpc_message' placeholder='<?php echo esc_html__('Enter Your Message here', 'wpcafe-pro'); ?>' id='wpc-message' class='wpc-form-control'></textarea>
                        </div>
                        <?php
                        // render extra field
                        if( !empty( $result_data['reservation_extra_field']) && file_exists( $result_data['reservation_extra_field'] )) {
                            include $result_data['reservation_extra_field'];
                        }
                        ?>
                        <input type='hidden' value='reservation_form_first_step' class='reservation_form_first_step' />
                        <button type='submit' class='reservation_form_submit wpc-btn'><?php echo esc_html( $first_booking_button ); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </form>


</div>

