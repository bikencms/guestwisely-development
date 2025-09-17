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

wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../../assets/template-1/css/villas-365-styles.css", [], "1.0");
wp_enqueue_style('_villas-365-properties-list', plugin_dir_url( __FILE__ ) . "../../assets/template-1/css/villas-365-properties-list.css", ["_villas-365-styles"], "1.0");
wp_enqueue_script('_villas-365-scripts', plugin_dir_url( __FILE__ ) . '../../assets/template-1/js/villas-365-scripts.js', ['jquery', '_villas-365-googlemaps'], "1.0", true);
wp_enqueue_script('_villas-365-properties-list-scripts', plugin_dir_url( __FILE__ ) . '../../assets/template-1/js/villas-365-properties-list.js', ['_villas-365-scripts'], "1.0", true);

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides(true);
wp_add_inline_style('_villas-365-properties-list', $villas365CSSOverrides);

$propertiesPerRow = 3;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("perrow", $propertiesList_atts) && is_numeric($propertiesList_atts["perrow"]))
{
	$propertiesPerRow = $propertiesList_atts["perrow"];
}

$propertiesPerPage = 10;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("perpage", $propertiesList_atts) && is_numeric($propertiesList_atts["perpage"]))
{
	$propertiesPerPage = $propertiesList_atts["perpage"];
}

$propertyPageURL = null;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("propertypageid", $propertiesList_atts) && is_numeric($propertiesList_atts["propertypageid"]))
{
	$propertyPageURL = Helpers365::GetPropertyPageURL($propertiesList_atts["propertypageid"]);
}

//Get the properties
$properties = null;
$searchParameters = null;
if(isset($_GET) && is_array($_GET) && ((array_key_exists("ppage", $_GET) && (count($_GET) > 0)) || (!array_key_exists("ppage", $_GET) && (count($_GET) > 0))))
{
	$searchParameters = $_GET;
}

$properties = API365::SearchProperties([
	"limit" => $propertiesPerPage
], $searchParameters);

$page = 1;
if(isset($_GET) && is_array($_GET) && (count($_GET) > 0) && array_key_exists("ppage", $_GET) && is_numeric($_GET["ppage"]))
{
	$page = $_GET["ppage"];
} else {
	$page = API365::usePageSlashToPaging();
}

$searchParametersSearchResults = API365::GetSearchParameters();
if(isset($_GET) && is_array($_GET))
{
	$searchParametersSearchResults = API365::GetSearchParameters($_GET);
}

$searchPageURLSearchResults = null;
if(isset($search_atts) && (count($search_atts) > 0) && array_key_exists("searchpageid", $search_atts) && is_numeric($search_atts["searchpageid"]))
{
	$searchPageURLSearchResults = get_page_link($search_atts["searchpageid"]);
}

if($page > $properties["totalPages"]) :
?>

<div class="_villas-365-bootstrap _villas-365-properties">
	<div class="container">
		<div class="row">
			<div class="col">
				Page does not exist.
			</div>
		</div>
	</div>
</div>

<?php Helpers365::Abort404(); ?>

