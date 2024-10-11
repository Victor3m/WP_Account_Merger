<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/Victor3m
 * @since             1.0.0
 * @package           Wpam
 *
 * @wordpress-plugin
 * Plugin Name:       WP_Account_Merger
 * Plugin URI:        https://github.com/Victor3m/WP_Account_Merger
 * Description:       This plugin can be used to Merge accounts in a Wordpress Site.
 * Version:           1.0.0
 * Author:            Victor3m
 * Author URI:        https://github.com/Victor3m/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpam
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
define( 'WPAM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpam-activator.php
 */
function activate_wpam() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpam-activator.php';
	Wpam_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpam-deactivator.php
 */
function deactivate_wpam() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpam-deactivator.php';
	Wpam_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpam' );
register_deactivation_hook( __FILE__, 'deactivate_wpam' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpam.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpam() {

	$plugin = new Wpam();
	$plugin->run();

}
run_wpam();
