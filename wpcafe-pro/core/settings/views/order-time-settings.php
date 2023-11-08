
<?php

use WpCafe\Utils\Wpc_Utilities;
?>

<div id="wpc_pro_order_time" data-id="tab_wpc_pro_order_time"  class="tab-pane <?php echo ('wpc_pro_order_time' == $recent_tab ) ? 'active' : ''?> ">
    <div class="mb-25">
        <?php echo esc_html__('Visit', 'wpcafe-pro'); ?>
        <a href="<?php echo esc_url('https://support.themewinter.com/docs/plugins/wp-cafe/food-ordering/'); ?>" target="_blank" class="doc-link"><?php echo esc_html__('documentation', 'wpcafe-pro'); ?></a>
        <?php echo esc_html__('for order time management section to maintain selling time of your restaurant.', "wpcafe-pro" ); ?>
    </div>

        
    <div class="wpc-tab-wrapper wpc-tab-style2">
        <ul class="wpc-nav mb-30">
            <li>
                <a class="wpc-tab-a wpc-active"  data-id="order-time-general-setting">
                    <?php echo esc_html__('General Settings', 'wpcafe-pro'); ?>
                </a>
            </li>
            <li>
                <a class="wpc-tab-a" data-id="order-time-settings">
                    <?php echo esc_html__('Time Settings', 'wpcafe-pro'); ?>
                </a>
            </li>
            <li>
                <a class="wpc-tab-a" data-id="order-holiday-settings">
                    <?php echo esc_html__('Holiday Settings', 'wpcafe-pro'); ?>
                </a>
            </li>
        </ul>
        <div class="wpc-tab-content">
            <!-- notifications Settings options -->
            <div class="wpc-tab wpc-active" data-id="order-time-general-setting">
                <?php
                $args = array( ''=>esc_html__('None','wpcafe-pro') , 'Both'=>esc_html__('Both','wpcafe-pro') , 'Delivery'=>esc_html__('Delivery', 'wpcafe-pro'), 'Pickup'=>esc_html__('Pickup','wpcafe-pro'));

                $markup_fields_tab1 = [

                    'wpc_pro_allow_order_for' => [
                        'item' => [
                            'label'    => esc_html__( 'Order Type', 'wpcafe-pro' ),
                            'desc'     => esc_html__( 'You can set "Delivery", "Pickup", "Both" or "None" for users.', 'wpcafe-pro' ),
                            'type'     => 'select_single',
                            'options'  => $args,
                            'attr'     => [
                                'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
                            ],
                        ],
                        'data' => [ 'wpc_pro_allow_order_for' => $wpc_pro_allow_order_for ],
                    ],        

                    'wpc_pro_delivery_message' => [
                        'item' => [
                            'label'    => esc_html__( 'Delivery Message', 'wpcafe-pro' ),
                            'desc'     => esc_html__( 'During checkout, if a customer selects the ‘delivery’ option, this message will show up. If you keep it blank, no message will appear. This option will work only if you allow order type "Delivery" from settings.', 'wpcafe-pro' ),
                            'type'     => 'textarea',
                            'attr'     => ['class' => 'wpc-label-item', 'row' => '7', 'col' => '30'],
                        ],
                        'data' => [ 'wpc_pro_delivery_message' => $wpc_pro_delivery_message ],
                    ],
                    'order_prepare_days' => [
                        'item' => [
                            'label'    => esc_html__( 'Order Preparation Days', 'wpcafe-pro' ),
                            'desc'     => esc_html__( 'If it takes more than a day to prepare your order, then mention how many days it took to prepare your order for delivery (Applies to all orders)', 'wpcafe-pro' ),
                            'type'     => 'number',
                            'attr'     => ['class' => 'wpc-label-item wpc-pro-interval-enabled', 'input_class'=> 'wpc-settings-input'],
                        ],
                        'data' => [ 'order_prepare_days' => intval($order_prepare_days) ],
                    ],
                    'wpc_pro_allow_delivery_date' => [
                        'item' => [
                            'options'  =>['off'=>'off','on'=>'on'],
                            'label'    => esc_html__( 'Allow Delivery Date?', 'wpcafe-pro' ),
                            'desc'     => esc_html__( 'You can enable the delivery date option', 'wpcafe-pro' ),
                            'type'     => 'checkbox',
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                        ],
                        'data' => [ 'wpc_pro_allow_delivery_date' => $wpc_pro_allow_delivery_date ],
                    ],
                    'wpc_pro_allow_delivery_time' => [
                        'item' => [
                            'options'  =>['off'=>'off','on'=>'on'],
                            'label'    => esc_html__( 'Allow Delivery Time?', 'wpcafe-pro' ),
                            'desc'     => esc_html__( 'You can enable the delivery time option', 'wpcafe-pro' ),
                            'type'     => 'checkbox',
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                        ],
                        'data' => [ 'wpc_pro_allow_delivery_time' => $wpc_pro_allow_delivery_time ],
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
                            'attr'     => ['class' => 'wpc-label-item mb-0', 'input_class'=> 'wpcafe-admin-control-input'],
                        ],
                        'data' => [ 'wpc_pro_allow_pickup_time' => $wpc_pro_allow_pickup_time ],
                    ],
                ];
            
                foreach ( $markup_fields_tab1 as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }
                ?>
            </div>
            <div class="wpc-tab" data-id="order-time-settings">

                <?php
                $markup_fields_tab2 = [
                    'wpc_pro_allow_delivery_date' => [
                        'item' => [
                            'options'  =>['off'=>'off','on'=>'on'],
                            'label'    => esc_html__( 'Allow Delivery Date?', 'wpcafe-pro' ),
                            'desc'     => esc_html__( 'You can enable the delivery date option', 'wpcafe-pro' ),
                            'type'     => 'checkbox',
                            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
                        ],
                        'data' => [ 'wpc_pro_allow_delivery_date' => $wpc_pro_allow_delivery_date ],
                    ],
                ];
            
                foreach ( $markup_fields_tab2 as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }
                ?>
                            
                <div class="wpc-label-item">
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
                                            <span class="wpc-btn-close dashicons dashicons dashicons-no-alt remove_pickup_block pl-1"></span>
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

                <div class="wpc-label-item wpc-label-item-top">
                    <div class="wpc-label">
                        <label for="wpc_delivery_schedule" class="wpc-settings-label"><?php esc_html_e('Delivery Weekly Schedule', 'wpcafe-pro'); ?></label>
                        <p class="wpc-desc"> <?php esc_html_e('Set delivery weekly schedule', 'wpcafe-pro'); ?> </p>
                    </div>
                    <div class="wpc-meta">
                        <div class="delivery_schedule_main_block">
                            <h5 class="wpc_pb_two"><?php esc_html_e('Delivery Weekly (Set opening and closing schedule for each day of a week separately)', 'wpcafe-pro'); ?></h5>
                            <?php
                            $wpc_schedule['wpc_delivery_schedule'] = isset($wpc_pro_menu_settings['wpc_delivery_schedule']) ? $wpc_pro_menu_settings['wpc_delivery_schedule'] : [];
                            $wpc_schedule['wpc_delivery_weekly_schedule_start_time'] = isset($wpc_pro_menu_settings['wpc_delivery_weekly_schedule_start_time']) ? $wpc_pro_menu_settings['wpc_delivery_weekly_schedule_start_time'] : [];
                            $wpc_schedule['wpc_delivery_weekly_schedule_end_time']   = isset($wpc_pro_menu_settings['wpc_delivery_weekly_schedule_end_time']) ? $wpc_pro_menu_settings['wpc_delivery_weekly_schedule_end_time'] : [];
                            if ( is_array($wpc_schedule['wpc_delivery_schedule']) && count($wpc_schedule['wpc_delivery_schedule']) > 0) {
                                for ($index = 0; $index < count($wpc_schedule['wpc_delivery_schedule']); $index++) {
                                    $schedule_sat = isset($wpc_schedule['wpc_delivery_schedule'][$index]['Sat']) ? 'checked' : '';
                                    $schedule_sun = isset($wpc_schedule['wpc_delivery_schedule'][$index]['Sun']) ? 'checked' : '';
                                    $schedule_mon = isset($wpc_schedule['wpc_delivery_schedule'][$index]['Mon']) ? 'checked' : '';
                                    $schedule_tue = isset($wpc_schedule['wpc_delivery_schedule'][$index]['Tue']) ? 'checked' : '';
                                    $schedule_wed = isset($wpc_schedule['wpc_delivery_schedule'][$index]['Wed']) ? 'checked' : '';
                                    $schedule_thu = isset($wpc_schedule['wpc_delivery_schedule'][$index]['Thu']) ? 'checked' : '';
                                    $schedule_fri = isset($wpc_schedule['wpc_delivery_schedule'][$index]['Fri']) ? 'checked' : '';
                                    ?>
                                    <div class="delivery_schedule_block delivery_weekly_block delivery_weekly_block_<?php esc_attr_e( $index ) ?>" data-id="<?php esc_attr_e( $index ) ?>">
                                        <div class="wpc-weekly-schedule-list">
                                            <input type="checkbox" name="wpc_delivery_schedule[<?php echo intval($index) ?>][Sat]" class="sat" id="delivery_sat<?php echo esc_html($index); ?>" <?php echo Wpc_Utilities::wpc_render($schedule_sat); ?> /> <label for="delivery_sat<?php echo esc_html($index); ?>"><?php echo esc_html__('Sat', 'wpcafe-pro'); ?></label>
                                            <input type="checkbox" name="wpc_delivery_schedule[<?php echo intval($index) ?>][Sun]" class="sun" id="delivery_sun<?php echo esc_html($index); ?>" <?php echo Wpc_Utilities::wpc_render($schedule_sun); ?> /> <label for="delivery_sun<?php echo esc_html($index); ?>"><?php echo esc_html__('Sun', 'wpcafe-pro'); ?></label>
                                            <input type="checkbox" name="wpc_delivery_schedule[<?php echo intval($index) ?>][Mon]" class="mon" id="delivery_mon<?php echo esc_html($index); ?>" <?php echo Wpc_Utilities::wpc_render($schedule_mon); ?> /> <label for="delivery_mon<?php echo esc_html($index); ?>"><?php echo esc_html__('Mon', 'wpcafe-pro'); ?></label>
                                            <input type="checkbox" name="wpc_delivery_schedule[<?php echo intval($index) ?>][Tue]" class="tue" id="delivery_tue<?php echo esc_html($index); ?>" <?php echo Wpc_Utilities::wpc_render($schedule_tue); ?> /> <label for="delivery_tue<?php echo esc_html($index); ?>"><?php echo esc_html__('Tue', 'wpcafe-pro'); ?></label>
                                            <input type="checkbox" name="wpc_delivery_schedule[<?php echo intval($index) ?>][Wed]" class="wed" id="delivery_wed<?php echo esc_html($index); ?>" <?php echo Wpc_Utilities::wpc_render($schedule_wed); ?> /> <label for="delivery_wed<?php echo esc_html($index); ?>"><?php echo esc_html__('Wed', 'wpcafe-pro'); ?></label>
                                            <input type="checkbox" name="wpc_delivery_schedule[<?php echo intval($index) ?>][Thu]" class="thu" id="delivery_thu<?php echo esc_html($index); ?>" <?php echo Wpc_Utilities::wpc_render($schedule_thu); ?> /> <label for="delivery_thu<?php echo esc_html($index); ?>"><?php echo esc_html__('Thu', 'wpcafe-pro'); ?></label>
                                            <input type="checkbox" name="wpc_delivery_schedule[<?php echo intval($index) ?>][Fri]" class="fri" id="delivery_fri<?php echo esc_html($index); ?>" <?php echo Wpc_Utilities::wpc_render($schedule_fri); ?> /> <label for="delivery_fri<?php echo esc_html($index); ?>"><?php echo esc_html__('Fri', 'wpcafe-pro'); ?></label>
                                        </div>
                                        <div class="wpc-schedule-field multi_schedule_wrap mb-2">
                                            <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Start Time', 'wpcafe-pro'); ?></p>
                                            <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('End Time', 'wpcafe-pro'); ?></p>
                                        </div>

                                        <div class="wpc-schedule-field">
                                            <?php
                                                $delivery_start_time = $wpc_schedule['wpc_delivery_weekly_schedule_start_time'][ $index ];
                                                $delivery_end_time   = $wpc_schedule['wpc_delivery_weekly_schedule_end_time'][ $index ];
                                            ?>
                                            <div class="wpc_delivery_weekly_start_wrap">
                                                <input type="text" name="wpc_delivery_weekly_schedule_start_time[]" value="<?php echo Wpc_Utilities::wpc_render( $delivery_start_time ); ?>" id="<?php echo intval($index) ?>" class="wpc_pro_time_picker wpc_delivery_start_time_<?php echo intval($index) ?> wpc_delivery_weekly_schedule_start_time wpc_delivery_weekly_schedule_start_time_<?php echo intval($index) ?> ml-2 mr-1 wpc-settings-input attr-form-control <?php echo empty( $delivery_start_time ) ? 'wpc_field_error' : '' ?>" placeholder="<?php esc_html_e('Start Time', 'wpcafe-pro');?>" />
                                                <?php if( empty( $delivery_start_time ) ) { ?>
                                                    <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe-pro'); ?></span>
                                                <?php } ?>
                                            </div>
                                            <div class="wpc_delivery_weekly_end_wrap">
                                                <input type="text" name="wpc_delivery_weekly_schedule_end_time[]" value="<?php echo Wpc_Utilities::wpc_render( $delivery_end_time ); ?>" id="<?php echo intval($index) ?>" class="wpc_pro_time_picker wpc_delivery_end_time_<?php echo intval($index) ?> wpc_delivery_weekly_schedule_end_time wpc_delivery_weekly_schedule_end_time_<?php echo intval($index) ?> ml-2 wpc-settings-input attr-form-control <?php echo empty( $delivery_end_time ) ? 'wpc_field_error' : '' ?>" placeholder="<?php esc_attr_e('End Time', 'wpcafe-pro');?>" />
                                                <?php if( empty( $delivery_end_time ) ) { ?>
                                                    <span class="wpc_field_error_msg"><?php echo esc_html__('This field should be filled up', 'wpcafe-pro'); ?></span>
                                                <?php } ?>
                                            </div>
                                            <div class="wpc_delivery_weekly_clear" id="<?php echo intval($index) ?>"><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></div>
                                        </div>
                                        <div class=" wpc-default-guest-message delivery_valid_message_<?php echo intval( $index );?>"></div>

                                        <?php if ($index != 0) { ?>
                                            <span class="wpc-btn-close dashicons dashicons dashicons-no-alt remove_delivery_block pl-1"></span>
                                        <?php } ?>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <div class="delivery_schedule_block delivery_weekly_block delivery_weekly_block_0" data-id="<?php esc_attr_e(0);?>">
                                    <div class="wpc-weekly-schedule-list">
                                        <?php  foreach ($week_days as $key => $value) { ?>
                                                <input type="checkbox" name="wpc_delivery_schedule[0][<?php echo esc_html($value);?>]" 
                                                class="<?php echo esc_html(strtolower($value));?>" id="delivery_<?php echo esc_html(strtolower($value));?>"
                                                /><label for="delivery_<?php echo esc_html(strtolower($value));?>"><?php echo esc_html($value); ?></label>
                                        <?php  }  ?>
                                    </div>
                                    <div class="wpc-schedule-field">
                                        <div class="wpc_delivery_weekly_start_wrap">
                                            <input type="text" name="wpc_delivery_weekly_schedule_start_time[]" id="<?php esc_attr_e(0);?>" class="wpc_pro_time_picker wpc_delivery_start_time_0 wpc_delivery_weekly_schedule_start_time wpc_delivery_weekly_schedule_start_time_0 mr-1 wpc-settings-input attr-form-control" disabled placeholder="<?php echo esc_html__('Start Time', 'wpcafe-pro'); ?>" />
                                        </div>
                                        <div class="wpc_delivery_weekly_end_wrap">
                                            <input type="text" name="wpc_delivery_weekly_schedule_end_time[]"  id="<?php esc_attr_e(0);?>" class="wpc_pro_time_picker wpc_delivery_end_time_0 wpc_delivery_weekly_schedule_end_time wpc_delivery_weekly_schedule_end_time_0 wpc-settings-input attr-form-control" disabled placeholder="<?php echo esc_attr__('End Time', 'wpcafe-pro'); ?>" />
                                        </div>
                                        <div class="wpc_delivery_weekly_clear" id="<?php esc_attr_e(0);?>" style="display: none;" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-clear_text="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>" data-remove_text="<?php echo esc_attr__('Remove Fields', 'wpcafe-pro'); ?>" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></div>
                                    </div>
                                    <div class="wpc-default-guest-message delivery_valid_message_0"></div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="wpc_flex_reverse wpc-weekly-schedule-btn">
                            <span class="add_delivery_weekly_block wpc-btn-text wpc-tooltip" data-clear_text="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>" data-remove_text="<?php echo esc_attr__('Remove Fields', 'wpcafe-pro'); ?>" data-title="<?php echo esc_attr__('Add more weekly schedule', 'wpcafe-pro'); ?>" data-start_time="<?php echo esc_html__("Start time", "wpcafe-pro" ); ?>" data-end_time="<?php echo esc_html__("End time", "wpcafe-pro" ); ?>">
                                <?php echo esc_html__('Add','wpcafe-pro'); ?>
                                <small class="wpc-tooltip-angle"></small>
                            </span>
                        </div>
                    </div>
                </div>
                <?php

                $delivery_time_interval = isset( $wpc_pro_menu_settings['delivery_time_interval'] ) ? $wpc_pro_menu_settings['delivery_time_interval'] : 15;
                $markup_fields_tab2_3 = [
                    'delivery_time_interval' => [
                        'item' => [
                            'label'    => esc_html__( 'Delivery Time Interval', 'wpcafe-pro' ),
                            'desc'     => esc_html__( 'Set delivery time interval in checkout page', 'wpcafe-pro' ),
                            'type'     => 'select_single',
                            'options'  => $interval_time,
                            'attr'     => [
                                'class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'
                            ],
                        ],
                        'data' => [ 'delivery_time_interval' => $delivery_time_interval ],
                    ],
                ];
            
                foreach ( $markup_fields_tab2_3 as $key => $info ) {
                    $this->get_field_markup( $info['item'], $key, $info['data'] );
                }
                ?>
            </div>
            <div class="wpc-tab" data-id="order-holiday-settings">
                            
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
                <div class="wpc-label-item">
                    <div class="wpc-label">
                        <label for="wpc_delivery_holiday" class="wpc-settings-label"><?php esc_html_e('Delivery on Weekly Holiday', "wpcafe-pro" ); ?></label>
                        <p class="wpc-desc"> <?php esc_html_e("Set a delivery day for weekly holiday", "wpcafe-pro" ); ?> </p>
                    </div>
                    <div class="wpc-meta">
                        <div class="wpc-weekly-schedule-list">
                            <?php
                            $wpc_schedule['wpc_delivery_holiday'] = isset($wpc_pro_menu_settings['wpc_delivery_holiday']) ? $wpc_pro_menu_settings['wpc_delivery_holiday'] : [];
                            if (is_array($wpc_schedule['wpc_delivery_holiday']) && count($wpc_schedule['wpc_delivery_holiday']) > 0) {
                                $index = 0;
                                foreach ($holidays as $key => $value) { ?>
                                    <input type="checkbox" name="wpc_delivery_holiday[<?php echo intval( $key );?>]" <?php echo isset($wpc_schedule['wpc_delivery_holiday'][$key]) ? 'checked' : ''?>
                                    id="delivery_holiday_<?php echo esc_html(strtolower($value));?>" /><label for="delivery_holiday_<?php echo esc_html(strtolower($value));?>"><?php echo esc_html($value); ?></label>
                                <?php }
                            } else {
                                foreach ($holidays as $key => $value) { ?>
                                    <input type="checkbox" name="wpc_delivery_holiday[<?php echo intval( $key );?>]" id="delivery_holiday_<?php echo esc_html(strtolower($value));?>" /><label for="delivery_holiday_<?php echo esc_html(strtolower($value));?>"><?php echo esc_html($value); ?></label>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="wpc-label-item wpc-label-item-top">
                    <div class="wpc-label">
                        <label for="wpc_delivery_holiday" class="wpc-settings-label"><?php esc_html_e('Delivery Holiday', 'wpcafe-pro'); ?></label>
                        <p class="wpc-desc"> <?php esc_html_e('Set a delivery day for a specific weekly holiday', 'wpcafe-pro'); ?> </p>
                    </div>
                    <div class="wpc-meta">
                        <div class="delivery_exception_main_block">
                            <?php
                            $wpc_exception['wpc_delivery_exception_date']       = isset($wpc_pro_menu_settings['wpc_delivery_exception_date']) ? $wpc_pro_menu_settings['wpc_delivery_exception_date'] : [];
                            if (is_array($wpc_exception['wpc_delivery_exception_date']) && count($wpc_exception['wpc_delivery_exception_date']) > 0 && $wpc_exception['wpc_delivery_exception_date']['0'] !== '') { ?>
                                <p class="wpc-desc"><?php echo esc_html__('Date', 'wpcafe-pro'); ?></p>
                                <?php
                                for ($index = 0; $index < count($wpc_exception['wpc_delivery_exception_date']); $index++) {
                                    ?>
                                    <div class="delivery_exception_block exception_block d-flex mb-2">
                                        <input type="text" name="wpc_delivery_exception_date[]" value="<?php echo Wpc_Utilities::wpc_render($wpc_exception['wpc_delivery_exception_date'][$index]); ?>" class="wpc_delivery_exception_date wpc_delivery_exception_date_<?php echo intval($index); ?> mr-1 wpc-settings-input attr-form-control" id="pickup_delivery_date_<?php echo Wpc_Utilities::wpc_render($index) ?>" placeholder="<?php esc_html_e('Date','wpcafe-pro');?>" />
                                        <span class="wpc_delivery_exception_date_clear" id="<?php echo intval( $index )?>" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                                        <?php if( $index != 0 ) { ?>
                                            <span class="wpc-btn-close dashicons dashicons-no-alt remove_delivery_exception_block wpc_icon_middle_position"></span>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="delivery_exception_block exception_block d-flex mb-2">
                                    <input type="text" name="wpc_delivery_exception_date[]" value="" class="wpc_delivery_exception_date wpc_delivery_exception_date_0 mr-1 wpc-settings-input attr-form-control" placeholder="<?php esc_attr_e('Date','wpcafe-pro'); ?>" />
                                    <span class="wpc_delivery_exception_date_clear" id="0" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="wpc_flex_reverse delivery_add_section ">
                            <span class="add_delivery_exception_block wpc-btn-text wpc-tooltip" data-title="<?php echo esc_attr__('Add More Day', 'wpcafe-pro'); ?>" data-date_text="<?php echo esc_attr__('Date', 'wpcafe-pro'); ?>" 
                                data-clear_button_text="<?php echo esc_html__('Reset Fields', 'wpcafe-pro'); ?>">
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
<?php
return;
        