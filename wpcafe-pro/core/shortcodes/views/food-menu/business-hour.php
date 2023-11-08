<?php

use WpCafe_Pro\Utils\Utilities;

if ( !empty($settings) && is_array($settings)) {

    $today      = date('D');
    $all_days   = ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'];

    $business_hour_label =  isset($settings['business_hour_label']) &&  $settings['business_hour_label'] !== '' ? $settings['business_hour_label'] : 'Our business hour';
    ?>
        <h3 class="business_hour_label"><?php echo esc_html( $business_hour_label ) ?></h3>
    <?php
    // If no schedule is set from admin
    if ( 
        ( ( is_array( $settings['multi_start_time'] ) && count($settings['multi_start_time']) == 0 )
    && ( is_array( $settings['multi_end_time'] ) && count($settings['multi_end_time']) == 0 ) )
        &&
        ( ( is_array( $settings['multi_diff_start_time'] ) && count($settings['multi_diff_start_time']) == 0 )
    && ( is_array( $settings['multi_diff_start_time'] ) && count($settings['multi_diff_start_time']) == 0 ) )
        &&
    ( ( is_array( $settings['wpc_weekly_schedule_start_time'] ) && count($settings['wpc_weekly_schedule_start_time'])  == 0 )
    && ( is_array( $settings['wpc_weekly_schedule_end_time'] ) && count($settings['wpc_weekly_schedule_end_time']) == 0 ) )
        &&
    ( ( empty( $settings['wpc_all_day_start_time'] ) || $settings['wpc_all_day_start_time'] =="" ) &&
    ( empty( $settings['wpc_all_day_end_time'] ) || $settings['wpc_all_day_end_time'] =="" ) )

    ) {
       ?>
        <div class="wpc_pro_business_hour">
            <?php echo esc_html__('Schedule is not set', 'wpcafe-pro');?>
        </div>
       <?php 
    }

    else if(isset($settings['reser_multi_schedule']) && ( !empty( $settings['multi_start_time'] ) && count($settings['multi_start_time'])>0 )
    && ( !empty( $settings['multi_end_time'] ) && count($settings['multi_end_time'])>0 ) ){
        
        // Multi slot all days schedule of week.
        ?>
        <ul class="wpc_pro_business_hour slot-item">
            <?php
                $schedules = "";
                for ($i=0; $i < count($settings['multi_start_time']); $i++) { 
                    $schedule_name = isset( $settings['schedule_name'][$i] ) && $settings['schedule_name'][$i] !=="" ? $settings['schedule_name'][$i] : esc_html__("Schedule ","wpcafe-pro").$i;
                    $start  = !empty( $settings['multi_start_time'][$i] ) ? $settings['multi_start_time'][$i] : "" ;
                    $end    = !empty( $settings['multi_end_time'][$i] ) ? $settings['multi_end_time'][$i] : "" ;
                    $schedules .=esc_html__( $settings['schedule_name'][$i] , 'wpcafe-pro' ) ." -- ". 
                    $start ."-". $end  ." ";
                }

                ?>
                <ul class="lebel2">
                <?php
                foreach ( $all_days as $key => $value) {
                    $result = Utilities::checking_off_day( $settings['wpc_reservation_holiday'] , $value);
                    if ( $result ) {
                    ?>
                        <li><?php esc_html_e($value)?></li>
                    <?php
                        esc_html_e($schedules);
                    }
                }
                ?>
                </ul>
                <?php

            ?>
        </ul>
        <?php
    }

    else if(isset($settings['reser_multi_schedule']) && ( !empty( $settings['multi_diff_start_time'] ) && count($settings['multi_diff_start_time'])>0 )
    && ( !empty( $settings['multi_diff_end_time'] ) && count($settings['multi_diff_end_time'])>0 ) ){
        
        // Multi slot different days schedule of week.
        ?>
        <div class="wpc_pro_business_hour">
            <?php
                if ($all_days_schedule == 'yes') {
                    if ( is_array( $settings['multi_diff_weekly_schedule'] ) && count($settings['multi_diff_weekly_schedule']) > 0) {
                        for ($i = 0; $i < count($settings['multi_diff_weekly_schedule']); $i++) {
                            foreach ($settings['multi_diff_weekly_schedule'][$i] as $key => $value) {

                                $business_hour_args = array(
                                    'alldays'   => true,
                                    'day_name'  => $key,
                                    'start_time'=> $settings['multi_diff_start_time'][$i],
                                    'end_time'  =>  $settings['multi_diff_end_time'][$i],
                                    'holiday'   =>  $settings['wpc_reservation_holiday']
                                );
                                Utilities::business_hour_multislot( $business_hour_args );
                            }
                        }
                    }
                } else {
                    if ( is_array( $settings['multi_diff_weekly_schedule'] ) && count($settings['multi_diff_weekly_schedule']) > 0  ) {
                        
                        $days = array();$day_no = false;

                        for ($i = 0; $i < count($settings['multi_diff_weekly_schedule']); $i++) {
                            $days = array_keys( $settings['multi_diff_weekly_schedule'][$i] );
                            if ( in_array( $today , $days ) ) {
                                $day_no = $i;
                            }
                        }

                        if ( is_numeric($day_no) ) {
                            $business_hour_args = array(
                                'alldays'   => true,
                                'day_name'  => $today,
                                'start_time'=> $settings['multi_diff_start_time'][$day_no],
                                'end_time'  =>  $settings['multi_diff_end_time'][$day_no],
                                'holiday'   =>  $settings['wpc_reservation_holiday']
                            );
                            // Utilities::business_hour_markup( $business_hour_args );
                        }
                        else{
                            echo esc_html__("Today's schedule is not set", "wpcafe-pro");
                        }
                    }
                }
            ?>
        </div>
        <?php
    }

    else {
        // single slot weekly schedule
        if (
            !empty($settings['wpc_weekly_schedule']) && ( !empty($settings['wpc_weekly_schedule_start_time']) && $settings['wpc_weekly_schedule_start_time'] !== '' )
            && ( !empty($settings['wpc_weekly_schedule_end_time']) && $settings['wpc_weekly_schedule_end_time'] !== '' )
        ) {
            
            // Single slot weekly schedules of week.
        ?>
            <div class="wpc_pro_business_hour">
                <div class="slot-item">
                    <ul>
                        <?php
                        if ($all_days_schedule == 'yes') {
                            //exception day for all day schedule
                            if(is_array( $settings['wpc_exception_date'] ) && count($settings['wpc_exception_date']) > 0){                        
                                foreach ($settings['wpc_exception_date'] as $key => $value) {
                                    $day = date('D', strtotime($value));

                                    $business_hour_args = array(
                                        'alldays'   => true,
                                        'day_name'  => $day,
                                        'start_time'=> $settings['wpc_exception_start_time'][$key],
                                        'end_time'  => $settings['wpc_exception_end_time'][$key],
                                        'holiday'   =>  $settings['wpc_reservation_holiday']
                                    );

                                    Utilities::business_hour_markup( $business_hour_args );    
                                }                            
                            }

                            if ( is_array( $settings['wpc_weekly_schedule'] ) && count($settings['wpc_weekly_schedule']) > 0) {
                                for ($i = 0; $i < count($settings['wpc_weekly_schedule']); $i++) {
                                    foreach ($settings['wpc_weekly_schedule'][$i] as $key => $value) {

                                        $business_hour_args = array(
                                            'alldays'   => true,
                                            'day_name'  => $key,
                                            'start_time'=> $settings['wpc_weekly_schedule_start_time'][$i],
                                            'end_time'  =>  $settings['wpc_weekly_schedule_end_time'][$i],
                                            'holiday'   =>  $settings['wpc_reservation_holiday']
                                        );

                                        Utilities::business_hour_markup( $business_hour_args );
                                    }
                                }
                            }

                            //holiday for all day schedule
                        

                        } else {
                            if ( is_array( $settings['wpc_weekly_schedule'] ) && count($settings['wpc_weekly_schedule']) > 0  ) {
                                
                                $days = array();$day_no = false;

                                for ($i = 0; $i < count($settings['wpc_weekly_schedule']); $i++) {
                                    $days = array_keys( $settings['wpc_weekly_schedule'][$i] );
                                    if ( in_array( $today , $days ) ) {
                                        $day_no = $i;
                                    }
                                }
                                
                                if ( is_numeric($day_no) ) {
                                    $business_hour_args = array(
                                        'alldays'   => true,
                                        'day_name'  => $today,
                                        'start_time'=> $settings['wpc_weekly_schedule_start_time'][$day_no],
                                        'end_time'  =>  $settings['wpc_weekly_schedule_end_time'][$day_no],
                                        'holiday'   =>  $settings['wpc_reservation_holiday']
                                    );

                                    Utilities::business_hour_markup( $business_hour_args );
                                }
                                else{
                                    echo esc_html__("Today's schedule is not set", "wpcafe-pro");
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        <?php
        }

        // Single slot all days schedules of week.
        else if (
            ( !empty($settings['wpc_all_day_start_time']) && $settings['wpc_all_day_start_time'] !== '') 
            &&  ( !empty($settings['wpc_all_day_end_time']) && $settings['wpc_all_day_end_time'] !== '' ) )
        {
            
            // Single slot all days schedule of week.
    
            if ($all_days_schedule == 'yes') {
            ?>
                <ul class="wpc_pro_business_hour slot-item">
                    <?php
                    foreach ($all_days as $key => $value) {
                        $business_hour_args = array(
                            'alldays'   => true,
                            'day_name'  => $value,
                            'start_time'=> $settings['wpc_all_day_start_time'],
                            'end_time'  =>  $settings['wpc_all_day_end_time'],
                            'holiday'   =>  $settings['wpc_reservation_holiday']
                        );
                        Utilities::business_hour_markup( $business_hour_args );
                    }
                    ?>
                </ul>
            <?php
            } else {
            ?>
                <div class="wpc_pro_business_hour">
                    <?php
                    $business_hour_args = array(
                        'alldays'   => false,
                        'day_name'  => $today,
                        'start_time'=> $settings['wpc_all_day_start_time'],
                        'end_time'  =>  $settings['wpc_all_day_end_time'],
                        'holiday'   =>  $settings['wpc_reservation_holiday']
                    );
                        Utilities::business_hour_markup( $business_hour_args );
                    ?>
                </div>
            <?php
            }
        }
    }

    //holiday schedule
    if(is_array( $settings['wpc_reservation_holiday'] ) && count($settings['wpc_reservation_holiday']) > 0){
        ?>
        <div class="wpc_pro_business_hour slot-item">
            <strong><?php echo esc_html__('Our Holidays', 'wpcafe-pro'); ?></strong>
            <ul>
                <?php                       
                foreach ($settings['wpc_reservation_holiday'] as $value) {
                    $day = date('D', strtotime($value));
                    $date = date(get_option('date_format'), strtotime($value));
                    ?>
                    <li class=""><?php echo esc_html($date); ?><?php echo esc_html__(', Day: '); ?><?php echo esc_html($day); ?></li>
                    <?php    
                }
                ?> 
            </ul>
        </div>
        <?php                           
    }

}
