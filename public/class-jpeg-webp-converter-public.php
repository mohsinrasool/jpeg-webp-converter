<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://meticulousolutions.com
 * @since      1.0.0
 *
 * @package    Jpeg_Webp_Converter
 * @subpackage Jpeg_Webp_Converter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Jpeg_Webp_Converter
 * @subpackage Jpeg_Webp_Converter/public
 * @author     Meticulous Solutions <info@meticulousolutions.com>
 */
class Jpeg_Webp_Converter_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


		// enable filtering for webp comaptible browsers only
		add_filter('the_content', array($this, 'the_content'), 90 );
		// add_filter('mfn_hook_content', array($this, 'the_content'), 30 );
		add_filter('max_srcset_image_width', create_function('', 'return 1;'));

	}

	function the_content($content)
	{
		$mappings = get_option( 'jwc_mappings', array() );

		// if(is_page('webp-test-page')) {
		// 	var_dump($content);
		// }

		foreach ($mappings as $mapping) {
			$content = $this->replace_images($mapping['source_url'], $mapping['destination_url'], $content);
		}

		return $content;
	}

	function replace_images($source_url, $destination_url, $content)
	{
		$matches = null;

		// $source_url = str_replace('/', '\\/', $source_url);

		preg_match_all('/<img(.*)(src="'.(str_replace('/', '\\/', $source_url)).'")(.*)\\/>/', $content, $matches);


		for($i=0; $i < count($matches[0]); $i++) {
			if(empty($matches[$i]))
				continue;

			$content = str_replace($matches[0][$i], '<picture> <source '.$matches[1][$i].' srcset="'.$destination_url.'" type="image/webp" /><img '.$matches[1][$i].' src="'.$source_url.'" '.$matches[3][$i].' /></picture>' , $content);
		}

		return $content;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jpeg_Webp_Converter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jpeg_Webp_Converter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jpeg-webp-converter-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jpeg_Webp_Converter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jpeg_Webp_Converter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jpeg-webp-converter-public.js', array( 'jquery' ), $this->version, false );

	}

}

/* override the muffin builder plugin for image */
// if( ! function_exists( 'mfn_print_image' ) )
{
	function mfn_print_image( $item ) {
		echo apply_filters('the_content', sc_image( $item['fields'] ) );
	}
}


/* override the muffin builder plugins for image gallery */
// if( ! function_exists( 'mfn_print_image_gallery' ) )
{
	function mfn_print_image_gallery( $item ) {
		$item[ 'fields' ][ 'link' ] = 'file';
		echo  apply_filters('the_content', sc_gallery( $item['fields'] ));
	}
}