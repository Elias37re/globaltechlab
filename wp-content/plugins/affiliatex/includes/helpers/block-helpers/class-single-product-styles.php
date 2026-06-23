<?php

use AffiliateX\Helpers\AffiliateX_Helpers;

/**
 * 'Single Product', Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Single_Product_Styles {

	private const HOVER_TRANSITION = 'color .15s ease, background-color .15s ease, border-color .15s ease, box-shadow .15s ease, border-radius .15s ease';

	public static function block_fonts( $attr ) {
		return array(
			'productTitleTypography'    => isset( $attr['productTitleTypography'] ) ? $attr['productTitleTypography'] : array(),
			'productSubtitleTypography' => isset( $attr['productSubtitleTypography'] ) ? $attr['productSubtitleTypography'] : array(),
			'productContentTypography'  => isset( $attr['productContentTypography'] ) ? $attr['productContentTypography'] : array(),
			'pricingTypography'         => isset( $attr['pricingTypography'] ) ? $attr['pricingTypography'] : array(),
			'ribbonContentTypography'   => isset( $attr['ribbonContentTypography'] ) ? $attr['ribbonContentTypography'] : array(),
			'numRatingTypography'       => isset( $attr['numRatingTypography'] ) ? $attr['numRatingTypography'] : array(),
			'readMoreTypography'        => isset( $attr['readMoreTypography'] ) ? $attr['readMoreTypography'] : array(),
		);
	}

	public static function block_css( $attr, $id ) {
		$selectors = self::get_selectors( $attr );

		$m_selectors = self::get_mobileselectors( $attr );

		$t_selectors = self::get_tabletselectors( $attr );

		self::apply_responsive_selectors( $selectors, $attr, 'desktop' );
		self::apply_responsive_selectors( $t_selectors, $attr, 'tablet' );
		self::apply_responsive_selectors( $m_selectors, $attr, 'mobile' );

		$hover_transition = self::get_hover_transition_value( $attr );

		self::apply_hover_selectors( $selectors, $attr, $hover_transition );
		self::apply_hover_radius( $t_selectors, $attr, 'tablet' );
		self::apply_hover_radius( $m_selectors, $attr, 'mobile' );

		self::apply_hover_typography( $selectors, $attr, 'desktop', $hover_transition );
		self::apply_hover_typography( $t_selectors, $attr, 'tablet', $hover_transition );
		self::apply_hover_typography( $m_selectors, $attr, 'mobile', $hover_transition );

		self::apply_hover_spacing( $selectors, $attr, 'desktop', $hover_transition );
		self::apply_hover_spacing( $t_selectors, $attr, 'tablet', $hover_transition );
		self::apply_hover_spacing( $m_selectors, $attr, 'mobile', $hover_transition );

		$desktop = AffiliateX_Helpers::generate_css( $selectors, '#affiliatex-single-product-style-' . $id );

		$tablet = AffiliateX_Helpers::generate_css( $t_selectors, '#affiliatex-single-product-style-' . $id );

		$mobile = AffiliateX_Helpers::generate_css( $m_selectors, '#affiliatex-single-product-style-' . $id );

		$generated_css = array(
			'desktop' => $desktop,
			'tablet'  => $tablet,
			'mobile'  => $mobile,
		);

		return $generated_css;
	}

	public static function get_selectors( $attr ) {

		$customization_data = affx_get_customization_settings();
		$global_font_family = isset( $customization_data['typography']['family'] ) ? $customization_data['typography']['family'] : 'Default';
		$global_font_color  = isset( $customization_data['fontColor'] ) ? $customization_data['fontColor'] : '#292929';
		$SPBgGradient       = isset( $attr['productBgGradient']['gradient'] ) ? $attr['productBgGradient']['gradient'] : '';
		$bgColor            = isset( $attr['productBGColor'] ) ? $attr['productBGColor'] : '#fff';
		$ribbonBgColor      = isset( $attr['ribbonBGColor'] ) ? $attr['ribbonBGColor'] : '#ff0000';
		$ribbonGradient     = isset( $attr['ribbonBgGradient']['gradient'] ) ? $attr['ribbonBgGradient']['gradient'] : '';
		$variation          = isset( $attr['productTitleTypography']['variation'] ) ? $attr['productTitleTypography']['variation'] : 'n5';
		$sub_variation      = isset( $attr['productSubtitleTypography']['variation'] ) ? $attr['productSubtitleTypography']['variation'] : 'n5';
		$con_variation      = isset( $attr['productContentTypography']['variation'] ) ? $attr['productContentTypography']['variation'] : 'n4';
		$price_variation    = isset( $attr['pricingTypography']['variation'] ) ? $attr['pricingTypography']['variation'] : 'n4';
		$ribbon_variation   = isset( $attr['ribbonContentTypography']['variation'] ) ? $attr['ribbonContentTypography']['variation'] : 'n4';
		$rating_variation   = isset( $attr['numRatingTypography']['variation'] ) ? $attr['numRatingTypography']['variation'] : 'n4';
		$box_shadow         = array(
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

		$selectors = array(
			' '                                        => array(
				'margin-top'    => '0',
				'margin-bottom' => '0',
			),
			' .affx-single-product-wrapper'            => array(
				'overflow'      => 'hidden',
				'border-width'  => isset( $attr['productBorderWidth']['desktop']['top'] ) && isset( $attr['productBorderWidth']['desktop']['right'] ) && isset( $attr['productBorderWidth']['desktop']['bottom'] ) && isset( $attr['productBorderWidth']['desktop']['left'] ) ? $attr['productBorderWidth']['desktop']['top'] . ' ' . $attr['productBorderWidth']['desktop']['right'] . ' ' . $attr['productBorderWidth']['desktop']['bottom'] . ' ' . $attr['productBorderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['productBorderRadius']['desktop']['top'] ) && isset( $attr['productBorderRadius']['desktop']['right'] ) && isset( $attr['productBorderRadius']['desktop']['bottom'] ) && isset( $attr['productBorderRadius']['desktop']['left'] ) ? $attr['productBorderRadius']['desktop']['top'] . ' ' . $attr['productBorderRadius']['desktop']['right'] . ' ' . $attr['productBorderRadius']['desktop']['bottom'] . ' ' . $attr['productBorderRadius']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['productBorder']['style'] ) ? $attr['productBorder']['style'] : 'solid',
				'border-color'  => isset( $attr['productBorder']['color']['color'] ) ? $attr['productBorder']['color']['color'] : '#E6ECF7',
				'background'    => isset( $attr['productBgColorType'] ) && $attr['productBgColorType'] === 'gradient' ? $SPBgGradient : $bgColor,
				'margin-top'    => isset( $attr['contentMargin']['desktop']['top'] ) ? $attr['contentMargin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['contentMargin']['desktop']['left'] ) ? $attr['contentMargin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['contentMargin']['desktop']['right'] ) ? $attr['contentMargin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['contentMargin']['desktop']['bottom'] ) ? $attr['contentMargin']['desktop']['bottom'] : '30px',
				'box-shadow'    => isset( $attr['productShadow'] ) && $attr['productShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['productShadow'] ) : array(),

			),
			' .affx-single-product-title'              => array(
				'font-family'     => isset( $attr['productTitleTypography']['family'] ) ? $attr['productTitleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['productTitleTypography']['size']['desktop'] ) ? $attr['productTitleTypography']['size']['desktop'] : '24px',
				'line-height'     => isset( $attr['productTitleTypography']['line-height']['desktop'] ) ? $attr['productTitleTypography']['line-height']['desktop'] : '1.333',
				'text-transform'  => isset( $attr['productTitleTypography']['text-transform'] ) ? $attr['productTitleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productTitleTypography']['text-decoration'] ) ? $attr['productTitleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productTitleTypography']['letter-spacing']['desktop'] ) ? $attr['productTitleTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['productTitleColor'] ) ? $attr['productTitleColor'] : '#060c0e',

			),
			' .affx-single-product-subtitle'           => array(
				'font-family'     => isset( $attr['productSubtitleTypography']['family'] ) ? $attr['productSubtitleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $sub_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $sub_variation ),
				'font-size'       => isset( $attr['productSubtitleTypography']['size']['desktop'] ) ? $attr['productSubtitleTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['productSubtitleTypography']['line-height']['desktop'] ) ? $attr['productSubtitleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['productSubtitleTypography']['text-transform'] ) ? $attr['productSubtitleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productSubtitleTypography']['text-decoration'] ) ? $attr['productSubtitleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productSubtitleTypography']['letter-spacing']['desktop'] ) ? $attr['productSubtitleTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['productSubtitleColor'] ) ? $attr['productSubtitleColor'] : '#A3ACBF',
			),
			' .affx-single-product-content'            => array(
				'font-family'     => isset( $attr['productContentTypography']['family'] ) ? $attr['productContentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $con_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $con_variation ),
				'font-size'       => isset( $attr['productContentTypography']['size']['desktop'] ) ? $attr['productContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['productContentTypography']['line-height']['desktop'] ) ? $attr['productContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['productContentTypography']['text-transform'] ) ? $attr['productContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productContentTypography']['text-decoration'] ) ? $attr['productContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productContentTypography']['letter-spacing']['desktop'] ) ? $attr['productContentTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['productContentColor'] ) ? $attr['productContentColor'] : $global_font_color,
			),
			' .affx-single-product-content p'          => array(
				'font-family'     => isset( $attr['productContentTypography']['family'] ) ? $attr['productContentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $con_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $con_variation ),
				'font-size'       => isset( $attr['productContentTypography']['size']['desktop'] ) ? $attr['productContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['productContentTypography']['line-height']['desktop'] ) ? $attr['productContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['productContentTypography']['text-transform'] ) ? $attr['productContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productContentTypography']['text-decoration'] ) ? $attr['productContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productContentTypography']['letter-spacing']['desktop'] ) ? $attr['productContentTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['productContentColor'] ) ? $attr['productContentColor'] : $global_font_color,
			),
			' .affx-single-product-content ul li'      => array(
				'font-family'     => isset( $attr['productContentTypography']['family'] ) ? $attr['productContentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $con_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $con_variation ),
				'font-size'       => isset( $attr['productContentTypography']['size']['desktop'] ) ? $attr['productContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['productContentTypography']['line-height']['desktop'] ) ? $attr['productContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['productContentTypography']['text-transform'] ) ? $attr['productContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productContentTypography']['text-decoration'] ) ? $attr['productContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productContentTypography']['letter-spacing']['desktop'] ) ? $attr['productContentTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['productContentColor'] ) ? $attr['productContentColor'] : $global_font_color,
			),
			' .affx-single-product-content ol li'      => array(
				'font-family'     => isset( $attr['productContentTypography']['family'] ) ? $attr['productContentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $con_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $con_variation ),
				'font-size'       => isset( $attr['productContentTypography']['size']['desktop'] ) ? $attr['productContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['productContentTypography']['line-height']['desktop'] ) ? $attr['productContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['productContentTypography']['text-transform'] ) ? $attr['productContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['productContentTypography']['text-decoration'] ) ? $attr['productContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['productContentTypography']['letter-spacing']['desktop'] ) ? $attr['productContentTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['productContentColor'] ) ? $attr['productContentColor'] : $global_font_color,
			),
			' .affx-single-product-content .affx-readmore-btn' => array(
				'color'           => isset( $attr['readMoreColor'] ) ? $attr['readMoreColor'] : '#00B0B0',
				'font-family'     => isset( $attr['readMoreTypography']['family'] ) ? $attr['readMoreTypography']['family'] : 'Default',
				'font-weight'     => isset( $attr['readMoreTypography']['variation'] ) ? AffiliateX_Helpers::get_fontweight_variation( $attr['readMoreTypography']['variation'] ) : '400',
				'font-style'      => isset( $attr['readMoreTypography']['variation'] ) ? AffiliateX_Helpers::get_font_style( $attr['readMoreTypography']['variation'] ) : 'normal',
				'font-size'       => isset( $attr['readMoreTypography']['size']['desktop'] ) ? $attr['readMoreTypography']['size']['desktop'] : '14px',
				'line-height'     => isset( $attr['readMoreTypography']['line-height']['desktop'] ) ? $attr['readMoreTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['readMoreTypography']['text-transform'] ) ? $attr['readMoreTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['readMoreTypography']['text-decoration'] ) ? $attr['readMoreTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['readMoreTypography']['letter-spacing']['desktop'] ) ? $attr['readMoreTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-sp-marked-price'                   => array(
				'font-family'     => isset( $attr['pricingTypography']['family'] ) ? $attr['pricingTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $price_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $price_variation ),
				'font-size'       => isset( $attr['pricingTypography']['size']['desktop'] ) ? $attr['pricingTypography']['size']['desktop'] : '22px',
				'line-height'     => isset( $attr['pricingTypography']['line-height']['desktop'] ) ? $attr['pricingTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['pricingTypography']['text-transform'] ) ? $attr['pricingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['pricingTypography']['text-decoration'] ) ? $attr['pricingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['pricingTypography']['letter-spacing']['desktop'] ) ? $attr['pricingTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-sp-sale-price'                     => array(
				'font-family'     => isset( $attr['pricingTypography']['family'] ) ? $attr['pricingTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $price_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $price_variation ),
				'font-size'       => isset( $attr['pricingTypography']['size']['desktop'] ) ? $attr['pricingTypography']['size']['desktop'] : '22px',
				'line-height'     => isset( $attr['pricingTypography']['line-height']['desktop'] ) ? $attr['pricingTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['pricingTypography']['text-transform'] ) ? $attr['pricingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['pricingTypography']['text-decoration'] ) ? $attr['pricingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['pricingTypography']['letter-spacing']['desktop'] ) ? $attr['pricingTypography']['letter-spacing']['desktop'] : '0em',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-content-wrapper' => array(
				'padding' => '0 0 0 0',
			),
			' .affx-sp-content-wrapper'                => array(
				'padding-top'    => isset( $attr['contentSpacing']['desktop']['top'] ) ? $attr['contentSpacing']['desktop']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['desktop']['bottom'] ) ? $attr['contentSpacing']['desktop']['bottom'] : '30px',
			),
			' .affx-sp-price'                          => array(
				'color' => isset( $attr['pricingColor'] ) ? $attr['pricingColor'] : '#262B33',
			),
			' .affx-sp-price .affx-sp-sale-price'      => array(
				'color' => isset( $attr['pricingHoverColor'] ) ? $attr['pricingHoverColor'] : '#A3ACBF',
			),
			' .affx-sp-rating-number'                  => array(
				'width' => '100px',
			),

			' .affx-sp-content-wrapper .title-wrapper' => array(
				'border-color'        => isset( $attr['productDivider']['color']['color'] ) ? $attr['productDivider']['color']['color'] : '#E6ECF7',
				'border-style'        => isset( $attr['productDivider']['style'] ) ? $attr['productDivider']['style'] : 'none',
				'border-bottom-width' => isset( $attr['productDivider']['width'] ) ? $attr['productDivider']['width'] . 'px' : '1',
			),

			' .affx-single-product-wrapper.product-layout-2 .title-wrapper' => array(
				'padding-top'    => isset( $attr['contentSpacing']['desktop']['top'] ) ? $attr['contentSpacing']['desktop']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['desktop']['bottom'] ) ? $attr['contentSpacing']['desktop']['bottom'] : '30px',
			),

			' .affx-single-product-wrapper.product-layout-2 .affx-sp-price' => array(
				'padding-left'  => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
			),

			' .affx-single-product-wrapper.product-layout-2 .button-wrapper' => array(
				'padding-left'   => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['desktop']['bottom'] ) ? $attr['contentSpacing']['desktop']['bottom'] : '30px',
			),

			' .affx-single-product-wrapper.product-layout-2 .affx-single-product-content' => array(
				'padding-left'  => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
			),

			' .affx-single-product-wrapper.product-layout-3 .affx-sp-inner' => array(
				'padding-top'    => isset( $attr['contentSpacing']['desktop']['top'] ) ? $attr['contentSpacing']['desktop']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['desktop']['left'] ) ? $attr['contentSpacing']['desktop']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['desktop']['right'] ) ? $attr['contentSpacing']['desktop']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['desktop']['bottom'] ) ? $attr['contentSpacing']['desktop']['bottom'] : '30px',
			),

			' .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-right .affx-sp-content-wrapper' => array(
				'padding-top'    => '0',
				'padding-left'   => '24px',
				'padding-right'  => '24px',
				'padding-bottom' => '0',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-left .affx-sp-content-wrapper' => array(
				'padding-top'    => '0',
				'padding-left'   => '24px',
				'padding-right'  => '0',
				'padding-bottom' => '0',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-content.image-left .button-wrapper' => array(
				'padding-left' => '24px',
			),

			' .affx-sp-ribbon'                         => array(
				'width' => '100%',
			),

			' .product-layout-2 .affx-sp-ribbon.ribbon-align-left' => array(
				'text-align' => 'left',
			),
			' .product-layout-2 .affx-sp-ribbon.ribbon-align-right' => array(
				'text-align' => 'right',
			),

			' .affx-sp-ribbon-title'                   => array(
				'background'      => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'gradient' ? $ribbonGradient : $ribbonBgColor,
				'font-family'     => isset( $attr['ribbonContentTypography']['family'] ) ? $attr['ribbonContentTypography']['family'] : 'Default',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $ribbon_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $ribbon_variation ),
				'font-size'       => isset( $attr['ribbonContentTypography']['size']['desktop'] ) ? $attr['ribbonContentTypography']['size']['desktop'] : '17px',
				'line-height'     => isset( $attr['ribbonContentTypography']['line-height']['desktop'] ) ? $attr['ribbonContentTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['ribbonContentTypography']['text-transform'] ) ? $attr['ribbonContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ribbonContentTypography']['text-decoration'] ) ? $attr['ribbonContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ribbonContentTypography']['letter-spacing']['desktop'] ) ? $attr['ribbonContentTypography']['letter-spacing']['desktop'] : '0em',
				'color'           => isset( $attr['ribbonColor'] ) ? $attr['ribbonColor'] : '#fff',
			),

			' .affx-sp-ribbon.ribbon-layout-two .affx-sp-ribbon-title:before' => array(
				'border-bottom-color' => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'gradient' ?
					$ribbonGradient :
					$ribbonBgColor,
			),

			' .affx-sp-ribbon.ribbon-layout-two.ribbon-align-right .affx-sp-ribbon-title:before' => array(
				'border-right-color' => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'gradient' ?
					$ribbonGradient :
					$ribbonBgColor,
			),

			' .affx-sp-ribbon.ribbon-layout-two.ribbon-align-left .affx-sp-ribbon-title:before' => array(
				'border-bottom-color' => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'gradient' ?
					$ribbonGradient :
					$ribbonBgColor,
			),

			' .affx-sp-content.image-right .affx-sp-ribbon.ribbon-layout-two .affx-sp-ribbon-title:before' => array(
				'border-bottom-color' => 'transparent',
				'border-right-color'  => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'gradient' ?
					$ribbonGradient :
					$ribbonBgColor,
			),

			' .affx-sp-content.image-right .affx-sp-ribbon.ribbon-layout-two .affx-sp-ribbon-title:hover:before' => array(
				'border-bottom-color' => isset( $attr['ribbonBgColorType'] ) && $attr['ribbonBgColorType'] === 'solid' ? $attr['ribbonBGColor'] : $ribbonGradient,
			),

			' .affx-single-product-content li:before'  => array(
				'color' => isset( $attr['iconColor'] ) ? $attr['iconColor'] : '#24B644',
			),
			' .affx-single-product-content i'          => array(
				'color' => isset( $attr['iconColor'] ) ? $attr['iconColor'] : '#24B644',
			),

			' .affx-rating-number'                     => array(
				'background'      => isset( $attr['productRateNumBgColor'] ) ? $attr['productRateNumBgColor'] : '#00B0B0',
				'color'           => isset( $attr['productRateNumberColor'] ) ? $attr['productRateNumberColor'] : '#ffffff',
				'font-family'     => isset( $attr['numRatingTypography']['family'] ) ? $attr['numRatingTypography']['family'] : 'Default',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $rating_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $rating_variation ),
				'font-size'       => isset( $attr['numRatingTypography']['size']['desktop'] ) ? $attr['numRatingTypography']['size']['desktop'] : '36px',
				'line-height'     => isset( $attr['numRatingTypography']['line-height']['desktop'] ) ? $attr['numRatingTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['numRatingTypography']['text-transform'] ) ? $attr['numRatingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['numRatingTypography']['text-decoration'] ) ? $attr['numRatingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['numRatingTypography']['letter-spacing']['desktop'] ) ? $attr['numRatingTypography']['letter-spacing']['desktop'] : '0em',
			),

			' .affx-rating-number .num'                => array(
				'background' => isset( $attr['productRateNumBgColor'] ) ? $attr['productRateNumBgColor'] : '#00B0B0',
				'color'      => isset( $attr['productRateNumberColor'] ) ? $attr['productRateNumberColor'] : '#ffffff',
			),

			' .affx-rating-number .label'              => array(
				'background' => isset( $attr['productRateContentBgColor'] ) ? $attr['productRateContentBgColor'] : '#262B33',
				'color'      => isset( $attr['productRateContentColor'] ) ? $attr['productRateContentColor'] : '#ffffff',
				'font-size'  => '0.444em',
			),

			' .affx-rating-number .label::before'      => array(
				'border-bottom-color' => isset( $attr['productRateContentBgColor'] ) ? $attr['productRateContentBgColor'] : '#262B33',
			),

			' .affx-rating-input-content:before'       => array(
				'border-bottom-color' => isset( $attr['productRateContentBgColor'] ) ? $attr['productRateContentBgColor'] : '#262B33',
			),

			' .affx-rating-input-content input'        => array(
				'color'           => isset( $attr['productRateContentColor'] ) ? $attr['productRateContentColor'] : '#ffffff',
				'font-family'     => isset( $attr['numRatingTypography']['family'] ) ? $attr['numRatingTypography']['family'] : 'Default',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $rating_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $rating_variation ),
				'font-size'       => isset( $attr['numRatingTypography']['size']['desktop'] ) ? $attr['numRatingTypography']['size']['desktop'] : '36px',
				'line-height'     => isset( $attr['numRatingTypography']['line-height']['desktop'] ) ? $attr['numRatingTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['numRatingTypography']['text-transform'] ) ? $attr['numRatingTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['numRatingTypography']['text-decoration'] ) ? $attr['numRatingTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['numRatingTypography']['letter-spacing']['desktop'] ) ? $attr['numRatingTypography']['letter-spacing']['desktop'] : '0em',
			),

			' .affx-single-product-wrapper .affx-sp-img-wrapper' => array(
				'padding-top'    => isset( $attr['imagePadding']['desktop']['top'] ) ? $attr['imagePadding']['desktop']['top'] : '0px',
				'padding-left'   => isset( $attr['imagePadding']['desktop']['left'] ) ? $attr['imagePadding']['desktop']['left'] : '0px',
				'padding-right'  => isset( $attr['imagePadding']['desktop']['right'] ) ? $attr['imagePadding']['desktop']['right'] : '0px',
				'padding-bottom' => isset( $attr['imagePadding']['desktop']['bottom'] ) ? $attr['imagePadding']['desktop']['bottom'] : '0px',
				'overflow'       => 'hidden',
				'border-radius'  => isset( $attr['productImageBorderRadius']['desktop']['top'] ) && isset( $attr['productImageBorderRadius']['desktop']['right'] ) && isset( $attr['productImageBorderRadius']['desktop']['bottom'] ) && isset( $attr['productImageBorderRadius']['desktop']['left'] ) ? $attr['productImageBorderRadius']['desktop']['top'] . ' ' . $attr['productImageBorderRadius']['desktop']['right'] . ' ' . $attr['productImageBorderRadius']['desktop']['bottom'] . ' ' . $attr['productImageBorderRadius']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-single-product-wrapper .affx-sp-img-wrapper img' => array(
				'border-radius' => isset( $attr['productImageBorderRadius']['desktop']['top'] ) && isset( $attr['productImageBorderRadius']['desktop']['right'] ) && isset( $attr['productImageBorderRadius']['desktop']['bottom'] ) && isset( $attr['productImageBorderRadius']['desktop']['left'] ) ? $attr['productImageBorderRadius']['desktop']['top'] . ' ' . $attr['productImageBorderRadius']['desktop']['right'] . ' ' . $attr['productImageBorderRadius']['desktop']['bottom'] . ' ' . $attr['productImageBorderRadius']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'aspect-ratio'  => isset( $attr['imageAspectRatio'] ) && 'auto' !== $attr['imageAspectRatio'] ? $attr['imageAspectRatio'] : '',
				'object-fit'    => isset( $attr['imageAspectRatio'] ) && 'auto' !== $attr['imageAspectRatio'] ? 'contain' : '',
			),
		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affx-single-product-wrapper'     => array(
				'border-width'  => isset( $attr['productBorderWidth']['mobile']['top'] ) && isset( $attr['productBorderWidth']['mobile']['right'] ) && isset( $attr['productBorderWidth']['mobile']['bottom'] ) && isset( $attr['productBorderWidth']['mobile']['left'] ) ? $attr['productBorderWidth']['mobile']['top'] . ' ' . $attr['productBorderWidth']['mobile']['right'] . ' ' . $attr['productBorderWidth']['mobile']['bottom'] . ' ' . $attr['productBorderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['productBorderRadius']['mobile']['top'] ) && isset( $attr['productBorderRadius']['mobile']['right'] ) && isset( $attr['productBorderRadius']['mobile']['bottom'] ) && isset( $attr['productBorderRadius']['mobile']['left'] ) ? $attr['productBorderRadius']['mobile']['top'] . ' ' . $attr['productBorderRadius']['mobile']['right'] . ' ' . $attr['productBorderRadius']['mobile']['bottom'] . ' ' . $attr['productBorderRadius']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['contentMargin']['mobile']['top'] ) ? $attr['contentMargin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['contentMargin']['mobile']['left'] ) ? $attr['contentMargin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['contentMargin']['mobile']['right'] ) ? $attr['contentMargin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['contentMargin']['mobile']['bottom'] ) ? $attr['contentMargin']['mobile']['bottom'] : '30px',

			),
			' .affx-single-product-title'       => array(
				'font-size'      => isset( $attr['productTitleTypography']['size']['mobile'] ) ? $attr['productTitleTypography']['size']['mobile'] : '24px',
				'line-height'    => isset( $attr['productTitleTypography']['line-height']['mobile'] ) ? $attr['productTitleTypography']['line-height']['mobile'] : '1.333',
				'letter-spacing' => isset( $attr['productTitleTypography']['letter-spacing']['mobile'] ) ? $attr['productTitleTypography']['letter-spacing']['mobile'] : '0em',

			),
			' .affx-single-product-subtitle'    => array(
				'font-size'      => isset( $attr['productSubtitleTypography']['size']['mobile'] ) ? $attr['productSubtitleTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['productSubtitleTypography']['line-height']['mobile'] ) ? $attr['productSubtitleTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['productSubtitleTypography']['letter-spacing']['mobile'] ) ? $attr['productSubtitleTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-single-product-content'     => array(
				'font-size'      => isset( $attr['productContentTypography']['size']['mobile'] ) ? $attr['productContentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['productContentTypography']['line-height']['mobile'] ) ? $attr['productContentTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['productContentTypography']['letter-spacing']['mobile'] ) ? $attr['productContentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-sp-marked-price'            => array(
				'font-size'      => isset( $attr['pricingTypography']['size']['mobile'] ) ? $attr['pricingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['pricingTypography']['line-height']['mobile'] ) ? $attr['pricingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['pricingTypography']['letter-spacing']['mobile'] ) ? $attr['pricingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-sp-sale-price'              => array(
				'font-size'      => isset( $attr['pricingTypography']['size']['mobile'] ) ? $attr['pricingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['pricingTypography']['line-height']['mobile'] ) ? $attr['pricingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['pricingTypography']['letter-spacing']['mobile'] ) ? $attr['pricingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-sp-content-wrapper'         => array(
				'padding-top'    => isset( $attr['contentSpacing']['mobile']['top'] ) ? $attr['contentSpacing']['mobile']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['mobile']['bottom'] ) ? $attr['contentSpacing']['mobile']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .title-wrapper' => array(
				'padding-top'    => isset( $attr['contentSpacing']['mobile']['top'] ) ? $attr['contentSpacing']['mobile']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['mobile']['bottom'] ) ? $attr['contentSpacing']['mobile']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .affx-sp-price' => array(
				'padding-left'  => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
			),
			' .affx-single-product-wrapper.product-layout-2 .button-wrapper' => array(
				'padding-left'   => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['mobile']['bottom'] ) ? $attr['contentSpacing']['mobile']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .affx-single-product-content' => array(
				'padding-left'  => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-inner' => array(
				'padding-top'    => isset( $attr['contentSpacing']['mobile']['top'] ) ? $attr['contentSpacing']['mobile']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['mobile']['left'] ) ? $attr['contentSpacing']['mobile']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['mobile']['right'] ) ? $attr['contentSpacing']['mobile']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['mobile']['bottom'] ) ? $attr['contentSpacing']['mobile']['bottom'] : '30px',
			),
			' .affx-sp-ribbon-title'            => array(
				'font-size'      => isset( $attr['ribbonContentTypography']['size']['mobile'] ) ? $attr['ribbonContentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['ribbonContentTypography']['line-height']['mobile'] ) ? $attr['ribbonContentTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonContentTypography']['letter-spacing']['mobile'] ) ? $attr['ribbonContentTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-rating-input-number'        => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['mobile'] ) ? $attr['numRatingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['mobile'] ) ? $attr['numRatingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['mobile'] ) ? $attr['numRatingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-rating-input-number input'  => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['mobile'] ) ? $attr['numRatingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['mobile'] ) ? $attr['numRatingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['mobile'] ) ? $attr['numRatingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-rating-input-content'       => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['mobile'] ) ? $attr['numRatingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['mobile'] ) ? $attr['numRatingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['mobile'] ) ? $attr['numRatingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-rating-input-content input' => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['mobile'] ) ? $attr['numRatingTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['mobile'] ) ? $attr['numRatingTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['mobile'] ) ? $attr['numRatingTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-single-product-wrapper .affx-sp-img-wrapper' => array(
				'padding-top'    => isset( $attr['imagePadding']['mobile']['top'] ) ? $attr['imagePadding']['mobile']['top'] : '0px',
				'padding-left'   => isset( $attr['imagePadding']['mobile']['left'] ) ? $attr['imagePadding']['mobile']['left'] : '0px',
				'padding-right'  => isset( $attr['imagePadding']['mobile']['right'] ) ? $attr['imagePadding']['mobile']['right'] : '0px',
				'padding-bottom' => isset( $attr['imagePadding']['mobile']['bottom'] ) ? $attr['imagePadding']['mobile']['bottom'] : '0px',
				'overflow'       => 'hidden',
				'border-radius'  => isset( $attr['productImageBorderRadius']['mobile']['top'] ) && isset( $attr['productImageBorderRadius']['mobile']['right'] ) && isset( $attr['productImageBorderRadius']['mobile']['bottom'] ) && isset( $attr['productImageBorderRadius']['mobile']['left'] ) ? $attr['productImageBorderRadius']['mobile']['top'] . ' ' . $attr['productImageBorderRadius']['mobile']['right'] . ' ' . $attr['productImageBorderRadius']['mobile']['bottom'] . ' ' . $attr['productImageBorderRadius']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-single-product-wrapper .affx-sp-img-wrapper img' => array(
				'border-radius' => isset( $attr['productImageBorderRadius']['mobile']['top'] ) && isset( $attr['productImageBorderRadius']['mobile']['right'] ) && isset( $attr['productImageBorderRadius']['mobile']['bottom'] ) && isset( $attr['productImageBorderRadius']['mobile']['left'] ) ? $attr['productImageBorderRadius']['mobile']['top'] . ' ' . $attr['productImageBorderRadius']['mobile']['right'] . ' ' . $attr['productImageBorderRadius']['mobile']['bottom'] . ' ' . $attr['productImageBorderRadius']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-single-product-content .affx-readmore-btn' => array(
				'font-size'      => isset( $attr['readMoreTypography']['size']['mobile'] ) ? $attr['readMoreTypography']['size']['mobile'] : '14px',
				'line-height'    => isset( $attr['readMoreTypography']['line-height']['mobile'] ) ? $attr['readMoreTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['readMoreTypography']['letter-spacing']['mobile'] ) ? $attr['readMoreTypography']['letter-spacing']['mobile'] : '0em',
			),
		);
		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affx-single-product-wrapper'     => array(
				'border-width'  => isset( $attr['productBorderWidth']['tablet']['top'] ) && isset( $attr['productBorderWidth']['tablet']['right'] ) && isset( $attr['productBorderWidth']['tablet']['bottom'] ) && isset( $attr['productBorderWidth']['tablet']['left'] ) ? $attr['productBorderWidth']['tablet']['top'] . ' ' . $attr['productBorderWidth']['tablet']['right'] . ' ' . $attr['productBorderWidth']['tablet']['bottom'] . ' ' . $attr['productBorderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius' => isset( $attr['productBorderRadius']['tablet']['top'] ) && isset( $attr['productBorderRadius']['tablet']['right'] ) && isset( $attr['productBorderRadius']['tablet']['bottom'] ) && isset( $attr['productBorderRadius']['tablet']['left'] ) ? $attr['productBorderRadius']['tablet']['top'] . ' ' . $attr['productBorderRadius']['tablet']['right'] . ' ' . $attr['productBorderRadius']['tablet']['bottom'] . ' ' . $attr['productBorderRadius']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['contentMargin']['tablet']['top'] ) ? $attr['contentMargin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['contentMargin']['tablet']['left'] ) ? $attr['contentMargin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['contentMargin']['tablet']['right'] ) ? $attr['contentMargin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['contentMargin']['tablet']['bottom'] ) ? $attr['contentMargin']['tablet']['bottom'] : '30px',

			),
			' .affx-single-product-title'       => array(
				'font-size'      => isset( $attr['productTitleTypography']['size']['tablet'] ) ? $attr['productTitleTypography']['size']['tablet'] : '24px',
				'line-height'    => isset( $attr['productTitleTypography']['line-height']['tablet'] ) ? $attr['productTitleTypography']['line-height']['tablet'] : '1.333',
				'letter-spacing' => isset( $attr['productTitleTypography']['letter-spacing']['tablet'] ) ? $attr['productTitleTypography']['letter-spacing']['tablet'] : '0em',

			),
			' .affx-single-product-subtitle'    => array(
				'font-size'      => isset( $attr['productSubtitleTypography']['size']['tablet'] ) ? $attr['productSubtitleTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['productSubtitleTypography']['line-height']['tablet'] ) ? $attr['productSubtitleTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['productSubtitleTypography']['letter-spacing']['tablet'] ) ? $attr['productSubtitleTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-single-product-content'     => array(
				'font-size'      => isset( $attr['productContentTypography']['size']['tablet'] ) ? $attr['productContentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['productContentTypography']['line-height']['tablet'] ) ? $attr['productContentTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['productContentTypography']['letter-spacing']['tablet'] ) ? $attr['productContentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-sp-marked-price'            => array(
				'font-size'      => isset( $attr['pricingTypography']['size']['tablet'] ) ? $attr['pricingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['pricingTypography']['line-height']['tablet'] ) ? $attr['pricingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['pricingTypography']['letter-spacing']['tablet'] ) ? $attr['pricingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-sp-sale-price'              => array(
				'font-size'      => isset( $attr['pricingTypography']['size']['tablet'] ) ? $attr['pricingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['pricingTypography']['line-height']['tablet'] ) ? $attr['pricingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['pricingTypography']['letter-spacing']['tablet'] ) ? $attr['pricingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-sp-content-wrapper'         => array(
				'padding-top'    => isset( $attr['contentSpacing']['tablet']['top'] ) ? $attr['contentSpacing']['tablet']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['tablet']['bottom'] ) ? $attr['contentSpacing']['tablet']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .title-wrapper' => array(
				'padding-top'    => isset( $attr['contentSpacing']['tablet']['top'] ) ? $attr['contentSpacing']['tablet']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['tablet']['bottom'] ) ? $attr['contentSpacing']['tablet']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .affx-sp-price' => array(
				'padding-left'  => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
			),
			' .affx-single-product-wrapper.product-layout-2 .button-wrapper' => array(
				'padding-left'   => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['tablet']['bottom'] ) ? $attr['contentSpacing']['tablet']['bottom'] : '30px',
			),
			' .affx-single-product-wrapper.product-layout-2 .affx-single-product-content' => array(
				'padding-left'  => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right' => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
			),
			' .affx-single-product-wrapper.product-layout-3 .affx-sp-inner' => array(
				'padding-top'    => isset( $attr['contentSpacing']['tablet']['top'] ) ? $attr['contentSpacing']['tablet']['top'] : '30px',
				'padding-left'   => isset( $attr['contentSpacing']['tablet']['left'] ) ? $attr['contentSpacing']['tablet']['left'] : '25px',
				'padding-right'  => isset( $attr['contentSpacing']['tablet']['right'] ) ? $attr['contentSpacing']['tablet']['right'] : '25px',
				'padding-bottom' => isset( $attr['contentSpacing']['tablet']['bottom'] ) ? $attr['contentSpacing']['tablet']['bottom'] : '30px',
			),
			' .affx-sp-ribbon-title'            => array(
				'font-size'      => isset( $attr['ribbonContentTypography']['size']['tablet'] ) ? $attr['ribbonContentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['ribbonContentTypography']['line-height']['tablet'] ) ? $attr['ribbonContentTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['ribbonContentTypography']['letter-spacing']['tablet'] ) ? $attr['ribbonContentTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-rating-input-number'        => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['tablet'] ) ? $attr['numRatingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['tablet'] ) ? $attr['numRatingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['tablet'] ) ? $attr['numRatingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-rating-input-number input'  => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['tablet'] ) ? $attr['numRatingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['tablet'] ) ? $attr['numRatingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['tablet'] ) ? $attr['numRatingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-rating-input-content'       => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['tablet'] ) ? $attr['numRatingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['tablet'] ) ? $attr['numRatingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['tablet'] ) ? $attr['numRatingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-rating-input-content input' => array(
				'font-size'      => isset( $attr['numRatingTypography']['size']['tablet'] ) ? $attr['numRatingTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['numRatingTypography']['line-height']['tablet'] ) ? $attr['numRatingTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['numRatingTypography']['letter-spacing']['tablet'] ) ? $attr['numRatingTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-single-product-wrapper .affx-sp-img-wrapper' => array(
				'padding-top'    => isset( $attr['imagePadding']['tablet']['top'] ) ? $attr['imagePadding']['tablet']['top'] : '0px',
				'padding-left'   => isset( $attr['imagePadding']['tablet']['left'] ) ? $attr['imagePadding']['tablet']['left'] : '0px',
				'padding-right'  => isset( $attr['imagePadding']['tablet']['right'] ) ? $attr['imagePadding']['tablet']['right'] : '0px',
				'padding-bottom' => isset( $attr['imagePadding']['tablet']['bottom'] ) ? $attr['imagePadding']['tablet']['bottom'] : '0px',
				'overflow'       => 'hidden',
				'border-radius'  => isset( $attr['productImageBorderRadius']['tablet']['top'] ) && isset( $attr['productImageBorderRadius']['tablet']['right'] ) && isset( $attr['productImageBorderRadius']['tablet']['bottom'] ) && isset( $attr['productImageBorderRadius']['tablet']['left'] ) ? $attr['productImageBorderRadius']['tablet']['top'] . ' ' . $attr['productImageBorderRadius']['tablet']['right'] . ' ' . $attr['productImageBorderRadius']['tablet']['bottom'] . ' ' . $attr['productImageBorderRadius']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-single-product-wrapper .affx-sp-img-wrapper img' => array(
				'border-radius' => isset( $attr['productImageBorderRadius']['tablet']['top'] ) && isset( $attr['productImageBorderRadius']['tablet']['right'] ) && isset( $attr['productImageBorderRadius']['tablet']['bottom'] ) && isset( $attr['productImageBorderRadius']['tablet']['left'] ) ? $attr['productImageBorderRadius']['tablet']['top'] . ' ' . $attr['productImageBorderRadius']['tablet']['right'] . ' ' . $attr['productImageBorderRadius']['tablet']['bottom'] . ' ' . $attr['productImageBorderRadius']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
			),
			' .affx-single-product-content .affx-readmore-btn' => array(
				'font-size'      => isset( $attr['readMoreTypography']['size']['tablet'] ) ? $attr['readMoreTypography']['size']['tablet'] : '14px',
				'line-height'    => isset( $attr['readMoreTypography']['line-height']['tablet'] ) ? $attr['readMoreTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['readMoreTypography']['letter-spacing']['tablet'] ) ? $attr['readMoreTypography']['letter-spacing']['tablet'] : '0em',
			),
		);
		return $tablet_selectors;
	}

	/**
	 * Merge styles into a selector bucket without dropping existing properties.
	 *
	 * @param array  $selectors Selector bucket, by reference.
	 * @param string $selector CSS selector key.
	 * @param array  $styles Property => value pairs.
	 * @return void
	 */
	private static function merge_selector( array &$selectors, string $selector, array $styles ): void {
		$selectors[ $selector ] = array_merge( $selectors[ $selector ] ?? array(), $styles );
	}

	/**
	 * Map alignment keywords to flexbox values, mirrors FLEX_ALIGN_MAP in styling.js.
	 *
	 * @param mixed $value Alignment keyword.
	 * @return string
	 */
	private static function get_flex_align( $value ): string {
		$map = array(
			'left'   => 'flex-start',
			'top'    => 'flex-start',
			'center' => 'center',
			'middle' => 'center',
			'right'  => 'flex-end',
			'bottom' => 'flex-end',
		);

		return is_string( $value ) && isset( $map[ $value ] ) ? $map[ $value ] : '';
	}

	/**
	 * Per-device rules for the responsive attributes, mirrors applyResponsiveStyles in styling.js.
	 *
	 * @param array  $selectors Selector bucket for the device, by reference.
	 * @param array  $attr Block attributes.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @return void
	 */
	private static function apply_responsive_selectors( array &$selectors, array $attr, string $device ): void {
		$title_align    = AffiliateX_Helpers::get_responsive_value( $attr['productTitleAlign'] ?? 'left', $device );
		$subtitle_align = AffiliateX_Helpers::get_responsive_value( $attr['productSubtitleAlign'] ?? 'left', $device );
		$content_align  = AffiliateX_Helpers::get_responsive_value( $attr['productContentAlign'] ?? 'left', $device );
		$pricing_align  = AffiliateX_Helpers::get_responsive_value( $attr['productPricingAlign'] ?? 'left', $device );
		$star_align     = AffiliateX_Helpers::get_responsive_value( $attr['productStarRatingAlign'] ?? 'left', $device );
		$score_align    = AffiliateX_Helpers::get_responsive_value( $attr['productRatingAlign'] ?? 'right', $device );

		self::merge_selector( $selectors, ' .affx-single-product-title', array( 'text-align' => $title_align ) );
		self::merge_selector( $selectors, ' .affx-single-product-subtitle', array( 'text-align' => $subtitle_align ) );
		self::merge_selector( $selectors, ' .affx-single-product-content', array( 'justify-content' => $content_align ) );
		self::merge_selector( $selectors, ' .affx-single-product-content p', array( 'text-align' => $content_align ) );
		self::merge_selector( $selectors, ' .affx-single-product-content ul li', array( 'justify-content' => $content_align ) );
		self::merge_selector( $selectors, ' .affx-single-product-content ol li', array( 'justify-content' => $content_align ) );
		self::merge_selector( $selectors, ' .affx-sp-price', array( 'text-align' => $pricing_align ) );
		self::merge_selector( $selectors, ' .affx-sp-pricing-pic', array( 'text-align' => $star_align ) );

		$is_score_left = 'left' === $score_align;
		self::merge_selector( $selectors, ' .title-wrapper.affx-number-rating', array( 'flex-direction' => $is_score_left ? 'row-reverse' : 'row' ) );
		self::merge_selector(
			$selectors,
			' .title-wrapper.affx-number-rating .affx-rating-number',
			array(
				'margin-left'  => $is_score_left ? '0px' : '15px',
				'margin-right' => $is_score_left ? '15px' : '0px',
			)
		);

		$buttons_gap = AffiliateX_Helpers::get_responsive_value( $attr['buttonsGap'] ?? 10, $device );
		self::merge_selector( $selectors, ' .button-wrapper', array( '--button-gap' => is_numeric( $buttons_gap ) ? $buttons_gap . 'px' : '' ) );

		$star_size     = AffiliateX_Helpers::get_responsive_value( $attr['ratingStarSize'] ?? 25, $device );
		$star_size_css = array(
			'width'  => is_numeric( $star_size ) ? $star_size . 'px' : '',
			'height' => is_numeric( $star_size ) ? $star_size . 'px' : '',
		);
		self::merge_selector( $selectors, ' .affx-sp-pricing-pic svg', $star_size_css );
		self::merge_selector( $selectors, ' .affx-sp-pricing-pic .affx-star', $star_size_css );

		$image_width  = AffiliateX_Helpers::get_responsive_value( $attr['productImageWidth'] ?? 'inherit', $device );
		$custom_width = AffiliateX_Helpers::get_responsive_value( $attr['productImageCustomWidth'] ?? '33', $device );
		$image_flex   = 'custom' === $image_width && is_numeric( $custom_width ) ? '0 0 ' . $custom_width . '%' : '';
		self::merge_selector( $selectors, ' .affx-single-product-wrapper.product-layout-1 .affx-sp-img-wrapper', array( 'flex' => $image_flex ) );
		self::merge_selector( $selectors, ' .affx-single-product-wrapper.product-layout-3 .affx-sp-img-wrapper', array( 'flex' => $image_flex ) );

		$product_layout = $attr['productLayout'] ?? 'layoutOne';

		if ( $attr['edProductImage'] ?? true ) {
			$image_align = AffiliateX_Helpers::get_responsive_value( $attr['productImageAlign'] ?? 'left', $device );

			if ( 'layoutTwo' !== $product_layout && 'mobile' !== $device ) {
				self::merge_selector( $selectors, ' .affx-sp-content', array( 'flex-direction' => 'right' === $image_align ? 'row-reverse' : 'row' ) );
			}

			self::merge_selector(
				$selectors,
				' .affx-single-product-wrapper .affx-sp-img-wrapper',
				array(
					'align-items'     => self::get_flex_align( AffiliateX_Helpers::get_responsive_value( $attr['productImageHorizontalAlign'] ?? 'center', $device ) ),
					'justify-content' => self::get_flex_align( AffiliateX_Helpers::get_responsive_value( $attr['productImageVerticalAlign'] ?? 'top', $device ) ),
				)
			);
		}

		if ( ! empty( $attr['edRibbon'] ) ) {
			$ribbon_align  = AffiliateX_Helpers::get_responsive_value( $attr['ribbonAlign'] ?? 'left', $device );
			$ribbon_styles = self::get_ribbon_align_selectors( $attr, 'right' === $ribbon_align ? 'right' : 'left' );

			foreach ( $ribbon_styles as $selector => $styles ) {
				self::merge_selector( $selectors, $selector, $styles );
			}
		}
	}

	/**
	 * Ribbon offset rules per alignment; the rendered class reflects the desktop value, mirrors styling.js.
	 *
	 * @param array  $attr Block attributes.
	 * @param string $align Resolved alignment for the device, 'left' or 'right'.
	 * @return array
	 */
	private static function get_ribbon_align_selectors( array $attr, string $align ): array {
		$product_layout = $attr['productLayout'] ?? 'layoutOne';
		$ribbon_layout  = $attr['productRibbonLayout'] ?? 'one';
		$desktop_align  = 'right' === AffiliateX_Helpers::get_responsive_value( $attr['ribbonAlign'] ?? 'left' ) ? 'right' : 'left';
		$ribbon_bg      = isset( $attr['ribbonBgColorType'] ) && 'gradient' === $attr['ribbonBgColorType']
			? ( $attr['ribbonBgGradient']['gradient'] ?? '' )
			: ( $attr['ribbonBGColor'] ?? '#ff0000' );

		$rendered_ribbon = sprintf( ' .affx-sp-ribbon.ribbon-layout-%s.ribbon-align-%s', $ribbon_layout, $desktop_align );
		$is_right        = 'right' === $align;
		$styles          = array();

		if ( 'layoutTwo' === $product_layout ) {
			$styles[ $rendered_ribbon ] = $is_right
				? array(
					'text-align' => 'right',
					'right'      => '-25px',
					'left'       => 'auto',
				)
				: array(
					'text-align' => 'left',
					'left'       => '-25px',
					'right'      => 'auto',
				);

			$styles[ $rendered_ribbon . ' .affx-sp-ribbon-title' ] = $is_right
				? array(
					'margin-left'  => 'auto',
					'margin-right' => '0px',
				)
				: array(
					'margin-right' => 'auto',
					'margin-left'  => '0px',
				);

			return $styles;
		}

		$is_ribbon_layout_two = 'two' === $ribbon_layout;
		$offset               = $is_ribbon_layout_two
			? array(
				'left'  => 'layoutThree' === $product_layout ? '-12px' : '-13px',
				'right' => 'layoutThree' === $product_layout ? '-12px' : '-15px',
			)
			: array(
				'left'  => '0px',
				'right' => '0px',
			);

		$styles[ $rendered_ribbon ] = array( 'text-align' => $is_right ? 'right' : 'left' );

		$styles[ $rendered_ribbon . ' .affx-sp-ribbon-title' ] = $is_right
			? array(
				'right' => $offset['right'],
				'left'  => 'auto',
			)
			: array(
				'left'  => $offset['left'],
				'right' => 'auto',
			);

		if ( $is_ribbon_layout_two ) {
			$styles[ $rendered_ribbon . ' .affx-sp-ribbon-title:before' ] = $is_right
				? array(
					'right'               => '1px',
					'left'                => 'auto',
					'top'                 => '33px',
					'bottom'              => 'auto',
					'transform'           => 'rotate(-180deg) translate(-5%, -28%)',
					'border-right-color'  => $ribbon_bg,
					'border-bottom-color' => 'transparent',
				)
				: array(
					'left'                => '12px',
					'right'               => 'auto',
					'bottom'              => '-10px',
					'top'                 => 'auto',
					'transform'           => 'rotate(45deg) translate(-48%, 28%)',
					'border-bottom-color' => $ribbon_bg,
					'border-right-color'  => 'transparent',
				);
		}

		return $styles;
	}

	/**
	 * Hover color attribute map: rules to emit and elements receiving the transition.
	 *
	 * @return array
	 */
	private static function get_hover_color_map(): array {
		return array(
			'productTitleHoverColor'         => array(
				'rules'      => array( ' .affx-single-product-title:hover' => 'color' ),
				'transition' => array( ' .affx-single-product-title' ),
			),
			'productSubtitleHoverColor'      => array(
				'rules'      => array( ' .affx-single-product-subtitle:hover' => 'color' ),
				'transition' => array( ' .affx-single-product-subtitle' ),
			),
			'productContentHoverColor'       => array(
				'rules'      => array(
					' .affx-single-product-content:hover' => 'color',
					' .affx-single-product-content:hover p' => 'color',
					' .affx-single-product-content:hover ul li' => 'color',
					' .affx-single-product-content:hover ol li' => 'color',
				),
				'transition' => array(
					' .affx-single-product-content',
					' .affx-single-product-content p',
					' .affx-single-product-content ul li',
					' .affx-single-product-content ol li',
				),
			),
			'iconHoverColor'                 => array(
				'rules'      => array(
					' .affx-single-product-content li:hover:before' => 'color',
					' .affx-single-product-content li:hover i' => 'color',
				),
				'transition' => array(
					' .affx-single-product-content li:before',
					' .affx-single-product-content i',
				),
			),
			'readMoreHoverColor'             => array(
				'rules'      => array( ' .affx-single-product-content .affx-readmore-btn:hover' => 'color' ),
				'transition' => array( ' .affx-single-product-content .affx-readmore-btn' ),
			),
			'productPriceHoverColor'         => array(
				'rules'      => array( ' .affx-sp-marked-price:hover' => 'color' ),
				'transition' => array( ' .affx-sp-marked-price' ),
			),
			'productSalePriceHoverColor'     => array(
				'rules'      => array( ' .affx-sp-sale-price:hover' => 'color' ),
				'transition' => array( ' .affx-sp-sale-price' ),
			),
			'ratingHoverColor'               => array(
				'rules'      => array( ' .affx-sp-pricing-pic:hover svg path' => 'fill' ),
				'transition' => array(),
			),
			'ratingInactiveHoverColor'       => array(
				'rules'      => array( ' .affx-sp-pricing-pic:hover .affx-star-inactive svg path' => 'fill' ),
				'transition' => array(),
			),
			'productRateNumberHoverColor'    => array(
				'rules'      => array(
					' .affx-rating-number:hover'      => 'color',
					' .affx-rating-number:hover .num' => 'color',
				),
				'transition' => array( ' .affx-rating-number', ' .affx-rating-number .num' ),
			),
			'productRateContentHoverColor'   => array(
				'rules'      => array(
					' .affx-rating-number:hover .label' => 'color',
					' .affx-rating-input-content:hover input' => 'color',
				),
				'transition' => array( ' .affx-rating-number .label', ' .affx-rating-input-content input' ),
			),
			'productRateNumBgHoverColor'     => array(
				'rules'      => array(
					' .affx-rating-number:hover'      => 'background-color',
					' .affx-rating-number:hover .num' => 'background-color',
				),
				'transition' => array( ' .affx-rating-number', ' .affx-rating-number .num' ),
			),
			'productRateContentBgHoverColor' => array(
				'rules'      => array(
					' .affx-rating-number:hover .label' => 'background-color',
					' .affx-rating-number:hover .label::before' => 'border-bottom-color',
					' .affx-rating-input-content:hover:before' => 'border-bottom-color',
				),
				'transition' => array(
					' .affx-rating-number .label',
					' .affx-rating-number .label::before',
					' .affx-rating-input-content:before',
				),
			),
			'ribbonHoverColor'               => array(
				'rules'      => array( ' .affx-sp-ribbon-title:hover' => 'color' ),
				'transition' => array( ' .affx-sp-ribbon-title' ),
			),
			'ribbonBGHoverColor'             => array(
				'rules'      => array(
					' .affx-sp-ribbon-title:hover' => 'background-color',
					' .affx-sp-ribbon.ribbon-layout-two.ribbon-align-right .affx-sp-ribbon-title:hover:before' => 'border-right-color',
					' .affx-sp-ribbon.ribbon-layout-two.ribbon-align-left .affx-sp-ribbon-title:hover:before' => 'border-bottom-color',
				),
				'transition' => array( ' .affx-sp-ribbon-title' ),
			),
		);
	}

	/**
	 * Emit :hover rules for set hover attributes, mirrors hover output in styling.js.
	 *
	 * @param array  $selectors Desktop selector bucket, by reference.
	 * @param array  $attr Block attributes.
	 * @param string $transition Shared hover transition value.
	 * @return void
	 */
	private static function apply_hover_selectors( array &$selectors, array $attr, string $transition ): void {
		foreach ( self::get_hover_color_map() as $key => $config ) {
			$value = $attr[ $key ] ?? '';

			if ( ! is_string( $value ) || '' === $value ) {
				continue;
			}

			foreach ( $config['rules'] as $selector => $property ) {
				self::merge_selector( $selectors, $selector, array( $property => $value ) );
			}

			self::apply_hover_transition( $selectors, $config['transition'], $transition );
		}

		$wrapper_hover = array();

		// Background mirrors styling.js: empty hover type inherits the normal type, then gradient wins, else solid color.
		$hover_type  = $attr['productBgHoverColorType'] ?? '';
		$bg_type     = is_string( $hover_type ) && '' !== $hover_type ? $hover_type : ( $attr['productBgColorType'] ?? 'solid' );
		$bg_gradient = $attr['productBgHoverGradient']['gradient'] ?? '';
		$bg_color    = $attr['productBGHoverColor'] ?? '';

		if ( 'gradient' === $bg_type && is_string( $bg_gradient ) && '' !== $bg_gradient ) {
			$wrapper_hover['background-image'] = $bg_gradient;
		} elseif ( is_string( $bg_color ) && '' !== $bg_color ) {
			$wrapper_hover['background-color'] = $bg_color;
		}

		$border = $attr['productHoverBorder'] ?? array();
		if ( is_array( $border ) && ! empty( $border['style'] ) && is_string( $border['style'] ) && 'none' !== $border['style'] ) {
			$wrapper_hover['border-style'] = $border['style'];

			if ( ! empty( $border['color']['color'] ) && is_string( $border['color']['color'] ) ) {
				$wrapper_hover['border-color'] = $border['color']['color'];
			}

			if ( ! empty( $border['width'] ) && is_numeric( $border['width'] ) ) {
				$wrapper_hover['border-width'] = $border['width'] . 'px';
			}
		}

		$shadow = $attr['productHoverShadow'] ?? array();
		if ( is_array( $shadow ) && ! empty( $shadow['enable'] ) ) {
			$wrapper_hover['box-shadow'] = AffiliateX_Helpers::get_css_boxshadow( $shadow );
		}

		$desktop_radius = self::get_hover_radius( $attr, 'desktop' );
		if ( '' !== $desktop_radius ) {
			$wrapper_hover['border-radius'] = $desktop_radius;
		}

		$has_responsive_radius = '' !== self::get_hover_radius( $attr, 'tablet' ) || '' !== self::get_hover_radius( $attr, 'mobile' );

		if ( ! empty( $wrapper_hover ) ) {
			self::merge_selector( $selectors, ' .affx-single-product-wrapper:hover', $wrapper_hover );
		}

		if ( ! empty( $wrapper_hover ) || $has_responsive_radius ) {
			self::apply_hover_transition( $selectors, array( ' .affx-single-product-wrapper' ), $transition );
		}
	}

	/**
	 * Add the shared hover transition to the given base selectors.
	 *
	 * @param array  $selectors Selector bucket, by reference.
	 * @param array  $targets Base selectors to receive the transition.
	 * @param string $transition Shared hover transition value.
	 * @return void
	 */
	private static function apply_hover_transition( array &$selectors, array $targets, string $transition ): void {
		foreach ( $targets as $selector ) {
			self::merge_selector( $selectors, $selector, array( 'transition' => $transition ) );
		}
	}

	/**
	 * Shared transition value, mirrors transitionProperties in styling.js.
	 *
	 * @param array $attr Block attributes.
	 * @return string
	 */
	private static function get_hover_transition_value( array $attr ): string {
		$transition = self::HOVER_TRANSITION;

		if ( self::has_hover_typography_value( $attr, 'size' ) ) {
			$transition .= ', font-size .15s ease';
		}

		if ( self::has_hover_typography_value( $attr, 'letter-spacing' ) ) {
			$transition .= ', letter-spacing .15s ease';
		}

		if ( self::has_hover_spacing_value( $attr['contentHoverMargin'] ?? array() ) ) {
			$transition .= ', margin .15s ease';
		}

		if ( self::has_hover_spacing_value( $attr['imageHoverPadding'] ?? array() ) || self::has_hover_spacing_value( $attr['contentHoverSpacing'] ?? array() ) ) {
			$transition .= ', padding .15s ease';
		}

		return $transition;
	}

	/**
	 * Hover typography attribute map: hover selectors per device and transition targets.
	 *
	 * @return array
	 */
	private static function get_hover_typography_map(): array {
		return array(
			'productTitleHoverTypography'    => array(
				'desktop'    => array( ' .affx-single-product-title:hover' ),
				'responsive' => array( ' .affx-single-product-title:hover' ),
				'transition' => array( ' .affx-single-product-title' ),
			),
			'productSubtitleHoverTypography' => array(
				'desktop'    => array( ' .affx-single-product-subtitle:hover' ),
				'responsive' => array( ' .affx-single-product-subtitle:hover' ),
				'transition' => array( ' .affx-single-product-subtitle' ),
			),
			'pricingHoverTypography'         => array(
				'desktop'    => array( ' .affx-sp-marked-price:hover', ' .affx-sp-sale-price:hover' ),
				'responsive' => array( ' .affx-sp-marked-price:hover', ' .affx-sp-sale-price:hover' ),
				'transition' => array( ' .affx-sp-marked-price', ' .affx-sp-sale-price' ),
			),
			'productContentHoverTypography'  => array(
				'desktop'    => array(
					' .affx-single-product-content:hover',
					' .affx-single-product-content:hover p',
					' .affx-single-product-content:hover ul li',
					' .affx-single-product-content:hover ol li',
				),
				'responsive' => array( ' .affx-single-product-content:hover' ),
				'transition' => array(
					' .affx-single-product-content',
					' .affx-single-product-content p',
					' .affx-single-product-content ul li',
					' .affx-single-product-content ol li',
				),
			),
			'readMoreHoverTypography'        => array(
				'desktop'    => array( ' .affx-single-product-content .affx-readmore-btn:hover' ),
				'responsive' => array(),
				'transition' => array( ' .affx-single-product-content .affx-readmore-btn' ),
			),
			'ribbonContentHoverTypography'   => array(
				'desktop'    => array( ' .affx-sp-ribbon-title:hover' ),
				'responsive' => array( ' .affx-sp-ribbon-title:hover' ),
				'transition' => array( ' .affx-sp-ribbon-title' ),
			),
			'numRatingHoverTypography'       => array(
				'desktop'    => array( ' .affx-rating-number:hover', ' .affx-rating-input-content:hover input' ),
				'responsive' => array(
					' .affx-rating-input-number:hover',
					' .affx-rating-input-number:hover input',
					' .affx-rating-input-content:hover',
					' .affx-rating-input-content:hover input',
				),
				'transition' => array( ' .affx-rating-number', ' .affx-rating-input-content input' ),
			),
		);
	}

	/**
	 * Whether a hover typography value is unset, mirrors isUnsetTypographyValue in styling.js.
	 *
	 * @param mixed $value Device value.
	 * @return bool
	 */
	private static function is_unset_typography_value( $value ): bool {
		if ( ! is_string( $value ) && ! is_numeric( $value ) ) {
			return true;
		}

		return '' === $value || 'CT_CSS_SKIP_RULE' === $value;
	}

	/**
	 * Per-device value from a hover typography key, scalars apply to every device.
	 *
	 * @param array  $typo Hover typography attribute value.
	 * @param string $key Typography key, e.g. 'size'.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @return mixed
	 */
	private static function get_hover_typography_device_value( array $typo, string $key, string $device ) {
		$value = $typo[ $key ] ?? '';

		return is_array( $value ) ? ( $value[ $device ] ?? '' ) : $value;
	}

	/**
	 * Font rules from a hover typography object, only the keys that are set.
	 *
	 * @param array  $typo Hover typography attribute value.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @return array
	 */
	private static function get_hover_font_styles( array $typo, string $device ): array {
		$styles = array();

		$responsive_props = array(
			'size'           => 'font-size',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
		);

		foreach ( $responsive_props as $key => $property ) {
			$value = self::get_hover_typography_device_value( $typo, $key, $device );

			if ( ! self::is_unset_typography_value( $value ) ) {
				$styles[ $property ] = $value;
			}
		}

		if ( 'desktop' === $device ) {
			if ( ! empty( $typo['family'] ) && is_string( $typo['family'] ) && 'Default' !== $typo['family'] ) {
				$styles['font-family'] = $typo['family'];
			}

			if ( ! empty( $typo['variation'] ) && is_string( $typo['variation'] ) && 'Default' !== $typo['variation'] ) {
				$styles['font-weight'] = AffiliateX_Helpers::get_fontweight_variation( $typo['variation'] );
				$styles['font-style']  = AffiliateX_Helpers::get_font_style( $typo['variation'] );
			}

			if ( ! empty( $typo['text-transform'] ) && is_string( $typo['text-transform'] ) && 'none' !== $typo['text-transform'] ) {
				$styles['text-transform'] = $typo['text-transform'];
			}
		}

		return $styles;
	}

	/**
	 * Whether any hover typography attribute sets the given key on any device.
	 *
	 * @param array  $attr Block attributes.
	 * @param string $key Typography key, e.g. 'size'.
	 * @return bool
	 */
	private static function has_hover_typography_value( array $attr, string $key ): bool {
		foreach ( array_keys( self::get_hover_typography_map() ) as $attr_key ) {
			$typo = $attr[ $attr_key ] ?? array();

			if ( ! is_array( $typo ) || empty( $typo[ $key ] ) ) {
				continue;
			}

			$values = is_array( $typo[ $key ] ) ? $typo[ $key ] : array( $typo[ $key ] );

			foreach ( $values as $value ) {
				if ( ! self::is_unset_typography_value( $value ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Emit :hover font rules for set hover typography attributes, empty object emits nothing.
	 *
	 * @param array  $selectors Selector bucket for the device, by reference.
	 * @param array  $attr Block attributes.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @param string $transition Shared hover transition value.
	 * @return void
	 */
	private static function apply_hover_typography( array &$selectors, array $attr, string $device, string $transition ): void {
		foreach ( self::get_hover_typography_map() as $key => $config ) {
			$typo = $attr[ $key ] ?? array();

			if ( ! is_array( $typo ) || empty( $typo ) ) {
				continue;
			}

			$styles = self::get_hover_font_styles( $typo, $device );

			if ( ! empty( $styles ) ) {
				$targets = 'desktop' === $device ? $config['desktop'] : $config['responsive'];

				foreach ( $targets as $selector ) {
					self::merge_selector( $selectors, $selector, $styles );
				}
			}

			if ( 'desktop' !== $device ) {
				continue;
			}

			$has_styles = ! empty( $styles )
				|| ! empty( self::get_hover_font_styles( $typo, 'tablet' ) )
				|| ! empty( self::get_hover_font_styles( $typo, 'mobile' ) );

			if ( $has_styles ) {
				self::apply_hover_transition( $selectors, $config['transition'], $transition );
			}
		}
	}

	/**
	 * Hover spacing attribute map: card-hover rules with side subsets, mirrors styling.js.
	 *
	 * @return array
	 */
	private static function get_hover_spacing_map(): array {
		$all_sides = array( 'top', 'left', 'right', 'bottom' );

		return array(
			'imageHoverPadding'   => array(
				'property'   => 'padding',
				'rules'      => array(
					' .affx-single-product-wrapper:hover .affx-sp-img-wrapper' => $all_sides,
				),
				'transition' => array( ' .affx-single-product-wrapper .affx-sp-img-wrapper' ),
			),
			'contentHoverMargin'  => array(
				'property'   => 'margin',
				'rules'      => array(
					' .affx-single-product-wrapper:hover' => $all_sides,
				),
				'transition' => array( ' .affx-single-product-wrapper' ),
			),
			'contentHoverSpacing' => array(
				'property'   => 'padding',
				'rules'      => array(
					' .affx-single-product-wrapper:hover .affx-sp-content-wrapper' => $all_sides,
					' .affx-single-product-wrapper.product-layout-2:hover .title-wrapper' => array( 'top', 'left', 'right' ),
					' .affx-single-product-wrapper.product-layout-2:hover .affx-sp-price' => array( 'left', 'right' ),
					' .affx-single-product-wrapper.product-layout-2:hover .button-wrapper' => array( 'left', 'right', 'bottom' ),
					' .affx-single-product-wrapper.product-layout-2:hover .affx-single-product-content' => array( 'left', 'right' ),
					' .affx-single-product-wrapper.product-layout-3:hover .affx-sp-inner' => $all_sides,
				),
				'transition' => array(
					' .affx-sp-content-wrapper',
					' .affx-single-product-wrapper.product-layout-2 .title-wrapper',
					' .affx-single-product-wrapper.product-layout-2 .affx-sp-price',
					' .affx-single-product-wrapper.product-layout-2 .button-wrapper',
					' .affx-single-product-wrapper.product-layout-2 .affx-single-product-content',
					' .affx-single-product-wrapper.product-layout-3 .affx-sp-inner',
				),
			),
		);
	}

	/**
	 * Whether a hover spacing attribute sets any side on any device, mirrors hasHoverSpacingValue in styling.js.
	 *
	 * @param mixed $spacing Hover spacing attribute value.
	 * @return bool
	 */
	private static function has_hover_spacing_value( $spacing ): bool {
		if ( ! is_array( $spacing ) ) {
			return false;
		}

		foreach ( $spacing as $sides ) {
			if ( ! is_array( $sides ) ) {
				continue;
			}

			foreach ( $sides as $value ) {
				if ( ( is_string( $value ) || is_numeric( $value ) ) && '' !== $value ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Emit :hover spacing rules for set hover spacing attributes, empty object emits nothing.
	 *
	 * @param array  $selectors Selector bucket for the device, by reference.
	 * @param array  $attr Block attributes.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @param string $transition Shared hover transition value.
	 * @return void
	 */
	private static function apply_hover_spacing( array &$selectors, array $attr, string $device, string $transition ): void {
		foreach ( self::get_hover_spacing_map() as $key => $config ) {
			$spacing = $attr[ $key ] ?? array();

			if ( ! is_array( $spacing ) || empty( $spacing ) ) {
				continue;
			}

			$sides = $spacing[ $device ] ?? array();

			if ( is_array( $sides ) ) {
				foreach ( $config['rules'] as $selector => $rule_sides ) {
					$styles = array();

					foreach ( $rule_sides as $side ) {
						$value = $sides[ $side ] ?? '';

						if ( ( is_string( $value ) || is_numeric( $value ) ) && '' !== $value ) {
							$styles[ $config['property'] . '-' . $side ] = $value;
						}
					}

					if ( ! empty( $styles ) ) {
						self::merge_selector( $selectors, $selector, $styles );
					}
				}
			}

			if ( 'desktop' === $device && self::has_hover_spacing_value( $spacing ) ) {
				self::apply_hover_transition( $selectors, $config['transition'], $transition );
			}
		}
	}

	/**
	 * Per-device wrapper :hover border-radius for productHoverBorderRadius.
	 *
	 * @param array  $selectors Selector bucket for the device, by reference.
	 * @param array  $attr Block attributes.
	 * @param string $device One of 'tablet', 'mobile'.
	 * @return void
	 */
	private static function apply_hover_radius( array &$selectors, array $attr, string $device ): void {
		$radius = self::get_hover_radius( $attr, $device );

		if ( '' !== $radius ) {
			self::merge_selector( $selectors, ' .affx-single-product-wrapper:hover', array( 'border-radius' => $radius ) );
		}
	}

	/**
	 * Resolve the hover border-radius shorthand for a device, empty when unset.
	 *
	 * @param array  $attr Block attributes.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @return string
	 */
	private static function get_hover_radius( array $attr, string $device ): string {
		$sides = $attr['productHoverBorderRadius'][ $device ] ?? array();

		if ( ! is_array( $sides ) || empty( $sides ) ) {
			return '';
		}

		$values    = array();
		$has_value = false;

		foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
			$value = $sides[ $side ] ?? '';
			$value = is_string( $value ) || is_numeric( $value ) ? (string) $value : '';
			$value = '' === $value ? '0' : $value;

			if ( (float) $value > 0 ) {
				$has_value = true;
			}

			$values[] = $value;
		}

		return $has_value ? implode( ' ', $values ) : '';
	}
}
