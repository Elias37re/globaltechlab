<?php

namespace AffiliateX\Blocks;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Traits\VersusLineRenderTrait;

/**
 * AffiliateX Versus Line Block
 *
 * @package AffiliateX
 */
class VersusLineBlock extends BaseBlock {

	use VersusLineRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
