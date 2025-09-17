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

$searchFullHtmlHeaderTemplate = Helpers365::LocateTemplateFile("villas-365-search-full-html-header.php");
if($searchFullHtmlHeaderTemplate === FALSE)
{
	echo "<pre>Search full template could not be found.</pre>";
}
else
{
	include $searchFullHtmlHeaderTemplate;
}

$searchPageURL = null;
if(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("usecurrentpageforsearch", $search_atts) && (trim($search_atts["usecurrentpageforsearch"]) !== "") && (($search_atts["usecurrentpageforsearch"] == 1) || ($search_atts["usecurrentpageforsearch"] == "true") || ($search_atts["usecurrentpageforsearch"] === true)))
{
	$searchPageURL = get_page_link(get_post()->ID);
}
elseif(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("searchpageid", $search_atts) && is_numeric($search_atts["searchpageid"]))
{
	$searchPageURL = get_page_link($search_atts["searchpageid"]);
}

$location = "homepage";
if(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("location", $search_atts) && (trim($search_atts["location"]) !== ""))
{
	$location = trim($search_atts["location"]);
}

$showFilter = false;
if(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("showfilter", $search_atts) && (($search_atts["showfilter"] == 1) || ($search_atts["showfilter"] == "true") || ($search_atts["showfilter"] === true)))
{
	$showFilter = $search_atts["showfilter"];
}

$size = "large";
$validSizes = ["small", "medium", "large"];
if(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("size", $search_atts) && (trim($search_atts["size"]) !== "") && in_array($size, $validSizes))
{
	$size = trim($search_atts["size"]);
}

$sizeClass = "_villas-365-large";
$sizeContainerColumnClass = "col-12";
$sizeColumnClass = "col-12 col-md-4 col-lg-2";
switch($size)
{
	case "small":
		$sizeClass = "_villas-365-small";
		$sizeContainerColumnClass = "col-12 col-md-6 col-lg-4";
		$sizeColumnClass = "col-12";
		break;
	case "medium":
		$sizeClass = "_villas-365-medium";
		$sizeContainerColumnClass = "col-12 col-lg-10";
		$sizeColumnClass = "col-12 col-md-4";
		break;
	case "large":
	default:
		break;
}

$defaultQuery = null;
if(isset($search_atts) && !is_null($search_atts) && is_array($search_atts) && (count($search_atts) > 0) && array_key_exists("defaultquery", $search_atts) && !is_null($search_atts["defaultquery"]) && is_array($search_atts["defaultquery"]) && (count($search_atts["defaultquery"]) > 0))
{
	$defaultQuery = $search_atts["defaultquery"];
}

$searchParameters = API365::GetSearchParameters();
if(isset($_GET) && is_array($_GET) && (count($_GET) > 0))
{
	$searchParameters = API365::GetSearchParameters($_GET);
}
elseif(!is_null($defaultQuery) && is_array($defaultQuery) && (count($defaultQuery) > 0))
{
	$searchParameters = API365::GetSearchParameters($defaultQuery);
}

$searchTerms = API365::GetSearchTerms();
$searchSettings = API365::GetSearchSettings($location);
$searchOptions = Helpers365::GetValueFromArray($searchSettings, "searchconfig", []);
$searchAmenities = Helpers365::GetValueFromArray($searchSettings, "amenity_list", []);
$searchCategories = API365::GetCategories([
	"include" => [
		"languages"
	],
	"property_include" => 1
]);

$maxGuests = API365::GetMaxGuests();
if(is_null($maxGuests) || is_wp_error($maxGuests) || !is_numeric($maxGuests) || ($maxGuests == 0))
{
	$maxGuests = 20;
}

$categoryLabel = null;
$tagsLabel = null;
if(is_array($searchTerms) && (count($searchTerms) > 0))
{
	if(array_key_exists("category", $searchTerms) && ($searchTerms["category"] !== ""))
	{
		$categoryLabel = $searchTerms["category"];
	}

	if(array_key_exists("tags", $searchTerms) && ($searchTerms["tags"] !== ""))
	{
		$tagsLabel = $searchTerms["tags"];
	}
}

if(is_null($categoryLabel))
{
	$categoryLabel = __v3t("Category");
}

if(is_null($tagsLabel))
{
	$tagsLabel = __v3t("Tags");
}
?>

<?php if(!is_null($searchPageURL)) :
	$firstRowMaxFieldCount = 5;
	$firstRowFieldCount = 0;
	$firstRowShownFields = [];
