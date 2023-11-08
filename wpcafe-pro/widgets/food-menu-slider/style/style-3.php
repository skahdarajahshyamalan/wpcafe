<?php

use WpCafe_Pro\Core\Shortcodes\Hook as Hook;
use WpCafe_Pro\Utils\Utilities;

$class = (($title_link_show == 'yes') ? '' : 'wpc-no-link');
$cafe_settings      =  \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
$cart_icon          = !empty($cafe_settings['wpc_cart_icon']) ? $cafe_settings['wpc_cart_icon'] : 'wpcafe-cart_icon';
$customization_icon = !empty($cafe_settings['wpc_customization_icon']) ? $cafe_settings['wpc_customization_icon'] : 'wpcafe-customize';
?>

<div class="wpc-nav-shortcode wpc-food-menu-slider wpc-widget-wrapper main_wrapper_<?php echo esc_attr($unique_id); ?>"
data-auto_play="<?php echo esc_html( $wpc_auto_play ); ?>"
data-id="<?php echo esc_attr($unique_id); ?>" data-count="<?php echo esc_attr($wpc_slider_count); ?>">
    <div class="swiper-wrapper">
        <?php
        if ( is_array( $products ) && count( $products )>0 ) { 
            foreach ($products as $product) {
                $preparing_time = get_post_meta( $product->get_id() , 'preparing_time', true ) ;
                $delivery_time  = get_post_meta( $product->get_id() , 'delivery_time', true ) ;
                
                $get_price  = Utilities::food_discount_price( $product->get_id() );
                $price      = !is_null( $get_price ) ? '<del>'. wc_price( $get_price['main_price'] ) .'</del>' . $get_price['price_afer_discount'] : $product->get_price_html(); // true for getting tax price                 $current_tags = get_the_terms( $product->get_id() , 'product_tag');
                $permalink  = (($title_link_show == 'yes') ? get_the_permalink( $product->get_id() ) : '');
                $discount   = Hook::instance()->check_discount_of_product( $product->get_id() );
                $current_tags = get_the_terms( $product->get_id() , 'product_tag');

                ?>
                <div class="swiper-slide wpc-slider-grid-3">
                    <?php include \Wpcafe_Pro::plugin_dir() . "/widgets/content-style/content-6.php"; ?>
                </div>
        <?php
            }
        }
        ?>
    </div>
    <?php if ($wpc_slider_nav_show == 'yes') : ?>
        <!-- next / prev arrows -->
        <div class="swiper-button-next"> <i class="wpcafe-next"></i> </div>
        <div class="swiper-button-prev"> <i class="wpcafe-previous"></i> </div>
        <!-- !next / prev arrows -->
    <?php endif; ?>
    <?php if ($wpc_slider_dot_show == 'yes') : ?>
        <!-- pagination dots -->
        <div class="swiper-pagination"></div>
        <!-- !pagination dots -->
    <?php endif; ?>
</div>