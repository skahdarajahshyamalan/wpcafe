<?php

namespace WpCafe_Pro\Core\Shortcodes;

defined("ABSPATH") || exit;

use WC_Product;
use WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;
use WpCafe_Pro\Utils\Table_Utils as Table_Layout_Helper;

use WpCafe\Core\Base\Wpc_Settings_Field as Settings;
use WP_Post;
use WpCafe_Pro\Traits\Singleton;

/**
 * create post type class
 */
class Hook
{
    use Singleton;

    private $settings_obj = null ;
    public $wpc_message   = '';
    public $wpc_cart_css  = '';

    /**
     * call hooks
     */

    public function init(){
        $this->settings_obj = Settings::instance()->get_settings_option();
        $shortcode_arr =  array(
            'wpc_pro_food_menu_tab'      => 'food_menu_tab',
            'wpc_pro_food_menu_list'     => 'food_menu_list',
            'wpc_pro_menu_slider'        => 'food_menu_slider',
            'wpc_pro_menu_tab_with_slider' => 'food_menu_tab_with_slider',
            'wpc_pro_food_menu_loadmore' => 'food_menu_loadmore',
            'wpc_pro_business_hour'      => 'business_hour',
            'wpc_pro_menu_category_list' => 'food_menu_category_list',
            'wpc_pro_menu_location_list' => 'food_menu_location_list',
            'wpc_reservation_form_pro'   => 'reservation_form_pro',
            'wpc_reservation_with_food'  => 'reservation_with_food',
            'wpc_visual_reservation_form' => 'visual_reservation_form',
            'wpc_pickup_delivery_search' => 'pickup_delivery_search',
        );

        // add shortcode
        if( ! empty( $shortcode_arr)){
            foreach ($shortcode_arr as $key => $value) {
                add_shortcode( $key, [$this, $value ] );
            }
        }

        // place shortcode to a page
        add_filter('the_content', [$this, 'place_shortcode']);

        // Set the price with WooCommerce compatibility
        if (class_exists('WooCommerce')) {
            // add body classes
            add_filter('body_class', [$this, 'wpc_body_classes']);

            // search like
            add_filter('posts_where', [$this, 'title_like_posts_where'], 10, 2);
			
            // variation product
            add_action('variation/product_title', 'woocommerce_template_single_title', 5);
            add_action('variation/product_thumbnails', 'woocommerce_show_product_images', 20);
            add_action('variation/popup_content', 'woocommerce_template_single_rating', 10);
            add_action('variation/popup_content', 'woocommerce_template_single_price', 15);
            add_action('variation/popup_content', 'woocommerce_show_product_sale_flash', 10);
            add_action('variation/popup_content', 'woocommerce_template_single_excerpt', 20);
			add_action('variation/popup_content', 'woocommerce_template_single_add_to_cart', 30);

            add_filter('woocommerce_dropdown_variation_attribute_options_html', [$this, 'variation_radio_buttons'], 20, 2);
            add_filter('woocommerce_dropdown_variation_attribute_options_args', [$this, 'variation_select_add_class'], 2);
            add_filter('woocommerce_locate_template', [$this, 'wpcafe_variable_template'], 1, 3);

            // variation button
            add_filter('wpcafe/shortcode/variation', [$this, 'variation_option_content'], 10, 4);
            add_filter('wpcafe/shortcode/simple', [$this, 'simple_product_content'], 10, 4);

        }
    }

    /**
     * Variation template
     */
    public function wpcafe_variable_template($template, $template_name, $template_path){

        if ( WPC_DEMO_SITE === true && WPC_VARIATION_TEMPLATE_ONE_ID == get_the_ID() ){
            if ($template_name == "single-product/add-to-cart/variable.php") {
                return \Wpcafe_Pro::core_dir() . "template/woocommerce/single-product/add-to-cart/variable.php";
            }
            return $template;
        } elseif ( WPC_DEMO_SITE === true && WPC_VARIATION_TEMPLATE_TWO_ID == get_the_ID() ){
            return $template;
        } else {
            if ($template_name == "single-product/add-to-cart/variable.php" && isset($this->settings_obj['wpc_pro_woocommerce_variation_layout']) && $this->settings_obj['wpc_pro_woocommerce_variation_layout'] == 'on' && $template_name == "single-product/add-to-cart/variable.php") {
                return \Wpcafe_Pro::core_dir() . "template/woocommerce/single-product/add-to-cart/variable.php";
            }
            return $template;
        }
    }

    /**
     * add class in variation function
     */
    public function variation_select_add_class($args){
        if ( WPC_DEMO_SITE === true && WPC_VARIATION_TEMPLATE_ONE_ID == get_the_ID() ){
            $args['class'] = 'wpc_variation_poppup';
            return $args;
        } elseif ( WPC_DEMO_SITE === true && WPC_VARIATION_TEMPLATE_TWO_ID == get_the_ID() ){
            return $args;
        } else{
            if (isset($this->settings_obj['wpc_pro_woocommerce_variation_layout']) && $this->settings_obj['wpc_pro_woocommerce_variation_layout'] == 'on') {
                $args['class'] = 'wpc_variation_poppup';
                return $args;
            }
            return $args;
        }
    } 

