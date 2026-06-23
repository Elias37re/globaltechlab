<?php

namespace AffiliateX\Migration\Migrations;

defined( 'ABSPATH' ) || exit;

use AffiliateX\Migration\Migration;
use AffiliateX\Helpers\MigrationHelper;

/**
 * Migration to remove Layout 3 from Notice blocks and widgets..
 *
 * @package AffiliateX\Migration\Migrations
 */
class RemoveNoticeLayout3 extends Migration {
	/**
	 * The version this migration targets.
	 */
	protected static function get_version() {
		return '1.3.8';
	}


	/**
	 * Run the migration logic.
	 */
	protected static function run() {
		self::migrate_gutenberg();
		self::migrate_elementor();
	}


	/**
	 * Migration for Gutenberg blocks.
	 */
	private static function migrate_gutenberg() {
		$posts = MigrationHelper::get_gutenberg_posts( 'affiliatex/notice' );

		$migration_configs = array(
			array(
				'block_name'     => 'affiliatex/notice',
				'attribute_name' => 'layoutStyle',
				'old_value'      => 'layout-type-3',
				'new_value'      => 'layout-type-2',
			),
		);

		MigrationHelper::migrate_gutenberg_posts_block_attributes( $posts, $migration_configs );
	}


	/**
	 * Migration for Elementor widgets.
	 */
	private static function migrate_elementor() {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		$meta_query = array(
			array(
				'key'     => '_elementor_data',
				'value'   => 'affiliatex-notice',
				'compare' => 'LIKE',
			),
		);

		$posts = MigrationHelper::get_elementor_posts( $meta_query );

		$migration_configs = array(
			array(
				'widget_type'    => 'affiliatex-notice',
				'attribute_name' => 'layoutStyle',
				'old_value'      => 'layout-type-3',
				'new_value'      => 'layout-type-2',
			),
		);

		MigrationHelper::migrate_elementor_posts_widget_attributes( $posts, $migration_configs );
	}
}
