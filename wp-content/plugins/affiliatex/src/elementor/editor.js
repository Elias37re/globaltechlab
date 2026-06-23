import React from 'react';
import ReactDOM from 'react-dom';
import UpgradeModal from '../blocks/ui-components/UpgradeModal';
import { __ } from '@wordpress/i18n';
import { initElementorTemplateLibrary } from './TemplateLibraryModalElementor';

( ( $ ) => {
	'use strict';

	const ElementorAmazonFree = {
		/** Currently active AffiliateX widget model */
		activeWidgetModel: null,

		/** Cached key combining widget ID and shortcode to prevent unnecessary re-renders */
		lastCacheKey: null,

		/** Debounce timer for change event handler */
		debounceTimer: null,

		/**
		 * Initialize the plugin.
		 * Sets up upgrade modal and binds all event listeners.
		 */
		init() {
			if ( window.AffiliateX && window.AffiliateX.proActive !== 'true' ) {
				ElementorAmazonFree.initUpgradeModal();
			}

			ElementorAmazonFree.bindEvents();
			ElementorAmazonFree.initTemplateLibrary();
		},

		/**
		 * Bind event listeners.
		 * Registers all document and window event handlers.
		 */
		bindEvents() {
			if ( window.AffiliateX && window.AffiliateX.proActive !== 'true' ) {
				$( document ).on( 'click', '.affx-action-button__amazon', ElementorAmazonFree.triggerUpgradeModal );
				$( document ).on( 'click', '.affx-connect-all-wrapper', ElementorAmazonFree.triggerUpgradeModal );
			}

			$( window ).on( 'elementor:init', ElementorAmazonFree.addConnectAllButton );
			$( window ).on( 'elementor:init', ElementorAmazonFree.addTemplateLibraryButton );
			$( window ).on( 'elementor:init', ElementorAmazonFree.initElementorHooks );

			$( document ).on( 'click', '.affx-template-library-button', ElementorAmazonFree.openTemplateLibrary );

			// Ignore wp-auth-check.js error
			$( document ).on( 'error', function ( e ) {
				if ( e.originalEvent?.filename?.includes( 'wp-auth-check.js' ) ) {
					return false;
				}
			} );
		},

		/**
		 * Initialize Elementor hooks.
		 */
		initElementorHooks() {
			if ( ! elementor || ! elementor.hooks ) {
				return;
			}
			elementor.hooks.addAction( 'panel/open_editor/widget', ElementorAmazonFree.handleActiveWidget );
			elementor.channels.editor.on( 'section:activated', ElementorAmazonFree.handleSectionActivated );
			$( document ).on( 'change', ElementorAmazonFree.debouncedHandleDisabledControls );
		},

		/**
		 * Handle the active widget and store model reference.
		 *
		 * @param {Object} panel - Elementor panel object
		 * @param {Object} model - Widget model object
		 */
		handleActiveWidget( panel, model ) {
			// eslint-disable-line no-unused-vars
			if ( model?.get( 'widgetType' )?.includes( 'affiliatex' ) ) {
				ElementorAmazonFree.activeWidgetModel = model;
			} else {
				ElementorAmazonFree.activeWidgetModel = null;
				ElementorAmazonFree.lastCacheKey = null;
			}
		},

		/**
		 * Handle section activation - clear cache so controls get re-applied on fresh DOM.
		 */
		handleSectionActivated() {
			ElementorAmazonFree.lastCacheKey = null;
			ElementorAmazonFree.handleDisabledControls();
		},

		/**
		 * Debounced wrapper for handleDisabledControls to prevent excessive calls.
		 * Limits execution to once every 150ms.
		 */
		debouncedHandleDisabledControls() {
			clearTimeout( ElementorAmazonFree.debounceTimer );
			ElementorAmazonFree.debounceTimer = setTimeout( () => {
				ElementorAmazonFree.handleDisabledControls();
			}, 150 );
		},

		/**
		 * Enable/disable controls based on Amazon shortcode limits.
		 * Caches shortcode value to prevent redundant DOM updates.
		 * Early exits if no active widget or shortcode unchanged.
		 */
		handleDisabledControls() {
			if ( ! ElementorAmazonFree.activeWidgetModel ) {
				return;
			}

			const shortcode = ElementorAmazonFree.activeWidgetModel.getSetting( 'productContentListAmazon' ) || '';
			const cacheKey = `${ ElementorAmazonFree.activeWidgetModel.id }_${ shortcode }`;

			if ( cacheKey === ElementorAmazonFree.lastCacheKey ) {
				return;
			}

			ElementorAmazonFree.lastCacheKey = cacheKey;

			const limitMatch = shortcode.match( /limit="(\d+),(\d+)"/ );

			let hasLimits = false;
			if ( limitMatch ) {
				const charLimit = parseInt( limitMatch[ 1 ], 10 );
				const itemLimit = parseInt( limitMatch[ 2 ], 10 );
				hasLimits = charLimit > 0 || itemLimit > 0;
			}

			const controlNames = [ 'descriptionLength', 'listItemCount' ];
			const controls = controlNames
				.map( ( fieldName ) => ( {
					name: fieldName,
					element: $( `#elementor-panel [data-setting="${ fieldName }"]` )
				} ) )
				.filter( ( control ) => control.element.length );

			controls.forEach( ( { element } ) => {
				const wrapper = element.closest( '.elementor-control' );
				if ( hasLimits ) {
					wrapper.addClass( 'affx-amazon-connected' );
					element.prop( 'disabled', true );
				} else {
					wrapper.removeClass( 'affx-amazon-connected' );
					element.prop( 'disabled', false );
				}
			} );
		},

		/**
		 * Initialize upgrade modal.
		 * Creates React root and renders UpgradeModal component.
		 */
		initUpgradeModal() {
			$( 'body' ).append( '<div id="affx-upgrade-modal-root"></div>' );

			const upgradeRootElement = document.getElementById( 'affx-upgrade-modal-root' );

			if ( upgradeRootElement && ! upgradeRootElement.hasChildNodes() ) {
				ReactDOM.createRoot( upgradeRootElement ).render( <UpgradeModal /> );
			}
		},

		/**
		 * Initialize template library.
		 * Sets up Elementor template library integration.
		 */
		initTemplateLibrary() {
			$( window ).on( 'elementor:init', () => {
				// Wait for Elementor to be fully loaded
				setTimeout( () => {
					initElementorTemplateLibrary();
				}, 1000 );
			} );
		},

		/**
		 * Open template library modal.
		 *
		 * @param {Event} e - Click event object
		 */
		openTemplateLibrary( e ) {
			e.preventDefault();

			let widgetType = null;
			let widgetModel = null;
			try {
				const currentPageView = elementor?.getPanelView()?.getCurrentPageView();
				const model = currentPageView?.model;
				if ( model && model.get( 'widgetType' ) ) {
					widgetType = model.get( 'widgetType' ).replace( 'affiliatex-', '' );
					widgetModel = model;
				}
			} catch ( error ) {
				console.error( 'Error getting widget type:', error );
			}

			if ( window.AffiliateXElementorTemplateLibrary ) {
				window.AffiliateXElementorTemplateLibrary.open( widgetType, widgetModel );
			} else {
				console.error( 'Template library not initialized' );
			}
		},

		/**
		 * Trigger upgrade modal.
		 * Dispatches actions to show Amazon integration upgrade modal.
		 */
		triggerUpgradeModal() {
			window.wp.data.dispatch( 'affiliatex' ).setActiveModal( 'upgrade-modal' );
			window.wp.data.dispatch( 'affiliatex' ).setUpgradeModal( {
				modalType: 'amazon',
				modalTitle: __( 'Amazon Integration', 'affiliatex' ),
				blockTitle: __( 'Amazon Integration', 'affiliatex' )
			} );
		},

		/**
		 * Add "Connect All" button to AffiliateX widget panels.
		 * Excludes specific widgets that don't need the button.
		 */
		addConnectAllButton() {
			const excludeWidgets = [
				'affiliatex-product-comparison',
				'affiliatex-product-table',
				'affiliatex-pros-and-cons',
				'affiliatex-specifications',
				'affiliatex-verdict',
				'affiliatex-versus-line',
				'affiliatex-versus',
				'affiliatex-dynamic-listing'
			];

			const initiallyHiddenWidgets = [ 'affiliatex-top-products', 'affiliatex-coupon-listing', 'affiliatex-coupon-grid' ];

			const addButton = ( panel, model ) => {
				if ( panel && model ) {
					if (
						model?.get( 'widgetType' ) &&
						model.get( 'widgetType' ).includes( 'affiliatex' ) &&
						! excludeWidgets.includes( model.get( 'widgetType' ) )
					) {
						if ( panel.getOption( 'tab' ) === 'content' ) {
							const navigationPanel = $( panel.el ).find( '.elementor-panel-navigation' );
							if ( $( panel.el ).find( '.affx-connect-all-wrapper' ).length === 0 ) {
								navigationPanel.after( AffiliateX.connectAllButton );
								if ( initiallyHiddenWidgets.includes( model.get( 'widgetType' ) ) ) {
									$( panel.el ).find( '.affx-connect-all-wrapper' ).hide();
								}
							}
						}
					}
				}
			};

			// Triggers when a widget is activated.
			elementor.hooks.addAction( 'panel/open_editor/widget', addButton );

			const processedViews = new WeakSet();

			elementor.hooks.addFilter( 'controls/base/behaviors', ( behaviors ) => {
				const currentPageView = elementor.getPanelView().getCurrentPageView();

				// Only add button once per unique PageView
				if ( currentPageView && ! processedViews.has( currentPageView ) ) {
					processedViews.add( currentPageView );
					addButton( currentPageView, currentPageView.model );
				}

				return behaviors;
			} );
		},

		/**
		 * Add template library button to AffiliateX widget panels.
		 * Only displays if templates are available.
		 */
		addTemplateLibraryButton() {
			if ( window.AffiliateX && window.AffiliateX.hasElementorTemplates !== 'true' ) {
				return;
			}

			const affxWidgets = [
				'affiliatex-buttons',
				'affiliatex-cta',
				'affiliatex-notice',
				'affiliatex-product-comparison',
				'affiliatex-product-table',
				'affiliatex-pros-and-cons',
				'affiliatex-single-product',
				'affiliatex-single-coupon',
				'affiliatex-specifications',
				'affiliatex-verdict',
				'affiliatex-versus-line',
				'affiliatex-top-products',
				'affiliatex-versus',
				'affiliatex-rating-box',
				'affiliatex-single-product-pros-and-cons',
				'affiliatex-product-image-button',
				'affiliatex-coupon-grid',
				'affiliatex-coupon-listing',
				'affiliatex-product-tabs',
				'affiliatex-dynamic-listing'
			];

			const addButton = ( panel, model ) => {
				if ( panel && model ) {
					if ( model?.get( 'widgetType' ) && affxWidgets.includes( model.get( 'widgetType' ) ) ) {
						if ( panel.getOption( 'tab' ) === 'content' ) {
							const navigationPanel = $( panel.el ).find( '.elementor-panel-navigation' );
							// Add button after navigation if it doesn't exist
							if ( $( panel.el ).find( '.affx-template-library-button-wrapper' ).length === 0 ) {
								navigationPanel.after( AffiliateX.templateLibraryButton );
							}
						}
					}
				}
			};

			elementor.hooks.addAction( 'panel/open_editor/widget', addButton );
			const processedViews = new WeakSet();

			elementor.hooks.addFilter( 'controls/base/behaviors', ( behaviors ) => {
				const currentPageView = elementor.getPanelView().getCurrentPageView();
				if ( currentPageView && ! processedViews.has( currentPageView ) ) {
					processedViews.add( currentPageView );
					addButton( currentPageView, currentPageView.model );
				}

				return behaviors;
			} );
		}
	};

	ElementorAmazonFree.init();
} )( jQuery );
