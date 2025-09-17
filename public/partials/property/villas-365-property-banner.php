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

$bannerStyle = "default";
if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("bannerstyle", $property_atts) && !is_null($property_atts["bannerstyle"]))
{
	$bannerStyle = strtolower(trim($property_atts["bannerstyle"]));
}

$propertyBannerHtmlHeaderTemplate = FALSE;
$bannerHtmlHeaderTemplateName = "villas-365-property-banner-html-header.php";
if(isset($bannerStyle) && !is_null($bannerStyle) && ($bannerStyle !== "") && ($bannerStyle === "custom"))
{
	$bannerHtmlHeaderTemplateName = "villas-365-property-banner-custom-html-header.php";
	$propertyBannerHtmlHeaderTemplate = Helpers365::LocateTemplateFile($bannerHtmlHeaderTemplateName, "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');

	//If it can't find the full map template then just use the regular one.
	if($propertyBannerHtmlHeaderTemplate === FALSE)
	{
		$bannerHtmlHeaderTemplateName = "villas-365-property-banner-html-header.php";
		$propertyBannerHtmlHeaderTemplate = Helpers365::LocateTemplateFile($bannerHtmlHeaderTemplateName, "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
	}
}
else
{
	$propertyBannerHtmlHeaderTemplate = Helpers365::LocateTemplateFile($bannerHtmlHeaderTemplateName, "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
}

if($propertyBannerHtmlHeaderTemplate === FALSE)
{
	echo "<pre>Property banner HTML header template could not be found.</pre>";
}
else
{
	include $propertyBannerHtmlHeaderTemplate;
}

//Get the property details
$property = $property_atts["property"];
$gallery = null;
if(!is_null($property))
{
	$gallery = API365::GetPropertyGallery($property->id);
}

$showIntroBox = true;
if(array_key_exists("showintrobox", $property_atts) &&
	!is_null($property_atts["showintrobox"]) &
	((!is_bool($property_atts["showintrobox"]) && $property_atts["showintrobox"] == "false") || (is_bool($property_atts["showintrobox"]) && !$property_atts["showintrobox"])))
{
	$showIntroBox = false;
}

$lightBoxOnly = false;
if(array_key_exists("lightboxonly", $property_atts) &&
	!is_null($property_atts["lightboxonly"]) &
	((!is_bool($property_atts["lightboxonly"]) && $property_atts["lightboxonly"] == "true") || (is_bool($property_atts["lightboxonly"]) && $property_atts["lightboxonly"])))
{
	$lightBoxOnly = true;
}

$bookingPageUrl = get_home_url() . "/book-now";

if(!is_null($property)) :

	$propertyName = Helpers365::Get365TranslationValue($property, ["name"], ["languages", "name"]);

	if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("bookingPageUrl", $property_atts) && !is_null($property_atts["bookingPageUrl"]) && ($property_atts["bookingPageUrl"] !== ""))
	{
		$bookingPageUrl = $property_atts["bookingPageUrl"];
	}

	$bookingPageUrl .= "?property=" . $property->id;

	if(!isset($villas365PropertiesFullHeightBannerClass) || is_null($villas365PropertiesFullHeightBannerClass))
	{
		$villas365PropertiesFullHeightBannerClass = Helpers365::GetPropertiesFullHeightBannerClass();
	}

	$propertyBannerTemplate = FALSE;
	$bannerTemplateName = "villas-365-property-banner.php";
	if(isset($bannerStyle) && !is_null($bannerStyle) && ($bannerStyle !== "") && ($bannerStyle === "custom"))
	{
		$bannerTemplateName = "villas-365-property-banner-custom.php";
		$propertyBannerTemplate = Helpers365::LocateTemplateFile($bannerTemplateName, "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');

		//If it can't find the full map template then just use the regular one.
		if($propertyBannerTemplate === FALSE)
		{
			$bannerTemplateName = "villas-365-property-banner.php";
			$propertyBannerTemplate = Helpers365::LocateTemplateFile($bannerTemplateName, "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
		}
	}
	else
	{
		$propertyBannerTemplate = Helpers365::LocateTemplateFile($bannerTemplateName, "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
	}

	if($propertyBannerTemplate === FALSE)
	{
		echo "<pre>Banner template could not be found.</pre>";
	}
	else
	{
		include $propertyBannerTemplate;
	}

endif; ?>