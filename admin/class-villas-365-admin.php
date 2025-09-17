<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Guestwisely
 * @subpackage Guestwisely/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Guestwisely
 * @subpackage Guestwisely/admin
 * @author     Your Name <email@example.com>
 */
class Villas_365_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/villas-365-admin.css', array(), $this->version, 'all' );

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

		wp_enqueue_script( $this->plugin_name . "_color-picker", plugin_dir_url( __FILE__ ) . 'js/tinyColorPicker/jqColorPicker.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/villas-365-admin.js', array( 'jquery', $this->plugin_name . "_color-picker" ), $this->version, false );

	}

	/**
	 * Sets the class variable $options
	 */
	private function set_options()
	{
		$this->options = [
			$this->plugin_name . "_owner_key_365_api" => get_option($this->plugin_name . "_owner_key_365_api"),
			$this->plugin_name . "_api_key_365_api" => get_option($this->plugin_name . "_api_key_365_api"),
			$this->plugin_name . "_api_password_365_api" => get_option($this->plugin_name . "_api_password_365_api"),
			$this->plugin_name . "_owner_username_365_api" => get_option($this->plugin_name . "_owner_username_365_api"),
			$this->plugin_name . "_owner_365_email" => get_option($this->plugin_name . "_owner_365_email"),
			$this->plugin_name . "_properties_per_row_in_list" => get_option($this->plugin_name . "_properties_per_row_in_list"),
			$this->plugin_name . "_properties_per_page_in_list" => get_option($this->plugin_name . "_properties_per_page_in_list"),
			$this->plugin_name . "_properties_list_view" => get_option($this->plugin_name . "_properties_list_view"),
			$this->plugin_name . "_properties_per_row_in_featured" => get_option($this->plugin_name . "_properties_per_row_in_featured"),
			$this->plugin_name . "_properties_total_in_featured" => get_option($this->plugin_name . "_properties_total_in_featured"),
			$this->plugin_name . "_properties_search_page_id" => get_option($this->plugin_name . "_properties_search_page_id"),
			$this->plugin_name . "_properties_discounts_page_id" => get_option($this->plugin_name . "_properties_discounts_page_id"),
			$this->plugin_name . "_properties_saved_page_id" => get_option($this->plugin_name . "_properties_saved_page_id"),
			$this->plugin_name . "_properties_open_in_new_tabs" => get_option($this->plugin_name . "_properties_open_in_new_tabs"),
			$this->plugin_name . "_properties_full_height_banner" => get_option($this->plugin_name . "_properties_full_height_banner"),
			$this->plugin_name . "_property_page_id" => get_option($this->plugin_name . "_property_page_id"),
			$this->plugin_name . "_contact_page_id" => get_option($this->plugin_name . "_contact_page_id"),
			$this->plugin_name . "_booking_page_id" => get_option($this->plugin_name . "_booking_page_id"),
			$this->plugin_name . "_google_maps_api_key" => get_option($this->plugin_name . "_google_maps_api_key"),
			$this->plugin_name . "_cache_365_api_calls" => get_option($this->plugin_name . "_cache_365_api_calls"),
			$this->plugin_name . "_cache_365_api_calls_duration" => get_option($this->plugin_name . "_cache_365_api_calls_duration"),
			$this->plugin_name . "_template_name" => get_option($this->plugin_name . "_template_name"),
			$this->plugin_name . "_template_color_primaryColour" => get_option($this->plugin_name . "_template_color_primaryColour"),
			$this->plugin_name . "_template_color_secondaryColour" => get_option($this->plugin_name . "_template_color_secondaryColour"),
			$this->plugin_name . "_template_color_buttonColour" => get_option($this->plugin_name . "_template_color_buttonColour"),
			$this->plugin_name . "_template_color_iconsColour" => get_option($this->plugin_name . "_template_color_iconsColour"),
			$this->plugin_name . "_template_color_searchLabelColour" => get_option($this->plugin_name . "_template_color_searchLabelColour"),
			$this->plugin_name . "_template_color_inputBorderColour" => get_option($this->plugin_name . "_template_color_inputBorderColour"),
			$this->plugin_name . "_template_color_propertyAmenitiesSwitcherColour" => get_option($this->plugin_name . "_template_color_propertyAmenitiesSwitcherColour"),
			$this->plugin_name . "_template_color_propertyRatesHeaderBackgoundColour" => get_option($this->plugin_name . "_template_color_propertyRatesHeaderBackgoundColour"),
			$this->plugin_name . "_template_color_propertyRatesHeaderTextColour" => get_option($this->plugin_name . "_template_color_propertyRatesHeaderTextColour"),
			$this->plugin_name . "_template_color_floaterBackgroundColour" => get_option($this->plugin_name . "_template_color_floaterBackgroundColour"),
			$this->plugin_name . "_template_color_floaterTextColour" => get_option($this->plugin_name . "_template_color_floaterTextColour"),
			$this->plugin_name . "_template_color_floaterHeaderBackgroundColour" => get_option($this->plugin_name . "_template_color_floaterHeaderBackgroundColour"),
			$this->plugin_name . "_template_color_floaterHeaderTextColour" => get_option($this->plugin_name . "_template_color_floaterHeaderTextColour"),
			$this->plugin_name . "_template_color_loginBackgroundColour" => get_option($this->plugin_name . "_template_color_loginBackgroundColour"),
			$this->plugin_name . "_template_color_calendarDateSelectedColour" => get_option($this->plugin_name . "_template_color_calendarDateSelectedColour"),
			$this->plugin_name . "_template_color_calendarDateSelectedTextColour" => get_option($this->plugin_name . "_template_color_calendarDateSelectedTextColour"),
			$this->plugin_name . "_template_color_calendarReservedDateColour" => get_option($this->plugin_name . "_template_color_calendarReservedDateColour"),
			$this->plugin_name . "_template_color_calendarReservedDateTextColour" => get_option($this->plugin_name . "_template_color_calendarReservedDateTextColour"),
			$this->plugin_name . "_template_color_featuredColour" => get_option($this->plugin_name . "_template_color_featuredColour"),
			$this->plugin_name . "_template_color_discountColour" => get_option($this->plugin_name . "_template_color_discountColour"),
			$this->plugin_name . "_template_color_search_buttonColour" => get_option($this->plugin_name . "_template_color_search_buttonColour"),
			$this->plugin_name . "_template_color_search_buttonTextColour" => get_option($this->plugin_name . "_template_color_search_buttonTextColour"),
			$this->plugin_name . "_template_color_search_buttonHoverColour" => get_option($this->plugin_name . "_template_color_search_buttonHoverColour"),
			$this->plugin_name . "_template_color_search_buttonHoverTextColour" => get_option($this->plugin_name . "_template_color_search_buttonHoverTextColour"),
			$this->plugin_name . "_365_google_tag_manager_head_script" => get_option($this->plugin_name . "_365_google_tag_manager_head_script"),
			$this->plugin_name . "_365_google_tag_manager_body_script" => get_option($this->plugin_name . "_365_google_tag_manager_body_script"),
			$this->plugin_name . "_365_setting_confirm_booking_page"   => get_option($this->plugin_name . "_365_setting_confirm_booking_page"),
			$this->plugin_name . "_detail_label"   => get_option($this->plugin_name . "_detail_label")
			
			
			
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
			'Guestwisely',
			'Guestwisely',
			'manage_options',
			'guestwisely',
			[$this, 'page_options'],
			'dashicons-admin-site'
		);
	} // add_menu()

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

			// Add the rewrite rules.
			Helpers365::AddRewriteRules(true);

			//Clear the colours CSS cache value.
			$templateFolder = Helpers365::GetTemplateFolder();
			$cacheKey = "colour-overrides_" . (!is_null($templateFolder) ? $templateFolder : "") . get_option("villas-365_owner_key_365_api");
			wp_cache_delete($cacheKey, Helpers365::SITE_CACHE_GROUP);
			
			//Generate the CSS overrides.
			$villas365CSSOverrides = Helpers365::GenerateCSSOverrides((!is_null($templateFolder) ? true : false));
		}

		// show error/update messages
 		settings_errors( $this->plugin_name . '_messages' );

		include( plugin_dir_path( __FILE__ ) . 'partials/villas-365-admin-page-settings.php' );
	} // page_options()


	public function register_options()
	{
		add_option(
			$this->plugin_name . "_owner_key_365_api"
		);

		add_option(
			$this->plugin_name . "_api_key_365_api"
		);

		add_option(
			$this->plugin_name . "_api_password_365_api"
		);

		add_option(
			$this->plugin_name . "_owner_username_365_api"
		);

		add_option(
			$this->plugin_name . "_owner_365_email"
		);

		add_option(
			$this->plugin_name . "_properties_per_row_in_list"
		);

		add_option(
			$this->plugin_name . "_properties_per_page_in_list"
		);

		add_option(
			$this->plugin_name . "_properties_list_view"
		);

		add_option(
			$this->plugin_name . "_properties_per_row_in_featured"
		);

		add_option(
			$this->plugin_name . "_properties_total_in_featured"
		);

		add_option(
			$this->plugin_name . "_properties_search_page_id"
		);

		add_option(
			$this->plugin_name . "_properties_discounts_page_id"
		);

		add_option(
			$this->plugin_name . "_properties_saved_page_id"
		);

		add_option(
			$this->plugin_name . "_properties_open_in_new_tabs"
		);

		add_option(
			$this->plugin_name . "_properties_full_height_banner"
		);

		add_option(
			$this->plugin_name . "_property_page_id"
		);

		add_option(
			$this->plugin_name . "_contact_page_id"
		);

		add_option(
			$this->plugin_name . "_booking_page_id"
		);

		add_option(
			$this->plugin_name . "_google_maps_api_key"
		);

		add_option(
			$this->plugin_name . "_cache_365_api_calls"
		);

		add_option(
			$this->plugin_name . "_cache_365_api_calls_duration"
		);

		add_option(
			$this->plugin_name . "_template_name"
		);

		add_option(
			$this->plugin_name . "_template_color_primaryColour"
		);

		add_option(
			$this->plugin_name . "_template_color_secondaryColour"
		);

		add_option(
			$this->plugin_name . "_template_color_buttonColour"
		);

		add_option(
			$this->plugin_name . "_template_color_iconsColour"
		);

		add_option(
			$this->plugin_name . "_template_color_searchLabelColour"
		);

		add_option(
			$this->plugin_name . "_template_color_inputBorderColour"
		);

		add_option(
			$this->plugin_name . "_template_color_propertyAmenitiesSwitcherColour"
		);

		add_option(
			$this->plugin_name . "_template_color_propertyRatesHeaderBackgoundColour"
		);

		add_option(
			$this->plugin_name . "_template_color_propertyRatesHeaderTextColour"
		);

		add_option(
			$this->plugin_name . "_template_color_floaterBackgroundColour"
		);

		add_option(
			$this->plugin_name . "_template_color_floaterTextColour"
		);

		add_option(
			$this->plugin_name . "_template_color_floaterHeaderBackgroundColour"
		);

		add_option(
			$this->plugin_name . "_template_color_floaterHeaderTextColour"
		);

		add_option(
			$this->plugin_name . "_template_color_loginBackgroundColour"
		);

		add_option(
			$this->plugin_name . "_template_color_calendarDateSelectedColour"
		);

		add_option(
			$this->plugin_name . "_template_color_calendarDateSelectedTextColour"
		);

		add_option(
			$this->plugin_name . "_template_color_calendarReservedDateColour"
		);

		add_option(
			$this->plugin_name . "_template_color_calendarReservedDateTextColour"
		);

		add_option(
			$this->plugin_name . "_template_color_featuredColour"
		);

		add_option(
			$this->plugin_name . "_template_color_discountColour"
		);

		add_option(
			$this->plugin_name . "_template_color_search_buttonColour"
		);

		add_option(
			$this->plugin_name . "_template_color_search_buttonTextColour"
		);

		add_option(
			$this->plugin_name . "_template_color_search_buttonHoverColour"
		);

		add_option(
			$this->plugin_name . "_template_color_search_buttonHoverTextColour"
		);

		add_option(
            $this->plugin_name . "_365_google_tag_manager_head_script"
		);

		add_option(
			$this->plugin_name . "_365_google_tag_manager_body_script"
		);

		add_option(
			$this->plugin_name . "_365_setting_confirm_booking_page"
		);

		add_option(
			$this->plugin_name . "_detail_label"
		);

	}

	public function register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_owner_key_365_api"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_api_key_365_api"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_api_password_365_api"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_owner_username_365_api"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_owner_365_email"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_per_row_in_list"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_per_page_in_list"
		);


		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_list_view"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_per_row_in_featured"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_total_in_featured"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_search_page_id"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_discounts_page_id"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_saved_page_id"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_open_in_new_tabs"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_properties_full_height_banner"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_property_page_id"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_contact_page_id"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_booking_page_id"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_google_maps_api_key"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_cache_365_api_calls"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_cache_365_api_calls_duration"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_name"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_primaryColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_secondaryColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_buttonColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_iconsColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_searchLabelColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_inputBorderColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_propertyAmenitiesSwitcherColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_propertyRatesHeaderBackgoundColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_propertyRatesHeaderTextColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_floaterBackgroundColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_floaterTextColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_floaterHeaderBackgroundColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_floaterHeaderTextColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_loginBackgroundColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_calendarDateSelectedColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_calendarDateSelectedTextColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_calendarReservedDateColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_calendarReservedDateTextColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_featuredColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_discountColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_search_buttonColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_search_buttonTextColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_search_buttonHoverColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_template_color_search_buttonHoverTextColour"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_365_google_tag_manager_head_script"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_365_google_tag_manager_body_script"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_365_setting_confirm_booking_page"
		);

		register_setting(
			$this->plugin_name,
			$this->plugin_name . "_detail_label"
		);


	} // register_settings()

	public function register_fields()
	{
		//API Settings
		add_settings_section(
			$this->plugin_name . '_api_section',
			__( '365villas API Settings', $this->plugin_name ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->plugin_name . '_owner_key_365_api_field',
			apply_filters( $this->plugin_name . '_label_owner_key_365_api', esc_html__( '365villas API Owner Token', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_api_section',
			array(
				'label_for'		=> $this->plugin_name . '_owner_key_365_api_field',
				'description' 	=> 'Used to connect to the 365villas API to access your properties.',
				'id' 			=> $this->plugin_name . "_owner_key_365_api"
			)
		);

		add_settings_field(
			$this->plugin_name . '_api_key_365_api_field',
			apply_filters( $this->plugin_name . '_label_api_key_365_api', esc_html__( '365villas API Key', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_api_section',
			array(
				'label_for'		=> $this->plugin_name . '_api_key_365_api_field',
				'description' 	=> 'Used to connect to the 365villas API to access your pricate properties data.',
				'id' 			=> $this->plugin_name . "_api_key_365_api"
			)
		);

		add_settings_field(
			$this->plugin_name . '_api_password_365_api_field',
			apply_filters( $this->plugin_name . '_label_api_password_365_api', esc_html__( '365villas API Password', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_api_section',
			array(
				'label_for'		=> $this->plugin_name . '_api_password_365_api_field',
				'description' 	=> 'Used to connect to the 365villas API to access your properties data.',
				'id' 			=> $this->plugin_name . "_api_password_365_api"
			)
		);

		add_settings_field(
			$this->plugin_name . '_owner_username_365_api_field',
			apply_filters( $this->plugin_name . '_label_owner_username_365_api', esc_html__( '365villas API Owner Username', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_api_section',
			array(
				'label_for'		=> $this->plugin_name . '_owner_username_365_api_field',
				'description' 	=> 'Used to connect to the 365villas API to access your properties.',
				'id' 			=> $this->plugin_name . "_owner_username_365_api"
			)
		);

		add_settings_field(
			$this->plugin_name . '_owner_365_email_field',
			apply_filters( $this->plugin_name . '_label_owner_365_email', esc_html__( '365villas Email Address', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_api_section',
			array(
				'label_for'		=> $this->plugin_name . '_owner_365_email_field',
				'description' 	=> 'Used to connect to send form messages to the 365 application.',
				'id' 			=> $this->plugin_name . "_owner_365_email"
			)
		);

		//Property Search Settings
		add_settings_section(
			$this->plugin_name . '_property_search_section',
			__( 'Properties Search Settings', $this->plugin_name ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

		$allPagesForSelect = get_pages();
		$pagesForSelect = [
			0 => [
				"label" => "",
				"value" => 0
			]
		];
		if(!is_null($allPagesForSelect) && is_array($allPagesForSelect) && (count($allPagesForSelect) > 0))
		{
			foreach($allPagesForSelect as $pageForSelect)
			{
				$pagesForSelect[] = [
					"label" => $pageForSelect->post_title,
					"value" => $pageForSelect->ID
				];
			}
		}

		add_settings_field(
			$this->plugin_name . '_properties_search_page_id_field',
			apply_filters( $this->plugin_name . '_label_properties_search_page_id', esc_html__( 'Which page to use for search results', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_property_search_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_search_page_id_field',
				'description' 	=> 'Which page to use for search results',
				'id' 			=> $this->plugin_name . "_properties_search_page_id",
				'selections'	=> $pagesForSelect
			)
		);

		add_settings_field(
			$this->plugin_name . '_properties_discounts_page_id_field',
			apply_filters( $this->plugin_name . '_label_properties_discounts_page_id', esc_html__( 'Which page to use for the discounts list', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_property_search_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_discounts_page_id_field',
				'description' 	=> 'Which page to use for the discounts list',
				'id' 			=> $this->plugin_name . "_properties_discounts_page_id",
				'selections'	=> $pagesForSelect
			)
		);

		add_settings_field(
			$this->plugin_name . '_properties_saved_page_id_field',
			apply_filters( $this->plugin_name . '_label_properties_saved_page_id', esc_html__( 'Which page to use for the saved properties list', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_property_search_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_saved_page_id_field',
				'description' 	=> 'Which page to use for the saved properties list',
				'id' 			=> $this->plugin_name . "_properties_saved_page_id",
				'selections'	=> $pagesForSelect
			)
		);

		add_settings_field(
			$this->plugin_name . '_property_page_id_field',
			apply_filters( $this->plugin_name . '_label_property_page_id', esc_html__( 'Which page to use for property details', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_property_search_section',
			array(
				'label_for'		=> $this->plugin_name . '_property_page_id_field',
				'description' 	=> 'Which page to use for search results',
				'id' 			=> $this->plugin_name . "_property_page_id",
				'selections'	=> $pagesForSelect
			)
		);

		add_settings_field(
			$this->plugin_name . '_properties_open_in_new_tabs_field',
			apply_filters( $this->plugin_name . '_label_properties_open_in_new_tabs', esc_html__( 'Open properties in new tabs', $this->plugin_name ) ),
			array( $this, 'field_checkbox' ),
			$this->plugin_name,
			$this->plugin_name . '_property_search_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_open_in_new_tabs_field',
				'description' 	=> 'Should links to properties across the site open in new tabs?',
				'id' 			=> $this->plugin_name . "_properties_open_in_new_tabs"
			)
		);

		add_settings_field(
			$this->plugin_name . '_properties_full_height_banner_field',
			apply_filters( $this->plugin_name . '_label_properties_full_height_banner', esc_html__( 'Full screen height property page banner ', $this->plugin_name ) ),
			array( $this, 'field_checkbox' ),
			$this->plugin_name,
			$this->plugin_name . '_property_search_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_full_height_banner_field',
				'description' 	=> 'Should the property details page banner be the full screen height?',
				'id' 			=> $this->plugin_name . "_properties_full_height_banner"
			)
		);
       

		//Properties List Settings
		add_settings_section(
			$this->plugin_name . '_properties_list_section',
			__( 'Properties List Settings', $this->plugin_name ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->plugin_name . '_properties_per_row_in_list_field',
			apply_filters( $this->plugin_name . '_label_properties_per_row_in_list', esc_html__( 'Number of properties per row', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_properties_list_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_per_row_in_list_field',
				'description' 	=> 'How many properties to show in each row in the property list screens.',
				'id' 			=> $this->plugin_name . "_properties_per_row_in_list"
			)
		);

		add_settings_field(
			$this->plugin_name . '_properties_per_page_in_list_field',
			apply_filters( $this->plugin_name . '_label_properties_per_page_in_list', esc_html__( 'Number of properties per page', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_properties_list_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_per_page_in_list_field',
				'description' 	=> 'How many properties to show on each page in the property list screens.',
				'id' 			=> $this->plugin_name . "_properties_per_page_in_list"
			)
		);


		$listViewOptions = [
			0 => [
				"label" => "Grid",
				"value" => "grid"
			],
			1 => [
				"label" => "List",
				"value" => "list"
			],
			2 => [
				"label" => "Map",
				"value" => "map"
			]
		];
		add_settings_field(
			$this->plugin_name . '_properties_list_view_field',
			apply_filters( $this->plugin_name . '_label_properties_list_view', esc_html__( 'Default list view', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_properties_list_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_list_view_field',
				'description' 	=> 'The default list view (grid, list, map).',
				'id' 			=> $this->plugin_name . "_properties_list_view",
				'selections'	=> $listViewOptions
			)
		);

		//Featured Properties Settings
		add_settings_section(
			$this->plugin_name . '_properties_featured_section',
			__( 'Featured Properties Settings', $this->plugin_name ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->plugin_name . '_properties_per_row_in_featured_field',
			apply_filters( $this->plugin_name . '_label_properties_per_row_in_featured', esc_html__( 'Number of properties per row', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_properties_featured_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_per_row_in_featured_field',
				'description' 	=> 'How many properties to show in each row in the featured properties lists.',
				'id' 			=> $this->plugin_name . "_properties_per_row_in_featured"
			)
		);

		add_settings_field(
			$this->plugin_name . '_properties_total_in_featured_field',
			apply_filters( $this->plugin_name . '_label_properties_total_in_featured', esc_html__( 'Total number of properties', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_properties_featured_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_total_in_featured_field',
				'description' 	=> 'How many properties to show in the featured properties lists.',
				'id' 			=> $this->plugin_name . "_properties_total_in_featured"
			)
		);

		add_settings_section(
			$this->plugin_name . '_other_section',
			__( 'Other Settings', $this->plugin_name ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

		add_settings_field(
			$this->plugin_name . '_contact_page_id_field',
			apply_filters( $this->plugin_name . '_label_contact_page_id', esc_html__( 'Which page to use for the contact page', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_contact_page_id_field',
				'description' 	=> 'Which page to use for the contact page',
				'id' 			=> $this->plugin_name . "_contact_page_id",
				'selections'	=> $pagesForSelect
			)
		);

		add_settings_field(
			$this->plugin_name . '_booking_page_id_field',
			apply_filters( $this->plugin_name . '_label_booking_page_id', esc_html__( 'Which page to use for the booking page', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_booking_page_id_field',
				'description' 	=> 'Which page to use for the booking page',
				'id' 			=> $this->plugin_name . "_booking_page_id",
				'selections'	=> $pagesForSelect
			)
		);

		//Check if we have a network level setting and adjust the description as required.
		$googleMapsAPIDescription = "This is required for Google Maps to function.";
		$optionValue = get_site_option($this->plugin_name . "-network_google_maps_api_key");
		if($optionValue !== FALSE)
		{
			$googleMapsAPIDescription = "This is an optional setting. Adding your own Google Maps API key will override the global Google Maps API key used for all 365villas sites.";
		}

		add_settings_field(
			$this->plugin_name . '_google_maps_api_key_field',
			apply_filters( $this->plugin_name . '_label_google_maps_api_key', esc_html__( 'Google Maps API Key', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_google_maps_api_key_field',
				'description' 	=> $googleMapsAPIDescription,
				'id' 			=> $this->plugin_name . "_google_maps_api_key"
			)
		);

		add_settings_field(
			$this->plugin_name . '_cache_365_api_calls_field',
			apply_filters( $this->plugin_name . '_label_cache_365_api_calls', esc_html__( 'Cache 365 data', $this->plugin_name ) ),
			array( $this, 'field_checkbox' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_cache_365_api_calls_field',
				'description' 	=> 'Should data from the 365 application be cached? This will help improve performance of the site. Caching will reduce the number of times the 365 application is queried. A longer time will mean data is not updated as often but should improve load times.',
				'id' 			=> $this->plugin_name . "_cache_365_api_calls"
			)
		);

		$cacheDurations = [
			0 => [
				"label" => "",
				"value" => 0
			],
			1 => [
				"label" => "5 minutes",
				"value" => 300
			],
			2 => [
				"label" => "30 minutes",
				"value" => 800
			],
			3 => [
				"label" => "1 hour",
				"value" => 3600
			],
			4 => [
				"label" => "2 hours",
				"value" => 7200
			],
			5 => [
				"label" => "3 hours",
				"value" => 10800
			],
			6 => [
				"label" => "4 hours",
				"value" => 14400
			],
			7 => [
				"label" => "5 hours",
				"value" => 18000
			],
			8 => [
				"label" => "6 hours",
				"value" => 21600
			],
			9 => [
				"label" => "12 hours",
				"value" => 43200
			],
			10 => [
				"label" => "24 hours",
				"value" => 86400
			]
		];
		add_settings_field(
			$this->plugin_name . '_cache_365_api_calls_duration_field',
			apply_filters( $this->plugin_name . '_label_cache_365_api_calls_duration', esc_html__( '365 data cache duration', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_cache_365_api_calls_duration_field',
				'description' 	=> 'How long the 365 data should be cached for before it is refreshed. (Default is 1 hour). This only applies if caching is turned on above.',
				'id' 			=> $this->plugin_name . "_cache_365_api_calls_duration",
				'selections'	=> $cacheDurations
			)
		);

		$optionValue = get_site_option($this->plugin_name . "-network_template_name");
		if($optionValue === FALSE)
		{
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
				$this->plugin_name . '_other_section',
				array(
					'label_for'		=> $this->plugin_name . '_template_name_field',
					'description' 	=> 'The template to use for outputting styles from the shortcodes.',
					'id' 			=> $this->plugin_name . "_template_name",
					'selections'	=> $templateNames
				)
			);
		}
		
		add_settings_field(
			$this->plugin_name . '_template_color_primaryColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_primaryColour', esc_html__( 'Template Primary Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_primaryColour_field',
				'id' 			=> $this->plugin_name . "_template_color_primaryColour",
				'colour'		=> "primaryColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_secondaryColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_secondaryColour', esc_html__( 'Template Secondary Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_secondaryColour_field',
				'id' 			=> $this->plugin_name . "_template_color_secondaryColour",
				'colour'		=> "secondaryColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_buttonColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_buttonColour', esc_html__( 'Template Button Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_buttonColour_field',
				'id' 			=> $this->plugin_name . "_template_color_buttonColour",
				'colour'		=> "buttonColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_iconsColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_iconsColour', esc_html__( 'Template Icons Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_iconsColour_field',
				'id' 			=> $this->plugin_name . "_template_color_iconsColour",
				'colour'		=> "iconsColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_searchLabelColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_searchLabelColour', esc_html__( 'Template Search Label Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_searchLabelColour_field',
				'id' 			=> $this->plugin_name . "_template_color_searchLabelColour",
				'colour'		=> "searchLabelColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_inputBorderColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_inputBorderColour', esc_html__( 'Template Form Input Border Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_inputBorderColour_field',
				'id' 			=> $this->plugin_name . "_template_color_inputBorderColour",
				'colour'		=> "inputBorderColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_propertyAmenitiesSwitcherColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_propertyAmenitiesSwitcherColour', esc_html__( 'Template Property Amenities Switcher Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_propertyAmenitiesSwitcherColour_field',
				'id' 			=> $this->plugin_name . "_template_color_propertyAmenitiesSwitcherColour",
				'colour'		=> "propertyAmenitiesSwitcherColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_propertyRatesHeaderBackgoundColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_propertyRatesHeaderBackgoundColour', esc_html__( 'Template Property Rates Header Background Color (does not apply to all themes)', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_propertyRatesHeaderBackgoundColour_field',
				'id' 			=> $this->plugin_name . "_template_color_propertyRatesHeaderBackgoundColour",
				'colour'		=> "propertyRatesHeaderBackgoundColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_propertyRatesHeaderTextColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_propertyRatesHeaderTextColour', esc_html__( 'Template Property Rates Header Text Color (does not apply to all themes)', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_propertyRatesHeaderTextColour_field',
				'id' 			=> $this->plugin_name . "_template_color_propertyRatesHeaderTextColour",
				'colour'		=> "propertyRatesHeaderTextColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_floaterBackgroundColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_floaterBackgroundColour', esc_html__( 'Template Property Floater Background Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_floaterBackgroundColour_field',
				'id' 			=> $this->plugin_name . "_template_color_floaterBackgroundColour",
				'colour'		=> "floaterBackgroundColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_floaterTextColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_floaterTextColour', esc_html__( 'Template Property Floater Text Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_floaterTextColour_field',
				'id' 			=> $this->plugin_name . "_template_color_floaterTextColour",
				'colour'		=> "floaterTextColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_floaterHeaderBackgroundColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_floaterHeaderBackgroundColour', esc_html__( 'Template Property Floater Header Background Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_floaterHeaderBackgroundColour_field',
				'id' 			=> $this->plugin_name . "_template_color_floaterHeaderBackgroundColour",
				'colour'		=> "floaterHeaderBackgroundColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_floaterHeaderTextColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_floaterHeaderTextColour', esc_html__( 'Template Property Floater Header Text Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_floaterHeaderTextColour_field',
				'id' 			=> $this->plugin_name . "_template_color_floaterHeaderTextColour",
				'colour'		=> "floaterHeaderTextColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_loginBackgroundColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_loginBackgroundColour', esc_html__( 'Template 365 Login Background Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_loginBackgroundColour_field',
				'id' 			=> $this->plugin_name . "_template_color_loginBackgroundColour",
				'colour'		=> "loginBackgroundColour"
			)
		);
		
		add_settings_field(
			$this->plugin_name . '_template_color_calendarDateSelectedColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_calendarDateSelectedColour', esc_html__( 'Template Calendar Selected Date Color (in search and property floater)', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_calendarDateSelectedColour_field',
				'id' 			=> $this->plugin_name . "_template_color_calendarDateSelectedColour",
				'colour'		=> "calendarDateSelectedColour"
			)
		);
		
		add_settings_field(
			$this->plugin_name . '_template_color_calendarDateSelectedTextColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_calendarDateSelectedTextColour', esc_html__( 'Template Calendar Selected Date Text Color (in search and property floater)', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_calendarDateSelectedTextColour_field',
				'id' 			=> $this->plugin_name . "_template_color_calendarDateSelectedTextColour",
				'colour'		=> "calendarDateSelectedTextColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_calendarReservedDateColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_calendarReservedDateColour', esc_html__( 'Template Calendar Reserved Date Color (in search and property floater)', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_calendarReservedDateColour_field',
				'id' 			=> $this->plugin_name . "_template_color_calendarReservedDateColour",
				'colour'		=> "calendarReservedDateColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_calendarReservedDateTextColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_calendarReservedDateTextColour', esc_html__( 'Template Calendar Reserved Date Text Color (in search and property floater)', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_calendarReservedDateTextColour_field',
				'id' 			=> $this->plugin_name . "_template_color_calendarReservedDateTextColour",
				'colour'		=> "calendarReservedDateTextColour"
			)
		);
		
		add_settings_field(
			$this->plugin_name . '_template_color_featuredColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_featuredColour', esc_html__( 'Template Featured Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_featuredColour_field',
				'id' 			=> $this->plugin_name . "_template_color_featuredColour",
				'colour'		=> "featuredColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_discountColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_discountColour', esc_html__( 'Template Discount Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_discountColour_field',
				'id' 			=> $this->plugin_name . "_template_color_discountColour",
				'colour'		=> "discountColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_search_buttonColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_search_buttonColour', esc_html__( 'Search Button Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_search_buttonColour_field',
				'id' 			=> $this->plugin_name . "_template_color_search_buttonColour",
				'colour'		=> "searchButtonColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_search_buttonTextColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_search_buttonTextColour', esc_html__( 'Search Button Text Color', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_search_buttonTextColour_field',
				'id' 			=> $this->plugin_name . "_template_color_search_buttonTextColour",
				'colour'		=> "searchButtonTextColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_search_buttonHoverColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_search_buttonHoverColour', esc_html__( 'Search Button Hover Colour', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_search_buttonHoverColour_field',
				'id' 			=> $this->plugin_name . "_template_color_search_buttonHoverColour",
				'colour'		=> "searchButtonHoverColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_template_color_search_buttonHoverTextColour_field',
			apply_filters( $this->plugin_name . '_label_template_color_search_buttonHoverTextColour', esc_html__( 'Search Button Hover Text Colour', $this->plugin_name ) ),
			array( $this, 'field_color' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_template_color_search_buttonHoverTextColour_field',
				'id' 			=> $this->plugin_name . "_template_color_search_buttonHoverTextColour",
				'colour'		=> "searchButtonHoverTextColour"
			)
		);

		add_settings_field(
			$this->plugin_name . '_google_tag_manager_head_script_field',
			apply_filters( $this->plugin_name . '_label_google_tag_manager_head_script_field', esc_html__( 'Google Tag Manager head script', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_google_tag_manager_head_script_field',
				'description' 	=> 'Add code to the < head > of your website (good for tracking codes such as google tag manager)',
				'id' 			=> $this->plugin_name . "_365_google_tag_manager_head_script"
			)
		);


		add_settings_field(
			$this->plugin_name . '_google_tag_manager_body_script_field',
			apply_filters( $this->plugin_name . '_label_google_maps_api_key', esc_html__( 'Google Tag Manager after body script', $this->plugin_name ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '_other_section',
			array(
				'label_for'		=> $this->plugin_name . '_google_tag_manager_head_script_field',
				'description' 	=> 'Add code to the < body > (good for tracking codes such as google tag manager)',
				'id' 			=> $this->plugin_name . "_365_google_tag_manager_body_script"
			)
		);

		add_settings_field(
			$this->plugin_name . '_properties_confirm_booking_page_id_field',
			apply_filters( $this->plugin_name . '_label_properties_confirm_booking_page_id', esc_html__( 'Which page to use for confirm booking', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_property_search_section',
			array(
				'label_for'		=> $this->plugin_name . '_properties_confirm_booking_page_id_field',
				'description' 	=> 'Which page to use for confirm booking',
				'id' 			=> $this->plugin_name . "_365_setting_confirm_booking_page",
				'selections'	=> $pagesForSelect
			)
		);

		$listLabelOptions = [
			0 => [
				"label" => "Reserve",
				"value" => "Reserve"
			],
			1 => [
				"label" => "Enquire",
				"value" => "Enquire"
			],
			2 => [
				"label" => "Inquire",
				"value" => "Inquire"
			],
			3 => [
				"label" => "Book",
				"value" => "Book"
			],
			4 => [
				"label" => "View",
				"value" => "View"
			]
		];

		add_settings_field(
			$this->plugin_name . '_detail_label',
			apply_filters( $this->plugin_name . '_label_detail', esc_html__( 'Label detail button', $this->plugin_name ) ),
			array( $this, 'field_select' ),
			$this->plugin_name,
			$this->plugin_name . '_properties_list_section',
			array(
				'label_for'		=> $this->plugin_name . '_detail_label',
				'id' 			=> $this->plugin_name . "_detail_label",
				'selections'	=> $listLabelOptions
			)
		);
	}

	public function section_messages( $params )
	{
		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-section-messages.php' );
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

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

	} // field_text()

	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_color( $args )
	{
		$defaults['class'] 			= 'text regular-text color-picker';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $args['id'];
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';
		$defaults['data-color-mode'] = 'HEX';

		$defaultColors = Helpers365::GetDefaultColors();
		$defaults['value'] = $defaultColors[$args['colour']];

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-color.php' );

	} // field_text()

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

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-select.php' );

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

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php' );

	} // field_checkbox()

	//Send the admin notification to the users 365 email address.
	function change_gravity_forms_notification_email( $notification )
	{
		//If we don't have a value then return.
		//get_blog_option only work on multisite
		if(is_multisite()) {
			$owner365Email = get_blog_option(get_current_blog_id(), $this->plugin_name . "_owner_365_email");
		} else {
			$owner365Email = get_option( $this->plugin_name . "_owner_365_email" );
		}

		if($owner365Email === FALSE)
		{
			return $notification;
		}

		//There is no concept of admin notifications anymore, so we will need to target notifications based on other criteria, such as name
		if($notification['name'] == 'Admin Notification')
		{
			// toType can be routing or email
			$notification['toType'] = 'email';
			$notification['to'] = $owner365Email;
			if(is_multisite()) {
				$notification['from'] = get_blog_option(get_current_blog_id(), $this->plugin_name . "_owner_username_365_api") . '@5ba1-3-442a-9ad0-bookings.com';
			}
		}
	
		return $notification;
	}
}
