<?php

namespace WpCafe_Pro\Widgets;

defined( "ABSPATH" ) || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;

class Food_Menu_slider extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wpc-food-menu-slider-pro';
	}

	/**
	 * Retrieve the widget title.
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__('WPC Food Menu Slider Pro', 'wpcafe-pro');
	}

	/**
	 * Retrieve the widget icon.
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-menu-card wpc-widget-icon';
	}

	/**
	 * Retrieve the widget category.
	 * @return string Widget category.
	 */
	public function get_categories() {
		return ['wpcafe-menu'];
	}

	protected function register_controls() {
		// Start of event section 
		$this->start_controls_section(
			'section_tab',
			[
				'label' => esc_html__('WPC Food Menu Slider', 'wpcafe-pro'),
			]
		);

		$this->add_control(
			'food_menu_style',
			[
				'label' => esc_html__('Menu Style', 'wpcafe-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1'  => esc_html__('Menu Style 1', 'wpcafe-pro'),
					'style-2'  => esc_html__('Menu Style 2', 'wpcafe-pro'),
					'style-3'  => esc_html__('Menu Style 3', 'wpcafe-pro'),
				],
			]
		);

		$this->add_control(
			'wpc_menu_cat',
			[
				'label' => esc_html__('Menu Category', 'wpcafe-pro'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_menu_category(),
				'multiple' => true,
			]
		);
		$this->add_control(
			'wpc_menu_count',
			[
				'label'         => esc_html__('Menu count', 'wpcafe-pro'),
				'type'          => Controls_Manager::NUMBER,
				'default'       => '6',
			]
		);
		$this->add_control(
			'wpc_slider_count',
			[
				'label'         => esc_html__('Slider count', 'wpcafe-pro'),
				'type'          => Controls_Manager::NUMBER,
				'default'       => '6',
			]
		);
		$this->add_control(
			'show_item_status',
			[
				'label' => esc_html__('Show Item Status', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'wpcafe-pro'),
				'label_off' => esc_html__('Hide', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'wpc_menu_order',
			[
				'label' => esc_html__('Menu Order', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC'  => esc_html__('ASC', 'wpcafe-pro'),
					'DESC' => esc_html__('DESC', 'wpcafe-pro'),
				],
			]
		);


		$this->add_control(
			'show_thumbnail',
			[
				'label' => esc_html__('Show Thumbnail', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'wpcafe-pro'),
				'label_off' => esc_html__('Hide', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);


		$this->add_control(
			'wpc_show_desc',
			[
				'label' => esc_html__('Show Description', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'wpcafe-pro'),
				'label_off' => esc_html__('Hide', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'wpc_desc_limit',
			[
				'label'         => esc_html__('Description Limit', 'wpcafe-pro'),
				'type'          => Controls_Manager::NUMBER,
				'default'       => '15',
				'condition' => ['wpc_show_desc' => 'yes']
			]
		);
		$this->add_control(
			'title_link_show',
			[
				'label' => esc_html__('Use Title Link?', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'wpcafe-pro'),
				'label_off' => esc_html__('Hide', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'wpc_delivery_time_show',
			[
				'label' => esc_html__('Show Preparing and Delivery Time', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'wpcafe-pro'),
				'label_off' => esc_html__('Hide', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'wpc_cart_button_show',
			[
				'label' => esc_html__('Show Cart Button', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'wpcafe-pro'),
				'label_off' => esc_html__('Hide', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'wpc_auto_play',
			[
				'label' => esc_html__('Auto play', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('On', 'wpcafe-pro'),
				'label_off' => esc_html__('Off', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		
		$this->add_control(
			'wpc_btn_text',
			[
				'label' => esc_html__('Button Text', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => ['wpc_cart_button_show' => 'yes']
			]
		);
		$this->add_control(
			'customize_btn',
			[
				'label' => esc_html__('Button Text For Variable Product', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => ['wpc_cart_button_show' => 'yes']
			]
		);


		$this->end_controls_section();


		// item cart button style section 
		$this->start_controls_section(
			'item_pro_thumbanil_style',
			[
				'label' => esc_html__('Thumbnail Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['food_menu_style' => ['style-3']]

			]
		);
		$this->add_responsive_control(
			'thumbnail_width',
			[
				'label' => esc_html__('Width', 'wpcafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-food-menu-thumb.wpc-post-bg-img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'thumbnail_height',
			[
				'label' => esc_html__('Height', 'wpcafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-food-menu-thumb.wpc-post-bg-img' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wpc_pro_thum_border_radius',
			[
				'label' => esc_html__('Border Radius', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-food-menu-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();



		// item slider nav style section 
		$this->start_controls_section(
			'wpc_slider_style',
			[
				'label' => esc_html__('Slider Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'wpc_slider_nav_show',
			[
				'label' => esc_html__('Slider Nav Show', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'wpcafe-pro'),
				'label_off' => esc_html__('Hide', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'wpc_slider_dot_show',
			[
				'label' => esc_html__('Slider dot Show', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'wpcafe-pro'),
				'label_off' => esc_html__('Hide', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'wpc_slider_nav_bg_color',
			[
				'label'         => esc_html__('Slider Nav BG Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-food-menu-slider .swiper-button-next, {{WRAPPER}} .wpc-food-menu-slider .swiper-button-prev' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();


		// item status style section 
		$this->start_controls_section(
			'item_status_style',
			[
				'label' => esc_html__('Item Status Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['show_item_status' => 'yes']
			]
		);
		$this->add_control(
			'wpc_menu_item_status_color',
			[
				'label'         => esc_html__('Item Status Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-menu-tag li' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wpc_menu_item_status_bg_color',
			[
				'label'         => esc_html__('Item Status BG Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-menu-tag li' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'wpc_menu_status_typo',
				'label'         => esc_html__('Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}} .wpc-menu-tag li',
			]
		);
		$this->add_responsive_control(
			'wpc_menu_item_status_paddding',
			[
				'label' => esc_html__('Padding', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-menu-tag li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wpc_item_status_border_radius',
			[
				'label' => esc_html__('Border Radius', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-menu-tag li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		// title style section 
		$this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__('Title Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'wpc_menu_title_color',
			[
				'label'         => esc_html__('Title Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-post-title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wpc_menu_title_hover_color',
			[
				'label'         => esc_html__('Title Hover Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-post-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		//control for title typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'wpc_menu_title',
				'label'         => esc_html__('Title Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}} .wpc-post-title',
			]
		);
		$this->add_responsive_control(
			'wpc_title_margin',
			[
				'label' => esc_html__('Title Margin', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// item cart button style section 
		$this->start_controls_section(
			'item_pro_cart_button_style',
			[
				'label' => esc_html__('Cart Button Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['wpc_cart_button_show' => 'yes']
			]
		);
		$this->add_control(
			'wpc_pro_cart_color',
			[
				'label'         => esc_html__('Cart Button Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wpc_pro_cart_button_bg_color',
			[
				'label'         => esc_html__('Cart Button BG Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'wpc_pro_cart_button_typo',
				'label'         => esc_html__('Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a',
			]
		);
		$this->add_responsive_control(
			'wpc_pro_cart_btn_width',
			[
				'label' => esc_html__('Width', 'wpcafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
			
				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wpc_pro_cart_btn_height',
			[
				'label' => esc_html__('Height', 'wpcafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
			
				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wpc_pro_cart_btn_paddding',
			[
				'label' => esc_html__('Padding', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wpc_pro_cart_btn_border_raidus',
			[
				'label' => esc_html__('Border Radius', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'wpc_pro_cart_btn_position_rtl',
			[
				'label' => esc_html__('Button Right To Left', 'wpcafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wpc_pro_cart_btn_position_ttb',
			[
				'label' => esc_html__('Button Bottom To Top', 'wpcafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-add-to-cart' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		// price style section 
		$this->start_controls_section(
			'price_style',
			[
				'label' => esc_html__('Price Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'wpc_menu_price_color',
			[
				'label'         => esc_html__('Price Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-menu-currency' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wpc_menu_price_bg_color',
			[
				'label'         => esc_html__('Price BG Color', 'wpcafe-pro'),
				'type'          => Controls_Manager::COLOR,
				'condition'=> ['food_menu_style'=> ['style-2', 'style-3']],
				'selectors'     => [
					'{{WRAPPER}} .wpc-food-menu-item .wpc-price' => 'background: {{VALUE}};',
					'{{WRAPPER}} .wpc-food-tab-style4.wpc-food-menu-item span.wpc-menu-currency,  {{WRAPPER}} .wpc-slider-grid-3 .wpc-food-menu-item span.wpc-menu-currency' => 'background: {{VALUE}};',
				],
			]
		);

		//control for title typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'wpc_menu_price',
				'label'         => esc_html__('Price Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}} .wpc-menu-currency',
			]
		);

		$this->end_controls_section();



		// description style section 
		$this->start_controls_section(
			'wpc_desc_style',
			[
				'label' => esc_html__('Description Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['wpc_show_desc' => 'yes'],
			]
		);
		$this->add_control(
			'wpc_menu_desc_color',
			[
				'label'         => esc_html__('Description Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-food-inner-content p' => 'color: {{VALUE}};',
				],
			]
		);

		//control for title typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'wpc_menu_desc',
				'label'         => esc_html__('Description Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}} .wpc-food-inner-content p',
			]
		);

		$this->add_responsive_control(
			'wpc_desc_padding',
			[
				'label' => esc_html__('Description Padding', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-food-inner-content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wpc_desc_margin',
			[
				'label' => esc_html__('Description Margin', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-food-inner-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// advance style section 
		$this->start_controls_section(
			'wpc_advance_style',
			[
				'label' => esc_html__('Advance Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'wpc_box_margin',
			[
				'label' => esc_html__('Margin', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-food-menu-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'wpc_box_padding',
			[
				'label' => esc_html__('Padding', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-food-slider1 .wpc-food-inner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpc-tab-grid-style2 .wpc-food-menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpc-slider-grid-3 .wpc-food-inner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__('Box Shadow', 'wpcafe-pro'),
				'selector' => '{{WRAPPER}} .wpc-food-menu-item',
			]
		);

		$this->end_controls_section();
	}


	protected function render() {
		$settings              = $this->get_settings();
		$style                 = $settings["food_menu_style"];
		$show_item_status      = $settings["show_item_status"];
		$wpc_show_desc         = $settings["wpc_show_desc"];
		$wpc_desc_limit        = $settings["wpc_desc_limit"];
		$title_link_show       = $settings["title_link_show"];
		$wpc_cart_button       = $settings["wpc_cart_button_show"];
		$wpc_btn_text          = $settings["wpc_btn_text"];
		$wpc_menu_order        = $settings["wpc_menu_order"];
		$show_thumbnail        = $settings["show_thumbnail"];
		$wpc_slider_dot_show   = $settings["wpc_slider_dot_show"];
		$wpc_slider_nav_show   = $settings["wpc_slider_nav_show"];
		$wpc_slider_count      = $settings["wpc_slider_count"];
		$wpc_delivery_time_show		= $settings["wpc_delivery_time_show"];
		$customize_btn      		= $settings["customize_btn"];
		$wpc_menu_cat      			= $settings["wpc_menu_cat"];
		$wpc_menu_count      		= $settings["wpc_menu_count"];
		$wpc_auto_play      		= $settings["wpc_auto_play"];

		$unique_id = $this->get_id();
		$wpc_standard_discount = \WpCafe\Core\Base\Wpc_Settings_Field::instance()->get_settings_option();
		$wpc_pro_standarad_off   = isset($wpc_standard_discount['wpc_pro_discount_standarad_off_message'])  ? sanitize_text_field($wpc_standard_discount['wpc_pro_discount_standarad_off_message']) : '';
		$no_desc_class = ($wpc_show_desc != 'yes') ? 'wpc-no-desc' : '';

		?>

		<div class="<?php echo esc_attr( $no_desc_class )?> wpc-food-wrapper">
			<?php
			if ($wpc_pro_standarad_off !== '') {
			?>
				<div class="wpc_pro_standard_offer_message"><?php echo esc_html($wpc_pro_standarad_off); ?></div>
			<?php
			}
			$unique_id = $this->get_id();

            $products_args = array(
                'post_type'     => 'product',
                'no_of_product' => $wpc_menu_count,
                'wpc_cat'       => $settings['wpc_menu_cat'] ,
                'order'         => $wpc_menu_order
            );
			$products = Wpc_Utilities::product_query( $products_args );
			include \Wpcafe_Pro::plugin_dir() . "/widgets/food-menu-slider/style/{$style}.php";
			?>
		</div>
		<?php
	}

	protected function get_menu_category() {
		return Wpc_Utilities::get_menu_category();
	}
}
