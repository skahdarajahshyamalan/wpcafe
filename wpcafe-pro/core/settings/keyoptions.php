<?php

namespace WpCafe_Pro\Core\Settings;

defined("ABSPATH") || exit;

use WpCafe\Utils\Wpc_Utilities;
use WpCafe_Pro\Utils\Utilities;

class Keyoptions extends \WpCafe\Core\Base\Config {

    use \WpCafe_Pro\Traits\Singleton;

    /**
     * Call all hooks
     */
    public function init() {

        // add filter to add more menu settings
        add_filter('wpcafe/key_options/menu_settings', [$this, 'pro_menu_settings']);

        // add filter to add more hook settings
        add_filter('wpcafe/key_options/hook_settings', [$this, 'hook_settings']);
        add_filter('wpcafe/key_options/hook_settings_reservation', [$this, 'hook_settings_reservation']);

        // add filter to frontend notification settings
        add_filter('wpcafe/action/cancell_notification', [$this, 'admin_reserve_submit_notification'], 10, 3);

        // add filter to add more  settings tab item, content
        add_filter('wpcafe/key_options/settings_tab_item', [$this, 'settings_tab_item']);

        //add filter for notification pro feature
        add_filter('wpcafe/metabox/notification', [$this, 'notifications'], 10, 3);

        //Seat capacity
        add_filter('wpcafe/reservation/seat_capacity', [$this, 'seat_capactiy'], 10, 1 );

    }

    /**
     * Return seat capacity
     */
    public function seat_capactiy( $settings  ){
        $data_range = Utilities::multi_schedule_time_seat( $settings );
        return isset($data_range['capacity']) ? $data_range['capacity']: 20;
    }

    /**
     * pro settings options 
     */
    public function pro_menu_settings($settings) {
        $all_data=[];
        $all_data['capacity']                   = $this->seat_capactiy( $settings );
        $all_data['menu_settings']              = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/menu-settings.php";
        $all_data['live_order_notification']    = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/live-order-notification.php";
        $all_data['tip_settings']               = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/tip-settings.php";
        $all_data['integration_settings']       = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/parts/integrations.php";
        $all_data['discount_settings']          = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/discount-settings.php";
        $all_data['reservation_form_settings']  = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/reservation-settings.php";
        $all_data['key_options']                = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/key-settings.php";
        $all_data['reservation_schedule']       = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/schedule-settings.php";
        $all_data['qrcode_settings']            = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/qrcode-settings.php";

        $all_data['reservation_holiday_settings']   = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/parts/reservation-holiday-settings.php";
        $all_data['special_menus']              = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/special-menus.php";

        $all_data['branch_email_settings']   = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/parts/branch-email-settings.php";

        $all_data['pickup_settings']   = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/parts/pickup.php";

        $all_data['delivery_settings']   = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/parts/delivery.php";

        $all_data['reservation_general_settings']   = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/parts/reservation-general.php";

        $all_data['style_settings']   = \Wpcafe_Pro::plugin_dir() . "/core/settings/views/parts/style.php";

        $all_data['notification_settings']      = $this->notification_settings( $settings );

        return $all_data;
    }
    
    /**
     * Send data to notification tab function
     */
    public function notification_settings( $settings ){
        $notify = array();
        $admin_notif_confirm_book          = ( !isset($settings['wpc_admin_notification_for_confirm_req']) || ( isset($settings['wpc_admin_notification_for_confirm_req']) && $settings['wpc_admin_notification_for_confirm_req'] =="on" ) ? 'on' : 'off');
        $user_notif_confirm_book           = ( !isset($settings['wpc_user_notification_for_confirm_req']) || ( isset($settings['wpc_user_notification_for_confirm_req']) && $settings['wpc_user_notification_for_confirm_req'] =="on" ) ? 'on' : 'off');
        $admin_notif_cancel_req            = ( !isset($settings['wpc_admin_cancel_notification']) || ( isset($settings['wpc_admin_cancel_notification']) && $settings['wpc_admin_cancel_notification'] =="on" ) ? 'on' : 'off');
        $user_notif_cancel_req             = ( !isset($settings['wpc_user_notification_for_cancel_req']) || ( isset($settings['wpc_user_notification_for_cancel_req']) && $settings['wpc_user_notification_for_cancel_req'] =="on" ) ? 'on' : 'off');
        $notify['admin_notif_confirm_book'] = $admin_notif_confirm_book;
        $notify['user_notif_confirm_book']  = $user_notif_confirm_book;
        $notify['admin_notif_cancel_req']   = $admin_notif_cancel_req;
        $notify['user_notif_cancel_req']    = $user_notif_cancel_req;

        return $notify;
    }


    /**
     * Add hook settings
     */
    public function hook_settings() {
        return include_once  \Wpcafe_Pro::core_dir() ."settings/views/hook-settings.php";
    }

