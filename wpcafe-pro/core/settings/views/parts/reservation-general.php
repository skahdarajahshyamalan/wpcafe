
<?php

use WpCafe\Utils\Wpc_Utilities;
$business_label = !empty($settings['business_hour_label']) ? $settings['business_hour_label'] : "";

$markup_fields_general = [      

    'business_hour_label' => [
        'item' => [
            'label'    => esc_html__( 'Business Hour Label', 'wpcafe-pro' ),
            'desc'     => esc_html__( 'Show business hour title. You can leave it empty if you wish. Time slot will be shown from Reservation Schedule.', 'wpcafe-pro' ),
            'type'     => 'textarea',
            'attr'     => ['class' => 'wpc-label-item', 'row' => '7', 'col' => '30'],
        ],
        'data' => [ 'business_hour_label' => $business_label ],
    ],
];

?>

<?php
return $markup_fields_general;
        