<?php

namespace AffiliateX\Modules;

defined( 'ABSPATH' ) || exit;

use WP_REST_Request;

/**
 * Modules REST API
 *
 * @package AffiliateX
 */
class ModulesAPI {
	use \AffiliateX\Helpers\ResponseHelper;

	/**
	 * Default module states
	 *
	 * @var array
	 */
	/**
	 * Get default module states.
	 * Pro modules default to true when pro is active.
	 *
	 * @return array
	 */
	private static function get_defaults(): array {
		$is_pro = function_exists( 'affiliatex_fs' ) && affiliatex_fs()->is__premium_only();

		return array(
			'analytics'           => true,
			'api_integration'     => $is_pro,
			'email_reports'       => $is_pro,
			'broken_link_checker' => true,
		);
	}

	/**
	 * Register REST routes
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			'affiliatex/v1/modules',
			'/status',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_status' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'affiliatex/v1/modules',
			'/toggle',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'toggle_module' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * Get all module statuses
	 *
	 * @return void
	 */
	public function get_status(): void {
		$saved    = get_option( 'affiliatex_modules', array() );
		$statuses = wp_parse_args( $saved, $this->get_defaults() );
		$is_pro   = function_exists( 'affiliatex_fs' ) && affiliatex_fs()->is__premium_only();

		if ( ! $is_pro ) {
			$statuses['api_integration'] = false;
			$statuses['email_reports']   = false;
		}

		$this->send_json_plain_success(
			array(
				'modules' => $statuses,
				'is_pro'  => $is_pro,
			)
		);
	}

	/**
	 * Toggle a module on/off
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function toggle_module( WP_REST_Request $request ): void {
		$body    = $request->get_json_params();
		$module  = isset( $body['module'] ) ? sanitize_text_field( $body['module'] ) : '';
		$enabled = isset( $body['enabled'] ) ? (bool) $body['enabled'] : false;

		if ( empty( $module ) || ! array_key_exists( $module, $this->get_defaults() ) ) {
			$this->send_json_error( __( 'Invalid module', 'affiliatex' ) );
			return;
		}

		$saved            = get_option( 'affiliatex_modules', array() );
		$saved[ $module ] = $enabled;
		update_option( 'affiliatex_modules', $saved );

		$this->send_json_plain_success(
			array(
				'module'  => $module,
				'enabled' => $enabled,
			)
		);
	}

	/**
	 * Check if a module is enabled
	 *
	 * @param string $module_id Module identifier.
	 * @return bool
	 */
	public static function is_enabled( string $module_id ): bool {
		$saved    = get_option( 'affiliatex_modules', array() );
		$statuses = wp_parse_args( $saved, self::get_defaults() );
		$is_pro   = function_exists( 'affiliatex_fs' ) && affiliatex_fs()->is__premium_only();

		$pro_only = array( 'api_integration', 'email_reports' );
		if ( ! $is_pro && in_array( $module_id, $pro_only, true ) ) {
			return false;
		}

		return ! empty( $statuses[ $module_id ] );
	}
}
