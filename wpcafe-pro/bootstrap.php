<?php
namespace WpCafe_Pro;

defined( 'ABSPATH' ) || exit;

use Wpcafe_Pro;
use WpCafe_Pro\Autoloader;
use WpCafe_Pro\Utils\Utilities;
use WpCafe_Pro\Widgets\Manifest;

/**
 * Autoload all classes
 */
require_once plugin_dir_path( __FILE__ ) . '/autoloader.php';

final class Bootstrap {

    private static $instance;
    /**
     * Register action
     */
    private function __construct() {
        // load autoload method
        Autoloader::run();
    }

    /**
     * Main function
     *
     * @return void
     */
    public function init() {
        $this->prepare_wpcafe();

        // activation and deactivation hook
        register_activation_hook( __FILE__, [$this, 'active'] );
        register_deactivation_hook( __FILE__, [$this, 'deactive'] );

        //enqueue file
        \WpCafe_Pro\Core\Enqueue\Enqueue::instance()->init();

        // fire in every plugin load action
        $this->init_plugin();

        $user = wp_get_current_user();
        $allowed_roles = array('editor', 'administrator','author', 'shop_manager', 'order_manager');
        if( array_intersect($allowed_roles, $user->roles ) ) {  
            add_action( 'admin_footer', [$this, 'add_notification_div_wrap'] );
        }

    }

     /**
     * Add markup in admin dashboard for showing notification list
     */
    public static  function add_notification_div_wrap(){
        ?>
        <div class="wpc-notification-wrapper" id="wpc-notification-wrapper"> <a href="#" class="wpc-notification-clear" style="display: none"><?php echo esc_html__('Clear', 'wpcafe-pro'); ?></a></div>
        <?php
    }

    /**
     * Prepare wp-cafe free version if not activated
     *
     * @return void
     */
    private function prepare_wpcafe() {

        // if wpcafe not installed
        if ( !did_action( 'wpcafe/after_load' ) ) {
            if ( \WpCafe_Pro\Utils\Utilities::make_wpcafe_ready() ) {
                // redirect to plugin dashboard
                wp_safe_redirect( "admin.php?page=cafe_menu" );
            };
        }

    }

    /**
     * do stuff on active
     *
     * @return void
     */
    public function active() {
        $installed = get_option( 'wpc_pro_cafe_installed' );

        if ( !$installed ) {
            update_option( 'wpc_pro_cafe_installed', time() );
        }

        update_option( 'wpc_pro_cafe_version', \Wpcafe_Pro::version() );

    }

    /**
     * do stuff on deactive
     *
     * @return void
     */
    public function deactive() {
        flush_rewrite_rules();
    }
    
    public static function instance() {

        if ( !self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Load all class
     *
     * @return void
     */
    public function init_plugin() {

        if ( !did_action( 'wpcafe/after_load' ) ) {
            add_action( 'admin_notices', [$this, 'notice_wpcafe_not_active'] );
            return;
        }

        // call ajax submit
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            // All ajax action
            \WpCafe_Pro\Core\Action\Ajax_Action::instance()->init();
            
            // Admin order push notifications
            \WpCafe_Pro\Core\Modules\Order_Notifications\Hooks::instance()->init();
        }

        // load elementor
        Manifest::instance()->init();

        // call admin action
        if ( is_admin() ) {
            \WpCafe_Pro\Core\Core::instance()->admin_action_init();
        }

        // load shortcode and all init action
        $this->register_shortcodes();

        \WpCafe_Pro\Core\Modules\Reservation_With_Food\Hooks::instance()->init();

        // load food menu all hooks
        \WpCafe_Pro\Core\Modules\Food_Menu\Hooks::instance()->init();
        
        //initialize license if only multisite is enabled and current site is main network site
        if ( (!is_multisite()) || (is_multisite() && is_main_network() && is_main_site() && defined( 'MULTISITE' )) ) {
            $this->initialize_license_module();
        }

        if ( \WpCafe_Pro\Utils\Utilities::is_table_layout_enabled() ) {
            // Table layout related all hooks and mechanism
            \WpCafe_Pro\Core\Modules\Table_Layout\Wpc_Table_Layout::instance()->init();
        }
    }
 

    public function initialize_license_module(){

        //add submenu for license
        add_action( "admin_menu", [$this, "add_submenu_for_license"], 99 );
            
        //fire up edd update module
        Utils\Updater\Init::instance()->init();
        
        //handle license notice
        $this->manage_license_notice();
    }

    /**
     * Load on plugin
     *
     * @return void
     */
    public function notice_wpcafe_not_active() {

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        if ( file_exists( WP_PLUGIN_DIR . '/wp-cafe/wpcafe.php' ) ) {
            $btn['label'] = esc_html__( 'Activate WpCafe', 'wpcafe-pro' );
            $btn['url']   = wp_nonce_url( 'plugins.php?action=activate&plugin=wp-cafe/wpcafe.php&plugin_status=all&paged=1', 'activate-plugin_wp-cafe/wpcafe.php' );
        } else {
            $btn['label'] = esc_html__( 'Install WpCafe', 'wpcafe-pro' );
            $btn['url']   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=wp-cafe' ), 'install-plugin_wp-cafe' );
        }

        Utilities::push(
            [
                'id'          => 'unsupported-wpcafe-version',
                'type'        => 'error',
                'dismissible' => true,
                'btn'         => $btn,
                'message'     => sprintf( esc_html__( 'WpCafe Pro requires WpCafe  , which is currently NOT RUNNING.', 'wpcafe-pro' ) ),
            ]
        );
    }

    /**
     * Register shortcode function
     *
     * @return void
     */
    public function register_shortcodes() {
        \WpCafe_Pro\Core\Shortcodes\Hook::instance()->init();
        
        //Frontend actions
        \WpCafe_Pro\Core\Core::instance()->frontend_action_init();
    }

    public function product_id() {
        return '1007';
    }

    public function store_url() {
        return 'https://themewinter.com';
    }

    public function marketplace() {
        return 'themewinter';
    }

    public function author_name() {
        return 'themewinter';
    }

    /**
     * Add admin submenu page for license
     */
    public function add_submenu_for_license() {
        //add submenu page for go-pro
        add_submenu_page(
            'cafe_menu',
            esc_html__( 'License', 'wpcafe-pro' ),
            esc_html__( 'License', 'wpcafe-pro' ),
            'manage_options',
            'wpc-license',
            [$this, 'wpc_license_page']
        );
    }

    public function wpc_license_page() {
        $file_path = plugin_dir_path( __FILE__ ) . "/core/settings/views/license-settings.php";

        if ( file_exists( $file_path ) ) {
            include_once $file_path;
        }

    }

    public function manage_license_notice() {
                
        // Register license module
        $license = \WpCafe_Pro\Utils\License\License::instance();
        $license->init();

        $settings               = get_option( "wpc_premium_marketplace" );
        $selected_market_place  = empty( $settings ) ? "" : $settings;
        if( $selected_market_place == "codecanyon" ){
            return;
        }


        if ( $license->status() != 'valid' ) {
            \Oxaim\Libs\Notice::instance( 'wpcafe-pro', 'pro-not-active' )
            ->set_class( 'error' )
            ->set_dismiss( 'global', ( 3600 * 24 * 30 ) )
            ->set_message( esc_html__( 'Please activate WpCafe Pro to get automatic updates and premium support.', 'wpcafe-pro' ) )
            ->set_button( [
                'url'   => self_admin_url( 'admin.php?page=wpc-license' ),
                'text'  => 'Activate License Now',
                'class' => 'button-primary',
            ] )
            ->call();
        }

    }

}
