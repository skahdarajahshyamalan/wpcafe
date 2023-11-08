<?php
use WpCafe\Utils\Wpc_Utilities;
use WpCafe_Pro\Traits\Singleton;

 if ( $live_search == 'yes' ) {
    // live search 
    $template_path = \Wpcafe_Pro::plugin_dir() . "/widgets/food-menu-tab/style/{$style}.php";
    $widget_arr=array(
        'show_thumbnail' 		=> $show_thumbnail,
        'title_link_show' 		=> $title_link_show,
        'wpc_menu_order' 		=> $wpc_menu_order,
        'show_item_status' 		=> $show_item_status,
        'wpc_delivery_time_show'=> $wpc_delivery_time_show,
        'wpc_show_desc'			=> $wpc_show_desc,
        'wpc_desc_limit'		=> $wpc_desc_limit,
        'wpc_btn_text'			=> $wpc_btn_text,
        'customize_btn'			=> $customize_btn,
        'unique_id'				=> $unique_id,
        'wpc_menu_col'			=> $wpc_menu_col,
        'wpc_nav_position'		=> $wpc_nav_position,
    );

     $live_search_args = array(
         'no_of_product' => $no_of_product,
         'wpc_cat_arr' => $wpc_cat_arr,
         'wpc_cart_button' => $wpc_cart_button,
         'template' => 'tab_template',
         'template_path' => $template_path,
         'widget_arr' => $widget_arr
     );
    echo \WpCafe_Pro\Core\Template\Food_Menu::instance()->live_search_markup( $live_search_args );
    } 
?>
    <div class="wpc-food-tab-wrapper wpc-nav-shortcode wpc-food-menu-wrapper  main_wrapper_<?php echo esc_html($unique_id)?> nav-position-<?php echo esc_attr($wpc_nav_position); ?>" data-id="<?php echo esc_html($unique_id)?>" >
        <ul class="wpc-nav">
            <?php
            foreach ($wpc_cat_sort_arr as $key => $value) {
                if ($wpc_cat = get_term_by('id', $value, 'product_cat')) {
                    $active_class = (($key == array_keys($wpc_cat_sort_arr)[0]) ? 'wpc-active' : ' ');
            ?>
                    <li>
                        <a href="#" data-id="tab_<?php echo esc_attr($key); ?>" data-cat_id="<?php echo intval($value) ?>" class="wpc-tab-a <?php echo esc_attr($active_class); ?>" data-toggle="tab" aria-expanded="true">
                            <span><?php echo esc_attr_e($wpc_cat->name, 'wpcafe-pro'); ?></span>
                        </a>
                    </li>
            <?php
                }
            }
            ?>
        </ul>
        <div class="wpc-tab-content wpc-widget-wrapper">
            <?php
            foreach ($wpc_cat_sort_arr as $current_key => $value) {
                $active_class = (($current_key == array_keys($wpc_cat_sort_arr)[0]) ? 'tab-active' : ' ');
                ?>
                <div class='wpc-tab <?php echo esc_attr($active_class); ?>' data-cat_id="<?php echo intval($value) ?>" data-id='tab_<?php echo esc_attr($current_key); ?>'>
                    <div class="tab_template_<?php echo esc_attr( $value.'_'.$unique_id ); ?>"></div>
                    <div class="template_data_<?php echo esc_attr( $value.'_'.$unique_id ); ?>">
                        <?php
                            $products_args = array(
                                'post_type'     => 'product',
                                'no_of_product' => $no_of_product,
                                'wpc_cat'       => [$value],
                                'order'         => 'DESC'
                            );
                            $products = Wpc_Utilities::product_query( $products_args );
                            include \Wpcafe_Pro::plugin_dir() . "/widgets/food-menu-tab/style/{$style}.php";
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
return;