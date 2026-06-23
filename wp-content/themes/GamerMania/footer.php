<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package GamerMania
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-widgets">
				<div class="footer-widget-column">
					<h3 class="footer-widget-title"><?php esc_html_e( 'Sobre o GamerMania', 'gamermania' ); ?></h3>
					<p><?php esc_html_e( 'O GamerMania é a sua principal fonte de notícias, reviews e as melhores promoções de jogos para PlayStation 5. Economize dinheiro e fique por dentro do mundo PlayStation!', 'gamermania' ); ?></p>
				</div>
				<div class="footer-widget-column">
					<h3 class="footer-widget-title"><?php esc_html_e( 'Links Úteis', 'gamermania' ); ?></h3>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-footer',
							'menu_id'        => 'footer-menu',
							'fallback_cb'    => 'gamermania_footer_menu_fallback',
						)
					);
					?>
				</div>
				<div class="footer-widget-column">
					<h3 class="footer-widget-title"><?php esc_html_e( 'Aviso de Afiliados', 'gamermania' ); ?></h3>
					<p><?php esc_html_e( 'Como afiliados, podemos receber comissões por compras qualificadas através de nossos links, sem custo adicional para você. Isso nos ajuda a manter o site no ar!', 'gamermania' ); ?></p>
				</div>
			</div>

			<div class="site-info">
				<div class="copyright">
					&copy; <?php echo esc_html( date( 'Y' ) ); ?> <strong>GamerMania</strong>. Todos os direitos reservados.
				</div>
				<div class="credits">
					<?php printf( esc_html__( 'Desenvolvido por %s', 'gamermania' ), 'Antigravity' ); ?>
				</div>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php
// Fallback for footer menu
if ( ! function_exists( 'gamermania_footer_menu_fallback' ) ) {
	function gamermania_footer_menu_fallback() {
		echo '<ul style="list-style:none; padding:0; margin:0;">';
		echo '<li style="margin-bottom:8px;"><a href="' . esc_url( home_url( '/politica-de-privacidade/' ) ) . '" style="color:var(--color-text-secondary);">' . esc_html__( 'Política de Privacidade', 'gamermania' ) . '</a></li>';
		echo '<li style="margin-bottom:8px;"><a href="' . esc_url( home_url( '/termos-de-uso/' ) ) . '" style="color:var(--color-text-secondary);">' . esc_html__( 'Termos de Uso', 'gamermania' ) . '</a></li>';
		echo '<li style="margin-bottom:8px;"><a href="' . esc_url( home_url( '/contato/' ) ) . '" style="color:var(--color-text-secondary);">' . esc_html__( 'Contato', 'gamermania' ) . '</a></li>';
		echo '</ul>';
	}
}
?>

<?php wp_footer(); ?>

</body>
</html>
