<?php

namespace WpCafe_Pro\Core\Modules\Food_Delivery\Order_Type;

use WpCafe_Pro\Utils\Utilities as Pro_Utilities;
use WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;
use WpCafe\Core\Base\Wpc_Settings_Field as Settings;

defined( "ABSPATH" ) || exit;

class Order_Type{
  use \WpCafe_Pro\Traits\Singleton;
  private $settings_obj = null ;
  public $wpc_message   = '';
  public $wpc_cart_css  = '';

  /**
   * call hooks
   */

  public function init(){
      $this->settings_obj = Settings::instance()->get_settings_option();

      // Set the price with WooCommerce compatibility
      if (class_exists('WooCommerce')) {

          add_action("woocommerce_widget_shopping_cart_before_buttons", [$this, "handle_mini_cart_buttons_before"]);

          // add order time manage feature in checkout page
		  if ( !empty( wp_get_theme()->name ) && 'Divi' !== wp_get_theme()->name) {
			add_action('woocommerce_checkout_before_customer_details', [$this, 'order_time_manage']);
		  } else {
			add_action('woocommerce_before_checkout_billing_form', [$this, 'order_time_manage']);
		  }
		  
          // save order type
          add_action('woocommerce_checkout_create_order', [$this, 'update_order_time_update_meta'], 10, 2);

          if (isset($this->settings_obj['wpc_pro_allow_order_for']) && $this->settings_obj['wpc_pro_allow_order_for'] == 'Both') {
              remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
              add_action( 'woocommerce_widget_shopping_cart_buttons',[$this,'mini_cart_add_class'], 20 );
          }
          if( !isset($_GET['wpc_mobile_thankyou'])){
                add_action( "wpcafe/after_thankyou", [$this,  "wpc_pro_after_thankyou"] );
          }
          add_action( 'woocommerce_checkout_process', [$this, 'order_type_form_validation']);
          add_action( 'woocommerce_email_before_order_table', [$this, 'wpc_pro_add_order_type_details'], 10, 2 );

      }
  }

		/**
		* Check if pickup date is enable
		*/
		public function enable_pickup_date() {
			return (!empty( $this->settings_obj['wpc_pro_allow_pickup_date'] ) && $this->settings_obj['wpc_pro_allow_pickup_date'] == "on" ) ? true : false ;
		}

		/**
		* Check if pickup time is enable
		*/
		public function enable_pickup_time() {
			return (!empty( $this->settings_obj['wpc_pro_allow_pickup_time'] ) && $this->settings_obj['wpc_pro_allow_pickup_time'] == "on" ) ? true : false ;
		}

		/**
		* Check if delivery date is enable
		*/
		public function enable_delivery_date() {
			return (!empty( $this->settings_obj['wpc_pro_allow_delivery_date'] ) && $this->settings_obj['wpc_pro_allow_delivery_date'] == "on" ) ? true : false ;
		}

		/**
		* Check if delivery time is enable
		*/
		public function enable_delivery_time() {
			return (!empty( $this->settings_obj['wpc_pro_allow_delivery_time'] ) && $this->settings_obj['wpc_pro_allow_delivery_time'] == "on" ) ? true : false ;
		}

    /**
     * Order, pickup, delivery time manager function for checkout
     */
    public function order_time_manage(){
      // Show in checkout
      $wpc_pro_settings   = $this->settings_obj;
      if ( !empty( $wpc_pro_settings['wpc_pro_allow_order_for'] ) && !class_exists('Wpcafe_Multivendor'))   {
          include \Wpcafe_Pro::core_dir() . "modules/food-delivery/order-type/order-time.php";
      }
    }

    /**
     * Update order time data
     */
    public function update_order_time_update_meta($order){
        //check for validation
        $post_arr = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if (Pro_Utilities::data_validation_check_arr($post_arr)) {
            $checked_data = Pro_Utilities::get_order_type();
            $checked_data = array_values($checked_data);
            foreach ($post_arr as $key => $value) {
                if (in_array($key, $checked_data) && $key !== '') {
                    $order->update_meta_data($key, $value);
                }
            }
        }
    }

