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

		google.maps.event.addListener(marker, 'click', function()
		{
			if(typeof(_infoWindowExternalContainerId) !== "undefined" && _infoWindowExternalContainerId != null)
			{
				jQuery("#" + _infoWindowExternalContainerId).html(this['infowindow'].getContent());
			}
			else
			{
				//Close the open infowindow.
				if (_infoWindow)
				{
					_infoWindow.close();
				}

				//Set the new infowindow as the current one.
				_infoWindow = this['infowindow'];

				this['infowindow'].open(map, this);
			}
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
