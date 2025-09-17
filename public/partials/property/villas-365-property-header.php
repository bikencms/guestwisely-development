<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file should be included in the property page with the shortcode [365-villas-property section=header]
 *
 * @link       https://loyaltymatters.co.uk
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/public/partials
 */

$propertyHeaderTemplate = Helpers365::LocateTemplateFile("villas-365-property-header.php", "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
if($propertyHeaderTemplate === FALSE)
{
	echo "<pre>Property banner HTML header template could not be found.</pre>";
}
else
{
	include $propertyHeaderTemplate;
}
?>