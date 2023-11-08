
<?php

use WpCafe\Utils\Wpc_Utilities;
$wpc_pro_pickup_message            = isset($wpc_pro_menu_settings['wpc_pro_pickup_message']) ? $wpc_pro_menu_settings['wpc_pro_pickup_message'] : '';
$wpc_pro_allow_pickup_date       = (! isset($wpc_pro_menu_settings['wpc_pro_allow_pickup_date'] ) ||  isset($wpc_pro_menu_settings['wpc_pro_allow_pickup_date'] ) && $wpc_pro_menu_settings['wpc_pro_allow_pickup_date'] == 'on'  ) ? 'on' : 'off';
$wpc_pro_allow_pickup_time         = isset($wpc_pro_menu_settings['wpc_pro_allow_pickup_time']) ? 'checked' : '';

$markup_fields_tab1 = [            
    'wpc_pro_pickup_message' => [
        'item' => [
            'label'    => esc_html__( 'Pickup Message', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'During checkout, if a customer selects the ‘Pickup’ option, this message will show up. If you keep it blank, no message will appear. This option will work only if you allow order type "Pickup" from settings.', 'wpcafe-pro' ),
            'type'     => 'textarea',
            'attr'     => ['class' => 'wpc-label-item', 'row' => '7', 'col' => '30'],
        ],
        'data' => [ 'wpc_pro_pickup_message' => $wpc_pro_pickup_message ],
    ],
    'wpc_pro_allow_pickup_date' => [
        'item' => [
            'options'  =>['off'=>'off','on'=>'on'],
            'label'    => esc_html__( 'Allow Pickup Date?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'You can enable the pickup date option', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'wpc_pro_allow_pickup_date' => $wpc_pro_allow_pickup_date ],
    ],
    'wpc_pro_allow_pickup_time' => [
        'item' => [
            'options'  =>['off'=>'off','on'=>'on'],
            'label'    => esc_html__( 'Allow Pickup Time?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'You can enable the pickup time option', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'wpc_pro_allow_pickup_time' => $wpc_pro_allow_pickup_time ],
    ],
];

foreach ( $markup_fields_tab1 as $key => $info ) {
    $this->get_field_markup( $info['item'], $key, $info['data'] );
}

?>

<div class="wpc-label-item wpc-label-item-top">
    <div class="wpc-label">
        <label for="wpc_pickup_weekly_schedule" class="wpc-settings-label"><?php esc_html_e('Pickup Weekly Schedule', 'wpcafe-pro'); ?></label>
        <p class="wpc-desc"> <?php esc_html_e('Set pickup weekly schedule', 'wpcafe-pro'); ?> </p>
    </div>
    <div class="wpc-meta">
        <div class="pickup_schedule_main_block">
            <h5 class="wpc_pb_two"><?php esc_html_e('Pickup Weekly (Use this option to set customized schedule for one or multiple days)', 'wpcafe-pro'); ?></h5>
            <?php
            $wpc_schedule['wpc_pickup_weekly_schedule'] = isset($wpc_pro_menu_settings['wpc_pickup_weekly_schedule']) ? $wpc_pro_menu_settings['wpc_pickup_weekly_schedule'] : [];
            $wpc_schedule['wpc_pickup_weekly_schedule_start_time'] = isset($wpc_pro_menu_settings['wpc_pickup_weekly_schedule_start_time']) ? $wpc_pro_menu_settings['wpc_pickup_weekly_schedule_start_time'] : [];
            $wpc_schedule['wpc_pickup_weekly_schedule_end_time']   = isset($wpc_pro_menu_settings['wpc_pickup_weekly_schedule_end_time']) ? $wpc_pro_menu_settings['wpc_pickup_weekly_schedule_end_time'] : [];
            if (is_array( $wpc_schedule['wpc_pickup_weekly_schedule'] ) && count($wpc_schedule['wpc_pickup_weekly_schedule']) > 0) {
                for ($index = 0; $index < count($wpc_schedule['wpc_pickup_weekly_schedule']); $index++) {
                    ?>
                    <div class="pickup_schedule_block pickup_weekly_block pickup_weekly_block_<?php esc_attr_e( $index ) ?>" data-id="<?php esc_attr_e($index);?>">
                        <div class="wpc-weekly-schedule-list">
                        <?php foreach ($week_days as $key => $value) { ?>
                            <input type="checkbox" name="wpc_pickup_weekly_schedule[<?php echo intval($index) ?>][<?php echo esc_attr($value);?>]" 
                            class="<?php echo esc_attr(strtolower($value));?>" id="weekly_pickup_<?php echo esc_attr(strtolower($value)).$index;?>"
                            <?php echo isset( $wpc_schedule['wpc_pickup_weekly_schedule'][$index][$value] ) ? 'checked' : ''?> /><label for="weekly_pickup_<?php echo esc_html(strtolower($value)).$index;?>"><?php echo esc_html($value); ?></label>
                        <?php  } ?>
                        </div>

                        <div class="wpc-schedule-field multi_schedule_wrap mb-2">
                            <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Start Time', 'wpcafe-pro'); ?></p>
                            <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('End Time', 'wpcafe-pro'); ?></p>
                        </div>

                        <div class="wpc-schedule-field">
                            <?php
                                $pickup_start_time = $wpc_schedule['wpc_pickup_weekly_schedule_start_time'][ $index ];
                                $pickup_end_time   = $wpc_schedule['wpc_pickup_weekly_schedule_end_time'][ $index ];
                            ?>
                            <div class="wpc_pickup_weekly_start_wrap">
                                <input type="text" name="wpc_pickup_weekly_schedule_start_time[]" value="<?php echo esc_html( $pickup_start_time ); ?>" id="<?php echo intval($index) ;?>" class="wpc_pro_time_picker wpc_pickup_start_time_<?php echo intval($index) ?> wpc_pickup_weekly_schedule_start_time wpc_pickup_weekly_schedule_start_time_<?php echo intval($index) ?> ml-2 mr-1 wpc-settings-input attr-form-control <?php echo empty( $pickup_start_time ) ? 'wpc_field_error' : '' ?>" placeholder="<?php echo esc_attr__('Start Time', 'wpcafe-pro');?>" />
                                <?php if( empty( $pickup_start_time ) ) { ?>
                                    <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe-pro'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="wpc_pickup_weekly_end_wrap">
                                <input type="text" name="wpc_pickup_weekly_schedule_end_time[]" value="<?php echo esc_attr( $pickup_end_time ); ?>" id="<?php echo intval($index) ;?>" class="wpc_pro_time_picker wpc_pickup_end_time_<?php echo intval($index) ?> wpc_pickup_weekly_schedule_end_time wpc_pickup_weekly_schedule_end_time_<?php echo intval($index) ?> ml-2 wpc-settings-input attr-form-control <?php echo empty( $pickup_end_time ) ? 'wpc_field_error' : '' ?>" placeholder="<?php echo esc_attr__('End time', 'wpcafe-pro'); ?>" />
                                <?php if( empty( $pickup_end_time ) ) { ?>
                                    <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe-pro'); ?></span>
                                <?php } ?>
                            </div>
                            <div class="wpc_pickup_weekly_clear" id="<?php echo intval($index) ?>"><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></div>
                        </div>
                        <div class="wpc-default-guest-message pickup_valid_message_<?php echo intval( $index );?>"></div>
                        
                        <?php if ($index != 0) { ?>
                            <span class="wpc-btn-close dashicons dashicons-no-alt remove_pickup_block pl-1"></span>
                        <?php } ?>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="pickup_schedule_block pickup_weekly_block pickup_weekly_block_0" data-id="<?php esc_attr_e(0);?>">
                    <div class="wpc-weekly-schedule-list">
                        <?php foreach ($week_days as $key => $value) { ?>
                            <input type="checkbox" name="wpc_pickup_weekly_schedule[0][<?php echo esc_html($value);?>]" 
                            class="<?php echo esc_html(strtolower($value));?>" id="weekly_pickup_<?php echo esc_html(strtolower($value))."0";?>"
                            /><label for="weekly_pickup_<?php echo esc_html(strtolower($value))."0";?>"><?php echo esc_html($value); ?></label>
                        <?php  } ?>
                    </div>
                    <div class="wpc-schedule-field">
                        <div class="wpc_pickup_weekly_start_wrap">
                            <input type="text" name="wpc_pickup_weekly_schedule_start_time[]" id="0" class="wpc_pro_time_picker wpc_pickup_start_time_0 wpc_pickup_weekly_schedule_start_time wpc_pickup_weekly_schedule_start_time_0 mr-1 wpc-settings-input attr-form-control" disabled placeholder="<?php echo esc_attr__('Start Time', 'wpcafe-pro'); ?>" />
                        </div>
                        <div class="wpc_pickup_weekly_end_wrap">
                            <input type="text" name="wpc_pickup_weekly_schedule_end_time[]" id="0" class="wpc_pro_time_picker wpc_pickup_end_time_0 wpc_pickup_weekly_schedule_end_time wpc_pickup_weekly_schedule_end_time_0 wpc-settings-input attr-form-control" disabled placeholder="<?php echo esc_attr__('End Time', 'wpcafe-pro'); ?>" />
                        </div>
                        <div class="wpc_pickup_weekly_clear" id="0" style="display: none;"><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span> </div>
                    </div>
                    <div class="wpc-default-guest-message pickup_valid_message_0"></div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="wpc_flex_reverse wpc-weekly-schedule-btn">
            <span class="add_pickup_weekly_block wpc-btn-text wpc-tooltip" data-clear_text="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>" data-remove_text="<?php echo esc_attr__('Remove Fields', 'wpcafe-pro'); ?>" data-clear_text="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>" data-remove_text="<?php echo esc_attr__('Remove Fields', 'wpcafe-pro'); ?>" data-title="<?php echo esc_attr__('Add more weekly schedule', 'wpcafe-pro'); ?>" data-start_time="<?php echo esc_attr__('Start Time', 'wpcafe-pro' ); ?>"="<?php echo esc_attr__('Start time', 'wpcafe-pro'); ?>" data-end_time="<?php echo esc_html__('End Time', 'wpcafe-pro'); ?>">
                <?php echo esc_html__('Add','wpcafe-pro'); ?>
                <small class="wpc-tooltip-angle"></small>
            </span>
        </div>
    </div>
</div>

<?php
$interval = array( 5,10,15,20,25,30,35,40,45,50,55,60 );
$interval_time = [];
foreach ($interval as $value) {
    $interval_time[$value] = $value;
}

$pickup_time_interval = isset( $wpc_pro_menu_settings['pickup_time_interval'] ) ? $wpc_pro_menu_settings['pickup_time_interval'] : 15;

$markup_fields_tab2_2 = [
    'pickup_time_interval' => [
        'item' => [
            'label'    => esc_html__( 'Pickup Time Interval', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Set pickup schedule time interval in checkout page', 'wpcafe-pro' ),
            'type'     => 'select_single',
            'options'  => $interval_time,
            'attr'     => [
                'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
            ],
        ],
        'data' => [ 'pickup_time_interval' => $pickup_time_interval ],
    ],
];

foreach ( $markup_fields_tab2_2 as $key => $info ) {
    $this->get_field_markup( $info['item'], $key, $info['data'] );
}

?>

<div class="wpc-label-item">
    <div class="wpc-label">
        <label for="wpc_pickup_holiday" class="wpc-settings-label"><?php esc_html_e('Pickup on Weekly Holiday', 'wpcafe-pro'); ?></label>
        <p class="wpc-desc"> <?php esc_html_e("Set a pickup day for weekly holidays", "wpcafe-pro" ); ?> </p>
    </div>
    <div class="wpc-meta">
        <div class="wpc-weekly-schedule-list">
            <?php
            $holidays =['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            $wpc_schedule['wpc_pickup_holiday'] = isset($wpc_pro_menu_settings['wpc_pickup_holiday']) ? $wpc_pro_menu_settings['wpc_pickup_holiday'] : [];
            if (is_array($wpc_schedule['wpc_pickup_holiday']) && count($wpc_schedule['wpc_pickup_holiday']) > 0) {
                $index = 0; 
                foreach ($holidays as $key => $value) { ?>
                    <input type="checkbox" name="wpc_pickup_holiday[<?php echo intval( $key );?>]" <?php echo isset($wpc_schedule['wpc_pickup_holiday'][$key]) ? 'checked' : ''?>
                    id="wpc_pickup_holiday_<?php echo esc_html(strtolower($value));?>" /><label for="wpc_pickup_holiday_<?php echo esc_html(strtolower($value));?>"><?php echo esc_html($value); ?></label>
                <?php }
            } else {
                foreach ($holidays as $key => $value) { ?>
                    <input type="checkbox" name="wpc_pickup_holiday[<?php echo intval( $key );?>]" id="wpc_pickup_holiday_<?php echo esc_html(strtolower($value));?>" /><label for="wpc_pickup_holiday_<?php echo esc_html(strtolower($value));?>"><?php echo esc_html($value); ?></label>
                <?php }
            }
            ?>
        </div>
    </div>
</div>
<div class="wpc-label-item wpc-label-item-top">
    <div class="wpc-label">
        <label for="wpc_pickup_holiday" class="wpc-settings-label"><?php esc_html_e('Pickup Holiday', "wpcafe-pro" ); ?></label>
        <p class="wpc-desc"> <?php esc_html_e("Set a pickup date for specific holiday", "wpcafe-pro" ); ?> </p>
    </div>
    <div class="wpc-meta">
        <div class="pickup_exception_main_block">
            <?php
            $wpc_exception['wpc_pickup_exception_date']       = isset($wpc_pro_menu_settings['wpc_pickup_exception_date']) ? $wpc_pro_menu_settings['wpc_pickup_exception_date'] : [];
            if ( is_array($wpc_exception['wpc_pickup_exception_date']) && count($wpc_exception['wpc_pickup_exception_date']) > 0 && $wpc_exception['wpc_pickup_exception_date']['0'] !== '') {
                ?>
                <p class="wpc-desc"><?php echo esc_html__('Date', 'wpcafe-pro'); ?></p>
                <?php
                for ($index = 0; $index < count($wpc_exception['wpc_pickup_exception_date']); $index++) {
                    ?>
                    <div class="pickup_exception_block exception_block d-flex mb-2">
                        <input type="text" name="wpc_pickup_exception_date[]" value="<?php echo Wpc_Utilities::wpc_render($wpc_exception['wpc_pickup_exception_date'][$index]); ?>" class="wpc_pickup_exception_date wpc_pickup_exception_date_<?php echo intval( $index ); ?> mr-1 wpc-settings-input attr-form-control" id="pickup_exception_date_<?php echo Wpc_Utilities::wpc_render($index) ?>" placeholder="<?php esc_html_e('Date','wpcafe-pro');?>" />
                        <span class="wpc_pickup_exception_date_clear" id="<?php echo intval( $index ); ?>" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                        <?php if( $index != 0 ) { ?>
                            <span class="wpc-btn-close dashicons dashicons-no-alt remove_pickup_exception_block wpc_icon_middle_position"></span>
                        <?php } ?>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="pickup_exception_block exception_block d-flex mb-2">
                    <input type="text" name="wpc_pickup_exception_date[]" value="" class="wpc_pickup_exception_date wpc_pickup_exception_date_0 mr-1 wpc-settings-input attr-form-control" placeholder="<?php esc_html_e('Date','wpcafe-pro');?>" />
                    <span class="wpc_pickup_exception_date_clear" id="0" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="wpc_flex_reverse pick_add_section">
            <span class="add_pickup_exception_block wpc-btn-text wpc-tooltip" 
                data-title="<?php echo esc_attr__('Add more day', 'wpcafe-pro'); ?>"
                data-date_text="<?php echo esc_html__("Date", "wpcafe-pro");?>"
                data-clear_button_text="<?php echo esc_html__("Reset fields", "wpcafe-pro");?>">
                <?php echo esc_html__('Add','wpcafe-pro'); ?>
                <small class="wpc-tooltip-angle"></small>
            </span>
        </div>
    </div>
</div>
<?php
return;
        