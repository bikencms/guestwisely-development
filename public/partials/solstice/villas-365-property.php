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

wp_enqueue_style('_villas-365-datepicker-styles', plugin_dir_url( __FILE__ ) . "../../assets/solstice/css/villas-365-datepicker.css", [], "1.0.0");
wp_enqueue_script('_villas-365-datepicker-scripts', plugin_dir_url( __FILE__ ) . "../../assets/solstice/js/villas-365-datepicker.js", ['jquery'], "1.0.0", true);

wp_enqueue_style('_villas-365-fontawesome', "https://use.fontawesome.com/releases/v5.8.2/css/all.css", [], "5.8.2", false);
wp_enqueue_style('_villas-365-fancybox-styles', "https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css", [], "3.5.7", false);
wp_enqueue_style('_villas-365-chosen', plugin_dir_url( __FILE__ ) . "../../assets/solstice/libs/chosen/chosen.css", ["_villas-365-fontawesome"], "1.0");
wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../../assets/solstice/css/villas-365-styles.css", ["_villas-365-fontawesome", "_villas-365-fancybox-styles", "_villas-365-chosen",  "_villas-365-datepicker-styles"], "1.0");
wp_enqueue_style('_villas-365-property-styles', plugin_dir_url( __FILE__ ) . "../../assets/solstice/css/villas-365-property.css", ["_villas-365-styles"], "1.0");
wp_enqueue_script('_villas-365-bootstrap', plugin_dir_url( __FILE__ ) . "../../assets/solstice/libs/bootstrap.js", ["jquery"], "4.1.1", true);
wp_enqueue_script('_villas-365-fancybox-scripts', "https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js", [], "3.5.7", false);
wp_enqueue_script('_villas-365-scripts', plugin_dir_url( __FILE__ ) . '../../assets/solstice/js/villas-365-scripts.js', ['jquery', '_villas-365-bootstrap', "_villas-365-fancybox-scripts", "_villas-365-datepicker-scripts"], "1.0", true);
wp_enqueue_script('_villas-365-property-scripts', plugin_dir_url( __FILE__ ) . '../../assets/solstice/js/villas-365-property.js', ['jquery', '_villas-365-scripts'], "1.0", true);

wp_localize_script('_villas-365-property-scripts', '_villas_365_wp_ajax', [
	'ajax_url' => admin_url('admin-ajax.php'),
	'nonce' => wp_create_nonce('_villas_365_calculate_booking')
]);

$googleMapsAPIKey = FALSE;
if(class_exists('Helpers365'))
{
	$googleMapsAPIKey = Helpers365::GetOption("_google_maps_api_key");
}

if($googleMapsAPIKey !== FALSE)
{
	wp_enqueue_script('_villas-365-googlemaps', "https://maps.googleapis.com/maps/api/js?v=3&key=" . $googleMapsAPIKey, [], "1.0", false);
	wp_add_inline_script('_villas-365-properties-list-scripts', $mapJavascriptString, 'before');
}

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides(true);
wp_add_inline_style('_villas-365-property-styles', $villas365CSSOverrides);

//Get the property details
$property = null;
$gallery = null;
$gallerySingle = false;
$policies = null;
$minimumNights = null;
$defaultSettings = null;
$reviews = true; //Show the reviews but this is just a 365 widget at the moment.
$localInformation = null;
$contactPageUrl = get_home_url() . "/contact";
$bookingPageUrl = get_home_url() . "/book-now";
$propertyPageURL = Helpers365::GetPropertyPageURL(get_option("villas-365_property_page_id"));

$googleMapsAPIKey = FALSE;
$showPropertyMap = false;

$propertySlug = Helpers365::GetPropertySlug();
if(!is_null($propertySlug))
{
	$property = API365::GetProperty($propertySlug);

	if(!is_null($property))
	{
		$gallery = API365::GetPropertyGallery($property->id);
		$policies = $property->policies;
		$localInformation = ((!is_null($property->local_information) && ($property->local_information != "")) ? $property->local_information : null);

		$defaultSettings = API365::GetDefaultSettings();

		if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("contactPageUrl", $property_atts) && !is_null($property_atts["contactPageUrl"]) && ($property_atts["contactPageUrl"] !== ""))
		{
			$contactPageUrl = $property_atts["contactPageUrl"];
		}

		if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("bookingPageUrl", $property_atts) && !is_null($property_atts["bookingPageUrl"]) && ($property_atts["bookingPageUrl"] !== ""))
		{
			$bookingPageUrl = $property_atts["bookingPageUrl"];
		}

		if(property_exists($property, "minimumStay") && !is_null($property->minimumStay) && ($property->minimumStay !== ""))
		{
			$minimumNights = $property->minimumStay;
		}

		if(class_exists('Helpers365'))
		{
			$googleMapsAPIKey = Helpers365::GetOption("_google_maps_api_key");
		}

		if (($googleMapsAPIKey !== FALSE) &&
			!is_null($property->local_area_action)
			&& (!is_null($property->local_area_action->lat) && (trim($property->local_area_action->lat) !== ""))
			&& (!is_null($property->local_area_action->lon) && (trim($property->local_area_action->lon) !== ""))
			&& (!is_null($property->local_area_action->address) && (trim($property->local_area_action->address) !== "")))
		{
			$showPropertyMap = true;
		}
	}
}

