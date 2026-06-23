<?php

namespace AffiliateX\Traits;

use AffiliateX\Elementor\ControlsManager;
use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\Elementor\WidgetHelper;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use AffiliateX\Blocks\AffiliateX_Customization_Helper;

/**
 * This trait is a channel for share rendering methods between Gutenberg and Elementor
 *
 * @package AffiliateX
 */
trait ButtonRenderTrait {

	/**
	 * Visibility switcher controls for a button element
	 *
	 * @param string $element Element key, e.g. 'icon'.
	 * @param array  $condition Display condition.
	 * @return array
	 */
	protected function get_button_visibility_controls( string $element, array $condition = array() ): array {
		$devices = array(
			'desktop' => __( 'Hide On Desktop', 'affiliatex' ),
			'tablet'  => __( 'Hide On Tablet', 'affiliatex' ),
			'mobile'  => __( 'Hide On Mobile', 'affiliatex' ),
		);

		$controls = array(
			"affx_btn_{$element}_visibility_heading" => array(
				'label'     => __( 'Visibility', 'affiliatex' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => $condition,
			),
		);

		foreach ( $devices as $device => $label ) {
			$controls[ "affx_btn_{$element}_hide_{$device}" ] = array(
				'label'        => $label,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => '',
				'condition'    => $condition,
			);
		}

		return $controls;
	}

	/**
	 * Map the visibility switchers to the shared hideOn attributes
	 *
	 * @param array $attributes Widget attributes.
	 * @return array
	 */
	protected function map_button_visibility_attributes( array $attributes ): array {
		$elements = array(
			'button'   => 'buttonHideOn',
			'icon'     => 'iconHideOn',
			'pricetag' => 'priceTagHideOn',
		);

		foreach ( $elements as $element => $attr_key ) {
			$attributes[ $attr_key ] = array(
				'desktop' => ! empty( $attributes[ "affx_btn_{$element}_hide_desktop" ] ),
				'tablet'  => ! empty( $attributes[ "affx_btn_{$element}_hide_tablet" ] ),
				'mobile'  => ! empty( $attributes[ "affx_btn_{$element}_hide_mobile" ] ),
			);
		}

		return $attributes;
	}

	/**
	 * Shared hover transition declaration for Elementor selectors
	 *
	 * @param array $extras Extra transitioned properties, e.g. 'font-size'.
	 * @return string
	 */
	protected function get_button_hover_transition( array $extras = array() ): string {
		$transition = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease';

		foreach ( $extras as $property ) {
			$transition .= ', ' . $property . ' .15s ease';
		}

		return $transition . ';';
	}

	/**
	 * Get the button Elementor fields
	 *
	 * @return array
	 */
	protected function get_button_elementor_fields(): array {
		$defaults         = $this->get_button_fields();
		$hover_transition = $this->get_button_hover_transition();
		$font_transition  = $this->get_button_hover_transition( array( 'font-size', 'letter-spacing' ) );

		$fields = array(
			'layout_settings'             => array(
				'label'  => __( 'Layout Settings', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'layoutStyle'      => array(
						'label'   => __( 'Layout Style', 'affiliatex' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'layout-type-1', // "Default Button" in Gutenberg
						'options' => array(
							'layout-type-1' => __( 'Default Button', 'affiliatex' ),
							'layout-type-2' => __( 'Price Button', 'affiliatex' ),
						),
					),
					'priceTagPosition' => array(
						'label'     => __( 'Price Tag Position', 'affiliatex' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'tagBtnright', // same default as Gutenberg
						'options'   => array(
							'tagBtnright' => __( 'Right', 'affiliatex' ),
							'tagBtnleft'  => __( 'Left', 'affiliatex' ),
						),
						'condition' => array(
							'layoutStyle' => 'layout-type-2', // only for Price Button
						),
					),
					'productPrice'     => array(
						'label'         => __( 'Product Price', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'amazon_button' => true,
						'default'       => '$145',
						'condition'     => array(
							'layoutStyle' => 'layout-type-2', // only for Price Button
						),
					),
				) + $this->get_button_visibility_controls( 'pricetag', array( 'layoutStyle' => 'layout-type-2' ) ),
			),
			'button_settings'             => array(
				'label'  => __( 'General Settings', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'button_label'       => array(
						'label'   => __( 'Button Label', 'affiliatex' ),
						'type'    => Controls_Manager::TEXT,
						'default' => 'Button',
					),
					'buttonLinkNotice'   => array(
						'type'       => \Elementor\Controls_Manager::ALERT,
						'alert_type' => 'warning',
						'content'    => esc_html__( 'Button link settings are disabled because the parent widget is set to be clickable. Configure the link in the parent widget\'s settings instead.', 'affiliatex' ),
					),
					'buttonURL'          => array(
						'label'         => __( 'Button URL', 'affiliatex' ),
						'label_block'   => true,
						'type'          => ControlsManager::TEXT,
						'placeholder'   => __( 'Paste URL or type to search', 'affiliatex' ),
						'input_type'    => 'url',
						'amazon_button' => true,
					),
					'btnRelNoFollow'     => array(
						'label'        => esc_html__( 'Add Rel="Nofollow"?', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					'btnRelSponsored'    => array(
						'label'        => esc_html__( 'Add Rel="Sponsored"?', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					'btnDownload'        => array(
						'label'        => esc_html__( 'Add Download Attribute', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					'openInNewTab'       => array(
						'label'        => esc_html__( 'Open Link In New Window', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					'buttonSize'         => array(
						'label'   => __( 'Size', 'affiliatex' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'medium',
						'options' => array(
							'small'  => __( 'Small', 'affiliatex' ),
							'medium' => __( 'Medium', 'affiliatex' ),
							'large'  => __( 'Large', 'affiliatex' ),
							'xlarge' => __( 'Extra Large', 'affiliatex' ),
						),
					),
					'buttonWidth'        => array(
						'label'   => __( 'Width', 'affiliatex' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'flexible',
						'options' => array(
							'fixed'    => __( 'Fixed', 'affiliatex' ),
							'flexible' => __( 'Flexible', 'affiliatex' ),
							'full'     => __( 'Full Width', 'affiliatex' ),
						),
					),
					'button_fixed_width' => array(
						'label'      => __( 'Fixed Width', 'affiliatex' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px' ),
						'range'      => array(
							'px' => array(
								'min'  => 50,
								'max'  => 500,
								'step' => 1,
							),
						),
						'default'    => array(
							'unit' => 'px',
							'size' => 200,
						),
						'condition'  => array(
							'buttonWidth!' => array( 'flexible', 'full' ),
						),
						'selectors'  => array(
							'{{WRAPPER}} .affiliatex-button' => 'width: {{SIZE}}{{UNIT}} !important;',
						),
					),
					'buttonAlignment'    => array(
						'label'     => __( 'Alignment', 'affiliatex' ),
						'type'      => Controls_Manager::CHOOSE,
						'toggle'    => true,
						'options'   => array(
							'flex-start' => array(
								'title' => __( 'Left', 'affiliatex' ),
								'icon'  => 'eicon-text-align-left',
							),
							'center'     => array(
								'title' => __( 'Center', 'affiliatex' ),
								'icon'  => 'eicon-text-align-center',
							),
							'flex-end'   => array(
								'title' => __( 'Right', 'affiliatex' ),
								'icon'  => 'eicon-text-align-right',
							),
						),
						'default'   => 'flex-start',
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner' => 'justify-content: {{VALUE}};',
						),
					),
				) + $this->get_button_visibility_controls( 'button' ),
			),
			'icon_settings'               => array(
				'label'  => __( 'Icon Settings', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'edButtonIcon' => array(
						'label'     => __( 'Enable Icon', 'affiliatex' ),
						'type'      => Controls_Manager::SWITCHER,
						'default'   => '',
						'label_on'  => __( 'Yes', 'affiliatex' ),
						'label_off' => __( 'No', 'affiliatex' ),
					),
					'ButtonIcon'   => array(
						'label'     => __( 'Icon', 'affiliatex' ),
						'type'      => Controls_Manager::ICONS,
						'default'   => array(
							'value'   => 'far fa-thumbs-up',
							'library' => 'regular',
						),
						'condition' => array(
							'edButtonIcon' => 'yes',
						),
					),
					'iconPosition' => array(
						'label'     => __( 'Icon Position', 'affiliatex' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'axBtnleft',
						'options'   => array(
							'axBtnleft'  => __( 'Left', 'affiliatex' ),
							'axBtnright' => __( 'Right', 'affiliatex' ),
						),
						'condition' => array(
							'edButtonIcon' => 'yes',
						),
					),
					'iconSize'     => array(
						'label'      => __( 'Icon Size', 'affiliatex' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px' ),
						'range'      => array(
							'px' => array(
								'min'  => 8,
								'max'  => 50,
								'step' => 1,
							),
						),
						'default'    => array(
							'unit' => 'px',
							'size' => 18,
						),
						'condition'  => array(
							'edButtonIcon' => 'yes',
						),
						'selectors'  => array(
							'{{WRAPPER}} .affiliatex-button .button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
						),
					),
				) + $this->get_button_visibility_controls( 'icon', array( 'edButtonIcon' => 'yes' ) ),
			),

			'border_settings_section'     => array(
				'label'  => __( 'Border', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'affx_btn_border_tabs'           => array( 'type' => 'tabs_start' ),
					'affx_btn_border_tab_normal'     => array(
						'type'  => 'tab_start',
						'label' => __( 'Normal', 'affiliatex' ),
					),
					'buttonBorder'                   => array(
						'label'      => __( 'Button Border', 'affiliatex' ),
						'type'       => Group_Control_Border::get_type(),
						'responsive' => true,
						'selector'   => '{{WRAPPER}} .affx-btn-inner a',
					),
					'buttonRadius'                   => array(
						'label'      => __( 'Border Radius', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em' ),
						'default'    => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'unit'     => 'px',
							'isLinked' => true,
						),
						'selectors'  => array(
							'{{WRAPPER}} .affx-btn-inner a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .affx-btn-inner a .price-tag' => '--border-top-right-radius: {{RIGHT}}{{UNIT}}; --border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
						),
					),
					'buttonShadow'                   => array(
						'label'    => __( 'Box Shadow', 'affiliatex' ),
						'type'     => Group_Control_Box_Shadow::get_type(),
						'selector' => '{{WRAPPER}} .affx-btn-inner a',
					),
					'affx_btn_border_tab_normal_end' => array( 'type' => 'tab_end' ),
					'affx_btn_border_tab_hover'      => array(
						'type'  => 'tab_start',
						'label' => __( 'Hover', 'affiliatex' ),
					),
					'buttonBorderHoverColor'         => array(
						'label'     => __( 'Border Hover Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner a:hover' => 'border-color: {{VALUE}};',
						),
					),
					'buttonHoverBorderStyle'         => array(
						'label'     => __( 'Border Style', 'affiliatex' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => '',
						'options'   => array(
							''       => __( 'Default', 'affiliatex' ),
							'solid'  => __( 'Solid', 'affiliatex' ),
							'dashed' => __( 'Dashed', 'affiliatex' ),
							'dotted' => __( 'Dotted', 'affiliatex' ),
							'double' => __( 'Double', 'affiliatex' ),
						),
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner a:hover' => 'border-style: {{VALUE}};',
							'{{WRAPPER}} .affiliatex-button' => $hover_transition,
						),
					),
					'buttonHoverBorderWidth'         => array(
						'label'      => __( 'Border Width', 'affiliatex' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px' ),
						'range'      => array(
							'px' => array(
								'min'  => 0,
								'max'  => 20,
								'step' => 1,
							),
						),
						'condition'  => array(
							'buttonHoverBorderStyle!' => '',
						),
						'selectors'  => array(
							'{{WRAPPER}} .affx-btn-inner a:hover' => 'border-width: {{SIZE}}{{UNIT}};',
						),
					),
					'buttonHoverBorderRadius'        => array(
						'label'      => __( 'Border Radius', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em' ),
						'selectors'  => array(
							'{{WRAPPER}} .affx-btn-inner a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .affiliatex-button' => $hover_transition,
						),
					),
					'buttonHoverShadow'              => array(
						'label'          => __( 'Box Shadow', 'affiliatex' ),
						'type'           => Group_Control_Box_Shadow::get_type(),
						'selector'       => '{{WRAPPER}} .affx-btn-inner a:hover',
						'fields_options' => array(
							'box_shadow' => array(
								'selectors' => array(
									'{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
									'{{WRAPPER}} .affiliatex-button' => $hover_transition,
								),
							),
						),
					),
					'affx_btn_border_tab_hover_end'  => array( 'type' => 'tab_end' ),
					'affx_btn_border_tabs_end'       => array( 'type' => 'tabs_end' ),
				),
			),

			'color_settings_section'      => array(
				'label'  => __( 'Colors', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'affx_btn_colors_tabs'           => array( 'type' => 'tabs_start' ),
					'affx_btn_colors_tab_normal'     => array(
						'type'  => 'tab_start',
						'label' => __( 'Normal', 'affiliatex' ),
					),
					'button_text_color'              => array(
						'label'     => __( 'Text Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => array(
							'{{WRAPPER}} .affiliatex-button' => 'color: {{VALUE}};',
						),
					),
					'buttonIconColor'                => array(
						'label'     => __( 'Icon Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'condition' => array(
							'edButtonIcon' => 'yes',
						),
						'selectors' => array(
							'{{WRAPPER}} .affiliatex-button .button-icon' => 'color: {{VALUE}};',
						),
					),
					'button_background'              => array(
						'label'          => __( 'Background', 'affiliatex' ),
						'type'           => Group_Control_Background::get_type(),
						'types'          => array( 'classic', 'gradient' ),
						'exclude'        => array( 'image' ),
						'selector'       => '{{WRAPPER}} .affiliatex-button',
						'fields_options' => array(
							'background'     => array(
								'label'   => __( 'Background Type', 'affiliatex' ),
								'default' => 'classic',
								'options' => array(
									'classic'  => array(
										'title' => __( 'Color', 'affiliatex' ),
										'icon'  => 'eicon-global-colors',
									),
									'gradient' => array(
										'title' => __( 'Gradient', 'affiliatex' ),
										'icon'  => 'eicon-barcode',
									),
								),
								'toggle'  => false,
							),
							'color'          => array(
								'label'   => __( 'Background Color', 'affiliatex' ),
								'default' => AffiliateX_Customization_Helper::get_value( 'btnColor', '#00B0B0' ),
							),
							'color_b'        => array(
								'default' => '#A9B8C3',
							),
							'color_b_stop'   => array(
								'default' => array(
									'unit' => '%',
									'size' => 100,
								),
							),
							'gradient_angle' => array(
								'default' => array(
									'unit' => 'deg',
									'size' => '135',
								),
							),
						),
					),
					'price_tag_colors_section'       => array(
						'label'     => __( 'Price Tag Colors', 'affiliatex' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => array(
							'layoutStyle' => 'layout-type-2',
						),
					),
					'priceTagTextColor'              => array(
						'label'     => __( 'Text Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#00B0B0',
						'condition' => array(
							'layoutStyle' => 'layout-type-2',
						),
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner a .price-tag' => 'color: {{VALUE}} !important;',
						),
					),
					'priceTagBgColor'                => array(
						'label'     => __( 'Background Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#FFFFFF',
						'condition' => array(
							'layoutStyle' => 'layout-type-2',
						),
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner a .price-tag'        => 'background-color: {{VALUE}} !important;',
							'{{WRAPPER}} .affx-btn-inner a .price-tag:before' => 'background-color: {{VALUE}} !important;',
						),
					),
					'affx_btn_colors_tab_normal_end' => array( 'type' => 'tab_end' ),
					'affx_btn_colors_tab_hover'      => array(
						'type'  => 'tab_start',
						'label' => __( 'Hover', 'affiliatex' ),
					),
					'button_hover_text_color'        => array(
						'label'     => __( 'Text Hover Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => array(
							'{{WRAPPER}} .affiliatex-button:hover' => 'color: {{VALUE}};',
						),
					),
					'buttonIconHoverColor'           => array(
						'label'     => __( 'Icon Hover Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'condition' => array(
							'edButtonIcon' => 'yes',
						),
						'selectors' => array(
							'{{WRAPPER}} .affiliatex-button:hover .button-icon' => 'color: {{VALUE}};',
						),
					),
					'button_hover_background'        => array(
						'label'          => __( 'Background', 'affiliatex' ),
						'type'           => Group_Control_Background::get_type(),
						'types'          => array( 'classic', 'gradient' ),
						'exclude'        => array( 'image' ),
						'selector'       => '{{WRAPPER}} .affiliatex-button:hover',
						'fields_options' => array(
							'background'     => array(
								'label'   => __( 'Background Hover Type', 'affiliatex' ),
								'default' => 'classic',
								'options' => array(
									'classic'  => array(
										'title' => __( 'Color', 'affiliatex' ),
										'icon'  => 'eicon-global-colors',
									),
									'gradient' => array(
										'title' => __( 'Gradient', 'affiliatex' ),
										'icon'  => 'eicon-barcode',
									),
								),
								'toggle'  => false,
							),
							'color'          => array(
								'label'     => __( 'Background Hover Color', 'affiliatex' ),
								'default'   => AffiliateX_Customization_Helper::get_value( 'btnHoverColor', '#00454A' ),
								'selectors' => array(
									'{{WRAPPER}} .affiliatex-button:hover' => 'background-color: {{VALUE}}; background-image: none;',
								),
							),
							'color_b'        => array(
								'default' => '#A9B8C3',
							),
							'color_b_stop'   => array(
								'default' => array(
									'unit' => '%',
									'size' => 100,
								),
							),
							'gradient_angle' => array(
								'default' => array(
									'unit' => 'deg',
									'size' => '135',
								),
							),
						),
					),
					'price_tag_hover_colors_section' => array(
						'label'     => __( 'Price Tag Colors', 'affiliatex' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => array(
							'layoutStyle' => 'layout-type-2',
						),
					),
					'priceTextHoverColor'            => array(
						'label'     => __( 'Text Hover Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'condition' => array(
							'layoutStyle' => 'layout-type-2',
						),
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner a:hover .price-tag' => 'color: {{VALUE}} !important;',
							'{{WRAPPER}} .affx-btn-inner a .price-tag' => $hover_transition,
						),
					),
					'priceBackgroundHoverColor'      => array(
						'label'     => __( 'Background Hover Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'condition' => array(
							'layoutStyle' => 'layout-type-2',
						),
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner a:hover .price-tag'        => 'background-color: {{VALUE}} !important;',
							'{{WRAPPER}} .affx-btn-inner a:hover .price-tag:before' => 'background-color: {{VALUE}} !important;',
							'{{WRAPPER}} .affx-btn-inner a .price-tag' => $hover_transition,
							'{{WRAPPER}} .affx-btn-inner a .price-tag:before' => $hover_transition,
						),
					),
					'affx_btn_colors_tab_hover_end'  => array( 'type' => 'tab_end' ),
					'affx_btn_colors_tabs_end'       => array( 'type' => 'tabs_end' ),
				),
			),

			'typography_settings_section' => array(
				'label'  => __( 'Typography', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'affx_btn_typography_tabs'           => array( 'type' => 'tabs_start' ),
					'affx_btn_typography_tab_normal'     => array(
						'type'  => 'tab_start',
						'label' => __( 'Normal', 'affiliatex' ),
					),
					'button_typography'                  => array(
						'label'          => __( 'Typography', 'affiliatex' ),
						'selector'       => '{{WRAPPER}} .affiliatex-button',
						'type'           => Group_Control_Typography::get_type(),
						'fields_options' => array(
							'typography'      => array(
								'default' => 'custom',
							),
							'font_family'     => array(
								'default' => AffiliateX_Customization_Helper::get_value( 'typography.family', '' ),
							),
							'font_weight'     => array(
								'default' => '400',
							),
							'font_size'       => array(
								'default' => array(
									'unit' => 'px',
									'size' => 18,
								),
							),
							'line_height'     => array(
								'default' => array(
									'unit' => '',
									'size' => 1.65,
								),
							),
							'letter_spacing'  => array(
								'default' => array(
									'unit' => 'em',
									'size' => 0,
								),
							),
							'text_transform'  => array(
								'default' => 'none',
							),
							'text_decoration' => array(
								'default' => 'none',
							),
						),
					),
					'affx_btn_typography_tab_normal_end' => array( 'type' => 'tab_end' ),
					'affx_btn_typography_tab_hover'      => array(
						'type'  => 'tab_start',
						'label' => __( 'Hover', 'affiliatex' ),
					),
					'buttonHoverTypography'              => array(
						'label'          => __( 'Hover Typography', 'affiliatex' ),
						'selector'       => '{{WRAPPER}} .affiliatex-button:hover',
						'type'           => Group_Control_Typography::get_type(),
						'fields_options' => array(
							'font_size'      => array(
								'selectors' => array(
									'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
									'{{WRAPPER}} .affiliatex-button' => $font_transition,
								),
							),
							'letter_spacing' => array(
								'selectors' => array(
									'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
									'{{WRAPPER}} .affiliatex-button' => $font_transition,
								),
							),
						),
					),
					'affx_btn_typography_tab_hover_end'  => array( 'type' => 'tab_end' ),
					'affx_btn_typography_tabs_end'       => array( 'type' => 'tabs_end' ),
				),
			),

			'spacing_settings_section'    => array(
				'label'  => __( 'Spacing', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'affx_btn_spacing_tabs'           => array( 'type' => 'tabs_start' ),
					'affx_btn_spacing_tab_normal'     => array(
						'type'  => 'tab_start',
						'label' => __( 'Normal', 'affiliatex' ),
					),
					'buttonPadding'                   => array(
						'label'      => __( 'Padding', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', 'em', '%' ),
						'selectors'  => array(
							'{{WRAPPER}} .affiliatex-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					'buttonMargin'                    => array(
						'label'      => __( 'Margin', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', 'em', '%' ),
						'selectors'  => array(
							'{{WRAPPER}} .affiliatex-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '30',
							'left'     => '0',
							'isLinked' => false,
						),
					),
					'affx_btn_spacing_tab_normal_end' => array( 'type' => 'tab_end' ),
					'affx_btn_spacing_tab_hover'      => array(
						'type'  => 'tab_start',
						'label' => __( 'Hover', 'affiliatex' ),
					),
					'buttonHoverPadding'              => array(
						'label'      => __( 'Padding', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', 'em', '%' ),
						'selectors'  => array(
							'{{WRAPPER}} .affiliatex-button:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .affiliatex-button' => $this->get_button_hover_transition( array( 'padding' ) ),
						),
					),
					'buttonHoverMargin'               => array(
						'label'      => __( 'Margin', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', 'em', '%' ),
						'selectors'  => array(
							'{{WRAPPER}} .affiliatex-button:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .affiliatex-button' => $this->get_button_hover_transition( array( 'margin' ) ),
						),
					),
					'affx_btn_spacing_tab_hover_end'  => array( 'type' => 'tab_end' ),
					'affx_btn_spacing_tabs_end'       => array( 'type' => 'tabs_end' ),
					'amazonAttributes'                => array(
						'type'    => Controls_Manager::HIDDEN,
						'default' => array(
							array(
								'field'      => 'display_price',
								'blockField' => array(
									'name'         => 'productPrice',
									'type'         => 'text',
									'format'       => 'price',
									'typeSelector' => 'layoutStyle',
									'defaults'     => array(
										'productPrice' => $defaults['productPrice'],
									),
									'conditions'   => array(
										'layoutStyle' => 'layout-type-2',
									),
								),
								'type'       => 'text',
							),
							array(
								'field'      => 'url',
								'blockField' => array(
									'name'     => 'buttonURL',
									'type'     => 'link',
									'defaults' => array(
										'buttonURL' => $defaults['buttonURL'],
									),
								),
								'type'       => 'link',
							),
						),
					),
				),
			),
		);

		return $fields;
	}

	/**
	 * Generate button class names based on attributes
	 *
	 * @param array $attributes Block attributes.
	 */
	protected function get_button_classes( $attributes ) {
		$classNames = array(
			'affiliatex-button',
			'btn-align-' . AffiliateX_Helpers::get_responsive_value( $attributes['buttonAlignment'] ?? 'flex-start' ),
			'btn-is-' . ( $attributes['buttonSize'] ?? 'medium' ),
			$attributes['buttonWidth'] === 'fixed' ? 'btn-is-fixed' : '',
			$attributes['buttonWidth'] === 'full' ? 'btn-is-fullw' : '',
			$attributes['buttonWidth'] === 'flexible' ? 'btn-is-flex-' . $attributes['buttonSize'] : '',
			$attributes['layoutStyle'] === 'layout-type-2' && $attributes['priceTagPosition'] === 'tagBtnleft' ? 'left-price-tag' : '',
			$attributes['layoutStyle'] === 'layout-type-2' && $attributes['priceTagPosition'] === 'tagBtnright' ? 'right-price-tag' : '',
			$attributes['edButtonIcon'] && $attributes['iconPosition'] === 'axBtnright' ? 'icon-right' : 'icon-left',
		);

		return array_filter( $classNames );
	}

	/**
	 * Prepare attributes for template
	 *
	 * @param array $attributes Block attributes.
	 */
	protected function prepare_template_attributes( $attributes ) {
		// Create wrapper attributes differently based on context
		if ( isset( $attributes['elementorLinkAttributes'] ) ) {
			// Elementor context
			$wrapper_attributes = sprintf(
				'class="affx-btn-wrapper" id="affiliatex-blocks-style-%s"',
				esc_attr( $attributes['block_id'] )
			);
		} else {
			// Gutenberg context
			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'class' => 'affx-btn-wrapper',
					'id'    => "affiliatex-blocks-style-{$attributes['block_id']}",
				)
			);

			$attributes['classNames'] = implode( ' ', $this->get_button_classes( $attributes ) );

			// Prepare Gutenberg-specific attributes.
			$rel = array( 'noopener' );

			if ( ! empty( $attributes['btnRelNoFollow'] ) ) {
				$rel[] = 'nofollow';
			}

			if ( ! empty( $attributes['btnRelSponsored'] ) ) {
				$rel[] = 'sponsored';
			}

			$attributes['rel'] = implode( ' ', $rel );

			$attributes['target']   = ! empty( $attributes['openInNewTab'] ) ? ' target="_blank"' : '';
			$attributes['download'] = ! empty( $attributes['btnDownload'] );

		}

		$attributes['buttonHideClass']   = AffiliateX_Helpers::get_responsive_hide_classes( $attributes['buttonHideOn'] ?? null );
		$attributes['priceTagHideClass'] = AffiliateX_Helpers::get_responsive_hide_classes( $attributes['priceTagHideOn'] ?? null );

		$icon_hide_class = AffiliateX_Helpers::get_responsive_hide_classes( $attributes['iconHideOn'] ?? null );
		$icon_classes    = 'button-icon ' . esc_attr( $attributes['ButtonIcon']['value'] ?? '' ) . ( '' !== $icon_hide_class ? ' ' . esc_attr( $icon_hide_class ) : '' );

		// Prepare icon HTML
		$attributes['iconLeft'] = ! empty( $attributes['edButtonIcon'] ) && $attributes['iconPosition'] === 'axBtnleft'
			? '<i class="' . $icon_classes . '"></i>'
			: '';

		$attributes['iconRight'] = ! empty( $attributes['edButtonIcon'] ) && $attributes['iconPosition'] === 'axBtnright'
			? '<i class="' . $icon_classes . '"></i>'
			: '';

		return array_merge( array( 'wrapper_attributes' => $wrapper_attributes ), $attributes );
	}

	protected function get_button_fields(): array {
		return array(
			'buttonLabel'      => 'Button',
			'buttonSize'       => 'medium',
			'buttonWidth'      => 'flexible',
			'buttonURL'        => '',
			'iconPosition'     => 'left',
			'block_id'         => '',
			'ButtonIcon'       => array(
				'name'  => 'thumb-up-simple',
				'value' => 'far fa-thumbs-up',
			),
			'edButtonIcon'     => false,
			'btnRelSponsored'  => false,
			'openInNewTab'     => false,
			'btnRelNoFollow'   => false,
			'buttonAlignment'  => 'flex-start',
			'btnDownload'      => false,
			'layoutStyle'      => 'layout-type-1',
			'priceTagPosition' => 'tagBtnright',
			'productPrice'     => '$145',
		);
	}

	/**
	 * Render function for Elementor
	 *
	 * @param array $settings
	 * @return void
	 */
	public function render_button( array $settings ): void {
		// Merge defaults with actual settings
		$settings = wp_parse_args( $settings, $this->get_button_fields() );
		$settings = WidgetHelper::process_attributes( $settings );
		$settings = $this->map_button_visibility_attributes( $settings );

		// Create unique button ID to prevent conflicts when multiple buttons are rendered
		$button_id = 'button-' . ( $settings['name'] ?? '' ) . '-' . $this->get_id() . '-' . uniqid();

		// Build all link attributes using Elementor's helper
		if ( isset( $settings['button_url'] ) ) {
			$this->add_link_attributes( $button_id, $settings['button_url'] );
		} elseif ( isset( $settings['buttonURL'] ) && ! empty( $settings['buttonURL'] ) ) {
			$this->add_render_attribute( $button_id, 'href', apply_filters( 'affiliatex_button_url', esc_url( do_shortcode( $settings['buttonURL'] ) ) ) );
		}

		// Add classes using the shared method
		$this->add_render_attribute( $button_id, 'class', $this->get_button_classes( $settings ) );

		// Add rel attributes if enabled.
		$rel = array();

		if ( isset( $settings['btnRelNoFollow'] ) && $settings['btnRelNoFollow'] ) {
			$rel[] = 'nofollow';
		}

		if ( isset( $settings['btnRelSponsored'] ) && $settings['btnRelSponsored'] ) {
			$rel[] = 'sponsored';
		}

		$this->add_render_attribute( $button_id, 'rel', implode( ' ', $rel ) );

		// Add _target attribute if open in new tab enabled.
		if ( isset( $settings['openInNewTab'] ) && $settings['openInNewTab'] ) {
			$this->add_render_attribute( $button_id, 'target', '_blank' );
		}

		// Add download attribute if enabled.
		if ( isset( $settings['btnDownload'] ) && $settings['btnDownload'] ) {
			$this->add_render_attribute( $button_id, 'download', '' );
		}

		// Convert Elementor icon to our format
		$icon_data = array(
			'name'  => 'thumb-up-simple',
			'value' => '',
		);

		if ( ! empty( $settings['ButtonIcon'] ) && ! empty( $settings['ButtonIcon']['value'] ) ) {
			$icon_data = array(
				'name'  => str_replace( array( 'fas ', 'far ', 'fab ', 'fa-' ), '', $settings['ButtonIcon']['value'] ),
				'value' => $settings['ButtonIcon']['value'],
			);
		}

		$attributes = array(
			'buttonLabel'             => $settings['button_label'] ?? $settings['buttonLabel'],
			'buttonSize'              => $settings['buttonSize'],
			'buttonWidth'             => $settings['buttonWidth'],
			'buttonFixedWidth'        => isset( $settings['button_fixed_width'] ) ? (int) $settings['button_fixed_width'] : 0,
			'buttonAlignment'         => $settings['buttonAlignment'],
			'layoutStyle'             => $settings['layoutStyle'],
			'priceTagPosition'        => $settings['priceTagPosition'],
			'productPrice'            => $settings['productPrice'],
			'edButtonIcon'            => ( $settings['edButtonIcon'] === 'yes' ),
			'ButtonIcon'              => $icon_data,
			'iconPosition'            => $settings['iconPosition'],
			'elementorLinkAttributes' => $this->get_render_attribute_string( $button_id ),
			'block_id'                => 'elementor-' . $this->get_id(),
			'parent_attributes'       => $settings['parent_attributes'] ?? array(),
			'buttonHideOn'            => $settings['buttonHideOn'],
			'iconHideOn'              => $settings['iconHideOn'],
			'priceTagHideOn'          => $settings['priceTagHideOn'],
		);

		echo wp_kses_post( $this->render_button_template( $attributes ) );
	}

	/**
	 * Main template rendering function, can be used by Gutenberg directly
	 *
	 * @param array $attributes
	 * @return string
	 */
	public function render_button_template( array $attributes ): string {
		$template_attributes = $this->prepare_template_attributes( $attributes );
		extract( $template_attributes );

		$parent_block_clickable = isset( $attributes['parent_attributes']['edFullBlockLink'] ) ? $attributes['parent_attributes']['edFullBlockLink'] : false;

		$tag = $parent_block_clickable ? 'div' : 'a';

		// Determine block type for analytics tracking
		$block_type   = $attributes['parent_attributes']['blockName'] ?? 'buttons';
		$resolved_url = do_shortcode( $buttonURL ?? '' );
		$track_attrs  = \AffiliateX\Analytics\AnalyticsHelper::get_tracking_attributes( $resolved_url, $block_type, 'button' );

		if ( isset( $elementorLinkAttributes ) ) {
			$link_attributes = $elementorLinkAttributes . ' ' . $track_attrs;
		} elseif ( $tag === 'a' ) {
			$link_attributes = sprintf(
				'href="%s" class="%s" rel="%s" %s %s %s',
				apply_filters( 'affiliatex_button_url', esc_url( $resolved_url ) ),
				esc_attr( $classNames ),
				esc_attr( $rel ),
				esc_html( $target ),
				esc_html( $download ? ' download' : '' ),
				$track_attrs
			);
		} else {
			$link_attributes = sprintf(
				'class="%s"',
				esc_attr( $classNames )
			);
		}

		ob_start();
		include AFFILIATEX_PLUGIN_DIR . '/templates/blocks/buttons.php';

		return ob_get_clean();
	}
}
