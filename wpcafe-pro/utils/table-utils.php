<?php

namespace WpCafe_Pro\Utils;

defined( 'ABSPATH' ) || exit;

use WpCafe\Core\Base\Wpc_Settings_Field;

/**
 * Global helper class.
 *
 * @since 1.0.0
 */

class Table_Utils {

    private static $settings_key = 'wpc_table_layout_options';

    /**
     * Input field escaping , sanitizing , validation
     *
     * @param array $request
     * @param array $input_fields
     * 
     * @return array
     */
    public static function input_field_validation( $request, $input_fields ) {

        $response = ['status_code' => 1, 'messages' => [], 'data' => []];

        if ( !empty( $input_fields ) ) {
            $error_field = [];

            foreach ( $input_fields as $key => $value ) {

                if ( $value['required'] == true && empty( $request[$value['name']] ) ) {
                    $error_field[] = esc_html( ucfirst( str_replace( '_', ' ', $value['name'] ) ) . ' is empty', 'wpcafe-pro' );
                }

            }

            if ( count( $error_field ) > 0 ) {
                $response = ['status_code' => 0, 'messages' => $error_field];
            } else {

                $input_data = [];

                foreach ( $input_fields as $key => $value ) {
                    $data                       = Table_Utils::validate_data( $request, $value );
                    $input_data[$value['name']] = $data;
                }

                // pass sanitizing data
                $response = [
                    'status_code' => 1,
                    'messages'    => [],
                    'data'        => $input_data,
                ];
            }

        } else {
            $response = [
                'status_code' => 0,
                'messages'    => [
                    'empty_input' => esc_html__( 'Input field is empty', 'wpcafe-pro' ),
                ],
            ];
        }

        return $response;
    }

    /**
     * Sanitize and escaping data
     *
     * @param array $request
     * @param array $input_fields
     * 
     * @return mixed 
     */
    public static function validate_data( $request, $input_fields ) {
        $data = "";

        switch ( $input_fields['type'] ) {
        case "text":
            $data = sanitize_text_field( $request[$input_fields['name']] );
            break;
        case "number":
            $data = absint( $request[$input_fields['name']] );
            break;
        default:
            break;
        }

        return $data;
    }

    /**
     * Get all settings
     */
    public static function get_settings_option( $key = null, $default = null ) {
        if ( $key != null ) {
            self::$settings_key = $key;
        }
        
        return get_option( self::$settings_key );
    }
    
    /**
     * Get Cafe options key
     *
     * @param string $key
     * @param string $default
     * 
     * @return string
     */
    public static function get_option( $key, $default = '' ) {
        $all_settings = get_option( self::$settings_key );

        return ( isset( $all_settings[$key] ) && $all_settings[$key] != '' ) ? $all_settings[$key] : $default;
    }

    /**
     * Check ajax nonce
     *
     * @param [type] $nonce_field
     * @param [type] $action
     * @param [type] $post
     * @return boolean
     */
    public static function is_secured( $nonce_field, $action, $post ) {
        $nonce = isset( $post[$nonce_field] ) ? sanitize_text_field( $post[$nonce_field] ) : '';
        if ( $nonce == '' ) {
            return false;
        }

        if ( !wp_verify_nonce( $nonce, $action ) ) {
            return false;
        }

        return true;
    }

    /**
     * get created schedules list
     *
     * @return array
     */
    public static function retrieve_available_schedules() {
        $available_schedules = [];
        $settings            = Wpc_Settings_Field::instance()->get_settings_option();

        if ( !empty( $settings['wpc_all_day_start_time'] ) && !empty( $settings['wpc_all_day_end_time'] ) ) {
            $slug_name           = (isset( $settings['slug_single_all'] ) && !empty( $settings['slug_single_all'] ) ) ? $settings['slug_single_all'] : 'single_all_0';
            $time_range          = $settings['wpc_all_day_start_time'] . ' - ' . $settings['wpc_all_day_end_time'];
            $schedule_name       =  esc_html__( 'All Day ', 'wpcafe-pro' ) . ' [' . $time_range . ']';
            $available_schedules = [ $slug_name => $schedule_name ];
        } else if( isset( $settings['wpc_weekly_schedule'] ) && !empty( $settings['wpc_weekly_schedule'] ) ) {
            foreach ( $settings['wpc_weekly_schedule'] as $index => $days_arr ) {
                $slug_name           = (isset( $settings['slug_single_weekly'] ) && !empty( $settings['slug_single_weekly'] ) ) ? $settings['slug_single_weekly'] : '';
                $time_range     = $settings['wpc_weekly_schedule_start_time'][$index] . ' - ' . $settings['wpc_weekly_schedule_end_time'][$index];
                $schedule_name  = join( ', ', array_keys( $days_arr ) ) . ' ' . ' [' . $time_range. ']';
                $available_schedules += [ $slug_name => $schedule_name ];
            }
        }

        if ( empty( $available_schedules ) ) {
            $available_schedules = [ '' => esc_html__( "No Schedule is created yet!", "wpcafe-pro" ) ];
        } else {
            $available_schedules = [ '' => esc_html__( "Select Schedule", "wpcafe-pro" ) ] + $available_schedules;
        }

        return $available_schedules;                                                                                                                                                                                                                                                                                                                                                        
    }

    
    /**
     * get slug name
     *
     * @return array
     */
    public static function retrieve_slug_name() {
        $settings  = Wpc_Settings_Field::instance()->get_settings_option();

        $slug_name = '';
        if ( !empty( $settings['wpc_all_day_start_time'] ) && !empty( $settings['wpc_all_day_end_time'] ) ) {
            $slug_name  = (isset( $settings['slug_single_all'] ) && !empty( $settings['slug_single_all'] ) ) ? $settings['slug_single_all'] : 'single_all_0';
        } else if( isset( $settings['wpc_weekly_schedule'] ) && !empty( $settings['wpc_weekly_schedule'] ) ) {
            $slug_name  = (isset( $settings['slug_single_weekly'] ) && !empty( $settings['slug_single_weekly'] ) ) ? $settings['slug_single_weekly'] : '';  
        }

        return $slug_name;
    }


