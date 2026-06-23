<?php

namespace AffiliateX\Analytics;

use AffiliateX\Helpers\QueryBuilder;

defined( 'ABSPATH' ) || exit;

/**
 * Analytics data cleanup handler
 *
 * @package AffiliateX
 */
class AnalyticsCleanup {

	public function __construct() {
		add_action( 'affiliatex_cleanup_analytics', array( $this, 'cleanup' ) );
	}

	/**
	 * Schedule the daily cleanup event if not already scheduled
	 *
	 * @return void
	 */
	public function schedule(): void {
		if ( ! wp_next_scheduled( 'affiliatex_cleanup_analytics' ) ) {
			wp_schedule_event( time(), 'daily', 'affiliatex_cleanup_analytics' );
		}
	}

	/**
	 * Delete analytics records older than the configured retention period
	 *
	 * @return void
	 */
	public function cleanup(): void {
		$days = absint( get_option( 'affiliatex_analytics_retention_days', 90 ) );

		QueryBuilder::table( 'clicks' )
			->where_raw( 'created_at < DATE_SUB(NOW(), INTERVAL ' . $days . ' DAY)' )
			->delete_where();
	}
}
