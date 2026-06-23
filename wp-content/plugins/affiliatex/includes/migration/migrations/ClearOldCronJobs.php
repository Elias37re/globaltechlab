<?php

namespace AffiliateX\Migration\Migrations;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Migration\Migration;

/**
 * Migration to clear old WP-Cron jobs before switching to Action Scheduler.
 *
 * @package AffiliateX\Migration\Migrations
 */
class ClearOldCronJobs extends Migration {
	/**
	 * The version this migration targets.
	 */
	protected static function get_version() {
		return '1.4.0';
	}

	/**
	 * Run the migration logic.
	 */
	protected static function run() {
		wp_clear_scheduled_hook( 'affiliatex_sync_amazon_products' );
		wp_clear_scheduled_hook( 'affiliatex_sync_product_listings' );
		wp_clear_scheduled_hook( 'affiliatex_cleanup_product_listings' );
	}
}
