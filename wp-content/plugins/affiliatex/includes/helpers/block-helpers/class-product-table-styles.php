<?php

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\HoverStyles;

require_once __DIR__ . '/class-affiliatex-block-styles-base.php';

/**
 * Product Table Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Product_Table_Styles extends AffiliateX_Block_Styles_Base {

	protected static function css_id_prefix(): string {
		return '#affiliatex-pdt-table-style-';
	}

	public static function block_fonts( $attr ) {
		return array(
			'ribbonTypography'  => isset( $attr['ribbonTypography'] ) ? $attr['ribbonTypography'] : array(),
			'priceTypography'   => isset( $attr['priceTypography'] ) ? $attr['priceTypography'] : array(),
			'buttonTypography'  => isset( $attr['buttonTypography'] ) ? $attr['buttonTypography'] : array(),
			'contentTypography' => isset( $attr['contentTypography'] ) ? $attr['contentTypography'] : array(),
			'counterTypography' => isset( $attr['counterTypography'] ) ? $attr['counterTypography'] : array(),
			'titleTypography'   => isset( $attr['titleTypography'] ) ? $attr['titleTypography'] : array(),
			'headerTypography'  => isset( $attr['headerTypography'] ) ? $attr['headerTypography'] : array(),
			'ratingTypography'  => isset( $attr['ratingTypography'] ) ? $attr['ratingTypography'] : array(),
			'rating2Typography' => isset( $attr['rating2Typography'] ) ? $attr['rating2Typography'] : array(),
		);
	}

	/**
	 * Hover rules for the wave-4 hover attributes, mirrors product-table/styling.js.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_hover_selectors( array &$buckets, array $attr ): void {
		$typos = array(
			$attr['ribbonHoverTypography'] ?? null,
			$attr['counterHoverTypography'] ?? null,
			$attr['priceHoverTypography'] ?? null,
			$attr['buttonHoverTypography'] ?? null,
			$attr['headerHoverTypography'] ?? null,
			$attr['titleHoverTypography'] ?? null,
			$attr['contentHoverTypography'] ?? null,
		);

		$extras = array();

		if ( HoverStyles::has_typography_value( $typos, 'size' ) ) {
			$extras[] = 'font-size';
		}

		if ( HoverStyles::has_typography_value( $typos, 'letter-spacing' ) ) {
			$extras[] = 'letter-spacing';
		}

		if ( HoverStyles::has_spacing_value( $attr['hoverMargin'] ?? null ) || HoverStyles::has_spacing_value( $attr['button1HoverMargin'] ?? null ) || HoverStyles::has_spacing_value( $attr['button2HoverMargin'] ?? null ) ) {
			$extras[] = 'margin';
		}

		if ( HoverStyles::has_spacing_value( $attr['hoverPadding'] ?? null ) || HoverStyles::has_spacing_value( $attr['imageHoverPadding'] ?? null ) || HoverStyles::has_spacing_value( $attr['button1HoverPadding'] ?? null ) || HoverStyles::has_spacing_value( $attr['button2HoverPadding'] ?? null ) ) {
			$extras[] = 'padding';
		}

		$transition = HoverStyles::get_transition( $extras );

		$ribbon_hover = array();

		if ( ! empty( $attr['ribbonHoverColor'] ) && is_string( $attr['ribbonHoverColor'] ) ) {
			$ribbon_hover['color'] = $attr['ribbonHoverColor'];
		}

		if ( ! empty( $attr['ribbonBgHoverColor'] ) && is_string( $attr['ribbonBgHoverColor'] ) ) {
			$ribbon_hover['background'] = $attr['ribbonBgHoverColor'];
			HoverStyles::merge_selector( $buckets['desktop'], ' .affx-pdt-table-wrapper .affx-pdt-ribbon:hover::before', array( 'background' => $attr['ribbonBgHoverColor'] ) );
		}

		if ( ! empty( $ribbon_hover ) ) {
			self::set_hover( $buckets, $transition, ' .affx-pdt-table-wrapper .affx-pdt-ribbon:hover', $ribbon_hover, array( ' .affx-pdt-table-wrapper .affx-pdt-ribbon' ) );
		}

		$counter_hover = array();

		if ( ! empty( $attr['counterHoverColor'] ) && is_string( $attr['counterHoverColor'] ) ) {
			$counter_hover['color'] = $attr['counterHoverColor'];
		}

		if ( ! empty( $attr['counterBgHoverColor'] ) && is_string( $attr['counterBgHoverColor'] ) ) {
			$counter_hover['background'] = $attr['counterBgHoverColor'];
		}

		if ( ! empty( $counter_hover ) ) {
			self::set_hover( $buckets, $transition, ' .affx-pdt-table-wrapper .affx-pdt-counter:hover', $counter_hover, array( ' .affx-pdt-table-wrapper .affx-pdt-counter' ) );
		}

		if ( ! empty( $attr['priceHoverColor'] ) && is_string( $attr['priceHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, ' .affx-pdt-table-wrapper .affx-pdt-price-wrap:hover', array( 'color' => $attr['priceHoverColor'] ), array( ' .affx-pdt-table-wrapper .affx-pdt-price-wrap' ) );
		}

		$header_hover = array();

		if ( ! empty( $attr['tableHeaderHoverColor'] ) && is_string( $attr['tableHeaderHoverColor'] ) ) {
			$header_hover['color'] = $attr['tableHeaderHoverColor'];
		}

		if ( ! empty( $attr['tableHeaderBgHoverColor'] ) && is_string( $attr['tableHeaderBgHoverColor'] ) ) {
			$header_hover['background']   = $attr['tableHeaderBgHoverColor'];
			$header_hover['border-color'] = $attr['tableHeaderBgHoverColor'];
		}

		if ( ! empty( $header_hover ) ) {
			self::set_hover( $buckets, $transition, ' .affx-pdt-table-wrapper .affx-pdt-table thead td:hover', $header_hover, array( ' .affx-pdt-table-wrapper .affx-pdt-table thead td' ) );
		}

		if ( ! empty( $attr['titleHoverColor'] ) && is_string( $attr['titleHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, ' .affx-pdt-table-wrapper .affx-pdt-name:hover', array( 'color' => $attr['titleHoverColor'] ), array( ' .affx-pdt-table-wrapper .affx-pdt-name' ) );
		}

		if ( ! empty( $attr['contentHoverColor'] ) && is_string( $attr['contentHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, ' .affx-pdt-table-wrapper p:hover', array( 'color' => $attr['contentHoverColor'] ), array( ' .affx-pdt-table-wrapper p' ) );
			self::set_hover( $buckets, $transition, ' .affx-pdt-table-wrapper li:hover', array( 'color' => $attr['contentHoverColor'] ), array( ' .affx-pdt-table-wrapper li' ) );
		}

		$container_bg_hover = HoverStyles::get_background_styles(
			$attr['bgHoverType'] ?? '',
			$attr['bgType'] ?? '',
			$attr['bgHoverColor'] ?? '',
			$attr['bgHoverGradient'] ?? ''
		);

		$container_hover = $container_bg_hover;
		$container_hover = array_merge( $container_hover, HoverStyles::get_border_styles( $attr['hoverBorder'] ?? null ) );
		$container_hover = array_merge( $container_hover, HoverStyles::get_shadow_styles( $attr['hoverShadow'] ?? null ) );

		$desktop_radius = HoverStyles::get_radius_value( $attr['hoverBorderRadius'] ?? null, 'desktop' );

		if ( '' !== $desktop_radius ) {
			$container_hover['border-radius'] = $desktop_radius;
		}

		if ( ! empty( $container_hover ) ) {
			self::set_hover( $buckets, $transition, ' .affx-pdt-table-wrapper:hover', $container_hover, array( ' .affx-pdt-table-wrapper' ) );
			self::set_hover( $buckets, $transition, ' .affx-pdt-table-single:hover', $container_hover, array( ' .affx-pdt-table-single' ) );

			if ( ! empty( $container_bg_hover ) ) {
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-pdt-table-wrapper:hover .affx-pdt-table', $container_bg_hover );
			}
		}

		foreach ( array( 'tablet', 'mobile' ) as $device ) {
			$radius = HoverStyles::get_radius_value( $attr['hoverBorderRadius'] ?? null, $device );

			if ( '' !== $radius ) {
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-pdt-table-wrapper:hover', array( 'border-radius' => $radius ) );
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-pdt-table-single:hover', array( 'border-radius' => $radius ) );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-pdt-table-wrapper', array( 'transition' => $transition ) );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-pdt-table-single', array( 'transition' => $transition ) );
			}
		}

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			$margin_hover = HoverStyles::get_spacing_styles( $attr['hoverMargin'] ?? null, $device, 'margin' );

			if ( ! empty( $margin_hover ) ) {
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-pdt-table-wrapper:hover', $margin_hover );
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-pdt-table-single:hover', $margin_hover );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-pdt-table-wrapper', array( 'transition' => $transition ) );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-pdt-table-single', array( 'transition' => $transition ) );
			}

			$padding_hover = HoverStyles::get_spacing_styles( $attr['hoverPadding'] ?? null, $device, 'padding' );

			if ( ! empty( $padding_hover ) ) {
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-pdt-table-wrapper:hover td', $padding_hover );
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-pdt-table-wrapper:hover th', $padding_hover );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-pdt-table-wrapper td', array( 'transition' => $transition ) );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-pdt-table-wrapper th', array( 'transition' => $transition ) );
			}

			$image_padding_hover = HoverStyles::get_spacing_styles( $attr['imageHoverPadding'] ?? null, $device, 'padding' );

			if ( ! empty( $image_padding_hover ) ) {
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-pdt-table-wrapper .affx-pdt-img-container:hover', $image_padding_hover );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-pdt-table-wrapper .affx-pdt-img-container', array( 'transition' => $transition ) );
			}
		}

		$button_hover_rules = array(
			array(
				'selector'      => ' .affx-pdt-table-wrapper .affiliatex-button.primary',
				'hover_border'  => $attr['button1HoverBorder'] ?? null,
				'hover_radius'  => $attr['button1HoverBorderRadius'] ?? null,
				'hover_shadow'  => $attr['button1HoverShadow'] ?? null,
				'hover_margin'  => $attr['button1HoverMargin'] ?? null,
				'hover_padding' => $attr['button1HoverPadding'] ?? null,
			),
			array(
				'selector'      => ' .affx-pdt-table-wrapper .affiliatex-button.secondary',
				'hover_border'  => $attr['button2HoverBorder'] ?? null,
				'hover_radius'  => $attr['button2HoverBorderRadius'] ?? null,
				'hover_shadow'  => $attr['button2HoverShadow'] ?? null,
				'hover_margin'  => $attr['button2HoverMargin'] ?? null,
				'hover_padding' => $attr['button2HoverPadding'] ?? null,
			),
		);

		foreach ( $button_hover_rules as $button ) {
			$button_hover = HoverStyles::get_border_styles( $button['hover_border'], false );
			$button_hover = array_merge( $button_hover, HoverStyles::get_shadow_styles( $button['hover_shadow'] ) );

			$has_button_hover = ! empty( $button_hover );

			HoverStyles::merge_selector( $buckets['desktop'], $button['selector'] . ':hover', $button_hover );

			foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
				$radius = HoverStyles::get_radius_value( $button['hover_radius'], $device );

				if ( '' !== $radius ) {
					$has_button_hover = true;
					HoverStyles::merge_selector( $buckets[ $device ], $button['selector'] . ':hover', array( 'border-radius' => $radius ) );
				}

				$spacing_hover = array_merge(
					HoverStyles::get_spacing_styles( $button['hover_margin'], $device, 'margin' ),
					HoverStyles::get_spacing_styles( $button['hover_padding'], $device, 'padding' )
				);

				if ( ! empty( $spacing_hover ) ) {
					$has_button_hover = true;
					HoverStyles::merge_selector( $buckets[ $device ], $button['selector'] . ':hover', $spacing_hover );
				}
			}

			if ( $has_button_hover ) {
				HoverStyles::merge_selector( $buckets['desktop'], $button['selector'], array( 'transition' => $transition ) );
			}
		}

		$typography_rules = array(
			array(
				'typography' => $attr['ribbonHoverTypography'] ?? null,
				'base'       => ' .affx-pdt-table-wrapper .affx-pdt-ribbon',
				'hover'      => ' .affx-pdt-table-wrapper .affx-pdt-ribbon:hover',
			),
			array(
				'typography' => $attr['counterHoverTypography'] ?? null,
				'base'       => ' .affx-pdt-table-wrapper .affx-pdt-counter',
				'hover'      => ' .affx-pdt-table-wrapper .affx-pdt-counter:hover',
			),
			array(
				'typography' => $attr['priceHoverTypography'] ?? null,
				'base'       => ' .affx-pdt-table-wrapper .affx-pdt-price-wrap',
				'hover'      => ' .affx-pdt-table-wrapper .affx-pdt-price-wrap:hover',
			),
			array(
				'typography' => $attr['buttonHoverTypography'] ?? null,
				'base'       => ' .affx-pdt-table-wrapper .affiliatex-button',
				'hover'      => ' .affx-pdt-table-wrapper .affiliatex-button:hover',
			),
			array(
				'typography' => $attr['headerHoverTypography'] ?? null,
				'base'       => ' .affx-pdt-table-wrapper .affx-pdt-table thead td',
				'hover'      => ' .affx-pdt-table-wrapper .affx-pdt-table thead td:hover',
			),
			array(
				'typography' => $attr['titleHoverTypography'] ?? null,
				'base'       => ' .affx-pdt-table-wrapper .affx-pdt-name',
				'hover'      => ' .affx-pdt-table-wrapper .affx-pdt-name:hover',
			),
			array(
				'typography' => $attr['contentHoverTypography'] ?? null,
				'base'       => ' .affx-pdt-table-wrapper p',
				'hover'      => ' .affx-pdt-table-wrapper p:hover',
			),
			array(
				'typography' => $attr['contentHoverTypography'] ?? null,
				'base'       => ' .affx-pdt-table-wrapper li',
				'hover'      => ' .affx-pdt-table-wrapper li:hover',
			),
		);

		foreach ( $typography_rules as $rule ) {
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

		$bgType           = isset( $attr['bgType'] ) ? $attr['bgType'] : 'solid';
		$bgGradient       = isset( $attr['bgColorGradient']['gradient'] ) ? $attr['bgColorGradient']['gradient'] : 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)';
		$bgColor          = isset( $attr['bgColorSolid'] ) ? $attr['bgColorSolid'] : '#FFFFFF';
		$variation        = isset( $attr['contentTypography']['variation'] ) ? $attr['contentTypography']['variation'] : 'n4';
		$ratingVariation  = isset( $attr['ratingTypography']['variation'] ) ? $attr['ratingTypography']['variation'] : 'n7';
		$rating2Variation = isset( $attr['rating2Typography']['variation'] ) ? $attr['rating2Typography']['variation'] : 'n4';
		$contentVariation = isset( $attr['contentTypography']['variation'] ) ? $attr['contentTypography']['variation'] : 'n4';
		$titleVariation   = isset( $attr['titleTypography']['variation'] ) ? $attr['titleTypography']['variation'] : 'n4';
		$ribbonVariation  = isset( $attr['ribbonTypography']['variation'] ) ? $attr['ribbonTypography']['variation'] : 'n5';
		$counterVariation = isset( $attr['counterTypography']['variation'] ) ? $attr['counterTypography']['variation'] : 'n5';
		$buttonVariation  = isset( $attr['buttonTypography']['variation'] ) ? $attr['buttonTypography']['variation'] : 'n4';
		$priceVariation   = isset( $attr['priceTypography']['variation'] ) ? $attr['priceTypography']['variation'] : 'n4';
		$headerVariation  = isset( $attr['headerTypography']['variation'] ) ? $attr['headerTypography']['variation'] : 'n4';

		$selectors = array(
			' .affx-pdt-table-wrapper'                     => array(
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'margin-top'      => isset( $attr['margin']['desktop']['top'] ) ? $attr['margin']['desktop']['top'] : '0px',
				'margin-left'     => isset( $attr['margin']['desktop']['left'] ) ? $attr['margin']['desktop']['left'] : '0px',
				'margin-right'    => isset( $attr['margin']['desktop']['right'] ) ? $attr['margin']['desktop']['right'] : '0px',
				'margin-bottom'   => isset( $attr['margin']['desktop']['bottom'] ) ? $attr['margin']['desktop']['bottom'] : '30px',
				'color'           => isset( $attr['contentColor'] ) ? $attr['contentColor'] : $global_font_color,
				'border-style'    => isset( $attr['border']['style'] ) ? $attr['border']['style'] : 'solid',
				'border-color'    => isset( $attr['border']['color']['color'] ) ? $attr['border']['color']['color'] : '#E6ECF7',
				'border-width'    => isset( $attr['borderWidth']['desktop']['top'] ) && isset( $attr['borderWidth']['desktop']['right'] ) && isset( $attr['borderWidth']['desktop']['bottom'] ) && isset( $attr['borderWidth']['desktop']['left'] ) ? $attr['borderWidth']['desktop']['top'] . ' ' . $attr['borderWidth']['desktop']['right'] . ' ' . $attr['borderWidth']['desktop']['bottom'] . ' ' . $attr['borderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'background'      => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
				'box-shadow'      => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : 'none',
			),
			' .star-rating-single-wrap'                    => array(
				'color'           => isset( $attr['ratingColor'] ) ? $attr['ratingColor'] : '#FFFFFF',
				'background'      => isset( $attr['ratingBgColor'] ) ? $attr['ratingBgColor'] : '#24B644',
				'font-family'     => isset( $attr['ratingTypography']['family'] ) ? $attr['ratingTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['ratingTypography']['size']['desktop'] ) ? $attr['ratingTypography']['size']['desktop'] : '13px',
				'line-height'     => isset( $attr['ratingTypography']['line-height']['desktop'] ) ? $attr['ratingTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ratingTypography']['text-transform'] ) ? $attr['ratingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ratingTypography']['text-decoration'] ) ? $attr['ratingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ratingTypography']['letter-spacing']['desktop'] ) ? $attr['ratingTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $ratingVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $ratingVariation ),
			),
			' .circle-wrap .circle-mask .fill'             => array(
				'background' => isset( $attr['rating2BgColor'] ) ? $attr['rating2BgColor'] : '#24B644',
			),
			' .affx-circle-progress-container .affx-circle-inside' => array(
				'color'           => isset( $attr['rating2Color'] ) ? $attr['rating2Color'] : '#262B33',
				'font-family'     => isset( $attr['rating2Typography']['family'] ) ? $attr['rating2Typography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['rating2Typography']['size']['desktop'] ) ? $attr['rating2Typography']['size']['desktop'] : '24px',
				'line-height'     => isset( $attr['rating2Typography']['line-height']['desktop'] ) ? $attr['rating2Typography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['rating2Typography']['text-transform'] ) ? $attr['rating2Typography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['rating2Typography']['text-decoration'] ) ? $attr['rating2Typography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['rating2Typography']['letter-spacing']['desktop'] ) ? $attr['rating2Typography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $rating2Variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $rating2Variation ),
			),
			' .affx-pdt-table-wrapper p'                   => array(
				'color'           => isset( $attr['contentColor'] ) ? $attr['contentColor'] : $global_font_color,
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $contentVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $contentVariation ),
			),
			' .affx-pdt-table-wrapper li'                  => array(
				'color'           => isset( $attr['contentColor'] ) ? $attr['contentColor'] : $global_font_color,
				'font-family'     => isset( $attr['contentTypography']['family'] ) ? $attr['contentTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['contentTypography']['size']['desktop'] ) ? $attr['contentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['contentTypography']['line-height']['desktop'] ) ? $attr['contentTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['contentTypography']['text-transform'] ) ? $attr['contentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['contentTypography']['text-decoration'] ) ? $attr['contentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['contentTypography']['letter-spacing']['desktop'] ) ? $attr['contentTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $contentVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $contentVariation ),
			),
			' .affx-pdt-table-wrapper .affx-pdt-name'      => array(
				'color'           => isset( $attr['titleColor'] ) ? $attr['titleColor'] : $global_font_color,
				'font-family'     => isset( $attr['titleTypography']['family'] ) ? $attr['titleTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['titleTypography']['size']['desktop'] ) ? $attr['titleTypography']['size']['desktop'] : '22px',
				'line-height'     => isset( $attr['titleTypography']['line-height']['desktop'] ) ? $attr['titleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['titleTypography']['text-transform'] ) ? $attr['titleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['titleTypography']['text-decoration'] ) ? $attr['titleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['titleTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $titleVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $titleVariation ),
			),
			' .affx-pdt-table'                             => array(
				'background' => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
			),
			' .affx-pdt-table-single'                      => array(
				'margin-top'    => isset( $attr['margin']['desktop']['top'] ) ? $attr['margin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['desktop']['left'] ) ? $attr['margin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['desktop']['right'] ) ? $attr['margin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['desktop']['bottom'] ) ? $attr['margin']['desktop']['bottom'] : '30px',
				'background'    => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
				'box-shadow'    => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : 'none',
			),
			' .affx-pdt-table-wrapper td:not(.affx-img-col)' => array(
				'padding-top'    => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '24px',
			),
			' .affx-pdt-table-wrapper th'                  => array(
				'padding-top'    => isset( $attr['padding']['desktop']['top'] ) ? $attr['padding']['desktop']['top'] : '24px',
				'padding-left'   => isset( $attr['padding']['desktop']['left'] ) ? $attr['padding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['padding']['desktop']['right'] ) ? $attr['padding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['padding']['desktop']['bottom'] ) ? $attr['padding']['desktop']['bottom'] : '24px',
			),
			' .affx-pdt-table-wrapper .affx-pdt-counter'   => array(
				'color'           => isset( $attr['counterColor'] ) ? $attr['counterColor'] : '#FFFFFF',
				'background'      => isset( $attr['counterBgColor'] ) ? $attr['counterBgColor'] : '#24B644',
				'font-family'     => isset( $attr['counterTypography']['family'] ) ? $attr['counterTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['counterTypography']['size']['desktop'] ) ? $attr['counterTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['counterTypography']['line-height']['desktop'] ) ? $attr['counterTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['counterTypography']['text-transform'] ) ? $attr['counterTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['counterTypography']['text-decoration'] ) ? $attr['counterTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['counterTypography']['letter-spacing']['desktop'] ) ? $attr['counterTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $counterVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $counterVariation ),
			),
			' .affx-pdt-table-wrapper .affx-pdt-ribbon'    => array(
				'color'           => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#FFFFFF',
				'background'      => isset( $attr['ribbonBgColor'] ) ? $attr['ribbonBgColor'] : '#F13A3A',
				'font-family'     => isset( $attr['ribbonTypography']['family'] ) ? $attr['ribbonTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['ribbonTypography']['size']['desktop'] ) ? $attr['ribbonTypography']['size']['desktop'] : '13px',
				'line-height'     => isset( $attr['ribbonTypography']['line-height']['desktop'] ) ? $attr['ribbonTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ribbonTypography']['text-transform'] ) ? $attr['ribbonTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ribbonTypography']['text-decoration'] ) ? $attr['ribbonTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ribbonTypography']['letter-spacing']['desktop'] ) ? $attr['ribbonTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $ribbonVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $ribbonVariation ),
			),
			' .affx-pdt-table-wrapper .affx-pdt-ribbon::before' => array(
				'background' => isset( $attr['ribbonBgColor'] ) ? $attr['ribbonBgColor'] : '#F13A3A',
			),
			' .affx-pdt-table-wrapper .affiliatex-button'  => array(
				'font-family'     => isset( $attr['buttonTypography']['family'] ) ? $attr['buttonTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['buttonTypography']['size']['desktop'] ) ? $attr['buttonTypography']['size']['desktop'] : '14px',
				'line-height'     => isset( $attr['buttonTypography']['line-height']['desktop'] ) ? $attr['buttonTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['buttonTypography']['text-transform'] ) ? $attr['buttonTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['buttonTypography']['text-decoration'] ) ? $attr['buttonTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['buttonTypography']['letter-spacing']['desktop'] ) ? $attr['buttonTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $buttonVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $buttonVariation ),
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary' => array(
				'color'            => isset( $attr['button1TextColor'] ) ? $attr['button1TextColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button1BgColor'] ) ? $attr['button1BgColor'] : $global_btn_color,
				'margin-top'       => isset( $attr['button1Margin']['desktop']['top'] ) ? $attr['button1Margin']['desktop']['top'] : '5px',
				'margin-left'      => isset( $attr['button1Margin']['desktop']['left'] ) ? $attr['button1Margin']['desktop']['left'] : '0px',
				'margin-right'     => isset( $attr['button1Margin']['desktop']['right'] ) ? $attr['button1Margin']['desktop']['right'] : '0px',
				'margin-bottom'    => isset( $attr['button1Margin']['desktop']['bottom'] ) ? $attr['button1Margin']['desktop']['bottom'] : '5px',
				'padding-top'      => isset( $attr['button1Padding']['desktop']['top'] ) ? $attr['button1Padding']['desktop']['top'] : '10px',
				'padding-left'     => isset( $attr['button1Padding']['desktop']['left'] ) ? $attr['button1Padding']['desktop']['left'] : '5px',
				'padding-right'    => isset( $attr['button1Padding']['desktop']['right'] ) ? $attr['button1Padding']['desktop']['right'] : '5px',
				'padding-bottom'   => isset( $attr['button1Padding']['desktop']['bottom'] ) ? $attr['button1Padding']['desktop']['bottom'] : '10px',
				'border-style'     => isset( $attr['button1Border']['style'] ) ? $attr['button1Border']['style'] : 'none',
				'border-width'     => isset( $attr['button1Border']['width'] ) ? $attr['button1Border']['width'] . 'px' : '1px',
				'border-color'     => isset( $attr['button1Border']['color']['color'] ) ? $attr['button1Border']['color']['color'] : '#dddddd',
				'box-shadow'       => isset( $attr['button1Shadow'] ) && $attr['button1Shadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['button1Shadow'] ) : 'none',
				'border-radius'    => isset( $attr['button1Radius']['desktop']['top'] ) && isset( $attr['button1Radius']['desktop']['right'] ) && isset( $attr['button1Radius']['desktop']['bottom'] ) && isset( $attr['button1Radius']['desktop']['left'] ) ? $attr['button1Radius']['desktop']['top'] . ' ' . $attr['button1Radius']['desktop']['right'] . ' ' . $attr['button1Radius']['desktop']['bottom'] . ' ' . $attr['button1Radius']['desktop']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary' => array(
				'color'            => isset( $attr['button2TextColor'] ) ? $attr['button2TextColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button2BgColor'] ) ? $attr['button2BgColor'] : '#FFB800',
				'margin-top'       => isset( $attr['button2Margin']['desktop']['top'] ) ? $attr['button2Margin']['desktop']['top'] : '5px',
				'margin-left'      => isset( $attr['button2Margin']['desktop']['left'] ) ? $attr['button2Margin']['desktop']['left'] : '0px',
				'margin-right'     => isset( $attr['button2Margin']['desktop']['right'] ) ? $attr['button2Margin']['desktop']['right'] : '0px',
				'margin-bottom'    => isset( $attr['button2Margin']['desktop']['bottom'] ) ? $attr['button2Margin']['desktop']['bottom'] : '5px',
				'padding-top'      => isset( $attr['button2Padding']['desktop']['top'] ) ? $attr['button2Padding']['desktop']['top'] : '10px',
				'padding-left'     => isset( $attr['button2Padding']['desktop']['left'] ) ? $attr['button2Padding']['desktop']['left'] : '5px',
				'padding-right'    => isset( $attr['button2Padding']['desktop']['right'] ) ? $attr['button2Padding']['desktop']['right'] : '5px',
				'padding-bottom'   => isset( $attr['button2Padding']['desktop']['bottom'] ) ? $attr['button2Padding']['desktop']['bottom'] : '10px',
				'border-style'     => isset( $attr['button2Border']['style'] ) ? $attr['button2Border']['style'] : 'none',
				'border-width'     => isset( $attr['button2Border']['width'] ) ? $attr['button2Border']['width'] . 'px' : '1px',
				'border-color'     => isset( $attr['button2Border']['color']['color'] ) ? $attr['button2Border']['color']['color'] : '#dddddd',
				'box-shadow'       => isset( $attr['button2Shadow'] ) && $attr['button2Shadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['button2Shadow'] ) : 'none',
				'border-radius'    => isset( $attr['button2Radius']['desktop']['top'] ) && isset( $attr['button2Radius']['desktop']['right'] ) && isset( $attr['button2Radius']['desktop']['bottom'] ) && isset( $attr['button2Radius']['desktop']['left'] ) ? $attr['button2Radius']['desktop']['top'] . ' ' . $attr['button2Radius']['desktop']['right'] . ' ' . $attr['button2Radius']['desktop']['bottom'] . ' ' . $attr['button2Radius']['desktop']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary:hover' => array(
				'color'            => isset( $attr['button1TextHoverColor'] ) ? $attr['button1TextHoverColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button1BgHoverColor'] ) ? $attr['button1BgHoverColor'] : $global_btn_hover_color,
				'border-color'     => isset( $attr['button1borderHoverColor'] ) ? $attr['button1borderHoverColor'] : '#ffffff',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary:hover' => array(
				'color'            => isset( $attr['button2TextHoverColor'] ) ? $attr['button2TextHoverColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button2BgHoverColor'] ) ? $attr['button2BgHoverColor'] : '#00454A',
				'border-color'     => isset( $attr['button2borderHoverColor'] ) ? $attr['button2borderHoverColor'] : '#ffffff',
			),
			' .affx-pdt-table-wrapper .affx-pdt-price-wrap' => array(
				'color'           => isset( $attr['priceColor'] ) ? $attr['priceColor'] : '#262B33',
				'font-family'     => isset( $attr['priceTypography']['family'] ) ? $attr['priceTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['priceTypography']['size']['desktop'] ) ? $attr['priceTypography']['size']['desktop'] : '22px',
				'line-height'     => isset( $attr['priceTypography']['line-height']['desktop'] ) ? $attr['priceTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['priceTypography']['text-transform'] ) ? $attr['priceTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['priceTypography']['text-decoration'] ) ? $attr['priceTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['priceTypography']['letter-spacing']['desktop'] ) ? $attr['priceTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $priceVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $priceVariation ),
			),
			' .affx-pdt-table-wrapper .affx-pdt-table thead td' => array(
				'background'      => isset( $attr['tableHeaderBgColor'] ) ? $attr['tableHeaderBgColor'] : '#00454A',
				'border-color'    => isset( $attr['tableHeaderBgColor'] ) ? $attr['tableHeaderBgColor'] : '#00454A',
				'color'           => isset( $attr['tableHeaderColor'] ) ? $attr['tableHeaderColor'] : '#FFFFFF',
				'font-family'     => isset( $attr['headerTypography']['family'] ) ? $attr['headerTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['headerTypography']['size']['desktop'] ) ? $attr['headerTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['headerTypography']['line-height']['desktop'] ) ? $attr['headerTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['headerTypography']['text-transform'] ) ? $attr['headerTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['headerTypography']['text-decoration'] ) ? $attr['headerTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['headerTypography']['letter-spacing']['desktop'] ) ? $attr['headerTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $headerVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $headerVariation ),
			),
			' .affx-pdt-table-wrapper .afx-icon-list li:before' => array(
				'color' => isset( $attr['productIconColor'] ) ? $attr['productIconColor'] : '#24B644',
			),
			' .affx-pdt-table-wrapper .afx-icon-list li i' => array(
				'color' => isset( $attr['productIconColor'] ) ? $attr['productIconColor'] : '#24B644',
			),
			' .affx-pdt-table-wrapper .affx-pdt-img-container' => array(
				'padding-top'    => isset( $attr['imagePadding']['desktop']['top'] ) ? $attr['imagePadding']['desktop']['top'] : '0px',
				'padding-left'   => isset( $attr['imagePadding']['desktop']['left'] ) ? $attr['imagePadding']['desktop']['left'] : '0px',
				'padding-right'  => isset( $attr['imagePadding']['desktop']['right'] ) ? $attr['imagePadding']['desktop']['right'] : '0px',
				'padding-bottom' => isset( $attr['imagePadding']['desktop']['bottom'] ) ? $attr['imagePadding']['desktop']['bottom'] : '0px',
			),

		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {
		$global_btn_hover_color = isset( $customization_data['btnHoverColor'] ) ? $customization_data['btnHoverColor'] : '#00454A';

		$mobile_selectors = array(
			' .affx-pdt-table-wrapper'                    => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
				'margin-top'     => isset( $attr['margin']['mobile']['top'] ) ? $attr['margin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['mobile']['left'] ) ? $attr['margin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['mobile']['right'] ) ? $attr['margin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['mobile']['bottom'] ) ? $attr['margin']['mobile']['bottom'] : '30px',
				'border-width'   => isset( $attr['borderWidth']['mobile']['top'] ) && isset( $attr['borderWidth']['mobile']['right'] ) && isset( $attr['borderWidth']['mobile']['bottom'] ) && isset( $attr['borderWidth']['mobile']['left'] ) ? $attr['borderWidth']['mobile']['top'] . ' ' . $attr['borderWidth']['mobile']['right'] . ' ' . $attr['borderWidth']['mobile']['bottom'] . ' ' . $attr['borderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
			),
			' .star-rating-single-wrap'                   => array(
				'font-size'      => isset( $attr['ratingTypography']['size']['mobile'] ) ? $attr['ratingTypography']['size']['mobile'] : '13px',
				'line-height'    => isset( $attr['ratingTypography']['line-height']['mobile'] ) ? $attr['ratingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['ratingTypography']['letter-spacing']['mobile'] ) ? $attr['ratingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-circle-progress-container .affx-circle-inside' => array(
				'font-size'      => isset( $attr['rating2Typography']['size']['mobile'] ) ? $attr['rating2Typography']['size']['mobile'] : '24px',
				'line-height'    => isset( $attr['rating2Typography']['line-height']['mobile'] ) ? $attr['rating2Typography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['rating2Typography']['letter-spacing']['mobile'] ) ? $attr['rating2Typography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper p'                  => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper li'                 => array(
				'font-size'      => isset( $attr['contentTypography']['size']['mobile'] ) ? $attr['contentTypography']['size']['mobile'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['mobile'] ) ? $attr['contentTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['mobile'] ) ? $attr['contentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-name'     => array(
				'font-size'      => isset( $attr['titleTypography']['size']['mobile'] ) ? $attr['titleTypography']['size']['mobile'] : '22px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['mobile'] ) ? $attr['titleTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['mobile'] ) ? $attr['titleTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-single'                     => array(
				'margin-top'    => isset( $attr['margin']['mobile']['top'] ) ? $attr['margin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['mobile']['left'] ) ? $attr['margin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['mobile']['right'] ) ? $attr['margin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['mobile']['bottom'] ) ? $attr['margin']['mobile']['bottom'] : '30px',
			),
			' .affx-pdt-table-wrapper td'                 => array(
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '16px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '16px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '16px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '16px',
			),
			' .affx-pdt-table-wrapper th'                 => array(
				'padding-top'    => isset( $attr['padding']['mobile']['top'] ) ? $attr['padding']['mobile']['top'] : '16px',
				'padding-left'   => isset( $attr['padding']['mobile']['left'] ) ? $attr['padding']['mobile']['left'] : '16px',
				'padding-right'  => isset( $attr['padding']['mobile']['right'] ) ? $attr['padding']['mobile']['right'] : '16px',
				'padding-bottom' => isset( $attr['padding']['mobile']['bottom'] ) ? $attr['padding']['mobile']['bottom'] : '16px',
			),
			' .affx-pdt-table-wrapper .affx-pdt-counter'  => array(
				'font-size'      => isset( $attr['counterTypography']['size']['mobile'] ) ? $attr['counterTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['counterTypography']['line-height']['mobile'] ) ? $attr['counterTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['counterTypography']['letter-spacing']['mobile'] ) ? $attr['counterTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-ribbon'   => array(
				'font-size'      => isset( $attr['ribbonTypography']['size']['mobile'] ) ? $attr['ribbonTypography']['size']['mobile'] : '13px',
				'line-height'    => isset( $attr['ribbonTypography']['line-height']['mobile'] ) ? $attr['ribbonTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonTypography']['letter-spacing']['mobile'] ) ? $attr['ribbonTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affiliatex-button' => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['mobile'] ) ? $attr['buttonTypography']['size']['mobile'] : '14px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['mobile'] ) ? $attr['buttonTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['mobile'] ) ? $attr['buttonTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary' => array(
				'margin-top'     => isset( $attr['button1Margin']['mobile']['top'] ) ? $attr['button1Margin']['mobile']['top'] : '5px',
				'margin-left'    => isset( $attr['button1Margin']['mobile']['left'] ) ? $attr['button1Margin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['button1Margin']['mobile']['right'] ) ? $attr['button1Margin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['button1Margin']['mobile']['bottom'] ) ? $attr['button1Margin']['mobile']['bottom'] : '5px',
				'padding-top'    => isset( $attr['button1Padding']['mobile']['top'] ) ? $attr['button1Padding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['button1Padding']['mobile']['left'] ) ? $attr['button1Padding']['mobile']['left'] : '5px',
				'padding-right'  => isset( $attr['button1Padding']['mobile']['right'] ) ? $attr['button1Padding']['mobile']['right'] : '5px',
				'padding-bottom' => isset( $attr['button1Padding']['mobile']['bottom'] ) ? $attr['button1Padding']['mobile']['bottom'] : '10px',
				'border-style'   => isset( $attr['button1Border']['style'] ) ? $attr['button1Border']['style'] : 'none',
				'border-width'   => isset( $attr['button1Border']['width'] ) ? $attr['button1Border']['width'] . 'px' : '1px',
				'border-color'   => isset( $attr['button1Border']['color']['color'] ) ? $attr['button1Border']['color']['color'] : '#dddddd',
				'box-shadow'     => isset( $attr['button1Shadow'] ) && $attr['button1Shadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['button1Shadow'] ) : 'none',
				'border-radius'  => isset( $attr['button1Radius']['desktop']['top'] ) && isset( $attr['button1Radius']['desktop']['right'] ) && isset( $attr['button1Radius']['desktop']['bottom'] ) && isset( $attr['button1Radius']['desktop']['left'] ) ? $attr['button1Radius']['desktop']['top'] . ' ' . $attr['button1Radius']['desktop']['right'] . ' ' . $attr['button1Radius']['desktop']['bottom'] . ' ' . $attr['button1Radius']['desktop']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary' => array(
				'margin-top'     => isset( $attr['button2Margin']['mobile']['top'] ) ? $attr['button2Margin']['mobile']['top'] : '5px',
				'margin-left'    => isset( $attr['button2Margin']['mobile']['left'] ) ? $attr['button2Margin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['button2Margin']['mobile']['right'] ) ? $attr['button2Margin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['button2Margin']['mobile']['bottom'] ) ? $attr['button2Margin']['mobile']['bottom'] : '5px',
				'padding-top'    => isset( $attr['button2Padding']['mobile']['top'] ) ? $attr['button2Padding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['button2Padding']['mobile']['left'] ) ? $attr['button2Padding']['mobile']['left'] : '5px',
				'padding-right'  => isset( $attr['button2Padding']['mobile']['right'] ) ? $attr['button2Padding']['mobile']['right'] : '5px',
				'padding-bottom' => isset( $attr['button2Padding']['mobile']['bottom'] ) ? $attr['button2Padding']['mobile']['bottom'] : '10px',
				'border-style'   => isset( $attr['button2Border']['style'] ) ? $attr['button2Border']['style'] : 'none',
				'border-width'   => isset( $attr['button2Border']['width'] ) ? $attr['button2Border']['width'] . 'px' : '1px',
				'border-color'   => isset( $attr['button2Border']['color']['color'] ) ? $attr['button2Border']['color']['color'] : '#dddddd',
				'box-shadow'     => isset( $attr['button2Shadow'] ) && $attr['button2Shadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['button2Shadow'] ) : 'none',
				'border-radius'  => isset( $attr['button2Radius']['desktop']['top'] ) && isset( $attr['button2Radius']['desktop']['right'] ) && isset( $attr['button2Radius']['desktop']['bottom'] ) && isset( $attr['button2Radius']['desktop']['left'] ) ? $attr['button2Radius']['desktop']['top'] . ' ' . $attr['button2Radius']['desktop']['right'] . ' ' . $attr['button2Radius']['desktop']['bottom'] . ' ' . $attr['button2Radius']['desktop']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary:hover' => array(
				'color'            => isset( $attr['button1TextHoverColor'] ) ? $attr['button1TextHoverColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button1BgHoverColor'] ) ? $attr['button1BgHoverColor'] : $global_btn_hover_color,
				'border-color'     => isset( $attr['button1borderHoverColor'] ) ? $attr['button1borderHoverColor'] : '#ffffff',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary:hover' => array(
				'color'            => isset( $attr['button2TextHoverColor'] ) ? $attr['button2TextHoverColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button2BgHoverColor'] ) ? $attr['button2BgHoverColor'] : '#00454A',
				'border-color'     => isset( $attr['button2borderHoverColor'] ) ? $attr['button2borderHoverColor'] : '#ffffff',
			),
			' .affx-pdt-table-wrapper .affx-pdt-price-wrap' => array(
				'font-size'      => isset( $attr['priceTypography']['size']['mobile'] ) ? $attr['priceTypography']['size']['mobile'] : '22px',
				'line-height'    => isset( $attr['priceTypography']['line-height']['mobile'] ) ? $attr['priceTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['priceTypography']['letter-spacing']['mobile'] ) ? $attr['priceTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-table thead td' => array(
				'font-size'      => isset( $attr['headerTypography']['size']['mobile'] ) ? $attr['headerTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['headerTypography']['line-height']['mobile'] ) ? $attr['headerTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['headerTypography']['letter-spacing']['mobile'] ) ? $attr['headerTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-img-wrapper' => array(
				'padding-top'    => isset( $attr['imagePadding']['mobile']['top'] ) ? $attr['imagePadding']['mobile']['top'] : '0px',
				'padding-left'   => isset( $attr['imagePadding']['mobile']['left'] ) ? $attr['imagePadding']['mobile']['left'] : '0px',
				'padding-right'  => isset( $attr['imagePadding']['mobile']['right'] ) ? $attr['imagePadding']['mobile']['right'] : '0px',
				'padding-bottom' => isset( $attr['imagePadding']['mobile']['bottom'] ) ? $attr['imagePadding']['mobile']['bottom'] : '0px',
			),
		);

		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {
		$global_btn_hover_color = isset( $customization_data['btnHoverColor'] ) ? $customization_data['btnHoverColor'] : '#00454A';

		$tablet_selectors = array(
			' .affx-pdt-table-wrapper'                    => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
				'margin-top'     => isset( $attr['margin']['tablet']['top'] ) ? $attr['margin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['margin']['tablet']['left'] ) ? $attr['margin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['margin']['tablet']['right'] ) ? $attr['margin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['margin']['tablet']['bottom'] ) ? $attr['margin']['tablet']['bottom'] : '30px',
				'border-width'   => isset( $attr['borderWidth']['tablet']['top'] ) && isset( $attr['borderWidth']['tablet']['right'] ) && isset( $attr['borderWidth']['tablet']['bottom'] ) && isset( $attr['borderWidth']['tablet']['left'] ) ? $attr['borderWidth']['tablet']['top'] . ' ' . $attr['borderWidth']['tablet']['right'] . ' ' . $attr['borderWidth']['tablet']['bottom'] . ' ' . $attr['borderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
			),
			' .star-rating-single-wrap'                   => array(
				'font-size'      => isset( $attr['ratingTypography']['size']['tablet'] ) ? $attr['ratingTypography']['size']['tablet'] : '13px',
				'line-height'    => isset( $attr['ratingTypography']['line-height']['tablet'] ) ? $attr['ratingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['ratingTypography']['letter-spacing']['tablet'] ) ? $attr['ratingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-circle-progress-container .affx-circle-inside' => array(
				'font-size'      => isset( $attr['rating2Typography']['size']['tablet'] ) ? $attr['rating2Typography']['size']['tablet'] : '24px',
				'line-height'    => isset( $attr['rating2Typography']['line-height']['tablet'] ) ? $attr['rating2Typography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['rating2Typography']['letter-spacing']['tablet'] ) ? $attr['rating2Typography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper p'                  => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper li'                 => array(
				'font-size'      => isset( $attr['contentTypography']['size']['tablet'] ) ? $attr['contentTypography']['size']['tablet'] : '16px',
				'line-height'    => isset( $attr['contentTypography']['line-height']['tablet'] ) ? $attr['contentTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['contentTypography']['letter-spacing']['tablet'] ) ? $attr['contentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-name'     => array(
				'font-size'      => isset( $attr['titleTypography']['size']['tablet'] ) ? $attr['titleTypography']['size']['tablet'] : '22px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['tablet'] ) ? $attr['titleTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['tablet'] ) ? $attr['titleTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-single'                     => array(
				'margin-top'    => isset( $attr['margin']['tablet']['top'] ) ? $attr['margin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['margin']['tablet']['left'] ) ? $attr['margin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['margin']['tablet']['right'] ) ? $attr['margin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['margin']['tablet']['bottom'] ) ? $attr['margin']['tablet']['bottom'] : '30px',
			),
			' .affx-pdt-table-wrapper td'                 => array(
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '16px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '16px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '16px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '16px',
			),
			' .affx-pdt-table-wrapper th'                 => array(
				'padding-top'    => isset( $attr['padding']['tablet']['top'] ) ? $attr['padding']['tablet']['top'] : '16px',
				'padding-left'   => isset( $attr['padding']['tablet']['left'] ) ? $attr['padding']['tablet']['left'] : '16px',
				'padding-right'  => isset( $attr['padding']['tablet']['right'] ) ? $attr['padding']['tablet']['right'] : '16px',
				'padding-bottom' => isset( $attr['padding']['tablet']['bottom'] ) ? $attr['padding']['tablet']['bottom'] : '16px',
			),
			' .affx-pdt-table-wrapper .affx-pdt-counter'  => array(
				'font-size'      => isset( $attr['counterTypography']['size']['tablet'] ) ? $attr['counterTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['counterTypography']['line-height']['tablet'] ) ? $attr['counterTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['counterTypography']['letter-spacing']['tablet'] ) ? $attr['counterTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-ribbon'   => array(
				'font-size'      => isset( $attr['ribbonTypography']['size']['tablet'] ) ? $attr['ribbonTypography']['size']['tablet'] : '13px',
				'line-height'    => isset( $attr['ribbonTypography']['line-height']['tablet'] ) ? $attr['ribbonTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonTypography']['letter-spacing']['tablet'] ) ? $attr['ribbonTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affiliatex-button' => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['tablet'] ) ? $attr['buttonTypography']['size']['tablet'] : '14px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['tablet'] ) ? $attr['buttonTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['tablet'] ) ? $attr['buttonTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary' => array(
				'margin-top'     => isset( $attr['button1Margin']['tablet']['top'] ) ? $attr['button1Margin']['tablet']['top'] : '5px',
				'margin-left'    => isset( $attr['button1Margin']['tablet']['left'] ) ? $attr['button1Margin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['button1Margin']['tablet']['right'] ) ? $attr['button1Margin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['button1Margin']['tablet']['bottom'] ) ? $attr['button1Margin']['tablet']['bottom'] : '5px',
				'padding-top'    => isset( $attr['button1Padding']['tablet']['top'] ) ? $attr['button1Padding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['button1Padding']['tablet']['left'] ) ? $attr['button1Padding']['tablet']['left'] : '5px',
				'padding-right'  => isset( $attr['button1Padding']['tablet']['right'] ) ? $attr['button1Padding']['tablet']['right'] : '5px',
				'padding-bottom' => isset( $attr['button1Padding']['tablet']['bottom'] ) ? $attr['button1Padding']['tablet']['bottom'] : '10px',
				'border-style'   => isset( $attr['button1Border']['style'] ) ? $attr['button1Border']['style'] : 'none',
				'border-width'   => isset( $attr['button1Border']['width'] ) ? $attr['button1Border']['width'] . 'px' : '1px',
				'border-color'   => isset( $attr['button1Border']['color']['color'] ) ? $attr['button1Border']['color']['color'] : '#dddddd',
				'box-shadow'     => isset( $attr['button1Shadow'] ) && $attr['button1Shadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['button1Shadow'] ) : 'none',
				'border-radius'  => isset( $attr['button1Radius']['desktop']['top'] ) && isset( $attr['button1Radius']['desktop']['right'] ) && isset( $attr['button1Radius']['desktop']['bottom'] ) && isset( $attr['button1Radius']['desktop']['left'] ) ? $attr['button1Radius']['desktop']['top'] . ' ' . $attr['button1Radius']['desktop']['right'] . ' ' . $attr['button1Radius']['desktop']['bottom'] . ' ' . $attr['button1Radius']['desktop']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary' => array(
				'margin-top'     => isset( $attr['button2Margin']['tablet']['top'] ) ? $attr['button2Margin']['tablet']['top'] : '5px',
				'margin-left'    => isset( $attr['button2Margin']['tablet']['left'] ) ? $attr['button2Margin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['button2Margin']['tablet']['right'] ) ? $attr['button2Margin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['button2Margin']['tablet']['bottom'] ) ? $attr['button2Margin']['tablet']['bottom'] : '5px',
				'padding-top'    => isset( $attr['button2Padding']['tablet']['top'] ) ? $attr['button2Padding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['button2Padding']['tablet']['left'] ) ? $attr['button2Padding']['tablet']['left'] : '5px',
				'padding-right'  => isset( $attr['button2Padding']['tablet']['right'] ) ? $attr['button2Padding']['tablet']['right'] : '5px',
				'padding-bottom' => isset( $attr['button2Padding']['tablet']['bottom'] ) ? $attr['button2Padding']['tablet']['bottom'] : '10px',
				'border-style'   => isset( $attr['button2Border']['style'] ) ? $attr['button2Border']['style'] : 'none',
				'border-width'   => isset( $attr['button2Border']['width'] ) ? $attr['button2Border']['width'] . 'px' : '1px',
				'border-color'   => isset( $attr['button2Border']['color']['color'] ) ? $attr['button2Border']['color']['color'] : '#dddddd',
				'box-shadow'     => isset( $attr['button2Shadow'] ) && $attr['button2Shadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['button2Shadow'] ) : 'none',
				'border-radius'  => isset( $attr['button2Radius']['desktop']['top'] ) && isset( $attr['button2Radius']['desktop']['right'] ) && isset( $attr['button2Radius']['desktop']['bottom'] ) && isset( $attr['button2Radius']['desktop']['left'] ) ? $attr['button2Radius']['desktop']['top'] . ' ' . $attr['button2Radius']['desktop']['right'] . ' ' . $attr['button2Radius']['desktop']['bottom'] . ' ' . $attr['button2Radius']['desktop']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.primary:hover' => array(
				'color'            => isset( $attr['button1TextHoverColor'] ) ? $attr['button1TextHoverColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button1BgHoverColor'] ) ? $attr['button1BgHoverColor'] : $global_btn_hover_color,
				'border-color'     => isset( $attr['button1borderHoverColor'] ) ? $attr['button1borderHoverColor'] : '#ffffff',
			),
			' .affx-pdt-table-wrapper .affiliatex-button.secondary:hover' => array(
				'color'            => isset( $attr['button2TextHoverColor'] ) ? $attr['button2TextHoverColor'] : '#FFFFFF',
				'background-color' => isset( $attr['button2BgHoverColor'] ) ? $attr['button2BgHoverColor'] : '#00454A',
				'border-color'     => isset( $attr['button2borderHoverColor'] ) ? $attr['button2borderHoverColor'] : '#ffffff',
			),
			' .affx-pdt-table-wrapper .affx-pdt-price-wrap' => array(
				'font-size'      => isset( $attr['priceTypography']['size']['tablet'] ) ? $attr['priceTypography']['size']['tablet'] : '22px',
				'line-height'    => isset( $attr['priceTypography']['line-height']['tablet'] ) ? $attr['priceTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['priceTypography']['letter-spacing']['tablet'] ) ? $attr['priceTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-table thead td' => array(
				'font-size'      => isset( $attr['headerTypography']['size']['tablet'] ) ? $attr['headerTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['headerTypography']['line-height']['tablet'] ) ? $attr['headerTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['headerTypography']['letter-spacing']['tablet'] ) ? $attr['headerTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-pdt-table-wrapper .affx-pdt-img-wrapper' => array(
				'padding-top'    => isset( $attr['imagePadding']['tablet']['top'] ) ? $attr['imagePadding']['tablet']['top'] : '0px',
				'padding-left'   => isset( $attr['imagePadding']['tablet']['left'] ) ? $attr['imagePadding']['tablet']['left'] : '0px',
				'padding-right'  => isset( $attr['imagePadding']['tablet']['right'] ) ? $attr['imagePadding']['tablet']['right'] : '0px',
				'padding-bottom' => isset( $attr['imagePadding']['tablet']['bottom'] ) ? $attr['imagePadding']['tablet']['bottom'] : '0px',
			),
		);

		return $tablet_selectors;
	}
}
