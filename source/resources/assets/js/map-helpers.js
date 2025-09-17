var _map = null;
var _infoWindow = null;

function createMarker(map, latitude, longitude, title, content, boundary, animation, icon)
{
	var marker = new google.maps.Marker(
	{
		position: new google.maps.LatLng(latitude, longitude),
		map: map
	});

	if(typeof(title) !== "undefined" && title !== null)
	{
		marker.setTitle(title);
	}

	if(typeof(animation) !== "undefined" && animation !== null)
	{
		marker.setAnimation(animation);
	}

	if(typeof(content) !== "undefined" && content != null)
	{
		marker['infowindow'] = new google.maps.InfoWindow(
		{
			content: content
		});

		let infoWindowVisibleListener = marker['infowindow'].addListener('visible', function()
		{
			if(typeof(_villas365MapInfoWindowOpened) === "function")
			{
				_villas365MapInfoWindowOpened();
			}

			// We only need this to run once so remove the listener after it has run.
			google.maps.event.removeListener(infoWindowVisibleListener);
		});

		google.maps.event.addListener(marker, 'click', function()
		{
			openMarkerInfoWindow(marker);
		});
	}

	//Add to the boundary
	if(typeof(boundary) !== "undefined" && boundary !== null)
	{
		boundary.extend(marker.position);
	}

	//Add the icon
	if(typeof(icon) !== "undefined" && icon !== null)
	{
		marker.setIcon(icon);
	}

	return marker;
}

function openMarkerInfoWindow(marker)
{
	if(typeof(_infoWindowExternalContainerId) !== "undefined" && _infoWindowExternalContainerId != null)
	{
		jQuery("#" + _infoWindowExternalContainerId).html(marker['infowindow'].getContent());
	}
	else
	{
		//Close the open infowindow.
		if (_infoWindow)
		{
			_infoWindow.close();
		}

		//Set the new infowindow as the current one.
		_infoWindow = marker['infowindow'];

		_infoWindow.open(_map, marker);
	}
}