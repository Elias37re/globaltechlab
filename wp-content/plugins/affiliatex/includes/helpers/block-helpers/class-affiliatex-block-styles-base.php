<?php

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\HoverStyles;

defined( 'ABSPATH' ) || exit;

/**
 * Shared base for block style classes.
 *
 * Provides the common block_css() template that builds the desktop/tablet/mobile
 * selector buckets, applies promoted/hover selectors, and generates the scoped CSS.
 * Each block subclass supplies its own css_id_prefix() and the block-specific
 * get_selectors()/get_tabletselectors()/get_mobileselectors() and the optional
 * apply_promoted_selectors()/apply_hover_selectors().
 *
 * @package AffiliateX
 */
abstract class AffiliateX_Block_Styles_Base {

	abstract protected static function css_id_prefix(): string;

	protected static function apply_promoted_selectors( array &$buckets, array $attr ): void {}

	protected static function apply_hover_selectors( array &$buckets, array $attr ): void {}

	/**
	 * Merge a hover rule into the desktop bucket and add the transition to its base selectors.
	 *
	 * @param array  $buckets              Buckets keyed by device, by reference.
	 * @param string $transition           Transition value.
	 * @param string $hover_selector       The :hover selector to receive the styles.
	 * @param array  $styles               Styles for the hover selector.
	 * @param array  $transition_selectors Base selectors that receive the transition.
	 * @return void
	 */
	protected static function set_hover( array &$buckets, string $transition, string $hover_selector, array $styles, array $transition_selectors ): void {
		HoverStyles::merge_selector( $buckets['desktop'], $hover_selector, $styles );

		foreach ( $transition_selectors as $selector ) {
			HoverStyles::merge_selector( $buckets['desktop'], $selector, array( 'transition' => $transition ) );
		}
	}

	public static function block_css( $attr, $id ) {
		$buckets = array(
			'desktop' => static::get_selectors( $attr ),
			'tablet'  => static::get_tabletselectors( $attr ),
			'mobile'  => static::get_mobileselectors( $attr ),
		);

		static::apply_promoted_selectors( $buckets, $attr );
		static::apply_hover_selectors( $buckets, $attr );

		$prefix = static::css_id_prefix();

		return array(
			'desktop' => AffiliateX_Helpers::generate_css( $buckets['desktop'], $prefix . $id ),
			'tablet'  => AffiliateX_Helpers::generate_css( $buckets['tablet'], $prefix . $id ),
			'mobile'  => AffiliateX_Helpers::generate_css( $buckets['mobile'], $prefix . $id ),
		);
	}
}
