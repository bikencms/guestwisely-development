<?php

if (!class_exists('Helpers365')) {

class Helpers365
{
	const BASE_APP_URL = "https://secure.365villas.com";
	const TEXT_DOMAIN = "villas-365-website-td";
	const SITE_CACHE_GROUP = "365SiteCacheGroup";
	const VR_MEMBER_ID_COOKIE_NAME = "villas365VRMemberID";

	public static function BaseAppURL()
	{
		$baseURL = Helpers365::BASE_APP_URL;
		if(defined('VILLAS_365_APP_URL') && !is_null(VILLAS_365_APP_URL))
		{
			$baseURL = VILLAS_365_APP_URL;
		}
		return $baseURL;
	}

	//Reference: https://richjenks.com/wordpress-throw-404/
	public static function Abort404()
	{
		// 1. Ensure `is_*` functions work
		global $wp_query;
		$wp_query->set_404();
	 
		// 2. Fix HTML title
		add_action( 'wp_title', function () {
			return '404: Not Found';
		}, 9999 );
	 
		// 3. Throw 404
		status_header(404);
		nocache_headers();
	}
	public static function SearchPage() {
		$accommodationPageId = Helpers365::GetOption("_properties_search_page_id", "villas-365", false);
		header("Location: " . get_page_link($accommodationPageId));
		die();
	}

	//Reference: http://www.tcbarrett.com/2013/05/wordpress-how-to-get-the-slug-of-your-post-or-page/
	public static function GetSlug($postId = null)
	{
		if(empty($postId))
		{
			global $post;
			if(!isset($post) || empty($post) || is_null($post))
			{
				return null; // No global $post var available.
			}
			
			$postId = $post->ID;
		}
	  
		return basename(get_permalink($postId));
	}

	public static function CheckPropertyPageExists()
	{
		if(isset($_GET) && array_key_exists("et_fb", $_GET) && !is_null($_GET["et_fb"]))
		{
			//If we get here then we are in the divi builder and should just continue.
			return;
		}

		if(isset($_GET) && array_key_exists("redirect_to_property", $_GET) && !is_null($_GET["redirect_to_property"]))
		{
			//If we get here then we need to redirect to the correct property page.
			$propertySlug = API365::GetPropertySlugFromId($_GET["redirect_to_property"]);
			if(is_null($propertySlug))
			{
				//Return to accommodation page when 404
				return Helpers365::SearchPage();
			}

			//Get the property page ID.
			$propertyPageId = Helpers365::GetOption("_property_page_id", "villas-365", false);
			if(!$propertyPageId)
			{
				//Return to accommodation page when 404
				return Helpers365::SearchPage();
			}
			
			//Get the page slug
			$propertyPage = get_page_link($propertyPageId);
			$propertyURL = $propertyPage . $propertySlug . "/";

			wp_safe_redirect($propertyURL, 301);
			exit;
			return;
		}

		$propertySlug = Helpers365::GetPropertySlug();

		if(!is_null($propertySlug))
		{
			$property = API365::GetProperty($propertySlug);

			if(is_null($property))
			{
				//Return to accommodation page when 404
				return Helpers365::SearchPage();
			}
		}
		elseif(!is_null($currentPostSlug = Helpers365::GetSlug()))
		{
			//Get the property page ID.
			$propertyPageId = Helpers365::GetOption("_property_page_id", "villas-365", false);

			if(!$propertyPageId)
			{
				return;
			}
			
			//Get the page slug
			$propertyPage = get_post($propertyPageId);
			$propertyPageSlug = $propertyPage->post_name;

			if($currentPostSlug === $propertyPageSlug)
			{
				//Return to accommodation page when 404
				return Helpers365::SearchPage();
			}
		}

		return;
	}

	public static function StoreParameters($parametersArray)
	{
		if(!isset($parametersArray) || is_null($parametersArray) || !is_array($parametersArray) || (count($parametersArray) == 0))
		{
			return;
		}

		if(array_key_exists("vr_member_id", $parametersArray) && !is_null($parametersArray["vr_member_id"]))
		{
			$vrMemberIDCookieExpiration = time() + 86400;
			if(defined('VR_MEMBER_ID_COOKIE_EXPIRATION'))
			{
				$vrMemberIDCookieExpiration = time() + VR_MEMBER_ID_COOKIE_EXPIRATION;
			}

			$vrMemberIDCookieSet = setcookie(
				Helpers365::VR_MEMBER_ID_COOKIE_NAME,
				esc_html($parametersArray["vr_member_id"]),
				[
					"expires" => $vrMemberIDCookieExpiration,
					"path" => COOKIEPATH,
					"domain" => COOKIE_DOMAIN,
					"secure" => FALSE,
					"httponly" => FALSE,
					"samesite" => "Strict"
				]
			);
			$_COOKIE[Helpers365::VR_MEMBER_ID_COOKIE_NAME] = esc_html($parametersArray["vr_member_id"]);
		}

		return;
	}

	public static function GetQueryStringParameterOrCookie($queryStringParameterName, $cookieName)
	{
		if(isset($_GET) && array_key_exists($queryStringParameterName, $_GET) && !is_null($_GET[$queryStringParameterName]))
		{
			return $_GET[$queryStringParameterName];
		}
		elseif(array_key_exists($cookieName, $_COOKIE) && !is_null($_COOKIE[$cookieName]) && (trim($_COOKIE[$cookieName]) !== ""))
		{
			return $_COOKIE[$cookieName];
		}

		return null;
	}

	//Get the option value and fallback to the network setting of the same name if required.
	//$optionName the last part of the option. eg. "_google_maps_api_key" where the full name is "villas-365_google_maps_api_key" and the network full name is "villas-365-network_google_maps_api_key".
	//$optionNamePrefix the first part of the option. eg. "villas-365" where the network name is "villas-365-network".
	//$fallback if we should fall back to the network value or not.
	public static function GetOption($optionName, $optionNamePrefix = "villas-365", $fallback = true)
	{
		$optionValue = get_option($optionNamePrefix . $optionName);
		if((trim($optionValue) == "") || is_null($optionValue))
		{
			$optionValue = FALSE;
		}

		if(($optionValue === FALSE) && $fallback)
		{
			$optionValue = get_site_option($optionNamePrefix . "-network" . $optionName);
		}
		
		if($optionValue === FALSE)
		{
			return FALSE;
		}

		return $optionValue;
	}

	//Get the network option value and fallback to the site setting of the same name if required.
	//This is useful where we need the network option to override the site option.
	public static function GetNetworkOption($optionName, $optionNamePrefix = "villas-365", $fallback = true)
	{
		$optionValue = get_site_option($optionNamePrefix . "-network" . $optionName);
		if((trim($optionValue) == "") || is_null($optionValue))
		{
			$optionValue = FALSE;
		}

		if(($optionValue === FALSE) && $fallback)
		{
			$optionValue = get_option($optionNamePrefix . $optionName);
		}
		
		if($optionValue === FALSE)
		{
			return FALSE;
		}

		return $optionValue;
	}

	public static function Cache365Calls()
	{
		$cache365Calls = false;
		$optionValue = get_option("villas-365_cache_365_api_calls");
		if((trim($optionValue) != "") && !is_null($optionValue) && is_numeric($optionValue) && ($optionValue == 1)) //If we have a value and it is true, then return true.
		{
			$cache365Calls = true;
		}

		return $cache365Calls;
	}

	public static function Cache365CallsDuration()
	{
		//Default to 5 minutes.
		$cache365CallsDuration = 3600;
		$optionValue = get_option("villas-365_cache_365_api_calls_duration");
		if((trim($optionValue) != "") && !is_null($optionValue) && is_numeric($optionValue) && ($optionValue >= 300)) //If we have a value and it is larger than 300 (5 minutes in seconds).
		{
			$cache365CallsDuration = $optionValue;
		}

		return $cache365CallsDuration;
	}

	public static function GetOpenPropertiesInNewTabsHTML()
	{
		$html = '';
		$optionValue = Helpers365::GetOption("_properties_open_in_new_tabs", "villas-365", false);
		if(($optionValue !== FALSE) && (trim($optionValue) != "") && !is_null($optionValue) && is_numeric($optionValue) && ($optionValue == 1)) //If we have a value and it is true, then return true.
		{
			$html = ' target="_blank"';
		}

		return $html;
	}

	public static function GetPropertiesFullHeightBannerClass()
	{
		$cssClass = '';
		$optionValue = Helpers365::GetOption("_properties_full_height_banner", "villas-365", false);
		if(($optionValue !== FALSE) && (trim($optionValue) != "") && !is_null($optionValue) && is_numeric($optionValue) && ($optionValue == 1)) //If we have a value and it is true, then return true.
		{
			$cssClass = ' banner-full-height';
		}

		return $cssClass;
	}

	public static function GetTemplateFolder()
	{
		$optionValue = Helpers365::GetNetworkOption("_template_name");

		$folderName = "template-1";
		switch($optionValue)
		{
			case "template_1":
				$folderName = "template-1";
				break;
			case "template_2":
				$folderName = "template-2";
				break;
			case "template_3":
				$folderName = "template-3";
				break;
			default:
				$folderName = null;
				break;
		}

		return $folderName;
	}

	//Taken from https://github.com/laravel/framework/blob/5.8/src/Illuminate/Support/Str.php
	public static function StrLimit($value, $limit = 100, $end = '...')
    {
		if (mb_strwidth($value, 'UTF-8') <= $limit)
		{
            return $value;
		}
		
        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
	}
	
	//Taken from https://github.com/laravel/framework/blob/5.8/src/Illuminate/Support/Str.php
	public static function StrContains($haystack, $needles)
    {
		foreach ((array) $needles as $needle)
		{
			if ($needle !== '' && mb_strpos($haystack, $needle) !== false)
			{
                return true;
            }
		}
		
        return false;
	}

	//http://stackoverflow.com/a/18623672
	/**
	* Get a center latitude,longitude from an array of like geopoints
	*
	* @param array data 2 dimensional array of latitudes and longitudes
	* For Example:
	* $data = array
	* (
	*   0 = > array(45.849382, 76.322333),
	*   1 = > array(45.843543, 75.324143),
	*   2 = > array(45.765744, 76.543223),
	*   3 = > array(45.784234, 74.542335)
	* );
	*/
	public static function GetCenterFromDegrees($data)
	{
		if (!is_array($data))
		{
			return false;
		}

		$num_coordinates = count($data);

		if($num_coordinates > 0)
		{
			$x = 0.0;
			$y = 0.0;
			$z = 0.0;

			foreach ($data as $coordinate)
			{
				$lat = $coordinate[0] * pi() / 180;
				$lon = $coordinate[1] * pi() / 180;

				$a = cos($lat) * cos($lon);
				$b = cos($lat) * sin($lon);
				$c = sin($lat);

				$x += $a;
				$y += $b;
				$z += $c;
			}

			$x /= $num_coordinates;
			$y /= $num_coordinates;
			$z /= $num_coordinates;

			$lon = atan2($y, $x);
			$hyp = sqrt($x * $x + $y * $y);
			$lat = atan2($z, $hyp);

			return array(round($lat * 180 / pi(), 8), round($lon * 180 / pi(), 8));
		}
		else
		{
			return array(0.0, 0.0);
		}
	}

	public static function GetDefaultColors($templateName = null)
	{
		$colours = null;

		//If we don't have a template name then get the option value.
		if(is_null($templateName))
		{
			$templateName = Helpers365::GetNetworkOption("_template_name");
		}

		switch($templateName)
		{
			case "template_2":
			case "template_3":
			case "template_1":
			default:
			{
				$colours = [
					"primaryColour" => "#4187bb",
					"secondaryColour" => "#d3e3f0",
					"buttonColour" => "#4187bb",
					"iconsColour" => "#4187bb",
					"searchLabelColour" => "#fff",
					"inputBorderColour" => "#93a1ae",
					"propertyAmenitiesSwitcherColour" => "#4187bb",
					"propertyRatesHeaderBackgoundColour" => "transparent",
					"propertyRatesHeaderTextColour" => "inherit",
					"floaterBackgroundColour" => "#fff",
					"floaterTextColour" => "#000",
					"floaterHeaderBackgroundColour" => "#4187bb",
					"floaterHeaderTextColour" => "#fff",
					"loginBackgroundColour" => "#f3f3f3",
					"calendarDateSelectedColour" => "#4187BB",
					"calendarDateSelectedTextColour" => "#fff",
					"calendarReservedDateColour" => "#4187BB",
					"calendarReservedDateTextColour" => "#fff",
					"featuredColour" => "#4187bb",
					"discountColour" => "#000000",
					"searchButtonColour" => "#ffffff",
					"searchButtonTextColour" => "#000000",
					"searchButtonHoverColour" => "#000000",
					"searchButtonHoverTextColour" => "#ffffff"
				];
				break;
			}
		}

		return $colours;
	}

	public static function GenerateCSSOverrides($inTemplate = false, $cssOverrideFile = "villas-365-color-overrides.css")
	{
		$templateFolder = null;
		if($inTemplate)
		{
			//Get the template folder name.
			$templateFolder = Helpers365::GetTemplateFolder();
		}

		$cacheKey = "colour-overrides_" . (!is_null($templateFolder) ? $templateFolder : "") . Helpers365::GetOption("_owner_key_365_api", "villas-365", false);
		if(Helpers365::Cache365Calls())
		{
			$cachedCSSString = wp_cache_get($cacheKey, Helpers365::SITE_CACHE_GROUP);
			if($cachedCSSString !== false)
			{
				return $cachedCSSString;
			}
		}
		
		//Get the colour settings
		$colours = [
			"primaryColour" => Helpers365::GetOption("_template_color_primaryColour", "villas-365", false),
			"secondaryColour" => Helpers365::GetOption("_template_color_secondaryColour", "villas-365", false),
			"buttonColour" => Helpers365::GetOption("_template_color_buttonColour", "villas-365", false),
			"iconsColour" => Helpers365::GetOption("_template_color_iconsColour", "villas-365", false),
			"searchLabelColour" => Helpers365::GetOption("_template_color_searchLabelColour", "villas-365", false),
			"inputBorderColour" => Helpers365::GetOption("_template_color_inputBorderColour", "villas-365", false),
			"propertyAmenitiesSwitcherColour" => Helpers365::GetOption("_template_color_propertyAmenitiesSwitcherColour", "villas-365", false),
			"propertyRatesHeaderBackgoundColour" => Helpers365::GetOption("_template_color_propertyRatesHeaderBackgoundColour", "villas-365", false),
			"propertyRatesHeaderTextColour" => Helpers365::GetOption("_template_color_propertyRatesHeaderTextColour", "villas-365", false),
			"floaterBackgroundColour" => Helpers365::GetOption("_template_color_floaterBackgroundColour", "villas-365", false),
			"floaterTextColour" => Helpers365::GetOption("_template_color_floaterTextColour", "villas-365", false),
			"floaterHeaderBackgroundColour" => Helpers365::GetOption("_template_color_floaterHeaderBackgroundColour", "villas-365", false),
			"floaterHeaderTextColour" => Helpers365::GetOption("_template_color_floaterHeaderTextColour", "villas-365", false),
			"loginBackgroundColour" => Helpers365::GetOption("_template_color_loginBackgroundColour", "villas-365", false),
			"featuredColour" => Helpers365::GetOption("_template_color_featuredColour", "villas-365", false),
			"discountColour" => Helpers365::GetOption("_template_color_discountColour", "villas-365", false),
			"searchButtonColour" => Helpers365::GetOption("_template_color_search_buttonColour", "villas-365", false),
			"searchButtonTextColour" => Helpers365::GetOption("_template_color_search_buttonTextColour", "villas-365", false),
			"searchButtonHoverColour" => Helpers365::GetOption("_template_color_search_buttonHoverColour", "villas-365", false),
			"searchButtonHoverTextColour" => Helpers365::GetOption("_template_color_search_buttonHoverTextColour", "villas-365", false)
		];

		$defaultColors = Helpers365::GetDefaultColors();

		//Set any defaults.
		if(($colours["primaryColour"] === FALSE) || (is_null($colours["primaryColour"])) || ($colours["primaryColour"] == ""))
		{
			$colours["primaryColour"] = $defaultColors["primaryColour"];
		}

		if(($colours["secondaryColour"] === FALSE) || (is_null($colours["secondaryColour"])) || ($colours["secondaryColour"] == ""))
		{
			$colours["secondaryColour"] = $defaultColors["secondaryColour"];
		}

		if(($colours["buttonColour"] === FALSE) || (is_null($colours["buttonColour"])) || ($colours["buttonColour"] == ""))
		{
			$colours["buttonColour"] = $defaultColors["buttonColour"];
		}

		if(($colours["iconsColour"] === FALSE) || (is_null($colours["iconsColour"])) || ($colours["iconsColour"] == ""))
		{
			$colours["iconsColour"] = $defaultColors["iconsColour"];
		}

		if(($colours["searchLabelColour"] === FALSE) || (is_null($colours["searchLabelColour"])) || ($colours["searchLabelColour"] == ""))
		{
			$colours["searchLabelColour"] = $defaultColors["searchLabelColour"];
		}

		if(($colours["inputBorderColour"] === FALSE) || (is_null($colours["inputBorderColour"])) || ($colours["inputBorderColour"] == ""))
		{
			$colours["inputBorderColour"] = $defaultColors["inputBorderColour"];
		}

		if(($colours["propertyAmenitiesSwitcherColour"] === FALSE) || (is_null($colours["propertyAmenitiesSwitcherColour"])) || ($colours["propertyAmenitiesSwitcherColour"] == ""))
		{
			$colours["propertyAmenitiesSwitcherColour"] = $defaultColors["propertyAmenitiesSwitcherColour"];
		}

		if(($colours["propertyRatesHeaderBackgoundColour"] === FALSE) || (is_null($colours["propertyRatesHeaderBackgoundColour"])) || ($colours["propertyRatesHeaderBackgoundColour"] == ""))
		{
			$colours["propertyRatesHeaderBackgoundColour"] = $defaultColors["propertyRatesHeaderBackgoundColour"];
		}

		if(($colours["propertyRatesHeaderBackgoundColour"] === FALSE) || (is_null($colours["propertyRatesHeaderBackgoundColour"])) || ($colours["propertyRatesHeaderBackgoundColour"] == ""))
		{
			$colours["propertyRatesHeaderBackgoundColour"] = $defaultColors["propertyRatesHeaderBackgoundColour"];
		}

		if(($colours["floaterBackgroundColour"] === FALSE) || (is_null($colours["floaterBackgroundColour"])) || ($colours["floaterBackgroundColour"] == ""))
		{
			$colours["floaterBackgroundColour"] = $defaultColors["floaterBackgroundColour"];
		}

		if(($colours["floaterTextColour"] === FALSE) || (is_null($colours["floaterTextColour"])) || ($colours["floaterTextColour"] == ""))
		{
			$colours["floaterTextColour"] = $defaultColors["floaterTextColour"];
		}

		if(($colours["floaterHeaderBackgroundColour"] === FALSE) || (is_null($colours["floaterHeaderBackgroundColour"])) || ($colours["floaterHeaderBackgroundColour"] == ""))
		{
			$colours["floaterHeaderBackgroundColour"] = $defaultColors["floaterHeaderBackgroundColour"];
		}

		if(($colours["floaterHeaderTextColour"] === FALSE) || (is_null($colours["floaterHeaderTextColour"])) || ($colours["floaterHeaderTextColour"] == ""))
		{
			$colours["floaterHeaderTextColour"] = $defaultColors["floaterHeaderTextColour"];
		}

		if(($colours["loginBackgroundColour"] === FALSE) || (is_null($colours["loginBackgroundColour"])) || ($colours["loginBackgroundColour"] == ""))
		{
			$colours["loginBackgroundColour"] = $defaultColors["loginBackgroundColour"];
		}
		
		if(($colours["featuredColour"] === FALSE) || (is_null($colours["featuredColour"])) || ($colours["featuredColour"] == ""))
		{
			$colours["featuredColour"] = $defaultColors["featuredColour"];
		}

		if(($colours["discountColour"] === FALSE) || (is_null($colours["discountColour"])) || ($colours["discountColour"] == ""))
		{
			$colours["discountColour"] = $defaultColors["discountColour"];
		}

		if(($colours["searchButtonColour"] === FALSE) || (is_null($colours["searchButtonColour"])) || ($colours["searchButtonColour"] == ""))
		{
			$colours["searchButtonColour"] = $defaultColors["searchButtonColour"];
		}

		if(($colours["searchButtonTextColour"] === FALSE) || (is_null($colours["searchButtonTextColour"])) || ($colours["searchButtonTextColour"] == ""))
		{
			$colours["searchButtonTextColour"] = $defaultColors["searchButtonTextColour"];
		}

		if(($colours["searchButtonHoverColour"] === FALSE) || (is_null($colours["searchButtonHoverColour"])) || ($colours["searchButtonHoverColour"] == ""))
		{
			$colours["searchButtonHoverColour"] = $defaultColors["searchButtonHoverColour"];
		}

		if(($colours["searchButtonHoverTextColour"] === FALSE) || (is_null($colours["searchButtonHoverTextColour"])) || ($colours["searchButtonHoverTextColour"] == ""))
		{
			$colours["searchButtonHoverTextColour"] = $defaultColors["searchButtonHoverTextColour"];
		}

		//Get the css to replace.
		$cssString = file_get_contents(plugin_dir_path( __FILE__ ) . "../../public/assets/" . (!is_null($templateFolder) ? $templateFolder . "/" : "") . "css/" . $cssOverrideFile);

		//If we can't read the file then just stop.
		if($cssString === FALSE)
		{
			return "/* Could not read css file to apply overrides. */";
		}

		//Find and replace each colour in the css string.
		foreach($colours as $colourName => $colour)
		{
			$cssString = str_ireplace("#" . $colourName, $colour, $cssString);
		}

		if(Helpers365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $cssString, Helpers365::SITE_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $cssString;
	}

	public static function GetCalendarColoursValues()
	{
		//Get the colour settings
		$colours = [
			"calendarDateSelectedColour" => Helpers365::GetOption("_template_color_calendarDateSelectedColour", "villas-365", false),
			"calendarDateSelectedTextColour" => Helpers365::GetOption("_template_color_calendarDateSelectedTextColour", "villas-365", false),
			"calendarReservedDateColour" => Helpers365::GetOption("_template_color_calendarReservedDateColour", "villas-365", false),
			"calendarReservedDateTextColour" => Helpers365::GetOption("_template_color_calendarReservedDateTextColour", "villas-365", false)
		];

		$defaultColors = Helpers365::GetDefaultColors();

		//Set any defaults.
		if(($colours["calendarDateSelectedColour"] === FALSE) || (is_null($colours["calendarDateSelectedColour"])) || ($colours["calendarDateSelectedColour"] == ""))
		{
			$colours["calendarDateSelectedColour"] = $defaultColors["calendarDateSelectedColour"];
		}

		if(($colours["calendarDateSelectedTextColour"] === FALSE) || (is_null($colours["calendarDateSelectedTextColour"])) || ($colours["calendarDateSelectedTextColour"] == ""))
		{
			$colours["calendarDateSelectedTextColour"] = $defaultColors["calendarDateSelectedTextColour"];
		}

		if(($colours["calendarReservedDateColour"] === FALSE) || (is_null($colours["calendarReservedDateColour"])) || ($colours["calendarReservedDateColour"] == ""))
		{
			$colours["calendarReservedDateColour"] = $defaultColors["calendarReservedDateColour"];
		}

		if(($colours["calendarReservedDateTextColour"] === FALSE) || (is_null($colours["calendarReservedDateTextColour"])) || ($colours["calendarReservedDateTextColour"] == ""))
		{
			$colours["calendarReservedDateTextColour"] = $defaultColors["calendarReservedDateTextColour"];
		}

		return $colours;
	}

	public static function GetCalendarColours()
	{
		$colours = Helpers365::GetCalendarColoursValues();

		$cssString = '._villas-365-booked-check-in {' .
				'background: linear-gradient( to left top, ' . $colours["calendarReservedDateColour"] . ' 50%, transparent 50%) no-repeat !important;' .
			'}' .
			'._villas-365-booked-check-out {' .
				'background: linear-gradient( to left top, transparent 50%, ' . $colours["calendarReservedDateColour"] . ' 50%) no-repeat !important;' .
				'border-top-color: ' . $colours["calendarReservedDateColour"] . ' !important;' .
			'}' .
			'._villas-365-booked-date {' .
				'background-color: ' . $colours["calendarReservedDateColour"] . ' !important;' .
				'color: ' . $colours["calendarReservedDateTextColour"] . ' !important;' .
			'}' .
			'._villas-365-booked-date.mbsc-disabled .mbsc-calendar-cell-text,' .
			'._villas-365-booked-check-in.mbsc-disabled .mbsc-calendar-cell-text,' .
			'._villas-365-booked-check-out.mbsc-disabled .mbsc-calendar-cell-text {' .
				'opacity: 0.7 !important;' .
			'}' .
			'.mbsc-ios.mbsc-selected .mbsc-calendar-cell-text {' .
				'border-color: ' . $colours["calendarDateSelectedColour"] . ';' .
				'background: ' . $colours["calendarDateSelectedColour"] . ';' .
				'color: ' . $colours["calendarDateSelectedTextColour"] . ';' .
			'}' .
			'.mbsc-ios.mbsc-range-day::after {' .
				'background-color: ' . $colours["calendarDateSelectedColour"] . ';' .
			'}' .
			'.mbsc-ios.mbsc-range-day .mbsc-calendar-cell-text {' .
				'color: ' . $colours["calendarDateSelectedTextColour"] . ';' .
			'}' .
			'.mbsc-ios.mbsc-hover .mbsc-calendar-cell-text {' .
				'background-color: ' . $colours["calendarDateSelectedColour"] . ';' .
				'color: ' . $colours["calendarDateSelectedTextColour"] . ';' .
			'}' .
			'.mbsc-ios.mbsc-calendar-today {' .
				'color: ' . $colours["calendarDateSelectedColour"] . ';' .
			'}' .
			'.mbsc-ios.mbsc-range-control-value.active {' .
				'color: ' . $colours["calendarDateSelectedColour"] . ';' .
			'}' .
			'.mbsc-ios.mbsc-calendar-button.mbsc-button {' .
				'color: #000;' .
			'}';
		
		return $cssString;
	}

	public static function MakeStringURLFriendly($string, $toLower = true)
	{
		if(is_null($string) || (trim($string) == ""))
		{
			return null;
		}
		
		if($toLower)
		{
			$string = strtolower($string);
		}
		$string = trim($string);
		$string = preg_replace("/[\s_]+/i", "-", $string);
		$string = preg_replace("/[^a-zA-Z0-9-]*/i", "", $string);
		$string = preg_replace('/[-]+/', '-', $string);
		$string = trim($string);

		return $string;
	}

	public static function GetPageSlug($pageId)
	{
		$propertyPageSlug = null;

		if(!is_null($pageId) && (trim($pageId) !== "") && is_numeric($pageId))
		{
			$propertyPage = get_post($pageId);
			if(!is_null($propertyPage))
			{
				$propertyPageSlug = $propertyPage->post_name;
			}
		}

		return $propertyPageSlug;
	}

	//Add the rewrite rules
	public static function AddRewriteRules($flush = false)
	{
		$propertyPageSlugs = [];

		//Get the property page.
		$option365VillasPropertyPageId = Helpers365::GetOption("_property_page_id", "villas-365", false);
		$propertyPageSlugBase = Helpers365::GetPageSlug($option365VillasPropertyPageId);
		
		if(!is_null($propertyPageSlugBase))
		{
			//Get the languages
			$propertyPageSlugs["en"] = $propertyPageSlugBase;
			$languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
			if(!empty($languages) && is_array($languages))
			{
				foreach($languages as $language)
				{
					$languageCode = $language["language_code"];
					if($languageCode == "en")
					{
						continue;
					}

					//Get the property pages for the different languages.
					$languagePropertyPageId = apply_filters('wpml_object_id', $option365VillasPropertyPageId, 'page', FALSE, $languageCode);
					$languagePropertyPageSlug = Helpers365::GetPageSlug($languagePropertyPageId);

					if(!is_null($languagePropertyPageSlug))
					{
						$propertyPageSlugs[$languageCode] = $languagePropertyPageSlug;
					}
				}
			}

			add_rewrite_tag('%villas365property%', '([^&]+)');

			//Add a rewrite rule for each language.
			foreach($propertyPageSlugs as $languageCode => $propertyPageSlug)
			{
				add_rewrite_rule(
					'(' . $propertyPageSlug . ')/([^/]*)/?$',
					'index.php?pagename=$matches[1]&villas365property=$matches[2]',
					'top'
				);
			}

			if($flush)
			{
				//Flush the rewrite rules if we have updated the settings so our new rules are added.
				flush_rewrite_rules(false);
			}
		}
	}

	//Get the page slug from the page ID.
	public static function GetPageSlugFromPageId($pageId)
	{
		$pageSlug = null;
		if(!is_null($pageId) && (trim($pageId) !== "") && ($pageId != 0) && is_numeric($pageId))
		{
			if(!is_null($pageId))
			{
				$propertyPage = get_post($pageId);
				if(!is_null($propertyPage))
				{
					$pageSlug = $propertyPage->post_name;
				}
			}
		}

		return $pageSlug;
	}

	public static function GetPropertySlug()
	{
		$propertySlug = null;

		if(isset($_GET) && array_key_exists("villas365property", $_GET) && !is_null($_GET["villas365property"]))
		{
			$propertySlug = $_GET["villas365property"];
		}
		elseif(($propertyQuerySlug = get_query_var("villas365property")) !== "")
		{
			$propertySlug = $propertyQuerySlug;
		}

		return $propertySlug;
	}

	public static function MakePropertySlug($property)
	{
		if(is_null($property) ||
			!property_exists($property, "id") ||
			!property_exists($property, "base_name"))
		{
			return null;
		}

		$propertySlug = $property->id . "-" . Helpers365::MakeStringURLFriendly($property->base_name);
		return $propertySlug;
	}

	//Check if an object exists and is not null and if all the properties listed are in the object.
	//Will return TRUE if the object is not null and all properties exist.
	//Will return FALSE otherwise
	//$nestedProperties is a bool. If true then each previous property will be treated like an object and
	//the next property in the list will be checked to see if it exists on the previous.
	//If this is false then each property listed will be checked on the main object.
	public static function CheckObjectPropertiesExist($object, $properties = null, $nestedProperties = true)
	{
		if(is_null($object))
		{
			return FALSE;
		}

		//There are no properties to check so return true.
		if(is_null($properties) || !is_array($properties) || (is_array($properties) && (count($properties) == 0)))
		{
			return TRUE;
		}

		if($nestedProperties)
		{
			//Need to pass the function by reference in the use section so it can be used recursivly.
			$checkProperty = function($currentObject, $propertyName) use (&$checkProperty, &$properties) {
				if(!is_object($currentObject) || !property_exists($currentObject, $propertyName))
				{
					//As soon as one property doesn't exist then return FALSE.
					return FALSE;
				}
				elseif(count($properties) > 0)
				{
					//If the property exists and we have property names left in the array then continue checking.
					return $checkProperty($currentObject->$propertyName, array_shift($properties));
				}
				
				//If we get to here then all the properties exist and we can return TRUE.
				return TRUE;
			};

			return $checkProperty($object, array_shift($properties));
		}
		elseif(!$nestedProperties)
		{
			foreach($properties as $property)
			{
				if(!is_object($object) || !property_exists($object, $property))
				{
					//As soon as one property doesn't exist then return FALSE.
					return FALSE;
				}
			}

			//If we get to here then all the properties exist and we can return TRUE.
			return TRUE;
		}

		return FALSE;
	}

	//Check get a value from an object if it exists exists and is not null and if all the properties listed are in the object.
	//Will return the value if the object is not null and all properties exist.
	//Will return null otherwise. For example if a property doesn't exist.
	//Each previous property will be treated like an object and the next property in the list will be checked to see if it exists on the previous.
	public static function GetValueFromObjectProperties($object, $properties)
	{
		if(is_null($object))
		{
			return null;
		}

		//Need to pass the function by reference in the use section so it can be used recursivly.
		$getPropertyValue = function($currentObject, $propertyName) use (&$getPropertyValue, &$properties) {
			if(!is_object($currentObject) || !property_exists($currentObject, $propertyName))
			{
				//As soon as one property doesn't exist then return FALSE.
				return FALSE;
			}
			elseif(count($properties) > 0)
			{
				//If the property exists and we have property names left in the array then continue checking.
				return $getPropertyValue($currentObject->$propertyName, array_shift($properties));
			}
			
			//If we get to here then all the properties exist and we can return the value.
			return $currentObject->$propertyName;
		};

		$propertyValue = $getPropertyValue($object, array_shift($properties));

		if($propertyValue === FALSE)
		{
			return null;
		}

		return $propertyValue;
	}

	/**
	 * Locate template.
	 *
	 * Locate the called template.
	 * Search Order:
	 * 1. /themes/theme/365villas/templates/$templateName
	 * 2. /plugins/villas-365/templates/$templateName.
	 * 
	 * Modified from https://jeroensormani.com/how-to-add-template-files-in-your-plugin/
	 *
	 * @since 1.0.0
	 *
	 * @param 	string 	$templateName			Template to load.
	 * @param 	string 	$string $templatePath	Path to templates.
	 * @param 	string	$defaultPath			Default path to template files.
	 * @return 	string 							Path to the template file.
	 */
	public static function LocateTemplateFile($templateName, $templatePath = null, $defaultPath = null)
	{
		// Set variable to search in 365villas/templates folder of theme.
		if(is_null($templatePath))
		{
			$templatePath = '365villas/templates/';
		}

		// Set default plugin templates path.
		if(is_null($defaultPath))
		{
			$defaultPath = plugin_dir_path( __FILE__ ) . '../../templates/'; // Path to the template folder
		}

		// Search template file in theme folder.
		$template = locate_template([
			$templatePath . $templateName
		]);

		// Get plugins template file.
		if(!$template)
		{
			$template = $defaultPath . $templateName;
		}

		if(!file_exists($template))
		{
			return FALSE;
		}

		return $template;
	}

	/**
	 * Locate parent template.
	 *
	 * Locate the called template parent only.
	 * Search Order:
	 * 1. /plugins/villas-365/templates/$templateName.
	 * 
	 * Modified from https://jeroensormani.com/how-to-add-template-files-in-your-plugin/
	 *
	 * @since 1.0.0
	 *
	 * @param 	string 	$templateName			Template to load.
	 * @param 	string 	$string $templatePath	Path to templates.
	 * @param 	string	$defaultPath			Default path to template files.
	 * @return 	string 							Path to the template file.
	 */
	public static function LocateParentTemplateFile($templateName, $defaultPath = null)
	{
		// Set default plugin templates path.
		if(is_null($defaultPath))
		{
			$defaultPath = plugin_dir_path( __FILE__ ) . '../../templates/'; // Path to the template folder
		}

		// Get plugins template file.
		$template = $defaultPath . $templateName;

		if(!file_exists($template))
		{
			return FALSE;
		}

		return $template;
	}

	public static function GetPropertyPageURL($propertyPageId)
	{
		$languagePropertyPageId = apply_filters('wpml_object_id', $propertyPageId, 'page');
		$languagePropertyPageSlug = Helpers365::GetPageSlug($languagePropertyPageId);

		if(!is_null($languagePropertyPageSlug))
		{
			return get_page_link($languagePropertyPageId);
		}

		return get_page_link($propertyPageId);
	}

	public static function MakePropertyURL($propertyPageUrl, $propertySlug, $escapeHtml = true)
	{
		$url = $propertyPageUrl . $propertySlug;

		if(!Helpers365::EndsWith($url, "/"))
		{
			$url .= "/";
		}

		if($escapeHtml)
		{
			$url = esc_html($url);
		}

		return $url;
	}

	public static function GetDatesListFromRange($startDate, $endDate, $format = "M/d/Y", $includeStart = true, $includeEnd = true)
	{
		$datesInRange = [];

		$datePeriodOptions = 0;
		if(!$includeStart)
		{
			$datePeriodOptions = DatePeriod::EXCLUDE_START_DATE;
		}

		$interval = DateInterval::createFromDateString("1 day");
		$period = new DatePeriod($startDate, $interval, $endDate, $datePeriodOptions);
		
		foreach($period as $date)
		{
			$datesInRange[] = $date->format($format);
		}

		return $datesInRange;
	}

	public static function MakeNonAvailableDatesList($nonAvailableDateRanges, $inputFormat = "Y-m-d", $outputFormat = "Y-m-d")
	{
		if(is_null($nonAvailableDateRanges) || !is_array($nonAvailableDateRanges) || (count($nonAvailableDateRanges) == 0))
		{
			return [];
		}

		$nonAvailableDates = [];
		$startDates = [];
		$endDates = [];

		foreach($nonAvailableDateRanges as $nonAvailableDateRange)
		{
			if(($nonAvailableDateRange->availability == "false") || ($nonAvailableDateRange->availability === FALSE))
			{
				$startDate = DateTime::createFromFormat($inputFormat, $nonAvailableDateRange->startDate)->setTime(0, 0, 0)->modify("+1 day");
				$endDate = DateTime::createFromFormat($inputFormat, $nonAvailableDateRange->endDate)->setTime(0, 0, 0);

				$start = $startDate->format($outputFormat);
				$end = $endDate->format($outputFormat);
				$nonAvailableDates[] = [
					"start" => $start,
					"end" => $end
				];

				//We need to make sure if there is an overlapping booking on a start/end date then it is disabled.
				$start = $startDate->modify("-1 day")->format($outputFormat);
				if(in_array($start, $endDates))
				{
					$nonAvailableDates[] = $start;
				}
				else
				{
					$startDates[] = $start;
				}
				
				$end = $endDate->modify("+1 day")->format($outputFormat);
				if(in_array($end, $startDates))
				{
					$nonAvailableDates[] = $end;
				}
				else
				{
					$endDates[] = $end;
				}
			}
		}

		return $nonAvailableDates;
	}

	public static function MakeNonAvailableDatesDisplayList($nonAvailableDateRanges, $inputFormat = "Y-m-d", $outputFormat = "Y-m-d")
	{
		if(is_null($nonAvailableDateRanges) || !is_array($nonAvailableDateRanges) || (count($nonAvailableDateRanges) == 0))
		{
			return [];
		}

		$nonAvailableDates = [];

		foreach($nonAvailableDateRanges as $nonAvailableDateRange)
		{
			if(($nonAvailableDateRange->availability == "false") || ($nonAvailableDateRange->availability === FALSE))
			{
				$startDate = DateTime::createFromFormat($inputFormat, $nonAvailableDateRange->startDate)->setTime(0, 0, 0);
				$endDate = DateTime::createFromFormat($inputFormat, $nonAvailableDateRange->endDate)->setTime(0, 0, 0);

				$start = $startDate->format($outputFormat);
				$cellCssClass = "_villas-365-booked-check-in";
				if(array_key_exists($start, $nonAvailableDates))
				{
					$cellCssClass = "_villas-365-booked-date";
				}
				$nonAvailableDates[$start] = [
					"date" => $start,
					"cellCssClass" => $cellCssClass
				];

				$start = $startDate->modify("+1 day")->format($outputFormat);
				$end = $endDate->format($outputFormat);
				$nonAvailableDates[$start . "-" . $end] = [
					"start" => $start,
					"end" => $end,
					"cellCssClass" => "_villas-365-booked-date"
				];

				$end = $endDate->modify("+1 day")->format($outputFormat);
				$cellCssClass = "_villas-365-booked-check-out";
				if(array_key_exists($end, $nonAvailableDates))
				{
					$cellCssClass = "_villas-365-booked-date";
				}
				$nonAvailableDates[$end] = [
					"date" => $end,
					"cellCssClass" => $cellCssClass
				];
			}
		}

		return array_values($nonAvailableDates);
	}

	public static function MakeStartEndDatesSelectList($dateRanges, $inputFormat = "Y-m-d", $outputFormat = null, $outputAsSelector = true)
	{
		$startDates = [];
		$endDates = [];

		$startDatesOutput = null;
		$endDatesOutput = null;

		foreach($dateRanges as $dateRange)
		{
			if(($dateRange->availability == "false") || ($dateRange->availability === FALSE))
			{
				$startDate = DateTime::createFromFormat($inputFormat, $dateRange->startDate)->setTime(0, 0, 0);
				$endDate = DateTime::createFromFormat($inputFormat, $dateRange->endDate)->setTime(0, 0, 0)->modify("+1 day");

				$startTimeOutput = null;
				$endTimeOutput = null;
				
				if(!is_null($outputFormat))
				{
					$startTimeOutput = $startDate->format($outputFormat);
					$endTimeOutput = $endDate->format($outputFormat);
				}
				else
				{
					$startTimeOutput = $startDate->getTimestamp();
					$endTimeOutput = $endDate->getTimestamp();
				}

				if(is_null($outputFormat) && $outputAsSelector)
				{
					$startTimeOutput = $startTimeOutput * 1000;
					$endTimeOutput = $endTimeOutput * 1000;
				}

				if($outputAsSelector)
				{
					$startDates[] = ".datepicker table tr td.day[data-date='" . $startTimeOutput . "']";
					$endDates[] = ".datepicker table tr td.day[data-date='" . $endTimeOutput . "']";
				}
				else
				{
					$startDates[] = $startTimeOutput;
					$endDates[] = $endTimeOutput;
				}
			}
		}

		if($outputAsSelector)
		{
			$startDatesOutput = implode(", ", $startDates);
			$endDatesOutput = implode(", ", $endDates);
		}

		return [
			"start" => $startDatesOutput,
			"end" => $endDatesOutput
		];
	}

	public static function MakeDiscountDatesList($discountDateRanges, $inputFormat = "Y-m-d", $outputFormat = "Y-m-d")
	{
		if(is_null($discountDateRanges) || !is_array($discountDateRanges) || (count($discountDateRanges) == 0))
		{
			return [];
		}

		$discountDates = [];

		foreach($discountDateRanges as $discountDateRange)
		{
			$startDate = DateTime::createFromFormat($inputFormat, $discountDateRange->startDate)->setTime(0, 0, 0);
			$endDate = null;

			$inputEndDate = $discountDateRange->endDate;
			if(is_null($inputEndDate) || (trim($inputEndDate) == ""))
			{
				$endDate = (new DateTime("now"))->modify("+2 years");
			}
			else
			{
				$endDate = DateTime::createFromFormat($inputFormat, $discountDateRange->endDate)->setTime(0, 0, 0);
			}

			$start = $startDate->format($outputFormat);
			$end = $endDate->format($outputFormat);
			$discountDates[] = [
				"start" => $start,
				"end" => $end
			];
		}

		return $discountDates;
	}

	public static function MakeDiscountDatesDisplayList($discountDateRanges, $inputFormat = "Y-m-d", $outputFormat = "Y-m-d")
	{
		if(is_null($discountDateRanges) || !is_array($discountDateRanges) || (count($discountDateRanges) == 0))
		{
			return [];
		}

		$discountDates = [];

		$discountColour = Helpers365::GetOption("_template_color_discountColour", "villas-365", false);
		if(($discountColour === FALSE) || (is_null($discountColour)) || ($discountColour == ""))
		{
			$discountColour = "#000000";
		}

		foreach($discountDateRanges as $discountDateRange)
		{
			$startDate = DateTime::createFromFormat($inputFormat, $discountDateRange->startDate)->setTime(0, 0, 0);
			$endDate = null;

			$inputEndDate = $discountDateRange->endDate;
			if(is_null($inputEndDate) || (trim($inputEndDate) == ""))
			{
				$endDate = (new DateTime("now"))->modify("+2 years");
			}
			else
			{
				$endDate = DateTime::createFromFormat($inputFormat, $discountDateRange->endDate)->setTime(0, 0, 0);
			}

			$start = $startDate->format($outputFormat);
			$end = $endDate->format($outputFormat);
			$discountDates[] = [
				"start" => $start,
				"end" => $end,
				"color" => $discountColour
			];
		}

		return array_values($discountDates);
	}

	//Get the translation value.
	public static function GetTranslationValue($string)
	{
		$languageCode = "en";

		//If we have a language code set our variable.
		//If we don't have one then use "en" as the default.
		if(defined('ICL_LANGUAGE_CODE') && !is_null(ICL_LANGUAGE_CODE) && (trim(ICL_LANGUAGE_CODE) !== ""))
		{
			$languageCode = ICL_LANGUAGE_CODE;
		}
		
		//If we do have a language code then check for a translation file.
		$translationsFolder = VILLAS_365_PLUGIN_PATH . 'languages/';
		$translationFile = $translationsFolder . $languageCode . ".json";

		//If we don't have a translation file then just return the base string.
		if(!file_exists($translationFile))
		{
			return $string;
		}
		
		//Get the translation json from the cache if we have it.
		$translationObject = null;
		$cacheKey = $translationFile . "?version=" . VILLAS_365_VERSION; //Append the plugin version to the cache key so it is updated on each plugin update.
		$cachedTranslationObject = wp_cache_get($cacheKey, Helpers365::SITE_CACHE_GROUP);
		if($cachedTranslationObject !== false)
		{
			$translationObject = $cachedTranslationObject;
		}
		else
		{
			$translationJson = file_get_contents($translationFile);

			//If we don't have a translation JSON string then just return the base string and don't try to cache the non-existant json.
			if(is_null($translationJson) || (trim($translationJson) == ""))
			{
				return $string;
			}

			$translationObject = json_decode($translationJson);

			wp_cache_set($cacheKey, $translationObject, Helpers365::SITE_CACHE_GROUP, MONTH_IN_SECONDS); //As the cached translation file doesn't change often it will be cleared after a month.
		}

		//If we don't have a translation array then just return the base string.
		if(!is_object($translationObject))
		{
			return $string;
		}

		//If we don't have a translation for this string then just return the base string.
		if(!property_exists($translationObject, $string))
		{
			return $string;
		}

		$translationValue = $translationObject->{$string};

		//If we don't have a translation value for this string then just return the base string.
		if(is_null($translationValue) || (trim($translationValue) == ""))
		{
			return $string;
		}

		//We should now have a translation value.
		return $translationValue;
	}

	//Get the plural of the string.
	public static function GetTranslationChoice($string, $number = 1)
	{
		$translationValue = Helpers365::GetTranslationValue($string);

		//Try and get all the translations for this value.
		$choices = explode("|", $translationValue);

		//If we have no choices or 1 or fewer choices then return the whole translation value as there aren't any choices.
		if(!is_array($choices) || (count($choices) <= 1))
		{
			return $translationValue;
		}

		//If the number is 1 or less then return the first choice.
		if($number <= 1)
		{
			return $choices[0];
		}

		//If the number is greater than 1 then return the second choice as this is the plural.
		return $choices[1];
	}

	//Some language codes are different in the 365 system than in the WPML plugin.
	public static function Get365LanguageCode()
	{
		if(!defined('ICL_LANGUAGE_CODE'))
		{
			return "en";
		}

		switch(ICL_LANGUAGE_CODE)
		{
			case "pt-pt":
				return "pt";
			default:
				break;
		}

		return ICL_LANGUAGE_CODE;
	}

	//Get the language code for the mobiscroll date picker.
	public static function GetMobiscrollLanguageCode()
	{
		if(!defined('ICL_LANGUAGE_CODE'))
		{
			return "localeEn";
		}

		$languageCode = ICL_LANGUAGE_CODE;
		$languageCode = strtolower($languageCode);
		$languageCode = str_replace("-", " ", $languageCode);
		$languageCode = ucwords($languageCode);
		$languageCode = str_replace(" ", "", $languageCode);

		return "locale" . $languageCode;
	}

	/**
	 * Get365TranslationValue
	 * Get the translation value from a 365 API object if it is available. Otherwise return the base value.
	 *
	 * @param $object - The object containing all the data. eg. a property.
	 * @param Array $baseFieldPath - An array of the nested fields path to the base value. eg. to get $property->brief this would be ["brief"].
	 * @param Array $languageFieldPath - An array of the nested fields path to the language value, excluding the language. eg. to get $property->languages->brief->pt this would be ["languages", "brief"]. The current langauge will be found automatically.
	 * @return string Value for the field either in the base language or the translation. Will return null if the value cannot be found.
	 */
	public static function Get365TranslationValue($object, Array $baseFieldPath, Array $languageFieldPath)
	{
		$languageCode = Helpers365::Get365LanguageCode();

		//If we have the base language then just return the base value.
		if($languageCode == "en")
		{
			$translationValue = Helpers365::GetValueFromObjectProperties($object, $baseFieldPath);

			//If we have a value then return it.
			if(!is_null($translationValue))
			{
				return $translationValue;
			}
		}

		//We don't have the base language so add the current language to the field path.
		$languageFieldPath[] = $languageCode;
		$translationValue = Helpers365::GetValueFromObjectProperties($object, $languageFieldPath);

		//If we have a value then return it.
		if(!is_null($translationValue))
		{
			return $translationValue;
		}

		//If we get here we don't have a value so try and return the base value.
		return Helpers365::GetValueFromObjectProperties($object, $baseFieldPath);
	}

	public static function LogToFile($message, $filename = "villas_365_debug", $appendDate = true)
	{
		$currentDate = "";
		if($appendDate)
		{
			$currentDate = "_" . (new DateTime())->format("Y-m-d");
		}
		$filename .= $currentDate . ".log";

		file_put_contents(WP_CONTENT_DIR . '/' . $filename, "[" . (new DateTime())->format("Y-m-d H:i:s") . "]: " . $message . PHP_EOL, FILE_APPEND);
	}

	//Get a list of the language URLs for the current page for use in a language switcher.
	public static function GetPageTranslationURLs(bool $includeQueryString = true)
	{
		if(!defined('ICL_LANGUAGE_CODE'))
		{
			return null;
		}

		$urls = null;

		//Get languages
		$languages = apply_filters('wpml_active_languages', NULL, 'skip_missing=1');
	 
		if($languages && !empty($languages) && is_array($languages))
		{
			$queryString = "";
			if($includeQueryString && !is_null($_GET) && is_array($_GET) && (count($_GET) > 0))
			{
				$queryString = "?" . http_build_query($_GET);
			}

			$urls = [];
			foreach($languages as $language)
			{
				$propertySlug = Helpers365::GetPropertySlug();
				if(is_null($propertySlug))
				{
					$propertySlug = "";
				}

				$urls[$language['language_code']] = [
					"active" => $language['active'],
					"native_name" => $language['native_name'],
					"url" => $language['url'] . $propertySlug . $queryString
				];
			}
		}
	 
		return $urls;
	}

	public static function StartsWith($haystack, $needle)
	{
		return (substr($haystack, 0, strlen($needle)) === $needle);
	}

	public static function EndsWith($haystack, $needle)
	{
		return (substr($haystack, -(strlen($needle))) === $needle);
	}

	public static function GetCustomSubtitutionCode(
		$property,
		$code,
		$translate = true,
		$sanitizeValue = true,
		$subtituteNewLinesForHTML = true,
		$lengthLimit = null
	)
	{
		if(is_null($property) || is_null($code))
		{
			return null;
		}

		$value = null;

		$language = "en";
		if($translate)
		{
			$language = Helpers365::Get365LanguageCode();
		}

		if(!is_null($property) && !is_null($code))
		{
			$customSubtitutionCodes = null;

			if(!is_null($language) && ($language !== "en") && Helpers365::CheckObjectPropertiesExist($property, ["language_data", $language, "custom_subtitution_code"]))
			{
				$customSubtitutionCodes = Helpers365::GetValueFromObjectProperties($property, ["language_data", $language, "custom_subtitution_code"]);
			}
			elseif(Helpers365::CheckObjectPropertiesExist($property, ["custom_subtitution_code"]))
			{
				$customSubtitutionCodes = Helpers365::GetValueFromObjectProperties($property, ["custom_subtitution_code"]);
			}

			foreach($customSubtitutionCodes as $customSubtitutionCode)
			{
				if(Helpers365::CheckObjectPropertiesExist($customSubtitutionCode, ["subtitution_code"]))
				{
					$subtitutionCode = Helpers365::GetValueFromObjectProperties($customSubtitutionCode, ["subtitution_code"]);
					$subtitutionCode = trim($subtitutionCode, "[]");
					$customcode = str_replace(["%5B" , "%5D"], ["[" , "]"], $code);
					$customcode = trim($customcode, "[]");
					if($subtitutionCode === $customcode)
					{
						$value = Helpers365::GetValueFromObjectProperties($customSubtitutionCode, ["content"]);
						
						if(!is_null($lengthLimit) && is_numeric($lengthLimit) && ($lengthLimit > 0))
						{
							$value = Helpers365::StrLimit($value, $lengthLimit);
						}

						if($subtituteNewLinesForHTML)
						{
							$value = str_replace(PHP_EOL, "<br>", $value);
						}

						if($sanitizeValue)
						{
							$value = wp_kses_post($value);
						}
						break;
					}
				}
			}
		}

		return $value;
	}

	// Get a custom subtitution code from a property or return the default field value.
	// This is in use by one client in their custom child theme to substitute the brief field.
	// See the theme "custom-brief".
	public static function GetPropertyCustomSubtitutionCode(
		$property,
		$code,
		$defaultField = null,
		$sanitizeValue = false,
		$subtituteNewLinesForHTML = false,
		$lengthLimit = null,
		$deafultSanitizeValue = false,
		$deafultSubtituteNewLinesForHTML = false,
		$defaultLengthLimit = null
	)
	{
		if(is_null($defaultField) && (is_null($property) || is_null($code)))
		{
			return null;
		}

		$value = null;

		if(!is_null($property) && !is_null($code) && Helpers365::CheckObjectPropertiesExist($property, ["custom_subtitution_code"]))
		{
			$customSubtitutionCodes = Helpers365::GetValueFromObjectProperties($property, ["custom_subtitution_code"]);
			foreach($customSubtitutionCodes as $customSubtitutionCode)
			{
				if(Helpers365::CheckObjectPropertiesExist($customSubtitutionCode, ["subtitution_code"]))
				{
					$subtitutionCode = Helpers365::GetValueFromObjectProperties($customSubtitutionCode, ["subtitution_code"]);
					if($subtitutionCode === $code)
					{
						$value = Helpers365::GetValueFromObjectProperties($customSubtitutionCode, ["content"]);
						
						if(!is_null($lengthLimit) && is_numeric($lengthLimit) && ($lengthLimit > 0))
						{
							$value = Helpers365::StrLimit($value, $lengthLimit);
						}

						if($subtituteNewLinesForHTML)
						{
							$value = str_replace(PHP_EOL, "<br>", $value);
						}

						if($sanitizeValue)
						{
							$value = wp_kses_post($value);
						}
						break;
					}
				}
			}
		}

		if(is_null($value) && !is_null($defaultField) && !is_null($property) && Helpers365::CheckObjectPropertiesExist($property, [$defaultField]))
		{
			$value = Helpers365::Get365TranslationValue($property, [$defaultField], ["languages", $defaultField]);
			
			if(!is_null($defaultLengthLimit) && is_numeric($defaultLengthLimit) && ($defaultLengthLimit > 0))
			{
				$value = Helpers365::StrLimit($value, $defaultLengthLimit);
			}

			if($deafultSubtituteNewLinesForHTML)
			{
				$value = str_replace(PHP_EOL, "<br>", $value);
			}

			if($deafultSanitizeValue)
			{
				$value = wp_kses_post($value);
			}
		}

		return $value;
	}

	public static function QueryStringToArray($queryString)
	{
		if(Helpers365::StartsWith($queryString, "?"))
		{
			$queryString = substr($queryString, 1);
		}

		$queryItems = [];
		parse_str($queryString, $queryItems);

		return $queryItems;
	}

	public static function ParseVideoUrl(string $url)
	{
		$output = [
			"type" => null, //youtube, vimeo
			"video_id" => null //video ID
		];

		$parsedUrl = null;
		if(!is_null($url))
		{
			if(!Helpers365::StartsWith($url, "http://") && !Helpers365::StartsWith($url, "https://") && !Helpers365::StartsWith($url, "//"))
			{
				$url = "//" . $url;
			}
			$parsedUrl = parse_url($url);
		}

		if(!is_null($parsedUrl) && $parsedUrl !== FALSE)
		{
			if(preg_match("/youtu\.be/i", $parsedUrl["host"]) === 1)
			{
				$output["type"] = "youtube";

				if(array_key_exists("path", $parsedUrl))
				{
					$output["video_id"] = str_replace("/", "", $parsedUrl["path"]);
				}
			}
			elseif(preg_match("/youtube\.com/i", $parsedUrl["host"]) === 1)
			{
				$output["type"] = "youtube";

				if(array_key_exists("query", $parsedUrl))
				{
					$queryString = Helpers365::QueryStringToArray($parsedUrl["query"]);
					
					if(array_key_exists("v", $queryString))
					{
						$output["video_id"] = $queryString["v"];
					}
				}
				
				if(is_null($output["video_id"]) && array_key_exists("path", $parsedUrl))
				{
					$pathArray = explode("/", $parsedUrl["path"]);
					$output["video_id"] = end($pathArray);
				}
			}
			elseif(preg_match("/vimeo\.com/i", $parsedUrl["host"]) === 1)
			{
				$output["type"] = "vimeo";

				if(array_key_exists("path", $parsedUrl))
				{
					$output["video_id"] = str_replace("/video/", "", $parsedUrl["path"]);
					$output["video_id"] = str_replace("/", "", $output["video_id"]);
				}
			}
		}

		return $output;
	}

	public static function GetValueFromArray($array, $key, $default = null, $checkEmptyString = true)
	{
		$currentKey = $key;
		if(is_array($key))
		{
			$currentKey = array_shift($key);
		}

		if(is_null($array) || !is_array($array) || !array_key_exists($currentKey, $array))
		{
			return $default;
		}

		$value = $array[$currentKey];

		if($checkEmptyString && !is_null($value) && is_string($value) && (trim($value) === ''))
		{
			return $default;
		}

		if(is_null($value))
		{
			return $default;
		}

		if(is_array($key) && (count($key) > 0))
		{
			return Helpers365::GetValueFromArray($value, $key, $default, $checkEmptyString);
		}

		return $value;
	}

}

}

add_action('wp', 'Villas365WordPressLoaded');

function Villas365WordPressLoaded()
{
	Helpers365::CheckPropertyPageExists();

	Helpers365::StoreParameters($_GET);
	Helpers365::StoreParameters($_POST);
}

//Create our own translation functions.
//Currently (2020/02/24) we can't get the "offical" method of translation to work for the plugin.
//We are using a simple custom method to store the string translations for the plugin in a JSON file for each language.
//This works well and makes more sense!
if(!function_exists('__v3tn'))
{
	function __v3tn($string, $number = 1, Array $replacements = null, $echo = false)
	{
		$translationValue = Helpers365::GetTranslationChoice($string, $number);

		if(!is_null($replacements) && is_array($replacements) && (count($replacements) > 0))
		{
			foreach($replacements as $replacementId => $replacement)
			{
				$translationValue = str_replace($replacementId, $replacement, $translationValue);
			}
		}

		if($echo)
		{
			echo $translationValue;
			return;
		}
		
		return $translationValue;
	}
}

if(!function_exists('__v3tne') && function_exists('__v3tn'))
{
	function __v3tne($string, $number = 1, Array $replacements = null)
	{
		return __v3tn($string, $number, $replacements, true);
    }
}

if(!function_exists('__v3t'))
{
	function __v3t($string, Array $replacements = null, $echo = false)
	{
		$translationValue = Helpers365::GetTranslationChoice($string);

		if(!is_null($replacements) && is_array($replacements) && (count($replacements) > 0))
		{
			foreach($replacements as $replacementId => $replacement)
			{
				$translationValue = str_replace($replacementId, $replacement, $translationValue);
			}
		}

		if($echo)
		{
			echo $translationValue;
			return;
		}
		
		return $translationValue;
    }
}

if(!function_exists('__v3te') && function_exists('__v3t'))
{
	function __v3te($string, Array $replacements = null)
	{
		return __v3t($string, $replacements, true);
    }
}