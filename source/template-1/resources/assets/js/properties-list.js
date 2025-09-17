var _mapOptions = null;
var _boundary = null;

jQuery(document).ready(function()
{
	if(typeof(google) !== "undefined")
	{
		_boundary = new google.maps.LatLngBounds();
		_mapOptions = {
			center: new google.maps.LatLng(_mapCenterLatitude, _mapCenterLongitude),
			zoom: 11
		};

		if(typeof(_mapStyles) !== "undefined" && _mapStyles != null)
		{
			_mapOptions["styles"] = _mapStyles;
		}

		_map = new google.maps.Map(document.getElementById("map-canvas"), _mapOptions);

		var totalProperties = _properties.length;

		for (var count = 0; count < totalProperties; count++)
		{
			if(_properties[count].latitude !== null && _properties[count].longitude !== null)
			{
				createMarker(_map, _properties[count].latitude, _properties[count].longitude, _properties[count].title, _properties[count].content, _boundary);
			}
		}

		_map.fitBounds(_boundary);
	}
});
