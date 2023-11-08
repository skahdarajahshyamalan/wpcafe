<?php
namespace WpCafe_Pro\Core\Modules\Food_Menu;

use WC_Product_Query;
use WP_Query;
use WpCafe\Utils\Wpc_Utilities;

defined( "ABSPATH" ) || exit;

class Helper {
    use \WpCafe_Pro\Traits\Singleton;
	
	/** 
	 *  Get all products query
	*/
    public static function get_products( $location_arr, $tag_arr, $cat_arr, $single_product_id, $product_price, $product_min_price ) {
		$args = array(
			'post_type'             => 'product',
			'post_status'           => 'publish',
			'posts_per_page'        => '10',
			'order'                 => 'DESC',
		);

		if($single_product_id != ''){
			$args['post__in'] = array($single_product_id);
		}
		
		if ( ! empty( $product_min_price ) && ! empty( $product_price ) ) {
			$args['meta_query'] = array(
				array(
				'key' => '_regular_price',
				'value' => array( $product_min_price, $product_price ),
				'compare' => 'BETWEEN',
				'type'      => 'NUMERIC'
				),
			);
		}

		if ( ! empty( $cat_arr ) ) {
			$args['tax_query'] = array(
				array(
				'taxonomy' => 'product_cat',
				'field'    => 'id',
				'terms'    => $cat_arr,
				),
			);
		}

		if ( ! empty( $location_arr ) ) {
			$args['tax_query'] = array(
				array(
				'taxonomy' => 'wpcafe_location',
				'field'    => 'id',
				'terms'    => $location_arr,
				),
			);
		}

		if ( ! empty( $tag_arr ) ) {
			$args['tax_query'] = array(
				array(
				'taxonomy' => 'product_tag',
				'field'    => 'id',
				'terms'    => $tag_arr,
				),
			);
		}

		if ( ! empty( $tag_arr ) && ! empty( $location_arr ) ) {
			$args['tax_query'] = array(
				'relation' => 'AND',
				array(
				'taxonomy' => 'wpcafe_location',
				'field'    => 'id',
				'terms'    => $location_arr,
				),
				array(
				'taxonomy' => 'product_tag',
				'field'    => 'id',
				'terms'    => $tag_arr,
				),
			);
		}

		if ( ! empty( $tag_arr ) && ! empty( $location_arr ) && ! empty( $cat_arr ) ) {
			$args['tax_query'] = array(
				'relation' => 'AND',
				array(
				'taxonomy' => 'wpcafe_location',
				'field'    => 'id',
				'terms'    => $location_arr,
				),
				array(
				'taxonomy' => 'product_tag',
				'field'    => 'id',
				'terms'    => $tag_arr,
				),
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'id',
					'terms'    => $cat_arr,
					),
			);
		}

		$posts = get_posts( $args );
		$products = array();
		

		if ( !empty($posts) ) {
			foreach ( $posts as $key => $post ):
				$product_instance = wc_get_product($post->ID);
				if(has_post_thumbnail($post->ID)){
					$image = wp_get_attachment_image( get_post_thumbnail_id( $post->ID ), 'medium', '', '' );
				} else {
					$image_url = wc_placeholder_img_src( 'woocommerce_single' );
					$image = '<img src="'.esc_url($image_url).'" alt="'.esc_attr__('single image blank','wpcafe-pro').'">';
				}				
				
				$products[$key]['id'] = $post->ID;
				$products[$key]['post_title'] = get_the_title( $post->ID );
				$products[$key]['post_permalink'] = get_permalink( $post->ID );
				$products[$key]['post_description'] = $product_instance->get_short_description();
				$products[$key]['post_image'] = $image;
				$products[$key]['post_image_alt'] = esc_html__('product image', 'wpcafe-pro');
				$products[$key]['post_price'] = $product_instance->get_price_html();
				// show cart button
				$add_cart_args = array(
					'product'       => $product_instance,
					'cart_button'   => 'on',
					'wpc_btn_text'  => '',
					'customize_btn' => '',
					'widget_id'     => '',
				);
				$products[$key]['add_to_cart'] =  Wpc_Utilities::product_add_to_cart( $add_cart_args );

			endforeach;
		} else {
			$products[0]['post_title_empty'] = esc_html__('No products found!', 'wpcafe-pro');
			$products[0]['post_empty_class'] = 'hidden-box';
		}

		return $products;

	}

	/** 
	 *  Get Location after search
	*/
	public static function get_search_html( $search_value ) {
		$search_html = array();
		if ( $search_value !== "" ) {
			$search_data    = trim( $search_value, " " );
			$matching_terms = get_terms( array(
				'taxonomy'   => 'wpcafe_location',
				'hide_empty' => false,
				'name__like' => $search_data
			) );

			if ( ! empty( $matching_terms ) ) {
				foreach ( $matching_terms as $key => $term ) {
					$search_html[$key]['term_id'] = $term->term_id;
					$search_html[$key]['term_name'] = $term->name;
				}
			} else {
				$search_html[0]['term_id'] = 0;
				$search_html[0]['term_name'] = esc_html__( 'No data found', 'wpcafe-pro' );				
			}
		}

		return $search_html;

	}

	/** 
	 *  Get Product after search
	*/
	public static function get_product_html( $search_value, $location_arr, $tag_arr, $cat_arr ) {
		$product_search = array();
		if ( $search_value !== "" ) {
			$search_data    = trim( $search_value, " " );

			$args = array(
				'post_type'             => 'product',
				'post_status'           => 'publish',
				'posts_per_page'        => -1,
				'order'                 => 'DESC',
				's'						=> $search_data
			);

			if ( ! empty( $cat_arr ) ) {
				$args['tax_query'] = array(
					array(
					'taxonomy' => 'product_cat',
					'field'    => 'id',
					'terms'    => $cat_arr,
					),
				);
			}

			if ( ! empty( $location_arr ) ) {
				$args['tax_query'] = array(
					array(
					'taxonomy' => 'wpcafe_location',
					'field'    => 'id',
					'terms'    => $location_arr,
					),
				);
			}

			if ( ! empty( $tag_arr ) ) {
				$args['tax_query'] = array(
					array(
					'taxonomy' => 'product_tag',
					'field'    => 'id',
					'terms'    => $tag_arr,
					),
				);
			}

			if ( ! empty( $tag_arr ) && ! empty( $location_arr ) ) {
				$args['tax_query'] = array(
					'relation' => 'AND',
					array(
					'taxonomy' => 'wpcafe_location',
					'field'    => 'id',
					'terms'    => $location_arr,
					),
					array(
					'taxonomy' => 'product_tag',
					'field'    => 'id',
					'terms'    => $tag_arr,
					),
				);
			}

			if ( ! empty( $tag_arr ) && ! empty( $location_arr ) && ! empty( $cat_arr ) ) {
				$args['tax_query'] = array(
					'relation' => 'AND',
					array(
					'taxonomy' => 'wpcafe_location',
					'field'    => 'id',
					'terms'    => $location_arr,
					),
					array(
					'taxonomy' => 'product_tag',
					'field'    => 'id',
					'terms'    => $tag_arr,
					),
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => $cat_arr,
						),
				);
			}

			$posts = get_posts( $args );

			if ( !empty($posts) ) {
				foreach ( $posts as $key => $post ):
					$product_search[$key]['id'] = $post->ID;
					$product_search[$key]['post_title'] = get_the_title( $post->ID );
				endforeach;
			} else {
				$product_search[0]['id'] = 0;
				$product_search[0]['post_title'] = esc_html__('No products found!', 'wpcafe-pro');
			}
		}

		return $product_search;

	}

}