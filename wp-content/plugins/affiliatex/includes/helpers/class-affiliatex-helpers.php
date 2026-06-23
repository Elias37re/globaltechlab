<?php
namespace AffiliateX\Helpers;

/**
 * Affiliatex Helpers class
 */

class AffiliateX_Helpers {

	/**
	 * Parse CSS into correct CSS syntax.
	 *
	 * @param array  $selectors The block selectors.
	 * @param string $id The selector ID.
	 * @since 0.0.1
	 */
	public static function generate_css( $selectors, $id ) {
		$styling_css = '';

		if ( empty( $selectors ) ) {
			return '';
		}

		foreach ( $selectors as $key => $value ) {

			$css = '';

			foreach ( $value as $j => $val ) {

				if ( 'font-family' === $j && 'Default' === $val ) {
					continue;
				}

				if ( ! empty( $val ) ) {
					$sanitized_val = self::sanitize_css_value( $val );
					if ( 'font-family' === $j ) {
						$css .= $j . ': "' . $sanitized_val . '";';
					} else {
						$css .= $j . ': ' . $sanitized_val . ';';
					}
				}
			}

			if ( ! empty( $css ) ) {
				$styling_css .= $id;
				$styling_css .= $key . '{';
				$styling_css .= $css . '}';
			}
		}

		return $styling_css;
	}

	/**
	 * Sanitize a CSS value to prevent XSS.
	 *
	 * @param string $value The CSS value to sanitize.
	 * @return string Sanitized CSS value.
	 * @since 1.3.9.4
	 */
	public static function sanitize_css_value( $value ) {
		if ( ! is_string( $value ) ) {
			return $value;
		}

		$value = wp_strip_all_tags( $value );
		$value = preg_replace( '/[<>"\']/', '', $value );
		$value = preg_replace( '/[\x00-\x1F\x7F]/', '', $value );

		return $value;
	}

	/**
	 * Get CSS value
	 *
	 * Syntax:
	 *
	 *  get_css_value( VALUE, UNIT );
	 *
	 * E.g.
	 *
	 *  get_css_value( VALUE, 'em' );
	 *
	 * @param string $value  CSS value.
	 * @param string $unit  CSS unit.
	 * @since x.x.x
	 */
	public static function get_css_value( $value = '', $unit = '' ) {

		if ( '' === $value ) {
			return $value;
		}

		$css_val = '';

		if ( ! empty( $value ) ) {

			$css_val = esc_attr( $value ) . $unit;
		}

		return $css_val;
	}

	public static function get_css_boxshadow( $v ) {
		if ( isset( $v['enable'] ) && $v['enable'] === true ) {
			$h_offset = isset( $v['h_offset'] ) ? $v['h_offset'] : 0;
			$v_offset = isset( $v['v_offset'] ) ? $v['v_offset'] : 5;
			$blur     = isset( $v['blur'] ) ? $v['blur'] : 20;
			$spread   = isset( $v['spread'] ) ? $v['spread'] : 0;
			$color    = isset( $v['color']['color'] ) ? $v['color']['color'] : 'rgba(210,213,218,0.2)';
			$inset    = isset( $v['inset'] ) && $v['inset'] === true ? 'inset' : '';

			return $h_offset . 'px ' . $v_offset . 'px ' . $blur . 'px ' . $spread . 'px ' . $color . $inset;
		} else {
			return 'none';
		}
	}

	public static function get_fontweight_variation( $variation ) {
		$fontType   = $variation[1];
		$fontWeight = (int) $fontType * 100;
		return $fontWeight;
	}

	public static function get_font_style( $variation ) {
		$variationType = $variation[0];
		$font          = $variationType === 'n' ? 'normal' : ( $variationType === 'i' ? 'italic' : 'Default' );
		return $font;
	}

	public static function validate_tag( $tag, $default = 'h2' ) {
		$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' );
		return in_array( $tag, $allowed_tags, true ) ? $tag : $default;
	}

