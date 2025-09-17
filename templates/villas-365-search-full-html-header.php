<?php
wp_enqueue_style('_villas-365-datepicker-styles-custom', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-datepicker.css", [], VILLAS_365_VERSION);
wp_enqueue_script('_villas-365-datepicker-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-datepicker.js", ['jquery'], VILLAS_365_VERSION, true);

wp_enqueue_style('_villas-365-fontawesome', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-fontawesome.css", [], "5.9.0");
wp_enqueue_style('_villas-365-chosen-styles', VILLAS_365_PLUGIN_URL . "public/assets/libs/chosen/chosen.css", ["_villas-365-fontawesome"], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", ["_villas-365-fontawesome", "_villas-365-chosen-styles", "_villas-365-datepicker-styles-custom"], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-search-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-search.css", ["_villas-365-styles"], VILLAS_365_VERSION);

wp_enqueue_script('_villas-365-chosen-scripts', VILLAS_365_PLUGIN_URL . "public/assets/libs/chosen/chosen.js", ['jquery'], VILLAS_365_VERSION, true);
wp_enqueue_script('_villas-365-search-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-search.js", ["jquery", "_villas-365-chosen-scripts", "_villas-365-datepicker-scripts"], VILLAS_365_VERSION, true);

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides();
wp_add_inline_style('_villas-365-styles', $villas365CSSOverrides);

wp_add_inline_style('_villas-365-datepicker-styles-custom', Helpers365::GetCalendarColours());
wp_add_inline_script('_villas-365-datepicker-scripts', "var _villas365CurrentLocaleDatepicker = '" . Helpers365::GetMobiscrollLanguageCode() . "';", "before");

wp_add_inline_script('_villas-365-chosen-scripts', "var _villas365SearchAvailable = true;", "before");

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