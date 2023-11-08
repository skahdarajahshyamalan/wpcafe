<?php

namespace WpCafe_Pro\Core\Modules\Reservation;

use DateTime;
use WpCafe\Core\Base\Wpc_Settings_Field as Settings;

defined( "ABSPATH" ) || exit;

use WpCafe\Utils\Wpc_Utilities;

class Reservation_Report{

    use \WpCafe_Pro\Traits\Singleton;
    
    /**
     * Class constructor.
     */
    public function init(){
        add_filter( 'page_row_actions', [$this,'modify_list_row_actions'], 10, 2 );
        add_filter('wpcafe/key_options/reservation_details',[$this,'reservation_details']);
        add_action( 'manage_posts_extra_tablenav', [$this,'reservation_export_block']);
        // filter reservation by booking date
        add_action('restrict_manage_posts', [$this, 'reservation_filter_booking_date']);
        add_filter('parse_query', [$this, 'reservation_filter_request_query']);

        // export reservation report
        add_action( 'init', [$this, 'csv_export_reservation_report'] );
    }

    /**
     * Get reservation post function
     */
    public function get_reservation(){
        $reservation_report = get_posts(
            array(
                'post_type'         => 'wpc_reservation',
                'numberposts'       => -1,
                'post_status'       => 'publish'
                )
        );

        return $reservation_report;
    }

    /**
     * Export reservation extra field data function
     */
    public function reservation_extra_field_data(){
        $data = [];$extra_field_name = [];
        $header_field   = [
            esc_html__("Invoice","wpcafe-pro"),esc_html__("Name","wpcafe-pro"),
            esc_html__("Email","wpcafe-pro"),esc_html__("Phone","wpcafe-pro"),
            esc_html__("Seats","wpcafe-pro"),esc_html__("Date","wpcafe-pro"),
            esc_html__("Status","wpcafe-pro")];

        $settings       =     Settings::instance()->get_settings_option();

        if( !empty( $settings['reserv_extra_label'] ) && !empty( $settings['reserv_extra_label'][0] )){
            
            foreach ( $settings['reserv_extra_label']  as $key => $value ) {
                if( $value !=='' ){
                    array_push( $extra_field_name , 'reserv_extra_'.$key );
                    array_push( $header_field , $value );
                }
            }
            
        }


        $data['header_field']       = $header_field;
        $data['extra_field_name']   = $extra_field_name;

        return $data;
    }

    /**
     * Export attendee function
     */
    public function csv_export_reservation_report() {
        // Check if we are in WP-Admin
        if ( !is_admin() ) {return false;}
        if(isset($_GET['reserv_export_pro'])) :

        $header_field   = [
            esc_html__("Invoice","wpcafe-pro"),esc_html__("Name","wpcafe-pro"),
            esc_html__("Email","wpcafe-pro"),esc_html__("Phone","wpcafe-pro"),
            esc_html__("Seats","wpcafe-pro"),esc_html__("Date","wpcafe-pro"),
            esc_html__("Status","wpcafe-pro"),esc_html__("Message","wpcafe-pro"),
            esc_html__("Booking time","wpcafe-pro")];

        // get reservation
        $reservation_report = $this->get_reservation();
            if( is_array( $reservation_report ) && count( $reservation_report )>0 ) :
                $generated_date = date( 'd-m-Y His' ); //Date will be part of file name.
                header( "Content-type: text/csv" );
                header( "Content-Disposition: attachment; filename=\"reservation_report_" . $generated_date . ".csv\";" );

                // create a file pointer connected to the output stream
                $output = fopen( 'php://output', 'w' ) or die( "Can\'t open php://output" );

                foreach ( $reservation_report as $key => $value ) {
                    $reserv_extra   = get_post_meta($value->ID, 'reserv_extra', true);

                    if ( !empty( $reserv_extra[0] )) {
                        for ($i=0; $i < count( $reserv_extra ) ; $i++) {
                            if(!in_array($reserv_extra[$i]['label'], $header_field)){
                                array_push( $header_field , $reserv_extra[$i]['label'] );
                            }
                        }
                    }
                }

                // output the column headings
                fputcsv( $output, $header_field );
       
                foreach ( $reservation_report as $key => $value ) {
                    $invoice        = get_post_meta($value->ID, 'wpc_reservation_invoice', true);
                    $name           = get_post_meta($value->ID, 'wpc_name', true);
                    $email          = get_post_meta($value->ID, 'wpc_email', true);
                    $phone          = get_post_meta($value->ID, 'wpc_phone', true);
                    $seats          = get_post_meta($value->ID, 'wpc_total_guest', true);
                    $date           = get_post_meta($value->ID, 'wpc_booking_date', true);
                    $status         = get_post_meta($value->ID, 'wpc_reservation_state', true);
                    $message        = get_post_meta($value->ID, 'wpc_message', true);
                    $booking_time   = get_post_meta($value->ID, 'booking_current_time', true);

                    if ( !empty( \WpCafe_Pro\Utils\Utilities::is_table_layout_enabled() ) ) {
                        $booked_seats_info = \Wpcafe_Pro\Utils\Table_Utils::get_booked_seats_info( $value->ID );
                        if ( !empty( $booked_seats_info ) && count( $booked_seats_info ) > 0 ) {
                            $seats = esc_html__( 'Total Guest: ', 'wpcafe-pro' ) . $seats . '; ';
                            $seats .= join( '; ', $booked_seats_info );
                        }
                    }

                    if ( !empty( $date ) ) {
                        $date = date_i18n( get_option( 'date_format' ), strtotime( $date ) );
                    }

                    if ( !empty( $booking_time ) ) {
                        $booking_time_int   = strtotime( $booking_time );
                        $booking_time       = date_i18n( get_option( 'date_format' ), $booking_time_int ) .  ' ' . date_i18n( get_option( 'time_format' ), $booking_time_int );
                    }

                    $reserve_value  = [$invoice , $name, $email, $phone, $seats, $date, $status, $message , $booking_time ];
                    // extra field
                    $reserv_extra   = get_post_meta($value->ID, 'reserv_extra', true);

                    if ( !empty( $reserv_extra[0] )) {
                        for ($i=0; $i < count( $reserv_extra ) ; $i++) {
                            array_push( $reserve_value , $reserv_extra[$i]['value'] );
                        }
                    }

                    fputcsv( $output, $reserve_value );
                }

                // Close output file stream
                fclose( $output );
                die();
            else:
            endif;
        endif;
    }

