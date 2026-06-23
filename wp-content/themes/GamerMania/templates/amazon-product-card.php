<?php
/**
 * GamerMania Amazon Product Card Template
 *
 * Displays a styled Amazon product card on the frontend.
 * Included by the shortcode class.
 *
 * @package GamerMania
 *
 * @var array $product Product data parsed from PAAPI 5.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( empty( $product ) || is_wp_error( $product ) ) {
	return;
}

$discount_pct = isset( $product['discount_pct'] ) ? intval( $product['discount_pct'] ) : 0;
$price        = isset( $product['price'] ) ? $product['price'] : '';
$old_price    = isset( $product['old_price'] ) ? $product['old_price'] : '';
$title        = isset( $product['title'] ) ? esc_html( $product['title'] ) : '';
$url          = isset( $product['url'] ) ? esc_url( $product['url'] ) : '#';
$image_url    = isset( $product['image_url'] ) ? esc_url( $product['image_url'] ) : '';
$asin         = isset( $product['asin'] ) ? esc_attr( $product['asin'] ) : '';
$features     = isset( $product['features'] ) ? $product['features'] : array();
?>

<div class="gamermania-amazon-card-wrapper" data-asin="<?php echo $asin; ?>">
	<!-- Top/Left Image Section -->
	<div class="amazon-card-image-box">
		<?php if ( $discount_pct > 0 ) : ?>
			<div class="amazon-card-discount-badge">-<?php echo $discount_pct; ?>%</div>
		<?php endif; ?>
		
		<?php if ( ! empty( $image_url ) ) : ?>
			<img class="amazon-card-img" src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>" loading="lazy" />
		<?php else : ?>
			<div class="amazon-card-placeholder">
				<i class="fa-solid fa-gamepad"></i>
			</div>
		<?php endif; ?>
	</div>

	<!-- Content Details Section -->
	<div class="amazon-card-details">
		<div class="amazon-card-tag">
			<i class="fa-brands fa-amazon" style="color: #ff9900; margin-right: 5px;"></i>
			<span><?php esc_html_e( 'OFERTA NA AMAZON', 'gamermania' ); ?></span>
		</div>
		
		<h3 class="amazon-card-title">
			<a href="<?php echo $url; ?>" target="_blank" rel="nofollow noopener"><?php echo $title; ?></a>
		</h3>

		<?php if ( ! empty( $features ) && is_array( $features ) ) : ?>
			<ul class="amazon-card-features-list">
				<?php 
				// Limit features to maximum 2 for card neatness
				$features_limit = array_slice( $features, 0, 2 );
				foreach ( $features_limit as $feature ) : 
				?>
					<li><i class="fa-solid fa-circle-check" style="margin-right: 6px; color: var(--color-ps-cyan, #00f0ff);"></i> <?php echo esc_html( $feature ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<div class="amazon-card-pricing-row">
			<div class="amazon-card-prices">
				<?php if ( ! empty( $old_price ) ) : ?>
					<del class="amazon-card-price-old"><?php echo $old_price; ?></del>
				<?php endif; ?>
				
				<div class="amazon-card-price-new">
					<span class="price-curr"><?php esc_html_e( 'Por apenas', 'gamermania' ); ?></span>
					<span class="price-val"><?php echo ! empty( $price ) ? $price : 'N/A'; ?></span>
				</div>
			</div>

			<div class="amazon-card-action">
				<a href="<?php echo $url; ?>" class="amazon-card-btn" target="_blank" rel="nofollow noopener">
					<i class="fa-solid fa-cart-shopping"></i>
					<span><?php esc_html_e( 'Comprar Agora', 'gamermania' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</div>

<style>
/* Inline styling specific to the frontend product card (scoped to avoid template styling dependency issues) */
.gamermania-amazon-card-wrapper {
	display: flex;
	background: var(--color-bg-card, #111827);
	border: 1px solid var(--color-border, #1f2937);
	border-radius: 12px;
	overflow: hidden;
	margin: 30px 0;
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	position: relative;
	font-family: var(--font-body, 'Inter', sans-serif);
}

.gamermania-amazon-card-wrapper:hover {
	transform: translateY(-4px);
	border-color: var(--color-ps-cyan, #00f0ff);
	box-shadow: 0 15px 35px rgba(0, 240, 255, 0.15);
}

.amazon-card-image-box {
	width: 200px;
	min-height: 220px;
	padding: 20px;
	background-color: #fff;
	position: relative;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
	border-right: 1px solid var(--color-border, #1f2937);
}

.amazon-card-img {
	max-width: 100%;
	max-height: 180px;
	object-fit: contain;
	transition: transform 0.3s ease;
}

.gamermania-amazon-card-wrapper:hover .amazon-card-img {
	transform: scale(1.05);
}

.amazon-card-discount-badge {
	position: absolute;
	top: 15px;
	left: 15px;
	background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
	color: #fff;
	font-weight: 800;
	font-size: 0.85rem;
	padding: 4px 10px;
	border-radius: 6px;
	z-index: 2;
	box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
}

.amazon-card-placeholder {
	font-size: 3rem;
	color: #94a3b8;
}

.amazon-card-details {
	padding: 25px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	flex-grow: 1;
}

.amazon-card-tag {
	font-family: var(--font-heading, 'Outfit', sans-serif);
	font-size: 0.75rem;
	font-weight: 700;
	letter-spacing: 1.5px;
	color: var(--color-ps-cyan, #00f0ff);
	margin-bottom: 8px;
	display: flex;
	align-items: center;
}

.amazon-card-title {
	margin: 0 0 12px 0;
	font-size: 1.25rem;
	font-weight: 700;
	line-height: 1.4;
	font-family: var(--font-heading, 'Outfit', sans-serif);
}

.amazon-card-title a {
	color: var(--color-text-primary, #f8fafc);
	text-decoration: none;
	transition: color 0.2s ease;
}

.amazon-card-title a:hover {
	color: var(--color-ps-cyan, #00f0ff);
}

.amazon-card-features-list {
	margin: 0 0 20px 0;
	padding: 0;
	list-style: none;
	font-size: 0.85rem;
	color: var(--color-text-secondary, #94a3b8);
}

.amazon-card-features-list li {
	margin-bottom: 6px;
	display: flex;
	align-items: flex-start;
	line-height: 1.4;
}

.amazon-card-pricing-row {
	display: flex;
	justify-content: space-between;
	align-items: center;
	border-top: 1px solid var(--color-border, #1f2937);
	padding-top: 18px;
	margin-top: auto;
	gap: 15px;
}

.amazon-card-prices {
	display: flex;
	flex-direction: column;
}

.amazon-card-price-old {
	font-size: 0.85rem;
	color: var(--color-text-muted, #64748b);
	margin-bottom: 2px;
}

.amazon-card-price-new {
	display: flex;
	flex-direction: column;
}

.amazon-card-price-new .price-curr {
	font-size: 0.75rem;
	color: var(--color-text-secondary, #94a3b8);
	text-transform: uppercase;
	font-weight: 500;
	margin-bottom: 2px;
}

.amazon-card-price-new .price-val {
	font-size: 1.4rem;
	font-weight: 800;
	color: #10b981;
	line-height: 1;
}

.amazon-card-btn {
	background: linear-gradient(135deg, #0072ce 0%, #00f0ff 100%);
	color: #fff !important;
	border: none;
	font-family: var(--font-heading, 'Outfit', sans-serif);
	font-weight: 700;
	font-size: 0.95rem;
	padding: 12px 24px;
	border-radius: 8px;
	text-decoration: none;
	display: inline-flex;
	align-items: center;
	gap: 8px;
	box-shadow: 0 4px 15px rgba(0, 114, 206, 0.3);
	transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
	cursor: pointer;
}

.amazon-card-btn:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(0, 240, 255, 0.45);
}

.amazon-card-btn:active {
	transform: translateY(0);
}

/* Responsive adjustment */
@media (max-width: 640px) {
	.gamermania-amazon-card-wrapper {
		flex-direction: column;
	}
	
	.amazon-card-image-box {
		width: 100%;
		min-height: 200px;
		border-right: none;
		border-bottom: 1px solid var(--color-border, #1f2937);
	}
	
	.amazon-card-pricing-row {
		flex-direction: column;
		align-items: flex-start;
		gap: 15px;
	}
	
	.amazon-card-action {
		width: 100%;
	}
	
	.amazon-card-btn {
		width: 100%;
		justify-content: center;
	}
}
</style>
