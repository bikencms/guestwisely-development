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
if(!isset($property) && isset($property_atts) && array_key_exists("property", $property_atts))
{
	$property = $property_atts["property"];
}

//If we are using icons then load the font awesome styles.
if(!is_null($sectionData["icons"]) && is_bool($sectionData["icons"]) && $sectionData["icons"])
{
	wp_enqueue_style('_villas-365-fontawesome', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-fontawesome.css", [], "5.9.0");
}

wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-styles.css", [], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-property-rooms-styles', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-property-rooms.css", ["_villas-365-styles"], VILLAS_365_VERSION);

if(array_key_exists("value", $sectionData) && !is_null($sectionData["value"]) && is_array($sectionData["value"])) :
?>
<div class="_villas-365-property-<?php echo $sectionData["name"]; ?>">
	<?php foreach($sectionData["value"] as $villas365PropertyRoomName => $villas365PropertyRoom) :
	$roomLabel = esc_html($villas365PropertyRoomName);
	$roomValue = $villas365PropertyRoom;
	if($sectionData["icons"])
	{
		if(strtolower($villas365PropertyRoomName) == "guests")
		{
			$roomLabel = '<i class="_villas-365-property-room-icon fas fa-user"></i>';
		}
		elseif(strtolower($villas365PropertyRoomName) == "bedrooms")
		{
			$roomLabel = '<i class="_villas-365-property-room-icon fas fa-bed"></i>';

			if($roomValue == 0)
			{
				$roomValue = __v3t('Studio');
			}
		}
		elseif(strtolower($villas365PropertyRoomName) == "bathrooms")
		{
			$roomLabel = '<i class="_villas-365-property-room-icon fas fa-bath"></i>';
		}
	}
	?>
	<span class="_villas-365-property-room"><?php echo $roomLabel; ?> <?php echo esc_html($roomValue); ?></span>
	<?php endforeach; ?>
</div>
<?php endif; ?>