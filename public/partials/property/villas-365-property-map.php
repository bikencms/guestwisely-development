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

$googleMapsAPIKey = FALSE;
if(class_exists('Helpers365'))
{
	$googleMapsAPIKey = Helpers365::GetOption("_google_maps_api_key");
}

if($googleMapsAPIKey !== FALSE) :
	wp_enqueue_style('_villas-365-property-map-styles', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-property-map.css", [], VILLAS_365_VERSION);
	wp_enqueue_script('_villas-365-map-helpers', plugin_dir_url( __FILE__ ) . "../../assets/js/villas-365-map-helpers.js", ["jquery"], VILLAS_365_VERSION, true);
	wp_enqueue_script('_villas-365-property-map-scripts', plugin_dir_url( __FILE__ ) . "../../assets/js/villas-365-property-map.js", ["jquery", "_villas-365-map-helpers"], VILLAS_365_VERSION, true);
	wp_enqueue_script('_villas-365-googlemaps', "https://maps.googleapis.com/maps/api/js?v=3&key=" . $googleMapsAPIKey . "&callback=initMap", ["_villas-365-map-helpers", "_villas-365-property-map-scripts"], "3.0", true);

	//Get the property details
	$property = $property_atts["property"];

	if(!is_null($sectionData["value"]) && is_array($sectionData["value"])) :
		$propertyMapCoordinates = "var _coordinates = {
			'latitude': '" . $sectionData["value"]["latitude"] . "',
			'longitude': '" . $sectionData["value"]["longitude"] . "'
		};";

		$propertyMapStyles = 'var _mapStyles = [
			{
				"featureType": "poi.business",
				"stylers": [
					{
						"visibility": "off"
					}
				]
			},
			{
				"featureType": "poi.park",
				"elementType": "labels.text",
				"stylers": [
					{
						"visibility": "off"
					}
				]
			}
		];';
		wp_add_inline_script('_villas-365-property-map-scripts', $propertyMapCoordinates . PHP_EOL . $propertyMapStyles, "before");
	?>
		<div id="_villas-365-map-canvas" class="_villas-365-map-container"></div>
	<?php
	endif;
else : ?>
<div class="_villas-365-bootstrap _villas-365-error-container">
	<div class="container mt-gutter mb-gutter">
		<div class="row">
			<div class="col-12">
				Google Maps API key not set in 365villas settings.<br>
				Please add a valid Google Maps API key in the WordPress 365villas plugin settings.
			</div>
		</div>
	</div>
</div>
<?php endif; ?>