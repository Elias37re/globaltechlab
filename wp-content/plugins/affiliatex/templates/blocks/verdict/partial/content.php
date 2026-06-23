<?php
defined( 'ABSPATH' ) || exit;
?>
<p class="verdict-content<?php echo '' !== ( $contentHideClass ?? '' ) ? ' ' . esc_attr( $contentHideClass ) : ''; ?>"><?php echo wp_kses_post( $verdictContent ); ?></p>