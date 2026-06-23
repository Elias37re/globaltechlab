<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package GamerMania
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>

<!-- Hero Banner Section -->
<div class="hero-section" style="margin-bottom: 50px; text-align: center; padding: 60px 20px; background: linear-gradient(135deg, rgba(0, 114, 206, 0.15) 0%, rgba(0, 240, 255, 0.05) 100%), url('<?php echo esc_url( get_template_directory_uri() . '/images/hero-banner.png' ); ?>') no-repeat center center; background-size: cover; border: 1px solid var(--color-border); border-radius: var(--border-radius-lg); position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.6); min-height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
	<div class="hero-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(8, 12, 20, 0.75); z-index: 1;"></div>
	<div style="position: relative; z-index: 2;">
		<h1 class="hero-title" style="font-size: 2.8rem; font-weight: 800; margin-bottom: 15px; background: linear-gradient(90deg, #ffffff, #88c9ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-shadow: 0 4px 12px rgba(0,0,0,0.3);"><?php esc_html_e( 'Promoções e Notícias de PS5', 'gamermania' ); ?></h1>
		<p class="hero-subtitle" style="font-size: 1.2rem; color: var(--color-text-secondary); max-width: 600px; margin: 0 auto 25px; line-height: 1.5;"><?php esc_html_e( 'As melhores ofertas de jogos para PlayStation 5 atualizadas diariamente. Economize e jogue mais!', 'gamermania' ); ?></p>
		<div class="hero-badge" style="display: inline-block; background-color: rgba(0, 240, 255, 0.1); color: var(--color-ps-cyan); border: 1px solid var(--color-ps-cyan); padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; text-shadow: 0 0 5px rgba(0,240,255,0.4);"><?php esc_html_e( 'Foco em PS5 & Acessórios', 'gamermania' ); ?></div>
	</div>
</div>

