<?php
$search = "";
if ($widget_arr) {
    $search = isset( $widget_arr['unique_id'] ) ? $widget_arr['unique_id'] : "";
}
?>
<div class="wpc-ajax-livesearch-wrap search_<?php echo esc_html($search); ?> <?php echo esc_attr($search_alignment); ?>" data-id="<?php echo esc_html($search); ?>">
    <div class="wpc-ajax-input-search">
        <div class="data_section data_value_<?php echo esc_html($search); ?>" data-total_product="<?php echo esc_html($no_of_product); ?>" data-cat_arr="<?php echo esc_html(json_encode($wpc_cat_arr)); ?>" data-cart_button="<?php echo esc_html($wpc_cart_button); ?>" data-template_name="<?php echo esc_html($template); ?>" data-template_path="<?php echo esc_html($template_path); ?>" 
        data-unique_id="<?php echo esc_html($search); ?>"
        data-widget_arr="<?php echo esc_html(json_encode($widget_arr)); ?>"></div>
        <input class="wpc-input-field live_food_menu_<?php echo esc_html($search); ?>" placeholder="<?php esc_html_e('Search here','wpcafe-pro')?>"/>
    </div>
    <div class="wpc-ajax-search-result">
        <div class="search_result_<?php echo esc_html($search); ?>"></div>
    </div>
</div>
<?php return; ?>