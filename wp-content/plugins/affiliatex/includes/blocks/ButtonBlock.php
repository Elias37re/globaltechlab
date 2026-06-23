<?php

namespace AffiliateX\Blocks;

use AffiliateX\Traits\ButtonRenderTrait;

defined( 'ABSPATH' ) || exit;

/**
 * AffiliateX Button Block
 *
 * @package AffiliateX
 */
class ButtonBlock extends BaseBlock {

	use ButtonRenderTrait;

	protected function get_slug(): string {
		return 'buttons';
	}

	protected function get_fields(): array {
		return $this->get_button_fields();
	}

	public function render( array $attributes, string $content, $block = null ): string {
		$attributes = $this->parse_attributes( $attributes );

		if ( ! is_null( $block ) ) {
			$context                         = $block->context;
			$attributes['parent_attributes'] = array(
				'edFullBlockLink' => $context['affiliatex/edFullBlockLink'] ?? false,
				'blockName'       => $context['affiliatex/parentBlockName'] ?? 'buttons',
			);
		}

		return $this->render_button_template( $attributes, $content );
	}
}
