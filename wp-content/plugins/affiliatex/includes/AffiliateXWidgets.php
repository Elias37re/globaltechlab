<?php

namespace AffiliateX;

use AffiliateX\Elementor\WidgetManager;
use AffiliateX\Elementor\Widgets\ButtonWidget;
use AffiliateX\Elementor\Widgets\CtaWidget;
use AffiliateX\Elementor\Widgets\ProsAndConsWidget;
use AffiliateX\Elementor\Widgets\NoticeWidget;
use AffiliateX\Elementor\Widgets\ProductComparisonWidget;
use AffiliateX\Elementor\Widgets\ProductTableWidget;
use AffiliateX\Elementor\Widgets\SingleProductWidget;
use AffiliateX\Elementor\Widgets\VerdictWidget;
use AffiliateX\Elementor\Widgets\SpecificationsWidget;
use AffiliateX\Elementor\Widgets\VersusLineWidget;

defined( 'ABSPATH' ) || exit;

/**
 * Elementor Widgets class
 *
 * @package AffiliateX
 */
class AffiliateXWidgets {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'elementor/init', array( $this, 'init' ) );
		add_filter( 'affiliatex_widgets_before_init', array( $this, 'disable_widgets' ) );
	}

	public function init() {
		// Check if Elementor is installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		$widgets = apply_filters(
			'affiliatex_widgets_before_init',
			array(
				'affiliatex/buttons'            => ButtonWidget::class,
				'affiliatex/cta'                => CtaWidget::class,
				'affiliatex/notice'             => NoticeWidget::class,
				'affiliatex/product-comparison' => ProductComparisonWidget::class,
				'affiliatex/product-table'      => ProductTableWidget::class,
				'affiliatex/pros-and-cons'      => ProsAndConsWidget::class,
				'affiliatex/single-product'     => SingleProductWidget::class,
				'affiliatex/specifications'     => SpecificationsWidget::class,
				'affiliatex/verdict'            => VerdictWidget::class,
				'affiliatex/versus-line'        => VersusLineWidget::class,
			)
		);

		new WidgetManager( $widgets );
	}

	/**
	 * Disable widgets that are disabled in the block settings.
	 *
	 * @param array $widgets Widgets array.
	 * @return array
	 */
	public function disable_widgets( $widgets ) {
		$disabled_widgets = affx_get_disabled_blocks();

		if ( ! empty( $disabled_widgets ) ) {
			foreach ( $disabled_widgets as $widget ) {
				unset( $widgets[ $widget ] );
			}
		}

		return $widgets;
	}
}
