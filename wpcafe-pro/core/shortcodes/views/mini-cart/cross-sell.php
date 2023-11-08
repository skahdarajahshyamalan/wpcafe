<?php 
use \WpCafe\Core\Shortcodes\Template_Functions as Wpc_Widget_Template;

 $cross_sells = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );
   $uniq_id = md5(md5(microtime()));
   if ( $cross_sells ) : ?>
        <div class="wpc-cross-sell-slider main_wrapper_<?php echo esc_attr($uniq_id); ?>" data-id="<?php echo esc_attr($uniq_id); ?>">
            <div class="wpc-cross-sells" data-auto_play="yes" data-count="1"> 
                <div class="title-wrap">
                    <svg width="17" height="22" viewBox="0 0 17 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.36842 12.2799H5.45842V19.4799C5.45842 21.1599 6.36842 21.4999 7.47842 20.2399L15.0484 11.6399C15.9784 10.5899 15.5884 9.7199 14.1784 9.7199H11.0884V2.5199C11.0884 0.839898 10.1784 0.499897 9.06842 1.7599L1.49842 10.3599C0.578422 11.4199 0.968423 12.2799 2.36842 12.2799Z" stroke="#E7272D" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h4><?php echo esc_html__('Frequently bought togather', 'wpcafe-pro' ); ?></h4>
                </div>
                <div class="swiper-wrapper">
                    <?php foreach ( $cross_sells as $cross_sell ) : ?>
                        <div class="wpc-cross-sell-item swiper-slide">
                            <?php 
                                $cross = wc_get_product(  $cross_sell->get_id());
                                $food_menu_list_args = array(
                                    'show_thumbnail'    => 'yes',
                                    'wpc_price_show'    => 'yes',
                                    'permalink'         => get_permalink($cross->get_id()),
                                    'wpc_cart_button'   => 'yes',
                                    'unique_id'         =>  $uniq_id,
                                    'product'           => $cross,
                                    'class'             => '',
                                    'show_item_status'  => 'no',
                                    'wpc_show_desc'     => 'no',
                                    'col'               => 'wpc-col-md-10',
                                    'wpc_desc_limit'    => '0'
                                );
                                Wpc_Widget_Template::wpc_food_menu_list_template( $food_menu_list_args );
                            ?>
                        </div>
                    <?php endforeach; ?> 
                </div>
              
            </div>
            <div class="navigation-wrapper">
                <div class="swiper-btn-prev">
                    <svg width="8" height="16" viewBox="0 0 8 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 1.60937L1.4882 7.12117C0.837267 7.7721 0.837267 8.83727 1.4882 9.4882L7 15" stroke="#292D32" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="swiper-btn-next">
                    <svg width="8" height="16" viewBox="0 0 8 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.999999 1.60937L6.5118 7.12117C7.16273 7.7721 7.16273 8.83727 6.5118 9.4882L1 15" stroke="#292D32" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
       </div>
       <?php
   endif;
   wp_reset_postdata();
?>