<div class="_villas-365-properties-map">
	<div class="row">
		<div class="col-12 col-lg-7 order-last order-lg-first _villas-365-map-properties-container">
			<?php $propertiesListMapItemTemplate = Helpers365::LocateTemplateFile("villas-365-properties-list-item-map.php"); ?>
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
				<?php if(!is_null($property)) : ?>
				<div class="_villas-365-property col-12 mt-2">
					<?php
					if($propertiesListMapItemTemplate === FALSE)
					{
						echo "<pre>Template could not be found.</pre>";
					}
					else
					{
						include $propertiesListMapItemTemplate;
					}
					?>
				</div>
				<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="col-12 col-lg-5 order-first order-lg-last mt-3">
			<div id="map-canvas" class="map-container"></div>
		</div>
	</div>
</div>

<?php
$latLongs = [];
$mapPropertiesJSArray = "var _properties = new Array();" . PHP_EOL;

foreach($properties["properties"] as $property)
{
	if(!is_null($property->latitude) && (trim($property->latitude) !== "") && !is_null($property->longitude) && (trim($property->longitude) !== ""))
	{
		$propertyName = Helpers365::Get365TranslationValue($property, ["name"], ["languages", "name"]);

		$content = '<div class="map-info-window _villas-365-property">' .
						'<div class="_villas-365-property-image">';
		
		$content .= '<a href="' . Helpers365::MakePropertyURL($propertyPageURL, $property->slug) . '"' . $villas365PropertiesOpenInNewTabsHTML . '>' .
						'<img src="' . $property->image_small . '" alt="" class="img-fluid">' .
					'</a>';

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

		$content .=		'</div>' .
						'<div class="_villas-365-property-name text-center mt-2">' .
							'<a href="' . Helpers365::MakePropertyURL($propertyPageURL, $property->slug) . '"' . $villas365PropertiesOpenInNewTabsHTML . '>' .
								'<strong>' . esc_html($propertyName) . '</strong>' .
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

		$content .= '<div class="_villas-365-property-button-container mt-2">' .
						'<a href="' . Helpers365::MakePropertyURL($propertyPageURL, $property->slug) . '" class="_villas-365-property-button btn btn-block btn-primary"' . $villas365PropertiesOpenInNewTabsHTML . '>' .
							'Details' .
						'</a>' .
					'</div>' .
				'</div>';

		$mapPropertiesJSArray .= "_properties.push({";
		$mapPropertiesJSArray .= "'property_id': " . $property->id . ",";
		$mapPropertiesJSArray .= "'latitude': " . $property->latitude . ",";
		$mapPropertiesJSArray .= "'longitude': " .  $property->longitude . ",";
		$mapPropertiesJSArray .= "'title': '" . esc_html($propertyName) . "',";
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