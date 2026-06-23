<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-article' ); ?>>
				
				<header class="post-header">
					<div class="post-meta">
						<span class="post-category"><?php the_category( ', ' ); ?></span>
						<span class="post-date"><i class="fa-solid fa-calendar-days" style="margin-right: 5px;"></i> <?php echo get_the_date(); ?></span>
						<span class="post-author"><i class="fa-solid fa-user" style="margin-right: 5px;"></i> <?php the_author_posts_link(); ?></span>
					</div>

					<h1 class="post-title-single"><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="post-thumbnail-single">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<div class="post-content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Páginas:', 'gamermania' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>

				<footer class="post-footer" style="margin-top:40px; padding-top:20px; border-top:1px solid var(--color-border); display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:20px;">
					<div class="post-tags" style="font-family:var(--font-heading); font-size:0.9rem;">
						<?php the_tags( '<span class="tag-title" style="color:var(--color-text-muted); margin-right:8px;"><i class="fa-solid fa-tags"></i> Tags:</span>', ', ', '' ); ?>
					</div>
					
					<!-- Social Sharing buttons mockup -->
					<div class="post-share" style="display:flex; align-items:center; gap:10px;">
						<span style="font-size:0.85rem; color:var(--color-text-muted); font-family:var(--font-heading); font-weight:600; text-transform:uppercase;"><?php esc_html_e( 'Compartilhar:', 'gamermania' ); ?></span>
						<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" style="color:#1877f2; font-size:1.2rem; transition:var(--transition-smooth);" onhover="this.style.color='#fff'"><i class="fa-brands fa-facebook"></i></a>
						<a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" rel="noopener noreferrer" style="color:#1da1f2; font-size:1.2rem; transition:var(--transition-smooth);"><i class="fa-brands fa-x-twitter"></i></a>
						<a href="https://api.whatsapp.com/send?text=<?php echo urlencode( get_the_title() . ' - ' . get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" style="color:#25d366; font-size:1.2rem; transition:var(--transition-smooth);"><i class="fa-brands fa-whatsapp"></i></a>
					</div>
				</footer>

			</article>

			<!-- Post Navigation -->
			<nav class="navigation post-navigation" aria-label="<?php esc_attr_e( 'Navegação de Posts', 'gamermania' ); ?>" style="margin-top: 30px;">
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
							<span style="display:block; font-size:0.75rem; color:var(--color-text-muted); text-transform:uppercase; font-family:var(--font-heading); font-weight:600; margin-bottom:5px;"><?php esc_html_e( 'Próximo', 'gamermania' ); ?> <i class="fa-solid fa-arrow-right"></i></span>
							<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" style="color:var(--color-text-primary); font-weight:600; font-size:0.95rem; line-height:1.3; display:block;"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></a>
						</div>
						<?php
					endif;
					?>
				</div>
			</nav>

			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

	<!-- Right Sidebar Column -->
	<?php get_sidebar(); ?>

</div><!-- .post-layout-container -->

<?php
get_footer();
?>
