<?php

namespace AffiliateX;

defined( 'ABSPATH' ) || exit;
use AffiliateX\Platforms\PlatformConfig;
use AffiliateX\Platforms\PlatformController;
use AffiliateX\Helpers\Elementor\WidgetHelper;
use AffiliateX\Elementor\ControlsManager;
use AffiliateX\Elementor\ElementorManager;
use AffiliateX\Analytics\AnalyticsController;
use AffiliateX\Modules\ModulesAPI;
use AffiliateX\Modules\WelcomeAPI;
use AffiliateX\Modules\PricingAPI;
use AffiliateX\BrokenLinks\BrokenLinkController;
/**
 * Admin class, handles admin screen functionality
 *
 * @package AffiliateX
 */
class AffiliateXAdmin {
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
        new PlatformController();
        new AnalyticsController();
        new BrokenLinkController();
        add_action( 'rest_api_init', array(new ModulesAPI(), 'register_routes') );
        add_action( 'rest_api_init', array(new WelcomeAPI(), 'register_routes') );
        add_action( 'rest_api_init', array(new PricingAPI(), 'register_routes') );
        new Notice\AdminNoticeManager();
        new ControlsManager();
        new ElementorManager();
        affiliatex_fs()->add_filter( 'after_connect_url', array($this, 'freemius_after_action_redirect') );
        affiliatex_fs()->add_filter( 'after_skip_url', array($this, 'freemius_after_action_redirect') );
        affiliatex_fs()->add_filter( 'after_pending_connect_url', array($this, 'freemius_after_action_redirect') );
    }

    public function freemius_after_action_redirect( $url ) {
        if ( affiliatex_fs()->is_premium() ) {
            return $url;
        }
        return admin_url( 'admin.php?page=affiliatex_getting_started' );
    }

    /**
     * Init
     *
     * @return void
     */
    public function init() {
        add_action( 'enqueue_block_editor_assets', array($this, 'editor_assets') );
        add_action( 'enqueue_block_assets', array($this, 'enqueue_block_styles') );
        add_action( 'elementor/editor/after_enqueue_scripts', array($this, 'editor_assets'), 9 );
        add_filter( 'block_categories_all', array($this, 'add_block_category') );
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_scripts') );
        // Admin Script Translations
        add_action( 'admin_enqueue_scripts', array($this, 'set_script_translations'), 99999999999 );
        // Register pages
        add_action( 'admin_menu', array($this, 'add_affiliate_menu') );
        add_action( 'admin_menu', array($this, 'add_getting_started_menu'), 999 );
        add_action( 'admin_menu', array($this, 'override_pricing_page'), PHP_INT_MAX );
        add_filter( 'plugin_action_links_' . plugin_basename( AFFILIATEX_PLUGIN_FILE ), array($this, 'affiliatex_add_action_links') );
        add_filter( 'network_admin_plugin_action_links_' . plugin_basename( AFFILIATEX_PLUGIN_FILE ), array($this, 'affiliatex_add_action_links') );
    }

    /**
     * Add Block Category
     *
     * @param [type] $categories
     */
    public function add_block_category( $categories ) {
        // setup category array
        $affx_category = array(
            'slug'  => 'affiliatex',
            'title' => __( 'AffiliateX', 'affiliatex' ),
        );
        // make a new category array and insert ours at position 1
        $new_categories = array();
        $new_categories[0] = $affx_category;
        // rebuild cats array
        foreach ( $categories as $category ) {
            $new_categories[] = $category;
        }
        return $new_categories;
    }

    /**
     * Admin Enqueue Scripts
     *
     * @param string $hook Current admin page hook.
     * @return void
     */
    public function enqueue_admin_scripts( $hook ) {
        $is_upgrade_page = 'affiliatex_page_affiliatex_blocks-pricing' === $hook;
        if ( $is_upgrade_page || in_array( $hook, array('toplevel_page_affiliatex_blocks', 'affiliatex_page_affiliatex_getting_started'), true ) ) {
            $admin_deps = (include_once plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/build/adminJS.asset.php');
            wp_register_script(
                'affiliatex-admin',
                plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/adminJS.js',
                $admin_deps['dependencies'],
                $admin_deps['version'],
                true
            );
            wp_enqueue_style(
                'affx-googlefonts',
                'https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap',
                array(),
                AFFILIATEX_VERSION,
                null
            );
            $ajax_nonce = wp_create_nonce( 'affiliatex_ajax_nonce' );
            // Add localization vars.
            wp_localize_script( 'affiliatex-admin', 'AffiliateXAdmin', array(
                'customizationData' => affx_get_customization_settings(),
                'pluginUrl'         => esc_url( plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) ),
                'ajax_nonce'        => $ajax_nonce,
                'licenseActive'     => ( affiliatex_fs()->is_premium() ? 'true' : 'false' ),
                'ajax_url'          => admin_url( 'admin-ajax.php' ),
                'features'          => apply_filters( 'affiliatex_admin_features', array() ),
                'version'           => AFFILIATEX_VERSION,
            ) );
            wp_enqueue_script( 'affiliatex-admin' );
            if ( $is_upgrade_page ) {
                wp_enqueue_script(
                    'freemius-checkout',
                    'https://checkout.freemius.com/js/v1/',
                    array(),
                    AFFILIATEX_VERSION,
                    true
                );
                wp_localize_script( 'affiliatex-admin', 'AffiliateXUpgrade', array(
                    'productId'  => (string) affiliatex_fs()->get_id(),
                    'publicKey'  => affiliatex_fs()->get_public_key(),
                    'accountUrl' => esc_url( affiliatex_fs()->get_account_url() ),
                ) );
            }
            // Styles.
            wp_enqueue_style(
                'affiliatex-dashboard',
                plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/dashboard.css',
                array(),
                AFFILIATEX_VERSION
            );
            // Styles.
            wp_enqueue_style(
                'affiliatex-options-style-css',
                plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/admin.css',
                array(),
                AFFILIATEX_VERSION
            );
            wp_enqueue_style(
                'toastr',
                plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'assets/css/toastr.min.css',
                array(),
                '2.1.3',
                'all'
            );
        }
        wp_enqueue_style(
            'affiliatex-admin-css',
            plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/adminCSS.css',
            array(),
            AFFILIATEX_VERSION
        );
    }

    /**
     * Enqueue block styles in editor iframe and frontend.
     *
     * @return void
     */
    public function enqueue_block_styles() {
        if ( is_admin() ) {
            wp_enqueue_style(
                'affiliatex-block-styles',
                plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/admin.css',
                array(),
                AFFILIATEX_VERSION
            );
            wp_enqueue_style(
                'fontawesome',
                plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/fontawesome.css',
                array(),
                AFFILIATEX_VERSION
            );
        }
    }

    /**
     * Enqueue Blocks.
     *
     * @return void
     */
    public function editor_assets() {
        global $wp_customize;
        $blocks_deps = (include_once plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/build/blocks.asset.php');
        $blocks_export_deps = (include_once plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/build/blockComponents.asset.php');
        $amazon_config = new PlatformConfig();
        wp_register_script(
            'affiliatex',
            plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/blocks.js',
            $blocks_deps['dependencies'],
            $blocks_deps['version'],
            true
        );
        wp_register_script(
            'affiliatex-block-export',
            plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/blockComponents.js',
            $blocks_export_deps['dependencies'],
            $blocks_export_deps['version'],
            true
        );
        // Add localization vars.
        wp_localize_script( 'affiliatex', 'AffiliateX', array(
            'customizationData'     => affx_get_customization_settings(),
            'siteURL'               => esc_url( home_url( '/' ) ),
            'pluginUrl'             => esc_url( plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) ),
            'proActive'             => ( affiliatex_fs()->is_premium() ? 'true' : 'false' ),
            'WPVersion'             => version_compare( get_bloginfo( 'version' ), '5.9', '>=' ),
            'isAmazonActive'        => ( $amazon_config->is_active() ? 'true' : 'false' ),
            'connectAllButton'      => WidgetHelper::get_connect_all_button_html(),
            'templateLibraryButton' => WidgetHelper::get_template_library_button_html(),
            'hasElementorTemplates' => ( \AffiliateXTemplateLibrary::instance()->has_elementor_templates() ? 'true' : 'false' ),
            'ajax_nonce'            => wp_create_nonce( 'affiliatex_ajax_nonce' ),
        ) );
        wp_enqueue_script( 'affiliatex' );
        wp_enqueue_script( 'affiliatex-block-export' );
        if ( !affiliatex_fs()->is_premium() ) {
            $pro_blocks_preview_deps = (include_once plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/build/proBlocksPreview.asset.php');
            wp_enqueue_script(
                'affiliatex-pro-blocks-preview',
                plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/proBlocksPreview.js',
                $pro_blocks_preview_deps['dependencies'],
                $pro_blocks_preview_deps['version'],
                true
            );
        }
        if ( !isset( $wp_customize ) ) {
            wp_enqueue_style(
                'affiliatex-gb-style-css',
                plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/admin.css',
                array(),
                AFFILIATEX_VERSION
            );
        }
        if ( function_exists( 'gutenberg_get_block_categories' ) ) {
            $scripts = 'js/scripts-old.js';
        } elseif ( function_exists( 'get_block_categories' ) ) {
            $scripts = 'js/scripts.js';
        }
        if ( !isset( $wp_customize ) ) {
            wp_enqueue_script(
                'affiliatex-disable-blocks',
                plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'assets/' . $scripts,
                array('wp-edit-post'),
                AFFILIATEX_VERSION,
                false
            );
            wp_localize_script( 'affiliatex-disable-blocks', 'AffiliateXBlocks', affx_get_disabled_blocks() );
        }
        // Styles.
        wp_enqueue_style(
            'fontawesome',
            plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/fontawesome.css',
            array(),
            AFFILIATEX_VERSION
        );
        wp_enqueue_style(
            'affiliatex-editor-css',
            plugin_dir_url( AFFILIATEX_PLUGIN_FILE ) . 'build/editorCSS.css',
            array(),
            filemtime( AFFILIATEX_PLUGIN_DIR . '/build/editorCSS.css' )
        );
    }

    /**
     * Add AffiliateX Menu
     *
     * @return void
     */
    public function add_affiliate_menu() {
        $ADMIN_ICON = base64_encode( '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0)"><path d="M25 8.73L24 7L4 41.64H6H16H18L31 19.12L25 8.73Z" fill="#69758F"/><path d="M23 8.73L24 7L44 41.64H42H32H30L17 19.12L23 8.73Z" fill="#69758F"/><path d="M23 8.73L17 19.12L24 31.25L31 19.12L25 8.73L24 7L23 8.73Z" fill="#A3ACBF"/></g><defs><clipPath id="clip0"><rect width="40" height="34.64" fill="white" transform="translate(4 7)"/></clipPath></defs></svg>' );
        // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
        add_menu_page(
            esc_html__( 'AffiliateX', 'affiliatex' ),
            esc_html__( 'AffiliateX', 'affiliatex' ),
            'manage_options',
            'affiliatex_blocks',
            array($this, 'render_options_page'),
            'data:image/svg+xml;base64,' . $ADMIN_ICON,
            40
        );
        add_submenu_page(
            'affiliatex_blocks',
            esc_html__( 'Dashboard', 'affiliatex' ),
            esc_html__( 'Dashboard', 'affiliatex' ),
            'manage_options',
            'affiliatex_blocks',
            array($this, 'render_options_page')
        );
    }

    /**
     * Add Getting Started submenu.
     * Hooked at a later admin_menu priority so it appears below the
     * Freemius-injected Account / Contact Us entries.
     *
     * @return void
     */
    public function add_getting_started_menu() {
        add_submenu_page(
            'affiliatex_blocks',
            esc_html__( 'Getting Started', 'affiliatex' ),
            esc_html__( 'Getting Started', 'affiliatex' ),
            'manage_options',
            'affiliatex_getting_started',
            array($this, 'render_getting_started_page')
        );
    }

    /**
     * Render the standalone Getting Started page.
     *
     * @return void
     */
    public function render_getting_started_page() {
        echo '<div id="affiliatexWelcomePageRoot"></div>';
    }

    /**
     * Render our Upgrade page on the Freemius pricing slug, keeping its menu item and checkout.
     *
     * @return void
     */
    public function override_pricing_page() {
        $hook = get_plugin_page_hookname( 'affiliatex_blocks-pricing', 'affiliatex_blocks' );
        if ( affiliatex_fs()->is_premium() ) {
            remove_submenu_page( 'affiliatex_blocks', 'affiliatex_blocks-pricing' );
            if ( !empty( $hook ) ) {
                remove_all_actions( $hook );
            }
            return;
        }
        if ( empty( $hook ) ) {
            return;
        }
        remove_all_actions( $hook );
        add_action( $hook, array($this, 'render_upgrade_page') );
    }

    /**
     * Render the custom Upgrade page.
     *
     * @return void
     */
    public function render_upgrade_page() {
        echo '<div id="affiliatexUpgradePageRoot"></div>';
    }

    /**
     * Set Script Translations
     *
     * @return void
     */
    public function set_script_translations() {
        wp_set_script_translations( 'affiliatex', 'affiliatex' );
        // Blocks.
        wp_set_script_translations( 'affiliatex-admin', 'affiliatex' );
        // AffiliateX Page.
    }

    /**
     * Render options page
     *
     * @return void
     */
    public function render_options_page() {
        echo '<div id="affiliateXAdminPageRoot"></div>';
    }

    /**
     * Add a link to the settings page to the plugins list
     *
     * @param array $links array of links for the plugins, adapted when the current plugin is found.
     *
     * @return array $links
     */
    public function affiliatex_add_action_links( $links ) {
        $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=affiliatex_blocks' ) ) . '">' . esc_html__( 'Settings', 'affiliatex' ) . '</a>';
        array_unshift( $links, $settings_link );
        $get_pro = '<a title="' . esc_html__( 'Get AffiliateX Pro', 'affiliatex' ) . '" href="' . esc_url( 'https://affiliatexblocks.com/pricing/?utm_source=affiliatex&utm_medium=getting_started&utm_campaign=pro_upgrade' ) . '" style="font-weight:700; color: #1da867;" target="_blank">' . esc_html__( 'Get AffiliateX Pro', 'affiliatex' ) . '</a>';
        array_unshift( $links, $get_pro );
        return $links;
    }

}
