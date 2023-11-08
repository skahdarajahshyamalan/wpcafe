<?php
use WpCafe\Utils\Wpc_Utilities;
$wpc_pro_menu_settings                  = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option(); 
$wpc_pro_tip_enable                     = isset($wpc_pro_menu_settings['wpc_pro_tip_enable']) ? 'checked' : '';
$wpc_pro_tip_allow_for                  = isset($wpc_pro_menu_settings['wpc_pro_tip_allow_for']) ? $wpc_pro_menu_settings['wpc_pro_tip_allow_for'] : '';

$args = array(  'tip_both'=>esc_html__('Both','wpcafe-pro') , 'tip_percentage'=>esc_html__('Percentage','wpcafe-pro'), 'tip_fixed'=>esc_html__('Fixed','wpcafe-pro'));

$style = empty( $wpc_pro_tip_enable ) ? 'display: none;' : '';

?>

<div class="tip-option-container">
    <?php
    $markup_fields = [
        'wpc_pro_tip_enable' => [
            'item' => [
                'options'  =>['on'=>'on'],
                'label'    => esc_html__( 'Enable Tip?', 'wpcafe-pro' ),
                'desc'     => esc_html__( 'Do you want to enable the tip/donation option on cart and checkout pages?', 'wpcafe-pro' ),
                'type'     => 'checkbox',
                'attr'     => ['class' => 'wpc-label-item', 'input_class'=> 'wpcafe-admin-control-input'],
            ],
            'data' => [ 'wpc_pro_tip_enable' => $wpc_pro_tip_enable ],
        ],
        'wpc_pro_tip_allow_for' => [
            'item' => [
                'label'    => esc_html__( 'Allow Tip for', 'wpcafe-pro' ),
                'desc'     => esc_html__( 'You can set tip percentage/fixed or both', 'wpcafe-pro' ),
                'type'     => 'select_single',
                'options'  => $args,
                'attr'     => [
                    'class' => 'wpc-label-item wpc-pro-tip-enabled-block', 'input_class'=> 'wpc-settings-input', 'style' => $style
                ],
            ],
            'data' => [ 'wpc_pro_tip_allow_for' => $wpc_pro_tip_allow_for ],
        ],     
    ];
    
    foreach ( $markup_fields as $key => $info ) {
        $this->get_field_markup( $info['item'], $key, $info['data'] );
    }
    ?>                               
    
    <div class="wpc-label-item wpc-pro-tip-enabled-block" style="<?php echo empty( $wpc_pro_tip_enable ) ? 'display: none;' : '' ?>">
        <div class="wpc-label">
            <label for="wpc_pro_custom_tip_percentage" class="wpc-settings-label"><?php esc_html_e('Custom Tip Percentage', "wpcafe-pro" ); ?></label>
            <p class="wpc-desc"> <?php esc_html_e("Add custom value for tip percentage", "wpcafe-pro" ); ?> </p>
        </div>

        <div class="wpc-meta">
            <div class="tip_percentage_main_block">
                <?php
                $wpc_pro_tip_percentage['wpc_pro_tip_percentage_data'] = isset($wpc_pro_menu_settings['wpc_pro_tip_percentage_data']) ? $wpc_pro_menu_settings['wpc_pro_tip_percentage_data'] : [];
                
                if (is_array($wpc_pro_tip_percentage['wpc_pro_tip_percentage_data']) && count($wpc_pro_tip_percentage['wpc_pro_tip_percentage_data']) > 0 && $wpc_pro_tip_percentage['wpc_pro_tip_percentage_data']['0'] !== '') {
                    for ($index = 0; $index < count($wpc_pro_tip_percentage['wpc_pro_tip_percentage_data']); $index++) {
                        ?>
                        <div class="tip_percentage_block percentage_block d-flex mb-2 wpc-schedule-field">
                            <input type="number" min="0" name="wpc_pro_tip_percentage_data[]" value="<?php echo Wpc_Utilities::wpc_render($wpc_pro_tip_percentage['wpc_pro_tip_percentage_data'][$index]); ?>" 
                                class="wpc_pro_tip_percentage_data wpc_pro_tip_percentage_data_<?php echo intval($index); ?> mr-1 wpc-settings-input attr-form-control" 
                                id="wpc_pro_tip_percentage_data<?php echo Wpc_Utilities::wpc_render($index) ?>" placeholder="<?php esc_html_e('Add Tip Percentage','wpcafe-pro');?>" />
                            <span class="wpc_pro_tip_percentage_data_clear" id="<?php echo intval( $index )?>" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                            <?php if( $index != 0 ) { ?>
                                <span class="wpc-btn-close dashicons dashicons-no-alt remove_tip_percentage_block wpc_icon_middle_position"></span>
                            <?php } ?>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="tip_percentage_block percentage_block d-flex mb-2 wpc-schedule-field">
                        <input type="number" min="0" name="wpc_pro_tip_percentage_data[]" value="" class="wpc_pro_tip_percentage_data wpc_pro_tip_percentage_data_0 mr-1 wpc-settings-input attr-form-control" placeholder="<?php esc_html_e('Add Tip Percentage','wpcafe-pro');?>" />
                        <span class="wpc_pro_tip_percentage_data_clear" id="0" ><span class="dashicons dashicons-update-alt wpc-tooltip" data-title="<?php echo esc_attr__('Reset fields', 'wpcafe-pro'); ?>"> <small class="wpc-tooltip-angle"></small></span></span>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="wpc_flex_reverse tip_percentage_add_section ">
                <span class="add_tip_percentage_block wpc-btn-text" data-pc_text="<?php echo esc_attr__('Add Tip Percentage', 'wpcafe-pro'); ?>" data-clear_button_text="<?php echo esc_html__("Reset fields", "wpcafe-pro");?>" >
                    <?php echo esc_html__('Add','wpcafe-pro'); ?>
                </span>
            </div>

        </div>
    </div>
</div>

<?php return; ?>