<?php

namespace AffiliateX\Blocks;

use AffiliateX\Traits\ProductComparisonRenderTrait;

defined( 'ABSPATH' ) || exit;

/**
 * AffiliateX Button Block
 *
 * @package AffiliateX
 */
class ProductComparisonBlock extends BaseBlock {

	use ProductComparisonRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
