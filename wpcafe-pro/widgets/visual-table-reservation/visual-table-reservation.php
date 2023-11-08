<?php

namespace WpCafe_Pro\Widgets;

defined( "ABSPATH" ) || exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use \WpCafe\Utils\Wpc_Utilities as Wpc_Utilities;

class Visual_Table_Reservation extends Widget_Base{

	/**
	 * Retrieve the widget name.
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wpc-pro-visual-table-form';
	}

	/**
	 * Retrieve the widget title.
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__('WPC Visual Table Reservation Pro', 'wpcafe-pro');
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
            'section_style_tab',
            [
                'label' => esc_html__( 'WPC visual table Pro', 'wpcafe-pro' ),
            ]
        );

        $this->add_control(
            'form_style',
            [
                'label' => esc_html__( 'Style', 'wpcafe-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('Style 1', 'wpcafe-pro'),
                ],
                'default' => '1',
            ]
        );

        $this->end_controls_section();

		

		// Start of event section 
		$this->start_controls_section(
			'label_tab',
			[
				'label' => esc_html__('Field Label', 'wpcafe-pro'),
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
			]
		);
		$this->add_control(
			'wpc_btn_link_color',
			[
				'label'         => esc_html__('Button Link color', 'wpcafe-pro'),
				'type'         => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}}  #wpc_book_table' => 'color: {{VALUE}};',
					'{{WRAPPER}} #wpc_cancel_request' => 'color: {{VALUE}};',
				],
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

		echo do_shortcode("[wpc_visual_reservation_form  form_style ='${form_style}'] ");
 
	}

	protected function get_menu_category() {
		return Wpc_Utilities::get_menu_category();
	}
}
