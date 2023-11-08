<?php

use WpCafe\Utils\Wpc_Utilities;

$wpc_pro_menu_settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option(); 
$wpc_pro_order_notify = isset($wpc_pro_menu_settings['wpc_pro_order_notify'])  ? 'checked' : '';
$wpc_pro_sound_notify = isset($wpc_pro_menu_settings['wpc_pro_sound_notify'])  ? 'checked' : '';
$sound_media_file = isset($wpc_pro_menu_settings['sound_media_file']) ? $wpc_pro_menu_settings['sound_media_file'] : '';
$wpc_pro_sound_repeat = isset($wpc_pro_menu_settings['wpc_pro_sound_repeat'])  ? 'checked' : '';
$repeat_interval_time = isset($wpc_pro_menu_settings['repeat_interval_time'])  ? intval($wpc_pro_menu_settings['repeat_interval_time']) : '';

$style = empty($wpc_pro_sound_repeat) ? 'display: none;' : '';
$style_repeat = empty($wpc_pro_sound_notify) ? 'display: none;' : '';
$sound_style = empty( $wpc_pro_sound_notify ) ? 'display: none;' : '';

$markup_fields_order = [

    'wpc_pro_order_notify' => [
        'item' => [
            'options'  =>['on'=>'on'],
            'label'    => esc_html__( 'Enable Order Notification?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Do you want to enable order notification in admin?', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'wpc_pro_order_notify' => $wpc_pro_order_notify ],
    ],

    'wpc_pro_sound_notify' => [
        'item' => [
            'options'  =>['on'=>'on'],
            'label'    => esc_html__( 'Enable Sound Notification?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Do you want to enable a sound notification for new orders?', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'wpc_pro_sound_notify' => $wpc_pro_sound_notify ],
    ],

    'sound_media_file' => [
        'item' => [
            'label'    => esc_html__( 'Upload Custom Sound File', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Upload a custom sound notification audio file', 'wpcafe-pro' ),
            'type'     => 'media',
            'input_name' => 'audio',
            'attr'     => ['class' => 'wpc-label-item wpc-pro-sound-enabled-block', 'input_class' => 'custom_media_url', 'style'=> $sound_style],
        ],
        'data' => [ 'sound_media_file' => $sound_media_file ],
    ],

    'wpc_pro_sound_repeat' => [
        'item' => [
            'options'  =>['on'=>'on'],
            'label'    => esc_html__( 'Enable Repeated Sound?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Do you want to enable repeated sound notification until open the order?', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'wpc_pro_sound_repeat' => $wpc_pro_sound_repeat ],
    ],
    
    'repeat_interval_time' => [
        'item' => [
            'label'    => esc_html__( 'Repeat Interval', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Interval in Minutes', 'wpcafe-pro' ),
            'type'     => 'number',
            'attr'     => ['class' => 'wpc-label-item wpc-pro-interval-enabled', 'input_class'=> 'wpc-settings-input'],
            'style'     => ['attr'=> $style]
        ],
        'data' => [ 'repeat_interval_time' => $repeat_interval_time ],
    ],
];

return $markup_fields_order; 
?>