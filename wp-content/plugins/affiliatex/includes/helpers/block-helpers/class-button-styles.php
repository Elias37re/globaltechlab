<?php

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\HoverStyles;

require_once __DIR__ . '/class-affiliatex-block-styles-base.php';

/**
 * Button Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Button_Styles extends AffiliateX_Block_Styles_Base {

	protected static function css_id_prefix(): string {
		return '#affiliatex-blocks-style-';
	}

	public static function block_fonts( $attr ) {
		return array( 'buttonTypography' => isset( $attr['buttonTypography'] ) ? $attr['buttonTypography'] : array() );
	}

	/**
	 * Per-device rules for the promoted attributes, mirrors styling.js. Scalars keep the legacy desktop-only output.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_promoted_selectors( array &$buckets, array $attr ): void {
		$promoted = array(
			'buttonFixWidth'  => array( ' .btn-is-fixed', 'max-width' ),
			'buttonAlignment' => array( ' .affx-btn-inner', 'justify-content' ),
			'buttonIconSize'  => array( ' .button-icon', 'font-size' ),
		);

		foreach ( $promoted as $key => $rule ) {
			if ( ! HoverStyles::is_responsive( $attr[ $key ] ?? null ) ) {
				continue;
			}

			list( $selector, $property ) = $rule;

			foreach ( array( 'tablet', 'mobile' ) as $device ) {
				$value = AffiliateX_Helpers::get_responsive_value( $attr[ $key ], $device );

				if ( is_string( $value ) && '' !== $value ) {
					HoverStyles::merge_selector( $buckets[ $device ], $selector, array( $property => $value ) );
				}
			}
		}
	}

	/**
	 * Hover rules for the wave-2 hover attributes, mirrors styling.js. The transition stays conditional so unset hover attributes emit nothing.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_hover_selectors( array &$buckets, array $attr ): void {
		$extras = array();

		if ( HoverStyles::has_typography_value( array( $attr['buttonHoverTypography'] ?? null ), 'size' ) ) {
			$extras[] = 'font-size';
		}

		if ( HoverStyles::has_typography_value( array( $attr['buttonHoverTypography'] ?? null ), 'letter-spacing' ) ) {
			$extras[] = 'letter-spacing';
		}

		if ( HoverStyles::has_spacing_value( $attr['buttonHoverMargin'] ?? null ) ) {
			$extras[] = 'margin';
		}

		if ( HoverStyles::has_spacing_value( $attr['buttonHoverPadding'] ?? null ) ) {
			$extras[] = 'padding';
		}

		$transition = HoverStyles::get_transition( $extras );

		$button_hover = array_merge(
			HoverStyles::get_border_styles( $attr['buttonHoverBorder'] ?? null, false ),
			HoverStyles::get_shadow_styles( $attr['buttonHoverShadow'] ?? null )
		);

		$has_hover_styles = ! empty( $button_hover );

		HoverStyles::merge_selector( $buckets['desktop'], ' .affiliatex-button:hover', $button_hover );

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			$radius = HoverStyles::get_radius_value( $attr['buttonHoverBorderRadius'] ?? null, $device );

			if ( '' !== $radius ) {
				$has_hover_styles = true;
				HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-button:hover', array( 'border-radius' => $radius ) );
			}

			$spacing_hover = array_merge(
				HoverStyles::get_spacing_styles( $attr['buttonHoverMargin'] ?? null, $device, 'margin' ),
				HoverStyles::get_spacing_styles( $attr['buttonHoverPadding'] ?? null, $device, 'padding' )
			);

			if ( ! empty( $spacing_hover ) ) {
				$has_hover_styles = true;
				HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-button:hover', $spacing_hover );
			}

			$typography_hover = HoverStyles::get_typography_styles( $attr['buttonHoverTypography'] ?? null, $device );

			if ( ! empty( $typography_hover ) ) {
				$has_hover_styles = true;
				HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-button:hover', $typography_hover );
			}
		}

		$price_tag_hover = array();

		if ( ! empty( $attr['priceTextHoverColor'] ) && is_string( $attr['priceTextHoverColor'] ) ) {
			$price_tag_hover['color'] = $attr['priceTextHoverColor'];
		}

		$price_bg_hover = ! empty( $attr['priceBackgroundHoverColor'] ) && is_string( $attr['priceBackgroundHoverColor'] ) ? $attr['priceBackgroundHoverColor'] : '';

		if ( '' !== $price_bg_hover ) {
			$price_tag_hover['background-color'] = $price_bg_hover;
		}

		if ( ! empty( $price_tag_hover ) ) {
			$has_hover_styles = true;
			HoverStyles::merge_selector( $buckets['desktop'], ' .affiliatex-button:hover .price-tag', $price_tag_hover );
			HoverStyles::merge_selector( $buckets['desktop'], ' .affiliatex-button .price-tag', array( 'transition' => $transition ) );
		}

		if ( '' !== $price_bg_hover ) {
			HoverStyles::merge_selector( $buckets['desktop'], ' .affiliatex-button:hover .price-tag::before', array( 'background-color' => $price_bg_hover ) );
			HoverStyles::merge_selector( $buckets['desktop'], ' .affiliatex-button .price-tag::before', array( 'transition' => $transition ) );
		}

		if ( $has_hover_styles ) {
			HoverStyles::merge_selector( $buckets['desktop'], ' .affiliatex-button', array( 'transition' => $transition ) );
		}
	}

	public static function get_selectors( $attr ) {

		$customization_data     = affx_get_customization_settings();
		$global_font_family     = isset( $customization_data['typography']['family'] ) ? $customization_data['typography']['family'] : 'Default';
		$global_btn_color       = isset( $customization_data['btnColor'] ) ? $customization_data['btnColor'] : '#00B0B0';
		$global_btn_hover_color = isset( $customization_data['btnHoverColor'] ) ? $customization_data['btnHoverColor'] : '#00454A';

		$bgType                = isset( $attr['buttonBGType'] ) ? $attr['buttonBGType'] : 'solid';
		$bgHoverType           = isset( $attr['buttonHoverBGType'] ) ? $attr['buttonHoverBGType'] : 'solid';
		$buttonBgGradient      = isset( $attr['buttonBgGradient']['gradient'] ) ? $attr['buttonBgGradient']['gradient'] : 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)';
		$buttonBgHoverGradient = isset( $attr['buttonBgHoverGradient']['gradient'] ) ? $attr['buttonBgHoverGradient']['gradient'] : 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)';
		$buttonBGColor         = isset( $attr['buttonBGColor'] ) ? $attr['buttonBGColor'] : $global_btn_color;
		$buttonBGHoverColor    = isset( $attr['buttonBGHoverColor'] ) ? $attr['buttonBGHoverColor'] : $global_btn_hover_color;

		$variation = isset( $attr['buttonTypography']['variation'] ) ? $attr['buttonTypography']['variation'] : 'n4';

		$selectors = array(
			' .affiliatex-button'                    => array(
				'font-family'      => isset( $attr['buttonTypography']['family'] ) ? $attr['buttonTypography']['family'] : $global_font_family,
				'font-size'        => isset( $attr['buttonTypography']['size']['desktop'] ) ? $attr['buttonTypography']['size']['desktop'] : '18px',
				'line-height'      => isset( $attr['buttonTypography']['line-height']['desktop'] ) ? $attr['buttonTypography']['line-height']['desktop'] : '1.65',
				'text-transform'   => isset( $attr['buttonTypography']['text-transform'] ) ? $attr['buttonTypography']['text-transform'] : 'none',
				'text-decoration'  => isset( $attr['buttonTypography']['text-decoration'] ) ? $attr['buttonTypography']['text-decoration'] : 'none',
				'letter-spacing'   => isset( $attr['buttonTypography']['letter-spacing']['desktop'] ) ? $attr['buttonTypography']['letter-spacing']['desktop'] : '0em',
				'margin-top'       => isset( $attr['buttonMargin']['desktop']['top'] ) ? $attr['buttonMargin']['desktop']['top'] : '0px',
				'margin-left'      => isset( $attr['buttonMargin']['desktop']['left'] ) ? $attr['buttonMargin']['desktop']['left'] : '0px',
				'margin-right'     => isset( $attr['buttonMargin']['desktop']['right'] ) ? $attr['buttonMargin']['desktop']['right'] : '0px',
				'margin-bottom'    => isset( $attr['buttonMargin']['desktop']['bottom'] ) ? $attr['buttonMargin']['desktop']['bottom'] : '30px',
				'padding-top'      => isset( $attr['buttonPadding']['desktop']['top'] ) ? $attr['buttonPadding']['desktop']['top'] : '',
				'padding-left'     => isset( $attr['buttonPadding']['desktop']['left'] ) ? $attr['buttonPadding']['desktop']['left'] : '',
				'padding-right'    => isset( $attr['buttonPadding']['desktop']['right'] ) ? $attr['buttonPadding']['desktop']['right'] : '',
				'padding-bottom'   => isset( $attr['buttonPadding']['desktop']['bottom'] ) ? $attr['buttonPadding']['desktop']['bottom'] : '',
				'border-style'     => isset( $attr['buttonBorder']['style'] ) ? $attr['buttonBorder']['style'] : 'none',
				'border-width'     => isset( $attr['buttonBorder']['width'] ) ? $attr['buttonBorder']['width'] . 'px' : '1px',
				'border-color'     => isset( $attr['buttonBorder']['color']['color'] ) ? $attr['buttonBorder']['color']['color'] : '#dddddd',
				'color'            => isset( $attr['buttonTextColor'] ) ? $attr['buttonTextColor'] : '#ffffff',
				'background-color' => $buttonBGColor,
				'background'       => $bgType && $bgType === 'solid' ? $buttonBGColor : $buttonBgGradient,
				'box-shadow'       => isset( $attr['buttonShadow'] ) && $attr['buttonShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['buttonShadow'] ) : 'none',
				'font-weight'      => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'       => AffiliateX_Helpers::get_font_style( $variation ),
				'border-radius'    => isset( $attr['buttonRadius']['desktop']['top'] ) && isset( $attr['buttonRadius']['desktop']['right'] ) && isset( $attr['buttonRadius']['desktop']['bottom'] ) && isset( $attr['buttonRadius']['desktop']['left'] ) ? $attr['buttonRadius']['desktop']['top'] . ' ' . $attr['buttonRadius']['desktop']['right'] . ' ' . $attr['buttonRadius']['desktop']['bottom'] . ' ' . $attr['buttonRadius']['desktop']['left'] . ' ' : '0 0 0 0',

			),
			' .btn-is-fixed'                         => array(
				'max-width' => isset( $attr['buttonFixWidth'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['buttonFixWidth'] ) : '100px',
				'width'     => '100%',
			),
			' .affx-btn-inner'                       => array(
				'justify-content' => isset( $attr['buttonAlignment'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['buttonAlignment'] ) : 'flex-start',
			),
			' .affiliatex-button:hover'              => array(
				'color'        => isset( $attr['buttonTextHoverColor'] ) ? $attr['buttonTextHoverColor'] : '#ffffff',
				'background'   => $bgHoverType && $bgHoverType === 'solid' ? $buttonBGHoverColor : $buttonBgHoverGradient,
				'border-color' => isset( $attr['buttonborderHoverColor'] ) ? $attr['buttonborderHoverColor'] : '#ffffff',
			),
			' .button-icon'                          => array(
				'font-size' => isset( $attr['buttonIconSize'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['buttonIconSize'] ) : '18px',
				'color'     => isset( $attr['buttonIconColor'] ) ? $attr['buttonIconColor'] : '#ffffff',
			),
			' .affiliatex-button:hover .button-icon' => array(
				'color' => isset( $attr['buttonIconHoverColor'] ) ? $attr['buttonIconHoverColor'] : '#ffffff',
			),
			' .affiliatex-button .price-tag'         => array(
				'color'                        => isset( $attr['priceTextColor'] ) ? $attr['priceTextColor'] : '#00B0B0',
				'background-color'             => isset( $attr['priceBackgroundColor'] ) ? $attr['priceBackgroundColor'] : '#ffff',
				'--border-top-left-radius'     => isset( $attr['buttonRadius']['desktop']['top'] ) ? $attr['buttonRadius']['desktop']['top'] : '0px',
				'--border-top-right-radius'    => isset( $attr['buttonRadius']['desktop']['right'] ) ? $attr['buttonRadius']['desktop']['right'] : '0px',
				'--border-bottom-right-radius' => isset( $attr['buttonRadius']['desktop']['bottom'] ) ? $attr['buttonRadius']['desktop']['bottom'] : '0px',
				'--border-bottom-left-radius'  => isset( $attr['buttonRadius']['desktop']['left'] ) ? $attr['buttonRadius']['desktop']['left'] : '0px',
			),
			' .affiliatex-button .price-tag::before' => array(
				'background-color' => isset( $attr['priceBackgroundColor'] ) ? $attr['priceBackgroundColor'] : '#ffff',
			),
		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affiliatex-button'            => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['mobile'] ) ? $attr['buttonTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['mobile'] ) ? $attr['buttonTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['mobile'] ) ? $attr['buttonTypography']['letter-spacing']['mobile'] : '0em',
				'margin-top'     => isset( $attr['buttonMargin']['mobile']['top'] ) ? $attr['buttonMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['buttonMargin']['mobile']['left'] ) ? $attr['buttonMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['buttonMargin']['mobile']['right'] ) ? $attr['buttonMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['buttonMargin']['mobile']['bottom'] ) ? $attr['buttonMargin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['buttonPadding']['mobile']['top'] ) ? $attr['buttonPadding']['mobile']['top'] : '',
				'padding-left'   => isset( $attr['buttonPadding']['mobile']['left'] ) ? $attr['buttonPadding']['mobile']['left'] : '',
				'padding-right'  => isset( $attr['buttonPadding']['mobile']['right'] ) ? $attr['buttonPadding']['mobile']['right'] : '',
				'padding-bottom' => isset( $attr['buttonPadding']['mobile']['bottom'] ) ? $attr['buttonPadding']['mobile']['bottom'] : '',
				'border-radius'  => isset( $attr['buttonRadius']['mobile']['top'] ) && isset( $attr['buttonRadius']['mobile']['right'] ) && isset( $attr['buttonRadius']['mobile']['bottom'] ) && isset( $attr['buttonRadius']['mobile']['left'] ) ? $attr['buttonRadius']['mobile']['top'] . ' ' . $attr['buttonRadius']['mobile']['right'] . ' ' . $attr['buttonRadius']['mobile']['bottom'] . ' ' . $attr['buttonRadius']['mobile']['left'] . ' ' : '0 0 0 0',

			),
			' .affiliatex-button .price-tag' => array(
				'--border-top-left-radius'     => isset( $attr['buttonRadius']['mobile']['top'] ) ? $attr['buttonRadius']['mobile']['top'] : '0px',
				'--border-top-right-radius'    => isset( $attr['buttonRadius']['mobile']['right'] ) ? $attr['buttonRadius']['mobile']['right'] : '0px',
				'--border-bottom-right-radius' => isset( $attr['buttonRadius']['mobile']['bottom'] ) ? $attr['buttonRadius']['mobile']['bottom'] : '0px',
				'--border-bottom-left-radius'  => isset( $attr['buttonRadius']['mobile']['left'] ) ? $attr['buttonRadius']['mobile']['left'] : '0px',
			),
		);

		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affiliatex-button'            => array(
				'font-size'      => isset( $attr['buttonTypography']['size']['tablet'] ) ? $attr['buttonTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['buttonTypography']['line-height']['tablet'] ) ? $attr['buttonTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['buttonTypography']['letter-spacing']['tablet'] ) ? $attr['buttonTypography']['letter-spacing']['tablet'] : '0em',
				'margin-top'     => isset( $attr['buttonMargin']['tablet']['top'] ) ? $attr['buttonMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['buttonMargin']['tablet']['left'] ) ? $attr['buttonMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['buttonMargin']['tablet']['right'] ) ? $attr['buttonMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['buttonMargin']['tablet']['bottom'] ) ? $attr['buttonMargin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['buttonPadding']['tablet']['top'] ) ? $attr['buttonPadding']['tablet']['top'] : '',
				'padding-left'   => isset( $attr['buttonPadding']['tablet']['left'] ) ? $attr['buttonPadding']['tablet']['left'] : '',
				'padding-right'  => isset( $attr['buttonPadding']['tablet']['right'] ) ? $attr['buttonPadding']['tablet']['right'] : '',
				'padding-bottom' => isset( $attr['buttonPadding']['tablet']['bottom'] ) ? $attr['buttonPadding']['tablet']['bottom'] : '',
				'border-radius'  => isset( $attr['buttonRadius']['tablet']['top'] ) && isset( $attr['buttonRadius']['tablet']['right'] ) && isset( $attr['buttonRadius']['tablet']['bottom'] ) && isset( $attr['buttonRadius']['tablet']['left'] ) ? $attr['buttonRadius']['tablet']['top'] . ' ' . $attr['buttonRadius']['tablet']['right'] . ' ' . $attr['buttonRadius']['tablet']['bottom'] . ' ' . $attr['buttonRadius']['tablet']['left'] . ' ' : '0 0 0 0',

			),
			' .affiliatex-button .price-tag' => array(
				'--border-top-left-radius'     => isset( $attr['buttonRadius']['tablet']['top'] ) ? $attr['buttonRadius']['tablet']['top'] : '0px',
				'--border-top-right-radius'    => isset( $attr['buttonRadius']['tablet']['right'] ) ? $attr['buttonRadius']['tablet']['right'] : '0px',
				'--border-bottom-right-radius' => isset( $attr['buttonRadius']['tablet']['bottom'] ) ? $attr['buttonRadius']['tablet']['bottom'] : '0px',
				'--border-bottom-left-radius'  => isset( $attr['buttonRadius']['tablet']['left'] ) ? $attr['buttonRadius']['tablet']['left'] : '0px',
			),
		);

		return $tablet_selectors;
	}
}
