<?php
use WpCafe\Utils\Wpc_Utilities;
$wpc_pro_menu_settings  = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option(); 
?>

<?php do_action('wpc_pro_before_discount_settings');?>

<div class="wpc-discount-settings-container" id="discount_message" 
data-percentage_exist="<?php echo esc_attr__("You have already select percentage discount","wpcafe-pro");?>"
data-standard_exist="<?php echo esc_attr__("You have already select standard discount","wpcafe-pro");?>"
>
    
    <div class='wpc-label-item percantage-block-item'>
        <?php
        $wpc_pro_order_amount_percent           = isset($wpc_pro_menu_settings['wpc_pro_discount_percentage']) ? intval($wpc_pro_menu_settings['wpc_pro_discount_percentage']) : '';
        $wpc_pro_order_standarad_off_amount     = isset($wpc_pro_menu_settings['wpc_pro_order_standarad_off_amount']) ? intval($wpc_pro_menu_settings['wpc_pro_order_standarad_off_amount']) : '';
        $wpc_pro_discount_standarad_off         = isset($wpc_pro_menu_settings['wpc_pro_discount_standarad_off']) ? intval($wpc_pro_menu_settings['wpc_pro_discount_standarad_off']) : '';
        $wpc_pro_discount_standarad_off_message = isset($wpc_pro_menu_settings['wpc_pro_discount_standarad_off_message']) ? esc_html($wpc_pro_menu_settings['wpc_pro_discount_standarad_off_message']) : '';
        $wpc_pro_include_cat                    = isset($wpc_pro_menu_settings['wpc_pro_include_cat']) ? $wpc_pro_menu_settings['wpc_pro_include_cat'] : [];
        $wpc_pro_addon_discount_to              = isset($wpc_pro_menu_settings['wpc_pro_addon_discount_to']) ? $wpc_pro_menu_settings['wpc_pro_addon_discount_to'] : '';

        $parcentage_active = '';
        $standard_active = '';
        if($wpc_pro_order_standarad_off_amount !== 0){
            $standard_active = 'wpc-active';
        } else{
            $parcentage_active = 'wpc-active';
        }

        ?>
        <div class="percantage-block">
            <div class='wpc_pro_discount_heading'>
                <h5 class='wpc_pb_two'><?php echo esc_html__('Percentage Discount', 'wpcafe-pro' ); ?></h5>
                <p class="wpc-desc"><?php echo esc_html__('Percentage discount applies on simple products from includes menus and includes categories', 'wpcafe-pro'); ?> </p>
            </div>
            <div class='wpc_pro_discount_main_block wpc-mb-30'>
                <div class='wpc_pro_discount_block'>
                    <span><?php echo esc_html__('Discount:', "wpcafe-pro" ) ?> </span>
                    <span><input type='text' name='wpc_pro_discount_percentage' class='wpc_pro_discount_percentage wpc-settings-input attr-form-control' value="<?php echo intval($wpc_pro_order_amount_percent); ?>" /></span>
                    <span><?php echo esc_html__('percent', 'wpcafe-pro'); ?></span>
                </div>
            </div>
            <!-- include discount menu -->
            <?php
                if (class_exists('Woocommerce')) {
                    ?>
                    <div class='wpc_pro_include_menu_wrapper mb-30'>
                        <?php
                        $menu_id  = isset($wpc_pro_menu_settings['wpc_pro_include_menu']) ? $wpc_pro_menu_settings['wpc_pro_include_menu'] : [];
                        $args = array(
                            'post_type'   => 'product',
                            'hide_empty'  => 0,
                        );
                        $products = wc_get_products($args);
                        $options_menu = [];

                        if (is_array($products)) {
                            foreach ($products as $product) {
                                if ($product->is_type('simple')) {
                                    $options_menu[$product->get_id()] = $product->get_name();
                                }
                            }
                        }

                        $markup_fields = [

                            'wpc_pro_include_menu' => [
                                'item' => [
                                    'label'    => esc_html__( 'Include Menus', 'wpcafe-pro' ),
                                    'desc'     => esc_html__( 'Include the following menu items while calculating discounts:', 'wpcafe-pro' ),
                                    'type'     => 'select2',
                                    'options'  => $options_menu,
                                    'attr'     => [
                                        'class' => 'wpc_pro_discount_heading', 'input_class'=> 'wpc_pro_multi_product wpc-settings-input'
                                    ],
                                ],
                                'data' => [ 'wpc_pro_include_menu' => $menu_id ],
                            ],
                        ];
                        
                        foreach ( $markup_fields as $key => $info ) {
                            $this->get_field_markup( $info['item'], $key, $info['data'] );
                        }
                        ?>
                    </div>

                    <div class='wpc_pro_multi_cat_wrapper'>
                        <?php
                        $args = array(
                            'hide_empty'  => 0,
                            'taxonomy'    => 'product_cat',
                            'hierarchical' => 1,
                        );
                        $categories = get_categories($args);
                        $options_cat = [];

                        if (is_array($categories)) {
                            foreach ($categories as $category) {
                                $options_cat[$category->term_id] = $category->cat_name;
                            }
                        }

                        $markup_fields_cats = [

                            'wpc_pro_include_cat' => [
                                'item' => [
                                    'label'    => esc_html__( 'Include Categories', 'wpcafe-pro' ),
                                    'desc'     => esc_html__( 'Include all menu items belonging to the following categories while calculating discounts:', 'wpcafe-pro' ),
                                    'type'     => 'select2',
                                    'options'  => $options_cat,
                                    'attr'     => [
                                        'class' => 'wpc_pro_discount_heading', 'input_class'=> 'wpc_pro_multi_cat wpc-settings-input'
                                    ],
                                ],
                                'data' => [ 'wpc_pro_include_cat' => $wpc_pro_include_cat ],
                            ],
                        ];
                        
                        foreach ( $markup_fields_cats as $key => $info ) {
                            $this->get_field_markup( $info['item'], $key, $info['data'] );
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
                <span class="wpc-or-text"><?php echo esc_html__('Or', 'wpcafe-pro'); ?></span>    
        </div>

        <!-- standard discount -->
        <div class='wpc_pro_discount_main_block'>
            <h5 class="wpc_pb_two"><?php echo esc_html__('Standard Discount:', "wpcafe-pro" ) ?></h5>
            <p class="wpc-desc"><?php echo esc_html__('Standard discount be applies on total purchase. It will apply to all menus and categories', 'wpcafe-pro' ) ?></p>
            <div class='wpc_pro_discount_block'>
                <span><?php echo esc_html__('If total order >=:', "wpcafe-pro" ) ?></span>
                <span><input type='number' name='wpc_pro_order_standarad_off_amount' class='wpc_pro_order_standarad_off_amount wpc-settings-input attr-form-control' value="<?php echo intval($wpc_pro_order_standarad_off_amount); ?>" /></span>
                <span><?php echo esc_html__('get:', "wpcafe-pro" ) ?></span>
                <span><input type='number' name='wpc_pro_discount_standarad_off' class='wpc_pro_discount_standarad_off wpc-settings-input attr-form-control' value="<?php echo intval($wpc_pro_discount_standarad_off); ?>" /></span>
                <span><?php echo esc_html__('off', "wpcafe-pro" ) ?></span>
            </div>
            <div>
                <label><?php echo esc_html('Standard discount message', 'wpcafe-pro'); ?></label>
                <input type='text' name='wpc_pro_discount_standarad_off_message' class="wpc-settings-input wpc_pro_discount_standarad_off_message" value="<?php echo esc_html($wpc_pro_discount_standarad_off_message); ?>" />
            </div>
        </div>
    </div>
    <?php
    $args = [ 
        'total'         => esc_html__( 'Total amount', 'wpcafe-pro' ),
        'options_total' => esc_html__( 'Addons total amount', 'wpcafe-pro' ),
    ];
    $markup_fields_two = [
        'wpc_pro_addon_discount_to' => [
            'item' => [
                'label'    => esc_html__( 'Addon discount applicable to?', 'wpcafe-pro' ),
                'desc'     => esc_html__( 'You can set addon discount to total amount, sum of addons total amount.', 'wpcafe-pro' ),
                'type'     => 'select_single',
                'options'  => $args,
                'attr'     => [
                    'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
                ],
            ],
            'data' => [ 'wpc_pro_addon_discount_to' => $wpc_pro_addon_discount_to ],
        ],
    ];

    foreach ( $markup_fields_two as $key => $info ) {
        $this->get_field_markup( $info['item'], $key, $info['data'] );
    }

    ?>
</div>
<?php do_action('wpc_pro_after_discount_settings');?>

<?php return; ?>