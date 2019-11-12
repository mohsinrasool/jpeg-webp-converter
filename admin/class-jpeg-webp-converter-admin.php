<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://meticulousolutions.com
 * @since      1.0.0
 *
 * @package    Jpeg_Webp_Converter
 * @subpackage Jpeg_Webp_Converter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Jpeg_Webp_Converter
 * @subpackage Jpeg_Webp_Converter/admin
 * @author     Meticulous Solutions <info@meticulousolutions.com>
 */
class Jpeg_Webp_Converter_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        $this->jwc_options = get_option( 'jwc_options' );

		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );


        // AJAX hooks
        add_action( 'wp_ajax_jwc_add_mapping', array( $this, 'jwc_add_mapping') );
        add_action( 'wp_ajax_jwc_update_mapping', array( $this, 'jwc_update_mapping') );
        add_action( 'wp_ajax_jwc_delete_mapping', array( $this, 'jwc_delete_mapping') );
	}

    /**
     * Returns plugin settings
     */
    
    public function get_settings()
    {
        return apply_filters('jwc_settings', $this->jwc_options);
    }


    /**
     * Register a custom menu page.
     */
    public function add_plugin_page(){

         // Add a new top-level menu (ill-advised):
        add_menu_page(
            __('JPG to WebP','jpeg-webp-converter'), 
            __('JPG to WebP','jpeg-webp-converter'), 
            'manage_options', 
            'jwc-setting', 
            array( &$this, 'create_settings_page')
        );

    }


    /**
     * Options page callback
     */
    public function create_settings_page()
    {
        
        include 'partials/settings.php';
    }


    /**
     * Register and add settings
     */
    public function page_init()
    {    

    }


    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        // print 'Enter your settings below:';
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jpeg-webp-converter-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_media ();
		wp_enqueue_script('media-upload'); //Provides all the functions needed to upload, validate and give format to files.
		wp_enqueue_script('thickbox'); //Responsible for managing the modal window.
		wp_enqueue_style('thickbox'); //Provides the styles needed for this window.
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jpeg-webp-converter-admin.js', array( 'jquery', 'media-editor' ), $this->version, false );

		// Get the protocol of the current page
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';

		// Set the ajaxurl Parameter which will be output right before
		// our ajax-delete-posts.js file so we can use ajaxurl
		$params = array(
			// Get the url to the admin-ajax.php file using admin_url()
			'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
		);
		// Print the script to our page
		wp_localize_script( 'spyr_ajax_delete_posts', 'jwc_params', $params );
	}


	public function jwc_add_mapping()
	{
		$output = array(
			'status' => 'error',
			'message' => 'Please specify valid source and destination urls.'
		);


		if(!empty($_POST['source_url']) && !empty($_POST['source_url'])) {
			$mappings = get_option( 'jwc_mappings', array() );

			$record = array();

			$record['source_url'] = esc_url($_POST['source_url']);
			$record['destination_url'] = esc_url($_POST['destination_url']);

			if(isset($mappings[$record['source_url']])) {

				$output = array(
					'status' => 'error',
					'record' => $record,
					'message' => 'Mapping for this URL already exists.'
				);

			} else {
				$mappings[$record['source_url']] = $record;

				update_option('jwc_mappings', $mappings);

				$output = array(
					'status' => 'success',
					'record' => $record,
					'message' => 'Mapping successfully added.'
				);

			}

		}

		die( json_encode($output) );
	}

	public function jwc_update_mapping()
	{
		$output = array(
			'status' => 'error',
			'message' => 'Please specify valid source and destination urls.'
		);

		if(!empty($_POST['source_url']) && !empty($_POST['source_url'])) {

			$mappings = get_option( 'jwc_mappings', array() );

			$record = array();

			$record['source_url'] = esc_url($_POST['source_url']);
			$record['destination_url'] = esc_url($_POST['destination_url']);

			if(isset($mappings[$record['source_url']])) {

				$mappings[$record['source_url']] = $record;

				update_option('jwc_mappings', $mappings);

				$output = array(
					'status' => 'success',
					'record' => $record,
					'message' => 'Mapping successfully updated.'
				);

			} else {
				$output = array(
					'status' => 'error',
					'record' => $record,
					'message' => 'Mapping not found.'
				);
			}

		}

		die( json_encode($output) );
	}

	public function jwc_delete_mapping()
	{

		$output = array(
			'status' => 'error',
			'message' => 'Please specify valid source and destination urls.'
		);


		if(!empty($_POST['source_url']) && !empty($_POST['source_url'])) {

			$record = array();

			$record['source_url'] = esc_url($_POST['source_url']);
			$record['destination_url'] = esc_url($_POST['destination_url']);

			$mappings = get_option( 'jwc_mappings', array() );

			if(isset($mappings[$record['source_url']])) {
				unset($mappings[$record['source_url']]);
				update_option('jwc_mappings', $mappings);

				$output = array(
					'status' => 'success',
					'record' => $record,
					'message' => 'Mapping successfully deleted.'
				);
			} else {
				$output = array(
					'status' => 'error',
					'record' => $record,
					'message' => 'Mapping not found.'
				);
			}
		}

		die( json_encode($output) );


	}

}
