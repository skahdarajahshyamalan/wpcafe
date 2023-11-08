<?php

use WpCafe_Pro\Utils\Utilities;

$wpc_pro_menu_settings             = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option(); 
$wpc_pro_allow_order_for           = isset($wpc_pro_menu_settings['wpc_pro_allow_order_for']) ? $wpc_pro_menu_settings['wpc_pro_allow_order_for'] : '';
$order_prepare_days                = isset($wpc_pro_menu_settings['order_prepare_days']) ? $wpc_pro_menu_settings['order_prepare_days'] : 0 ;
$google_api_key                    = !empty($wpc_pro_menu_settings['google_api_key']) ? $wpc_pro_menu_settings['google_api_key'] : "" ;
$min_order_amount                  = !empty($wpc_pro_menu_settings['min_order_amount']) ? $wpc_pro_menu_settings['min_order_amount'] : "" ;
$address_validation                = !empty($settings['address_validation']) ? "checked" : "";

$customization_icon = isset($settings['wpc_customization_icon'] ) ? $settings['wpc_customization_icon'] : '';

$args = array( ''=>esc_html__('None','wpcafe-pro') , 'Both'=>esc_html__('Both','wpcafe-pro') , 'Delivery'=>esc_html__('Delivery', 'wpcafe-pro'), 'Pickup'=>esc_html__('Pickup','wpcafe-pro'));

$markup_fields_menu = [
    'wpc_pro_allow_order_for' => [
        'item' => [
            'label'    => esc_html__( 'Order Type', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'You can set "Delivery", "Pickup", "Both" or "None" for users.', 'wpcafe-pro' ),
            'type'     => 'select_single',
            'options'  => $args,
            'attr'     => [
                'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
            ],
        ],
        'data' => [ 'wpc_pro_allow_order_for' => $wpc_pro_allow_order_for ],
    ],

    'order_prepare_days' => [
        'item' => [
            'label'    => esc_html__( 'Order Preparation Days', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'If it takes more than a day to prepare your order, then mention how many days it took to prepare your order for delivery (Applies to all orders)', 'wpcafe-pro' ),
            'type'     => 'number',
            'attr'     => ['class' => 'wpc-label-item wpc-pro-interval-enabled', 'input_class'=> 'wpc-settings-input'],
        ],
        'data' => [ 'order_prepare_days' => intval($order_prepare_days) ],
    ],
    
    'google_api_key' => [
        'item' => [
            'label'    => esc_html__( 'Google Map Api Key', 'wpcafe-pro' ),
            'desc'     => Utilities::kses( 'Check <a href="' .esc_url( 'https://console.cloud.google.com/apis/enableflow?apiid=maps_backend&keyType=CLIENT_SIDE&reusekey=true' ).'" target="_blank" >'. esc_html__('Documentation', 'wpcafe-pro').'</a> to get API key' , 'wpcafe-pro' ),
            'type'     => 'password',
            'place_holder' => esc_html__('Api key here', 'wpcafe-pro'),
            'attr'     => ['class' => 'wpc-label-item wpc-label-google-api', 'input_class'=> 'wpc-settings-input'],
        ],
        'data' => [ 'google_api_key' => $google_api_key ],
    ],

    'address_validation' => [
        'item' => [
            'options'  =>['on'=>'on'],
            'label'    => esc_html__( 'Auto Complete Order Address In Checkout', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Auto complete address from Google map. You need Google map service to use the feature', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'address_validation' => $address_validation ],
    ],

    'min_order_amount' => [
        'item' => [
            'label'    => esc_html__( 'Minimum Order Amount', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Minimum order amount to place order', 'wpcafe-pro' ),
            'type'     => 'number',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'min_order_amount' => $min_order_amount ],
    ],
    
];

return $markup_fields_menu; ?>
