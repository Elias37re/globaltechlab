<?php defined( 'ABSPATH' ) || exit;
use AffiliateX\Helpers\AffiliateX_Helpers;
?>
<div class="affx-pdt-img-wrapper">
	<?php
	echo wp_kses_post( AffiliateX_Helpers::affiliatex_get_media_image_html( $imageId, $imageUrl, $imageAlt ) );
	?>
</div>
