<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://github.com/mtmsujan
 * @since      1.0.0
 *
 * @package    Real_Estate_Api_For_Property_Listing
 * @subpackage Real_Estate_Api_For_Property_Listing/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Real_Estate_Api_For_Property_Listing
 * @subpackage Real_Estate_Api_For_Property_Listing/includes
 * @author     MTM Sujan <mtmsujon@gmail.com>
 */
class Real_Estate_Api_For_Property_Listing_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'real-estate-api-for-property-listing',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
