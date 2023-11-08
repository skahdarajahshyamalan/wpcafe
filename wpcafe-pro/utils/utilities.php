<?php

namespace WpCafe_Pro\Utils;

use DateTime;
use WpCafe\Utils\Wpc_Utilities;
use WpCafe_Pro\Core\Shortcodes\Hook;

defined("ABSPATH") || exit;

class Utilities
{

    use \WpCafe_Pro\Traits\Singleton;

    /**
     * Show Notices
     *
     * @since 1.0.0
     * @return void
     */
    public static function push($notice){
        $defaults = [
            'id'               => '',
            'type'             => 'info',
            'show_if'          => true,
            'message'          => '',
            'class'            => 'wpc-active-notice',
            'dismissible'      => false,
            'btn'              => [],
            'dismissible-meta' => 'user',
            'dismissible-time' => WEEK_IN_SECONDS,
            'data'             => '',
        ];

        $notice = wp_parse_args($notice, $defaults);

        $classes = ['wpc-notice', 'notice'];

        $classes[] = $notice['class'];

        if (isset($notice['type'])) {
            $classes[] = 'notice-' . $notice['type'];
        }

        // Is notice dismissible?
        if (true === $notice['dismissible']) {
            $classes[] = 'is-dismissible';

            // Dismissable time.
            $notice['data'] = ' dismissible-time=' . esc_attr($notice['dismissible-time']) . ' ';
        }

        // Notice ID.
        $notice_id    = 'wpc-sites-notice-id-' . $notice['id'];
        $notice['id'] = $notice_id;

        if (!isset($notice['id'])) {
            $notice_id    = 'wpc-sites-notice-id-' . $notice['id'];
            $notice['id'] = $notice_id;
        } else {
            $notice_id = $notice['id'];
        }

        $notice['classes'] = implode(' ', $classes);


        // User meta.
        $notice['data'] .= ' dismissible-meta=' . esc_attr($notice['dismissible-meta']) . ' ';

        if ('user' === $notice['dismissible-meta']) {
            $expired = get_user_meta(get_current_user_id(), $notice_id, true);
        } elseif ('transient' === $notice['dismissible-meta']) {
            $expired = get_transient($notice_id);
        }

        // Notice visible after transient expire.
        if (isset($notice['show_if'])) {
            if (true === $notice['show_if']) {

                // Is transient expired?
                if (false === $expired || empty($expired)) {
                    self::markup($notice);
                }
            }
        } else {
            self::markup($notice);
        }
    }

    /**
     * return same data function
     *
     * @param $content
     * @return void
     */
    public static function render($content)
    {
        if ($content == "") {
            return "";
        }

        return $content;
    }

    /**
     * Allowed vallid html
     *
     * @param [type] $raw
     * @return void
     */
    public static function kses($raw)
    {
        $allowed_tags = [
            'a'                             => [
                'class'  => [],
                'href'   => [],
                'rel'    => [],
                'title'  => [],
                'target' => [],
            ],
            'input'                         => [
                'value'       => [],
                'type'        => [],
                'size'        => [],
                'name'        => [],
                'checked'     => [],
                'placeholder' => [],
                'id'          => [],
                'class'       => [],
            ],

            'select'                        => [
                'value'       => [],
                'type'        => [],
                'size'        => [],
                'name'        => [],
                'placeholder' => [],
                'id'          => [],
                'class'       => [],
                'option'      => [
                    'value'   => [],
                    'checked' => [],
                ],
            ],

            'textarea'                      => [
                'value'       => [],
                'type'        => [],
                'size'        => [],
                'name'        => [],
                'rows'        => [],
                'cols'        => [],

                'placeholder' => [],
                'id'          => [],
                'class'       => [],
            ],
            'abbr'                          => [
                'title' => [],
            ],
            'b'                             => [],
            'blockquote'                    => [
                'cite' => [],
            ],
            'cite'                          => [
                'title' => [],
            ],
            'code'                          => [],
            'del'                           => [
                'datetime' => [],
                'title'    => [],
            ],
            'dd'                            => [],
            'div'                           => [
                'class' => [],
                'title' => [],
                'style' => [],
            ],
            'dl'                            => [],
            'dt'                            => [],
            'em'                            => [],
            'h1'                            => [
                'class' => [],
            ],
            'h2'                            => [
                'class' => [],
            ],
            'h3'                            => [
                'class' => [],
            ],
            'h4'                            => [
                'class' => [],
            ],
            'h5'                            => [
                'class' => [],
            ],
            'h6'                            => [
                'class' => [],
            ],
            'i'                             => [
                'class' => [],
            ],
            'img'                           => [
                'alt'    => [],
                'class'  => [],
                'height' => [],
                'src'    => [],
                'width'  => [],
            ],
            'li'                            => [
                'class' => [],
            ],
            'ol'                            => [
                'class' => [],
            ],
            'p'                             => [
                'class' => [],
            ],
            'q'                             => [
                'cite'  => [],
                'title' => [],
            ],
            'span'                          => [
                'class' => [],
                'title' => [],
                'style' => [],
            ],
            'iframe'                        => [
                'width'       => [],
                'height'      => [],
                'scrolling'   => [],
                'frameborder' => [],
                'allow'       => [],
                'src'         => [],
            ],
            'strike'                        => [],
            'br'                            => [],
            'strong'                        => [],
            'data-wow-duration'             => [],
            'data-wow-delay'                => [],
            'data-wallpaper-options'        => [],
            'data-stellar-background-ratio' => [],
            'ul'                            => [
                'class' => [],
            ],
            'label'                         => [
                'class' => [],
                'for' => [],
            ]
        ];

        if (function_exists('wp_kses')) { // WP is here
            return wp_kses($raw, $allowed_tags);
        } else {
            return $raw;
        }
    }

