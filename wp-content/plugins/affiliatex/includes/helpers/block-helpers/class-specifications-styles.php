<?php

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\HoverStyles;

require_once __DIR__ . '/class-affiliatex-block-styles-base.php';

/**
 * Specifications Block Styles
 *
 * @package AffiliateX
 */

class AffiliateX_Specifications_Styles extends AffiliateX_Block_Styles_Base {

	protected static function css_id_prefix(): string {
		return '#affiliatex-specification-style-';
	}

	public static function block_fonts( $attr ) {
		return array(
			'specificationTitleTypography' => isset( $attr['specificationTitleTypography'] ) ? $attr['specificationTitleTypography'] : array(),
			'specificationLabelTypography' => isset( $attr['specificationLabelTypography'] ) ? $attr['specificationLabelTypography'] : array(),
			'specificationValueTypography' => isset( $attr['specificationValueTypography'] ) ? $attr['specificationValueTypography'] : array(),
		);
	}

	/**
	 * Per-device text-align for the promoted align attributes, mirrors styling.js. Scalars keep the legacy desktop-only output.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_promoted_selectors( array &$buckets, array $attr ): void {
		$promoted = array(
			'specificationTitleAlign' => ' .affx-specification-table th .affx-specification-title',
			'specificationLabelAlign' => ' .affx-specification-table td.affx-spec-label',
			'specificationValueAlign' => ' .affx-specification-table td.affx-spec-value',
		);

		foreach ( $promoted as $key => $selector ) {
			if ( ! HoverStyles::is_responsive( $attr[ $key ] ?? null ) ) {
				continue;
			}

			foreach ( array( 'tablet', 'mobile' ) as $device ) {
				$value = AffiliateX_Helpers::get_responsive_value( $attr[ $key ], $device );

				if ( is_string( $value ) && '' !== $value ) {
					HoverStyles::merge_selector( $buckets[ $device ], $selector, array( 'text-align' => $value ) );
				}
			}
		}
	}

	/**
	 * Hover rules for the wave-2 hover attributes, mirrors specifications/styling.js.
	 *
	 * @param array $buckets Buckets keyed by device, by reference.
	 * @param array $attr Block attributes.
	 * @return void
	 */
	protected static function apply_hover_selectors( array &$buckets, array $attr ): void {
		$typos = array(
			$attr['specificationTitleHoverTypography'] ?? null,
			$attr['specificationLabelHoverTypography'] ?? null,
			$attr['specificationValueHoverTypography'] ?? null,
		);

		$extras = array();

		if ( HoverStyles::has_typography_value( $typos, 'size' ) ) {
			$extras[] = 'font-size';
		}

		if ( HoverStyles::has_typography_value( $typos, 'letter-spacing' ) ) {
			$extras[] = 'letter-spacing';
		}

		if ( HoverStyles::has_spacing_value( $attr['specificationHoverMargin'] ?? null ) ) {
			$extras[] = 'margin';
		}

		if ( HoverStyles::has_spacing_value( $attr['specificationHoverPadding'] ?? null ) ) {
			$extras[] = 'padding';
		}

		$transition = HoverStyles::get_transition( $extras );

		$hover_colors = array(
			'specificationTitleHoverColor'   => array(
				'selector'    => ' .affx-specification-table th:hover .affx-specification-title',
				'property'    => 'color',
				'transitions' => array( ' .affx-specification-table th .affx-specification-title' ),
			),
			'specificationTitleBgHoverColor' => array(
				'selector'    => ' .affx-specification-table th:hover',
				'property'    => 'background',
				'transitions' => array( ' .affx-specification-table th' ),
			),
			'specificationLabelHoverColor'   => array(
				'selector'    => ' .affx-specification-table td.affx-spec-label:hover',
				'property'    => 'color',
				'transitions' => array( ' .affx-specification-table td.affx-spec-label' ),
			),
			'specificationValueHoverColor'   => array(
				'selector'    => ' .affx-specification-table td.affx-spec-value:hover',
				'property'    => 'color',
				'transitions' => array( ' .affx-specification-table td.affx-spec-value' ),
			),
			'specificationRowHoverColor'     => array(
				'selector'    => ' .affx-specification-table tbody tr:hover td',
				'property'    => 'background',
				'transitions' => array( ' .affx-specification-table td' ),
			),
		);

		foreach ( $hover_colors as $key => $rule ) {
			if ( ! empty( $attr[ $key ] ) && is_string( $attr[ $key ] ) ) {
				self::set_hover( $buckets, $transition, $rule['selector'], array( $rule['property'] => $attr[ $key ] ), $rule['transitions'] );
			}
		}

		$table_bg_hover = HoverStyles::get_background_styles(
			$attr['specificationBgHoverType'] ?? '',
			$attr['specificationBgType'] ?? 'solid',
			$attr['specificationBgHoverColor'] ?? '',
			$attr['specificationBgHoverGradient'] ?? ''
		);

		if ( ! empty( $table_bg_hover ) ) {
			self::set_hover( $buckets, $transition, ' .affx-specification-block-container:hover .affx-specification-table', $table_bg_hover, array( ' .affx-specification-table' ) );
		}

		$container_hover = array_merge(
			HoverStyles::get_border_styles( $attr['specificationHoverBorder'] ?? null ),
			HoverStyles::get_shadow_styles( $attr['specificationHoverShadow'] ?? null )
		);

		$desktop_radius = HoverStyles::get_radius_value( $attr['specificationHoverBorderRadius'] ?? null, 'desktop' );

		if ( '' !== $desktop_radius ) {
			$container_hover['border-radius'] = $desktop_radius;
		}

		if ( ! empty( $container_hover ) ) {
			self::set_hover( $buckets, $transition, ' .affx-specification-block-container:hover', $container_hover, array( ' .affx-specification-block-container' ) );
		}

		foreach ( array( 'tablet', 'mobile' ) as $device ) {
			$radius = HoverStyles::get_radius_value( $attr['specificationHoverBorderRadius'] ?? null, $device );

			if ( '' !== $radius ) {
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-specification-block-container:hover', array( 'border-radius' => $radius ) );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-specification-block-container', array( 'transition' => $transition ) );
			}
		}

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			$margin_hover = HoverStyles::get_spacing_styles( $attr['specificationHoverMargin'] ?? null, $device, 'margin' );

