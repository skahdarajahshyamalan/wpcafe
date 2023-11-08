<?php
use WpCafe\Utils\Wpc_Utilities;
$allow_location_email = ( isset($settings['wpc_allow_location_email'] ) && $settings['wpc_allow_location_email'] == 'on' )  ? 'on' : 'off';

$markup_fields_branch = [
    'wpc_allow_location_email' => [
        'item' => [
            'options'  =>['off'=>'off', 'on'=>'on'],
            'label'    => esc_html__( 'Send to Branch Email?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'It will send and receive emails by location\'s email address.', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'wpc_allow_location_email' => $allow_location_email ],
    ],
];

return $markup_fields_branch; ?>