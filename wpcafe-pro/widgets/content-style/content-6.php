<?php
use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;
use WpCafe_Pro\Core\Shortcodes\Hook as Hook;
use WpCafe_Pro\Utils\Utilities;

?>
<div class="wpc-food-menu-item wpc-food-tab-style4">
    <?php if ($show_thumbnail == 'yes') : ?>
        <!-- thumbnail -->
            <div class="wpc-food-menu-thumb wpc-post-bg-img" style="background-image: url(<?php echo esc_url(get_the_post_thumbnail_url($product->get_id())); ?>);">
                <a href="<?php echo esc_url($permalink); ?>" class="wpc-img-link <?php echo esc_attr($class); ?>">
                </a>
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
                            <li> <?php echo esc_html($discount['percentage']); ?><?php echo esc_html__('%  Off', 'wpcafe-pro'); ?> </li>
                        </ul>
                    <?php
                    }
                    ?>
                </div>
                <span class="wpc-menu-currency"><span class="wpc-menu-price"><?php echo Wpc_Utilities::wpc_kses($price); ?></span></span>
            </div>
    <?php endif; ?>
    <div class="wpc-food-inner-content">
        <h3 class="wpc-post-title">
            <a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr($class); ?>"><?php echo Wpc_Utilities::wpc_render(get_the_title( $product->get_id() ));  ?> </a>

        </h3>
        <?php
        if ($wpc_delivery_time_show == 'yes') {
            Hook::instance()->food_time( $product->get_id() );
        }
        ?>

        <?php if ($wpc_show_desc == 'yes') { ?>
            <p>
                <?php echo  Wpc_Utilities::wpcafe_trim_words(get_the_excerpt( $product->get_id() ), $wpc_desc_limit); ?>
            </p>
        <?php  
        } 

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