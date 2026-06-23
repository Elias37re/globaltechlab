<?php

namespace AffiliateX\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\Elementor\WidgetHelper;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use AffiliateX\Traits\ProsAndConsRenderTrait;
use AffiliateX\Elementor\ControlsManager;
use AffiliateX\Blocks\AffiliateX_Customization_Helper;

/**
 * AffiliateX Single Product Elementor Widget
 *
 * @package AffiliateX
 */
class ProsAndConsWidget extends ElementorBase {

	use ProsAndConsRenderTrait;

	public function get_title() {
		return __( 'AffiliateX Pros and Cons', 'affiliatex' );
	}

	public function get_icon() {
		return 'affx-icon-pros-cons';
	}

	public function get_keywords() {
		return array(
			'Pros and Cons',
			'Pros',
			'Cons',
			'AffiliateX',
		);
	}

	protected function get_elements(): array {
		return array(
			'wrapper'                  => 'affx-pros-cons-inner-wrapper',
			'layout-1-wrapper'         => 'affx-pros-cons-inner-wrapper.layout-type-1',
			'layout-2-wrapper'         => 'affx-pros-cons-inner-wrapper.layout-type-2',
			'layout-3-wrapper'         => 'affx-pros-cons-inner-wrapper.layout-type-3',
			'layout-4-wrapper'         => 'affx-pros-cons-inner-wrapper.layout-type-4',
			'pros-block'               => 'affiliatex-block-pros',
			'cons-block'               => 'affiliatex-block-cons',
			'layout-3-pros-title-icon' => 'affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-pros i',
			'layout-3-cons-title-icon' => 'affx-pros-cons-inner-wrapper.layout-type-3 .affiliatex-block-cons i',
			'pros'                     => 'affx-pros-inner',
			'cons'                     => 'affx-cons-inner',
			'pros-content-wrapper'     => 'affiliatex-pros',
			'cons-content-wrapper'     => 'affiliatex-cons',
			'title'                    => 'affiliatex-title',
			'content'                  => 'affiliatex-content',
			'list'                     => 'affiliatex-list',
			'list-item'                => 'affiliatex-list li',
		);
	}

