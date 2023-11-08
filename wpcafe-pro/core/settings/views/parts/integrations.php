<?php

    $zapier_web_hooks   =  !empty($settings['zapier_web_hooks'] ) ?  $settings['zapier_web_hooks'] : '';
    $pabbly_web_hooks   =  !empty($settings['pabbly_web_hooks'] ) ?  $settings['pabbly_web_hooks'] : '';
    $wpc_allow_zapier   =  !empty($settings['wpc_allow_zapier'] )   ? 'on' : 'off';
    $wpc_allow_pabbly   =  !empty($settings['wpc_allow_pabbly'] )   ? 'on' : 'off';
    $hide_class_zapier  = $wpc_allow_zapier == "off" ? "hide_field" : "";
    $hide_class_pabbly  = $wpc_allow_pabbly == "off" ? "hide_field" : "";

    $markup_fields = [
        'wpc_allow_zapier' => [
            'item' => [
                'options'  =>['on'=>'on'],
                'label'    => esc_html__( 'Enable Zapier?', 'wpcafe-pro' ),
                'desc'     => esc_html__( 'Integrate zapier with reservation form', 'wpcafe-pro' ),
                'type'     => 'checkbox',
                'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
            ],
            'data' => [ 'wpc_allow_zapier' => $wpc_allow_zapier ],
        ],
        'zapier_web_hooks' => [
            'item' => [
                'label'    => esc_html__( 'Web Hooks', 'wpcafe-pro' ),
                'desc'     => esc_html__( 'Enter here zapier web hook', 'wpcafe-pro' ),
                'type'     => 'text',
                'place_holder' => '',
                'attr'     => ['class' => 'wpc-label-item zap_web_hook '.$hide_class_zapier, 'input_class'=> 'wpc-settings-input'],
            ],
            'data' => [ 'zapier_web_hooks' => $zapier_web_hooks ],            
        ],
        'wpc_allow_pabbly' => [
            'item' => [
                'options'  =>['on'=>'on'],
                'label'    => esc_html__( 'Enable Pabbly?', 'wpcafe-pro' ),
                'desc'     => esc_html__( 'Integrate pabbly with reservation form', 'wpcafe-pro' ),
                'type'     => 'checkbox',
                'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
            ],
            'data' => [ 'wpc_allow_pabbly' => $wpc_allow_pabbly ],
        ],
        'pabbly_web_hooks' => [
            'item' => [
                'label'    => esc_html__( 'Web Hooks', 'wpcafe-pro' ),
                'desc'     => esc_html__( 'Enter here pabbly web hook.', 'wpcafe-pro' ),
                'type'     => 'text',
                'place_holder' => '',
                'attr'     => ['class' => 'wpc-label-item pabbly_web_hook '.$hide_class_pabbly, 'input_class'=> 'wpc-settings-input'],
            ],
            'data' => [ 'pabbly_web_hooks' => $pabbly_web_hooks ],
        ],
    ];

    foreach ( $markup_fields as $key => $info ) {
        $this->get_field_markup( $info['item'], $key, $info['data'] );
    }
    
?>
