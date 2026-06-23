<?php
namespace AffiliateX\Traits;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\Elementor\WidgetHelper;

defined( 'ABSPATH' ) || exit;

/**
 * AffiliateX Specifications Render Trait
 *
 * @package AffiliateX
 */
trait SpecificationsRenderTrait {

	use ElementorVisibilityTrait;

	protected function get_elements(): array {
		return array(
			'wrapper'       => 'wp-block-affiliatex-specifications',
			'container'     => 'affx-specification-block-container',
			'title'         => 'affx-specification-title',
			'table'         => 'affx-specification-table',
			'label'         => 'affx-specification-block-container .affx-specification-table td.affx-spec-label',
			'value'         => 'affx-specification-block-container .affx-specification-table td.affx-spec-value',
			'table-cell'    => 'affx-specification-block-container .affx-specification-table td',
			'table-heading' => 'affx-specification-block-container .affx-specification-table th',
		);
	}

	protected function get_slug(): string {
		return 'specifications';
	}

	protected function get_fields(): array {
		return array(
			'block_id'              => '',
			'layoutStyle'           => 'layout-1',
			'specificationTitle'    => 'Specifications',
			'specificationTable'    => array(),
			'edSpecificationTitle'  => true,
			'specificationTitleTag' => 'h2',
		);
	}

	protected function render(): void {
		$attributes             = $this->get_settings_for_display();
		$attributes             = $this->parse_attributes( $attributes );
		$attributes             = WidgetHelper::process_attributes( $attributes );
		$attributes             = $this->map_visibility_attribute( $attributes, 'affx_spec_title', 'titleHideOn' );
		$attributes             = $this->map_visibility_attribute( $attributes, 'affx_spec_rows', 'rowsHideOn' );
		$attributes['block_id'] = $this->get_id();

		echo wp_kses_post( $this->render_template( $attributes ) );
	}

	public function render_template( array $attributes ): string {
		$attributes = $this->parse_attributes( $attributes );
		extract( $attributes );

		if ( self::IS_ELEMENTOR ) {
			// Elementor Context.
			$wrapper_attributes = sprintf(
				" id='affiliatex-specification-style-%s' class='%s'",
				$block_id,
				$attributes['wrapper_class'] ?? ''
			);
		} else {
			// Gutenberg Context.
			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'id'    => "affiliatex-specification-style-$block_id",
					'class' => $attributes['wrapper_class'] ?? '',
				)
			);
		}

		$specificationTitleTag = AffiliateX_Helpers::validate_tag( $specificationTitleTag, 'h2' );
		$styleClasses          = esc_attr( $layoutStyle ) . ' affx-col-' . esc_attr( $specificationColumnWidth );

		$titleHideClass = AffiliateX_Helpers::get_responsive_hide_classes( $attributes['titleHideOn'] ?? null );
		$rowsHideClass  = AffiliateX_Helpers::get_responsive_hide_classes( $attributes['rowsHideOn'] ?? null );

		ob_start();
		include $this->get_template_path();
		return ob_get_clean();
	}
}
