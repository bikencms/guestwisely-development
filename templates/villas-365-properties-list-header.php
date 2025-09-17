<?php
$searchPageURLForSort = null;
$propertiesSearchPageId = null;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("usecurrentpageforsearch", $propertiesList_atts) && (trim($propertiesList_atts["usecurrentpageforsearch"]) !== "") && (($propertiesList_atts["usecurrentpageforsearch"] == 1) || ($propertiesList_atts["usecurrentpageforsearch"] == "true") || ($propertiesList_atts["usecurrentpageforsearch"] === true)))
{
	$propertiesSearchPageId = get_post()->ID;
}
elseif(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("propertiessearchpageid", $propertiesList_atts) && (trim($propertiesList_atts["propertiessearchpageid"]) !== "") && is_numeric($propertiesList_atts["propertiessearchpageid"]))
{
	$propertiesSearchPageId = $propertiesList_atts["propertiessearchpageid"];
}

if(!is_null($propertiesSearchPageId) && is_numeric($propertiesSearchPageId))
{
	$searchPageURLForSort = get_page_link($propertiesSearchPageId);
}
if(!isset($discountsOnly) || (is_bool($discountsOnly) && ($discountsOnly === false))):
?>
<div class="container">
	<div class="row">
		<div class="col">
			<h2 class="_villas-365-properties-title"><?php echo esc_html($properties["totalProperties"]); ?> <?php __v3te("Available"); ?></h2>

			<div class="_villas-365-properties-title-separator"></div>
		</div>
	</div>

	<div class="row align-items-center">
		<div class="col-12 col-sm mb-half-gutter mb-sm-0">
			<div class="_villas-365-properties-view-controls">
				<span class="_villas-365-properties-view-control _villas-365-properties-view-control-title"><?php __v3te("View"); ?>:</span>
				<?php
				$newViewData = $searchParametersSearchResults;
				unset($newViewData["view"]);
				?>
				<a class="_villas-365-properties-view-control _villas-365-properties-view-button<?php echo ($searchParametersSearchResults["view"] === 'map' ? ' active' : ''); ?>" href="<?php echo ($searchPageURLForSort . '?' . http_build_query($newViewData) . '&view=map'); ?>" data-href="<?php echo ($searchPageURLForSort . '?' . http_build_query($newViewData) . '&view=map'); ?>"><i class="fas fa-map-marker-alt"></i><span class="sr-only">Map</span></a>
				<a class="_villas-365-properties-view-control _villas-365-properties-view-button<?php echo ($searchParametersSearchResults["view"] === 'grid' ? ' active' : ''); ?>" href="<?php echo ($searchPageURLForSort . '?' . http_build_query($newViewData) . '&view=grid'); ?>" data-href="<?php echo ($searchPageURLForSort . '?' . http_build_query($newViewData) . '&view=grid'); ?>"><i class="fas fa-th"></i><span class="sr-only">Grid</span></a>
				<a class="_villas-365-properties-view-control _villas-365-properties-view-button<?php echo ($searchParametersSearchResults["view"] === 'list' ? ' active' : ''); ?>" href="<?php echo ($searchPageURLForSort . '?' . http_build_query($newViewData) . '&view=list'); ?>" data-href="<?php echo ($searchPageURLForSort . '?' . http_build_query($newViewData) . '&view=list'); ?>"><i class="fas fa-list"></i><span class="sr-only">List</span></a>
			</div>
		</div>
		<div class="col-12 col-sm-auto">
			<div class="_villas-365-properties-sort-controls">
				<?php
				$newSortData = $searchParametersSearchResults;
				unset($newSortData["sort"]);
				?>
				<span class="_villas-365-properties-sort-control-label"><?php __v3te("Sort"); ?>:</span>
				<select name="sort" class="_villas-365-properties-sort-control form-control search-text chosen-select chosen-select-single" data-href="<?php echo ($searchPageURLForSort . '?' . http_build_query($newSortData) . '&sort='); ?>" data-placeholder="<?php __v3te("Select"); ?>">
					<option value=""<?php echo (!array_key_exists("sort", $searchParametersSearchResults) || is_null($searchParametersSearchResults["sort"]) || ($searchParametersSearchResults["sort"] === "") || ($searchParametersSearchResults["sort"] === 'default') ? ' selected' : ''); ?>></option>
					<option value="price-high"<?php echo (array_key_exists("sort", $searchParametersSearchResults) && ($searchParametersSearchResults["sort"] === 'price-high') ? ' selected' : ''); ?>><?php __v3te("Price high to low"); ?></option>
					<option value="price-low"<?php echo (array_key_exists("sort", $searchParametersSearchResults) && ($searchParametersSearchResults["sort"] === 'price-low') ? ' selected' : ''); ?>><?php __v3te("Price low to high"); ?></option>
					<option value="bedrooms"<?php echo (array_key_exists("sort", $searchParametersSearchResults) && ($searchParametersSearchResults["sort"] === 'bedrooms') ? ' selected' : ''); ?>><?php __v3te("Bedrooms"); ?></option>
					<option value="name"<?php echo (array_key_exists("sort", $searchParametersSearchResults) && ($searchParametersSearchResults["sort"] === 'name') ? ' selected' : ''); ?>><?php __v3te("Name"); ?></option>
				</select>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>