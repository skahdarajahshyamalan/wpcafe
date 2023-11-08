<?php
defined('ABSPATH') || exit;

use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;
use WpCafe_Pro\Core\Shortcodes\Hook as Hook;
use WpCafe_Pro\Utils\Utilities;

$col = ($show_thumbnail == 'yes') ? 'wpc-col-md-9' : 'wpc-col-md-12';
$class = (($title_link_show == 'yes') ? '' : 'wpc-no-link');
$cafe_settings      =  \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
$cart_icon          = !empty($cafe_settings['wpc_cart_icon']) ? $cafe_settings['wpc_cart_icon'] : 'wpcafe-cart_icon';
$customization_icon = !empty($cafe_settings['wpc_customization_icon']) ? $cafe_settings['wpc_customization_icon'] : 'wpcafe-customize';

if ( is_array( $products ) && count( $products )>0 ) : ?>
    <div class="wpc-food-block-tab-item wpc-tab-grid">
        <div class="wpc-row">
            <?php foreach ($products as $product) {
                $get_price      = Utilities::food_discount_price( $product->get_id() );
                $price          = !is_null( $get_price ) ? '<del>'. wc_price( $get_price['main_price'] ) .'</del>' . $get_price['price_afer_discount'] :  $product->get_price_html(); // true for getting tax price 
                $current_tags   = get_the_terms( $product->get_id() , 'product_tag');
                $permalink      = (($title_link_show == 'yes') ? get_the_permalink( $product->get_id() ) : '');
                $discount       = Hook::instance()->check_discount_of_product( $product->get_id() );
                ?>
                <div class="wpc-col-lg-<?php echo esc_attr($wpc_menu_col); ?> wpc-col-md-6">
                <?php include \Wpcafe_Pro::plugin_dir() . "/widgets/content-style/content-2.php"; ?>

                </div>
            <?php } ?>
        </div>
    </div><!-- block-item6 -->
<?php endif; ?>
