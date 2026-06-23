<?php

namespace AffiliateX\Blocks;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Traits\CtaRenderTrait;

/**
 * AffiliateX CTA Block
 *
 * @package AffiliateX
 */
class CtaBlock extends BaseBlock {

	use CtaRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
