<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Villas_365
 * @subpackage Villas_365/admin
 * @author     Your Name <email@example.com>
 */
class Villas_365_Admin_Network {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	private $plugin_name_base;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_name_base ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_name_base = $plugin_name_base;
		$this->version = $version;

		$this->set_options();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Villas_365_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Villas_365_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/villas-365-admin-network.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Villas_365_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Villas_365_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/villas-365-admin-network.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Sets the class variable $options
	 */
	private function set_options()
	{
		$this->options = [
			$this->plugin_name . "_google_maps_api_key" => get_site_option($this->plugin_name . "_google_maps_api_key"),
			$this->plugin_name . "_template_name" => get_site_option($this->plugin_name . "_template_name")
		];
	} // set_options()

	/**
	 * Adds a settings page link to a menu
	 *
	 * @link 		https://codex.wordpress.org/Administration_Menus
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function add_menu()
	{
		// Top-level page
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

		// Submenu Page
		// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);

		add_menu_page(
			'365villas',
			'365villas',
			'manage_options',
			'365-villas-network',
			[$this, 'page_options'],
			'dashicons-admin-site'
		);
	} // add_menu()

	public function update_settings()
	{
		if (isset($_POST['submit']))
		{
			// verify authentication (nonce)
			if(!isset($_POST['villas_365_nonce']))
			{
				return;
			}
 
			// verify authentication (nonce)
			if(!wp_verify_nonce($_POST['villas_365_nonce'], 'villas_365_nonce'))
			{
				return;
			}

			//Save the API key used by the external application for calling in to the API.
			if(isset($_POST[$this->plugin_name . "_google_maps_api_key"]) && trim($_POST[$this->plugin_name . "_google_maps_api_key"]))
			{
				$settingUpdated = update_site_option($this->plugin_name . "_google_maps_api_key", $_POST[$this->plugin_name . "_google_maps_api_key"]);
			}
			else
			{
				delete_site_option($this->plugin_name . "_google_maps_api_key");
			}

			//Save the template name used to output the correct styles.
			if(isset($_POST[$this->plugin_name . "_template_name"]) && trim($_POST[$this->plugin_name . "_template_name"]))
			{
				$settingUpdated = update_site_option($this->plugin_name . "_template_name", $_POST[$this->plugin_name . "_template_name"]);
			}
			else
			{
				delete_site_option($this->plugin_name . "_template_name");
			}

			wp_redirect(add_query_arg([
				'page' => '365-villas-network',
				'settings-updated' => 'true'
			], network_admin_url('admin.php')));
			exit;
	
			return true;
		}
	} // update_settings()

	/**
	 * Creates the options page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_options()
	{
		// check user capabilities
		if(!current_user_can('manage_options'))
		{
			return;
		}

		// add error/update messages
 
		// check if the user have submitted the settings
		// wordpress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) )
		{
			// add settings saved message with the class of "updated"
			add_settings_error( $this->plugin_name . '_messages', $this->plugin_name . '_message', __( 'Settings Saved', $this->plugin_name ), 'updated' );
		}

		// show error/update messages
 		settings_errors( $this->plugin_name . '_messages' );

		include( plugin_dir_path( __FILE__ ) . 'partials/villas-365-admin-network-page-settings.php' );
	} // page_options()


	public function register_options()
	{
		add_option(
			$this->plugin_name . "_google_maps_api_key"
		);

		add_option(
			$this->plugin_name . "_template_name"
		);
	}

	public function register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_google_maps_api_key"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_name"
		);

	} // register_settings()

	public function register_fields()
	{
		//API Settings
		add_settings_section(
			$this->plugin_name . '_settings_network_section',
			__( '365villas Network Settings', $this->plugin_name ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->plugin_name . '_google_maps_api_key_field',
			apply_filters( $this->plugin_name . '_label_google_maps_api_key', esc_html__( 'Google Maps API Key', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_settings_network_section',
			array(
				'label_for'		=> $this->plugin_name . '_google_maps_api_key_field',
				'description' 	=> 'Google Maps API key required for maps to function. This key will be used on every site, however a separate key can be specified on each site where required.',
				'id' 			=> $this->plugin_name . "_google_maps_api_key"
			)
		);

		$templateNames = [
			0 => [
				"label" => "",
				"value" => ""
			],
			1 => [
				"label" => "Template 1",
				"value" => "template_1"
			]
		];
		add_settings_field(
			$this->plugin_name . '_template_name_field',
			apply_filters( $this->plugin_name . '_label_template_name', esc_html__( 'Template style for shortcodes', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_settings_network_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_name_field',
				'description' 	=> 'The template to use for outputting styles from the shortcodes. This will be used on every site in this installation.',
				'id' 			=> $this->plugin_name . "_template_name",
				'selections'	=> $templateNames
			)
		);
	}

	public function section_messages( $params )
	{
		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name_base . '-admin-section-messages.php' );
	} // section_messages()

	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_text( $args )
	{
		$defaults['class'] 			= 'text widefat';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $args['id'];
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name_base . '-admin-field-text.php' );

	} // field_text()

	/**
	 * Creates an editor field
	 *
	 * NOTE: ID must only be lowercase letter, no spaces, dashes, or underscores.
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_editor( $args ) {

		$defaults['description'] 	= '';
		$defaults['settings'] 		= array( 'textarea_name' => $args['id'] );
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-editor-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name_base . '-admin-field-editor.php' );

	} // field_editor()

	/**
	 * Creates a select field
	 *
	 * Note: label is blank since its created in the Settings API
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_select( $args ) {

		$defaults['aria'] 			= '';
		$defaults['blank'] 			= '';
		$defaults['class'] 			= 'widefat';
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $args['id'];
		$defaults['selections'] 	= array();
		$defaults['value'] 			= 0;

		apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

			$atts['aria'] = $atts['description'];

		} elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

			$atts['aria'] = $atts['label'];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name_base . '-admin-field-select.php' );

	} // field_select()

	/**
	 * Creates a select field
	 *
	 * Note: label is blank since its created in the Settings API
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_roles_select( $args ) {

		$defaults['aria'] 			= '';
		$defaults['blank'] 			= '';
		$defaults['class'] 			= 'widefat';
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $args['id'];
		$defaults['selections'] 	= array();
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

			$atts['aria'] = $atts['description'];

		} elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

			$atts['aria'] = $atts['label'];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name_base . '-admin-field-roles-select.php' );

	} // field_select()

	/**
	 * Creates a checkbox field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_checkbox( $args ) {

		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $args['id'];
		$defaults['value'] 			= 0;

		apply_filters( $this->plugin_name . '-field-checkbox-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name_base . '-admin-field-checkbox.php' );

	} // field_checkbox()
	
}
