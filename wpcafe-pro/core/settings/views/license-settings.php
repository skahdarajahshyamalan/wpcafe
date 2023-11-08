<?php
$license                = \WpCafe_Pro\Utils\License\License::instance();
$settings               = get_option( "wpc_premium_marketplace" );
$selected_market_place  = empty( $settings ) ? "" : $settings;

?>
<!-- header start -->
<?php include_once \Wpcafe::core_dir() . "settings/layout/header.php"; ?>
<!-- header end -->

<div class="wpc-admin-sec wpc-license-module-parent">
    <div class="wpc-settings-section wpc-row">
        <div class="wpc-col-md-6">
            <div class="wpc-license-item">
                <div class="wpc-marketplace-data-holder">
                    <label for="marketplace">
                        <h2 class="wpc-admin-section-header-title wpc-main-title"></i><?php esc_html_e('Marketplace', 'wpcafe-pro'); ?></h2>
                    </label>
                    <div class="wpc-desc"> 
                        <?php esc_html_e('Select the marketplace from where you bought the premium version', 'wpcafe-pro'); ?>
                    </div>
                    <div  class="attr-form-group marketplace-save-result">
                        <span class="wpc-marketplace-save-result"></span>
                    </div>
                    <div class="wpc-marketplace-input-wrapper wpc-label-item wpc-p-0">
                        <div class="wpc-marketplace-item">
                            <select class="wpc-settings-input wpc-input-dark input-select wpc-select-marketplace" name="marketplace">
                                <option value="select"><?php echo esc_html__('Select Marketplace', 'wpcafe-pro'); ?></option>
                                <option value="codecanyon" <?php selected($selected_market_place, 'codecanyon', true); ?>><?php echo esc_html__('CodeCanyon', 'wpcafe-pro'); ?></option>
                                <option value="themewinter" <?php selected($selected_market_place, 'themewinter', true); ?>><?php echo esc_html__('Themewinter', 'wpcafe-pro'); ?></option>
                            </select>
                        </div>

                        <div  class="attr-form-group">
                            <button class="wpc-btn-save wpc-success wpc-btn-save-marketplace <?php echo esc_attr($selected_market_place); ?>">
                                <?php echo esc_html__('save marketplace for future use', 'wpcafe-pro'); ?>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="wpc-marketplace-codecanyon">
                    <div class="wpc-admin-section-header">
                        <h2 class="wpc-admin-section-header-title"><i class="icon icon-key2"></i><?php echo esc_html__('For CodeCanyon Platform', 'wpcafe-pro');?></h2>
                    </div>
                    <div class="wpc-admin-card attr-tab-content wpc-admin-card-shadow">
                        <div class="attr-card-body">
                            <p>
                                <?php echo esc_html__('No license key is required for CodeCanyon users. You can only use ', 'wpcafe-pro');?>
                                <a href="<?php echo esc_url('https://envato.com/market-plugin/');?>"><?php echo esc_html__('Envato Market', 'wpcafe-pro'); ?></a>
                                <?php echo esc_html__(' plugin to get auto update of premium version ', 'wpcafe-pro');?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="wpc-marketplace-themewinter">
                    <?php
                    if( $license->status() !== 'valid' ){
                        ?>
                           <div class="wpc-license-content">
                                <div class="wpc-admin-section-header">
                                    <h2 class="wpc-admin-section-header-title"><i class="icon icon-key2"></i><?php echo esc_html__("For Themewinter Platform", "wpcafe-pro");?></h2>
                                </div>
                                
                                <ul class="wpc-license-link">
                                    <li><?php echo esc_html__("If you don", "wpcafe-pro"); ?>&#039;<?php echo esc_html__("t yet have a license key, get ", "wpcafe-pro"); ?><a href="https://themewinter.com/wp-cafe/" target="_blank"><?php echo esc_html__("WPCafe Pro", "wpcafe-pro"); ?></a><?php echo esc_html__(" now.", "wpcafe-pro");?></li>
                                    <li><?php echo esc_html__( "Log in to your ", "wpcafe-pro" ); ?><a href="https://themewinter.com/purchase-history/" target="_blank"><?php echo esc_html__("Themewinter account", "wpcafe-pro"); ?></a><?php echo esc_html__(" to get your license key.", "wpcafe-pro");?></li>
                                    <li><?php echo esc_html__("Copy the license key from your account and paste it below.", "wpcafe-pro");?></li>
                                    <li><?php echo esc_html__("Follow the ", "wpcafe-pro");?> 
                                        <a href="https://support.themewinter.com/docs/plugins/wp-cafe/license-wpcafe/" target="_blank"><?php echo esc_html__("Official Documentation", "wpcafe-pro"); ?></a>
                                        <?php echo esc_html__("for details ", "wpcafe-pro");?> 
                                    </li>
                                </ul>
                           </div>
                        <?php
                    } else {
                        ?>
                        <div class="wpc-license-form-result">
                            <p class="attr-alert attr-alert-success">
                                <?php printf( esc_html__('Congratulations! Your product is activated for "%s"', 'wpcafe-pro'), parse_url(home_url(), PHP_URL_HOST)); ?>
                            </p>
                            <a href="#" class='wpc-btn wpc-btn-secondary wpc-revoke-license-text'><?php echo esc_html__('Revoke License', 'wpcafe-pro');?></a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>
        <?php if( $license->status() !== 'valid' ){?>
            <div class="wpc-col-md-6">
                <div class="wpc-license-item wpc-marketplace-themewinter">
                    <form action="" method="POST" class="wpc-admin-form" id="wpc-admin-license-form">
                        <div class="wpc_tab_wraper">
                            <label class="wpc-admin-option-text-wpc-license-key" for="wpc-admin-option-text-wpc-license-key" >
                                <h2 class="wpc-main-title"><?php echo esc_html__("Your License Key", "wpcafe-pro");?></h2>                                
                            </label>
                            <div class="wpc-desc"> 
                                <?php esc_html_e('Enter your license key here, to get auto updates.', 'wpcafe-pro'); ?>
                            </div>
                            <div class="wpc-admin-input-text  wpc-license-input-box">
                                <input
                                    type="text"
                                    class="attr-form-control"
                                    id="wpc-admin-option-text-wpc-license-key"
                                    aria-describedby="wpc-admin-option-text-help-wpc-license-key"
                                    placeholder="Please insert your license key here"
                                    name="elementkit_pro_license_key"
                                    value=""
                                >
                            </div>

                            <div class="attr-input-group-btn wpc-license-input-box">
                                <input type="hidden" name="type" value="activate" />
                                <button class="wpc-btn wpc-btn-primary btn-license-activate wpc-admin-license-form-submit" type="submit" ><div class="wpc-spinner"></div><i class="wpc-admin-save-icon fa fa-check-circle"></i> <?php echo esc_html__("Activate License", "wpcafe-pro");?></button>
                            </div>
                            <div class="wpc-license-form-result wpc-license-input-box">
                                <p class="attr-alert">
                                    <?php echo esc_html__("Still can", "wpcafe-pro");?>&#039;<?php echo esc_html__("t find your license key? ", "wpcafe-pro");?><a target="_blank" href="https://themewinter.com/support/"><?php echo esc_html__("Knock us here!", "wpcafe-pro");?></a>
                                </p>
                            </div>
                            <div class="wpc-license-result-box"></div>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
