jQuery(document).ready(function () {
	//Check all properties on load.
	jQuery("._villas-365-property-save-button").each(function() {
		let saveButton = jQuery(this);
		let propertyId = saveButton.data("propertyId");

		saveButton.removeClass("loading");
		if(_villas365PropertySaveHasProperty(propertyId))
		{
			saveButton.addClass("active");
		}
		else
		{
			saveButton.removeClass("active");
		}

		let hasClickEvent = saveButton.data("hasClickEvent");
		if(hasClickEvent !== true)
		{
			saveButton.on("click", function() {
				let saveButton = jQuery(this);
				let propertyId = saveButton.data("propertyId");
				_villas365PropertySaveToggleProperty(saveButton, propertyId);
			});

			saveButton.data("hasClickEvent", true);
		}
	});
});

function _villas365PropertySaveGetSavedProperties()
{
	let savedPropertiesObject = localStorage.getItem("villas_365_saved_properties");
	let savedProperties = [];
	if((savedPropertiesObject != null) && (savedPropertiesObject !== "[object Object]"))
	{
		savedProperties = JSON.parse(savedPropertiesObject);
	}

	return savedProperties;
}

function _villas365PropertySaveToggleProperty(saveButton, propertyId)
{
	let propertyIds = _villas365PropertySaveGetSavedProperties();

	let propertyIdIndex = propertyIds.indexOf(propertyId);
	if(propertyIdIndex === -1)
	{
		propertyIds.push(propertyId);
		saveButton.addClass("active");
	}
	else
	{
		propertyIds.splice(propertyIdIndex, 1);
		saveButton.removeClass("active");
	}

	localStorage.setItem("villas_365_saved_properties", JSON.stringify(propertyIds));

	let savedPropertiesLink = jQuery("._villas-365-saved-properties");
	savedPropertiesLink.find("._villas-365-saved-properties-count").text(propertyIds.length);
	
	if(propertyIds.length == 1)
	{
		savedPropertiesLink.find("._villas-365-saved-properties-label-single").show();
		savedPropertiesLink.find("._villas-365-saved-properties-label-multiple").hide();
	}
	else
	{
		savedPropertiesLink.find("._villas-365-saved-properties-label-single").hide();
		savedPropertiesLink.find("._villas-365-saved-properties-label-multiple").show();
	}

	if(propertyIds.length > 0)
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
	if(propertyIds.length != 0)
	{
		savedPropertiesUrl = savedPropertiesUrl + propertyIds.join(",");
	}
	else
	{
		savedPropertiesUrl = savedPropertiesUrl + "0";
	}

	savedPropertiesLink.attr("href", savedPropertiesUrl);
}

function _villas365PropertySaveHasProperty(propertyId)
{
	let propertyIds = _villas365PropertySaveGetSavedProperties();

	let propertyIdIndex = propertyIds.indexOf(propertyId);
	if(propertyIdIndex !== -1)
	{
		return true;
	}

	return false;
}

function _villas365PropertySaveTotalSaved()
{
	let propertyIds = _villas365PropertySaveGetSavedProperties();
	return propertyIds.length;
}

function _villas365MapInfoWindowOpened()
{
	//Check all properties on load.
	jQuery(".map-info-window ._villas-365-property-save-button").each(function() {
		let saveButton = jQuery(this);
		let propertyId = saveButton.data("propertyId");

		saveButton.removeClass("loading");
		if(_villas365PropertySaveHasProperty(propertyId))
		{
			saveButton.addClass("active");
		}
		else
		{
			saveButton.removeClass("active");
		}

		let hasClickEvent = saveButton.data("hasClickEvent");
		if(hasClickEvent !== true)
		{
			saveButton.on("click", function() {
				let saveButton = jQuery(this);
				let propertyId = saveButton.data("propertyId");
				_villas365PropertySaveToggleProperty(saveButton, propertyId);
			});

			saveButton.data("hasClickEvent", true);
		}
	});
}