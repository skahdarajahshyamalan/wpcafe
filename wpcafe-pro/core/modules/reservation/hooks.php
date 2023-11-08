<?php
namespace WpCafe_Pro\Core\Modules\Reservation;

use WpCafe\Core\Base\Wpc_Settings_Field as Settings;
use WpCafe_Pro\Utils\Utilities as Pro_Utilities;

defined( "ABSPATH" ) || exit;

class Hooks{

    use \WpCafe_Pro\Traits\Singleton;

    public function init(){
        $filter_arr = array(
			array(
					'hook'      => 'wpcafe/action/reservation_template',
					'callback'  => 'reservations_template',
					'priority'  => 10,
					'args'      => 1,
			),
			array(
					'hook'      => 'wpcafe_pro/action/extra_field',
					'callback'  => 'reservations_submit_extra_field',
					'priority'  => 10,
					'args'      => 2,
			),
			array(
					'hook'      => 'wpcafe/meta/extra_field_label',
					'callback'  => 'reservations_report_extra_field',
					'priority'  => 10,
					'args'      => 2,
			),
			array(
					'hook'      => 'wpcafe_pro/multiple_slot/settings',
					'callback'  => 'reservations_multislot_settings',
					'priority'  => 10,
					'args'      => 1,
			)
        );

        // add filter to reservation template
        if( ! empty( $filter_arr)){
                foreach ($filter_arr as $key => $value) {
                        add_filter( $value['hook'], [$this, $value['callback'] ] , $value['priority'], $value['args'] );
                }
        }
    }

    /**
     * 
     */
    public function reservations_multislot_settings( $request ){
        // weekday based multi_diff schedule array for frontend only
        $weekly_multi_diff_arr = [];
        if( !empty( $request['multi_diff_weekly_schedule'][0])  && !empty( $request['multi_diff_start_time'][0])  && !empty( $request['multi_diff_end_time'][0] ) ){
                $schedule_row = $request['multi_diff_weekly_schedule'];

                foreach($schedule_row as $key =>$schedule){

					foreach( $schedule as $dayName => $dayStatus ){
						if (!empty( $request['multi_diff_start_time'] ) || !empty( $request['multi_diff_end_time'] ) ) {
						$weekly_multi_diff_arr[$dayName] = [];
							foreach ( $request['multi_diff_start_time'][$key] as $timeKey => $dayTime) {
								$time_arr = [
										'start_time'    => $dayTime,
										'end_time'      => $request['multi_diff_end_time'][$key][$timeKey],
										'seat_capacity'  => $request['diff_seat_capacity'][$key][$timeKey],
										'schedule_name'  => $request['diff_schedule_name'][$key][$timeKey]
								];
								array_push($weekly_multi_diff_arr[$dayName], $time_arr);
							}
						}
					}
                }

        }
        $request['weekly_multi_diff_times'] = $weekly_multi_diff_arr;

        return $request;
    }

    /**
     * Submit reservation extra field function
     */
    public function reservations_submit_extra_field(  $pid , $post_arr ){
		// Integration with zapier / pabbly
		\WpCafe_Pro\Core\Modules\Integrations\Zapier::instance()->send_data_to_api( $post_arr );
		// Extra field
		if(  !empty( $post_arr['reserv_extra'][0] ) )  {
				// add reservation extra field array data 
				add_post_meta( $pid, 'reserv_extra', $post_arr['reserv_extra'] , true );
				// save data by key
				if ( !empty( $post_arr['reserv_extra'][0] ) ) {
					foreach ($post_arr['reserv_extra'] as $key => $value) {
						add_post_meta( $pid, 'reserv_extra_'.$key , $value['value'] , true );
					}
				}
		}

		// save booking current time
		add_post_meta( $pid, 'booking_current_time', date_i18n( WPCAFE_DEFAULT_DATE_FORMAT. " " . WPCAFE_DEFAULT_TIME_FORMAT ) , true );

    }

