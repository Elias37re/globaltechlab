<?php
namespace AffiliateX\Elementor;

defined( 'ABSPATH' ) || exit;


use AffiliateX\Elementor\Controls\TextControl;

/**
 * Widget Manager Class.
 *
 * This class is responsible for registering the widgets for Elementor.
 *
 * @package AffiliateX\Elementor
 * @since 1.0.0
 * @version 1.0.0
 */
class WidgetManager {
	/**
	 * Widgets.
	 *
	 * @var array
	 */
	private $widgets = array();

	/**
	 * Constructor.
	 *
	 * @param array $widgets
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function __construct( $widgets = array() ) {
		$this->widgets = $widgets;

		// Register widgets
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		// Register category
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );

		// Enqueue main styles
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// Register Font Awesome
		add_action( 'wp_enqueue_scripts', array( $this, 'register_font_awesome' ) );
	}

	/**
	 * Register Widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function register_widgets( $widgets_manager ) {
		foreach ( $this->widgets as $widget ) {
			$widgets_manager->register( new $widget() );
		}
	}

	/**
	 * Add Elementor Widget Categories.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'affiliatex',
			array(
				'title'    => __( 'AffiliateX', 'affiliatex' ),
				'icon'     => 'fa fa-plug',
				'active'   => true,
				'position' => 1,
			)
		);
	}

	/**
	 * Enqueue Styles.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function enqueue_styles() {
		// Enqueue compiled CSS for elementor editor control styles.
		wp_enqueue_style(
			'affiliatex-elementor-editor-style',
			AFFILIATEX_PLUGIN_URL . 'build/elementorEditorCSS.css',
			array( 'wp-components' ),
			AFFILIATEX_VERSION
		);

		$frontend_deps = array( 'jquery' );

		if ( wp_script_is( 'elementor-frontend', 'registered' ) ) {
			$frontend_deps[] = 'elementor-frontend';
		}

		wp_enqueue_script(
			'affiliatex-frontend',
			AFFILIATEX_PLUGIN_URL . 'build/frontendJs.js',
			$frontend_deps,
			AFFILIATEX_VERSION,
			true
		);
	}

	/**
	 * Register Font Awesome.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function register_font_awesome() {
		wp_register_style(
			'fontawesome',
			AFFILIATEX_PLUGIN_URL . 'build/fontawesome.css',
			array(),
			AFFILIATEX_VERSION
		);

		wp_register_script(
			'fontawesome-all',
			AFFILIATEX_PLUGIN_URL . 'build/fontawesome.js',
			array(),
			AFFILIATEX_VERSION,
			true
		);
	}
}