<main id="primary" class="site-main">

	<!-- 1. DEALS SECTION (10 MELHORES PROMOÇÕES) -->
	<section class="promotions-section" style="margin-bottom: 60px;">
		<h2 class="section-title"><?php esc_html_e( '10 Melhores Promoções da Semana', 'gamermania' ); ?></h2>

		<?php
		// Query the latest 10 promotions
		$deals_args = array(
			'post_type'      => 'promocao',
			'posts_per_page' => 10,
			'post_status'    => 'publish',
		);

		$deals_query = new WP_Query( $deals_args );

		if ( $deals_query->have_posts() ) :
			?>
			<div class="deals-grid">
				<?php
				while ( $deals_query->have_posts() ) :
					$deals_query->the_post();

					// Get meta fields
					$preco_antigo  = get_post_meta( get_the_ID(), '_preco_antigo', true );
					$preco_novo    = get_post_meta( get_the_ID(), '_preco_novo', true );
					$link_afiliado = get_post_meta( get_the_ID(), '_link_afiliado', true );

					// Default affiliate fallback link if empty
					if ( empty( $link_afiliado ) ) {
						$link_afiliado = get_permalink();
					}

					// Calculate discount percentage
					$discount_percentage = gamermania_calculate_discount( $preco_antigo, $preco_novo );
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'deal-card' ); ?>>
						<div class="deal-image-container">
							<?php if ( $discount_percentage > 0 ) : ?>
								<div class="discount-badge">-<?php echo esc_html( $discount_percentage ); ?>%</div>
							<?php endif; ?>
							
							<a href="<?php the_permalink(); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large' ); ?>
								<?php else : ?>
									<!-- Fallback game placeholder image -->
									<img src="<?php echo esc_url( get_template_directory_uri() . '/images/game-placeholder.jpg' ); ?>" alt="<?php the_title_attribute(); ?>" />
								<?php endif; ?>
							</a>
						</div>

						<div class="deal-content">
							<h3 class="deal-title">
								<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
							</h3>

							<div class="deal-prices">
								<?php if ( ! empty( $preco_antigo ) ) : ?>
									<span class="price-old">De R$ <?php echo esc_html( $preco_antigo ); ?></span>
								<?php endif; ?>
								
								<div class="price-new-wrapper">
									<span class="price-currency">Por</span>
									<span class="price-new">R$ <?php echo esc_html( ! empty( $preco_novo ) ? $preco_novo : 'N/A' ); ?></span>
								</div>
							</div>

							<a href="<?php echo esc_url( $link_afiliado ); ?>" class="btn-deal" target="_blank" rel="nofollow noopener">
								<i class="fa-solid fa-cart-shopping" style="margin-right: 8px;"></i>
								<?php esc_html_e( 'Ir para a Oferta', 'gamermania' ); ?>
							</a>
						</div>
					</article>
				<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<div class="no-deals-found" style="background-color: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--border-radius-lg); padding: 40px; text-align: center; color: var(--color-text-muted);">
				<i class="fa-solid fa-gamepad" style="font-size: 3rem; color: var(--color-ps-blue); margin-bottom: 15px; opacity: 0.5;"></i>
				<p><?php esc_html_e( 'Nenhuma promoção cadastrada no momento. Volte mais tarde!', 'gamermania' ); ?></p>
			</div>
		<?php endif; ?>
	</section>

	<!-- 2. NEWS/BLOG SECTION (ÚLTIMAS NOTÍCIAS) -->
	<section class="news-section" style="margin-top: 40px; border-top: 1px solid var(--color-border); padding-top: 50px;">
		<h2 class="section-title"><?php esc_html_e( 'Últimas Notícias do PlayStation 5', 'gamermania' ); ?></h2>

		<?php
		// Query normal blog posts, excluding promotions
		$news_args = array(
			'post_type'      => 'post',
			'posts_per_page' => 6,
			'post_status'    => 'publish',
		);

		$news_query = new WP_Query( $news_args );

		if ( $news_query->have_posts() ) :
			?>
			<div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
				<?php
				while ( $news_query->have_posts() ) :
					$news_query->the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'news-card' ); ?> style="background-color: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--border-radius-lg); overflow: hidden; display: flex; flex-direction: column; transition: var(--transition-smooth);">
						<div class="news-image-container" style="aspect-ratio: 16/9; overflow: hidden; position: relative;">
							<a href="<?php the_permalink(); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%; height:100%; object-fit:cover; transition:var(--transition-smooth);' ) ); ?>
								<?php else : ?>
									<div style="width: 100%; height: 100%; background: linear-gradient(135deg, #1e293b, #0f172a); display: flex; align-items: center; justify-content: center; color: var(--color-text-muted);">
										<i class="fa-solid fa-newspaper" style="font-size: 2.5rem;"></i>
									</div>
								<?php endif; ?>
							</a>
						</div>
						<div class="news-content" style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;">
							<div class="news-meta" style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 10px; font-family: var(--font-heading);">
								<span><i class="fa-solid fa-calendar-days" style="margin-right: 5px;"></i> <?php echo get_the_date(); ?></span>
							</div>
							<h3 class="news-title" style="font-size: 1.2rem; font-weight: 700; margin-bottom: 12px; line-height: 1.4;">
								<a href="<?php the_permalink(); ?>" style="color: var(--color-text-primary);"><?php the_title(); ?></a>
							</h3>
							<div class="news-excerpt" style="font-size: 0.95rem; color: var(--color-text-secondary); margin-bottom: 15px; flex-grow: 1;">
								<?php the_excerpt(); ?>
							</div>
							<a href="<?php the_permalink(); ?>" class="read-more-link" style="color: var(--color-ps-cyan); font-family: var(--font-heading); font-weight: 600; font-size: 0.9rem; align-self: flex-start; display: flex; align-items: center; gap: 5px;">
								<?php esc_html_e( 'Ler Notícia', 'gamermania' ); ?> <i class="fa-solid fa-arrow-right-long"></i>
							</a>
						</div>
					</article>
				<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<div class="no-news-found" style="background-color: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--border-radius-lg); padding: 40px; text-align: center; color: var(--color-text-muted);">
				<i class="fa-solid fa-circle-info" style="font-size: 3rem; color: var(--color-ps-blue); margin-bottom: 15px; opacity: 0.5;"></i>
				<p><?php esc_html_e( 'Nenhuma notícia publicada ainda. Fique atento às novidades!', 'gamermania' ); ?></p>
			</div>
		<?php endif; ?>
	</section>

</main>

<?php
get_footer();
?>
