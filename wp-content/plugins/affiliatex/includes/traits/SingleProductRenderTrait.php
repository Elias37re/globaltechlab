<?php

namespace AffiliateX\Traits;

use AffiliateX\Elementor\ControlsManager;
use AffiliateX\Helpers\ElementorHelper;
use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\Elementor\ChildHelper;
use AffiliateX\Helpers\Elementor\WidgetHelper;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use AffiliateX\Traits\ButtonRenderTrait;
use Elementor\Group_Control_Background;
use AffiliateX\Blocks\AffiliateX_Customization_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * This trait is a channel for share rendering methods between Gutenberg and Elementor
 *
 * @package AffiliateX
 */
trait SingleProductRenderTrait {

	use ButtonRenderTrait;
	use SliderRenderTrait;

	/**
	 * Elements supporting per-device visibility
	 *
	 * @var array
	 */
	protected static $hideable_elements = array( 'ribbon', 'image', 'title', 'subtitle', 'rating', 'pricing', 'content', 'button' );

	protected function get_elements(): array {
		return array(
			'wrapper'       => 'affx-single-product-wrapper',
			'title'         => 'affx-single-product-title',
			'subtitle'      => 'affx-single-product-subtitle',
			'content'       => 'affx-single-product-content',
			'image'         => 'affx-sp-img-wrapper',
			'ribbon'        => 'affx-sp-ribbon-title',
			'price'         => 'affx-sp-price',
			'price-marked'  => 'affx-sp-marked-price',
			'price-sale'    => 'affx-sp-sale-price',
			'list'          => 'affiliatex-list',
			'readmore'      => 'affx-readmore',
			'rating-number' => 'affx-rating-number',
		);
	}

	/**
	 * Inner button config
	 *
	 * @var array
	 */
	protected static $inner_button_config = array(
		'name_prefix'      => 'button_child',
		'label_prefix'     => 'Button',
		'index'            => null,
		'is_child'         => true,
		'conditions'       => array( 'edButton' => 'true' ),
		'field_conditions' => array(
			'button_child_buttonLinkNotice' => array(
				'edFullBlockLink' => 'true',
			),
			'button_child_buttonURL'        => array(
				'edFullBlockLink!' => 'true',
			),
			'button_child_btnRelNoFollow'   => array(
				'edFullBlockLink!' => 'true',
			),
			'button_child_btnRelSponsored'  => array(
				'edFullBlockLink!' => 'true',
			),
			'button_child_btnDownload'      => array(
				'edFullBlockLink!' => 'true',
			),
			'button_child_openInNewTab'     => array(
				'edFullBlockLink!' => 'true',
			),
		),
		'defaults'         => array(
			'button_label' => 'Buy Now',
			'buttonMargin' => array(
				'top'    => 16,
				'left'   => 0,
				'right'  => 0,
				'bottom' => 0,
				'unit'   => 'px',
			),
		),
	);

	/**
	 * Get default fields
	 *
	 * @return array
	 */
	protected function get_fields(): array {
		return array_merge(
			array(
				'block_id'                    => '',
				'productLayout'               => 'layoutOne',
				'productTitle'                => 'Title',
				'productTitleTag'             => 'h2',
				'productContent'              => 'You can have short product description here. It can be added as and enable/disable toggle option from which user can have control on it.',
				'productSubTitle'             => 'Subtitle',
				'productSubTitleTag'          => 'h3',
				'productContentType'          => 'paragraph',
				'ContentListType'             => 'unordered',
				'productContentList'          => array(),
				'productImageAlign'           => 'left',
				'productImageVerticalAlign'   => 'top',
				'productImageHorizontalAlign' => 'center',
				'productSalePrice'            => '$49',
				'productPrice'                => '$59',
				'productIconList'             => array(
					'name'  => 'check-circle-outline',
					'value' => 'far fa-check-circle',
				),
				'ratings'                     => 5,
				'edRatings'                   => false,
				'edTitle'                     => true,
				'edSubTitle'                  => false,
				'edContent'                   => true,
				'edPricing'                   => false,
				'PricingType'                 => 'picture',
				'productRatingColor'          => '#FFB800',
				'ratingInactiveColor'         => '#808080',
				'ratingContent'               => 'Our Score',
				'ratingStarSize'              => 25,
				'edButton'                    => false,
				'edProductImage'              => false,
				'edRibbon'                    => false,
				'productRibbonLayout'         => 'one',
				'ribbonText'                  => 'Sale',
				'ribbonAlign'                 => 'left',
				'ImgUrl'                      => '',
				'numberRatings'               => '8.5',
				'edFullBlockLink'             => false,
				'blockUrl'                    => '',
				'blockRelNoFollow'            => false,
				'blockRelSponsored'           => false,
				'blockDownload'               => false,
				'blockOpenInNewTab'           => false,
				'productRatingAlign'          => 'right',
				'productStarRatingAlign'      => 'left',
				'productImageType'            => 'default',
				'productImageExternal'        => '',
				'productImageSiteStripe'      => '',
				'productPricingAlign'         => 'left',
				'edReadMore'                  => false,
				'descriptionLength'           => 150,
				'listItemCount'               => 3,
				'readMoreText'                => __( 'Read more', 'affiliatex' ),
				'readLessText'                => __( 'Read less', 'affiliatex' ),
				'readMoreColor'               => '#00B0B0',
				'imageAspectRatio'            => 'auto',
			),
			$this->get_hide_on_defaults(),
			$this->get_slider_defaults()
		);
	}

	/**
	 * Default {element}HideOn attributes, all visible
	 *
	 * @return array
	 */
	protected function get_hide_on_defaults(): array {
		$defaults = array();

		foreach ( self::$hideable_elements as $element ) {
			$defaults[ $element . 'HideOn' ] = array(
				'desktop' => false,
				'tablet'  => false,
				'mobile'  => false,
			);
		}

		return $defaults;
	}

	/**
	 * Parse attributes
	 *
	 * @param array $attributes
	 * @return array
	 */
	protected function parse_attributes( array $attributes ): array {
		$defaults = $this->get_fields();

		return wp_parse_args( $attributes, $defaults );
	}

	/**
	 * Render stars
	 *
	 * @param [type] $ratings
	 * @param [type] $productRatingColor
	 * @param [type] $ratingInactiveColor
	 * @param [type] $ratingStarSize
	 * @return string
	 */
	private function render_pb_stars( $ratings, $productRatingColor, $ratingInactiveColor, $ratingStarSize ): string {
		$stars = '';
		$size  = is_numeric( $ratingStarSize ) ? (int) $ratingStarSize : 25;

		// Elementor has no per-device generated CSS for star size, keep the inline size there only.
		$inline_size = self::IS_ELEMENTOR ? sprintf( 'width:%dpx;height:%dpx;', $size, $size ) : '';

		for ( $i = 1; $i <= 5; $i++ ) {
			$is_active = $i <= $ratings;
			$color     = $is_active ? $productRatingColor : $ratingInactiveColor;
			$stars    .= sprintf(
				'<span class="%s" style="color:%s;%s">
                    <svg fill="currentColor" width="%d" height="%d" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                    </svg>
                </span>',
				esc_attr( $is_active ? 'affx-star' : 'affx-star affx-star-inactive' ),
				esc_attr( $color ),
				esc_attr( $inline_size ),
				esc_attr( $size ),
				esc_attr( $size )
			);
		}

		return $stars;
	}

