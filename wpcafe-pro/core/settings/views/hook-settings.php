<?php 
 use WpCafe\Utils\Wpc_Utilities;
?>

<!-- food with reservation form pro  -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Reservation Template', 'wpcafe-pro'); ?></h3>
                        <select  class="get_template wpc-setting-input">
                            <option value="wpc_reservation_with_food" selected> <?php echo esc_html__('Reservation Pro', 'wpcafe-pro'); ?> </option>
                        </select>
                    </div>
                </div>

                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Food Template', 'wpcafe-pro'); ?></h3>
                        <?php
                            $arr0 = [
                                "wpc_food_menu_list" => esc_html__('Food Menu List', 'wpcafe-pro'),
                                "wpc_food_menu_tab" => esc_html__('Food Menu Tab', 'wpcafe-pro'),
                            ];
                        ?>
                        <?php echo Wpc_Utilities::get_option_range( $arr0, '' );?>


                    </div>
                </div>
              
            </div>
            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select reservation template Style', 'wpcafe-pro'); ?></h3>
                        <?php
                            echo Wpc_Utilities::get_option_style( 2 ,'form_style','style-', 'Form Style ' );
                        ?>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php  echo esc_html__('Select food template Style', 'wpcafe-pro'); ?></h3>
                        <?php
                            echo Wpc_Utilities::get_option_style( 2 ,'style','style-', 'style ' );
                        ?>
                    </div>
                </div>
            </div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Order', 'wpcafe-pro'); ?></h3>
                        <?php Wpc_Utilities::get_order('wpc_menu_order');?>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Product Count', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('no_of_product') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>
            </div>
            <div class="wpc-row">
                <div class="wpc-col-lg-12">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'wpcafe-pro'); ?></h3>
                        <?php
                        echo Wpc_Utilities::get_wpc_taxonomy_ids('product_cat','wpc_food_categories');
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="wpc-row">
                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Description', 'wpcafe-pro'),'wpc_show_desc'); ?>

                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Description Limit', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('wpc_desc_limit') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>
            </div>

            <div class="wpc-row">
                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Enable title link?', 'wpcafe-pro'),'title_link_show'); ?>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show item status?', 'wpcafe-pro'),'show_item_status'); ?>

            </div>
            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Product Thumbnail', 'wpcafe-pro'),'product_thumbnail'); ?>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show cart button', 'wpcafe-pro'),'wpc_cart_button'); ?>

            </div>

            <?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_reservation_with_food]', 'wpc_reservation_with_food-shortcode'); ?>
        
        </div>
    </div>
   
    <div class="wpc-label-item">
        <div class="wpc-label">
            <label for="wpc_reservation_form"><?php esc_html_e('Food With Reservation (Pro)', 'wpcafe-pro'  ); ?></label>
            <?php
                $food_with_res_doc_link = Wpc_Utilities::wpc_kses( '<a href="https://support.themewinter.com/docs/plugins/wp-cafe/reservation-with-food-order-pro/" target="_blank" class="doc-link">documentation</a> ' );

            ?>
            <div class="wpc-desc"> <?php echo Wpc_Utilities::wpc_kses('Visit '. $food_with_res_doc_link .'for Reservation with food menu ', 'wpcafe-pro'); ?></div>
        </div>
        <div class="wpc-meta">
            <button type="button" class="s-generate-btn wpc-btn wpc-btn-border wpc-btn-secondary"><?php echo esc_html__('Generate Shortcode', 'wpcafe-pro'); ?></button>

        </div>
    </div>
</div>

<!-- Category List  -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'wpcafe-pro'); ?></h3>
                        <select  class="get_template wpc-setting-input">
                            <option value="wpc_pro_menu_category_list"> <?php echo esc_html__('Category List Template', 'wpcafe-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'wpcafe-pro'); ?></h3>
                        <?php  echo Wpc_Utilities::get_option_style( 4 ,'style','style-', 'style ' ); ?>
                    </div>
                </div>
            </div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'wpcafe-pro'); ?></h3>
                        <?php
                          echo Wpc_Utilities::get_wpc_taxonomy_ids('product_cat','wpc_food_categories');
                        ?>
                    </div>
                </div>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Hide empty', 'wpcafe-pro'),'hide_empty'); ?>

            </div>
            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                      <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Category Limit (its work when category not selected)', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('category_limit') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Count', 'wpcafe-pro'),'show_count'); ?>

            </div>

            <div class="wpc-row">
                <div class="wpc-col-lg-12">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Column', 'wpcafe-pro'); ?></h3>
                        <?php  echo Wpc_Utilities::get_option_style( 4 ,'grid_column','', 'Column ' ); ?>
                    </div>
                </div>
            </div>

            <?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_pro_menu_category_list]', 'wpc_pro_menu_category_list-shortcode'); ?>
        
        </div>
    </div>

    <?php Wpc_Utilities::generate_shortcode_button(esc_html__('Category List (Pro)', 'wpcafe-pro')); ?>
   
</div>

