<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Villas_365
 * @subpackage Villas_365/includes
 * @author     Your Name <email@example.com>
 */
class Villas_365 {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Villas_365_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'villas-365';

		if(!defined('VILLAS_365_PLUGIN_NAME'))
		{
			define('VILLAS_365_PLUGIN_NAME', $this->plugin_name);
		}

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Villas_365_Loader. Orchestrates the hooks of the plugin.
	 * - Villas_365_i18n. Defines internationalization functionality.
	 * - Villas_365_Admin. Defines all hooks for the admin area.
	 * - Villas_365_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-villas-365-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-villas-365-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the network admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-villas-365-admin-network.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-villas-365-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-villas-365-public.php';

		//Fix issue Divi Theme: Saving Yoast Meta Data
		if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . '../../themes/Divi/includes/builder/class-et-builder-value.php' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . '../../themes/Divi/includes/builder/class-et-builder-value.php';
		}

		$this->loader = new Villas_365_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Villas_365_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Villas_365_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_network_admin = new Villas_365_Admin_Network( $this->get_plugin_name() . "-network", $this->get_version(), $this->get_plugin_name() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_network_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_network_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'network_admin_menu', $plugin_network_admin, 'add_menu' );
		$this->loader->add_action( 'network_admin_menu', $plugin_network_admin, 'update_settings');
		$this->loader->add_action( 'admin_init', $plugin_network_admin, 'register_options' );
		$this->loader->add_action( 'admin_init', $plugin_network_admin, 'register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_network_admin, 'register_fields' );

		$plugin_admin = new Villas_365_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_options' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_fields' );

		$this->loader->add_filter( 'gform_notification', $plugin_admin, 'change_gravity_forms_notification_email' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Villas_365_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
        //Load _365_google_tag_manager_head_script in < head > and load _365_google_tag_manager_after_body_script after body tag
		$this->loader->add_action('wp_head', $plugin_public, '_365_google_tag_manager_head_script');
		$this->loader->add_action('wp_footer', $plugin_public, '_365_google_tag_manager_after_body_script');

		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
		$this->loader->add_action( 'init', $plugin_public, 'register_rewrites' );

		//Add a calculate booking action (both public and private so it works even when logged in).
		$this->loader->add_action( 'wp_ajax_nopriv__villas_365_calculate_booking', $plugin_public, 'calculate_booking' );
		$this->loader->add_action( 'wp_ajax__villas_365_calculate_booking', $plugin_public, 'calculate_booking' );

		$this->loader->add_filter( 'wp_nav_menu_items', $plugin_public, 'add_language_switcher_to_nav_menu_items', 10, 2 );

		add_filter('https_ssl_verify', function () {
			return false;
		});

		add_filter('http_request_args', function($args) {
			$args['reject_unsafe_urls'] = false;
			return $args;
		});

		if((in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins')))) ||
			(in_array('wordpress-seo-premium/wp-seo-premium.php', apply_filters('active_plugins', get_option('active_plugins')))))
		{ 
			$this->loader->add_filter( 'wpseo_title', $plugin_public, 'change_property_page_meta_title', 100 );
			$this->loader->add_filter( 'wpseo_opengraph_title', $plugin_public, 'change_property_page_meta_title', 100 );
			$this->loader->add_filter( 'wpseo_metadesc', $plugin_public, 'change_property_page_meta_description', 100 );
			$this->loader->add_filter( 'wpseo_opengraph_desc', $plugin_public, 'change_property_page_meta_description', 100 );
			$this->loader->add_filter( 'wpseo_canonical', $plugin_public, 'change_property_page_meta_canonical_url', 100 );
			$this->loader->add_filter( 'wpseo_opengraph_url', $plugin_public, 'change_property_page_meta_canonical_url', 100 );
			$this->loader->add_action( 'wpseo_add_opengraph_images', $plugin_public, 'add_property_page_meta_opengraph_image' );
		}
		else
		{
			$this->loader->add_action( 'wp_head', $plugin_public, 'add_property_page_meta' );
			$this->loader->add_filter( 'pre_get_document_title', $plugin_public, 'change_property_page_meta_title', 100 );
			$this->loader->add_filter( 'get_canonical_url', $plugin_public, 'change_property_page_meta_canonical_url', 100 );
		}
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Villas_365_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
