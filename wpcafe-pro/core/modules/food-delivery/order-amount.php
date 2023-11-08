<?php

namespace WpCafe_Pro\Core\Modules\Food_Delivery;

defined( "ABSPATH" ) || exit;

use WpCafe_Pro\Traits\Singleton;

class Order_Amount{

    use Singleton;

    private $message;
    private $disable ;

    public function init(){
        
        if ( !class_exists('Woocommerce') ) {
            return true;
        }

        // disable checkout on cart based on amount
        add_action( 'wp_footer', [ $this, 'add_disable_checkout_script' ] );
        add_action( 'wp', [ $this, 'set_cookie_on_cart' ] );

        add_action('woocommerce_after_checkout_validation', [ $this,'amount_check_validation_checkout']);

    }

    /**
     * Get minimum order amount from settings
     */
    public function get_min_order_amount(){
        $settings = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
        return !empty($settings['min_order_amount']) ? floatval( $settings['min_order_amount'] ) : 0 ;
    }

    /**
     * Set cookie for cart
     */
    public function set_cookie_on_cart() {
		if ( is_cart() ) {
			$this->set_cookie_if_has_notices();
		}
	}

    function set_cookie_if_has_notices() {
        $min_order_amount = $this->get_min_order_amount();
		if ( floatval(WC()->cart->subtotal) < $min_order_amount ) {
			setcookie( 'wpc_min_order', true, time() + 31556926 );
            $this->message = sprintf(__("Your current amount is %s,
                 You need to add at least %s to place order", "wpcafe-pro"),  
                \WpCafe\Core\Modules\Food_Menu\Hooks::get_price_with_currency_symbol(  WC()->cart->subtotal ),  
                \WpCafe\Core\Modules\Food_Menu\Hooks::get_price_with_currency_symbol( $min_order_amount ) );
            $this->get_output_notices_from_position( 'cart' );

		} else {
			setcookie( 'wpc_min_order' );
		}
	}

    /**
     * Check amount validation before checkout
     */
    public function amount_check_validation_checkout(){
        $min_order_amount   = $this->get_min_order_amount();
        $subtotal           = floatval(WC()->cart->subtotal);
  
        if ( floatval($subtotal) <  $min_order_amount ) {
            $message = sprintf(__("Your current amount is %s,
                 You need to add at least %s to place order", "wpcafe-pro"), 
                \WpCafe\Core\Modules\Food_Menu\Hooks::get_price_with_currency_symbol( $subtotal ), 
                \WpCafe\Core\Modules\Food_Menu\Hooks::get_price_with_currency_symbol(  $min_order_amount ) );
            wc_add_notice( __( $message , 'wpcafe-pro' ), 'error' );
       }
    }

  
    /**
     * Show output message
     */
    public function get_output_notices_from_position( $page ){
        $params = array( 'cart' => 'woocommerce_before_cart');
        if ( isset( $params[ $page ] ) ) {            
            add_action( $params[ $page ] , [ $this , 'custom_notice'] ) ;
        } else {
            return false;
        }
    }

    public function custom_notice(){
        wc_print_notice( $this->message , 'error' );
    }

    /**
     * Disable button based on amount
     */
    public function add_disable_checkout_script(){
        if ( !is_cart() ) {
            return;
        }
        ?>
        <style type="text/css">
         .disable-checkout-btn {
                pointer-events: none;
                color: #fff !important;
                background-color:rgba(0, 0, 0, 0.5) !important;
            }
        </style>
        <script>
            jQuery(document).ready(function () {
                // remove shipping charge if order type is pickup
                var order_type = localStorage.getItem('wpcpro_delivery');
                Cookies.set('wpcpro_delivery', order_type );

                if ( order_type !== null && order_type == "Pickup" ) {
                    var ship_class = jQuery(".shipping");
                    if (ship_class.length>0  ) {
                        ship_class.css("display","none");
                    }
                }

                // minimum order amount

				function get_cookie_data(name) {
					let value = `; ${document.cookie}`;
					let parts = value.split(`; ${name}=`);
					if (parts.length === 2) return parts.pop().split(';').shift();
				}

                // disable cart button 
                function proceed_action() {
                    let proceed_button      = jQuery('.wc-proceed-to-checkout > a');
                    let mini_cart_cart      = jQuery('.woocommerce-mini-cart__buttons > a');
                    let woocommerce_info    = jQuery(".woocommerce-info");
  
                    if (typeof proceed_button === 'undefined' || proceed_button.length <= 0) {
                        return;
                    } 
                    if (get_cookie_data('wpc_min_order')) {
						proceed_button.addClass('disable-checkout-btn');
						mini_cart_cart.addClass('disable-checkout-btn');
					} else {
						proceed_button.removeClass('disable-checkout-btn');
						mini_cart_cart.removeClass('disable-checkout-btn');
					}
                   
                }

                jQuery(document.body).on('updated_cart_totals', proceed_action );
                proceed_action();


            });
        </script>
        <?php
    }

}