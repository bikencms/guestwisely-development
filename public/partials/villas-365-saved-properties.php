<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/public/partials
 */

wp_enqueue_style('_villas-365-fontawesome', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-fontawesome.css", [], "5.9.0");
wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", ['_villas-365-fontawesome'], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-saved-properties', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-saved-properties.css", ['_villas-365-styles', '_villas-365-fontawesome'], VILLAS_365_VERSION);
wp_enqueue_script('_villas-365-saved-properties', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-saved-properties.js", ['jquery'], VILLAS_365_VERSION, true);

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides();
wp_add_inline_style('_villas-365-styles', $villas365CSSOverrides);

$savedPropertiesTemplate = Helpers365::LocateTemplateFile("villas-365-saved-properties.php");
if($savedPropertiesTemplate === FALSE)
{
	echo "<pre>Saved properties template could not be found.</pre>";
}
else
{
	include $savedPropertiesTemplate;
}
?>