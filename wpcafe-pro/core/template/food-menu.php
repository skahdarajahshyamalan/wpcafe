<?php

namespace WpCafe_Pro\Core\Template;

defined("ABSPATH") || exit;

use WpCafe_Pro\Utils\Utilities;

class Food_Menu{

    use \WpCafe_Pro\Traits\Singleton;

    /**
     * Dokan Food time markup
     */
    public function dokan_food_time_markup( $preparing_time , $delivery_time ){
        ?>
        <div class="dokan-form-group">
            <label for="preparing_time" class="form-label"><?php esc_html_e( 'Preparing time', 'wpcafe-pro' ); ?></label>
            <input name="wpc_pro_preparing_time" type="text" value="<?php echo esc_attr( $preparing_time ) ;?>" id="preparing_time" class="dokan-form-control"/>
        </div>
        <div class="dokan-form-group">
            <label for="delivery_time" class="form-label"><?php esc_html_e( 'Delivery time', 'wpcafe-pro' ); ?></label>
            <input name="wpc_pro_delivery_time" type="text" value="<?php echo esc_attr( $delivery_time ) ;?>" id="delivery_time" class="dokan-form-control"/>
        </div>
        <?php
    }

    /**
     * Order type markup for thank you page
     */
    public  function order_type_markup( $wpc_pro_order_id ){
        $checked_data   = Utilities::get_order_type();
        $settings       = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option(); 
        if( !empty( $settings['wpcafe_food_location'] ) || class_exists('Wpcafe_Multivendor')){
            $order_food_location    = get_post_meta($wpc_pro_order_id, 'wpc_location_name', true);
            $order_location         = apply_filters('wpcafe_pro/render/thankyou_order_location', $order_food_location, $wpc_pro_order_id);
            
            if(!empty( $order_location )){
                ?>
                <div class="wpc-order-food-location">
                    <h2 class="wpc-thankyou-location-text">
                        <?php echo esc_html__("Food Order Location","wpcafe-pro"); ?>
                    </h2>
                    <div class="wpc-thankyou-location-value">
                        <?php echo Utilities::render($order_location); ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        <?php if(!empty($settings['wpc_pro_allow_order_for']) && $settings['wpc_pro_allow_order_for'] !=''){ ?>
            <ul class="wpc-pickup-delivery">
                <?php
                foreach ($checked_data as $key => $value) {
                    $wpc_pro_result = get_post_meta($wpc_pro_order_id, $value, true); 

                    $delivery_type_arr = [
                        'Pickup'    => esc_html__('Pickup', 'wpcafe-pro'),
                        'Delivery'    => esc_html__('Delivery', 'wpcafe-pro')
                    ];

                    if('Pickup' == $wpc_pro_result || 'Delivery' == $wpc_pro_result){
                        $wpc_pro_result = $delivery_type_arr[$wpc_pro_result];
                    }
                    
                    if ($wpc_pro_result !== '') { 
                        ?>
                        <li><?php echo esc_html($key); ?>: <strong><?php echo esc_html($wpc_pro_result); ?></strong></li>
                        <?php
                    } 
                } 
                ?>
            </ul>
        <?php } ?>
        <?php
    }

    /**
     * live search markup function
     *  * @param array $args = [ $no_of_product, $wpc_cat_arr, $wpc_cart_button, $template, $template_path, $widget_arr, $search_alignment ]
     */
    public function live_search_markup( $args ){
        $defaults = array(
            'no_of_product' => 10,
            'wpc_cat_arr' => null,
            'wpc_cart_button' => "yes",
            'template' => null,
            'template_path' => null,
            'widget_arr' => null,
            'search_alignment'=> ''
        );

        extract( wp_parse_args( $args, $defaults));
        $live_search_template = \Wpcafe_Pro::core_dir() . "shortcodes/views/live-search/live-search.php";
        if( file_exists( $live_search_template ) ){
           return include $live_search_template;
        }
    }
    
}
