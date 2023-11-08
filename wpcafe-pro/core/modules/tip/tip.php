<?php

namespace WpCafe_Pro\Core\Modules\Tip;

use WpCafe_Pro\Utils\Utilities as Pro_Utilities;
use WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;
use WpCafe\Core\Base\Wpc_Settings_Field as Settings;

defined( "ABSPATH" ) || exit;

class Tip{
    use \WpCafe_Pro\Traits\Singleton;

    private $settings = null ;

    /**
    * call hooks
    *
    * @return void
    */
    public function init(){
        $this->settings = Settings::instance()->get_settings_option();
    
        if ( class_exists('WooCommerce') && isset( $this->settings['wpc_pro_tip_enable'] ) && $this->settings['wpc_pro_tip_enable'] == 'on' ) {
            add_action( 'wp_enqueue_scripts', [$this, 'frontend_enqueue_assets'] );

            add_action( 'woocommerce_after_order_notes', [ $this, 'tip_form_html' ] );
            add_action( 'woocommerce_before_cart_totals', [ $this, 'tip_form_html' ] );
            
            add_action( 'wp_ajax_add_tip', [ $this, 'add_tip_to_session' ] );
            add_action( 'wp_ajax_nopriv_add_tip', [ $this, 'add_tip_to_session' ] );

            add_action( 'wp_ajax_remove_tip', [ $this, 'remove_tip_from_session' ] );
            add_action( 'wp_ajax_nopriv_remove_tip', [ $this, 'remove_tip_from_session' ] );

            add_action( 'woocommerce_cart_calculate_fees', [ $this, 'calculate_tip_as_fee' ] );
            add_action( 'woocommerce_new_order', [ $this, 'remove_tip_when_order_placed' ] );
        }
    }

    /**
     * enqueue all css and js with localization data
     *
     * @return void
     */
    public function frontend_enqueue_assets(){
        wp_enqueue_style( 'wpc-pro-tip', \Wpcafe_Pro::core_url() . 'modules/tip/assets/css/style.css', [], \Wpcafe_Pro::version(), 'all' );
        wp_enqueue_script( 'wpc-pro-tip', \Wpcafe_Pro::core_url() . 'modules/tip/assets/js/script.js', ['jquery'], \Wpcafe_Pro::version(), true );

        $translation_data = [
            'ajax_url'         => admin_url( 'admin-ajax.php' ),
            'add_tip_nonce'    => wp_create_nonce( 'add_tip_nonce_value' ),
            'remove_tip_nonce' => wp_create_nonce( 'remove_tip_nonce_value' ),
        ];
         
        wp_localize_script( 'wpc-pro-tip', 'wpc_pro_tip_obj', $translation_data);
    }

    /**
     * tip adding html view
     *
     * @return void
     */
    public function tip_form_html() {
        include_once \Wpcafe_Pro::core_dir() . "modules/tip/tip-view.php";
    }

    /**
    * Store the tip to the session
    *
    * @return void
    **/
    public function add_tip_to_session() {

        $status_code = 0;
        $message     = '';
                
        if ( ! wp_verify_nonce( $_POST['security'], 'add_tip_nonce_value' ) ) {
            $message = esc_html__( 'Nonce is not valid! Please try again.', 'wpcafe-pro' );
        } else {
            $tip_selected_type     = sanitize_text_field( $_POST['tip_selected_type'] );
            $tip_fixed_amount      = floatval( sanitize_text_field( $_POST['tip_fixed_amount'] ) );
            $tip_percentage_amount = absint( sanitize_text_field( $_POST['tip_percentage_amount'] ) );
    
            $tip_added = 0;
            if ( $tip_selected_type == 'tip_fixed' && $tip_fixed_amount > 0 ) {
                $tip_added = 1;
            }
    
            if ( $tip_selected_type == 'tip_percentage' && $tip_percentage_amount > 0 ) {
                $tip_added = 1;
            }
    
            if ( $tip_added == 1 ) {
                $tip_data = [
                    'tip_added'             => $tip_added,
                    'tip_selected_type'     => $tip_selected_type,
                    'tip_fixed_amount'      => $tip_fixed_amount,
                    'tip_percentage_amount' => $tip_percentage_amount,
                ];
        
                $wc_session = WC()->session;
                $wc_session->set( 'wpc_pro_tip', $tip_data );

                $status_code = 1;
                $message     = esc_html__( 'Tip is added successfully.', 'wpcafe-pro' );
            } else {
                $message = esc_html__( 'There is some error in tip type selection and corresponding tip value! Please try again.', 'wpcafe-pro' );
            }
        }

        $response = [
            'status_code' => $status_code,
            'message'     => $message,
        ];

        echo json_encode( $response );
        wp_die();
    }

    /**
    * Remove the tip from the session
    *
    * @return void
    **/
    function remove_tip_from_session() {
        $status_code = 0;
        $message     = '';

        if ( ! wp_verify_nonce( $_POST['security'], 'remove_tip_nonce_value' ) ) {
            $message = esc_html__( 'Nonce is not valid! Please try again.', 'wpcafe-pro' );
        } else {
            $wc_session = WC()->session;
            $wc_session->__unset( 'wpc_pro_tip' );
            $status_code = 1;
            $message     = esc_html__( 'Tip is removed successfully.', 'wpcafe-pro' );
        }

        $response = [
            'status_code' => $status_code,
            'message'     => $message,
        ];

        echo json_encode( $response );
        wp_die();
    }

    /**
    * Add tip action
    *
    * @return void
    **/
    function calculate_tip_as_fee() {

        $wc_session = WC()->session;
        $tip = $wc_session->get('wpc_pro_tip');
    
        if( $tip ) {  
            $tip_title  = esc_html__( 'Tip', 'wpcafe-pro' );
            $tip_amount = 0;   

            switch( $tip['tip_selected_type'] ) { 
                case 'tip_fixed':
                    $tip_amount = $tip['tip_fixed_amount'];
                break;
                case 'tip_percentage':
                    $subtotal   = WC()->cart->get_subtotal();
                    $tip_amount = ( $tip['tip_percentage_amount'] / 100 ) * $subtotal;
                    $tip_title  .= '(' .$tip['tip_percentage_amount']. '%)';
                break;
            }

            if ( $tip_amount > 0 ) {
                WC()->cart->add_fee( $tip_title, $tip_amount );
            }
        }

    }

    /**
    * Remove tip when an order is placed
    *
    * @return void
    **/
    function remove_tip_when_order_placed( $order_id ) {

        if( ! is_admin() ) {
            $wc_session = WC()->session;
            $wc_session->__unset( 'wpc_pro_tip' );
        }
    }

}