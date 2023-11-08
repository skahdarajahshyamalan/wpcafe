<?php

use WpCafe_Pro\Utils\Utilities;

$taxonomy = 'wpcafe_location';
if ( is_array($categories_id) && isset($categories_id[0]) && $categories_id[0] !=="" && count( $categories_id )>0 ) {
    $cats = $categories_id;
} else {
    $cats = Utilities::get_all_cat_by_texonomy( $taxonomy, $location_limit , $hide_empty );
}

$render = ""; $style_name = "";
switch ($style) {
    case 'style-1': 
        $style_name = "style-1";
        $render     = "style1";
        break;
    case 'style-2': 
        $style_name = "style-1";
        $render     = "style2";

        break;
    case 'style-3': 
        $style_name = "style-1";
        $render     = "style3";
        break;
    case 'style-4': 
        $style_name = "style-2";
        $render     = "style4";
        break;
    case 'style-5': 
        $style_name = "style-5";
        $render     = "style5";
        break;
    default:
        $style_name = "style-1";
        $render     = "style1";
}


?>
<div class="wpc-menu-location-wrap wpc-location-list-<?php echo esc_attr( $render );?>">
    <?php 
    	$location = isset( $_GET['location'] ) ? absint( $_GET['location'] ): 0;

        if ( empty( $location ) ) {
            include \Wpcafe_Pro::plugin_dir() . "/widgets/menu-location-list/style/".esc_attr( $style_name ).".php"; 
        } else {
            $show_thumbnail         = 'yes';
            $show_item_status       = 'yes';
            $wpc_cart_button        = 'yes';
            $wpc_price_show         = 'yes';
            $wpc_show_desc          = 'yes';
            $wpc_delivery_time_show = 'yes';
            $wpc_desc_limit         = 20;
            $unique_id              = md5(md5(microtime()));
            $col                    = 'wpc-col-md-8';
            $title_link_show        = 'yes';
            $get_location           = $location =="" ? [] : [$location];

            $args = array(
                'order'         => 'DESC',
                'wpc_cat'       => $get_location,
                'taxonomy'      => 'wpcafe_location',
            );
            $products = \WpCafe\Utils\Wpc_Utilities::product_query ( $args );
            
            $style = 'style-1';
            include_once \WpCafe::plugin_dir() . "core/shortcodes/views/food-menu/location-menu.php";
        }
        
    ?>
</div>
<?php

return;