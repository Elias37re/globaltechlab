<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="affx-connect-all-wrapper">
	<button class="affx-action-button affx-action-button__connect affx-connect-inspector-btn" label="Connect">
		<div class="affx-action-button__icon-wrapper">
			<span class="affx-action-button__secondary-icon">
				<?php if ( affiliatex_fs()->is_premium() ) { ?>
					<svg class='affx-swap-icon' width="10" height="10" viewBox="0 0 448 512"><path fill="#0034ff" d="M438.6 150.6c12.5-12.5 12.5-32.8 0-45.3l-96-96c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.7 96 32 96C14.3 96 0 110.3 0 128s14.3 32 32 32l306.7 0-41.4 41.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l96-96zm-333.3 352c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 416 416 416c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0 41.4-41.4c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-96 96c-12.5 12.5-12.5 32.8 0 45.3l96 96z" /></svg>
				<?php } else { ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="affx-lock-icon" width="10" height="10" viewBox="0 0 26 26"><g fill="#0034ff" fill-rule="nonzero"><path d="M16,0c-2.21094,0 -4.12109,0.91797 -5.3125,2.40625c-1.19141,1.48828 -1.6875,3.41797 -1.6875,5.5v1.09375h3v-1.09375c0,-1.57812 0.39063,-2.82031 1.03125,-3.625c0.64063,-0.80469 1.51172,-1.28125 2.96875,-1.28125c1.46094,0 2.32813,0.44922 2.96875,1.25c0.64063,0.80078 1.03125,2.05859 1.03125,3.65625v1.09375h3v-1.09375c0,-2.09375 -0.52734,-4.04297 -1.71875,-5.53125c-1.19141,-1.48828 -3.07422,-2.375 -5.28125,-2.375zM9,10c-1.65625,0 -3,1.34375 -3,3v10c0,1.65625 1.34375,3 3,3h14c1.65625,0 3,-1.34375 3,-3v-10c0,-1.65625 -1.34375,-3 -3,-3zM16,15c1.10547,0 2,0.89453 2,2c0,0.73828 -0.40234,1.37109 -1,1.71875v2.28125c0,0.55078 -0.44922,1 -1,1c-0.55078,0 -1,-0.44922 -1,-1v-2.28125c-0.59766,-0.34766 -1,-0.98047 -1,-1.71875c0,-1.10547 0.89453,-2 2,-2z"></path></g></svg>
				<?php } ?>
			</span>
			<svg class="affx-connect-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="#0034ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke="#0034ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</div>
		<span class="affx-connect-all-button-text"><?php esc_html_e( 'Connect', 'affiliatex' ); ?></span>
	</button>
</div>
