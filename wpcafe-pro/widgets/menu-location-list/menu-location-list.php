<?php

namespace WpCafe_Pro\Widgets;

defined("ABSPATH") || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use WpCafe_Pro\Utils\Utilities as Pro_Utilities;


class Menu_Location_List extends Widget_Base
{

	/**
	 * Retrieve the widget name.
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'wpc-location-list-pro';
	}

	/**
	 * Retrieve the widget title.
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('WPC Location List Pro', 'wpcafe-pro');
	}

	/**
	 * Retrieve the widget icon.
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-menu-card wpc-widget-icon';
	}

	/**
	 * Retrieve the widget category.
	 * @return string Widget category.
	 */
	public function get_categories()
	{
		return ['wpcafe-menu'];
	}

	protected function register_controls()
	{
		// Start of event section 
		$this->start_controls_section(
			'section_tab',
			[
				'label' => esc_html__('Location List', 'wpcafe-pro'),
			]
		);

		$this->add_control(
			'food_cat_style',
			[
				'label' => esc_html__('Location Style', 'wpcafe-pro'),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => [
					'style-1'  => esc_html__('Style 1', 'wpcafe-pro'),
					'style-2'  => esc_html__('Style 2', 'wpcafe-pro'),
					'style-3'  => esc_html__('Style 3', 'wpcafe-pro'),
					'style-4'  => esc_html__('Style 4', 'wpcafe-pro'),
					'style-5'  => esc_html__('Style 5', 'wpcafe-pro'),
				],
			]
		);
		$this->add_control(
			'wpc_menu_col',
			[
				'label' => esc_html__('Column', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'12' => esc_html__('1', 'wpcafe-pro'),
					'6' => esc_html__('2', 'wpcafe-pro'),
					'4' => esc_html__('3', 'wpcafe-pro'),
					'3' => esc_html__('4', 'wpcafe-pro'),
					'2' => esc_html__('6', 'wpcafe-pro'),
				],
				'condition' => ['food_cat_style' => ['style-1','style-2','style-3','style-4']],

			]
		);

		$this->add_control(
			'location_limit',
			[
				'label' 		=> esc_html__('Location Limit', 'wpcafe-pro'),
				'description'	=> esc_html__('Limit works when food location is not selected', 'wpcafe-pro'),
				'type' 			=> Controls_Manager::NUMBER,
				'default' 		=> '20',
				'condition' => ['food_cat_style' => ['style-1','style-2','style-3','style-4','style-5']],
			]
		);

		$this->add_control(
			'wpc_menu_cat',
			[
				'label' => esc_html__('Location', 'wpcafe-pro'),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->get_menu_location(),
				'multiple' => true,
				'condition' => ['food_cat_style' => ['style-1','style-2','style-3','style-4','style-5']],
			]
		);
		$this->add_control(
			'hide_empty',
			[
				'label'     	=> esc_html__('Hide Empty?', 'wpcafe-pro'),
				'description'	=> esc_html__('Hide empty works when food location is not selected', 'wpcafe-pro'),
				'type'      	=> Controls_Manager::SWITCHER,
				'default'   	=> '',
				'condition' => ['food_cat_style' => ['style-1','style-2','style-3','style-4','style-5']],
			]
		);
		$this->add_control(
			'show_count',
			[
				'label' => esc_html__('Show Count', 'wpcafe-pro'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Show', 'wpcafe-pro'),
				'label_off' => esc_html__('Hide', 'wpcafe-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => ['food_cat_style' => ['style-1','style-2','style-3','style-4','style-5']],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__('Title Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'titlte_color',
			[
				'label'         => esc_html__('Title Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-category-title a' => 'color: {{VALUE}};',
				],
			]
		);

	
		$this->add_control(
			'titlte_bg_color',
			[
				'label'         => esc_html__('Title BG Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-category-title a' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'title_typo',
				'label'         => esc_html__('Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}} .wpc-category-title',
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__('Padding', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-category-title a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'readmore_btn_style',
			[
				'label' => esc_html__('Button Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['food_cat_style' => 'style-4']
			]
		);
		$this->add_control(
			'btn_color',
			[
				'label'         => esc_html__('Button Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-readmore-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label'         => esc_html__('Button Hover Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-single-cat-item:hover .wpc-readmore-link' => 'color: {{VALUE}};',
				],
			]
		);

	
		$this->add_control(
			'btn_bg_color',
			[
				'label'         => esc_html__('Button BG Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-readmore-link' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_bg_hover_color',
			[
				'label'         => esc_html__('Button BG Hover Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-single-cat-item:hover .wpc-readmore-link' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'btn_typo',
				'label'         => esc_html__('Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}} .wpc-readmore-link',
			]
		);
		$this->add_responsive_control(
			'btn_width',
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
					'{{WRAPPER}} .wpc-readmore-link' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_height',
			[
				'label' => esc_html__('Height', 'wpcafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpc-readmore-link' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();



		$this->start_controls_section(
			'thumbnail_style',
			[
				'label' => esc_html__('Thumbnail Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'img_width',
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
					'{{WRAPPER}} .wpc-cat-thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'img_height',
			[
				'label' => esc_html__('Height', 'wpcafe-pro'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpc-cat-thumb' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'advance_style',
			[
				'label' => esc_html__('Advance Style', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'wpc_box_bg_color',
			[
				'label'         => esc_html__('Box Bacground Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-location-list-style4 .wpc-single-cat-item' => 'background-color: {{VALUE}};',
				],
				'condition' => ['food_cat_style' => ['style-4']],
			]
		);
		$this->add_control(
			'wpc_box_bg_hover_color',
			[
				'label'         => esc_html__('Box Hover Bacground Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-location-list-style4 .wpc-single-cat-item:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => ['food_cat_style' => ['style-4']],
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'label' => esc_html__('Border Radius', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-single-cat-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_margin',
			[
				'label' => esc_html__('Margin', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-single-cat-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}


	protected function render()
	{
		$settings   		 = $this->get_settings();
		$style      		 = $settings["food_cat_style"];
		$grid_column         = $settings['wpc_menu_col'];
		$categories_id       = $settings['wpc_menu_cat'];
		$hide_empty          = $settings['hide_empty'] == 'yes' ? true : false;
		$show_count          = $settings['show_count'];
		$location_limit      = $settings['location_limit'];
		
		include \Wpcafe_Pro::core_dir() . "shortcodes/views/food-menu/food-location.php";
	}

	protected function get_menu_location()
	{
		return Pro_Utilities::get_menu_location();
	}
}