    /**
     * Variation radio function
     */
    public function variation_radio_buttons($html, $args){

        if ( WPC_DEMO_SITE === true && WPC_VARIATION_TEMPLATE_ONE_ID == get_the_ID() ){
            $args = wp_parse_args(apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args), array(
                'options'          => false,
                'attribute'        => false,
                'product'          => false,
                'selected'         => false,
                'name'             => '',
                'id'               => '',
                'class'            => '',
                'show_option_none' => esc_html__('Choose an option', 'wpcafe-pro'),
            ));
    
            if (false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product) {
                $selected_key     = 'attribute_' . sanitize_title($args['attribute']);
                $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key])) : $args['product']->get_variation_default_attribute($args['attribute']);
            }
    
            $options               = $args['options'];
            $product               = $args['product'];
            $attribute             = $args['attribute'];
            $name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
            $id                    = $args['id'] ? $args['id'] : sanitize_title($attribute);
            $class                 = $args['class'];
            $show_option_none      = (bool)$args['show_option_none'];
            $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__('Choose an option', 'wpcafe-pro');
    
            if (empty($options) && !empty($product) && !empty($attribute)) {
                $attributes = $product->get_variation_attributes();
                $options    = $attributes[$attribute];
            }
    
            $radios = '<div class="wpc-variation">';
            $radios .= '<label class="wpc-variation-title">' . esc_html(wc_attribute_label($attribute)) . '<span class="dashicons dashicons-minus toggle-icon-trigger"></span></label>';
            $radios .= '<div class="wpc-variation-body variation-radios">';
            
            if (!empty($options)) {
                if ($product && taxonomy_exists($attribute)) {
                    $terms = wc_get_product_terms($product->get_id(), $attribute, array(
                        'fields' => 'all',
                    ));
    
                    foreach ($terms as $term) {
                        if (in_array($term->slug, $options, true)) {
                            $radios .= '<label for="' . esc_attr($term->slug) . '"><input type="radio" name="' . esc_attr($name) . '" value="' . esc_attr($term->slug) . '" ' . checked(sanitize_title($args['selected']), $term->slug, false) . '><span class="wpc-veriation-attribute">' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name)) . '</span></label>';
                        }
                    }
                } else {
                    foreach ($options as $option) {
                        $checked    = sanitize_title($args['selected']) === $args['selected'] ? checked($args['selected'], sanitize_title($option), false) : checked($args['selected'], $option, false);
                        $radios    .= '<label for="' . sanitize_title($option) . '"><input type="radio" name="' . esc_attr($name) . '" value="' . esc_attr($option) . '" id="' . sanitize_title($option) . '" ' . $checked . '><span class="wpc-veriation-attribute">' . esc_html(apply_filters('woocommerce_variation_option_name', $option)) . '</span></label>';
                    }
                }
            }
            
            $radios .= '</div></div>';
    
            return $html . $radios;
        } elseif ( WPC_DEMO_SITE === true && WPC_VARIATION_TEMPLATE_TWO_ID == get_the_ID() ){
            return $html;
        } else {
            if (isset($this->settings_obj['wpc_pro_woocommerce_variation_layout']) && $this->settings_obj['wpc_pro_woocommerce_variation_layout'] == 'on') {
                $args = wp_parse_args(apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args), array(
                    'options'          => false,
                    'attribute'        => false,
                    'product'          => false,
                    'selected'         => false,
                    'name'             => '',
                    'id'               => '',
                    'class'            => '',
                    'show_option_none' => esc_html__('Choose an option', 'wpcafe-pro'),
                ));
        
                if (false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product) {
                    $selected_key     = 'attribute_' . sanitize_title($args['attribute']);
                    $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key])) : $args['product']->get_variation_default_attribute($args['attribute']);
                }
        
                $options               = $args['options'];
                $product               = $args['product'];
                $attribute             = $args['attribute'];
                $name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
                $id                    = $args['id'] ? $args['id'] : sanitize_title($attribute);
                $class                 = $args['class'];
                $show_option_none      = (bool)$args['show_option_none'];
                $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__('Choose an option', 'wpcafe-pro');
        
                if (empty($options) && !empty($product) && !empty($attribute)) {
                    $attributes = $product->get_variation_attributes();
                    $options    = $attributes[$attribute];
                }
        
                $radios = '<div class="wpc-variation">';
                $radios .= '<label class="wpc-variation-title">' . esc_html(wc_attribute_label($attribute)) . '<span class="dashicons dashicons-minus toggle-icon-trigger"></span></label>';
                $radios .= '<div class="wpc-variation-body variation-radios">';
                
                if (!empty($options)) {
                    if ($product && taxonomy_exists($attribute)) {
                        $terms = wc_get_product_terms($product->get_id(), $attribute, array(
                            'fields' => 'all',
                        ));
        
                        foreach ($terms as $term) {
                            if (in_array($term->slug, $options, true)) {
                                $radios .= '<label for="' . esc_attr($term->slug) . '"><input type="radio" name="' . esc_attr($name) . '" value="' . esc_attr($term->slug) . '" ' . checked(sanitize_title($args['selected']), $term->slug, false) . '><span class="wpc-veriation-attribute">' . esc_html(apply_filters('woocommerce_variation_option_name', $term->name)) . '</span></label>';
                            }
                        }
                    } else {
                        foreach ($options as $option) {
                            $checked    = sanitize_title($args['selected']) === $args['selected'] ? checked($args['selected'], sanitize_title($option), false) : checked($args['selected'], $option, false);
                            $radios    .= '<label for="' . sanitize_title($option) . '"><input type="radio" name="' . esc_attr($name) . '" value="' . esc_attr($option) . '" id="' . sanitize_title($option) . '" ' . $checked . '><span class="wpc-veriation-attribute">' . esc_html(apply_filters('woocommerce_variation_option_name', $option)) . '</span></label>';
                        }
                    }
                }
                
                $radios .= '</div></div>';
        
                return $html . $radios;
            }
            return $html;
        }
    }





    /**
     * Food by location
     */
    public function food_location_menu( $atts ){
        if (!class_exists('Woocommerce')) {
            return;
        }

        ob_start();

        $unique_id = md5(md5(microtime()));

        // shortcode option
        $atts = extract(shortcode_atts(
            [
                'wpc_food_categories'   => '',
                'style'                 => 'style-3',
                'no_of_product'         => 5,
                'show_thumbnail'        => "yes",
                'wpc_cart_button'       => 'yes',
                'title_link_show'       => 'yes',
                'wpc_menu_col'          => '6',
                'wpc_show_desc'         => 'yes',
                'wpc_desc_limit'        => '15',
                'live_search'           => 'yes',
                'wpc_delivery_time_show'=> 'yes',
                'show_item_status'      => 'yes',
                'wpc_btn_text'          => '',
                'customize_btn'         => '',
                'wpc_menu_order'        => 'DESC',
                'wpc_nav_position'      => 'top',
                'class'                 => ''
            ], $atts ));

        $products = wc_get_products([]);
        
        $product_data = [
            'wpc_food_categories'   => $wpc_food_categories,
            'style'                 => $style,
            'no_of_product'         => $no_of_product,
            'show_thumbnail'        => $show_thumbnail,
            'wpc_cart_button'       => $wpc_cart_button,
            'title_link_show'       => $title_link_show,
            'wpc_menu_col'          => $wpc_menu_col,
            'wpc_show_desc'         => $wpc_show_desc,
            'wpc_desc_limit'        => $wpc_desc_limit,
            'live_search'           => $live_search,
            'wpc_delivery_time_show'=> $wpc_delivery_time_show,
            'show_item_status'      => $show_item_status,
            'wpc_btn_text'          => $wpc_btn_text,
            'customize_btn'         => $customize_btn,
            'wpc_menu_order'        => $wpc_menu_order,
            'wpc_nav_position'      => $wpc_nav_position,
            'unique_id'             => $unique_id,
        ];

        if ( file_exists( \WpCafe_Pro::plugin_dir() . "core/shortcodes/views/food-menu/location-menu.php" ) ) {
            $col = "wpc-col-md-". $wpc_menu_col;
            ?>
            <div class="location_menu" data-product_data ="<?php esc_attr_e( json_encode( $product_data  ));?>">
                <?php include \WpCafe_Pro::plugin_dir() . "core/shortcodes/views/food-menu/location-menu.php"; ?>
            </div>
            <?php
        }

        return ob_get_clean();
    }
    



    /**
     * Update search function for live search 
     *
     */
    public function title_like_posts_where($where, $wp_query){
        global $wpdb;
        if ($post_title_like = $wp_query->get('post_title_like')) {
            $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($wpdb->esc_like($post_title_like)) . '%\'';
        }

        return $where;
    }


    /**
     * Variation product template
     */
    public function variation_popup_template($product_id){
        // Set the main wp query for the product.
        wp('p=' . $product_id . '&post_type=product');
        while (have_posts()) :
            the_post();
        ?>
            <div id="product-<?php echo intval($product_id) ?>" <?php post_class('product wpc-row') ?>>

                <div class="wpc-col-lg-6 variation_product_image">
                    <?php do_action('variation/product_thumbnails'); ?>
                </div>
                <div class="wpc-col-lg-6">
                    <div class="wpc-single-content summary entry-summary">
                        <h2 class="product_title entry-title"><?php echo esc_html(the_title());?></h2>
                        <?php do_action('variation/popup_content'); ?>
                    </div>
                </div>
            </div>
        <?php
        endwhile; // end of the loop.
    }

    /**
     * Check discount for a product function
     */
    public function check_discount_of_product($product_id, $flag = null , $dokan_user = null ){
        $settings = $this->settings_obj;
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
                $wpc_pro_discount_message .=   esc_html__("Discount " . $wpc_pro_percentage, 'wpcafe-pro') . "%";
                $tag_message .= esc_html($wpc_pro_percentage) . "%";
            }
        } else {
            // get cat id 
            $wpc_pro_terms = get_the_terms($product_id, 'product_cat');
            if (is_array($wpc_pro_terms)) {
                foreach ($wpc_pro_terms as $term) {
                    if (in_array($term->term_id, $wpc_pro_discount_cat)) {
                        if ($wpc_pro_percentage !== '0' && !empty($wpc_pro_percentage)) {
                            $wpc_pro_discount_message .=  esc_html("Discount " . $wpc_pro_percentage . "%");
                            $tag_message .= esc_html__($wpc_pro_percentage . "% off", 'wpcafe-pro');
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
     * Show tag function
     */
    public function tag($id, $stock_status){
        ?>
        <div class="wpc-pro-tag">
            <?php
            echo \WpCafe\Utils\Wpc_Utilities::wpc_tag($id, $stock_status);
            // show discount 
            if ($stock_status == true) {
                $data = $this->check_discount_of_product($id, 'wpc_pro_tag');
                if (count($data) > 0 && $data['percentage_offer'] != '') {
            ?>
                    <ul class="wpc-menu-tag wpc-discount-offer">
                        <li><?php echo esc_html($data['percentage_offer']); ?></li>
                    </ul>
            <?php
                }
            }
            ?>
        </div>
        <?php
    }

    /**
     * Convert food preparing and delivery time for individual product
     */
    public function food_time_in_convert($time){
        $time = explode(':', $time);
        $main_time = '';
        if (is_array($time)) {
            if (isset($time[0]) && $time[0] !== '' && $time[0] !== '00') {
                $main_time .= $time[0] . " " . "hr";
            }
            if (isset($time[1]) && $time[1] !== '' && $time[1] !== '00') {
                $main_time .= $time[1] . " " . "min";
            }
        }
        return $main_time;
    }

    /**
     * Food preparing and delivery function
     */
    public function food_time($id){
        // food preparing and delivery time
        $wpc_pro_preparing_time = get_post_meta($id, 'wpc_pro_preparing_time', true);
        $wpc_pro_preparing_time = $this->food_time_in_convert($wpc_pro_preparing_time);
        $wpc_pro_delivery_time  = get_post_meta($id, 'wpc_pro_delivery_time', true);
        $wpc_pro_delivery_time  = $this->food_time_in_convert($wpc_pro_delivery_time);

        if ($wpc_pro_preparing_time !== '' || $wpc_pro_delivery_time !== '') {
        ?>
            <ul class="wpc_pro_food_time">
                <?php if ($wpc_pro_preparing_time !== '') { ?>
                    <li class="wpc_pro_food_preparing_time">
                        <span class="wpc_label_meta">
                            <?php echo esc_html__(' Preparing time : ', 'wpcafe-pro'); ?>
                        </span>
                        <span class="wpc_preparing_time">
                            <?php echo Wpc_Utilities::wpc_numeric($wpc_pro_preparing_time); ?>
                        </span>
                    </li>
                <?php }
                if ($wpc_pro_delivery_time !== '') { ?>
                    <li class="wpc_pro_food_delivery_time">
                        <span class="wpc_label_meta">
                            <?php echo esc_html__(' Delivery time : ', 'wpcafe-pro'); ?>
                        </span>
                        <span class="wpc_preparing_time">
                            <?php echo Wpc_Utilities::wpc_numeric($wpc_pro_delivery_time); ?>
                        </span>
                    </li>
                <?php } ?>
            </ul>
        <?php
        }
    }

    /**
     * Food menu tab shortcode
     */
    public function food_menu_tab($atts){
        if (!class_exists('Woocommerce')) {
            return;
        }
        ob_start();
        $settings = $this->settings_obj;
        $atts     = Wpc_Utilities::replace_qoute( $atts );

        $wpc_pro_standarad_off   = isset($settings['wpc_pro_discount_standarad_off_message'])  ? sanitize_text_field($settings['wpc_pro_discount_standarad_off_message']) : '';
        if ($wpc_pro_standarad_off !== '') {
        ?>
            <div class="wpc_pro_standard_offer_message"><?php echo esc_html($wpc_pro_standarad_off); ?></div>
        <?php
        }
        // shortcode option
        $atts = extract(shortcode_atts(
            [
                'wpc_food_categories'   => '',
                'style'                  => 'style-3',
                'no_of_product'         => 5,
                'show_thumbnail'        => "yes",
                'wpc_cart_button'       => 'yes',
                'title_link_show'       => 'yes',
                'wpc_menu_col'          => '6',
                'wpc_show_desc'         => 'yes',
                'wpc_desc_limit'        => '15',
                'live_search'           => 'yes',
                'wpc_delivery_time_show'=> 'yes',
                'show_item_status'      => 'yes',
                'wpc_btn_text'          => '',
                'customize_btn'         => '',
                'wpc_menu_order'        => 'DESC',
                'wpc_nav_position'        => 'top',
            ], $atts ));


        $style = (isset($style) && $style != '') ? $style : 'style-3';

        $unique_id = md5(md5(microtime()));
        
        $wpc_cat_arr      = explode(',', $wpc_food_categories);

        $wpc_cat_sort_arr = [];
        $i                = 0;
        if (is_array($wpc_cat_arr)) {
            foreach ($wpc_cat_arr as $value) {
                $i++;

                if ($wpc_cat = get_term_by('id', $value, 'product_cat')) {
                    $wpc_get_menu_order = get_term_meta($wpc_cat->term_id, 'wpc_menu_order_priority', true);
                    if ($wpc_get_menu_order == '') {
                        $wpc_cat_sort_arr[$i] = $value;
                    } else {
                        $wpc_cat_sort_arr[$wpc_get_menu_order] = $value;
                    }
                }
            }
        }
        
        // sort category list
        if ( !empty($wpc_cat_sort_arr) ) {
            ksort($wpc_cat_sort_arr);
        }
        // render template
        include \Wpcafe_Pro::core_dir(). "shortcodes/views/food-menu/food-tab.php";

        return ob_get_clean();
    }


    /**
     * Food menu slider
     */
    public function food_menu_slider($atts){
        if (!class_exists('Woocommerce')) {
            return;
        }

        ob_start();

        $atts    = Wpc_Utilities::replace_qoute( $atts );

        // shortcode option
        $atts = extract(shortcode_atts(
            [
                'wpc_food_categories'   => '',
                'style'                 => 'style-3',
                'wpc_menu_count'        => 5,
                'wpc_slider_count'      => 3,
                'show_thumbnail'        => "yes",
                'wpc_menu_order'        => "DESC",
                'wpc_cart_button'       => 'yes',
                'title_link_show'       => 'yes',
                'wpc_show_desc'         => 'yes',
                'wpc_desc_limit'        => '15',
                'wpc_delivery_time_show' => 'yes',
                'wpc_slider_nav_show'   => 'yes',
                'wpc_slider_dot_show'   => 'yes',
                'show_item_status'      => 'yes',
                'wpc_btn_text'          => '',
                'customize_btn'         => '',
                'wpc_auto_play'         => 'yes',
            ],
            $atts
        ));
        
        $settings = $this->settings_obj;
        $wpc_pro_standarad_off   = isset($settings['wpc_pro_discount_standarad_off_message'])  ? sanitize_text_field($settings['wpc_pro_discount_standarad_off_message']) : '';
        if ($wpc_pro_standarad_off !== '') {
        ?>
            <div class="wpc_pro_standard_offer_message"><?php echo esc_html($wpc_pro_standarad_off); ?></div>
        <?php
        }

        $style = (isset($style) && $style != '') ? $style : 'style-1';
        $unique_id = md5(md5(microtime()));
        $wpc_cat_arr      = explode(',', $wpc_food_categories);
		$no_desc_class = ($wpc_show_desc != 'yes') ? 'wpc-no-desc' : '';

        $products_args = array(
            'post_type'     => 'product',
            'no_of_product' => $wpc_menu_count,
            'wpc_cat'       => $wpc_cat_arr,
            'order'         => $wpc_menu_order
        );
        $products = Wpc_Utilities::product_query( $products_args );
        
        ?>
        <div class="wpc-menu-slider-shortcode <?php echo esc_attr($no_desc_class); ?>">
            <?php include \Wpcafe_Pro::plugin_dir() . "/widgets/food-menu-slider/style/{$style}.php"; ?>
        </div>

        <?php
        return ob_get_clean();
    }


    /**
     * Food menu slider
     */
    public function food_menu_loadmore($atts){
        if (!class_exists('Woocommerce')) {
            return;
        }

        ob_start();

        $atts    = Wpc_Utilities::replace_qoute( $atts );

        $settings = $this->settings_obj;
        $wpc_pro_standarad_off   = isset($settings['wpc_pro_discount_standarad_off_message'])  ? sanitize_text_field($settings['wpc_pro_discount_standarad_off_message']) : '';
        if ($wpc_pro_standarad_off !== '') {
        ?>
            <div class="wpc_pro_standard_offer_message"><?php echo esc_html($wpc_pro_standarad_off); ?></div>
        <?php
        }
        // shortcode option
        $atts = extract(shortcode_atts(
            [
                'wpc_food_categories'   => '',
                'style'                 => 'style-1',
                'no_of_product'        => 5,
                'show_thumbnail'        => "yes",
                'wpc_menu_order'        => "DESC",
                'wpc_cart_button'       => 'yes',
                'title_link_show'       => 'yes',
                'wpc_show_desc'         => 'yes',
                'wpc_desc_limit'        => '15',
                'wpc_delivery_time_show'=> 'yes',
                'show_item_status'      => 'yes',
                'wpc_btn_text'          => '',
                'customize_btn'         => '',
            ],
            $atts
        ));
        $unique_id = md5(md5(microtime()));
        $wpc_cat_arr    = explode(',', $wpc_food_categories);

        $products_args = array(
            'post_type'     => 'product',
            'no_of_product' => $no_of_product,
            'wpc_cat'       => $wpc_cat_arr,
            'order'         => $wpc_menu_order
        );
        $products 		= Wpc_Utilities::product_query( $products_args );

        $total_product_args = array(
            'post_type'     => 'product',
            'no_of_product' => $no_of_product,
            'wpc_cat'       => $wpc_cat_arr,
            'order'         => $wpc_menu_order,
            'total_count'   => true,
        );
		$total_products = count( Wpc_Utilities::product_query( $total_product_args ) );

        $widget_data = [
			'show_item_status'           => $show_item_status,
			'show_thumbnail'             => $show_thumbnail,
			'wpc_cart_button_show'       => $wpc_cart_button,
			'title_link_show'            => $title_link_show,
			'wpc_btn_text'               => $wpc_btn_text,
			'customize_btn'              => $customize_btn,
			'wpc_desc_limit'             => $wpc_desc_limit,
			'wpc_show_desc'              => $wpc_show_desc,
			'order'                      => $wpc_menu_order,
			'wpc_menu_count'             => $no_of_product,
			'cat_id'                     => $wpc_cat_arr,
			'total_post'                 => $total_products,
			'unique_id'					 =>	$unique_id,
            'wpc_delivery_time_show'     => 'yes',
        ];
        
        $ajax_json_data 			= json_encode( $widget_data );

        $style = (isset($style) && $style != '') ? $style : 'style-1';
        ?>
        
        <div class="wpc-nav-shortcode">
            <div class="wpc-food-wrapper wpc-menu-list-style1 wpc-widget-wrapper wpc-loadmore-wrap<?php echo esc_attr($unique_id); ?> main_wrapper_<?php echo esc_attr($unique_id); ?>" data-id="<?php echo esc_attr($unique_id); ?>">
                <?php
                    if (is_array( $products ) && count( $products )>0 ) {
                        include \Wpcafe_Pro::plugin_dir() . "/widgets/food-menu-loadmore/style/{$style}.php";
                    }
                ?>
            </div>
            <div class="loadmore-section" data-id="<?php echo esc_attr($unique_id); ?>">
                <?php if( $total_products > $no_of_product): ?>
                <div class="loadmore-btn-wrap">
                    <div class="loadmore<?php echo esc_attr($unique_id); ?> wpc-btn" data-json_grid_meta="<?php echo esc_attr($ajax_json_data); ?>" data-paged="<?php echo esc_attr(isset($paged) ? $paged : 2); ?>">
                        <?php echo esc_html__('Load More...', 'wpcafe-pro'); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Food menu tab with slider
     */
    public function food_menu_tab_with_slider($atts){
        if (!class_exists('Woocommerce')) {
            return;
        }

        ob_start();
        $settings = $this->settings_obj;
        $atts    = Wpc_Utilities::replace_qoute( $atts );

        // shortcode option
        $atts = extract(shortcode_atts(
            [
                'wpc_food_categories' => '',
                'style' => 'style-3',
                'no_of_product' => 5,
                'wpc_slider_count' => 3,
                'show_thumbnail' => "yes",
                'wpc_cart_button' => 'yes',
                'title_link_show' => 'yes',
                'wpc_menu_col' => '6',
                'wpc_show_desc' => 'yes',
                'wpc_desc_limit' => '15',
                'wpc_delivery_time_show' => 'yes',
                'wpc_slider_nav_show' => 'yes',
                'wpc_slider_dot_show' => 'yes',
                'show_item_status' => 'yes',
                'wpc_btn_text' => '',
                'customize_btn' => '',
                'wpc_auto_play' => 'yes',
            ],
            $atts
        ));

        // render template
        include \Wpcafe_Pro::core_dir() ."shortcodes/views/food-menu/food-tab-slider.php";

        return ob_get_clean();
    }

    /**
     * Food menu list block
     */
    public function food_menu_list($atts){
        if (!class_exists('Woocommerce')) { return; }
        ob_start();
        $settings = $this->settings_obj;
        $wpc_pro_standarad_off   = isset($settings['wpc_pro_discount_standarad_off_message'])  ? sanitize_text_field($settings['wpc_pro_discount_standarad_off_message']) : '';
        if ($wpc_pro_standarad_off !== '') {
        ?>
            <div class="wpc_pro_standard_offer_message"><?php echo esc_html($wpc_pro_standarad_off); ?></div>
        <?php
        }

        $atts    = Wpc_Utilities::replace_qoute( $atts );
        
        // shortcode option
        $atts = extract(shortcode_atts(
            [
                'style'                 => 'style-1',
                'wpc_food_categories'   => '',
                'show_thumbnail'        => 'yes',
                'no_of_product'         => 5,
                'live_search'           => 'yes',
                'show_item_status'      => 'yes',
                'title_link_show'       => 'yes',
                'wpc_cart_button'       => 'yes',
                'wpc_delivery_time_show' => 'yes',
                'wpc_show_desc'         => 'yes',
                'customize_btn'         => '',
                'wpc_btn_text'          => '',
                'wpc_desc_limit'        => '10',
                'wpc_menu_order'        => 'DESC',
                'wpc_menu_col'          => '2'

            ],$atts));

            if ( $wpc_menu_col == 6 ) {
                $wpc_menu_col = 2;
            } else
            if ( $wpc_menu_col == 5 ) {
                $wpc_menu_col = 2;
            } else
            if ( $wpc_menu_col == 4 ) {
                $wpc_menu_col = 3;
            } else
            if ( $wpc_menu_col == 3 ) {
                $wpc_menu_col = 4;
            } else
            if ( $wpc_menu_col == 2 ) {
                $wpc_menu_col = 6;
            } else
            if ( $wpc_menu_col == 1 ) {
                $wpc_menu_col = 12;
            }

        $wpc_cat_arr   = explode(',', $wpc_food_categories);
        $unique_id = md5(md5(microtime()));

        // render template
        include \Wpcafe_Pro::core_dir(). "shortcodes/views/food-menu/food-list.php";

        return ob_get_clean();
    }

    /**
     * Food menu category list
     */
    public function food_menu_category_list($atts){
        if (!class_exists('Woocommerce')) { return; }
        ob_start();
        // shortcode option
        $atts = extract(shortcode_atts(
            [ 'style' => 'style-1',
							'wpc_food_categories' => '',
              'category_limit' => 5,
              'hide_empty' => 'no',
              'show_count' => "yes",
              'grid_column' => 4],
            $atts ));

        // category sorting from backend
        $categories_id   = explode(',', $wpc_food_categories);

        // change grid column value
        switch ( $grid_column ) {
            case "4":
                $grid_column = 3;
                break;
            case "3":
                $grid_column = 4;
                break;
            case "2":
                $grid_column = 6;
                break;
            case 1:
                $grid_column = 12;
                break;
            default:
                $grid_column = 2;
                break;
        }

        // change hide empty value
        switch ( $hide_empty ) {
            case 'yes':
                $hide_empty = 1;
                break;
            case 'no':
                $hide_empty = 0;
                break;
            default:
                $hide_empty = 0;
                break;
        }

        // render template
        include \Wpcafe_Pro::core_dir() . "shortcodes/views/food-menu/food-categories.php";

        return ob_get_clean();
    }

    /**
     * Food menu location list
     */
    public function food_menu_location_list($atts){
        if (!class_exists('Woocommerce')) { return; }
        ob_start();
        // shortcode option
        $atts = extract(shortcode_atts(['style' => 'style-5', 'location_ids' => '', 'location_limit' => 5, 'hide_empty' => 'no', 'show_count' => "yes", 'grid_column' => 4],
            $atts
        ));

        // change grid column value
        switch ( $grid_column ) {
            case "4":
                $grid_column = 3;
                break;
            case "3":
                $grid_column = 4;
                break;
            case "2":
                $grid_column = 6;
                break;
            case 1:
                $grid_column = 12;
                break;
            default:
                $grid_column = 2;
                break;
        }

        // category sorting from backend
        $categories_id   = explode(',', $location_ids);

        // change hide empty value
        switch ( $hide_empty ) {
            case 'yes':
                $hide_empty = 1;
                break;
            case 'no':
                $hide_empty = 0;
                break;
            default:
                $hide_empty = 0;
                break;
        }

        // render template
        include \Wpcafe_Pro::core_dir() . "shortcodes/views/food-menu/food-location.php";

        return ob_get_clean();
    }

    /**
     * Show business hour function
     */
    public function business_hour($atts){
        ob_start();
        extract(shortcode_atts(['all_days_schedule' => 'yes'], $atts));
        $settings = $this->settings_obj;
        // render template
        include \Wpcafe_Pro::core_dir() . "shortcodes/views/food-menu/business-hour.php";

        return ob_get_clean();
    }

    /**
     * Place reservation shortcode to any page function
     */
    public function place_shortcode($content){
        if (!is_admin()) {
            global $post;
            if (!$post instanceof WP_Post) return $content;
            $settings = $this->settings_obj;
            if (is_array($settings)) {
                if (isset($settings['wpc_reservation_form_display_page']) && $settings['wpc_reservation_form_display_page'] !== '') {
                    if ($post->post_type == 'page' && $post->ID == $settings['wpc_reservation_form_display_page']) {
                        $shortcode = "[wpc_reservation_form wpc_image_url='']";
                        $content = $content . $shortcode;
                    }
                }
            }
        }

        return $content;
    }

    /**
     * Reservation form common markup
     *
     * @param [type] $atts
     * @return void
     */
    public function common_reservation_markup($atts , $data = [] ){

        // getting setting object
        $settings = $this->settings_obj;
        $result_data        = apply_filters('wpcafe/action/reservation_template', $atts);

        $from_field_label   = esc_html__("From", 'wpcafe-pro');
        $to_field_label = esc_html__("To", 'wpcafe-pro');
        $show_form_field = "on";
        $show_to_field = "on";
        $from_to_column     = "wpc-col-md-6"; $required_from_field = 'on'; $required_to_field = 'on';$view = 'yes'; 
        $column_lg          = 'wpc-col-lg-6'; $column_md = 'wpc-col-md-12';
        $booking_button_text = esc_html__("Confirm Booking","wpcafe-pro");
        $cancel_button_text  = esc_html__("Request Cancellation","wpcafe-pro");

        // getting style number
        $style = !empty( $atts['form_style'] ) ? $atts['form_style'] : 1 ;

        if ( is_array($result_data) ) {
            if ( isset( $result_data['calender_view']) ) {
                $view      = $result_data['calender_view'];
                $column_lg = isset($result_data['column_lg']) ? $result_data['column_lg'] : 'wpc-col-lg-6';
                $column_md = isset($result_data['column_md']) ? $result_data['column_md'] : 'wpc-col-md-12';
            }

            if(isset( $result_data['from_field_label'] ) && isset( $result_data['to_field_label'] )  ) {
                $from_field_label   =  $result_data['from_field_label'];
                $to_field_label     =  $result_data['to_field_label'];
                $show_form_field    =  $result_data['show_form_field'];
                $show_to_field      =  $result_data['show_to_field'];
                $required_from_field=  $result_data['required_from_field'];
                $required_to_field  =  $result_data['required_to_field'];

                if(!( $show_form_field =='on' && $show_to_field =='on' ) ){
                    $from_to_column = "wpc-col-md-12";
                }

                $booking_button_text = $result_data['form_booking_button'];
                $cancel_button_text = $result_data['form_cancell_button'];
            }
        }

        $seat_capacity = isset( $result_data['seat_capacity'] ) ? $result_data['seat_capacity'] : 20;
        $booking_status = isset( $result_data['booking_status'] ) ? $result_data['booking_status']: '';

        // All form settings for reservation
        if ( file_exists( \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/form-settings.php" ) ) {
            include \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/form-settings.php";
        }

        if( empty( $atts['reservation_food'] ) ){
            $reservation_form_template = \Wpcafe_Pro::plugin_dir() . "/core/shortcodes/views/reservation/style-$style.php";
        }else{
            if( $style == "style-2" ){
                $reservation_form_template = \Wpcafe_Pro::plugin_dir() . "/core/shortcodes/views/reservation/style-1.php";
            }else {
                $reservation_form_template = \Wpcafe_Pro::plugin_dir() . "/core/shortcodes/views/reservation/reservation-with-food/$style.php";
            }
        }

        extract( shortcode_atts( [
            'fluent_crm_webhook'  => '',
        ], $atts ));


        ?>
        <div class="reservation_section">
            <?php
            if( file_exists( $reservation_form_template ) ){
                include $reservation_form_template;
            }
            ?>
        </div>
        <?php
    }


    /**
     * Create a shortcode to render the reservation form.
     * Print the reservation form's HTML code.
     */
    public function reservation_form_pro($atts){

        ob_start();
        $this->common_reservation_markup( $atts );

        return ob_get_clean();
    }

    public function reservation_with_food( $atts ){
        ob_start();
        $atts['reservation_food']   = 'yes';
        $atts['calender_view']      = 'no';

        $this->common_reservation_markup( $atts );

        return ob_get_clean();
    }

    /**
     * Variation content html
     */
    public function variation_option_content($product, $customize_btn = '', $unique_id = '' , $customization_icon='wpcafe-customize'){
        // variation price
        Wpc_Utilities::get_variation_price($product);
		$wrap = '';
		// show customize button
		if ($product->is_in_stock() == true && $product->get_type() == 'variable' || $product->get_type() == 'grouped') {
        $wrap='
        <div class="wpc-menu-footer">
            <div class="wpc-customize-btn">
				<div class="wpc-add-to-cart">
					<a href="#" id="product_popup'.$product->get_id() . $unique_id.'" class="customize_button"
					data-product_id='.intval(($product->get_id())).'>
						'.$customize_btn.'
						<i class="'.$customization_icon.'"></i>
					</a>
				</div>
            </div>
        </div>
		';
		}
		return $wrap;
    }

    /**
     * Simple content html
     */
    public function simple_product_content($product, $customize_btn = '', $unique_id = '' , $customization_icon='wpcafe-customize' ){
		$wrapper = '';
		// show customize button
		if ($product->is_in_stock() == true && $product->get_type() == 'simple' ) {
			$wrapper ='
				<div class="wpc-menu-footer">
					<div class="wpc-customize-btn">
						<div class="wpc-add-to-cart">
							<a href="#" id="product_popup'.$product->get_id() . $unique_id.'" class="customize_button" data-product_id='.intval(($product->get_id())).'>
								'.$customize_btn.'
								<i class="'.$customization_icon.'"></i>
							</a>
						</div>
					</div>
				</div>
		';
		}
		return $wrapper;
    }

    // add body class
    public function wpc_body_classes($classes){
        $settings       = $this->settings_obj;
        if (isset($settings['wpc_pro_woocommerce_override_css']) && $settings['wpc_pro_woocommerce_override_css'] == 'on') {
            $classes[] = 'woocomerce-layout-override-enable';
        }

        return $classes;
    }

    
    /**
     * generate markup for visual table selection shortcode
     *
     * @param [type] $atts
     * @return void
     */
    public function visual_reservation_form($atts){
        ob_start();

        $settings = $this->settings_obj;
        // get pro feature values
        $result_data = apply_filters('wpcafe/action/reservation_template', $atts );

        $from_field_label = esc_html__('From', 'wpcafe-pro');
        $to_field_label = esc_html__('To', 'wpcafe-pro');
        $show_form_field = "on";
        $show_to_field = "on";
        $from_to_column = "wpc-col-md-6";
        $required_from_field = 'on';
        $required_to_field = 'on';
        $view = 'yes';
        $column_lg = 'wpc-col-lg-6';
        $column_md = 'wpc-col-md-12';
        $first_booking_button   = esc_html__("Book a table","wpcafe-pro");
        $booking_button_text    = esc_html__("Confirm Booking","wpcafe-pro");
        $cancel_button_text     = esc_html__("Request Cancellation" ,"wpcafe-pro");

        if ( is_array($result_data) ) {
            if ( isset( $result_data['calender_view']) ) {
                $view      = $result_data['calender_view'];
                $column_lg = isset($result_data['column_lg']) ? $result_data['column_lg'] : 'wpc-col-lg-6';
                $column_md = isset($result_data['column_md']) ? $result_data['column_md'] : 'wpc-col-md-12';
            }
            if(isset( $result_data['from_field_label'] ) && isset( $result_data['to_field_label'] )  ) {
                $from_field_label   =  $result_data['from_field_label'];
                $to_field_label     =  $result_data['to_field_label'];
                $show_form_field    =  $result_data['show_form_field'];
                $show_to_field      =  $result_data['show_to_field'];
                $required_from_field=  $result_data['required_from_field'];
                $required_to_field  =  $result_data['required_to_field'];

                if(!( $show_form_field =='on' && $show_to_field =='on' ) ){
                    $from_to_column = "wpc-col-md-12";
                }

                $first_booking_button   = $result_data['first_booking_button'];
                $booking_button_text    = $result_data['form_booking_button'];
                $cancel_button_text     = $result_data['form_cancell_button'];
            }
        }

        $seat_capacity  = isset( $result_data['seat_capacity'] ) ? $result_data['seat_capacity'] : 20;

        $booking_status = isset( $result_data['booking_status'] ) ? $result_data['booking_status']: '';

        $reservation_form_template = \Wpcafe_Pro::core_dir() . "shortcodes/views/reservation/reservation-visual-selection.php";

        // All form settings for reservation
        if ( file_exists( \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/form-settings.php" ) ) {
            include_once \Wpcafe::plugin_dir() . "core/shortcodes/views/reservation/form-settings.php";
        }

        ?>
        <div class="reservation_section">
            <?php
            $is_multi_slot = ( isset($settings['reser_multi_schedule'] ) && $settings['reser_multi_schedule'] == 'on' )  ? true : false;

            $schedule_slug = Table_Layout_Helper::retrieve_slug_name();
            $all_mappings  = Table_Layout_Helper::get_settings_option( 'wpc_table_layout' );
            $has_mapping   = ( isset( $all_mappings[$schedule_slug]['chairQty'] ) && absint( $all_mappings[$schedule_slug]['chairQty'] ) > 0 )  ? true : false;
            
            $show_table_layout = ( !$is_multi_slot && $has_mapping ) ? true : false;
            if ( $show_table_layout && file_exists( $reservation_form_template ) ) {
                include_once $reservation_form_template;
            } else {
                echo esc_html__( 'Visual table reservations (mapping) work on single-slot reservations only.', 'wpcafe-pro' );
            }
            ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * generate markup for pickup delivery search shortcode
     *
     * @param [type] $atts
     * @return void
     */
    public function pickup_delivery_search($atts){
        ob_start();

        $tag_type = isset($_GET['tag_type'])? $_GET['tag_type'] : '';
        $picup_delivery_id = isset($_GET['picup_delivery_id'])? $_GET['picup_delivery_id'] : '';
        $location_id = isset($_GET['location_id'])? $_GET['location_id'] : '';
        $template = 'list_template';
        $template_path = \Wpcafe_Pro::plugin_dir() . "/widgets/pickup-delivery/style/style-2.php";
        $checked_pic = ($tag_type == 'pickup')? 'checked=checked': '';
        $checked_del = ($tag_type == 'delivery')? 'checked=checked': '';

        $pickup_data = get_term_by('name', 'Pickup', 'product_tag');
        $pickup = $pickup_data->term_id;
        $delivery_data = get_term_by('name', 'Delivery', 'product_tag');
        $delivery = $delivery_data->term_id;
        ?>
        <div class="wpc-row">
            <div class="wpc-col-lg-3">
                <div class="wpc-ajax-sidebar-filter">
                    <div class="data_section_filter" data-total_product="10" data-template_name="<?php echo esc_attr($template); ?>" data-template_path="<?php echo esc_attr($template_path); ?>" data-wpc_tag="<?php echo esc_attr($picup_delivery_id); ?>" data-wpc_location="<?php echo esc_attr($location_id); ?>" data-tag_type="<?php echo esc_attr($tag_type); ?>"></div>

                    <div class="widget product-filter-widget">
                        <div class="widget-content">
                            <input class="wpc-input-field product-filter-search" placeholder="<?php esc_attr_e('Search', 'wpcafe-pro')?>" />
                            <button type="button" value="product_search" class="search" data-wpc_tag="<?php echo esc_attr($picup_delivery_id); ?>">
                                <svg width="16" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352c79.5 0 144-64.5 144-144s-64.5-144-144-144S64 128.5 64 208s64.5 144 144 144z"/></svg>
                            </button>
                        </div>
                        <div class="search-result-products">
                            <ul class="get_product_search"></ul>
                            <?php 
                                if ( file_exists( \Wpcafe_Pro::plugin_dir() . "/widgets/pickup-delivery/style/product-data.php" )) {
                                        include \Wpcafe_Pro::plugin_dir() . "/widgets/pickup-delivery/style/product-data.php";
                                }
                            ?>
                        </div>
                        
                    </div>

                    <div class="sidebar-filter-wrapper">
                        <div class="widget pickup-delivery-widget">
                            <h4 class="widget-title"><?php echo esc_html__('What Type Want', 'wpcafe-pro'); ?></h4>
                            <div class="widget-content">
                                <input type="radio" class="pickup-delivery-filter" id="pickup" name="pickup_delivery" data-pickup-delivery="<?php echo esc_attr($pickup); ?>" data-location_id="<?php echo esc_attr($location_id); ?>" value="pickup" <?php echo esc_attr($checked_pic); ?>>
                                <label for="pickup"><?php echo esc_html__('Pickup', 'wpcafe-pro'); ?></label><br>
                                <input type="radio" class="pickup-delivery-filter" id="delivery" name="pickup_delivery" data-pickup-delivery="<?php echo esc_attr($delivery); ?>" data-location_id="<?php echo esc_attr($location_id); ?>" value="delivery" <?php echo esc_attr($checked_del); ?>>
                                <label for="delivery"><?php echo esc_html__('Delivery', 'wpcafe-pro'); ?></label><br>
                            </div>
                        </div>
    
                        <div class="widget etn-category-widget">
                            <h4 class="widget-title"><?php echo esc_html__('Categories', 'wpcafe-pro'); ?></h4>
                            <div class="widget-content">
                                <?php 
                                $args = array(
                                    'hide_empty'  => 0,
                                    'taxonomy'    => 'product_cat',
                                    'hierarchical' => 1,
                                );
    
                                $categories = get_terms($args);
                                if (!empty($categories)) {
                                    foreach ($categories as $category) {
                                        ?>
                                        <input type="radio" class="product-category-filter" id="category_filter_<?php echo esc_attr($category->term_id); ?>" name="product_category_filter" data-location_id="<?php echo esc_attr($location_id); ?>" data-wpc_tag="<?php echo esc_attr($picup_delivery_id); ?>" value="<?php echo esc_attr($category->term_id); ?>" >
                                        <label for="category_filter_<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?></label><br/>
                                        <?php
                                    }
                                }
                                ?>                            
                                <br>
                            </div>
                        </div>

                        <div class="widget price-range-slider">
                            <?php 
                            $args = array(
                                'post_type'             => 'product',
                                'post_status'           => 'publish',
                                'posts_per_page'        => -1,
                                'order'                 => 'DESC',
                            );
                            $posts = get_posts( $args );
                            $price = array();
                            $min_pri = '';
                            $max_pri = '';
                            $average_price = '';
                            $currency_pos = get_option( 'woocommerce_currency_pos' );
							$currency_symbol_html = get_woocommerce_currency_symbol();
                            $min_price = '';
                            $max_price = '';
                            if ( !empty($posts) ) {
                                foreach ( $posts as $post ){
                                    $product = wc_get_product( $post->ID );
                                    $price[] = $product->get_regular_price();
                                }
                                $min_pri = min($price) == "" ? 2: min($price);
                                $max_pri = max($price);
                                $average_price = $max_pri/2;

                                if ( $currency_pos == 'left' ) {
                                    $min_price = $currency_symbol_html . $min_pri;
                                    $max_price = $currency_symbol_html . $max_pri;
                                } elseif ( $currency_pos == 'right' ) {
                                    $min_price = $min_pri . $currency_symbol_html;
                                    $max_price = $max_pri . $currency_symbol_html;
                                }
                            }

                            ?>
                            <h4 class="widget-title"><?php echo esc_html__('Price Range', 'wpcafe-pro'); ?></h4>
                            <div class="range-slider">
                                <input name="product_price_min_max" type="range" data-minprice="<?php echo esc_attr($min_pri); ?>" min="<?php echo esc_attr($min_pri); ?>" max="<?php echo esc_attr($max_pri); ?>" value="<?php echo esc_attr($average_price); ?>" class="product-price-min-max">
                            </div>
                            <div class="price-and-reset">
                                <div class="price">
                                    <span><?php echo esc_html($min_price); ?></span>-<span class="last-price" data-currency="<?php echo esc_attr($currency_symbol_html); ?>" data-currency-pos="<?php echo esc_attr($currency_pos); ?>"><?php echo esc_html($max_price); ?></span>
                                </div>
                                <button class="btn reset_btn_price_filter" data-default-price="<?php echo esc_attr($max_price); ?>"><?php echo esc_html__('Reset', 'wpcafe-pro'); ?></button>
                            </div>
                        </div>
                    </div>

                    
                </div>          
            </div>
            <div class="wpc-col-lg-9 wpc-nav-shortcode">         
                <div class="main_wrapper_ main_wrapper_list search-item-wrapper">
                    <div class="list_template_list list-item-wrapper wpc-row">                        
                    </div>
					<?php 
						if ( file_exists( \Wpcafe_Pro::plugin_dir() . "/widgets/pickup-delivery/style/style-2.php" )) {
							include \Wpcafe_Pro::plugin_dir() . "/widgets/pickup-delivery/style/style-2.php";
						}
                    ?>
                </div>
            </div>
      </div>
      <?php

      return ob_get_clean();

    }
    
}
