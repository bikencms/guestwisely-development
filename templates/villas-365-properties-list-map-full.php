<div class="_villas-365-properties-map _villas-365-properties-map-full">
	<div class="row">
		<div class="col-12">
			<div id="map-canvas" class="map-container"></div>
		</div>
	</div>
</div>

<?php
if(isset($showSaveButton) && ($showSaveButton === true))
{
	wp_enqueue_style('_villas-365-fontawesome', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-fontawesome.css", [], "5.9.0");
	wp_enqueue_style('_villas-365-property-save', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-property-save.css", ["_villas-365-fontawesome"], VILLAS_365_VERSION);
	wp_enqueue_script('_villas-365-property-save', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-property-save.js", ['jquery', '_villas-365-map-helpers'], VILLAS_365_VERSION, true);
}

$latLongs = [];
$mapPropertiesJSArray = "var _properties = new Array();" . PHP_EOL;

foreach($properties["properties"] as $property)
{
	if(!is_null($property->latitude) && (trim($property->latitude) !== "") && !is_null($property->longitude) && (trim($property->longitude) !== ""))
	{
		$content = '<div class="map-info-window _villas-365-property">' .
						'<div class="_villas-365-property-inner">' .
							'<div class="_villas-365-property-image">';
		
		if (!is_null($property->image_path) && (trim($property->image_path) !== ""))
		{
			$content .= '<div class="_villas-365-property-image-overlay"></div>' .
				'<a href="' . Helpers365::MakePropertyURL($propertyPageURL, $property->slug) . '" class="unstyled-link _villas-365-property-image-link" style="background-image:url(\\\'' . esc_html($property->image_path) . '\\\');"' . $villas365PropertiesOpenInNewTabsHTML . '>' .
					'<img src="' . esc_html($property->image_path) . '" alt="" class="img-fluid">' .
				'</a>';
		}

		if($showDiscountLabel && Helpers365::CheckObjectPropertiesExist($property, ["discounttexts"], false) && !is_null($property->discounttexts) && is_array($property->discounttexts) && (count($property->discounttexts) > 0))
		{
			$discountText = null;
			$discountNumber = count($property->discounttexts);
			if($discountNumber >= 2)
			{
				$discountText = $discountNumber . " " . __v3t("discounts available");
			}
			elseif($discountNumber == 1)
			{
				$discountText = $property->discounttexts[0];
			}

			$content .= '<div class="_villas-365-property-info-label _villas-365-property-discount">' .
							'<div class="_villas-365-property-discount-label">' . __v3t("Discount") . '</div><div class="_villas-365-property-discount-icon"><i class="fa fa-fw fa-info-circle"></i></div><div class="_villas-365-property-discount-text">' . esc_html($discountText) . '</div>' .
						'</div>';
		}
		elseif($showFeaturedLabel && Helpers365::CheckObjectPropertiesExist($property, ["isfeatured"], false) && !is_null($property->isfeatured) && ((is_bool($property->isfeatured) && ($property->isfeatured === true)) || ($property->isfeatured == 1)))
		{
			$content .= '<div class="_villas-365-property-info-label _villas-365-property-featured">' .
							__v3t("Featured") .
						'</div>';
		}

		if ((!is_null($property->bedroom) && (trim($property->bedroom) !== "")) ||
			(!is_null($property->maxguest) && (trim($property->maxguest) !== "")) ||
			(!is_null($property->bathroom) && (trim($property->bathroom) !== "")))
		{
			$content .=	'<div class="_villas-365-property-rooms">';

						if (!is_null($property->maxguest) && (trim($property->maxguest) !== ""))
						{
							$content .= '<div class="_villas-365-property-room">' .
								'<i class="_villas-365-property-room-icon fas fa-user"></i> ' . esc_html($property->maxguest) .
							'</div>';
						}
						
						if (!is_null($property->bedroom) && (trim($property->bedroom) !== ""))
						{
							$content .= '<div class="_villas-365-property-room">' .
								'<i class="_villas-365-property-room-icon fas fa-bed"></i> ' . esc_html($property->bedroom) .
							'</div>';
						}

						if (!is_null($property->bathroom) && (trim($property->bathroom) !== ""))
						{
							$content .= '<div class="_villas-365-property-room">' .
								'<i class="_villas-365-property-room-icon fas fa-bath"></i> ' . esc_html($property->bathroom) .
							'</div>';
						}

			$content .= '</div>';
		}

		if(isset($showSaveButton) && ($showSaveButton === true))
		{
			$content .= '<div class="_villas-365-property-save-button loading" data-property-id="' . $property->id . '">' .
				'<i class="fas fa-spinner fa-spin fa-fw _villas-365-property-save-button-icon _villas-365-property-save-spinner"></i>' .
				'<i class="far fa-heart fa-fw _villas-365-property-save-button-icon _villas-365-property-save-inactive"></i>' .
				'<i class="fas fa-heart fa-fw _villas-365-property-save-button-icon _villas-365-property-save-active"></i>' .
			'</div>';
		}

		$content .=		'</div>' .
						'<div class="_villas-365-property-inner-text">' .
							'<h6 class="_villas-365-property-name">' .
								'<a href="' . Helpers365::MakePropertyURL($propertyPageURL, $property->slug) . '"' . $villas365PropertiesOpenInNewTabsHTML . '>' .
									esc_html($property->name) .
								'</a>' .
							'</h6>' .
							'<div class="_villas-365-property-footer">';

		$rateValue = Helpers365::GetValueFromObjectProperties($property, ["rateValue"]);
		$rateLabel = Helpers365::GetValueFromObjectProperties($property, ["rateLabel"]);
		$rateToolTip = Helpers365::GetValueFromObjectProperties($property, ["rateToolTip"]);
		$bookingPrice = Helpers365::GetValueFromObjectProperties($property, ["bookingprice"]);
		if(!is_null($bookingPrice))
		{
			$perNightOrOriginalValue = esc_html($bookingPrice->grandTotalPerNightValue) . "/" . __v3t("night");
			if(Helpers365::GetValueFromObjectProperties($bookingPrice, ["grandTotalBeforeDiscountedValue"]) && ($bookingPrice->grandTotalValue != $bookingPrice->grandTotalBeforeDiscountedValue))
			{
				$perNightOrOriginalValue = "<s>" . esc_html($bookingPrice->grandTotalBeforeDiscountedValue) . "</s>";
			}

			$content .= '<div class="_villas-365-property-price" title="' . __v3t($rateToolTip) . '">' .
							'<span class="_villas-365-property-price-from-text">' . $perNightOrOriginalValue . '</span><br><span class="_villas-365-property-price-value _villas-365-text-primary">' . esc_html($bookingPrice->grandTotalValue) . " " . __v3t("total") . '</span>' .
						'</div>';
		}
		elseif(!is_null($rateValue) && (trim($rateValue) !== ""))
		{
			$content .= '<div class="_villas-365-property-price" title="' . __v3t($rateToolTip) . '">' .
							'<span class="_villas-365-property-price-from-text">' . __v3t("From") . '</span><br><span class="_villas-365-property-price-value _villas-365-text-primary">' . esc_html($rateValue) . '/' . __v3t(($rateLabel == "day" ? "night" : $rateLabel)) . '</span>' .
						'</div>';
		}

		$content .= 	'<div class="_villas-365-property-button-container text-right mt-2 mt-md-0">' .
							'<a href="' . Helpers365::MakePropertyURL($propertyPageURL, $property->slug) . '"' . $villas365PropertiesOpenInNewTabsHTML . '" class="_villas-365-property-button _villas-365-property-outline-button _villas-365-property-outline-button-inverted btn btn-outline-primary"' . $villas365PropertiesOpenInNewTabsHTML . '>' .
								__v3t("Details") .
							'</a>' .
						'</div>' .
					'</div>' .
				'</div>' .
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

wp_add_inline_script('_villas-365-properties-list-scripts', $mapJavascriptString, 'before');
?>