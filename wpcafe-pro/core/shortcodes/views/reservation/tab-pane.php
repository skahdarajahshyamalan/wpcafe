<?php
$title            = esc_html__('Make a Reservation', 'wpcafe-pro');
$first_tab_title  = esc_html__('Booking', 'wpcafe-pro');
$second_tab_title = esc_html__('Give your details', 'wpcafe-pro');
$last_tab_title   = esc_html__('Confirmation', 'wpcafe-pro');

if ( !empty( $atts['reservation_food'] ) ) {
    $title .= esc_html__(" with Food","wpcafe-pro");

    switch ( $atts['form_style'] ) {
        case 'style-1':
            $second_tab_title   = esc_html__("Select food","wpcafe-pro");
            break;
        case 'style-2':
            $last_tab_title     = esc_html__("Select Menu","wpcafe-pro");
            break;
        default:
            break;
    }
}else{

	switch ( $atts['form_style'] ) {
		case '2':
				$title              = esc_html__("","");
				$first_tab_title    = esc_html__('Reservation Info.', 'wpcafe-pro');
				$second_tab_title   = esc_html__("Personal Info.","wpcafe-pro");
				$last_tab_title     = esc_html__("Confirmation","wpcafe-pro");
				break;
		default:
				break;
}

}
?>
<h3 class="reservation-title"><?php echo sprintf(esc_html__("%s", 'wpcafe-pro'), $title); ?></h3>
	<ul class="wpc-reservation-pagination">
	<li class="active"><?php echo sprintf(esc_html__("%s", 'wpcafe-pro'), $first_tab_title); ?></li>
	<li><?php echo sprintf(esc_html__("%s", 'wpcafe-pro'), $second_tab_title); ?></li>
	<li><?php echo sprintf(esc_html__("%s", 'wpcafe-pro'), $last_tab_title); ?></li>
</ul>