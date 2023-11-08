<?php

namespace WpCafe_Pro\Core\Modules\Table_Layout;

use Wpcafe_Pro;

defined( 'ABSPATH' ) || exit;

class Wpc_Table_Layout {
    use \WpCafe_Pro\Traits\Singleton;

    /**
	 * constructor function for class initialization 
     * 
     * @return void
	 */
    public function init() {

        // Includes necessary files
        $this->include_files();

        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin' ] );

        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_public'] );  

    }

    /**
     * include additional files
     * 
     * @return void
     */
    private function include_files() {
        // Controls_Manager
        include_once \WpCafe_Pro::plugin_dir() . '/utils/table-utils.php';

        if( file_exists( \Wpcafe::plugin_dir() . 'core/base/Api-Handler.php' )){
            include_once \Wpcafe::plugin_dir() . 'core/base/Api-Handler.php';
            include_once \Wpcafe_Pro::core_dir() . 'modules/table-layout/settings/settings.php';
            include_once \Wpcafe_Pro::core_dir() . 'modules/table-layout/settings/settings-api.php';
        } 


       
    }

    /**
     * Enqueue scripts
     *
     * Enqueue js and css to admin.
     *
     * @since 1.0.0
     * @access public
     * 
     * @return mixed
     */
    public function enqueue_admin() {

        // get screen id
        $screen    = get_current_screen();
        $screen_id = $screen->id;
        
        $allowed_screen_ids = [
            'toplevel_page_app_settings','wpcafe-app_page_app_notifications', 'wpcafe_page_wpc_table_layout',
        ];

        if( in_array($screen_id, $allowed_screen_ids) ){
            wp_enqueue_media();
            wp_enqueue_style( 'wpc-table-layout-admin', \Wpcafe_Pro::core_url() . 'modules/table-layout/assets/css/admin.css', [], \Wpcafe_Pro::version(), 'all' );
            wp_enqueue_script( 'wpc-table-index', \Wpcafe_Pro::plugin_url() . 'build/index.js', ['jquery', 'wp-element'] , \Wpcafe_Pro::version(), true );
            
            $localize_data                                = []; 
            $localize_data['site_url']                    = site_url();
            $localize_data['is_admin']                    = is_admin();
            $localize_data['table_layout']                = [
                'save_settings'         => esc_html__( 'Save Changes', 'wpcafe-pro' ),
                'saving'                => esc_html__( 'Saving...', 'wpcafe-pro' ),
                'loading'               => esc_html__( 'Loading...', 'wpcafe-pro' ),
                'capacity_reached_msg'  => esc_html__( 'You added the maximum of your seat. In order to add more, update seat capacity from settings.', 'wpcafe-pro' ),
                'separate_table_msg'    => esc_html__( 'Did you forget to insert the chair with the table? In order to proceed, you must insert the chair and connect with table.', 'wpcafe-pro' ),
                'default_table_name'    => esc_html__( 'Table Name', 'wpcafe-pro' ),
                'booked_alert_msg'      => esc_html__( 'Selected items contain some table/chair which are already booked. Deleting them may show inconsistent result in reservation list.', 'wpcafe-pro' ),
                'fields' => [
                    'seat_capacity'     => esc_html__( 'Seat Capacity', 'wpcafe-pro' ),
                    'canvas_width'      => esc_html__( 'Room Width', 'wpcafe-pro' ),
                    'canvas_height'     => esc_html__( 'Room Height', 'wpcafe-pro' ),
                    'table_fill_color'  => esc_html__( 'Table Fill Color', 'wpcafe-pro' ),
                    'chair_fill_color'  => esc_html__( 'Chair Fill Color', 'wpcafe-pro' ),
                    'text_fill_color'   => esc_html__( 'Text Fill Color', 'wpcafe-pro' ),
                    'selection_color'   => esc_html__( 'Selection Color', 'wpcafe-pro' ),
                    'booked_color'      => esc_html__( 'Booked Color', 'wpcafe-pro' ),
                    'canvas_bg_img'     => esc_html__( 'Room Background Image', 'wpcafe-pro' ),
                    'select_image'      => esc_html__( 'Upload Image', 'wpcafe-pro' ),
                    'create_table_layout'      => esc_html__( 'Create Table Layout', 'wpcafe-pro' ),
                ],
                'buttons' => [
                    'table'  => esc_html__( 'Add Corner Table', 'wpcafe-pro' ),
                    'table_round'         => esc_html__( 'Add Round Table', 'wpcafe-pro' ),
                    'chair'         => esc_html__( 'Add Chair', 'wpcafe-pro' ),
                    'text'          => esc_html__( 'Add Text', 'wpcafe-pro' ),
                    'zoom_in'       => esc_html__( 'Zoom In', 'wpcafe-pro' ),
                    'zoom_out'      => esc_html__( 'Zoom Out', 'wpcafe-pro' ),
                    'delete_items'  => esc_html__( 'Delete Selected Item', 'wpcafe-pro' ),
                ],
            ];

            wp_localize_script( 'wpc-table-index', 'localized_data_obj', $localize_data );
        }
    }
    
    /**
     * enqueue and localize public resources
     *
     * @return void
     */
    public function enqueue_public() {
        wp_enqueue_style( 'wpc-table-layout-public', \Wpcafe_Pro::core_url() . 'modules/table-layout/assets/css/public.css', [], \Wpcafe_Pro::version(), 'all' );
        wp_enqueue_script( 'wpc-table-index', \Wpcafe_Pro::plugin_url() . 'build/index.js', ['jquery', 'wp-element'] , \Wpcafe_Pro::version(), true );

        $localize_data                                = []; 
            $localize_data['site_url']                    = site_url();
             $localize_data['table_layout']                = [ 
                'buttons' => [
                    'zoom_in'                             => esc_html__( 'Zoom In', 'wpcafe-pro' ),
                    'zoom_out'                            => esc_html__( 'Zoom Out', 'wpcafe-pro' ),
                    'zoom_reset'                            => esc_html__( 'Zoom Reset', 'wpcafe-pro' ),
                    'available_seats'                     => esc_html__( 'Available Seats', 'wpcafe-pro' ),
                    'selected_seats'                      => esc_html__( 'Selected Seats', 'wpcafe-pro' ),
                    'unavailable_seats'                   => esc_html__( 'Unavailable Seats', 'wpcafe-pro' ),
                 ],
            ];
            wp_localize_script( 'wpc-table-index', 'localized_data_obj', $localize_data );
    }
    
}
