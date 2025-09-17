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

wp_enqueue_style('_villas-365-datepicker-styles', plugin_dir_url( __FILE__ ) . "../../assets/template-1/css/villas-365-datepicker.css", [], "1.0.0");
wp_enqueue_script('_villas-365-bootstrap-scripts', plugin_dir_url( __FILE__ ) . "../../assets/template-1/js/villas-365-datepicker.js", ['jquery'], "1.0.0", true);

wp_enqueue_style('_villas-365-fontawesome', "https://use.fontawesome.com/releases/v5.8.2/css/all.css", [], "5.8.2", false);
wp_enqueue_style('_villas-365-chosen', plugin_dir_url( __FILE__ ) . "../../assets/template-1/libs/chosen/chosen.css", ["_villas-365-fontawesome"], "1.0");
wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../../assets/template-1/css/villas-365-styles.css", ["_villas-365-chosen", "_villas-365-fontawesome", "_villas-365-datepicker-styles"], "1.0");
wp_enqueue_script('_villas-365-scripts', plugin_dir_url( __FILE__ ) . '../../assets/template-1/js/villas-365-scripts.js', ['jquery', '_villas-365-bootstrap-scripts'], "1.0", true);

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides(true);
wp_add_inline_style('_villas-365-styles', $villas365CSSOverrides);

$searchPageURL = null;
if(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("searchpageid", $search_atts) && is_numeric($search_atts["searchpageid"]))
{
	$searchPageURL = get_page_link($search_atts["searchpageid"]);
}

$searchParameters = API365::GetSearchParameters();
if(isset($_GET) && is_array($_GET))
{
	$searchParameters = API365::GetSearchParameters($_GET);
}

$searchOptions = API365::GetSearchOptions();
$searchCategories = API365::GetCategories([
	"include" => [
		"languages"
	],
	"property_include" => 1
]);
?>

