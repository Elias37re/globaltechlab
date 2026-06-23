<?php

namespace AffiliateX\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Utils;
use Elementor\Controls_Manager;
use AffiliateX\Traits\CtaRenderTrait;
use Elementor\Group_Control_Background;
use AffiliateX\Traits\ButtonRenderTrait;
use AffiliateX\Helpers\Elementor\ChildHelper;
use AffiliateX\Elementor\ControlsManager;
use AffiliateX\Blocks\AffiliateX_Customization_Helper;

/**
 * Cta Widget Class
 *
 * @package AffiliateX\Elementor\Widgets
 */
class CtaWidget extends ElementorBase {

	use CtaRenderTrait;
	use ButtonRenderTrait;

	protected function get_slug(): string {
		return 'cta';
	}

	protected function get_child_slugs(): array {
		return array( 'buttons' );
	}

	public function get_title() {
		return __( 'AffiliateX Call To Action', 'affiliatex' );
	}

	public function get_icon() {
		return 'affx-icon-cta';
	}

	public function get_keywords() {
		return array(
			'CTA',
			'affliatex',
		);
	}

	protected function register_controls() {
		$hover_transition = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease;';
		$font_transition  = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, font-size .15s ease, letter-spacing .15s ease;';

		//
		// CONTENT TAB
		//
		/**************************************************************
		 * Layout Settings
		 */
		$this->start_controls_section(
			'affx_cta_layout_settings',
			array(
				'label' => __( 'Layout Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ctaLayout',
			array(
				'label'   => __( 'Select Layout', 'affiliatex' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'layoutOne' => __( 'Layout One', 'affiliatex' ),
					'layoutTwo' => __( 'Layout Two', 'affiliatex' ),
				),
				'default' => 'layoutOne',
			)
		);

		$this->add_control(
			'ctaAlignment',
			array(
				'label'     => __( 'Alignment', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'toggle'    => false,
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
				'condition' => array(
					'ctaLayout' => 'layoutOne',
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * CTA Image
		 */
		$this->start_controls_section(
			'affx_cta_image_settings',
			array(
				'label'     => __( 'CTA Image', 'affiliatex' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_control(
			'imageType',
			array(
				'name'      => 'imageType',
				'label'     => __( 'Image Source', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'default',
				'options'   => array(
					'default'  => array(
						'title' => __( 'Upload', 'affiliatex' ),
						'icon'  => 'eicon-kit-upload',
					),
					'external' => array(
						'title' => __( 'External', 'affiliatex' ),
						'icon'  => 'eicon-external-link-square',
					),
				),
				'toggle'    => false,
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_control(
			'imgURL',
			array(
				'label'     => __( 'Image', 'affiliatex' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
					'imageType' => 'default',
				),
				'selectors' => array(
					$this->select_element( 'image' ) => 'background-image: url({{URL}})',
				),
			)
		);

		$this->add_control(
			'imageExternal',
			array(
				'label'     => __( 'External Image URL', 'affiliatex' ),
				'type'      => ControlsManager::TEXT,
				'classes'   => 'affx-cta-image-external',
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
					'imageType' => 'external',
				),
				'selectors' => array(
					$this->select_element( 'image' ) => 'background-image: url({{VALUE}})',
				),
			)
		);

		$this->add_control(
			'imagePosition',
			array(
				'label'     => __( 'Image Position', 'affiliatex' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'center center' => __( 'Center Center', 'affiliatex' ),
					'center left'   => __( 'Center Left', 'affiliatex' ),
					'center right'  => __( 'Center Right', 'affiliatex' ),
					'top center'    => __( 'Top Center', 'affiliatex' ),
					'top left'      => __( 'Top Left', 'affiliatex' ),
					'top right'     => __( 'Top Right', 'affiliatex' ),
					'bottom center' => __( 'Bottom Center', 'affiliatex' ),
					'bottom left'   => __( 'Bottom Left', 'affiliatex' ),
					'bottom right'  => __( 'Bottom Right', 'affiliatex' ),
				),
				'default'   => 'center center',
				'selectors' => array(
					$this->select_element( 'image' ) => 'background-position: {{VALUE}}; align-items: flex-end; background-repeat: no-repeat; background-size: cover; display: flex; flex: 0 0 50%; justify-content: flex-end;',
				),
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_control(
			'columnReverse',
			array(
				'label'        => __( 'Enable Column Reverse', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => '',
				'condition'    => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_visibility_controls( 'affx_cta_image', array( 'ctaLayout' => 'layoutTwo' ) );

		$this->end_controls_section();

		/**************************************************************
		 * Title Settings
		 */
		$this->start_controls_section(
			'affx_cta_title',
			array(
				'label' => __( 'Title Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ctaTitle',
			array(
				'label'       => esc_html__( 'Title', 'affiliatex' ),
				'type'        => ControlsManager::TEXT,
				'default'     => esc_html__( 'Call to Action Title.', 'affiliatex' ),
				'placeholder' => esc_html__( 'Type your title here', 'affiliatex' ),
			)
		);

		$this->add_control(
			'ctaTitleTag',
			array(
				'label'   => __( 'Title Heading Tag', 'affiliatex' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h2' => __( 'Heading 2 (h2)', 'affiliatex' ),
					'h3' => __( 'Heading 3 (h3)', 'affiliatex' ),
					'h4' => __( 'Heading 4 (h4)', 'affiliatex' ),
					'h5' => __( 'Heading 5 (h5)', 'affiliatex' ),
					'h6' => __( 'Heading 6 (h6)', 'affiliatex' ),
					'p'  => __( 'Paragraph (p)', 'affiliatex' ),

				),
				'default' => 'h2',
			)
		);

		$this->add_visibility_controls( 'affx_cta_title' );

		$this->end_controls_section();

		/**************************************************************
		 * Content Settings
		 */
		$this->start_controls_section(
			'affx_cta_content',
			array(
				'label' => __( 'Content Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ctaContent',
			array(
				'label'       => esc_html__( 'Description', 'affiliatex' ),
				'type'        => ControlsManager::TEXTAREA,
				'default'     => esc_html__( 'Start creating CTAs in seconds, and convert more of your visitors into leads.', 'affiliatex' ),
				'placeholder' => esc_html__( 'Type your description here', 'affiliatex' ),
				'rows'        => 4,
			)
		);

		$this->add_control(
			'contentAlignment',
			array(
				'label'     => __( 'Content Alignment', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'affiliatex' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'affiliatex' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'affiliatex' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'title' )   => 'text-align: {{VALUE}}',
					$this->select_element( 'content' ) => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_visibility_controls( 'affx_cta_content' );

		$this->end_controls_section();

		/**************************************************************
		 * Button Settings
		 */
		$this->start_controls_section(
			'affx_cta_button_settings',
			array(
				'label' => __( 'Button Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'edButtons',
			array(
				'label'        => __( 'Enable Buttons', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'edButtonTwo',
			array(
				'label'        => __( 'Enable Button Two', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'edButtons' => 'true',
				),
			)
		);

		$this->add_control(
			'ctaButtonAlignment',
			array(
				'label'     => __( 'Button Alignment', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'affiliatex' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'affiliatex' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'affiliatex' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'buttons' ) => 'display: flex; flex-wrap: wrap; width: 100%; justify-content: {{VALUE}};',
				),
				'condition' => array(
					'edButtons' => 'true',
				),
			)
		);

		$this->add_visibility_controls( 'affx_cta_buttons', array( 'edButtons' => 'true' ) );

		$this->end_controls_section();

		//
		// STYLE TAB
		//

		/**************************************************************
		 * Border Settings
		 */

		$this->start_controls_section(
			'affx_cta_border_settings',
			array(
				'label' => __( 'Border', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_cta_border_tabs' );

		$this->start_controls_tab(
			'affx_cta_border_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'           => 'ctaBorder',
				'label'          => __( 'Border', 'affiliatex' ),
				'responsive'     => true,
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'color'  => array(
						'default' => '#E6ECF7',
					),
					'width'  => array(
						'default' => array(
							'isLinked' => true,
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
			'ctaBorderRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'rem', 'em' ),
				'default'    => array(
					'top'    => 8,
					'right'  => 8,
					'bottom' => 8,
					'left'   => 8,
					'unit'   => 'px',
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'ctaBoxShadow',
				'label'    => __( 'Box Shadow', 'affiliatex' ),
				'selector' => $this->select_element( 'wrapper' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_cta_border_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'           => 'ctaHoverBorder',
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
			'ctaHoverBorderRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'rem', 'em' ),
				'selectors'  => array(
					$this->select_element( array( 'wrapper', ':hover' ) ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'wrapper' ) => $hover_transition,
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'ctaHoverShadow',
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
		 * Color Settings
		 */
		$this->start_controls_section(
			'affx_cta_colors_section',
			array(
				'label' => __( 'Colors', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_cta_colors_tabs' );

		$this->start_controls_tab(
			'affx_cta_colors_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_control(
			'ctaTitleColor',
			array(
				'label'     => __( 'Title Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#262b33',
				'selectors' => array(
					$this->select_element( 'title' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ctaTextColor',
			array(
				'label'     => __( 'Text Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => AffiliateX_Customization_Helper::get_value( 'fontColor', '#292929' ),
				'selectors' => array(
					$this->select_element( 'content' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ctaLayout2BGType',
			array(
				'label'     => esc_html__( 'Background Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					$this->select_element( 'wrapper' ) => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'ctaBGType',
				'label'          => __( 'Background', 'affiliatex' ),
				'types'          => array( 'classic', 'image' ),
				'exclude'        => array( 'gradient' ),
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'background'       => array(
						'label'       => __( 'Background Type', 'affiliatex' ),
						'default'     => 'classic',
						'options'     => array(
							'classic' => array(
								'title' => __( 'Color', 'affiliatex' ),
								'icon'  => 'eicon-global-colors',
							),
							'image'   => array(
								'title' => __( 'Image', 'affiliatex' ),
								'icon'  => 'eicon-e-image',
							),
						),
						'toggle'      => false,
						'render_type' => 'template',
					),
					'color'            => array(
						'label'     => __( 'Background Color', 'affiliatex' ),
						'default'   => '#fff',
						'condition' => array(
							'background' => 'classic',
						),
					),
					'image'            => array(
						'label'     => __( 'Background Image', 'affiliatex' ),
						'condition' => array(
							'background' => 'image',
						),
					),
					'position'         => array(
						'default'   => 'center center',
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
						),
					),
					'xpos'             => array(
						'condition' => array(
							'background'  => array( 'image' ),
							'position'    => array( 'initial' ),
							'image[url]!' => '',
						),
					),
					'ypos'             => array(
						'condition' => array(
							'background'  => array( 'image' ),
							'position'    => array( 'initial' ),
							'image[url]!' => '',
						),
					),
					'attachment'       => array(
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
						),
					),
					'attachment_alert' => array(
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
							'attachment'  => 'fixed',
						),
					),
					'repeat'           => array(
						'default'   => 'no-repeat',
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
						),
					),
					'size'             => array(
						'default'   => 'cover',
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
						),
					),
					'bg_width'         => array(
						'condition' => array(
							'background'  => 'image',
							'size'        => 'initial',
							'image[url]!' => '',
						),
					),
				),
				'condition'      => array(
					'ctaLayout' => 'layoutOne',
				),
			)
		);

		$this->add_control(
			'ctaExternalBgImage',
			array(
				'label'     => __( 'External Image URL', 'affiliatex' ),
				'type'      => ControlsManager::TEXT,
				'condition' => array(
					'ctaLayout'            => 'layoutOne',
					'ctaBGType_background' => 'image',
				),
			)
		);

		$this->add_control(
			'overlayOpacity',
			array(
				'label'      => esc_html__( 'Overlay Opacity', 'affiliatex' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 0.1,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) . '::before' => 'opacity: {{SIZE}};',
				),
				'condition'  => array(
					'ctaLayout'            => 'layoutOne',
					'ctaBGType_background' => 'image',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_cta_colors_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_control(
			'ctaTitleHoverColor',
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
			'ctaTextHoverColor',
			array(
				'label'     => __( 'Text Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'content', ':hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( 'content' ) => $hover_transition,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'ctaBgHover',
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( array( 'wrapper', '.bg-color:hover' ) ) . ', ' . $this->select_element( array( 'wrapper', '.layout-type-2:hover .content-wrapper' ) ),
				'fields_options' => array(
					'background' => array(
						'label'   => __( 'Background Hover Type', 'affiliatex' ),
						'options' => array(
							'classic'  => array(
								'title' => esc_html__( 'Color', 'affiliatex' ),
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
							$this->select_element( array( 'wrapper', '.bg-color' ) ) => $hover_transition,
							$this->select_element( array( 'wrapper', '.layout-type-2 .content-wrapper' ) ) => $hover_transition,
						),
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**************************************************************
		 * Typography Section
		 */
		$this->start_controls_section(
			'affx_cta_section_typography',
			array(
				'label' => __( 'Typography', 'affiliatex' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_cta_typography_tabs' );

		$this->start_controls_tab(
			'affx_cta_typography_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'ctaTitleTypography',
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
							'size' => 40,
						),
					),
					'line_height'     => array(
						'default' => array(
							'unit' => '',
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
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'ctaContentTypography',
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
							'size' => 18,
						),
					),
					'line_height'     => array(
						'default' => array(
							'unit' => '',
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

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_cta_typography_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'ctaTitleHoverTypography',
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
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'ctaContentHoverTypography',
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
		 * Spacing Section
		 */
		$this->start_controls_section(
			'affx_cta_section_spacing',
			array(
				'label' => __( 'Spacing', 'affiliatex' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_cta_spacing_tabs' );

		$this->start_controls_tab(
			'affx_cta_spacing_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'ctaBoxPadding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => '60',
					'right'    => '30',
					'bottom'   => '60',
					'left'     => '30',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) . '.layout-type-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'wrapper' ) . '.layout-type-2 .content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'ctaMargin',
			array(
				'label'      => __( 'Margin', 'affiliatex' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '30',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_cta_spacing_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'ctaHoverPadding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( array( 'wrapper', '.layout-type-1:hover' ) ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( array( 'wrapper', '.layout-type-2:hover .content-wrapper' ) ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'wrapper' ) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, padding .15s ease;',
					$this->select_element( array( 'wrapper', '.layout-type-2 .content-wrapper' ) ) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, padding .15s ease;',
				),
			)
		);

		$this->add_responsive_control(
			'ctaHoverMargin',
			array(
				'label'      => __( 'Margin', 'affiliatex' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
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
							'name'     => 'ctaTitle',
							'type'     => 'text',
							'defaults' => array(
								'ctaTitle' => esc_html__( 'Call to Action Title.', 'affiliatex' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'     => 'ctaContent',
							'type'     => 'text',
							'defaults' => array(
								'ctaContent' => esc_html__( 'Start creating CTAs in seconds, and convert more of your visitors into leads.', 'affiliatex' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'field'      => 'display_price',
						'blockField' => array(
							'name'       => 'button_child1_1_productPrice',
							'type'       => 'text',
							'defaults'   => array(
								'button_child1_1_productPrice' => '$145',
							),
							'conditions' => array(
								'button_child1_1_layoutStyle' => 'layout-type-2',
							),
						),
						'type'       => 'text',
					),
					array(
						'field'      => 'images',
						'blockField' => array(
							'name'       => 'imageExternal',
							'type'       => 'image',
							'defaults'   => array(
								'imageExternal' => '',
								'imageType'     => 'default',
							),
							'conditions' => array(
								'imageType' => 'external',
							),
						),
						'type'       => 'image',
					),
					array(
						'field'      => 'images',
						'blockField' => array(
							'name'     => 'ctaExternalBgImage',
							'type'     => 'image',
							'defaults' => array(
								'ctaExternalBgImage' => '',
							),
						),
						'type'       => 'image',
					),
					array(
						'field'      => 'url',
						'blockField' => array(
							'name'     => 'button_child1_1_buttonURL',
							'type'     => 'link',
							'defaults' => array(
								'button_child1_1_buttonURL' => '',
							),
						),
						'type'       => 'link',
					),
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Button 1 & 2 fields
		 */
		$child1 = new ChildHelper( $this, $this->get_button_elementor_fields(), self::$button1_config );
		$child2 = new ChildHelper( $this, $this->get_button_elementor_fields(), self::$button2_config );

		$child1->generate_fields();
		$child2->generate_fields();
	}
}