    /**
     * Reservation status based on seat capacity
     */
    public function reser_capacity_status( $selected_date = null, $from_time = '', $to_time = '', $type = '' ){
            $settings = Settings::instance()->get_settings_option();

            $response       = array(); $booking = "open";
            // check for multi-slot
            if ( ! empty( $settings['reser_multi_schedule'] ) && $settings['reser_multi_schedule'] =="on" ) {
                    $data = Pro_Utilities::multi_schedule_time_seat( $settings , $from_time  );
                    $data['selected_date'] = $selected_date;

                    if ( !empty( $data ) ) {
						// Find total seat after booking confirmation
						$total_seat = $this->get_all_reservation( $data , $from_time, $to_time, $type );
						if( $data['capacity'] == 0 ){
								$booking                = "open";
						}
						elseif ( $total_seat  >= (int) $data['capacity'] ) {
								$booking                = "closed";
								$response['message']    = esc_html__('Reservation is closed . Please try another time.','wpcafe-pro');
						}
                    }
                    $response['capacity'] = absint( (int) $data['capacity'] - $total_seat ); // now remaining seat.

            }
            else {
                // check single slot reservation seat capacity
                $response['capacity'] = 100; // default seat.
                if ( !empty( $settings['rest_max_reservation']) ) {

                        $total_seat = $this->get_all_reservation( array( 'capacity' => (int) $settings['rest_max_reservation'] ,
                        'selected_date'=> $selected_date ), $from_time, $to_time, $type );

                        if ( !empty( $type ) ) {
                                $response['date_booked_ids']        = $total_seat['date_booked_ids'];
                                $response['date_booked_table_ids']  = $total_seat['date_booked_table_ids'];
                                // total_seat is already confirmed seat.
                                $total_seat                         = $total_seat['total_seat'];
                                $response['date_booked_total']      = $total_seat;
                        }

                        if ($total_seat  >= (int) $settings['rest_max_reservation'] ) {
                                $booking                = "closed";
                                $response['message']    = esc_html__('Our all seat is booked. Reservation is closed . Please try another time','wpcafe-pro');
                        }

                        $response['capacity'] = absint( (int) $settings['rest_max_reservation'] - $total_seat ); // now remaining seat.
                        $response['max_capacity'] = absint( $settings['wpc_max_guest_no'] < $response['capacity'] ? $settings['wpc_max_guest_no'] : $response['capacity'] ); // maximum allowed capacity

                }
                // endif;
            }


            $response['status'] = $booking;

            return $response;
    }

