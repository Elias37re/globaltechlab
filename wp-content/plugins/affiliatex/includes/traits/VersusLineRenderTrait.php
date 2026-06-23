<?php
namespace AffiliateX\Traits;

use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\Elementor\WidgetHelper;

defined( 'ABSPATH' ) || exit;

trait VersusLineRenderTrait {

	use ElementorVisibilityTrait;

	protected function get_slug(): string {
		return 'versus-line';
	}

	protected function get_fields(): array {
		return array(
			'block_id'        => '',
			'versusTitleTag'  => 'p',
			'vsLabel'         => 'VS',
			'versusTable'     => array(),
			'boxShadow'       => array(
				'enable'   => false,
				'h_offset' => 0,
				'v_offset' => 5,
				'blur'     => 20,
				'spread'   => 0,
				'inset'    => false,
				'color'    => array(
					'color' => 'rgba(210,213,218,0.2)',
				),
			),
			'border'          => array(
				'width' => '0',
				'style' => 'solid',
				'color' => array(
					'color' => '#E6ECF7',
				),
			),
			'vsTextColor'     => '#000',
			'vsBgColor'       => '#E6ECF7',
			'contentColor'    => '#292929',
			'versusRowColor'  => '#F5F7FA',
			'bgType'          => 'solid',
			'bgColorSolid'    => '#FFFFFF',
			'bgColorGradient' => array(
				'gradient' => 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)',
			),
		);
	}

	public function render(): void {
		$attributes             = $this->get_settings_for_display();
		$attributes             = WidgetHelper::process_attributes( $attributes );
		$attributes             = $this->map_visibility_attribute( $attributes, 'affx_vl_title', 'titleHideOn' );
		$attributes             = $this->map_visibility_attribute( $attributes, 'affx_vl_vs', 'vsHideOn' );
		$attributes['block_id'] = $this->get_id();

		echo wp_kses_post( $this->render_template( $attributes ) );
	}

	public function render_template( array $attributes ): string {
		$attributes = $this->parse_attributes( $attributes );
		extract( $attributes );

		if ( self::IS_ELEMENTOR ) {
			// Elementor Context.

			$wrapper_attributes = sprintf(
				"id='affiliatex-versus-line-style-%s' class='affx-versus-line-block-container %s'",
				$block_id,
				isset( $attributes['wrapper_class'] ) ? $attributes['wrapper_class'] : ''
			);
		} else {
			// Gutenberg Context.

			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'id'    => "affiliatex-versus-line-style-$block_id",
					'class' => 'affx-versus-line-block-container ' . ( isset( $attributes['wrapper_class'] ) ? $attributes['wrapper_class'] : '' ),
				)
			);
		}

		$versusTitleTag = AffiliateX_Helpers::validate_tag( $attributes['versusTitleTag'], 'p' );

		$titleHideClass = AffiliateX_Helpers::get_responsive_hide_classes( $attributes['titleHideOn'] ?? null );
		$vsHideClass    = AffiliateX_Helpers::get_responsive_hide_classes( $attributes['vsHideOn'] ?? null );

		ob_start();
		include $this->get_template_path();

		return ob_get_clean();
	}
}
