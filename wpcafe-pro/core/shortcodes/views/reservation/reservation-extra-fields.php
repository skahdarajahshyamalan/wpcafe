<?php
$settings = get_option('wpcafe_reservation_settings_options'); ; 

if( isset($settings['reserv_extra_label']) && is_array($settings['reserv_extra_label']) && !empty($settings['reserv_extra_label']) ){
    $wpc_extra_field_type   = isset($settings['wpc_extra_field_type'])? $settings['wpc_extra_field_type'] : ['text'];
    $reserv_extra_label     = $settings['reserv_extra_label'];
    $reserv_extra_place_ho  = $settings['reserv_extra_place_ho'];
    $wpc_extra_field_option = !empty($settings['wpc_extra_field_option']) ? $settings['wpc_extra_field_option'] : [];
    foreach ( $reserv_extra_label as  $i => $extra_field_item ) {
		$field_required     = !empty($settings['wpc_extra_field_required'][$i]) ? $settings['wpc_extra_field_required'][$i] : "";
		$input_field_show   = $reserv_extra_label[$i] =='' ? "wpc-none" : "";
		$data_valid         = $wpc_extra_field_type[$i] == "checkbox" ? "data-validation=".$field_required : "";
		?>
		<div class='wpc-row <?php esc_attr_e( $input_field_show )?>'>
			<div class='wpc-col-md-12'>
				<div class='wpc-reservation-field'>
					<label id="wpc-reser-extra-<?php echo intval( $i );?>" for='reserv_extra_<?php echo intval( $i );?>' class='width_100'><?php esc_html_e( $reserv_extra_label[$i] ) ?></label>
					<div class="wpc-reser-extra-options" id="wpc_reser_extra_<?php echo intval( $i );?>>" <?php esc_attr_e($data_valid)?>>
						<?php if($wpc_extra_field_type[$i] == 'checkbox'){
							$next_add_time_checkbox_index = count($wpc_extra_field_option[$i]);
							if($next_add_time_checkbox_index >= 1){
								foreach($wpc_extra_field_option[$i] as $checkbox_index => $checkbox_val) {
									if(!empty($checkbox_val) && $checkbox_val != ''){
										?>
										<input type='checkbox' name='reserv_extra[]' data-row_id="<?php echo intval( $i );?>" id='wpc_extra_<?php echo intval( $i );?>_checkbox_<?php echo intval( $checkbox_index );?>' class='wpc-input-field-value' value='<?php esc_attr_e( $checkbox_val ) ?>' />
										<label for='wpc_extra_<?php echo intval( $i );?>_checkbox_<?php echo intval( $checkbox_index );?>'><?php esc_html_e( $checkbox_val ) ?></label>
										<?php
									}
								}
							}
							
						} else {
							?>
							<input type='text' name='reserv_extra[]' data-row_id="<?php echo intval( $i );?>" <?php esc_attr_e( $field_required );?> id='input_reserv_extra_<?php echo intval( $i );?>' class='wpc-form-control wpc-input-field-value' value='' placeholder='<?php esc_attr_e( $reserv_extra_place_ho[$i] ) ?>'/>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
    }
}
return;
?>