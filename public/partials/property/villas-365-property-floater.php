<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://loyaltymatters.co.uk
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/public/partials
 */

$quoteWidget = false;
if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("quotewidget", $property_atts) && (($property_atts["quotewidget"] == 1) || ($property_atts["quotewidget"] == "true") || ($property_atts["quotewidget"] === true)))
{
	$quoteWidget = true;
}

$modal = false;
if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("modal", $property_atts) && (($property_atts["modal"] == 1) || ($property_atts["modal"] == "true") || ($property_atts["modal"] === true)))
{
	$modal = true;
}

wp_enqueue_style('_villas-365-datepicker-styles-custom', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-datepicker.css", [], VILLAS_365_VERSION);
wp_enqueue_script('_villas-365-datepicker-scripts', plugin_dir_url( __FILE__ ) . "../../assets/js/villas-365-datepicker.js", ['jquery'], VILLAS_365_VERSION, true);

wp_enqueue_style('_villas-365-fontawesome', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-fontawesome.css", [], "5.9.0");
wp_enqueue_style('_villas-365-chosen-styles', plugin_dir_url( __FILE__ ) . "../../assets/libs/chosen/chosen.css", ["_villas-365-fontawesome"], "1.8.7");
wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-styles.css", ["_villas-365-fontawesome", "_villas-365-chosen-styles", "_villas-365-datepicker-styles-custom"], VILLAS_365_VERSION);

wp_enqueue_script('_villas-365-chosen-scripts', plugin_dir_url( __FILE__ ) . '../../assets/libs/chosen/chosen.js', ['jquery'], "1.8.7", true);
//Get calendar first day of week from 365villas api

$_firstDayCalendarOfWeek = 0;
$workingDayJS = "";
$arr = ['monday' => 1, 'tuesday'=>2, 'wednesday'=>3, 'thursday'=>4, 'friday'=>5, 'saturday' => 6];
$_defaultConfig = API365::GetDefaultSettings();

if(isset($_defaultConfig->calendar_first_day_of_the_week)) {
	$_365_startWorkingDay = strtolower($_defaultConfig->calendar_first_day_of_the_week);
	if(isset($arr[$_365_startWorkingDay])) {
		$_firstDayCalendarOfWeek = $arr[$_365_startWorkingDay];
	}
}

$workingDayJS .=  "let _365_startWorkingDayCalendar;".PHP_EOL;
$workingDayJS .=  "_365_startWorkingDayCalendar=" . $_firstDayCalendarOfWeek . ";".PHP_EOL;


wp_add_inline_script( '_villas-365-datepicker-scripts', $workingDayJS, "before" );

$quoteWidgetJS = null;
if($quoteWidget)
{
	wp_enqueue_style('_villas-365-property-floater', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-property-quote-widget.css", ["_villas-365-styles"], VILLAS_365_VERSION);
	wp_enqueue_script('_villas-365-property-floater', plugin_dir_url( __FILE__ ) . "../../assets/js/villas-365-property-quote-widget.js", ["jquery", "_villas-365-chosen-scripts", "_villas-365-datepicker-scripts"], VILLAS_365_VERSION, true);

	if($modal)
	{
		wp_enqueue_style('_villas-365-property-floater-modal', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-property-quote-widget-modal.css", ["_villas-365-styles", "_villas-365-property-floater"], VILLAS_365_VERSION);

		$quoteWidgetJS = "var _365_floaterModalBreakPoint = 0;" . PHP_EOL;
		$quoteWidgetJS .= "var _365_floaterModal = true;";
	}
}
else
{
	wp_enqueue_style('_villas-365-property-floater', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-property-floater.css", ["_villas-365-styles"], VILLAS_365_VERSION);
	wp_enqueue_script('_villas-365-property-floater', plugin_dir_url( __FILE__ ) . "../../assets/js/villas-365-property-floater.js", ["jquery", "_villas-365-chosen-scripts", "_villas-365-datepicker-scripts"], VILLAS_365_VERSION, true);

	$quoteWidgetJS = "var _365_floaterModalBreakPoint = 992;";
}

if(!is_null($quoteWidgetJS))
{
	wp_add_inline_script('_villas-365-property-floater', $quoteWidgetJS, "before");
}

if(file_exists(get_stylesheet_directory() . "/365villas/css/villas-365-styles.css"))
{
	wp_enqueue_style('_child-villas-365-styles', get_stylesheet_directory_uri() . "/365villas/css/villas-365-styles.css", ["_villas-365-styles"], wp_get_theme()->get('Version'));
}

if(file_exists(get_stylesheet_directory() . "/365villas/css/villas-365-property-floater.css"))
{
	wp_enqueue_style('_child-villas-365-property-floater-styles', get_stylesheet_directory_uri() . "/365villas/css/villas-365-property-floater.css", ["_child-villas-365-styles", "_villas-365-property-floater"], wp_get_theme()->get('Version'));
}

wp_localize_script('_villas-365-property-floater', '_villas_365_wp_ajax', [
	'ajax_url' => admin_url('admin-ajax.php'),
	'nonce' => wp_create_nonce('_villas_365_calculate_booking')
]);

wp_add_inline_style('_villas-365-datepicker-styles-custom', Helpers365::GetCalendarColours());
wp_add_inline_script('_villas-365-datepicker-scripts', "var _villas365CurrentLocaleDatepicker = '" . Helpers365::GetMobiscrollLanguageCode() . "';", "before");

//Get the property details
$property = $property_atts["property"];
$minimumNights = null;
$defaultSettings = null;
$contactPageUrl = get_home_url() . "/contact";
$bookingPageUrl = get_home_url() . "/book-now";
$enquireLabel = "Inquire";
$contactItems = [];
$nonAvailableDates = [];
$maxGuests = 20;
$showDiscounts = false;

if(!is_null($property))
{
	$defaultSettings = API365::GetDefaultSettings();

	if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("contactPageUrl", $property_atts) && !is_null($property_atts["contactPageUrl"]) && ($property_atts["contactPageUrl"] !== ""))
	{
		$contactPageUrl = $property_atts["contactPageUrl"];
	}

	if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("bookingPageUrl", $property_atts) && !is_null($property_atts["bookingPageUrl"]) && ($property_atts["bookingPageUrl"] !== ""))
	{
		$bookingPageUrl = $property_atts["bookingPageUrl"];
	}

	if(property_exists($property, "minimumStay") && !is_null($property->minimumStay) && ($property->minimumStay !== ""))
	{
		$minimumNights = $property->minimumStay;
	}

	if(!is_null($defaultSettings) && property_exists($defaultSettings, "use_enquire") && ($defaultSettings->use_enquire == 1))
	{
		$enquireLabel = "Enquire";
	}

	if(!is_null($defaultSettings) && property_exists($defaultSettings, "contact_items") && (count($defaultSettings->contact_items) > 0))
	{
		$contactItems = $defaultSettings->contact_items;
	}

	if(property_exists($property, "maxguest") && !is_null($property->maxguest) && ($property->maxguest !== ""))
	{
		$maxGuests = $property->maxguest;
		if(is_null($maxGuests) || is_wp_error($maxGuests) || !is_numeric($maxGuests) || ($maxGuests == 0))
		{
			$maxGuests = API365::GetMaxGuests();
			if(is_null($maxGuests) || is_wp_error($maxGuests) || !is_numeric($maxGuests) || ($maxGuests == 0))
			{
				$maxGuests = 20;
			}
		}
	}
	$maxGuests = (int)$maxGuests;

	if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("showdiscounts", $property_atts) && (($property_atts["showdiscounts"] == 1) || ($property_atts["showdiscounts"] == "true") || ($property_atts["showdiscounts"] === true)))
	{
		$showDiscounts = $property_atts["showdiscounts"];
	}

	$datesJS = "";

	//Non Available Dates
	$nonAvailableDateRanges = API365::GetNonAvailableDates($property->id);

	$nonAvailableDates = Helpers365::MakeNonAvailableDatesList($nonAvailableDateRanges);
	if(!is_null($nonAvailableDates) && (count($nonAvailableDates) > 0))
	{
		$datesJS .= 'var _365_nonAvailableDates = ' . json_encode($nonAvailableDates) . ';';
	}

	$nonAvailableDatesDisplay = Helpers365::MakeNonAvailableDatesDisplayList($nonAvailableDateRanges);
	if(!is_null($nonAvailableDatesDisplay) && (count($nonAvailableDatesDisplay) > 0))
	{
		$datesJS .= 'var _365_nonAvailableDatesDisplay = ' . json_encode($nonAvailableDatesDisplay) . ';';
	}

	//Discount Dates
	if($showDiscounts)
	{
		$discountDateRanges = API365::GetDiscounts($property->id);

		$discountDates = Helpers365::MakeDiscountDatesList($discountDateRanges);
		if(!is_null($discountDates) && (count($discountDates) > 0))
		{
			$datesJS .= 'var _365_discountDates = ' . json_encode($discountDates) . ';';
		}

		$discountDatesDisplay = Helpers365::MakeDiscountDatesDisplayList($discountDateRanges);
		if(!is_null($discountDatesDisplay) && (count($discountDatesDisplay) > 0))
		{
			$datesJS .= 'var _365_discountDatesDisplay = ' . json_encode($discountDatesDisplay) . ';';
		}
	}

	if(!is_null($datesJS) && (trim($datesJS) !== ""))
	{
		wp_add_inline_script('_villas-365-datepicker-scripts', $datesJS, "before");
	}
}

if(!is_null($property))
{
	$propertyFloaterTemplate = Helpers365::LocateTemplateFile("villas-365-property-floater.php", "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
	if($propertyFloaterTemplate === FALSE)
	{
		echo "<pre>Floater template could not be found.</pre>";
	}
	else
	{
		include $propertyFloaterTemplate;
	}
}
?>