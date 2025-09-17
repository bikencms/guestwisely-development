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

$propertiesListHtmlHeaderTemplate = Helpers365::LocateTemplateFile("villas-365-properties-list-html-header.php");
if($propertiesListHtmlHeaderTemplate === FALSE)
{
	echo "<pre>List styles template could not be found.</pre>";
}
else
{
	include $propertiesListHtmlHeaderTemplate;
}

$propertiesPerRow = 3;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("perrow", $propertiesList_atts) && is_numeric($propertiesList_atts["perrow"]))
{
	$propertiesPerRow = $propertiesList_atts["perrow"];
}

$propertiesPerPage = 10;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("perpage", $propertiesList_atts) && is_numeric($propertiesList_atts["perpage"]) && (!isset($atts['maxnumperproperties'])))
{
	$propertiesPerPage = $propertiesList_atts["perpage"];
} else if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && (isset($atts['maxnumperproperties']))) {
	$propertiesPerPage = $atts['maxnumperproperties'];
}

$propertyPageURL = null;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("propertypageid", $propertiesList_atts) && is_numeric($propertiesList_atts["propertypageid"]))
{
	$propertyPageURL = Helpers365::GetPropertyPageURL($propertiesList_atts["propertypageid"]);
}

$propertiesListView = "list";
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("view", $propertiesList_atts) && !is_null($propertiesList_atts["view"]))
{
	$propertiesListView = $propertiesList_atts["view"];
}

$discountsOnly = false;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("discountsonly", $propertiesList_atts) && (($propertiesList_atts["discountsonly"] === 1) || ($propertiesList_atts["discountsonly"] === "true") || ($propertiesList_atts["discountsonly"] === true)))
{
	$discountsOnly = true;
}

$discountsPageURL = null;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("propertiesdiscountspageid", $propertiesList_atts) && (trim($propertiesList_atts["propertiesdiscountspageid"]) !== "") && is_numeric($propertiesList_atts["propertiesdiscountspageid"]))
{
	$propertiesDiscountsPageId = $propertiesList_atts["propertiesdiscountspageid"];

	if(!is_null($propertiesDiscountsPageId) && is_numeric($propertiesDiscountsPageId))
	{
		$discountsPageURL = get_page_link($propertiesDiscountsPageId);
	}
}

$showSaveButton = false;
$propertySaveButtonTemplate = null;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("showsavebutton", $propertiesList_atts) && (($propertiesList_atts["showsavebutton"] == 1) || ($propertiesList_atts["showsavebutton"] == "true") || ($propertiesList_atts["showsavebutton"] === true)))
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
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("showdiscountlabel", $propertiesList_atts) && (($propertiesList_atts["showdiscountlabel"] === "false") || ($propertiesList_atts["showdiscountlabel"] === false)))
{
	$showDiscountLabel = false;
} else if (isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("showdiscounttab", $propertiesList_atts) && (($propertiesList_atts["showdiscounttab"] === "false") || ($propertiesList_atts["showdiscounttab"] === false))) {
	$showDiscountLabel = false;
}

$showFeaturedLabel = true;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("showfeaturedlabel", $propertiesList_atts) && (($propertiesList_atts["showfeaturedlabel"] === "false") || ($propertiesList_atts["showfeaturedlabel"] === false)))
{
	$showFeaturedLabel = false;
} else if (isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("showfeaturetab", $propertiesList_atts) && (($propertiesList_atts["showfeaturetab"] === "false") || ($propertiesList_atts["showfeaturetab"] === false))) {
	$showFeaturedLabel = false;
}

$mapStyle = "default";
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("mapstyle", $propertiesList_atts) && !is_null($propertiesList_atts["mapstyle"]))
{
	$mapStyle = strtolower(trim($propertiesList_atts["mapstyle"]));
}

if(!isset($villas365PropertiesOpenInNewTabsHTML) || is_null($villas365PropertiesOpenInNewTabsHTML))
{
	$villas365PropertiesOpenInNewTabsHTML = Helpers365::GetOpenPropertiesInNewTabsHTML();
}

$detailLabel = "Reserve";
if ( get_option("villas-365_detail_label") && get_option("villas-365_detail_label") != "" ) {
	$detailLabel = get_option("villas-365_detail_label");
}

