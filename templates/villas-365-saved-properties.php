<?php
$savedPropertiesPageUrl = "#";

$optionVillas365PropertiesSavedPageId = get_option("villas-365_properties_saved_page_id");
if(!is_null($optionVillas365PropertiesSavedPageId) && (trim($optionVillas365PropertiesSavedPageId) !== "") && is_numeric($optionVillas365PropertiesSavedPageId))
{
	$savedPropertiesPageUrl = get_page_link($optionVillas365PropertiesSavedPageId);
}

if($savedPropertiesPageUrl == "#")
{
	$optionVillas365PropertiesSearchPageId = get_option("villas-365_properties_search_page_id");
	if(!is_null($optionVillas365PropertiesSearchPageId) && (trim($optionVillas365PropertiesSearchPageId) !== "") && is_numeric($optionVillas365PropertiesSearchPageId))
	{
		$savedPropertiesPageUrl = get_page_link($optionVillas365PropertiesSearchPageId);
	}
}
?>
<a href="<?php echo esc_html($savedPropertiesPageUrl) ?>?propertyids=" class="_villas-365-saved-properties" data-href="<?php echo esc_html($savedPropertiesPageUrl) ?>?propertyids=">
	<i class="far fa-heart fa-fw"></i>&nbsp;<span class="_villas-365-saved-properties-count"><i class="fas fa-spinner fa-spin fa-fw"></i></span>&nbsp;<span class="_villas-365-saved-properties-label _villas-365-saved-properties-label-single" style="display:none;"><?php __v3te("property saved"); ?></span><span class="_villas-365-saved-properties-label _villas-365-saved-properties-label-multiple"><?php __v3te("properties saved"); ?></span>
</a>