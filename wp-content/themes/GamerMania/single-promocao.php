<?php
/**
 * The template for displaying single promotions (deals)
 *
 * @package GamerMania
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>

<div class="post-layout-container">

	<!-- Left Main Content Column -->
	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			// Retrieve custom fields
			$preco_antigo  = get_post_meta( get_the_ID(), '_preco_antigo', true );
			$preco_novo    = get_post_meta( get_the_ID(), '_preco_novo', true );
			$link_afiliado = get_post_meta( get_the_ID(), '_link_afiliado', true );

			if ( empty( $link_afiliado ) ) {
				$link_afiliado = get_permalink();
			}

			// Calculate discount percentage
			$discount_percentage = gamermania_calculate_discount( $preco_antigo, $preco_novo );
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-article' ); ?>>
				
				<header class="post-header">
					<div class="post-meta">
						<span class="post-category" style="background-color:rgba(0, 240, 255, 0.1); color:var(--color-ps-cyan);"><?php esc_html_e( 'Promoção Ativa', 'gamermania' ); ?></span>
						<span class="post-date"><i class="fa-solid fa-calendar-days" style="margin-right: 5px;"></i> <?php echo get_the_date(); ?></span>
					</div>
					<h1 class="post-title-single"><?php the_title(); ?></h1>
				</header>

				<!-- Promotion Showcase Box -->
				<div class="promotion-layout">
					<!-- Game Cover Column -->
					<div class="promotion-gallery">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large' ); ?>
						<?php else : ?>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/images/game-placeholder.jpg' ); ?>" alt="<?php the_title_attribute(); ?>" />
						<?php endif; ?>
					</div>

					<!-- Pricing & Call to Action Column -->
					<div class="promotion-info">
						<span class="promotion-badge"><?php esc_html_e( 'Oferta PlayStation 5', 'gamermania' ); ?></span>
						<h2 style="font-size: 1.5rem; margin-bottom: 20px; font-weight: 700; color: #fff;"><?php the_title(); ?></h2>
						
						<div class="promotion-pricing-box">
							<?php if ( $discount_percentage > 0 ) : ?>
								<div class="promotion-discount-calc">-<?php echo esc_html( $discount_percentage ); ?>%</div>
							<?php endif; ?>

							<div class="promotion-prices">
								<?php if ( ! empty( $preco_antigo ) ) : ?>
									<span class="promotion-price-old">De R$ <?php echo esc_html( $preco_antigo ); ?></span>
								<?php endif; ?>
								
								<span class="promotion-price-new">
									<span style="font-size: 1.2rem; font-weight: 600; color: var(--color-text-secondary);">R$</span> 
									<?php echo esc_html( ! empty( $preco_novo ) ? $preco_novo : 'N/A' ); ?>
								</span>
							</div>
						</div>

						<a href="<?php echo esc_url( $link_afiliado ); ?>" class="btn-deal promotion-btn-action" target="_blank" rel="nofollow noopener">
							<i class="fa-solid fa-cart-shopping" style="margin-right: 10px;"></i>
							<?php esc_html_e( 'Ir para a Oferta (Link Afiliado)', 'gamermania' ); ?>
						</a>

						<p style="font-size: 0.8rem; color: var(--color-text-muted); margin-top: 15px; text-align: left;">
							*<?php esc_html_e( 'Os preços podem variar de acordo com o estoque das lojas parceiras.', 'gamermania' ); ?>
						</p>
					</div>
				</div>

				<!-- Deal Description / Details -->
				<div class="post-content">
					<h2 style="font-size:1.5rem; margin-top:0; border-left: 4px solid var(--color-ps-cyan); padding-left: 15px; margin-bottom:20px;"><?php esc_html_e( 'Sobre esta promoção e o jogo', 'gamermania' ); ?></h2>
					<?php
					the_content();
					?>
				</div>

				<footer class="post-footer" style="margin-top:40px; padding-top:20px; border-top:1px solid var(--color-border); display:flex; justify-content:space-between; align-items:center;">
					<div class="post-tags" style="font-family:var(--font-heading); font-size:0.9rem;">
						<?php the_tags( '<span class="tag-title" style="color:var(--color-text-muted); margin-right:8px;"><i class="fa-solid fa-tags"></i> Tags:</span>', ', ', '' ); ?>
					</div>
				</footer>

			</article>

			<!-- Navigation -->
			<nav class="navigation post-navigation" aria-label="<?php esc_attr_e( 'Navegação de Promoções', 'gamermania' ); ?>" style="margin-top: 30px;">
				<div class="nav-links" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
					<?php
					$prev_post = get_previous_post();
					$next_post = get_next_post();

					if ( $prev_post ) :
						?>
						<div class="nav-previous" style="background-color: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--border-radius-md); padding: 15px;">
							<span style="display:block; font-size:0.75rem; color:var(--color-text-muted); text-transform:uppercase; font-family:var(--font-heading); font-weight:600; margin-bottom:5px;"><i class="fa-solid fa-arrow-left"></i> <?php esc_html_e( 'Anterior', 'gamermania' ); ?></span>
							<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" style="color:var(--color-text-primary); font-weight:600; font-size:0.95rem; line-height:1.3; display:block;"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></a>
						</div>
						<?php
					endif;

					if ( $next_post ) :
						?>
						<div class="nav-next" style="background-color: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--border-radius-md); padding: 15px; text-align: right;">
							<span style="display:block; font-size:0.75rem; color:var(--color-text-muted); text-transform:uppercase; font-family:var(--font-heading); font-weight:600; margin-bottom:5px;"><?php esc_html_e( 'Próxima', 'gamermania' ); ?> <i class="fa-solid fa-arrow-right"></i></span>
							<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" style="color:var(--color-text-primary); font-weight:600; font-size:0.95rem; line-height:1.3; display:block;"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></a>
						</div>
						<?php
					endif;
					?>
				</div>
			</nav>

		<?php
		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

	<!-- Right Sidebar Column -->
	<?php get_sidebar(); ?>

</div><!-- .post-layout-container -->

<?php
get_footer();
?>
