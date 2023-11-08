<?php
namespace WpCafe_Pro\Core\Metaboxes;

defined( "ABSPATH" ) || exit;

use WpCafe\Core\Base\Wpc_Metabox;

class Foodmenu_Meta extends Wpc_Metabox {

    use \WpCafe_Pro\Traits\Singleton;

    public $metabox_id       = 'Foodmenu_Meta';
    public $foodmenu_metabox = [];
    public $cpt_id           = 'product';

    /**
     * Register meta box
     *
     * @return void
     */
    public function register_meta_boxes() {
        add_meta_box(
            $this->metabox_id,
            esc_html__( 'WpCafe Food Preparation and Delivery Time', 'wpcafe-pro' ),
            [$this, 'display_callback'],
            $this->cpt_id
        );
    }

    /**
     * Pass metabox array
     *
     * @return void
     */
    public function wpc_default_metabox_fields() {
        $this->foodmenu_metabox = [
            'wpc_pro_preparing_time' => [
                'label'    => esc_html__( 'Preparation time', 'wpcafe-pro' ),
                'type'     => 'text',
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Food item preparation time', 'wpcafe-pro' ),
                'priority' => 1,
                'attr'     => ['class' => 'wpc-label-item wpc_pro_preparing_time'],
                'required' => true,
            ],
            'wpc_pro_delivery_time'  => [
                'label'    => esc_html__( 'Delivery time', 'wpcafe-pro' ),
                'type'     => 'text',
                'default'  => '',
                'value'    => '',
                'desc'     => esc_html__( 'Food item delivery time', 'wpcafe-pro' ),
                'priority' => 1,
                'attr'     => ['class' => 'wpc-label-item wpc_pro_delivery_time'],
                'required' => true,
            ],
        ];
        return $this->foodmenu_metabox;
    }

    /**
     * Save metabox title
     *
     * @param [type] $data
     * @param [type] $postarr
     * @return void
     */
    public function save_foodmenu_title( $data, $postarr ) {
        if ( 'product' == $data['post_type'] ) {
            /**
             * update  food menu meta
             */
            if ( isset( $postarr['wpc_pro_preparing_time'] ) && isset( $postarr['wpc_pro_delivery_time'] ) ) {
                $wpc_pro_preparing_time = sanitize_text_field( $postarr['wpc_pro_preparing_time'] );
                $wpc_pro_delivery_time  = sanitize_text_field( $postarr['wpc_pro_delivery_time'] );
                update_post_meta( $postarr['ID'], 'wpc_pro_preparing_time', $wpc_pro_preparing_time );
                update_post_meta( $postarr['ID'], 'wpc_pro_delivery_time', $wpc_pro_delivery_time );
            }
        }
    }

}