<?php if(!is_null($searchPageURL)) : ?>
<div class="_villas-365-bootstrap _villas-365-search _villas-365-search-1">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-12">
				<form method="GET" action="<?php echo $searchPageURL; ?>" accept-charset="UTF-8" class="_villas-365-search-form">
					<div class="row align-items-end _villas-365-date-range-container">
						<div class="col-12 col-lg-2 mb-3 mb-lg-0 pr-lg-0">
							<label class="d-block d-lg-none" for="categoryselect"><?php __v3te("Category"); ?></label>
							<div class="input-group chosen-input-group chosen-select-wide search-categories">
								<input type="hidden" name="categoryid" id="_villas-365-search-categoryid" value="">
								<input type="hidden" name="propertyid" id="_villas-365-search-propertyid" value="">
								<select class="form-control search-text chosen-select chosen-select-single" placeholder="<?php __v3te("Category"); ?>" data-placeholder="<?php __v3te("Category"); ?>">
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
										<?php
										endif;

										foreach($searchCategory["properties"] as $searchCategoryPropertyKey => $searchCategoryProperty) :
											$propertySelected = "";
											if($searchParameters["propertyid"] == $searchCategoryPropertyKey)
											{
												$propertySelected = " selected";
											}
										?>
										<option value="property<?php echo $searchCategoryPropertyKey; ?>" class="<?php echo (($searchCategoryKey != -1) ? 'search-category-property' : ''); ?>" data-id="<?php echo $searchCategoryPropertyKey; ?>" data-type="property"<?php echo $propertySelected ?>><?php echo $searchCategoryProperty["name"]; ?></option>
										<?php endforeach; ?>

									<?php endforeach; ?>
								</select>
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-stream"></i></span>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg mb-3 mb-lg-0 px-lg-0 _villas-365-date-container">
							<label class="d-block d-lg-none" for="checkin"><?php __v3te("Arrival Date"); ?></label>
							<div class="input-group">
								<input type="date" name="checkin" class="form-control _villas-365-checkin _villas-365-date-control" value="<?php echo esc_html($searchParameters["checkin"]); ?>" placeholder="Check-in">
								<div class="input-group-append">
									<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg mb-3 mb-lg-0 px-lg-0 _villas-365-date-container">
							<label class="d-block d-lg-none" for="checkout"><?php __v3te("Departure Date"); ?></label>
							<div class="input-group">
								<input type="date" name="checkout" class="form-control _villas-365-checkout _villas-365-date-control" value="<?php echo esc_html($searchParameters["checkout"]); ?>" placeholder="Check-out">
								<div class="input-group-append">
									<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg mb-3 mb-lg-0 px-lg-0">
							<label class="d-block d-lg-none" for="guests"><?php __v3te("Guests"); ?></label>
							<div class="input-group chosen-input-group">
								<select name="guests" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php __v3te("Guests"); ?>" data-placeholder="<?php __v3te("Guests"); ?>">
									<option value=""></option>
									<?php
									for($counter = 1; $counter <= 20; $counter++) :
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
						<div class="col-12 col-lg mb-4 mb-lg-0 px-lg-0">
							<label class="d-block d-lg-none" for="bedrooms"><?php __v3te("Bedrooms"); ?></label>
							<div class="input-group chosen-input-group">
								<select name="bedrooms" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php __v3te("Bedrooms"); ?>" data-placeholder="<?php __v3te("Bedrooms"); ?>">
									<option value=""></option>
									<?php
									for($counter = 1; $counter <= 20; $counter++) :
										$selected = "";
										if($searchParameters["bedrooms"] == $counter)
										{
											$selected = " selected";
										}
									?>
									<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo $counter; ?></option>
									<?php endfor; ?>
								</select>
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-bed"></i></span>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-2 mb-4 mb-lg-0 px-lg-0">
							<?php
							$priceOptions = API365::GetPriceOptions();
							?>
							<label class="d-block d-lg-none" for="price"><?php __v3te("Price From"); ?> (<?php __v3te($priceOptions["rate_type"]); ?>)</label>
							<div class="input-group chosen-input-group">
								<select name="price" id="price" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php __v3te("Price"); ?>" data-placeholder="<?php __v3te("Price From"); ?>">
									<?php foreach($priceOptions["price_options"] as $priceValue => $priceOption) : ?>
									<option value="<?php echo esc_html($priceValue); ?>"<?php echo $searchParameters["price"] == $priceValue ? " selected" : ""; ?>><?php echo esc_html($priceOption); ?></option>
									<?php endforeach; ?>
								</select>
								<div class="input-group-append">
									<span class="input-group-text no-border currency-symbol"><?php echo esc_html($priceOptions["currency_symbol"]); ?></span>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-auto pl-lg-0 d-none d-lg-block">
							<button type="submit" class="_villas-365-search-button btn btn-primary btn-block"><i class="fas fa-search d-lg-none d-xl-inline-block"></i><span class="sr-only"><?php __v3te("Search"); ?></span></button>
						</div>
					</div>
					<div class="row mt-lg-2">
						<?php if(count($searchOptions) > 0) : ?>
						<div class="col-12 col-lg _villas-365-option-checkboxes mb-4 mb-lg-0 text-left text-md-center">
							<?php foreach($searchOptions as $searchOptionKey => $searchOption) : ?>
							<span class="_villas-365-option-checkbox d-block d-md-inline-block mb-2 mb-md-0">
								<label>
									<input type="checkbox" name="searchoptions[<?php echo esc_html($searchOptionKey); ?>]" <?php echo esc_html($searchParameters[$searchOptionKey]); ?>>&nbsp;<?php echo esc_html($searchOption); ?>
								</label>
								<div class="_villas-365-option-checkbox-custom-container">
									<span class="_villas-365-option-checkbox-custom <?php echo esc_html($searchParameters[$searchOptionKey]); ?>">
										<i class="fas fa-check"></i>
									</span>
									<span class="_villas-365-option-checkbox-custom-text"><?php echo esc_html($searchOption); ?></span>
								</div>
							</span>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>

						<div class="col-12 d-block d-lg-none">
							<button type="submit" class="_villas-365-search-button btn btn-primary btn-block"><i class="fas fa-search d-lg-none d-xl-inline-block"></i>&nbsp;<?php __v3te("Search"); ?></button>
						</div>
					</div>
					<div id="_datetime-hidden-fields"></div>
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