$defaultQuery = null;
if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("defaultquery", $propertiesList_atts) && !is_null($propertiesList_atts["defaultquery"]) && is_array($propertiesList_atts["defaultquery"]) && (count($propertiesList_atts["defaultquery"]) > 0))
{
	$defaultQuery = $propertiesList_atts["defaultquery"];
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

//Get the properties
$properties = null;
$properties = API365::SearchProperties([
	"limit" => $propertiesPerPage,
	"discountsonly" => $discountsOnly
], $searchParameters);

$page = 1;
if(isset($_GET) && is_array($_GET) && (count($_GET) > 0) && array_key_exists("ppage", $_GET) && is_numeric($_GET["ppage"]))
{
	$page = $_GET["ppage"];
} else {
	$page = API365::usePageSlashToPaging();
}

$searchParametersSearchResults = $searchParameters;
if(!is_null($searchParametersSearchResults["view"]) && ($searchParametersSearchResults["view"] !== ""))
{
	$propertiesListView = $searchParametersSearchResults["view"];
}
else
{
	$searchParametersSearchResults["view"] = $propertiesListView;
}

$totalPages = Helpers365::GetValueFromArray($properties, "totalPages");
if(!is_null($totalPages) && ($page > $totalPages)) :
?>

<div class="_villas-365-bootstrap _villas-365-properties">
	<div class="container">
		<div class="row">
			<div class="col">
				<?php __v3te("No properties found for your search. Try changing dates or filters to expand your search."); ?>
			</div>
		</div>
	</div>
</div>

<?php elseif(!is_null($properties) && ($properties["count"] > 0)) :
	$viewClass = '_villas-365-properties-grid';
	if($propertiesListView === 'list')
	{
		$viewClass = '_villas-365-properties-list';
	}
	elseif($propertiesListView === 'map')
	{
		$viewClass = '_villas-365-properties-map';
	}
?>
<div id="properties-list" class="_villas-365-bootstrap _villas-365-properties <?php echo $viewClass; ?>">
	<div class="_villas-365-properties-header">
		<?php
		$propertiesListHeaderTemplate = Helpers365::LocateTemplateFile("villas-365-properties-list-header.php");
		if($propertiesListHeaderTemplate === FALSE)
		{
			echo "<pre>List header template could not be found.</pre>";
		}
		else
		{
			include $propertiesListHeaderTemplate;
		}
		?>
	</div>

	<div class="container mt-4">
		<?php if($propertiesListView === "grid") : ?>
			<?php $propertiesListItemTemplate = Helpers365::LocateTemplateFile("villas-365-properties-list-item-grid.php"); ?>
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
				<div class="<?php echo (!is_null($property) ? '_villas-365-property' : ''); ?> col-md mb-3">
					<?php if(!is_null($property)) : ?>
						<?php
						if($propertiesListItemTemplate === FALSE)
						{
							echo "<pre>Template could not be found.</pre>";
						}
						else
						{
							include $propertiesListItemTemplate;
						}
						?>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		<?php elseif($propertiesListView === "map") : ?>
			<?php
			$googleMapsAPIKey = FALSE;
			if(class_exists('Helpers365'))
			{
				$googleMapsAPIKey = Helpers365::GetOption("_google_maps_api_key");
			}

			if($googleMapsAPIKey !== FALSE) :
				wp_enqueue_script('_villas-365-map-helpers', plugin_dir_url( __FILE__ ) . "../assets/js/villas-365-map-helpers.js", ["jquery"], VILLAS_365_VERSION, true);
				wp_enqueue_script('_villas-365-properties-map', plugin_dir_url( __FILE__ ) . "../assets/js/villas-365-properties-map.js", ["jquery", "_villas-365-map-helpers"], VILLAS_365_VERSION, true);
				wp_enqueue_script('_villas-365-googlemaps', "https://maps.googleapis.com/maps/api/js?v=3&key=" . $googleMapsAPIKey . "&callback=initMap", ["_villas-365-map-helpers", "_villas-365-properties-map"], "3.0", true);
				
				$propertiesListItemTemplate = FALSE;
				$mapViewTemplateName = "villas-365-properties-list-map.php";
				if(isset($mapStyle) && !is_null($mapStyle) && ($mapStyle !== "") && ($mapStyle === "full"))
				{
					$mapViewTemplateName = "villas-365-properties-list-map-full.php";
					$propertiesListItemTemplate = Helpers365::LocateTemplateFile($mapViewTemplateName);

					//If it can't find the full map template then just use the regular one.
					if($propertiesListItemTemplate === FALSE)
					{
						$mapViewTemplateName = "villas-365-properties-list-map.php";
						$propertiesListItemTemplate = Helpers365::LocateTemplateFile($mapViewTemplateName);
					}
				}
				else
				{
					$propertiesListItemTemplate = Helpers365::LocateTemplateFile($mapViewTemplateName);
				}

				if($propertiesListItemTemplate === FALSE)
				{
					echo "<pre>Template could not be found.</pre>";
				}
				else
				{
					include $propertiesListItemTemplate;
				}
			else :
			?>
			<div class="_villas-365-bootstrap _villas-365-error-container">
				<div class="container mt-gutter mb-gutter">
					<div class="row">
						<div class="col-12">
							Google Maps API key not set in 365villas settings.<br>
							Please add a valid Google Maps API key in the WordPress 365villas plugin settings.
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
		<?php else : ?>
			<?php $propertiesListItemTemplate = Helpers365::LocateTemplateFile("villas-365-properties-list-item-list.php"); ?>
			<?php foreach($properties["properties"] as $property): ?>
			<?php if(!is_null($property)) : ?>
			<div class="row">
				<div class="col mb-4">
					<div class="_villas-365-property">
						<?php
						if($propertiesListItemTemplate === FALSE)
						{
							echo "<pre>Template could not be found.</pre>";
						}
						else
						{
							include $propertiesListItemTemplate;
						}
						?>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<?php endforeach; ?>
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
			<?php
			if(isset($propertiesList_atts) && (count($propertiesList_atts) > 0) && array_key_exists("defaultquery", $propertiesList_atts) && !is_null($propertiesList_atts["defaultquery"]) && is_array($propertiesList_atts["defaultquery"]) && (count($propertiesList_atts["defaultquery"]) > 0)){
				$currentPageURLData = $searchParameters;
			}
			$propertiesListPaginatorTemplate = Helpers365::LocateTemplateFile("villas-365-properties-list-paginator.php");
			if($propertiesListPaginatorTemplate === FALSE)
			{
				echo "<pre>Template could not be found.</pre>";
			}
			else
			{
				include $propertiesListPaginatorTemplate;
			}
			?>
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
				<?php esc_html_e("No properties found.", "villas-365-website-td"); ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>