$enquireLabel = "Inquire";
if(!is_null($defaultSettings) && property_exists($defaultSettings, "use_enquire") && ($defaultSettings->use_enquire == 1))
{
	$enquireLabel = "Enquire";
}
?>
<?php if(!is_null($property)) : ?>
<div class="_villas-365-bootstrap _villas-365-property-container">
	<script>
		_365_owner = "<?php echo get_option("villas-365_owner_username_365_api") ?>";
		_365_owner_token = "<?php echo get_option("villas-365_owner_key_365_api") ?>";
		_365_language = "en";
		_365_property_id = "<?php echo $property->id ?>";
	</script>
	<?php if(!is_null($gallery) && (count($gallery) > 0)) : ?>
	<div class="_banner">
		<div id="property-banner" class="banner-carousel hidden-print carousel slide" data-ride="carousel" data-interval="false<?php /*echo (defined('DEBUG_365') && DEBUG_365 ? 'false' : '10000');*/ ?>">
			<?php if(!$gallerySingle) : ?>
			<!-- Indicators -->
			<ol class="carousel-indicators d-none d-lg-flex">
				<?php foreach($gallery as $key => $banner) : ?>
				<li data-target="#property-banner" data-slide-to="<?php echo $key; ?>" class="<?php echo (($key == 0) ? 'active' : ''); ?>"></li>
				<?php endforeach; ?>
			</ol>
			<?php endif; ?>

			<!-- Wrapper for slides -->
			<div class="carousel-inner">
				<?php foreach($gallery as $key => $banner) : ?>
				<div class="carousel-item <?php echo (($key == 0) ? 'active' : ''); ?>">
					<div class="banner-item-container" style="background-image:url('<?php echo esc_html($banner->link_banner); ?>')" data-fancybox="_property-images-fancybox" data-src="<?php echo esc_html($banner->link_banner); ?>">
						<img src="<?php echo esc_html($banner->link_banner); ?>" alt="" class="img-fluid d-lg-none">
					</div>
				</div>
				<?php
				if($gallerySingle)
				{
					break;
				}
				?>
				<?php endforeach; ?>
			</div>

			<?php if(!$gallerySingle) : ?>
			<div class="left carousel-control carousel-control-prev" role="button" data-slide="prev" data-target="#property-banner">
				<i class="fas fa-chevron-left prev-icon carousel-control-icon" aria-hidden="true"></i>
				<span class="sr-only">Previous</span>
			</div>
			<div class="right carousel-control carousel-control-next" role="button" data-slide="next" data-target="#property-banner">
				<i class="fas fa-chevron-right next-icon carousel-control-icon" aria-hidden="true"></i>
				<span class="sr-only">Next</span>
			</div>
			<?php endif; ?>
		</div>

		<div class="_villas-365-property-header">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-12 col-lg-6 col-xl-5">
						<div class="_villas-365-property-header-inner">
							<div class="_villas-365-property-header-inner-background">
								<div id="_villas-365-property-header-close-button"><i class="fas fa-times"></i></div>
								<div class="row">
									<div class="col">
										<h1 class="_villas-365-property-name"><?php echo esc_html($property->name); ?></h1>
									</div>
								</div>
								
								<?php if (!is_null($property->brief) && (trim($property->brief) !== "")) : ?>
								<div class="row">
									<div class="col">
										<div class="_villas-365-property-summary">
										<?php echo esc_html(Helpers365::StrLimit(Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]), 300)); ?>
										</div>
									</div>
								</div>
								<?php endif; ?>

								<div class="row mt-3">
									<div class="col">
										<a href="#booking" class="_villas-365-property-button btn btn-primary"><?php __v3te("Book Now"); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="_property-images-scrolling-container">
		<ul id="_property-images-scrolling-features">
			<?php foreach($gallery as $key => $banner) : ?>
				<li>
					<a class="property-images-link" target="_blank" href="<?php echo esc_html($banner->link_banner); ?>" data-image-key="<?php echo $key; ?>">
						<img class="img-responsive" src="<?php echo esc_html($banner->link_banner); ?>" alt="">
					</a>
					<div class="_property-image-overlay"></div>
				</li>
			<?php endforeach; ?>
		</ul>
		<a id="_property-images-scrolling-features-scroll-left" class="carousel-control carousel-control-prev" href="#">
			<div class="banner-control-background">
				<i class="fa fa-angle-left"></i>
			</div>
		</a>
		<a id="_property-images-scrolling-features-scroll-right" class="carousel-control carousel-control-next" href="#">
			<div class="banner-control-background">
				<i class="fa fa-angle-right"></i>
			</div>
		</a>
	</div>

	<?php elseif(!is_null($property)) : ?>
	<div id="property-image" class="margin-bottom-gutter" style="background-image:url('<?php echo esc_html($property->image_path) ?>');">
		<img src="<?php echo esc_html($property->image_path) ?>" alt="<?php echo (!is_null($property->name) && (trim($property->name) !== "") ? $property->name : ''); ?>" class="img-fluid d-md-none">
		
		<div class="_villas-365-property-header">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-12 col-lg-6 col-xl-4">
						<div class="_villas-365-property-header-inner">
							<div class="_villas-365-property-header-inner-background">
								<div class="row">
									<div class="col">
										<h1 class="_villas-365-property-name"><?php echo esc_html($property->name); ?></h1>
									</div>
								</div>
								
								<?php if (!is_null($property->brief) && (trim($property->brief) !== "")) : ?>
								<div class="row">
									<div class="col">
										<div class="_villas-365-property-summary">
										<?php echo esc_html(Helpers365::StrLimit(Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]), 300)); ?>
										</div>
									</div>
								</div>
								<?php endif; ?>

								<div class="row mt-3">
									<div class="col">
										<a href="#booking" class="_villas-365-property-button btn btn-primary"><?php __v3te("Book Now"); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<div id="_villas-365-property-floater-anchor"></div>
	<div id="_villas-365-property-floater" class="d-none d-lg-block" data-property-id="<?php echo esc_html($property->id) ?>">
		<div class="container">
			<div class="row justify-content-end">
				<div class="col-12 col-lg-4">
					<div class="_villas-365-property-floater-content">
						<div class="_villas-365-property-floater-header">
							<div class="row align-items-center">
								<div class="col-12 col-lg-6">
									<?php __v3te("We're here to help:"); ?>
								</div>
								<div class="col-12 col-lg-6">
									<a href="<?php echo esc_html($contactPageUrl); ?>" class="_villas-365-property-button _villas-365-property-button-alt btn btn-block btn-primary"><?php __v3te("Contact Us"); ?></a>
								</div>
							</div>
						</div>
						<div class="_villas-365-property-floater-body">
							<div class="row">
								<?php
								$defaultPerNight = __v3t("From", false) . " " . esc_html($property->rateValue) . "/" . __v3t(($property->rateLabel == "day" ? "night" : $property->rateLabel), false);
								$rateLabel = __v3t(($property->rateLabel == "day" ? "night" : $property->rateLabel), false);
								$rateLabelPlural = __v3tn($rateLabel, 2);
								?>
								<div id="_villas-365-property-floater-price-per-night"
									class="col-12"
									data-default-per-night="<?php echo $defaultPerNight ?>"
									data-rate-label="<?php echo esc_html($rateLabel) ?>"
									data-rate-label-plural="<?php echo esc_html($rateLabelPlural) ?>">
									<?php echo $defaultPerNight ?>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<hr>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<label><?php __v3te("Dates"); ?></label>
								</div>
							</div>
							<div class="row mb-3 _villas-365-date-range-container">
								<div class="col-6 pr-0">
									<div class="_villas-365-date-container">
										<label class="d-none" for="checkin"><?php __v3te("Arrival Date"); ?></label>
										<input type="date" name="checkin" class="form-control _villas-365-checkin _villas-365-date-control" value="<?php echo esc_html($searchParameters["checkin"]); ?>" placeholder="Check-in">
									</div>
								</div>
								<div class="col-6 pl-0">
									<div class="_villas-365-date-container">
										<label class="d-none" for="checkout"><?php __v3te("Departure Date"); ?></label>
										<input type="date" name="checkout" class="form-control _villas-365-checkout _villas-365-date-control" value="<?php echo esc_html($searchParameters["checkout"]); ?>" placeholder="Check-out">
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-12">
									<label for="adults"><?php __v3te("Adults"); ?></label>
									<select id="_villas-365-adults" name="adults" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php __v3te("Adults"); ?>" data-placeholder="<?php __v3te("Adults"); ?>">
										<option value=""></option>
										<?php
										for($counter = 1; $counter <= 20; $counter++) :
											$selected = "";
											if($searchParameters["adults"] == $counter)
											{
												$selected = " selected";
											}
										?>
										<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo $counter; ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-12">
									<label for="children"><?php __v3te("Children"); ?></label>
									<select id="_villas-365-children" name="children" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php __v3te("Children"); ?>" data-placeholder="<?php __v3te("Children"); ?>">
										<option value=""></option>
										<?php
										for($counter = 1; $counter <= 20; $counter++) :
											$selected = "";
											if($searchParameters["children"] == $counter)
											{
												$selected = " selected";
											}
										?>
										<option value="<?php echo $counter; ?>"<?php echo $selected ?>><?php echo $counter; ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div id="_villas-365-property-floater-total-row" class="row mb-3">
								<div id="_villas-365-property-floater-total-label" class="col-auto d-none"><?php __v3te("Total"); ?></div>
								<div id="_villas-365-property-floater-total" class="col"></div>
							</div>
							<div id="_villas-365-property-floater-price-details-row" class="row mb-3 d-none">
								<div class="col">
									<div class="row">
										<div class="col">
											<div id="_villas-365-property-floater-price-details-button" class="collapsed" data-rotate-class="rotate-180r">
												<span class="hide-on-show"><?php __v3te("Price details"); ?></span><span class="show-on-show"><?php __v3te("Hide details"); ?></span>
												<span class="button-icon"><i class="fas fa-chevron-down rotate"></i></span>
											</div>
										</div>
									</div>

									<div class="_villas-365-property-floater-price-details-container" style="display:none;" data-collapsed="true">
										<div class="row mb-2">
											<div id="_villas-365-property-floater-price-details-label-per-night" class="col _villas-365-property-floater-price-details-label col-auto"><?php __v3te("Rent"); ?></div>
											<div id="_villas-365-property-floater-price-details-per-night" class="_villas-365-property-floater-price-details col"></div>
										</div>
										<div class="row mb-2">
											<div id="_villas-365-property-floater-price-details-label-fees" class="col _villas-365-property-floater-price-details-label col-auto"><?php __v3te("Service fees"); ?></div>
											<div id="_villas-365-property-floater-price-details-fees" class="_villas-365-property-floater-price-details col"></div>
										</div>
										<div class="row">
											<div id="_villas-365-property-floater-price-details-label-tax" class="col _villas-365-property-floater-price-details-label col-auto"><?php __v3te("Tax"); ?></div>
											<div id="_villas-365-property-floater-price-details-tax" class="_villas-365-property-floater-price-details col"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6 pr-1">
									<a href="<?php echo esc_html($contactPageUrl); ?>" class="_villas-365-property-button _villas-365-property-button-inverted btn btn-block btn-primary"><?php __v3te($enquireLabel); ?></a>
								</div>
								<div class="col-6 pl-1">
									<a id="_villas-365-property-floater-book-now-button" href="<?php echo esc_html($bookingPageUrl); ?>" class="_villas-365-property-button btn btn-block btn-primary"><?php __v3te("Book Now"); ?></a>
								</div>
							</div>
							<?php if(!is_null($minimumNights)) : ?>
							<div id="_villas-365-property-floater-minimum-nights-container" class="row mt-3">
								<div class="col-12">
									There is a <span id="_villas-365-property-floater-minimum-nights" data-default-minimum-nights="<?php echo esc_html($minimumNights); ?>"><?php echo esc_html($minimumNights); ?></span> night minimum<span id="_villas-365-property-floater-minimum-nights-dates-text" style="display:none;">&nbsp;for these dates. Please <?php __v3te(strtolower($enquireLabel)); ?> or extend your stay.</span>
								</div>
							</div>
							<?php endif; ?>
							<div id="_datetime-hidden-fields"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="wrapper">
		<div class="container">
			<?php if (!is_null($property->name) && (trim($property->name) !== "")) : ?>
			<div class="row mt-3 d-lg-none">
				<div class="col text-center">
					<h1><?php echo esc_html($property->name); ?></h1>
				</div>
			</div>
			<?php endif; ?>

			<?php if((!is_null($property->brief) && (trim($property->brief) !== "")) || (!is_null($property->property_video) && (trim($property->property_video) !== ""))) : ?>
			<div class="row mt-3 mt-lg-5">
				<div class="col-12 col-lg-4">
					<h4 class="_villas-365-title _villas-365-property-description-title"><?php __v3te("Property Description"); ?></h4>
				</div>
				<div class="col-12 col-lg-4">
					<div class="_villas-365-property-rooms text-left text-lg-right">
						<?php if (!is_null($property->maxguest) && (trim($property->maxguest) !== "")) : ?>
						<div class="_villas-365-property-room">
							<i class="_villas-365-property-room-icon fas fa-user"></i> <?php echo esc_html($property->maxguest); ?>
						</div>
						<?php endif; ?>
						
						<?php if (!is_null($property->bedroom) && (trim($property->bedroom) !== "")) : ?>
						<div class="_villas-365-property-room">
							<i class="_villas-365-property-room-icon fas fa-bed"></i> <?php echo esc_html($property->bedroom); ?>
						</div>
						<?php endif; ?>

						<?php if (!is_null($property->bathroom) && (trim($property->bathroom) !== "")) : ?>
						<div class="_villas-365-property-room">
							<i class="_villas-365-property-room-icon fas fa-bath"></i> <?php echo esc_html($property->bathroom); ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="row mt-1">
				<?php if (!is_null($property->brief) && (trim($property->brief) !== "")) : ?>
				<div class="col-12 col-lg-4 mb-4 mb-lg-0">
					<div class="row">
						<div class="col">
							<div class="_read-more-outer" data-collapsed="true" data-initial-height="180">
								<div class="_read-more-inner">
									<?php echo str_replace(PHP_EOL, "<br>", esc_html(Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]))); ?>
								</div>
							</div>
							<div class="_read-more-more-button clearfix collapsed" data-rotate-class="rotate-90r">
								<span class="hide-on-show"><?php __v3te("More"); ?></span><span class="show-on-show"><?php __v3te("Less"); ?></span>
								<span class="button-icon"><i class="fas fa-chevron-right rotate"></i></span>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
				
				<div class="col-12 col-lg-4">
					<div class="_villas-365-property-image-virtual-tour">
						<?php if (!is_null($property->property_video) && (trim($property->property_video) !== "")) : ?>
						<a href="<?php echo esc_html($property->property_video); ?>" target="_blank" rel="noopener"><img src="<?php echo esc_html($property->image_path); ?>" alt="<?php echo (!is_null($property->name) && (trim($property->name) !== "") ? $property->name : ''); ?>"></a>
						<div class="_property-image-overlay">
							<div class="_property-image-overlay-content">
								<div class="_property-image-overlay-image">
									<img src="<?php echo plugins_url('../../assets/solstice/img/virtual-tour.svg', __FILE__); ?>" alt="">
								</div>
								<div class="_property-image-overlay-text"><?php __v3te("View Video"); ?></div>
							</div>
						</div>
						<?php else : ?>
						<img src="<?php echo esc_html($property->image_path); ?>" alt="<?php echo (!is_null($property->name) && (trim($property->name) !== "") ? $property->name : ''); ?>" class="img-fluid">
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="_villas-365-property-information mt-5">
		<div class="container">
			<div class="row mb-2">
				<div class="col-12 col-lg-8">
					<h4 class="_villas-365-title _villas-365-property-information-title"><?php __v3te("Property Information"); ?></h4>
				</div>
			</div>
			<div class="row mb-4">
				<div class="col-12 col-lg-8 _villas-365-property-information-controls">
					<?php if($showPropertyMap) : ?><span class="_villas-365-property-information-control active" data-information-item-id="_villas-365-property-map-container"><?php __v3te("Location"); ?></span><?php endif; ?>
					<?php if(isset($localInformation) && !is_null($localInformation)) : ?><span class="_villas-365-property-information-control <?php if(!$showPropertyMap) : ?>active<?php endif; ?>" data-information-item-id="_villas-365-property-local-information-container"><?php __v3te("Local Information"); ?></span><?php endif; ?>
					<span class="_villas-365-property-information-control <?php if(!$showPropertyMap && (!isset($localInformation) || is_null($localInformation))) : ?>active<?php endif; ?>" data-information-item-id="_villas-365-property-rates-table-container"><?php __v3te("Rates"); ?></span>
					<?php if(isset($reviews) && !is_null($reviews)) : ?><span class="_villas-365-property-information-control" data-information-item-id="_villas-365-property-reviews-container"><?php __v3te("Reviews"); ?></span><?php endif; ?>
					<?php if(isset($policies) && !is_null($policies)) : ?><span class="_villas-365-property-information-control" data-information-item-id="_villas-365-property-policies-container"><?php __v3te("Policies"); ?></span><?php endif; ?>
				</div>
			</div>
		</div>

		<div class="_villas-365-property-information-items">
			
			<?php if($showPropertyMap) : ?>
			<div id="_villas-365-property-map-container" class="container-fluid _villas-365-property-information-item active">
				<div class="row">
					<div class="col-12 col-lg px-0 _villas-365-property-information-item-content">
						<?php
						$propertyMapCoordinates = "var _coordinates = {
							'latitude': '" . esc_html($property->local_area_action->lat) . "',
							'longitude': '" . esc_html($property->local_area_action->lon) . "'
						};";

						$propertyMapStyles = 'var _mapStyles = [
							{
								"featureType": "poi.business",
								"stylers": [
									{
										"visibility": "off"
									}
								]
							},
							{
								"featureType": "poi.park",
								"elementType": "labels.text",
								"stylers": [
									{
										"visibility": "off"
									}
								]
							}
						];';
						wp_add_inline_script('_villas-365-property-scripts', $propertyMapCoordinates . PHP_EOL . $propertyMapStyles, "before");
						?>
						<div id="_villas-365-map-canvas" class="_villas-365-map-container"></div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if(isset($localInformation) && !is_null($localInformation)) : ?>
			<div id="_villas-365-property-local-information-container" class="container _villas-365-property-information-item py-gutter <?php if(!$showPropertyMap) : ?>active<?php endif; ?>">
				<div class="row">
					<div class="col-12 col-lg-8">
						<div class="_villas-365-property-local-information _villas-365-property-information-item-content">
							<div class="row">
								<div class="col">
									<div class="_villas-365-property-local-information">
										<h4 class="_villas-365-title _villas-365-property-local-information-title"><?php __v3te("Local Information") ?></h4>
										<p><?php echo html_entity_decode(str_replace(PHP_EOL, "<br>", $localInformation)); ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div id="_villas-365-property-rates-table-container" class="container _villas-365-property-information-item py-gutter <?php if(!$showPropertyMap && (!isset($localInformation) || is_null($localInformation))) : ?>active<?php endif; ?>">
				<div class="row">
					<div class="col-12 col-lg-8">
						<div class="_villas-365-property-rates-table _villas-365-property-information-item-content">
							<?php
							$ratesTableData = API365::GetRateTable($property->id);
							if(!is_null($ratesTableData)) : ?>
							<div class="row _villas-365-property-rates-table-header">
								<div class="col-6 col-lg-8">Rates</div>
								<div class="col-3 col-lg-2 text-center">Daily</div>
								<div class="col-3 col-lg-2 text-center">Weekly</div>
							</div>

							<?php
							$ratesCounter = 0;
							foreach($ratesTableData->seasons as $ratesTableItem) :
							?>
							<div class="row _villas-365-property-rates-table-item">
								<div class="col">
									<div class="_villas-365-property-rates-table-item-inner<?php echo ($ratesCounter % 2 ? ' _villas-365-property-rates-table-item-inner-alt' : ''); ?>">
										<div class="row align-items-center">
											<div class="col-6 col-lg-8"><?php echo esc_html($ratesTableItem->seasonName); ?></div>
											
											<div class="col-3 col-lg-2 text-center">
												<?php if(property_exists($ratesTableItem, "nightly")) : ?>
													<?php echo esc_html($ratesTableData->currencySymbol) . esc_html($ratesTableItem->nightly); ?>
												<?php else: ?>
													<?php __v3te("N/A"); ?>
												<?php endif; ?>
											</div>

											<div class="col-3 col-lg-2 text-center">
												<?php if(property_exists($ratesTableItem, "weekly")) : ?>
													<?php echo esc_html($ratesTableData->currencySymbol) . esc_html($ratesTableItem->weekly); ?>
												<?php else: ?>
													<?php __v3te("N/A"); ?>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
							$ratesCounter++;
							endforeach;
							else : ?>
							<div class="row _villas-365-property-rates-table-item">
								<div class="col">
									<?php __v3te("There was an error getting the rate information. Please check your API keys and if the issue is still present contact 365villas."); ?>
								</div>
							</div>
							<?php endif;?>
						</div>
					</div>
				</div>
			</div>

			<?php if(isset($reviews) && !is_null($reviews)) : ?>
			<div id="_villas-365-property-reviews-container" class="container _villas-365-property-information-item py-gutter">
				<div class="row">
					<div class="col-12 col-lg-8">
						<div class="_villas-365-property-reviews _villas-365-property-information-item-content">
							<div class="row">
								<div class="col">
									<div class="_villas-365-property-review">
										<script src="https://secure.365villas.com/widget/customer-review.js"></script>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if(isset($policies) && !is_null($policies)) : ?>
			<div id="_villas-365-property-policies-container" class="container _villas-365-property-information-item py-gutter">
				<div class="row">
					<div class="col-12 col-lg-8">
						<div class="_villas-365-property-policies _villas-365-property-information-item-content">
							<div class="row">
								<div class="col">
									<?php foreach($policies as $key => $policies) : ?>
									<div class="_villas-365-property-policy">
										<h4 class="_villas-365-title _villas-365-property-policy-title"><?php __v3te(ucwords(str_replace("_", " ", $key))) ?></h4>
										<p><?php echo html_entity_decode(str_replace(PHP_EOL, "<br>", $policies)); ?></p>
									</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if (!is_null($property->amenity)) : ?>
	<div class="_villas-365-property-amenities">
		<div class="container">
			<div class="row">
				<div class="col-12 col-lg-8">
					<h4 class="_villas-365-title"><?php __v3te("Amenities"); ?></h4>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-lg-8">
					<div class="row">
						<div class="col-12 col-lg-4 mb-3 mb-lg-0 _villas-365-property-amenity-switcher-column">
							<div class="_villas-365-property-amenity-switcher d-block d-lg-none">
								<div class="_villas-365-property-amenity-switcher-item-selected">
									<?php foreach (get_object_vars($property->amenity) as $key => $amenityLocation) :
										if($key === "extra")
										{
											continue;	
										}
									?>

									<span class="_villas-365-property-amenity-switcher-item-selected-icon"><i class="fas fa-chevron-down"></i></span>

									<?php if (!is_null($amenityLocation->amenity) && count($amenityLocation->amenity) > 0) : ?>
									<span class="_villas-365-property-amenity-switcher-item-selected-text"><?php echo esc_html(ucwords(str_replace("_", " ", strtolower($amenityLocation->label)))); ?></span>
									<?php
									break;
									endif; ?>
									<?php endforeach; ?>
								</div>
								<div class="_villas-365-property-amenity-switcher-items" style="display:none;">
									<?php foreach (get_object_vars($property->amenity) as $key => $amenityLocation) :
										if($key === "extra")
										{
											continue;	
										}
									?>

									<?php if (!is_null($amenityLocation->amenity) && count($amenityLocation->amenity) > 0) : ?>
									<div class="_villas-365-property-amenity-switcher-item" data-amenity-name="<?php echo esc_html($amenityLocation->label); ?>">
										<?php echo esc_html(ucwords(str_replace("_", " ", strtolower($amenityLocation->label)))); ?>
									</div>
									<?php endif; ?>

									<?php endforeach; ?>
								</div>
							</div>

							<div class="_villas-365-property-amenity-switcher-inline d-none d-lg-block">
								<div class="_villas-365-property-amenity-switcher-inline-items">
									<?php
									$isFirstAmenity = true;
									foreach (get_object_vars($property->amenity) as $key => $amenityLocation) :
										if($key === "extra")
										{
											continue;	
										}
									?>

									<?php if (!is_null($amenityLocation->amenity) && count($amenityLocation->amenity) > 0) : ?>
									<div class="_villas-365-property-amenity-switcher-inline-item<?php echo ($isFirstAmenity ? ' active' : ''); ?>" data-amenity-name="<?php echo esc_html($amenityLocation->label); ?>">
										<div class="_villas-365-property-amenity-switcher-inline-item-background"></div>
										<span><?php echo esc_html(ucwords(str_replace("_", " ", strtolower($amenityLocation->label)))); ?></span>
										<span><i class="fas fa-chevron-right float-right"></i></span>
									</div>
									<?php endif; ?>

									<?php
									$isFirstAmenity = false;
									endforeach;
									?>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-8 _villas-365-property-amenities-container">
							<?php
							$isFirstAmenity = true;
							foreach (get_object_vars($property->amenity) as $key => $amenityLocation) :
								if($key === "extra")
								{
									continue;	
								}
							?>

							<?php if (!is_null($amenityLocation->amenity) && count($amenityLocation->amenity) > 0) : ?>
							<div class="_villas-365-property-amenity-group<?php echo ($isFirstAmenity ? ' active' : ''); ?>" data-amenity-name="<?php echo esc_html($amenityLocation->label); ?>"<?php echo (!$isFirstAmenity ? ' style="display:none;"' : ''); ?>>
								<div class="row amenities">
									<div class="col-12">
										<?php foreach (array_chunk($amenityLocation->amenity, 2) as $amenityChunk) : ?>
										<div class="row">
											<?php
											foreach ($amenityChunk as $amenity) : ?>
											<div class="col-6 mb-3">
												<div class="_villas-365-property-amenity">
													<div class="_villas-365-property-amenity-icon <?php echo esc_html($amenity->class); ?> mb-1"></div>
													<div class="_villas-365-property-amenity-name">
														<?php echo esc_html($amenity->name); ?> <?php echo ($amenity->qty > 1 ? "x " . $amenity->qty : ""); ?>
													</div>
												</div>
											</div>		
											<?php endforeach; ?>
										</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
							<?php
							$isFirstAmenity = false;
							endif;
							?>

							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<div id="booking" class="wrapper py-5">
		<div class="container">
			<div class="row">
				<div class="col-12 col-lg-8">
					<h4 class="_villas-365-title"><?php __v3te("Availability"); ?></h4>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-md-8 col-lg-6">
					<div class="booking-container">
						<script src="https://secure.365villas.com/widget/bcalendar.js"></script>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	$relatedProperties = API365::GetPropertyRelated(["property_id" => $property->id, "size" => 4]);
	if(!is_null($relatedProperties) && ($relatedProperties["count"] > 0)) :
	?>
	<div class="wrapper _villas-365-property-related-container py-5">
		<div class="container">
			<div class="row">
				<div class="col">
					<h4 class="_villas-365-title"><?php __v3te("Similar Properties"); ?></h4>
				</div>
			</div>
			<div class="_villas-365-properties _villas-365-property-related">
				<div class="row">
					<div class="col-12 col-lg-8">
					<?php foreach(array_chunk($relatedProperties["properties"][0], 4) as $relatedPropertiesChunk): ?>
						<div class="row">
							<?php
							//Add some empty properties so the columns on the page add up correctly and are the correct width.
							while(count($relatedPropertiesChunk) < 3)
							{
								$relatedPropertiesChunk[] = null;
							}
							?>
							<?php foreach($relatedPropertiesChunk as $relatedProperty): ?>
							<div class="<?php echo (!is_null($relatedProperty) ? '_villas-365-property' : ''); ?> col-12 col-lg">
								<?php if(!is_null($relatedProperty)) : ?>
								<div class="_villas-365-property-inner">
									<div class="row">
										<?php if (!is_null($relatedProperty->image_path) && (trim($relatedProperty->image_path) !== "")) : ?>
										<div class="col-12">
											<div class="_villas-365-property-image">
												<a href="<?php echo esc_html($propertyPageURL); ?><?php echo esc_html($relatedProperty->slug) ?>" class="unstyled-link"><img src="<?php echo esc_html($relatedProperty->image_small); ?>" alt="" class="img-fluid"></a>
											</div>
										</div>
										<?php endif; ?>
										<div class="col-12">
											<div class="row">
												<div class="col">
													<?php if (!is_null($relatedProperty->name) && (trim($relatedProperty->name) !== "")) : ?>
													<h5 class="_villas-365-property-name">
														<a href="<?php echo esc_html($propertyPageURL); ?><?php echo esc_html($relatedProperty->slug) ?>"><?php echo esc_html($relatedProperty->name); ?></a>
													</h5>
													<?php endif; ?>
												</div>
											</div>

											<?php if ((!is_null($relatedProperty->bedroom) && (trim($relatedProperty->bedroom) !== "")) ||
												(!is_null($relatedProperty->maxguest) && (trim($relatedProperty->maxguest) !== "")) ||
												(!is_null($relatedProperty->bathroom) && (trim($relatedProperty->bathroom) !== ""))) : ?>
											<div class="row">
												<div class="col">
													<div class="_villas-365-property-rooms">
														<?php if (!is_null($relatedProperty->maxguest) && (trim($relatedProperty->maxguest) !== "")) : ?>
														<div class="_villas-365-property-room">
															<i class="_villas-365-property-room-icon fas fa-user"></i> <?php echo esc_html($relatedProperty->maxguest); ?>
														</div>
														<?php endif; ?>
														
														<?php if (!is_null($relatedProperty->bedroom) && (trim($relatedProperty->bedroom) !== "")) : ?>
														<div class="_villas-365-property-room">
															<i class="_villas-365-property-room-icon fas fa-bed"></i> <?php echo esc_html($relatedProperty->bedroom); ?>
														</div>
														<?php endif; ?>

														<?php if (!is_null($relatedProperty->bathroom) && (trim($relatedProperty->bathroom) !== "")) : ?>
														<div class="_villas-365-property-room">
															<i class="_villas-365-property-room-icon fas fa-bath"></i> <?php echo esc_html($relatedProperty->bathroom); ?>
														</div>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<?php endif; ?>
							</div>
							<?php endforeach; ?>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>