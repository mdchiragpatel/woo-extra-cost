<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_Extra_Cost
 * @subpackage Woo_Extra_Cost/includes
 * @author     Multidots <wordpress@multidots.com>
 */
class Woo_Extra_Cost_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb,$woocommerce;
		set_transient( '_woo_extra_cost_welcome_screen', true, 30 );
		
		if( !in_array( 'woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins'))) && !is_plugin_active_for_network( 'woocommerce/woocommerce.php' )   ) { 
			wp_die( "<strong>WooCommerce Extra Cost</strong> Plugin requires <strong>WooCommerce</strong> <a href='".get_admin_url(null, 'plugins.php')."'>Plugins page</a>." );
		} else {
			$table_name = $wpdb->prefix . "woocommerce_extra_cost";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$sql = "CREATE TABLE $table_name (
					extra_cost_country_code varchar(200) NOT NULL DEFAULT '',
					extra_cost_rate_id bigint(20) NOT NULL auto_increment,
					extra_cost_name varchar(200) NOT NULL DEFAULT '',
					extra_cost_value varchar(200) NOT NULL DEFAULT '',
					PRIMARY KEY  (extra_cost_rate_id)
			   		);";	
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			}
			
			
		}
	}

}
