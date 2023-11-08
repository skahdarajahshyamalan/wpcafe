<?php
use WpCafe\Utils\Wpc_Utilities;
?>
<div class="wpc-label-item wpc-label-item-top">
    <div class="wpc-label">
        <label for="wpc_reservation_holiday" class="wpc-settings-label"><?php esc_html_e('Holiday Schedule', 'wpcafe-pro'); ?></label>
        <p class="wpc-desc"> <?php esc_html_e('Set the holidays', 'wpcafe-pro'); ?> </p>
    </div>
    <div class="wpc-meta">
        <div class="holiday_exception_main_block">
            <?php
            $wpc_holiday['wpc_reservation_holiday']       = isset($settings['wpc_reservation_holiday']) ? $settings['wpc_reservation_holiday'] : [];
            if (is_array($wpc_holiday['wpc_reservation_holiday']) && count($wpc_holiday['wpc_reservation_holiday']) > 0 && $wpc_holiday['wpc_reservation_holiday']['0'] !== '') {
                ?>
                <p class="wpc-desc"><?php echo esc_html__('Date', 'wpcafe-pro'); ?></p>
                <?php
                for ($index = 0; $index < count($wpc_holiday['wpc_reservation_holiday']); $index++) {
                    ?>
                    <div class="holiday_exception_block exception_block d-flex mb-2">
                        
                        <input type="text" name="wpc_reservation_holiday[]" value="<?php echo Wpc_Utilities::wpc_render($wpc_holiday['wpc_reservation_holiday'][$index]); ?>" class="wpc_reservation_holiday wpc_reservation_holiday_<?php echo intval($index); ?> mr-1 wpc-settings-input attr-form-control" id="holiday_delivery_date_<?php echo Wpc_Utilities::wpc_render($index) ?>" placeholder="<?php esc_html_e('Date', 'wpcafe-pro'); ?>" />
                        <span class="wpc_reservation_holiday_clear" id="<?php echo intval( $index )?>" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                        <?php if( $index != 0 ) { ?>
                            <span class="wpc-btn-close dashicons dashicons-no-alt remove_holiday_exception_block wpc_icon_middle_position"></span>
                        <?php } ?>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="holiday_exception_block exception_block d-flex mb-2">
                    <input type="text" name="wpc_reservation_holiday[]" value="" class="wpc_reservation_holiday wpc_reservation_holiday_0 mr-1 wpc-settings-input attr-form-control" placeholder="<?php esc_html_e('Date','wpcafe-pro');?>" />
                    <span class="wpc_reservation_holiday_clear" id="0" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="wpc_flex_reverse holiday_add_section ">
            <span class="add_holiday_reservation_block wpc-btn-text wpc-tooltip" data-title="<?php echo esc_attr__('Add another day', 'wpcafe-pro'); ?>" data-date_text="<?php echo esc_html__("Date", "wpcafe-pro");?>" 
                data-clear_button_text="<?php echo esc_html__('Reset fields', 'wpcafe-pro'); ?>">
                <?php echo esc_html__('Add','wpcafe-pro'); ?>
                <small class="wpc-tooltip-angle"></small>
            </span>
        </div>
    </div>
</div>

<?php return; ?>