<?php

/**
 * Plugin Name:     AffiliateX – Amazon Affiliate Plugin
 * Plugin URI:      https://affiliatexblocks.com
 * Description:     AffiliateX is the best WordPress Amazon Affiliate Plugin. Create professional affiliate websites with customizable WordPress Amazon Affiliate Blocks.
 * Author:          AffiliateX
 * Author URI:      https://affiliatexblocks.com
 * Text Domain:     affiliatex
 * Domain Path:     /languages
 * Version:         2.2.1
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package         AffiliateX
 */
use AffiliateX\AffiliateX;
defined( 'ABSPATH' ) || exit;
if ( function_exists( 'affiliatex_fs' ) ) {
    affiliatex_fs()->set_basename( false, __FILE__ );
} else {
    if ( !function_exists( 'affiliatex_fs' ) ) {
        /**
         * Create a helper function for easy SDK access.
         *
         * @return object
         */
        function affiliatex_fs() {
            global $affiliatex_fs;
            if ( !isset( $affiliatex_fs ) ) {
                // Include Freemius SDK.
                require_once __DIR__ . '/vendor/freemius/wordpress-sdk/start.php';
                $affiliatex_fs = fs_dynamic_init( array(
                    'id'               => '15886',
                    'slug'             => 'affiliatex',
                    'premium_slug'     => 'affiliatex-pro',
                    'type'             => 'plugin',
                    'public_key'       => 'pk_76dcb91998e6cb52401be629fea6f',
                    'is_premium'       => false,
                    'premium_suffix'   => 'pro',
                    'has_addons'       => false,
                    'has_paid_plans'   => true,
                    'menu'             => array(
                        'slug'    => 'affiliatex_blocks',
                        'support' => false,
                        'contact' => true,
                    ),
                    'is_live'          => true,
                    'is_org_compliant' => true,
                ) );
            }
            return $affiliatex_fs;
        }

        // Init Freemius.
        affiliatex_fs();
        // Signal that SDK was initiated.
        do_action( 'affiliatex_fs_loaded' );
    }
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/vendor/woocommerce/action-scheduler/action-scheduler.php';
    if ( !defined( 'AFFILIATEX_PLUGIN_FILE' ) ) {
        define( 'AFFILIATEX_PLUGIN_FILE', __FILE__ );
    }
    if ( !defined( 'AFFILIATEX_PLUGIN_DIR' ) ) {
        define( 'AFFILIATEX_PLUGIN_DIR', __DIR__ );
    }
    if ( !defined( 'AFFILIATEX_PLUGIN_URL' ) ) {
        define( 'AFFILIATEX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
    }
    if ( !defined( 'AFFILIATEX_VERSION' ) ) {
        define( 'AFFILIATEX_VERSION', '2.2.1' );
    }
    if ( !defined( 'AFFILIATEX_EXTERNAL_API_ENDPOINT' ) ) {
        define( 'AFFILIATEX_EXTERNAL_API_ENDPOINT', 'https://affiliatexblocks.com' );
    }
    /**
     * Init function
     */
    function affiliatex_init() {
        return AffiliateX::instance();
    }

    $GLOBALS['AffiliateX'] = affiliatex_init();
    // Invokes all functions attached to the 'affiliatex_free_loaded' hook
    do_action( 'affiliatex_free_loaded' );
}