
<?php
$variation_layout   = isset($settings['wpc_pro_woocommerce_variation_layout']) ? 'checked' : '';
$override_css       = isset($settings['wpc_pro_woocommerce_override_css']) ? 'checked' : '';

$markup_fields_style = [      

    'wpc_customization_icon' => [
        'item' => [
            'label'    => esc_html__( 'Menu PopUp Icon', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Icon class for product addons, variable product. Any icon library which is available in your site will work. Example:  font-awesome, dash-icon etc.', 'wpcafe-pro' ),
            'type'     => 'text',
            'place_holder' => esc_html__('icon here', 'wpcafe-pro'),
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
            'span'     => ['class'=>'wpc-admin-settings-message', 'html'=>'For instance : fa fa-cog']
        ],
        'data' => [ 'wpc_customization_icon' => $customization_icon ],
    ],
    
    'wpc_pro_woocommerce_variation_layout' => [
        'item' => [
            'options'  =>['on'=>'on'],
            'label'    => esc_html__( 'Product Variation List Layout Override?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'You can override WooComerce product variation layout in popup and single page', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'wpc_pro_woocommerce_variation_layout' => $variation_layout ],
    ],
    'wpc_pro_woocommerce_override_css' => [
        'item' => [
            'options'  =>['on'=>'on'],
            'label'    => esc_html__( 'Override WooCommerce Default Layout?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'You can override WooComerce default layout (Product Details, Cart, Checkout and Thank you page)', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item mb-0', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'wpc_pro_woocommerce_override_css' => $override_css ],
    ],
];

?>

<?php
return $markup_fields_style;
        