<?php

namespace AffiliateX\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Hover/responsive CSS emission, mirrors src/blocks/helpers/hoverStyling.js and pxValue.js.
 *
 * @package AffiliateX
 */
class HoverStyles {

	private const DEVICES = array( 'desktop', 'tablet', 'mobile' );

	/**
	 * Merge styles into a selector bucket, mirrors mergeSelector.
	 *
	 * @param array  $bucket Device selector bucket, by reference.
	 * @param string $selector CSS selector.
	 * @param array  $styles Property/value pairs.
	 * @return void
	 */
	public static function merge_selector( array &$bucket, string $selector, array $styles ): void {
		if ( empty( $styles ) ) {
			return;
		}

		$bucket[ $selector ] = array_merge( $bucket[ $selector ] ?? array(), $styles );
	}

	/**
	 * Whether the attribute uses the responsive {desktop,tablet,mobile} shape.
	 *
	 * @param mixed $value Attribute value.
	 * @return bool
	 */
	public static function is_responsive( $value ): bool {
		return is_array( $value ) && array_key_exists( 'desktop', $value );
	}

	/**
	 * Normalize legacy bare numbers into px strings, mirrors toPxValue.
	 *
	 * @param mixed $value Scalar or per-device value.
	 * @return mixed
	 */
	public static function to_px_value( $value ) {
		if ( is_numeric( $value ) && ! is_string( $value ) ) {
			return $value . 'px';
		}

		if ( is_array( $value ) ) {
			return array_map( array( self::class, 'to_px_value' ), $value );
		}

		return $value;
	}

	/**
	 * Transition shorthand, mirrors the transitionProperties list in styling.js.
	 *
	 * @param array $extras Extra transitioned properties, e.g. 'font-size'.
	 * @return string
	 */
	public static function get_transition( array $extras = array() ): string {
		$properties = array_merge( array( 'color', 'background-color', 'border-color', 'box-shadow', 'border-radius' ), $extras );

		return implode(
			', ',
			array_map(
				static function ( string $property ): string {
					return $property . ' .15s ease';
				},
				$properties
			)
		);
	}

