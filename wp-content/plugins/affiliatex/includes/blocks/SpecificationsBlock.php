<?php

namespace AffiliateX\Blocks;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Traits\SpecificationsRenderTrait;

/**
 * AffiliateX Specifications Block
 *
 * @package AffiliateX
 */
class SpecificationsBlock extends BaseBlock {

	use SpecificationsRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
