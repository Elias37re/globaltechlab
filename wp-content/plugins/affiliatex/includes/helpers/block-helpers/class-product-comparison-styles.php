<?php

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\HoverStyles;

require_once __DIR__ . '/class-affiliatex-block-styles-base.php';

/**
 * Product Comparison Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Product_Comparison_Styles extends AffiliateX_Block_Styles_Base {

	private const CONTAINER   = ' .affx-product-comparison-block-container';
	private const TITLE       = ' .affx-comparison-title';
	private const RIBBON      = ' .affx-versus-table-wrap .affx-pc-ribbon';
	private const PRICE       = ' .affx-versus-table-wrap .affx-price';
	private const TD          = ' .affx-versus-table-wrap td';
	private const TH          = ' .affx-versus-table-wrap th';
	private const CONTENT_TD  = ' .affx-versus-table-wrap .affx-product-versus-table td';
	private const IMG         = ' .affx-versus-table-wrap .affx-versus-product-img';
	private const BUTTON      = ' .affx-versus-table-wrap .affiliatex-button.affx-winner-button';
	private const BUTTON_FONT = ' .affx-versus-table-wrap .affiliatex-button';
	private const ROW_BG      = ' .affx-versus-table-wrap .affx-product-versus-table tbody tr:hover td:not(.affx-specification-add-col):not(.affx-specification-remove-col)';

	protected static function css_id_prefix(): string {
		return '#affiliatex-product-comparison-blocks-style-';
	}

	public static function block_fonts( $attr ) {
		return array(
			'titleTypography'   => isset( $attr['titleTypography'] ) ? $attr['titleTypography'] : array(),
			'ribbonTypography'  => isset( $attr['ribbonTypography'] ) ? $attr['ribbonTypography'] : array(),
			'priceTypography'   => isset( $attr['priceTypography'] ) ? $attr['priceTypography'] : array(),
			'buttonTypography'  => isset( $attr['buttonTypography'] ) ? $attr['buttonTypography'] : array(),
			'contentTypography' => isset( $attr['contentTypography'] ) ? $attr['contentTypography'] : array(),
		);
	}

	/**
	 * Per-device rules for the promoted attributes, mirrors styling.js. Scalars keep the legacy desktop output.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_promoted_selectors( array &$buckets, array $attr ): void {
		foreach ( array( 'tablet', 'mobile' ) as $device ) {
			if ( HoverStyles::is_responsive( $attr['pcTitleAlign'] ?? null ) ) {
				$align = AffiliateX_Helpers::get_responsive_value( $attr['pcTitleAlign'], $device );

				if ( is_string( $align ) && '' !== $align ) {
					HoverStyles::merge_selector( $buckets[ $device ], self::TITLE, array( 'text-align' => $align ) );
				}
			}
		}
	}

	/**
	 * Hover rules for the wave-3 hover attributes, mirrors product-comparison/styling.js.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_hover_selectors( array &$buckets, array $attr ): void {
		$typos = array(
			$attr['titleHoverTypography'] ?? null,
			$attr['ribbonHoverTypography'] ?? null,
			$attr['priceHoverTypography'] ?? null,
			$attr['buttonHoverTypography'] ?? null,
			$attr['contentHoverTypography'] ?? null,
		);

		$extras = array();

		if ( HoverStyles::has_typography_value( $typos, 'size' ) ) {
			$extras[] = 'font-size';
		}

		if ( HoverStyles::has_typography_value( $typos, 'letter-spacing' ) ) {
			$extras[] = 'letter-spacing';
		}

		if ( HoverStyles::has_spacing_value( $attr['hoverMargin'] ?? null ) || HoverStyles::has_spacing_value( $attr['buttonHoverMargin'] ?? null ) ) {
			$extras[] = 'margin';
		}

		if ( HoverStyles::has_spacing_value( $attr['hoverPadding'] ?? null ) || HoverStyles::has_spacing_value( $attr['imageHoverPadding'] ?? null ) || HoverStyles::has_spacing_value( $attr['buttonHoverPadding'] ?? null ) ) {
			$extras[] = 'padding';
		}

		$transition = HoverStyles::get_transition( $extras );

		if ( ! empty( $attr['titleHoverColor'] ) && is_string( $attr['titleHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, self::TITLE . ':hover', array( 'color' => $attr['titleHoverColor'] ), array( self::TITLE ) );
		}

		if ( ! empty( $attr['ribbonTextHoverColor'] ) && is_string( $attr['ribbonTextHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, self::RIBBON . ':hover', array( 'color' => $attr['ribbonTextHoverColor'] ), array( self::RIBBON ) );
		}

		if ( ! empty( $attr['ribbonHoverColor'] ) && is_string( $attr['ribbonHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, self::RIBBON . ':hover', array( 'background' => $attr['ribbonHoverColor'] ), array( self::RIBBON ) );
			HoverStyles::merge_selector( $buckets['desktop'], self::RIBBON . ':hover::before', array( 'background' => $attr['ribbonHoverColor'] ) );
			HoverStyles::merge_selector( $buckets['desktop'], self::RIBBON . ':hover::after', array( 'background' => $attr['ribbonHoverColor'] ) );
		}

		if ( ! empty( $attr['priceHoverColor'] ) && is_string( $attr['priceHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, self::PRICE . ':hover', array( 'color' => $attr['priceHoverColor'] ), array( self::PRICE ) );
		}

		if ( ! empty( $attr['contentHoverColor'] ) && is_string( $attr['contentHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, self::CONTENT_TD . ':hover', array( 'color' => $attr['contentHoverColor'] ), array( self::TD ) );
		}

		if ( ! empty( $attr['tableRowBgHoverColor'] ) && is_string( $attr['tableRowBgHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, self::ROW_BG, array( 'background' => $attr['tableRowBgHoverColor'] ), array( self::TD ) );
		}

		self::apply_container_hover( $buckets, $attr, $transition );
		self::apply_button_hover( $buckets, $attr, $transition );
		self::apply_typography_hover( $buckets, $attr, $transition );
	}

	/**
	 * Container :hover (background/border/shadow/radius/margin) plus per-device cell padding, mirrors styling.js.
	 *
	 * @param array  $buckets Buckets keyed by device, by reference.
	 * @param array  $attr Block attributes.
	 * @param string $transition Transition shorthand.
	 * @return void
	 */
	private static function apply_container_hover( array &$buckets, array $attr, string $transition ): void {
		$container_hover = HoverStyles::get_background_styles(
			$attr['bgHoverType'] ?? '',
			$attr['bgType'] ?? '',
			$attr['bgHoverColor'] ?? '',
			$attr['bgHoverGradient'] ?? ''
		);

		$container_hover = array_merge( $container_hover, HoverStyles::get_border_styles( $attr['hoverBorder'] ?? null ) );
		$container_hover = array_merge( $container_hover, HoverStyles::get_shadow_styles( $attr['hoverShadow'] ?? null ) );

		$desktop_radius = HoverStyles::get_radius_value( $attr['hoverBorderRadius'] ?? null, 'desktop' );

		if ( '' !== $desktop_radius ) {
			$container_hover['border-radius'] = $desktop_radius;
		}

		if ( ! empty( $container_hover ) ) {
			HoverStyles::merge_selector( $buckets['desktop'], self::CONTAINER . ':hover', $container_hover );
			HoverStyles::merge_selector( $buckets['desktop'], self::CONTAINER, array( 'transition' => $transition ) );
		}

		foreach ( array( 'tablet', 'mobile' ) as $device ) {
			$radius = HoverStyles::get_radius_value( $attr['hoverBorderRadius'] ?? null, $device );

			if ( '' !== $radius ) {
				HoverStyles::merge_selector( $buckets[ $device ], self::CONTAINER . ':hover', array( 'border-radius' => $radius ) );
				HoverStyles::merge_selector( $buckets['desktop'], self::CONTAINER, array( 'transition' => $transition ) );
			}
		}

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			$margin_hover = HoverStyles::get_spacing_styles( $attr['hoverMargin'] ?? null, $device, 'margin' );

			if ( ! empty( $margin_hover ) ) {
				HoverStyles::merge_selector( $buckets[ $device ], self::CONTAINER . ':hover', $margin_hover );
				HoverStyles::merge_selector( $buckets['desktop'], self::CONTAINER, array( 'transition' => $transition ) );
			}

			$padding_hover = HoverStyles::get_spacing_styles( $attr['hoverPadding'] ?? null, $device, 'padding' );

			if ( ! empty( $padding_hover ) ) {
				HoverStyles::merge_selector( $buckets[ $device ], self::CONTAINER . ':hover' . self::TD, $padding_hover );
				HoverStyles::merge_selector( $buckets[ $device ], self::CONTAINER . ':hover' . self::TH, $padding_hover );
				HoverStyles::merge_selector( $buckets['desktop'], self::TD, array( 'transition' => $transition ) );
				HoverStyles::merge_selector( $buckets['desktop'], self::TH, array( 'transition' => $transition ) );
			}

			$image_padding_hover = HoverStyles::get_spacing_styles( $attr['imageHoverPadding'] ?? null, $device, 'padding' );

			if ( ! empty( $image_padding_hover ) ) {
				HoverStyles::merge_selector( $buckets[ $device ], self::IMG . ':hover', $image_padding_hover );
				HoverStyles::merge_selector( $buckets['desktop'], self::IMG, array( 'transition' => $transition ) );
			}
		}
	}

	/**
	 * Winner button :hover (border/shadow/radius/spacing), mirrors styling.js.
	 *
	 * @param array  $buckets Buckets keyed by device, by reference.
	 * @param array  $attr Block attributes.
	 * @param string $transition Transition shorthand.
	 * @return void
	 */
	private static function apply_button_hover( array &$buckets, array $attr, string $transition ): void {
		$button_hover = HoverStyles::get_border_styles( $attr['buttonHoverBorder'] ?? null, false );
		$button_hover = array_merge( $button_hover, HoverStyles::get_shadow_styles( $attr['buttonHoverShadow'] ?? null ) );

		$has_button_hover = ! empty( $button_hover );

		HoverStyles::merge_selector( $buckets['desktop'], self::BUTTON . ':hover', $button_hover );

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			$radius = HoverStyles::get_radius_value( $attr['buttonHoverBorderRadius'] ?? null, $device );

			if ( '' !== $radius ) {
				$has_button_hover = true;
				HoverStyles::merge_selector( $buckets[ $device ], self::BUTTON . ':hover', array( 'border-radius' => $radius ) );
			}

			$spacing_hover = array_merge(
				HoverStyles::get_spacing_styles( $attr['buttonHoverMargin'] ?? null, $device, 'margin' ),
				HoverStyles::get_spacing_styles( $attr['buttonHoverPadding'] ?? null, $device, 'padding' )
			);

			if ( ! empty( $spacing_hover ) ) {
				$has_button_hover = true;
				HoverStyles::merge_selector( $buckets[ $device ], self::BUTTON . ':hover', $spacing_hover );
			}
		}

		if ( $has_button_hover ) {
			HoverStyles::merge_selector( $buckets['desktop'], self::BUTTON, array( 'transition' => $transition ) );
		}
	}

	/**
	 * Per-element hover typography, mirrors styling.js.
	 *
	 * @param array  $buckets Buckets keyed by device, by reference.
	 * @param array  $attr Block attributes.
	 * @param string $transition Transition shorthand.
	 * @return void
	 */
	private static function apply_typography_hover( array &$buckets, array $attr, string $transition ): void {
		$rules = array(
			array(
				'typography' => $attr['titleHoverTypography'] ?? null,
				'base'       => self::TITLE,
				'hover'      => self::TITLE . ':hover',
			),
			array(
				'typography' => $attr['ribbonHoverTypography'] ?? null,
				'base'       => self::RIBBON,
				'hover'      => self::RIBBON . ':hover',
			),
			array(
				'typography' => $attr['priceHoverTypography'] ?? null,
				'base'       => self::PRICE,
				'hover'      => self::PRICE . ':hover',
			),
			array(
				'typography' => $attr['buttonHoverTypography'] ?? null,
				'base'       => self::BUTTON_FONT,
				'hover'      => self::BUTTON_FONT . ':hover',
			),
			array(
				'typography' => $attr['contentHoverTypography'] ?? null,
				'base'       => self::TD,
				'hover'      => self::CONTENT_TD . ':hover',
			),
		);

		foreach ( $rules as $rule ) {
			$has_styles = false;

			foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
				$styles = HoverStyles::get_typography_styles( $rule['typography'], $device );

				if ( empty( $styles ) ) {
					continue;
				}

				$has_styles = true;
				HoverStyles::merge_selector( $buckets[ $device ], $rule['hover'], $styles );
			}

			if ( $has_styles ) {
				HoverStyles::merge_selector( $buckets['desktop'], $rule['base'], array( 'transition' => $transition ) );
			}
		}
	}

	public static function get_selectors( $attr ) {

		$customization_data     = affx_get_customization_settings();
		$global_font_family     = isset( $customization_data['typography']['family'] ) ? $customization_data['typography']['family'] : 'Default';
		$global_font_color      = isset( $customization_data['fontColor'] ) ? $customization_data['fontColor'] : '#292929';
		$global_btn_color       = isset( $customization_data['btnColor'] ) ? $customization_data['btnColor'] : '#00B0B0';
		$global_btn_hover_color = isset( $customization_data['btnHoverColor'] ) ? $customization_data['btnHoverColor'] : '#00454A';
		$title_variation        = isset( $attr['titleTypography']['variation'] ) ? $attr['titleTypography']['variation'] : 'n5';
		$variation              = isset( $attr['contentTypography']['variation'] ) ? $attr['contentTypography']['variation'] : 'n4';
		$ribbon_variation       = isset( $attr['ribbonTypography']['variation'] ) ? $attr['ribbonTypography']['variation'] : 'n4';
		$button_variation       = isset( $attr['buttonTypography']['variation'] ) ? $attr['buttonTypography']['variation'] : 'n4';
		$price_variation        = isset( $attr['priceTypography']['variation'] ) ? $attr['priceTypography']['variation'] : 'n4';
		$bgGradient             = isset( $attr['bgColorGradient']['gradient'] ) ? $attr['bgColorGradient']['gradient'] : '';
		$bgColor                = isset( $attr['bgColorSolid'] ) ? $attr['bgColorSolid'] : '#FFFFFF';
		$box_shadow             = array(
			'enable'   => true,
			'h_offset' => 2,
			'v_offset' => 5,
			'blur'     => 20,
			'spread'   => 0,
			'inset'    => false,
			'color'    => array(
				'color' => 'rgba(210,213,218,0.2)',
			),
		);
		$selector               = array(
			' .affx-product-comparison-block-container'   => array(
				'border-width'  => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['borderRadius']['desktop']['top'] ) && isset( $attr['borderRadius']['desktop']['right'] ) && isset( $attr['borderRadius']['desktop']['bottom'] ) && isset( $attr['borderRadius']['desktop']['left'] ) ? $attr['borderRadius']['desktop']['top'] . ' ' . $attr['borderRadius']['desktop']['right'] . ' ' . $attr['borderRadius']['desktop']['bottom'] . ' ' . $attr['borderRadius']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color'  => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'background'    => isset( $attr['bgType'] ) && $attr['bgType'] === 'gradient' ? $bgGradient : $bgColor,
				'margin-top'    => isset( $attr['margin']['desktop']['top'] ) ? $attr['margin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['desktop']['left'] ) ? $attr['margin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['desktop']['right'] ) ? $attr['margin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['desktop']['bottom'] ) ? $attr['margin']['desktop']['bottom'] : '30px',
				'box-shadow'    => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),
			),
			' .affx-product-versus-table'                 => array(
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['contentColor'] ) ? $attr['contentColor'] : $global_font_color,
			),
			' .affx-comparison-title'                     => array(
				'font-family'     => isset( $attr['titleTypography']['family'] ) ? $attr['titleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $title_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $title_variation ),
				'font-size'       => isset( $attr['titleTypography']['size']['desktop'] ) ? $attr['titleTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['titleTypography']['line-height']['desktop'] ) ? $attr['titleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['titleTypography']['text-transform'] ) ? $attr['titleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['titleTypography']['text-decoration'] ) ? $attr['titleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['titleTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['titleColor'] ) ? $attr['titleColor'] : '#262B33',
			),
			' .affx-versus-table-wrap tr:first-child th:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['desktop']['top'] ) ? $attr['borderRadius']['desktop']['top'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['desktop']['right'] ) ? $attr['borderRadius']['desktop']['right'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child .affx-versus-product' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['desktop']['right'] ) ? $attr['borderRadius']['desktop']['right'] : '0px',
				'overflow'                => 'hidden',
			),
			' .affx-versus-table-wrap tr:last-child td:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['desktop']['left'] ) ? $attr['borderRadius']['desktop']['left'] : '0px',
			),
			' .affx-versus-table-wrap tr:last-child td:last-child' => array(
				'border-bottom-left-radius' => isset( $attr['borderRadius']['desktop']['buttom'] ) ? $attr['borderRadius']['desktop']['buttom'] : '0px',
			),
			' .affx-versus-table-wrap td'                 => array(
				'border-width'   => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-style'   => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color'   => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'padding-top'    => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap th'                 => array(
				'border-width'   => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-style'   => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color'   => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'padding-top'    => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon'    => array(
				'font-family'     => isset( $attr['ribbonTypography']['family'] ) ? $attr['ribbonTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $ribbon_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $ribbon_variation ),
				'font-size'       => isset( $attr['ribbonTypography']['size']['desktop'] ) ? $attr['ribbonTypography']['size']['desktop'] : '13px',
				'line-height'     => isset( $attr['ribbonTypography']['line-height']['desktop'] ) ? $attr['ribbonTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ribbonTypography']['text-transform'] ) ? $attr['ribbonTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ribbonTypography']['text-decoration'] ) ? $attr['ribbonTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ribbonTypography']['letter-spacing']['desktop'] ) ? $attr['ribbonTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['ribbonTextColor'] ) ? $attr['ribbonTextColor'] : '#fff',
				'background'      => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#F13A3A',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon::before' => array(
				'background' => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#F13A3A',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon::after' => array(
				'background' => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#F13A3A',
			),
			' .affx-versus-table-wrap .affiliatex-button' => array(
				'font-family'     => isset( $attr['buttonTypography']['family'] ) ? $attr['buttonTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $button_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $button_variation ),
				'font-size'       => isset( $attr['buttonTypography']['size']['desktop'] ) ? $attr['buttonTypography']['size']['desktop'] : '16px',
				'line-height'     => isset( $attr['buttonTypography']['line-height']['desktop'] ) ? $attr['buttonTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['buttonTypography']['text-transform'] ) ? $attr['buttonTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['buttonTypography']['text-decoration'] ) ? $attr['buttonTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['buttonTypography']['letter-spacing']['desktop'] ) ? $attr['buttonTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button' => array(
				'padding-top'      => isset( $attr['buttonPadding']['desktop']['top'] ) ? $attr['buttonPadding']['desktop']['top'] : '10px',
				'padding-left'     => isset( $attr['buttonPadding']['desktop']['left'] ) ? $attr['buttonPadding']['desktop']['left'] : '10px',
				'padding-right'    => isset( $attr['buttonPadding']['desktop']['right'] ) ? $attr['buttonPadding']['desktop']['right'] : '10px',
				'padding-bottom'   => isset( $attr['buttonPadding']['desktop']['bottom'] ) ? $attr['buttonPadding']['desktop']['bottom'] : '10px',
				'margin-top'       => isset( $attr['buttonMargin']['desktop']['top'] ) ? $attr['buttonMargin']['desktop']['top'] : '0px',
				'margin-left'      => isset( $attr['buttonMargin']['desktop']['left'] ) ? $attr['buttonMargin']['desktop']['left'] : '0px',
				'margin-right'     => isset( $attr['buttonMargin']['desktop']['right'] ) ? $attr['buttonMargin']['desktop']['right'] : '0px',
				'margin-bottom'    => isset( $attr['buttonMargin']['desktop']['bottom'] ) ? $attr['buttonMargin']['desktop']['bottom'] : '0px',
				'color'            => isset( $attr['buttonTextColor'] ) ? $attr['buttonTextColor'] : '#fff',
				'background-color' => isset( $attr['buttonBgColor'] ) ? $attr['buttonBgColor'] : $global_btn_color,
				'border-style'     => isset( $attr['buttonBorder']['style'] ) ? $attr['buttonBorder']['style'] : 'none',
				'border-width'     => isset( $attr['buttonBorder']['width'] ) ? $attr['buttonBorder']['width'] . 'px' : '1px',
				'border-color'     => isset( $attr['buttonBorder']['color']['color'] ) ? $attr['buttonBorder']['color']['color'] : '#dddddd',
				'box-shadow'       => isset( $attr['buttonShadow'] ) && $attr['buttonShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['buttonShadow'] ) : 'none',
				'border-radius'    => isset( $attr['buttonRadius']['desktop']['top'] ) && isset( $attr['buttonRadius']['desktop']['right'] ) && isset( $attr['buttonRadius']['desktop']['bottom'] ) && isset( $attr['buttonRadius']['desktop']['left'] ) ? $attr['buttonRadius']['desktop']['top'] . ' ' . $attr['buttonRadius']['desktop']['right'] . ' ' . $attr['buttonRadius']['desktop']['bottom'] . ' ' . $attr['buttonRadius']['desktop']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button:hover' => array(
				'color'            => isset( $attr['buttonTextHoverColor'] ) ? $attr['buttonTextHoverColor'] : '#fff',
				'background-color' => isset( $attr['buttonBgHoverColor'] ) ? $attr['buttonBgHoverColor'] : $global_btn_hover_color,
				'border-color'     => isset( $attr['buttonborderHoverColor'] ) ? $attr['buttonborderHoverColor'] : '#ffffff',
			),
			' .affx-versus-table-wrap .affx-price'        => array(
				'font-family'     => isset( $attr['priceTypography']['family'] ) ? $attr['priceTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $price_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $price_variation ),
				'font-size'       => isset( $attr['priceTypography']['size']['desktop'] ) ? $attr['priceTypography']['size']['desktop'] : '16px',
				'line-height'     => isset( $attr['priceTypography']['line-height']['desktop'] ) ? $attr['priceTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['priceTypography']['text-transform'] ) ? $attr['priceTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['priceTypography']['text-decoration'] ) ? $attr['priceTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['priceTypography']['letter-spacing']['desktop'] ) ? $attr['priceTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['priceColor'] ) ? $attr['priceColor'] : '#262B33',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header' => array(
				'background' => isset( $attr['tableRowBgColor'] ) ? $attr['tableRowBgColor'] : '#F5F7FA',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header th' => array(
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '16px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table th' => array(
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '16px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table tbody tr:nth-child(odd) td' => array(
				'background' => isset( $attr['tableRowBgColor'] ) ? $attr['tableRowBgColor'] : '#F5F7FA',
			),
			' .affx-versus-table-wrap .affx-versus-product-img' => array(
				'padding-top'    => isset( $attr['imagePadding']['desktop']['top'] ) ? $attr['imagePadding']['desktop']['top'] : '0px',
				'padding-left'   => isset( $attr['imagePadding']['desktop']['left'] ) ? $attr['imagePadding']['desktop']['left'] : '0px',
				'padding-right'  => isset( $attr['imagePadding']['desktop']['right'] ) ? $attr['imagePadding']['desktop']['right'] : '0px',
				'padding-bottom' => isset( $attr['imagePadding']['desktop']['bottom'] ) ? $attr['imagePadding']['desktop']['bottom'] : '0px',
			),
		);
		return $selector;
	}

	public static function get_mobileselectors( $attr ) {
		$global_btn_hover_color = isset( $customization_data['btnHoverColor'] ) ? $customization_data['btnHoverColor'] : '#00454A';

		$mobile_selector = array(
			' .affx-product-comparison-block-container'   => array(
				'border-width'  => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['borderRadius']['mobile']['top'] ) && isset( $attr['borderRadius']['mobile']['right'] ) && isset( $attr['borderRadius']['mobile']['bottom'] ) && isset( $attr['borderRadius']['mobile']['left'] ) ? $attr['borderRadius']['mobile']['top'] . ' ' . $attr['borderRadius']['mobile']['right'] . ' ' . $attr['borderRadius']['mobile']['bottom'] . ' ' . $attr['borderRadius']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['margin']['mobile']['top'] ) ? $attr['margin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['mobile']['left'] ) ? $attr['margin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['mobile']['right'] ) ? $attr['margin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['mobile']['bottom'] ) ? $attr['margin']['mobile']['bottom'] : '30px',
			),
			' .affx-product-versus-table'                 => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-comparison-title'                     => array(
				'font-size'      => isset( $attr['titleTypography']['size']['mobile'] ) ? $attr['titleTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['mobile'] ) ? $attr['titleTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['mobile'] ) ? $attr['titleTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap tr:first-child th:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['mobile']['top'] ) ? $attr['borderRadius']['mobile']['top'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['mobile']['right'] ) ? $attr['borderRadius']['mobile']['right'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child .affx-versus-product' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['mobile']['right'] ) ? $attr['borderRadius']['mobile']['right'] : '0px',
				'overflow'                => 'hidden',
			),
			' .affx-versus-table-wrap tr:last-child td:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['mobile']['left'] ) ? $attr['borderRadius']['mobile']['left'] : '0px',
			),
			' .affx-versus-table-wrap tr:last-child td:last-child' => array(
				'border-bottom-left-radius' => isset( $attr['borderRadius']['mobile']['buttom'] ) ? $attr['borderRadius']['mobile']['buttom'] : '0px',
			),
			' .affx-versus-table-wrap td'                 => array(
				'border-width'   => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap th'                 => array(
				'border-width'   => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon'    => array(
				'font-size'      => isset( $attr['ribbonTypography']['size']['mobile'] ) ? $attr['ribbonTypography']['size']['mobile'] : '13px',
				'line-height'    => isset( $attr['ribbonTypography']['line-height']['mobile'] ) ? $attr['ribbonTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonTypography']['letter-spacing']['mobile'] ) ? $attr['ribbonTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button' => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['mobile'] ) ? $attr['buttonTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['mobile'] ) ? $attr['buttonTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['mobile'] ) ? $attr['buttonTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button' => array(
				'padding-top'    => isset( $attr['buttonPadding']['mobile']['top'] ) ? $attr['buttonPadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['buttonPadding']['mobile']['left'] ) ? $attr['buttonPadding']['mobile']['left'] : '10px',
				'padding-right'  => isset( $attr['buttonPadding']['mobile']['right'] ) ? $attr['buttonPadding']['mobile']['right'] : '10px',
				'padding-bottom' => isset( $attr['buttonPadding']['mobile']['bottom'] ) ? $attr['buttonPadding']['mobile']['bottom'] : '10px',
				'margin-top'     => isset( $attr['buttonMargin']['mobile']['top'] ) ? $attr['buttonMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['buttonMargin']['mobile']['left'] ) ? $attr['buttonMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['buttonMargin']['mobile']['right'] ) ? $attr['buttonMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['buttonMargin']['mobile']['bottom'] ) ? $attr['buttonMargin']['mobile']['bottom'] : '0px',
				'border-style'   => isset( $attr['buttonBorder']['style'] ) ? $attr['buttonBorder']['style'] : 'none',
				'border-width'   => isset( $attr['buttonBorder']['width'] ) ? $attr['buttonBorder']['width'] . 'px' : '1px',
				'border-color'   => isset( $attr['buttonBorder']['color']['color'] ) ? $attr['buttonBorder']['color']['color'] : '#dddddd',
				'box-shadow'     => isset( $attr['buttonShadow'] ) && $attr['buttonShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['buttonShadow'] ) : 'none',
				'border-radius'  => isset( $attr['buttonRadius']['desktop']['top'] ) && isset( $attr['buttonRadius']['desktop']['right'] ) && isset( $attr['buttonRadius']['desktop']['bottom'] ) && isset( $attr['buttonRadius']['desktop']['left'] ) ? $attr['buttonRadius']['desktop']['top'] . ' ' . $attr['buttonRadius']['desktop']['right'] . ' ' . $attr['buttonRadius']['desktop']['bottom'] . ' ' . $attr['buttonRadius']['desktop']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button:hover' => array(
				'color'            => isset( $attr['buttonTextHoverColor'] ) ? $attr['buttonTextHoverColor'] : '#fff',
				'background-color' => isset( $attr['buttonBgHoverColor'] ) ? $attr['buttonBgHoverColor'] : $global_btn_hover_color,
				'border-color'     => isset( $attr['buttonborderHoverColor'] ) ? $attr['buttonborderHoverColor'] : '#ffffff',
			),
			' .affx-versus-table-wrap .affx-price'        => array(
				'font-size'      => isset( $attr['priceTypography']['size']['mobile'] ) ? $attr['priceTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['priceTypography']['line-height']['mobile'] ) ? $attr['priceTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['priceTypography']['letter-spacing']['mobile'] ) ? $attr['priceTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header th' => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table th' => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-versus-table-wrap .affx-versus-product-img' => array(
				'padding-top'    => isset( $attr['imagePadding']['mobile']['top'] ) ? $attr['imagePadding']['mobile']['top'] : '0px',
				'padding-left'   => isset( $attr['imagePadding']['mobile']['left'] ) ? $attr['imagePadding']['mobile']['left'] : '0px',
				'padding-right'  => isset( $attr['imagePadding']['mobile']['right'] ) ? $attr['imagePadding']['mobile']['right'] : '0px',
				'padding-bottom' => isset( $attr['imagePadding']['mobile']['bottom'] ) ? $attr['imagePadding']['mobile']['bottom'] : '0px',
			),
		);
		return $mobile_selector;
	}

	public static function get_tabletselectors( $attr ) {
		$global_btn_hover_color = isset( $customization_data['btnHoverColor'] ) ? $customization_data['btnHoverColor'] : '#00454A';

		$tablet_selector = array(
			' .affx-product-comparison-block-container'   => array(
				'border-width'  => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['borderRadius']['tablet']['top'] ) && isset( $attr['borderRadius']['tablet']['right'] ) && isset( $attr['borderRadius']['tablet']['bottom'] ) && isset( $attr['borderRadius']['tablet']['left'] ) ? $attr['borderRadius']['tablet']['top'] . ' ' . $attr['borderRadius']['tablet']['right'] . ' ' . $attr['borderRadius']['tablet']['bottom'] . ' ' . $attr['borderRadius']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['margin']['tablet']['top'] ) ? $attr['margin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['tablet']['left'] ) ? $attr['margin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['tablet']['right'] ) ? $attr['margin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['tablet']['bottom'] ) ? $attr['margin']['tablet']['bottom'] : '30px',
			),
			' .affx-product-versus-table'                 => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-comparison-title'                     => array(
				'font-size'      => isset( $attr['titleTypography']['size']['tablet'] ) ? $attr['titleTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['tablet'] ) ? $attr['titleTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['tablet'] ) ? $attr['titleTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap tr:first-child th:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['tablet']['top'] ) ? $attr['borderRadius']['tablet']['top'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['tablet']['right'] ) ? $attr['borderRadius']['tablet']['right'] : '0px',
			),
			' .affx-versus-table-wrap tr:first-child th:last-child .affx-versus-product' => array(
				'border-top-right-radius' => isset( $attr['borderRadius']['tablet']['right'] ) ? $attr['borderRadius']['tablet']['right'] : '0px',
				'overflow'                => 'hidden',
			),
			' .affx-versus-table-wrap tr:last-child td:first-child' => array(
				'border-top-left-radius' => isset( $attr['borderRadius']['tablet']['left'] ) ? $attr['borderRadius']['tablet']['left'] : '0px',
			),
			' .affx-versus-table-wrap tr:last-child td:last-child' => array(
				'border-bottom-left-radius' => isset( $attr['borderRadius']['tablet']['buttom'] ) ? $attr['borderRadius']['tablet']['buttom'] : '0px',
			),
			' .affx-versus-table-wrap td'                 => array(
				'border-width'   => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap th'                 => array(
				'border-width'   => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '24px',
			),
			' .affx-versus-table-wrap .affx-pc-ribbon'    => array(
				'font-size'      => isset( $attr['ribbonTypography']['size']['tablet'] ) ? $attr['ribbonTypography']['size']['tablet'] : '13px',
				'line-height'    => isset( $attr['ribbonTypography']['line-height']['tablet'] ) ? $attr['ribbonTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonTypography']['letter-spacing']['tablet'] ) ? $attr['ribbonTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button' => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['tablet'] ) ? $attr['buttonTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['tablet'] ) ? $attr['buttonTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['tablet'] ) ? $attr['buttonTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button' => array(
				'padding-top'    => isset( $attr['buttonPadding']['tablet']['top'] ) ? $attr['buttonPadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['buttonPadding']['tablet']['left'] ) ? $attr['buttonPadding']['tablet']['left'] : '0px',
				'padding-right'  => isset( $attr['buttonPadding']['tablet']['right'] ) ? $attr['buttonPadding']['tablet']['right'] : '0px',
				'padding-bottom' => isset( $attr['buttonPadding']['tablet']['bottom'] ) ? $attr['buttonPadding']['tablet']['bottom'] : '10px',
				'margin-top'     => isset( $attr['buttonMargin']['tablet']['top'] ) ? $attr['buttonMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['buttonMargin']['tablet']['left'] ) ? $attr['buttonMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['buttonMargin']['tablet']['right'] ) ? $attr['buttonMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['buttonMargin']['tablet']['bottom'] ) ? $attr['buttonMargin']['tablet']['bottom'] : '0px',
				'border-style'   => isset( $attr['buttonBorder']['style'] ) ? $attr['buttonBorder']['style'] : 'none',
				'border-width'   => isset( $attr['buttonBorder']['width'] ) ? $attr['buttonBorder']['width'] . 'px' : '1px',
				'border-color'   => isset( $attr['buttonBorder']['color']['color'] ) ? $attr['buttonBorder']['color']['color'] : '#dddddd',
				'box-shadow'     => isset( $attr['buttonShadow'] ) && $attr['buttonShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['buttonShadow'] ) : 'none',
				'border-radius'  => isset( $attr['buttonRadius']['desktop']['top'] ) && isset( $attr['buttonRadius']['desktop']['right'] ) && isset( $attr['buttonRadius']['desktop']['bottom'] ) && isset( $attr['buttonRadius']['desktop']['left'] ) ? $attr['buttonRadius']['desktop']['top'] . ' ' . $attr['buttonRadius']['desktop']['right'] . ' ' . $attr['buttonRadius']['desktop']['bottom'] . ' ' . $attr['buttonRadius']['desktop']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-versus-table-wrap .affiliatex-button.affx-winner-button:hover' => array(
				'color'            => isset( $attr['buttonTextHoverColor'] ) ? $attr['buttonTextHoverColor'] : '#fff',
				'background-color' => isset( $attr['buttonBgHoverColor'] ) ? $attr['buttonBgHoverColor'] : $global_btn_hover_color,
				'border-color'     => isset( $attr['buttonborderHoverColor'] ) ? $attr['buttonborderHoverColor'] : '#ffffff',
			),
			' .affx-versus-table-wrap .affx-price'        => array(
				'font-size'      => isset( $attr['priceTypography']['size']['tablet'] ) ? $attr['priceTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['priceTypography']['line-height']['tablet'] ) ? $attr['priceTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['priceTypography']['letter-spacing']['tablet'] ) ? $attr['priceTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header' => array(
				'background' => isset( $attr['tableRowBgColor'] ) ? $attr['tableRowBgColor'] : '#F5F7FA',
			),
			' .affx-versus-table-wrap .affx-product-versus-table .affx-table-row-header th' => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affx-product-versus-table th' => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-versus-table-wrap .affx-versus-product-img' => array(
				'padding-top'    => isset( $attr['imagePadding']['tablet']['top'] ) ? $attr['imagePadding']['tablet']['top'] : '0px',
				'padding-left'   => isset( $attr['imagePadding']['tablet']['left'] ) ? $attr['imagePadding']['tablet']['left'] : '0px',
				'padding-right'  => isset( $attr['imagePadding']['tablet']['right'] ) ? $attr['imagePadding']['tablet']['right'] : '0px',
				'padding-bottom' => isset( $attr['imagePadding']['tablet']['bottom'] ) ? $attr['imagePadding']['tablet']['bottom'] : '0px',
			),
		);
		return $tablet_selector;
	}
}
