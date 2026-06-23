<?php
namespace AffiliateX\Traits;

use AffiliateX\Elementor\Widgets\ElementorBase;
use AffiliateX\Helpers\AffiliateX_Helpers;
use AffiliateX\Helpers\Elementor\WidgetHelper;

defined( 'ABSPATH' ) || exit;

/**
 * This trait is a channel for share rendering methods between Gutenberg and Elementor
 *
 * @package AffiliateX
 */
trait NoticeRenderTrait {

	use ElementorVisibilityTrait;

	protected function get_slug(): string {
		return 'notice';
	}

	protected function get_fields(): array {
		return array(
			'block_id'            => '',
			'titleTag1'           => 'h2',
			'layoutStyle'         => 'layout-type-1',
			'noticeTitle'         => __( 'Notice', 'affiliatex' ),
			'noticeTitleIcon'     => array(
				'name'  => 'info-circle',
				'value' => 'fa fa-info-circle',
			),
			'noticeListItems'     => array( __( 'List items', 'affiliatex' ) ),
			'noticeListType'      => 'unordered',
			'noticeContent'       => __( 'This is the notice content', 'affiliatex' ),
			'noticeContentType'   => 'list',
			'noticeListIcon'      => array(
				'name'  => 'check-circle',
				'value' => 'far fa-check-circle',
			),
			'noticeunorderedType' => 'icon',
			'edTitleIcon'         => true,
			'titleAlignment'      => 'left',
		);
	}

	protected function get_elements(): array {
		return array(
			'wrapper'          => 'affx-notice-inner-wrapper',
			'layout-1-wrapper' => 'affx-notice-inner-wrapper.layout-type-1',
			'layout-2-wrapper' => 'affx-notice-inner-wrapper.layout-type-2',
			'title'            => 'affiliatex-notice-title',
			'paragraph'        => 'affiliatex-notice-content p',
			'list'             => 'affiliatex-notice-content li',
			'paragraph-list'   => 'affiliatex-notice-content p, {{WRAPPER}} .affiliatex-notice-content li',
			'content'          => 'affiliatex-notice-content',
			'list-icon'        => 'affiliatex-list li:before',
		);
	}

	protected function render(): void {
		$attributes             = $this->get_settings_for_display();
		$attributes             = $this->parse_attributes( $attributes );
		$attributes             = WidgetHelper::process_attributes( $attributes );
		$attributes             = $this->map_visibility_attribute( $attributes, 'affx_notice_title', 'titleHideOn' );
		$attributes             = $this->map_visibility_attribute( $attributes, 'affx_notice_content', 'contentHideOn' );
		$attributes['block_id'] = $this->get_id();

		if ( ! empty( $attributes['noticeListItems'] ) ) {
			$attributes['noticeListItems'] = WidgetHelper::extract_list_items( $attributes['noticeListItems'] );
		}

		if ( ! empty( $attributes['noticeTitleIcon'] ) ) {
			$attributes['noticeTitleIcon'] = WidgetHelper::extract_icon( $attributes['noticeTitleIcon'] );
		}

		if ( ! empty( $attributes['noticeListIcon'] ) ) {
			$attributes['noticeListIcon'] = WidgetHelper::extract_icon( $attributes['noticeListIcon'] );
		}

		if ( isset( $attributes['noticeListItemsAmazon'] ) && ! empty( $attributes['noticeListItemsAmazon'] ) ) {
			$attributes['noticeListItems'] = $attributes['noticeListItemsAmazon'];
		}

		echo wp_kses_post( $this->render_template( $attributes ) );
	}

	public function render_template( array $attributes ): string {
		$attributes = $this->parse_attributes( $attributes );

		extract( $attributes );

		if ( is_array( $noticeListItems ) && count( $noticeListItems ) === 1 && isset( $noticeListItems[0]['list'] ) && has_shortcode( $noticeListItems[0]['list'], 'affiliatex-product' ) ) {
			$noticeListItems = json_decode( do_shortcode( $noticeListItems[0]['list'] ), true );
		}

		$wrapper_class           = isset( $attributes['wrapper_class'] ) ? $attributes['wrapper_class'] : '';
		$wrapper_attributes_args = array(
			'class' => 'affx-notice-wrapper ' . $wrapper_class,
			'id'    => "affiliatex-notice-style-$block_id",
		);

		// Check if called from  Elementor.
		if ( self::IS_ELEMENTOR ) {
			$wrapper_attributes = AffiliateX_Helpers::array_to_attributes( $wrapper_attributes_args );
		} else {
			$wrapper_attributes = get_block_wrapper_attributes( $wrapper_attributes_args );
		}

		$titleTag1 = AffiliateX_Helpers::validate_tag( $titleTag1, 'h2' );

		$titleHideClass   = AffiliateX_Helpers::get_responsive_hide_classes( $attributes['titleHideOn'] ?? null );
		$contentHideClass = AffiliateX_Helpers::get_responsive_hide_classes( $attributes['contentHideOn'] ?? null );

		if ( $noticeContentType === 'list' || $noticeContentType === 'amazon' ) {
			$listTag = $noticeListType === 'unordered' ? 'ul' : 'ol';
			$list    = AffiliateX_Helpers::render_list(
				array(
					'listType'      => $noticeListType,
					'unorderedType' => $noticeunorderedType,
					'listItems'     => $noticeListItems,
					'iconName'      => $noticeListIcon['value'] ?? '',
				)
			);
		}

		ob_start();
		include $this->get_template_path();

		return ob_get_clean();
	}
}
