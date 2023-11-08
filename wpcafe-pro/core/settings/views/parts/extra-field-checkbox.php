<?php

if( !empty( isset($wpc_extra_field_type[$index]) ) && $wpc_extra_field_type[$index] == 'checkbox' ) {
    $next_add_time_checkbox_index = count($wpc_extra_field_option[$index]);
    if($next_add_time_checkbox_index >= 1){ ?>
        <div class="wpc_extra_type_checkbox_main_block">
            <?php
            // generating each checkbox field.
            foreach($wpc_extra_field_option[$index] as $checkbox_index => $checkbox_val) {
                ?>
                <div class="wpc_extra_type_checkbox_block mb-2">
                    <input type="text" name="wpc_extra_field_option[<?php echo esc_attr($index); ?>][<?php echo esc_attr($checkbox_index); ?>]" value="<?php esc_attr_e($checkbox_val) ?>"
                    id="wpc_extra_type_<?php echo esc_attr($index); ?>_checkbox_<?php echo esc_attr($checkbox_index); ?>" class="wpc_extra_type_checkbox mr-1 wpc-settings-input wpc-form-control"
                    placeholder="<?php esc_attr_e('Checkbox text', 'wpcafe-pro'); ?>" />

                    <?php
                    if($checkbox_index > 0) {
                        ?>
                        <span class="wpc-btn-close dashicons dashicons dashicons-no-alt remove_wpc_extra_type_checkbox_field pl-1"></span>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>

            <!-- add more checkbox portion -->
            <div class="wpc_flex_reverse wpc_extra_type_checkbox_section">
                <span class="add_wpc_extra_type_checkbox_block wpc-btn-text"
                data-checkbox_placeholder_text="<?php echo esc_html__("Checkbox text", "wpcafe-pro"); ?>"
                data-next_add_time_checkbox_parent_index="<?php echo esc_attr($index); ?>"
                data-next_add_time_checkbox_index="<?php echo esc_attr($next_add_time_checkbox_index); ?>">
                <?php echo esc_html__('Add Checkbox', 'wpcafe-pro'); ?>
                </span>
            </div>
        </div>
        <?php
    }
}
        