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

wp_enqueue_style('_villas-365-datepicker-styles-custom', plugin_dir_url( __FILE__ ) . "../assets/css/villas-365-datepicker.css", [], VILLAS_365_VERSION);
wp_enqueue_script('_villas-365-datepicker-scripts', plugin_dir_url( __FILE__ ) . "../assets/js/villas-365-datepicker.js", ['jquery'], VILLAS_365_VERSION, true);

wp_enqueue_style('_villas-365-fontawesome', plugin_dir_url( __FILE__ ) . "../assets/css/villas-365-fontawesome.css", [], "5.9.0");
wp_enqueue_style('_villas-365-chosen-styles', plugin_dir_url( __FILE__ ) . "../assets/libs/chosen/chosen.css", ["_villas-365-fontawesome"], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../assets/css/villas-365-styles.css", ["_villas-365-fontawesome", "_villas-365-chosen-styles", "_villas-365-datepicker-styles-custom"], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-search-styles', plugin_dir_url( __FILE__ ) . "../assets/css/villas-365-search.css", ["_villas-365-styles"], VILLAS_365_VERSION);

wp_enqueue_script('_villas-365-chosen-scripts', plugin_dir_url( __FILE__ ) . '../assets/libs/chosen/chosen.js', ['jquery'], VILLAS_365_VERSION, true);
wp_enqueue_script('_villas-365-search-scripts', plugin_dir_url( __FILE__ ) . "../assets/js/villas-365-search.js", ["jquery", "_villas-365-chosen-scripts", "_villas-365-datepicker-scripts"], VILLAS_365_VERSION, true);

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides();
wp_add_inline_style('_villas-365-styles', $villas365CSSOverrides);

wp_add_inline_style('_villas-365-datepicker-styles-custom', Helpers365::GetCalendarColours());
wp_add_inline_script('_villas-365-datepicker-scripts', "var _villas365CurrentLocaleDatepicker = '" . Helpers365::GetMobiscrollLanguageCode() . "';", "before");

wp_add_inline_script('_villas-365-chosen-scripts', "var _villas365SearchAvailable = true;", "before");

//Get calendar first day of week from 365villas api

$_firstDayCalendarOfWeek = 0;
$workingDayJS = "";
$arr = ['monday' => 1, 'tuesday'=>2, 'wednesday'=>3, 'thursday'=>4, 'friday'=>5, 'saturday' => 6];
$_defaultConfig = API365::GetDefaultSettings();

if(isset($_defaultConfig->calendar_first_day_of_the_week)) {
	$_365_startWorkingDay = strtolower($_defaultConfig->calendar_first_day_of_the_week);
	if(isset($arr[$_365_startWorkingDay])) {
		$_firstDayCalendarOfWeek = $arr[$_365_startWorkingDay];
	}
}

$workingDayJS .=  "let _365_startWorkingDayCalendar;".PHP_EOL;
$workingDayJS .=  "_365_startWorkingDayCalendar=" . $_firstDayCalendarOfWeek . ";".PHP_EOL;


wp_add_inline_script( '_villas-365-datepicker-scripts', $workingDayJS, "before" );

$searchPageURL = null;
if(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("usecurrentpageforsearch", $search_atts) && (trim($search_atts["usecurrentpageforsearch"]) !== "") && (($search_atts["usecurrentpageforsearch"] == 1) || ($search_atts["usecurrentpageforsearch"] == "true") || ($search_atts["usecurrentpageforsearch"] === true)))
{
	$searchPageURL = get_page_link(get_post()->ID);
}
elseif(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("searchpageid", $search_atts) && is_numeric($search_atts["searchpageid"]))
{
	$searchPageURL = get_page_link($search_atts["searchpageid"]);
}

$defaultQuery = null;
if(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("defaultquery", $search_atts) && !is_null($search_atts["defaultquery"]) && is_array($search_atts["defaultquery"]) && (count($search_atts["defaultquery"]) > 0))
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
$searchOptions = API365::GetSearchOptions();
$searchCategories = API365::GetCategories([
	"include" => [
		"languages"
	],
	"property_include" => 1
]);
$searchPluginOptions = API365::GetSearchPluginOptions();

$searchTags = null;
if(is_array($searchPluginOptions) && array_key_exists("show_tags_filter", $searchPluginOptions) && ($searchPluginOptions["show_tags_filter"] == "1"))
{
	$searchTags = API365::GetTags();
}

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

$fieldLabelCheckIn = esc_html(Helpers365::GetValueFromArray($searchTerms, "checkin", __v3t("Check-in")));
$fieldLabelCheckOut = esc_html(Helpers365::GetValueFromArray($searchTerms, "checkout", __v3t("Check-out")));
$fieldLabelGuests = esc_html(Helpers365::GetValueFromArray($searchTerms, "guests", __v3t("Guests")));
$fieldLabelBedrooms = esc_html(Helpers365::GetValueFromArray($searchTerms, "bedrooms", __v3t("Bedrooms")));
?>

<?php if(!is_null($searchPageURL)) : ?>
<div class="_villas-365-bootstrap _villas-365-search _villas-365-search-2">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col">
				<form method="GET" action="<?php echo $searchPageURL; ?>" accept-charset="UTF-8" class="_villas-365-search-form">
					<div class="row _villas-365-date-range-container justify-content-center">
						<div class="col-12 col-md-6 col-lg-2 mb-3 pr-md-2 px-lg-0">
							<label class="d-block d-lg-none" for="categoryselect"><?php echo $categoryLabel; ?></label>
							<div class="input-group chosen-input-group chosen-select-wide search-categories">
								<input type="hidden" name="categoryid" id="_villas-365-search-categoryid" value="<?php echo esc_html($searchParameters["categoryid"]); ?>">
								<input type="hidden" name="propertyid" id="_villas-365-search-propertyid" value="<?php echo esc_html($searchParameters["propertyid"]); ?>">
								<select class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $categoryLabel; ?>" data-placeholder="<?php echo $categoryLabel; ?>">
									<option value=""></option>
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

										if(!is_array($searchPluginOptions) || !array_key_exists("removed_search_property", $searchPluginOptions) || ($searchPluginOptions["removed_search_property"] == "0")):
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
									<span class="input-group-text"><i class="fas fa-stream"></i></span>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6 col-lg-2 mb-3 pl-md-2 px-lg-0 _villas-365-date-container">
							<label class="d-block d-lg-none" for="checkin"><?php __v3te("Arrival Date"); ?></label>
							<div class="input-group">
								<input type="text" class="form-control _villas-365-checkin _villas-365-date-control _villas-365-date-control-start" value="<?php echo esc_html($searchParameters["checkin"]); ?>" placeholder="<?php echo $fieldLabelCheckIn; ?>" data-start-field-id="_villas-365-checkin" data-end-field-id="_villas-365-checkout">
								<div class="input-group-append">
									<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6 col-lg-2 mb-3 pl-md-2 px-lg-0 _villas-365-date-container">
							<label class="d-block d-lg-none" for="checkout"><?php __v3te("Departure Date"); ?></label>
							<div class="input-group">
								<input type="text" class="form-control _villas-365-checkout _villas-365-date-control _villas-365-date-control-end" value="<?php echo esc_html($searchParameters["checkout"]); ?>" placeholder="<?php echo $fieldLabelCheckOut; ?>">
								<div class="input-group-append">
									<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								</div>
							</div>
						</div>
						<?php if(!is_null($searchTags)): ?>
						<div class="col-12 col-md-6 col-lg-2 mb-3 pr-md-2 px-lg-0">
							<label class="d-block d-lg-none" for="tag_id"><?php echo $tagsLabel; ?></label>
							<div class="input-group chosen-input-group search-tags">
								<select name="tag_id" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $tagsLabel; ?>" data-placeholder="<?php echo $tagsLabel; ?>">
									<option value=""></option>
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
									<span class="input-group-text"><i class="fas fa-th-large"></i></span>
								</div>
							</div>
						</div>
						<?php else: ?>
						<div class="col-12 col-md-6 col-lg-2 mb-3 pr-md-2 px-lg-0">
							<label class="d-block d-lg-none" for="guests"><?php echo $fieldLabelGuests; ?></label>
							<div class="input-group chosen-input-group search-guests">
								<select name="guests" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $fieldLabelGuests; ?>" data-placeholder="<?php echo $fieldLabelGuests; ?>">
									<option value=""></option>
									<?php
									for($counter = 1; $counter <= $maxGuests; $counter++) :
										$selected = "";
										if($searchParameters["guests"] == $counter)
										{
											$selected = " selected";
										}
									?>
									<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo $counter; ?></option>
									<?php endfor; ?>
								</select>
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-user-friends"></i></span>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<div class="col-12 col-md-6 col-lg-2 mb-3 pl-md-2 pl-lg-0 pr-lg-0">
							<label class="d-block d-lg-none" for="bedrooms"><?php echo $fieldLabelBedrooms; ?></label>
							<div class="input-group chosen-input-group">
								<select name="bedrooms" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php echo $fieldLabelBedrooms; ?>" data-placeholder="<?php echo $fieldLabelBedrooms; ?>">
									<option value=""></option>
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
									<span class="input-group-text no-border"><i class="fas fa-bed"></i></span>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-2 px-lg-0 d-none d-lg-block">
							<button type="submit" class="_villas-365-search-button btn btn-primary btn-block"><i class="fas fa-search"></i>&nbsp;<?php __v3te("Search"); ?></button>
						</div>
					</div>
					<div class="row justify-content-between">
						<?php if(count($searchOptions) > 0) : ?>
						<div class="col-12 col-md _villas-365-option-checkboxes mb-3 mb-lg-0 text-left text-md-center">
							<?php foreach($searchOptions as $searchOptionKey => $searchOption) :
							$searchParametersValue = "";
							if(array_key_exists($searchOptionKey, $searchParameters))
							{
								$searchParametersValue = $searchParameters[$searchOptionKey];
							}	
							?>
							<span class="_villas-365-option-checkbox d-block d-md-inline-block mb-2 mb-md-0">
								<label>
									<input type="checkbox" name="searchoptions[<?php echo esc_html($searchOptionKey); ?>]" <?php echo esc_html($searchParametersValue); ?>>&nbsp;<?php echo esc_html($searchOption); ?>
								</label>
								<div class="_villas-365-option-checkbox-custom-container">
									<span class="_villas-365-option-checkbox-custom <?php echo esc_html($searchParametersValue); ?>">
										<i class="fas fa-check"></i>
									</span>
									<span class="_villas-365-option-checkbox-custom-text"><?php echo esc_html($searchOption); ?></span>
								</div>
							</span>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>

						<div class="col-12 d-block d-lg-none">
							<button type="submit" class="_villas-365-search-button btn btn-primary btn-block"><i class="fas fa-search"></i>&nbsp;<?php __v3te("Search"); ?></button>
						</div>
					</div>
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
		</div>
	</div>
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