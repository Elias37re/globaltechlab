<?php
/**
 * GamerMania Amazon PAAPI 5.0 API Client
 *
 * Handles connecting to Amazon Product Advertising API 5.0, signing requests with AWS Signature V4,
 * and caching results via WordPress Transients.
 *
 * @package GamerMania
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class GamerMania_Amazon_API {

	/**
	 * Available marketplaces and their configuration.
	 *
	 * @return array
	 */
	public static function get_marketplaces() {
		return array(
			'com.br' => array(
				'name'        => __( 'Brasil', 'gamermania' ),
				'host'        => 'webservices.amazon.com.br',
				'region'      => 'us-east-1',
				'marketplace' => 'www.amazon.com.br',
			),
			'com'    => array(
				'name'        => __( 'Estados Unidos (USA)', 'gamermania' ),
				'host'        => 'webservices.amazon.com',
				'region'      => 'us-east-1',
				'marketplace' => 'www.amazon.com',
			),
			'co.uk'  => array(
				'name'        => __( 'Reino Unido (UK)', 'gamermania' ),
				'host'        => 'webservices.amazon.co.uk',
				'region'      => 'eu-west-1',
				'marketplace' => 'www.amazon.co.uk',
			),
			'es'     => array(
				'name'        => __( 'Espanha', 'gamermania' ),
				'host'        => 'webservices.amazon.es',
				'region'      => 'eu-west-1',
				'marketplace' => 'www.amazon.es',
			),
			'de'     => array(
				'name'        => __( 'Alemanha', 'gamermania' ),
				'host'        => 'webservices.amazon.de',
				'region'      => 'eu-west-1',
				'marketplace' => 'www.amazon.de',
			),
			'it'     => array(
				'name'        => __( 'Itália', 'gamermania' ),
				'host'        => 'webservices.amazon.it',
				'region'      => 'eu-west-1',
				'marketplace' => 'www.amazon.it',
			),
			'fr'     => array(
				'name'        => __( 'França', 'gamermania' ),
				'host'        => 'webservices.amazon.fr',
				'region'      => 'eu-west-1',
				'marketplace' => 'www.amazon.fr',
			),
		);
	}

	/**
	 * Retrieve saved settings.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$defaults = array(
			'access_key'    => '',
			'secret_key'    => '',
			'associate_tag' => '',
			'marketplace'   => 'com.br',
			'cache_expiry'  => 86400, // 24 hours in seconds
		);

		$settings = get_option( 'gamermania_amazon_settings', array() );
		return wp_parse_args( $settings, $defaults );
	}

	/**
	 * Formulates and executes signed requests to PAAPI 5.0.
	 *
	 * @param string $action Payload Action (e.g. 'GetItems', 'SearchItems').
	 * @param array  $payload_params Request body parameters specific to the action.
	 * @return array|WP_Error Response array or WP_Error object.
	 */
	public static function request( $action, $payload_params = array() ) {
		$settings = self::get_settings();

		// Validate credentials
		if ( empty( $settings['access_key'] ) || empty( $settings['secret_key'] ) || empty( $settings['associate_tag'] ) ) {
			return new WP_Error( 'missing_credentials', __( 'Credenciais da Amazon PAAPI 5.0 não configuradas.', 'gamermania' ) );
		}

		$marketplaces = self::get_marketplaces();
		$mp_code      = $settings['marketplace'];

		if ( ! isset( $marketplaces[ $mp_code ] ) ) {
			$mp_code = 'com.br';
		}

		$mp_config = $marketplaces[ $mp_code ];
		$host      = $mp_config['host'];
		$region    = $mp_config['region'];
		$market    = $mp_config['marketplace'];

		// Prepare standard payload fields
		$payload = array(
			'PartnerTag'  => $settings['associate_tag'],
			'PartnerType' => 'Associates',
			'Marketplace' => $market,
		);

		// Merge action-specific parameters
		$payload = array_merge( $payload, $payload_params );
		$payload_json = wp_json_encode( $payload );

		// Sign request using AWS Signature Version 4
		$amz_date = gmdate( 'Ymd\THis\Z' );
		$date     = gmdate( 'Ymd' );

		$headers = array(
			'content-encoding' => 'amz-1.0',
			'content-type'     => 'application/json; charset=utf-8',
			'host'             => $host,
			'x-amz-date'       => $amz_date,
			'x-amz-target'     => 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.' . $action,
		);

		// Canonical URI and Query String (POST request)
		$canonical_uri = '/paapi5/' . strtolower( $action );
		$canonical_query = '';

		// Sort headers alphabetically
		ksort( $headers );

		// Build canonical headers string
		$canonical_headers = '';
		foreach ( $headers as $key => $val ) {
			$canonical_headers .= $key . ':' . trim( $val ) . "\n";
		}

		// List of signed headers
		$signed_headers = implode( ';', array_keys( $headers ) );

		// Hashed payload
		$hashed_payload = hash( 'sha256', $payload_json );

		// Combine to form Canonical Request
		$canonical_request = implode( "\n", array(
			'POST',
			$canonical_uri,
			$canonical_query,
			$canonical_headers,
			$signed_headers,
			$hashed_payload,
		) );

		// Hash the Canonical Request
		$hashed_canonical_request = hash( 'sha256', $canonical_request );

		// Build Credential Scope
		$credential_scope = implode( '/', array(
			$date,
			$region,
			'ProductAdvertisingAPI',
			'aws4_request',
		) );

		// Build String to Sign
		$string_to_sign = implode( "\n", array(
			'AWS4-HMAC-SHA256',
			$amz_date,
			$credential_scope,
			$hashed_canonical_request,
		) );

		// Derive Signature Key
		$k_date    = hash_hmac( 'sha256', $date, 'AWS4' . $settings['secret_key'], true );
		$k_region  = hash_hmac( 'sha256', $region, $k_date, true );
		$k_service = hash_hmac( 'sha256', 'ProductAdvertisingAPI', $k_region, true );
		$k_signing = hash_hmac( 'sha256', 'aws4_request', $k_service, true );

		// Compute Signature
		$signature = hash_hmac( 'sha256', $string_to_sign, $k_signing );

		// Add Authorization to headers
		$headers['authorization'] = 'AWS4-HMAC-SHA256 Credential=' . $settings['access_key'] . '/' . $credential_scope . ', SignedHeaders=' . $signed_headers . ', Signature=' . $signature;

		// Convert headers key-value to request format
		$request_headers = array();
		foreach ( $headers as $k => $v ) {
			// Convert headers to proper casing for WordPress wp_safe_remote_post compatibility
			$request_headers[ $k ] = $v;
		}

		$api_url = 'https://' . $host . $canonical_uri;

		// Perform remote request
		$response = wp_safe_remote_post(
			$api_url,
			array(
				'headers' => $request_headers,
				'body'    => $payload_json,
				'timeout' => 15,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		$data          = json_decode( $response_body, true );

		if ( 200 !== $response_code ) {
			$error_message = isset( $data['Errors'][0]['Message'] ) ? $data['Errors'][0]['Message'] : __( 'Erro de comunicação desconhecido na API da Amazon.', 'gamermania' );
			$error_code    = isset( $data['Errors'][0]['Code'] ) ? $data['Errors'][0]['Code'] : 'api_error_code_' . $response_code;
			return new WP_Error( $error_code, $error_message, $data );
		}

		return $data;
	}

	/**
	 * Fetch a single product details by ASIN (with Transient Caching).
	 *
	 * @param string $asin Amazon Standard Identification Number.
	 * @param bool   $bypass_cache If true, skips checking or saving to transients.
	 * @return array|WP_Error
	 */
	public static function get_item( $asin, $bypass_cache = false ) {
		$asin = trim( strtoupper( $asin ) );
		if ( empty( $asin ) ) {
			return new WP_Error( 'empty_asin', __( 'Por favor, forneça um ASIN válido.', 'gamermania' ) );
		}

		$settings = self::get_settings();
		$transient_name = 'gamermania_amazon_asin_' . $asin;

		// Read from cache if not bypassed
		if ( ! $bypass_cache && $settings['cache_expiry'] > 0 ) {
			$cached = get_transient( $transient_name );
			if ( false !== $cached ) {
				return $cached;
			}
		}

		// Standard resources needed for promotions
		$resources = array(
			'Images.Primary.Large',
			'ItemInfo.Title',
			'ItemInfo.Features',
			'Offers.Listings.Price',
			'Offers.Listings.SavingBasis',
		);

		$params = array(
			'ItemIds'   => array( $asin ),
			'Resources' => $resources,
		);

		$result = self::request( 'GetItems', $params );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Validate response contains items
		if ( empty( $result['ItemsResult']['Items'] ) ) {
			return new WP_Error( 'product_not_found', __( 'Produto não encontrado na Amazon. Verifique o ASIN e as configurações de país.', 'gamermania' ) );
		}

		$item = $result['ItemsResult']['Items'][0];

		// Parse product data to simplify usage
		$parsed_data = self::parse_api_item( $item );

		// Cache data
		if ( ! $bypass_cache && $settings['cache_expiry'] > 0 && ! is_wp_error( $parsed_data ) ) {
			set_transient( $transient_name, $parsed_data, $settings['cache_expiry'] );
		}

		return $parsed_data;
	}

	/**
	 * Helper function to parse raw item data from PAAPI 5.0 into simplified associative array.
	 *
	 * @param array $item Raw item array.
	 * @return array
	 */
	private static function parse_api_item( $item ) {
		$parsed = array(
			'asin'          => isset( $item['ASIN'] ) ? $item['ASIN'] : '',
			'url'           => isset( $item['DetailPageURL'] ) ? $item['DetailPageURL'] : '',
			'title'         => '',
			'image_url'     => '',
			'price_raw'     => 0,
			'price'         => '',
			'old_price_raw' => 0,
			'old_price'     => '',
			'savings'       => '',
			'discount_pct'  => 0,
			'features'      => array(),
		);

		// Parse Title
		if ( isset( $item['ItemInfo']['Title']['DisplayValue'] ) ) {
			$parsed['title'] = $item['ItemInfo']['Title']['DisplayValue'];
		}

		// Parse Image
		if ( isset( $item['Images']['Primary']['Large']['URL'] ) ) {
			$parsed['image_url'] = $item['Images']['Primary']['Large']['URL'];
		}

		// Parse Features / Bullet Points
		if ( isset( $item['ItemInfo']['Features']['DisplayValues'] ) ) {
			$parsed['features'] = $item['ItemInfo']['Features']['DisplayValues'];
		}

		// Parse Price details
		if ( isset( $item['Offers']['Listings'][0] ) ) {
			$listing = $item['Offers']['Listings'][0];

			// New/Current Price
			if ( isset( $listing['Price']['Amount'] ) ) {
				$parsed['price_raw'] = (float) $listing['Price']['Amount'];
				$parsed['price']     = isset( $listing['Price']['DisplayAmount'] ) ? $listing['Price']['DisplayAmount'] : '';
				// Strip currency symbol if needed or formatted for WP fields
				// PAAPI DisplayAmount typically returns "R$ 250,00" or "$25.00"
			}

			// Saving Basis / Old Price
			if ( isset( $listing['SavingBasis']['Amount'] ) ) {
				$parsed['old_price_raw'] = (float) $listing['SavingBasis']['Amount'];
				$parsed['old_price']     = isset( $listing['SavingBasis']['DisplayAmount'] ) ? $listing['SavingBasis']['DisplayAmount'] : '';
			} elseif ( isset( $listing['Price']['Savings']['Amount'] ) && $listing['Price']['Savings']['Amount'] > 0 ) {
				// Fallback to calculation
				$savings_amt = (float) $listing['Price']['Savings']['Amount'];
				$parsed['old_price_raw'] = $parsed['price_raw'] + $savings_amt;
				// Format simple string
				$parsed['old_price']     = 'R$ ' . number_format( $parsed['old_price_raw'], 2, ',', '.' );
			}

			// Discount Percentage
			if ( $parsed['old_price_raw'] > 0 && $parsed['price_raw'] > 0 && $parsed['old_price_raw'] > $parsed['price_raw'] ) {
				$diff = $parsed['old_price_raw'] - $parsed['price_raw'];
				$parsed['discount_pct'] = round( ( $diff / $parsed['old_price_raw'] ) * 100 );
			}
		}

		return $parsed;
	}
}
