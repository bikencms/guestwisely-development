<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file should be included in the property page with the shortcode [365-villas-property section=savebutton]
 *
 * @link       https://loyaltymatters.co.uk
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/public/partials
 */

$propertySaveButtonTemplate = Helpers365::LocateTemplateFile("villas-365-property-save-button.php", "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
if($propertySaveButtonTemplate === FALSE)
{
	echo "<pre>Property save button HTML header template could not be found.</pre>";
}
else
{
	wp_enqueue_style('_villas-365-fontawesome', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-fontawesome.css", [], "5.9.0");
	wp_enqueue_style('_villas-365-property-save', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-property-save.css", ["_villas-365-fontawesome"], VILLAS_365_VERSION);
	wp_enqueue_script('_villas-365-property-save', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-property-save.js", ['jquery'], VILLAS_365_VERSION, true);

	$showText = false;
	if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("showtext", $property_atts) && (($property_atts["showtext"] == 1) || ($property_atts["showtext"] == "true") || ($property_atts["showtext"] === true)))
	{
		$showText = true;
	}
	
	include $propertySaveButtonTemplate;
}
?>