    /**
     * Add class to disable button
     */
    public function mini_cart_add_class(){
      echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="button checkout wc-forward">' . esc_html__( 'Checkout', 'wpcafe-pro' ) . '</a>';
    }


    /**
     * Add delivery / pickup in  Mini-Cart
     */
    public function handle_mini_cart_buttons_before(){
      ?>
        <script type="text/javascript">

            jQuery( function($){
                $( '.mini-cart-quantity-wrapper' ).on( 'click', 'button.plus, button.minus', function() {

                    var qty = $( this ).parent( '.quantity' ).find( '.qty' );
                    var val = parseFloat( qty.val() );
                    var max = parseFloat( qty.attr( 'max' ) );
                    var min = parseFloat( qty.attr( 'min' ) );
                    var step = parseFloat( qty.attr( 'step' ) );

                    if ( $( this ).is( '.plus' ) ) {
                        if ( max && ( max <= val ) ) {
                            qty.val( max );
                        } else {
                            qty.val( val + step );
                        }
                    } else if ( min && ( min >= val ) ) {
                        qty.val( min );
                    } else if ( val > 1 ) {
                        qty.val( val - step );
                    }

                    qty.trigger( 'change' );
                } );

                var get_reserv_detials = localStorage.getItem('wpc_reservation_details');
                if ( typeof get_reserv_detials !== "undefined" && get_reserv_detials !== null ) {
                    $(".wpc_pro_order_time").remove();
                }

                // cross sell product in minicart
                var swiper = new Swiper(".wpc-cross-sells", {
                    navigation: {
                        nextEl: ".swiper-btn-next",
                        prevEl: ".swiper-btn-prev",
                    },
                    autoplay: false,
                    spaceBetween: 0,
                    pagination: true
                });

                // Minicart Cupon from

                $('.showcoupon').on('click',function(){
                    $('.coupon_from_wrap').slideToggle(400);
                });

				if ( $(".minus").length == 0 ) {
						$('.mini-cart-quantity-wrapper .quantity').prepend('<button type="button" class="minus" >-</button>')
				}
				if ( $(".plus").length == 0 ) {
						$('.mini-cart-quantity-wrapper .quantity').append('<button type="button" class="plus" >+</button>')
				}

            });
        </script>
      <?php

      if( !class_exists('Wpcafe_Multivendor') ){
          ?>
          <div class="wpc_pro_order_time">
              <?php
              $settings = $this->settings_obj;

              if ( isset($settings['wpc_pro_allow_order_for']) && Pro_Utilities::data_validation_check($settings['wpc_pro_allow_order_for'])) {
                  $minicart_input_field = "";
                  $delivery_text = esc_html__("Delivery", "wpcafe-pro");
                  $pickup_text = esc_html__("Pickup", "wpcafe-pro");
                      if ($settings['wpc_pro_allow_order_for'] == 'Delivery') {
                          $minicart_input_field .= '<div class="wpc-field-wrap"> <label for="wpc_pro_order_time_delivary"><input type="radio" name="wpc_pro_order_time" checked="checked" id="wpc_pro_order_time_delivary" class="wpc-minicart-condition-input" value="Delivery"/>' .  __( 'Delivery', 'wpcafe-pro' ) . '<span class="dot-shadow"></span></label></div>';
                      } elseif ($settings['wpc_pro_allow_order_for'] == 'Pickup') {
                          $minicart_input_field .= '<div class="wpc-field-wrap"><label for="wpc_pro_order_time_pickup"><input type="radio" name="wpc_pro_order_time" checked="checked"  class="wpc-minicart-condition-input" id="wpc_pro_order_time_pickup" value="Pickup"/> ' . __( 'Pickup', 'wpcafe-pro' ) . '<span class="dot-shadow"></span></label></div>';
                      } elseif ($settings['wpc_pro_allow_order_for'] == 'Both') {

							// check pickup date / time enable
							$enable_pickup_date = $this->enable_pickup_date() ;
							// check delivery date / time enable
							$enable_delivery_date = $this->enable_delivery_date();

							if ( $enable_delivery_date  ) {
								$minicart_input_field .= '<div class="wpc-field-wrap"><label for="wpc_pro_order_time_delivary"><input type="radio" name="wpc_pro_order_time" class="wpc-minicart-condition-input" id="wpc_pro_order_time_delivary" value="Delivery"/> ' .  __( 'Delivery', 'wpcafe-pro' ) . '<span class="dot-shadow"></span></label></div>';
							}
							if ( $enable_pickup_date ) {
								$minicart_input_field .= '<div class="wpc-field-wrap"><label for="wpc_pro_order_time_pickup"><input type="radio" name="wpc_pro_order_time" class="wpc-minicart-condition-input" id="wpc_pro_order_time_pickup" value="Pickup"/> ' .  __( 'Pickup', 'wpcafe-pro' ) . '<span class="dot-shadow"></span></label></div>';
							}

                      }
						$minicart_input_field .= '<input type="hidden" name="is_order_time_selected" id="wpc-minicart-condition-value-holder" value=""/>';
						$minicart_input_field .= '<input type="hidden" name="order_type" class="order_type" value="'. esc_html( $settings['wpc_pro_allow_order_for'] ).'"/>';
                  ?>
                  <div class="minicart-condition-parent">
                      <?php echo Wpc_Utilities::wpc_kses($minicart_input_field); ?>
                  </div>
              <?php
              }
              ?>
          </div>
          <?php
      }

    }

