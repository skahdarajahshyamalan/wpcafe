<?php

namespace WpCafe_Pro\Core\Modules\Food_Menu;

defined( "ABSPATH" ) || exit;

use WpCafe\Core\Base\Wpc_Settings_Field as Settings;
use WpCafe_Pro\Traits\Singleton;
use WpCafe_Pro\Utils\Utilities as Pro_Utilities;
use WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;

class Food_Discount{
    
    use Singleton;

    public $wpc_message   = '';
    public $wpc_cart_css  = '';

    /**
     * call hooks
     */

    public function init(){
        // Set the price with WooCommerce compatibility
        if (class_exists('WooCommerce')) {
            add_filter('woocommerce_get_item_data', [$this, 'display_cart_item_custom_meta_data'], 10, 2);
            // checkout table
            add_filter('woocommerce_get_item_data', [$this, 'display_cart_item_custom_meta_data'], 10, 2);
            // single page
            add_action('woocommerce_single_product_summary', [$this, 'meta_product'], 15);

            // change in single page
            add_filter('woocommerce_get_price_html', [$this, 'change_simple_product_price_html'], 10, 2);

            // money off from total
            add_filter('woocommerce_calculated_total', [$this, 'money_off'], 10, 2);
            
            add_action('woocommerce_cart_total', [$this, 'add_text_cart_totals']);
            // add discount label in order total  thank you
            add_action('woocommerce_checkout_update_order_meta', [$this, 'change_total_on_thankyou'], 10, 4);

            // show standard off in thank you 
            add_action('woocommerce_order_details_after_order_table', [$this, 'standard_off_thank_you_page'], 10, 1);

            // show order time details in thank you 
            add_action('woocommerce_order_details_before_order_table', [$this, 'order_times_thank_you_page'], 10, 1);

            // show standard off in admin order details page 
            add_action('woocommerce_admin_order_totals_after_total', [$this, 'standard_off_admin_order_details'], 10, 1);

            if (version_compare(WC()->version, '3.0', '<')) {
                add_action('woocommerce_add_order_item_meta', [$this, 'order_item_add_discount'], 1, 2);
            } else {
                add_action('woocommerce_new_order_item', [$this, 'order_item_add_discount'], 1, 2);
            }

            add_filter( 'woocommerce_cart_totals_order_total_html', [$this, 'override_cart_total_html'] );
            add_action('woocommerce_thankyou', [$this, 'unset_discount_session_message'], 10, 1);
        }

    }
    
    /**
     * Unset Discount Message After Checkout
     *
     * @param [type] $order_id
     * @return void
     */
    function unset_discount_session_message( $order_id ) {
        if ( ! $order_id ){
            return;
        }

        if( WC()->session->get( 'wpc_flat_discount_applied' ) === 'yes' ){
            WC()->session->set( 'wpc_flat_discount_applied', null );
            WC()->session->set( 'wpc_pro_actual_price', null ); 
        } 
    }

    /**
     * Update Cart Total Message With Discount Text
     *
     * @param [type] $value
     * @return void
     */
    public function override_cart_total_html( $value ){
        if( WC()->session->get( 'wpc_flat_discount_applied' ) === 'yes' ){
            $settings           = Settings::instance()->get_settings_option();
            $flat_discount_msg  = ( isset($settings['wpc_pro_discount_standarad_off_message']) && ! empty( $settings['wpc_pro_discount_standarad_off_message'] ) ) ? $settings['wpc_pro_discount_standarad_off_message'] : esc_html__('You got exclusive discount on order total', 'wpcafe-pro');
            ob_start();
            ?>
            <span class='wpc-standard-discount-msg'>(<?php echo esc_html( $flat_discount_msg ); ?>)</span>
            <?php
            $value = $value . ob_get_clean();
        } 
        return $value;
    }


