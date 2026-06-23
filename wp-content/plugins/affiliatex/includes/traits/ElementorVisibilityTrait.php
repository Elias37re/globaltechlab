<?php
namespace AffiliateX\Traits;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Per-device visibility switchers shared by Elementor widgets, mapped to the hideOn block attributes.
 *
 * @package AffiliateX
 */
trait ElementorVisibilityTrait {

	/**
	 * Visibility switcher controls for an element
	 *
	 * @param string $prefix Control name prefix, e.g. 'affx_notice_title'.
	 * @param array  $condition Display condition.
	 * @return array
	 */
	protected function get_visibility_controls( string $prefix, array $condition = array() ): array {
		$devices = array(
			'desktop' => __( 'Hide On Desktop', 'affiliatex' ),
			'tablet'  => __( 'Hide On Tablet', 'affiliatex' ),
			'mobile'  => __( 'Hide On Mobile', 'affiliatex' ),
		);

		$controls = array(
			"{$prefix}_visibility_heading" => array(
				'label'     => __( 'Visibility', 'affiliatex' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => $condition,
			),
		);

		foreach ( $devices as $device => $label ) {
			$controls[ "{$prefix}_hide_{$device}" ] = array(
				'label'        => $label,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'affiliatex' ),
				'label_off'    => __( 'No', 'affiliatex' ),
				'return_value' => 'true',
				'default'      => '',
				'condition'    => $condition,
			);
		}

		return $controls;
	}

	/**
	 * Register the visibility switcher controls on a widget
	 *
	 * @param string $prefix Control name prefix used in get_visibility_controls().
	 * @param array  $condition Display condition.
	 * @return void
	 */
	protected function add_visibility_controls( string $prefix, array $condition = array() ): void {
		foreach ( $this->get_visibility_controls( $prefix, $condition ) as $name => $args ) {
			$this->add_control( $name, $args );
		}
	}

	/**
	 * Map the visibility switchers to the shared hideOn attribute consumed by the template
	 *
	 * @param array  $attributes Widget attributes.
	 * @param string $prefix Control name prefix used in get_visibility_controls().
	 * @param string $attr_key Block attribute key, e.g. 'titleHideOn'.
	 * @return array
	 */
	protected function map_visibility_attribute( array $attributes, string $prefix, string $attr_key ): array {
		$attributes[ $attr_key ] = array(
			'desktop' => ! empty( $attributes[ "{$prefix}_hide_desktop" ] ),
			'tablet'  => ! empty( $attributes[ "{$prefix}_hide_tablet" ] ),
			'mobile'  => ! empty( $attributes[ "{$prefix}_hide_mobile" ] ),
		);

		return $attributes;
	}
}
