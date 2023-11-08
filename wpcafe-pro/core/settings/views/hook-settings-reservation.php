<?php 
 use WpCafe\Utils\Wpc_Utilities;
?>

<!-- reservation form pro  -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'wpcafe-pro'); ?></h3>
                        <select  class="get_template wpc-setting-input">
                            <option value="wpc_reservation_form_pro"> <?php echo esc_html__(' Reservation Form Pro', 'wpcafe-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'wpcafe-pro'); ?></h3>
                        <?php
                            echo Wpc_Utilities::get_option_style( 2 , 'form_style','', 'Style ' );
                        ?>
                    </div>
                </div>
            </div>

            <?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_reservation_form_pro]', 'wpc_reservation_form_pro-shortcode'); ?>

        </div>
    </div>

    <?php Wpc_Utilities::generate_shortcode_button(esc_html__('Reservation Form (Pro)', 'wpcafe-pro')); ?>

</div>

<!-- visual table reservation form pro  -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'wpcafe-pro'); ?></h3>
                        <select class="get_template wpc-setting-input">
                            <option value="wpc_visual_reservation_form"> <?php echo esc_html__('Visual Reservation', 'wpcafe-pro'); ?> </option>
                        </select>
                    </div>
                </div>
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Style', 'wpcafe-pro'); ?></h3>
                        <?php
                            echo Wpc_Utilities::get_option_style( 1, 'form_style','', 'Style ' );
                        ?>
                    </div>
                </div>
            </div>

            <?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_visual_reservation_form]', 'wpc_visual_reservation_form-shortcode'); ?>            

        </div>
    </div>

    <?php Wpc_Utilities::generate_shortcode_button(esc_html__('Reservation Form with Visual Table/Seat Selection (Pro)', 'wpcafe-pro')); ?>

</div>

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
            <button type="button" class="wpc-btn wpc-btn-border wpc-btn-secondary s-generate-btn"><?php echo esc_html__('Generate Shortcode', 'wpcafe-pro'); ?></button>

        </div>
    </div>
</div>



<!-- Business hours  -->
<div class="shortcode-generator-wrap">
    <div class="shortcode-generator-main-wrap">
        <div class="shortcode-generator-inner">
            <div class="shortcode-popup-close">x</div>

            <div class="wpc-row">
                <div class="wpc-col-lg-6">
                    <div class="wpc-field-wrap">
                        <h3><?php echo esc_html__('Select Template', 'wpcafe-pro'); ?></h3>
                        <select  class="get_template wpc-setting-input">
                            <option value="wpc_pro_business_hour"> <?php echo esc_html__(' Business Hours', 'wpcafe-pro'); ?> </option>
                        </select>
                    </div>
                </div>

                <?php Wpc_Utilities::generate_shortcode_show_hide(esc_html__('Select All days schedule', 'wpcafe-pro'),'all_days_schedule'); ?>

            </div>

            <?php Wpc_Utilities::generate_shortcode_button_popup('[wpc_pro_business_hour]', 'wpc_pro_business_hour-shortcode'); ?>
        
        </div>
    </div>

    <?php Wpc_Utilities::generate_shortcode_button( esc_html__('Business Hours (Pro)', 'wpcafe-pro'),
    esc_html__('Time slot will be generate from reservation schedule and Title from 
    Reservation Schedule=>"Business Hour Label" ', 'wpcafe-pro') ); ?>
   
</div>

<?php
return;


