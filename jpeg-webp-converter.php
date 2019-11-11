<?php

/**
 * @link              http://meticulousolutions.com
 * @since             1.0.0
 * @package           Jpeg_Webp_Converter
 *
 * @wordpress-plugin
 * Plugin Name:       JPG To WebP Converter
 * Plugin URI:        http://meticulousolutions.com
 * Description:       This plugin provides a mapping screen to convert JPG/PNG to WebP
 * Version:           1.0.0
 * Author:            Meticulous Solutions
 * Author URI:        http://meticulousolutions.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jpeg-webp-converter
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
define( 'Jpeg_Webp_Converter_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jpeg-webp-converter-activator.php
 */
function activate_jpeg_webp_converter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jpeg-webp-converter-activator.php';
	Jpeg_Webp_Converter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jpeg-webp-converter-deactivator.php
 */
function deactivate_jpeg_webp_converter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jpeg-webp-converter-deactivator.php';
	Jpeg_Webp_Converter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jpeg_webp_converter' );
register_deactivation_hook( __FILE__, 'deactivate_jpeg_webp_converter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-jpeg-webp-converter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_jpeg_webp_converter() {

	$plugin = new Jpeg_Webp_Converter();
	$plugin->run();

}
run_jpeg_webp_converter();