	/**
	 * Render a list
	 *
	 * @param array $args {
	 *     @type string $listType           'unordered' or 'ordered'
	 *     @type string $unorderedType      'icon' or 'bullet'
	 *     @type array  $listItems          Array of list items
	 *     @type string $iconName           Optional icon class name
	 * }
	 * @return string
	 */
	public static function render_list( $args ) {
		$defaults = array(
			'listType'      => 'unordered',
			'unorderedType' => 'icon',
			'listItems'     => array(),
			'iconName'      => '',
			'wrapperClass'  => '',
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args );
		$listTag           = $listType === 'unordered' ? 'ul' : 'ol';
		$wrapperClasses    = array( 'affiliatex-list' );
		$wrapperClasses[] .= 'affiliatex-list-type-' . $listType;
		$wrapperClasses[] .= $unorderedType === 'icon' ? 'afx-icon-list' : 'bullet';

		if ( is_string( $wrapperClass ) && '' !== $wrapperClass ) {
			$wrapperClasses[] = $wrapperClass;
		}

		// Parse Amazon shortcode if listItems is a string.
		if ( is_string( $listItems ) ) {
			$listItems = affx_maybe_parse_amazon_shortcode( $listItems );
		}

		ob_start();
		include AFFILIATEX_PLUGIN_DIR . '/templates/components/list.php';
		return ob_get_clean();
	}

	/**
	 * Convert an associative array of html element attributes into string format.
	 *
	 * @param array $args   An associative array of attribute keys and values.
	 * @return string
	 */
	public static function array_to_attributes( array $args = array() ): string {
		$string = '';

		if ( is_array( $args ) && ! empty( $args ) ) {
			$string = implode(
				' ',
				array_map(
					function ( $key, $value ) {
						if ( is_array( $value ) ) {
								$value = implode( ' ', $value );
						}

						return esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
					},
					array_keys( $args ),
					$args
				)
			);
		}

		return $string;
	}

	/**
	 * This function handles both media library images and images with url
	 *
	 * @param int       $image_id      The WordPress media library image ID
	 * @param string    $image_url     The image URL
	 * @param string    $image_alt     The image alt text
	 * @param bool      $is_sitestripe Whether to show sitestripe
	 * @param string    $sitestripe    The sitestripe HTML content
	 * @param string    $size          The image size.
	 * @return string   The image HTML or empty string if no image available
	 */
	public static function affiliatex_get_media_image_html( $image_id = 0, $image_url = '', $image_alt = '', $is_sitestripe = false, $sitestripe = '', $size = 'full' ) {

		if ( $is_sitestripe && ! empty( $sitestripe ) ) {
			return $sitestripe;
		}

		if ( ! empty( $image_id ) && wp_attachment_is_image( $image_id ) ) {
			// Setting height to auto to prevent image distortion
			$image = wp_get_attachment_image( $image_id, $size, false, array( 'style' => 'height: auto;' ) );
			return $image;
		}

		if ( ! empty( $image_url ) ) {
			$fallback_url_bc = str_replace( 'app/src/images/fallback.jpg', 'src/images/fallback.jpg', $image_url );
			$processed_url   = do_shortcode( $fallback_url_bc );
			$escaped_url     = esc_url( $processed_url );

			return sprintf(
				'<img src="%s" alt="%s"/>',
				$escaped_url,
				esc_attr( $image_alt )
			);
		}

		return '';
	}

	/**
	 * Check if the current page has AffiliateX Elementor widgets.
	 *
	 * @param int|null $post_id Optional. Post ID to check. Defaults to current post.
	 * @return bool
	 */
	public static function has_elementor_widgets( $post_id = null ) {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return false;
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id || ! \Elementor\Plugin::$instance->db->is_built_with_elementor( $post_id ) ) {
			return false;
		}

		$elementor_data = get_post_meta( $post_id, '_elementor_data', true );

