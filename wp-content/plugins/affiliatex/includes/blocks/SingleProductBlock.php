<?php

namespace AffiliateX\Blocks;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Traits\SingleProductRenderTrait;

/**
 * AffiliateX Single Product Block
 *
 * @package AffiliateX
 */
class SingleProductBlock extends BaseBlock {

	use SingleProductRenderTrait;

	protected function get_slug(): string {
		return 'single-product';
	}

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_sp_template( $attributes, $content );
	}
}
