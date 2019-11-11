<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://meticulousolutions.com
 * @since      1.0.0
 *
 * @package    Jpeg_Webp_Converter
 * @subpackage Jpeg_Webp_Converter/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Jpeg_Webp_Converter
 * @subpackage Jpeg_Webp_Converter/includes
 * @author     Meticulous Solutions <info@meticulousolutions.com>
 */
class Jpeg_Webp_Converter_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'jpeg-webp-converter',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
