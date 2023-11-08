<?php

use WpCafe\Utils\Wpc_Utilities;
$settings                  = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();

$args = array('confirmed'=>esc_html__('Total Confirmed', 'wpcafe-pro'), 'pending'=>esc_html__('Total Pending','wpcafe-pro') );

if (! isset( $settings['rest_reservation_off']) ) {
    $rest_reservation_off = 'confirmed';
}
else{
    $rest_reservation_off = $settings['rest_reservation_off'] =='' ? 'confirmed': $settings['rest_reservation_off'];
}

$markup_fields_rest = [
    'rest_reservation_off' => [
        'item' => [
            'label'    => esc_html__( 'Reservation Close State', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Reservation request can be closed either maximum confirmed booking or maximum pending booking request', 'wpcafe-pro' ),
            'type'     => 'select_single',
            'options'  => $args,
            'attr'     => [
                'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
            ],
        ],
        'data' => [ 'rest_reservation_off' => $rest_reservation_off ],
    ],
];

return $markup_fields_rest; ?>