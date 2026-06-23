<?php
defined( 'ABSPATH' ) || exit;
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="affx-pros-cons-inner-wrapper <?php echo esc_attr( $inner_wrapper_classes ); ?>">
		<div class="affx-pros-inner">
			<div class="pros-icon-title-wrap<?php echo '' !== ( $prosTitleHideClass ?? '' ) ? ' ' . esc_attr( $prosTitleHideClass ) : ''; ?>">
				<div class="affiliatex-block-pros">
					<?php if ( $prosIconStatus ) : ?>
						<i class="<?php echo esc_attr( $prosListIcon['value'] ?? '' ); ?>"></i>
					<?php endif; ?>
					<<?php echo wp_kses_post( $titleTag1 ); ?> class="affiliatex-title"><?php echo wp_kses_post( $prosTitle ); ?></<?php echo wp_kses_post( $titleTag1 ); ?>>
				</div>
			</div>
			<div class="affiliatex-pros<?php echo '' !== ( $prosContentHideClass ?? '' ) ? ' ' . esc_attr( $prosContentHideClass ) : ''; ?>">
				<?php if ( $prosContentType === 'list' || $prosContentType === 'amazon' ) : ?>
					<?php echo wp_kses_post( $prosList ); ?>
				<?php else : ?>
					<p class="affiliatex-content"><?php echo wp_kses_post( $prosContent ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<div class="affx-cons-inner">
			<div class="cons-icon-title-wrap<?php echo '' !== ( $consTitleHideClass ?? '' ) ? ' ' . esc_attr( $consTitleHideClass ) : ''; ?>">
				<div class="affiliatex-block-cons">
					<?php if ( $consIconStatus ) : ?>
						<i class="<?php echo esc_attr( $consListIcon['value'] ?? '' ); ?>"></i>
					<?php endif; ?>
					<<?php echo wp_kses_post( $titleTag1 ); ?> class="affiliatex-title"><?php echo wp_kses_post( $consTitle ); ?></<?php echo wp_kses_post( $titleTag1 ); ?>>
				</div>
			</div>
			<div class="affiliatex-cons<?php echo '' !== ( $consContentHideClass ?? '' ) ? ' ' . esc_attr( $consContentHideClass ) : ''; ?>">
				<?php if ( $consContentType === 'list' || $consContentType === 'amazon' ) : ?>
					<?php echo wp_kses_post( $consList ); ?>
				<?php else : ?>
					<p class="affiliatex-content"><?php echo wp_kses_post( $consContent ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
