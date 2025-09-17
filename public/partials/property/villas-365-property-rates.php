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

//Get the property
$property = $property_atts["property"];

$showRateDates = true;
if(array_key_exists("showratedates", $property_atts) &&
	!is_null($property_atts["showratedates"]) &
	((!is_bool($property_atts["showratedates"]) && $property_atts["showratedates"] == "false") || (is_bool($property_atts["showratedates"]) && !$property_atts["showratedates"])))
{
	$showRateDates = false;
}

$showRateName = true;
if(array_key_exists("showratename", $property_atts) &&
	!is_null($property_atts["showratename"]) &
	((!is_bool($property_atts["showratename"]) && $property_atts["showratename"] == "false") || (is_bool($property_atts["showratename"]) && !$property_atts["showratename"])))
{
	$showRateName = false;
}

if(!is_null($property)) :

	$ratesTableData = API365::GetRateTable($property->id);

	//If we have at least one daily rate and one weekly rate we need to show the columns.
	$hasDaily = false;
	$hasWeekly = false;
	foreach($ratesTableData->seasons as $ratesTableItem)
	{
		if(property_exists($ratesTableItem, "nightly"))
		{
			$hasDaily = true;
		}

		if(property_exists($ratesTableItem, "weekly"))
		{
			$hasWeekly = true;
		}

		if($hasDaily && $hasWeekly)
		{
			break;
		}
	}

	$propertyRatesTemplate = "villas-365-property-rates.php";
	if(array_key_exists("readmore", $property_atts) &&
		!is_null($property_atts["readmore"]) &&
		((!is_bool($property_atts["readmore"]) && $property_atts["readmore"] == "true") || (is_bool($property_atts["readmore"]) && $property_atts["readmore"])))
	{
		$propertyRatesTemplate = "villas-365-property-rates-readmore.php";
	}

	$propertyRatesTemplate = Helpers365::LocateTemplateFile($propertyRatesTemplate, "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
	if($propertyRatesTemplate === FALSE)
	{
		echo "<pre>Rates template could not be found.</pre>";
	}
	else
	{
		include $propertyRatesTemplate;
	}
?>
<?php endif; ?>