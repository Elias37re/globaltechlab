<?php
defined( 'ABSPATH' ) || exit;
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="<?php echo esc_attr( $classes ); ?>" <?php echo ( $ctaLayout === 'layoutOne' && ! empty( $inlineImageWrapperStyles ) ) ? esc_html( $inlineImageWrapperStyles ) : ''; ?>>
		<div class="content-wrapper">
			<div class="content-wrap">
				<<?php echo esc_attr( $ctaTitleTag ); ?> class="affliatex-cta-title<?php echo '' !== ( $titleHideClass ?? '' ) ? ' ' . esc_attr( $titleHideClass ) : ''; ?>">
					<?php echo wp_kses_post( $ctaTitle ); ?>
				</<?php echo esc_attr( $ctaTitleTag ); ?>>
				<?php if ( ! empty( $ctaContent ) ) : ?>
					<p class="affliatex-cta-content<?php echo '' !== ( $contentHideClass ?? '' ) ? ' ' . esc_attr( $contentHideClass ) : ''; ?>"><?php echo wp_kses_post( $ctaContent ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $edButtons ) : ?>
				<div class="button-wrapper cta-btn-<?php echo esc_attr( $ctaButtonAlignment ); ?><?php echo '' !== ( $buttonsHideClass ?? '' ) ? ' ' . esc_attr( $buttonsHideClass ) : ''; ?>">
					<?php echo wp_kses_post( $content ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $ctaLayout === 'layoutTwo' ) : ?>
			<div class="image-wrapper<?php echo '' !== ( $imageHideClass ?? '' ) ? ' ' . esc_attr( $imageHideClass ) : ''; ?>" <?php echo esc_html( $inlineImageWrapperStyles ); ?>></div>
		<?php endif; ?>
	</div>
</div>