    /**
     * Markup Notice.
     *
     * @since 1.0.0
     * @param  array $notice Notice markup.
     * @return void
     */
    public static function markup($notice = [])
    {
?>
        <div id="<?php echo esc_attr($notice['id']); ?>" class="<?php echo esc_attr($notice['classes']); ?>" <?php echo Self::render($notice['data']); ?>>
            <p>
                <?php echo Self::kses($notice['message']); ?>
            </p>
            <?php
            if (!empty($notice['btn'])) : ?>
                <p>
                    <a href="<?php echo esc_url($notice['btn']['url']); ?>" class="button-primary"><?php echo esc_html($notice['btn']['label']); ?></a>
                </p>
            <?php endif; ?>
        </div>
<?php
    }

    /**
     * Check empty and single quote string function
     *
     * @return void
     */
    public static function data_validation_check($data)
    {
        $flag = false;

        if (isset($data) && sanitize_text_field($data) !== '') {
            $flag = true;
        }

        return $flag;
    }

    /**
     * Check empty and single quote string for array function
     *
     * @return void
     */
    public static function data_validation_check_arr($data)
    {
        $flag = false;

        foreach ($data as $key => $value) {

            if (isset($value) && sanitize_text_field($value) !== '') {
                $flag = true;
            }
        }

        return $flag;
    }


    public static function make_wpcafe_ready()
    {
        $basename = 'wp-cafe/wpcafe.php';
        $is_plugin_installed     = self::get_installed_plugin_data($basename);
        $plugin_data             = self::get_plugin_data('wp-cafe', $basename);

        if ($is_plugin_installed) {
            // upgrade plugin - attempt for once
            if (isset($plugin_data->version) && $is_plugin_installed['Version'] != $plugin_data->version) {
                self::upgrade_or_install_plugin($basename);
            }

            // activate plugin
            if (is_plugin_active($basename)) {
                return true;
            } else {
                activate_plugin(self::safe_path(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $basename), '', false, true);
                return true;
            }
        } else {
            // install & activate plugin
            $download_link = isset($plugin_data->download_link) ? $plugin_data->download_link : "";
            if (self::upgrade_or_install_plugin($download_link, false)) {
                return true;
            }
        }
        return false;
    }

    private static function get_plugin_data($slug = '', $basename = '')
    {
        if (empty($slug)) {
            return false;
        }
        $installed_plugin = false;
        if ($basename) {
            $installed_plugin = self::get_installed_plugin_data($basename);
        }

        if ($installed_plugin) {
            return $installed_plugin;
        }

        $args = array(
            'slug' => $slug,
            'fields' => array(
                'version' => false,
            ),
        );

        $response = wp_remote_post(
            'http://api.wordpress.org/plugins/info/1.0/',
            array(
                'body' => array(
                    'action' => 'plugin_information',
                    'request' => serialize((object) $args),
                ),
            )
        );

        if (is_wp_error($response)) {
            return false;
        } else {
            $response = unserialize(wp_remote_retrieve_body($response));

            if ($response) {
                return $response;
            } else {
                return false;
            }
        }
    }