			if ( ! empty( $margin_hover ) ) {
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-specification-block-container:hover', $margin_hover );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-specification-block-container', array( 'transition' => $transition ) );
			}

			$padding_hover = HoverStyles::get_spacing_styles( $attr['specificationHoverPadding'] ?? null, $device, 'padding' );

			if ( ! empty( $padding_hover ) ) {
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-specification-block-container:hover .affx-specification-table td', $padding_hover );
				HoverStyles::merge_selector( $buckets[ $device ], ' .affx-specification-block-container:hover .affx-specification-table th', $padding_hover );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-specification-table td', array( 'transition' => $transition ) );
				HoverStyles::merge_selector( $buckets['desktop'], ' .affx-specification-table th', array( 'transition' => $transition ) );
			}
		}

		$typography_rules = array(
			array(
				'typography' => $attr['specificationTitleHoverTypography'] ?? null,
				'base'       => ' .affx-specification-table th .affx-specification-title',
				'hover'      => ' .affx-specification-table th:hover .affx-specification-title',
			),
			array(
				'typography' => $attr['specificationLabelHoverTypography'] ?? null,
				'base'       => ' .affx-specification-table td.affx-spec-label',
				'hover'      => ' .affx-specification-table td.affx-spec-label:hover',
			),
			array(
				'typography' => $attr['specificationValueHoverTypography'] ?? null,
				'base'       => ' .affx-specification-table td.affx-spec-value',
				'hover'      => ' .affx-specification-table td.affx-spec-value:hover',
			),
		);

		foreach ( $typography_rules as $rule ) {
			$has_styles = false;

			foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
				$styles = HoverStyles::get_typography_styles( $rule['typography'], $device );

				if ( ! empty( $styles ) ) {
					$has_styles = true;
					HoverStyles::merge_selector( $buckets[ $device ], $rule['hover'], $styles );
				}
			}

			if ( $has_styles ) {
				HoverStyles::merge_selector( $buckets['desktop'], $rule['base'], array( 'transition' => $transition ) );
			}
		}
	}

	public static function get_selectors( $attr ) {

		$customization_data = affx_get_customization_settings();
		$global_font_family = isset( $customization_data['typography']['family'] ) ? $customization_data['typography']['family'] : 'Default';
		$global_font_color  = isset( $customization_data['fontColor'] ) ? $customization_data['fontColor'] : '#292929';

		$bgType         = isset( $attr['specificationBgType'] ) ? $attr['specificationBgType'] : 'solid';
		$bgGradient     = isset( $attr['specificationBgColorGradient']['gradient'] ) ? $attr['specificationBgColorGradient']['gradient'] : 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)';
		$bgColor        = isset( $attr['specificationBgColorSolid'] ) ? $attr['specificationBgColorSolid'] : '#FFFFFF';
		$titleVariation = isset( $attr['specificationTitleTypography']['variation'] ) ? $attr['specificationTitleTypography']['variation'] : 'n5';
		$labelVariation = isset( $attr['specificationLabelTypography']['variation'] ) ? $attr['specificationLabelTypography']['variation'] : 'n4';
		$valueVariation = isset( $attr['specificationValueTypography']['variation'] ) ? $attr['specificationValueTypography']['variation'] : 'n4';

		$selectors = array(
			' .affx-specification-block-container' => array(
				'border-style'  => isset( $attr['specificationBorder']['style'] ) ? $attr['specificationBorder']['style'] : 'solid',
				'border-color'  => isset( $attr['specificationBorder']['color']['color'] ) ? $attr['specificationBorder']['color']['color'] : '#E6ECF7',
				'border-width'  => isset( $attr['specificationBorderWidth']['desktop']['top'] ) && isset( $attr['specificationBorderWidth']['desktop']['right'] ) && isset( $attr['specificationBorderWidth']['desktop']['bottom'] ) && isset( $attr['specificationBorderWidth']['desktop']['left'] ) ? $attr['specificationBorderWidth']['desktop']['top'] . ' ' . $attr['specificationBorderWidth']['desktop']['right'] . ' ' . $attr['specificationBorderWidth']['desktop']['bottom'] . ' ' . $attr['specificationBorderWidth']['desktop']['left'] . ' ' : '1px 1px 1px 1px',
				'margin-top'    => isset( $attr['specificationMargin']['desktop']['top'] ) ? $attr['specificationMargin']['desktop']['top'] : '0px',
				'margin-left'   => isset( $attr['specificationMargin']['desktop']['left'] ) ? $attr['specificationMargin']['desktop']['left'] : '0px',
				'margin-right'  => isset( $attr['specificationMargin']['desktop']['right'] ) ? $attr['specificationMargin']['desktop']['right'] : '0px',
				'margin-bottom' => isset( $attr['specificationMargin']['desktop']['bottom'] ) ? $attr['specificationMargin']['desktop']['bottom'] : '30px',
				'overflow'      => 'hidden',
				'border-radius' => isset( $attr['specificationBorderRadius']['desktop']['top'] ) && isset( $attr['specificationBorderRadius']['desktop']['right'] ) && isset( $attr['specificationBorderRadius']['desktop']['bottom'] ) && isset( $attr['specificationBorderRadius']['desktop']['left'] ) ? $attr['specificationBorderRadius']['desktop']['top'] . ' ' . $attr['specificationBorderRadius']['desktop']['right'] . ' ' . $attr['specificationBorderRadius']['desktop']['bottom'] . ' ' . $attr['specificationBorderRadius']['desktop']['left'] . ' ' : '0 0 0 0',
				'box-shadow'    => isset( $attr['specificationBoxShadow'] ) && $attr['specificationBoxShadow']['enable'] ? AffiliateX_Helpers::get_css_boxshadow( $attr['specificationBoxShadow'] ) : 'none',
			),
			' .affx-specification-table'           => array(
				'margin'     => '0',
				'background' => $bgType && $bgType === 'solid' ? $bgColor : $bgGradient,
			),
			' .affx-specification-table td'        => array(
				'padding-top'    => isset( $attr['specificationPadding']['desktop']['top'] ) ? $attr['specificationPadding']['desktop']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['desktop']['left'] ) ? $attr['specificationPadding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['desktop']['right'] ) ? $attr['specificationPadding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['desktop']['bottom'] ) ? $attr['specificationPadding']['desktop']['bottom'] : '16px',
			),
			' .affx-specification-table th'        => array(
				'background'     => isset( $attr['specificationTitleBgColor'] ) ? $attr['specificationTitleBgColor'] : '#FFFFFF',
				'padding-top'    => isset( $attr['specificationPadding']['desktop']['top'] ) ? $attr['specificationPadding']['desktop']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['desktop']['left'] ) ? $attr['specificationPadding']['desktop']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['desktop']['right'] ) ? $attr['specificationPadding']['desktop']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['desktop']['bottom'] ) ? $attr['specificationPadding']['desktop']['bottom'] : '16px',
			),

			' .affx-specification-table th .affx-specification-title' => array(
				'margin'          => '0',
				'color'           => isset( $attr['specificationTitleColor'] ) ? $attr['specificationTitleColor'] : '#292929',
				'text-align'      => isset( $attr['specificationTitleAlign'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['specificationTitleAlign'] ) : 'left',
				'font-family'     => isset( $attr['specificationTitleTypography']['family'] ) ? $attr['specificationTitleTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['specificationTitleTypography']['size']['desktop'] ) ? $attr['specificationTitleTypography']['size']['desktop'] : '24px',
				'line-height'     => isset( $attr['specificationTitleTypography']['line-height']['desktop'] ) ? $attr['specificationTitleTypography']['line-height']['desktop'] : '1.5',
				'text-transform'  => isset( $attr['specificationTitleTypography']['text-transform'] ) ? $attr['specificationTitleTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['specificationTitleTypography']['text-decoration'] ) ? $attr['specificationTitleTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['specificationTitleTypography']['letter-spacing']['desktop'] ) ? $attr['specificationTitleTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $titleVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $titleVariation ),
			),

			' .affx-specification-table td.affx-spec-label' => array(
				'color'           => isset( $attr['specificationLabelColor'] ) ? $attr['specificationLabelColor'] : '#000000',
				'text-align'      => isset( $attr['specificationLabelAlign'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['specificationLabelAlign'] ) : 'left',
				'font-family'     => isset( $attr['specificationLabelTypography']['family'] ) ? $attr['specificationLabelTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['specificationLabelTypography']['size']['desktop'] ) ? $attr['specificationLabelTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['specificationLabelTypography']['line-height']['desktop'] ) ? $attr['specificationLabelTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['specificationLabelTypography']['text-transform'] ) ? $attr['specificationLabelTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['specificationLabelTypography']['text-decoration'] ) ? $attr['specificationLabelTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['specificationLabelTypography']['letter-spacing']['desktop'] ) ? $attr['specificationLabelTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $labelVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $labelVariation ),
				'width'           => isset( $attr['specificationColumnWidth'] ) && $attr['specificationColumnWidth'] === 'styleThree' ? '66.66%' : ( isset( $attr['specificationColumnWidth'] ) && $attr['specificationColumnWidth'] === 'styleTwo' ? '50%' : '33.33%' ),
			),
			' .affx-specification-table td.affx-spec-value' => array(
				'color'           => isset( $attr['specificationValueColor'] ) ? $attr['specificationValueColor'] : $global_font_color,
				'text-align'      => isset( $attr['specificationValueAlign'] ) ? AffiliateX_Helpers::get_responsive_value( $attr['specificationValueAlign'] ) : 'left',
				'font-family'     => isset( $attr['specificationValueTypography']['family'] ) ? $attr['specificationValueTypography']['family'] : $global_font_family,
				'font-size'       => isset( $attr['specificationValueTypography']['size']['desktop'] ) ? $attr['specificationValueTypography']['size']['desktop'] : '18px',
				'line-height'     => isset( $attr['specificationValueTypography']['line-height']['desktop'] ) ? $attr['specificationValueTypography']['line-height']['desktop'] : '1.65',
				'text-transform'  => isset( $attr['specificationValueTypography']['text-transform'] ) ? $attr['specificationValueTypography']['text-transform'] : 'none',
				'text-decoration' => isset( $attr['specificationValueTypography']['text-decoration'] ) ? $attr['specificationValueTypography']['text-decoration'] : 'none',
				'letter-spacing'  => isset( $attr['specificationValueTypography']['letter-spacing']['desktop'] ) ? $attr['specificationValueTypography']['letter-spacing']['desktop'] : '0em',
				'font-weight'     => AffiliateX_Helpers::get_fontweight_variation( $valueVariation ),
				'font-style'      => AffiliateX_Helpers::get_font_style( $valueVariation ),
				'width'           => isset( $attr['specificationColumnWidth'] ) && $attr['specificationColumnWidth'] === 'styleThree' ? '33.33%' : ( isset( $attr['specificationColumnWidth'] ) && $attr['specificationColumnWidth'] === 'styleTwo' ? '50%' : '66.66%' ),
			),
			' .affx-specification-table.layout-2 td.affx-spec-label' => array(
				'background' => isset( $attr['specificationRowColor'] ) ? $attr['specificationRowColor'] : '#F5F7FA',
			),
			' .affx-specification-table.layout-3 tbody tr:nth-child(even) td' => array(
				'background' => isset( $attr['specificationRowColor'] ) ? $attr['specificationRowColor'] : '#F5F7FA',
			),

		);

		return $selectors;
	}

	public static function get_mobileselectors( $attr ) {

		$mobile_selectors = array(
			' .affx-specification-block-container' => array(
				'border-width'  => isset( $attr['specificationBorderWidth']['mobile']['top'] ) && isset( $attr['specificationBorderWidth']['mobile']['right'] ) && isset( $attr['specificationBorderWidth']['mobile']['bottom'] ) && isset( $attr['specificationBorderWidth']['mobile']['left'] ) ? $attr['specificationBorderWidth']['mobile']['top'] . ' ' . $attr['specificationBorderWidth']['mobile']['right'] . ' ' . $attr['specificationBorderWidth']['mobile']['bottom'] . ' ' . $attr['specificationBorderWidth']['mobile']['left'] . ' ' : '1px 1px 1px 1px',
				'margin-top'    => isset( $attr['specificationMargin']['mobile']['top'] ) ? $attr['specificationMargin']['mobile']['top'] : '0px',
				'margin-left'   => isset( $attr['specificationMargin']['mobile']['left'] ) ? $attr['specificationMargin']['mobile']['left'] : '0px',
				'margin-right'  => isset( $attr['specificationMargin']['mobile']['right'] ) ? $attr['specificationMargin']['mobile']['right'] : '0px',
				'margin-bottom' => isset( $attr['specificationMargin']['mobile']['bottom'] ) ? $attr['specificationMargin']['mobile']['bottom'] : '30px',
				'border-radius' => isset( $attr['specificationBorderRadius']['mobile']['top'] ) && isset( $attr['specificationBorderRadius']['mobile']['right'] ) && isset( $attr['specificationBorderRadius']['mobile']['bottom'] ) && isset( $attr['specificationBorderRadius']['mobile']['left'] ) ? $attr['specificationBorderRadius']['mobile']['top'] . ' ' . $attr['specificationBorderRadius']['mobile']['right'] . ' ' . $attr['specificationBorderRadius']['mobile']['bottom'] . ' ' . $attr['specificationBorderRadius']['mobile']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-specification-table td'        => array(
				'padding-top'    => isset( $attr['specificationPadding']['mobile']['top'] ) ? $attr['specificationPadding']['mobile']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['mobile']['left'] ) ? $attr['specificationPadding']['mobile']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['mobile']['right'] ) ? $attr['specificationPadding']['mobile']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['mobile']['bottom'] ) ? $attr['specificationPadding']['mobile']['bottom'] : '16px',
			),
			' .affx-specification-table th'        => array(
				'padding-top'    => isset( $attr['specificationPadding']['mobile']['top'] ) ? $attr['specificationPadding']['mobile']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['mobile']['left'] ) ? $attr['specificationPadding']['mobile']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['mobile']['right'] ) ? $attr['specificationPadding']['mobile']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['mobile']['bottom'] ) ? $attr['specificationPadding']['mobile']['bottom'] : '16px',
			),
			' .affx-specification-table th .affx-specification-title' => array(
				'font-size'      => isset( $attr['specificationTitleTypography']['size']['mobile'] ) ? $attr['specificationTitleTypography']['size']['mobile'] : '24px',
				'line-height'    => isset( $attr['specificationTitleTypography']['line-height']['mobile'] ) ? $attr['specificationTitleTypography']['line-height']['mobile'] : '1.5',
				'letter-spacing' => isset( $attr['specificationTitleTypography']['letter-spacing']['mobile'] ) ? $attr['specificationTitleTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-specification-table td.affx-spec-label' => array(
				'font-size'      => isset( $attr['specificationLabelTypography']['size']['mobile'] ) ? $attr['specificationLabelTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['specificationLabelTypography']['line-height']['mobile'] ) ? $attr['specificationLabelTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['specificationLabelTypography']['letter-spacing']['mobile'] ) ? $attr['specificationLabelTypography']['letter-spacing']['mobile'] : '0em',
			),
			' .affx-specification-table td.affx-spec-value' => array(
				'font-size'      => isset( $attr['specificationValueTypography']['size']['mobile'] ) ? $attr['specificationValueTypography']['size']['mobile'] : '18px',
				'line-height'    => isset( $attr['specificationValueTypography']['line-height']['mobile'] ) ? $attr['specificationValueTypography']['line-height']['mobile'] : '1.65',
				'letter-spacing' => isset( $attr['specificationValueTypography']['letter-spacing']['mobile'] ) ? $attr['specificationValueTypography']['letter-spacing']['mobile'] : '0em',
			),

		);

		return $mobile_selectors;
	}

	public static function get_tabletselectors( $attr ) {

		$tablet_selectors = array(
			' .affx-specification-block-container' => array(
				'border-width'  => isset( $attr['specificationBorderWidth']['tablet']['top'] ) && isset( $attr['specificationBorderWidth']['tablet']['right'] ) && isset( $attr['specificationBorderWidth']['tablet']['bottom'] ) && isset( $attr['specificationBorderWidth']['tablet']['left'] ) ? $attr['specificationBorderWidth']['tablet']['top'] . ' ' . $attr['specificationBorderWidth']['tablet']['right'] . ' ' . $attr['specificationBorderWidth']['tablet']['bottom'] . ' ' . $attr['specificationBorderWidth']['tablet']['left'] . ' ' : '1px 1px 1px 1px',
				'margin-top'    => isset( $attr['specificationMargin']['tablet']['top'] ) ? $attr['specificationMargin']['tablet']['top'] : '0px',
				'margin-left'   => isset( $attr['specificationMargin']['tablet']['left'] ) ? $attr['specificationMargin']['tablet']['left'] : '0px',
				'margin-right'  => isset( $attr['specificationMargin']['tablet']['right'] ) ? $attr['specificationMargin']['tablet']['right'] : '0px',
				'margin-bottom' => isset( $attr['specificationMargin']['tablet']['bottom'] ) ? $attr['specificationMargin']['tablet']['bottom'] : '30px',
				'border-radius' => isset( $attr['specificationBorderRadius']['tablet']['top'] ) && isset( $attr['specificationBorderRadius']['tablet']['right'] ) && isset( $attr['specificationBorderRadius']['tablet']['bottom'] ) && isset( $attr['specificationBorderRadius']['tablet']['left'] ) ? $attr['specificationBorderRadius']['tablet']['top'] . ' ' . $attr['specificationBorderRadius']['tablet']['right'] . ' ' . $attr['specificationBorderRadius']['tablet']['bottom'] . ' ' . $attr['specificationBorderRadius']['tablet']['left'] . ' ' : '0 0 0 0',
			),
			' .affx-specification-table td'        => array(
				'padding-top'    => isset( $attr['specificationPadding']['tablet']['top'] ) ? $attr['specificationPadding']['tablet']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['tablet']['left'] ) ? $attr['specificationPadding']['tablet']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['tablet']['right'] ) ? $attr['specificationPadding']['tablet']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['tablet']['bottom'] ) ? $attr['specificationPadding']['tablet']['bottom'] : '16px',
			),
			' .affx-specification-table th'        => array(
				'padding-top'    => isset( $attr['specificationPadding']['tablet']['top'] ) ? $attr['specificationPadding']['tablet']['top'] : '16px',
				'padding-left'   => isset( $attr['specificationPadding']['tablet']['left'] ) ? $attr['specificationPadding']['tablet']['left'] : '24px',
				'padding-right'  => isset( $attr['specificationPadding']['tablet']['right'] ) ? $attr['specificationPadding']['tablet']['right'] : '24px',
				'padding-bottom' => isset( $attr['specificationPadding']['tablet']['bottom'] ) ? $attr['specificationPadding']['tablet']['bottom'] : '16px',
			),
			' .affx-specification-table th .affx-specification-title' => array(
				'font-size'      => isset( $attr['specificationTitleTypography']['size']['tablet'] ) ? $attr['specificationTitleTypography']['size']['tablet'] : '24px',
				'line-height'    => isset( $attr['specificationTitleTypography']['line-height']['tablet'] ) ? $attr['specificationTitleTypography']['line-height']['tablet'] : '1.5',
				'letter-spacing' => isset( $attr['specificationTitleTypography']['letter-spacing']['tablet'] ) ? $attr['specificationTitleTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-specification-table td.affx-spec-label' => array(
				'font-size'      => isset( $attr['specificationLabelTypography']['size']['tablet'] ) ? $attr['specificationLabelTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['specificationLabelTypography']['line-height']['tablet'] ) ? $attr['specificationLabelTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['specificationLabelTypography']['letter-spacing']['tablet'] ) ? $attr['specificationLabelTypography']['letter-spacing']['tablet'] : '0em',
			),
			' .affx-specification-table td.affx-spec-value' => array(
				'font-size'      => isset( $attr['specificationValueTypography']['size']['tablet'] ) ? $attr['specificationValueTypography']['size']['tablet'] : '18px',
				'line-height'    => isset( $attr['specificationValueTypography']['line-height']['tablet'] ) ? $attr['specificationValueTypography']['line-height']['tablet'] : '1.65',
				'letter-spacing' => isset( $attr['specificationValueTypography']['letter-spacing']['tablet'] ) ? $attr['specificationValueTypography']['letter-spacing']['tablet'] : '0em',
			),
		);

		return $tablet_selectors;
	}
}
