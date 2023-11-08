<?php
use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;
use WpCafe_Pro\Core\Shortcodes\Hook as Hook;
use WpCafe_Pro\Utils\Utilities;

$col = ($show_thumbnail == 'yes') ? 'wpc-col-md-9' : 'wpc-col-md-12';
$class = (($title_link_show == 'yes') ? '' : 'wpc-no-link');
$cafe_settings      =  \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
$cart_icon          = !empty($cafe_settings['wpc_cart_icon']) ? $cafe_settings['wpc_cart_icon'] : 'wpcafe-cart_icon';
$customization_icon = !empty($cafe_settings['wpc_customization_icon']) ? $cafe_settings['wpc_customization_icon'] : 'wpcafe-customize';

if ( is_array( $products ) && !empty( $products ) ){
    foreach ($products as $product) {
        $get_price      = Utilities::food_discount_price( $product->get_id() );
        $price          = !is_null( $get_price ) ? '<del>'. wc_price( $get_price['main_price'] ) .'</del>' . $get_price['price_afer_discount'] : $product->get_price_html(); // true for getting tax price         $current_tags = get_the_terms( $product->get_id() , 'product_tag');
        $permalink      = (($title_link_show == 'yes') ? get_the_permalink( $product->get_id() )  : '');
        $discount       = Hook::instance()->check_discount_of_product( $product->get_id() );
        $current_tags   = get_the_terms( $product->get_id() , 'product_tag');
        ?>
        <div class="wpc-food-menu-item wpc-row">
            <?php if ($show_thumbnail == 'yes') : ?>
                <div class="wpc-col-md-3">
                    <!-- thumbnail -->
                    <?php if ( $product->get_image() ) { ?>
                        <div class="wpc-food-menu-thumb">
                            <a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr($class); ?>">
                                <?php echo Wpc_Utilities::wpc_kses( $product->get_image() ); ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php endif; ?>
            <div class="<?php echo esc_attr($col); ?>">
                <div class="wpc-food-inner-content">
                    <div class="wpc-menu-tag-wrap">
                        <?php
                        //only start if we have some tags
                        if ($show_item_status == 'yes' && $current_tags && !is_wp_error($current_tags)) { ?>
                            <!-- create a list to hold our tags -->
                            <ul class="wpc-menu-tag">
                                <!-- for each tag we create a list item -->
                                <?php
                                foreach ($current_tags as $tag) {
                                    $tag_title = $tag->name; // tag name
                                ?>
                                    <li> <?php echo esc_html($tag_title); ?> </li>

                                <?php
                                } ?>

                            </ul>
                        <?php
                        }
                        ?>
                        <?php
                        // show discount 
                        if (is_array($discount) > 0 && $discount['percentage'] != '') { ?>
                            <ul class="wpc-menu-tag wpc-discount-offer">
                                <li> <?php echo esc_html($discount['percentage']); ?><?php echo esc_html__('% Off', 'wpcafe-pro'); ?> </li>
                            </ul>
                        <?php
                        }
                        ?>
                    </div>
                    <h3 class="wpc-post-title wpc-title-with-border">
                        <a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr($class); ?>"><?php echo Wpc_Utilities::wpc_render(get_the_title( $product->get_id() ));  ?> </a>
                        <span class="wpc-title-border"></span>
                        <span class="wpc-menu-currency"><span class="wpc-menu-price"><?php echo Wpc_Utilities::wpc_kses($price); ?></span></span>

                    </h3>
                    <?php 
                    if( $wpc_delivery_time_show == 'yes'){
                        Hook::instance()->food_time( $product->get_id() );
                        }
                      ?>
                    <?php if ($wpc_show_desc == 'yes') { ?>
                        <p>
                            <?php echo  Wpc_Utilities::wpcafe_trim_words(get_the_excerpt( $product->get_id() ), $wpc_desc_limit); ?>
                        </p>
                    <?php  } ?>
                    <?php
                    // show cart button
                    $add_cart_args = array(
                        'product'       => $product,
                        'cart_button'   => $wpc_cart_button,
                        'wpc_btn_text'  => $wpc_btn_text,
                        'customize_btn' => $customize_btn,
                        'widget_id'     => $unique_id,
                        'cart_icon'     => $cart_icon,
                        'customization_icon' => $customization_icon,
                    );
                    echo  Wpc_Utilities::product_add_to_cart( $add_cart_args );
                    ?>
                </div>
            </div>
        </div>
<?php
    }
}
