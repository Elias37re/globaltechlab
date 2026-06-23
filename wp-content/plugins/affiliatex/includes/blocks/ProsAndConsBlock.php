<?php

namespace AffiliateX\Blocks;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Traits\ProsAndConsRenderTrait;

/**
 * AffiliateX Pros and Cons Block
 *
 * @package AffiliateX
 */
class ProsAndConsBlock extends BaseBlock {

	use ProsAndConsRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		$attributes = $this->parse_attributes( $attributes );
		extract( $attributes );

		if ( is_array( $prosListItems ) && count( $prosListItems ) > 0 && isset( $prosListItems[0]['list'] ) && is_string( $prosListItems[0]['list'] ) && has_shortcode( $prosListItems[0]['list'], 'affiliatex-product' ) ) {
			$attributes['prosListItems'] = json_decode( do_shortcode( $prosListItems[0]['list'] ), true );
		}

		if ( is_array( $consListItems ) && count( $consListItems ) > 0 && isset( $consListItems[0]['list'] ) && is_string( $consListItems[0]['list'] ) && has_shortcode( $consListItems[0]['list'], 'affiliatex-product' ) ) {
			$attributes['consListItems'] = json_decode( do_shortcode( $consListItems[0]['list'] ), true );
		}
		return $this->render_template( $attributes, $content );
	}
}
