<?php defined( 'ABSPATH' ) || exit; if ( $edContent ) : ?>
	<div class="affx-single-product-content<?php echo ! empty( $edReadMore ) ? ' affx-readmore' : ''; ?> <?php echo esc_attr( $contentHideClass ?? '' ); ?>"<?php echo wp_kses_post( $readmore_attrs ); ?>>
		<?php if ( $productContentType === 'list' || $productContentType === 'amazon' ) : ?>
			<?php echo wp_kses_post( $list ); ?>
		<?php elseif ( $productContentType === 'paragraph' ) : ?>
			<p class="affiliatex-content"><?php echo wp_kses_post( $productContent ); ?></p>
		<?php endif; ?>
		<?php echo wp_kses_post( $readmore_btn ); ?>
	</div>
<?php endif; ?>
