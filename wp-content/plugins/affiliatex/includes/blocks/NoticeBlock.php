<?php

namespace AffiliateX\Blocks;

use AffiliateX\Traits\NoticeRenderTrait;
use AffiliateX\Helpers\AffiliateX_Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * AffiliateX Notice Block
 *
 * @package AffiliateX
 */
class NoticeBlock extends BaseBlock {

	use NoticeRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
