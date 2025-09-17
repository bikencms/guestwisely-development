<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Villas_365
 * @subpackage Villas_365/public
 * @author     Your Name <email@example.com>
 */
class Villas_365_Public {

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
		 * defined in Villas_365_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Villas_365_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/villas-365-public.css', array(), $this->version, 'all' );

	}
    /**
	 * Register the JavaScript for the google tag manager script in header tag of the site.
	 *
	 * @since    1.0.0
	 */
	public function _365_google_tag_manager_head_script() {
		$google_tag_manager_head_script = get_option( 'villas-365_365_google_tag_manager_head_script', '' );
		if(isset($google_tag_manager_head_script) && !empty($google_tag_manager_head_script)) {
			 $google_tag_manager_head_script = str_replace("noscript","script",$google_tag_manager_head_script);
			 echo $google_tag_manager_head_script;
		}
	}

    /**
	 * Register the JavaScript for the google tag manager script after body tag of the site.
	 *
	 * @since    1.0.0
	 */
	public function _365_google_tag_manager_after_body_script() {
		$google_tag_manager_after_body_script = get_option('villas-365_365_google_tag_manager_body_script','');
		if(isset($google_tag_manager_after_body_script) && !empty($google_tag_manager_after_body_script)) {
           echo $google_tag_manager_after_body_script;
		}
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
		 * defined in Villas_365_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Villas_365_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/villas-365-public.js', array( 'jquery' ), $this->version, false );
		// $google_tag_manager_head_script = get_option( 'villas-365_365_google_tag_manager_head_script', '' );
		$google_tag_manager_after_body_script = get_option('villas-365_365_google_tag_manager_body_script','');
        // if(isset($google_tag_manager_head_script) && !empty($google_tag_manager_head_script)) {
		// 	print_r($google_tag_manager_head_script);
		// }

	}

	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {
		add_shortcode( '365-villas-properties-list', array( $this, 'propertiesList' ) );
		add_shortcode( '365-villas-property', array( $this, 'property' ) );
		add_shortcode( '365-villas-featured-properties', array( $this, 'featuredProperties' ) );
		add_shortcode( '365-villas-search', array( $this, 'search' ) );
		add_shortcode( '365-villas-category-grid', array( $this, 'categoryGrid' ) );
		add_shortcode( '365-villas-booking', array( $this, 'booking' ) );
		add_shortcode( '365-villas-reviews', array( $this, 'reviews' ) );
		add_shortcode( '365-villas-login', array( $this, 'login' ) );
		add_shortcode( '365-villas-language-switcher', array( $this, 'languageSwitcher' ) );
		add_shortcode( '365-villas-saved-properties', array( $this, 'savedProperties' ) );

		add_shortcode( 'guestwisely-search', array( $this, 'search' ) );
		add_shortcode( 'guestwisely-category-grid', array( $this, 'categoryGrid' ) );
		add_shortcode( 'guestwisely-properties-list', array( $this, 'propertiesList' ) );
		add_shortcode( 'guestwisely-property', array( $this, 'property' ) );
		add_shortcode( 'guestwisely-featured-properties', array( $this, 'featuredProperties' ) );
		
		add_shortcode( 'guestwisely-booking', array( $this, 'booking' ) );
		add_shortcode( 'guestwisely-reviews', array( $this, 'reviews' ) );
		add_shortcode( 'guestwisely-login', array( $this, 'login' ) );
		add_shortcode( 'guestwisely-language-switcher', array( $this, 'languageSwitcher' ) );
		add_shortcode( 'guestwisely-saved-properties', array( $this, 'savedProperties' ) );

	} // register_shortcodes()


	/**
	 * Registers all rewrites at once
	 *
	 * @return [type] [description]
	 */
	public function register_rewrites() {

		Helpers365::AddRewriteRules();

	} // register_rewrites()


	/**
	 * Calls the 365villas API to calculate a temporary booking.
	 * This is a function for an AJAX request.
	 */
	public function calculate_booking()
	{
		//Check the nonce.
		check_ajax_referer('_villas_365_calculate_booking');

		//Handle the request.
		$propertyId = array_key_exists("propertyId", $_POST) && !is_null($_POST["propertyId"]) ? $_POST["propertyId"] : null;
		$checkIn = array_key_exists("checkIn", $_POST) && !is_null($_POST["checkIn"]) ? $_POST["checkIn"] : null;
		$checkOut = array_key_exists("checkOut", $_POST) && !is_null($_POST["checkOut"]) ? $_POST["checkOut"] : null;
		$adults = array_key_exists("adults", $_POST) && !is_null($_POST["adults"]) ? $_POST["adults"] : null;
		$children = array_key_exists("children", $_POST) && !is_null($_POST["children"]) ? $_POST["children"] : null;

		if(!is_null($propertyId) && ($propertyId !== "") &&
			!is_null($checkIn) && ($checkIn !== "") &&
			!is_null($checkOut) && ($checkOut !== "") &&
			!is_null($adults) && ($adults !== ""))
		{
			$booking = API365::GetTemporaryBooking($propertyId, $checkIn, $checkOut, $adults, $children);
			
			if(!is_null($booking) && is_array($booking) && array_key_exists("status", $booking) && array_key_exists("message", $booking) && ($booking["status"] == "error") && ($booking["message"] !== ""))
			{
				wp_send_json_success([
					"status" => "error",
					"message" => $booking["message"]
				]);
			}

			if(is_null($booking) || !property_exists($booking, "grandTotal") || !property_exists($booking, "currencySymbol"))
			{
				wp_send_json_error([
					"status" => "error"
				]);
			}

			$bookingTotal = $booking->currencySymbol . number_format($booking->grandTotal, 2, ".", ",");
			if(property_exists($booking, "grandTotalBeforeDiscounted") && ($booking->grandTotal !== $booking->grandTotalBeforeDiscounted))
			{
				$bookingTotal = "<s>" . $booking->currencySymbol . number_format($booking->grandTotalBeforeDiscounted, 2, ".", ",") . "</s>&nbsp;&nbsp;" . $bookingTotal;
			}

			$discountTotal = null;
			if(!is_null($booking->discountTotal) && is_numeric($booking->discountTotal) && ($booking->discountTotal > 0))
			{
				$discountTotal = "&minus;&nbsp;" . $booking->currencySymbol . number_format($booking->discountTotal, 2, ".", ",");
			}

			$totalNights = $booking->qtyofnights;
			$minimumNights = $booking->minimumStay;
			$numberOfNightsOk = "true";
			$perNight = $booking->currencySymbol . $booking->perNight;
			$rentTotal = $booking->currencySymbol . number_format($booking->totalRent, 2, ".", ",");
			$taxTotal = $booking->currencySymbol . number_format($booking->taxTotal, 2, ".", ",");
			$serviceTotal = $booking->currencySymbol . number_format($booking->serviceTotal, 2, ".", ",");
			$discount = $booking->discount;

			if($totalNights < $minimumNights)
			{
				$numberOfNightsOk = "false";
			}

			wp_send_json_success([
				"status" => "success",
				"total" => $bookingTotal,
				"totalNights" => $totalNights,
				"perNight" => $perNight,
				"rentTotal" => $rentTotal,
				"taxTotal" => $taxTotal,
				"serviceTotal" => $serviceTotal,
				"discountTotal" => $discountTotal,
				"minimumNights" => $minimumNights,
				"numberOfNightsOk" => $numberOfNightsOk,
				"discount" => $discount
			]);
		}
		
		wp_send_json_error([
			"status" => "error"
		]);
	} // calculate_booking()

	/**
	 * Processes shortcode 365-villas-properties-list
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function propertiesList($atts = [], $content = null, $tag = '')
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		//Get the default values from the settings
		$optionVillas365PropertiesPerRowInList = get_option("villas-365_properties_per_row_in_list");
		$optionVillas365PropertiesPerPageInList = get_option("villas-365_properties_per_page_in_list");
        
		$propertiesPerRow = 3;
		if(!is_null($optionVillas365PropertiesPerRowInList) && (trim($optionVillas365PropertiesPerRowInList) !== "") && is_numeric($optionVillas365PropertiesPerRowInList))
		{
			$propertiesPerRow = $optionVillas365PropertiesPerRowInList;
		}

		$propertiesPerPage = 10;
		$propertiesPerLandingPage = 10;
		if(!is_null($optionVillas365PropertiesPerPageInList) && (trim($optionVillas365PropertiesPerPageInList) !== "") && is_numeric($optionVillas365PropertiesPerPageInList))
		{
			$propertiesPerPage = $optionVillas365PropertiesPerPageInList;
		}


		//Get the default values from the settings
		$optionVillas365PropertyPageId = get_option("villas-365_property_page_id");
		$propertyPageId = null;
		if(!is_null($optionVillas365PropertyPageId) && (trim($optionVillas365PropertyPageId) !== "") && is_numeric($optionVillas365PropertyPageId))
		{
			$propertyPageId = $optionVillas365PropertyPageId;
		}

		$optionVillas365PropertiesSearchPageId = get_option("villas-365_properties_search_page_id");
		$propertiesSearchPageId = null;
		if(!is_null($optionVillas365PropertiesSearchPageId) && (trim($optionVillas365PropertiesSearchPageId) !== "") && is_numeric($optionVillas365PropertiesSearchPageId))
		{
			$propertiesSearchPageId = $optionVillas365PropertiesSearchPageId;
		}

		$optionVillas365PropertiesDiscountsPageId = get_option("villas-365_properties_discounts_page_id");
		$propertiesDiscountsPageId = null;
		if(!is_null($optionVillas365PropertiesDiscountsPageId) && (trim($optionVillas365PropertiesDiscountsPageId) !== "") && is_numeric($optionVillas365PropertiesDiscountsPageId))
		{
			$propertiesDiscountsPageId = $optionVillas365PropertiesDiscountsPageId;
		}

		$optionVillas365PropertiesListView = get_option("villas-365_properties_list_view");
		$propertiesListView = "list";
		$validListViews = ["list", "grid", "map"];
		if(!is_null($optionVillas365PropertiesListView) && (trim($optionVillas365PropertiesListView) !== "") && in_array($optionVillas365PropertiesListView, $validListViews))
		{
			$propertiesListView = $optionVillas365PropertiesListView;
		}
		
		if(!is_null($atts) && is_array($atts) && array_key_exists("defaultquery", $atts) && ($atts["defaultquery"] !== ""))
		{
			$atts["defaultquery"] = Helpers365::QueryStringToArray($atts["defaultquery"]);
		}

		// override default attributes with user attributes
		$propertiesList_atts = shortcode_atts([
			'perrow' => $propertiesPerRow,
			'perpage' => $propertiesPerPage,
			'propertypageid' => $propertyPageId,
			'propertiessearchpageid' => $propertiesSearchPageId,
			'propertiesdiscountspageid' => $propertiesDiscountsPageId,
			'usecurrentpageforsearch' => false,
			'discountsonly' => false,
			'showsavebutton' => false,
			'showdiscountlabel' => true,
			'showfeaturedlabel' => true,
			'showdiscounttab'   => true, 
			'showfeaturetab'    => true,
			'view' => $propertiesListView,
			'mapstyle' => 'default',
			'defaultquery' => null
		], $atts, $tag);


		

		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-properties-list.php';
		if(array_key_exists("template", $atts) && !is_null($atts["template"]) && (trim($atts["template"]) !== ""))
		{
			$templateFolder = Helpers365::GetTemplateFolder();
			$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $templateFolder . '/' . $this->plugin_name . '-properties-list.php';
		}

		ob_start();

		if(file_exists($filePath))
		{
			include( $filePath );
		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // propertiesList()

	/**
	 * Processes shortcode 365-villas-property
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function property($atts = [], $content = null, $tag = '')
	{
		if(!isset($atts) || is_null($atts) || !is_array($atts) || ($atts == ""))
		{
			$atts = [];
		}
		
		//Set the default partial path to the full property page.
		$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-data.php';

		$sectionData = [
			"value" => null
		];

		//If we have an attribute that specifies a part of the property page then just output that part.
		if(array_key_exists("section", $atts))
		{
			$propertySlug = null;
			if(array_key_exists("propertyslug", $atts) && !is_null($atts["propertyslug"]) && (trim($atts["propertyslug"]) !== ""))
			{
				$propertySlug = $atts["propertyslug"];
			}
			else
			{
				$propertySlug = Helpers365::GetPropertySlug();
			}

			$property = null;
			$propertyUrl = null;
			if(!is_null($propertySlug))
			{
				$amenitiesSpecificRooms = true;
				if(array_key_exists("amenitiesspecificrooms", $atts) &&
					!is_null($atts["amenitiesspecificrooms"]) &&
					(trim($atts["amenitiesspecificrooms"]) != "") &&
					((!is_bool($atts["amenitiesspecificrooms"]) && $atts["amenitiesspecificrooms"] == "false") || (is_bool($atts["amenitiesspecificrooms"]) && !$atts["amenitiesspecificrooms"])))
				{
					$amenitiesSpecificRooms = false;
				}

				$property = API365::GetProperty($propertySlug, [
					"include" => [
						$amenitiesSpecificRooms ? "specificroomamenities" : ""
					],
					"language_data" => [
						"language" => [
							"all"
						],
						"fields" => [
							"custom_subtitution_code"
						]
					]
				]);

				if(is_null($property))
				{
					return Helpers365::Abort404();
				}

				//Get the default values from the settings
				$optionVillas365PropertyPageId = get_option("villas-365_property_page_id");
				$propertyPageId = null;
				if(!is_null($optionVillas365PropertyPageId) && (trim($optionVillas365PropertyPageId) !== "") && is_numeric($optionVillas365PropertyPageId))
				{
					$propertyPageId = $optionVillas365PropertyPageId;

					if(!is_null($propertyPageId))
					{
						$propertyPageURL = Helpers365::GetPropertyPageURL($propertyPageId);

						if(!is_null($propertyPageURL))
						{
							$propertyUrl = Helpers365::MakePropertyURL($propertyPageURL, $propertySlug);
						}
					}
				}
			}

			if(is_null($property))
			{
				return Helpers365::Abort404();
			}

			$sectionData = [
				"property" => $property,
				"propertyUrl" => $propertyUrl,
				"tag" => "div",
				"name" => "data",
				"value" => null,
				"icons" => true
			];

			if(array_key_exists("icons", $atts) &&
				!is_null($atts["icons"]) &&
				(trim($atts["icons"]) != "") &&
				((!is_bool($atts["icons"]) && $atts["icons"] == "false") || (is_bool($atts["icons"]) && !$atts["icons"])))
			{
				$sectionData["icons"] = false;
			}

			if(array_key_exists("readmore", $atts) &&
				!is_null($atts["readmore"]) &&
				((!is_bool($atts["readmore"]) && $atts["readmore"] == "true") || (is_bool($atts["readmore"]) && $atts["readmore"])))
			{
				$sectionData["readmore"] = true;
			}

			if(array_key_exists("readmoreheight", $atts) &&
				!is_null($atts["readmoreheight"]) &&
				($atts["readmoreheight"] !== "") &&
				is_numeric($atts["readmoreheight"]))
			{
				$sectionData["readmoreheight"] = $atts["readmoreheight"];
			}

			if(array_key_exists("bannerstyle", $atts) &&
				!is_null($atts["bannerstyle"]) &&
				($atts["bannerstyle"] !== "") &&
				is_string($atts["bannerstyle"]))
			{
				$sectionData["bannerstyle"] = $atts["bannerstyle"];
			}

			$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-data.php';

			switch($atts["section"])
			{
				case "header":
					//Change page meta tile and meta description
					$this->change_property_page_meta_title('');
					$this->change_property_page_meta_description('');
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-header.php';
					break;
				case "banner":
				{
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-banner.php';
					$sectionData["showintrobox"] = true;
					$sectionData["lightboxonly"] = false;
					
					//Get the default values from the settings
					$optionVillas365BookingPageId = get_option("villas-365_booking_page_id");

					$bookingPageUrl = null;
					if(!is_null($optionVillas365BookingPageId) && (trim($optionVillas365BookingPageId) !== "") && is_numeric($optionVillas365BookingPageId))
					{
						$bookingPageUrl = get_page_link($optionVillas365BookingPageId);
					}

					$sectionData["bookingPageUrl"] = $bookingPageUrl;

					break;
				}
				case "imagescroller":
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-image-scroller.php';
					break;
				case "quotewidget":
				{
					$sectionData["quotewidget"] = true;
					$sectionData["modal"] = false;
				}
				case "floater":
				{
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-floater.php';

					//Get the default values from the settings
					$optionVillas365ContactPageId = get_option("villas-365_contact_page_id");
					$optionVillas365BookingPageId = get_option("villas-365_booking_page_id");

					$contactPageUrl = null;
					if(!is_null($optionVillas365ContactPageId) && (trim($optionVillas365ContactPageId) !== "") && is_numeric($optionVillas365ContactPageId))
					{
						$contactPageUrl = get_page_link($optionVillas365ContactPageId);
					}

					$bookingPageUrl = null;
					if(!is_null($optionVillas365BookingPageId) && (trim($optionVillas365BookingPageId) !== "") && is_numeric($optionVillas365BookingPageId))
					{
						$bookingPageUrl = get_page_link($optionVillas365BookingPageId);
					}

					$sectionData["contactPageUrl"] = $contactPageUrl;
					$sectionData["bookingPageUrl"] = $bookingPageUrl;
					$sectionData["showdiscounts"] = false;
				
					break;
				}
				case "name":
				{
					$sectionData["tag"] = "h1";
					$sectionData["name"] = "name";
					if(Helpers365::CheckObjectPropertiesExist($property, ["name"]))
					{
						$sectionData["value"] = esc_html(Helpers365::Get365TranslationValue($property, ["name"], ["languages", "name"]));
						$sectionData["seoValue"] = $this->getPpropertySEOName($property);
					}
					break;
				}
				case "rooms":
				{
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-rooms.php';

					$sectionData["name"] = "rooms";
					if(Helpers365::CheckObjectPropertiesExist($property, ["maxguest", "bedroom", "bathroom"], false))
					{
						$sectionData["value"] = [
							"Guests" => $property->maxguest,
							"Bedrooms" => $property->bedroom,
							"Bathrooms" => $property->bathroom
						];
					}
					break;
				}
				case "brief":
				{
					$sectionData["tag"] = "div";
					$sectionData["name"] = "brief";

					if(Helpers365::CheckObjectPropertiesExist($property, ["brief"]))
					{
						$sectionData["value"] = wp_kses_post(str_replace(PHP_EOL, "<br>", Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"])));
					}
					break;
				}
				case "map":
				{
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-map.php';

					$sectionData["name"] = "map";
					if(Helpers365::CheckObjectPropertiesExist($property, ["local_area_action"]) && Helpers365::CheckObjectPropertiesExist($property->local_area_action, ["lat", "lon"], false))
					{
						$sectionData["value"] = [
							"latitude" => $property->local_area_action->lat,
							"longitude" => $property->local_area_action->lon
						];
					}
					break;
				}
				case "localinformation":
				{
					$sectionData["tag"] = "div";
					$sectionData["name"] = "localinformation";
					if(Helpers365::CheckObjectPropertiesExist($property, ["local_information"]))
					{
						$sectionData["value"] = wp_kses_post(str_replace(PHP_EOL, "<br>", $property->local_information));
					}
					break;
				}
				case "rates":
				{
					$sectionData["showratedates"] = true;
					$sectionData["showratename"] = true;
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-rates.php';
					break;
				}
				case "reviews":
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-reviews.php';
					break;
				case "policies":
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-policies.php';
					break;
				case "amenities":
				{
					$sectionData["showoverview"] = false;
					$sectionData["amenitiesspecificrooms"] = $amenitiesSpecificRooms;
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-amenities.php';
					break;
				}
				case "booking":
					$sectionData["advanced"] = false;
					$sectionData["style"] = null;
					$sectionData["singleproperty"] = false;
					$sectionData["displaymonths"] = null;
					$sectionData["showdiscounts"] = false;
					$sectionData["includebeyondpricing"] = false;
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-booking.php';
					break;
				case "login":
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-login.php';
					break;
				case "related":
				{
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-related.php';
					$sectionData["limit"] = 4;
					$sectionData["showdiscountlabel"] = true;
					$sectionData["showfeaturedlabel"] = true;
					break;
				}
				case "virtualtour":
				{
					$sectionData["lightbox"] = false;
					$sectionData["textonly"] = false;
					$sectionData["embed"] = false;
					$sectionData["showimagewhennovideo"] = true;
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-virtual-tour.php';
					break;
				}
				case "savebutton":
					$sectionData["showtext"] = false;
					$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-save-button.php';
					break;
				case "custom":
				{
					$sectionData["tag"] = "div";
					$sectionData["name"] = "custom";

					if(array_key_exists("name", $atts) &&
						!is_null($atts["name"]) &&
						($atts["name"] !== "") &&
						is_string($atts["name"]))
					{
						$sectionData["name"] = $atts["name"];
					}

					if(array_key_exists("customcode", $atts) &&
						!is_null($atts["customcode"]) &&
						($atts["customcode"] !== "") &&
						is_string($atts["customcode"]))
					{
						$sectionData["value"] = Helpers365::GetCustomSubtitutionCode($property, $atts["customcode"]);
					}
					break;
				}
				default:
					break;
			}
		}
		else //if(array_key_exists("fullpage", $atts))
		{
			//Output the original property page.
			
			// normalize attribute keys, lowercase
			$atts = array_change_key_case((array)$atts, CASE_LOWER);

			//Get the default values from the settings
			$optionVillas365ContactPageId = get_option("villas-365_contact_page_id");
			$optionVillas365BookingPageId = get_option("villas-365_booking_page_id");

			$contactPageUrl = null;
			if(!is_null($optionVillas365ContactPageId) && (trim($optionVillas365ContactPageId) !== "") && is_numeric($optionVillas365ContactPageId))
			{
				$contactPageUrl = get_page_link($optionVillas365ContactPageId);
			}

			$bookingPageUrl = null;
			if(!is_null($optionVillas365BookingPageId) && (trim($optionVillas365BookingPageId) !== "") && is_numeric($optionVillas365BookingPageId))
			{
				$bookingPageUrl = get_page_link($optionVillas365BookingPageId);
			}

			// override default attributes with user attributes
			$property_atts = shortcode_atts([
				'contactPageUrl' => $contactPageUrl,
				'bookingPageUrl' => $bookingPageUrl
			], $atts, $tag);

			ob_start();

			$templateFolder = Helpers365::GetTemplateFolder();
			$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $templateFolder . '/' . $this->plugin_name . '-property.php';
			if(file_exists($filePath))
			{
				include( $filePath );
			}

			$output = ob_get_contents();

			ob_end_clean();

			return $output;
		}
		// else
		// {
		// 	$sectionData = [
		// 		"tag" => "div",
		// 		"name" => "data",
		// 		"value" => "Error: 'section' attribute required on shortcode."
		// 	];

		// 	$partialPath = plugin_dir_path( __FILE__ ) . 'partials/property/' . $this->plugin_name . '-property-data.php';
		// }

		$property_atts = shortcode_atts($sectionData, $atts, $tag);

		ob_start();

		include( $partialPath );

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // property()

	/**
	 * Processes shortcode 365-villas-featured-properties
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function featuredProperties($atts = [], $content = null, $tag = '')
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		//Get the default values from the settings
		$optionVillas365PropertiesPerRowInFeatured = get_option("villas-365_properties_per_row_in_featured");
		$optionVillas365PropertiesTotalInFeatured = get_option("villas-365_properties_total_in_featured");

		$propertiesPerRow = 3;
		if(!is_null($optionVillas365PropertiesPerRowInFeatured) && (trim($optionVillas365PropertiesPerRowInFeatured) !== "") && is_numeric($optionVillas365PropertiesPerRowInFeatured))
		{
			$propertiesPerRow = $optionVillas365PropertiesPerRowInFeatured;
		}

		$propertiesLimit = 3;
		if(!is_null($optionVillas365PropertiesTotalInFeatured) && (trim($optionVillas365PropertiesTotalInFeatured) !== "") && is_numeric($optionVillas365PropertiesTotalInFeatured))
		{
			$propertiesLimit = $optionVillas365PropertiesTotalInFeatured;
		}

		//Get the default values from the settings
		$optionVillas365PropertyPageId = get_option("villas-365_property_page_id");
		$propertyPageId = null;
		if(!is_null($optionVillas365PropertyPageId) && (trim($optionVillas365PropertyPageId) !== "") && is_numeric($optionVillas365PropertyPageId))
		{
			$propertyPageId = $optionVillas365PropertyPageId;
		}

		$optionVillas365PropertiesDiscountsPageId = get_option("villas-365_properties_discounts_page_id");
		$propertiesDiscountsPageId = null;
		if(!is_null($optionVillas365PropertiesDiscountsPageId) && (trim($optionVillas365PropertiesDiscountsPageId) !== "") && is_numeric($optionVillas365PropertiesDiscountsPageId))
		{
			$propertiesDiscountsPageId = $optionVillas365PropertiesDiscountsPageId;
		}

		// override default attributes with user attributes
		$propertiesFeatured_atts = shortcode_atts([
			'perrow' => $propertiesPerRow,
			'limit' => $propertiesLimit,
			'propertypageid' => $propertyPageId,
			'propertiesdiscountspageid' => $propertiesDiscountsPageId,
			'showsavebutton' => false,
			'showdiscountlabel' => true,
			'showfeaturedlabel' => true,
			'showdiscounttab'   => true, 
			'showfeaturetab'    => true,
		], $atts, $tag);

		ob_start();
		
		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-featured-properties.php';
		if(array_key_exists("template", $atts) && !is_null($atts["template"]) && (trim($atts["template"]) !== ""))
		{
			$templateFolder = Helpers365::GetTemplateFolder();
			$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $templateFolder . '/' . $this->plugin_name . '-featured-properties.php';
		}

		if(file_exists($filePath))
		{
			include( $filePath );
		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // featuredProperties()

	/**
	 * Processes shortcode 365-villas-search
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function search($atts = [], $content = null, $tag = '')
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		//Get the default values from the settings
		$optionVillas365PropertiesSearchPageId = get_option("villas-365_properties_search_page_id");

		$propertiesSearchPageId = null;
		if(!is_null($optionVillas365PropertiesSearchPageId) && (trim($optionVillas365PropertiesSearchPageId) !== "") && is_numeric($optionVillas365PropertiesSearchPageId))
		{
			$propertiesSearchPageId = $optionVillas365PropertiesSearchPageId;
		}

		$propertiesSearchStyle = 1;
		if(is_null($atts) || !is_array($atts) || !array_key_exists("style", $atts))
		{
			$atts["style"] = 1;
		}

		if(!is_null($atts) && is_array($atts) && array_key_exists("defaultquery", $atts) && ($atts["defaultquery"] !== ""))
		{
			$atts["defaultquery"] = Helpers365::QueryStringToArray($atts["defaultquery"]);
		}

		// override default attributes with user attributes
		$search_atts = shortcode_atts([
			"searchpageid" => $propertiesSearchPageId,
			"style" => $propertiesSearchStyle,
			"usecurrentpageforsearch" => false,
			"showfilter" => false,
			"location" => "searchpage",
			"size" => "large",
			"defaultquery" => null
		], $atts, $tag);

		ob_start();

		$searchStyle = "";
		switch($search_atts["style"])
		{
			case "full":
				$searchStyle = "-full";
				break;
			case 2:
				$searchStyle = "-2";
				break;
			case 1:
			default:
				$searchStyle = "";
				break;
		}

		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-search' . $searchStyle . '.php';
		if(array_key_exists("template", $atts) && !is_null($atts["template"]) && (trim($atts["template"]) !== ""))
		{
			$templateFolder = $atts['template'];
		} else {
			$templateFolder = Helpers365::GetTemplateFolder();
		}

		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $templateFolder . '/' . $this->plugin_name . '-search' . $searchStyle . '.php';

		if(file_exists($filePath))
		{
			include( $filePath );
		}
		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // search()

	/**
	 * Processes shortcode 365-villas-category-grid
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function categoryGrid($atts = [], $content = null, $tag = '')
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		//Get the default values from the settings
		$optionVillas365PropertiesSearchPageId = get_option("villas-365_properties_search_page_id");

		$propertiesSearchPageId = null;
		if(!is_null($optionVillas365PropertiesSearchPageId) && (trim($optionVillas365PropertiesSearchPageId) !== "") && is_numeric($optionVillas365PropertiesSearchPageId))
		{
			$propertiesSearchPageId = $optionVillas365PropertiesSearchPageId;
		}

		// override default attributes with user attributes
		$cateogryGrid_atts = shortcode_atts([
			'perrow' => 4,
			"searchpageid" => $propertiesSearchPageId
		], $atts, $tag);

		ob_start();

		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-category-grid.php';
		if(array_key_exists("template", $atts) && !is_null($atts["template"]) && (trim($atts["template"]) !== ""))
		{
			$templateFolder = Helpers365::GetTemplateFolder();
			$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $templateFolder . '/' . $this->plugin_name . '-category-grid.php';
		}

		if(file_exists($filePath))
		{
			include( $filePath );
		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // search()

	/**
	 * Processes shortcode 365-villas-booking
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function booking($atts = [], $content = null, $tag = '')
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		// override default attributes with user attributes
		$booking_atts = shortcode_atts([
			'advanced' => false,
			'style' => null,
			'singleproperty' => false,
			'displaymonths' => null,
			'showdiscounts' => false
		], $atts, $tag);

		ob_start();

		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-booking.php';
		if(file_exists($filePath))
		{
			include( $filePath );
		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // search()

	/**
	 * Processes shortcode 365-villas-reviews
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function reviews($atts = [], $content = null, $tag = '')
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		// override default attributes with user attributes
		$reviews_atts = shortcode_atts([
			'display' => 'grid'
		], $atts, $tag);

		ob_start();

		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-reviews.php';
		if(file_exists($filePath))
		{
			include( $filePath );
		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // reviews()

	/**
	 * Processes shortcode 365-villas-login
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function login($atts = [], $content = null, $tag = '')
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		ob_start();

		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-login.php';
		if(file_exists($filePath))
		{
			include( $filePath );
		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // search()

	/**
	 * Processes shortcode 365-villas-language-switcher
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function languageSwitcher($atts = [], $content = null, $tag = '')
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		ob_start();

		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-language-switcher.php';
		if(file_exists($filePath))
		{
			include( $filePath );
		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // search()

	/**
	 * Processes shortcode 365-villas-saved-properties
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	get_option
	 * @uses	get_layout
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function savedProperties($atts = [], $content = null, $tag = '')
	{
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		ob_start();

		$filePath = plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-saved-properties.php';
		if(file_exists($filePath))
		{
			include( $filePath );
		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	} // search()
  
  /**
   * Get Property SEO Title
   * 
   * @param object $property
   * @param string $language
   * @return string
   */
  function getPpropertySEOName($property) {
    $seoTitle = '';
    if (!empty($property)) {
      $seoTitle = $property->name;
      $language = Helpers365::Get365LanguageCode();
      if (isset($property->seo)) {
        if (isset($property->seo->seoTitle->{$language})) {
          $seoTitle = $property->seo->seoTitle->{$language};
        } else {
          $seoTitle = $property->seo->seoTitle->en;
        }
      }
      
      $seoTitle = str_replace('[siteTitle]', '', $seoTitle);
      $seoTitle = trim(trim(trim($seoTitle), '-'));
    }
    
    return $seoTitle;
  }

	//Change the page meta title for the property pages.
	//This requires the Yoast plugin.
	//See here for details: https://www.whatmarkdid.com/php/overriding-yoast-seo-plugin-page-title-meta-description/
	function change_property_page_meta_title( $title )
	{
		global $pagename;
		$originalTitle = $title;
		$newTitle = $title;

		$optionVillas365PropertyPageId = get_option("villas-365_property_page_id");
		$propertyPageSlug = Helpers365::GetPageSlugFromPageId($optionVillas365PropertyPageId);
		
		if(!is_null($propertyPageSlug) && ($pagename == $propertyPageSlug))
		{
			$propertySlug = Helpers365::GetPropertySlug();
			if(!is_null($propertySlug))
			{
				$property = API365::GetProperty($propertySlug);

				if(!is_null($property))
				{
					if (isset($property->seo)) {
						$language = Helpers365::Get365LanguageCode();
						if (isset($property->seo->seoTitle->$language)) {
							$newTitle = $property->seo->seoTitle->$language;
						} else {
							$newTitle = $property->seo->seoTitle->en;
						}
						$newTitle = str_replace("[siteTitle]", get_bloginfo("name"), $newTitle);
					} else {
						$newTitle = $originalTitle . " - " . esc_html(Helpers365::Get365TranslationValue($property, ["name"], ["languages", "name"]));
					}

				}
			}
		}

		return $newTitle;
	}

	//Change the page meta description for the property pages.
	//This requires the Yoast plugin.
	//See here for details: https://www.whatmarkdid.com/php/overriding-yoast-seo-plugin-page-title-meta-description/
	function change_property_page_meta_description( $description )
	{
		global $pagename;
		$newDescription = $description;

		$optionVillas365PropertyPageId = get_option("villas-365_property_page_id");
		$propertyPageSlug = Helpers365::GetPageSlugFromPageId($optionVillas365PropertyPageId);
		
		if(!is_null($propertyPageSlug) && ($pagename == $propertyPageSlug))
		{
			$propertySlug = Helpers365::GetPropertySlug();
			if(!is_null($propertySlug)) 
			{
				$property = API365::GetProperty($propertySlug);

				if(!is_null($property))
				{
					if(isset($property->seo)) {
						$language = Helpers365::Get365LanguageCode();
						if(isset($property->seo->metaDescription->$language)) {
							$newDescription = $property->seo->metaDescription->$language;
						} else {
							$newDescription = $property->seo->metaDescription->en; 
						}
					} else {
						$newDescription = str_replace("\r\n\r\n", " ", Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]));
					}					
					$newDescription = str_replace(PHP_EOL, " ", $newDescription);
					$newDescription = esc_html(trim(Helpers365::StrLimit($newDescription, 250)));
				}
			}
		}

		return $newDescription;
	}

	//Change the page meta canonical URL for the property pages.
	//This requires the Yoast plugin.
	//See here for details: https://www.whatmarkdid.com/php/overriding-yoast-seo-plugin-page-title-meta-description/
	function change_property_page_meta_canonical_url( $url )
	{
		global $pagename;
		$newURL = $url;

		$optionVillas365PropertyPageId = get_option("villas-365_property_page_id");
		$propertyPageSlug = Helpers365::GetPageSlugFromPageId($optionVillas365PropertyPageId);
		
		if(!is_null($propertyPageSlug) && ($pagename == $propertyPageSlug))
		{
			$propertySlug = Helpers365::GetPropertySlug();
			if(!is_null($propertySlug))
			{
				$property = API365::GetProperty($propertySlug);

				if(!is_null($property))
				{
					$newURL = esc_html(get_page_link($optionVillas365PropertyPageId) . $propertySlug . "/");
				}
			}
		}

		return $newURL;
	}

	//Add the page meta opengraph image URL for the property pages.
	//This requires the Yoast plugin.
	//See here for details: https://www.whatmarkdid.com/php/overriding-yoast-seo-plugin-page-title-meta-description/
	function add_property_page_meta_opengraph_image( $object )
	{
		global $pagename;

		$optionVillas365PropertyPageId = get_option("villas-365_property_page_id");
		$propertyPageSlug = Helpers365::GetPageSlugFromPageId($optionVillas365PropertyPageId);
		
		if(!is_null($propertyPageSlug) && ($pagename == $propertyPageSlug))
		{
			$propertySlug = Helpers365::GetPropertySlug();
			if(!is_null($propertySlug))
			{
				$property = API365::GetProperty($propertySlug);

				if(!is_null($property))
				{
					$object->add_image($property->image_path);
				}
			}
		}
	}

	//Add page meta if Yoast is not installed.
	function add_property_page_meta()
	{
		global $pagename;
		$metaHTML = "";

		$optionVillas365PropertyPageId = get_option("villas-365_property_page_id");
		$propertyPageSlug = Helpers365::GetPageSlugFromPageId($optionVillas365PropertyPageId);
		
		if(!is_null($propertyPageSlug) && ($pagename == $propertyPageSlug))
		{
			$propertySlug = Helpers365::GetPropertySlug();
			if(!is_null($propertySlug))
			{
				$property = API365::GetProperty($propertySlug);

				if(!is_null($property))
				{
					$propertyPage = get_post($optionVillas365PropertyPageId);
					$siteTitle = get_bloginfo('name');

					$meta = "";
					$metaOG = "";
					$metaTwitter = "";

					//Set the title
					$title = null;
					if(!is_null($propertyPage))
					{
						$title = $propertyPage->post_title;
					}

					if(!is_null($title) && ($title !== ""))
					{
						$title = $title . " - " . $siteTitle . " - " . esc_html(Helpers365::Get365TranslationValue($property, ["name"], ["languages", "name"]));
					}
					else
					{
						$title = $siteTitle . " - " . esc_html(Helpers365::Get365TranslationValue($property, ["name"], ["languages", "name"]));
					}
					
					if(!is_null($title) && ($title !== ""))
					{
						$metaOG .= PHP_EOL . '<meta property="og:title" content="' . $title . '" />';
						$metaTwitter .= PHP_EOL . '<meta name="twitter:title" content="' . $title . '" />';
					}
					
					//Set the description
					$description = str_replace("\r\n\r\n", " ", Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]));
					$description = str_replace(PHP_EOL, " ", $description);
					$description = esc_html(trim(Helpers365::StrLimit($description, 250)));
					if(!is_null($description) && ($description !== ""))
					{
						$meta .= PHP_EOL . '<meta name="description" content="' . $description . '"/>';
						$metaOG .= PHP_EOL . '<meta property="og:description" content="' . $description . '" />';
						$metaTwitter .= PHP_EOL . '<meta name="twitter:description" content="' . $description . '" />';
					}

					//Set the URL
					$url = esc_html(get_page_link($optionVillas365PropertyPageId) . $propertySlug . "/");
					if(!is_null($url) && ($url !== ""))
					{
						$metaOG .= PHP_EOL . '<meta property="og:url" content="' . $url . '" />';
					}

					//Set the image
					if(!is_null($property->image_path))
					{
						$metaOG .= PHP_EOL . '<meta property="og:image" content="' . esc_html($property->image_path) . '" />';
						$metaTwitter .= PHP_EOL . '<meta name="twitter:card" content="summary_large_image" />';
					}

					//Set various other meta tags
					$metaOG .= PHP_EOL . '<meta property="og:site_name" content="' . $siteTitle . '" />';
					$metaOG .= PHP_EOL . '<meta property="og:locale" content="en_GB" />';
					$metaOG .= PHP_EOL . '<meta property="og:type" content="article" />';

					$metaHTML = $meta . $metaOG . $metaTwitter . PHP_EOL . PHP_EOL;
				}
			}
		}

		echo $metaHTML;
	}

	function add_language_switcher_to_nav_menu_items( $items, $args )
	{
		if(!defined('ICL_LANGUAGE_CODE'))
		{
			return $items;
		}

		//Get languages
		$languages = apply_filters('wpml_active_languages', NULL, 'skip_missing=1');
	 
		if($languages && (($args->theme_location == 'primary-menu') || Helpers365::StartsWith($args->menu->slug, 'primary-menu')))
		{
			if(!empty($languages) && is_array($languages))
			{
				$queryString = "";
				if(!is_null($_GET) && is_array($_GET) && (count($_GET) > 0))
				{
					$queryString = "?" . http_build_query($_GET);
				}
				
				$languagesItems = "";
				$currentLanguage = apply_filters('wpml_current_language', NULL);
	
				$languagesItems = "";
				foreach($languages as $language)
				{
					if($language['language_code'] == $currentLanguage)
					{
						$propertySlug = Helpers365::GetPropertySlug();
						if(is_null($propertySlug))
						{
							$propertySlug = "";
						}
						
						$languagesItems = '<li class="menu-item wpml-ls-slot-4 wpml-ls-item wpml-ls-item-fr wpml-ls-current-language wpml-ls-menu-item wpml-ls-last-item menu-item-type-wpml_ls_menu_item menu-item-object-wpml_ls_menu_item menu-item-has-children menu-item-wpml-ls-4-fr">' .
							'<a title="' . $language['native_name'] . '" href="' . $language['url'] . $propertySlug . $queryString . '"><span class="wpml-ls-native" lang="' . $language['language_code'] . '">' . $language['native_name'] . '</span></a>';
						
						break;
					}
				}
	
				$languagesSubItems = "";
				foreach($languages as $language)
				{
					if(!$language['active'])
					{
						if($languagesSubItems == "")
						{
							$languagesSubItems = '<ul class="sub-menu">';
						}
	
						$propertySlug = Helpers365::GetPropertySlug();
						if(is_null($propertySlug))
						{
							$propertySlug = "";
						}
	
						$languagesSubItems .= '<li class="menu-item wpml-ls-slot-4 wpml-ls-item wpml-ls-item-en wpml-ls-menu-item wpml-ls-first-item menu-item-type-wpml_ls_menu_item menu-item-object-wpml_ls_menu_item menu-item-wpml-ls-4-en">' .
									'<a title="' . $language['native_name'] . '" href="' . $language['url'] . $propertySlug . $queryString . '"><span class="wpml-ls-native" lang="' . $language['language_code'] . '">' . $language['native_name'] . '</span></a>' .
								'</li>';
					}
				}
	
				if($languagesSubItems != "")
				{
					$languagesSubItems .= '</ul>';
				}
				
				$languagesItems .= $languagesSubItems . '</li>';
	
				$items = $languagesItems . $items;
			}
		}
	 
		return $items;
	}
}
