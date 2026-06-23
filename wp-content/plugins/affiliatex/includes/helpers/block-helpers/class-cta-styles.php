<?php

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\HoverStyles;

require_once __DIR__ . '/class-affiliatex-block-styles-base.php';

/**
 * CTA Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_CTA_Styles extends AffiliateX_Block_Styles_Base {

	protected static function css_id_prefix(): string {
		return '#affiliatex-style-';
	}

	public static function block_fonts( $attr ) {
		return array(
			'ctaTitleTypography'   => isset( $attr['ctaTitleTypography'] ) ? $attr['ctaTitleTypography'] : array(),
			'ctaContentTypography' => isset( $attr['ctaContentTypography'] ) ? $attr['ctaContentTypography'] : array(),
		);
	}

	/**
	 * Per-device rules for the promoted attributes, mirrors styling.js. Scalars keep the legacy desktop-only output.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_promoted_selectors( array &$buckets, array $attr ): void {
		foreach ( array( 'tablet', 'mobile' ) as $device ) {
			if ( HoverStyles::is_responsive( $attr['contentAlignment'] ?? null ) ) {
				$align = AffiliateX_Helpers::get_responsive_value( $attr['contentAlignment'], $device );

				if ( is_string( $align ) && '' !== $align ) {
					HoverStyles::merge_selector( $buckets[ $device ], '.wp-block-affiliatex-cta .affliatex-cta-title', array( 'text-align' => $align ) );
					HoverStyles::merge_selector( $buckets[ $device ], '.wp-block-affiliatex-cta .affliatex-cta-content', array( 'text-align' => $align ) );
				}
			}

			if ( HoverStyles::is_responsive( $attr['overlayOpacity'] ?? null ) ) {
				$overlay = AffiliateX_Helpers::get_responsive_value( $attr['overlayOpacity'], $device );

				if ( is_numeric( $overlay ) || ( is_string( $overlay ) && '' !== $overlay ) ) {
					HoverStyles::merge_selector( $buckets[ $device ], ' .img-opacity::before', array( 'opacity' => $overlay ) );
				}
			}

			if ( HoverStyles::is_responsive( $attr['ctaButtonAlignment'] ?? null ) ) {
				$button_align = AffiliateX_Helpers::get_responsive_value( $attr['ctaButtonAlignment'], $device );

				if ( is_string( $button_align ) && '' !== $button_align ) {
					HoverStyles::merge_selector( $buckets[ $device ], ' .button-wrapper', array( 'justify-content' => $button_align ) );
				}
			}
		}
	}

	/**
	 * Hover rules for the wave-3 hover attributes, mirrors cta/styling.js.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_hover_selectors( array &$buckets, array $attr ): void {
		$typos  = array( $attr['ctaTitleHoverTypography'] ?? null, $attr['ctaContentHoverTypography'] ?? null );
		$extras = array();

		if ( HoverStyles::has_typography_value( $typos, 'size' ) ) {
			$extras[] = 'font-size';
		}

		if ( HoverStyles::has_typography_value( $typos, 'letter-spacing' ) ) {
			$extras[] = 'letter-spacing';
		}

		if ( HoverStyles::has_spacing_value( $attr['ctaHoverMargin'] ?? null ) ) {
			$extras[] = 'margin';
		}

		if ( HoverStyles::has_spacing_value( $attr['ctaHoverPadding'] ?? null ) ) {
			$extras[] = 'padding';
		}

		$transition = HoverStyles::get_transition( $extras );

		if ( ! empty( $attr['ctaTitleHoverColor'] ) && is_string( $attr['ctaTitleHoverColor'] ) ) {
			self::set_hover(
				$buckets,
				$transition,
				'.wp-block-affiliatex-cta .affliatex-cta-title:hover',
				array( 'color' => $attr['ctaTitleHoverColor'] ),
				array( '.wp-block-affiliatex-cta .affliatex-cta-title' )
			);
		}

		if ( ! empty( $attr['ctaTextHoverColor'] ) && is_string( $attr['ctaTextHoverColor'] ) ) {
			self::set_hover(
				$buckets,
				$transition,
				'.wp-block-affiliatex-cta .affliatex-cta-content:hover',
				array( 'color' => $attr['ctaTextHoverColor'] ),
				array( '.wp-block-affiliatex-cta .affliatex-cta-content' )
			);
		}

		$bg_hover = HoverStyles::get_background_styles(
			$attr['ctaBgHoverType'] ?? '',
			$attr['ctaBgColorType'] ?? '',
			$attr['ctaBGHoverColor'] ?? '',
			$attr['ctaBgHoverGradient'] ?? ''
		);

		if ( ! empty( $bg_hover ) ) {
			self::set_hover( $buckets, $transition, ' .bg-color:hover', $bg_hover, array( ' .bg-color' ) );
			self::set_hover( $buckets, $transition, '.wp-block-affiliatex-cta .layout-type-2:hover .content-wrapper', $bg_hover, array( '.wp-block-affiliatex-cta .layout-type-2 .content-wrapper' ) );
		}

		$container_hover = HoverStyles::get_border_styles( $attr['ctaHoverBorder'] ?? null );
		$container_hover = array_merge( $container_hover, HoverStyles::get_shadow_styles( $attr['ctaHoverShadow'] ?? null ) );

		$desktop_radius = HoverStyles::get_radius_value( $attr['ctaHoverBorderRadius'] ?? null, 'desktop' );

		if ( '' !== $desktop_radius ) {
			$container_hover['border-radius'] = $desktop_radius;
		}

		if ( ! empty( $container_hover ) ) {
			self::set_hover( $buckets, $transition, '.wp-block-affiliatex-cta > div:hover', $container_hover, array( '.wp-block-affiliatex-cta > div' ) );
		}

		foreach ( array( 'tablet', 'mobile' ) as $device ) {
			$radius = HoverStyles::get_radius_value( $attr['ctaHoverBorderRadius'] ?? null, $device );

			if ( '' !== $radius ) {
				HoverStyles::merge_selector( $buckets[ $device ], '.wp-block-affiliatex-cta > div:hover', array( 'border-radius' => $radius ) );
			}
		}

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			HoverStyles::merge_selector(
				$buckets[ $device ],
				'.wp-block-affiliatex-cta > div:hover',
				HoverStyles::get_spacing_styles( $attr['ctaHoverMargin'] ?? null, $device, 'margin' )
			);

			$padding_hover = HoverStyles::get_spacing_styles( $attr['ctaHoverPadding'] ?? null, $device, 'padding' );

			HoverStyles::merge_selector( $buckets[ $device ], '.wp-block-affiliatex-cta > div.layout-type-1:hover', $padding_hover );
			HoverStyles::merge_selector( $buckets[ $device ], '.wp-block-affiliatex-cta .layout-type-2:hover .content-wrapper', $padding_hover );
		}

		if ( HoverStyles::has_spacing_value( $attr['ctaHoverMargin'] ?? null ) ) {
			HoverStyles::merge_selector( $buckets['desktop'], '.wp-block-affiliatex-cta > div', array( 'transition' => $transition ) );
		}

		if ( HoverStyles::has_spacing_value( $attr['ctaHoverPadding'] ?? null ) ) {
			HoverStyles::merge_selector( $buckets['desktop'], '.wp-block-affiliatex-cta > div', array( 'transition' => $transition ) );
			HoverStyles::merge_selector( $buckets['desktop'], '.wp-block-affiliatex-cta .layout-type-2 .content-wrapper', array( 'transition' => $transition ) );
		}

		$typography_rules = array(
			array(
				'typography' => $attr['ctaTitleHoverTypography'] ?? null,
				'base'       => '.wp-block-affiliatex-cta .affliatex-cta-title',
				'hover'      => '.wp-block-affiliatex-cta .affliatex-cta-title:hover',
			),
			array(
				'typography' => $attr['ctaContentHoverTypography'] ?? null,
				'base'       => '.wp-block-affiliatex-cta .affliatex-cta-content',
				'hover'      => '.wp-block-affiliatex-cta .affliatex-cta-content:hover',
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

		$content_align_desktop = isset( $attr['contentAlignment'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['contentAlignment'] ) : null;
		$button_align_desktop  = isset( $attr['ctaButtonAlignment'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['ctaButtonAlignment'] ) : null;
		$overlay_desktop       = isset( $attr['overlayOpacity'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['overlayOpacity'] ) : null;

		$customization_data = affx_get_customization_settings();
		$global_font_family = isset( $customization_data['typography']['family'] ) ? $customization_data['typography']['family'] : 'Default';
		$global_font_color  = isset( $customization_data['fontColor'] ) ? $customization_data['fontColor'] : '#292929';
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
		$ctaBgGradient      = isset( $attr['ctaBgGradient']['gradient'] ) ? $attr['ctaBgGradient']['gradient'] : '';
		$ctaBGColor         = isset( $attr['ctaBGColor'] ) ? $attr['ctaBGColor'] : '#fff';
		$buttonBGColor      = isset( $attr['buttonBGColor'] ) ? $attr['buttonBGColor'] : '#00B0B0';
		$variation          = isset( $attr['ctaTitleTypography']['variation'] ) ? $attr['ctaTitleTypography']['variation'] : 'n5';
		$content_variation  = isset( $attr['ctaContentTypography']['variation'] ) ? $attr['ctaContentTypography']['variation'] : 'n5';
		$position           = 'center';
		if ( isset( $attr['imagePosition'] ) ) {
			if ( $attr['imagePosition'] === 'center' ) {
				$position = 'center center';
			} elseif ( $attr['imagePosition'] === 'centerLeft' ) {
				$position = 'center left';
			} elseif ( $attr['imagePosition'] === 'centerRight' ) {
				$position = 'center right';
			} elseif ( $attr['imagePosition'] === 'topCenter' ) {
				$position = 'top center';
			} elseif ( $attr['imagePosition'] === 'topLeft' ) {
				$position = 'top left';
			} elseif ( $attr['imagePosition'] === 'topRight' ) {
				$position = 'top right';
			} elseif ( $attr['imagePosition'] === 'bottomCenter' ) {
				$position = 'bottom center';
			} elseif ( $attr['imagePosition'] === 'bottomLeft' ) {
				$position = 'bottom left';
			} elseif ( $attr['imagePosition'] === 'bottomRight' ) {
				$position = 'bottom right';
			}
		}
		if ( isset( $attr['imgURL'] ) && ! empty( $attr['imgURL'] ) ) {
			$attr['imgURL'] = do_shortcode( $attr['imgURL'] );
		}
		$selectors = array(
			' .layout-type-1'                         => array(
				'background-image' => isset( $attr['imgURL'] ) ? 'url(' . $attr['imgURL'] . ')' : 'url(' . plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'src/images/fallback.jpg)',
			),
			' .layout-type-3'                         => array(
				'background-image' => isset( $attr['imgURL'] ) ? 'url(' . $attr['imgURL'] . ')' : 'url(' . plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'src/images/fallback.jpg)',
			),
			' .image-wrapper'                         => array(
				'background-image'    => isset( $attr['imgURL'] ) ? 'url(' . $attr['imgURL'] . ')' : 'url(' . plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'src/images/fallback.jpg)',
				'background-position' => $position,
			),
			' .bg-color'                              => array(
				'background' => ( ! isset( $attr['ctaBGType'] ) || $attr['ctaBGType'] !== 'image' ) ? ( isset( $attr['ctaBgColorType'] ) && $attr['ctaBgColorType'] === 'gradient' ? $ctaBgGradient : $ctaBGColor ) : 'undefined',
			),

			'.wp-block-affiliatex-cta > div'          => array(
				'background-size'     => 'cover',
				'background-repeat'   => 'no-repeat',
				'background-position' => $position,
				'border-style'        => isset( $attr['ctaBorder']['style'] ) ? $attr['ctaBorder']['style'] : 'solid',
				'border-width'        => isset( $attr['ctaBorderWidth']['top'] ) && isset( $attr['ctaBorderWidth']['right'] ) && isset( $attr['ctaBorderWidth']['bottom'] ) && isset( $attr['ctaBorderWidth']['left'] ) ? $attr['ctaBorderWidth']['top'] . ' ' . $attr['ctaBorderWidth']['right'] . ' ' . $attr['ctaBorderWidth']['bottom'] . ' ' . $attr['ctaBorderWidth']['left'] . ' ' : '1px 1px 1px 1px',
				'border-radius'       => isset( $attr['ctaBorderRadius']['desktop']['top'] ) && isset( $attr['ctaBorderRadius']['desktop']['right'] ) && isset( $attr['ctaBorderRadius']['desktop']['bottom'] ) && isset( $attr['ctaBorderRadius']['desktop']['left'] ) ? $attr['ctaBorderRadius']['desktop']['top'] . ' ' . $attr['ctaBorderRadius']['desktop']['right'] . ' ' . $attr['ctaBorderRadius']['desktop']['bottom'] . ' ' . $attr['ctaBorderRadius']['desktop']['left'] . ' ' : '8px 8px 8px 8px',
				'border-color'        => isset( $attr['ctaBorder']['color']['color'] ) ? $attr['ctaBorder']['color']['color'] : '#E6ECF7',
				'margin-top'          => isset( $attr['ctaMargin']['desktop']['top'] ) ? $attr['ctaMargin']['desktop']['top'] : '0px',
				'margin-left'         => isset( $attr['ctaMargin']['desktop']['left'] ) ? $attr['ctaMargin']['desktop']['left'] : '0px',
				'margin-right'        => isset( $attr['ctaMargin']['desktop']['right'] ) ? $attr['ctaMargin']['desktop']['right'] : '0px',
				'margin-bottom'       => isset( $attr['ctaMargin']['desktop']['bottom'] ) ? $attr['ctaMargin']['desktop']['bottom'] : '30px',
				'padding-top'         => isset( $attr['ctaBoxPadding']['desktop']['top'] ) ? $attr['ctaBoxPadding']['desktop']['top'] : '60px',
				'padding-left'        => isset( $attr['ctaBoxPadding']['desktop']['left'] ) ? $attr['ctaBoxPadding']['desktop']['left'] : '30px',
				'padding-right'       => isset( $attr['ctaBoxPadding']['desktop']['right'] ) ? $attr['ctaBoxPadding']['desktop']['right'] : '30px',
				'padding-bottom'      => isset( $attr['ctaBoxPadding']['desktop']['bottom'] ) ? $attr['ctaBoxPadding']['desktop']['bottom'] : '60px',
				'box-shadow'          => isset( $attr['ctaBoxShadow'] ) && $attr['ctaBoxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['ctaBoxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),

			),

			'.wp-block-affiliatex-cta .affliatex-cta-title' => array(
				'color'           => isset( $attr['ctaTitleColor'] ) ? $attr['ctaTitleColor'] : '#262B33',
				'font-family'     => isset( $attr['ctaTitleTypography']['family'] ) ? $attr['ctaTitleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['ctaTitleTypography']['size']['desktop'] ) ? $attr['ctaTitleTypography']['size']['desktop'] : '40px',
				'line-height'     => isset( $attr['ctaTitleTypography']['line-height']['desktop'] ) ? $attr['ctaTitleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ctaTitleTypography']['text-transform'] ) ? $attr['ctaTitleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ctaTitleTypography']['text-decoration'] ) ? $attr['ctaTitleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ctaTitleTypography']['letter-spacing']['desktop'] ) ? $attr['ctaTitleTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => null !== $content_align_desktop ? $content_align_desktop : 'center',

			),

			'.wp-block-affiliatex-cta .affliatex-cta-content' => array(
				'color'           => isset( $attr['ctaTextColor'] ) ? $attr['ctaTextColor'] : $global_font_color,
				'font-family'     => isset( $attr['ctaContentTypography']['family'] ) ? $attr['ctaContentTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $content_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $content_variation ),
				'font-size'       => isset( $attr['ctaContentTypography']['size']['desktop'] ) ? $attr['ctaContentTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['ctaContentTypography']['line-height']['desktop'] ) ? $attr['ctaContentTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['ctaContentTypography']['text-transform'] ) ? $attr['ctaContentTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['ctaContentTypography']['text-decoration'] ) ? $attr['ctaContentTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['ctaContentTypography']['letter-spacing']['desktop'] ) ? $attr['ctaContentTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => null !== $content_align_desktop ? $content_align_desktop : 'center',
			),

			' .img-opacity::before'                   => array(
				'opacity' => null !== $overlay_desktop ? $overlay_desktop : 0.1,
			),

			'.wp-block-affiliatex-cta .layout-type-2' => array(
				'padding-top'    => '0px',
				'padding-left'   => '0px',
				'padding-right'  => '0px',
				'padding-bottom' => '0px',
			),

			'.wp-block-affiliatex-cta .layout-type-2 .content-wrapper' => array(
				'background'     => isset( $attr['ctaBgColorType'] ) && $attr['ctaBgColorType'] === 'gradient' ? $ctaBgGradient : $ctaBGColor,
				'padding-top'    => isset( $attr['ctaBoxPadding']['desktop']['top'] ) ? $attr['ctaBoxPadding']['desktop']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['desktop']['left'] ) ? $attr['ctaBoxPadding']['desktop']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['desktop']['right'] ) ? $attr['ctaBoxPadding']['desktop']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['desktop']['bottom'] ) ? $attr['ctaBoxPadding']['desktop']['bottom'] : '60px',
			),
			' .button-wrapper'                        => array(
				'justify-content' => null !== $button_align_desktop ? $button_align_desktop : 'center',
			),
		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			'.wp-block-affiliatex-cta > div' => array(
				'letter-spacing' => isset( $attr['ctaTitleTypography']['letter-spacing']['mobile'] ) ? $attr['ctaTitleTypography']['letter-spacing']['mobile'] : '0em',
				'border-radius'  => isset( $attr['ctaBorderRadius']['mobile']['top'] ) && isset( $attr['ctaBorderRadius']['mobile']['right'] ) && isset( $attr['ctaBorderRadius']['mobile']['bottom'] ) && isset( $attr['ctaBorderRadius']['mobile']['left'] ) ? $attr['ctaBorderRadius']['mobile']['top'] . ' ' . $attr['ctaBorderRadius']['mobile']['right'] . ' ' . $attr['ctaBorderRadius']['mobile']['bottom'] . ' ' . $attr['ctaBorderRadius']['mobile']['left'] . ' ' : '8px 8px 8px 8px',
				'font-size'      => isset( $attr['ctaTitleTypography']['size']['mobile'] ) ? $attr['ctaTitleTypography']['size']['mobile'] : '40px',
				'line-height'    => isset( $attr['ctaTitleTypography']['line-height']['mobile'] ) ? $attr['ctaTitleTypography']['line-height']['mobile'] : '1.5',
				'margin-top'     => isset( $attr['ctaMargin']['mobile']['top'] ) ? $attr['ctaMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['ctaMargin']['mobile']['left'] ) ? $attr['ctaMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['ctaMargin']['mobile']['right'] ) ? $attr['ctaMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['ctaMargin']['mobile']['bottom'] ) ? $attr['ctaMargin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['ctaBoxPadding']['mobile']['top'] ) ? $attr['ctaBoxPadding']['mobile']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['mobile']['left'] ) ? $attr['ctaBoxPadding']['mobile']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['mobile']['right'] ) ? $attr['ctaBoxPadding']['mobile']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['mobile']['bottom'] ) ? $attr['ctaBoxPadding']['mobile']['bottom'] : '60px',
			),
			'.wp-block-affiliatex-cta .affliatex-cta-content' => array(
				'letter-spacing' => isset( $attr['ctaContentTypography']['letter-spacing']['mobile'] ) ? $attr['ctaContentTypography']['letter-spacing']['mobile'] : '0em',
				'font-size'      => isset( $attr['ctaContentTypography']['size']['mobile'] ) ? $attr['ctaContentTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['ctaContentTypography']['line-height']['mobile'] ) ? $attr['ctaContentTypography']['line-height']['mobile'] : '1.5',
			),
			'.wp-block-affiliatex-cta h2'    => array(
				'letter-spacing' => isset( $attr['ctaTitleTypography']['letter-spacing']['mobile'] ) ? $attr['ctaTitleTypography']['letter-spacing']['mobile'] : '0em',
				'font-size'      => isset( $attr['ctaTitleTypography']['size']['mobile'] ) ? $attr['ctaTitleTypography']['size']['mobile'] : '40px',
				'line-height'    => isset( $attr['ctaTitleTypography']['line-height']['mobile'] ) ? $attr['ctaTitleTypography']['line-height']['mobile'] : '1.5',
			),

			'.wp-block-affiliatex-cta .layout-type-2 .content-wrapper' => array(
				'padding-top'    => isset( $attr['ctaBoxPadding']['mobile']['top'] ) ? $attr['ctaBoxPadding']['mobile']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['mobile']['left'] ) ? $attr['ctaBoxPadding']['mobile']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['mobile']['right'] ) ? $attr['ctaBoxPadding']['mobile']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['mobile']['bottom'] ) ? $attr['ctaBoxPadding']['mobile']['bottom'] : '60px',
			),
		);
		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			'.wp-block-affiliatex-cta > div' => array(
				'letter-spacing' => isset( $attr['ctaTitleTypography']['letter-spacing']['tablet'] ) ? $attr['ctaTitleTypography']['letter-spacing']['tablet'] : '0em',
				'border-radius'  => isset( $attr['ctaBorderRadius']['tablet']['top'] ) && isset( $attr['ctaBorderRadius']['tablet']['right'] ) && isset( $attr['ctaBorderRadius']['tablet']['bottom'] ) && isset( $attr['ctaBorderRadius']['tablet']['left'] ) ? $attr['ctaBorderRadius']['tablet']['top'] . ' ' . $attr['ctaBorderRadius']['tablet']['right'] . ' ' . $attr['ctaBorderRadius']['tablet']['bottom'] . ' ' . $attr['ctaBorderRadius']['tablet']['left'] . ' ' : '8px 8px 8px 8px',
				'font-size'      => isset( $attr['ctaTitleTypography']['size']['tablet'] ) ? $attr['ctaTitleTypography']['size']['tablet'] : '40px',
				'line-height'    => isset( $attr['ctaTitleTypography']['line-height']['tablet'] ) ? $attr['ctaTitleTypography']['line-height']['tablet'] : '1.5',
				'margin-top'     => isset( $attr['ctaMargin']['tablet']['top'] ) ? $attr['ctaMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['ctaMargin']['tablet']['left'] ) ? $attr['ctaMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['ctaMargin']['tablet']['right'] ) ? $attr['ctaMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['ctaMargin']['tablet']['bottom'] ) ? $attr['ctaMargin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['ctaBoxPadding']['tablet']['top'] ) ? $attr['ctaBoxPadding']['tablet']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['tablet']['left'] ) ? $attr['ctaBoxPadding']['tablet']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['tablet']['right'] ) ? $attr['ctaBoxPadding']['tablet']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['tablet']['bottom'] ) ? $attr['ctaBoxPadding']['tablet']['bottom'] : '60px',
			),
			'.wp-block-affiliatex-cta .affliatex-cta-content' => array(
				'letter-spacing' => isset( $attr['ctaContentTypography']['letter-spacing']['tablet'] ) ? $attr['ctaContentTypography']['letter-spacing']['tablet'] : '0em',
				'font-size'      => isset( $attr['ctaContentTypography']['size']['tablet'] ) ? $attr['ctaContentTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['ctaContentTypography']['line-height']['tablet'] ) ? $attr['ctaContentTypography']['line-height']['tablet'] : '1.5',
			),
			'.wp-block-affiliatex-cta h2'    => array(
				'letter-spacing' => isset( $attr['ctaTitleTypography']['letter-spacing']['tablet'] ) ? $attr['ctaTitleTypography']['letter-spacing']['tablet'] : '0em',
				'font-size'      => isset( $attr['ctaTitleTypography']['size']['tablet'] ) ? $attr['ctaTitleTypography']['size']['tablet'] : '40px',
				'line-height'    => isset( $attr['ctaTitleTypography']['line-height']['tablet'] ) ? $attr['ctaTitleTypography']['line-height']['tablet'] : '1.5',
			),

			'.wp-block-affiliatex-cta .layout-type-2 .content-wrapper' => array(
				'padding-top'    => isset( $attr['ctaBoxPadding']['tablet']['top'] ) ? $attr['ctaBoxPadding']['tablet']['top'] : '60px',
				'padding-left'   => isset( $attr['ctaBoxPadding']['tablet']['left'] ) ? $attr['ctaBoxPadding']['tablet']['left'] : '30px',
				'padding-right'  => isset( $attr['ctaBoxPadding']['tablet']['right'] ) ? $attr['ctaBoxPadding']['tablet']['right'] : '30px',
				'padding-bottom' => isset( $attr['ctaBoxPadding']['tablet']['bottom'] ) ? $attr['ctaBoxPadding']['tablet']['bottom'] : '60px',
			),
		);
		return $tablet_selectors;
	}
}
