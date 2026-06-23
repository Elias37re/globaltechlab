<?php

namespace AffiliateX\Modules;

defined( 'ABSPATH' ) || exit;

/**
 * Welcome / Getting Started REST API
 *
 * @package AffiliateX
 */
class WelcomeAPI {
	use \AffiliateX\Helpers\ResponseHelper;

	public function register_routes(): void {
		register_rest_route(
			'affiliatex/v1/welcome',
			'/create-starter-page',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'create_starter_page' ),
				'permission_callback' => function () {
					return current_user_can( 'publish_posts' );
				},
			)
		);
	}

	public function create_starter_page(): void {
		$post_id = wp_insert_post(
			array(
				'post_title'   => esc_html__( 'My first AffiliateX page', 'affiliatex' ),
				'post_status'  => 'draft',
				'post_type'    => 'post',
				'post_content' => wp_slash( $this->get_starter_content() ),
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$this->send_json_plain_error( array( 'message' => $post_id->get_error_message() ) );
			return;
		}

		$this->send_json_plain_success(
			array(
				'post_id'  => (int) $post_id,
				'edit_url' => admin_url( 'post.php?post=' . (int) $post_id . '&action=edit' ),
			)
		);
	}

	private function get_starter_content(): string {
		$template = <<<'HTML'
<!-- wp:affiliatex/single-product {"block_id":"__SP_ID__","productTitle":"CuttyClassic 198X","productTitleTypography":{"family":"Default","variation":"n4","size":{"desktop":"24px","mobile":"24px","tablet":"24px"},"line-height":{"desktop":"1.333","mobile":"1.333","tablet":"1.333"},"letter-spacing":{"desktop":"0em","mobile":"0em","tablet":"0em"},"text-transform":"none","text-decoration":"none"},"productTitleColor":"#ed833c","productImageType":"external","productImageExternal":"https://affiliatexblocks.com/wp-content/uploads/2024/12/retro-computer-desk-arrangement.jpg","productSubTitle":"\u003cstrong\u003eReady to jump back to the future?\u003c/strong\u003e","productIconList":{"name":"chevron-right","value":"fas fa-chevron-right"},"productContent":"Step back in time with this charming, pastel-pink Single Product template that features a delightful retro computer animation. Rounded corners and playful accent details evoke a cute 80s tech vibe, while bold headings and an eye-catching CTA ensure your product stands out.","productContentColor":"rgba(41, 41, 41, 0.84)","contentMargin":{"desktop":{"top":"0px","left":"0px","right":"0px","bottom":"39px"},"mobile":{"top":"0px","left":"0px","right":"0px","bottom":"30px"},"tablet":{"top":"0px","left":"0px","right":"0px","bottom":"30px"},"__changed":[]},"contentSpacing":{"desktop":{"top":"30px","left":"25px","right":"25px","bottom":"10px"},"mobile":{"top":"30px","left":"25px","right":"25px","bottom":"30px"},"tablet":{"top":"30px","left":"25px","right":"25px","bottom":"30px"},"__changed":[]},"productBorderWidth":{"desktop":{"top":"1px","left":"1px","right":"1px","bottom":"1px","linked":true},"mobile":{"top":"1px","left":"1px","right":"1px","bottom":"1px"},"tablet":{"top":"1px","left":"1px","right":"1px","bottom":"1px"},"__changed":[]},"productBorderRadius":{"desktop":{"top":"30px","left":"30px","right":"30px","bottom":"30px","linked":true},"mobile":{"top":"0","left":"0","right":"0","bottom":"0"},"tablet":{"top":"0","left":"0","right":"0","bottom":"0"},"__changed":[]},"productShadow":{"enable":true,"h_offset":0,"v_offset":5,"blur":"49","spread":0,"inset":false,"color":{"color":"rgba(144, 145, 148, 0.2)"},"inherit":false},"productDivider":{"style":"dashed","width":"1","color":{"color":"#E6ECF7"}},"ratings":4,"ratingStarSize":24,"edSubtitle":true,"productBgColorType":"gradient","productBgGradient":{"gradient":"linear-gradient(130deg,rgba(255,106,0,0.01) 0%,rgba(255,106,0,0.13) 100%)"},"ribbonText":"50% Discount","ribbonBGColor":"rgba(254, 5, 206, 0.32)","edRibbon":true} -->
<!-- wp:affiliatex/buttons {"block_id":"__BTN_ID__","buttonLabel":"Buy Now","buttonBGColor":"#ff8989","buttonBGHoverColor":"#fa5b5b","buttonMargin":{"desktop":{"top":"30px","left":"0px","right":"0px","bottom":"0px"},"mobile":{"top":"16px","left":"0px","right":"0px","bottom":"0px"},"tablet":{"top":"16px","left":"0px","right":"0px","bottom":"0px"},"__changed":[]},"buttonPadding":{"desktop":{"top":"px","left":"","right":"","bottom":""},"mobile":{"top":"","left":"","right":"","bottom":""},"tablet":{"top":"","left":"","right":"","bottom":""},"__changed":[]},"buttonURL":"https://affiliatexblocks.com/demo/?utm_source=playground\u0026utm_medium=editor\u0026utm_campaign=single_product","openInNewTab":true,"buttonRadius":{"desktop":{"top":"8px","left":"8px","right":"8px","bottom":"8px","linked":true},"mobile":{"top":"0","left":"0","right":"0","bottom":"0"},"tablet":{"top":"0","left":"0","right":"0","bottom":"0"},"__changed":[]},"layoutStyle":"layout-type-2","priceTextColor":"#ff8989"} /-->
<!-- /wp:affiliatex/single-product -->
HTML;

		return str_replace(
			array( '__SP_ID__', '__BTN_ID__' ),
			array( 'block-' . wp_generate_uuid4(), 'block-' . wp_generate_uuid4() ),
			$template
		);
	}
}
