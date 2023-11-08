<?php
use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;
use WpCafe_Pro\Core\Shortcodes\Hook as Hook;
use WpCafe_Pro\Utils\Utilities;

?>
<div class="wpc-food-menu-item content-style-5">
    <div class="wpc-row">
        <div class="<?php echo esc_attr($col); ?> wpc-align-self-center">
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
                    // show discount 
                    if (is_array($discount) > 0 && $discount['percentage'] != '') { ?>
                        <ul class="wpc-menu-tag wpc-discount-offer">
                            <li> <?php echo esc_html($discount['percentage']); ?><?php echo esc_html__('%  Off', 'wpcafe-pro'); ?> </li>
                        </ul>
                    <?php
                    }
                    ?>
                </div>
                <h3 class="wpc-post-title">
                    <a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr($class); ?>"><?php echo Wpc_Utilities::wpc_render(get_the_title( $product->get_id() ));  ?> </a>
                </h3>
                <?php
                if ($wpc_delivery_time_show == 'yes') {
                    Hook::instance()->food_time( $product->get_id() );
                }
                if ($wpc_show_desc == 'yes') { ?>
                    <p>
                        <?php echo  Wpc_Utilities::wpcafe_trim_words(get_the_excerpt( $product->get_id() ), $wpc_desc_limit); ?>
                    </p>
                <?php  } ?>

                <div class="wpc-menu-footer-wrap">
                <?php
                    if ( $wpc_cart_button == 'yes' ) {
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
                    } 
                    ?>
                    <span class="wpc-menu-currency"><span class="wpc-menu-price"><?php echo Wpc_Utilities::wpc_kses($price); ?></span>

                </div>
            </div>
        </div>
        <?php if ($show_thumbnail == 'yes') :  ?>
            <div class="wpc-col-md-4">
                <!-- thumbnail -->
                <?php if ( $product->get_image() ) { ?>
                    <div class="wpc-food-menu-thumb">
                        <a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr($class); ?>">
                            <?php  echo Wpc_Utilities::wpc_kses( $product->get_image() )?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
    </div>
</div>