	/**
	 * Whether any device sets any side, mirrors hasHoverSpacingValue.
	 *
	 * @param mixed $spacing Spacing attribute value.
	 * @return bool
	 */
	public static function has_spacing_value( $spacing ): bool {
		if ( ! is_array( $spacing ) ) {
			return false;
		}

		foreach ( $spacing as $sides ) {
			if ( ! is_array( $sides ) ) {
				continue;
			}

			foreach ( $sides as $value ) {
				if ( null !== $value && '' !== $value ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Hover spacing rules for a device, mirrors hoverSpacingStyles.
	 *
	 * @param mixed  $spacing Spacing attribute value.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @param string $property Either 'padding' or 'margin'.
	 * @return array
	 */
	public static function get_spacing_styles( $spacing, string $device, string $property ): array {
		$sides = is_array( $spacing ) ? ( $spacing[ $device ] ?? null ) : null;

		if ( ! is_array( $sides ) ) {
			return array();
		}

		$styles = array();

		foreach ( array( 'top', 'left', 'right', 'bottom' ) as $side ) {
			$value = $sides[ $side ] ?? null;

			if ( ( is_string( $value ) && '' !== $value ) || ( is_numeric( $value ) && 0.0 !== (float) $value ) ) {
				$styles[ $property . '-' . $side ] = $value;
			}
		}

		return $styles;
	}

	/**
	 * Border radius shorthand for a device, mirrors hoverRadiusValue: empty when no side is above zero.
	 *
	 * @param mixed  $radius Radius attribute value.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @return string
	 */
	public static function get_radius_value( $radius, string $device ): string {
		$sides = is_array( $radius ) ? ( $radius[ $device ] ?? null ) : null;

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

	/**
	 * Hover border rules, mirrors the noticeHoverBorder emission in styling.js.
	 *
	 * @param mixed $border Border attribute value.
	 * @param bool  $include_color Whether border-color is part of the attribute.
	 * @return array
	 */
	public static function get_border_styles( $border, bool $include_color = true ): array {
		if ( ! is_array( $border ) || empty( $border['style'] ) || ! is_string( $border['style'] ) || 'none' === $border['style'] ) {
			return array();
		}

		$styles = array( 'border-style' => $border['style'] );

		if ( $include_color && ! empty( $border['color']['color'] ) && is_string( $border['color']['color'] ) ) {
			$styles['border-color'] = $border['color']['color'];
		}

		if ( ! empty( $border['width'] ) && is_numeric( $border['width'] ) ) {
			$styles['border-width'] = $border['width'] . 'px';
		}

		return $styles;
	}

	/**
	 * Hover shadow rules, only when explicitly enabled, mirrors styling.js.
	 *
	 * @param mixed $shadow Shadow attribute value.
	 * @return array
	 */
	public static function get_shadow_styles( $shadow ): array {
		if ( ! is_array( $shadow ) || true !== ( $shadow['enable'] ?? false ) ) {
			return array();
		}

		return array( 'box-shadow' => AffiliateX_Helpers::get_css_boxshadow( $shadow ) );
	}

	/**
	 * Whether a hover typography value is unset, mirrors isUnsetTypographyValue.
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
	 * Font rules from a hover typography object, mirrors hoverTypographyStyles.
	 *
	 * @param mixed  $typo Hover typography attribute value.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @return array
	 */
	public static function get_typography_styles( $typo, string $device ): array {
		if ( ! is_array( $typo ) || empty( $typo ) ) {
			return array();
		}

		$styles = array();

		$responsive_props = array(
			'font-size'      => 'size',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
		);

		foreach ( $responsive_props as $property => $key ) {
			$value = $typo[ $key ] ?? '';
			$value = is_array( $value ) ? ( $value[ $device ] ?? '' ) : $value;

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
	 * Whether any hover typography attribute sets the given key, mirrors hasHoverTypographyValue.
	 *
	 * @param array  $typography_values Hover typography attribute values.
	 * @param string $key Typography key, e.g. 'size'.
	 * @return bool
	 */
	public static function has_typography_value( array $typography_values, string $key ): bool {
		foreach ( $typography_values as $typo ) {
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
	 * Hover background rules, mirrors hoverBackground: unset hover type inherits the normal type.
	 *
	 * @param mixed $hover_type Hover background type attribute value.
	 * @param mixed $normal_type Normal background type attribute value.
	 * @param mixed $hover_color Hover background color.
	 * @param mixed $hover_gradient Hover background gradient.
	 * @return array
	 */
	public static function get_background_styles( $hover_type, $normal_type, $hover_color, $hover_gradient ): array {
		$styles = array();
		$type   = is_string( $hover_type ) && '' !== $hover_type ? $hover_type : $normal_type;

		if ( 'gradient' === $type ) {
			if ( is_string( $hover_gradient ) && '' !== $hover_gradient ) {
				$styles['background-image'] = $hover_gradient;
			}
		} elseif ( is_string( $hover_color ) && '' !== $hover_color ) {
			$styles['background-color'] = $hover_color;
		}

		return $styles;
	}

	/**
	 * Container :hover rules and transition, mirrors containerHoverCSS.
	 *
	 * @param array  $buckets Buckets keyed by device, by reference.
	 * @param string $selector Container selector.
	 * @param mixed  $normal_bg_type Normal background type attribute value.
	 * @param array  $hover Hover values: bg_type, bg_color, bg_gradient, border, shadow, border_radius, padding, margin.
	 * @return void
	 */
	public static function apply_container_hover( array &$buckets, string $selector, $normal_bg_type, array $hover ): void {
		$extras = array();

		if ( self::has_spacing_value( $hover['margin'] ?? null ) ) {
			$extras[] = 'margin';
		}

		if ( self::has_spacing_value( $hover['padding'] ?? null ) ) {
			$extras[] = 'padding';
		}

		$transition = self::get_transition( $extras );

		$hover_styles = self::get_background_styles(
			$hover['bg_type'] ?? '',
			$normal_bg_type,
			$hover['bg_color'] ?? '',
			$hover['bg_gradient'] ?? ''
		);

		$hover_styles = array_merge( $hover_styles, self::get_border_styles( $hover['border'] ?? null ) );
		$hover_styles = array_merge( $hover_styles, self::get_shadow_styles( $hover['shadow'] ?? null ) );

		$desktop_radius = self::get_radius_value( $hover['border_radius'] ?? null, 'desktop' );

		if ( '' !== $desktop_radius ) {
			$hover_styles['border-radius'] = $desktop_radius;
		}

		$has_hover_styles = ! empty( $hover_styles );

		self::merge_selector( $buckets['desktop'], $selector . ':hover', $hover_styles );

		foreach ( array( 'tablet', 'mobile' ) as $device ) {
			$radius = self::get_radius_value( $hover['border_radius'] ?? null, $device );

			if ( '' !== $radius ) {
				$has_hover_styles = true;
				self::merge_selector( $buckets[ $device ], $selector . ':hover', array( 'border-radius' => $radius ) );
			}
		}

		foreach ( self::DEVICES as $device ) {
			$spacing_hover = array_merge(
				self::get_spacing_styles( $hover['padding'] ?? null, $device, 'padding' ),
				self::get_spacing_styles( $hover['margin'] ?? null, $device, 'margin' )
			);

			if ( ! empty( $spacing_hover ) ) {
				$has_hover_styles = true;
				self::merge_selector( $buckets[ $device ], $selector . ':hover', $spacing_hover );
			}
		}

		if ( $has_hover_styles ) {
			self::merge_selector( $buckets['desktop'], $selector, array( 'transition' => $transition ) );
		}
	}
}
