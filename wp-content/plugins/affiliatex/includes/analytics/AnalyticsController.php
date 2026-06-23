<?php

namespace AffiliateX\Analytics;

defined( 'ABSPATH' ) || exit;

/**
 * Analytics controller - initializes table, API routes, and cleanup
 *
 * @package AffiliateX
 */
class AnalyticsController {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'create_clicks_table' ) );
		add_action( 'rest_api_init', array( new AnalyticsAPI(), 'register_routes' ) );

		$cleanup = new AnalyticsCleanup();
		$cleanup->schedule();
	}

	/**
	 * Create the clicks and impressions tracking tables using dbDelta
	 *
	 * @return void
	 */
	public function create_clicks_table(): void {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$db_version_key  = 'affiliatex_clicks_db_version';
		$current_version = '1.3';

		if ( get_option( $db_version_key ) === $current_version ) {
			return;
		}

		$clicks_table      = $wpdb->prefix . 'affiliatex_clicks';
		$impressions_table = $wpdb->prefix . 'affiliatex_impressions';

		// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$clicks_exists      = (bool) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $clicks_table ) );
		$impressions_exists = (bool) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $impressions_table ) );
		// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		if ( $clicks_exists && $impressions_exists ) {
			update_option( $db_version_key, $current_version );
			return;
		}

		$clicks_sql = "CREATE TABLE {$clicks_table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			url varchar(2048) NOT NULL,
			url_hash varchar(32) NOT NULL,
			page_id bigint(20) unsigned NOT NULL,
			page_title varchar(400) NOT NULL DEFAULT '',
			block_type varchar(100) DEFAULT '',
			element_type varchar(50) DEFAULT '',
			platform varchar(40) DEFAULT '',
			product_id bigint(20) DEFAULT NULL,
			device varchar(20) DEFAULT '',
			browser varchar(50) DEFAULT '',
			os varchar(50) DEFAULT '',
			visitor_hash varchar(64) DEFAULT '',
			referrer varchar(2048) DEFAULT '',
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY url_hash (url_hash),
			KEY page_id (page_id),
			KEY platform (platform),
			KEY created_at (created_at),
			KEY visitor_hash_url (visitor_hash, url_hash)
		) {$charset_collate};";

		$impressions_sql = "CREATE TABLE {$impressions_table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			page_id bigint(20) unsigned NOT NULL,
			page_title varchar(400) NOT NULL DEFAULT '',
			block_count int NOT NULL DEFAULT 0,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY page_id (page_id),
			KEY created_at (created_at)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $clicks_sql );
		dbDelta( $impressions_sql );

		update_option( $db_version_key, $current_version );
	}
}
