<?php

namespace WpCafe_Pro\Widgets;

defined( "ABSPATH" ) || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;

class Pickup_Delivery extends Widget_Base {
	public $base;

		public function get_name() {
				return 'pickup-delivery';
		}

		public function get_title() {
				return esc_html__( 'Pickup Delivery', 'wpcafe-pro' );
		}

		public function get_icon() {
				return 'eicon-site-search';
		}

		public function get_categories() {
				return [ 'wpcafe-menu' ];
		}

		protected function register_controls() {

			$this->start_controls_section(
					'section_tab',
					[
							'label' => esc_html__('Pickup-delivery settings', 'wpcafe-pro' ),
					]
			);

			$this->add_control(
				'delivery_title_text',
				[
					'label' => esc_html__('Delivery Button Text','wpcafe-pro'),
					'type'         => Controls_Manager::TEXT,
					'label_block'  => true,
					'default'	=> esc_html__('Delivery', 'wpcafe-pro')
				]
			);

			$this->add_control(
				'pickup_title_text',
				[
					'label' => esc_html__('Pickup Button Text','wpcafe-pro'),
					'type'         => Controls_Manager::TEXT,
					'label_block'  => true,
					'default'	=> esc_html__('Pickup', 'wpcafe-pro')
				]
			);

			$this->add_control(
				'select_search_page',
				[
						'label'    => esc_html__( 'Select Search Page', 'wpcafe-pro' ),
						'type'     => Controls_Manager::SELECT,
						'options'  => $this->get_all_pages(),
				]
			);

				$this->end_controls_section();
		}

		protected function render( ) {
			$settings = $this->get_settings();

			$unique_id = $this->get_id();

			$widget_arr=array(
				'unique_id'				=> $unique_id
			);

			$search = "";
			if ($widget_arr) {
					$search = isset( $widget_arr['unique_id'] ) ? $widget_arr['unique_id'] : "";
			}

			$template = 'list_template';
			$no_of_product = 20;
			$wpc_cart_button = 'yes';

			$locations = $this->get_all_locations();
			$pickup_data = get_term_by('name', 'Pickup', 'product_tag');
			$pickup = $pickup_data->term_id;
			$delivery_data = get_term_by('name', 'Delivery', 'product_tag');
			$delivery = $delivery_data->term_id;

			$template_path = \Wpcafe_Pro::plugin_dir() . "/widgets/pickup-delivery/style/style-1.php";
			
			$args = array(
				'post_type'     => 'product',
				'order'         => 'DESC',
				'posts_per_page'   => $no_of_product,
			);
	
			if( !empty($locations) ){
				$tax_query = array(
				array(
					'taxonomy'          => 'wpcafe_location',
					'terms'             =>  $locations,
					'field'             => 'id',
					'include_children'  => true,
					'operator'          => 'IN'
				),
			);
				$args['tax_query'] = $tax_query;
			}

			$products = [];

			?>
		<div class="widget-pickup-delivery">
			<form class="pickup-delivery-form" action="<?php echo get_permalink($settings['select_search_page']); ?>" method="POST">
			<div class="wpc-ajax-locations-wrap search_<?php echo esc_attr($search); ?>" data-id="<?php echo esc_attr($search); ?>">
				<div class="wpc-ajax-input-search">
					<div class="data_section data_value_<?php echo esc_attr($search); ?>" data-total_product="<?php echo esc_attr($no_of_product); ?>" data-location_arr="<?php echo esc_attr(json_encode($locations)); ?>" data-cart_button="<?php echo esc_attr($wpc_cart_button); ?>" data-pageurl="<?php echo get_permalink($settings['select_search_page']); ?>" data-template_name="<?php echo esc_attr($template); ?>" data-template_path="<?php echo esc_attr($template_path); ?>" data-unique_id="<?php echo esc_attr($search); ?>" data-widget_arr="<?php echo esc_attr(json_encode($widget_arr)); ?>">
					</div>
					<input class="wpc-input-field live_food_menu_<?php echo esc_attr($search); ?>" placeholder="<?php esc_attr_e('Enter Your Location', 'wpcafe-pro')?>" />
					<button type="button" value="pickup" class="pickup_<?php echo esc_attr($search); ?>" data-pickup="<?php echo esc_attr($pickup); ?>"><?php echo esc_html($settings['pickup_title_text'] ); ?></button>
					<p><?php echo esc_html__('or', 'wpcafe-pro'); ?></p>
					<button type="button" value="delivery" class="delivery_<?php echo esc_attr($search); ?> btn" data-delivery="<?php echo esc_attr($delivery); ?>"><?php echo esc_html($settings['delivery_title_text'] ); ?></button>
				</div>
				<div class="wpc-ajax-search-result">
						<div class="search_result_<?php echo esc_attr($search); ?>">
							<ul class="get_result"></ul>
							<?php 
								if ( file_exists( \Wpcafe_Pro::plugin_dir() . "/widgets/pickup-delivery/style/search-data.php" )) {
										include \Wpcafe_Pro::plugin_dir() . "/widgets/pickup-delivery/style/search-data.php";
								}
							?>
						
						</div>
				</div>
			</div>
			</form>
		</div>	

		<?php
	}

	protected function get_all_locations() {
		$locations = [];
		try {

				$terms = get_terms( [
					'taxonomy'   => 'wpcafe_location',
					'hide_empty' => false,
				] );

				foreach ( $terms as $location ) {
					if(is_object( $location ) ){
						$locations[] = $location->term_id;
					}
				}

				return $locations;

		} catch ( \Exception $es ) {
			return [];
		}

	}

	protected function get_all_pickup() {
		$pickup = [];
		try {

				$terms = get_terms( [
					'taxonomy'   => 'product_tag',
					'hide_empty' => false,
				] );

				foreach ( $terms as $pick ) {
					if(is_object( $pick ) ){
						$pickup[] = $pick->term_id;
					}
				}

				return $pickup;

		} catch ( \Exception $es ) {
			return [];
		}

	}

	protected function get_all_pages() {
		$pages_list = [];
		try {

			$pages = get_pages(); 
			foreach ( $pages as $page ) {
				$pages_list[$page->ID] = $page->post_title;
			}

				return $pages_list;

		} catch ( \Exception $es ) {
			return [];
		}

	}

}