	public function get_elementor_controls() {
		return array(
			'layout_settings_section'     => array(
				'label'  => __( 'Layout Settings', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'layoutStyle' => array(
						'label'   => __( 'Choose Layout', 'affiliatex' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'layout-type-1',
						'options' => array(
							'layout-type-1' => __( 'Layout One', 'affiliatex' ),
							'layout-type-2' => __( 'Layout Two', 'affiliatex' ),
							'layout-type-3' => __( 'Layout Three', 'affiliatex' ),
							'layout-type-4' => __( 'Layout Four', 'affiliatex' ),
						),
					),
				),
			),

			'general_settings_section'    => array(
				'label'  => __( 'General Settings', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'titleTag1'        => array(
						'label'   => __( 'Heading Tag', 'affiliatex' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'p',
						'options' => array(
							'h2' => 'H2',
							'h3' => 'H3',
							'h4' => 'H4',
							'h5' => 'H5',
							'h6' => 'H6',
							'p'  => 'Paragraph (p)',
						),
					),
					'alignment'        => array(
						'label'     => __( 'Title Alignment', 'affiliatex' ),
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
						'default'   => 'left',
						'selectors' => array(
							$this->select_elements( array( 'pros-block', 'cons-block' ) ) => 'text-align: {{VALUE}};',
						),
						'toggle'    => false,
						'condition' => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'alignmentThree'   => array(
						'label'     => __( 'Title Alignment', 'affiliatex' ),
						'type'      => Controls_Manager::CHOOSE,
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
						'default'   => 'center',
						'selectors' => array(
							$this->select_elements(
								array(
									array( 'layout-3-wrapper', ' .affiliatex-block-pros .pros-title-icon' ),
									array( 'layout-3-wrapper', ' .affiliatex-block-cons .cons-title-icon' ),
								)
							) => 'justify-content: {{VALUE}};',
							$this->select_elements(
								array(
									array( 'layout-3-wrapper', ' .affiliatex-block-pros' ),
									array( 'layout-3-wrapper', ' .affiliatex-block-cons' ),
								)
							) => 'align-items: {{VALUE}}',
						),
						'toggle'    => false,
						'condition' => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'contentAlignment' => array(
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
						'default'   => 'left',
						'selectors' => array(
							$this->select_elements( array( 'pros-content-wrapper', 'cons-content-wrapper' ) ) => 'text-align: {{VALUE}};',
						),
						'toggle'    => false,
					),
				),
			),

			'pros_settings_section'       => array(
				'label'  => __( 'Pros Settings', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'prosTitle'           => array(
						'label'   => __( 'Pros Heading Title', 'affiliatex' ),
						'type'    => ControlsManager::TEXT,
						'default' => __( 'Pros', 'affiliatex' ),
					),
					'prosIconStatus'      => array(
						'label'        => __( 'Enable Pros Title Icon', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'true',
					),
					'prosListIcon'        => array(
						'label'     => __( 'Pros Title Icon', 'affiliatex' ),
						'type'      => Controls_Manager::ICONS,
						'default'   => array(
							'value'   => 'far fa-thumbs-up',
							'library' => 'fa-regular',
						),
						'condition' => array(
							'prosIconStatus' => 'true',
						),
					),
					'prosIconSize'        => array(
						'label'      => __( 'Pros Title Icon Size', 'affiliatex' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px' ),
						'range'      => array(
							'px' => array(
								'min'  => 0,
								'max'  => 40,
								'step' => 1,
							),
						),
						'default'    => array(
							'unit' => 'px',
							'size' => 18,
						),
						'selectors'  => array(
							$this->select_element( 'pros-block' ) . ' i' => 'font-size: {{SIZE}}{{UNIT}};',
						),
						'condition'  => array(
							'prosIconStatus' => 'true',
						),
					),
					'prosContentType'     => array(
						'label'   => __( 'Content Type', 'affiliatex' ),
						'type'    => Controls_Manager::CHOOSE,
						'options' => array(
							'paragraph' => array(
								'title' => __( 'Paragraph', 'affiliatex' ),
								'icon'  => 'eicon-editor-paragraph',
							),
							'list'      => array(
								'title' => __( 'List', 'affiliatex' ),
								'icon'  => 'eicon-editor-list-ul',
							),
							'amazon'    => array(
								'title' => __( 'Amazon', 'affiliatex' ),
								'icon'  => 'fa-brands fa-amazon',
							),
						),
						'default' => 'list',
						'toggle'  => false,
					),
					'prosContent'         => array(
						'label'       => esc_html__( 'Pros Content', 'affiliatex' ),
						'type'        => ControlsManager::TEXTAREA,
						'rows'        => 4,
						'default'     => esc_html__( 'Content', 'affiliatex' ),
						'placeholder' => esc_html__( 'Content', 'affiliatex' ),
						'condition'   => array(
							'prosContentType' => 'paragraph',
						),
					),
					'prosListItems'       => array(
						'label'       => __( 'Pros List Items', 'affiliatex' ),
						'type'        => Controls_Manager::REPEATER,
						'title_field' => '{{{ content }}}',
						'fields'      => array(
							array(
								'name'    => 'content',
								'label'   => __( 'Pros Content', 'affiliatex' ),
								'type'    => ControlsManager::TEXT,
								'default' => 'Enter new item',
							),
						),
						'default'     => array(
							array(
								'content' => 'Enter new item',
							),
						),
						'condition'   => array(
							'prosContentType' => 'list',
						),
					),
					'prosListItemsAmazon' => array(
						'label'       => __( 'Amazon Pros List Items', 'affiliatex' ),
						'type'        => ControlsManager::TEXT,
						'disabled'    => true,
						'placeholder' => __( 'Click on the button to connect product', 'affiliatex' ),
						'condition'   => array(
							'prosContentType' => 'amazon',
						),
					),
					'prosListType'        => array(
						'label'     => __( 'List Type', 'affiliatex' ),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => array(
							'unordered' => array(
								'title' => __( 'Unordered', 'affiliatex' ),
								'icon'  => 'eicon-editor-list-ul',
							),
							'ordered'   => array(
								'title' => __( 'Ordered', 'affiliatex' ),
								'icon'  => 'eicon-editor-list-ol',
							),
						),
						'toggle'    => false,
						'default'   => 'unordered',
						'condition' => array(
							'prosContentType' => array( 'list', 'amazon' ),
						),
					),
					'prosUnorderedType'   => array(
						'label'     => __( 'Unordered Type', 'affiliatex' ),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => array(
							'icon'   => array(
								'title' => __( 'Show Icon', 'affiliatex' ),
								'icon'  => 'eicon-star',
							),
							'bullet' => array(
								'title' => __( 'Show Bullet', 'affiliatex' ),
								'icon'  => 'eicon-dot-circle-o',
							),
						),
						'toggle'    => false,
						'default'   => 'icon',
						'condition' => array(
							'prosContentType' => array( 'list', 'amazon' ),
							'prosListType'    => 'unordered',
						),
					),
					'prosIcon'            => array(
						'label'     => __( 'Pros List Icon', 'affiliatex' ),
						'type'      => Controls_Manager::ICONS,
						'default'   => array(
							'value'   => 'far fa-check-circle',
							'library' => 'fa-regular',
						),
						'condition' => array(
							'prosContentType'   => array( 'list', 'amazon' ),
							'prosListType'      => 'unordered',
							'prosUnorderedType' => 'icon',
						),
					),
				),
			),

			'cons_settings_section'       => array(
				'label'  => __( 'Cons Settings', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'consTitle'           => array(
						'label'   => __( 'Cons Heading Title', 'affiliatex' ),
						'type'    => ControlsManager::TEXT,
						'default' => __( 'Cons', 'affiliatex' ),
					),
					'consIconStatus'      => array(
						'label'        => __( 'Enable Cons Title Icon', 'affiliatex' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'true',
					),
					'consListIcon'        => array(
						'label'     => __( 'Cons Title Icon', 'affiliatex' ),
						'type'      => Controls_Manager::ICONS,
						'default'   => array(
							'value'   => 'far fa-thumbs-down',
							'library' => 'fa-regular',
						),
						'condition' => array(
							'consIconStatus' => 'true',
						),
					),
					'consIconSize'        => array(
						'label'      => __( 'Cons Title Icon Size', 'affiliatex' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px' ),
						'range'      => array(
							'px' => array(
								'min'  => 0,
								'max'  => 40,
								'step' => 1,
							),
						),
						'default'    => array(
							'unit' => 'px',
							'size' => 18,
						),
						'selectors'  => array(
							$this->select_element( 'cons-block' ) . ' i' => 'font-size: {{SIZE}}{{UNIT}};',
						),
						'condition'  => array(
							'consIconStatus' => 'true',
						),
					),
					'consContentType'     => array(
						'label'   => __( 'Content Type', 'affiliatex' ),
						'type'    => Controls_Manager::CHOOSE,
						'options' => array(
							'paragraph' => array(
								'title' => __( 'Paragraph', 'affiliatex' ),
								'icon'  => 'eicon-editor-paragraph',
							),
							'list'      => array(
								'title' => __( 'List', 'affiliatex' ),
								'icon'  => 'eicon-editor-list-ul',
							),
							'amazon'    => array(
								'title' => __( 'Amazon', 'affiliatex' ),
								'icon'  => 'fa-brands fa-amazon',
							),
						),
						'default' => 'list',
						'toggle'  => false,
					),
					'consContent'         => array(
						'label'       => esc_html__( 'Cons Content', 'affiliatex' ),
						'type'        => ControlsManager::TEXTAREA,
						'rows'        => 4,
						'default'     => esc_html__( 'Content', 'affiliatex' ),
						'placeholder' => esc_html__( 'Content', 'affiliatex' ),
						'condition'   => array(
							'consContentType' => 'paragraph',
						),
					),
					'consListItems'       => array(
						'label'       => __( 'Cons List Items', 'affiliatex' ),
						'type'        => Controls_Manager::REPEATER,
						'title_field' => '{{{ content }}}',
						'fields'      => array(
							array(
								'name'    => 'content',
								'label'   => __( 'Cons Content', 'affiliatex' ),
								'type'    => ControlsManager::TEXT,
								'default' => 'Enter new item',
							),
						),
						'default'     => array(
							array(
								'content' => 'Enter new item',
							),
						),
						'condition'   => array(
							'consContentType' => 'list',
						),
					),
					'consListItemsAmazon' => array(
						'label'       => __( 'Amazon Cons List Items', 'affiliatex' ),
						'type'        => ControlsManager::TEXT,
						'disabled'    => true,
						'placeholder' => __( 'Click on the button to connect product', 'affiliatex' ),
						'condition'   => array(
							'consContentType' => 'amazon',
						),
					),
					'consListType'        => array(
						'label'     => __( 'List Type', 'affiliatex' ),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => array(
							'unordered' => array(
								'title' => __( 'Unordered', 'affiliatex' ),
								'icon'  => 'eicon-editor-list-ul',
							),
							'ordered'   => array(
								'title' => __( 'Ordered', 'affiliatex' ),
								'icon'  => 'eicon-editor-list-ol',
							),
						),
						'toggle'    => false,
						'default'   => 'unordered',
						'condition' => array(
							'consContentType' => array( 'list', 'amazon' ),
						),
					),
					'consUnorderedType'   => array(
						'label'     => __( 'Unordered Type', 'affiliatex' ),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => array(
							'icon'   => array(
								'title' => __( 'Show Icon', 'affiliatex' ),
								'icon'  => 'eicon-star',
							),
							'bullet' => array(
								'title' => __( 'Show Bullet', 'affiliatex' ),
								'icon'  => 'eicon-dot-circle-o',
							),
						),
						'default'   => 'icon',
						'condition' => array(
							'consContentType' => array( 'list', 'amazon' ),
							'consListType'    => 'unordered',
						),
						'toggle'    => false,
					),
					'consIcon'            => array(
						'label'     => __( 'Cons List Icon', 'affiliatex' ),
						'type'      => Controls_Manager::ICONS,
						'default'   => array(
							'value'   => 'far fa-times-circle',
							'library' => 'fa-regular',
						),
						'condition' => array(
							'consContentType'   => array( 'list', 'amazon' ),
							'consListType'      => 'unordered',
							'consUnorderedType' => 'icon',
						),
					),
				),
			),

			'border_settings_section'     => array(
				'label'  => __( 'Border', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'prosBorder'               => array(
						'label'          => __( 'Pros Title Border', 'affiliatex' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_elements( array( 'pros-block' ) ),
						'fields_options' => array(
							'border' => array(
								'default' => 'none',
								'label'   => __( 'Pros Title Border Type', 'affiliatex' ),
							),
							'color'  => array(
								'default' => '#dddddd',
								'label'   => __( 'Pros Title Border Color', 'affiliatex' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => '1',
									'right'    => '1',
									'bottom'   => '1',
									'left'     => '1',
								),
								'label'   => __( 'Pros Title Border Width', 'affiliatex' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'prosBorderThree'          => array(
						'label'          => __( 'Pros Title Border', 'affiliatex' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_element( 'layout-3-pros-title-icon' ),
						'fields_options' => array(
							'border' => array(
								'default' => 'solid',
								'label'   => __( 'Pros Title Border Type', 'affiliatex' ),
							),
							'color'  => array(
								'default' => '#ffffff',
								'label'   => __( 'Pros Title Border Color', 'affiliatex' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => '4',
									'right'    => '4',
									'bottom'   => '4',
									'left'     => '4',
								),
								'label'   => __( 'Pros Title Border Width', 'affiliatex' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'prosContentBorder'        => array(
						'label'          => __( 'Pros Content Border', 'affiliatex' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_elements(
							array(
								'pros-content-wrapper',
							)
						),
						'fields_options' => array(
							'border' => array(
								'default' => 'none',
								'label'   => __( 'Pros Content Border Type', 'affiliatex' ),
							),
							'color'  => array(
								'default' => '#dddddd',
								'label'   => __( 'Pros Content Border Color', 'affiliatex' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => '0',
									'right'    => '0',
									'bottom'   => '0',
									'left'     => '0',
								),
								'label'   => __( 'Pros Content Border Width', 'affiliatex' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'prosContentBorderThree'   => array(
						'label'          => __( 'Pros Content Border', 'affiliatex' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_elements(
							array(
								array( 'layout-3-wrapper', ' .pros-icon-title-wrap' ),
								array( 'layout-3-wrapper', ' .affiliatex-pros' ),
							)
						),
						'fields_options' => array(
							'border' => array(
								'default' => 'solid',
								'label'   => __( 'Pros Content Border Type', 'affiliatex' ),
							),
							'color'  => array(
								'default' => '#24B644',
								'label'   => __( 'Pros Content Border Color', 'affiliatex' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => '4',
									'right'    => '4',
									'bottom'   => '4',
									'left'     => '4',
								),
								'label'   => __( 'Pros Content Border Width', 'affiliatex' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'consBorder'               => array(
						'label'          => __( 'Cons Title Border', 'affiliatex' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_elements( array( 'cons-block' ) ),
						'fields_options' => array(
							'border' => array(
								'default' => 'none',
								'label'   => __( 'Cons Title Border Type', 'affiliatex' ),
							),
							'color'  => array(
								'default' => '#dddddd',
								'label'   => __( 'Cons Title Border Color', 'affiliatex' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => '1',
									'right'    => '1',
									'bottom'   => '1',
									'left'     => '1',
								),
								'label'   => __( 'Cons Title Border Width', 'affiliatex' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'consBorderThree'          => array(
						'label'          => __( 'Cons Title Border', 'affiliatex' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_element( 'layout-3-cons-title-icon' ),
						'fields_options' => array(
							'border' => array(
								'default' => 'solid',
								'label'   => __( 'Cons Title Border Type', 'affiliatex' ),
							),
							'color'  => array(
								'default' => '#ffffff',
								'label'   => __( 'Cons Title Border Color', 'affiliatex' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => '4',
									'right'    => '4',
									'bottom'   => '4',
									'left'     => '4',
								),
								'label'   => __( 'Cons Title Border Width', 'affiliatex' ),

							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'consContentBorder'        => array(
						'label'          => __( 'Cons Content Border', 'affiliatex' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_elements(
							array(
								'cons-content-wrapper',
							)
						),
						'fields_options' => array(
							'border' => array(
								'default' => 'none',
								'label'   => __( 'Cons Content Border Type', 'affiliatex' ),
							),
							'color'  => array(
								'default' => '#dddddd',
								'label'   => __( 'Cons Content Border Color', 'affiliatex' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => '1',
									'right'    => '1',
									'bottom'   => '1',
									'left'     => '1',
								),
								'label'   => __( 'Cons Content Border Width', 'affiliatex' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'consContentBorderThree'   => array(
						'label'          => __( 'Cons Content Border', 'affiliatex' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_elements(
							array(
								array( 'layout-3-wrapper', ' .cons-icon-title-wrap' ),
								array( 'layout-3-wrapper', ' .affiliatex-cons' ),
							)
						),
						'fields_options' => array(
							'border' => array(
								'default' => 'solid',
								'label'   => __( 'Cons Content Border Type', 'affiliatex' ),
							),
							'color'  => array(
								'default' => '#F13A3A',
								'label'   => __( 'Cons Content Border Color', 'affiliatex' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => '4',
									'right'    => '4',
									'bottom'   => '4',
									'left'     => '4',
								),
								'label'   => __( 'Cons Content Border Width', 'affiliatex' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'titleBorderRadius'        => array(
						'label'      => __( 'Title Border Radius', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'rem', 'em' ),
						'default'    => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'unit'     => 'px',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_elements( array( 'pros-block', 'cons-block' ) ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'separator'  => 'before',
						'condition'  => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'titleBorderRadiusThree'   => array(
						'label'      => __( 'Title Border Radius', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'rem', 'em' ),
						'default'    => array(
							'top'      => '50',
							'right'    => '50',
							'bottom'   => '50',
							'left'     => '50',
							'unit'     => 'px',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'title' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'contentBorderRadius'      => array(
						'label'      => __( 'Content Border Radius', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'rem', 'em' ),
						'default'    => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'unit'     => 'px',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_elements( array( 'pros-content-wrapper', 'cons-content-wrapper' ) ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'contentBorderRadiusThree' => array(
						'label'      => __( 'Content Border Radius', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'rem', 'em' ),
						'default'    => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'unit'     => 'px',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_elements( array( 'pros-content-wrapper', 'cons-content-wrapper' ) ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							$this->select_elements(
								array(
									array( 'layout-3-wrapper', ' .pros-icon-title-wrap' ),
									array( 'layout-3-wrapper', ' .cons-icon-title-wrap' ),
								)
							) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'boxShadow'                => array(
						'type'           => Group_Control_Box_Shadow::get_type(),
						'selector'       => $this->select_elements(
							array(
								'layout-1-wrapper',
								array( 'layout-2-wrapper', ' .affx-pros-inner' ),
								array( 'layout-2-wrapper', ' .affx-cons-inner' ),
								array( 'layout-3-wrapper', ' .affx-pros-inner' ),
								array( 'layout-3-wrapper', ' .affx-cons-inner' ),
							)
						),
						'label'          => __( 'Box Shadow', 'affiliatex' ),
						'fields_options' => array(
							'box_shadow_type' => array(
								'default' => '',
							),
							'box_shadow'      => array(
								'default' => array(
									'vertical'   => '5',
									'horizontal' => '0',
									'blur'       => '20',
									'spread'     => '0',
									'color'      => 'rgba(210,213,218,0.2)',
									'inset'      => false,
								),
							),
						),
					),
				),
			),

			'colors_setting_section'      => array(
				'label'  => __( 'Colors', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'prosColorSettingsLabel'  => array(
						'label'     => esc_html__( 'Pros Color Settings', 'affiliatex' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
					),
					'prosTextColor'           => array(
						'label'     => __( 'Title Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => array(
							$this->select_element( 'pros-block' ) . ' .affiliatex-title' => 'color: {{VALUE}}',
							$this->select_element( 'pros-block' ) . ' i' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'prosTextColorThree'      => array(
						'label'     => __( 'Title Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#24B644',
						'selectors' => array(
							$this->select_element( 'pros-block' ) . ' .affiliatex-title' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'prosTitleIconColorThree' => array(
						'label'     => __( 'Title Icon Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => array(
							$this->select_element( 'layout-3-wrapper' ) . ' .affiliatex-block-pros i' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'prosListColor'           => array(
						'label'     => __( 'Content Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => AffiliateX_Customization_Helper::get_value( 'fontColor', '#292929' ),
						'selectors' => array(
							$this->select_element( 'pros' ) . ' .affiliatex-list li' => 'color: {{VALUE}}',
							$this->select_element( 'pros' ) . ' .affiliatex-content' => 'color: {{VALUE}}',
						),
					),
					'prosIconColor'           => array(
						'label'     => __( 'List Icon Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#24B644',
						'selectors' => array(
							$this->select_element( 'pros' ) . ' li::before' => 'color: {{VALUE}}; border-color: {{VALUE}}; background: {{VALUE}}',
							$this->select_element( 'pros' ) . ' li::marker' => 'color: {{VALUE}}',
							$this->select_element( 'pros' ) . ' ul.before li::marker' => 'background: {{VALUE}}',
							$this->select_element( 'pros' ) . ' li i' => 'color: {{VALUE}}',
						),
					),
					'prosBgColor'             => array(
						'type'           => Group_Control_Background::get_type(),
						'types'          => array( 'classic', 'gradient' ),
						'selector'       => $this->select_elements(
							array(
								'pros-block',
								'layout-3-pros-title-icon',
							)
						),
						'exclude'        => array( 'image' ),
						'fields_options' => array(
							'background'     => array(
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
								'label'   => __( 'Title Background Type', 'affiliatex' ),
								'toggle'  => false,
							),
							'color'          => array(
								'label'   => __( 'Title Background Color', 'affiliatex' ),
								'default' => '#24B644',
							),
							'color_b'        => array(
								'default' => '#7ADCB4',
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
					),
					'prosListBg'              => array(
						'type'           => Group_Control_Background::get_type(),
						'types'          => array( 'classic', 'gradient' ),
						'selector'       => $this->select_elements(
							array(
								'pros-content-wrapper',
								array( 'layout-3-wrapper', ' .affiliatex-block-pros' ),
								array( 'layout-4-wrapper', ' .affiliatex-pros p' ),
								array( 'layout-4-wrapper', ' .affiliatex-pros li' ),
							)
						),
						'exclude'        => array( 'image' ),
						'fields_options' => array(
							'background'     => array(
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
								'label'   => __( 'Content Background Type', 'affiliatex' ),
								'toggle'  => false,
							),
							'color'          => array(
								'label'   => __( 'Content Background Color', 'affiliatex' ),
								'default' => '#F5FFF8',

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
					),
					'consColorSettingsLabel'  => array(
						'label'     => esc_html__( 'Cons Color Settings', 'affiliatex' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
					),
					'consTextColor'           => array(
						'label'     => __( 'Title Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => array(
							$this->select_element( 'cons-block' ) . ' .affiliatex-title' => 'color: {{VALUE}}',
							$this->select_element( 'cons-block' ) . ' i' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'consTextColorThree'      => array(
						'label'     => __( 'Title Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#F13A3A',
						'selectors' => array(
							$this->select_element( 'cons-block' ) . ' .affiliatex-title' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'consTitleIconColorThree' => array(
						'label'     => __( 'Title Icon Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => array(
							$this->select_element( 'layout-3-wrapper' ) . ' .affiliatex-block-cons i' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'layoutStyle' => 'layout-type-3',
						),
					),
					'consListColor'           => array(
						'label'     => __( 'Content Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => AffiliateX_Customization_Helper::get_value( 'fontColor', '#292929' ),
						'selectors' => array(
							$this->select_element( 'cons' ) . ' .affiliatex-list li' => 'color: {{VALUE}}',
							$this->select_element( 'cons' ) . ' .affiliatex-content' => 'color: {{VALUE}}',
						),
					),
					'consIconColor'           => array(
						'label'     => __( 'List Icon Color', 'affiliatex' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#F13A3A',
						'selectors' => array(
							$this->select_element( 'cons' ) . ' li::before' => 'color: {{VALUE}}; border-color: {{VALUE}}; background: {{VALUE}}',
							$this->select_element( 'cons' ) . ' li::marker' => 'color: {{VALUE}}',
							$this->select_element( 'cons' ) . ' ul.before li::marker' => 'background: {{VALUE}}',
							$this->select_element( 'cons' ) . ' li i' => 'color: {{VALUE}}',
						),
					),
					'consBgColor'             => array(
						'type'           => Group_Control_Background::get_type(),
						'types'          => array( 'classic', 'gradient' ),
						'selector'       => $this->select_elements(
							array(
								'cons-block',
								'layout-3-cons-title-icon',
							)
						),
						'exclude'        => array( 'image' ),
						'fields_options' => array(
							'background'     => array(
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
								'label'   => __( 'Title Background Type', 'affiliatex' ),
								'toggle'  => false,
							),
							'color'          => array(
								'label'   => __( 'Title Background Color', 'affiliatex' ),
								'default' => '#F13A3A',
							),
							'color_b'        => array(
								'default' => '#FF6900',
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
					),
					'consListBg'              => array(
						'type'           => Group_Control_Background::get_type(),
						'types'          => array( 'classic', 'gradient' ),
						'selector'       => $this->select_elements(
							array(
								'cons-content-wrapper',
								array( 'layout-3-wrapper', ' .affiliatex-block-cons' ),
								array( 'layout-4-wrapper', ' .affiliatex-cons p' ),
								array( 'layout-4-wrapper', ' .affiliatex-cons li' ),
							)
						),
						'exclude'        => array( 'image' ),
						'fields_options' => array(
							'background'     => array(
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
								'label'   => __( 'Content Background Type', 'affiliatex' ),
								'toggle'  => 'false',
							),
							'color'          => array(
								'label'   => __( 'Content Background Color', 'affiliatex' ),
								'default' => '#FFF5F5',
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
					),
				),
			),

			'typography_settings_section' => array(
				'label'  => __( 'Typography', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'titleTypography' => array(
						'label'          => __( 'Title Typography', 'affiliatex' ),
						'type'           => Group_Control_Typography::get_type(),
						'selector'       => $this->select_element( 'title' ),
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
									'size' => '20',
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
					),
					'listTypography'  => array(
						'label'          => __( 'List/Content Typography', 'affiliatex' ),
						'type'           => Group_Control_Typography::get_type(),
						'selector'       => $this->select_elements( array( 'content', 'list' ) ),
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
					),

				),
			),

			'spacing_settings_section'    => array(
				'label'  => __( 'Spacing', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'titleMargin'      => array(
						'label'      => __( 'Title Margin', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'pros-block' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							$this->select_element( 'cons-block' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'titlePadding'     => array(
						'label'      => __( 'Title Padding', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '10',
							'right'    => '20',
							'bottom'   => '10',
							'left'     => '20',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'pros-block' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							$this->select_element( 'cons-block' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'layoutStyle!' => 'layout-type-3',
						),
					),
					'contentMargin'    => array(
						'label'      => __( 'Content Margin', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_elements( array( 'content', 'list' ) ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					'contentPadding'   => array(
						'label'      => __( 'Content Padding', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '10',
							'right'    => '20',
							'bottom'   => '10',
							'left'     => '20',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_elements(
								array(
									'content',
									'list',
									array( 'layout-4-wrapper', ' .affiliatex-content li' ),
									array( 'layout-4-wrapper', ' .affiliatex-list li' ),
								)
							) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					'margin'           => array(
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
					),
					'padding'          => array(
						'label'      => __( 'Padding', 'affiliatex' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'wrapper' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					// Amazon Attributes Configuration
					'amazonAttributes' => array(
						'type'    => Controls_Manager::HIDDEN,
						'default' => array(
							array(
								'field'      => 'features',
								'blockField' => array(
									'name'       => 'prosListItemsAmazon',
									'type'       => 'list',
									'defaults'   => array(
										'prosContentType' => 'list',
										'prosListItemsAmazon' => '',
									),
									'conditions' => array(
										'prosContentType' => 'amazon',
									),
								),
								'type'       => 'list',
							),
							array(
								'field'      => 'features',
								'blockField' => array(
									'name'       => 'consListItemsAmazon',
									'type'       => 'list',
									'defaults'   => array(
										'consContentType' => 'list',
										'consListItemsAmazon' => '',
									),
									'conditions' => array(
										'consContentType' => 'amazon',
									),
								),
								'type'       => 'list',
							),
						),
					),
				),
			),
		);
	}

	protected function register_controls() {
		WidgetHelper::generate_fields(
			$this,
			$this->get_elementor_controls(),
			'pros-and-cons'
		);
	}

	/**
	 * Render for Elementor
	 *
	 * @param array $attributes Widget attributes.
	 * @return void
	 */
	public function render( $attributes = array() ) {
		if ( ! $attributes ) {
			$settings   = $this->get_settings_for_display();
			$attributes = $this->parse_attributes( $settings );
			$attributes = WidgetHelper::process_attributes( $attributes );
		}

		$attributes['block_id'] = $this->get_id();
		if ( 'list' === $attributes['prosContentType'] ) {
			$attributes['prosListItems'] = WidgetHelper::format_list_items( $attributes['prosListItems'] );
		} elseif ( 'amazon' === $attributes['prosContentType'] ) {
			$attributes['prosListItems'] = $attributes['prosListItemsAmazon'];
		}

		if ( 'list' === $attributes['consContentType'] ) {
			$attributes['consListItems'] = WidgetHelper::format_list_items( $attributes['consListItems'] );
		} elseif ( 'amazon' === $attributes['consContentType'] ) {
			$attributes['consListItems'] = $attributes['consListItemsAmazon'];
		}

		echo wp_kses_post( $this->render_template( $attributes ) );
	}
}