     /**
     * After Thank You Page Hook called
     *
     * @since  1.0.0
     * @return void
     */
    public function wpc_pro_after_thankyou(){
        wp_enqueue_script( 'pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js', ['jquery'], \Wpcafe_Pro::version(), true );
        wp_enqueue_script( 'html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js', ['jquery'], \Wpcafe_Pro::version(), true );

        ?>
        <div class="extra-buttons">
            <button class="wpc-btn wpc-primary" onclick="wpc_pro_pirnt_content_area('woocommerce-order');"><?php echo esc_html__( "Print", "wpcafe-pro" ); ?></button>
            <a class="wpc-btn wpc-primary download-invoice-pdf" href="javascript:wpc_pro_download_pdf()" ><?php echo esc_html__( "Download", "wpcafe-pro" ); ?></a>
        </div>
        <?php
    }

    /**
     * Add order type and details
     */
    public function wpc_pro_add_order_type_details( $order, $is_admin_email ){
        $wpc_pro_order_id = $order->get_id();
        
        \WpCafe_Pro\Core\Template\Food_Menu::instance()->order_type_markup( $wpc_pro_order_id );
    }

    /**
     * Woocommerce checkout order type form validation message
     */
    public function order_type_form_validation(){

        if ( !empty( $_POST['wpc_pro_order_time']  ) ) {

            $settings = Settings::instance()->get_settings_option();
            // if any validation errors
            switch ( $_POST['wpc_pro_order_time'] ) {
                case 'Delivery':
                    // date
                    if ( isset( $settings['wpc_pro_allow_delivery_date'] ) && $settings['wpc_pro_allow_delivery_date'] == "on" && empty( $_POST['wpc_pro_delivery_date'] ) ) {
                        wc_add_notice( esc_html__( 'Please select delivery date', 'wpcafe-pro' ), 'error' );
                    } 
                    // time 
                    if ( isset( $settings['wpc_pro_allow_delivery_time'] ) && $settings['wpc_pro_allow_delivery_time'] == "on" && empty( $_POST['wpc_pro_delivery_time'] ) ) {
                        wc_add_notice( esc_html__( 'Please select delivery time', 'wpcafe-pro' ), 'error' );
                    }
                    break;
                case 'Pickup':
                    // date
                    if ( isset( $settings['wpc_pro_allow_pickup_date'] ) && $settings['wpc_pro_allow_pickup_date'] == "on" && empty( $_POST['wpc_pro_pickup_date'] ) ) {
                        wc_add_notice( esc_html__( 'Please select pickup date', 'wpcafe-pro' ), 'error' );
                    }
                    if ( isset( $settings['wpc_pro_allow_pickup_time'] ) && $settings['wpc_pro_allow_pickup_time'] == "on" && empty( $_POST['wpc_pro_pickup_time'] ) ) {
                        wc_add_notice( esc_html__( 'Please select pickup time', 'wpcafe-pro' ), 'error' );
                    }
                    break;
                default:
                    break;
            } 
        }
    } 

}