    /**
     * Reservation export section
     */
    public function reservation_export_block() {
        global $typenow , $pagenow ;
        if ( $typenow == 'wpc_reservation' &&  $pagenow == "edit.php"  ):
            $reservation_count = count( $this->get_reservation() );
            if ($reservation_count > 0) {
        ?>
            <button type="submit" name="reserv_export_pro" class="button button-primary">
                <?php echo esc_html__('Export to CSV', 'wpcafe-pro'); ?>
            </button>
        <?php
            }
        endif;
    }

    /**
     * Result of query
     */
    public function reservation_filter_request_query($query){
		$post_type = 'wpc_reservation';
		$query->query['post_type'] = $post_type;
		
		if ( ( !(is_admin()) && $query->is_main_query() ) 
		|| ( is_admin() && ('wpc_reservation' == $query->query['post_type']) 
		&& empty($_GET['booking_date']) && empty($_GET['reserv_export_pro']) )  ) {
            return $query;
		}

        $search_value = isset($_GET['booking_date']) &&  $_GET['booking_date'] !=="Filter booking" ? sanitize_text_field($_GET['booking_date']) : null;

        if (!isset($query->query['post_type']) || ( $post_type !== $query->query['post_type']) || !isset($search_value) ) {
            return $query;
        }

        if (!isset($query->query_vars['meta_query'])) {
            $query->query_vars['meta_query'] = array();
        }

        $wpc_format = ['m/d/Y','m-d-Y'];
        $current_format = get_option('date_format');
		if ( in_array( $current_format , $wpc_format ) ) {
			$date = DateTime::createFromFormat( get_option('date_format') , $search_value );
			$get_date = ! is_bool( $date ) ? $date->format('Y-m-d') : '';
		}
		else{
			$get_date = date('Y-m-d', strtotime(str_replace(array('/','.'), '-', $search_value)));
		}

        // setup this functions meta values
        $meta = array(
            'key'       =>  'wpc_booking_date',
            'value'     =>  $get_date ,
            'compare'   => 'LIKE',
        );

        // append to meta_query array
        $query->query_vars['meta_query'][] = $meta;

        return $query;
    }

    /**
     * date wise reservation filtering function
     *
     */
    public function reservation_filter_booking_date(){
        global $typenow , $pagenow ;

        $count_booking = count( $this->get_reservation() );

        if ( $typenow == 'wpc_reservation' &&  $pagenow == "edit.php" && $count_booking > 0 ):
            $selected = '';
            if ((isset($_GET['booking_date']))  && isset($_GET['post_type'])
                && !empty(sanitize_text_field($_GET['booking_date'])) &&  sanitize_text_field($_GET['post_type']) == 'wpc_reservation'
            ) {
                $selected = sanitize_text_field($_GET['booking_date']);
            }
            ?>
            <input type="text" name="booking_date" value="<?php echo esc_attr( $selected ) ?>"
            placeholder="<?php echo esc_attr_e( "Booking by date","wpcafe-pro" ) ?>" id="booking_date"/>
            <?php
        endif;

    }

