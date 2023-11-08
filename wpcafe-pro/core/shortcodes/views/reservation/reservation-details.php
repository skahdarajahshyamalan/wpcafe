<?php
if (!empty($settings['show_branches']) && ( "yes" == $settings['show_branches'] || "on" == $settings['show_branches'] ) ) { ?>
<div class='wpc-reservation-field branch'>
    <label for='wpc-branch'>
        <?php echo esc_html__('Which branch of our restaurant?', 'wpcafe-pro'); echo ( $require_branch == "required" ) ? "<small class='wpc_required'>*</small>" : "" ?>
    </label>
    <select name='wpc_branch' id='wpc-branch' class='wpc-form-control' <?php echo esc_attr($require_branch == "required" ? "required" : ""); ?>>
        <?php foreach( $wpc_location_arr as $key=>$branch ) {?>
            <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $branch ); ?></option>
        <?php } ?>
    </select>
</div>
<?php } ?>
<!-- date -->
<div class='wpc-reservation-field'>
    <label for='wpc_booking_date'><?php echo esc_html__('Select Your Reservation Date', 'wpcafe-pro'); ?><small class='wpc_required'>*</small></label>
    <input type='text' placeholder='<?php echo esc_html__('Booking date here', 'wpcafe-pro'); ?>' name='wpc_booking_date' class='wpc-form-control wpc-validate' id='wpc_booking_date' value='' required aria-required='true' />
    <i class="wpcafe-icon1 reserv-date-icon"></i>
    <span class="wpc-validate-msg"></span>
</div>
<!-- time -->
<?php if( empty($booking_style_name) || 'cafe_pro_reserve_style_2' !== $booking_style_name ) :?>
<span class="wpc-validate-msg1"></span>
<?php endif; ?>

<div class="wpc-reservation-time-wrap">
    <div class='wpc-row'>
        <div class='<?php esc_attr_e( $from_to_column ) ;?>'>
            <?php if( $show_form_field == 'on'): ?>
                <div class='wpc-reservation-field time'>
                    <label for='wpc_from_time'><?php echo esc_html( $from_field_label ); ?>
                        <?php if ( $required_from_field == 'on') : ?>
                            <small class='wpc_required'>*</small>
                        <?php endif; ?> 
                    </label>
                    <input type='text' name='wpc_from_time' placeholder='<?php echo esc_html__('Start time here', 'wpcafe-pro'); ?>' class='wpc-form-control wpc-validate' id='wpc_from_time' value='' <?php echo ( $required_from_field == 'on' ) ? 'required aria-required="true"' : '' ?>  >
                    <span class="dashicons dashicons-clock"></span>

                </div>
            <?php endif;?>
        </div>
        <?php if( $show_to_field == 'on' ): ?>
            <div class='<?php esc_attr_e( $from_to_column ) ;?>'>
                <div class='wpc-reservation-field time'>
                    <label for='wpc_to_time'><?php echo esc_html( $to_field_label ); ?>
                        <?php if ( $required_to_field == 'on') : ?>
                            <small class='wpc_required'>*</small>
                        <?php endif; ?>
                    </label>
                    <input type='text' name='wpc_to_time' placeholder='<?php echo esc_html__('End time here', 'wpcafe-pro'); ?>' class='wpc-form-control wpc-validate' id='wpc_to_time' value='' <?php echo ( $required_to_field == 'on' ) ? 'required aria-required="true"' : '' ?> >
                    <span class="dashicons dashicons-clock"></span>
                </div>
            </div>
        <?php endif;?>
    </div>
</div>
<!-- Quantity -->
<div class='wpc-select party wpc-reservation-field'>
    <label for='wpc-total-guest'><?php echo esc_html__('How Many People are coming? ', 'wpcafe-pro'); ?><small class='wpc_required'>*</small></label>
    <select name='wpc_guest_count' id='wpc-party' class='wpc-form-control' required aria-required='true'>
        <option value=""><?php echo esc_html__('Select no. of guests', 'wpcafe-pro'); ?></option>
        <?php for ($i = $wpc_min_guest_no; $i <= $wpc_max_guest_no; $i++) {
            $selected = ($wpc_default_gest_no == $i) ? "selected" : ""; ?>
            <option value='<?php echo esc_attr( $i ); ?>' <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $i ); ?></option>
        <?php } ?>
    </select>
</div>