    /**
     * Check discount for a product function
     */
    public function check_discount_of_product($product_id, $flag = null , $dokan_user = null ){
        $settings = Settings::instance()->get_settings_option();
        $wpc_pro_discount_message = '';
        $tag_message = '';
        $data = [];

        // check if multi-vendor dokan addon is activated or not
        if( !class_exists('Wpcafe_Multivendor')  ){
            $wpc_pro_discount_product   = isset($settings['wpc_pro_include_menu']) ? $settings['wpc_pro_include_menu'] : [];
            $wpc_pro_discount_cat       = isset($settings['wpc_pro_include_cat']) ? $settings['wpc_pro_include_cat'] : [];
            $wpc_pro_percentage         = isset($settings['wpc_pro_discount_percentage'])  ? sanitize_text_field($settings['wpc_pro_discount_percentage']) : null;
        }
        else{
            // apply vendor percentage
            $vendor_settings            = get_user_meta( $dokan_user , 'dokan_wpcafe_settings', true );
            $wpc_pro_discount_product   = isset($vendor_settings['wpc_pro_include_menu']) ? $vendor_settings['wpc_pro_include_menu'] : [];
            $wpc_pro_discount_cat       = isset($vendor_settings['wpc_pro_include_cat']) ? $vendor_settings['wpc_pro_include_cat'] : [];
            $wpc_pro_percentage         = isset($vendor_settings['wpc_pro_discount_percentage'])  ? sanitize_text_field($vendor_settings['wpc_pro_discount_percentage']) : null;
        }
        if (in_array($product_id, $wpc_pro_discount_product)) {
            if ($wpc_pro_percentage !== '0' && !empty($wpc_pro_percentage)) {
                $wpc_pro_discount_message .=   esc_html__("Discount " , 'wpcafe-pro') . $wpc_pro_percentage . "%";
                $tag_message .= esc_html($wpc_pro_percentage) . "%";
            }
        } else {
            // get cat id 
            $wpc_pro_terms = get_the_terms($product_id, 'product_cat');
            if (is_array($wpc_pro_terms)) {
                foreach ($wpc_pro_terms as $term) {
                    if (in_array($term->term_id, $wpc_pro_discount_cat)) {
                        if ($wpc_pro_percentage !== '0' && !empty($wpc_pro_percentage)) {
                            $wpc_pro_discount_message .=  esc_html__("Discount " , 'wpcafe-pro') . $wpc_pro_percentage . "%";
                            $tag_message .= $wpc_pro_percentage . esc_html__("% off", 'wpcafe-pro') ;
                        }
                    }
                }
            }
        }
        if (empty($flag)) {
            $data['message'] = $wpc_pro_discount_message;
            if ($data['message'] !== '') {
                $data['percentage'] = $wpc_pro_percentage;
            } else {
                $data['percentage'] = '';
            }
        } else {
            $data['percentage_offer'] = $tag_message; // tag name
        }
        return $data;
    }


    /**
     * Display discount data in  cart item meta data (in cart)
     */
    public function display_cart_item_custom_meta_data($item_data, $cart_item){
        if ( empty( $cart_item['wpc_addons'] ) ) {
            $vendor_id = get_post_field( 'post_author', $cart_item['product_id'] );
            $wpc_pro_check_discount = $this->check_discount_of_product($cart_item['product_id']  , null , $vendor_id );
            ?>
            <div class='wpc_pro_discount_price'><?php echo esc_html($wpc_pro_check_discount['message']); ?>
            </div>
            <?php
        }
        return $item_data;
    }

    /**
     * Displaying the value on single product pages
     */
    public function meta_product(){
        global $product;

        $vendor_id = get_post_field( 'post_author', $product->get_id() );
        $wpc_pro_check_discount = $this->check_discount_of_product($product->get_id() , null , $vendor_id );
        
        ?>
        <div class="discount-message"><?php echo esc_html($wpc_pro_check_discount['message']); ?></div>
        <?php
        // preparing time and delivery time in single product
        $preparing_time = get_post_meta( $product->get_id(), 'wpc_pro_preparing_time', true);
        $wpc_pro_preparing_time = \WpCafe_Pro\Core\Shortcodes\Hook::instance()->food_time_in_convert($preparing_time);
        $delivery_time = get_post_meta( $product->get_id(), 'wpc_pro_delivery_time', true);
        $wpc_pro_delivery_time = \WpCafe_Pro\Core\Shortcodes\Hook::instance()->food_time_in_convert($delivery_time);

        if($wpc_pro_preparing_time != '' || $wpc_pro_delivery_time != ''):
            ?>
            <ul class="food-preparation">
                <?php if($wpc_pro_preparing_time != ''): ?>
                    <li><strong><?php echo esc_html__('Preparing Time: ', 'wpcafe-pro'); ?></strong><?php echo esc_html($wpc_pro_preparing_time); ?></li>
                <?php endif; ?>
                <?php if($wpc_pro_delivery_time != ''): ?>
                    <li><strong><?php echo esc_html__('Delivery Time: ', 'wpcafe-pro'); ?></strong><?php echo esc_html($wpc_pro_delivery_time); ?></li>
                <?php endif; ?>
            </ul>
            <?php
        endif;
    }

