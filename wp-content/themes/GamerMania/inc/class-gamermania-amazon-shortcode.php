<?php
/**
 * GamerMania Amazon Shortcode Integration
 *
 * Registers shortcodes to easily embed beautifully designed Amazon product cards
 * in blog posts, news, or pages.
 *
 * Usage:
 * [amazon_product asin="B0CN9M7QDG"]
 *
 * @package GamerMania
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class GamerMania_Amazon_Shortcode {

	/**
	 * Initialize shortcode registrations.
	 */
	public static function init() {
		add_shortcode( 'amazon_product', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'amazon_box', array( __CLASS__, 'render_shortcode' ) ); // Alias for convenience
	}

	/**
	 * Render shortcode content.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public static function render_shortcode( $atts ) {
		// Parse attributes
		$args = shortcode_atts(
			array(
				'asin'     => '',
				'template' => 'card',
			),
			$atts
		);

		$asin = trim( strtoupper( $args['asin'] ) );

		if ( empty( $asin ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				return '<div class="amazon-shortcode-warning" style="background:#fee2e2; border:1px solid #fecaca; color:#991b1b; padding:15px; border-radius:8px; font-size:13px; font-family:sans-serif; margin:15px 0;">' . 
					sprintf( __( '<strong>Erro do Shortcode Amazon:</strong> Nenhum ASIN foi fornecido. Use %s.', 'gamermania' ), '<code>[amazon_product asin="B0CN9M7QDG"]</code>' ) . 
					'</div>';
			}
			return '';
		}

		// Fetch item data (uses WordPress Transients caching internally)
		$product = GamerMania_Amazon_API::get_item( $asin );

		if ( is_wp_error( $product ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				return '<div class="amazon-shortcode-warning" style="background:#fee2e2; border:1px solid #fecaca; color:#991b1b; padding:15px; border-radius:8px; font-size:13px; font-family:sans-serif; margin:15px 0;">' . 
					sprintf( __( '<strong>Erro da API Amazon (%s):</strong> %s', 'gamermania' ), esc_html( $product->get_error_code() ), esc_html( $product->get_error_message() ) ) . 
					'</div>';
			}
			return '<!-- Amazon Product Shortcode Error: ' . esc_html( $product->get_error_message() ) . ' -->';
		}

		// Load the product card template securely and return buffer content
		ob_start();
		
		// Look for template in the child theme first, fallback to parent theme
		$template_path = locate_template( 'templates/amazon-product-card.php' );
		if ( ! $template_path ) {
			$template_path = get_template_directory() . '/templates/amazon-product-card.php';
		}

		if ( file_exists( $template_path ) ) {
			include $template_path;
		} else {
			// Fallback rendering in case template is deleted
			self::render_fallback_card( $product );
		}

		return ob_get_clean();
	}

	/**
	 * Minimal fallback render output in case template file isn't found.
	 *
	 * @param array $product Simplified product array.
	 */
	private static function render_fallback_card( $product ) {
		?>
		<div style="background:#111827; border:1px solid #1f2937; border-radius:8px; padding:20px; color:#fff; display:flex; gap:15px; margin:20px 0;">
			<?php if ( ! empty( $product['image_url'] ) ) : ?>
				<img src="<?php echo esc_url( $product['image_url'] ); ?>" style="max-height:100px; object-fit:contain;" alt="" />
			<?php endif; ?>
			<div>
				<h4 style="margin:0 0 10px 0;"><a href="<?php echo esc_url( $product['url'] ); ?>" style="color:#00f0ff; text-decoration:none;" target="_blank"><?php echo esc_html( $product['title'] ); ?></a></h4>
				<p style="margin:0; font-weight:bold; color:#10b981;"><?php echo esc_html( $product['price'] ); ?></p>
				<a href="<?php echo esc_url( $product['url'] ); ?>" style="display:inline-block; margin-top:10px; background:#0072ce; color:#fff; padding:6px 12px; border-radius:4px; text-decoration:none; font-size:12px;" target="_blank">Comprar na Amazon</a>
			</div>
		</div>
		<?php
	}
}
