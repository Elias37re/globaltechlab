<?php
namespace AffiliateX\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use AffiliateX\Traits\VersusLineRenderTrait;
use AffiliateX\Helpers\Elementor\WidgetHelper;
use AffiliateX\Elementor\ControlsManager;
use AffiliateX\Blocks\AffiliateX_Customization_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Versus Line Widget Class
 *
 * @package AffiliateX\Elementor\Widgets
 */
class VersusLineWidget extends ElementorBase {

	use VersusLineRenderTrait;

	public function get_title(): string {
		return __( 'AffiliateX Versus Line', 'affiliatex' );
	}

	public function get_icon(): string {
		return 'affx-icon-versus-line';
	}

	public function get_keywords() {
		return array(
			'Versus Line',
			'versus',
			'AffiliateX',
		);
	}

	public function get_elements(): array {
		return array(
			'wrapper'       => 'affx-versus-table-wrap',
			'title'         => 'affx-versus-table-wrap .affx-versus-title',
			'vs-icon'       => 'affx-versus-table-wrap .affx-vs-icon',
			'table-odd-row' => 'affx-product-versus-table tbody tr:nth-child(odd) td',
		);
	}

	protected function register_controls() {
		$defaults         = $this->get_fields();
		$hover_transition = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease;';
		$font_transition  = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, font-size .15s ease, letter-spacing .15s ease;';

		//
		// Content Tab
		//
		/**************************************************************
		 * Title Settings
		 */
		$this->start_controls_section(
			'affx_vl_title_settings',
			array(
				'label' => __( 'Title Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'versusTitleTag',
			array(
				'label'   => __( 'Title Tag', 'affiliatex' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'p'  => 'p',
				),
				'default' => $defaults['versusTitleTag'],
			)
		);

		$this->add_visibility_controls( 'affx_vl_title' );

		$this->end_controls_section();

		/**************************************************************
		 * Content Settings
		 */
		$this->start_controls_section(
			'affx_vl_content_settings',
			array(
				'label' => __( 'Content Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'vsLabel',
			array(
				'label'       => __( 'VS Label', 'affiliatex' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => $defaults['vsLabel'],
			)
		);

		$this->add_visibility_controls( 'affx_vl_vs' );

		$this->add_control(
			'versusTable',
			array(
				'label'       => __( 'Table Items', 'affiliatex' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ versusTitle }}}',
				'fields'      => array(
					array(
						'name'          => 'versusTitle',
						'label'         => __( 'Title', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'versusTable',
						'default'       => __( 'Title', 'affiliatex' ),
					),
					array(
						'name'          => 'versusSubTitle',
						'label'         => __( 'Subtitle', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'versusTable',
						'default'       => __( 'Subtitle', 'affiliatex' ),
					),
					array(
						'name'          => 'versusValue1',
						'label'         => __( 'Value 1', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'label_block'   => true,
						'repeater_name' => 'versusTable',
						'default'       => __( 'Value 1', 'affiliatex' ),
					),
					array(
						'name'          => 'versusValue2',
						'label'         => __( 'Value 2', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'versusTable',
						'default'       => __( 'Value 2', 'affiliatex' ),
					),
				),
				'default'     => array(
					array(
						'versusTitle'    => __( 'Title', 'affiliatex' ),
						'versusSubTitle' => __( 'Subtitle', 'affiliatex' ),
						'versusValue1'   => __( 'Value 1', 'affiliatex' ),
						'versusValue2'   => __( 'Value 2', 'affiliatex' ),
					),
				),
			)
		);

		$this->end_controls_section();

		//
		// Style Tab
		//
		/**************************************************************
		 * Border settings
		 */
		$this->start_controls_section(
			'affx_vl_border_settings',
			array(
				'label' => __( 'Border Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_vl_border_tabs' );

		$this->start_controls_tab(
			'affx_vl_border_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'border',
				'label'          => __( 'Border', 'affiliatex' ),
				'responsive'     => true,
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'border' => array(
						'default' => '',
					),
					'color'  => array(
						'default' => $defaults['border']['color']['color'],
					),
					'width'  => array(
						'default' => array(
							'isLinked' => true,
							'unit'     => 'px',
							'top'      => $defaults['border']['width'],
							'right'    => $defaults['border']['width'],
							'bottom'   => $defaults['border']['width'],
							'left'     => $defaults['border']['width'],
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'borderRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'boxShadow',
				'selector'       => $this->select_element( 'wrapper' ),
				'label'          => __( 'Box Shadow', 'affiliatex' ),
				'fields_options' => array(
					'box_shadow_type' => array(
						'default' => '',
					),
					'box_shadow'      => array(
						'default' => array(
							'vertical'   => $defaults['boxShadow']['v_offset'],
							'horizontal' => $defaults['boxShadow']['h_offset'],
							'blur'       => $defaults['boxShadow']['blur'],
							'spread'     => $defaults['boxShadow']['spread'],
							'color'      => $defaults['boxShadow']['color']['color'],
							'inset'      => $defaults['boxShadow']['inset'],
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_vl_border_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'versusHoverBorder',
				'label'          => __( 'Border', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'wrapper', ':hover' ) ),
				'fields_options' => array(
					'color' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'border-color: {{VALUE}};',
							$this->select_element( 'wrapper' ) => $hover_transition,
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'versusHoverBorderRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( array( 'wrapper', ':hover' ) ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'wrapper' ) => $hover_transition,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'versusHoverShadow',
				'label'          => __( 'Box Shadow', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'wrapper', ':hover' ) ),
				'fields_options' => array(
					'box_shadow' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
							$this->select_element( 'wrapper' ) => $hover_transition,
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**************************************************************
		 * Color settings
		 */
		$this->start_controls_section(
			'affx_vl_color_settings',
			array(
				'label' => __( 'Color Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_vl_colors_tabs' );

		$this->start_controls_tab(
			'affx_vl_colors_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_control(
			'vsTextColor',
			array(
				'label'     => __( 'VS Text Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['vsTextColor'],
				'selectors' => array(
					$this->select_element( 'vs-icon' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'vsBgColor',
			array(
				'label'     => __( 'VS Background Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['vsBgColor'],
				'selectors' => array(
					$this->select_element( 'vs-icon' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'contentColor',
			array(
				'label'     => __( 'Content Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => AffiliateX_Customization_Helper::get_value( 'fontColor', $defaults['contentColor'] ),
				'selectors' => array(
					$this->select_elements( array( 'wrapper', 'title' ) ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'versusRowColor',
			array(
				'label'     => __( 'Row Background Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['versusRowColor'],
				'selectors' => array(
					$this->select_element( 'table-odd-row' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'bgColorSolid',
				'label'          => __( 'Background', 'affiliatex' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'background'     => array(
						'label'       => __( 'Background Type', 'affiliatex' ),
						'default'     => 'classic',
						'options'     => array(
							'classic'  => array(
								'title' => __( 'Color', 'affiliatex' ),
								'icon'  => 'eicon-global-colors',
							),
							'gradient' => array(
								'title' => __( 'Gradient', 'affiliatex' ),
								'icon'  => 'eicon-barcode',
							),
						),
						'toggle'      => false,
						'render_type' => 'template',
					),
					'color'          => array(
						'label'     => __( 'Background Color', 'affiliatex' ),
						'default'   => $defaults['bgColorSolid'],
						'condition' => array(
							'background' => 'classic',
						),
					),
					'color_b'        => array(
						'default' => '#A9B8C3',
					),
					'color_b_stop'   => array(
						'default' => array(
							'unit' => '%',
							'size' => 60,
						),
					),
					'gradient_angle' => array(
						'default' => array(
							'unit' => 'deg',
							'size' => '135',
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_vl_colors_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_control(
			'vsTextHoverColor',
			array(
				'label'     => __( 'VS Text Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'vs-icon', ':hover' ) ) => 'color: {{VALUE}}',
					$this->select_element( 'vs-icon' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'vsBgHoverColor',
			array(
				'label'     => __( 'VS Background Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'vs-icon', ':hover' ) ) => 'background: {{VALUE}}',
					$this->select_element( 'vs-icon' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'contentHoverColor',
			array(
				'label'     => __( 'Content Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'wrapper', ':hover' ) ) => 'color: {{VALUE}}',
					$this->select_element( 'wrapper' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'versusRowHoverColor',
			array(
				'label'     => __( 'Row Background Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .affx-product-versus-table tbody tr:hover td' => 'background: {{VALUE}}',
					'{{WRAPPER}} .affx-product-versus-table tbody td' => $hover_transition,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'versusBgHover',
				'label'          => __( 'Background', 'affiliatex' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( array( 'wrapper', ':hover' ) ),
				'fields_options' => array(
					'background' => array(
						'label'   => __( 'Background Hover Type', 'affiliatex' ),
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
					),
					'color'      => array(
						'label'     => __( 'Background Hover Color', 'affiliatex' ),
						'selectors' => array(
							'{{SELECTOR}}' => 'background-color: {{VALUE}};',
							$this->select_element( 'wrapper' ) => $hover_transition,
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**************************************************************
		 * Typography settings
		 */
		$this->start_controls_section(
			'affx_vl_typography_settings',
			array(
				'label' => __( 'Typography Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_vl_typography_tabs' );

		$this->start_controls_tab(
			'affx_vl_typography_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'vsTypography',
				'label'          => __( 'VS Label Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'vs-icon' ),
				'fields_options' => array(
					'typography'  => array(
						'default' => 'yes',
					),
					'font_family' => array(
						'default' => AffiliateX_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight' => array(
						'default' => '400',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => '18',
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'custom',
							'size' => 1.65,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'versusTitleTypography',
				'label'          => __( 'Title Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'title' ),
				'fields_options' => array(
					'typography'  => array(
						'default' => 'yes',
					),
					'font_family' => array(
						'default' => AffiliateX_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight' => array(
						'default' => '400',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => '18',
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'custom',
							'size' => 1.65,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'versusContentTypography',
				'label'          => __( 'Content Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'typography'  => array(
						'default' => 'yes',
					),
					'font_family' => array(
						'default' => AffiliateX_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight' => array(
						'default' => '400',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => '18',
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'custom',
							'size' => 1.65,
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_vl_typography_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'vsHoverTypography',
				'label'          => __( 'VS Label Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'vs-icon', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'vs-icon' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'vs-icon' ) => $font_transition,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'versusTitleHoverTypography',
				'label'          => __( 'Title Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'title', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'title' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'title' ) => $font_transition,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'versusContentHoverTypography',
				'label'          => __( 'Content Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'wrapper', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'wrapper' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'wrapper' ) => $font_transition,
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**************************************************************
		 * Spacing
		 */
		$this->start_controls_section(
			'affx_vl_spacing_settings',
			array(
				'label' => __( 'Spacing Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_vl_spacing_tabs' );

		$this->start_controls_tab(
			'affx_vl_spacing_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => __( 'Margin', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '30',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,

				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_vl_spacing_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'versusHoverMargin',
			array(
				'label'      => __( 'Margin', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( array( 'wrapper', ':hover' ) ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'wrapper' ) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, margin .15s ease;',
				),
			)
		);

		$this->add_responsive_control(
			'versusHoverPadding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( array( 'wrapper', ':hover' ) ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'wrapper' ) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, padding .15s ease;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Amazon Attributes Configuration
		$this->add_control(
			'amazonAttributes',
			array(
				'type'    => Controls_Manager::HIDDEN,
				'default' => array(
					array(
						'field'      => 'title',
						'blockField' => array(
							'name'         => 'versusTitle',
							'type'         => 'text',
							'repeaterName' => 'versusTable',
							'defaults'     => array(
								'versusTitle' => __( 'Title', 'affiliatex' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'versusSubTitle',
							'type'         => 'text',
							'repeaterName' => 'versusTable',
							'defaults'     => array(
								'versusSubTitle' => __( 'Subtitle', 'affiliatex' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'versusValue1',
							'type'         => 'text',
							'repeaterName' => 'versusTable',
							'defaults'     => array(
								'versusValue1' => '$145',
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'versusValue2',
							'type'         => 'text',
							'repeaterName' => 'versusTable',
							'defaults'     => array(
								'versusValue2' => '$195',
							),
						),
						'type'       => 'text',
					),
				),
			)
		);

		$this->end_controls_section();
	}
}
