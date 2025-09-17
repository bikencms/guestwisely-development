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
$limit = $property_atts["limit"];
$relatedProperties = API365::GetPropertyRelated(["property_id" => $property->id, "size" => $limit]);

if(!is_null($relatedProperties) && ($relatedProperties["count"] > 0)) :
	$relatedPropertiesHtmlHeaderTemplate = Helpers365::LocateTemplateFile("villas-365-property-related-html-header.php", "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
	if($relatedPropertiesHtmlHeaderTemplate === FALSE)
	{
		echo "<pre>Related properties HTML header template could not be found.</pre>";
	}
	else
	{
		include $relatedPropertiesHtmlHeaderTemplate;
	}

	if(!isset($propertyPageURL))
	{
		$propertyPageURL = Helpers365::GetPropertyPageURL(get_option("villas-365_property_page_id"));
	}

	if(!isset($villas365PropertiesOpenInNewTabsHTML) || is_null($villas365PropertiesOpenInNewTabsHTML))
	{
		$villas365PropertiesOpenInNewTabsHTML = Helpers365::GetOpenPropertiesInNewTabsHTML();
	}

	$showDiscountLabel = true;
	if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("showdiscountlabel", $property_atts) && (($property_atts["showdiscountlabel"] === 0) || ($property_atts["showdiscountlabel"] === "false") || ($property_atts["showdiscountlabel"] === false)))
	{
		$showDiscountLabel = false;
	}

	$showFeaturedLabel = true;
	if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("showfeaturedlabel", $property_atts) && (($property_atts["showfeaturedlabel"] === 0) || ($property_atts["showfeaturedlabel"] === "false") || ($property_atts["showfeaturedlabel"] === false)))
	{
		$showFeaturedLabel = false;
	}
?>
<div class="_villas-365-bootstrap _villas-365-properties _villas-365-property-related">
	<div class="container">
		<?php foreach(array_chunk($relatedProperties["properties"][0], $limit) as $relatedPropertiesChunk): ?>
		<div class="row">
			<?php foreach($relatedPropertiesChunk as $relatedProperty): ?>
			<div class="<?php echo (!is_null($relatedProperty) ? '_villas-365-property' : ''); ?> col-12 col-lg">
				<?php
				$relatedPropertyTemplate = Helpers365::LocateTemplateFile("villas-365-properties-related-item-grid.php");
				if($relatedPropertyTemplate === FALSE)
				{
					echo "<pre>Related property template could not be found.</pre>";
				}
				else
				{
					include $relatedPropertyTemplate;
				}
				?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>