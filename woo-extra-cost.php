<?php

/**
 * Plugin Name:       WooCommerce Extra Cost
 * Plugin URI:        http://www.multidots.com/
 * Description:       This plugin allows store owner to add extra cost based on product as well as based on country selected.
 * Version:           1.2.2
 * Author:            Multidots
 * Author URI:        http://www.multidots.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-extra-cost
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-extra-cost-activator.php
 */
function activate_woo_extra_cost() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-extra-cost-activator.php';
	Woo_Extra_Cost_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-extra-cost-deactivator.php
 */
function deactivate_woo_extra_cost() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-extra-cost-deactivator.php';
	Woo_Extra_Cost_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_extra_cost' );
register_deactivation_hook( __FILE__, 'deactivate_woo_extra_cost' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-extra-cost.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_extra_cost() {

	$plugin = new Woo_Extra_Cost();
	$plugin->run();

}
run_woo_extra_cost();