    public function hook_settings_reservation() {
        return include_once  \Wpcafe_Pro::core_dir() ."settings/views/hook-settings-reservation.php";
    }

    /**
     * Tab content function
     */
    public function settings_tab_item($wpc_pro_tab_item) {
        $all_data=[];
        // Settings tab
        $result = [];
        if (is_array($wpc_pro_tab_item)) {
            // tab hook
            $wpc_pro_tab_hook = $wpc_pro_tab_item[0];
            $wpc_pro_tab_doc  = $wpc_pro_tab_item[1];
            $key = 'menu_settings';
            $offset = array_search($key, array_keys($wpc_pro_tab_hook));
            $result[0] = array_merge(
                array_slice($wpc_pro_tab_hook, 0, 1),
                array_slice($wpc_pro_tab_hook, $offset, null)
            );
            $pointer = array_search($key, array_keys($wpc_pro_tab_doc));
            $result[1] = array_merge(
                array_slice($wpc_pro_tab_doc, 0, $pointer),
                ['wpc_pro_order_time' => ''],
                array_slice($wpc_pro_tab_doc, $pointer, null)
            );
        }

        $all_data['settings_tab'] = $result;

        return $all_data;
    }


    /**
     * Send notification both for confirm and cancel function
     */
    public function notifications($settings, $reservation_state, $wpc_template) {

        $invoice_no        = $wpc_template['invoice'];
        $send_notification = \WpCafe_Pro\Core\Modules\Reservation_With_Food\Hooks::send_reservation_email( $invoice_no );

        //if this reservation includes menu order, then don't send separate email for reservation.
        if( $send_notification ){
            if (is_array($wpc_template) && isset($wpc_template['reservation_id'])) {
                if (isset($reservation_state) && $reservation_state == 'cancelled') {
                    // if reservation cancelled
                    $this->canelled_request($settings, $wpc_template['invoice'], $wpc_template);
                } else {
                    /**
                     * email to admin & user for confirmed / pending request
                     */
                    if (!isset($settings['wpc_admin_notification_for_confirm_req']) || ( isset($settings['wpc_admin_notification_for_confirm_req']) && $settings['wpc_admin_notification_for_confirm_req'] =='on' ) && isset($settings['wpc_admin_email_address']) ) {
                        // admin
                        $mail_to        = $settings['wpc_admin_email_address'];
                        $mail_subject   = isset($settings['wpc_admin_booking_confirm_subject']) ? $settings['wpc_admin_booking_confirm_subject'] : "";
                        $mail_body      = isset($settings['wpc_admin_booking_confirm_email'] ) && $settings['wpc_admin_booking_confirm_email'] !=='' ?  esc_html__($settings['wpc_admin_booking_confirm_email'],'wpcafe-pro') : esc_html__("Reservation confirmed. Invoice no. ".$wpc_template['invoice'] .".  ","wpcafe-pro");
                        $mail_from      = isset($settings['sender_email_address']) && $settings['sender_email_address'] !==''  ? $settings['sender_email_address'] : $settings['wpc_admin_email_address'];
                        $mail_from_name = isset($settings['wpc_reply_to_name'])  ? $settings['wpc_reply_to_name']  : esc_html__("Admin","wpcafe-pro");
                        $wpc_email_body     = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags( $wpc_template['reservation_id'],$mail_body, $invoice_no );

                        $email_args = array(
                            'to'        => $mail_to,
                            'subject'   => $mail_subject,
                            'mail_body' => $wpc_email_body,
                            'from'      => $mail_from,
                            'from_name' => $mail_from_name
                        );
                        Wpc_Utilities::wpc_send_email( $email_args );

                    }
                    if ( !isset( $settings['wpc_user_notification_for_confirm_req'] ) || ( isset($settings['wpc_user_notification_for_confirm_req']) && $settings['wpc_user_notification_for_confirm_req'] =='on' ) ) {
                        // user
                        $mail_to = isset( $wpc_template['wpc_email'] ) ? $wpc_template['wpc_email'] : "";
                        $mail_subject   = isset($settings['wpc_confirm_email_subject']) ? $settings['wpc_confirm_email_subject'] : "";
                        $mail_body      = isset( $settings['wpc_confirm_email'] ) && $settings['wpc_confirm_email'] !=='' ?  esc_html__( $settings['wpc_confirm_email'] ,'wpcafe-pro') : esc_html__('Your reservation is confirmed.Invoice no. '.$wpc_template['invoice'].". ",'wpcafe-pro');
                        $mail_from      = isset($settings['sender_email_address']) && $settings['sender_email_address'] !==''  ? $settings['sender_email_address'] : $settings['wpc_admin_email_address'];
                        $mail_from_name = isset($settings['wpc_reply_to_name'])  ? $settings['wpc_reply_to_name']  : esc_html__("Admin","wpcafe-pro");
                        $wpc_email_body     = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags( $wpc_template['reservation_id'],$mail_body, $invoice_no );

                        $email_args = array(
                            'to'        => $mail_to,
                            'subject'   => $mail_subject,
                            'mail_body' => $wpc_email_body,
                            'from'      => $mail_from,
                            'from_name' => $mail_from_name
                        );

                        Wpc_Utilities::wpc_send_email( $email_args );

                    }

                }
            }
        }

    }

