<?php

use WpCafe\Utils\Wpc_Utilities;

$settings              = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option(); 
$show_form_field       =  (! isset($settings['show_form_field'] ) ||  isset($settings['show_form_field'] ) && $settings['show_form_field'] == 'on'  ) ? 'on' : 'off';
$required_from_field   =  (! isset($settings['required_from_field'] ) ||  isset($settings['required_from_field'] ) && $settings['required_from_field'] == 'on'  ) ? 'on' : 'off';
$from_field_label      =  isset($settings['from_field_label'])   ? $settings['from_field_label'] : '';
$first_booking_button  =  isset($settings['first_booking_button'])   ? $settings['first_booking_button'] : esc_html__('Book a table','wpcafe-pro');
$form_booking_button   =  isset($settings['form_booking_button'])   ? $settings['form_booking_button'] : esc_html__('Confirm Booking','wpcafe-pro');
$form_cancel_button    =  isset($settings['form_cancell_button'])   ? $settings['form_cancell_button'] : esc_html__('Request Cancellation','wpcafe-pro');
$show_to_field         =  (! isset($settings['show_to_field'] ) ||  isset($settings['show_to_field'] ) && $settings['show_to_field'] == 'on'  ) ? 'on' : 'off';
$required_to_field     =  (! isset($settings['required_to_field'] ) ||  isset($settings['required_to_field'] ) && $settings['required_to_field'] == 'on'  ) ? 'on' : 'off';
$to_field_label        =  !empty($settings['to_field_label'])   ? $settings['to_field_label'] : '';

