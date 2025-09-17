jQuery(document).ready(function () {
	let savedPropertiesObject = localStorage.getItem("villas_365_saved_properties");
	let savedProperties = [];
	if((savedPropertiesObject != null) && (savedPropertiesObject !== "[object Object]"))
	{
		savedProperties = JSON.parse(savedPropertiesObject);
	}

	let totalProperties = savedProperties.length;

	let savedPropertiesLink = jQuery("._villas-365-saved-properties");
	savedPropertiesLink.find("._villas-365-saved-properties-count").text(totalProperties);

	if(totalProperties == 1)
	{
		savedPropertiesLink.find("._villas-365-saved-properties-label-single").show();
		savedPropertiesLink.find("._villas-365-saved-properties-label-multiple").hide();
	}

	if(totalProperties > 0)
	{
		savedPropertiesLink.find("i").removeClass("far");
		savedPropertiesLink.find("i").addClass("fas");
	}
	else
	{
		savedPropertiesLink.find("i").removeClass("fas");
		savedPropertiesLink.find("i").addClass("far");
	}

	let savedPropertiesUrl = savedPropertiesLink.data("href");
	if(savedProperties.length != 0)
	{
		savedPropertiesUrl = savedPropertiesUrl + savedProperties.join(",");
	}
	else
	{
		savedPropertiesUrl = savedPropertiesUrl + "0";
	}
	savedPropertiesLink.attr("href", savedPropertiesUrl);
});