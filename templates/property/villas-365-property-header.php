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

wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", [], VILLAS_365_VERSION);

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides();
wp_add_inline_style('_villas-365-styles', $villas365CSSOverrides);

wp_enqueue_script('_villas-365-property', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-property.js", ["jquery"], VILLAS_365_VERSION, true);
if(isset($sectionData) && !is_null($sectionData) && array_key_exists("propertyUrl", $sectionData) && !is_null($sectionData["propertyUrl"]))
{
	$propertyScriptExtras = "var _villas365CurrentPropertyUrl = '" . $sectionData["propertyUrl"] . "';";
	wp_add_inline_script('_villas-365-property', $propertyScriptExtras, "before");
}
?>