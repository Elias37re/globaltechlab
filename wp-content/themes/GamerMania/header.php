<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package GamerMania
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary" style="display:none;"><?php esc_html_e( 'Pular para o conteúdo', 'gamermania' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container header-container">
			<div class="site-logo">
				<?php
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<i class="fa-solid fa-gamepad" style="color: var(--color-ps-cyan); margin-right: 8px; text-shadow: 0 0 8px rgba(0, 240, 255, 0.6);"></i>
						Gamer<span>Mania</span>
					</a>
					<?php
				endif;
				?>
			</div>

			<button id="masthead-toggle" class="menu-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Alternar Menu', 'gamermania' ); ?>">
				<i class="fa-solid fa-bars"></i>
			</button>

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Navegação Principal', 'gamermania' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-primary',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => 'gamermania_default_menu',
					)
				);
				?>
			</nav>
		</div>
	</header>


	<div id="content" class="site-content">
		<div class="container">
