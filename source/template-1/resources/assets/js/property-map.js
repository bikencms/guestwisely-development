var _mapOptions = null;
var _marker = null;
var _mapLoaded = false;

jQuery(document).ready(function () {
	if(!_mapLoaded && (jQuery("#_villas-365-map-canvas").length == 1))
	{
		setupMap();
		_mapLoaded = true;
	}
});

function setupMap()
{
	//Setup the Google Map options.
	_mapOptions = {
		center: new google.maps.LatLng(54.7990732,-3.9634416), //Hard code to over the UK.
		zoom: 5,
		streetViewControl: true
	};

	if(typeof(_mapStyles) !== "undefined" && _mapStyles != null)
	{
		_mapOptions["styles"] = _mapStyles;
	}

	//Create the map.
	_map = new google.maps.Map(document.getElementById("_villas-365-map-canvas"), _mapOptions);

	//Add the window resize event so the map resizes correctly.
	jQuery(window).on("resize", function(){
		google.maps.event.trigger(_map, 'resize');
	});

	//If we have coordinates saved against the property then show them on the map.
	if(_coordinates.latitude != null && _coordinates.longitude != null)
	{
		var latLng = new google.maps.LatLng(_coordinates.latitude, _coordinates.longitude);
		_map.setCenter(latLng);
		_map.setZoom(16);
		makeMarker(latLng);
	}
}

//Create or update the marker.
function makeMarker(latLng)
{
	if(_marker != null)
	{
		_marker.setPosition(latLng);
	}
	else
	{
		_marker = createMarker(_map, latLng.lat(), latLng.lng(), null, null, null, google.maps.Animation.DROP);
	}
}
