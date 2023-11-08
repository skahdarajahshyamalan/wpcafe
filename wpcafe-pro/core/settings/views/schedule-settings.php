<?php
use WpCafe\Utils\Wpc_Utilities;
$settings                   = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
$reser_multi_schedule       =  isset($settings['reser_multi_schedule'] ) && $settings['reser_multi_schedule'] == 'on'  ? 'checked' : '';

$weekly_active = '';
$daily_active = '';
if(!empty($settings['multi_diff_weekly_schedule'])){
    $weekly_active = 'wpc-schedule-active';
} else {
    $daily_active = 'wpc-schedule-active';
}

$markup_fields = [
    'reser_multi_schedule' => [
        'item' => [
            'options'  =>['on'=>'on'],
            'label'    => esc_html__( 'Reservation Multi-slot Schedule?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Select single slot or multi-slot schedule in reservation form', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'reser_multi_schedule' => $reser_multi_schedule ],
    ],    
];

foreach ( $markup_fields as $key => $info ) {
    $this->get_field_markup( $info['item'], $key, $info['data'] );
}

?>
        <!-- Multi-slot schedule in different days -->
        <div class="wpc-schedule-tab wpc-multi-slot-tab <?php  echo ( $reser_multi_schedule !== 'checked' ) ? esc_html('hide_field') : '' ?>">
            <div class="wpc-schedule-tab-wrapper wpc-label-item mb-15">
                <div class="wpc-label">
                    <label><?php esc_html_e('Select Reservation Schedule', 'wpcafe-pro'); ?></label>
                    <div class="wpc-desc"> <?php esc_html_e('Select day slot or weekly slot schedule in multi-slot schedule', 'wpcafe-pro'); ?> </div>
                </div>
                <div class="wpc-meta wpc-align-top">
                    <ul class="wpc-nav-schedule">
                        <li>
                            <a class="wpc-tab-a <?php echo esc_attr($weekly_active); ?> wpc-tooltip" data-id="weekly-schedule">
                                <?php echo esc_html__('Weekly Schedule', 'wpcafe-pro'); ?>
                                <small class="wpc-tooltip-angle"></small>
                            </a>
                        </li>
                        <li>
                            <a class="wpc-tab-a wpc-tooltip <?php echo esc_attr($daily_active); ?>" data-id="daily-schedule">
                                <?php echo esc_html__('Everyday Schedule', 'wpcafe-pro'); ?>
                                <small class="wpc-tooltip-angle"></small>
                            </a>
                        </li>
                    </ul>              
                </div>
            <div class="wpc-tab-content wpc-slot-schedule" id="multi_time_exist" 
            data-multi_time_exist="<?php echo esc_attr__('Time already exist. Please select another day.','wpcafe-pro');?>" 
            data-day_exist="<?php echo esc_attr__('day exist. Please check another day','wpcafe-pro');?>" 
            >
                <div class="wpc-tab <?php echo esc_attr($weekly_active); ?>" id="multi_diff_block" data-id="weekly-schedule" data-multi_diff_block="<?php echo esc_attr__("You have already set everyday schedule. Please unset everyday schedule.","wpcafe-pro")?>">
                    <div class="wpc-label-item multi_schedule_block mb-0">
                        <div class="wpc-meta">
                            <div class="multi_diff_block">
                                <h5 class="wpc_pb_two"><?php esc_html_e('Weekly (set multiple opening and closing schedule for each day of a week separately)', 'wpcafe-pro'); ?></h5>
                                <?php
                                $schedule['multi_diff_weekly_schedule']  = isset( $settings['multi_diff_weekly_schedule'] ) ? $settings['multi_diff_weekly_schedule'] : [];
                                $schedule['multi_diff_start_time']       = isset( $settings['multi_diff_start_time'] ) ? $settings['multi_diff_start_time'] : [];
                                $schedule['multi_diff_end_time']         = isset( $settings['multi_diff_end_time'] ) ? $settings['multi_diff_end_time'] : [];
                                $schedule['diff_schedule_name']          = isset( $settings['diff_schedule_name'] ) ? $settings['diff_schedule_name'] : [];
                                $schedule['diff_seat_capacity']          = isset( $settings['diff_seat_capacity'] ) ? $settings['diff_seat_capacity'] : [];
    
                                if( !empty( $schedule['multi_diff_weekly_schedule'][0] ) && !empty( $schedule['multi_diff_start_time'][0] ) ){ 
                                    for ( $index=0; $index < count( $schedule['multi_diff_weekly_schedule'] ) ; $index ++) { ?>
                                        <div class="schedule_block week_diff_schedule_wrap week_diff_schedule_wrap_<?php echo intval($index)?>" data-schedule_diff_block="<?php echo intval($index)?>">
                                            <div class="wpc-weekly-schedule-list">
                                                <?php foreach ($week_days as $key => $value) { ?>
                                                    <input type="checkbox" name="multi_diff_weekly_schedule[<?php echo intval($index)?>][<?php echo esc_attr($value);?>]"
                                                        class="<?php echo esc_html(strtolower($value.intval($index)));?>" id="<?php echo esc_attr(strtolower($value).intval($index));?>"
                                                        <?php echo isset( $schedule['multi_diff_weekly_schedule'][$index][$value] ) ? 'checked' : ''?>
                                                    /><label for="<?php echo esc_attr(strtolower($value).intval($index));?>"><?php echo esc_html($value); ?></label>
                                                <?php } ?>
                                            </div>

                                            <?php 
                                            if ( is_array( $schedule['multi_diff_start_time'][$index] ) ) { ?>
                                                <div class="wpc-schedule-field multi_schedule_wrap mb-2">
                                                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Start Time', 'wpcafe-pro'); ?></p>
                                                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('End Time', 'wpcafe-pro'); ?></p>
                                                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Schedule Name', 'wpcafe-pro'); ?></p>
                                                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Seat Capacity', 'wpcafe-pro'); ?></p>
                                                </div>
                                                <?php
                                                for ( $multi_index =0; $multi_index < count( $schedule['multi_diff_start_time'][$index]   ) ; $multi_index ++) { 
                                                ?>
                                                    <div class="schedule_block wpc-schedule-field" data-id="<?php echo intval($index).intval($multi_index); ?>">
                                                        <input type="text"  name="multi_diff_start_time[<?php echo intval($index)?>][<?php echo intval($multi_index)?>]" id="<?php echo intval($index).intval($multi_index); ?>" value="<?php echo Wpc_Utilities::wpc_render( $schedule['multi_diff_start_time'][ $index ][ $multi_index ] ); ?>" class="multi_diff_start_time multi_diff_start_time_<?php echo intval($index); ?> multi_diff_start_time_<?php echo intval($index).intval($multi_index); ?> ml-2 mr-1 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Start time', 'wpcafe-pro'); ?>"/>
                                                        <input type="text"  name="multi_diff_end_time[<?php echo intval($index)?>][<?php echo intval($multi_index)?>]"   id="<?php echo intval($index).intval($multi_index); ?>" value="<?php echo Wpc_Utilities::wpc_render( $schedule['multi_diff_end_time'][ $index ][ $multi_index ] ); ?>" class="multi_diff_end_time multi_diff_end_time_<?php echo intval($index); ?> multi_diff_end_time_<?php echo intval($index).intval($multi_index); ?> ml-2 wpc-settings-input attr-form-control"  placeholder="<?php echo esc_attr__('End time', 'wpcafe-pro'); ?>" />
                                                        <input type="text" name="diff_schedule_name[<?php echo intval($index)?>][<?php echo intval($multi_index)?>]" id="<?php echo intval($index).intval($multi_index) ;?>" value="<?php echo Wpc_Utilities::wpc_render( $schedule['diff_schedule_name'][ $index ][ $multi_index ] ); ?>" class="diff_schedule_name diff_schedule_name_<?php echo intval($index); ?> diff_schedule_name_<?php echo intval($index).intval($multi_index); ?> ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Schedule Name', 'wpcafe-pro'); ?>" />
                                                        <input type="number" name="diff_seat_capacity[<?php echo intval($index)?>][<?php echo intval($multi_index)?>]" min="1" id="<?php echo intval($index).intval($multi_index) ;?>" value="<?php echo Wpc_Utilities::wpc_render( $schedule['diff_seat_capacity'][ $index ][ $multi_index ] ); ?>" class="diff_seat_capacity  diff_seat_capacity_<?php echo intval($index);?> diff_seat_capacity_<?php echo intval($index).intval($multi_index); ?> ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Seat Capacity', 'wpcafe-pro'); ?>" />
                                                        <div class="wpc_multi_weekly_clear" id="<?php echo intval($index).intval($multi_index); ?>" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></div>
                                                        <?php if( $multi_index != 0 ) { ?>
                                                            <span class="wpc-btn-close dashicons dashicons-no-alt remove_multi_time_block pl-1"></span>
                                                        <?php } ?>
                                                        <div class="weekly_multi_message_<?php echo intval($index).intval($multi_index) ?> wpc-default-guest-message"></div>
                                                    </div>
                                                <?php 
                                                }
                                            } 
                                            ?>
                                            <span class="dashicons wpc-btn-close dashicons-plus add_multi_time_block pl-1 wpc-tooltip" data-remove_text="<?php echo esc_attr__('Remove schedule','wpcafe-pro'); ?>" data-clear_text="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"  data-diff_start_time="<?php echo esc_attr__('Start Time', 'wpcafe-pro'); ?>" data-diff_end_time="<?php echo esc_attr__('End Time', 'wpcafe-pro'); ?>" data-diff_seat_capacity="<?php echo esc_attr__('Seat Capacity', 'wpcafe-pro'); ?>" data-diff_schedule_name="<?php echo esc_attr__('Schedule Name', 'wpcafe-pro'); ?>">
                                                <small class="wpc-tooltip-angle"></small>
                                            </span>
                                            <?php if( $index != 0 ) { ?>
                                                <span class="wpc-btn-close dashicons dashicons-no-alt remove_multi_schedule_block pl-1"></span>
                                            <?php } ?>
                                        </div>
                                        <?php
                                    }
                                }
                                else { ?>
                                    <div class="schedule_block week_diff_schedule_wrap week_diff_schedule_wrap_0" data-schedule_diff_block="0">
                                        <div class="wpc-weekly-schedule-list">
                                            <?php foreach ($week_days as $key => $value) { ?>
                                                <input type="checkbox" name="multi_diff_weekly_schedule[0][<?php echo esc_html($value);?>]"
                                                    class="<?php echo esc_html(strtolower($value.'0'));?>" id="<?php echo esc_html(strtolower($value.'0'));?>"
                                                /><label for="<?php echo esc_html(strtolower($value.'0'));?>"><?php echo esc_html($value); ?></label>
                                            <?php } ?>
                                        </div>

                                        <div class="wpc-schedule-field schedule_block d-flex mb-2" data-id="0">
                                            <input type="text" name="multi_diff_start_time[0][0]" id="00" class="multi_diff_start_time multi_diff_start_time_0 multi_diff_start_time_00 mr-1 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Start Time', 'wpcafe-pro'); ?>"/>
                                            <input type="text" name="multi_diff_end_time[0][0]" id="00" class="multi_diff_end_time multi_diff_end_time_0 multi_diff_end_time_00 wpc-settings-input attr-form-control"  placeholder="<?php echo esc_attr__('End Time', 'wpcafe-pro' ); ?>" disabled="disabled"/>
                                            <input type="text" name="diff_schedule_name[0][0]" id="00" class="diff_schedule_name diff_schedule_name_0 diff_schedule_name_00 ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Schedule Name', 'wpcafe-pro'); ?>" />
                                            <input type="number" name="diff_seat_capacity[0][0]" min="1" id="00" class="diff_seat_capacity diff_seat_capacity_0 diff_seat_capacity_00 ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Seat Capacity', 'wpcafe-pro'); ?>" />
                                            <div class="wpc_multi_weekly_clear" id="00" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></div>
                                        </div>
                                        <span class="dashicons wpc-btn xxx dashicons-plus add_multi_time_block pl-1 wpc-tooltip" data-remove_text="<?php esc_attr_e('Remove Fields', 'wpcafe-pro'); ?>" data-clear_text="<?php esc_attr_e('Reset Fields', 'wpcafe-pro'); ?>" data-title="<?php esc_attr_e('Add Same Day Schedule', 'wpcafe-pro'); ?>" data-diff_start_time="<?php echo esc_attr__('Start Time', 'wpcafe-pro'); ?>" data-diff_end_time="<?php echo esc_attr__('End time', 'wpcafe-pro' ); ?>" data-diff_seat_capacity="<?php echo esc_attr__('Seat Capacity', 'wpcafe-pro'); ?>" data-diff_schedule_name="<?php echo esc_attr__('Schedule Name', 'wpcafe-pro'); ?>">
                                            <small class="wpc-tooltip-angle"></small>
                                        </span>
                                        <div class="weekly_multi_message_00 wpc-default-guest-message"></div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="wpc_flex_reverse multi-diff-weekly-btn" >
                                <span class="add_multi_diff_schedule_block wpc-btn-text wpc-tooltip" data-remove_text="<?php esc_attr_e('Remove Fields', 'wpcafe-pro'); ?>" data-clear_text="<?php esc_attr_e('Reset Fields', 'wpcafe-pro'); ?>" data-title="<?php esc_attr_e('Add Different Day Schedule', 'wpcafe-pro'); ?>" data-same-day-title="<?php esc_attr_e('Add Same Day Schedule', 'wpcafe-pro'); ?>" data-diff_start_time="<?php echo esc_attr__('Start Time', 'wpcafe-pro'); ?>" data-diff_end_time="<?php echo esc_attr__('End Time', 'wpcafe-pro'); ?>" data-diff_seat_capacity="<?php echo esc_attr__('Seat Capacity', 'wpcafe-pro'); ?>" data-diff_schedule_name="<?php echo esc_attr__('Schedule Name', 'wpcafe-pro'); ?>">
                                        <?php echo esc_html__('Add','wpcafe-pro'); ?>
                                        <small class="wpc-tooltip-angle"></small>
                                </span>
                            </div>
                            <div class="wpc-all-day-multiple-schedule"></div>
                        </div>
                    </div>
                </div>
                <div class="wpc-tab <?php echo esc_attr($daily_active); ?>" 
                id="every_diff_block" data-id="daily-schedule"
                data-every_diff_block="<?php echo esc_attr__("You have already set weekly schedule. Please unset weekly schedule.","wpcafe-pro")?>">
                    <!-- Multi slot schedule in add days -->
                    <div class="wpc-label-item multi_schedule_block mb-0">
                        <div class="wpc-meta">
                            <div class="multi_schedule">
                                <h5 class="wpc_pb_two"><?php esc_html_e('Reservation Multi-slot Schedule for Each Day', 'wpcafe-pro'); ?></h5>
                                <div class="wpc-desc mb-2"> <?php esc_html_e('All schedule slots applies in each days except exceptional days.
                                If you change single slot/ multi slot . You need to change Maximum party size from "Key options" ', 'wpcafe-pro'); ?> </div>
                                <?php
                                $schedule_name          =  isset($settings['schedule_name'] ) ? $settings['schedule_name'] : [];
                                $multi_start_time       = isset($settings['multi_start_time']) ? $settings['multi_start_time'] : [];
                                $multi_end_time         = isset($settings['multi_end_time']) ? $settings['multi_end_time'] : [];
                                $seat_capacity          = isset($settings['seat_capacity']) ? $settings['seat_capacity'] : [];

                                if (is_array( $multi_start_time ) && count($multi_start_time) > 0 ) {
                                    ?>
                                    <div class="wpc-schedule-field multi_schedule_wrap mb-2">
                                        <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Start Time', 'wpcafe-pro'); ?></p>
                                        <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('End Time', 'wpcafe-pro'); ?></p>
                                        <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Schedule Name', 'wpcafe-pro'); ?></p>
                                        <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Seat Capacity', 'wpcafe-pro'); ?></p>
                                    </div>
                                    <?php
                                    for ($index = 0; $index < count($multi_start_time); $index++) {
                                        ?>
                                            <div class="wpc-schedule-field multi_schedule_wrap mb-2">
                                            
                                                <input type="text" name="multi_start_time[]" value="<?php echo esc_attr($multi_start_time[$index]); ?>" id="<?php echo intval($index); ?>" class="multi_all_start_time  multi_all_start_time_<?php echo intval($index);?> ml-2 mr-1 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Start Time', 'wpcafe-pro'); ?>" />
                                                <input type="text" name="multi_end_time[]" value="<?php echo esc_attr($multi_end_time[$index]); ?>" id="<?php echo intval($index); ?>"  class="multi_all_end_time  multi_all_end_time_<?php echo intval($index) ?> ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('End Time', 'wpcafe-pro'); ?>" />
                                                <input type="text" name="schedule_name[]" value="<?php echo esc_attr($schedule_name[$index]); ?>" class="all_schedule_name all_schedule_name_<?php echo intval($index) ?> ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Schedule Name', 'wpcafe-pro'); ?>" />
                                                <input type="number" name="seat_capacity[]" min="1" value="<?php echo esc_attr($seat_capacity[$index]); ?>" class="all_seat_capacity all_seat_capacity_<?php echo intval($index) ?> ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Seat Capacity', 'wpcafe-pro');?>" />
                                                <div class="wpc_all_multi_clear" id="<?php echo intval($index) ?>"><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></div>
                                                <?php if ($index != 0) { ?>
                                                <span class="wpc-btn-close dashicons dashicons-no-alt remove_reserve_multi_field pl-1"></span>
                                            <?php } ?>
                                            </div>
                                        <div class="allday_multi_message_<?php echo intval($index); ?> wpc-default-guest-message"></div>
                                        <?php
                                    }
                                } else { ?>
                                    <div class="wpc-schedule-field multi_schedule_wrap mb-2">
                                        <input type="text" name="multi_start_time[]" id="0" class="multi_all_start_time  multi_all_start_time_0  ml-2 mr-1 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Start Time', 'wpcafe-pro'); ?>" />
                                        <input type="text" name="multi_end_time[]" id="0" class="multi_all_end_time  multi_all_end_time_0 ?> ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('End Time', 'wpcafe-pro'); ?>" disabled="disabled" />
                                        <input type="text" name="schedule_name[]" class="all_schedule_name all_schedule_name_0 ?> ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Schedule Name', 'wpcafe-pro'); ?>" />
                                        <input type="number" name="seat_capacity[]" min="1" class="all_seat_capacity all_seat_capacity_0 ?> ml-2 wpc-settings-input attr-form-control" placeholder="<?php echo esc_attr__('Seat Capacity', 'wpcafe-pro'); ?>" />
                                        <div class="wpc_all_multi_clear" id="0"><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></div>
                                    </div>
                                    <div class="allday_multi_message_0 wpc-default-guest-message"></div>
                            <?php }  ?>
                            </div>
                            <!-- Add button  -->
                            <div class="wpc_flex_reverse multi_block_add_section">
                                <span class="add_multi_schedule wpc-btn-text" data-title="<?php esc_attr_e('Add More', 'wpcafe-pro'); ?>" data-start_time="<?php echo esc_attr__('Start Time', 'wpcafe-pro'); ?>"="<?php echo esc_attr__("Start Time", "wpcafe-pro"); ?>"
                                    data-end_time="<?php echo esc_attr__("End Time", "wpcafe-pro" ); ?>"
                                    data-schedule_name="<?php echo esc_attr__("Schedule Name", "wpcafe-pro"); ?>"
                                    data-seat_capactiy="<?php echo esc_attr__("Seat Capacity", "wpcafe-pro"); ?>"
                                    data-clear_text="<?php echo esc_attr__("Reset Fields", "wpcafe-pro"); ?>"
                                    data-remove_text="<?php echo esc_attr__("Remove schedule for each days", "wpcafe-pro"); ?>"
                                >
                                    <?php echo esc_html__('Add','wpcafe-pro'); ?>
                                    <small class="wpc-tooltip-angle"></small>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <div class="wpc-label-item single_schedule <?php  echo ( $reser_multi_schedule === 'checked' ) ? esc_attr('hide_field') : '' ?>">
        <div class="wpc-label">
            <label for="rest_max_reservation"><?php esc_html_e('Seat Capacity for Single Slot', 'wpcafe-pro'); ?></label>
            <div class="wpc-desc"> <?php esc_html_e('If you use single slot schedule option, this will be counted as the total seat capacity of your restaurant.', 'wpcafe-pro'); ?></div>
        </div>
        <div class="wpc-meta">
            <?php
            $min_geust_no = isset( $settings['rest_max_reservation'] ) && $settings['rest_max_reservation'] !== '' ? $settings['rest_max_reservation'] : 20;            
            ?>
            <input value="<?php echo esc_attr( $min_geust_no );?>" type="number" min="1" id="rest_max_reservation" class="wpc-settings-input" name="rest_max_reservation" placeholder="<?php echo esc_attr__('No. of Guests', 'wpcafe-pro'); ?>" />
        </div>
    </div>

<?php return; ?>