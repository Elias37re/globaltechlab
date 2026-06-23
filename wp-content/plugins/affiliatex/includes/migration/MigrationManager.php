<?php

namespace AffiliateX\Migration;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Migration\Migrations\RemoveNoticeLayout3;
use AffiliateX\Migration\Migrations\ClearOldCronJobs;

/**
 * Migration Manager Class
 *
 * Provides migration utilities for AffiliateX
 *
 * @since 1.3.8
 * @package AffiliateX\Migration
 */
class MigrationManager {

	public function __construct() {
		add_action( 'init', array( $this, 'run_migrations' ) );
	}

	/**
	 * Run migrations sequentially.
	 */
	public function run_migrations() {
		try {
			$migrations = array(
				RemoveNoticeLayout3::class,
				ClearOldCronJobs::class,
			);

			foreach ( $migrations as $migration_class ) {
				if ( class_exists( $migration_class ) ) {
					$migration_class::execute();
				}
			}
		} catch ( \Exception $e ) {
			error_log( 'AffiliateX Migration failed: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}
}

new MigrationManager();