<?php elseif(!is_null($properties) && ($properties["count"] > 0)) : ?>
<?php
$googleMapsAPIKey = FALSE;
$viewClass = '_villas-365-properties-grid';
if($searchParametersSearchResults["view"] === 'list')
{
	$viewClass = '_villas-365-properties-list';
}
elseif($searchParametersSearchResults["view"] === 'map')
{
	$viewClass = '_villas-365-properties-map';

	if(class_exists('Helpers365'))
	{
		$googleMapsAPIKey = Helpers365::GetOption("_google_maps_api_key");
	}

	if($googleMapsAPIKey !== FALSE)
	{
		$latLongs = [];
		$mapPropertiesJSArray = "var _properties = new Array();" . PHP_EOL;

		foreach($properties["properties"] as $property)
		{
			if(!is_null($property->latitude) && (trim($property->latitude) !== "") && !is_null($property->longitude) && (trim($property->longitude) !== ""))
			{
				$content = '<div class="map-info-window">' .
								'<div class="_villas-365-property-image">';
				
				$content .= '<a href="' . esc_html($propertyPageURL) . esc_html($property->slug) . '">' .
								'<img src="' . $property->image_small . '" alt="" class="img-fluid">' .
							'</a>';

				$content .=		'</div>' .
								'<div class="_villas-365-property-name text-center mt-2">' .
									'<a href="' . esc_html($propertyPageURL) . esc_html($property->slug) . '">' .
										'<strong>' . esc_html($property->name) . '</strong>' .
									'</a>' .
								'</div>';
				
				if ((!is_null($property->bedroom) && (trim($property->bedroom) !== "")) ||
					(!is_null($property->maxguest) && (trim($property->maxguest) !== "")) ||
					(!is_null($property->bathroom) && (trim($property->bathroom) !== "")))
				{
					$content .=	'<div class="_villas-365-property-rooms text-center mt-2">';

								if (!is_null($property->maxguest) && (trim($property->maxguest) !== ""))
								{
									$content .= '<div class="_villas-365-property-room">' .
										'<i class="_villas-365-property-room-icon fas fa-user"></i>' . esc_html($property->maxguest) .
									'</div>';
								}
								
								if (!is_null($property->bedroom) && (trim($property->bedroom) !== ""))
								{
									$content .= '<div class="_villas-365-property-room">' .
										'<i class="_villas-365-property-room-icon fas fa-bed"></i>' . esc_html($property->bedroom) .
									'</div>';
								}

								if (!is_null($property->bathroom) && (trim($property->bathroom) !== ""))
								{
									$content .= '<div class="_villas-365-property-room">' .
										'<i class="_villas-365-property-room-icon fas fa-bath"></i>' . esc_html($property->bathroom) .
									'</div>';
								}

					$content .= '</div>';
				}

				$content .= '<div class="_villas-365-property-button-container mt-2">' .
								'<a href="' . esc_html($propertyPageURL) . esc_html($property->slug) . '" class="_villas-365-property-button btn btn-block btn-primary">' .
									'Details' .
								'</a>' .
							'</div>' .
						'</div>';

				$mapPropertiesJSArray .= "_properties.push({";
				$mapPropertiesJSArray .= "'property_id': " . $property->id . ",";
				$mapPropertiesJSArray .= "'latitude': " . $property->latitude . ",";
				$mapPropertiesJSArray .= "'longitude': " .  $property->longitude . ",";
				$mapPropertiesJSArray .= "'title': '" . esc_html($property->name) . "',";
				$mapPropertiesJSArray .= "'content': '" . $content . "'";
				$mapPropertiesJSArray .= "});" . PHP_EOL;

				$latLongs[] = array($property->latitude, $property->longitude);
			}
		}

		$mapCenter = Helpers365::GetCenterFromDegrees($latLongs);

		$mapJavascriptString = $mapPropertiesJSArray;
		$mapJavascriptString .= 'var _mapCenterLatitude = "' . $mapCenter[0] . '";';
		$mapJavascriptString .= 'var _mapCenterLongitude = "' . $mapCenter[1] . '";';

		$mapJavascriptString .= 'var _mapStyles = [
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

		wp_enqueue_script('_villas-365-googlemaps', "https://maps.googleapis.com/maps/api/js?v=3&key=" . $googleMapsAPIKey, [], "1.0", false);
		wp_add_inline_script('_villas-365-properties-list-scripts', $mapJavascriptString, 'before');
	}
}
?>
<div class="_villas-365-bootstrap _villas-365-properties <?php echo $viewClass; ?>">
	<div class="container">
		<div class="row">
			<div class="col">
				<h2 class="_villas-365-properties-title"><?php echo esc_html($properties["totalProperties"]); ?> <?php __v3te("Available"); ?></h2>

				<div class="_villas-365-properties-title-separator"></div>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div class="_villas-365-properties-view-controls">
					<span class="_villas-365-properties-view-control _villas-365-properties-view-control-title"><?php __v3te("View"); ?></span>
					<?php
					$newViewData = $searchParametersSearchResults;
					unset($newViewData["view"]);
					?>
					<a class="_villas-365-properties-view-control _villas-365-properties-view-button<?php echo ($searchParametersSearchResults["view"] === 'map' ? ' active' : ''); ?>" href="<?php echo ($searchPageURLSearchResults . '?' . http_build_query($newViewData) . '&view=map'); ?>"><i class="fas fa-map-marker-alt"></i><span class="sr-only">Map</span></a>
					<a class="_villas-365-properties-view-control _villas-365-properties-view-button<?php echo ($searchParametersSearchResults["view"] === 'grid' ? ' active' : ''); ?>" href="<?php echo ($searchPageURLSearchResults . '?' . http_build_query($newViewData) . '&view=grid'); ?>"><i class="fas fa-th"></i><span class="sr-only">Grid</span></a>
					<a class="_villas-365-properties-view-control _villas-365-properties-view-button<?php echo ($searchParametersSearchResults["view"] === 'list' ? ' active' : ''); ?>" href="<?php echo ($searchPageURLSearchResults . '?' . http_build_query($newViewData) . '&view=list'); ?>"><i class="fas fa-list"></i><span class="sr-only">List</span></a>
				</div>
			</div>
		</div>

		

		<?php if($searchParametersSearchResults["view"] === 'map') : ?>
		<div class="row">
			<div class="col-12 col-lg-7 order-last order-lg-first _villas-365-map-properties-container">
		<?php endif; ?>

				<?php foreach(array_chunk($properties["properties"], $propertiesPerRow) as $propertiesChunk): ?>
				<div class="row">
					<?php
					//Add some empty properties so the columns on the page add up correctly and are the correct width.
					while(count($propertiesChunk) < $propertiesPerRow)
					{
						$propertiesChunk[] = null;
					}
					?>
					<?php foreach($propertiesChunk as $property): ?>
					<div class="<?php echo (!is_null($property) ? '_villas-365-property' : ''); ?> <?php echo ($searchParametersSearchResults["view"] === 'grid' ? 'col-12 col-lg mt-3' : 'col-12 mt-2'); ?>">
						<?php if(!is_null($property)) : ?>
						<div class="_villas-365-property-inner">
							<div class="row<?php echo ($searchParametersSearchResults["view"] === 'grid' ? ' h-100' : ''); ?>">
								<?php if($searchParametersSearchResults["view"] === 'grid') : ?>
								<div class="col-12">
									<div class="row">
								<?php endif; ?>


								<?php if (!is_null($property->image_path) && (trim($property->image_path) !== "")) : ?>
								<div class="<?php echo ($searchParametersSearchResults["view"] === 'grid' ? 'col-12 mb-2' : 'col-12 col-lg-6 mb-2 mb-lg-0'); ?>">
									<div class="_villas-365-property-image">
										<a href="<?php echo esc_html($propertyPageURL); ?><?php echo esc_html($property->slug) ?>" class="unstyled-link"<?php echo ($searchParametersSearchResults["view"] !== 'list') ? ' style="background-image:url(\'' . esc_html($property->image_path) . '\');"' : '' ?>>
											<img src="<?php echo esc_html($property->image_path); ?>" alt="" class="img-fluid<?php echo ($searchParametersSearchResults["view"] !== 'list') ? ' d-block d-lg-none' : ''; ?>">
										</a>
									</div>
								</div>
								<?php endif; ?>
								<div class="<?php echo ($searchParametersSearchResults["view"] === 'grid' ? 'col-12' : 'col-12 col-lg'); ?>">
									<div class="_villas-365-property-inner-text">
										<div class="row h-100">
											<div class="col-12">
												<div class="row">
													<?php if (!is_null($property->name) && (trim($property->name) !== "")) : ?>
													<div class="col-12">
														<<?php echo ($searchParametersSearchResults["view"] === 'list' ? 'h4' : 'h5'); ?> class="_villas-365-property-name">
															<a href="<?php echo esc_html($propertyPageURL); ?><?php echo esc_html($property->slug) ?>"><?php echo esc_html($property->name); ?></a>
														</<?php echo ($searchParametersSearchResults["view"] === 'list' ? 'h4' : 'h5'); ?>>
													</div>
													<?php endif; ?>

													<?php if ((!is_null($property->bedroom) && (trim($property->bedroom) !== "")) ||
													(!is_null($property->maxguest) && (trim($property->maxguest) !== "")) ||
													(!is_null($property->bathroom) && (trim($property->bathroom) !== ""))) : ?>
													<div class="col-12">
														<div class="_villas-365-property-rooms">
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
													<?php endif; ?>

													<?php if (!is_null($property->brief) && (trim($property->brief) !== "")) : ?>
													<div class="col-12">
														<div class="_villas-365-property-summary">
															<?php $propertyBrief = Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]); ?>
															<?php if($searchParametersSearchResults["view"] === 'map') : ?>
															<?php echo esc_html(Helpers365::StrLimit($propertyBrief, 100)); ?>
															<?php elseif($searchParametersSearchResults["view"] === 'grid') : ?>
															<?php echo esc_html(Helpers365::StrLimit($propertyBrief, 250)); ?>
															<?php else : ?>
															<?php echo esc_html(Helpers365::StrLimit($propertyBrief, 375)); ?>
															<?php endif; ?>
														</div>
													</div>
													<?php endif; ?>
												</div>
											</div>

											<?php if(($searchParametersSearchResults["view"] !== 'grid') && !is_null($property->rateValue) && (trim($property->rateValue) !== "")) : ?>
											<div class="w-100"></div>
											<div class="col-12 align-self-end">
												<div class="_villas-365-property-price" title="<?php echo __v3te($property->rateToolTip); ?>">
													<?php echo __v3te("From"); ?> <?php echo esc_html($property->rateValue); ?>/<?php echo __v3te(($property->rateLabel == "day" ? "night" : $property->rateLabel)); ?>
												</div>
											</div>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<?php if($searchParametersSearchResults["view"] === 'grid') : ?>
									</div>
								</div>

								<?php if(!is_null($property->rateValue) && (trim($property->rateValue) !== "")) : ?>
								<div class="w-100"></div>
								<div class="col-12 align-self-end">
									<div class="_villas-365-property-price" title="<?php echo __v3te($property->rateToolTip); ?>">
										<?php echo __v3te("From"); ?> <?php echo esc_html($property->rateValue); ?>/<?php echo __v3te(($property->rateLabel == "day" ? "night" : $property->rateLabel)); ?>
									</div>
								</div>
								<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
		
		<?php if($searchParametersSearchResults["view"] === 'map') : ?>
			</div>
			<div class="col-12 col-lg-5 order-first order-lg-last mt-3">
				<div id="map-canvas" class="map-container"></div>
			</div>
		</div>
		<?php endif; ?>

		<?php if($properties["totalPages"] > 1) :
			$currentPageURLBase = null;
			$currentPageURLData = null;
			$currentPageNumber = 1;
			$currentPage = get_post();
			if(!is_null($currentPage))
			{
				$currentPageURLBase = get_page_link($currentPage->id);

				if(isset($_GET) && is_array($_GET) && (count($_GET) > 0))
				{
					$currentPageURLData = $_GET;
					if(array_key_exists("ppage", $currentPageURLData))
					{
						$currentPageNumber = $currentPageURLData["ppage"];
						unset($currentPageURLData["ppage"]);
					} else {
						$currentPageNumber = API365::usePageSlashToPaging();
					}
				} else {
					$currentPageNumber = API365::usePageSlashToPaging();
				}
			}

			if(!is_null($currentPageURLBase)) :
		?>
			<div class="row mt-5">
				<div class="col-12<?php echo ($searchParametersSearchResults["view"] === 'map' ? ' col-lg-7' : '') ?>">
					<nav class="_villas-365-navigation" aria-label="Property navigation">
						<div class="row justify-content-between">
							<div class="col-auto">
								<?php if($currentPageNumber != 1) : ?>
								<a class="_villas-365-navigation-link" href="<?php $currentPageURLData["ppage"] = $currentPageNumber; echo $currentPageURLBase . "?" . http_build_query($currentPageURLData); ?>" aria-label="Previous">
									<span aria-hidden="true">&laquo;</span>
									<span><?php __v3te("Prev"); ?></span>
								</a>
								<?php endif; ?>
							</div>

							<div class="col text-center">
								<span class="d-none d-md-inline-block"><?php __v3te("Displaying") ?></span>
								<?php
								$maxPropertiesOnPage = $currentPageNumber * $properties["perPage"];
								if($maxPropertiesOnPage > $properties["totalProperties"])
								{
									$maxPropertiesOnPage = $properties["totalProperties"];
								}
								?>
								<span><?php echo (($currentPageNumber * $properties["perPage"]) - ($properties["perPage"] - 1)); ?> - <?php echo $maxPropertiesOnPage; ?> <?php __v3te("of") ?> <?php echo $properties["totalProperties"]; ?></span>
							</div>

							<div class="col-auto text-right">
								<?php if($currentPageNumber != $properties["totalPages"]) : ?>
								<a class="_villas-365-navigation-link" href="<?php $currentPageURLData["ppage"] = ($currentPageNumber + 1); echo $currentPageURLBase . "?" . http_build_query($currentPageURLData); ?>" aria-label="Next">
									<span><?php __v3te("Next"); ?></span>
									<span aria-hidden="true">&raquo;</span>
								</a>
								<?php endif; ?>
							</div>
						</div>
					</nav>
				</div>
			</div>
		<?php
			endif;
		endif;
		?>
	</div>
</div>
<?php else : ?>
<div class="_villas-365-bootstrap _villas-365-properties">
	<div class="container">
		<div class="row">
			<div class="col">
				No properties found.
			</div>
		</div>
	</div>
</div>
<?php endif; ?>