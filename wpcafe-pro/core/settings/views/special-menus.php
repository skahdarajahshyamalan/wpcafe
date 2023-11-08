<?php
$enable_special_menu    = isset($settings['enable_special_menu']) ? 'checked' : '';
$popup_duration         = isset($settings['menu_popup_duration'] ) ? $settings['menu_popup_duration'] : '';
$special_menu_button    = isset($settings['special_menu_button'] ) ? $settings['special_menu_button'] : esc_html__('Order Now', 'wpcafe-pro');
$button_link            = isset($settings['special_menu_button_link'] ) ? $settings['special_menu_button_link'] : '';
?>
<!-- include menu for offer -->
<div class="tip-option-container">
    <?php
    $markup_fields = [
        'enable_special_menu' => [
            'item' => [
                'options'  =>['on'=>'on'],
                'label'    => esc_html__( 'Enable Special Menus?', 'wpcafe-pro' ),
                'desc'     => esc_html__( 'Do you want to enable special menus for your restaurant?', 'wpcafe-pro' ),
                'type'     => 'checkbox',
                'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
            ],
            'data' => [ 'enable_special_menu' => $enable_special_menu ],
        ],
    ];
    foreach ( $markup_fields as $key => $info ) {
        $this->get_field_markup( $info['item'], $key, $info['data'] );
    }
    ?>
                              
    <div class="wpc-label-item special-menu-block" style="<?php echo empty( $enable_special_menu ) ? 'display: none;' : '' ?>">
        <?php
        $markup_fields_two = [
            'menu_popup_duration' => [
                'item' => [
                    'label'    => esc_html__( 'PopUp Duration', 'wpcafe-pro' ),
                    'desc'     => esc_html__( 'Special menu popup duration', 'wpcafe-pro' ),
                    'type'     => 'text',
                    'place_holder' => '',
                    'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                ],
                'data' => [ 'menu_popup_duration' => $popup_duration ],
            ],
            'menu_popup_duration' => [
                'item' => [
                    'label'    => esc_html__( 'PopUp Duration', 'wpcafe-pro' ),
                    'desc'     => esc_html__( 'Special menu popup duration', 'wpcafe-pro' ),
                    'type'     => 'text',
                    'place_holder' => '',
                    'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                ],
                'data' => [ 'menu_popup_duration' => $popup_duration ],
            ],
        ];
        foreach ( $markup_fields_two as $key => $info ) {
            $this->get_field_markup( $info['item'], $key, $info['data'] );
        }
        ?>    
        <div class="wpc-label-item">
            <div class="wpc-label">
                <label for="special_menu_heading"><?php esc_html_e('Special menu title', 'wpcafe-pro'); ?></label>
                <div class="wpc-desc"> <?php esc_html_e('Special menu heading popup', 'wpcafe-pro'); ?></div>
            </div>
            <div class="wpc-meta">
                <label class="wpc-label-top"> <?php esc_html_e('Special Day Title', 'wpcafe-pro'); ?></label>
                <input type="text" maxlength="30" class="wpc-settings-input" name="special_menu_title1" id="special_menu_heading1"
                    value="<?php echo esc_attr( isset($settings['special_menu_title1'] ) ? $settings['special_menu_title1'] : esc_attr_e('Holiday Special', 'wpcafe-pro')); ?>"
                />
            </div>
            <div class="wpc-meta">
                <label class="wpc-label-top"> <?php esc_html_e('Discount/Offer', 'wpcafe-pro'); ?></label>
                <input type="text" maxlength="15" class="wpc-settings-input" name="special_menu_title2" id="special_menu_heading2"
                    value="<?php echo esc_attr( isset($settings['special_menu_title2'] ) ? $settings['special_menu_title2'] : esc_attr_e('30% Off', 'wpcafe-pro')); ?>"
                     />
            </div>
            <div class="wpc-meta">
                <label class="wpc-label-top"> <?php esc_html_e('Offer Duration', 'wpcafe-pro'); ?></label>
                <input type="text" class="wpc-settings-input" name="special_menu_title3" id="special_menu_heading3"
                    value="<?php echo esc_attr( isset($settings['special_menu_title3'] ) ? $settings['special_menu_title3'] : esc_attr_e('Only For Today', 'wpcafe-pro')); ?>"
                    />
            </div>
          
        </div>

        <?php
        if (class_exists('WooCommerce')) {
            $menu_id  = isset($settings['special_menus']) ? $settings['special_menus'] : [];
            $args = array(
                'post_type'   => 'product',
                'hide_empty'  => 0,
                'limit'       => -1,
            );
            $products = wc_get_products($args);

            $options_menu = [];

            if (is_array($products)) {
                foreach ($products as $product) {
                    $options_menu[$product->get_id()] = $product->get_name();
                }
            }

            $markup_fields_three = [

                'special_menus' => [
                    'item' => [
                        'label'    => esc_html__( 'Include Menus', 'wpcafe-pro' ),
                        'desc'     => esc_html__( 'Include following menu items for special menu offer', 'wpcafe-pro' ),
                        'type'     => 'select2',
                        'options'  => $options_menu,
                        'attr'     => [
                            'class' => 'wpc_pro_discount_heading wpc-label-item', 'input_class'=> 'wpc_pro_multi_product wpc-settings-input'
                        ],
                    ],
                    'data' => [ 'special_menus' => $menu_id ],
                ],
            ];
            
            foreach ( $markup_fields_three as $key => $info ) {
                $this->get_field_markup( $info['item'], $key, $info['data'] );
            }
        }else{   
            printf( ( '<span class="wpc-warning">%1$s</span>' ), esc_html__('Activate WooCommerce to get the list of the menus.', 'wpcafe-pro') );
        }

        $markup_fields_four = [
            'special_menu_button' => [
                'item' => [
                    'label'    => esc_html__( 'Button Text', 'wpcafe-pro' ),
                    'desc'     => esc_html__( 'Button text for special menu popup', 'wpcafe-pro' ),
                    'type'     => 'text',
                    'place_holder' => esc_html__( 'Order Now', 'wpcafe-pro' ),
                    'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                ],
                'data' => [ 'special_menu_button' => $special_menu_button ],
            ],
            'special_menu_button_link' => [
                'item' => [
                    'label'    => esc_html__( 'Button Link', 'wpcafe-pro' ),
                    'desc'     => esc_html__( 'Button link to go the page', 'wpcafe-pro' ),
                    'type'     => 'text',
                    'place_holder' => '',
                    'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
                ],
                'data' => [ 'special_menu_button_link' => $button_link ],
            ],
        ];
        foreach ( $markup_fields_four as $key => $info ) {
            $this->get_field_markup( $info['item'], $key, $info['data'] );
        }
        ?>
    </div>

</div>