    /**
     * Cancel request mail function
     */
    public function canelled_request($settings, $invoice_no, $wpc_template ) {
        /**
         * email to admin & user for cancel request
         */

        if (!isset($settings['wpc_admin_cancel_notification']) || ( isset($settings['wpc_admin_cancel_notification']) && $settings['wpc_admin_cancel_notification'] =='on' ) &&  isset( $settings['wpc_admin_email_address'] ) ) {
            $mail_to      = $settings['wpc_admin_email_address'];
            $mail_subject = isset($settings['wpc_admin_booking_cancel_subject']) ? $settings['wpc_admin_booking_cancel_subject'] : '';
            $mail_body    = esc_html__("Reservation is cancelled. Invoice No: ","wpcafe-pro"). $invoice_no .". ";
            $mail_body   .= isset($settings['wpc_admin_booking_cancel_email']) ? $settings['wpc_admin_booking_cancel_email'] : '';
            $mail_from      = isset($settings['sender_email_address']) && $settings['sender_email_address'] !==''  ? $settings['sender_email_address'] : $settings['wpc_admin_email_address'];
            $mail_from_name = isset($settings['wpc_reply_to_name'])  ? $settings['wpc_reply_to_name']  : esc_html__("Admin","wpcafe-pro");
            $wpc_email_body = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags( $wpc_template['reservation_id'], $mail_body, $wpc_template['invoice'] );

            $email_args = array(
                'to'        => $mail_to,
                'subject'   => $mail_subject,
                'mail_body' => $wpc_email_body,
                'from'      => $mail_from,
                'from_name' => $mail_from_name
            );
            Wpc_Utilities::wpc_send_email( $email_args );
        }
        if ( ( !isset($settings['wpc_user_notification_for_cancel_req']) || (isset($settings['wpc_user_notification_for_cancel_req']) && $settings['wpc_user_notification_for_cancel_req'] == 'on') )) {
            if (is_array($wpc_template) && count($wpc_template) > 0) {
                $wpc_pro_email_info = get_post_meta( $wpc_template['reservation_id'], 'wpc_email', true );
                $mail_to      = isset( $wpc_pro_email_info ) ? $wpc_pro_email_info : "";
                $mail_subject = isset($settings['wpc_rejected_email_subject']) ? $settings['wpc_rejected_email_subject'] : '';
                $mail_body    = esc_html__("Reservation is cancelled. Invoice No: ","wpcafe-pro") . $invoice_no.". ";
                $mail_body   .= isset($settings['wpc_rejected_email'])   ? $settings['wpc_rejected_email'] : '';
                $mail_from      = isset($settings['sender_email_address']) && $settings['sender_email_address'] !==''  ? $settings['sender_email_address'] : $settings['wpc_admin_email_address'];
                $mail_from_name = isset($settings['wpc_reply_to_name'])  ? $settings['wpc_reply_to_name']  : esc_html__("Admin","wpcafe-pro");

                $wpc_email_body = \WpCafe\Core\Modules\Reservation\Hooks::instance()->filter_template_tags( $wpc_template['reservation_id'], $mail_body, $wpc_template['invoice'] );

                $email_args = array(
                    'to'        => $mail_to,
                    'subject'   => $mail_subject,
                    'mail_body' => $wpc_email_body,
                    'from'      => $mail_from,
                    'from_name' => $mail_from_name
                );
                Wpc_Utilities::wpc_send_email( $email_args );
            }
        }
    }

    /**
     * Frontend ajax from submission function
     */
    public function admin_reserve_submit_notification($settings, $invoice_no, $wpc_template) {
        // get post id 
        global $wpdb;
        $table = $wpdb->prefix . 'postmeta';
        $invoice_no = trim($invoice_no);
        $prepare_guery = $wpdb->prepare("SELECT post_id FROM $table where meta_key ='wpc_reservation_invoice' and meta_value like '%%%s%%' ", $invoice_no);
        $get_values = $wpdb->get_col($prepare_guery);
        if (count($get_values) > 0) {
            $pid = $get_values[0];

            $wpc_template = [
                'invoice'       => $invoice_no, 
                'reservation_id'=>$pid
            ];

            $send_notification = \WpCafe_Pro\Core\Modules\Reservation_With_Food\Hooks::send_reservation_email( $invoice_no );
            //if this reservation includes menu order, then don't send separate email for reservation.
            if( $send_notification ){
                $this->canelled_request($settings, $invoice_no, $wpc_template);
            }
        }
    }

}
