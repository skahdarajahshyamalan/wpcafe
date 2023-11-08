<?php
namespace WpCafe_Pro\Utils\Updater;
use WpCafe_Pro\Utils\Utilities;

defined( 'ABSPATH' ) || exit;

class Init {

    use \WpCafe_Pro\Traits\Singleton;

    public function init() {
        $license_key = explode( '-', trim( get_option( "wpc_license_key" ) ) );
        $license_key = !isset( $license_key[0] ) ? '' : $license_key[0];

        $plugin_dir_and_filename = \Wpcafe_Pro::plugin_dir() . 'wpcafe-pro.php';

        $active_plugins = get_option( 'active_plugins' );

        foreach ( $active_plugins as $active_plugin ) {

            if ( false !== strpos( $active_plugin, 'wpcafe-pro.php' ) ) {
                $plugin_dir_and_filename = $active_plugin;
                break;
            }

        }

        if ( !isset( $plugin_dir_and_filename ) || empty( $plugin_dir_and_filename ) ) {
            throw ( esc_html__('Plugin not found! Check the name of your plugin file in the if check above', "wpcafe-pro") );
        }

        new Edd_Warper(
            \WpCafe_Pro\Bootstrap::instance()->store_url(),
            $plugin_dir_and_filename,
            [
                'version' => \WpCafe_Pro::version(),
                'license' => $license_key,
                'item_id' => \WpCafe_Pro\Bootstrap::instance()->product_id(),
                'author'  => \WpCafe_Pro\Bootstrap::instance()->author_name(),
                'url'     => home_url(),
            ]
        );
    }

}
