<?php

namespace WpCafe_Pro\Core\Modules\Reservation_With_Food;

use WpCafe\Utils\Wpc_Utilities as Free_Utilities;
use WpCafe_Pro\Utils\Utilities;

defined('ABSPATH') || exit;

class Hooks {
    use \WpCafe_Pro\Traits\Singleton;

    public function init(){

        // Filter hooks 
        $filter_hooks = array(
            array ( 
                'hook' => 'wpcafe/meta/show_reservation_status_dynamic', 
                'callback'  => 'show_reservation_status_dynamic', 
                'priority'  => 10, 
                'args'      => 2 
            ),
            array ( 
                'hook' => 'wpcafe/notification/send_email_notification', 
                'callback'  => 'send_email_notification_wpcafe_free', 
                'priority'  => 10, 
                'args'      => 2 
            ),
            // food with reservation admin order details 
            array ( 
                'hook' => 'woocommerce_admin_order_totals_after_total', 
                'callback'  => 'order_details_reservation_info', 
                'priority'  => 10, 
                'args'      => 1 
            ),
            array ( 
                'hook'      => 'wpcafe/reservation_with_food/extra_field', 
                'callback'  => 'update_reservation_extra_field', 
                'priority'  => 10, 
                'args'      => 1 
            ),
            array ( 
                'hook'      => 'wpc/reservation_with_food/food_details', 
                'callback'  => 'reservation_with_food_details', 
                'priority'  => 10, 
                'args'      => 1 
            ),
            // reservaion with food
            array ( 
                'hook'      => 'wpcafe/cancellation_form/invoice_eligibility', 
                'callback'  => 'invoice_eligible_for_cancellation', 
                'priority'  => 10, 
                'args'      => 2 
            ),
            array ( 
                'hook'      => 'woocommerce_cod_process_payment_order_status', 
                'callback'  => 'change_cod_payment_order_status', 
                'priority'  => 10, 
                'args'      => 2 
            ),
        );

        if ( !empty($filter_hooks)) {
            foreach ($filter_hooks as $key => $value) {
                add_filter( $value['hook'], [ $this, $value['callback'] ] , $value['priority'] , $value['args'] );
            }
        }

        // Action hooks 
        $actions_hooks = array( 
            array(  
                'hook'       => 'wpcafe/metabox/before_reservation_meta',
                'callback'   => 'before_reservation_meta_show_order_link',
                'priority'   => 10, 
                'args'       => 1 
            ),
            array(  
                'hook'       => 'wpcafe/metabox/before_reservation_meta',
                'callback'   => 'after_reservation_meta_show_order_status',
                'priority'   => 10, 
                'args'       => 1 
            ),
            array(  
                'hook'       => 'woocommerce_order_status_changed',
                'callback'   => 'change_reservation_status_on_order_status_change',
                'priority'   => 10, 
                'args'       => 3 
            ),
            array(  
                'hook'       => 'woocommerce_order_status_changed',
                'callback'   => 'send_reservation_status_on_order_status_change',
                'priority'   => 10, 
                'args'       => 3 
            ),
            // save data from checkout
            array(  
                'hook'       => 'woocommerce_checkout_create_order',
                'callback'   => 'create_order_meta_reservation',
                'priority'   => 10, 
                'args'       => 1 
            ), 
            array(  
                'hook'       => 'woocommerce_email_after_order_table',
                'callback'   => 'woocommerce_email_after_order_table_show_reservation_data',
                'priority'   => 10, 
                'args'       => 4 
            ),
            array(  
                'hook'       => 'woocommerce_after_order_notes',
                'callback'   => 'reservation_details_in_checkout',
                'priority'   => 10, 
                'args'       => 1 
            ),
            array(  
                'hook'       => 'woocommerce_order_details_before_order_table',
                'callback'   => 'reservation_details_in_thankyou',
                'priority'   => 10, 
                'args'       => 1 
            ),
        );

        
        if ( !empty($actions_hooks)) {
            foreach ($actions_hooks as $key => $value) {
                add_action( $value['hook'], [ $this, $value['callback'] ] , $value['priority'] , $value['args'] );
            }
        }

        // save reservation custom post type after checkout
        add_action('wp_footer', [$this,'save_reservation_custom_post']);

        // food with reservation
        add_action('woocommerce_before_cart', [$this,'reservation_details_display'], 20);
        add_action('woocommerce_checkout_before_order_review', [$this,'reservation_details_display'], 10 );
    }