    /**
     * Change price in single page function and from backend product price
     */
    public function change_simple_product_price_html($price_html, $product){
        $vendor_id = get_post_field( 'post_author', $product->get_id() );
        $discount_price_args = array(
            'product_id'    => $product->get_id(),
            'data'          => 'wpc_pro_single_page',
            'product_price' => null,
            'auth_id'       => $vendor_id
        );
        $wpc_pro_data = Pro_Utilities::discount_price( $discount_price_args );

        if (
            is_product() && !empty($wpc_pro_data['main_price']) && !empty($wpc_pro_data['price_afer_discount'])
            && $wpc_pro_data['main_price'] !== '' && $wpc_pro_data['price_afer_discount'] !== '' && $product->get_type() != 'variable' 
        ) { ?>
            <div class='wpc-price-wrap'>
                <del class='wpc-main-price'><?php echo wc_price( $wpc_pro_data['main_price'] ); ?></del>
                <span class='wpc-discount-price'><?php echo  Pro_Utilities::kses( $wpc_pro_data['price_afer_discount'] ) ;?></span>
            </div>
        <?php } else {
            
            return $price_html;
        }
    }

    /**
     * Change price in cart function
     */
    public function product_discount_price_cart($price, $cart_item, $cart_item_key){
        $product = wc_get_product($cart_item['product_id']);
        $variation_id = $cart_item['variation_id'];

        $main_price = "";
        $price_for_discount = "";
        if ($product->get_type() == "variable") {
            // $main_price = $price;
            // $price_for_discount = $cart_item['data']->get_sale_price();

            $main_price = wc_price( get_post_meta( $variation_id, '_price', true ) );
            $price_for_discount = get_post_meta( $variation_id, '_price', true );
        } else {
            $tax_price  = Wpc_Utilities::menu_price_by_tax( $product );
            
            $main_price = wc_price( $tax_price );
            $price_for_discount = $tax_price;
        }
        $vendor_id = get_post_field( 'post_author', $cart_item['product_id'] );
        $discount_price_args = array(
            'product_id'    => $cart_item['product_id'],
            'data'          => 'wpc_pro_cart',
            'product_price' => $price_for_discount,
            'auth_id'       => $vendor_id
        );
        $get_discount_price = Pro_Utilities::discount_price( $discount_price_args );
        
        if (empty($get_discount_price) === false) { ?>
            <div class='wpc_pro_main_price'><?php echo esc_html__("Main price :", 'wpcafe-pro') . $main_price; ?></div>
            <?php
            $price = $get_discount_price;
        }
        
        return $price;
    }

    /**
     * Filter subtotal function
     */
    public function woocommerce_calculate_subtotal($cart_object){
        if (is_admin() && !defined('DOING_AJAX'))
            return;
        if (did_action('woocommerce_before_calculate_totals') >= 2)
            return;
        foreach ($cart_object->get_cart() as $cart_item) {
            // Price calculation 
            $vendor_id = get_post_field( 'post_author', $cart_item['product_id'] );

            $product       = wc_get_product( $cart_item['product_id'] );
            $product_price = null;
            if ( $product->get_type() == 'variable' ) {
                $variation_id  = $cart_item['variation_id'];
                $product_price = get_post_meta( $variation_id, '_price', true );
            }

            $discount_price_args = array(
                'product_id'    => $cart_item['product_id'],
                'data'          => 'wpc_pro_cart_sub_total',
                'product_price' => $product_price,
                'auth_id'       => $vendor_id
            );
            $get_discount_price = Pro_Utilities::discount_price( $discount_price_args );
            
            if (!empty($get_discount_price)) {
                // Set the price with WooCommerce compatibility 
                if (version_compare(WC()->version, '3.0', '<')) {
                    $cart_item['data']->price =  (float) $get_discount_price; // Before WC 3.0
                } else {
                    $cart_item['data']->set_price((float) $get_discount_price); // WC 3.0+
                }
            }
        }
    }

