<?php

namespace Wpc_Table_Layout\Modules\Settings;

use WpCafe\Core\Base\Wpc_Settings_Field;
use WpCafe_Pro\Utils\Table_Utils as Table_Layout_Helper;

defined( 'ABSPATH' ) || exit;

class Settings_Api extends \WpCafe\Core\Base\Api_Handler{

    public function config() {
        $this->prefix = ''; // settings
        $this->param = ""; // /(?P<id>\w+)/
    }

    /**
     * save table layout mapping data through api
     *
     * @return array
     */
    public function post_table_mapping() {
        $status_code    = 0;
        $messages       = $content = [];
        $request        = $this->request;

        if ( !is_admin() && !current_user_can( 'manage_options' ) ) {
            
            if ( !wp_verify_nonce( $this->request->get_header( 'X-WP-Nonce' ), 'wp_rest' ) ) {
                $messages[] = esc_html__( 'Nonce is not valid! Please try again.', 'wpcafe-pro' );
            } else {
                if ( isset( $request['layout'] ) && !empty( $request['layout'] ) ) {
                    $layout_data    = $request['layout'];
                    
                    $validation_ok  = true;
                    $settings       = Wpc_Settings_Field::instance()->get_settings_option();

                    $chair_qty      = isset( $layout_data['chairQty'] ) ? absint( $layout_data['chairQty'] ) : 0;
                    $seat_capacity  = isset( $settings['rest_max_reservation'] ) ? absint( $settings['rest_max_reservation'] ) : 100;
                    $canvas_height  = isset( $layout_data['canvasHeight'] ) ? absint( $layout_data['canvasHeight'] ) : 500;
                    $layout_data['canvasHeight'] = $canvas_height;

                    if ( $chair_qty > $seat_capacity ) {
                        $validation_ok = false;
                        $messages[]    = esc_html__( 'You added the maximum of your seat. In order to add more, update seat capacity from settings.', 'wpcafe-pro' );
                    }

                    $chair_intersect_data = isset( $layout_data['chairIntersectData'] ) ? $layout_data['chairIntersectData'] : [];
                    foreach ( $chair_intersect_data as $table_key => $chair_data ) {
                        if ( empty( $chair_data )  ) {
                            $validation_ok = false;
                            $messages[]    = esc_html__( 'Did you forget to insert the chair with the table? In order to proceed, you must insert the chair and connect with table.', 'wpcafe-pro' );
                        }
                    }

                    if ( $validation_ok ) {
                        $schedule_slug   = ( isset( $request['schedule_slug'] ) && !empty( $request['schedule_slug'] ) ) ? $request['schedule_slug'] : Table_Layout_Helper::retrieve_slug_name();
                        if ( !empty( $schedule_slug ) ) {
                            $status_code = 1;
                            $messages[]  = esc_html__( 'Mapping data saved successfully.', 'wpcafe-pro' );

                            $all_table_mapping = get_option( 'wpc_table_layout', [] );
        
                            $all_table_mapping[ $schedule_slug ] = $layout_data;
                            $all_table_mapping['common_mapping'] = $layout_data;
                            update_option( 'wpc_table_layout', $all_table_mapping );
                        } else {
                            $messages[]  = esc_html__( 'Sorry! There\'s a problem in reservation schedule setting. Please check schedule and update.', 'wpcafe-pro' );
                        }

                    } 
                }
            }
        } else {
            $messages[] = esc_html__( 'You haven\'t authorization permission to update table layout mapping.', 'wpcafe-pro' );
        }

        return [
            'status_code' => $status_code,
            'messages'    => $messages,
            'content'     => $content,
        ];
    }

