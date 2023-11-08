<?php
/**
 * This class is firing all ajax related action
 */

namespace WpCafe_Pro\Core\Action;
use WpCafe_Pro\Core\Shortcodes\Hook as Hook;
use WpCafe_Pro\Utils\Utilities;

defined( "ABSPATH" ) || exit;

use WC_Coupon;
use WC_AJAX;
use WpCafe\Utils\Wpc_Utilities;

Class Ajax_Action {

    use \WpCafe\Traits\Wpc_Singleton;

    public function init() {
        $callback = ['load_posts_by_ajax','live_search_ajax',
        'variaion_product_popup_content','update_cart_with_quantiy','checking_cart_items','wpc_apply_coupon_code','wpc_remove_coupon_code', 'get_all_locations'];

        if ( !empty( $callback ) ) {
            foreach ($callback as $key => $value) {
                add_action( 'wp_ajax_'.$value , [$this , $value ] );
                add_action( 'wp_ajax_nopriv_'.$value , [$this , $value ] );
            }
        }

    }

    /**
     * find all location filtered to lat, lng, radius
     *
     * @return array
     */
    public function get_all_locations(){
        if ( ! wp_verify_nonce( $_POST['security'], 'location_map_nonce' ) ) {
            $msg = esc_html__( 'Nonce is not valid! Please try again.', 'wpcafe-pro' );

            $response = [
                'status_code'   => 403,
                'message'       => [ $msg ],
                'data'          => [],
            ];

            wp_send_json_error( $response );
        } else {
            $lat            = floatval( $_POST['lat'] );
            $lng            = floatval( $_POST['lng'] );
            $radius         = absint( $_POST['radius'] );
            $redirect_url   = esc_url_raw( $_POST['redirect_url'] );

            $all_locations       = $this->query_all_locations( $lat, $lng, $radius );
            $locations_html      = '';
            $locations_html_data = [];
            $msg = esc_html__( 'ok', 'wpcafe-pro' );

            if ( !empty( $all_locations ) ) {
                foreach ( $all_locations as $index => $location ) {
                    $term_id        = $location->term_id;
                    $address        = get_term_meta( $term_id, 'address', true );
                    $email          = get_term_meta( $term_id, 'location_email', true );

                    $location_url       = $redirect_url . '?location=' . $term_id;
                    $location_direction = '';
                    if ( !empty( $location->lat ) ) {
                        $location_direction = '<a href="http://maps.google.com/maps?saddr=' . $lat. ',' . $lng . '&daddr=' . $location->lat . ',' . $location->lng . '" target="_blank">' . esc_html__( 'Get Directions', 'wpcafe-pro' ) . '</a>';
                    }

                    $image_id  = get_term_meta( $term_id, 'location_image', true );
                    $loc_image = \Wpcafe_Pro::assets_url() . 'images/placeholder.png';
                    if ( ! empty( $image_id ) ) {
                        $loc_image = wp_get_attachment_image_src( $image_id, 'thumbnail' );
                        if ( is_array( $loc_image ) ) {
                            $loc_image = $loc_image[0];
                        }
                    }
                    ob_start();
                ?>

                <div class='wpc-location-item wpc-location-item-<?php echo esc_attr($index+1); ?>'>
                    <div class="wpc-location-item-image">
                        <a href="<?php echo esc_attr( $location_url ); ?>"><img src="<?php echo esc_url( $loc_image ); ?>" alt="<?php echo esc_html( $location->name ); ?>"></a>
                    </div>
                    <div class="wpc-location-item-content">
                        <h3 class="wpc-location-item-name">
                            <a href="<?php echo esc_attr( $location_url ); ?>" target="_blank">
                                <?php echo esc_html( $location->name ); ?>
                            </a>
                        </h3>
                        <?php if( $address !=='' ) : ?>
                        <p class="wpc-location-item-address">
                            <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.89994 10.1463C9.27879 10.1463 10.3966 9.02855 10.3966 7.6497C10.3966 6.27085 9.27879 5.15308 7.89994 5.15308C6.5211 5.15308 5.40332 6.27085 5.40332 7.6497C5.40332 9.02855 6.5211 10.1463 7.89994 10.1463Z" stroke="#5F6A78" stroke-width="1.5"/>
                            <path d="M1.19425 6.1933C2.77065 -0.736432 13.0372 -0.72843 14.6056 6.2013C15.5258 10.2663 12.9972 13.7072 10.7806 15.8357C9.17225 17.3881 6.62761 17.3881 5.01121 15.8357C2.80266 13.7072 0.274023 10.2583 1.19425 6.1933Z" stroke="#5F6A78" stroke-width="1.5"/>
                            </svg>
                            <?php echo esc_html( $address ); ?>
                        </p>
                        <?php endif; ?>
						<?php if( $email !=='' ) : ?>
                        <p class="wpc-location-item-email">
                            <svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.3529 15.5H5.11765C2.64706 15.5 1 14.2647 1 11.3824V5.61765C1 2.73529 2.64706 1.5 5.11765 1.5H13.3529C15.8235 1.5 17.4706 2.73529 17.4706 5.61765V11.3824C17.4706 14.2647 15.8235 15.5 13.3529 15.5Z" stroke="#5F6A78" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.3535 6.0293L10.7758 8.08812C9.92757 8.76341 8.53581 8.76341 7.68757 8.08812L5.11816 6.0293" stroke="#5F6A78" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <?php echo esc_html__( 'Email: ', 'wpcafe-pro' ) . esc_html( $email ); ?>
                        </p>
                        <?php endif; ?>
                        <p class="wpc-location-item-direction">
                            <?php echo Wpc_Utilities::wpc_render($location_direction); ?>
                        </p>
                    </div>
                </div>
                    
                <?php
                    $locations_html_data[ $index ] = ob_get_clean();
                }
            } else {
                $msg = "<p class='location-not-found'>";
                $msg .=  esc_html__( 'No store found in this location. Please try another location', 'wpcafe-pro');
                $msg .= "</p>";
            }
    
            if ( !empty( $locations_html_data ) ) {
                $locations_html = "<div class='wpc-location-item-wrapper'><h4 class='location-area-title'>";
                $locations_html .= esc_html__('Nearby stores:', 'wpcafe-pro');
                $locations_html .=  "</h4>";
                $locations_html .= join( '', $locations_html_data );
                $locations_html .= "</div>";
            }

            $response = [
                'status_code'   => 200,
                'message'       => [ $msg ],
                'data'          => [
                    'locations'             => $all_locations,
                    'locations_html'        => $locations_html,
                    'locations_html_data'   => $locations_html_data,
                ]
            ];
            
            wp_send_json_success( $response );
        }

        exit;
    }

    /**
     * helper function to db query to get locations
     *
     * @param [float] $lat
     * @param [float] $lng
     * @param [int] $radius
     * @return array
     */
    public function query_all_locations($lat, $lng, $radius) {
        global $wpdb;

        $locations = [];

        $sorting = '';
        if( empty( $sorting ) ) {
            $sorting = 'distance';
        }

        $max_row        = 100;
        $distance_unit  = 6371; // 6371 : 3959

        $sql = "SELECT terms.term_id, terms.name, terms.slug,
                    term_lat.meta_value AS lat, term_lng.meta_value AS lng,
                    ( %d
                        * acos(
                            cos( radians(%s) )
                            * cos( radians(term_lat.meta_value) ) * cos( radians(term_lng.meta_value) - radians(%s) )
                            + sin( radians(%s) )
                            * sin( radians(term_lat.meta_value) )
                        )
                    ) AS distance
                    FROM $wpdb->terms AS terms
                    INNER JOIN $wpdb->termmeta AS term_lat ON term_lat.term_id = terms.term_id AND term_lat.meta_key = 'location_latitude'
                    INNER JOIN $wpdb->termmeta AS term_lng ON term_lng.term_id = terms.term_id AND term_lng.meta_key = 'location_longitude'
                    GROUP BY lat HAVING distance < %d ORDER BY " . $sorting . " LIMIT 0, %d";

        $params = [
            $distance_unit,
            $lat, $lng, $lat, $radius,
            $max_row,
        ];

        $locations = $wpdb->get_results( $wpdb->prepare( $sql, $params ) );

        return $locations;
    }

    /**
     * Variation popup
     */
    public function filter_food_location(){

        $post_arr     = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
        $location     = $post_arr['location'];
        if( $location !== '' ){
            $product_data           = $post_arr['product_data'];
            $show_thumbnail         = $product_data['show_thumbnail'];
            $show_item_status       = $product_data['show_item_status'];
            $wpc_cart_button        = $product_data['wpc_cart_button'];
            $wpc_show_desc          = $product_data['wpc_show_desc'];
            $wpc_delivery_time_show = $product_data['wpc_delivery_time_show'];
            $wpc_desc_limit         = $product_data['wpc_desc_limit'];
            $wpc_btn_text           = $product_data['wpc_btn_text'];
            $customize_btn          = $product_data['customize_btn'];
            $unique_id              = $product_data['unique_id'];
            $col                    = 'wpc-col-md-'.$product_data['wpc_menu_col'];
            $class                  = '';

            $args = array(
                'order'         => 'DESC',
                'wpc_cat'       => [$location],
                'taxonomy'      => 'wpcafe_location',
            );

            $products = Wpc_Utilities::product_query ( $args );

            ?>
            <div class='wpc-food-wrapper wpc-menu-list-style1'>
                <?php

                if ( is_array( $products ) && count( $products )>0 ) {
                    foreach ($products as $product) {
                        $get_price      = Utilities::food_discount_price( $product->get_id() );
                        $price          = !is_null( $get_price ) ? '<del>'. wc_price( $get_price['main_price'] ) .'</del>' . $get_price['price_afer_discount'] : $product->get_price_html(); // true for getting tax price 
                        $current_tags   = get_the_terms( $product->get_id() , 'product_tag');
                        $permalink      = get_the_permalink($product->get_id() ) ;
                        $discount       = Hook::instance()->check_discount_of_product( $product->get_id() );

                        include \Wpcafe_Pro::plugin_dir() . "/widgets/content-style/content-1.php";

                    }
                }else{
                    ?>
                        <div><?php esc_html_e( 'No menu found' , 'wpcafe-pro' )?></div>
                    <?php
                }
                ?>
            </div>
            <?php
        }

        wp_die();
    }

    /**
     * update quantity in mini-cart
     */
    public function update_cart_with_quantiy(){
        $response = [ 'code' => 401 , 'message' => array(
            'error' => esc_html__( 'Something went wrong', 'wpcafe-pro' ) ) ];

        $post_arr = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
        
        if ( isset( $post_arr['quantity'] ) &&  $post_arr['quantity'] !=="" ) {
            
            $quantity = (double) $post_arr['quantity'];

            if ( !is_numeric( $quantity ) || $quantity < 0 || ! $post_arr['cart_item_key'] ) {
                wp_send_json( $response  );
            }

            // update product quantity
            if ( 0 === (int) $quantity ) {
                $action = WC()->cart->remove_cart_item( $post_arr['cart_item_key'] );
            } else {
                $action = WC()->cart->set_quantity(  $post_arr['cart_item_key'], $quantity );
            }

            // update minicart fragment
            if ( $action ) {
                WC_AJAX::get_refreshed_fragments();
            }
        }

        wp_die();
    }

    /**
     * function for load-more
     */
    public function load_posts_by_ajax() {

        //check for valid nonce
        $all_data         = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
        $settings         = $all_data['ajax_json_data'];
        $unique_id        = isset( $settings['unique_id'] ) ? $settings['unique_id'] : 0;
        $show_item_status = $settings['show_item_status'];
        $wpc_cart_button  = $settings['wpc_cart_button_show'];
        $title_link_show  = $settings['title_link_show'];
        $wpc_btn_text     = $settings['wpc_btn_text'];
        $wpc_desc_limit   = $settings['wpc_desc_limit'];
        $wpc_show_desc    = $settings['wpc_show_desc'];
        $customize_btn    = $settings["customize_btn"];
        $show_thumbnail   = $settings['show_thumbnail'];
        $cat_id           = $settings['cat_id'];
        $wpc_menu_count   = $settings['wpc_menu_count'];
        $order            = $settings['order'];
        $wpc_delivery_time_show      	= $settings["wpc_delivery_time_show"];

        $col              = ( $show_thumbnail == 'yes' ) ? 'wpc-col-md-9' : 'wpc-col-md-12';
        $permalink        = (  ( $title_link_show == 'yes' ) ? get_the_permalink() : '' );
        $class            = (  ( $title_link_show == 'yes' ) ? '' : 'wpc-no-link' );
        $paged            = isset( $all_data['paged'] ) ? $all_data['paged'] : 1;

        $ajax_product_args = array(
            'post_type'     => 'product',
            'no_of_product' => $wpc_menu_count,
            'wpc_cat'       => $cat_id,
            'order'         => $order,
            'page'          => $paged
        );
        $products = Wpc_Utilities::product_query( $ajax_product_args );

        if ( (is_array( $products ) && count( $products )>0 ) ):
            include \Wpcafe_Pro::plugin_dir() . "/widgets/food-menu-loadmore/style/style-1.php";
        endif;
        wp_die();
    }

    /**
     * Variation popup
     */
    public function variaion_product_popup_content(){

        if ( "variation_popup" == sanitize_text_field( $_POST['wpc_action'] ) ) {
            $post_arr     = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
            $product_id   = $post_arr['product_id'];
            if( $product_id !== ''){
                \WpCafe_Pro\Core\Shortcodes\Hook::instance()->variation_popup_template( $product_id );
                wp_send_json_success( ['success'=>1,'message'=>'success','data'=>json_encode(ob_get_clean())] );
            }
        }
        wp_die();
    }

    /**
     * Live search in product title
     *
     * @return void
     */
    public function live_search_ajax(){
        $search_html = '';
        $post_arr               = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
        $wpc_cart_button        = $post_arr['wpc_cart_button'];
        // widget data
        if( $post_arr['widget_arr'] ){
            $widget_arr             = $post_arr['widget_arr'];
            include \Wpcafe_Pro::plugin_dir() . "/widgets/filter-search/filter-search-data.php";
        }
        $class_id="";
        
        if( $post_arr['search_value'] !=="" ){
            ob_start();
            ?><ul class="get_result"><?php
            
            $search_data    = trim( $post_arr['search_value'] , " " ); 

            $get_cat_id = get_term_by('name', $search_data, 'product_cat');

            $wpc_menu_order == '' ? 'DESC' : $wpc_menu_order;
            $wpc_menu_order == '' ? 'DESC' : $wpc_menu_order;
            
            if($get_cat_id && in_array( $get_cat_id->term_id, $post_arr['cat_arr'] )){
                if( $post_arr['template_name'] !== "list_template"){
                    $class_id       = $get_cat_id->term_id;
                }
                $wpc_cat            = [ $get_cat_id->term_id ];

                $products_args = array(
                    'post_type'     => 'product',
                    'no_of_product' => $post_arr['total_product'],
                    'wpc_cat'       => $wpc_cat,
                    'order'         => $wpc_menu_order,
                );
                $products           = Wpc_Utilities::product_query( $products_args );

                $product_search_args = array(
                    'post_type'     => 'product',
                    'no_of_product' => -1,
                    'wpc_cat'       => $wpc_cat,
                    'order'         => $wpc_menu_order,
                );
                $products_search    = Wpc_Utilities::product_query( $product_search_args );
            }else {
                $products_args = array(
                    'post_type'     => 'product',
                    'no_of_product' => $post_arr['total_product'],
                    'wpc_cat'       => $post_arr['cat_arr'],
                    'order'         => $wpc_menu_order,
                    'page'          => null,
                    'total_count'   => false,
                    'search_value'  => $search_data,
                );
                $products           = Wpc_Utilities::product_query(  $products_args );

                $product_search_args = array(
                    'post_type'     => 'product',
                    'no_of_product' => -1,
                    'wpc_cat'       => $post_arr['cat_arr'],
                    'order'         => $wpc_menu_order,
                    'page'          => null,
                    'total_count'   => false,
                    'search_value'  => $search_data,
                );
                $products_search    = Wpc_Utilities::product_query( $product_search_args );
            }
            if( count( $products_search )>0 ){
                foreach ( $products_search as $key => $product) {
                    $select_cat_id = '';
                    $term_list = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'ids'));
                    if( is_array( $term_list ) && count( $term_list ) >0 ){
                        $select_cat_id = array_values($term_list)[0];
                        if( $post_arr['template_name'] !== "list_template"){
                            $class_id       = $select_cat_id;
                        }
                    }
                    ?>
                    <li data-cat_id="<?php echo intval($select_cat_id) ?>"> <?php esc_html_e($product->get_name());?> </li>
                    <?php
                }
            }else {
            ?><li><?php  esc_html_e( "No data found", "wpcafe-pro" ) ?></li><?php
            }
            ?></ul><?php
            $search_html = ob_get_clean();
        }else {
            $products_args = array(
                'post_type'     => 'product',
                'no_of_product' => $post_arr['total_product'],
                'wpc_cat'       => $post_arr['cat_arr'] ,
                'order'         => $wpc_menu_order,
                'page'          => null,
                'total_count'   => false,
                'search_value'  => null,
            );
            $products       = Wpc_Utilities::product_query( $products_args );

            if($post_arr['template_name'] == "tab_template" ){
                if(is_array( $post_arr['cat_arr'] ) && count($post_arr['cat_arr']) ){
                    $class_id = $post_arr['cat_arr'][0];
                }else{
                    $class_id = 0;
                }
            }
        }

        ob_start();
        if( $post_arr['template_path'] !== ''){
            include $post_arr['template_path'];
        }
        $content = ob_get_clean();
        wp_send_json_success( ['success'=>1,'message'=>'success','data'=> [ 'search_html'=>$search_html , 'content'=>$content , 'template_name' => $post_arr['template_name'] , 'cat_id' => $class_id ] ] );
        wp_die();
    }


    /**
     * Apply coupon
     */
    public function wpc_apply_coupon_code(){
        $coupon_code = isset( $_POST["coupon_code"] ) ? $_POST["coupon_code"] : '';
        WC()->cart->apply_coupon($coupon_code);
        ob_start();

        ?>
        <div class="widget_shopping_cart_content">
            <?php
            if(file_exists(\Wpcafe::core_dir().'modules/mini-cart/views/mini-cart-template.php')){
                include_once \Wpcafe::core_dir().'modules/mini-cart/views/mini-cart-template.php';
            }
            ?>
        </div>
        <?php
      $coupon_html =  ob_get_clean();

      echo Wpc_Utilities::wpc_render($coupon_html);

      wp_die();
    }

    /**
     * Remove coupon
     */
    public function wpc_remove_coupon_code(){
        $coupon_code = isset( $_POST["coupon_code"] ) ? $_POST["coupon_code"] : '';
        WC()->cart->remove_coupon($coupon_code);  

        $coupon = new WC_Coupon($coupon_code);
        $coupon_amount = wc_format_decimal( $coupon->get_amount(), 2 );
        $new_amount    = wc_format_decimal( WC()->cart->total + $coupon->get_amount(), 2 );
        ob_start();
        ?>
        <div class="widget_shopping_cart_content">

            <?php
            if(file_exists(\Wpcafe::core_dir().'modules/mini-cart/views/mini-cart-template.php')){
                include_once \Wpcafe::core_dir().'modules/mini-cart/views/mini-cart-template.php';
            }
            ?>
        </div>
        <?php
      $coupon_html =  ob_get_clean();

      wp_send_json_success([ 'html'=>$coupon_html, 'new_amount' => $new_amount ]);

      wp_die();
    }
}
