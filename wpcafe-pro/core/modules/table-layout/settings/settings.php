<?php

namespace Wpc_Table_Layout\Modules\Settings;

use WpCafe\Core\Base\Wpc_Settings_Field;
use WpCafe\Utils\Wpc_Utilities;


defined( 'ABSPATH' ) || exit;
class Settings extends \WpCafe\Core\Base\Config{

    /**
     * Fire all hooks
     */
    public function __construct(){
        add_filter( 'wpc_table_layout/menus/admin_submenu_pages', [$this,'wpc_table_layout_settings'], 10 , 1 );
    }

    /**
     * admin part table layout view
     *
     * @param [type] $item
     * @param string $key
     * @param array $data
     * 
     * @return void
     */
    public function wpc_table_layout_settings() {
        $settings      = Wpc_Settings_Field::instance()->get_settings_option();
        $seat_capacity = isset($settings['rest_max_reservation']) ? $settings['rest_max_reservation'] : '100';
        
        ?>
        <?php include_once \Wpcafe::core_dir() . "settings/layout/header.php"; ?>


        <div class="wrap">
            <p class="wpc-table-notice">
                <?php echo esc_html__('Table Reservation modules works only with single slot reservation.', 'wpcafe-pro') ?>
                <a href="https://support.themewinter.com/docs/plugins/wp-cafe/table-selection-layout/" target="_blank"><?php echo esc_html__('Documentation', 'wpcafe-pro') ?></a>
            </p>
            <h2 class="wpc-heading medium"><?php echo esc_html__( "Visual Table Mapping", "wpcafe-pro" ); ?></h2>

            <div class="wpc-table-main" id="table-map" data-seat_capacity="<?php echo esc_attr($seat_capacity); ?>"></div>
        
        </div>
        <?php
    }
}

new Settings();
