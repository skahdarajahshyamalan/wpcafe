<?php
namespace WpCafe_Pro\Core;

defined( "ABSPATH" ) || exit;

/**
 * Load all admin class
 */
class Core {
    use \WpCafe_Pro\Traits\Singleton;

    /**
     *  Call admin function
     */
    public function admin_action_init() {

        // settings
        \WpCafe_Pro\Core\Settings\Keyoptions::instance()->init();

        add_action( 'admin_head', [$this,'add_custom_admin_css'], 10, 1 );

        /**
         * Register metaboxes for product preparing and delivery time
         */
        $foodmenu_metabox = \WpCafe_Pro\Core\Metaboxes\Foodmenu_Meta::instance();
        add_action( 'add_meta_boxes', [$foodmenu_metabox, 'register_meta_boxes'] );
        add_action( 'save_post', [$foodmenu_metabox, 'save_meta_box_data'] );

        if ( is_array( $foodmenu_metabox ) ) {
            add_filter( 'wp_insert_post_data', [$foodmenu_metabox, 'save_foodmenu_title'], 500, 2 );
        }

    }

    function add_custom_admin_css(){
        $currentScreen = get_current_screen();

        if( $currentScreen->id === "edit-shop_order" ) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery("tbody").addClass("wpc_shop_order") 
                })
            </script>
            <?php
        }
    }
    /**
     *  Call frontend init function
     */
    public function frontend_action_init() {
        // Food delivery hooks
        \WpCafe_Pro\Core\Modules\Food_Delivery\Food_Delivery::instance()->init();
        // Food discount hooks
        \WpCafe_Pro\Core\Modules\Food_Menu\Food_Discount::instance()->init();
        // Reservation hooks
        \WpCafe_Pro\Core\Modules\Reservation\Hooks::instance()->init();
        //register reservation report dashboard
        \WpCafe_Pro\Core\Modules\Reservation\Reservation_Report::instance()->init();
        // Order tip related all hooks and mechanism
        \WpCafe_Pro\Core\Modules\Tip\Tip::instance()->init();
        // product customizable option related hook in frontend 
        \WpCafe_Pro\Core\Modules\Product_Addons\Frontend\Hooks::instance()->init();

        // product addons related all hooks and mechanism
        \WpCafe_Pro\Core\Modules\Product_Addons\Admin\Hooks::instance()->init();

        // Food delivery hooks
        \WpCafe_Pro\Core\Modules\Pickup_Delivery\Pickup_Delivery::instance()->init();

    }
}