    /**
     * get date specific booking data
     *
     * @param string $selected_date
     * @return array
     */
    public static function get_booking_data( $selected_date = '', $from_time = '', $to_time = '' ) {
        $booking_open = $booked_total = 0;
        $booked_ids   = $booked_table_ids = [];

        if ( !empty( $selected_date ) ) {
            $date_booked_info = \WpCafe_Pro\Core\Modules\Reservation\Hooks::instance()->reser_capacity_status( $selected_date, $from_time, $to_time, 'table_layout' );
            $capacity         = $date_booked_info['capacity'];

            if ( $date_booked_info['status'] == 'open' ) {
                $booking_open = 1;
                $booked_total = $date_booked_info['date_booked_total'];

                if ( isset( $date_booked_info['date_booked_ids'] ) && !empty( $date_booked_info['date_booked_ids']  ) ) {
                    foreach ( $date_booked_info['date_booked_ids'] as $index => $indi_booked_arr ) {
                        $booked_ids = array_merge( $booked_ids, $indi_booked_arr );
                    }
                }

                if ( isset( $date_booked_info['date_booked_table_ids'] ) && !empty( $date_booked_info['date_booked_table_ids']  ) ) {
                    foreach ( $date_booked_info['date_booked_table_ids'] as $index => $indi_booked_table_arr ) {
                        $booked_table_ids = array_merge( $booked_table_ids, $indi_booked_table_arr );
                    }
                }
            }
        }

        $booking_data = [
            'booking_open'      => $booking_open,
            'booked_total'      => $booked_total,
            'capacity'          => $capacity,
            'booked_ids'        => $booked_ids,
            'booked_table_ids'  => $booked_table_ids,
        ];

        return $booking_data;
    }

    /**
     * booked seats info like table1, chair1,2... to show in reservation list and export
     *
     * @param integer $post_id
     * @return array
     */
    public static function get_booked_seats_info( $post_id = 0 ) {
        $booked_seats_info = [];
        $booked_ids        = get_post_meta( $post_id, 'wpc_booked_ids', true );

        if ( !empty( $booked_ids ) ) {
            $booked_ids = maybe_unserialize( $booked_ids );
            
            if ( is_array( $booked_ids ) && count( $booked_ids ) > 0 ) {
                $obj_names        = get_post_meta( $post_id, 'wpc_obj_names', true );
                $intersected_data = get_post_meta( $post_id, 'wpc_intersected_data', true );
                if ( !empty( $obj_names ) ) {
                    $obj_names        = json_decode( $obj_names, true );
                    $intersected_data = json_decode( $intersected_data, true );

                    $friendly_names = [];
                    $table_prefix   = esc_html__( 'Table ', 'wpcafe-pro' );
                    $chair_prefix   = esc_html__( 'Chair ', 'wpcafe-pro' );

                    if ( !empty( $intersected_data ) ) {
                        $table_chairs = [];
                        foreach ( $intersected_data as $table_id => $chair_arr ) {
                            $table_chairs[$table_id] = [];

                            if ( empty( array_diff( $chair_arr, $booked_ids ) ) ) { // check table specific all chairs are selected for booking
                                $booked_ids = array_diff( $booked_ids, $chair_arr ); // remove table specific all chairs

                                $friendly_name = $table_prefix . $obj_names[$table_id] . '/' . esc_html__( 'All chairs', 'wpcafe-pro' ) . '(' . count( $chair_arr ) . ')';
                                array_push( $friendly_names, $friendly_name );
                            } else {
                                foreach ( $booked_ids as $index => $booked_id ) {
                                    if ( in_array( $booked_id, $chair_arr ) ) {
                                        array_push( $table_chairs[$table_id], $obj_names[$booked_id] );
                                        unset($booked_ids[$index]);
                                    }
                                }
                            }
                        }
                
                        foreach ( $table_chairs as $t_id => $t_chairs ) {
                            if ( !empty( $t_chairs ) ) { 
                                $friendly_name = $table_prefix . $obj_names[$t_id] . '/' . esc_html__( 'Chairs: ', 'wpcafe-pro' )  . join( ', ', $t_chairs );
                                array_push( $friendly_names, $friendly_name );
                            }
                        }
                        
                        if ( !empty( $booked_ids ) ) {
                            foreach ( $booked_ids as $index => $booked_id ) {
                                array_push( $friendly_names, $chair_prefix . $obj_names[$booked_id] );
                            }
                        }
                    } else {
                        foreach ( $booked_ids as $index => $booked_id ) {
                            array_push( $friendly_names, $chair_prefix . $obj_names[$booked_id] );
                        }
                    }
                    
                    $booked_seats_info = $friendly_names;
                }

            }
        }

        return $booked_seats_info;
    }
}
