<?php

namespace AffiliateX\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use AffiliateX\Helpers\Elementor\ChildHelper;
use AffiliateX\Traits\VerdictRenderTrait;
use AffiliateX\Elementor\ControlsManager;
use AffiliateX\Helpers\Elementor\WidgetHelper;
use AffiliateX\Blocks\AffiliateX_Customization_Helper;

/**
 * AffiliateX Single Product Elementor Widget
 *
 * @package AffiliateX
 */
class VerdictWidget extends ElementorBase {

	use VerdictRenderTrait;

	protected function get_child_slugs(): array {
		return array( 'buttons', 'pros-and-cons' );
	}

	public function get_title() {
		return __( 'AffiliateX Verdict', 'affiliatex' );
	}

	public function get_icon() {
		return 'affx-icon-verdict';
	}

	public function get_keywords() {
		return array(
			'verdict',
			'AffiliateX',
		);
	}

	protected function get_elements(): array {
		return array(
			'wrapper'  => 'affblk-verdict-wrapper',
			'layout-1' => 'verdict-layout-1',
			'layout-2' => 'verdict-layout-2',
			'title'    => 'verdict-title',
			'content'  => 'verdict-content',
		);
	}

	protected function register_controls() {
		$defaults = $this->get_fields();

		$hover_transition = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease;';
		$font_transition  = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, font-size .15s ease, letter-spacing .15s ease;';

		//
		// Content Tab
		//
		/**************************************************************
		 * Layout Settings
		 */
		$this->start_controls_section(
			'layout_settings_section',
			array(
				'label' => __( 'Layout Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'verdictLayout',
			array(
				'label'   => __( 'Verdict Layout', 'affiliatex' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $defaults['verdictLayout'],
				'options' => array(
					'layoutOne' => __( 'Layout One', 'affiliatex' ),
					'layoutTwo' => __( 'Layout Two', 'affiliatex' ),
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * General Settings
		 */
		$this->start_controls_section(
			'general_settings_section',
			array(
				'label' => __( 'General Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'verdictTitleTag',
			array(
				'label'   => __( 'Verdict Heading Tag', 'affiliatex' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $defaults['verdictTitleTag'],
				'options' => array(
					'h2' => __( 'Heading 2 (h2)', 'affiliatex' ),
					'h3' => __( 'Heading 3 (h3)', 'affiliatex' ),
					'h4' => __( 'Heading 4 (h4)', 'affiliatex' ),
					'h5' => __( 'Heading 5 (h5)', 'affiliatex' ),
					'h6' => __( 'Heading 6 (h6)', 'affiliatex' ),
					'p'  => __( 'Paragraph (p)', 'affiliatex' ),
				),
			)
		);

		$this->add_control(
			'verdictTitle',
			array(
				'label'   => __( 'Title', 'affiliatex' ),
				'type'    => ControlsManager::TEXT,
				'default' => $defaults['verdictTitle'],
			)
		);

		$this->add_visibility_controls( 'affx_verdict_title' );

		$this->add_control(
			'verdictContent',
			array(
				'label'   => __( 'Content', 'affiliatex' ),
				'type'    => ControlsManager::TEXTAREA,
				'default' => $defaults['verdictContent'],
			)
		);

		$this->add_visibility_controls( 'affx_verdict_content' );

		$this->add_control(
			'contentAlignment',
			array(
				'label'     => __( 'Content Alignment', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => $defaults['contentAlignment'],
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'affiliatex' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'affiliatex' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'affiliatex' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					$this->select_element( 'layout-2' ) => 'text-align: {{VALUE}}',
				),
				'condition' => array(
					'verdictLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_control(
			'edProsCons',
			array(
				'label'        => __( 'Show Pros and Cons', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => $defaults['edProsCons'] ? 'true' : 'false',
				'condition'    => array(
					'verdictLayout' => 'layoutOne',
				),
			)
		);

		$this->add_control(
			'edRatingsArrow',
			array(
				'label'        => __( 'Display Arrow', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => $defaults['edRatingsArrow'] ? 'true' : 'false',
				'condition'    => array(
					'verdictLayout' => 'layoutTwo',
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Rating Settings
		 */
		$this->start_controls_section(
			'rating_settings_section',
			array(
				'label'     => __( 'Rating Settings', 'affiliatex' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'verdictLayout' => 'layoutOne',
				),
			)
		);

		$this->add_control(
			'edverdictTotalScore',
			array(
				'label'        => esc_html__( 'Enable Score Rating', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => $defaults['edverdictTotalScore'] ? 'true' : 'false',
			)
		);

		$this->add_control(
			'verdictTotalScore',
			array(
				'label'      => esc_html__( 'Total Score', 'affiliatex' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => $defaults['verdictTotalScore'],
				),
				'condition'  => array(
					'edverdictTotalScore' => 'true',
				),
			)
		);

		$this->add_control(
			'ratingContent',
			array(
				'label'     => __( 'Rating Score Content', 'affiliatex' ),
				'type'      => ControlsManager::TEXT,
				'default'   => $defaults['ratingContent'],
				'condition' => array(
					'edverdictTotalScore' => 'true',
				),
			)
		);

		$this->add_control(
			'ratingAlignment',
			array(
				'label'     => __( 'Rating Alignment', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'row-reverse',
				'options'   => array(
					'row-reverse' => array(
						'title' => esc_html__( 'Left', 'affiliatex' ),
						'icon'  => 'eicon-text-align-left',
					),
					'row'         => array(
						'title' => esc_html__( 'Right', 'affiliatex' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( array( 'layout-1', ' .main-text-holder' ) ) => 'flex-direction: {{VALUE}} !important;',
				),
				'condition' => array(
					'edverdictTotalScore' => 'true',
				),
			)
		);

		$this->add_visibility_controls( 'affx_verdict_rating', array( 'edverdictTotalScore' => 'true' ) );

		$this->end_controls_section();

		//
		// Style Tab
		//
		/**************************************************************
		 * Border Settings.
		 */
		$this->start_controls_section(
			'border_settings_section',
			array(
				'label' => __( 'Border Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_verdict_border_tabs' );

		$this->start_controls_tab(
			'affx_verdict_border_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'verdictBorder',
				'responsive'     => true,
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'border' => array(
						'default' => $defaults['verdictBorder']['style'],
					),
					'color'  => array(
						'default' => $defaults['verdictBorder']['color']['color'],
					),
					'width'  => array(
						'default' => array(
							'isLinked' => 'true',
							'unit'     => 'px',
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'verdictBorderRadius',
			array(
				'label'      => esc_html__( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 0,
					'left'     => 0,
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
				'name'           => 'verdictBoxShadow',
				'selector'       => $this->select_element( 'wrapper' ),
				'label'          => __( 'Box Shadow', 'affiliatex' ),
				'fields_options' => array(
					'box_shadow_type' => array(
						'default' => '',
					),
					'box_shadow'      => array(
						'default' => array(
							'vertical'   => $defaults['verdictBoxShadow']['v_offset'],
							'horizontal' => $defaults['verdictBoxShadow']['h_offset'],
							'blur'       => $defaults['verdictBoxShadow']['blur'],
							'spread'     => $defaults['verdictBoxShadow']['spread'],
							'color'      => $defaults['verdictBoxShadow']['color']['color'],
							'inset'      => $defaults['verdictBoxShadow']['inset'],
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_verdict_border_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'verdictHoverBorder',
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
			'verdictHoverBorderRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					$this->select_element( array( 'wrapper', ':hover' ) ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'wrapper' ) => $hover_transition,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'verdictHoverShadow',
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
		 * Colors
		 */
		$this->start_controls_section(
			'affx_sp_style_general',
			array(
				'label' => __( 'Colors', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_verdict_colors_tabs' );

		$this->start_controls_tab(
			'affx_verdict_colors_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_control(
			'verdictTitleColor',
			array(
				'label'     => __( 'Title Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['verdictTitleColor'],
				'selectors' => array(
					$this->select_element( 'title' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'verdictContentColor',
			array(
				'label'     => __( 'Content Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => AffiliateX_Customization_Helper::get_value( 'fontColor', $defaults['verdictContentColor'] ),
				'selectors' => array(
					$this->select_element( 'content' ) => 'color: {{VALUE}}',
					$this->select_element( array( 'wrapper', ' .verdict-user-rating-wrapper' ) ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'scoreTextColor',
			array(
				'label'     => __( 'Score Text Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['scoreTextColor'],
				'selectors' => array(
					$this->select_element( array( 'layout-1', ' .num' ) ) => 'color: {{VALUE}}',
					$this->select_element( array( 'layout-1', ' .affx-verdict-rating-number' ) ) => 'color: {{VALUE}}',
					$this->select_element( array( 'layout-1', ' .rich-content' ) ) => 'color: {{VALUE}}',
				),
				'condition' => array(
					'verdictLayout'       => 'layoutOne',
					'edverdictTotalScore' => 'true',
				),
			)
		);

		$this->add_control(
			'scoreBgTopColor',
			array(
				'label'     => __( 'Score Top Background Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['scoreBgTopColor'],
				'selectors' => array(
					$this->select_element( 'layout-1' ) . ' .num' => 'background: {{VALUE}}',
				),
				'condition' => array(
					'verdictLayout'       => 'layoutOne',
					'edverdictTotalScore' => 'true',
				),
			)
		);

		$this->add_control(
			'scoreBgBotColor',
			array(
				'label'     => __( 'Score Bottom Background Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['scoreBgBotColor'],
				'selectors' => array(
					$this->select_element( array( 'layout-1', ' .rich-content' ) ) => 'background: {{VALUE}}',
					$this->select_element( array( 'layout-1', ' .rich-content::after' ) ) => 'border-top: 5px solid {{VALUE}}',
				),
				'condition' => array(
					'verdictLayout'       => 'layoutOne',
					'edverdictTotalScore' => 'true',
				),
			)
		);

		$this->add_control(
			'verdictArrowColor',
			array(
				'label'     => __( 'Arrow Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['verdictArrowColor'],
				'selectors' => array(
					$this->select_element(
						array( 'layout-2', '.display-arrow .affx-btn-inner .affiliatex-button::after' )
					) => 'background: {{VALUE}}',
				),
				'condition' => array(
					'verdictLayout'  => 'layoutTwo',
					'edRatingsArrow' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'verdictBackground',
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => $this->select_element( 'wrapper' ),
				'exclude'        => array( 'image' ),
				'fields_options' => array(
					'background'     => array(
						'default' => 'classic',
						'options' => array(
							'classic'  => array(
								'title' => esc_html__( 'Solid Color', 'affiliatex' ),
								'icon'  => 'eicon-paint-brush',
							),
							'gradient' => array(
								'title' => esc_html__( 'Gradient', 'affiliatex' ),
								'icon'  => 'eicon-barcode',
							),
						),
						'label'   => __( 'Verdict Background Type', 'affiliatex' ),
					),
					'color'          => array(
						'default' => $defaults['verdictBgColorSolid'],
						'label'   => __( 'Verdict Background Color', 'affiliatex' ),
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
			'affx_verdict_colors_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_control(
			'verdictTitleHoverColor',
			array(
				'label'     => __( 'Title Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'title', ':hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( 'title' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'verdictContentHoverColor',
			array(
				'label'     => __( 'Content Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'content', ':hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( 'content' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'scoreTextHoverColor',
			array(
				'label'     => __( 'Score Text Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'layout-1', ' .affx-verdict-rating-number:hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( array( 'layout-1', ' .affx-verdict-rating-number:hover .num' ) ) => 'color: {{VALUE}};',
					$this->select_element( array( 'layout-1', ' .affx-verdict-rating-number:hover .rich-content' ) ) => 'color: {{VALUE}};',
					$this->select_element( array( 'layout-1', ' .affx-verdict-rating-number' ) ) => $hover_transition,
					$this->select_element( array( 'layout-1', ' .num' ) ) => $hover_transition,
					$this->select_element( array( 'layout-1', ' .rich-content' ) ) => $hover_transition,
				),
				'condition' => array(
					'verdictLayout'       => 'layoutOne',
					'edverdictTotalScore' => 'true',
				),
			)
		);

		$this->add_control(
			'scoreBgTopHoverColor',
			array(
				'label'     => __( 'Score Top Background Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'layout-1', ' .affx-verdict-rating-number:hover .num' ) ) => 'background-color: {{VALUE}};',
					$this->select_element( array( 'layout-1', ' .num' ) ) => $hover_transition,
				),
				'condition' => array(
					'verdictLayout'       => 'layoutOne',
					'edverdictTotalScore' => 'true',
				),
			)
		);

		$this->add_control(
			'scoreBgBotHoverColor',
			array(
				'label'     => __( 'Score Bottom Background Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'layout-1', ' .affx-verdict-rating-number:hover .rich-content' ) ) => 'background-color: {{VALUE}};',
					$this->select_element( array( 'layout-1', ' .affx-verdict-rating-number:hover .rich-content::after' ) ) => 'border-top: 5px solid {{VALUE}};',
					$this->select_element( array( 'layout-1', ' .rich-content' ) ) => $hover_transition,
				),
				'condition' => array(
					'verdictLayout'       => 'layoutOne',
					'edverdictTotalScore' => 'true',
				),
			)
		);

		$this->add_control(
			'verdictArrowHoverColor',
			array(
				'label'     => __( 'Arrow Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element(
						array( 'layout-2', '.display-arrow .affx-btn-inner .affiliatex-button:hover::after' )
					) => 'background: {{VALUE}};',
					$this->select_element(
						array( 'layout-2', '.display-arrow .affx-btn-inner .affiliatex-button::after' )
					) => $hover_transition,
				),
				'condition' => array(
					'verdictLayout'  => 'layoutTwo',
					'edRatingsArrow' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'verdictBgHover',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( array( 'wrapper', ':hover' ) ),
				'fields_options' => array(
					'background' => array(
						'label'   => __( 'Background Hover Type', 'affiliatex' ),
						'options' => array(
							'classic'  => array(
								'title' => esc_html__( 'Solid Color', 'affiliatex' ),
								'icon'  => 'eicon-paint-brush',
							),
							'gradient' => array(
								'title' => esc_html__( 'Gradient', 'affiliatex' ),
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
		 * Typography
		 */
		$this->start_controls_section(
			'typography_settings_section',
			array(
				'label' => __( 'Typography', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_verdict_typography_tabs' );

		$this->start_controls_tab(
			'affx_verdict_typography_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'verdictTitleTypography',
				'label'          => __( 'Title Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'title' ),
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
							'size' => '24',
						),
					),
					'line_height'     => array(
						'default' => array(
							'unit' => 'custom',
							'size' => 1.5,
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
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'verdictContentTypography',
				'label'          => __( 'Content Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'content' ),
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
							'size' => '18',
						),
					),
					'line_height'     => array(
						'default' => array(
							'unit' => 'custom',
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
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_verdict_typography_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'verdictTitleHoverTypography',
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
				'name'           => 'verdictContentHoverTypography',
				'label'          => __( 'Content Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'content', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'content' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'content' ) => $font_transition,
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
			'spacing_section_settings',
			array(
				'label' => __( 'Spacing', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_verdict_spacing_tabs' );

		$this->start_controls_tab(
			'affx_verdict_spacing_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'verdictBoxPadding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'default'    => array(
					'unit'     => 'px',
					'top'      => '24',
					'right'    => '24',
					'bottom'   => '24',
					'left'     => '24',
					'isLinked' => false,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'verdictMargin',
			array(
				'label'      => __( 'Margin', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'default'    => array(
					'unit'     => 'px',
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '30',
					'left'     => '0',
					'isLinked' => false,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_verdict_spacing_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'verdictHoverPadding',
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

		$this->add_responsive_control(
			'verdictHoverMargin',
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
							'name'     => 'verdictTitle',
							'type'     => 'text',
							'defaults' => array(
								'verdictTitle' => $defaults['verdictTitle'],
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'     => 'verdictContent',
							'type'     => 'text',
							'defaults' => array(
								'verdictContent' => $defaults['verdictContent'],
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'       => 'pros_and_cons_child_prosListItemsAmazon',
							'type'       => 'list',
							'defaults'   => array(
								'pros_and_cons_child_prosContentType' => 'list',
								'pros_and_cons_child_prosListItemsAmazon' => '',
							),
							'conditions' => array(
								'pros_and_cons_child_prosContentType' => 'amazon',
							),
						),
						'type'       => 'list',
					),
					array(
						'blockField' => array(
							'name'       => 'pros_and_cons_child_consListItemsAmazon',
							'type'       => 'list',
							'defaults'   => array(
								'pros_and_cons_cons_child_contentType' => 'list',
								'pros_and_cons_cons_child_listItemsAmazon' => '',
							),
							'conditions' => array(
								'pros_and_cons_child_consContentType' => 'amazon',
							),
						),
						'type'       => 'list',
					),
					array(
						'field'      => 'display_price',
						'blockField' => array(
							'name'     => 'button_child_productPrice',
							'type'     => 'text',
							'defaults' => array(
								'button_child_productPrice' => '$145',
							),
						),
						'type'       => 'text',
					),
					array(
						'field'      => 'url',
						'blockField' => array(
							'name'     => 'button_child_buttonURL',
							'type'     => 'link',
							'defaults' => array(
								'button_child_buttonURL' => '',
							),
						),
						'type'       => 'link',
					),
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Child Button settings
		 */
		$child = new ChildHelper(
			$this,
			$this->get_button_elementor_fields(),
			self::$inner_button_config
		);

		$child->generate_fields();

		/**************************************************************
		 * Child Pros and Cons settings
		 */
		$pros_and_cons_widget = new ProsAndConsWidget();

		$controls = $pros_and_cons_widget->get_elementor_controls();

		$child = new ChildHelper(
			$this,
			$controls,
			self::$inner_pros_and_cons_config
		);

		$child->generate_fields();
	}

	/**
	 * Render for Elementor
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$attributes = $this->parse_attributes( $settings );
		$attributes = WidgetHelper::process_attributes( $attributes );
		$attributes = $this->map_visibility_attribute( $attributes, 'affx_verdict_title', 'titleHideOn' );
		$attributes = $this->map_visibility_attribute( $attributes, 'affx_verdict_content', 'contentHideOn' );
		$attributes = $this->map_visibility_attribute( $attributes, 'affx_verdict_rating', 'ratingHideOn' );

		extract( $attributes );

		$attributes['block_id']          = $this->get_id();
		$attributes['verdictTotalScore'] = isset( $attributes['verdictTotalScore']['size'] ) ? esc_html( $attributes['verdictTotalScore']['size'] ) : '';

		if ( 'layoutOne' === $verdictLayout ) {
			$child_attributes = ChildHelper::extract_attributes( $attributes, self::$inner_pros_and_cons_config );

			$pros_and_cons_widget = new ProsAndConsWidget();

			ob_start();
			$pros_and_cons_widget->render( $child_attributes );
			$inner_widget_content = ob_get_clean();
		} elseif ( 'layoutTwo' === $verdictLayout ) {
			$button_child = '';

			$child_attributes                      = ChildHelper::extract_attributes( $attributes, self::$inner_button_config );
			$child_attributes['parent_attributes'] = array( 'blockName' => 'verdict' );

			ob_start();
			$this->render_button( $child_attributes );
			$button_child = ob_get_clean();

			$inner_widget_content = $button_child;
		}

		echo wp_kses_post( $this->render_template( $attributes, $inner_widget_content ) );
	}
}