		return $elementor_data && (
			strpos( $elementor_data, 'affiliatex-' ) !== false ||
			strpos( $elementor_data, '"widgetType":"affx-' ) !== false
		);
	}

	/**
	 * Is_affiliatex_block - Returns true when viewing a page with AffiliateX blocks.
	 *
	 * Checks for both Gutenberg blocks.
	 *
	 * @return bool
	 */
	public static function is_affiliatex_block() {
		$affx_block =
			has_block( 'affiliatex/buttons' ) ||
			has_block( 'affiliatex/pros-and-cons' ) ||
			has_block( 'affiliatex/cta' ) ||
			has_block( 'affiliatex/notice' ) ||
			has_block( 'affiliatex/verdict' ) ||
			has_block( 'affiliatex/single-product' ) ||
			has_block( 'affiliatex/specifications' ) ||
			has_block( 'affiliatex/versus-line' ) ||
			has_block( 'affiliatex/single-product-pros-and-cons' ) ||
			has_block( 'affiliatex/product-image-button' ) ||
			has_block( 'affiliatex/single-coupon' ) ||
			has_block( 'affiliatex/coupon-grid' ) ||
			has_block( 'affiliatex/product-tabs' ) ||
			has_block( 'affiliatex/coupon-listing' ) ||
			has_block( 'affiliatex/top-products' ) ||
			has_block( 'affiliatex/versus' ) ||
			has_block( 'affiliatex/product-table' ) ||
			has_block( 'affiliatex/product-comparison' ) ||
			has_block( 'affiliatex/rating-box' ) ||
			has_block( 'affiliatex/dynamic-listing' );

		return apply_filters( 'is_affiliatex_block', $affx_block );
	}

	/**
	 * Returns true when viewing a page with AffiliateX blocks or widgets.
	 *
	 * Checks for both Gutenberg blocks and Elementor widgets.
	 *
	 * @return bool
	 */
	public static function post_has_affiliatex_items() {
		return apply_filters( 'post_has_affiliatex_items', self::is_affiliatex_block() || self::has_elementor_widgets() );
	}

	/**
	 * Generate wrapper tag and attributes for clickable blocks.
	 *
	 * @param array $attributes Array of necessary attributes: edFullBlockLink, blockUrl, blockRelNoFollow, blockRelSponsored, blockDownload, blockOpenInNewTab
	 *
	 * @since 1.3.9
	 * @return array Array with 'tag' and 'attributes' keys
	 */
	public static function get_clickable_wrapper_config( array $attributes ) {
		extract( $attributes );

		if ( ! $edFullBlockLink || empty( $blockUrl ) ) {
			return array(
				'tag'        => 'div',
				'attributes' => '',
			);
		}

		$rel_attributes = array( 'noopener' );

		if ( $blockRelNoFollow ) {
			$rel_attributes[] = 'nofollow';
		}
		if ( $blockRelSponsored ) {
			$rel_attributes[] = 'sponsored';
		}

		$attributes = sprintf(
			'href="%s"',
			esc_url( do_shortcode( $blockUrl ) )
		);

		if ( ! empty( $rel_attributes ) ) {
			$attributes .= sprintf( ' rel="%s"', esc_attr( implode( ' ', $rel_attributes ) ) );
		}

		if ( $blockOpenInNewTab ) {
			$attributes .= ' target="_blank"';
		}

		if ( $blockDownload ) {
			$attributes .= ' download';
		}

		return array(
			'tag'        => 'a',
			'attributes' => $attributes,
		);
	}

	/**
	 * Remove inline <a> tags while keeping their inner content. Used for
	 * RichText fields rendered inside a wrapper anchor, where nested anchors
	 * are invalid HTML5 and the browser parser silently breaks the layout.
	 *
	 * @param string $content
	 * @return string
	 */
	public static function strip_anchor_tags( $content ) {
		if ( ! is_string( $content ) || $content === '' ) {
			return $content;
		}
		return preg_replace( '#</?a\b[^>]*>#i', '', $content );
	}

	/**
	 * Escape HTML content while allowing SVG elements.
	 *
	 * @param string $content The content to escape.
	 * @return string Escaped content with SVG allowed.
	 */
	public static function kses( $content ) {
		$allowed_html = array_merge(
			wp_kses_allowed_html( 'post' ),
			array(
				'svg'  => array(
					'class'           => true,
					'aria-hidden'     => true,
					'aria-labelledby' => true,
					'role'            => true,
					'xmlns'           => true,
					'width'           => true,
					'height'          => true,
					'viewbox'         => true,
					'fill'            => true,
					'stroke'          => true,
					'stroke-width'    => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
				),
				'path' => array(
					'd'               => true,
					'fill'            => true,
					'stroke'          => true,
					'stroke-width'    => true,
					'stroke-linecap'  => true,
					'stroke-linejoin' => true,
				),
				'g'    => array(
					'fill'         => true,
					'stroke'       => true,
					'stroke-width' => true,
				),
			)
		);

		return wp_kses( $content, $allowed_html );
	}

	/**
	 * Generate data attributes for Read More wrapper element.
	 *
	 * @param array $config {
	 *     Configuration array for Read More attributes.
	 *
	 *     @type bool   $edReadMore         Whether Read More is enabled.
	 *     @type string $productContentType Content type: 'paragraph', 'list', or 'amazon'.
	 *     @type int    $descriptionLength  Character limit for truncation. Default 150.
	 *     @type int    $listItemCount      Item limit for list truncation. Default 3.
	 * }
	 * @return string HTML data attributes string or empty string if Read More is disabled.
	 */
	public static function get_readmore_attrs( $config ) {
		extract( $config );

		$data_attrs = '';
		if ( ! empty( $edReadMore ) ) {
			$content_type = ( $productContentType === 'list' || $productContentType === 'amazon' ) ? 'list' : 'paragraph';
			$char_limit   = $descriptionLength ?? 150;
			$item_limit   = $listItemCount ?? 3;

			$data_attrs = sprintf(
				' data-content-type="%s" data-char-limit="%d" data-item-limit="%d"',
				esc_attr( $content_type ),
				absint( $char_limit ),
				absint( $item_limit )
			);
		}

		return $data_attrs;
	}

	/**
	 * Get Read More button HTML.
	 *
	 * @param bool   $enabled       Whether read more is enabled.
	 * @param string $read_more_text Read more button text.
	 * @param string $read_less_text Read less button text.
	 * @return string Button HTML or empty string.
	 */
	public static function get_readmore_btn( $enabled, $read_more_text = 'Read more', $read_less_text = 'Read less' ) {
		if ( empty( $enabled ) ) {
			return '';
		}

		return sprintf(
			'<button type="button" class="affx-readmore-btn" data-readmore-text="%s" data-readless-text="%s">%s</button>',
			esc_attr( $read_more_text ),
			esc_attr( $read_less_text ),
			esc_html( $read_more_text )
		);
	}

	/**
	 * Resolve a responsive attribute value for a device, legacy scalars pass through.
	 *
	 * Cascade: empty tablet inherits desktop, empty mobile inherits tablet.
	 *
	 * @param mixed  $value Scalar or {desktop,tablet,mobile} object.
	 * @param string $device One of 'desktop', 'tablet', 'mobile'.
	 * @return mixed
	 */
	public static function get_responsive_value( $value, string $device = 'desktop' ) {
		if ( ! is_array( $value ) || ! array_key_exists( 'desktop', $value ) ) {
			return $value;
		}

		$desktop = $value['desktop'];
		$tablet  = self::is_empty_device_value( $value['tablet'] ?? null ) ? $desktop : $value['tablet'];
		$mobile  = self::is_empty_device_value( $value['mobile'] ?? null ) ? $tablet : $value['mobile'];

		if ( 'tablet' === $device ) {
			return $tablet;
		}

		if ( 'mobile' === $device ) {
			return $mobile;
		}

		return $desktop;
	}

	/**
	 * Whether a per-device value should inherit from the larger device.
	 *
	 * @param mixed $value Device value.
	 * @return bool
	 */
	private static function is_empty_device_value( $value ): bool {
		return null === $value || '' === $value;
	}

	/**
	 * Build affx-hide-* classes from a {desktop,tablet,mobile} visibility attribute.
	 *
	 * @param mixed $hide_on Visibility attribute value, may be missing on old content.
	 * @return string Space separated classes, empty string when nothing is hidden.
	 */
	public static function get_responsive_hide_classes( $hide_on ): string {
		if ( ! is_array( $hide_on ) ) {
			return '';
		}

		$classes = array();

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
			if ( ! empty( $hide_on[ $device ] ) && 'false' !== $hide_on[ $device ] ) {
				$classes[] = 'affx-hide-' . $device;
			}
		}

		return implode( ' ', $classes );
	}
}
