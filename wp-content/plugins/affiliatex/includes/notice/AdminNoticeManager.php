<?php
/**
 * Admin notice manager for AffiliateX
 *
 * @package AffiliateX\Notice
 */

namespace AffiliateX\Notice;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class AdminNoticeManager
 *
 * @package AffiliateX\Notice
 */
class AdminNoticeManager {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize hooks
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'hide_non_affiliatex_notices' ) );
	}

	/**
	 * Hide non-AffiliateX notices in plugin admin pages
	 *
	 * @return void
	 */
	public function hide_non_affiliatex_notices() {
		$screen = get_current_screen();

		// Return early if not on AffiliateX admin page
		if ( ! $screen || false === strpos( $screen->base, 'affiliatex' ) ) {
			return;
		}

		$custom_css = '
			.wrap > .notice,
			.wrap > div.updated,
			.wrap > div.error,
			.wrap > div.warning,
			#wpbody-content > .notice,
			#wpbody-content > div.updated,
			#wpbody-content > div.error,
			#wpbody-content > div.warning {
				display: none !important;
			}
    	';

		wp_add_inline_style( 'affiliatex-admin-css', $custom_css );
	}
}