$markup_fields = [
    'first_booking_button' => [
        'item' => [
            'label'    => esc_html__( 'Reservation Form Button Text', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Show booking button text for the first section in reservation form', 'wpcafe-pro' ),
            'type'     => 'text',
            'place_holder' => esc_html__('Book a Table', 'wpcafe-pro'),
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
        ],
        'data' => [ 'first_booking_button' => $first_booking_button ],            
    ],
    'form_booking_button' => [
        'item' => [
            'label'    => esc_html__( 'Reservation Confirm Booking Button Text', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Show confirm booking button text in reservation form', 'wpcafe-pro' ),
            'type'     => 'text',
            'place_holder' => esc_html__('Confirm Booking', 'wpcafe-pro'),
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
        ],
        'data' => [ 'form_booking_button' => $form_booking_button ],            
    ],
    'form_cancell_button' => [
        'item' => [
            'label'    => esc_html__( 'Reservation Cancellation Button Text', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Show reservation cancellation button text in reservation form', 'wpcafe-pro' ),
            'type'     => 'text',
            'place_holder' => esc_html__('Request Cancellation', 'wpcafe-pro'),
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
        ],
        'data' => [ 'form_cancell_button' => $form_cancel_button ],            
    ],
    'show_form_field' => [
        'item' => [
            'options'  =>['off'=>'off','on'=>'on'],
            'label'    => esc_html__( 'Show "Start Time" Field?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Show/hide form time field in reservation form', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'show_form_field' => $show_form_field ],
    ],
    'required_from_field' => [
        'item' => [
            'options'  =>['off'=>'off','on'=>'on'],
            'label'    => esc_html__( 'Required "Start Time" Field?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Required/Optional form time field in reservation form', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'required_from_field' => $required_from_field ],
    ],
    'from_field_label' => [
        'item' => [
            'label'    => esc_html__( 'Start Time Field Label Text', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Show "Start Time" field label text in reservation form', 'wpcafe-pro' ),
            'type'     => 'text',
            'place_holder' => esc_html__('Start', 'wpcafe-pro'),
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
        ],
        'data' => [ 'from_field_label' => $from_field_label ],            
    ],
    'show_to_field' => [
        'item' => [
            'options'  =>['off'=>'off','on'=>'on'],
            'label'    => esc_html__( 'Show "End Time" Field?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Show/Hide "End Time" field in reservation form', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'show_to_field' => $show_to_field ],
    ],
    'required_to_field' => [
        'item' => [
            'options'  =>['off'=>'off','on'=>'on'],
            'label'    => esc_html__( 'Required "End Time" Field?', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Required/Optional "End Time" field in reservation form', 'wpcafe-pro' ),
            'type'     => 'checkbox',
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
        ],
        'data' => [ 'required_to_field' => $required_to_field ],
    ],
    'to_field_label' => [
        'item' => [
            'label'    => esc_html__( 'End Time Field Label Text', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Show "End Time" field label text in reservation form', 'wpcafe-pro' ),
            'type'     => 'text',
            'place_holder' => esc_html__('End', 'wpcafe-pro'),
            'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpc-settings-input'],
        ],
        'data' => [ 'to_field_label' => $to_field_label ],            
    ],
];

foreach ( $markup_fields as $key => $info ) {
    $this->get_field_markup( $info['item'], $key, $info['data'] );
}

$input_type_array = [
    'text'     => esc_html__('Text', 'wpcafe-pro'),
    'checkbox' => esc_html__('Checkbox', 'wpcafe-pro')
];

$special_types = [
    'checkbox'
];

?>


<div class="wpc-label-item wpc-label-item-top wpc-label-extra-field">
    <div class="wpc-label">
        <label for="reserv_extra_field" class="wpc-settings-label"><?php esc_html_e('Extra Field', 'wpcafe-pro'); ?></label>
        <p class="wpc-desc"> <?php esc_html_e('Add unlimited extra fields. Extra fields will be added in reservation form', 'wpcafe-pro'); ?> </p>
    </div>
    <div class="wpc-meta">
        <div class="reserv_extra_main_block">
            <?php
            $wpc_extra_field_type       = isset($settings['wpc_extra_field_type']) ? $settings['wpc_extra_field_type'] : ['text'];
            $wpc_extra_field_required   = isset($settings['wpc_extra_field_required']) ? $settings['wpc_extra_field_required'] : ['optional'];
			// field option for checkbox
            $wpc_extra_field_option   = isset($settings['wpc_extra_field_option']) ? $settings['wpc_extra_field_option'] : [];
            $reserv_extra_label       = isset($settings['reserv_extra_label']) ? $settings['reserv_extra_label'] : [];
            $reserv_extra_place_ho    = isset($settings['reserv_extra_place_ho']) ? $settings['reserv_extra_place_ho'] : [];

            if ( is_array($reserv_extra_label) && count($reserv_extra_label) > 0 ) { ?>
                <div class="wpc-schedule-field multi_schedule_wrap mb-2">
                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Required?', 'wpcafe-pro'); ?></p>
                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Field Type', 'wpcafe-pro'); ?></p>
                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Label Text', 'wpcafe-pro'); ?></p>
                    <p class="wpc-desc wpc-settings-input attr-form-control"><?php echo esc_html__('Placeholder Text', 'wpcafe-pro'); ?></p>
                </div>
                <?php
                foreach ($reserv_extra_label as $index => $item ) {
                    $selection = !empty($wpc_extra_field_required[$index]) ? $wpc_extra_field_required[$index]: "";
                    ?>
                    <div class="wpc-schedule-field extra_field_block schedule_block mb-2">

                        <select name='wpc_extra_field_required[<?php echo esc_attr($index); ?>]' class="wpc-settings-input wpc-form-control wpc_extra_field_required_<?php echo intval($index) ?>">
                            <option value="optional" <?php echo esc_attr($selection == 'optional' ? 'selected' : ''); ?> > <?php echo esc_html__("Optional", "wpcafe-pro") ?></option>
                            <option value="required" <?php echo esc_attr($selection == 'required' ? 'selected' : ''); ?>><?php echo esc_html__("Required", "wpcafe-pro") ?></option>
                        </select>
                        
                        <select name="wpc_extra_field_type[<?php echo intval($index) ?>]" class="wpc_extra_field_type mr-1 wpc-settings-input wpc-form-control wpc_extra_field_type_<?php echo intval($index) ?>" data-current_extra_block_index="<?php echo intval($index) ?>" id="wpc_extra_field_type_<?php echo intval($index) ?>">
                            <?php                            
                            foreach($input_type_array as $key => $value) {
                                ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php selected($wpc_extra_field_type[$index], $key, true) ?>><?php echo esc_html($value); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="text" name="reserv_extra_label[<?php echo intval($index) ?>]" value="<?php echo Wpc_Utilities::wpc_render($reserv_extra_label[$index]); ?>" class="reserv_extra_label reserv_extra_label_<?php echo intval($index) ?> mr-1 wpc-settings-input attr-form-control" id="reserv_extra_label_<?php echo intval($index) ?>" placeholder="<?php esc_attr_e('Label Text', 'wpcafe-pro'); ?>" />
                        <input type="text" name="reserv_extra_place_ho[<?php echo intval($index) ?>]" value="<?php echo Wpc_Utilities::wpc_render($reserv_extra_place_ho[$index]); ?>" class="reserv_extra_place_ho reserv_extra_place_ho_<?php echo intval($index) ?> mr-1 wpc-settings-input attr-form-control" id="reserv_extra_place_ho_<?php echo intval($index) ?>" placeholder="<?php esc_attr_e('Placeholder Text', 'wpcafe-pro');?>" style="display: <?php echo in_array( $wpc_extra_field_type[$index], $special_types) ? 'none' : 'block'; ?>;" />
                        <span class="wpc_extra_field_clear" id="<?php echo intval( $index )?>" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                        <?php if( $index != 0 ) { ?>
                            <span class="wpc-btn-close dashicons dashicons-no-alt remove_reserve_extra_field pl-1"></span>
                        <?php }
                        
                        // add checkbox type options
                        if( file_exists( \Wpcafe_Pro::core_dir().'/settings/views/parts/extra-field-checkbox.php' )) {
                            include \Wpcafe_Pro::core_dir().'/settings/views/parts/extra-field-checkbox.php';
                        }                        
                        ?>
                    </div>
                    <?php
                    
                }
            } else {
                ?>
                <div class="wpc-schedule-field schedule_block mb-2">
                    <select name='wpc_extra_field_required[0]' class="wpc-settings-input wpc-form-control wpc_extra_field_required_0">
                        <option value="optional" selected> <?php echo esc_html__("Optional", "wpcafe-pro") ?></option>
                        <option value="required"><?php echo esc_html__("Required", "wpcafe-pro") ?></option>
                    </select>

                    <select name="wpc_extra_field_type[0]" class="wpc_extra_field_type mr-1 wpc-settings-input wpc_extra_field_type_0" data-current_extra_block_index="0" id="wpc_extra_field_type_0">
						<?php
						foreach($input_type_array as $key => $value) {
							?>
							<option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
							<?php
						}
						?>
                    </select>

                    <input type="text" name="reserv_extra_label[0]" value="" class="reserv_extra_label reserv_extra_label_0 mr-1 wpc-settings-input attr-form-control" id="reserv_extra_label_0" placeholder="<?php esc_attr_e('Label Text', 'wpcafe-pro'); ?>" />
                    <input type="text" name="reserv_extra_place_ho[0]" value="" class="reserv_extra_place_ho reserv_extra_place_ho_0 mr-1 wpc-settings-input attr-form-control" id="reserv_extra_place_ho_0" placeholder="<?php esc_attr_e('Placeholder Text','wpcafe-pro'); ?>" />
                    <span class="wpc_extra_field_clear" id="0" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="wpc_flex_reverse reserv_extra_section">
            <span class="add_reserve_extra_block wpc-btn-text wpc-tooltip" data-title="<?php echo esc_attr__('Add More', 'wpcafe-pro'); ?>" data-label_text="<?php echo esc_attr__('Label text', 'wpcafe-pro'); ?>"
                data-placeholder_text="<?php echo esc_attr__('Placeholder Text', 'wpcafe-pro'); ?>" 
                data-clear_button_text="<?php echo esc_attr__('Reset Fields', 'wpcafe-pro'); ?>" data-checkbox_placeholder_text="<?php echo esc_attr__('Checkbox text', "wpcafe-pro"); ?>"
                  data-checkbox_add_btn_text="<?php echo esc_attr__(' Add Checkbox', "wpcafe-pro"); ?>">
                <?php echo esc_html__('Add', 'wpcafe-pro'); ?>
                <small class="wpc-tooltip-angle"></small>
            </span>
        </div>
    </div>
</div>

<?php return; ?>