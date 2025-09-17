function addMatchHeight(containers, matchByRow)
{
	containers.matchHeight({
		byRow: matchByRow
	});
}

function removeMatchHeight(containers)
{
	containers.matchHeight({
		remove: true
	});
}

function _CookieRead(name)
{
	var nameEquals = name + "=";
	var cookiesArray = document.cookie.split(';');

	for (var i = 0; i < cookiesArray.length; i++)
	{
		var cookie = cookiesArray[i];
		
		while (cookie.charAt(0) == ' ')
		{
			cookie = cookie.substring(1, cookie.length);
		}

		if (cookie.indexOf(nameEquals) == 0)
		{
			return cookie.substring(nameEquals.length, cookie.length);
		}
	}

	return null;
}

function _CookiesCreate(name, value, days)
{
	var expires = "";

	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toGMTString();
	}

	document.cookie = name + "=" + value + expires + "; path=/";
}

function _CookieErase(name)
{
	_CookiesCreate(name, "", -1);
}

function _Villas365LazyLoadImages(element)
{
	let currentImageElement = jQuery(element);
	let src = currentImageElement.data("srcVillas");
	let attribute = currentImageElement.data("srcVillasAttribute");

	if(attribute == "src")
	{
		currentImageElement.attr("src", src);
	}
	else if(attribute == "background-image")
	{
		currentImageElement.css("background-image", "url('" + src + "')");
	}

	currentImageElement.removeClass("_villas-365-lazy-load-image");
}