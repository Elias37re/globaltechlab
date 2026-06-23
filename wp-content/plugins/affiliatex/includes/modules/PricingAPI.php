<?php

namespace AffiliateX\Modules;

defined( 'ABSPATH' ) || exit;

/**
 * Upgrade / Pricing REST API — serves live Freemius plan + pricing data.
 *
 * @package AffiliateX
 */
class PricingAPI {
	use \AffiliateX\Helpers\ResponseHelper;

	const CACHE_KEY = 'affiliatex_upgrade_pricing';
	const CACHE_TTL = HOUR_IN_SECONDS;

	public function register_routes(): void {
		register_rest_route(
			'affiliatex/v1/upgrade',
			'/pricing',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_pricing' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	public function get_pricing(): void {
		$cached = get_transient( self::CACHE_KEY );
		if ( false !== $cached ) {
			$this->send_json_plain_success( $cached );
			return;
		}

		$data = $this->fetch_pricing();
		if ( empty( $data['tiers'] ) ) {
			$this->send_json_plain_error( array( 'message' => esc_html__( 'Unable to load pricing right now.', 'affiliatex' ) ) );
			return;
		}

		set_transient( self::CACHE_KEY, $data, self::CACHE_TTL );
		$this->send_json_plain_success( $data );
	}

	/**
	 * Fetch and normalise plan + pricing data from the Freemius plugin scope.
	 *
	 * @return array
	 */
	private function fetch_pricing(): array {
		$result = affiliatex_fs()->get_api_plugin_scope()->get( 'pricing.json?is_enriched=true' );

		$plans = ( is_object( $result ) && isset( $result->plans ) && is_array( $result->plans ) ) ? $result->plans : array();

		$paid_plan = $this->find_paid_plan( $plans );
		if ( null === $paid_plan ) {
			return array();
		}

		$currency = 'usd';
		$tiers    = array();

		foreach ( (array) $paid_plan->pricing as $pricing ) {
			$annual   = $this->price( $pricing, 'annual_price' );
			$monthly  = $this->price( $pricing, 'monthly_price' );
			$lifetime = $this->price( $pricing, 'lifetime_price' );

			if ( null === $annual && null === $monthly && null === $lifetime ) {
				continue;
			}

			if ( ! empty( $pricing->currency ) ) {
				$currency = $pricing->currency;
			}

			$licenses = isset( $pricing->licenses ) && is_numeric( $pricing->licenses ) ? (int) $pricing->licenses : 0;

			$tiers[] = array(
				'pricing_id' => isset( $pricing->id ) ? (int) $pricing->id : 0,
				'licenses'   => $licenses,
				'annual'     => $annual,
				'monthly'    => $monthly,
				'lifetime'   => $lifetime,
			);
		}

		usort(
			$tiers,
			function ( $a, $b ) {
				// Unlimited (0) goes last.
				if ( 0 === $a['licenses'] ) {
					return 1;
				}
				if ( 0 === $b['licenses'] ) {
					return -1;
				}
				return $a['licenses'] - $b['licenses'];
			}
		);

		return array(
			'plan_id'  => (int) $paid_plan->id,
			'currency' => $currency,
			'tiers'    => $tiers,
		);
	}

	/**
	 * Pick the main paid plan (the one carrying the most priced tiers).
	 *
	 * @param array $plans
	 * @return object|null
	 */
	private function find_paid_plan( array $plans ) {
		$best       = null;
		$best_count = 0;

		foreach ( $plans as $plan ) {
			if ( empty( $plan->pricing ) || ! is_array( $plan->pricing ) ) {
				continue;
			}

			$count = 0;
			foreach ( $plan->pricing as $pricing ) {
				if (
					null !== $this->price( $pricing, 'annual_price' ) ||
					null !== $this->price( $pricing, 'monthly_price' ) ||
					null !== $this->price( $pricing, 'lifetime_price' )
				) {
					++$count;
				}
			}

			if ( $count > $best_count ) {
				$best_count = $count;
				$best       = $plan;
			}
		}

		return $best;
	}

	/**
	 * Read a positive numeric price off a pricing object, else null.
	 *
	 * @param object $pricing
	 * @param string $key
	 * @return float|null
	 */
	private function price( $pricing, string $key ) {
		if ( isset( $pricing->{$key} ) && is_numeric( $pricing->{$key} ) && $pricing->{$key} > 0 ) {
			return (float) $pricing->{$key};
		}
		return null;
	}
}
