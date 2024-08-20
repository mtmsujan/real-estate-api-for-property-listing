<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://github.com/mtmsujan
 * @since             1.0.0
 * @package           Real_Estate_Api_For_Property_Listing
 *
 * @wordpress-plugin
 * Plugin Name:       Real Estate API for Property Listing
 * Plugin URI:        https://github.com/mtmsujan/kinguin-api-for-woocommerce-product
 * Description:       Real Estate API for Property Listing
 * Version:           1.0.0
 * Author:            MTM Sujan
 * Author URI:        https://https://github.com/mtmsujan/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       real-estate-api-for-property-listing
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'REAL_ESTATE_API_FOR_PROPERTY_LISTING_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-real-estate-api-for-property-listing-activator.php
 */
function activate_real_estate_api_for_property_listing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-real-estate-api-for-property-listing-activator.php';
	Real_Estate_Api_For_Property_Listing_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-real-estate-api-for-property-listing-deactivator.php
 */
function deactivate_real_estate_api_for_property_listing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-real-estate-api-for-property-listing-deactivator.php';
	Real_Estate_Api_For_Property_Listing_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_real_estate_api_for_property_listing' );
register_deactivation_hook( __FILE__, 'deactivate_real_estate_api_for_property_listing' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-real-estate-api-for-property-listing.php';

// Inculd fetch api function file
require plugin_dir_path( __FILE__ ) . 'admin/fetch-api-property-import-to-db.php';

// Import Property Listing File
require plugin_dir_path( __FILE__ ) . 'admin/import-property-listing.php';

/**
 * API Endpoints
 */
require plugin_dir_path( __FILE__ ) . 'admin/api_endpoints.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_real_estate_api_for_property_listing() {

	$plugin = new Real_Estate_Api_For_Property_Listing();
	$plugin->run();

}
run_real_estate_api_for_property_listing();
