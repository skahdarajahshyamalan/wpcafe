
<div class="wpc-product-addons-wrapper wpc-settings">
		<h1 class="wpc-settings-title"> <i class="wpcafe-icon4"></i> <?php echo esc_html__('Product addons', 'wpcafe-pro' ) ?></h1>
		<form method="post">
				<div class="addons-block wrap mb-30">
						<?php
						if ( class_exists( 'Woocommerce' ) ) {
								// all settings for product addons global portion.
								if( is_admin() ) {
									$addons_field = get_option( 'wpcafe_product_addons' );
								} else {
									$addons_field = get_option( 'wpcafe_product_addons_'. get_current_user_id() );

								}
								?>
							<div class="wpc-pao-items-wrapper">
								<!-- include category for addons  -->
								<div class='wpc-label-item'>
									<div class='wpc-label'>
										<label>
												<?php echo esc_html__('Include Categories', 'wpcafe-pro'); ?>
										</label>
										<p class='wpc-desc'><?php echo  esc_html__('Include all menu categories for product addons', 'wpcafe-pro'); ?></p>
									</div>
									<div class="wpc-meta wpc_pro_multi_cats">
											<?php
											$args = array(
													'hide_empty'  => 0,
													'taxonomy'    => 'product_cat',
													'hierarchical' => 1,
											);
											$categories = get_categories($args);
											?>
											<select multiple="multiple" class="wpc_pro_multi_cat wpc-settings-input" id="addons_categories" name="addons_categories[]">
													<?php
													if (is_array($categories)) {
															$addons_categories  = isset($addons_field['addons_categories']) ? $addons_field['addons_categories'] : [];
															foreach ($categories as $key => $category) {
																	$selected = in_array($category->term_id, $addons_categories) ? "selected" : '';
																	?>
																	<option <?php echo esc_html($selected); ?> value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->cat_name); ?></option>
																	<?php
															}
													}
													?>
											</select>
									</div>
								</div>

								<!-- include menu for addons -->
								<div class='wpc-label-item'>
										<div class='wpc-label'>
												<label><?php echo esc_html__('Include Menus', 'wpcafe-pro'); ?></label>
												<div class='wpc-desc'>
														<?php echo  esc_html__('Include following menu items for product addons', 'wpcafe-pro'); ?>
												</div>
										</div>
										<div class="wpc-meta wpc_pro_multi_products">
												<?php
												$menu_id  = isset($addons_field['addons_menu']) ? $addons_field['addons_menu'] : [];
												$args = array(
														'post_type'   => 'product',
														'hide_empty'  => 0,
														'limit'       => -1,
												);
												$products = wc_get_products($args);
												?>
												<select multiple="multiple" class="wpc_pro_multi_product wpc-settings-input" id="addons_menu" name="addons_menu[]">
														<?php
														if (is_array($products)) {
																foreach ($products as $product) {
																		if ($product->is_type('simple')) {
																				$selected = in_array($product->get_id(), $menu_id) ? "selected " : '';
																				?>
																				<option <?php echo esc_html($selected); ?> value='<?php echo intval($product->get_id()); ?>'><?php echo esc_html($product->get_name());  ?></option>
																				<?php
																		}
																}
														}
														?>
												</select>
										</div>
								</div>
							</div>
								<?php
								$addon_section = 'global';
								$product_paos  = \WpCafe_Pro\Core\Modules\Product_Addons\Admin\Hooks::instance()->process_addon_data( $addons_field );
								include_once \Wpcafe_Pro::core_dir() . "modules/product-addons/admin/templates/fields-area.php";
								?>
						<?php
						}
						?>
				</div>
				<?php
					if( !is_admin() ){
					?>
						<input type="hidden" name="global_addons_vendor_id" value="<?php echo get_current_user_id(); ?>"/>
					<?php
					}
					wp_nonce_field('wpcafe-product-addons', 'wpcafe-product-addons');
				?>
				<input type="hidden" name="wpcafe_product_addons" value="product_addons_save">
				<input type="submit" name="submit"  class="wpc_mt_two wpc-btn wpc_global_addons_save" value="<?php esc_attr_e('Save Changes', 'wpcafe-pro'); ?>">
		</form>
</div>