    /**
     * get table layout mapping data through api
     *
     * @return void
     */
    public function get_table_mapping() {
        $status_code     = $validation_code = $booking_open = 0;
        $messages        = $content = [];
        $request         = $this->request;

        $schedule_slug   = Table_Layout_Helper::retrieve_slug_name();
        $content         = get_option( 'wpc_table_layout', [] );  
        
        if ( !empty( $content ) ) {
            $status_code = 1;
            $booked_ids  = $booked_table_ids = [];
            
            if ( !is_admin() ) {
                
                if ( isset( $request['date'] ) && !empty( $request['date'] ) ) {
                    // pass input field for checking empty value
                    $inputs_field = [
                        ['name' => 'date', 'required'  => true, 'type' => 'text'],
                        ['name' => 'from_time', 'required'  => false, 'type' => 'text'],
                        ['name' => 'to_time', 'required'  => false, 'type' => 'text'],
                    ];

                    $validation = Table_Layout_Helper::input_field_validation( $request, $inputs_field );
                    if ( !empty( $validation['status_code'] ) && $validation['status_code'] == true ) {
                        $validation_code = 1;
                        $input_data      = $validation['data'];

                        $selected_date   = $input_data['date'];
                        $from_time       = !empty( $input_data['from_time'] ) ? $input_data['from_time'] : '12:00 AM';
                        $to_time         = !empty( $input_data['to_time'] ) ? $input_data['to_time'] : '11:30 PM';

                        $booking_data   = Table_Layout_Helper::get_booking_data( $selected_date, $from_time, $to_time );
                        $booking_open   = $booking_data['booking_open'];
                        $booked_ids     = $booking_data['booked_ids'];

                        if ( $booking_open && !empty( $booked_ids ) ) {
                            $table_chair_data = $content[$schedule_slug]['chairIntersectData'];
            
                            foreach ( $table_chair_data as $table_id => $chair_arr ) {
                                if ( empty( array_diff( $chair_arr, $booked_ids ) ) ) {
                                    array_push( $booked_table_ids, $table_id );
                                }
                            }
                        } 
                    } else {
                        $validation_code = $validation['status_code'];
                        $messages        = $validation['messages'];
                    }
                } else {

                    if ( !empty( $schedule_slug ) ) {
                        if ( isset( $content[$schedule_slug] ) ) {
                            $content[$schedule_slug] = $content['common_mapping'];
                        } else {
                            if ( isset( $content['common_mapping'] ) ) {
                                $content[$schedule_slug] = $content['common_mapping'];
                            }
                        }
                    }

                    $state           = "confirmed";
                    $all_reservation = [];
                    
                    $meta_query = [
                        [
                            'key'           => 'wpc_reservation_state',
                            'value'         => [ $state , 'Processing' ],
                            'compare'       => 'IN'
                        ],
                        // for single slot
                        [
                            'key'           => 'wpc_visual_selection',
                            'value'         => 1,
                            'compare'       => '=',
                        ],
                    ];
                    
                    $all_reservation = get_posts(
                        [
                            'post_type'         => 'wpc_reservation',
                            'numberposts'       => -1,
                            'post_status'       => 'publish',
                            'meta_query'        => $meta_query 
                        ]
                    );
    
                    if( count( $all_reservation )>0 ){
                        foreach ($all_reservation as $key => $value) {
        
                            $s_booked_ids       = get_post_meta($value->ID, 'wpc_booked_ids', true);
                            $s_booked_table_ids = get_post_meta($value->ID, 'wpc_booked_table_ids', true);
            
                            if( !empty( $s_booked_ids ) ) {
                                $s_booked_ids = maybe_unserialize( $s_booked_ids );

                                if( is_array( $s_booked_ids ) && count( $s_booked_ids ) > 0 ) {
                                    foreach ( $s_booked_ids as $index => $s_booked_id ) {
                                        if ( !in_array( $s_booked_id, $booked_ids ) ) {
                                            array_push( $booked_ids, $s_booked_id );
                                        }
                                    }
                                }
                            }
    
                            if( !empty( $s_booked_table_ids ) ) {
                                $s_booked_table_ids = maybe_unserialize( $s_booked_table_ids );

                                if( is_array( $s_booked_table_ids ) && count( $s_booked_table_ids ) > 0 ) {
                                    foreach ( $s_booked_table_ids as $index => $s_booked_table_id ) {
                                        if ( !in_array( $s_booked_table_id, $booked_table_ids ) ) {
                                            array_push( $booked_table_ids, $s_booked_table_id );
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                }
            }

            $content[$schedule_slug]['booked_ids']       = $booked_ids;
            $content[$schedule_slug]['booked_table_ids'] = $booked_table_ids;
        } else {
            $messages[] = esc_html__( 'No mapping is done yet. Please draw mapping', 'wpcafe-pro' );
        }

        return [
            'status_code'       => $status_code,
            'validation_code'   => $validation_code,
            'booking_open'      => $booking_open,
            'schedule_slug'     => $schedule_slug,
            'messages'          => $messages,
            'content'           => $content,
        ];
    }
   
}

new Settings_Api();
