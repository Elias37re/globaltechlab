<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package GamerMania
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<aside id="secondary" class="sidebar-area">

	<!-- AdSense Banner 300x600 Widget -->
	<section class="widget widget_adsense">
		<h2 class="widget-title">
			<?php esc_html_e( 'Publicidade', 'gamermania' ); ?>
			<span style="font-size: 0.65rem; color: var(--color-ps-cyan); font-weight: 500; text-transform: lowercase; letter-spacing: 0.5px; opacity: 0.8;"><?php esc_html_e( 'patrocinado', 'gamermania' ); ?></span>
		</h2>
		<div class="adsense-container" style="display: flex; justify-content: center; align-items: center; padding: 10px 0;">
			<div class="adsense-300x600">
				<div class="adsense-icon">
					<i class="fa-solid fa-rectangle-ad"></i>
				</div>
				<div class="adsense-text-title"><?php esc_html_e( 'Espaço AdSense', 'gamermania' ); ?></div>
				<div class="adsense-text-size">300 x 600 px</div>
				<p style="font-size: 0.75rem; text-align: center; margin-top: 15px; padding: 0 20px; line-height: 1.4; color: var(--color-text-muted);">
					<?php esc_html_e( 'Este bloco está otimizado para banners verticais do Google AdSense.', 'gamermania' ); ?>
				</p>
			</div>
		</div>
	</section>

	<?php
	// Dynamic Sidebar Fallback
	if ( is_active_sidebar( 'sidebar-primary' ) ) :
		dynamic_sidebar( 'sidebar-primary' );
	else :
		// Default widgets if none are added in Admin panel
		?>
		<section class="widget widget_recent_entries">
			<h2 class="widget-title"><?php esc_html_e( 'Promoções Recentes', 'gamermania' ); ?></h2>
			<?php
			$recent_deals = new WP_Query(
				array(
					'post_type'      => 'promocao',
					'posts_per_page' => 4,
					'post_status'    => 'publish',
				)
			);

			if ( $recent_deals->have_posts() ) :
				echo '<ul style="list-style:none; padding:0; margin:0;">';
				while ( $recent_deals->have_posts() ) :
					$recent_deals->the_post();
					$preco_novo = get_post_meta( get_the_ID(), '_preco_novo', true );
					?>
					<li style="margin-bottom:15px; display:flex; gap:12px; align-items:center;">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" style="width: 50px; height: 65px; flex-shrink: 0; border-radius: var(--border-radius-sm); overflow: hidden; border:1px solid var(--color-border);">
								<?php the_post_thumbnail( array( 50, 65 ), array( 'style' => 'width:100%; height:100%; object-fit:cover;' ) ); ?>
							</a>
						<?php endif; ?>
						<div>
							<a href="<?php the_permalink(); ?>" style="color:var(--color-text-primary); font-weight:600; font-size:0.95rem; line-height:1.3; display:block; margin-bottom:4px;"><?php the_title(); ?></a>
							<span style="color:var(--color-ps-cyan); font-weight:700; font-size:0.9rem;">R$ <?php echo esc_html( ! empty( $preco_novo ) ? $preco_novo : 'N/A' ); ?></span>
						</div>
					</li>
					<?php
				endwhile;
				echo '</ul>';
				wp_reset_postdata();
			else :
				?>
				<p><?php esc_html_e( 'Nenhuma promoção recente cadastrada.', 'gamermania' ); ?></p>
			<?php
			endif;
			?>
		</section>

		<section class="widget widget_newsletter" style="background: linear-gradient(135deg, rgba(0, 114, 206, 0.05) 0%, transparent 100%);">
			<h2 class="widget-title"><?php esc_html_e( 'Alerta de Ofertas', 'gamermania' ); ?></h2>
			<p style="font-size:0.9rem; margin-bottom:15px; color:var(--color-text-secondary);"><?php esc_html_e( 'Inscreva-se para receber as melhores ofertas de PS5 diretamente no seu e-mail.', 'gamermania' ); ?></p>
			<form onsubmit="event.preventDefault(); alert('Inscrição simulada!');" style="display:flex; flex-direction:column; gap:10px;">
				<input type="email" placeholder="Seu e-mail de gamer" required style="background-color:#080c14; border:1px solid var(--color-border); border-radius:var(--border-radius-sm); padding:10px 15px; color:#fff; font-family:var(--font-body); font-size:0.9rem; outline:none; transition:var(--transition-smooth);" onfocus="this.style.borderColor='var(--color-ps-cyan)';" onblur="this.style.borderColor='var(--color-border)';" />
				<button type="submit" class="btn-deal" style="padding:10px; font-size:0.9rem;"><?php esc_html_e( 'Inscrever-se', 'gamermania' ); ?></button>
			</form>
		</section>
	<?php
	endif;
	?>

</aside>
