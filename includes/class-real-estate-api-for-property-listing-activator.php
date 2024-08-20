<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://github.com/mtmsujan
 * @since      1.0.0
 *
 * @package    Real_Estate_Api_For_Property_Listing
 * @subpackage Real_Estate_Api_For_Property_Listing/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Real_Estate_Api_For_Property_Listing
 * @subpackage Real_Estate_Api_For_Property_Listing/includes
 * @author     MTM Sujan <mtmsujon@gmail.com>
 */
class Real_Estate_Api_For_Property_Listing_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;
        $table_name = $wpdb->prefix . 'sync_propertys';
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT,
            unique_id varchar(255) NOT NULL,
            status varchar(255) NOT NULL,
            value text NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            PRIMARY KEY (id)
        )";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

}