    public static function get_installed_plugin_data($basename = '')
    {
        if (empty($basename)) {
            return false;
        }
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugins = get_plugins();
        return isset($plugins[$basename]) ? $plugins[$basename] : false;
    }

    private static function upgrade_or_install_plugin($basename = '', $upgrade = true)
    {
        if (empty($basename)) {
            return false;
        }
        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';

        $skin = new \Automatic_Upgrader_Skin;
        $upgrader = new \Plugin_Upgrader($skin);
        if ($upgrade == true) {
            $upgrader->upgrade($basename);
        } else {
            $upgrader->install($basename);
            activate_plugin($upgrader->plugin_info(), '', false, true);
        }
        return $skin->result;
    }

    public static function safe_path($path)
    {
        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }



    // menu location
    public static function get_menu_location($id = null)
    {
        $menu_category = [];
        try {

            if (is_null($id)) {
                $terms = get_terms([
                    'taxonomy'   => 'wpcafe_location',
                    'hide_empty' => false,
                ]);

                foreach ($terms as $cat) {
                    $menu_category[$cat->term_id] = $cat->name;
                }

                return $menu_category;
            } else {
                // return single menu
                return get_post($id);
            }
        } catch (\Exception $es) {
            return [];
        }
    }

    /**
     * Checking off day
     */
    public  static function checking_off_day( $holiday , $day_name ){
        $result = false;
        $off_days = "";
        if( !empty( $holiday ) && is_array( $holiday ) && count($holiday)> 0 ){
            $today = date( WPCAFE_DEFAULT_DATE_FORMAT );
            if ( in_array($today,$holiday) ) {
                $day = date('D', strtotime($today));
                $off_days = $day;
            }
        }
        if ( $day_name !== $off_days ) {
            $result = true;
        }

        return $result;
    }

