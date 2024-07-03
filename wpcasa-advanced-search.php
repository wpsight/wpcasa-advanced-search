<?php
/**
 * WPCasa Advanced Search
 *
 * @package           WPCasaAdvancedSearch
 * @author            WPSight
 * @copyright         2024 Kybernetik Services GmbH
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WPCasa Advanced Search
 * Plugin URI:        https://wpcasa.com/downloads/wpcasa-advanced-search
 * Description:       Display an expandable area with advanced options in WPCasa property search form.
 * Version:           1.1.1
 * Requires at least: 6.2
 * Requires PHP:      7.2
 * Requires Plugins:  wpcasa
 * Author:            WPSight
 * Author URI:        https://wpcasa.com
 * Text Domain:       wpcasa-advanced-search
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 *	WPSight_Advanced_Search class
 */
class WPSight_Advanced_Search {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Define constants
		
		if ( ! defined( 'WPSIGHT_NAME' ) )
			define( 'WPSIGHT_NAME', 'WPCasa' );
		
		if ( ! defined( 'WPSIGHT_DOMAIN' ) )
			define( 'WPSIGHT_DOMAIN', 'wpcasa' );

		define( 'WPSIGHT_ADVANCED_SEARCH_NAME', 'WPCasa Advanced Search' );
		define( 'WPSIGHT_ADVANCED_SEARCH_DOMAIN', 'wpcasa-advanced-search' );
		define( 'WPSIGHT_ADVANCED_SEARCH_VERSION', '1.1.1' );
		define( 'WPSIGHT_ADVANCED_SEARCH_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'WPSIGHT_ADVANCED_SEARCH_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		// Cookie constants
		
		define( 'WPSIGHT_COOKIE_SEARCH_ADVANCED', WPSIGHT_DOMAIN . '_advanced_search' );

		// Actions
		
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		
		// Filters
		
		add_filter( 'wpsight_get_search_fields', array( $this, 'get_advanced_search_fields' ) );

	}

	/**
	 *	init()
	 *
	 *	Initialize the plugin when WPCasa is loaded.
	 *
	 *  @param	object	$wpsight
	 *	@uses	do_action_ref_array()
	 *  @return object	$wpsight->advanced_search
	 *
	 *	@since 1.0.0
	 */
	public static function init( $wpsight ) {
		
		if ( ! isset( $wpsight->advanced_search ) )
			$wpsight->advanced_search = new self();

		do_action_ref_array( 'wpsight_init_advanced_search', array( &$wpsight ) );

		return $wpsight->advanced_search;
	}

	/**
	 *	frontend_scripts()
	 *	
	 *	Register and enqueue scripts and css.
	 *	
	 *	@uses	wp_enqueue_style()
	 *	@uses	wp_localize_script()
	 *
	 *	@since 1.0.0
	 */
	public function frontend_scripts() {
		
		// Script debugging?
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'wpsight-listings-search-advanced', WPSIGHT_ADVANCED_SEARCH_PLUGIN_URL . '/assets/js/listings-search-advanced' . $suffix . '.js', array( 'jquery' ), WPSIGHT_ADVANCED_SEARCH_VERSION, true );
		
		// Localize scripts
	
		$data = array(
			'cookie_path'				=> COOKIEPATH,
			'cookie_search_advanced'	=> WPSIGHT_COOKIE_SEARCH_ADVANCED
		);
		
		wp_localize_script( 'wpsight-listings-search-advanced', 'wpsight_localize', $data );

	}

	/**
	 *	advanced_search_fields()
	 *	
	 *	Register and enqueue scripts and css.
	 *	
	 *	@uses	wpsight_sort_array_by_priority()
	 *
	 *	@since 1.0.0
	 */
	public function get_advanced_search_fields( $fields_default ) {
		
		// Set advanced form fields
		
		$fields_advanced = array(

			'min' => array(
				'label' 		=> __( 'Price (min)', 'wpcasa-advanced-search' ),
				'key'			=> '_price',
				'type' 			=> 'text',
				'data_compare' 	=> '>=',
				'data_type' 	=> 'numeric',
				'advanced'		=> true,
				'class'			=> 'width-1-4',
		    	'priority'		=> 90
			),

			'max' => array(
				'label' 		=> __( 'Price (max)', 'wpcasa-advanced-search' ),
				'key'			=> '_price',
				'type' 			=> 'text',
				'data_compare' 	=> '<=',
				'data_type' 	=> 'numeric',
				'advanced'		=> true,
				'class'			=> 'width-1-4',
		    	'priority'		=> 100
			),

			'orderby' => array(
				'label'			=> __( 'Order by', 'wpcasa-advanced-search' ),
				'type' 			=> 'select',
				'data' 			=> array(
					'date'  => __( 'Date', 'wpcasa-advanced-search' ),
					'price' => __( 'Price', 'wpcasa-advanced-search' ),
					'title'	=> __( 'Title', 'wpcasa-advanced-search' )
				),
				'default'		=> 'date',
				'advanced'		=> true,
				'class'			=> 'width-1-4',
		    	'priority'		=> 110
			),

			'order' => array(
				'label'			=> __( 'Order', 'wpcasa-advanced-search' ),
				'type' 			=> 'select',
				'data' 			=> array(
					'asc'  => __( 'asc', 'wpcasa-advanced-search' ),
					'desc' => __( 'desc', 'wpcasa-advanced-search' )
				),
				'default'		=> 'desc',
				'advanced'		=> true,
				'class'			=> 'width-1-4',
		    	'priority'		=> 120
			),
			
			'feature' => array(
				'label'			=> '',
				'data' 			=> array(
					// get_terms() options
					'taxonomy'	=> 'feature',
			    	'orderby'	=> 'count', 
					'order'		=> 'DESC',
					'operator'	=> 'AND', // can be OR
					'number'	=> 8
				),
				'type' 			=> 'taxonomy_checkbox',
				'advanced'		=> true,
				'class'			=> 'width-auto',
		    	'priority'		=> 130
			)
			
		);
		
		$fields_advanced = apply_filters( 'wpsight_get_advanced_search_fields', $fields_advanced, $fields_default );
		
		// Merge default and advanced search
		$fields = array_merge( $fields_default, $fields_advanced );
		
		// Apply filter and sort array by priority  
		return wpsight_sort_array_by_priority( $fields );

	}
	
}

// Initialize plugin on wpsight_init
add_action( 'wpsight_init', array( 'WPSight_Advanced_Search', 'init' ) );
