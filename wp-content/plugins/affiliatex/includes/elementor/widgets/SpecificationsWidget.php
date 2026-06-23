<?php
namespace AffiliateX\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use AffiliateX\Helpers\Elementor\WidgetHelper;
use AffiliateX\Traits\SpecificationsRenderTrait;
use AffiliateX\Elementor\ControlsManager;
use AffiliateX\Blocks\AffiliateX_Customization_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Specifications Widget Class
 *
 * @package AffiliateX\Elementor\Widgets
 */
class SpecificationsWidget extends ElementorBase {

	use SpecificationsRenderTrait;

	public function get_title(): string {
		return __( 'Affiliatex Specifications', 'affiliatex' );
	}

	public function get_icon(): string {
		return 'affx-icon-product-spec';
	}

	public function get_keywords() {
		return array(
			'specifications',
			'AffiliateX',
		);
	}

	protected function register_controls() {
		$hover_transition = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease;';
		$font_transition  = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, font-size .15s ease, letter-spacing .15s ease;';

		//
		// Content
		//
		/**************************************************************
		 * Layout settings
		 */
		$this->start_controls_section(
			'affx_specifications_layout_settings',
			array(
				'label' => __( 'Layout Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layoutStyle',
			array(
				'label'   => __( 'Layout Style', 'affiliatex' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'layout-1',
				'options' => array(
					'layout-1' => __( 'Layout 1', 'affiliatex' ),
					'layout-2' => __( 'Layout 2', 'affiliatex' ),
					'layout-3' => __( 'Layout 3', 'affiliatex' ),
				),
			)
		);

		$this->add_control(
			'specificationColumnWidth',
			array(
				'label'        => esc_html__( 'Table Column', 'affiliatex' ),
				'type'         => Controls_Manager::VISUAL_CHOICE,
				'label_block'  => true,
				'options'      => array(
					'style-one'   => array(
						'title' => esc_attr__( '33 | 66', 'affiliatex' ),
						'image' => AFFILIATEX_PLUGIN_URL . '/assets/icons/layout/33-66.svg',
					),
					'style-two'   => array(
						'title' => esc_attr__( '50 | 50', 'affiliatex' ),
						'image' => AFFILIATEX_PLUGIN_URL . '/assets/icons/layout/50-50.svg',
					),
					'style-three' => array(
						'title' => esc_attr__( '66 | 33', 'affiliatex' ),
						'image' => AFFILIATEX_PLUGIN_URL . '/assets/icons/layout/66-33.svg',
					),
				),
				'default'      => 'style-one',
				'columns'      => 3,
				'prefix_class' => 'col-width-',
				'render_type'  => 'template',
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Title Settings
		 */
		$this->start_controls_section(
			'affx_title_settings',
			array(
				'label' => __( 'Title Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'edSpecificationTitle',
			array(
				'label'        => __( 'Enable Title', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'specificationTitle',
			array(
				'label'   => __( 'Title', 'affiliatex' ),
				'type'    => ControlsManager::TEXT,
				'default' => __( 'Specifications', 'affiliatex' ),
			)
		);

		$this->add_control(
			'productTitleAlign',
			array(
				'label'     => __( 'Title Alignment', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
					'edSpecificationTitle' => 'true',
				),
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'title' ) => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationTitleTag',
			array(
				'label'     => __( 'Title Tag', 'affiliatex' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => array(
					'h2' => __( 'Heading 2 (h2)', 'affiliatex' ),
					'h3' => __( 'Heading 3 (h3)', 'affiliatex' ),
					'h4' => __( 'Heading 4 (h4)', 'affiliatex' ),
					'h5' => __( 'Heading 5 (h5)', 'affiliatex' ),
					'h6' => __( 'Heading 6 (h6)', 'affiliatex' ),
					'p'  => __( 'Paragraph (p)', 'affiliatex' ),
				),
				'condition' => array(
					'edSpecificationTitle' => 'true',
				),
			)
		);

		$this->add_visibility_controls( 'affx_spec_title', array( 'edSpecificationTitle' => 'true' ) );

		$this->end_controls_section();

		/**************************************************************
		 * Specifications
		 */
		$this->start_controls_section(
			'affx_specifications_settings',
			array(
				'label' => __( 'Specifications Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'specificationTable',
			array(
				'label'       => __( 'Specifications', 'affiliatex' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ specificationLabel }}}',
				'fields'      => array(
					array(
						'name'          => 'specificationLabel',
						'label'         => __( 'Label', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'specificationTable',
						'default'       => __( 'Specification Label', 'affiliatex' ),
					),
					array(
						'name'          => 'specificationValue',
						'label'         => __( 'Value', 'affiliatex' ),
						'type'          => ControlsManager::TEXTAREA,
						'repeater_name' => 'specificationTable',
						'default'       => __( 'Specification Value', 'affiliatex' ),
					),
				),
				'default'     => array(
					array(
						'specificationLabel' => __( 'Specification Label', 'affiliatex' ),
						'specificationValue' => __( 'Specification Value', 'affiliatex' ),
					),
				),
			)
		);

		$this->add_control(
			'specificationLabelAlign',
			array(
				'label'     => __( 'Label Alignment', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'label' ) => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationValueAlign',
			array(
				'label'     => __( 'Value Alignment', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'value' ) => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_visibility_controls( 'affx_spec_rows' );

		$this->end_controls_section();

		//
		// Style tab
		//
		/**************************************************************
		 * Border Settings
		 */
		$this->start_controls_section(
			'affx_specifications_border_settings',
			array(
				'label' => __( 'Border Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_spec_border_tabs' );

		$this->start_controls_tab(
			'affx_spec_border_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'specificationBorder',
				'label'          => __( 'Border', 'affiliatex' ),
				'responsive'     => true,
				'selector'       => $this->select_element( 'container' ),
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
			'specificationBorderRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( 'container' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'specificationBoxShadow',
				'selector' => $this->select_element( 'wrapper' ),
				'label'    => __( 'Box Shadow', 'affiliatex' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_spec_border_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'specificationHoverBorder',
				'label'          => __( 'Border', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'container', ':hover' ) ),
				'fields_options' => array(
					'color' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'border-color: {{VALUE}};',
							$this->select_element( 'container' ) => $hover_transition,
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'specificationHoverBorderRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( array( 'container', ':hover' ) ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'container' ) => $hover_transition,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'specificationHoverShadow',
				'label'          => __( 'Box Shadow', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'container', ':hover' ) ),
				'fields_options' => array(
					'box_shadow' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
							$this->select_element( 'container' ) => $hover_transition,
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
			'affx_specifications_colors',
			array(
				'label' => __( 'Colors', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_spec_colors_tabs' );

		$this->start_controls_tab(
			'affx_spec_colors_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_control(
			'specificationTitleColor',
			array(
				'label'     => __( 'Title Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#292929',
				'selectors' => array(
					$this->select_element( 'title' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationTitleBgColor',
			array(
				'label'     => __( 'Title Background Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					$this->select_element( 'table-heading' ) => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationLabelColor',
			array(
				'label'     => __( 'Label Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					$this->select_element( 'label' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationValueColor',
			array(
				'label'     => __( 'Value Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => AffiliateX_Customization_Helper::get_value( 'fontColor', '#292929' ),
				'selectors' => array(
					$this->select_element( 'value' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationRowColor',
			array(
				'label'     => __( 'Table Row Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F5F7FA',
				'selectors' => array(
					$this->select_element( 'table' ) . '.layout-2 .affx-spec-label' => 'background: {{VALUE}};',
					$this->select_element( 'table' ) . '.layout-3 tbody tr:nth-child(2n) td' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'layoutStyle' => array( 'layout-2', 'layout-3' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'specificationBgColorSolid',
				'label'          => __( 'Background', 'affiliatex' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( 'table' ),
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
						'label'     => __( 'Background Color', 'affiliatex' ),
						'default'   => '#fff',
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
			'affx_spec_colors_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_control(
			'specificationTitleHoverColor',
			array(
				'label'     => __( 'Title Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( 'table-heading' ) . ':hover .affx-specification-title' => 'color: {{VALUE}};',
					$this->select_element( 'title' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'specificationTitleBgHoverColor',
			array(
				'label'     => __( 'Title Background Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'table-heading', ':hover' ) ) => 'background-color: {{VALUE}};',
					$this->select_element( 'table-heading' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'specificationLabelHoverColor',
			array(
				'label'     => __( 'Label Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'label', ':hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( 'label' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'specificationValueHoverColor',
			array(
				'label'     => __( 'Value Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'value', ':hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( 'value' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'specificationRowHoverColor',
			array(
				'label'     => __( 'Table Row Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( 'table' ) . ' tbody tr:hover td' => 'background: {{VALUE}};',
					$this->select_element( 'table-cell' ) => $hover_transition,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'specificationBgHover',
				'label'          => __( 'Background', 'affiliatex' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( 'container' ) . ':hover .affx-specification-table',
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
							$this->select_element( 'table' ) => $hover_transition,
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
			'affx_specifications_typography',
			array(
				'label' => __( 'Typography', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_spec_typography_tabs' );

		$this->start_controls_tab(
			'affx_spec_typography_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'specificationTitleTypography',
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
				'name'           => 'specificationLabelTypography',
				'label'          => __( 'Label Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'label' ),
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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'specificationValueTypography',
				'label'          => __( 'Value Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'value' ),
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
			'affx_spec_typography_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'specificationTitleHoverTypography',
				'label'          => __( 'Title Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'table-heading' ) . ':hover .affx-specification-title',
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
				'name'           => 'specificationLabelHoverTypography',
				'label'          => __( 'Label Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'label', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'label' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'label' ) => $font_transition,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'specificationValueHoverTypography',
				'label'          => __( 'Value Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'value', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'value' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'value' ) => $font_transition,
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
			'affx_specifications_spacing',
			array(
				'label' => __( 'Spacing', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_spec_spacing_tabs' );

		$this->start_controls_tab(
			'affx_spec_spacing_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'specificationMargin',
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
			'specificationPadding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					WidgetHelper::select_multiple_elements(
						array(
							$this->select_element( 'table-cell' ),
							$this->select_element( 'table-heading' ),
						)
					) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '16',
					'right'    => '24',
					'bottom'   => '16',
					'left'     => '24',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_spec_spacing_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'specificationHoverMargin',
			array(
				'label'      => __( 'Margin', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( array( 'container', ':hover' ) ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'container' ) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, margin .15s ease;',
				),
			)
		);

		$this->add_responsive_control(
			'specificationHoverPadding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					WidgetHelper::select_multiple_elements(
						array(
							$this->select_element( 'container' ) . ':hover .affx-specification-table td',
							$this->select_element( 'container' ) . ':hover .affx-specification-table th',
						)
					) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					WidgetHelper::select_multiple_elements(
						array(
							$this->select_element( 'table-cell' ),
							$this->select_element( 'table-heading' ),
						)
					) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, padding .15s ease;',
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
							'name'     => 'specificationTitle',
							'type'     => 'text',
							'defaults' => array(
								'specificationTitle' => __( 'Specifications', 'affiliatex' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'specificationLabel',
							'type'         => 'text',
							'repeaterName' => 'specificationTable',
							'defaults'     => array(
								'specificationLabel' => __( 'Specification Label', 'affiliatex' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'specificationValue',
							'type'         => 'text',
							'repeaterName' => 'specificationTable',
							'defaults'     => array(
								'specificationValue' => __( 'Specification Value', 'affiliatex' ),
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