    /**
     * Check money off function
     */
    public function money_off_data(){
        if( !class_exists('Wpcafe_Multivendor') ){
            $settings = Settings::instance()->get_settings_option();
            $wpc_pro_order_standarad_off_amount = isset($settings['wpc_pro_order_standarad_off_amount']) ? $settings['wpc_pro_order_standarad_off_amount'] : '';
            $wpc_pro_discount_standarad_off     = isset($settings['wpc_pro_discount_standarad_off']) ? $settings['wpc_pro_discount_standarad_off'] : '';
        }
        else{
            $vendor_id                          = get_post_field( 'post_author', get_the_id() );
            $settings                           = get_user_meta( $vendor_id, 'dokan_wpcafe_settings', true );
            $wpc_pro_order_standarad_off_amount = isset($settings['wpc_pro_order_standarad_off_amount']) ? $settings['wpc_pro_order_standarad_off_amount'] : '';
            $wpc_pro_discount_standarad_off     = isset($settings['wpc_pro_discount_standarad_off']) ? $settings['wpc_pro_discount_standarad_off'] : '';
        }    
        $data = [];
        $data['wpc_pro_order_standarad_off_amount'] = $wpc_pro_order_standarad_off_amount;
        $data['wpc_pro_discount_standarad_off']     = $wpc_pro_discount_standarad_off;
        
        return $data;
    }



    /**
     * Total with standarad money off function
     */
    public function money_off($total, $cart){
        $wpc_pro_money_off = $this->money_off_data();
        // check if money off not null
        if ( ($wpc_pro_money_off['wpc_pro_order_standarad_off_amount'] !== '') && ($wpc_pro_money_off['wpc_pro_discount_standarad_off'] !== '' && absint( $wpc_pro_money_off['wpc_pro_discount_standarad_off'] ) > 0 ) && ((float) $total >= (float) $wpc_pro_money_off['wpc_pro_order_standarad_off_amount']) ) {
            WC()->session->set( 'wpc_flat_discount_applied', 'yes' ); 
            WC()->session->set( 'wpc_pro_actual_price', $total ); 
            return $total - $wpc_pro_money_off['wpc_pro_discount_standarad_off'];
        } else {
            WC()->session->set( 'wpc_flat_discount_applied', null ); 
            WC()->session->set( 'wpc_pro_actual_price', null ); 
            return $total;
        }
    }