    /**
     * Add details action and remove views actions
     */
    public function modify_list_row_actions( $actions , $post ) {
        // Check for your post type.
        if ( $post->post_type == "wpc_reservation" ) {
            unset($actions['view']);
            unset($actions['inline hide-if-no-js']);
            // Build  URL.
            $url = admin_url( 'admin.php?page=cafe_settings&post_id=' . $post->ID );

            // current user has rights.
            if ( current_user_can( 'manage_options', $post->ID ) ) {
                // Include a nonce in this link
                $details_link = wp_nonce_url( add_query_arg( array( 'action' => 'reservation_details' ), $url ), 'wpc_pro_reservation_details' );
                // Add the new details quick link.
                $actions = array_merge( $actions , array(
                    'reservation_details' => sprintf( '<a href="%1$s">%2$s</a>',
                        esc_url( $details_link ), 
                        'Details'
                    ) 
                ) );
            }
        }

        return $actions;
    }

    /**
     * Single reservation details
     */
    public function reservation_details() {
        
        if ( is_admin()  && isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'reservation_details') {
            if (!wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpc_pro_reservation_details' ) ) {
                return false;
            }else{
                $id             = Wpc_Utilities::wpc_numeric($_GET['post_id']);
                $name           = get_post_meta($id,'wpc_name',true);
                $reserv_extra   = get_post_meta($id,'reserv_extra',true);
                $order_id       = get_post_meta( $id , 'order_id' , true );
                ?>
                <div class="wrap">
                    <button class="button-primary" onclick="wpc_pro_pirnt_content_area('print_reservation_details');"><?php echo esc_html__( 'Print' , 'wpcafe-pro' );?></button>
                    <div id="print_reservation_details">
                        <h3><?php echo esc_html__('Booking Details of ', 'wpcafe-pro' ) . sanitize_text_field($name); ?></h3>
                        <table>
                            <tbody>
                            <?php
                                $reservation_field_arr = \WpCafe_Pro\Core\Modules\Reservation\Hooks::instance()->reservation_field_array();
                                // add more field
                                $reservation_field_arr['booking_current_time'] = esc_html__( 'Reservation placing time', 'wpcafe-pro' );

                                if ( !empty( $reservation_field_arr)) {
                                    foreach( $reservation_field_arr as  $field_name => $field_value ){
                                        $get_field_name = $field_name == "wpc_guest_count" ? "wpc_total_guest" : $field_name;
                                        $field = get_post_meta($id, $get_field_name ,true)  ;

                                        if ( $get_field_name == 'booking_current_time' ) {
                                            $field_time_int = strtotime( $field );
                                            $field          = date_i18n( get_option( 'date_format' ), $field_time_int ) .  ' ' . date_i18n( get_option( 'time_format' ), $field_time_int );
                                        }
                                        if ( $get_field_name == 'wpc_booking_date' ) {
                                            $wpc_date_format =  get_option('date_format');
                                            $field           = date_i18n($wpc_date_format,strtotime($field) );
                                        }


                                        if( !empty($field)  ){
                                    ?>
                                        <tr>
                                            <td>
                                                <strong>
                                                    <?php echo esc_html($field_value);?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php echo esc_html( $field );?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                }
                            }
                            ?>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html__('Booking status', 'wpcafe-pro');?></strong>
                                    </td>
                                    <td>
                                        <?php
                                        $reservation_status = apply_filters('wpcafe/reservation/dashboard_reservation_report_status', get_post_meta( $id, 'wpc_reservation_state', true ), $id);
                                        echo Wpc_Utilities::wpc_render( ucfirst($reservation_status) );
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php
                            if ( $order_id !=="" ) {?>
                            <h3 class="reservation-form-title"><?php echo esc_html__('Food Details', 'wpcafe-pro' ) ; ?></h3>
                        <?php
                            $order = wc_get_order( $order_id );
                            $items = $order->get_items();
                            
                            if ( count( $items )>0 ) {
                                ?>
                                <table>
                                    <thead>
                                        <td><strong><?php echo esc_html__('Name:', 'wpcafe-pro' );?></strong></td>
                                        <td><strong><?php echo esc_html__('Quantity:', 'wpcafe-pro' );?></strong></td>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($items as $key => $value) {
                                            ?>
                                                <tr>
                                                    <td><?php esc_html_e( $value->get_name() );?></td>
                                                    <td><?php esc_html_e( $value->get_quantity() )?></td>
                                                </tr>
                                            <?php
                                        }
                                        ?>
                                    <tr>
                                        <td><strong><?php echo esc_html__('Total:', 'wpcafe-pro' );?></strong></td>
                                        <td><?php esc_html_e( $order->get_total() );?></td>
                                    </tr>
                                    <table>
                                <tbody>
                                <?php
                            }

                            }
                        ?>

                    </div>
                </div>
                <?php
            }
        }
    }
}