    /**
     * Get reservation by single / multi slot and
     * By time range
     */
    public function get_all_reservation( $data=[], $from_time = '',  $to_time = '', $type = '' ){
		$settings = Settings::instance()->get_settings_option();
		$all_reservation = array(); $state = "confirmed";
		if( isset( $settings['rest_reservation_off'] ) && $settings['rest_reservation_off'] !== "" ){
				$state = $settings['rest_reservation_off'];
		}

		// Check today for checking seat capacity.

		$wpc_booking_date   = !empty( $data['selected_date'] ) ? $data['selected_date'] : date( WPCAFE_DEFAULT_DATE_FORMAT );

		$meta_query =
		array(
				array(
						'key'           => 'wpc_reservation_state',
						'value'         => array( $state ,'Confirmed','Processing','Completed' ),
						'compare'       => 'IN'
				),
				// for single slot
				array(
						'key'           => 'wpc_booking_date',
						'value'         => $wpc_booking_date,
						'compare'       => 'LIKE'
				),
		);

		$all_reservation = get_posts(
			array(
				'post_type'         => 'wpc_reservation',
				'numberposts'       => -1,
				'post_status'       => 'publish',
				'meta_type'         => 'NUMERIC',
				'meta_query'        => $meta_query
			)
		);


		$total_seat = 0;
		$date_booked_ids = $date_booked_table_ids = [];
		
		if ( !empty( $settings['reser_multi_schedule'] ) && $settings['reser_multi_schedule'] =="on") {
				// for multiple slot , count reservation
				if( count( $all_reservation )>0 ){
					$wpc_booking_start_time = date("H:i:s", strtotime($data['multi_start_schedule'])) ;
					$wpc_booking_end_time   = date("H:i:s", strtotime($data['multi_end_schedule'])) ;

					foreach ($all_reservation as $key => $value) {
						if( empty( $type ) ) {
							$total_seat_in_reserve  = get_post_meta($value->ID, 'wpc_total_guest', true);
							$total_seat += (int) $total_seat_in_reserve;
						} else {
							$from_time      = get_post_meta($value->ID, 'wpc_from_time', true);
							$wpc_from_time  = date("H:i:s", strtotime( $from_time )) ;

							if ( ( $wpc_from_time >= $wpc_booking_start_time ) && ( $wpc_from_time < $wpc_booking_end_time ) ) {
									$total_seat_in_reserve  = get_post_meta($value->ID, 'wpc_total_guest', true);
									$total_seat += (int) $total_seat_in_reserve;
							}
						}

					}
				}
		}
		else {
			// for single slot , count reservation
			if( count( $all_reservation )>0 ){
				
				if( empty( $type ) ) {
					$picked_time = strtotime( $from_time );

					foreach ($all_reservation as $key => $value) {
						$saved_from_time = get_post_meta($value->ID, 'wpc_from_time', true);
						$saved_to_time = get_post_meta($value->ID, 'wpc_to_time', true);
						$schedule_start_time   = strtotime( $saved_from_time );
						$schedule_end_time   = strtotime( $saved_to_time );
						if ( ( $picked_time >= $schedule_start_time ) && ( $picked_time < $schedule_end_time ) ) {
							$total_seat_in_reserve  = get_post_meta($value->ID, 'wpc_total_guest', true);
							$total_seat += (int) $total_seat_in_reserve;
						}
						
					}
				} else {
				// for default call select current date and time
					$schedule_start_time = '12:00 AM';
					$schedule_end_time   = '11:30 PM';

					if ( !empty( $settings['wpc_all_day_start_time'] ) && !empty( $settings['wpc_all_day_end_time'] ) ) {
							$schedule_start_time = !empty( $settings['wpc_all_day_start_time'] ) ? $settings['wpc_all_day_start_time'] : $schedule_start_time;
							$schedule_end_time   = !empty( $settings['wpc_all_day_end_time'] ) ? $settings['wpc_all_day_end_time'] : $schedule_end_time;
					} else if( isset( $settings['wpc_weekly_schedule'] ) && !empty( $settings['wpc_weekly_schedule'] ) ) {
							$selected_day = date( 'D', strtotime( $wpc_booking_date ) );
							foreach ( $settings['wpc_weekly_schedule'] as $index => $days_arr ) {
								if ( array_key_exists( $selected_day, $days_arr ) ) {
									$schedule_start_time = !empty($settings['wpc_weekly_schedule_start_time'][$index] ) ? $settings['wpc_weekly_schedule_start_time'][$index] : $schedule_start_time;
									$schedule_end_time   = !empty($settings['wpc_weekly_schedule_end_time'][$index] ) ? $settings['wpc_weekly_schedule_end_time'][$index] : $schedule_end_time;
								}
							}
					}

					$schedule_start_time = strtotime( $schedule_start_time );
					$schedule_end_time   = strtotime( $schedule_end_time );

					$from_time         = strtotime( $from_time );
					$to_time           = strtotime( $to_time );

					foreach ( $all_reservation as $key => $value ) {
						$calculate_seats = false;
						// saved from time and to time
						$saved_from_time = get_post_meta($value->ID, 'wpc_from_time', true);
						if ( empty( $saved_from_time ) ) {
							$saved_from_time = '12:00 AM';
						}
						$saved_from_time = strtotime( $saved_from_time );

						$saved_to_time = get_post_meta($value->ID, 'wpc_to_time', true);
						if ( empty( $saved_to_time ) ) {
							$saved_to_time = '11:30 PM';
						}
						$saved_to_time = strtotime( $saved_to_time );
						
						// no 'from and to' value has sent
						if ( empty( $from_time ) && empty( $to_time ) ) {
								$calculate_seats = true;
						}
						else if ( !empty( $from_time ) && empty( $to_time ) ){
							$calculate_seats = true;
						}
						else { // both 'from and to' value has sent
							if (
								( $saved_from_time >= $schedule_start_time ) && ( $saved_from_time < $schedule_end_time ) &&
								( $saved_to_time > $schedule_start_time ) && ( $saved_to_time <= $schedule_end_time ) &&
								( ( ( $saved_from_time >= $from_time && $saved_from_time < $to_time )
										|| ( $saved_to_time > $from_time && $saved_to_time <= $to_time ) ) ||
								( ( $from_time >= $saved_from_time && $from_time < $saved_to_time )
										|| ( $to_time > $saved_from_time && $to_time <= $saved_to_time ) ) )
								) {
								$calculate_seats = true;
							}
						}

						

						if ( $calculate_seats ) {
							$total_seat_in_reserve = get_post_meta($value->ID, 'wpc_total_guest', true);
							$total_seat += (int) $total_seat_in_reserve;

							$booked_ids       = get_post_meta($value->ID, 'wpc_booked_ids', true);
							$booked_table_ids = get_post_meta($value->ID, 'wpc_booked_table_ids', true);

							if( !empty( $booked_ids ) ) {
									$booked_ids = maybe_unserialize( $booked_ids );
									if( is_array( $booked_ids ) && count( $booked_ids ) > 0 ) {
											array_push( $date_booked_ids, $booked_ids );
									}
							}

							if( !empty( $booked_table_ids ) ) {
									$booked_table_ids = maybe_unserialize( $booked_table_ids );
									if( is_array( $booked_table_ids ) && count( $booked_table_ids ) > 0 ) {
											array_push( $date_booked_table_ids, $booked_table_ids );
									}
							}
						}
					}
				}
			}
		}

		$date_booking_data = [
				'total_seat'            => $total_seat,
				'date_booked_ids'       => $date_booked_ids,
				'date_booked_table_ids' => $date_booked_table_ids,
		];

		return empty( $type ) ? $total_seat : $date_booking_data;
    }