<!-- Location List  -->
<div class="shortcode-generator-wrap loc_list_short_code">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'wpcafe-pro'); ?></h3>
                        <select  class="get_template wpc-setting-input">
                            <option value="wpc_pro_menu_location_list"> <?php echo esc_html__('Location List Template', 'wpcafe-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap loc_list_style">
                        <h3><?php echo esc_html__('Select Style', 'wpcafe-pro'); ?></h3>
                        <?php  echo Wpc_Utilities::get_option_style( 5 ,'style','style-', 'style ' ); ?>
                    </div>
                </div>
            </div>
            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Location Limit (its work when Location not selected)', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('location_limit') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>
            </div>
            
            <div class="location_option_group">               
                <div class="wpc-row">
                    <div class="wpc-col-lg-6">
                        <div class="wpc-field-wrap">
                            <h3><?php echo esc_html__('Select Category', 'wpcafe-pro'); ?></h3>
                            <?php
                            echo Wpc_Utilities::get_wpc_taxonomy_ids('wpcafe_location', 'location_ids');
                            ?>
                        </div>
                    </div>

                    <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Hide Empty', 'wpcafe-pro'),'hide_empty'); ?>

                </div>
                <div class="wpc-row">                   

                    <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Count', 'wpcafe-pro'),'show_count'); ?>

                </div>
                <div class="wpc-row">
                    <div class="wpc-col-lg-12">
                        <div class="wpc-field-wrap">
                            <h3><?php echo esc_html__('Column', 'wpcafe-pro'); ?></h3>
                            <?php  echo Wpc_Utilities::get_option_style( 4 ,'grid_column',' ', 'Column ' ); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_pro_menu_location_list]', 'wpc_pro_menu_location_list-shortcode'); ?> 
        
        </div>
    </div>

    <?php Wpc_Utilities::generate_shortcode_button(esc_html__('Location List (Pro)', 'wpcafe-pro')); ?>
   
</div>


<!-- Food Menu -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'wpcafe-pro'); ?></h3>
                        <select  class="get_template wpc-setting-input">
                            <option value="wpc_pro_food_menu_list"> <?php echo esc_html__(' Food Menu List', 'wpcafe-pro'); ?> </option>
                            <option value="wpc_pro_food_menu_tab"> <?php echo esc_html__(' Food Menu Tab', 'wpcafe-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'wpcafe-pro'); ?></h3>
                        <?php  echo Wpc_Utilities::get_option_style( 4 ,'style','style-', 'Style ' ); ?>
                    </div>
                </div>
            </div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Order', 'wpcafe-pro'); ?></h3>
                        <?php Wpc_Utilities::get_order('wpc_menu_order');?>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Product Count', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('no_of_product') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>
            </div>
            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'wpcafe-pro'); ?></h3>
                        <?php
                        echo Wpc_Utilities::get_wpc_taxonomy_ids('product_cat','wpc_food_categories');
                        ?>
                    </div>
                </div>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Live Search', 'wpcafe-pro'), 'live_search'); ?>

            </div>
            
            <div class="wpc-row">
                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Description', 'wpcafe-pro'), 'wpc_show_desc'); ?>

                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Description Limit', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('wpc_desc_limit') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>
            </div>

            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Enable Title Link?', 'wpcafe-pro'), 'title_link_show'); ?>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Item Status?', 'wpcafe-pro'), 'show_item_status'); ?>

            </div>
            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Product Thumbnail', 'wpcafe-pro'), 'show_thumbnail'); ?>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Cart Button', 'wpcafe-pro'), 'wpc_cart_button'); ?>

            </div>

            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Delivery Time', 'wpcafe-pro'), 'wpc_delivery_time_show'); ?>

                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Nav Position (only for tab style) ', 'wpcafe-pro'); ?></h3>
                        <select class="wpc-setting-input">
                            <option value="wpc_nav_position='top'"><?php echo esc_html__('Top', 'wpcafe-pro') ?></option>
                            <option value="wpc_nav_position='left'"><?php echo esc_html__('Left', 'wpcafe-pro') ?></option>
                            <option value="wpc_nav_position='right'"><?php echo esc_html__('Right', 'wpcafe-pro') ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_pro_food_menu_list]', 'wpc_pro_food_menu_list-shortcode'); ?>            

        </div>
    </div>

    <?php Wpc_Utilities::generate_shortcode_button(esc_html__('Food Menu List and Tab(Pro)', 'wpcafe-pro')); ?>
   
</div>


