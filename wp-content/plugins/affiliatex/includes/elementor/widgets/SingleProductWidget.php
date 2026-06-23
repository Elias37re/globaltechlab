<?php

namespace AffiliateX\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Helpers\Elementor\ChildHelper;
use AffiliateX\Helpers\Elementor\WidgetHelper;
use AffiliateX\Traits\SingleProductRenderTrait;

/**
 * AffiliateX Single Product Elementor Widget
 *
 * @package AffiliateX
 */
class SingleProductWidget extends ElementorBase {

	use SingleProductRenderTrait;

	protected function get_slug(): string {
		return 'single-product';
	}

	protected function get_child_slugs(): array {
		return array( 'buttons' );
	}

	public function get_title() {
		return __( 'AffiliateX Single Product', 'affiliatex' );
	}

	public function get_icon() {
		return 'affx-icon-single-product';
	}

	public function get_keywords() {
		return array(
			'Product',
			'Single Product',
			'AffiliateX',
		);
	}

	protected function register_controls() {
		WidgetHelper::generate_fields(
			$this,
			$this->get_sp_elementor_controls(),
			'single-product'
		);

		/**************************************************************
		 * Child Button settings
		 */
		$child = new ChildHelper(
			$this,
			$this->get_button_elementor_fields(),
			self::$inner_button_config
		);

		$child->generate_fields();
	}
}
