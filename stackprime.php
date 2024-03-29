<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.stackprime.com
 * @since             1.0.0
 * @package           Stackprime
 *
 * @wordpress-plugin
 * Plugin Name:       Stackprime Essentials
 * Plugin URI:        https://www.stackprime.com
 * Description:       A custom plugin from stackprime to unbloat your site from unwanted output. Remove admin nags and notifications, unnecessary items and performance-draining code. Includes security patches and shortcodes.
 * Version:           1.1.3
 * Author:            stackprime
 * Author URI:        https://www.stackprime.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       stackprime
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
define( 'STACKPRIME_VERSION', '1.1.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-stackprime-activator.php
 */
function activate_stackprime() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-stackprime-activator.php';
	Stackprime_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-stackprime-deactivator.php
 */
function deactivate_stackprime() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-stackprime-deactivator.php';
	Stackprime_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_stackprime' );
register_deactivation_hook( __FILE__, 'deactivate_stackprime' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-stackprime.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_stackprime() {

	$plugin = new Stackprime();
	$plugin->run();

	require_once plugin_dir_path(  __FILE__ ) . 'update.php' ;
	if ( is_admin() ) {
		$updater = new Smashing_Updater( __FILE__ );
		$updater->set_username( 'ventouris' );
		$updater->set_repository( 'stackprime-essentials' );
		/*
			$updater->authorize( 'abcdefghijk1234567890' ); // Your auth code goes here for private repos
		*/
		$updater->initialize();
	}

}
run_stackprime();
