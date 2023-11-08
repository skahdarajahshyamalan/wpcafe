<?php
use WpCafe_Pro\Utils\Utilities;

$taxonomy    = 'product_cat';
if (is_array($categories_id) && isset($categories_id[0]) && $categories_id[0] !=="" && count( $categories_id )>0 ) {
    $cats = $categories_id;
} else {
    $cats = Utilities::get_all_cat_by_texonomy( $taxonomy , $category_limit , $hide_empty );
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
        $style_name= "style-1";
        $render    = "style3";
        break;
    case 'style-4': 
        $style_name= "style-2";
        $render    = "style4";
        break;
    default:
        $style_name = "style-1";
        $render     = "style1";
}
?>
<div class="wpc-menu-category-wrap wpc-category-list-<?php echo esc_attr( $render );?>">
    <?php include \Wpcafe_Pro::plugin_dir() . "/widgets/category-list/style/".esc_attr( $style_name ).".php"; ?>
</div>
<?php

return;