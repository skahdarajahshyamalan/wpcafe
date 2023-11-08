<?php

namespace WpCafe_Pro\Widgets;

defined( "ABSPATH" ) || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;

class Reservation_With_Food extends Widget_Base{

	/**
	 * Retrieve the widget name.
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wpc-pro-reservation-with-food';
	}

	/**
	 * Retrieve the widget title.
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__('WPC Reservation With Food Pro', 'wpcafe-pro');
	}

	/**
	 * Retrieve the widget icon.
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	/**
	 * Retrieve the widget category.
	 * @return string Widget category.
	 */
	public function get_categories() {
		return ['wpcafe-menu'];
	}

	protected function register_controls() {
        //start of style content tab
        $this->start_controls_section(
            'section_reserv_tab',
            [
                'label' => esc_html__( 'WPC Reservation settings', 'wpcafe-pro' ),
            ]
        );

        $this->add_control(
            'form_style',
            [
                'label' => esc_html__( 'Reservation Form Style', 'wpcafe-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'style-1' => esc_html__('Style 1', 'wpcafe-pro'),
                    'style-2' => esc_html__('Style 2', 'wpcafe-pro'),
                ],
                'default' => 'style-1',
            ]
        );

        $this->end_controls_section();
		
		//start of style content tab
		$this->start_controls_section(
			'section_menu_tab',
			[
				'label' => esc_html__( 'Food Menu settings', 'wpcafe-pro' ),
			]
		);

		$this->add_control(
            'food_template',
            [
                'label'   => esc_html__( 'Menu Template', 'wpcafe-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'wpc_food_menu_list',
                'options' => [
                    'wpc_food_menu_list' => esc_html__( 'Menu List', 'wpcafe-pro' ),
                    'wpc_food_menu_tab' => esc_html__( 'Menu Tab', 'wpcafe-pro' ),
                ],
            ]
        );

		$this->add_control(
            'food_menu_style',
            [
                'label'   => esc_html__( 'Menu Style','wpcafe-pro' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'style-1',
                'options' => [
                    'style-1' => esc_html__( 'Menu Style 1','wpcafe-pro' ),
                    'style-2' => esc_html__( 'Menu Style 2','wpcafe-pro' ),
                ],
            ]
        );

        $this->add_control(
            'wpc_menu_cat',
            [
                'label'       => esc_html__( 'Menu Category','wpcafe-pro' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_menu_category(),
                'multiple'    => true,
                'label_block' => true,

            ]
        );
        $this->add_control(
            'wpc_menu_count',
            [
                'label'   => esc_html__( 'Menu count','wpcafe-pro' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '6',
            ]
        );
        $this->add_control(
            'wpc_menu_order',
            [
                'label'   => esc_html__( 'Menu Order','wpcafe-pro' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC'  => esc_html__( 'ASC','wpcafe-pro' ),
                    'DESC' => esc_html__( 'DESC','wpcafe-pro' ),
                ],
            ]
        );
        $this->add_control(
            'show_thumbnail',
            [
                'label'        => esc_html__( 'Show Thumbnail','wpcafe-pro' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show','wpcafe-pro' ),
                'label_off'    => esc_html__( 'Hide','wpcafe-pro' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'show_item_status',
            [
                'label'        => esc_html__( 'Show Item Status','wpcafe-pro' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show','wpcafe-pro' ),
                'label_off'    => esc_html__( 'Hide','wpcafe-pro' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'wpc_show_desc',
            [
                'label'        => esc_html__( 'Show Description','wpcafe-pro' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show','wpcafe-pro' ),
                'label_off'    => esc_html__( 'Hide','wpcafe-pro' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'wpc_desc_limit',
            [
                'label'     => esc_html__( 'Description Limit','wpcafe-pro' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '15',
                'condition' => ['wpc_show_desc' => 'yes'],
            ]
        );
        $this->add_control(
            'title_link_show',
            [
                'label'        => esc_html__( 'Use Title Link?','wpcafe-pro' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show','wpcafe-pro' ),
                'label_off'    => esc_html__( 'Hide','wpcafe-pro' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'wpc_cart_button_show',
            [
                'label'        => esc_html__( 'Show add to cart button','wpcafe-pro' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show','wpcafe-pro' ),
                'label_off'    => esc_html__( 'Hide','wpcafe-pro' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

			
        $this->end_controls_section();

		// Start of button section 
		$this->start_controls_section(
			'section_nav',
			[
				'label' => esc_html__('Form Header', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);
		$this->add_control(
			'title_color',
			[
				'label'         => esc_html__('Title color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc-reservation-pro-wrap .reservation-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'title',
				'label'         => esc_html__('Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}} .wpc-reservation-pro-wrap .reservation-title',
			]
		);

		
		$this->add_control(
			'pagination',
			[
				'label' => esc_html__( 'Pagination Number Style', 'wpcafe-pro' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);


		//start of nav color tabs (normal and hover)
		$this->start_controls_tabs(
			'wpc_pagination_tabs'
		);


		//start of nav normal color tab
		$this->start_controls_tab(
			'wpc_pagination_normal_tab',
			[
				'label' => esc_html__('Normal', 'wpcafe-pro'),
			]
		);


		$this->add_control(
			'wpc_pagination_color',
			[
				'label'         => esc_html__('Pagination color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} #wpc-multi-step-reservation .wpc-reservation-pagination li' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wpc_pagination_bg_color',
			[
				'label'         => esc_html__('Background color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}}  #wpc-multi-step-reservation .wpc-reservation-pagination li:before, {{WRAPPER}} #wpc-multi-step-reservation .wpc-reservation-pagination li:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		//end of nav normal color tab

		//start of nav active color tab
		$this->start_controls_tab(
			'wpc_pagination_active_tab',
			[
				'label' => esc_html__('Active', 'wpcafe-pro'),

			]
		);
		$this->add_control(
			'wpc_pagination_active_color',
			[
				'label'         => esc_html__('color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} #wpc-multi-step-reservation .wpc-reservation-pagination li.active' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wpc_pagination_bg_active_color',
			[
				'label'         => esc_html__(' Background color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}}  #wpc-multi-step-reservation .wpc-reservation-pagination li.active:before, {{WRAPPER}} #wpc-multi-step-reservation .wpc-reservation-pagination li.active:after' => 'background-color: {{VALUE}};',
				],
			]
		);
	
		$this->end_controls_tab();
		//end of nav hover color tab

		$this->end_controls_tabs();
		//end of nav color tabs (normal and hover)


		$this->end_controls_section();

		// Start of event section 
		$this->start_controls_section(
			'label_tab',
			[
				'label' => esc_html__('Field Label', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'wpc_label_color',
			[
				'label'         => esc_html__('Label Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}}  .wpc-reservation-field label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'wpc_label_typo',
				'label'         => esc_html__('Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}} .wpc-reservation-field label',
			]
		);


		$this->end_controls_section();
		// Start of event section 
		$this->start_controls_section(
			'section_input_field',
			[
				'label' => esc_html__('Input field', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);
		$this->add_control(
			'wpc_input_color',
			[
				'label'         => esc_html__('Input Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}}   .wpc-reservation-field .wpc-form-control' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wpc_input_bg_color',
			[
				'label'         => esc_html__('Input Background Color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}}  .wpc-reservation-form .wpc-reservation-field .wpc-form-control' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'         => 'wpc_input_typo',
				'label'         => esc_html__('Typography', 'wpcafe-pro'),
				'selector'     => '{{WRAPPER}}  .wpc-reservation-field .wpc-form-control',
			]
		);
		$this->add_responsive_control(
			'input_height',
			[
				'label' => esc_html__('Input Height', 'wpcafe-pro'),
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
					'{{WRAPPER}} .wpc-reservation-field .wpc-form-control' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'input_textarea_height',
			[
				'label' => esc_html__('Textarea Height', 'wpcafe-pro'),
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
					'{{WRAPPER}} .wpc-reservation-form .wpc-reservation-field .wpc-form-control#wpc-message,{{WRAPPER}} .wpc-reservation-form .wpc-reservation-field .wpc_cancell_message' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__('Input Padding', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc-reservation-field .wpc-form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Start of button section 
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__('Button', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);
		//start of nav color tabs (normal and hover)
		$this->start_controls_tabs(
			'wpc_btn_tabs'
		);


		//start of nav normal color tab
		$this->start_controls_tab(
			'wpc_btn_normal_tab',
			[
				'label' => esc_html__('Normal', 'wpcafe-pro'),
			]
		);


		$this->add_control(
			'wpc_btn_color',
			[
				'label'         => esc_html__('Button color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} #wpc-multi-step-reservation .wpc-btn' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wpc_btn_bg_color',
			[
				'label'         => esc_html__('Button Background color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} #wpc-multi-step-reservation .wpc-btn' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'label' => esc_html__('Box Shadow', 'wpcafe-pro'),
				'selector' => '{{WRAPPER}} #wpc-multi-step-reservation .wpc-btn',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'btn_border',
				'label' => esc_html__('Border', 'wpcafe-pro'),
				'selector' => '{{WRAPPER}} #wpc-multi-step-reservation .wpc-btn',
			]
		);

		$this->end_controls_tab();
		//end of nav normal color tab

		//start of nav active color tab
		$this->start_controls_tab(
			'wpc_btn_hover_tab',
			[
				'label' => esc_html__('Hover', 'wpcafe-pro'),
			]
		);
		$this->add_control(
			'wpc_btn_Hover_color',
			[
				'label'         => esc_html__('Button Hover color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}}  #wpc-multi-step-reservation .wpc-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'wpc_btn_bg_hover_color',
			[
				'label'         => esc_html__('Button Background Hover color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}}   #wpc-multi-step-reservation .wpc-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box__hover_shadow',
				'label' => esc_html__('Box Shadow', 'wpcafe-pro'),
				'selector' => '{{WRAPPER}} #wpc-multi-step-reservation .wpc-btn:hover',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'btn_border_hover',
				'label' => esc_html__('Border', 'wpcafe-pro'),
				'selector' => '{{WRAPPER}} #wpc-multi-step-reservation .wpc-btn:hover',
			]
		);
		$this->end_controls_tab();
		//end of nav hover color tab

		$this->end_controls_tabs();
		//end of nav color tabs (normal and hover)

		$this->add_responsive_control(
			'wpc_btn_padding',
			[
				'label' => esc_html__('Button Padding', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} #wpc-multi-step-reservation .wpc-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		

		// Start of event section 
		$this->start_controls_section(
			'remember_notification',
			[
				'label' => esc_html__(' Notification Message', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);
		$this->add_control(
			'notification_color',
			[
				'label'         => esc_html__('Error notification color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc_error_message' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'notification_bg_color',
			[
				'label'         => esc_html__('Error notification BG color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc_error_message' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'success_msg_color',
			[
				'label'         => esc_html__('Success notification color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc_success_message' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'sucess_bg_color',
			[
				'label'         => esc_html__('Success notification BG color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .wpc_success_message' => 'background-color: {{VALUE}};',
				],
			]
		);
	
		$this->end_controls_section();

		// Start of event section 
		$this->start_controls_section(
			'section_advance',
			[
				'label' => esc_html__('Advance', 'wpcafe-pro'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,

			]
		);
		$this->add_control(
			'wpc_form_bg_color',
			[
				'label'         => esc_html__('Form Backround color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}}   .wpc-reservation-form' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__('Box Shadow', 'wpcafe-pro'),
				'selector' => '{{WRAPPER}}  .wpc_reservation_form',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'label' => esc_html__('Border', 'wpcafe-pro'),
				'selector' => '{{WRAPPER}} .wpc_reservation_form',
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__('Box Padding', 'wpcafe-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .wpc_reservation_form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}


	protected function render() {
		$settings   = $this->get_settings();
		
		$form_style = $settings['form_style'];
		$food_template = $settings['food_template'];
		$food_menu_style = $settings['food_menu_style'];
		$wpc_menu_cat = $settings['wpc_menu_cat'];
		$cats = !empty($wpc_menu_cat) ? join(",",$wpc_menu_cat) : '';
		$wpc_menu_count = $settings['wpc_menu_count'];
		$wpc_menu_order = $settings['wpc_menu_order'];
		$show_thumbnail = $settings['show_thumbnail'];
		$show_item_status = $settings['show_item_status'];
		$wpc_show_desc = $settings['wpc_show_desc'];
		$wpc_desc_limit = $settings['wpc_desc_limit'];
		$title_link_show = $settings['title_link_show'];
		$wpc_cart_button_show = $settings['wpc_cart_button_show'];

		echo do_shortcode("[wpc_reservation_with_food {$food_template} form_style={$form_style} style={$food_menu_style} wpc_menu_order={$wpc_menu_order} no_of_product={$wpc_menu_count} wpc_food_categories ={$cats} wpc_show_desc={$wpc_show_desc} wpc_desc_limit={$wpc_desc_limit} title_link_show={$title_link_show} show_item_status={$show_item_status} product_thumbnail={$show_thumbnail} wpc_cart_button={$wpc_cart_button_show} ] ");


	}

	protected function get_menu_category() {
		return Wpc_Utilities::get_menu_category();
	}
}
