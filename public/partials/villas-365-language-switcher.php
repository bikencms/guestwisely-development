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

wp_enqueue_style('_villas-365-language-switcher-styles', plugin_dir_url( __FILE__ ) . "../assets/css/villas-365-language-switcher.css", [], VILLAS_365_VERSION);

$languageUrls = Helpers365::GetPageTranslationURLs();
if(!is_null($languageUrls) && is_array($languageUrls) && (count($languageUrls) > 0))
{
	$languageSwitcherTemplate = Helpers365::LocateTemplateFile("villas-365-language-switcher.php");
	if($languageSwitcherTemplate === FALSE)
	{
		echo "<pre>Language switcher template could not be found.</pre>";
	}
	else
	{
		include $languageSwitcherTemplate;
	}
}
?>