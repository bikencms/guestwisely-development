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

//Get the property details
$property = $property_atts["property"];
$gallery = null;
$gallerySingle = false;
if(!is_null($property))
{
	$gallery = API365::GetPropertyGallery($property->id);
}

if(!is_null($property) && !is_null($gallery) && (count($gallery) > 1))
{
	wp_enqueue_style('_villas-365-fontawesome', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-fontawesome.css", [], "5.9.0");
	wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-styles.css", ["_villas-365-fontawesome"], VILLAS_365_VERSION);
	wp_enqueue_style('_villas-365-fancybox-styles', VILLAS_365_PLUGIN_URL . "public/assets/libs/fancybox/fancybox.css", ["_villas-365-styles"], "3.5.7");
	wp_enqueue_style('_villas-365-property-styles', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-property.css", ["_villas-365-styles"], VILLAS_365_VERSION);
	wp_enqueue_script('_villas-365-bootstrap', plugin_dir_url( __FILE__ ) . "../../assets/libs/bootstrap.js", ["jquery"], "4.3.1", true);
	wp_enqueue_script('_villas-365-scripts', plugin_dir_url( __FILE__ ) . '../../assets/js/villas-365-scripts.js', ['jquery', '_villas-365-bootstrap'], VILLAS_365_VERSION, true);
	wp_enqueue_script('_villas-365-fancybox', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-fancybox.js", ["jquery", "_villas-365-scripts"], "3.5.7", true);
	wp_enqueue_script('_villas-365-property-image-scroller', plugin_dir_url( __FILE__ ) . "../../assets/js/villas-365-property-image-scroller.js", ["jquery", "_villas-365-scripts"], VILLAS_365_VERSION, true);

	$propertyImageScrollerTemplate = Helpers365::LocateTemplateFile("villas-365-property-image-scroller.php", "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
	if($propertyImageScrollerTemplate === FALSE)
	{
		echo "<pre>Image scroller template could not be found.</pre>";
	}
	else
	{
		include $propertyImageScrollerTemplate;
	}
	
} ?>