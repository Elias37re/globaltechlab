<?php defined( 'ABSPATH' ) || exit; if ( $edButton ) : ?>
	<div class="button-wrapper <?php echo esc_attr( $buttonDirection ); ?>-direction <?php echo esc_attr( $buttonHideClass ?? '' ); ?>"<?php echo ! empty( $useInlineButtonGap ) && is_numeric( $buttonsGap ) ? ' style="--button-gap: ' . esc_attr( $buttonsGap ) . 'px"' : ''; ?>><?php echo wp_kses_post( $content ); ?></div>
<?php endif; ?>
