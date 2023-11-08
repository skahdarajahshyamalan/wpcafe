<?php
use WpCafe\Utils\Wpc_Utilities;
$settings                  = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
$qrcode_data               = !empty( $settings['wpc_pro_qrcode_data'] ) ? $settings['wpc_pro_qrcode_data'] : [];
$wpc_pro_qrcode_id         = !empty( $settings['wpc_pro_qrcode_id'] ) ? $settings['wpc_pro_qrcode_id'] : [];

$qrcode_json_data = \json_encode($qrcode_data);
$qrcode_id_json_data = \json_encode($wpc_pro_qrcode_id);
?>
<div class="qrcode-option-container" data-qrcode="<?php echo esc_attr($qrcode_json_data ); ?>" data-qrcode-id="<?php echo esc_attr($qrcode_id_json_data ); ?>"> 
    <div class="wpc-label-item">
        <div class="wpc-label">
            <label for="wpc_pro_qrcode" class="wpc-settings-label"><?php esc_html_e('Food Menu QR Code', "wpcafe-pro" ); ?></label>
            <p class="wpc-desc"> <?php esc_html_e("Add the table name or table id, and the page URL. With that, the first 2 parameters will show up during the QR Code scan.", "wpcafe-pro" ); ?> </p>

            <div class="qrcode_main_block">
                <?php
                $wpc_pro_qrcode['wpc_pro_qrcode_data'] = isset($settings['wpc_pro_qrcode_data']) ? $settings['wpc_pro_qrcode_data'] : [];
                $wpc_pro_qrcode['wpc_pro_qrcode_id'] = isset($settings['wpc_pro_qrcode_id']) ? $settings['wpc_pro_qrcode_id'] : [];

                if (is_array($wpc_pro_qrcode['wpc_pro_qrcode_data']) && count($wpc_pro_qrcode['wpc_pro_qrcode_data']) > 0 && $wpc_pro_qrcode['wpc_pro_qrcode_data']['0'] !== '') {
                    for ($index = 0; $index < count($wpc_pro_qrcode['wpc_pro_qrcode_data']); $index++) {
                        ?>
                        <div class="qrcode_block d-flex mb-2 wpc-schedule-field">
                                <label class="wpc-qr-info-label wpc-qr-info-label-id"> <span class="wpc-qr-info-labe-txt"><?php echo esc_html__('Table Name/ID', 'wpcafe-pro') ?></span>
                                    <input type="text"  name="wpc_pro_qrcode_id[]" value="<?php echo Wpc_Utilities::wpc_render($wpc_pro_qrcode['wpc_pro_qrcode_id'][$index]); ?>" 
                                    class="wpc_pro_qrcode_id wpc_pro_qrcode_id<?php echo intval($index); ?> mr-1 wpc-settings-input attr-form-control" 
                                    id="wpc_pro_qrcode_id<?php echo Wpc_Utilities::wpc_render($index) ?>" placeholder="<?php esc_html_e('Enter table name/ID','wpcafe-pro');?>" />
                                </label>
                                <label class="wpc-qr-info-label">
                                    <span class="wpc-qr-info-labe-txt"> <?php echo esc_html__('Enter Your page url', 'wpcafe-pro') ?></span>
                                    <input type="text"  name="wpc_pro_qrcode_data[]" value="<?php echo Wpc_Utilities::wpc_render($wpc_pro_qrcode['wpc_pro_qrcode_data'][$index]); ?>" 
                                    class="wpc_pro_qrcode_data wpc_pro_qrcode_data_<?php echo intval($index); ?> mr-1 wpc-settings-input attr-form-control" 
                                    id="wpc_pro_qrcode_data<?php echo Wpc_Utilities::wpc_render($index) ?>" placeholder="<?php esc_html_e('Enter page URL','wpcafe-pro');?>" />
                                </label>
                                <div class="wpc-qr-img-<?php echo esc_attr($index); ?>"></div>
                              <?php if( $index != 0 ) { ?>
                                <span class="wpc-btn-close dashicons dashicons-no-alt remove_qrcode_block wpc_icon_middle_position"></span>
                            <?php } ?>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="qrcode_block d-flex mb-2 wpc-schedule-field">
                        <label class="wpc-qr-info-label wpc-qr-info-label-id"> <span class="wpc-qr-info-labe-txt"><?php echo esc_html__('Table Name/ID', 'wpcafe-pro') ?></span>
                            <input type="text"  name="wpc_pro_qrcode_id[]" value=""
                                    class="wpc_pro_qrcode_data wpc_pro_qrcode_id mr-1 wpc-settings-input attr-form-control"
                                    placeholder="<?php esc_html_e('Enter table name/ID','wpcafe-pro');?>" /> 
                        </label>
                        <label class="wpc-qr-info-label wpc-qr-info-label-id"> <span class="wpc-qr-info-labe-txt"><?php echo esc_html__('Enter Page URL', 'wpcafe-pro') ?></span>
                              <input type="text"  name="wpc_pro_qrcode_data[]" value="" class="wpc_pro_qrcode_data wpc_pro_qrcode_data_0 mr-1 wpc-settings-input attr-form-control" placeholder="<?php esc_html_e('Enter page URL','wpcafe-pro');?>" />

                        </label>
                    </div>
                    <?php
                }
                ?>

            </div>

            <div class="wpc_flex_reverse qrcode_add_section ">
                <span class="add_qrcode_block wpc-btn-text" data-table-name="<?php echo esc_attr('Table Name/ID') ?>" data-pc_text="<?php echo esc_attr__('Enter page URL', 'wpcafe-pro'); ?>" data-clear_button_text="<?php echo esc_html__("Reset fields", "wpcafe-pro");?>" >
                    <?php echo esc_html__('Add','wpcafe-pro'); ?>
                </span>
            </div>

        </div>
    </div>
</div>

<?php return; ?>