    /**
     * Food details in reservaion details
     */
    public function reservation_with_food_details( $atts )
    {
        if ( !empty( $atts['reservation_food'] ) ) {

        ?>
            <h3 class="reservation-form-title"><?php esc_html_e('Food details','wpcafe-pro')?></h3>
            <table>
                <thead>
                    <tr>
                        <th><?php echo esc_html_e( 'Name','wpcafe-pro');?></th>
                        <th><?php echo esc_html_e( 'Price','wpcafe-pro');?></th>
                    </tr>
                </thead>
                <tbody class="food_details">
                </tbody>
            </table>
        <?php
        }

        return;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $order_id
     * @return void
     */
    public function change_cod_payment_order_status( $order_status, $order ) {
        if (sanitize_text_field(isset($_POST['reservation_details'])) && !empty(sanitize_text_field($_POST['reservation_details']))) {
            return 'on-hold';
        }
    	return $order_status;
    }

    /**
     * Undocumented function
     *
     * @param [type] $order
     * @return void
     */
    public function reservation_details_in_thankyou( $order ){
        $response_data   =  get_post_meta( $order->get_id() , 'reservation_details', true);
        if ( $response_data !=="" ) {
            $reserv_field = \WpCafe_Pro\Core\Modules\Reservation\Hooks::instance()->reservation_field_array();

            ?>
            <div class="food-with-reserv-wrap">
              <h3 class="food_with_reserv"><?php echo esc_html__("Reservation details","wpcafe-pro")?></h3>
                <ul class="food_with_reserv">
                <?php
                    foreach ($reserv_field as $key => $value ) {
                        if ( !empty( $response_data->$key ) && $response_data->$key !=="" ) {
                            ?>
                            <li id="<?php esc_attr_e($key) ?>"> <?php esc_html_e( $value  , 'wpcafe-pro')?>
                                <div class="<?php esc_attr_e( $key ) ?>">
                                    <?php esc_attr_e( $response_data->$key ) ?>
                                </div>
                            </li>
                            <?php
                        }
                    }
                ?>
                </ul>
            </div>
            <?php
        }
    }

    /**
     * food with reservation details in cart
     */
    public function reservation_details_display(){
        $reserv_field = \WpCafe_Pro\Core\Modules\Reservation\Hooks::instance()->reservation_field_array();
        ?>
            <div class="food-with-reserv-wrap">
                <!--  modal  start -->
                <div id="wpc_booking_modal" class="wpc_booking_modal hide_field">
                    <div class="modal-content">
                        <div class="discard-title"><?php echo esc_html__( "Are you sure to discard reservation?","wpcafe-pro");?></div>
                        <div>
                            <span class="no_cancel_food_booking"><?php echo esc_html__("No","wpcafe-pro");?></span>
                            <span class="cancel_food_booking"><?php echo esc_html__("Yes","wpcafe-pro");?></span>
                        </div>
                        <button class="wpc-close wpc-btn"> <i class="wpc-close-btn-icon"><svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.76992 0.7523C2.28177 0.264145 1.49031 0.264145 1.00216 0.7523C0.514001 1.24046 0.514001 2.03191 1.00216 2.52007L6.48223 8.00014L1.00216 13.4802C0.514002 13.9684 0.514001 14.7598 1.00216 15.248C1.49031 15.7361 2.28177 15.7361 2.76992 15.248L8.25 9.76791L13.7301 15.248C14.2182 15.7361 15.0097 15.7361 15.4978 15.248C15.986 14.7598 15.986 13.9684 15.4978 13.4802L10.0178 8.00014L15.4978 2.52007C15.986 2.03191 15.986 1.24046 15.4978 0.7523C15.0097 0.264145 14.2182 0.264145 13.7301 0.7523L8.25 6.23238L2.76992 0.7523Z" fill="#fff"/>
                        </svg></i> </button>
                    </div>
                </div>
                <!--  modal  end -->
                <div class="food_with_reserv">
                    <h3><?php echo esc_html__("Reservation details","wpcafe-pro")?></h3>
                </div>
                <ul class="food_with_reserv">
                <?php
                    foreach ($reserv_field as $key => $value ) {
                        ?>
                        <li id="<?php esc_attr_e('food_'.$key) ?>"> <?php esc_html_e( $value  , 'wpcafe-pro')?>
                            <div class="<?php esc_attr_e('food_'.$key) ?>"></div>
                        </li>
                        <?php
                    }
                ?>
                </ul>
                <div class="cancel_section">
                    <span class="discard_booking">
                        <?php echo esc_html__("Discard Reservation", 'wpcafe-pro'); ?>
                    </span>
                </div>
            </div>
        <?php
    }

    /**
     * Set reservation data in checkout
     */
    public function reservation_details_in_checkout( $checkout  ){
        ?>
        <input type="hidden" class="input-hidden" name="reservation_details" id="reservation_details" value=""/>
        <?php
    }

    public function create_order_meta_reservation( $order ){
        if (sanitize_text_field(isset($_POST['reservation_details'])) && !empty(sanitize_text_field($_POST['reservation_details']))) {

            $order->update_meta_data('reservation_details', json_decode( stripslashes( $_POST['reservation_details'] ) ) );
        }
    }

    /**
     * Save reservation post
     */
    public function save_reservation_custom_post(){
        // Only on order received" (thankyou)
        if( class_exists('Wocommerce') && ! is_wc_endpoint_url('order-received') )
        return; // Exit

        $order_id = absint( get_query_var('order-received') ); // Get the order ID

        if( get_post_type( $order_id ) !== 'shop_order' ) {
            return; // Exit
        }

        $order = wc_get_order( $order_id ); // Get the WC_Order Object

        ?>
        <script type="text/javascript">
            // Once DOM is loaded 
            // Show reservation data in thank you page
            jQuery( function($) { 

                $( document ).ready(function() {
                    jQuery("ul.food_with_reserv").removeAttr("style");
                    
                    // Trigger a jquery action function 
                    save_reservation_after_checkout({
                        'order_id'  :       '<?php echo intval( $order->get_id() ); ?>',
                    });

                 });
            });
        </script>
        <?php
    }

    public function order_details_reservation_info( $order_id ) {
        $response_data          = get_post_meta( $order_id , 'reservation_details', true);
        
        $reserv_field           = \WpCafe_Pro\Core\Modules\Reservation\Hooks::instance()->reservation_field_array();

        if ( !empty( $order_id  ) && !empty( $response_data->reservation_id )) {
        ?>
            <div class="wpc-label-item left_side">
                <a target='_blank' href="<?php echo admin_url( 'post.php?post=' . absint( $response_data->reservation_id  ) . '&action=edit' ) ?>"><?php esc_html_e('Update Reservation Details','wpcafe-pro')?></a>
            </div>

            <h4 class="left_side"><?php esc_html_e( 'Reservation details', 'wpcafe-pro' )?></h4>
            <ul class="order_details food_with_reserv">
                <?php
                    foreach ($reserv_field as $key => $value ) {
                        if ( !empty($response_data->$key)) {
                            ?>
                                <li> 
									<?php esc_html_e( $value  , 'wpcafe-pro')?>
									<div class="<?php esc_attr_e($key) ?>">
									<?php 
										if( $key !== "wpc_booking_date" ){
											esc_html_e( $response_data->$key );
										}else{
											$wpc_date_format =  get_option('date_format');
											esc_html_e(date_i18n($wpc_date_format,strtotime( $response_data->$key ) ));
										} 
									?>
									</div>
								</li>
                            <?php

                        }
                    }
                ?>
            </ul>
            <?php
        }
    }

    /**
     * Update extra field for order details
     */
    public function update_reservation_extra_field( $postarr ){
        // Integration with zapier / pabbly
        $data = \WpCafe_Pro\Core\Modules\Integrations\Zapier::instance()->send_data_to_api( $postarr );

        // update extra field
        $order_id = get_post_meta( $postarr['ID'] , 'order_id' , true );
        
        if ( $order_id !=="" ) {
            $reservation_details = get_post_meta( $order_id , 'reservation_details', true ); 
            $extra_field         = get_post_meta( $postarr['ID'] , 'reserv_extra', true ); 

            $reservation_details->{'wpc_name'}      = $postarr['wpc_name'];
            $reservation_details->{'wpc_email'}     = $postarr['wpc_email'];
            $reservation_details->{'wpc_phone'}     = $postarr['wpc_phone'];
            $reservation_details->{'wpc_message'}   = $postarr['wpc_message'];
            $reservation_details->{'wpc_total_guest'}   = $postarr['wpc_total_guest'];
            $reservation_details->{'wpc_booking_date'}  = $postarr['wpc_booking_date'];
            $reservation_details->{'wpc_from_time'}     = $postarr['wpc_from_time'];
            $reservation_details->{'wpc_to_time'}       = $postarr['wpc_to_time'];
            
            if( !empty( $extra_field[0] ) && !empty( $reservation_details->reserv_extra ) && !empty( $reservation_details->reserv_extra[0] ) ){

                foreach ($extra_field as $key => $value) {
                    $reservation_details->{'reserv_extra_'.$key } = $postarr['reserv_extra_'.$key];
                }
            }
            
            update_post_meta( $order_id , 'reservation_details', $reservation_details ); 
        }

    }

    /**
     * Show Order Link Before Reservation Meta
     */
    public function before_reservation_meta_show_order_link( $post_id ){

        $order_id = get_post_meta( $post_id , 'order_id' , true );
        
        if ( !empty( $order_id  ) ) {
            ?>
            <div class="wpc-label-item wpc-reserv-order-details">
                <span><?php echo esc_html__('This reservation includes food menu order. Click to see ', 'wpcafe-pro');?></span>
                <a target="_blank" href="<?php echo admin_url( 'post.php?post=' . absint( $order_id  ) . '&action=edit' ) ?>"><?php esc_html_e('Food Menu Order Details','wpcafe-pro')?></a>
            </div>
            <?php
        }
    }

    public function after_reservation_meta_show_order_status( $post_id ){
        
        if ( !empty( $post_id  ) ) {
            $order_status = get_post_meta( $post_id, 'wpc_reservation_state', true );
            if ( $order_status !=="" ) {
                ?>
                <div class="wpc-label-item wpc-order-status <?php echo esc_attr($order_status); ?>">
                    <div class="wpc-label"> 
                        <label for="wpc_name"> <?php echo esc_html__('Order Status', 'wpcafe-pro'); ?> </label>
                        <div class="wpcafe-desc">   <?php echo esc_html__("Current reservation status ", 'wpcafe-pro');?> </div>
                    </div>
                    <span>
                    <div class="wpcafe-meta"> 
                        <b><?php echo esc_html( ucfirst( $order_status ) );?></b>
                    </div>
                    </span>
                </div>
                <?php
            }
        }
    }

    public function show_reservation_status_dynamic($post_id, $show){
        $order_id = get_post_meta( $post_id , 'order_id' , true );
        if ( !empty( $order_id  ) ) {
            return false;
        }
        return true;
    }
 
    /**
     * Change Reservation Status On Order Status Change
     */
    function change_reservation_status_on_order_status_change( $order_id, $old_order_status, $new_order_status ) {

        $order_status_array = [
            'pending'   => "pending",
            'on-hold'   => "pending",
            'processing'=> "Processing",
            'completed' => "completed",
            'refunded'  => "completed",
            'cancelled' => "cancelled",
            'failed'    => "cancelled", 
        ];

        $reservation_data = get_post_meta( $order_id , 'reservation_details', true );

        //reservation id exists, update reservation status into object
        if( $reservation_data !=="" && array_key_exists($new_order_status, $order_status_array) ){
            $status                   = $order_status_array[$new_order_status];
            $reservation_data->status = $status;

            update_post_meta( $order_id, 'reservation_details', $reservation_data );

            if( !empty( $reservation_data->reservation_id) ){
                update_post_meta( $reservation_data->reservation_id , 'wpc_reservation_state', $status );
            }
        }
        return;

    }

    /**
     * Send Email for this Invoice or Not
     *
     * @param [type] $send_email
     * @param [type] $invoice_no
     * @return void
     */
    public function send_email_notification_wpcafe_free($send_email, $invoice_no){

        return self::send_reservation_email($invoice_no);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function woocommerce_email_after_order_table_show_reservation_data($order, $sent_to_admin, $plain_text, $email){

        $order_id = $order->get_id();
        $reservation_data = get_post_meta( $order_id, 'reservation_details', true );
        if( !empty( $reservation_data->reservation_id ) ){

            $settings            = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
            $wc_email_stats      = [
                'failed'    => "Failed",     // custom email from our plugin
                'on-hold'   => "Hold",       // custom email from our plugin
                'pending'   => "Pending",    // custom email from our plugin
                'cancelled' => "Cancelled",  // custom email from our plugin
                'processing'=> "Processing", // default woocommerce email
                'completed' => "Completed",  // default woocommerce email
                'refunded'  => "Refunded",   // default woocommerce email
            ];
            $reservation_id      = $reservation_data->reservation_id;

            $body            = !empty($settings['wpc_reservation_with_menu_email']) ? $settings['wpc_reservation_with_menu_email'] : '' ;
            $email_body     = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags( $reservation_id, $body );

            //send reservation details after table
            ?>
            <div class="wpc-food-reservation-email">

                <!-- Send custom email body  -->
                <div class="wpc-food-reservation-email-body">
                    <?php echo esc_html( $email_body ); ?>
                </div>
                <!-- Send reservation details -->
                <div class="wpc-food-reservation-details-body">
                    <h2 class="wpc-food-reservation-heading"><?php echo esc_html__('Above order includes restaurant reservation. Reservation details are as follows: ', 'wpcafe-pro');?></h2>
                    <table style='border:1px solid #dcdcdc; border-radius:5px; padding:12px; width:50%' class="wpc-food-reservation-details">
                        <tr><td><?php echo esc_html__('Invoice No', 'wpcafe-pro');?></td><td><?php echo esc_html( get_post_meta( $reservation_id , 'wpc_reservation_invoice', true ) ); ?></td></tr> 
                        <tr><td><?php echo esc_html__('Name', 'wpcafe-pro');?></td><td><?php echo esc_html($reservation_data->wpc_name);?></td></tr> 
                        <tr><td><?php echo esc_html__('Email', 'wpcafe-pro');?></td><td><?php echo esc_html($reservation_data->wpc_email);?></td></tr> 
                        <tr><td><?php echo esc_html__('Phone', 'wpcafe-pro');?></td><td><?php echo esc_html($reservation_data->wpc_phone);?></td></tr>
                        <tr><td><?php echo esc_html__('Date', 'wpcafe-pro');?></td><td><?php echo esc_html($reservation_data->wpc_booking_date);?></td></tr>
                        <tr><td><?php echo esc_html__('From', 'wpcafe-pro');?></td><td><?php echo esc_html($reservation_data->wpc_from_time);?></td></tr>
                        <tr><td><?php echo esc_html__('To', 'wpcafe-pro');?></td><td><?php echo esc_html($reservation_data->wpc_to_time);?></td></tr>
                        <tr><td><?php echo esc_html__('Guest', 'wpcafe-pro');?></td><td><?php echo esc_html($reservation_data->wpc_guest_count);?></td></tr>
                    </table>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Send Email of Reservation Status On Order Status Change
     * 
     * @since 1.3.7
     *
     * @param [type] $order_id
     * @param [type] $old_order_status
     * @param [type] $new_order_status
     * @return void
     */
    function send_reservation_status_on_order_status_change( $order_id, $old_order_status, $new_order_status ) {

        $order_status_array = array(
            'pending',
            'on-hold',
            'cancelled',
            'failed',);
        
        $reservation_data = get_post_meta( $order_id, 'reservation_details', true );
        if( !empty( $reservation_data->reservation_id ) && in_array($new_order_status, $order_status_array) ){
            //this order includes reservation and order status is eligible for custom email

            $settings            = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
            $reservation_id      = $reservation_data->reservation_id;
            $mail_to             = get_post_meta( $order_id, '_billing_email', true );
            $mail_from           = !empty($settings['wpc_admin_email_address']) ? $settings['wpc_admin_email_address'] : $settings['sender_email_address'];
            $mail_from_name      = !empty($settings['wpc_reply_to_name'])  ? $settings['wpc_reply_to_name']  : esc_html__( "Admin",'wpcafe-pro' );
            $mail_subject        = esc_html__('Reservation status update', 'wpcafe-pro');
            $order_status        = ucwords( strval($new_order_status));
            $default_message     = esc_html__( "Your reservation for invoice No: {$reservation_invoice} is '$order_status'. Please contact to admin for more details. " , 'wpcafe-pro');
            $dynamic_message     = !empty($settings['wpc_reservation_with_menu_email']) ? $settings['wpc_reservation_with_menu_email'] : '' ;
            $body                = $default_message . '<br>' . $dynamic_message;

            $email_body     = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags( $reservation_id, $body );

            $email_args = array(
                'to'         => $mail_to,
                'subject'    => $mail_subject,
                'mail_body'  => $email_body,
                'from'       => $mail_from,
                'from_name'  => $mail_from_name
            );

            Free_Utilities::wpc_send_email( $email_args );
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $eligibility
     * @param [type] $invoice
     * @return void
     */
    public function invoice_eligible_for_cancellation($eligibility, $invoice){

        return ( self::reservation_invoice_includes_woo_order( $invoice ) === false ) ? true : false;
    }
    /**
     * Checks If Reservation Invoice Includes Woo Order
     *
     * @param [type] $invoice_no
     * @return void
     * @since 1.3.7
     */
    public static function reservation_invoice_includes_woo_order( $invoice_no ){
			global $wpdb;
			$prepare_query     = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta where meta_key ='wpc_reservation_invoice' and meta_value = '%s' ", $invoice_no );
			$reservation_id    = $wpdb->get_col( $prepare_query );
			return !empty(get_post_meta( $reservation_id, 'order_id', true )) ? get_post_meta( $reservation_id, 'order_id', true ) : false;
	}


	/**
	 * Send Reservation Email
	 *
	 * @param [type] $invoice_no
	 * @return void
	 * Returns false if invoice includes woo order
	 */
	public static function send_reservation_email($invoice_no){
			$send_email_notification = ( self::reservation_invoice_includes_woo_order($invoice_no) === false ) ? true : false;
			return $send_email_notification;
	}

}