    /**
     * Show business hour in markup
     */
    public  static function business_hour_markup( $args ){
        extract( $args );

        $result = self::checking_off_day( $holiday , $day_name );

        if ( $result ) {
            if ($alldays) {
                ?>
                    <li>
                        <?php
                        echo esc_html($day_name, 'wpcafe-pro') . '   ' . $start_time . '---' . $end_time;
                        ?>
                    </li>
                <?php
            } else {
                echo esc_html__('Our opening time ' . $start_time . ' and closing time 
                    ' . $end_time . ' ', 'wpcafe-pro');
            }
        }
    }

    /**
     * Show business hour multislot
     */
    public  static function business_hour_multislot( $args ){
        extract( $args );

        $result = self::checking_off_day( $holiday , $day_name );

        if ( $result ) {
            if ($alldays) {
                ?>
                    <div class="slot-item">
                       <strong>
                        <?php
                            echo esc_html($day_name);
                        ?>
                       </strong>
                        <ul>
                        <?php
                        foreach($start_time as $key => $time){
                            ?>
                            <li><?php echo esc_html($time). ' --- ' . esc_html($end_time[$key]); ?></li>
                            <?php
                        }
                        
                        ?>
                        </ul>
                    </div>
                <?php
            }
        }
    }


    /**
     * return time slots and  capacity in this time range
     */
    public static function  get_multi_status( $args ){
        $response =[]; $capacity = 0; $multi_start_schedule = ''; $multi_end_schedule = '';
        $state          = false;

				if ( !empty($args['selected_start_time']) ) {
					$date           = date("H:i", strtotime( $args['selected_start_time'] ) );
					$current_time   =  $date;
				}
				else{
					$selected_start_time   = date("H:i", strtotime( $args['selected_start_time'] ) );
					$date           = new \DateTime(date( WPCAFE_DEFAULT_DATE_FORMAT. " " . $selected_start_time   ));
					$current_time   =  $date->format("H:i");
				}

        for ($i=0; $i < count( $args['seat_capacity']); $i++) {
            if ( isset($args['multi_diff_start_time'][$i] ) && isset($args['multi_diff_end_time'][$i] ) ) {
                $multi_start_time   = date("H:i", strtotime( $args['multi_diff_start_time'][$i] ) );
                $multi_end_time     = date("H:i", strtotime( $args['multi_diff_end_time'][$i] ) );

                if ( ( $current_time >= $multi_start_time ) && ( $current_time <= $multi_end_time ) ) {
                    $capacity               = $args['seat_capacity'][$i];
                    $multi_start_schedule   = $args['multi_diff_start_time'][$i];
                    $multi_end_schedule     = $args['multi_diff_end_time'][$i];
                    $state = true;
                }
            }
        }
        if( $state == false ){
            $get_first_time  = array_shift( $args['multi_diff_end_time'] );
            $get_last_time  = array_pop( $args['multi_diff_start_time']);
            if( $current_time >= $get_first_time ){
                $multi_start_schedule   = $get_first_time;
                $multi_end_schedule     = $get_last_time;
            }
        }

        $response['multi_start_schedule']   = $multi_start_schedule ;
        $response['multi_end_schedule']     = $multi_end_schedule ;
        $response['capacity']               = $capacity;

        return $response;
    }

    public static function time_zone(){
        $current_offset      = get_option( 'gmt_offset' );
        $timezone_str        = get_option( 'timezone_string' );

        if ( false !== strpos( $timezone_str, 'Etc/GMT' ) ) {
            $timezone_str = '';
        }
        if ( empty( $timezone_str ) ) {
            if ( 0 == $current_offset ) {
                $timezone_str = 'UTC+0';
            } elseif ( $current_offset < 0 ) {
                $timezone_str = 'UTC' . $current_offset;
            } else {
                $timezone_str = 'UTC+' . $current_offset;
            }
        }
        return $timezone_str;
    }

    /**
     * Seat capacity range 
     */
    public static function multi_schedule_time_seat( $settings , $selected_start_time ='' ){
        $response =[]; $capacity = 0; $multi_start_schedule = ''; $multi_end_schedule = ''; 
 
        if ( isset( $settings['reser_multi_schedule'] ) && $settings['reser_multi_schedule'] == 'on' ) {

            if ( !empty( $settings['seat_capacity'][0] ) && !empty( $settings['multi_start_time'][0] ) &&
                !empty( $settings['multi_end_time'][0] ) ) {

                $args = array(
                    'state'         => false,
                    'seat_capacity' => $settings['seat_capacity'] ,
                    'multi_diff_start_time' => $settings['multi_start_time'] ,
                    'multi_diff_end_time'   => $settings['multi_end_time'] ,
                    'wpc_timezone'          => Utilities::time_zone(),
                    'wpc_time_format'       => WPCAFE_DEFAULT_TIME_FORMAT,
                    'selected_start_time'   => $selected_start_time,
                );

                $result_data             = Utilities::get_multi_status( $args  );

                $multi_start_schedule    = $result_data['multi_start_schedule'];
                $multi_end_schedule      = $result_data['multi_end_schedule'];
                $capacity                = $result_data['capacity'];
            }else {

                $get_today =  date('D') ;$get_key = "";

                if( !empty($settings['multi_diff_weekly_schedule'])  && is_array($settings['multi_diff_weekly_schedule']) ){
                    foreach ($settings['multi_diff_weekly_schedule'] as $key => $value) {
                        if ( !empty($value[$get_today ]) && "on" == $value[$get_today ]) {
                            $get_key = $key;
                        }
                    }
                }           

                if ( $get_key !=="" && count( $settings['diff_seat_capacity'][$get_key] ) > 0) {

                    $args = array( 
                        'state'         => false,
                        'seat_capacity' => $settings['diff_seat_capacity'][$get_key] ,
                        'multi_diff_start_time' => $settings['multi_diff_start_time'][$get_key] ,
                        'multi_diff_end_time'   => $settings['multi_diff_end_time'][$get_key] ,
                        'wpc_timezone'  => Utilities::time_zone(),
                        'wpc_time_format'  => ( !empty(get_option( 'time_format' )) || null !== get_option( 'time_format' ) ) ? get_option( 'time_format' ) : 'H:i',
												'selected_start_time'   => $selected_start_time,
                    );

                    $result_data             = Utilities::get_multi_status( $args  );
                    $multi_start_schedule    = $result_data['multi_start_schedule'];
                    $multi_end_schedule      = $result_data['multi_end_schedule'];
                    $capacity                = $result_data['capacity'];
                }
            }
           
        }else {
            if ( isset( $settings['rest_max_reservation'] ) && $settings['rest_max_reservation'] !=="" ) {
                $capacity     = $settings['rest_max_reservation'];
            }
        }
        $response['capacity']               = $capacity == 0 ? 100 : $capacity ;
        $response['multi_start_schedule']   = $multi_start_schedule;
        $response['multi_end_schedule']     = $multi_end_schedule;

        return $response;
    }

    /**
     * Return texonomy data
     */
    public static function get_all_cat_by_texonomy( $taxonomy ,$limit, $hide_empty ) {
        $args = array(
            'taxonomy'      => $taxonomy,
            'number'        => $limit,
            'hide_empty'    => $hide_empty,
        );

        return get_categories($args);
    }

    /**
     * Return Price with discount
     */
    public static function food_discount_price( $id , $auth_id = null ) {
        $new_price =  Utilities::discount_price( $id , 'food_price' , null , $auth_id );

        return $new_price;
    }

    /**
     * Return discount price function
     * @param array $args = [ $product_id, $data, $product_price = null, $auth_id=null ]
     */
    public static function discount_price( $args ){
        $defaults = array(
            'product_id'    => null,
            'data'          => '',
            'product_price' => null,
            'auth_id'       => null,
            'addons_price'  => 0,
        );
        extract( wp_parse_args( $args, $defaults ) );
        $wpc_pro_check_discount = Hook::instance()->check_discount_of_product( $product_id, null ,  $auth_id );

        // if discount found
        if (is_array($wpc_pro_check_discount) && $wpc_pro_check_discount['percentage'] !== '') {
            if (empty($product_price)) {
                $product = wc_get_product($product_id);
                // Get current price
                $get_price_tax  = Wpc_Utilities::menu_price_by_tax( $product );

                $main_price     = $get_price_tax ;
            } else {
                $main_price     =  $product_price;
            }
            $percentage             = $wpc_pro_check_discount['percentage'];

            $price_after_discount   = (float)($percentage / 100) * (float) $main_price;
            $new_price              = (float) $main_price - (float) $price_after_discount;

            $wpc_pro_menu_settings  	= \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option(); 
			$wpc_pro_addon_discount_to  = isset($wpc_pro_menu_settings['wpc_pro_addon_discount_to']) ? $wpc_pro_menu_settings['wpc_pro_addon_discount_to'] : '';
            // addons discount calculation
            $addons_new_price = $addons_price;
            if ( $addons_price > 0 && $wpc_pro_addon_discount_to == 'options_total' ) {
                $addons_price_discounted = (float)($percentage / 100) * (float) $addons_price;
                $addons_new_price        = (float) $addons_price - (float) $addons_price_discounted;
            }

            if ($data == 'wpc_pro_cart' || $data == 'wpc_pro_cart_sub_total') {
                // Set the new price
                return [
                    'new_price'           => $new_price,
                    'addons_new_price'    => $addons_new_price,
                    'discount_percentage' => $percentage,
                ];
            } else {
                $data = [];
                $data['main_price'] = $main_price ;
                $data['new_price']  = $new_price ;
                $data['price_afer_discount'] = wc_price( $new_price );
                $data['discount_percentage'] = $percentage;
                $data['addons_new_price']    = $addons_new_price;
                $data['discount_applied_on'] = $wpc_pro_addon_discount_to;

                return $data;
            }
        }
    }

    /**
     * Get checkout order time array in desired format
     */
    public static function get_order_type(){
        return array(
            esc_html__('Delivery type','wpcafe-pro') => 'wpc_pro_order_time',
            esc_html__('Delivery date','wpcafe-pro') => 'wpc_pro_delivery_date',
            esc_html__('Delivery time','wpcafe-pro') => 'wpc_pro_delivery_time',
            esc_html__('Pickup date','wpcafe-pro')   => 'wpc_pro_pickup_date',
            esc_html__('Pickup time','wpcafe-pro')   => 'wpc_pro_pickup_time'
        );
    }

    /**
     * Undocumented function
     *
     * @param integer $date
     * @return void
     */
    public static function preparing_date( $date = 0 ) {
        $datetime = new DateTime(date( WPCAFE_DEFAULT_DATE_FORMAT ));
        $datetime->modify('+'. $date .' day');

        return $datetime->format( WPCAFE_DEFAULT_DATE_FORMAT );
    }

    /**
     * Undocumented function
     *
     * @param [type] $wc_order_id
     * @return void
     */
    public static function wc_order_includes_reservation( $wc_order_id ){
        $reservation_data = get_post_meta( $wc_order_id, 'reservation_details', true );
        if( !empty( $reservation_data->reservation_id ) ){
            //reservation exists
            return true;
        }
        return false;
    }

    /**
     * check table layout option is enabled
     *
     * @return boolean
     */
    public static function is_table_layout_enabled() {
        $tools_settings      = get_option( 'wpcafe_tools_settings' );
        $enable_table_layout = ( isset( $tools_settings['enable_table_layout'] ) && $tools_settings['enable_table_layout'] == 'on' )  ? 'checked' : '';

        return $enable_table_layout;
    }

}
