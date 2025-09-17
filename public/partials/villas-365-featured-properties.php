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

$propertiesFeaturedHtmlHeaderTemplate = Helpers365::LocateTemplateFile("villas-365-properties-featured-html-header.php");
if($propertiesFeaturedHtmlHeaderTemplate === FALSE)
{
	echo "<pre>Featured styles template could not be found.</pre>";
}
else
{
	include $propertiesFeaturedHtmlHeaderTemplate;
}

$propertiesPerRow = 3;
if(isset($propertiesFeatured_atts) && (count($propertiesFeatured_atts) > 0) && array_key_exists("perrow", $propertiesFeatured_atts) && is_numeric($propertiesFeatured_atts["perrow"]))
{
	$propertiesPerRow = $propertiesFeatured_atts["perrow"];
}

$propertiesLimit = 3;
if(isset($propertiesFeatured_atts) && (count($propertiesFeatured_atts) > 0) && array_key_exists("limit", $propertiesFeatured_atts) && is_numeric($propertiesFeatured_atts["limit"]))
{
	$propertiesLimit = $propertiesFeatured_atts["limit"];
}

$propertyPageURL = null;
if(isset($propertiesFeatured_atts) && (count($propertiesFeatured_atts) > 0) && array_key_exists("propertypageid", $propertiesFeatured_atts) && is_numeric($propertiesFeatured_atts["propertypageid"]))
{
	$propertyPageURL = Helpers365::GetPropertyPageURL($propertiesFeatured_atts["propertypageid"]);
}

if(!isset($villas365PropertiesOpenInNewTabsHTML) || is_null($villas365PropertiesOpenInNewTabsHTML))
{
	$villas365PropertiesOpenInNewTabsHTML = Helpers365::GetOpenPropertiesInNewTabsHTML();
}

$discountsPageURL = null;
if(isset($propertiesFeatured_atts) && (count($propertiesFeatured_atts) > 0) && array_key_exists("propertiesdiscountspageid", $propertiesFeatured_atts) && (trim($propertiesFeatured_atts["propertiesdiscountspageid"]) !== "") && (trim($propertiesFeatured_atts["propertiesdiscountspageid"]) !== "0") &&is_numeric($propertiesFeatured_atts["propertiesdiscountspageid"]))
{
	$propertiesDiscountsPageId = $propertiesFeatured_atts["propertiesdiscountspageid"];

	if(!is_null($propertiesDiscountsPageId) && is_numeric($propertiesDiscountsPageId))
	{
		$discountsPageURL = get_page_link($propertiesDiscountsPageId);
	}
}

$showSaveButton = false;
$propertySaveButtonTemplate = null;
if(isset($propertiesFeatured_atts) && (count($propertiesFeatured_atts) > 0) && array_key_exists("showsavebutton", $propertiesFeatured_atts) && (($propertiesFeatured_atts["showsavebutton"] == 1) || ($propertiesFeatured_atts["showsavebutton"] == "true") || ($propertiesFeatured_atts["showsavebutton"] === true)))
{
	$showSaveButton = true;

	wp_enqueue_style('_villas-365-fontawesome', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-fontawesome.css", [], "5.9.0");
	wp_enqueue_style('_villas-365-property-save', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-property-save.css", ["_villas-365-fontawesome"], VILLAS_365_VERSION);
	wp_enqueue_script('_villas-365-property-save', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-property-save.js", ['jquery'], VILLAS_365_VERSION, true);

	$propertySaveButtonTemplate = Helpers365::LocateTemplateFile("villas-365-property-save-button.php", "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
	if($propertySaveButtonTemplate === FALSE)
	{
		$propertySaveButtonTemplate = null;
	}
}
$showDiscountLabel = true;

// if(isset($propertiesFeatured_atts) && array_key_exists("showdiscountlabel", $propertiesFeatured_atts) && (($propertiesFeatured_atts["showdiscountlabel"] == 0) || ($propertiesFeatured_atts["showdiscountlabel"] == "false") || ($propertiesFeatured_atts["showdiscountlabel"] === false))) {
if(isset($propertiesFeatured_atts) && (isset($propertiesFeatured_atts["showdiscountlabel"]) 
&& 

($propertiesFeatured_atts["showdiscountlabel"] === "false" || $propertiesFeatured_atts["showdiscountlabel"] ===false)

)) {
	$showDiscountLabel = false;
	
} else if(isset($propertiesFeatured_atts) && (isset($propertiesFeatured_atts["showdiscounttab"]) 
&& 

($propertiesFeatured_atts["showdiscounttab"] === "false" || $propertiesFeatured_atts["showdiscounttab"] ===false)

)) {
	$showDiscountLabel = false;
}

$showFeaturedLabel = true;
if(isset($propertiesFeatured_atts) && (isset($propertiesFeatured_atts["showfeaturedlabel"]) 
&& 

($propertiesFeatured_atts["showfeaturedlabel"] === "false" || $propertiesFeatured_atts["showfeaturedlabel"] ===false)

)) {
	$showFeaturedLabel =false;
} else if(isset($propertiesFeatured_atts) && (isset($propertiesFeatured_atts["showfeaturetab"]) 
&& 

($propertiesFeatured_atts["showfeaturetab"] === "false" || $propertiesFeatured_atts["showfeaturetab"] ===false)

)) {
	$showFeaturedLabel =false ;
}

$detailLabel = "Reserve";
if ( get_option("villas-365_detail_label") && get_option("villas-365_detail_label") != "" ) {
	$detailLabel = get_option("villas-365_detail_label");
}

//Get the property details
$properties = API365::SearchProperties([
	"isfeatured" => 1,
	"limit" => $propertiesLimit
]);
if(!is_null($properties) && ($properties["count"] > 0)) :
?>
<div class="_villas-365-bootstrap _villas-365-properties _villas-365-properties-featured">
	<div class="container">
		<?php foreach(array_chunk($properties["properties"], $propertiesPerRow) as $featuredPropertiesChunk): ?>
		<div class="row">
			<?php foreach($featuredPropertiesChunk as $featuredProperty): ?>
			<div class="_villas-365-property col-md margin-bottom-gutter">
				<?php
				$featuredPropertyTemplate = Helpers365::LocateTemplateFile("villas-365-properties-featured-item-grid.php");
				if($featuredPropertyTemplate === FALSE)
				{
					echo "<pre>Featured property template could not be found.</pre>";
				}
				else
				{
					include $featuredPropertyTemplate;
				}
				?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>