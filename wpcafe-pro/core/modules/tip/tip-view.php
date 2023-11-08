<?php
    $settings   = $this->settings;
    $wc_session = WC()->session;

    $tip_types     = [];
    $allow_tip_for = isset( $settings['wpc_pro_tip_allow_for'] ) ? $settings['wpc_pro_tip_allow_for'] : 'tip_both';

    $tip_selected_type = 'tip_fixed';
    if ( $allow_tip_for == 'tip_fixed' ) {
        $tip_types['tip_fixed']      = esc_html__( 'Fixed', 'wpcafe-pro' );
    } elseif ( $allow_tip_for == 'tip_percentage') {
        $tip_types['tip_percentage'] = esc_html__( 'Percentage(%)', 'wpcafe-pro' );
        $tip_selected_type = 'tip_percentage';
    } else {
        $tip_types['tip_fixed']      = esc_html__( 'Fixed', 'wpcafe-pro' );
        $tip_types['tip_percentage'] = esc_html__( 'Percentage(%)', 'wpcafe-pro' );
    }

    $tip_percentage_data = isset( $settings['wpc_pro_tip_percentage_data'] ) ? $settings['wpc_pro_tip_percentage_data'] : [];

    $tip_fixed_amount = $tip_percentage_amount = $tip_added = 0;
    $tip_data = $wc_session->get('wpc_pro_tip');
    if( !empty( $tip_data ) ){
        $tip_added             = $tip_data['tip_added'];
        $tip_selected_type     = $tip_data['tip_selected_type'];
        $tip_fixed_amount      = $tip_data['tip_fixed_amount'];
        $tip_percentage_amount = $tip_data['tip_percentage_amount'];
    }    
?>

<div class="wpc_pro_order_tip_block" id="wpc_pro_order_tip_block">

    <div class="wpc_pro_order_tip_title">
        <h3><?php echo esc_html__( 'Would you like to give a tip?', 'wpcafe-pro' ); ?></h3>
    </div>
    <div class="wpc_pro_order_tip_wrapper" id="wpc_pro_order_tip_wrapper">
        <div class="wpc_pro_tip_type_wrap">
            <select name="wpc_pro_tip_type" class="wpc_pro_tip_type wpc-form-control input-text">
                <?php
                foreach ( $tip_types as $type_key => $type_name ) { ?>
                    <option value='<?php echo esc_attr( $type_key ); ?>' <?php selected( $type_key, $tip_selected_type, true ); ?>><?php echo esc_html($type_name); ?></option>
                <?php }
                ?>
            </select>
        </div>

        <?php if ( $allow_tip_for == 'tip_fixed' || $allow_tip_for == 'tip_both' ) { ?>
        <div class="wpc_pro_tip_type_fixed_wrap" style="<?php echo ( $tip_selected_type != 'tip_fixed' ) ? 'display: none;' : ''; ?>">
            <input type="number" name="wpc_pro_fixed_tip_amount" min="0" value="<?php echo esc_attr( $tip_fixed_amount ) ?>" class="wpc_pro_fixed_tip_amount wpc-form-control input-text" />
        </div>
        <?php } ?>

        <?php if ( $allow_tip_for == 'tip_percentage' || $allow_tip_for == 'tip_both' ) { ?>
        <div class="wpc_pro_tip_type_percentage_wrap" style="<?php echo ( $tip_selected_type != 'tip_percentage' ) ? 'display: none;' : ''; ?>">
            <select name="wpc_pro_percentage_tip_amount" class="wpc_pro_percentage_tip_amount wpc-form-control input-text" id="wpc_pro_percentage_tip_amount">
                <option value='0'><?php echo esc_html( 'Please select', 'wpcafe-pro' ); ?></option>
                <?php
                foreach ( $tip_percentage_data as $percentage_key => $tip_percentage ) {
                    if ( ! empty ( $tip_percentage ) ) {
                ?>
                    <option value='<?php echo esc_attr( $tip_percentage ); ?>' <?php selected( $tip_percentage, $tip_percentage_amount, true ); ?>><?php echo esc_html( $tip_percentage ); ?>%</option>
                <?php 
                    }
                }
                ?>
            </select>
        </div>
        <?php } ?>

     

        <div class="wpc_pro_tip_button_wrap">
            <input type="button" name="wpc_pro_add_tip" class="wpc-btn wpc_pro_add_tip <?php echo ( $tip_added == 0 ) ? "wpc_pro_add_tip_disabled" : ''; ?>" <?php echo ( $tip_added == 0 ) ? "disabled" : ''; ?> value="<?php echo esc_html__( 'Add Tip', 'wpcafe-pro' ); ?>" />
            <input type="button" name="wpc_pro_remove_tip" class="wpc_pro_remove_tip wpc-btn wpc-btn-border" style="<?php echo ! $tip_data ? 'display: none;' : ''; ?>" value="<?php echo esc_html__( 'Remove Tip', 'wpcafe-pro' ); ?>" />
        </div>
        <div class="wpc_pro_tip_msg_wrap">
            <span class="wpc_pro_tip_msg"></span>
        </div>
    </div>
</div>