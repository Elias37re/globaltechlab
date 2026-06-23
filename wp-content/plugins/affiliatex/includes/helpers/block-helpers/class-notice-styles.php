<?php

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\HoverStyles;

require_once __DIR__ . '/class-affiliatex-block-styles-base.php';

/**
 * Notice Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Notice_Styles extends AffiliateX_Block_Styles_Base {

	protected static function css_id_prefix(): string {
		return '#affiliatex-notice-style-';
	}

	public static function block_fonts( $attr ) {
		return array(
			'titleTypography' => isset( $attr['titleTypography'] ) ? $attr['titleTypography'] : array(),
			'listTypography'  => isset( $attr['listTypography'] ) ? $attr['listTypography'] : array(),
		);
	}

	/**
	 * Icon size for a device, normalized like pxValue.js, null when the attribute is missing.
	 *
	 * @param array  $attr Block attributes.
	 * @param string $key Attribute key.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @return string|null
	 */
	private static function get_icon_size( $attr, $key, $device = 'desktop' ) {
		if ( ! isset( $attr[ $key ] ) ) {
			return null;
		}

		$value = AffiliateX_Helpers::get_responsive_value( HoverStyles::to_px_value( $attr[ $key ] ), $device );

		return is_string( $value ) && '' !== $value ? $value : null;
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
			if ( HoverStyles::is_responsive( $attr['titleAlignment'] ?? null ) ) {
				$title_align = AffiliateX_Helpers::get_responsive_value( $attr['titleAlignment'], $device );

				if ( is_string( $title_align ) && '' !== $title_align ) {
					HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-notice-title', array( 'text-align' => $title_align ) );
				}
			}

			if ( HoverStyles::is_responsive( $attr['alignment'] ?? null ) ) {
				$align = AffiliateX_Helpers::get_responsive_value( $attr['alignment'], $device );

				if ( is_string( $align ) && '' !== $align ) {
					HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-notice-content p', array( 'text-align' => $align ) );
					HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-notice-content li', array( 'justify-content' => $align ) );
					HoverStyles::merge_selector( $buckets[ $device ], ' .affx-notice-inner-wrapper', array( 'text-align' => $align ) );
				}
			}

			if ( HoverStyles::is_responsive( $attr['noticeIconSize'] ?? null ) ) {
				$icon_size = self::get_icon_size( $attr, 'noticeIconSize', $device );

				if ( null !== $icon_size ) {
					$icon_styles = array( 'font-size' => $icon_size );
					HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-notice-title i', $icon_styles );
					HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-notice-icon', $icon_styles );
					HoverStyles::merge_selector( $buckets[ $device ], ' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title:before', $icon_styles );
					HoverStyles::merge_selector( $buckets[ $device ], ' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title:before', $icon_styles );
				}
			}

			if ( HoverStyles::is_responsive( $attr['noticeListIconSize'] ?? null ) ) {
				$list_icon_size = self::get_icon_size( $attr, 'noticeListIconSize', $device );

				if ( null !== $list_icon_size ) {
					$list_icon_styles = array( 'font-size' => $list_icon_size );
					HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-notice-content ul.affiliatex-list li:before', $list_icon_styles );
					HoverStyles::merge_selector( $buckets[ $device ], ' .affiliatex-notice-content .affiliatex-list li i', $list_icon_styles );
				}
			}
		}
	}

	/**
	 * Hover rules for the wave-2 hover attributes, mirrors notice/styling.js.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_hover_selectors( array &$buckets, array $attr ): void {
		$typos  = array( $attr['titleHoverTypography'] ?? null, $attr['listHoverTypography'] ?? null );
		$extras = array();

		if ( HoverStyles::has_typography_value( $typos, 'size' ) ) {
			$extras[] = 'font-size';
		}

		if ( HoverStyles::has_typography_value( $typos, 'letter-spacing' ) ) {
			$extras[] = 'letter-spacing';
		}

		if ( HoverStyles::has_spacing_value( $attr['noticeHoverMargin'] ?? null ) ) {
			$extras[] = 'margin';
		}

		if ( HoverStyles::has_spacing_value( $attr['titleHoverPadding'] ?? null )
			|| HoverStyles::has_spacing_value( $attr['contentHoverPadding'] ?? null )
			|| HoverStyles::has_spacing_value( $attr['noticeHoverPadding'] ?? null ) ) {
			$extras[] = 'padding';
		}

		$transition = HoverStyles::get_transition( $extras );

		if ( ! empty( $attr['noticeTextHoverColor'] ) && is_string( $attr['noticeTextHoverColor'] ) ) {
			self::set_hover(
				$buckets,
				$transition,
				' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title:hover',
				array( 'color' => $attr['noticeTextHoverColor'] ),
				array( ' .affiliatex-notice-title' )
			);
		}

		if ( ! empty( $attr['noticeTextTwoHoverColor'] ) && is_string( $attr['noticeTextTwoHoverColor'] ) ) {
			self::set_hover(
				$buckets,
				$transition,
				' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title:hover',
				array( 'color' => $attr['noticeTextTwoHoverColor'] ),
				array( ' .affiliatex-notice-title' )
			);
		}

		if ( ! empty( $attr['noticeIconTwoHoverColor'] ) && is_string( $attr['noticeIconTwoHoverColor'] ) ) {
			self::set_hover(
				$buckets,
				$transition,
				' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title:hover i',
				array( 'color' => $attr['noticeIconTwoHoverColor'] ),
				array( ' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title i' )
			);
			self::set_hover( $buckets, $transition, ' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title:hover:before', array( 'color' => $attr['noticeIconTwoHoverColor'] ), array() );
		}

		if ( ! empty( $attr['noticeListHoverColor'] ) && is_string( $attr['noticeListHoverColor'] ) ) {
			self::set_hover( $buckets, $transition, ' .affiliatex-notice-content:hover p', array( 'color' => $attr['noticeListHoverColor'] ), array( ' .affiliatex-notice-content p' ) );
			self::set_hover( $buckets, $transition, ' .affiliatex-notice-content:hover li', array( 'color' => $attr['noticeListHoverColor'] ), array( ' .affiliatex-notice-content li' ) );
		}

		if ( ! empty( $attr['noticeIconHoverColor'] ) && is_string( $attr['noticeIconHoverColor'] ) ) {
			self::set_hover(
				$buckets,
				$transition,
				' .affiliatex-notice-content .affiliatex-list li:hover i',
				array( 'color' => $attr['noticeIconHoverColor'] ),
				array( ' .affiliatex-notice-content .affiliatex-list li i' )
			);
			self::set_hover( $buckets, $transition, ' .affiliatex-notice-content .affiliatex-list li:hover:before', array( 'color' => $attr['noticeIconHoverColor'] ), array() );
			self::set_hover( $buckets, $transition, ' .affiliatex-notice-content .affiliatex-list li:hover::marker', array( 'color' => $attr['noticeIconHoverColor'] ), array() );
		}

		$title_bg_hover = HoverStyles::get_background_styles(
			$attr['noticeBgHoverType'] ?? '',
			$attr['noticeBgType'] ?? 'solid',
			$attr['noticeBgHoverColor'] ?? '',
			$attr['noticeBgHoverGradient'] ?? ''
		);

		if ( ! empty( $title_bg_hover ) ) {
			self::set_hover( $buckets, $transition, ' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title:hover', $title_bg_hover, array( ' .affiliatex-notice-title' ) );
		}

		$content_bg_hover = HoverStyles::get_background_styles(
			$attr['listBgHoverType'] ?? '',
			$attr['listBgType'] ?? 'solid',
			$attr['listBgHoverColor'] ?? '',
			$attr['listBgHoverGradient'] ?? ''
		);

		if ( ! empty( $content_bg_hover ) ) {
			self::set_hover( $buckets, $transition, ' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-content:hover', $content_bg_hover, array( ' .affiliatex-notice-content' ) );
		}

		$wrapper_hover = HoverStyles::get_background_styles(
			$attr['noticeBgTwoHoverType'] ?? '',
			$attr['noticeBgTwoType'] ?? 'solid',
			$attr['noticeBgTwoHoverColor'] ?? '',
			$attr['noticeBgTwoHoverGradient'] ?? ''
		);

		$wrapper_hover = array_merge( $wrapper_hover, HoverStyles::get_border_styles( $attr['noticeHoverBorder'] ?? null ) );
		$wrapper_hover = array_merge( $wrapper_hover, HoverStyles::get_shadow_styles( $attr['noticeHoverShadow'] ?? null ) );

		$desktop_radius = HoverStyles::get_radius_value( $attr['noticeHoverBorderRadius'] ?? null, 'desktop' );

		if ( '' !== $desktop_radius ) {
			$wrapper_hover['border-radius'] = $desktop_radius;
		}

		if ( ! empty( $wrapper_hover ) ) {
			self::set_hover( $buckets, $transition, ' .affx-notice-inner-wrapper:hover', $wrapper_hover, array( ' .affx-notice-inner-wrapper' ) );
		}

		foreach ( array( 'tablet', 'mobile' ) as $device ) {
			$radius = HoverStyles::get_radius_value( $attr['noticeHoverBorderRadius'] ?? null, $device );

			if ( '' !== $radius ) {
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-notice-inner-wrapper:hover', array( 'border-radius' => $radius ) );
			}
		}

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			HoverStyles::merge_selector(
				$buckets[ $device ],
				' .affx-notice-inner-wrapper:hover',
				HoverStyles::get_spacing_styles( $attr['noticeHoverMargin'] ?? null, $device, 'margin' )
			);
			HoverStyles::merge_selector(
				$buckets[ $device ],
				' .affx-notice-inner-wrapper.layout-type-2:hover',
				HoverStyles::get_spacing_styles( $attr['noticeHoverPadding'] ?? null, $device, 'padding' )
			);
			HoverStyles::merge_selector(
				$buckets[ $device ],
				' .affx-notice-inner-wrapper.layout-type-1:hover .affiliatex-notice-title',
				HoverStyles::get_spacing_styles( $attr['titleHoverPadding'] ?? null, $device, 'padding' )
			);
			HoverStyles::merge_selector(
				$buckets[ $device ],
				' .affx-notice-inner-wrapper.layout-type-1:hover .affiliatex-notice-content',
				HoverStyles::get_spacing_styles( $attr['contentHoverPadding'] ?? null, $device, 'padding' )
			);
		}

		if ( HoverStyles::has_spacing_value( $attr['noticeHoverMargin'] ?? null ) || HoverStyles::has_spacing_value( $attr['noticeHoverPadding'] ?? null ) ) {
			HoverStyles::merge_selector( $buckets['desktop'], ' .affx-notice-inner-wrapper', array( 'transition' => $transition ) );
		}

		if ( HoverStyles::has_spacing_value( $attr['titleHoverPadding'] ?? null ) ) {
			HoverStyles::merge_selector( $buckets['desktop'], ' .affiliatex-notice-title', array( 'transition' => $transition ) );
		}

		if ( HoverStyles::has_spacing_value( $attr['contentHoverPadding'] ?? null ) ) {
			HoverStyles::merge_selector( $buckets['desktop'], ' .affiliatex-notice-content', array( 'transition' => $transition ) );
		}

		$typography_rules = array(
			array(
				'typography'  => $attr['titleHoverTypography'] ?? null,
				'transitions' => array( ' .affiliatex-notice-title' ),
				'hovers'      => array( ' .affiliatex-notice-title:hover' ),
			),
			array(
				'typography'  => $attr['listHoverTypography'] ?? null,
				'transitions' => array( ' .affiliatex-notice-content p', ' .affiliatex-notice-content li' ),
				'hovers'      => array( ' .affiliatex-notice-content:hover p', ' .affiliatex-notice-content:hover li' ),
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

				foreach ( $rule['hovers'] as $selector ) {
					HoverStyles::merge_selector( $buckets[ $device ], $selector, $styles );
				}
			}

			if ( $has_styles ) {
				foreach ( $rule['transitions'] as $selector ) {
					HoverStyles::merge_selector( $buckets['desktop'], $selector, array( 'transition' => $transition ) );
				}
			}
		}
	}

	public static function get_selectors( $attr ) {

		$icon_size_desktop      = self::get_icon_size( $attr, 'noticeIconSize' );
		$list_icon_size_desktop = self::get_icon_size( $attr, 'noticeListIconSize' );
		$alignment_desktop      = isset( $attr['alignment'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['alignment'] ) : null;
		$title_align_desktop    = isset( $attr['titleAlignment'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['titleAlignment'] ) : null;

		$customization_data  = affx_get_customization_settings();
		$global_font_family  = isset( $customization_data['typography']['family'] ) ? $customization_data['typography']['family'] : 'Default';
		$global_font_color   = isset( $customization_data['fontColor'] ) ? $customization_data['fontColor'] : '#292929';
		$variation           = isset( $attr['titleTypography']['variation'] ) ? $attr['titleTypography']['variation'] : 'n4';
		$list_variation      = isset( $attr['listTypography']['variation'] ) ? $attr['listTypography']['variation'] : 'n4';
		$bgGradient          = isset( $attr['noticeBgGradient']['gradient'] ) ? $attr['noticeBgGradient']['gradient'] : '';
		$bgColor             = isset( $attr['noticeBgColor'] ) ? $attr['noticeBgColor'] : '#24b644';
		$listBgGradient      = isset( $attr['listBgGradient']['gradient'] ) ? $attr['listBgGradient']['gradient'] : '';
		$listBgColor         = isset( $attr['listBgColor'] ) ? $attr['listBgColor'] : '#ffffff';
		$bg2Gradient         = isset( $attr['noticeBgTwoGradient']['gradient'] ) ? $attr['noticeBgTwoGradient']['gradient'] : '';
		$bg2Color            = isset( $attr['noticeBgTwoColor'] ) ? $attr['noticeBgTwoColor'] : '#F6F9FF';
		$noticeContentType   = isset( $attr['noticeContentType'] ) ? $attr['noticeContentType'] : 'list';
		$noticeListType      = isset( $attr['noticeListType'] ) ? $attr['noticeListType'] : 'unordered';
		$noticeunorderedType = isset( $attr['noticeunorderedType'] ) ? $attr['noticeunorderedType'] : 'icon';
		$box_shadow          = array(
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
			' .affx-notice-inner-wrapper'                  => array(
				'border-width'  => isset( $attr['noticeBorderWidth']['desktop']['top'] ) && isset( $attr['noticeBorderWidth']['desktop']['right'] ) && isset( $attr['noticeBorderWidth']['desktop']['bottom'] ) && isset( $attr['noticeBorderWidth']['desktop']['left'] ) ? $attr['noticeBorderWidth']['desktop']['top'] . ' ' . $attr['noticeBorderWidth']['desktop']['right'] . ' ' . $attr['noticeBorderWidth']['desktop']['bottom'] . ' ' . $attr['noticeBorderWidth']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-radius' => isset( $attr['noticeBorderRadius']['desktop']['top'] ) && isset( $attr['noticeBorderRadius']['desktop']['right'] ) && isset( $attr['noticeBorderRadius']['desktop']['bottom'] ) && isset( $attr['noticeBorderRadius']['desktop']['left'] ) ? $attr['noticeBorderRadius']['desktop']['top'] . ' ' . $attr['noticeBorderRadius']['desktop']['right'] . ' ' . $attr['noticeBorderRadius']['desktop']['bottom'] . ' ' . $attr['noticeBorderRadius']['desktop']['left'] . ' ' : '0px 0px 0px 0px',
				'border-style'  => isset( $attr['noticeBorder']['style'] ) ? $attr['noticeBorder']['style'] : 'solid',
				'border-color'  => isset( $attr['noticeBorder']['color']['color'] ) ? $attr['noticeBorder']['color']['color'] : '#E6ECF7',
				'margin-top'    => isset( $attr['noticeMargin']['desktop']['top'] ) ? $attr['noticeMargin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['noticeMargin']['desktop']['left'] ) ? $attr['noticeMargin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['noticeMargin']['desktop']['right'] ) ? $attr['noticeMargin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['noticeMargin']['desktop']['bottom'] ) ? $attr['noticeMargin']['desktop']['bottom'] : '30px',
				'box-shadow'    => isset( $attr['boxShadow'] ) && $attr['boxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['boxShadow'] ) : AffiliateX_Helpers::get_css_boxshadow( $box_shadow ),
				'text-align'    => null !== $alignment_desktop ? $alignment_desktop : 'left',
			),
			' .affiliatex-notice-title'                    => array(
				'font-family'     => isset( $attr['titleTypography']['family'] ) ? $attr['titleTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $variation ),
				'font-size'       => isset( $attr['titleTypography']['size']['desktop'] ) ? $attr['titleTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['titleTypography']['line-height']['desktop'] ) ? $attr['titleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['titleTypography']['text-transform'] ) ? $attr['titleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['titleTypography']['text-decoration'] ) ? $attr['titleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['titleTypography']['letter-spacing']['desktop'] ) ? $attr['titleTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => null !== $title_align_desktop ? $title_align_desktop : 'left',
				'color'           => isset( $attr['noticeTextColor'] ) ? $attr['noticeTextColor'] : '#ffffff',
				'padding-top'     => isset( $attr['titlePadding']['desktop']['top'] ) ? $attr['titlePadding']['desktop']['top'] : '10px',
				'padding-left'    => isset( $attr['titlePadding']['desktop']['left'] ) ? $attr['titlePadding']['desktop']['left'] : '15px',
				'padding-right'   => isset( $attr['titlePadding']['desktop']['right'] ) ? $attr['titlePadding']['desktop']['right'] : '15px',
				'padding-bottom'  => isset( $attr['titlePadding']['desktop']['bottom'] ) ? $attr['titlePadding']['desktop']['bottom'] : '10px',
			),
			' .affiliatex-notice-title i'                  => array(
				'font-size' => null !== $icon_size_desktop ? $icon_size_desktop : '18px',
			),
			' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title i' => array(
				'color' => isset( $attr['noticeIconTwoColor'] ) ? $attr['noticeIconTwoColor'] : '#00454A',
			),
			' .affiliatex-notice-icon'                     => array(
				'color'     => isset( $attr['noticeIconTwoColor'] ) ? $attr['noticeIconTwoColor'] : '#00454A',
				'font-size' => null !== $icon_size_desktop ? $icon_size_desktop : '18px',
			),
			' .affiliatex-notice-content'                  => array(
				'padding-top'    => isset( $attr['contentPadding']['desktop']['top'] ) ? $attr['contentPadding']['desktop']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['desktop']['left'] ) ? $attr['contentPadding']['desktop']['left'] : '15px',
				'padding-right'  => isset( $attr['contentPadding']['desktop']['right'] ) ? $attr['contentPadding']['desktop']['right'] : '15px',
				'padding-bottom' => isset( $attr['contentPadding']['desktop']['bottom'] ) ? $attr['contentPadding']['desktop']['bottom'] : '10px',
			),
			' .affiliatex-notice-content p'                => array(
				'font-family'     => isset( $attr['listTypography']['family'] ) ? $attr['listTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $list_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $list_variation ),
				'font-size'       => isset( $attr['listTypography']['size']['desktop'] ) ? $attr['listTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['listTypography']['line-height']['desktop'] ) ? $attr['listTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['listTypography']['text-transform'] ) ? $attr['listTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['listTypography']['text-decoration'] ) ? $attr['listTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['listTypography']['letter-spacing']['desktop'] ) ? $attr['listTypography']['letter-spacing']['desktop'] : '0em',
				'text-align'      => null !== $alignment_desktop ? $alignment_desktop : 'left',
				'color'           => isset( $attr['noticeListColor'] ) ? $attr['noticeListColor'] : $global_font_color,
			),
			' .affiliatex-notice-content li'               => array(
				'font-family'     => isset( $attr['listTypography']['family'] ) ? $attr['listTypography']['family'] : $global_font_family,
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $list_variation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $list_variation ),
				'font-size'       => isset( $attr['listTypography']['size']['desktop'] ) ? $attr['listTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['listTypography']['line-height']['desktop'] ) ? $attr['listTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['listTypography']['text-transform'] ) ? $attr['listTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['listTypography']['text-decoration'] ) ? $attr['listTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['listTypography']['letter-spacing']['desktop'] ) ? $attr['listTypography']['letter-spacing']['desktop'] : '0em',
				'justify-content' => null !== $alignment_desktop ? $alignment_desktop : 'left',
				'color'           => isset( $attr['noticeListColor'] ) ? $attr['noticeListColor'] : $global_font_color,
			),
			' .affiliatex-notice-content .affiliatex-list li::marker' => array(
				'color' => isset( $attr['noticeIconColor'] ) ? $attr['noticeIconColor'] : '#24b644',
			),
			' .affiliatex-notice-content .affiliatex-list li:before' => array(
				'color' => isset( $attr['noticeIconColor'] ) ? $attr['noticeIconColor'] : '#24b644',
			),
			' .affiliatex-notice-content ul.affiliatex-list li:before' => array(
				'font-size' => null !== $list_icon_size_desktop ? $list_icon_size_desktop : '17px',
			),
			' .affiliatex-notice-content .affiliatex-list li i' => array(
				'color'     => isset( $attr['noticeIconColor'] ) ? $attr['noticeIconColor'] : '#24b644',
				'font-size' => null !== $list_icon_size_desktop ? $list_icon_size_desktop : '17px',
			),
			' .affx-notice-inner-wrapper.layout-type-2'    => array(
				'margin-top'     => isset( $attr['noticeMargin']['desktop']['top'] ) ? $attr['noticeMargin']['desktop']['top'] : '0px',
				'margin-left'    => isset( $attr['noticeMargin']['desktop']['left'] ) ? $attr['noticeMargin']['desktop']['left'] : '0px',
				'margin-right'   => isset( $attr['noticeMargin']['desktop']['right'] ) ? $attr['noticeMargin']['desktop']['right'] : '0px',
				'margin-bottom'  => isset( $attr['noticeMargin']['desktop']['bottom'] ) ? $attr['noticeMargin']['desktop']['bottom'] : '30px',
				'padding-top'    => isset( $attr['noticePadding']['desktop']['top'] ) ? $attr['noticePadding']['desktop']['top'] : '20px',
				'padding-left'   => isset( $attr['noticePadding']['desktop']['left'] ) ? $attr['noticePadding']['desktop']['left'] : '20px',
				'padding-right'  => isset( $attr['noticePadding']['desktop']['right'] ) ? $attr['noticePadding']['desktop']['right'] : '20px',
				'padding-bottom' => isset( $attr['noticePadding']['desktop']['bottom'] ) ? $attr['noticePadding']['desktop']['bottom'] : '20px',
				'background'     => isset( $attr['noticeBgTwoType'] ) && $attr['noticeBgTwoType'] === 'gradient' ? $bg2Gradient : $bg2Color,
			),
			' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title' => array(
				'color'          => isset( $attr['noticeTextTwoColor'] ) ? $attr['noticeTextTwoColor'] : '#00454A',
				'padding-top'    => '0px',
				'padding-left'   => '0px',
				'padding-right'  => '0px',
				'padding-bottom' => '10px',
			),
			' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title:before' => array(
				'font-size' => null !== $icon_size_desktop ? $icon_size_desktop : '17px',
			),
			' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-title:before' => array(
				'color'     => isset( $attr['noticeTextTwoColor'] ) ? $attr['noticeTextTwoColor'] : '#00454A',
				'font-size' => null !== $icon_size_desktop ? $icon_size_desktop : '17px',
			),
			' .affx-notice-inner-wrapper.layout-type-2 .affiliatex-notice-content' => array(
				'padding-top'    => '0px',
				'padding-left'   => '0px',
				'padding-right'  => '0px',
				'padding-bottom' => '0px',
			),
			' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-title' => array(
				'background' => isset( $attr['noticeBgType'] ) && $attr['noticeBgType'] === 'gradient' ? $bgGradient : $bgColor,
			),
			' .affx-notice-inner-wrapper.layout-type-1 .affiliatex-notice-content' => array(
				'background' => isset( $attr['listBgType'] ) && $attr['listBgType'] === 'gradient' ? $listBgGradient : $listBgColor,
			),
			' .affiliatex-notice-content .affiliatex-list' => array(
				'list-style' => $noticeContentType === 'list' &&
				$noticeListType === 'unordered' &&
				$noticeunorderedType === 'icon'
					? 'none'
					: '',
			),
		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affiliatex-notice-title'                 => array(
				'font-size'      => isset( $attr['titleTypography']['size']['mobile'] ) ? $attr['titleTypography']['size']['mobile'] : '24px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['mobile'] ) ? $attr['titleTypography']['line-height']['mobile'] : '1.333',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['mobile'] ) ? $attr['titleTypography']['letter-spacing']['mobile'] : '0em',
				'padding-top'    => isset( $attr['titlePadding']['mobile']['top'] ) ? $attr['titlePadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['mobile']['left'] ) ? $attr['titlePadding']['mobile']['left'] : '15px',
				'padding-right'  => isset( $attr['titlePadding']['mobile']['right'] ) ? $attr['titlePadding']['mobile']['right'] : '15px',
				'padding-bottom' => isset( $attr['titlePadding']['mobile']['bottom'] ) ? $attr['titlePadding']['mobile']['bottom'] : '10px',
			),
			' .affiliatex-notice-content'               => array(
				'padding-top'    => isset( $attr['contentPadding']['mobile']['top'] ) ? $attr['contentPadding']['mobile']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['mobile']['left'] ) ? $attr['contentPadding']['mobile']['left'] : '15px',
				'padding-right'  => isset( $attr['contentPadding']['mobile']['right'] ) ? $attr['contentPadding']['mobile']['right'] : '15px',
				'padding-bottom' => isset( $attr['contentPadding']['mobile']['bottom'] ) ? $attr['contentPadding']['mobile']['bottom'] : '10px',
			),
			' .affiliatex-notice-content p'             => array(
				'font-size'      => isset( $attr['listTypography']['size']['mobile'] ) ? $attr['listTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['mobile'] ) ? $attr['listTypography']['line-height']['mobile'] : '1.333',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['mobile'] ) ? $attr['listTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affiliatex-notice-content li'            => array(
				'font-size'      => isset( $attr['listTypography']['size']['mobile'] ) ? $attr['listTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['mobile'] ) ? $attr['listTypography']['line-height']['mobile'] : '1.333',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['mobile'] ) ? $attr['listTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-notice-inner-wrapper'               => array(
				'border-width'  => isset( $attr['noticeBorderWidth']['mobile']['top'] ) && isset( $attr['noticeBorderWidth']['mobile']['right'] ) && isset( $attr['noticeBorderWidth']['mobile']['bottom'] ) && isset( $attr['noticeBorderWidth']['mobile']['left'] ) ? $attr['noticeBorderWidth']['mobile']['top'] . ' ' . $attr['noticeBorderWidth']['mobile']['right'] . ' ' . $attr['noticeBorderWidth']['mobile']['bottom'] . ' ' . $attr['noticeBorderWidth']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
				'border-radius' => isset( $attr['noticeBorderRadius']['mobile']['top'] ) && isset( $attr['noticeBorderRadius']['mobile']['right'] ) && isset( $attr['noticeBorderRadius']['mobile']['bottom'] ) && isset( $attr['noticeBorderRadius']['mobile']['left'] ) ? $attr['noticeBorderRadius']['mobile']['top'] . ' ' . $attr['noticeBorderRadius']['mobile']['right'] . ' ' . $attr['noticeBorderRadius']['mobile']['bottom'] . ' ' . $attr['noticeBorderRadius']['mobile']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['noticeMargin']['mobile']['top'] ) ? $attr['noticeMargin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['noticeMargin']['mobile']['left'] ) ? $attr['noticeMargin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['noticeMargin']['mobile']['right'] ) ? $attr['noticeMargin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['noticeMargin']['mobile']['bottom'] ) ? $attr['noticeMargin']['mobile']['bottom'] : '30px',
			),
			' .affx-notice-inner-wrapper.layout-type-2' => array(
				'margin-top'     => isset( $attr['noticeMargin']['mobile']['top'] ) ? $attr['noticeMargin']['mobile']['top'] : '0px',
				'margin-left'    => isset( $attr['noticeMargin']['mobile']['left'] ) ? $attr['noticeMargin']['mobile']['left'] : '0px',
				'margin-right'   => isset( $attr['noticeMargin']['mobile']['right'] ) ? $attr['noticeMargin']['mobile']['right'] : '0px',
				'margin-bottom'  => isset( $attr['noticeMargin']['mobile']['bottom'] ) ? $attr['noticeMargin']['mobile']['bottom'] : '30px',
				'padding-top'    => isset( $attr['noticePadding']['mobile']['top'] ) ? $attr['noticePadding']['mobile']['top'] : '20px',
				'padding-left'   => isset( $attr['noticePadding']['mobile']['left'] ) ? $attr['noticePadding']['mobile']['left'] : '20px',
				'padding-right'  => isset( $attr['noticePadding']['mobile']['right'] ) ? $attr['noticePadding']['mobile']['right'] : '20px',
				'padding-bottom' => isset( $attr['noticePadding']['mobile']['bottom'] ) ? $attr['noticePadding']['mobile']['bottom'] : '20px',
			),
		);
		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affiliatex-notice-title'                 => array(
				'font-size'      => isset( $attr['titleTypography']['size']['tablet'] ) ? $attr['titleTypography']['size']['tablet'] : '24px',
				'line-height'    => isset( $attr['titleTypography']['line-height']['tablet'] ) ? $attr['titleTypography']['line-height']['tablet'] : '1.333',
				'letter-spacing' => isset( $attr['titleTypography']['letter-spacing']['tablet'] ) ? $attr['titleTypography']['letter-spacing']['tablet'] : '0em',
				'padding-top'    => isset( $attr['titlePadding']['tablet']['top'] ) ? $attr['titlePadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['titlePadding']['tablet']['left'] ) ? $attr['titlePadding']['tablet']['left'] : '15px',
				'padding-right'  => isset( $attr['titlePadding']['tablet']['right'] ) ? $attr['titlePadding']['tablet']['right'] : '15px',
				'padding-bottom' => isset( $attr['titlePadding']['tablet']['bottom'] ) ? $attr['titlePadding']['tablet']['bottom'] : '10px',
			),
			' .affiliatex-notice-content'               => array(
				'padding-top'    => isset( $attr['contentPadding']['tablet']['top'] ) ? $attr['contentPadding']['tablet']['top'] : '10px',
				'padding-left'   => isset( $attr['contentPadding']['tablet']['left'] ) ? $attr['contentPadding']['tablet']['left'] : '15px',
				'padding-right'  => isset( $attr['contentPadding']['tablet']['right'] ) ? $attr['contentPadding']['tablet']['right'] : '15px',
				'padding-bottom' => isset( $attr['contentPadding']['tablet']['bottom'] ) ? $attr['contentPadding']['tablet']['bottom'] : '10px',
			),
			' .affiliatex-notice-content p'             => array(
				'font-size'      => isset( $attr['listTypography']['size']['tablet'] ) ? $attr['listTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['tablet'] ) ? $attr['listTypography']['line-height']['tablet'] : '1.333',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['tablet'] ) ? $attr['listTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affiliatex-notice-content li'            => array(
				'font-size'      => isset( $attr['listTypography']['size']['tablet'] ) ? $attr['listTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['listTypography']['line-height']['tablet'] ) ? $attr['listTypography']['line-height']['tablet'] : '1.333',
				'letter-spacing' => isset( $attr['listTypography']['letter-spacing']['tablet'] ) ? $attr['listTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-notice-inner-wrapper'               => array(
				'border-width'  => isset( $attr['noticeBorderWidth']['tablet']['top'] ) && isset( $attr['noticeBorderWidth']['tablet']['right'] ) && isset( $attr['noticeBorderWidth']['tablet']['bottom'] ) && isset( $attr['noticeBorderWidth']['tablet']['left'] ) ? $attr['noticeBorderWidth']['tablet']['top'] . ' ' . $attr['noticeBorderWidth']['tablet']['right'] . ' ' . $attr['noticeBorderWidth']['tablet']['bottom'] . ' ' . $attr['noticeBorderWidth']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
				'border-radius' => isset( $attr['noticeBorderRadius']['tablet']['top'] ) && isset( $attr['noticeBorderRadius']['tablet']['right'] ) && isset( $attr['noticeBorderRadius']['tablet']['bottom'] ) && isset( $attr['noticeBorderRadius']['tablet']['left'] ) ? $attr['noticeBorderRadius']['tablet']['top'] . ' ' . $attr['noticeBorderRadius']['tablet']['right'] . ' ' . $attr['noticeBorderRadius']['tablet']['bottom'] . ' ' . $attr['noticeBorderRadius']['tablet']['left'] . ' ' : '0px 0px 0px 0px',
				'margin-top'    => isset( $attr['noticeMargin']['tablet']['top'] ) ? $attr['noticeMargin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['noticeMargin']['tablet']['left'] ) ? $attr['noticeMargin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['noticeMargin']['tablet']['right'] ) ? $attr['noticeMargin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['noticeMargin']['tablet']['bottom'] ) ? $attr['noticeMargin']['tablet']['bottom'] : '30px',
			),
			' .affx-notice-inner-wrapper.layout-type-2' => array(
				'margin-top'     => isset( $attr['noticeMargin']['tablet']['top'] ) ? $attr['noticeMargin']['tablet']['top'] : '0px',
				'margin-left'    => isset( $attr['noticeMargin']['tablet']['left'] ) ? $attr['noticeMargin']['tablet']['left'] : '0px',
				'margin-right'   => isset( $attr['noticeMargin']['tablet']['right'] ) ? $attr['noticeMargin']['tablet']['right'] : '0px',
				'margin-bottom'  => isset( $attr['noticeMargin']['tablet']['bottom'] ) ? $attr['noticeMargin']['tablet']['bottom'] : '30px',
				'padding-top'    => isset( $attr['noticePadding']['tablet']['top'] ) ? $attr['noticePadding']['tablet']['top'] : '20px',
				'padding-left'   => isset( $attr['noticePadding']['tablet']['left'] ) ? $attr['noticePadding']['tablet']['left'] : '20px',
				'padding-right'  => isset( $attr['noticePadding']['tablet']['right'] ) ? $attr['noticePadding']['tablet']['right'] : '20px',
				'padding-bottom' => isset( $attr['noticePadding']['tablet']['bottom'] ) ? $attr['noticePadding']['tablet']['bottom'] : '20px',
			),
		);
		return $tablet_selectors;
	}
}
