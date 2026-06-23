<?php

namespace AffiliateX\Blocks;

use AffiliateX\Traits\ProductTableRenderTrait;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;

/**
 * AffiliateX Product Table Block
 *
 * @package AffiliateX
 */
class ProductTableBlock extends BaseBlock {

	use ProductTableRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
