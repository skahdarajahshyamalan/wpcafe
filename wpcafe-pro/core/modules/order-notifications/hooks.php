<?php

namespace WpCafe_Pro\Core\Modules\Order_Notifications;

use WpCafe\Utils\Wpc_Utilities;
use Wpcafe_Pro;

defined('ABSPATH') || exit;

class Hooks {

    use \WpCafe_Pro\Traits\Singleton;

    public function init(){
        add_filter( 'heartbeat_received', array( $this, 'heartbeat_received' ), 10, 2 );
    }

    /**
     * Received send data
     */
    public function heartbeat_received( $response , $data ) {
        
        if ( !isset( $data['wpc_pro_heartbeat'] ) || 'live_notify' !== $data['wpc_pro_heartbeat'] ) {
			return $response;
		}

        $orders_data = self::get_last_order_details();

		$response['notify_data'] = array(
			'last_order_id' => self::get_last_order_id(),
            'popup'         => $orders_data['popup'],
            'rows'          => $orders_data['rows']
		);
        
		return $response;
    }
 

    /**
     * Check nonce of ajax call
     */
    public function check_nonce( $nonce_param , $nonce, $post ){
        $wpc_pro_secured = Wpc_Utilities::is_secured( $nonce_param, $nonce , $post );

        if ( $wpc_pro_secured == false ) {
            $response = ['status_code' => 500, 'message' => [ esc_html__('Something is wrong','wpcafe-pro') ], 'data' => []];
            wp_send_json_error( $response );
        }

        return $wpc_pro_secured;
    }

    /**
     * Get order id
     *
     * @return void
     */
    public static function get_last_order_id() {

        if ( !class_exists('WooCommerce') ) {
            return -1;
        }

		$orders = wc_get_orders( array(
			'limit' => 1,
		) );

		if ( empty( $orders ) ) {
			return 0;
		}
        
		return $orders[0]->get_id();
	}

    /**
     * Get order details
     *
     * @return void
     */
    public static function get_last_order_details() {
        $order_html = ['rows' => '' , 'popup'=>'']; 
        $order_data = [];
        if (class_exists('WooCommerce')) {
            $orders = wc_get_orders( array(
                'date_created' => '>' . ( time() - 17 ),
            ) );

            if ( count( $orders )>0 ) {
                foreach ($orders as $key => $order) {
                    $currency_code      = $order->get_currency();
                    $currency_symbol    = get_woocommerce_currency_symbol( $currency_code );
                    $order_data[$key]['order_id']   = $order->get_id();
                    $order_data[$key]['order_date'] = $order->get_date_created();
                    $order_data[$key]['first_name'] = $order->get_billing_first_name();
                    $order_data[$key]['last_name']  = $order->get_billing_last_name();
                    $order_data[$key]['status']     = $order->get_status();
                    $order_data[$key]['total']      = $currency_symbol . $order->get_total();
                }
            }
        } 
        // markup for notification
        $markup_type ='popup';
        ob_start();
        include \Wpcafe_Pro::core_dir() . "modules/order-notifications/order-view.php";
        $order_html['popup'] = ob_get_clean();

        // new order 
        $markup_type ='rows';
        ob_start();
        include \Wpcafe_Pro::core_dir() . "modules/order-notifications/order-view.php";
        $order_html['rows'] = ob_get_clean();

		return $order_html;
	}

}