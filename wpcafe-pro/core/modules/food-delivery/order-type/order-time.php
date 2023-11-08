<?php

use WpCafe\Utils\Wpc_Utilities;
use WpCafe_Pro\Utils\Utilities as Pro_Utilities;

$checkout = WC()->checkout;

?>
<div class="wpc_pro_order_time_settings wpc_pro_order_time">
<?php

$wpc_pro_hidden_field = '';
if ($wpc_pro_settings['wpc_pro_allow_order_for'] == 'Delivery') {
    $wpc_pro_hidden_field .= '<input type="hidden" name="wpc_pro_order_time" value="Delivery"/>';
    echo Wpc_Utilities::wpc_kses($wpc_pro_hidden_field);

} elseif ($wpc_pro_settings['wpc_pro_allow_order_for'] == 'Pickup') {
    $wpc_pro_hidden_field .= '<input type="hidden" name="wpc_pro_order_time" value="Pickup"/>';
    echo Wpc_Utilities::wpc_kses($wpc_pro_hidden_field);

} elseif ($wpc_pro_settings['wpc_pro_allow_order_for'] == 'Both') {
    $wpc_pro_options = ['Pickup' => esc_html__('Pickup','wpcafe-pro'), 'Delivery' => esc_html__('Delivery','wpcafe-pro')];
    $checked = $checkout->get_value( 'wpc_pro_order_time' ) ? $checkout->get_value( 'wpc_pro_order_time' ) : "Delivery";
    // radio
    $wpc_pro_order_time_array = [
        'type'        => 'radio',
        'class'       => ['wpc_pro_order_time form-row-wide'],
        'options'     => $wpc_pro_options,
        'required'    => true,
    ];
    
    if( ( !empty($wpc_pro_settings['wpc_pro_allow_delivery_date']) && $wpc_pro_settings['wpc_pro_allow_delivery_date'] == 'on' )
     && ( !empty($wpc_pro_settings['wpc_pro_allow_pickup_date']) && $wpc_pro_settings['wpc_pro_allow_pickup_date'] == 'on' ) ){
        woocommerce_form_field('wpc_pro_order_time', $wpc_pro_order_time_array, $checked );
    }   
}
?>
    <div class="wpc_error_message"
    data-pickup_date="<?php echo esc_html__("Please select pickup date","wpcafe-pro")?>"
    data-delivery_date="<?php echo esc_html__("Please select delivery date","wpcafe-pro")?>"
    ></div>

    <?php
    if($wpc_pro_settings['wpc_pro_allow_delivery_date'] == 'off'){
    $wpc_time_pickup_id =  Pro_Utilities::data_validation_check($wpc_pro_settings['wpc_pro_allow_order_for'])
        && $wpc_pro_settings['wpc_pro_allow_order_for'] == 'Both' ? 'pickup' : '';
    } else {
        $wpc_time_pickup_id =  Pro_Utilities::data_validation_check($wpc_pro_settings['wpc_pro_allow_order_for'])
        && $wpc_pro_settings['wpc_pro_allow_order_for'] == 'Both' ? 'both_pickup' : '';
    }
    $wpc_time_delivery_id =  Pro_Utilities::data_validation_check($wpc_pro_settings['wpc_pro_allow_order_for'])
        && $wpc_pro_settings['wpc_pro_allow_order_for'] == 'Both' ? 'both_delivery' : '';

    if($wpc_pro_settings['wpc_pro_allow_delivery_date'] == 'on'){
        if (
            Pro_Utilities::data_validation_check($wpc_pro_settings['wpc_pro_allow_order_for'])
            && ($wpc_pro_settings['wpc_pro_allow_order_for'] == 'Both'
                || $wpc_pro_settings['wpc_pro_allow_order_for'] == 'Delivery')
        ) {
        ?>
            <div class="wpc_pro_delivery" id="<?php echo esc_attr($wpc_time_delivery_id); ?>">
                <?php
                if (
                    isset($wpc_pro_settings['wpc_pro_allow_delivery_date']) &&
                    sanitize_text_field($wpc_pro_settings['wpc_pro_allow_delivery_date']) == 'on'
                ) {
                ?>    
                <div class="wpc_pro_date_section">
                    <?php
                    $div_class = isset($wpc_pro_settings['wpc_pro_allow_delivery_time']) ? 'form-row-first' : '';
                    woocommerce_form_field('wpc_pro_delivery_date', [
                        'label'         => esc_html__('Delivery date','wpcafe-pro'),
                        'type'          => 'text',
                        'class'         => ['wpc_pro_delivery_date ' . $div_class . ''],
                        'required'      => true,
                    ]);
                    ?>
                </div>
                <?php } ?>
                <?php
                if (
                    isset($wpc_pro_settings['wpc_pro_allow_delivery_time']) &&
                    sanitize_text_field($wpc_pro_settings['wpc_pro_allow_delivery_time']) == 'on'
                ) {
                ?>
                    <div class="wpc_pro_time_section">
                        <?php
                        woocommerce_form_field('wpc_pro_delivery_time', [
                            'label'        => esc_html__('Delivery time','wpcafe-pro'),
                            'type'        => 'text',
                            'class'       => ['wpc_pro_delivery_time form-row-last'],
                            'required'    => true,
                        ]);
                        ?>
                    </div>
                <?php
                }
                if (Pro_Utilities::data_validation_check($wpc_pro_settings['wpc_pro_delivery_message'])) {
                ?>
                    <div class="wpc_pro_delivary_message">
                        <?php
                        echo esc_html($wpc_pro_settings['wpc_pro_delivery_message']);
                        ?>
                    </div>
                <?php
                } ?>
            </div>
        <?php
        }
    }

    if($wpc_pro_settings['wpc_pro_allow_pickup_date'] == 'on'){
        if (
            Pro_Utilities::data_validation_check($wpc_pro_settings['wpc_pro_allow_order_for'])
            && ($wpc_pro_settings['wpc_pro_allow_order_for'] == 'Both'
                || $wpc_pro_settings['wpc_pro_allow_order_for'] == 'Pickup')
        ) {
        ?>
            <div class="wpc_pro_pickup" id="<?php echo esc_attr($wpc_time_pickup_id); ?>">
                <?php
                if (
                    isset($wpc_pro_settings['wpc_pro_allow_pickup_date']) &&
                    sanitize_text_field($wpc_pro_settings['wpc_pro_allow_pickup_date']) == 'on'
                ) {
                ?>    
                    <div class="wpc_pro_date_section">
                        <?php
                        $div_class = isset($wpc_pro_settings['wpc_pro_allow_pickup_time']) ? 'form-row-first' : '';

                        woocommerce_form_field(
                            'wpc_pro_pickup_date',
                            [
                                'label'        => esc_html__('Pickup date','wpcafe-pro'),
                                'type'        => 'text',
                                'class'       => ['wpc_pro_pickup_date ' . $div_class . ''],
                                'required'    => true,
                            ]
                        );
                        ?>
                    </div>
                <?php } ?>
                <?php
                if (
                    isset($wpc_pro_settings['wpc_pro_allow_pickup_time']) &&
                    sanitize_text_field($wpc_pro_settings['wpc_pro_allow_pickup_time']) == 'on'
                ) {
                ?>
                    <div class="wpc_pro_time_section">
                        <?php
                        woocommerce_form_field(
                            'wpc_pro_pickup_time',
                            [
                                'label'        => esc_html__('Pickup time','wpcafe-pro'),
                                'type'        => 'text',
                                'class'       => ['wpc_pro_pickup_time form-row-last'],
                                'required'    => true,
                            ]
                        );
                        ?>
                    </div>
                <?php
                }
                if (Pro_Utilities::data_validation_check($wpc_pro_settings['wpc_pro_pickup_message'])) {
                ?>
                    <div class="wpc_pro_pickup_message">
                        <?php
                        echo esc_html($wpc_pro_settings['wpc_pro_pickup_message']);
                        ?>
                    </div>
                <?php } ?>
            </div>
        <?php
        }
    } ?>
</div>