    /**
     * Pass reservation pro options to free
    */
    public function reservations_template($atts){
            $settings = Settings::instance()->get_settings_option();

            if ( is_array($atts) && isset($atts['calender_view']) ) {
            ?>
                    <div class="wpc-calender-view wpc-none"><?php echo esc_html($atts['calender_view']); ?></div>
            <?php
                    $column_lg = ($atts['calender_view'] == 'yes') ? 'wpc-col-lg-6' : 'wpc-col-lg-12';
                    $column_md = ($atts['calender_view'] == 'yes') ? 'wpc-col-md-12' : 'wpc-col-md-6';
                    $atts['column_lg'] = $column_lg;
                    $atts['column_md'] = $column_md;
            } else {
                    $atts = array();
                    $atts['calender_view'] = 'yes';
                    $atts['column_lg']     = 'wpc-col-lg-6';
                    $atts['column_md']     = 'wpc-col-md-12';
            }
            $atts['reservation_extra_field']= \Wpcafe_Pro::plugin_dir() . "core/shortcodes/views/reservation/reservation-extra-fields.php";
            $data                           = Pro_Utilities::multi_schedule_time_seat( $settings );
            $capacity                       = !empty( $data['capacity']) ? $data['capacity'] : 0;
            $atts['seat_capacity']          = $capacity;
            $atts['booking_status']         = $this->reser_capacity_status();
            $atts['show_form_field']        = isset( $settings['show_form_field'] ) ? $settings['show_form_field'] : 'on';
            $atts['required_from_field']    = isset( $settings['required_from_field'] ) ? $settings['required_from_field'] : 'on';
            $atts['from_field_label']       = isset( $settings['from_field_label'] ) && $settings['from_field_label'] !=="" ? $settings['from_field_label'] : esc_html__('From when?','wpcafe-pro');
            $atts['show_to_field']          = isset( $settings['show_to_field'] ) ? $settings['show_to_field'] : 'on';
            $atts['required_to_field']      = isset( $settings['required_to_field'] ) ? $settings['required_to_field'] : 'on';
            $atts['to_field_label']         = isset( $settings['to_field_label'] ) && $settings['to_field_label'] !==""  ? $settings['to_field_label'] : esc_html__('Until?','wpcafe-pro');
            $atts['first_booking_button']   = isset( $settings['first_booking_button'] ) && $settings['first_booking_button'] !==""  ? $settings['first_booking_button'] : esc_html__('Book a table','wpcafe-pro');
            $atts['form_booking_button']    = isset( $settings['form_booking_button'] ) && $settings['form_booking_button'] !==""  ? $settings['form_booking_button'] : esc_html__('Confirm Booking','wpcafe-pro');
            $atts['form_cancell_button']    = isset( $settings['form_cancell_button'] ) && $settings['form_cancell_button'] !==""  ? $settings['form_cancell_button'] : esc_html__('Request Cancellation','wpcafe-pro');

            return $atts;
    }