?>
<div class="_villas-365-bootstrap _villas-365-search _villas-365-search-full">
	<form method="GET" action="<?php echo $searchPageURL; ?>" accept-charset="UTF-8" class="_villas-365-search-form">
		<div id="_villas-365-search-form-fields" class="container-fluid">
			<div class="row justify-content-center">
				<div class="<?php echo $sizeContainerColumnClass; ?>">
					<div class="row align-items-end _villas-365-date-range-container justify-content-center _villas-365-field-container <?php echo $sizeClass; ?>">
						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_category_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_category_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_category_filter";
						?>
						<div class="<?php echo $sizeColumnClass; ?>">
							<div class="d-border">
								<label class="" for="categoryselect"><?php echo $categoryLabel; ?></label>
								<div class="input-group chosen-input-group search-categories">
									<input type="hidden" name="categoryid" id="_villas-365-search-categoryid" value="<?php echo esc_html($searchParameters["categoryid"]); ?>">
									<input type="hidden" name="propertyid" id="_villas-365-search-propertyid" value="<?php echo esc_html($searchParameters["propertyid"]); ?>">
									<select class="form-control search-text chosen-select chosen-select-single" placeholder="Select" data-placeholder="<?php echo $categoryLabel; ?>">
										<?php
										foreach($searchCategories as $searchCategoryKey => $searchCategory) :
											$selected = "";
											if($searchParameters["categoryid"] == $searchCategoryKey)
											{
												$selected = " selected";
											}

											if($searchCategoryKey != -1) :
										?>
												<option value="category<?php echo $searchCategoryKey; ?>" class="search-category" data-id="<?php echo $searchCategoryKey; ?>" data-type="category"<?php echo $selected ?>><?php echo $searchCategory["name"]; ?></option>
												<optgroup label="<?php echo $searchCategory["name"]; ?>">
											<?php
											endif;

											if(!Helpers365::GetValueFromArray($searchSettings, ["fields", "removed_search_property"])):
											foreach($searchCategory["properties"] as $searchCategoryPropertyKey => $searchCategoryProperty) :
												$propertySelected = "";
												if($searchParameters["propertyid"] == $searchCategoryPropertyKey)
												{
													$propertySelected = " selected";
												}
											?>
											<option value="property<?php echo $searchCategoryPropertyKey; ?>" class="<?php echo (($searchCategoryKey != -1) ? 'search-category-property' : ''); ?>" data-id="<?php echo $searchCategoryPropertyKey; ?>" data-type="property"<?php echo $propertySelected ?>><?php echo $searchCategoryProperty["name"]; ?></option>
											<?php endforeach;
											endif;

											if($searchCategoryKey != -1) : ?>
												</optgroup>
											<?php endif;
										endforeach; ?>
									</select>
									<div class="input-group-append">
										<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_checkinout_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_checkinout_filter"])):
						$firstRowFieldCount = $firstRowFieldCount + 2;
						$firstRowShownFields[] = "show_checkinout_filter";
						$fieldLabelCheckIn = esc_html(Helpers365::GetValueFromArray($searchTerms, "checkin", __v3t("Check-in")));
						$fieldLabelCheckOut = esc_html(Helpers365::GetValueFromArray($searchTerms, "checkout", __v3t("Check-out")));
						?>
						<div class="<?php echo $sizeColumnClass; ?> _villas-365-date-container">
							<div class="d-border">
								<label class="" for="checkin"><?php echo $fieldLabelCheckIn; ?></label>
								<div class="input-group">
									<input type="text" class="form-control _villas-365-checkin _villas-365-date-control _villas-365-date-control-start" value="<?php echo esc_html($searchParameters["checkin"]); ?>" placeholder="Add Date" data-start-field-id="_villas-365-checkin" data-end-field-id="_villas-365-checkout">
									<div class="input-group-append">
										<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="<?php echo $sizeColumnClass; ?> _villas-365-date-container">
							<div class="d-border">
								<label class="" for="checkout"><?php echo $fieldLabelCheckOut; ?></label>
								<div class="input-group">
									<input type="text" class="form-control _villas-365-checkout _villas-365-date-control _villas-365-date-control-end" value="<?php echo esc_html($searchParameters["checkout"]); ?>" placeholder="Add Date">
									<div class="input-group-append">
										<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_adults_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_adults_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_adults_filter";
						$fieldLabelAdults = esc_html(Helpers365::GetValueFromArray($searchTerms, "adults", __v3t("Adults")));
						?>
						<div class="<?php echo $sizeColumnClass; ?>">
							<div class="d-border">
								<label class="" for="adultguests"><?php echo $fieldLabelAdults; ?></label>
								<div class="input-group chosen-input-group search-guests">
									<select name="adultguests" class="form-control search-text chosen-select chosen-select-single" placeholder="1" data-placeholder="<?php echo $fieldLabelAdults; ?>">
										<?php
										for($counter = 1; $counter <= $maxGuests; $counter++) :
											$selected = "";
											if(array_key_exists("adultguests", $searchParameters) && $searchParameters["adultguests"] == $counter)
											{
												$selected = " selected";
											}
										?>
										<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo $counter; ?></option>
										<?php endfor; ?>
									</select>
									<div class="input-group-append">
										<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_children_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_children_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_children_filter";
						$fieldLabelChildren = esc_html(Helpers365::GetValueFromArray($searchTerms, "children", __v3t("Children")));
						?>
						<div class="<?php echo $sizeColumnClass; ?>">
							<div class="d-border">
								<label class="" for="childguests"><?php echo $fieldLabelChildren; ?></label>
								<div class="input-group chosen-input-group search-guests">
									<select name="childguests" class="form-control search-text chosen-select chosen-select-single" placeholder="0" data-placeholder="<?php echo $fieldLabelChildren; ?>">
										<?php
										for($counter = 1; $counter <= $maxGuests; $counter++) :
											$selected = "";
											if(array_key_exists("childguests", $searchParameters) && $searchParameters["childguests"] == $counter)
											{
												$selected = " selected";
											}
										?>
										<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo $counter; ?></option>
										<?php endfor; ?>
									</select>
									<div class="input-group-append">
										<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("min_price_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "min_price_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "min_price_filter";
						$fieldLabelMinPrice = esc_html(Helpers365::GetValueFromArray($searchTerms, "min_price", __v3t("Min Price")));
						?>
						<div class="<?php echo $sizeColumnClass; ?>">
							<?php
							$priceOptions = API365::GetPriceOptions();
							?>
							<div class="d-border">
								<label class="" for="minprice"><?php echo $fieldLabelMinPrice; ?> (<?php __v3te($priceOptions["rate_type"]); ?>)</label>
								<div class="input-group chosen-input-group">
									<select name="minprice" id="minprice" class="form-control search-text chosen-select chosen-select-single" placeholder="0" data-placeholder="<?php echo $fieldLabelMinPrice; ?>">
										<?php foreach($priceOptions["price_options"] as $priceValue => $priceOption) :
											if($priceOption == "")
											{
												$priceOption = $fieldLabelMinPrice;
											}
										?>
										<option value="<?php echo esc_html($priceValue); ?>"<?php echo $searchParameters["minprice"] == $priceValue ? " selected" : ""; ?>><?php echo esc_html($priceOption); ?></option>
										<?php endforeach; ?>
									</select>
									<div class="input-group-append">
										<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("max_price_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "max_price_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "max_price_filter";
						$fieldLabelMaxPrice = esc_html(Helpers365::GetValueFromArray($searchTerms, "max_price", __v3t("Max Price")));
						?>
						<div class="<?php echo $sizeColumnClass; ?>">
							<?php
							$priceOptions = API365::GetPriceOptions();
							?>
							<div class="d-border">
								<label class="" for="maxprice"><?php echo $fieldLabelMaxPrice; ?> (<?php __v3te($priceOptions["rate_type"]); ?>)</label>
								<div class="input-group chosen-input-group">
									<select name="maxprice" id="maxprice" class="form-control search-text chosen-select chosen-select-single" placeholder="0" data-placeholder="<?php echo $fieldLabelMaxPrice; ?>">
										<?php foreach($priceOptions["price_options"] as $priceValue => $priceOption) :
											if($priceOption == "")
											{
												$priceOption = $fieldLabelMaxPrice;
											}
										?>
										<option value="<?php echo esc_html($priceValue); ?>"<?php echo $searchParameters["maxprice"] == $priceValue ? " selected" : ""; ?>><?php echo esc_html($priceOption); ?></option>
										<?php endforeach; ?>
									</select>
									<div class="input-group-append">
										<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_bathrooms_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_bathrooms_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_bathrooms_filter";
						$fieldLabelBathrooms = esc_html(Helpers365::GetValueFromArray($searchTerms, "bathrooms", __v3t("Bathrooms")));
						?>
						<div class="<?php echo $sizeColumnClass; ?>">
							<div class="d-border">
								<label class="" for="bathrooms"><?php echo $fieldLabelBathrooms; ?></label>
								<div class="input-group chosen-input-group">
									<select name="bathrooms" class="form-control search-text chosen-select chosen-select-single" placeholder="1" data-placeholder="<?php echo $fieldLabelBathrooms; ?>">
										<?php
										$bathroomsRange = API365::GetBathroomsRange();
										for($counter = $bathroomsRange["min"]; $counter <= $bathroomsRange["max"]; $counter++) :
											$selected = "";
											if(array_key_exists("bathrooms", $searchParameters) && (strval($searchParameters["bathrooms"]) === strval($counter)))
											{
												$selected = " selected";
											}
										?>
										<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo ($counter == 0 ? __v3t('Studio') : $counter); ?></option>
										<?php endfor; ?>
									</select>
									<div class="input-group-append">
										<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_bedrooms_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_bedrooms_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_bedrooms_filter";
						$fieldLabelBedrooms = esc_html(Helpers365::GetValueFromArray($searchTerms, "bedrooms", __v3t("Bedrooms")));
						?>
						<div class="<?php echo $sizeColumnClass; ?>">
							<div class="d-border">
								<label class="" for="bedrooms"><?php echo $fieldLabelBedrooms; ?></label>
								<div class="input-group chosen-input-group">
									<label class="" for="bedrooms"><?php echo $fieldLabelBedrooms; ?></label>
									<select name="bedrooms" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $fieldLabelBedrooms; ?>" data-placeholder="<?php echo $fieldLabelBedrooms; ?>">
										<option value=""><?php echo $fieldLabelBedrooms; ?></option>
										<?php
										$bedroomsRange = API365::GetBedroomsRange();
										for($counter = $bedroomsRange["min"]; $counter <= $bedroomsRange["max"]; $counter++) :
											$selected = "";
											if(array_key_exists("bedrooms", $searchParameters) && (strval($searchParameters["bedrooms"]) === strval($counter)))
											{
												$selected = " selected";
											}
										?>
										<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo ($counter == 0 ? __v3t('Studio') : $counter); ?></option>
										<?php endfor; ?>
									</select>
									<div class="input-group-append">
										<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_city_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_city_filter"])):
						$cities = API365::GetCities();
						if(count($cities) > 0):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_city_filter";
						$fieldLabelCity = esc_html(Helpers365::GetValueFromArray($searchTerms, "city", __v3t("City")));
						?>
						<div class="<?php echo $sizeColumnClass; ?>">
							<div class="d-border">
								<label class="" for="city"><?php echo $fieldLabelCity; ?></label>
								<div class="input-group chosen-input-group">
									<select name="city" class="form-control search-text chosen-select chosen-select-single" placeholder="Select" data-placeholder="<?php echo $fieldLabelCity; ?>">
										<?php
										foreach($cities as $city) :
											$selected = "";
											if(array_key_exists("city", $searchParameters) && (strval($searchParameters["city"]) === strval($city)))
											{
												$selected = " selected";
											}
										?>
										<option value="<?php echo esc_html($city); ?>"<?php echo $selected; ?>><?php echo esc_html($city); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<?php endif;
						endif; ?>

						<?php
						if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_tags_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_tags_filter"])):
							$firstRowFieldCount++;
							$firstRowShownFields[] = "show_tags_filter";
							$searchTags = API365::GetTags();
							if(!is_null($searchTags)): ?>
							<div class="<?php echo $sizeColumnClass; ?>">
								<div class="d-border">
									<label class="" for="tag_id"><?php echo $tagsLabel; ?></label>
									<div class="input-group chosen-input-group search-tags">
										<select name="tag_id" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $tagsLabel; ?>" data-placeholder="<?php echo $tagsLabel; ?>">
											<option value=""><?php echo $tagsLabel; ?></option>
											<?php
											foreach($searchTags as $searchTagId => $searchTag):
												$selected = "";
												if(array_key_exists("tag_id", $searchParameters) && ($searchParameters["tag_id"] == $searchTagId))
												{
													$selected = " selected";
												}
											?>
											<option value="<?php echo $searchTagId; ?>"<?php echo $selected ?>><?php echo $searchTag; ?></option>
											<?php endforeach; ?>
										</select>
										<div class="input-group-append">
											<span class="input-group-text input-group-text-icon-sm"><i class="fas fa-fw fa-chevron-down"></i></span>
										</div>
									</div>
								</div>
							</div>
						<?php
							endif;
						endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_searchterm_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_searchterm_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_searchterm_filter";
						$fieldLabelKeywords = esc_html(Helpers365::GetValueFromArray($searchTerms, "searchterm", __v3t("Keywords")));
						?>
						<div class="<?php echo $sizeColumnClass; ?>">
							<label class="" for="q"><?php echo $fieldLabelKeywords; ?></label>
							<div class="input-group">
								<input name="q" type="text" class="form-control" value="<?php echo esc_html($searchParameters["q"]); ?>" placeholder="Type">
							</div>
						</div>
						<?php endif; ?>
						
						<div class="<?php echo $sizeColumnClass; ?> mt-md-0 search-container">
							<div class="d-flex">
								<?php if($showFilter) : ?>
								<button type="button" class="_villas-365-filter-button btn btn-primary"><i class="fas fa-fw fa-sliders-h"></i><span class="sr-only"><?php __v3te("Filter"); ?></span></button>
								<?php endif; ?>
								<button type="submit" class="_villas-365-search-button btn btn-primary">
									<div class="input-group justify-content-center">
										<div class="input-group-append">
											<span class="input-group-text search"><i class="fas fa-fw fa-search"></i></span>
										</div>
									</div>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if($showFilter) : ?>
		<div id="_villas-365-search-form-filter" style="display: none;">
			<div class="_villas-365-search-form-filter-inner">
				<div class="container-fluid">
					<div class="row align-items-end _villas-365-date-range-container _villas-365-field-container">
						<?php if(!in_array("show_category_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_category_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_category_filter";
						?>
						<div class="col-12 col-md-4 col-lg-2">
							<div class="d-border">
								<label class="" for="categoryselect"><?php echo $categoryLabel; ?></label>
								<div class="chosen-select-wide search-categories">
									<input type="hidden" name="categoryid" id="_villas-365-search-categoryid" value="<?php echo esc_html($searchParameters["categoryid"]); ?>">
									<input type="hidden" name="propertyid" id="_villas-365-search-propertyid" value="<?php echo esc_html($searchParameters["propertyid"]); ?>">
									<select class="form-control search-text chosen-select chosen-select-single" placeholder="Select" data-placeholder="<?php echo $categoryLabel; ?>">
										<option value="">Select</option>
										<?php
										foreach($searchCategories as $searchCategoryKey => $searchCategory) :
											$selected = "";
											if($searchParameters["categoryid"] == $searchCategoryKey)
											{
												$selected = " selected";
											}

											if($searchCategoryKey != -1) :
										?>
												<option value="category<?php echo $searchCategoryKey; ?>" class="search-category" data-id="<?php echo $searchCategoryKey; ?>" data-type="category"<?php echo $selected ?>><?php echo $searchCategory["name"]; ?></option>
												<optgroup label="<?php echo $searchCategory["name"]; ?>">
											<?php
											endif;

											if(!Helpers365::GetValueFromArray($searchSettings, ["fields", "removed_search_property"])):
											foreach($searchCategory["properties"] as $searchCategoryPropertyKey => $searchCategoryProperty) :
												$propertySelected = "";
												if($searchParameters["propertyid"] == $searchCategoryPropertyKey)
												{
													$propertySelected = " selected";
												}
											?>
											<option value="property<?php echo $searchCategoryPropertyKey; ?>" class="<?php echo (($searchCategoryKey != -1) ? 'search-category-property' : ''); ?>" data-id="<?php echo $searchCategoryPropertyKey; ?>" data-type="property"<?php echo $propertySelected ?>><?php echo $searchCategoryProperty["name"]; ?></option>
											<?php endforeach;
											endif;

											if($searchCategoryKey != -1) : ?>
												</optgroup>
											<?php endif;
										endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(!in_array("show_checkinout_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_checkinout_filter"])):
						$firstRowFieldCount = $firstRowFieldCount + 2;
						$firstRowShownFields[] = "show_checkinout_filter";
						$fieldLabelCheckIn = esc_html(Helpers365::GetValueFromArray($searchTerms, "checkin", __v3t("Check-in")));
						$fieldLabelCheckOut = esc_html(Helpers365::GetValueFromArray($searchTerms, "checkout", __v3t("Check-out")));
						?>
						<div class="col-12 col-md-4 col-lg-2 _villas-365-date-container">
							<div class="d-border">
								<label class="" for="checkin"><?php echo $fieldLabelCheckIn; ?></label>
								<input type="text" class="form-control _villas-365-checkin _villas-365-date-control _villas-365-date-control-start" value="<?php echo esc_html($searchParameters["checkin"]); ?>" placeholder="Add Date" data-start-field-id="_villas-365-checkin" data-end-field-id="_villas-365-checkout">
							</div>
						</div>
						<div class="col-12 col-md-4 col-lg-2 _villas-365-date-container">
							<div class="d-border">
								<label class="" for="checkout"><?php echo $fieldLabelCheckOut; ?></label>
								<input type="text" class="form-control _villas-365-checkout _villas-365-date-control _villas-365-date-control-end" value="<?php echo esc_html($searchParameters["checkout"]); ?>" placeholder="Add Date">
							</div>
						</div>
						<?php endif; ?>

						<?php if(!in_array("show_adults_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_adults_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_adults_filter";
						$fieldLabelAdults = esc_html(Helpers365::GetValueFromArray($searchTerms, "adults", __v3t("Adults")));
						?>
						<div class="col-12 col-md-4 col-lg-2">
							<div class="d-border">
								<label class="" for="adultguests"><?php echo $fieldLabelAdults; ?></label>
								<div class="search-guests">
									<select name="adultguests" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $fieldLabelAdults; ?>" data-placeholder="<?php echo $fieldLabelAdults; ?>">
										<option value="">0</option>
										<?php
										for($counter = 1; $counter <= $maxGuests; $counter++) :
											$selected = "";
											if(array_key_exists("adultguests", $searchParameters) && $searchParameters["adultguests"] == $counter)
											{
												$selected = " selected";
											}
										?>
										<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo $counter; ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(!in_array("show_children_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_children_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_children_filter";
						$fieldLabelChildren = esc_html(Helpers365::GetValueFromArray($searchTerms, "children", __v3t("Children")));
						?>
						<div class="col-12 col-md-4 col-lg-2">
							<div class="d-border">
								<label class="" for="childguests"><?php echo $fieldLabelChildren; ?></label>
								<div class="search-guests">
									<select name="childguests" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $fieldLabelChildren; ?>" data-placeholder="<?php echo $fieldLabelChildren; ?>">
										<option value="">0</option>
										<?php
										for($counter = 1; $counter <= $maxGuests; $counter++) :
											$selected = "";
											if(array_key_exists("childguests", $searchParameters) && $searchParameters["childguests"] == $counter)
											{
												$selected = " selected";
											}
										?>
										<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo $counter; ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
						</div>
						<?php endif; ?>

						<?php if(!in_array("min_price_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "min_price_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "min_price_filter";
						$fieldLabelMinPrice = esc_html(Helpers365::GetValueFromArray($searchTerms, "min_price", __v3t("Min Price")));
						?>
						<div class="col-12 col-md-4 col-lg-2">
							<?php
							$priceOptions = API365::GetPriceOptions();
							?>
							<div class="d-border">
								<label class="" for="minprice"><?php echo $fieldLabelMinPrice; ?> (<?php __v3te($priceOptions["rate_type"]); ?>)</label>
								<select name="minprice" id="minprice" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $fieldLabelMinPrice; ?>" data-placeholder="<?php echo $fieldLabelMinPrice; ?>">
									<?php foreach($priceOptions["price_options"] as $priceValue => $priceOption) :
										if($priceOption == "")
										{
											$priceOption = $fieldLabelMinPrice;
										}	
									?>
									<option value="<?php echo esc_html($priceValue); ?>"<?php echo $searchParameters["minprice"] == $priceValue ? " selected" : ""; ?>><?php echo esc_html($priceOption); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<?php endif; ?>

						<?php if(!in_array("max_price_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "max_price_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "max_price_filter";
						$fieldLabelMaxPrice = esc_html(Helpers365::GetValueFromArray($searchTerms, "max_price", __v3t("Max Price")));
						?>
						<div class="col-12 col-md-4 col-lg-2">
							<?php
							$priceOptions = API365::GetPriceOptions();
							?>
							<div class="d-border">
								<label class="" for="maxprice"><?php echo $fieldLabelMaxPrice; ?> (<?php __v3te($priceOptions["rate_type"]); ?>)</label>
								<select name="maxprice" id="maxprice" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $fieldLabelMaxPrice; ?>" data-placeholder="<?php echo $fieldLabelMaxPrice; ?>">
									<?php foreach($priceOptions["price_options"] as $priceValue => $priceOption) :
										if($priceOption == "")
										{
											$priceOption = $fieldLabelMaxPrice;
										}
									?>
									<option value="<?php echo esc_html($priceValue); ?>"<?php echo $searchParameters["maxprice"] == $priceValue ? " selected" : ""; ?>><?php echo esc_html($priceOption); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<?php endif; ?>

						<?php if(!in_array("show_bathrooms_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_bathrooms_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_bathrooms_filter";
						$fieldLabelBathrooms = esc_html(Helpers365::GetValueFromArray($searchTerms, "bathrooms", __v3t("Bathrooms")));
						?>
						<div class="col-12 col-md-4 col-lg-2">
							<div class="d-border">
								<label class="" for="bathrooms"><?php echo $fieldLabelBathrooms; ?></label>
								<select name="bathrooms" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $fieldLabelBathrooms; ?>" data-placeholder="<?php echo $fieldLabelBathrooms; ?>">
									<option value=""><?php echo $fieldLabelBathrooms; ?></option>
									<?php
									$bathroomsRange = API365::GetBathroomsRange();
									for($counter = $bathroomsRange["min"]; $counter <= $bathroomsRange["max"]; $counter++) :
										$selected = "";
										if(array_key_exists("bathrooms", $searchParameters) && (strval($searchParameters["bathrooms"]) === strval($counter)))
										{
											$selected = " selected";
										}
									?>
									<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo ($counter == 0 ? __v3t('Studio') : $counter); ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
						<?php endif; ?>

						<?php if(!in_array("show_bedrooms_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_bedrooms_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_bedrooms_filter";
						$fieldLabelBedrooms = esc_html(Helpers365::GetValueFromArray($searchTerms, "bedrooms", __v3t("Bedrooms")));
						?>
						<div class="col-12 col-md-4 col-lg-2">
							<div class="d-border">
								<label class="" for="bedrooms"><?php echo $fieldLabelBedrooms; ?></label>
								<select name="bedrooms" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $fieldLabelBedrooms; ?>" data-placeholder="<?php echo $fieldLabelBedrooms; ?>">
									<option value=""><?php echo $fieldLabelBedrooms; ?></option>
									<?php
									$bedroomsRange = API365::GetBedroomsRange();
									for($counter = $bedroomsRange["min"]; $counter <= $bedroomsRange["max"]; $counter++) :
										$selected = "";
										if(array_key_exists("bedrooms", $searchParameters) && (strval($searchParameters["bedrooms"]) === strval($counter)))
										{
											$selected = " selected";
										}
									?>
									<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo ($counter == 0 ? __v3t('Studio') : $counter); ?></option>
									<?php endfor; ?>
								</select>
							</div>
						</div>
						<?php endif; ?>

						<?php if(!in_array("show_city_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_city_filter"])):
						$cities = API365::GetCities();
						if(count($cities) > 0):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_city_filter";
						$fieldLabelCity = esc_html(Helpers365::GetValueFromArray($searchTerms, "city", __v3t("City")));
						?>
						<div class="col-12 col-md-4 col-lg-2">
							<div class="d-border">
								<label class="" for="city"><?php echo $fieldLabelCity; ?></label>
								<select name="city" class="form-control search-text chosen-select chosen-select-single" placeholder="Select" data-placeholder="<?php echo $fieldLabelCity; ?>">
									<?php
									foreach($cities as $city) :
										$selected = "";
										if(array_key_exists("city", $searchParameters) && (strval($searchParameters["city"]) === strval($city)))
										{
											$selected = " selected";
										}
									?>
									<option value="<?php echo esc_html($city); ?>"<?php echo $selected; ?>><?php echo esc_html($city); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<?php endif;
						endif; ?>

						<?php
						if(!in_array("show_tags_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_tags_filter"])):
							$firstRowFieldCount++;
							$firstRowShownFields[] = "show_tags_filter";
							$searchTags = API365::GetTags();
							if(!is_null($searchTags)): ?>
							<div class="col-12 col-md-4 col-lg-2">
								<div class="d-border">
									<label class="" for="tag_id"><?php echo $tagsLabel; ?></label>
									<div class="search-tags">
										<select name="tag_id" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $tagsLabel; ?>" data-placeholder="<?php echo $tagsLabel; ?>">
											<option value="">Select</option>
											<?php
											foreach($searchTags as $searchTagId => $searchTag):
												$selected = "";
												if(array_key_exists("tag_id", $searchParameters) && ($searchParameters["tag_id"] == $searchTagId))
												{
													$selected = " selected";
												}
											?>
											<option value="<?php echo $searchTagId; ?>"<?php echo $selected ?>><?php echo $searchTag; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
						<?php
							endif;
						endif; ?>

						<?php if(($firstRowFieldCount != $firstRowMaxFieldCount) && !in_array("show_searchterm_filter", $firstRowShownFields) && Helpers365::GetValueFromArray($searchSettings, ["fields", "show_searchterm_filter"])):
						$firstRowFieldCount++;
						$firstRowShownFields[] = "show_searchterm_filter";
						$fieldLabelKeywords = esc_html(Helpers365::GetValueFromArray($searchTerms, "searchterm", __v3t("Keywords")));
						?>
						<div class="col-12 col-md-4 col-lg-2">
							<div class="d-border">
								<label class="" for="q"><?php echo $fieldLabelKeywords; ?></label>
								<input name="q" type="text" class="form-control" value="<?php echo esc_html($searchParameters["q"]); ?>" placeholder="Type">
							</div>
						</div>
						<?php endif; ?>
					</div>

					<?php if(count($searchOptions) > 0) : ?>
					<div class="row _villas-365-hr-container">
						<div class="col">
							<div class="_villas-365-hr"></div>
						</div>
					</div>
					<div class="row _villas-365-field-container _villas-365-options-container">
						<div class="col-12 col-md-4 col-lg-2 _villas-365-option-container">
							<strong><?php __v3te("Filter Options"); ?>:</strong>
						</div>

						<div class="col-12 col-lg-10">
							<div class="row align-items-center _villas-365-option-checkboxes">
								<?php
								$hasSearchOptionsSelected = array_key_exists("searchoptions", $searchParameters);
								foreach($searchOptions as $searchOptionKey => $searchOption) :
								$searchParametersValue = "";
								if($hasSearchOptionsSelected && array_key_exists($searchOptionKey, $searchParameters["searchoptions"]))
								{
									$searchParametersValue = ($searchParameters["searchoptions"][$searchOptionKey] === "on" ? "checked" : "");
								}	
								?>
								<div class="col-12 col-md-4 col-lg-5-columns _villas-365-option-checkbox _villas-365-option-container">
									<label>
									<input type="checkbox" name="searchoptions[<?php echo esc_html($searchOptionKey); ?>]" <?php echo esc_html($searchParametersValue); ?>>&nbsp;<?php echo esc_html($searchOption); ?>
									</label>
									<div class="_villas-365-option-checkbox-custom-container">
										<span class="_villas-365-option-checkbox-custom <?php echo esc_html($searchParametersValue); ?>">
											<i class="fas fa-fw fa-check"></i>
										</span>
										<span class="_villas-365-option-checkbox-custom-text"><?php echo esc_html($searchOption); ?></span>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<?php if(count($searchAmenities) > 0) : ?>
					<div class="row _villas-365-hr-container">
						<div class="col">
							<div class="_villas-365-hr"></div>
						</div>
					</div>
					<div class="row _villas-365-field-container _villas-365-options-container">
						<div class="col-12 col-md-4 col-lg-2 _villas-365-option-container">
							<strong><?php __v3te("Amenities"); ?>:</strong>
						</div>

						<div class="col-12 col-lg-10">
							<div class="row align-items-center _villas-365-option-checkboxes">
								<?php
								$hasAmenitiesSelected = array_key_exists("amenity", $searchParameters);
								foreach($searchAmenities as $searchAmenityKey => $searchAmenity) :
								$searchParametersValue = "";
								if($hasAmenitiesSelected && array_key_exists($searchAmenityKey, $searchParameters["amenity"]))
								{
									$searchParametersValue = ($searchParameters["amenity"][$searchAmenityKey] === "on" ? "checked" : "");
								}
								?>
								<div class="col-12 col-md-4 col-lg-5-columns _villas-365-option-checkbox _villas-365-option-container">
									<label>
										<input type="checkbox" name="amenity[<?php echo esc_html($searchAmenityKey); ?>]" <?php echo esc_html($searchParametersValue); ?>>&nbsp;<?php echo esc_html($searchAmenity); ?>
									</label>
									<div class="_villas-365-option-checkbox-custom-container">
										<span class="_villas-365-option-checkbox-custom <?php echo esc_html($searchParametersValue); ?>">
											<i class="fas fa-fw fa-check"></i>
										</span>
										<span class="_villas-365-option-checkbox-custom-text"><?php echo esc_html($searchAmenity); ?></span>
									</div>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<div class="row _villas-365-hr-container">
						<div class="col">
							<div class="_villas-365-hr"></div>
						</div>
					</div>
					<div class="row justify-content-end _villas-365-field-container _villas-365-search-form-filter-buttons">
						<div class="col-12 col-md-5 col-lg-4 mt-2 mt-md-0">
							<div class="d-flex">
								<button type="button" id="_villas-365-search-reset" class="btn btn btn-outline-primary _villas-365-property-outline-button"><?php __v3te("Reset"); ?></button>
								<button type="submit" id="_villas-365-search-apply" class="btn btn-primary _villas-365-property-button"><?php __v3te("Apply"); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div id="_datetime-hidden-fields">
			<input id="_villas-365-checkin" type='hidden' name='checkin' value="<?php echo esc_html($searchParameters["checkin"]); ?>">
			<input id="_villas-365-checkout" type='hidden' name='checkout' value="<?php echo esc_html($searchParameters["checkout"]); ?>">
		</div>
		<div id="_hidden-fields">
			<?php
			$searchBy = "";
			if(array_key_exists("sort", $searchParameters) && ($searchParameters["sort"] != "") && ($searchParameters["sort"] != "default"))
			{
				$searchBy = $searchParameters["sort"];
			}
			?>
			<input id="_villas-365-sort" type='hidden' name='sort' value="<?php echo esc_html($searchBy); ?>">

			<?php
			$listView = "";
			if(array_key_exists("view", $searchParameters) && ($searchParameters["view"] != ""))
			{
				$listView = $searchParameters["view"];
			}
			?>
			<input id="_villas-365-view" type='hidden' name='view' value="<?php echo esc_html($listView); ?>">
		</div>
	</form>
</div>
<?php else : ?>
<div class="_villas-365-bootstrap _villas-365-search">
	<div class="container">
		<div class="row">
			<div class="col">
			<?php __v3te("Please specify a valid search page ID to use the search control."); ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>