<!-- Food Menu Slider-->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>
            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'wpcafe-pro'); ?></h3>
                        <select  class="get_template wpc-setting-input">
                            <option value="wpc_pro_menu_tab_with_slider"> <?php echo esc_html__(' Tab with Slider', 'wpcafe-pro'); ?> </option>
                            <option value="wpc_pro_menu_slider"> <?php echo esc_html__(' Menu Slider', 'wpcafe-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'wpcafe-pro'); ?></h3>
                        <?php  echo Wpc_Utilities::get_option_style( 3 ,'style','style-', 'Style ' ); ?>
                    </div>
                </div>
            </div>

            <div class="wpc-row">
                <div class="wpc-col-lg-4">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Order', 'wpcafe-pro'); ?></h3>
                        <?php Wpc_Utilities::get_order('wpc_menu_order');?>

                    </div>
                </div>
                <div class="wpc-col-lg-4">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Product Count', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('no_of_product') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>
                <div class="wpc-col-lg-4">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Slider Count', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('wpc_slider_count') ?>" class="post_count wpc-setting-input" value="4">
                    </div>
                </div>
            </div>
            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'wpcafe-pro'); ?></h3> 
                        <?php
                        echo Wpc_Utilities::get_wpc_taxonomy_ids('product_cat','wpc_food_categories');
                        ?>
                    </div>
                </div>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Auto Play', 'wpcafe-pro'), 'wpc_auto_play'); ?>

            </div>
            
            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Description', 'wpcafe-pro'), 'wpc_show_desc'); ?>

                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Description Limit', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('wpc_desc_limit') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>
            </div>

            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Enable Title Link?', 'wpcafe-pro'), 'title_link_show'); ?>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Item Status?', 'wpcafe-pro'), 'show_item_status'); ?>

            </div>
            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Product Thumbnail', 'wpcafe-pro'), 'show_thumbnail'); ?>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Cart Button', 'wpcafe-pro'), 'wpc_cart_button'); ?>

            </div>
            <div class="wpc-row">
                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Slider Nav', 'wpcafe-pro'), 'wpc_slider_nav_show'); ?>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Slider Dot Pagination', 'wpcafe-pro'), 'wpc_slider_dot_show'); ?>

            </div>

            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Delivery Time', 'wpcafe-pro'), 'wpc_delivery_time_show'); ?>

                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Nav Position(only for tab style) ', 'wpcafe-pro'); ?></h3>
                            <select class="wpc-setting-input">
                                <option value="wpc_nav_position='top'"><?php echo esc_html__('Top', 'wpcafe-pro') ?></option>
                                <option value="wpc_nav_position='left'"><?php echo esc_html__('Left', 'wpcafe-pro') ?></option>
                                <option value="wpc_nav_position='right'"><?php echo esc_html__('Right', 'wpcafe-pro') ?></option>
                            </select>
                    </div>
                </div>
            </div>

            <?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_pro_menu_slider]', 'wpc_pro_menu_slider-shortcode'); ?>            
            
        </div>
    </div>

    <?php Wpc_Utilities::generate_shortcode_button(esc_html__('Food Menu Slider(Pro)', 'wpcafe-pro')); ?>
   
</div>

<!-- Food Menu loadmore-->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'wpcafe-pro'); ?></h3>
                        <select  class="get_template wpc-setting-input">
                            <option value="wpc_pro_food_menu_loadmore"> <?php echo esc_html__(' Food Menu Loadmore', 'wpcafe-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'wpcafe-pro'); ?></h3>
                        <?php  echo Wpc_Utilities::get_option_style( 1 ,'style','style-', 'Style ' ); ?>
                    </div>
                </div>
            </div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Order', 'wpcafe-pro'); ?></h3>
                        <?php Wpc_Utilities::get_order('wpc_menu_order');?>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Product Count', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('no_of_product') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>
            </div>
            <div class="wpc-row">
                <div class="wpc-col-lg-12">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Category', 'wpcafe-pro'); ?></h3>
                        <?php
                        echo Wpc_Utilities::get_wpc_taxonomy_ids('product_cat','wpc_food_categories');
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Description', 'wpcafe-pro'), 'wpc_show_desc'); ?>

                <div class="wpc-col-lg-6">
                     <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Description Limit', 'wpcafe-pro'); ?></h3>
                        <input type="number" data-count ="<?php echo esc_attr('wpc_desc_limit') ?>" class="post_count wpc-setting-input" value="20">
                    </div>
                </div>
            </div>

            <div class="wpc-row">
                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Enable Title Link?', 'wpcafe-pro'), 'title_link_show'); ?>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Item Status?', 'wpcafe-pro'), 'show_item_status'); ?>

            </div>
            <div class="wpc-row">

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Product Thumbnail', 'wpcafe-pro'), 'show_thumbnail'); ?>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Show Cart Button', 'wpcafe-pro'), 'wpc_cart_button'); ?>

            </div>

            <div class="wpc-row">
                <div class="wpc-col-lg-12">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Show Delivery Time ', 'wpcafe-pro'); ?></h3>
                        <?php Wpc_Utilities::get_show_hide('wpc_delivery_time_show');?> 
                    </div>
                </div>
            </div>

            <?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_pro_food_menu_loadmore]', 'wpc_pro_food_menu_loadmore-shortcode'); ?>
        
            
        </div>
    </div>

    <?php Wpc_Utilities::generate_shortcode_button(esc_html__('Food Menu Loadmore(Pro)', 'wpcafe-pro')); ?>
   
</div>

<?php
return;


