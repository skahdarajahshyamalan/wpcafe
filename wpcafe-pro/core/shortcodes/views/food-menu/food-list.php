<?php

use WpCafe\Utils\Wpc_Utilities;
use WpCafe_Pro\Traits\Singleton;

if ($live_search == 'yes') {
        $template_path = \Wpcafe_Pro::plugin_dir() . "/widgets/menus/style/$style.php";
        // live search 
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
        );

        $live_search_args = array(
            'no_of_product' => $no_of_product,
            'wpc_cat_arr' => $wpc_cat_arr,
            'wpc_cart_button' => $wpc_cart_button,
            'template' => 'list_template',
            'template_path' => $template_path,
            'widget_arr' => $widget_arr,
        );

        echo \WpCafe_Pro\Core\Template\Food_Menu::instance()->live_search_markup( $live_search_args );
    }

    $no_desc_class = ($wpc_show_desc != 'yes') ? 'wpc-no-desc' : '';
?>

<div class="wpc-nav-shortcode main_wrapper_<?php echo esc_attr($unique_id.' '. $no_desc_class)?>" data-id="<?php echo esc_attr($unique_id)?>">
    <div class="list_template_<?php echo esc_attr( $unique_id ) ;?> ">
        <?php
        if (is_array($wpc_cat_arr) && count($wpc_cat_arr) > 0) {
            $food_list_args = array(
                'post_type'     => 'product',
                'no_of_product' => $no_of_product,
                'wpc_cat'       => $wpc_cat_arr,
                'order'         => $wpc_menu_order,
            );
            $products = Wpc_Utilities::product_query( $food_list_args );
            include \Wpcafe_Pro::plugin_dir() . "widgets/menus/style/{$style}.php";
        }
        ?>
    </div>
</div>
<?php
return;