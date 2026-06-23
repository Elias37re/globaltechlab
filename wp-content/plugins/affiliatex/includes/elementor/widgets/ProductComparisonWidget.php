<?php
namespace AffiliateX\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use AffiliateX\Helpers\Elementor\WidgetHelper;
use Affiliatex\Traits\ProductComparisonRenderTrait;
use AffiliateX\Elementor\ControlsManager;
use AffiliateX\Blocks\AffiliateX_Customization_Helper;

/**
 * Product Comparison Widget Class
 *
 * @package AffiliateX\Elementor\Widgets
 */
class ProductComparisonWidget extends ElementorBase {

	use ProductComparisonRenderTrait;

	public function get_title() {
		return __( 'AffiliateX Product Comparison', 'affiliatex' );
	}

	public function get_icon() {
		return 'affx-icon-product-comparison';
	}

	public function get_keywords() {
		return array(
			'product',
			'comparison',
			'AffiliateX',
		);
	}

	protected function register_controls() {
		$hover_transition = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease;';
		$font_transition  = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, font-size .15s ease, letter-spacing .15s ease;';

		//
		// CONENT TAB
		//
		/**************************************************************
		 * General Settings
		 */
		$this->start_controls_section(
			'affx_pc_general_settings',
			array(
				'label' => __( 'General Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pcRibbon',
			array(
				'label'        => __( 'Enable Ribbon', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcImage',
			array(
				'label'        => __( 'Enable Images', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcTitle',
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
			'pcPrice',
			array(
				'label'        => __( 'Enable Prices', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcRating',
			array(
				'label'        => __( 'Enable Ratings', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcTitleColumn',
			array(
				'label'        => __( 'Enable Title Column', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'matchCardHeights',
			array(
				'label'        => __( 'Match Card Heights', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'false',
				'description'  => __( 'Make all product cards the same height and align buttons at the bottom.', 'affiliatex' ),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Title Settings
		 */
		$this->start_controls_section(
			'affx_pc_title_settings',
			array(
				'label'     => __( 'Title Settings', 'affiliatex' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_control(
			'pcTitleTag',
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
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_control(
			'pcTitleAlign',
			array(
				'label'       => __( 'Title Alignment', 'affiliatex' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'affiliatex' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'affiliatex' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'affiliatex' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'     => 'center',
				'toggle'      => false,
				'render_type' => 'template',
				'selectors'   => array(
					$this->select_element( 'product-title' ) => 'text-align: {{VALUE}};',
				),
				'condition'   => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_visibility_controls( 'affx_pc_title' );

		$this->end_controls_section();

		/**************************************************************
		 * Button Settings
		 */
		$this->start_controls_section(
			'affx_button_settings_section',
			array(
				'label' => __( 'Button Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pcButton',
			array(
				'label'        => __( 'Enable Button', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcButtonIcon',
			array(
				'label'        => __( 'Enable Icon', 'affiliatex' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonIcon',
			array(
				'label'     => __( 'Button Icon', 'affiliatex' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'pcButton'     => 'true',
					'pcButtonIcon' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonIconAlign',
			array(
				'label'     => __( 'Icon Alignment', 'affiliatex' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => __( 'Left', 'affiliatex' ),
						'icon'  => 'fa fa-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'affiliatex' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'right',
				'toggle'    => false,
				'condition' => array(
					'pcButton'     => 'true',
					'pcButtonIcon' => 'true',
				),
			)
		);

		$this->add_visibility_controls( 'affx_pc_button' );

		$this->end_controls_section();

		/**************************************************************
		 * Products
		 */
		$this->start_controls_section(
			'affx_pc_products_section',
			array(
				'label' => __( 'Products Settings', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'productComparisonTable',
			array(
				'label'       => __( 'Products', 'affiliatex' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ title }}}',
				'fields'      => array(
					array(
						'name'          => 'title',
						'label'         => __( 'Title', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'productComparisonTable',
						'default'       => __( 'Product Title', 'affiliatex' ),
					),
					array(
						'name'    => 'productImageType',
						'label'   => __( 'Image Source', 'affiliatex' ),
						'type'    => Controls_Manager::CHOOSE,
						'default' => 'default',
						'options' => array(
							'default'  => array(
								'title' => __( 'Upload', 'affiliatex' ),
								'icon'  => 'eicon-kit-upload',
							),
							'external' => array(
								'title' => __( 'External', 'affiliatex' ),
								'icon'  => 'eicon-external-link-square',
							),
						),
						'toggle'  => false,
					),
					array(
						'name'      => 'imageUrl',
						'label'     => __( 'Image', 'affiliatex' ),
						'type'      => Controls_Manager::MEDIA,
						'default'   => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'condition' => array(
							'productImageType' => 'default',
						),
					),
					array(
						'name'          => 'productImageExternal',
						'label'         => __( 'External Image URL', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'productComparisonTable',
						'condition'     => array(
							'productImageType' => 'external',
						),
					),
					array(
						'name'  => 'ribbonText',
						'label' => __( 'Ribbon Text', 'affiliatex' ),
						'type'  => Controls_Manager::TEXT,
					),
					array(
						'name'          => 'price',
						'label'         => __( 'Price', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'default'       => '$59.00',
						'repeater_name' => 'productComparisonTable',
					),
					array(
						'name'    => 'rating',
						'label'   => __( 'Rating', 'affiliatex' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 4,
						'options' => array(
							1 => 1,
							2 => 2,
							3 => 3,
							4 => 4,
							5 => 5,
						),
					),
					array(
						'name'    => 'button',
						'label'   => __( 'Button Text', 'affiliatex' ),
						'type'    => Controls_Manager::TEXT,
						'default' => 'Buy Now',
					),
					array(
						'name'          => 'buttonURL',
						'label'         => __( 'Button URL', 'affiliatex' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'productComparisonTable',
					),
					array(
						'name'         => 'btnOpenInNewTab',
						'label'        => esc_html__( 'Open Link In New Tab', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					array(
						'name'         => 'btnRelNoFollow',
						'label'        => esc_html__( 'Add Rel="Nofollow"?', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					array(
						'name'         => 'btnRelSponsored',
						'label'        => esc_html__( 'Add Rel="Sponsored"?', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					array(
						'name'         => 'btnDownload',
						'label'        => esc_html__( 'Download Button', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
				),
				'default'     => array(
					array(
						'title'      => __( 'Product Title', 'affiliatex' ),
						'imageUrl'   => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'ribbonText' => 'Our Pick',
						'price'      => '$59.00',
						'rating'     => 4,
						'button'     => 'Buy Now',
						'buttonURL'  => '',
					),
					array(
						'title'      => __( 'Product Title', 'affiliatex' ),
						'imageUrl'   => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'ribbonText' => '',
						'price'      => '$59.00',
						'rating'     => 4,
						'button'     => 'Buy Now',
						'buttonURL'  => '',
					),
				),
			)
		);

		$specsRepeater = new \Elementor\Repeater();

		$specsRepeater->add_control(
			'spec',
			array(
				'label'               => __( 'Text', 'affiliatex' ),
				'type'                => ControlsManager::TEXT,
				'default'             => 'Specification',
				'repeater_name'       => 'comparisonSpecs',
				'inner_repeater_name' => 'inner_rows',
			)
		);

		$rowsRepeater = new \Elementor\Repeater();

		$rowsRepeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'affiliatex' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Title',
			)
		);

		$rowsRepeater->add_control(
			'inner_rows',
			array(
				'label'       => __( 'Specifications', 'affiliatex' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'title_field' => '{{{ spec }}}',
				'fields'      => $specsRepeater->get_controls(),
				'default'     => array(
					array(
						'spec' => __( 'Specification', 'affiliatex' ),
					),
					array(
						'spec' => __( 'Specification', 'affiliatex' ),
					),
				),
			)
		);

		// Add the outer repeater to the main control
		$this->add_control(
			'comparisonSpecs',
			array(
				'label'       => __( 'Comparision Rows', 'affiliatex' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $rowsRepeater->get_controls(),
				'title_field' => '{{ title }}',
				'classes'     => 'affx-nested-repeater',
				'default'     => array(
					array(
						'row_label'  => __( 'Title', 'affiliatex' ),
						'inner_rows' => array(
							array(
								'spec' => __( 'Specification', 'affiliatex' ),
							),
							array(
								'spec' => __( 'Specification', 'affiliatex' ),
							),
						),
					),
				),
			)
		);

		// Amazon Attributes Configuration
		$this->add_control(
			'amazonAttributes',
			array(
				'type'    => Controls_Manager::HIDDEN,
				'default' => array(
					array(
						'field'      => 'title',
						'blockField' => array(
							'name'         => 'title',
							'type'         => 'text',
							'repeaterName' => 'productComparisonTable',
							'defaults'     => array(
								'title' => __( 'Product Title', 'affiliatex' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'field'      => 'display_price',
						'blockField' => array(
							'name'         => 'price',
							'type'         => 'text',
							'repeaterName' => 'productComparisonTable',
							'defaults'     => array(
								'price' => '$59.00',
							),
							'conditions'   => array(
								'pcPrice' => 'true',
							),
						),
						'type'       => 'text',
					),
					array(
						'field'      => 'images',
						'blockField' => array(
							'name'         => 'productImageExternal',
							'type'         => 'image',
							'repeaterName' => 'productComparisonTable',
							'defaults'     => array(
								'productImageExternal' => '',
							),
							'conditions'   => array(
								'pcImage' => 'true',
							),
						),
						'type'       => 'image',
					),
					array(
						'field'      => 'url',
						'blockField' => array(
							'name'         => 'buttonURL',
							'type'         => 'link',
							'repeaterName' => 'productComparisonTable',
							'defaults'     => array(
								'buttonURL' => '',
							),
							'conditions'   => array(
								'pcButton' => 'true',
							),
						),
						'type'       => 'link',
					),
					array(
						'field'      => 'features',
						'blockField' => array(
							'name'              => 'spec',
							'type'              => 'text',
							'repeaterName'      => 'comparisonSpecs',
							'innerRepeaterName' => 'inner_rows',
							'defaults'          => array(
								'spec' => __( 'Specification', 'affiliatex' ),
							),
						),
						'type'       => 'text',
					),
				),
			)
		);

		$this->end_controls_section();

		//
		// STYLE TAB
		//
		/**************************************************************
		 * Border Settings
		 */
		$this->start_controls_section(
			'affx_pc_border_section',
			array(
				'label' => __( 'Border', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_pc_border_tabs' );

		$this->start_controls_tab(
			'affx_pc_border_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'border',
				'label'          => __( 'Border', 'affiliatex' ),
				'responsive'     => true,
				'selector'       => WidgetHelper::select_multiple_elements(
					array(
						$this->select_element( 'container' ),
						$this->select_element( 'table-headings' ),
						$this->select_element( 'table-cells' ),
					)
				),
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
			'borderRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( 'container' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'boxShadow',
				'selector' => $this->select_element( 'wrapper' ),
				'label'    => __( 'Box Shadow', 'affiliatex' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_pc_border_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'hoverBorder',
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
			'hoverBorderRadius',
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
				'name'           => 'hoverShadow',
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
		 * Color Settings Section
		 */
		$this->start_controls_section(
			'affx_pc_colors_section',
			array(
				'label' => __( 'Colors', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_pc_colors_tabs' );

		$this->start_controls_tab(
			'affx_pc_colors_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_control(
			'ribbonColor',
			array(
				'label'     => __( 'Ribbon Background Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F13A3A',
				'selectors' => array(
					$this->select_element( 'product-ribbon' ) => 'background-color: {{VALUE}};',
					$this->select_element( 'product-ribbon' ) . '::before' => 'background-color: {{VALUE}};',
					$this->select_element( 'product-ribbon' ) . '::after' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pcRibbon' => 'true',
				),
			)
		);

		$this->add_control(
			'ribbonTextColor',
			array(
				'label'     => __( 'Ribbon Text Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					$this->select_element( 'product-ribbon' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcRibbon' => 'true',
				),
			)
		);

		$this->add_control(
			'titleColor',
			array(
				'label'     => __( 'Title Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#262B33',
				'selectors' => array(
					$this->select_element( 'product-title' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_control(
			'priceColor',
			array(
				'label'     => __( 'Price Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#262B33',
				'selectors' => array(
					$this->select_element( 'product-price' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcPrice' => 'true',
				),
			)
		);

		$this->add_control(
			'starColor',
			array(
				'label'     => __( 'Star Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFB800',
				'condition' => array(
					'pcRating' => 'true',
				),
			)
		);

		$this->add_control(
			'starInactiveColor',
			array(
				'label'     => __( 'Inactive Star Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#A3ACBF',
				'condition' => array(
					'pcRating' => 'true',
				),
			)
		);

		$this->add_control(
			'tableRowBgColor',
			array(
				'label'     => __( 'Alternate Table Row Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F5F7FA',
				'selectors' => array(
					$this->select_element( 'table-alternate-row' ) => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'contentColor',
			array(
				'label'     => __( 'Content Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => AffiliateX_Customization_Helper::get_value( 'fontColor', '#292929' ),
				'selectors' => array(
					$this->select_element( 'product-content' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pcButtonColorsLabel',
			array(
				'label'     => esc_html__( 'Button', 'affiliatex' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'buttonTextColor',
			array(
				'label'     => __( 'Button Text Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					$this->select_element( 'button' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonBgColor',
			array(
				'label'     => __( 'Button Background Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => AffiliateX_Customization_Helper::get_value( 'btnColor', '#00B0B0' ),
				'selectors' => array(
					$this->select_element( 'button' ) => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'pcBackgroundLabel',
			array(
				'label'     => esc_html__( 'Background', 'affiliatex' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'bgColorSolid',
				'label'          => __( 'Background Color', 'affiliatex' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( 'container' ),
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
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
						'default' => '#FFFFFF',
						'label'   => __( 'Background Color', 'affiliatex' ),
					),
					'gradient'   => array(
						'default' => 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)',
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_pc_colors_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_control(
			'ribbonHoverColor',
			array(
				'label'     => __( 'Ribbon Background Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'product-ribbon', ':hover' ) ) => 'background: {{VALUE}};',
					$this->select_element( array( 'product-ribbon', ':hover::before' ) ) => 'background: {{VALUE}};',
					$this->select_element( array( 'product-ribbon', ':hover::after' ) ) => 'background: {{VALUE}};',
					$this->select_element( 'product-ribbon' ) => $hover_transition,
				),
				'condition' => array(
					'pcRibbon' => 'true',
				),
			)
		);

		$this->add_control(
			'ribbonTextHoverColor',
			array(
				'label'     => __( 'Ribbon Text Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'product-ribbon', ':hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( 'product-ribbon' ) => $hover_transition,
				),
				'condition' => array(
					'pcRibbon' => 'true',
				),
			)
		);

		$this->add_control(
			'titleHoverColor',
			array(
				'label'     => __( 'Title Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'product-title', ':hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( 'product-title' ) => $hover_transition,
				),
				'condition' => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_control(
			'priceHoverColor',
			array(
				'label'     => __( 'Price Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'product-price', ':hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( 'product-price' ) => $hover_transition,
				),
				'condition' => array(
					'pcPrice' => 'true',
				),
			)
		);

		$this->add_control(
			'contentHoverColor',
			array(
				'label'     => __( 'Content Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( array( 'table-cells', ':hover' ) ) => 'color: {{VALUE}};',
					$this->select_element( 'product-content' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'tableRowBgHoverColor',
			array(
				'label'     => __( 'Table Row Background Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .affx-versus-table-wrap .affx-product-versus-table tbody tr:hover td:not(.affx-specification-add-col):not(.affx-specification-remove-col)' => 'background: {{VALUE}};',
					$this->select_element( 'table-cells' ) => $hover_transition,
				),
			)
		);

		$this->add_control(
			'pcButtonHoverColorsLabel',
			array(
				'label'     => esc_html__( 'Button', 'affiliatex' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'buttonTextHoverColor',
			array(
				'label'     => __( 'Button Text Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					$this->select_element( 'button:hover' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonBgHoverColor',
			array(
				'label'     => __( 'Button Background Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => AffiliateX_Customization_Helper::get_value( 'btnHoverColor', '#00454A' ),
				'selectors' => array(
					$this->select_element( 'button:hover' ) => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'pcBgHoverLabel',
			array(
				'label'     => esc_html__( 'Background', 'affiliatex' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'bgHoverType',
				'label'          => __( 'Background Hover', 'affiliatex' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( array( 'container', ':hover' ) ),
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
		 * Typography Section
		 */
		$this->start_controls_section(
			'affx_pc_typography_section',
			array(
				'label' => __( 'Typography', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_pc_typography_tabs' );

		$this->start_controls_tab(
			'affx_pc_typography_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'titleTypography',
				'label'          => __( 'Title Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'product-title' ),
				'fields_options' => array(
					'typography'      => array(
						'default' => 'custom',
					),
					'font_family'     => array(
						'default' => AffiliateX_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight'     => array(
						'default' => '500',
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
				'condition'      => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'ribbonTypography',
				'label'          => __( 'Ribbon Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'product-ribbon' ),
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
							'size' => '13',
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
				'condition'      => array(
					'pcRibbon' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'priceTypography',
				'label'          => __( 'Pricing Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'product-price' ),
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
					'font_style'      => array(
						'default' => 'normal',
					),
					'font_size'       => array(
						'default' => array(
							'unit' => 'px',
							'size' => '20',
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
				'condition'      => array(
					'pcPrice' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'buttonTypography',
				'label'          => __( 'Button Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'button' ),
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
							'size' => '16',
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
				'condition'      => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'contentTypography',
				'label'          => __( 'Content Typography', 'affiliatex' ),
				'selector'       => $this->select_element( 'product-table' ),
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
			'affx_pc_typography_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'titleHoverTypography',
				'label'          => __( 'Title Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'product-title', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'product-title' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'product-title' ) => $font_transition,
						),
					),
				),
				'condition'      => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'ribbonHoverTypography',
				'label'          => __( 'Ribbon Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'product-ribbon', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'product-ribbon' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'product-ribbon' ) => $font_transition,
						),
					),
				),
				'condition'      => array(
					'pcRibbon' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'priceHoverTypography',
				'label'          => __( 'Price Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'product-price', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'product-price' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'product-price' ) => $font_transition,
						),
					),
				),
				'condition'      => array(
					'pcPrice' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'buttonHoverTypography',
				'label'          => __( 'Button Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'button', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'button' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'button' ) => $font_transition,
						),
					),
				),
				'condition'      => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'contentHoverTypography',
				'label'          => __( 'Content Hover Typography', 'affiliatex' ),
				'selector'       => $this->select_element( array( 'table-cells', ':hover' ) ),
				'fields_options' => array(
					'font_size'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
							$this->select_element( 'product-content' ) => $font_transition,
						),
					),
					'letter_spacing' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
							$this->select_element( 'product-content' ) => $font_transition,
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
			'affx_pc_spacing_section',
			array(
				'label' => __( 'Spacing', 'affiliatex' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'affx_pc_spacing_tabs' );

		$this->start_controls_tab(
			'affx_pc_spacing_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'imagePadding',
			array(
				'label'      => __( 'Image Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'product-image' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'condition'  => array(
					'pcImage' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => __( 'Margin', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'container' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					WidgetHelper::select_multiple_elements(
						array(
							$this->select_element( 'table-headings' ),
							$this->select_element( 'table-cells' ),
						)
					) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '24',
					'right'    => '24',
					'bottom'   => '24',
					'left'     => '24',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_pc_spacing_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_responsive_control(
			'imageHoverPadding',
			array(
				'label'      => __( 'Image Hover Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( array( 'product-image', ':hover' ) ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'product-image' ) => $hover_transition,
				),
				'condition'  => array(
					'pcImage' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'hoverMargin',
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
			'hoverPadding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( array( 'container', ':hover' ) ) . ' ' . $this->select_element( 'table-headings' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( array( 'container', ':hover' ) ) . ' ' . $this->select_element( 'table-cells' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'table-headings' ) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, padding .15s ease;',
					$this->select_element( 'table-cells' ) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, padding .15s ease;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**************************************************************
		 * Button Styling
		 */
		$this->start_controls_section(
			'affx_pc_button_style_section',
			array(
				'label'     => __( 'Button', 'affiliatex' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->start_controls_tabs( 'affx_pc_button_style_tabs' );

		$this->start_controls_tab(
			'affx_pc_button_style_tab_normal',
			array( 'label' => __( 'Normal', 'affiliatex' ) )
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'       => 'buttonBorder',
				'label'      => __( 'Button Border', 'affiliatex' ),
				'responsive' => true,
				'selector'   => $this->select_element( 'button' ),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'buttonRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'button' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'buttonShadow',
				'label'     => __( 'Box Shadow', 'affiliatex' ),
				'selector'  => $this->select_element( 'button' ),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'buttonPadding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'button' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '10',
					'right'    => '10',
					'bottom'   => '10',
					'left'     => '10',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'buttonMargin',
			array(
				'label'      => __( 'Margin', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( 'button' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'affx_pc_button_style_tab_hover',
			array( 'label' => __( 'Hover', 'affiliatex' ) )
		);

		$this->add_control(
			'buttonBorderHoverColor',
			array(
				'label'     => __( 'Border Hover Color', 'affiliatex' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( 'button:hover' ) => 'border-color: {{VALUE}};',
					$this->select_element( 'button' ) => $hover_transition,
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'buttonHoverBorder',
				'label'          => __( 'Button Hover Border', 'affiliatex' ),
				'selector'       => $this->select_element( 'button:hover' ),
				'fields_options' => array(
					'color' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'border-color: {{VALUE}};',
							$this->select_element( 'button' ) => $hover_transition,
						),
					),
				),
				'condition'      => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'buttonHoverBorderRadius',
			array(
				'label'      => __( 'Border Radius', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'button:hover' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'button' ) => $hover_transition,
				),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'buttonHoverShadow',
				'label'          => __( 'Box Shadow', 'affiliatex' ),
				'selector'       => $this->select_element( 'button:hover' ),
				'fields_options' => array(
					'box_shadow' => array(
						'selectors' => array(
							'{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
							$this->select_element( 'button' ) => $hover_transition,
						),
					),
				),
				'condition'      => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'buttonHoverPadding',
			array(
				'label'      => __( 'Padding', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'button:hover' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'button' ) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, padding .15s ease;',
				),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'buttonHoverMargin',
			array(
				'label'      => __( 'Margin', 'affiliatex' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( 'button:hover' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'button' ) => 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease, margin .15s ease;',
				),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
}