    /**
     * reservation extra field in reservation report function
     */
    public function reservations_report_extra_field( $args , $id ){
		$settings               = Settings::instance()->get_settings_option();
		$reserv_extra_label     = [];

		if(  !empty( get_post_meta( $id,'reserv_extra' ) )  ){
			// get from meta
			$reserv_extra_label     =  get_post_meta( $id,'reserv_extra' )[0];

			if ( !empty( $reserv_extra_label ) ) {
				foreach ($reserv_extra_label as $key => &$item) {
					if ( "checkbox" == $item['type'] ) {
						if ( !empty( $reserv_extra_label[$key]['options'] )) {
							$reserv_extra_label[$key]['options']  = is_array( $item['options'] ) ? $item['options'] : explode(",",$item['options']);
						}else{
							$item['options']  = $settings['wpc_extra_field_option'][$key];
						}
					} 
				}
			}	

		}else{
			// get from settings
			$extra_field_arr        = array();
			if ( isset( $settings['reserv_extra_label'] ) ) {
				foreach ($settings['reserv_extra_label'] as $key => $value) {
					$extra_field_arr[$key]['label'] = $settings['reserv_extra_label'][$key];
					$extra_field_arr[$key]['type']  = $settings['wpc_extra_field_type'][$key];
					if ( "text" == $extra_field_arr[$key]['type'] ) {
						$extra_field_arr[$key]['value']  = "";
					} else {
						$extra_field_arr[$key]['value']  = "";
						$extra_field_arr[$key]['options'] = !empty($settings['wpc_extra_field_option'][$key]) ? $settings['wpc_extra_field_option'][$key] : [];
					}
					
				}
			}	

			$reserv_extra_label     =  $extra_field_arr;

		}

		foreach ( $reserv_extra_label as $key => $value ) {
			if( $value !== null  ):
				if ( "checkbox" == $value['type'] ) {
				$args['reserv_extra_'.$key] = [
					'label'    => esc_html( $value['label']),
					'type'     => $value['type'],
					'priority' => 1,
					'required' => false,
					'desc'     => '',
					'attr'     => ['class' => 'wpc-label-item'] 
				];
				$args['reserv_extra_'.$key]['options'] = $value['options'];
				$checked_value = is_array($value['value']) ? [] : explode(',', str_replace(' ', '', $value['value'] ) );
				$args['reserv_extra_'.$key]['checked_value'] = $checked_value;
				$args['reserv_extra_'.$key]['row_key'] = $key;
					
				} else {

					$args['reserv_extra_'.$key] = [
						'label'    => esc_html( $value['label']),
						'type'     => $value['type'],
						'priority' => 1,
						'required' => false,
						'desc'     => '',
						'attr'     => ['class' => 'wpc-label-item'] 
					];
				}
			endif;

		}
		
		return $args;
    }

    public function reservation_field_array(){
            $settings = Settings::instance()->get_settings_option();

            $reservation_arr = array( 
                    'wpc_name'          => 'Name',
                    'wpc_email'         => 'Email',
                    'wpc_phone'         => 'Phone',
                    'wpc_booking_date'  => 'Booking date',
                    'wpc_guest_count'   => 'Guest',
                    'wpc_from_time'     => 'Start time',
                    'wpc_to_time'       => 'End time',
                    'wpc_message'       => 'Message',
            );

            if ( !empty($settings['show_branches']) ) {
                    $reservation_arr[ 'wpc_branch'] = 'Branch';
            }

            if( !empty( $settings['reserv_extra_label'] ) && !empty( $settings['reserv_extra_label'][0] )){
                    
                    foreach ( $settings['reserv_extra_label']  as $key => $value ) {
						$reservation_arr[ 'reserv_extra_'.$key ] = $value;
                    }
                    
            }

            return  $reservation_arr;
    }

}