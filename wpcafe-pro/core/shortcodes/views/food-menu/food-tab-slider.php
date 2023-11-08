<?php

use WpCafe\Utils\Wpc_Utilities;

$wpc_pro_standarad_off   = isset($settings['wpc_pro_discount_standarad_off_message'])  ? sanitize_text_field($settings['wpc_pro_discount_standarad_off_message']) : '';
if ($wpc_pro_standarad_off !== '') {
?>
    <div class="wpc_pro_standard_offer_message"><?php echo esc_html($wpc_pro_standarad_off); ?></div>
<?php
}

$style = (isset($style) && $style != '') ? $style : 'style-1';
$unique_id = md5(md5(microtime()));
$wpc_cat_arr      = explode(',', $wpc_food_categories);

$wpc_cat_sort_arr = [];
$i                = 0;
if (is_array($wpc_cat_arr)) {
    foreach ($wpc_cat_arr as $value) {
        $i++;

        if ($wpc_cat = get_term_by('id', $value, 'product_cat')) {
            $wpc_get_menu_order = get_term_meta($wpc_cat->term_id, 'wpc_menu_order_priority', true);
            if ($wpc_get_menu_order == '') {
                $wpc_cat_sort_arr[$i] = $value;
            } else {
                $wpc_cat_sort_arr[$wpc_get_menu_order] = $value;
            }
        }
    }
}
ksort($wpc_cat_sort_arr);
?>
<div class="wpc-nav-shortcode wpc-menu-tab-slider-shortcode main_wrapper_<?php echo esc_attr($unique_id); ?>" 
 data-id="<?php echo esc_attr($unique_id); ?>">
    <div class="wpc-food-tab-wrapper wpc-food-menu-wrapper">
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
        <div class='wpc-tab-content  wpc-widget-wrapper'>
            <?php
            foreach ($wpc_cat_sort_arr as $current_key => $value) {
                $active_class = (($current_key == array_keys($wpc_cat_sort_arr)[0]) ? 'tab-active' : ' ');
            ?>
                <div class='wpc-tab <?php echo esc_attr($active_class); ?>' data-cat_id="<?php echo intval($value) ?>" data-id='tab_<?php echo esc_attr($current_key); ?>'>
                    <div class="tab_template_<?php echo intval($value); ?>"></div>
                    <div class="template_data_<?php echo intval($value); ?>">
                        <?php
                            $products_args = array(
                                'post_type'     => 'product',
                                'no_of_product' => $no_of_product,
                                'wpc_cat'       => [$value],
                                'order'         => 'DESC'
                            );
                            $products = Wpc_Utilities::product_query( $products_args );
                            include \Wpcafe_Pro::plugin_dir() . "/widgets/menu-tab-with-slider/style/{$style}.php";
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
return;