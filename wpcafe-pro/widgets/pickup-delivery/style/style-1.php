<?php
use WpCafe\Utils\Wpc_Utilities;
use WpCafe_Pro\Utils\Utilities as Pro_Utilities;
?>
<div class="search-main-wrapper">
    <?php if(! empty($products)) {?>
        <div class="row">
            <?php foreach ($products as $product) { ?>
            <?php $product_id = $product->get_id(); ?>
                <div class="wpc-col-lg-4 wpc-col-md-4">  
                    <div class="food-item-wrapper wpc-food-menu-item ">
                        <?php if ($product->get_image()) { ?>
                            <div class="wpc-food-menu-thumb">
                                <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                                    <?php echo Wpc_Utilities::wpc_kses($product->get_image()); ?>
                                </a>
                            </div>                           
                        <?php }  ?>
                        <div class="wpc-food-inner-content">
                            <!-- product tag and tax -->
                            <div class="wpc-menu-tag-wrap">
                                <?php
                                if ($product->get_price_suffix() != '') { ?>
                                    <ul class="wpc-menu-tag">
                                        <li>
                                            <?php if (wc_get_price_including_tax($product)) {
                                                // get percentage tax
                                                echo Wpc_Utilities::wpc_kses($product->get_price_suffix());
                                            } ?>
                                        </li>
                                    </ul>
                                    <?php
                                } ?>
                            </div>
        
                            <h3 class="wpc-post-title wpc-title-with-border">
                                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>"> <?php echo Wpc_Utilities::wpc_kses($product->get_name());  ?> </a>
                                    <span class="wpc-title-border"></span>
                                    <?php
                                    if( $product->get_type() !== 'variable') {
                                        ?>
                                        <span class="wpc-menu-price"><?php echo Wpc_Utilities::wpc_kses( $product->get_price_html() ); ?></span></span>
                                        <?php
                                    } else {
                                            
                                        // variation price.
                                        $variation_price = $product->get_variation_prices( true ); // true for getting tax price
                                        $var_price = '';
                                        if( is_array( $variation_price ) && isset( $variation_price['price'] ) ){
                                                
                                            $var_price .= "<span class='min_price'>". get_woocommerce_currency_symbol() . array_shift($variation_price['price']) . "</span>";                              
                                                
                                            $var_price .= " - ";
                                            $var_price .= "<span class='max_price'>". get_woocommerce_currency_symbol() . array_pop($variation_price['price']) . "</span>";
                                        }
        
                                        ?>
                                        <span class="wpc-menu-currency"><span class="wpc-menu-price"><?php echo Wpc_Utilities::wpc_kses($var_price); ?></span></span>
                                        <?php
                                            
                                    }
                                    ?>
                            </h3>
                        </div>
                    </div>                          
                </div>
                <div class="wpc_loader_wrapper">
                    <div class="loder-dot dot-a"></div>
                    <div class="loder-dot dot-b"></div>
                    <div class="loder-dot dot-c"></div>
                    <div class="loder-dot dot-d"></div>
                    <div class="loder-dot dot-e"></div>
                    <div class="loder-dot dot-f"></div>
                    <div class="loder-dot dot-g"></div>
                    <div class="loder-dot dot-h"></div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

</div>
    