	/**
	 * Visibility switcher controls for an element
	 *
	 * @param string $element Element key, e.g. 'title'.
	 * @param array  $condition Display condition, e.g. element enable switch.
	 * @return array
	 */
	protected function get_sp_visibility_controls( string $element, array $condition ): array {
		$devices = array(
			'desktop' => __( 'Hide On Desktop', 'affiliatex' ),
			'tablet'  => __( 'Hide On Tablet', 'affiliatex' ),
			'mobile'  => __( 'Hide On Mobile', 'affiliatex' ),
		);

		$controls = array(
			"affx_sp_{$element}_visibility_heading" => array(
				'label'     => __( 'Visibility', 'affiliatex' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => $condition,
			),
		);

		foreach ( $devices as $device => $label ) {
			$controls[ "affx_sp_{$element}_hide_{$device}" ] = array(
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
	 * Shared hover transition declaration for Elementor selectors
	 *
	 * @param array $extras Extra transitioned properties, e.g. 'font-size'.
	 * @return string
	 */
	protected function get_sp_hover_transition( array $extras = array() ): string {
		$transition = 'transition: color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease';

		foreach ( $extras as $property ) {
			$transition .= ', ' . $property . ' .15s ease';
		}

		return $transition . ';';
	}

	/**
	 * Field options for a hover typography group: adds the font transition to the base selector.
	 *
	 * @param string $base_selector Non-hover element selector.
	 * @return array
	 */
	protected function get_sp_hover_typography_options( string $base_selector ): array {
		$transition = $this->get_sp_hover_transition( array( 'font-size', 'letter-spacing' ) );

		return array(
			'font_size'      => array(
				'selectors' => array(
					'{{SELECTOR}}' => 'font-size: {{SIZE}}{{UNIT}};',
					$base_selector => $transition,
				),
			),
			'letter_spacing' => array(
				'selectors' => array(
					'{{SELECTOR}}' => 'letter-spacing: {{SIZE}}{{UNIT}};',
					$base_selector => $transition,
				),
			),
		);
	}

	/**
	 * Map Elementor visibility switchers to the shared {element}HideOn attributes
	 *
	 * @param array $attributes
	 * @return array
	 */
	protected function map_sp_visibility_attributes( array $attributes ): array {
		foreach ( self::$hideable_elements as $element ) {
			$attributes[ $element . 'HideOn' ] = array(
				'desktop' => ! empty( $attributes[ "affx_sp_{$element}_hide_desktop" ] ),
				'tablet'  => ! empty( $attributes[ "affx_sp_{$element}_hide_tablet" ] ),
				'mobile'  => ! empty( $attributes[ "affx_sp_{$element}_hide_mobile" ] ),
			);
		}

		return $attributes;
	}

	/**
	 * Elementor controls array.
	 *
	 * @param array $config Elementor control config.
	 */
	public function get_sp_elementor_controls( $config = array() ) {
		$defaults         = $this->get_fields();
		$hover_transition = $this->get_sp_hover_transition();

		$layoutSettings = array(
			'affx_sp_layout_settings' => array(
				'label'  => __( 'Layout Settings', 'affiliatex' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'productLayout' => array(
						'label'   => __( 'Product Layout', 'affiliatex' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'layoutOne',
						'options' => array(
							'layoutOne'   => __( 'Layout One', 'affiliatex' ),
							'layoutTwo'   => __( 'Layout Two', 'affiliatex' ),
							'layoutThree' => __( 'Layout Three', 'affiliatex' ),
						),
					),
				),
			),
		);

		$hiddenLayoutSettings = array();

		if ( isset( $config['is_child'] ) && $config['is_child'] ) {
			$layoutSettings = array();

			$hiddenLayoutSettings = array(
				'productLayout' => array(
					'type'    => Controls_Manager::HIDDEN,
					'default' => 'layoutTwo',
				),
			);
		}

		return array_merge(
			$layoutSettings,
			array(
				'affx_sp_ribbon_settings'   => array(
					'label'  => __( 'Ribbon Settings', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_CONTENT,
					'fields' => array_merge(
						$hiddenLayoutSettings,
						array(
							'edRibbon'            => array(
								'label'        => __( 'Enable Ribbon', 'affiliatex' ),
								'type'         => Controls_Manager::SWITCHER,
								'label_on'     => __( 'Yes', 'affiliatex' ),
								'label_off'    => __( 'No', 'affiliatex' ),
								'return_value' => 'true',
								'default'      => 'false',
							),
							'productRibbonLayout' => array(
								'label'     => __( 'Ribbon Layout', 'affiliatex' ),
								'type'      => Controls_Manager::SELECT,
								'default'   => 'one',
								'options'   => array(
									'one' => __( 'Ribbon One', 'affiliatex' ),
									'two' => __( 'Ribbon Two', 'affiliatex' ),
								),
								'condition' => array(
									'edRibbon' => 'true',
								),
							),
							'ribbonAlign'         => array(
								'label'     => __( 'Ribbon Alignment', 'affiliatex' ),
								'type'      => Controls_Manager::CHOOSE,
								'default'   => 'left',
								'options'   => array(
									'left'  => array(
										'title' => esc_html__( 'Left', 'affiliatex' ),
										'icon'  => 'eicon-text-align-left',
									),
									'right' => array(
										'title' => esc_html__( 'Right', 'affiliatex' ),
										'icon'  => 'eicon-text-align-right',
									),
								),
								'toggle'    => false,
								'condition' => array(
									'edRibbon'      => 'true',
									'productLayout' => array( 'layoutOne', 'layoutTwo' ),
								),
							),
							'ribbonText'          => array(
								'label'       => __( 'Ribbon Text', 'affiliatex' ),
								'type'        => Controls_Manager::TEXT,
								'label_block' => true,
								'default'     => __( 'Sale', 'affiliatex' ),
								'condition'   => array(
									'edRibbon' => 'true',
								),
							),
						),
						$this->get_sp_visibility_controls( 'ribbon', array( 'edRibbon' => 'true' ) )
					),
				),

				'affx_sp_general_settings'  => array(
					'label'  => __( 'General Settings', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_CONTENT,
					'fields' => array(
						'edButton'                    => array(
							'label'        => __( 'Enable Button', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => __( 'Yes', 'affiliatex' ),
							'label_off'    => __( 'No', 'affiliatex' ),
							'return_value' => 'true',
							'default'      => 'true',
						),
						'buttonDirection'             => array(
							'label'     => __( 'Buttons Direction', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'column',
							'options'   => array(
								'row'    => array(
									'title' => esc_html__( 'Row', 'affiliatex' ),
									'icon'  => 'eicon-arrow-right',
								),
								'column' => array(
									'title' => esc_html__( 'Column', 'affiliatex' ),
									'icon'  => 'eicon-arrow-down',
								),
							),
							'toggle'    => false,
							'condition' => array(
								'edButton' => 'true',
							),
						),
						'buttonsGap'                  => array(
							'label'     => esc_html__( 'Buttons Gap', 'affiliatex' ),
							'type'      => Controls_Manager::NUMBER,
							'min'       => 0,
							'max'       => 50,
							'step'      => 1,
							'default'   => 10,
							'condition' => array(
								'edButton' => 'true',
							),
						),
						'edProductImage'              => array(
							'label'        => __( 'Enable Product Image', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => __( 'Yes', 'affiliatex' ),
							'label_off'    => __( 'No', 'affiliatex' ),
							'return_value' => 'true',
							'default'      => 'true',
						),
						'productImageAlign'           => array(
							'label'     => __( 'Image Alignment', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'left',
							'options'   => array(
								'left'  => array(
									'title' => esc_html__( 'Left', 'affiliatex' ),
									'icon'  => 'eicon-text-align-left',
								),
								'right' => array(
									'title' => esc_html__( 'Right', 'affiliatex' ),
									'icon'  => 'eicon-text-align-right',
								),
							),
							'toggle'    => false,
							'condition' => array(
								'edProductImage' => 'true',
								'productLayout!' => 'layoutTwo',
							),
						),
						'productImageHorizontalAlign' => array(
							'label'     => __( 'Image Horizontal Alignment', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'center',
							'options'   => array(
								'left'   => array(
									'title' => esc_html__( 'Left', 'affiliatex' ),
									'icon'  => 'eicon-h-align-left',
								),
								'center' => array(
									'title' => esc_html__( 'Center', 'affiliatex' ),
									'icon'  => 'eicon-h-align-center',
								),
								'right'  => array(
									'title' => esc_html__( 'Right', 'affiliatex' ),
									'icon'  => 'eicon-h-align-right',
								),
							),
							'toggle'    => false,
							'condition' => array(
								'edProductImage' => 'true',
							),
						),
						'productImageVerticalAlign'   => array(
							'label'     => __( 'Image Vertical Alignment', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'top',
							'options'   => array(
								'top'    => array(
									'title' => esc_html__( 'Top', 'affiliatex' ),
									'icon'  => 'eicon-v-align-top',
								),
								'middle' => array(
									'title' => esc_html__( 'Middle', 'affiliatex' ),
									'icon'  => 'eicon-v-align-middle',
								),
								'bottom' => array(
									'title' => esc_html__( 'Bottom', 'affiliatex' ),
									'icon'  => 'eicon-v-align-bottom',
								),
							),
							'toggle'    => false,
							'condition' => array(
								'edProductImage' => 'true',
								'productLayout!' => 'layoutTwo',
							),
						),
						'productImageWidth'           => array(
							'label'     => esc_html__( 'Image Width', 'affiliatex' ),
							'type'      => Controls_Manager::SELECT,
							'default'   => 'inherit',
							'options'   => array(
								'inherit' => esc_html__( 'Inherit', 'affiliatex' ),
								'custom'  => esc_html__( 'Custom', 'affiliatex' ),
							),
							'condition' => array(
								'edProductImage' => 'true',
								'productLayout!' => 'layoutTwo',
							),
						),
						'productImageCustomWidth'     => array(
							'label'     => esc_html__( 'Custom Image Width ( % )', 'affiliatex' ),
							'type'      => Controls_Manager::NUMBER,
							'min'       => 0,
							'max'       => 100,
							'step'      => 1,
							'default'   => 45,
							'selectors' => array(
								$this->select_element( 'wrapper' ) . '.product-layout-1 .affx-sp-img-wrapper' => 'flex: 0 0 {{VALUE}}%',
								$this->select_element( 'wrapper' ) . '.product-layout-3 .affx-sp-img-wrapper' => 'flex: 0 0 {{VALUE}}%',
							),
							'condition' => array(
								'edProductImage'    => 'true',
								'productLayout!'    => 'layoutTwo',
								'productImageWidth' => 'custom',
							),
						),
						'productImageType'            => array(
							'label'     => __( 'Image Source', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'default',
							'options'   => array(
								'default'    => array(
									'title' => __( 'Upload', 'affiliatex' ),
									'icon'  => 'eicon-kit-upload',
								),
								'external'   => array(
									'title' => __( 'External', 'affiliatex' ),
									'icon'  => 'eicon-external-link-square',
								),
								'sitestripe' => array(
									'title' => __( 'SiteStripe', 'affiliatex' ),
									'icon'  => 'eicon-stripe-button',
								),
							),
							'toggle'    => false,
							'condition' => array(
								'edProductImage' => 'true',
							),
						),
						'ImgUrl'                      => array(
							'label'     => __( 'Image', 'affiliatex' ),
							'type'      => Controls_Manager::MEDIA,
							'default'   => array(
								'url' => \Elementor\Utils::get_placeholder_image_src(),
							),
							'condition' => array(
								'edProductImage'    => 'true',
								'productImageType'  => 'default',
								'useMultipleImages' => '',
							),
						),
						'productImageExternal'        => array(
							'label'         => __( 'External Image URL', 'affiliatex' ),
							'type'          => ControlsManager::TEXT,
							'label_block'   => true,
							'amazon_button' => true,
							'condition'     => array(
								'productImageType' => 'external',
							),
						),
						'productImageSiteStripe'      => array(
							'label'       => __( 'SiteStripe Markup', 'affiliatex' ),
							'type'        => Controls_Manager::TEXTAREA,
							'label_block' => true,
							'placeholder' => 'Enter SiteStripe Markup',
							'condition'   => array(
								'productImageType' => 'sitestripe',
							),
						),
					) + $this->get_slider_elementor_controls( 'edProductImage' )
						+ $this->get_sp_visibility_controls( 'image', array( 'edProductImage' => 'true' ) )
						+ $this->get_sp_visibility_controls( 'button', array( 'edButton' => 'true' ) ),
				),

				'affx_sp_link_settings'     => array(
					'label'  => __( 'Link Settings', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_CONTENT,
					'fields' => array(
						'edFullBlockLink'   => array(
							'label'        => __( 'Make Whole Block Clickable', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'return_value' => 'true',
							'default'      => 'false',
						),
						'linkNotice'        => array(
							'type'       => \Elementor\Controls_Manager::ALERT,
							'alert_type' => 'info',
							'content'    => esc_html__( 'When the whole block is clickable, individual button links are disabled. The entire block will use the link settings below.', 'affiliatex' ),
							'condition'  => array(
								'edFullBlockLink' => 'true',
							),
						),
						'blockUrl'          => array(
							'label'       => __( 'Link URL', 'affiliatex' ),
							'type'        => ControlsManager::TEXT,
							'label_block' => true,
							'placeholder' => __( 'Enter link URL', 'affiliatex' ),
							'condition'   => array(
								'edFullBlockLink' => 'true',
							),
						),
						'blockRelNoFollow'  => array(
							'label'        => __( 'Add rel="nofollow"', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'return_value' => 'true',
							'default'      => 'false',
							'condition'    => array(
								'edFullBlockLink' => 'true',
							),
						),
						'blockRelSponsored' => array(
							'label'        => __( 'Add rel="sponsored"', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'return_value' => 'true',
							'default'      => 'false',
							'condition'    => array(
								'edFullBlockLink' => 'true',
							),
						),
						'blockDownload'     => array(
							'label'        => __( 'Add download attribute', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'return_value' => 'true',
							'default'      => 'false',
							'condition'    => array(
								'edFullBlockLink' => 'true',
							),
						),
						'blockOpenInNewTab' => array(
							'label'        => __( 'Open link in new tab', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'return_value' => 'true',
							'default'      => 'false',
							'condition'    => array(
								'edFullBlockLink' => 'true',
							),
						),
					),
				),

				'affx_sp_title_settings'    => array(
					'label'  => __( 'Title Settings', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_CONTENT,
					'fields' => array(
						'edTitle'           => array(
							'label'        => __( 'Enable Title', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => __( 'Yes', 'affiliatex' ),
							'label_off'    => __( 'No', 'affiliatex' ),
							'return_value' => 'true',
							'default'      => 'true',
						),
						'productTitle'      => array(
							'label'         => __( 'Product Title', 'affiliatex' ),
							'type'          => ControlsManager::TEXT,
							'label_block'   => true,
							'default'       => __( 'Title', 'affiliatex' ),
							'amazon_button' => true,
							'condition'     => array(
								'edTitle' => 'true',
							),
						),
						'productTitleTag'   => array(
							'label'     => __( 'Product Heading Tag', 'affiliatex' ),
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
								'edTitle' => 'true',
							),
						),
						'productTitleAlign' => array(
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
							'toggle'    => false,
							'condition' => array(
								'edTitle' => 'true',
							),
							'selectors' => array(
								$this->select_element( 'title' ) => 'text-align: {{VALUE}};',
							),
						),
					) + $this->get_sp_visibility_controls( 'title', array( 'edTitle' => 'true' ) ),
				),

				'affx_sp_subtitle_settings' => array(
					'label'  => __( 'Subtitle Settings', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_CONTENT,
					'fields' => array(
						'edSubtitle'           => array(
							'label'        => __( 'Enable Subtitle', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => __( 'Yes', 'affiliatex' ),
							'label_off'    => __( 'No', 'affiliatex' ),
							'return_value' => 'true',
							'default'      => 'false',
						),
						'productSubTitle'      => array(
							'label'         => __( 'Product Subtitle', 'affiliatex' ),
							'type'          => ControlsManager::TEXT,
							'default'       => __( 'Subtitle', 'affiliatex' ),
							'placeholder'   => __( 'Enter Product Subtitle', 'affiliatex' ),
							'amazon_button' => true,
							'condition'     => array(
								'edSubtitle' => 'true',
							),
						),
						'productSubTitleTag'   => array(
							'label'     => __( 'Product Subtitle Tag', 'affiliatex' ),
							'type'      => Controls_Manager::SELECT,
							'default'   => 'h6',
							'options'   => array(
								'h2' => __( 'Heading 2 (h2)', 'affiliatex' ),
								'h3' => __( 'Heading 3 (h3)', 'affiliatex' ),
								'h4' => __( 'Heading 4 (h4)', 'affiliatex' ),
								'h5' => __( 'Heading 5 (h5)', 'affiliatex' ),
								'h6' => __( 'Heading 6 (h6)', 'affiliatex' ),
								'p'  => __( 'Paragraph (p)', 'affiliatex' ),
							),
							'condition' => array(
								'edSubtitle' => 'true',
							),
						),
						'productSubtitleAlign' => array(
							'label'     => __( 'Sub Title Alignment', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'left',
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
							'toggle'    => false,
							'selectors' => array(
								$this->select_element( 'subtitle' ) => 'text-align: {{VALUE}};',
							),
							'condition' => array(
								'edSubtitle' => 'true',
							),
						),
					) + $this->get_sp_visibility_controls( 'subtitle', array( 'edSubtitle' => 'true' ) ),
				),

				'affx_sp_rating_settings'   => array(
					'label'  => __( 'Rating Settings', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_CONTENT,
					'fields' => array(
						'edRatings'              => array(
							'label'        => __( 'Enable Rating', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => __( 'Yes', 'affiliatex' ),
							'label_off'    => __( 'No', 'affiliatex' ),
							'return_value' => 'true',
							'default'      => 'false',
						),
						'PricingType'            => array(
							'label'     => __( 'Rating Type', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'options'   => array(
								'picture' => array(
									'title' => __( 'Star rating', 'affiliatex' ),
									'icon'  => 'eicon-star',
								),
								'number'  => array(
									'title' => __( 'Score box', 'affiliatex' ),
									'icon'  => 'eicon-section',
								),
							),
							'toggle'    => false,
							'default'   => 'picture',
							'condition' => array(
								'edRatings' => 'true',
							),
						),
						'ratings'                => array(
							'label'     => __( 'Ratings', 'affiliatex' ),
							'type'      => Controls_Manager::SELECT,
							'default'   => 5,
							'options'   => array(
								1 => 1,
								2 => 2,
								3 => 3,
								4 => 4,
								5 => 5,
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'picture',
							),
						),
						'ratingStarSize'         => array(
							'label'      => __( 'Star Rating size', 'affiliatex' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px' ),
							'range'      => array(
								'px' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 5,
								),
							),
							'default'    => array(
								'unit' => 'px',
								'size' => 25,
							),
							'condition'  => array(
								'edRatings'   => 'true',
								'PricingType' => 'picture',
							),
						),
						'productStarRatingAlign' => array(
							'label'     => __( 'Rating Alignment', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'left',
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
							'toggle'    => false,
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'picture',
							),
						),
						'numberRatings'          => array(
							'label'       => __( 'Rating Number', 'affiliatex' ),
							'type'        => Controls_Manager::NUMBER,
							'label_block' => true,
							'default'     => 8.5,
							'condition'   => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'ratingContent'          => array(
							'label'       => __( 'Rating Content', 'affiliatex' ),
							'type'        => Controls_Manager::TEXT,
							'label_block' => true,
							'default'     => 'Our Score',
							'condition'   => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'productRatingAlign'     => array(
							'label'     => __( 'Rating Alignment', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'left',
							'options'   => array(
								'left'  => array(
									'title' => __( 'Left', 'affiliatex' ),
									'icon'  => 'eicon-text-align-left',
								),
								'right' => array(
									'title' => __( 'Right', 'affiliatex' ),
									'icon'  => 'eicon-text-align-right',
								),
							),
							'toggle'    => false,
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
					) + $this->get_sp_visibility_controls( 'rating', array( 'edRatings' => 'true' ) ),
				),

				'affx_sp_pricing_settings'  => array(
					'label'  => __( 'Pricing Settings', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_CONTENT,
					'fields' => array(
						'edPricing'           => array(
							'label'        => __( 'Enable Pricing', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => __( 'Yes', 'affiliatex' ),
							'label_off'    => __( 'No', 'affiliatex' ),
							'return_value' => 'true',
							'default'      => '',
						),
						'productPrice'        => array(
							'label'         => __( 'Product Marked Price', 'affiliatex' ),
							'type'          => ControlsManager::TEXT,
							'label_block'   => true,
							'default'       => '$59',
							'amazon_button' => true,
							'condition'     => array(
								'edPricing' => 'true',
							),
						),
						'productSalePrice'    => array(
							'label'         => __( 'Product Sale Price', 'affiliatex' ),
							'type'          => ControlsManager::TEXT,
							'label_block'   => true,
							'default'       => '$49',
							'amazon_button' => true,
							'condition'     => array(
								'edPricing' => 'true',
							),
						),
						'productPricingAlign' => array(
							'label'     => __( 'Pricing Alignment', 'affiliatex' ),
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
							'condition' => array(
								'edPricing' => 'true',
							),
						),
					) + $this->get_sp_visibility_controls( 'pricing', array( 'edPricing' => 'true' ) ),
				),

				'affx_sp_content_settings'  => array(
					'label'  => __( 'Content Settings', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_CONTENT,
					'fields' => array(
						'edContent'                => array(
							'label'        => __( 'Enable Content', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'return_value' => 'true',
							'default'      => 'true',
						),
						'productContentType'       => array(
							'label'     => __( 'Content Type', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'paragraph',
							'options'   => array(
								'paragraph' => array(
									'title' => esc_html__( 'Paragraph', 'affiliatex' ),
									'icon'  => 'eicon-editor-paragraph',
								),
								'list'      => array(
									'title' => esc_html__( 'List', 'affiliatex' ),
									'icon'  => 'eicon-bullet-list',
								),
								'amazon'    => array(
									'title' => esc_html__( 'Amazon', 'affiliatex' ),
									'icon'  => 'fa-brands fa-amazon',
								),
							),
							'toggle'    => false,
							'condition' => array(
								'edContent' => 'true',
							),
						),
						'productContent'           => array(
							'label'       => __( 'Product Content', 'affiliatex' ),
							'type'        => Controls_Manager::TEXTAREA,
							'label_block' => true,
							'default'     => 'You can have short product description here. It can be added as and enable/disable toggle option from which user can have control on it.',
							'condition'   => array(
								'edContent'          => 'true',
								'productContentType' => 'paragraph',
							),
						),
						'productContentListAmazon' => array(
							'type'        => ControlsManager::TEXT,
							'label'       => __( 'Amazon Product Content', 'affiliatex' ),
							'default'     => '',
							'disabled'    => true,
							'placeholder' => __( 'Click on the button to connect product', 'affiliatex' ),
							'condition'   => array(
								'edContent'          => 'true',
								'productContentType' => 'amazon',
							),
						),
						'productContentList'       => array(
							'type'        => Controls_Manager::REPEATER,
							'label'       => __( 'Product Content List', 'affiliatex' ),
							'title_field' => '{{{ content }}}',
							'fields'      => array(
								array(
									'name'    => 'content',
									'label'   => __( 'List Item', 'affiliatex' ),
									'type'    => Controls_Manager::TEXT,
									'default' => 'Product List Item',
								),
							),
							'default'     => array(
								array(
									'content' => 'Product List Item',
								),
							),
							'condition'   => array(
								'edContent'          => 'true',
								'productContentType' => 'list',
							),
						),
						'ContentListType'          => array(
							'label'     => __( 'List Type', 'affiliatex' ),
							'type'      => Controls_Manager::CHOOSE,
							'default'   => 'unordered',
							'options'   => array(
								'unordered' => array(
									'title' => esc_html__( 'Unordered', 'affiliatex' ),
									'icon'  => 'eicon-editor-list-ul',
								),
								'ordered'   => array(
									'title' => esc_html__( 'Ordered', 'affiliatex' ),
									'icon'  => 'eicon-editor-list-ol',
								),
							),
							'toggle'    => false,
							'condition' => array(
								'edContent'          => 'true',
								'productContentType' => array( 'list', 'amazon' ),
							),
						),
						'productIconList'          => array(
							'label'       => __( 'Product Icon List', 'affiliatex' ),
							'type'        => Controls_Manager::ICONS,
							'label_block' => true,
							'default'     => array(
								'value'   => 'far fa-check-circle',
								'library' => 'fa-regular',
							),
							'render_type' => 'template',
							'condition'   => array(
								'edContent'          => 'true',
								'productContentType' => array( 'list', 'amazon' ),
								'ContentListType'    => 'unordered',
							),
						),
						'productContentAlign'      => array(
							'label'     => __( 'Content Alignment', 'affiliatex' ),
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
								$this->select_element( 'content' ) => 'justify-content: {{VALUE}};',
								$this->select_element( array( 'content', ' p' ) ) => 'text-align: {{VALUE}};',
								$this->select_element( array( 'content', ' li' ) ) => 'justify-content: {{VALUE}};',
							),
							'condition' => array(
								'edContent' => 'true',
							),
						),
						'edReadMore'               => array(
							'label'        => __( 'Enable Read More', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'return_value' => 'true',
							'default'      => 'false',
							'render_type'  => 'template',
							'condition'    => array(
								'edContent' => 'true',
							),
						),
						'descriptionLength'        => array(
							'label'       => __( 'Description Length (characters)', 'affiliatex' ),
							'type'        => Controls_Manager::NUMBER,
							'default'     => 150,
							'min'         => 10,
							'description' => __( 'Number of characters to show before Read More', 'affiliatex' ),
							'condition'   => array(
								'edContent'  => 'true',
								'edReadMore' => 'true',
							),
						),
						'listItemCount'            => array(
							'label'       => __( 'List Item Count', 'affiliatex' ),
							'type'        => Controls_Manager::NUMBER,
							'default'     => 3,
							'min'         => 1,
							'description' => __( 'Number of list items to show before Read More', 'affiliatex' ),
							'condition'   => array(
								'edContent'          => 'true',
								'edReadMore'         => 'true',
								'productContentType' => array( 'list', 'amazon' ),
							),
						),
						'readMoreText'             => array(
							'label'     => __( 'Read More Text', 'affiliatex' ),
							'type'      => Controls_Manager::TEXT,
							'default'   => __( 'Read more', 'affiliatex' ),
							'condition' => array(
								'edContent'  => 'true',
								'edReadMore' => 'true',
							),
						),
						'readLessText'             => array(
							'label'     => __( 'Read Less Text', 'affiliatex' ),
							'type'      => Controls_Manager::TEXT,
							'default'   => __( 'Read less', 'affiliatex' ),
							'condition' => array(
								'edContent'  => 'true',
								'edReadMore' => 'true',
							),
						),
					) + $this->get_sp_visibility_controls( 'content', array( 'edContent' => 'true' ) ),
				),

				'affx_sp_style_general'     => array(
					'label'  => __( 'Colors', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_STYLE,
					'fields' => array(
						'affx_sp_colors_tabs'            => array(
							'type' => 'tabs_start',
						),
						'affx_sp_colors_tab_normal'      => array(
							'type'  => 'tab_start',
							'label' => __( 'Normal', 'affiliatex' ),
						),
						'productTitleColor'              => array(
							'label'     => __( 'Title Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#060c0e',
							'selectors' => array(
								$this->select_element( 'title' ) => 'color: {{VALUE}}',
							),
							'condition' => array(
								'edTitle' => 'true',
							),
						),
						'productSubtitleColor'           => array(
							'label'     => __( 'Subtitle Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#A3ACBF',
							'selectors' => array(
								$this->select_element( 'subtitle' ) => 'color: {{VALUE}}',
							),
							'condition' => array(
								'edSubtitle' => 'true',
							),
						),
						'productContentColor'            => array(
							'label'     => __( 'Content Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => AffiliateX_Customization_Helper::get_value( 'fontColor', '#292929' ),
							'selectors' => array(
								$this->select_elements(
									array(
										'content',
										array( 'content', ' p' ),
										array( 'content', ' li' ),
									)
								) => 'color: {{VALUE}}',
							),
							'condition' => array(
								'edContent' => 'true',
							),
						),
						'iconColor'                      => array(
							'label'     => __( 'Icon Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#24B644',
							'selectors' => array(
								$this->select_element( 'list' ) . ' li::before' => 'color: {{VALUE}}',
								$this->select_element( 'list' ) . ' li > i' => 'color: {{VALUE}}',
							),
							'condition' => array(
								'productContentType' => array( 'list', 'amazon' ),
							),
						),
						'readMoreColor'                  => array(
							'label'     => __( 'Read More Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#00B0B0',
							'selectors' => array(
								$this->select_element( 'readmore' ) . ' .affx-readmore-btn' => 'color: {{VALUE}};',
							),
							'condition' => array(
								'edContent'  => 'true',
								'edReadMore' => 'true',
							),
						),
						'productBackground'              => array(
							'type'           => Group_Control_Background::get_type(),
							'name'           => 'productBackground',
							'label'          => __( 'Background', 'affiliatex' ),
							'types'          => array( 'classic', 'gradient' ),
							'selector'       => $this->select_element( 'wrapper' ),
							'exclude'        => array( 'image' ),
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
									'label'     => __( 'Background Color', 'affiliatex' ),
									'selectors' => array(
										'{{SELECTOR}}' => 'background-color: {{VALUE}};',
									),
								),
								'image'      => array(
									'label' => __( 'Background Image', 'affiliatex' ),
								),
							),
						),
						'affx_sp_style_pricing'          => array(
							'label'     => esc_html__( 'Pricing', 'affiliatex' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
							'condition' => array(
								'edPricing' => 'true',
							),
						),
						'pricingHoverColor'              => array(
							'label'     => __( 'Sale Price Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#A3ACBF',
							'selectors' => array(
								$this->select_element( 'price-marked' ) => 'color: {{VALUE}}',
							),
							'condition' => array(
								'edPricing' => 'true',
							),
						),
						'pricingColor'                   => array(
							'label'     => __( 'Marked Price Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#262B33',
							'selectors' => array(
								$this->select_element( 'price-sale' ) => 'color: {{VALUE}}',
							),
							'condition' => array(
								'edPricing' => 'true',
							),
						),
						'affx_sp_style_ratings'          => array(
							'label'     => esc_html__( 'Ratings', 'affiliatex' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
							'condition' => array(
								'edRatings' => 'true',
							),
						),
						'productRateNumberColor'         => array(
							'label'     => __( 'Score Box Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#ffffff',
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-rating-box span.num' => 'color: {{VALUE}}',
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'productRateContentColor'        => array(
							'label'     => __( 'Content Rating Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#ffffff',
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-rating-box span.label' => 'color: {{VALUE}}',
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'productRateNumBgColor'          => array(
							'label'     => __( 'Score Box Background Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#00B0B0',
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-rating-box .num' => 'background-color: {{VALUE}}',
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'productRateContentBgColor'      => array(
							'label'     => __( 'Content Rating Background Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#262B33',
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-rating-box span.label' => 'background-color: {{VALUE}}',
								$this->select_element( 'wrapper' ) . ' .affx-rating-box span.label::before' => 'border-bottom-color: {{VALUE}}',
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'productRatingColor'             => array(
							'label'     => __( 'Rating Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#FFB800',
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'picture',
							),
						),
						'ratingInactiveColor'            => array(
							'label'     => __( 'Inactive Rating Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#808080',
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'picture',
							),
						),
						'affx_sp_style_ribbon'           => array(
							'label'     => esc_html__( 'Ribbon', 'affiliatex' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
							'condition' => array(
								'edRibbon' => 'true',
							),
						),
						'ribbonColor'                    => array(
							'label'     => __( 'Ribbon Text Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#fff',
							'selectors' => array(
								$this->select_element( 'ribbon' ) => 'color: {{VALUE}}',
							),
							'condition' => array(
								'edRibbon' => 'true',
							),
						),
						'ribbonBGColor'                  => array(
							'label'     => __( 'Ribbon Background Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#ff0000',
							'selectors' => array(
								$this->select_element( 'ribbon' )              => 'background-color: {{VALUE}}',
								$this->select_element( 'ribbon' ) . '::before' => 'border-bottom-color: {{VALUE}}!important;',
								$this->select_element( 'wrapper' ) . ' .ribbon-align-right .affx-sp-ribbon-title::before' => ' border-right-color: {{VALUE}}!important',
							),
							'condition' => array(
								'edRibbon' => 'true',
							),
						),
						'affx_sp_colors_tab_normal_end'  => array(
							'type' => 'tab_end',
						),
						'affx_sp_colors_tab_hover'       => array(
							'type'  => 'tab_start',
							'label' => __( 'Hover', 'affiliatex' ),
						),
						'productTitleHoverColor'         => array(
							'label'     => __( 'Title Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( array( 'title', ':hover' ) ) => 'color: {{VALUE}}',
								$this->select_element( 'title' ) => $hover_transition,
							),
							'condition' => array(
								'edTitle' => 'true',
							),
						),
						'productSubtitleHoverColor'      => array(
							'label'     => __( 'Subtitle Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( array( 'subtitle', ':hover' ) ) => 'color: {{VALUE}}',
								$this->select_element( 'subtitle' ) => $hover_transition,
							),
							'condition' => array(
								'edSubtitle' => 'true',
							),
						),
						'productContentHoverColor'       => array(
							'label'     => __( 'Content Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_elements(
									array(
										array( 'content', ':hover' ),
										array( 'content', ':hover p' ),
										array( 'content', ':hover li' ),
									)
								) => 'color: {{VALUE}}',
								$this->select_elements(
									array(
										'content',
										array( 'content', ' p' ),
										array( 'content', ' li' ),
									)
								) => $hover_transition,
							),
							'condition' => array(
								'edContent' => 'true',
							),
						),
						'iconHoverColor'                 => array(
							'label'     => __( 'Icon Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( 'list' ) . ' li:hover::before' => 'color: {{VALUE}}',
								$this->select_element( 'list' ) . ' li:hover > i' => 'color: {{VALUE}}',
								$this->select_element( 'list' ) . ' li::before' => $hover_transition,
								$this->select_element( 'list' ) . ' li > i' => $hover_transition,
							),
							'condition' => array(
								'productContentType' => array( 'list', 'amazon' ),
							),
						),
						'readMoreHoverColor'             => array(
							'label'     => __( 'Read More Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( 'readmore' ) . ' .affx-readmore-btn:hover' => 'color: {{VALUE}};',
								$this->select_element( 'readmore' ) . ' .affx-readmore-btn' => $hover_transition,
							),
							'condition' => array(
								'edContent'  => 'true',
								'edReadMore' => 'true',
							),
						),
						'productHoverBackground'         => array(
							'type'           => Group_Control_Background::get_type(),
							'name'           => 'productHoverBackground',
							'label'          => __( 'Background', 'affiliatex' ),
							'types'          => array( 'classic', 'gradient' ),
							'selector'       => $this->select_element( array( 'wrapper', ':hover' ) ),
							'exclude'        => array( 'image' ),
							'fields_options' => array(
								'background' => array(
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
										$this->select_element( 'wrapper' ) => $hover_transition,
									),
								),
								'image'      => array(
									'label' => __( 'Background Image', 'affiliatex' ),
								),
							),
						),
						'affx_sp_style_pricing_hover'    => array(
							'label'     => esc_html__( 'Pricing', 'affiliatex' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
							'condition' => array(
								'edPricing' => 'true',
							),
						),
						'productPriceHoverColor'         => array(
							'label'     => __( 'Sale Price Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( array( 'price-marked', ':hover' ) ) => 'color: {{VALUE}}',
								$this->select_element( 'price-marked' ) => $hover_transition,
							),
							'condition' => array(
								'edPricing' => 'true',
							),
						),
						'productSalePriceHoverColor'     => array(
							'label'     => __( 'Marked Price Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( array( 'price-sale', ':hover' ) ) => 'color: {{VALUE}}',
								$this->select_element( 'price-sale' ) => $hover_transition,
							),
							'condition' => array(
								'edPricing' => 'true',
							),
						),
						'affx_sp_style_ratings_hover'    => array(
							'label'     => esc_html__( 'Ratings', 'affiliatex' ),
							'type'      => Controls_Manager::HEADING,
							'separator' => 'before',
							'condition' => array(
								'edRatings' => 'true',
							),
						),
						'productRateNumberHoverColor'    => array(
							'label'     => __( 'Score Box Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-rating-box:hover span.num' => 'color: {{VALUE}}',
								$this->select_element( 'wrapper' ) . ' .affx-rating-box span.num' => $hover_transition,
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'productRateContentHoverColor'   => array(
							'label'     => __( 'Content Rating Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-rating-box:hover span.label' => 'color: {{VALUE}}',
								$this->select_element( 'wrapper' ) . ' .affx-rating-box span.label' => $hover_transition,
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'productRateNumBgHoverColor'     => array(
							'label'     => __( 'Score Box Background Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-rating-box:hover .num' => 'background-color: {{VALUE}}',
								$this->select_element( 'wrapper' ) . ' .affx-rating-box .num' => $hover_transition,
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'productRateContentBgHoverColor' => array(
							'label'     => __( 'Content Rating Background Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-rating-box:hover span.label' => 'background-color: {{VALUE}}',
								$this->select_element( 'wrapper' ) . ' .affx-rating-box:hover span.label::before' => 'border-bottom-color: {{VALUE}}',
								$this->select_element( 'wrapper' ) . ' .affx-rating-box span.label' => $hover_transition,
								$this->select_element( 'wrapper' ) . ' .affx-rating-box span.label::before' => $hover_transition,
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'ratingHoverColor'               => array(
							'label'     => __( 'Rating Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-sp-pricing-pic:hover svg path' => 'fill: {{VALUE}}',
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'picture',
							),
						),
						'ratingInactiveHoverColor'       => array(
							'label'     => __( 'Inactive Rating Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .affx-sp-pricing-pic:hover .affx-star-inactive svg path' => 'fill: {{VALUE}}',
							),
							'condition' => array(
								'edRatings'   => 'true',
								'PricingType' => 'picture',
							),
						),
						'ribbonHoverColor'               => array(
							'label'     => __( 'Ribbon Text Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( array( 'ribbon', ':hover' ) ) => 'color: {{VALUE}}',
								$this->select_element( 'ribbon' ) => $hover_transition,
							),
							'condition' => array(
								'edRibbon' => 'true',
							),
						),
						'ribbonBGHoverColor'             => array(
							'label'     => __( 'Ribbon Background Hover Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( array( 'ribbon', ':hover' ) ) => 'background-color: {{VALUE}}',
								$this->select_element( 'ribbon' ) . ':hover::before' => 'border-bottom-color: {{VALUE}}!important;',
								$this->select_element( 'wrapper' ) . ' .ribbon-align-right .affx-sp-ribbon-title:hover::before' => 'border-right-color: {{VALUE}}!important',
								$this->select_element( 'ribbon' ) => $hover_transition,
							),
							'condition' => array(
								'edRibbon' => 'true',
							),
						),
						'affx_sp_colors_tab_hover_end'   => array(
							'type' => 'tab_end',
						),
						'affx_sp_colors_tabs_end'        => array(
							'type' => 'tabs_end',
						),
					),
				),

				'affx_sp_typography'        => array(
					'label'  => __( 'Typography', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_STYLE,
					'fields' => array(
						'affx_sp_typography_tabs'          => array(
							'type' => 'tabs_start',
						),
						'affx_sp_typography_tab_normal'    => array(
							'type'  => 'tab_start',
							'label' => __( 'Normal', 'affiliatex' ),
						),
						'productTitleTypography'           => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Title', 'affiliatex' ),
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
										'size' => '24',
									),
								),
								'line_height'     => array(
									'default' => array(
										'unit' => 'custom',
										'size' => 1.33,
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
								'edTitle' => 'true',
							),
						),
						'productSubtitleTypography'        => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Subtitle', 'affiliatex' ),
							'selector'       => $this->select_element( 'subtitle' ),
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
								'edSubtitle' => 'true',
							),
						),
						'pricingTypography'                => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Price', 'affiliatex' ),
							'selector'       => $this->select_element( 'price' ),
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
										'size' => '22',
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
								'edPricing' => 'true',
							),
						),
						'productContentTypography'         => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Content', 'affiliatex' ),
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
							'condition'      => array(
								'edContent' => 'true',
							),
						),
						'readMoreTypography'               => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Read More', 'affiliatex' ),
							'selector'       => $this->select_element( 'readmore' ) . ' .affx-readmore-btn',
							'fields_options' => array(
								'typography'      => array(
									'default' => 'custom',
								),
								'font_family'     => array(
									'default' => '',
								),
								'font_weight'     => array(
									'default' => '400',
								),
								'font_size'       => array(
									'default' => array(
										'unit' => 'px',
										'size' => '14',
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
								'edContent'  => 'true',
								'edReadMore' => 'true',
							),
						),
						'ribbonContentTypography'          => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Ribbon', 'affiliatex' ),
							'selector'       => $this->select_element( 'ribbon' ),
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
										'size' => '17',
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
								'edRibbon' => 'true',
							),
						),
						'numRatingTypography'              => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Score Box', 'affiliatex' ),
							'selector'       => $this->select_element( 'rating-number' ),
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
										'size' => '36',
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
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'affx_sp_typography_tab_normal_end' => array(
							'type' => 'tab_end',
						),
						'affx_sp_typography_tab_hover'     => array(
							'type'  => 'tab_start',
							'label' => __( 'Hover', 'affiliatex' ),
						),
						'productTitleHoverTypography'      => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Title', 'affiliatex' ),
							'selector'       => $this->select_element( array( 'title', ':hover' ) ),
							'fields_options' => $this->get_sp_hover_typography_options( $this->select_element( 'title' ) ),
							'exclude'        => array( 'text_decoration' ),
							'condition'      => array(
								'edTitle' => 'true',
							),
						),
						'productSubtitleHoverTypography'   => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Subtitle', 'affiliatex' ),
							'selector'       => $this->select_element( array( 'subtitle', ':hover' ) ),
							'fields_options' => $this->get_sp_hover_typography_options( $this->select_element( 'subtitle' ) ),
							'exclude'        => array( 'text_decoration' ),
							'condition'      => array(
								'edSubtitle' => 'true',
							),
						),
						'pricingHoverTypography'           => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Price', 'affiliatex' ),
							'selector'       => $this->select_element( array( 'price', ':hover' ) ),
							'fields_options' => $this->get_sp_hover_typography_options( $this->select_element( 'price' ) ),
							'exclude'        => array( 'text_decoration' ),
							'condition'      => array(
								'edPricing' => 'true',
							),
						),
						'productContentHoverTypography'    => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Content', 'affiliatex' ),
							'selector'       => $this->select_element( array( 'content', ':hover' ) ),
							'fields_options' => $this->get_sp_hover_typography_options( $this->select_element( 'content' ) ),
							'exclude'        => array( 'text_decoration' ),
							'condition'      => array(
								'edContent' => 'true',
							),
						),
						'readMoreHoverTypography'          => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Read More', 'affiliatex' ),
							'selector'       => $this->select_element( 'readmore' ) . ' .affx-readmore-btn:hover',
							'fields_options' => $this->get_sp_hover_typography_options( $this->select_element( 'readmore' ) . ' .affx-readmore-btn' ),
							'exclude'        => array( 'text_decoration' ),
							'condition'      => array(
								'edContent'  => 'true',
								'edReadMore' => 'true',
							),
						),
						'ribbonContentHoverTypography'     => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Product Ribbon', 'affiliatex' ),
							'selector'       => $this->select_element( array( 'ribbon', ':hover' ) ),
							'fields_options' => $this->get_sp_hover_typography_options( $this->select_element( 'ribbon' ) ),
							'exclude'        => array( 'text_decoration' ),
							'condition'      => array(
								'edRibbon' => 'true',
							),
						),
						'numRatingHoverTypography'         => array(
							'type'           => Group_Control_Typography::get_type(),
							'label'          => __( 'Score Box', 'affiliatex' ),
							'selector'       => $this->select_element( array( 'rating-number', ':hover' ) ),
							'fields_options' => $this->get_sp_hover_typography_options( $this->select_element( 'rating-number' ) ),
							'exclude'        => array( 'text_decoration' ),
							'condition'      => array(
								'edRatings'   => 'true',
								'PricingType' => 'number',
							),
						),
						'affx_sp_typography_tab_hover_end' => array(
							'type' => 'tab_end',
						),
						'affx_sp_typography_tabs_end'      => array(
							'type' => 'tabs_end',
						),
					),
				),

				'affx_sp_spacing'           => array(
					'label'  => __( 'Spacing', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_STYLE,
					'fields' => array(
						'affx_sp_spacing_tabs'           => array(
							'type' => 'tabs_start',
						),
						'affx_sp_spacing_tab_normal'     => array(
							'type'  => 'tab_start',
							'label' => __( 'Normal', 'affiliatex' ),
						),
						'imagePadding'                   => array(
							'label'      => __( 'Image Padding', 'affiliatex' ),
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
								$this->select_element( 'image' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
						),
						'contentMargin'                  => array(
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
						'contentSpacing'                 => array(
							'label'      => __( 'Padding', 'affiliatex' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
							'default'    => array(
								'unit'     => 'px',
								'top'      => '30',
								'right'    => '25',
								'bottom'   => '30',
								'left'     => '25',
								'isLinked' => false,
							),
							'selectors'  => array(
								$this->select_element( 'wrapper' ) . '.product-layout-1 .affx-sp-content-wrapper' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . '.product-layout-2 .title-wrapper' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . '.product-layout-2 .affx-single-product-content' => 'padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . '.product-layout-2 .button-wrapper' => 'padding-bottom: {{BOTTOM}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . '.product-layout-3 .affx-sp-inner' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
							),
						),
						'affx_sp_spacing_tab_normal_end' => array(
							'type' => 'tab_end',
						),
						'affx_sp_spacing_tab_hover'      => array(
							'type'  => 'tab_start',
							'label' => __( 'Hover', 'affiliatex' ),
						),
						'imageHoverPadding'              => array(
							'label'      => __( 'Image Padding', 'affiliatex' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
							'selectors'  => array(
								$this->select_element( array( 'wrapper', ':hover' ) ) . ' .affx-sp-img-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								$this->select_element( 'image' ) => $this->get_sp_hover_transition( array( 'padding' ) ),
							),
						),
						'contentHoverMargin'             => array(
							'label'      => __( 'Margin', 'affiliatex' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
							'selectors'  => array(
								$this->select_element( array( 'wrapper', ':hover' ) ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) => $this->get_sp_hover_transition( array( 'margin' ) ),
							),
						),
						'contentHoverSpacing'            => array(
							'label'      => __( 'Padding', 'affiliatex' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
							'selectors'  => array(
								$this->select_element( 'wrapper' ) . '.product-layout-1:hover .affx-sp-content-wrapper' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . '.product-layout-2:hover .title-wrapper' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . '.product-layout-2:hover .affx-single-product-content' => 'padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . '.product-layout-2:hover .button-wrapper' => 'padding-bottom: {{BOTTOM}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . '.product-layout-3:hover .affx-sp-inner' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . '.product-layout-1 .affx-sp-content-wrapper' => $this->get_sp_hover_transition( array( 'padding' ) ),
								$this->select_element( 'wrapper' ) . '.product-layout-2 .title-wrapper' => $this->get_sp_hover_transition( array( 'padding' ) ),
								$this->select_element( 'wrapper' ) . '.product-layout-2 .affx-single-product-content' => $this->get_sp_hover_transition( array( 'padding' ) ),
								$this->select_element( 'wrapper' ) . '.product-layout-2 .button-wrapper' => $this->get_sp_hover_transition( array( 'padding' ) ),
								$this->select_element( 'wrapper' ) . '.product-layout-3 .affx-sp-inner' => $this->get_sp_hover_transition( array( 'padding' ) ),
							),
						),
						'affx_sp_spacing_tab_hover_end'  => array(
							'type' => 'tab_end',
						),
						'affx_sp_spacing_tabs_end'       => array(
							'type' => 'tabs_end',
						),
					),
				),

				'affx_sp_style_border'      => array(
					'label'  => __( 'Border', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_STYLE,
					'fields' => array(
						'affx_sp_border_tabs'           => array(
							'type' => 'tabs_start',
						),
						'affx_sp_border_tab_normal'     => array(
							'type'  => 'tab_start',
							'label' => __( 'Normal', 'affiliatex' ),
						),
						'productBorder'                 => array(
							'type'           => \Elementor\Group_Control_Border::get_type(),
							'name'           => 'productBorder',
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
						),
						'productBorderRadius'           => array(
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
						),
						'productImageBorderRadius'      => array(
							'label'      => esc_html__( 'Image Border Radius', 'affiliatex' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%' ),
							'default'    => array(
								'top'      => 0,
								'right'    => 0,
								'bottom'   => 0,
								'left'     => 0,
								'unit'     => 'px',
								'isLinked' => false,
							),
							'selectors'  => array(
								$this->select_element( 'wrapper' ) . ' .affx-sp-img-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) . ' .affx-sp-img-wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							),
							'condition'  => array(
								'edProductImage' => 'true',
							),
						),
						'productShadow'                 => array(
							'type'          => \Elementor\Group_Control_Box_Shadow::get_type(),
							'name'          => 'productShadow',
							'selector'      => $this->select_element( 'wrapper' ),
							'field_options' => array(
								'box_shadow_type' => array(
									'default' => 'no',
								),
								'box_shadow'      => array(
									'default' => array(
										'v_offset' => 5,
										'h_offset' => 0,
										'blur'     => 20,
										'spread'   => 0,
										'color'    => 'rgba(93, 113, 147, 0.2)',
										'inset'    => false,
									),
								),
							),
						),
						'affx_sp_border_tab_normal_end' => array(
							'type' => 'tab_end',
						),
						'affx_sp_border_tab_hover'      => array(
							'type'  => 'tab_start',
							'label' => __( 'Hover', 'affiliatex' ),
						),
						'productHoverBorder'            => array(
							'type'           => \Elementor\Group_Control_Border::get_type(),
							'name'           => 'productHoverBorder',
							'selector'       => $this->select_element( array( 'wrapper', ':hover' ) ),
							'fields_options' => array(
								'color' => array(
									'selectors' => array(
										'{{SELECTOR}}' => 'border-color: {{VALUE}};',
										$this->select_element( 'wrapper' ) => $hover_transition,
									),
								),
							),
						),
						'productHoverBorderRadius'      => array(
							'label'      => esc_html__( 'Border Radius', 'affiliatex' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
							'selectors'  => array(
								$this->select_element( array( 'wrapper', ':hover' ) ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
								$this->select_element( 'wrapper' ) => $hover_transition,
							),
						),
						'productHoverShadow'            => array(
							'type'           => \Elementor\Group_Control_Box_Shadow::get_type(),
							'name'           => 'productHoverShadow',
							'selector'       => $this->select_element( array( 'wrapper', ':hover' ) ),
							'fields_options' => array(
								'box_shadow' => array(
									'selectors' => array(
										'{{SELECTOR}}' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}} {{box_shadow_position.VALUE}};',
										$this->select_element( 'wrapper' ) => $hover_transition,
									),
								),
							),
						),
						'affx_sp_border_tab_hover_end'  => array(
							'type' => 'tab_end',
						),
						'affx_sp_border_tabs_end'       => array(
							'type' => 'tabs_end',
						),
					),
				),
				'affx_sp_style_divider'     => array(
					'label'  => __( 'Divider', 'affiliatex' ),
					'tab'    => Controls_Manager::TAB_STYLE,
					'fields' => array(
						'edDivider'           => array(
							'label'        => esc_html__( 'Enable Divider', 'affiliatex' ),
							'type'         => Controls_Manager::SWITCHER,
							'label_on'     => esc_html__( 'On', 'affiliatex' ),
							'label_off'    => esc_html__( 'Off', 'affiliatex' ),
							'return_value' => 'true',
							'default'      => 'false',
						),
						'productDividerStyle' => array(
							'label'     => esc_html__( 'Divider Style', 'affiliatex' ),
							'type'      => Controls_Manager::SELECT,
							'default'   => 'solid',
							'options'   => array(
								'none'   => esc_html__( 'None', 'affiliatex' ),
								'solid'  => esc_html__( 'Solid', 'affiliatex' ),
								'dashed' => esc_html__( 'Dashed', 'affiliatex' ),
								'dotted' => esc_html__( 'Dotted', 'affiliatex' ),
								'double' => esc_html__( 'Double', 'affiliatex' ),
								'groove' => esc_html__( 'Groove', 'affiliatex' ),
							),
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .title-wrapper' => 'border-bottom-style: {{VALUE}};',
							),
							'condition' => array(
								'edDivider' => 'true',
							),
						),
						'productDividerWidth' => array(
							'label'      => esc_html__( 'Divider Width', 'affiliatex' ),
							'type'       => Controls_Manager::SLIDER,
							'size_units' => array( 'px' ),
							'range'      => array(
								'px' => array(
									'min'  => 0,
									'max'  => 5,
									'step' => 1,
								),
							),
							'default'    => array(
								'unit' => 'px',
								'size' => 1,
							),
							'selectors'  => array(
								$this->select_element( 'wrapper' ) . ' .title-wrapper' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
							),
							'condition'  => array(
								'edDivider' => 'true',
							),
						),
						'productDividerColor' => array(
							'label'     => esc_html__( 'Divider Color', 'affiliatex' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								$this->select_element( 'wrapper' ) . ' .title-wrapper' => 'border-color: {{VALUE}}',
							),
							'condition' => array(
								'edDivider' => 'true',
							),
						),
						'amazonAttributes'    => array(
							'type'    => Controls_Manager::HIDDEN,
							'default' => array(
								array(
									'field'      => 'title',
									'blockField' => array(
										'name'     => 'productTitle',
										'type'     => 'text',
										'defaults' => array(
											'productTitle' => $defaults['productTitle'],
										),
									),
									'type'       => 'text',
								),
								array(
									'field'      => 'features',
									'blockField' => array(
										'name'       => 'productContentListAmazon',
										'type'       => 'list',
										'string'     => 'productContent',
										'list'       => 'productContentList',
										'defaults'   => array(
											'productContentListAmazon' => '',
											'productContentType' => 'list',
										),
										'conditions' => array(
											'productContentType' => 'amazon',
										),
									),
									'type'       => 'list',
								),
								array(
									'field'      => 'display_price',
									'blockField' => array(
										'name'       => 'productSalePrice',
										'type'       => 'text',
										'format'     => 'price',
										'defaults'   => array(
											'productSalePrice' => $defaults['productSalePrice'],
										),
										'conditions' => array(
											'edPricing' => 'true',
										),
									),
									'type'       => 'text',
								),
								array(
									'field'      => 'regular_display_price',
									'blockField' => array(
										'name'       => 'productPrice',
										'type'       => 'text',
										'format'     => 'price',
										'defaults'   => array(
											'productPrice' => $defaults['productPrice'],
										),
										'conditions' => array(
											'edPricing' => 'true',
										),
									),
									'type'       => 'text',
								),
								array(
									'field'      => 'images',
									'blockField' => array(
										'name'             => 'ImgUrl',
										'type'             => 'image',
										'galleryField'     => 'galleryImages',
										'useMultipleField' => 'useMultipleImages',
										'defaults'         => array(
											'ImgUrl' => '',
										),
									),
									'type'       => 'image',
								),
								array(
									'field'      => 'url',
									'blockField' => array(
										'name'       => 'button_child_buttonURL',
										'type'       => 'link',
										'defaults'   => array(
											'button_child_buttonURL' => '',
										),
										'conditions' => array(
											'blockUrl' => '[@copy]',
										),
									),
									'type'       => 'link',
								),
								array(
									'field'      => 'url',
									'blockField' => array(
										'name'     => 'blockUrl',
										'type'     => 'link',
										'defaults' => array(
											'blockUrl' => '',
										),
									),
									'type'       => 'link',
								),
							),
						),
					),
				),
			)
		);
	}

	/**
	 * Render for Elementor
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->render_sp( $settings );
	}

	/**
	 * Suffix added so it is easy to be called from other blocks.
	 *
	 * @param mixed $settings
	 * @param mixed $child_attributes
	 * @return void
	 */
	public function render_sp( $settings, $child_attributes = null ) {
		$attributes = $this->parse_attributes( $settings );

		if ( ! empty( $attributes['edReadMore'] ) && 'true' === $attributes['edReadMore'] && ! empty( $attributes['productContentListAmazon'] ) && has_shortcode( $attributes['productContentListAmazon'], 'affiliatex-product' ) ) {
			if ( strpos( $attributes['productContentListAmazon'], 'limit=' ) !== false ) {
				$attributes['productContentListAmazon'] = preg_replace( '/limit="[^"]*"/', 'limit="0,0"', $attributes['productContentListAmazon'] );
			} else {
				$attributes['productContentListAmazon'] = str_replace( ']', ' limit="0,0"]', $attributes['productContentListAmazon'] );
			}
		}

		$attributes                   = WidgetHelper::process_attributes( $attributes );
		$attributes                   = $this->map_sp_visibility_attributes( $attributes );
		$attributes['block_id']       = $this->get_id();
		$attributes['ImgUrl']         = $settings['ImgUrl']['url'] ?? $settings['ImgUrl'];
		$attributes['ratingStarSize'] = $attributes['ratingStarSize']['size'] ?? 25;

		if ( ! empty( $attributes['productContentListAmazon'] ) ) {
			$attributes['productContentList'] = $attributes['productContentListAmazon'];
		} elseif ( ! empty( $attributes['productContentList'] ) && is_array( $attributes['productContentList'] ) ) {
			$attributes['productContentList'] = ElementorHelper::extract_list_items( $attributes['productContentList'] );
		}

		if ( ! empty( $attributes['productIconList'] ) ) {
			$attributes['productIconList'] = ElementorHelper::extract_icon( $attributes['productIconList'] );
		}

		$button_child = '';

		if ( isset( $attributes['edButton'] ) ) {
			if ( ! $child_attributes ) {
				$child_attributes = ChildHelper::extract_attributes( $attributes, self::$inner_button_config );
			}

			// Pass parent block clickable state to button for server-side rendering
			$child_attributes['parent_attributes'] = array(
				'edFullBlockLink' => isset( $attributes['edFullBlockLink'] ) ? $attributes['edFullBlockLink'] : false,
				'blockName'       => 'single-product',
			);

			ob_start();
			$this->render_button( $child_attributes );
			$button_child = ob_get_clean();
		}

		echo AffiliateX_Helpers::kses( $this->render_sp_template( $attributes, $button_child ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Core render function
	 *
	 * @param array $attributes
	 * @param string $content
	 * @return string
	 */
	public function render_sp_template( array $attributes, string $content = '' ): string {
		$attributes = $this->parse_attributes( $attributes );
		extract( $attributes );

		// Markup classes always reflect the desktop value, per-device overrides come from the generated CSS.
		$productImageAlign           = AffiliateX_Helpers::get_responsive_value( $productImageAlign ?? 'left' );
		$productImageVerticalAlign   = AffiliateX_Helpers::get_responsive_value( $productImageVerticalAlign ?? 'top' );
		$productImageHorizontalAlign = AffiliateX_Helpers::get_responsive_value( $productImageHorizontalAlign ?? 'center' );
		$productStarRatingAlign      = AffiliateX_Helpers::get_responsive_value( $productStarRatingAlign ?? 'left' );
		$productRatingAlign          = AffiliateX_Helpers::get_responsive_value( $productRatingAlign ?? 'right' );
		$productPricingAlign         = AffiliateX_Helpers::get_responsive_value( $productPricingAlign ?? 'left' );
		$ribbonAlign                 = AffiliateX_Helpers::get_responsive_value( $ribbonAlign ?? 'left' );
		$ratingStarSize              = AffiliateX_Helpers::get_responsive_value( $ratingStarSize ?? 25 );
		$buttonsGap                  = AffiliateX_Helpers::get_responsive_value( $buttonsGap ?? 10 );

		if ( is_array( $productContentList ) && count( $productContentList ) > 0 && isset( $productContentList[0]['list'] ) && is_string( $productContentList[0]['list'] ) && has_shortcode( $productContentList[0]['list'], 'affiliatex-product' ) ) {
			$shortcode_content = $productContentList[0]['list'];

			if ( ! empty( $edReadMore ) ) {
				if ( strpos( $shortcode_content, 'limit=' ) !== false ) {
					$shortcode_content = preg_replace( '/limit="[^"]*"/', 'limit="0,0"', $shortcode_content );
				} else {
					$shortcode_content = str_replace( ']', ' limit="0,0"]', $shortcode_content );
				}
			}

			$productContentList = do_shortcode( $shortcode_content );
			$productContentList = json_decode( $productContentList, true );
		}

		if ( self::IS_ELEMENTOR ) {
			$wrapper_attributes = sprintf(
				'id="%s" class="%s" data-widget-type="%s"',
				"affiliatex-single-product-style-$block_id",
				'affx-amazon-item',
				'affiliatex-single-product'
			);
		} else {
			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'id' => "affiliatex-single-product-style-$block_id",
				)
			);
		}

		$productTitleTag    = AffiliateX_Helpers::validate_tag( $productTitleTag, 'h2' );
		$productSubTitleTag = AffiliateX_Helpers::validate_tag( $productSubTitleTag, 'h3' );

		// Clickable wrapper config for inner wrapper
		$inner_wrapper_config = AffiliateX_Helpers::get_clickable_wrapper_config(
			array(
				'edFullBlockLink'   => $edFullBlockLink,
				'blockUrl'          => $blockUrl,
				'blockRelNoFollow'  => $blockRelNoFollow,
				'blockRelSponsored' => $blockRelSponsored,
				'blockDownload'     => $blockDownload,
				'blockOpenInNewTab' => $blockOpenInNewTab,
			)
		);

		$layoutClass = '';
		if ( $productLayout === 'layoutOne' ) {
			$layoutClass = ' product-layout-1';
		} elseif ( $productLayout === 'layoutTwo' ) {
			$layoutClass = ' product-layout-2';
		} elseif ( $productLayout === 'layoutThree' ) {
			$layoutClass = ' product-layout-3';
		}

		if ( strpos( $content, $layoutClass ) !== false ) {
			return str_replace( 'app/src/images/fallback', 'src/images/fallback', $content );
		}

		$ratingClass = '';

		if ( $PricingType === 'picture' ) {
			$ratingClass = 'star-rating';
		} elseif ( $PricingType === 'number' ) {
			$ratingClass = 'number-rating';
		}

		$imageAlign   = $edProductImage ? 'image-' . $productImageAlign : '';
		$ribbonLayout = '';

		if ( $productRibbonLayout === 'one' ) {
			$ribbonLayout = ' ribbon-layout-one';
		} elseif ( $productRibbonLayout === 'two' ) {
			$ribbonLayout = ' ribbon-layout-two';
		}

		$imageClass               = ! $edProductImage ? 'no-image' : '';
		$productRatingNumberClass = $PricingType === 'number' ? 'rating-align-' . $productRatingAlign : '';
		$ImageURL                 = $productImageType === 'default' ? $ImgUrl : $productImageExternal;
		$isSiteStripe             = 'sitestripe' === $productImageType && '' !== $productImageSiteStripe ? true : false;
		$productImage             = AffiliateX_Helpers::affiliatex_get_media_image_html( $ImgID ?? 0, $ImageURL, $ImgAlt ?? '', $isSiteStripe, $productImageSiteStripe );

		$slider_data  = $this->build_slider_data(
			$useMultipleImages,
			$galleryImages,
			$sliderShowArrows,
			$sliderShowPagination,
			$sliderArrowColor,
			$sliderArrowHoverColor,
			$sliderAutoplay,
			$sliderAutoplaySpeed
		);
		$useSlider    = $slider_data['use_slider'];
		$sliderImages = $slider_data['slider_images'];
		$sliderConfig = $slider_data['slider_config'];

		$buttonDirection = $buttonDirection ?? 'column';
		$buttonsGap      = $buttonsGap ?? 10;

		// Gutenberg gets per-device --button-gap from generated CSS, Elementor keeps the inline fallback.
		$useInlineButtonGap = self::IS_ELEMENTOR;

		$valid_valigns             = array( 'top', 'middle', 'bottom' );
		$valid_haligns             = array( 'left', 'center', 'right' );
		$imageVerticalAlignClass   = isset( $productImageVerticalAlign ) && in_array( $productImageVerticalAlign, $valid_valigns, true ) ? 'img-valign-' . $productImageVerticalAlign : '';
		$imageHorizontalAlignClass = isset( $productImageHorizontalAlign ) && in_array( $productImageHorizontalAlign, $valid_haligns, true ) ? 'img-halign-' . $productImageHorizontalAlign : '';

		$list = '';

		if ( $edContent && isset( $productContentList ) && ! empty( $productContentList ) ) {
			$list = AffiliateX_Helpers::render_list(
				array(
					'listType'      => $ContentListType,
					'unorderedType' => 'icon',
					'listItems'     => $productContentList ?? array(),
					'iconName'      => isset( $productIconList['value'] ) ? $productIconList['value'] : '',
				)
			);
		}

		$tag                      = $inner_wrapper_config['tag'];
		$inner_wrapper_attributes = sprintf(
			'class="%s" %s',
			implode(
				' ',
				array(
					'affx-single-product-wrapper' . $layoutClass,
					'affx-whole-block-clickable',
				)
			),
			$inner_wrapper_config['attributes']
		);

		// When the wrapper renders as <a>, inline anchors inside RichText fields
		// produce invalid nested anchors that browsers silently reflow, breaking
		// the layout. Strip them so the wrapper link wins.
		if ( $tag === 'a' ) {
			$productTitle     = AffiliateX_Helpers::strip_anchor_tags( $productTitle );
			$productSubTitle  = AffiliateX_Helpers::strip_anchor_tags( $productSubTitle );
			$ribbonText       = AffiliateX_Helpers::strip_anchor_tags( $ribbonText ?? '' );
			$productContent   = AffiliateX_Helpers::strip_anchor_tags( $productContent );
			$productSalePrice = AffiliateX_Helpers::strip_anchor_tags( $productSalePrice );
			$productPrice     = AffiliateX_Helpers::strip_anchor_tags( $productPrice );
			$list             = AffiliateX_Helpers::strip_anchor_tags( $list );
		}

		$readmore_config = array(
			'edReadMore'         => $edReadMore,
			'productContentType' => $productContentType,
			'descriptionLength'  => $descriptionLength,
			'listItemCount'      => $listItemCount,
		);

		$readmore_attrs = AffiliateX_Helpers::get_readmore_attrs( $readmore_config );
		$readmore_btn   = AffiliateX_Helpers::get_readmore_btn( $edReadMore, $readMoreText ?? 'Read more', $readLessText ?? 'Read less' );

		// Per-device visibility classes consumed by the layout partials.
		$ribbonHideClass   = AffiliateX_Helpers::get_responsive_hide_classes( $ribbonHideOn ?? null );
		$imageHideClass    = AffiliateX_Helpers::get_responsive_hide_classes( $imageHideOn ?? null );
		$titleHideClass    = AffiliateX_Helpers::get_responsive_hide_classes( $titleHideOn ?? null );
		$subtitleHideClass = AffiliateX_Helpers::get_responsive_hide_classes( $subtitleHideOn ?? null );
		$ratingHideClass   = AffiliateX_Helpers::get_responsive_hide_classes( $ratingHideOn ?? null );
		$pricingHideClass  = AffiliateX_Helpers::get_responsive_hide_classes( $pricingHideOn ?? null );
		$contentHideClass  = AffiliateX_Helpers::get_responsive_hide_classes( $contentHideOn ?? null );
		$buttonHideClass   = AffiliateX_Helpers::get_responsive_hide_classes( $buttonHideOn ?? null );

		ob_start();
		// Directly include the template file instead of get_template_path() to make it work as child widget.
		include AFFILIATEX_PLUGIN_DIR . '/templates/blocks/single-product.php';
		$output = ob_get_clean();

		return $output;
	}
}
