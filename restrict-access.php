<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Restrict_Access
 *
 * @wordpress-plugin
 * Plugin Name:       Restrict Access
 * Description:       Restrict access to specific pages.
 * Version:           1.0.0
 * Author:            Tammersoft
 * Author URI:        https://www.tammersoft.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       restrict-access
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('RESTRICT_ACCESS_VERSION', '1.0.0');
define('RESTRICT_ACCESS_URI', plugin_dir_url(__FILE__));  
define('RESTRICT_ACCESS_PATH', plugin_dir_path(__FILE__));  

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ra-activator.php
 */
function activate_restrict_access() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ra-activator.php';
	Restrict_Access_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ra-deactivator.php
 */
function deactivate_restrict_access() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ra-deactivator.php';
	Restrict_Access_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_restrict_access' );
register_deactivation_hook( __FILE__, 'deactivate_restrict_access' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-restrict-access.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_restrict_access() {

	$plugin = new Restrict_Access();
	$plugin->run();

}

run_restrict_access();
