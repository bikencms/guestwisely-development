<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://guestwisely.io/
 * @since             1.0.0
 * @package           Guestwisely
 *
 * @wordpress-plugin
 * Plugin Name:       Guestwisely
 * Plugin URI:        https://guestwisely.io/
 * Description:       Adds various functions related to Guestwisely.
 * Version:           1.0.0
 * Author:            Guestwisely
 * Author URI:        https://guestwisely.io/
 * License:           Copyright (C) 2025 Guestwisely.
 * Text Domain:       guestwisely
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
define( 'VILLAS_365_VERSION',  '1.0.0' );

define( 'VILLAS_365_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'VILLAS_365_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-villas-365-activator.php
 */
function activate_villas_365() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-villas-365-activator.php';
	Villas_365_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-villas-365-deactivator.php
 */
function deactivate_villas_365() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-villas-365-deactivator.php';
	Villas_365_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_villas_365' );
register_deactivation_hook( __FILE__, 'deactivate_villas_365' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-villas-365.php';

/**
 * 365villas Classes
 */
require plugin_dir_path( __FILE__ ) . 'includes/API365/API365.php';
require plugin_dir_path( __FILE__ ) . 'includes/API365/Helpers365.php';

//Add the update checker.
require plugin_dir_path( __FILE__ ) . 'includes/plugin-update-checker-5.6/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/bikencms/guestwisely-development',
	__FILE__,
	'guestwisely'
);

$myUpdateChecker->setAuthentication('glpat-2Xqa6wADGA8cRpVZKtCH');
$myUpdateChecker->setBranch('main');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_villas_365() {

	$plugin = new Villas_365();
	$plugin->run();

}
run_villas_365();