    /**
     * Change cart total text  function
     */
    public function add_text_cart_totals($price){

        $actual_price      = WC()->session->get( 'wpc_pro_actual_price' );        
        $wpc_pro_money_off = $this->money_off_data();
        
        // check if money off not null
        if ( ! empty( $actual_price ) &&
            $wpc_pro_money_off['wpc_pro_order_standarad_off_amount'] !== '' && $wpc_pro_money_off['wpc_pro_order_standarad_off_amount'] !='' &&  $wpc_pro_money_off['wpc_pro_discount_standarad_off'] !== '' && absint( $wpc_pro_money_off['wpc_pro_discount_standarad_off'] ) > 0
            && ( (float) $actual_price >= (float) $wpc_pro_money_off['wpc_pro_order_standarad_off_amount'] )
        ) {
            $wpc_pro_money_off_message = esc_html__('You got discount ', "wpcafe-pro") . wc_price( $wpc_pro_money_off['wpc_pro_discount_standarad_off'] );
            ob_start();
            ?>
                <div><?php echo Wpc_Utilities::wpc_kses($price); ?></div>
                <div>(<?php echo Wpc_Utilities::wpc_kses($wpc_pro_money_off_message); ?>)</div>
            <?php
            return ob_get_clean();
        } else {
            return $price;
        }
    }

   
    /**
     * Add discount on thank you and admin order
     */
    public function order_item_add_discount($item_id, $values) {
        
        $addons_price = absint( wc_get_order_item_meta( $item_id, '_addons_price' ) );

        $vendor_id      = get_post_field( 'post_author', $item_id );
        $discount_price_args = array(
            'product_id'    => $values['product_id'],
            'data'          => 'thank_you_order_details',
            'product_price' => null,
            'auth_id'       => $vendor_id,
            'addons_price'  => $addons_price,
            );
        $wpc_pro_data   = Pro_Utilities::discount_price( $discount_price_args );
        
        if ( !empty( $wpc_pro_data['main_price'] ) && 
            $wpc_pro_data['main_price'] !== '' && $wpc_pro_data['price_afer_discount'] !== ''
            && !empty($wpc_pro_data['main_price']) && !empty($wpc_pro_data['price_afer_discount'])
        ) {
            wc_add_order_item_meta($item_id, esc_html__('Main price', 'wpcafe-pro'), wc_price( $wpc_pro_data['main_price'] ) );
            if ( $addons_price > 0 )  {
                wc_add_order_item_meta($item_id, esc_html__('Addons price', 'wpcafe-pro'), wc_price( $addons_price ) );
                
                $discount_applied_on = ( $wpc_pro_data['discount_applied_on'] == 'options_total' ) ? 'product and addons' : 'product';
                $discount_final_msg = "Total price after ". $wpc_pro_data['discount_percentage'] ."% discount on " . $discount_applied_on;
                wc_add_order_item_meta( $item_id, esc_html($discount_final_msg), wc_price( $wpc_pro_data['new_price'] ) . '+' . wc_price( $wpc_pro_data['addons_new_price'] ) );
            } else {
                wc_add_order_item_meta($item_id, esc_html__('Price after discount', 'wpcafe-pro'), $wpc_pro_data['price_afer_discount']);
            }
        }

        // Add food preparing and delivery time
        $wpc_pro_preparing_time = get_post_meta( $values['product_id'] , 'wpc_pro_preparing_time', true);
        $wpc_pro_preparing_time = \WpCafe_Pro\Core\Shortcodes\Hook::instance()->food_time_in_convert($wpc_pro_preparing_time);
        $wpc_pro_delivery_time  = get_post_meta( $values['product_id'] , 'wpc_pro_delivery_time', true);
        $wpc_pro_delivery_time  = \WpCafe_Pro\Core\Shortcodes\Hook::instance()->food_time_in_convert($wpc_pro_delivery_time);
       
        if ( $wpc_pro_preparing_time !== '' ) {
            wc_add_order_item_meta($item_id, esc_html__('Preparing time ', 'wpcafe-pro'), Wpc_Utilities::wpc_numeric($wpc_pro_preparing_time));
        }

        if ( $wpc_pro_delivery_time !== '' ) {
            wc_add_order_item_meta($item_id, esc_html__('Delivery time ', 'wpcafe-pro'), Wpc_Utilities::wpc_numeric($wpc_pro_delivery_time));
        }
    }
 
    /**
     * Standard discount save from checkout Total function
     */
    public function change_total_on_thankyou($order_id){
        
        $wpc_pro_money_off = $this->money_off_data();
        $order = wc_get_order( $order_id );

        // check if money off not null
        if (
            $wpc_pro_money_off['wpc_pro_order_standarad_off_amount'] !== '' && $wpc_pro_money_off['wpc_pro_discount_standarad_off'] !== ''
            &&  !empty($wpc_pro_money_off['wpc_pro_order_standarad_off_amount']) &&  !empty($wpc_pro_money_off['wpc_pro_discount_standarad_off'])
            && $wpc_pro_money_off['wpc_pro_discount_standarad_off'] !== '0' 
            && ( $order !==null && ( (float) $order->get_total() >= (float) $wpc_pro_money_off['wpc_pro_discount_standarad_off']) )
        ) {
            $wpc_pro_money_off_message = esc_html__('After money off ', "wpcafe-pro") . $wpc_pro_money_off['wpc_pro_discount_standarad_off'];
            update_post_meta($order_id, 'wpc_pro_money_off', $wpc_pro_money_off_message);
        }
    }

    public function standard_message($id){
        $wpc_pro_money_off = get_post_meta($id, 'wpc_pro_money_off', true);
        
        ?>
        <div class="wpc_pro_standard_off"><?php echo esc_html($wpc_pro_money_off); ?></div>
        <?php
    }

    /**
     * Show standard off in thank you page
     */
    public function standard_off_thank_you_page($order) {
        
        $wpc_pro_order_id = $order->get_data()['id'];
        $this->standard_message($wpc_pro_order_id);
    }

    /**
     * Show standard off in admin order page
     */
    public function standard_off_admin_order_details($order_id){
        $this->standard_message($order_id);
    }

    /**
     * Show order time data in thank you page
     */
    public function order_times_thank_you_page($order){
        $wpc_pro_order_id = $order->get_data()['id'];
        
        \WpCafe_Pro\Core\Template\Food_Menu::instance()->order_type_markup( $wpc_pro_order_id );
    }
}
