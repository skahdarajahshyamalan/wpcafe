
<div class='wpc-reservation-field name'>
    <label for='wpc-name'><?php echo esc_html__('Enter Your Full Name', 'wpcafe-pro'); ?><small class='wpc_required'>*</small></label>
    <input type='text' name='wpc_name' placeholder='<?php echo esc_html__('Name here', 'wpcafe-pro'); ?>' id='wpc-name' class='wpc-form-control' value='' required aria-required='true'>
    <div class="wpc-name wpc_danger_text"></div>
</div>

<!-- Contact info -->
<div class="wpc-row">
    <div class='wpc-col-md-6'>
    <div class='wpc-reservation-field phone'>
            <label for='wpc-phone'><?php echo esc_html__('How can we contact you?', 'wpcafe-pro');
                echo ( $phone_required == "required" ) ? "<small class='wpc_required'>*</small>" : "" ?>
            </label>
            <input type='tel' placeholder='<?php echo esc_html__('Phone Number here', 'wpcafe-pro'); ?>' <?php echo esc_attr($phone_required == "required" ? "required" : ""); ?> name='wpc_phone' class='wpc-form-control' id='wpc-phone' value=''>
            <div class="wpc-phone wpc_danger_text"></div>
        </div>
    </div>
    <div class='wpc-col-md-6'>
        <div class='wpc-reservation-field email'>
            <label for='wpc-email'><?php echo esc_html__('Enter Your Email', 'wpcafe-pro'); ?><small class='wpc_required'>*</small></label>
            <input type='email' name='wpc_email' placeholder='<?php echo esc_html__('Email here', 'wpcafe-pro'); ?>' class='wpc-form-control' id='wpc-email' value='' required aria-required='true'>
            <div class="wpc-email wpc_danger_text"></div>
        </div>
    </div>
</div>
<!-- message -->
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