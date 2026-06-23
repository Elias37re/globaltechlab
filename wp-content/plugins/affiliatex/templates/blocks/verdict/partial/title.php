<?php
defined( 'ABSPATH' ) || exit;
?>
<<?php echo esc_attr( $verdictTitleTag ); ?> class="verdict-title<?php echo '' !== ( $titleHideClass ?? '' ) ? ' ' . esc_attr( $titleHideClass ) : ''; ?>"><?php echo esc_html( $verdictTitle ); ?></<?php echo esc_attr( $verdictTitleTag ); ?>>