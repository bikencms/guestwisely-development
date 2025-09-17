jQuery(document).ready(function() {
	jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-items ._villas-365-property-amenity-switcher-item").on("click", function () {
		var selectedAmenityGroup = jQuery(this);
		_villas365PropertyAmenitySwitch(selectedAmenityGroup);
		jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-items").fadeOut();

		let selected = jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-item-selected");
		selected.find("._villas-365-property-amenity-switcher-item-selected-icon i").toggleClass("fa-chevron-right").toggleClass("fa-chevron-down");
		selected.toggleClass("active");
	});

	jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-item-selected").on("click", function () {
		let selected = jQuery(this);
		jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-items").fadeToggle();
		selected.find("._villas-365-property-amenity-switcher-item-selected-icon i").toggleClass("fa-chevron-right").toggleClass("fa-chevron-down");
		selected.toggleClass("active");
	});

	jQuery("._villas-365-property-amenity-switcher-inline ._villas-365-property-amenity-switcher-inline-items ._villas-365-property-amenity-switcher-inline-item").on("click", function () {
		var selectedAmenityGroup = jQuery(this);
		_villas365PropertyAmenitySwitch(selectedAmenityGroup);
		
		jQuery("._villas-365-property-amenity-switcher-inline ._villas-365-property-amenity-switcher-inline-items ._villas-365-property-amenity-switcher-inline-item.active").removeClass("active");
		selectedAmenityGroup.addClass("active");
	});

	jQuery("._villas-365-property-amenities-container ._villas-365-property-amenity-group ._villas-365-property-amenity-specific-room-buttons ._villas-365-property-amenity-room-button").on("click", function() {
		_villas365PropertyAmenitySpecificRoomSwitch(jQuery(this));
	});
});

function _villas365PropertyAmenitySwitch(selected)
{
	var selectedAmenityName = selected.data("amenityName");
	var currentAmenityItem = jQuery("._villas-365-property-amenities-container ._villas-365-property-amenity-group.active");
	var sameAmenitySelectedAsActive = (currentAmenityItem.data("amenityName") == selectedAmenityName);
	if(sameAmenitySelectedAsActive || (typeof(selectedAmenityName) == "undefined") || (selectedAmenityName == null) || (selectedAmenityName == ""))
	{
		//Don't hide or show anything as we don't have a value.
		return;
	}

	//Remove active from the current active item and show the new item.
	var selectedAmenityItem = jQuery("._villas-365-property-amenities-container ._villas-365-property-amenity-group[data-amenity-name='" + selectedAmenityName + "']");
	var currentAmenityItem = jQuery("._villas-365-property-amenities-container ._villas-365-property-amenity-group.active");
	currentAmenityItem.removeClass("active");
	selectedAmenityItem.addClass("active");
	currentAmenityItem.fadeOut();
	selectedAmenityItem.fadeIn();

	//Display the selected amenity group.
	jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-item-selected ._villas-365-property-amenity-switcher-item-selected-text").text(selectedAmenityName);
}

function _villas365PropertyAmenitySpecificRoomSwitch(selected)
{
	var selectedAmenitySpecificRoomNumber = selected.data("amenityRoomNumber");
	var currentAmenitySpecificRoom = jQuery("._villas-365-property-amenities-container ._villas-365-property-amenity-group.active ._villas-365-property-amenity-specific-room.active");
	var sameAmenitySelectedAsActive = (currentAmenitySpecificRoom.data("amenityRoomNumber") == selectedAmenitySpecificRoomNumber);
	if(sameAmenitySelectedAsActive || (typeof(selectedAmenitySpecificRoomNumber) == "undefined") || (selectedAmenitySpecificRoomNumber == null) || (selectedAmenitySpecificRoomNumber == ""))
	{
		//Don't hide or show anything as we don't have a value.
		return;
	}

	//Remove active from the current active item and show the new item.
	var selectedAmenitySpecificRoom = jQuery("._villas-365-property-amenities-container ._villas-365-property-amenity-group.active ._villas-365-property-amenity-specific-room[data-amenity-room-number='" + selectedAmenitySpecificRoomNumber + "']");
	currentAmenitySpecificRoom.removeClass("active");
	selectedAmenitySpecificRoom.addClass("active");
	currentAmenitySpecificRoom.css("top", ((selected.parent("._villas-365-property-amenity-specific-room-buttons").height() + 15) + "px"));
	currentAmenitySpecificRoom.fadeOut(function() {
		currentAmenitySpecificRoom.css("top", "auto");
	});
	selectedAmenitySpecificRoom.fadeIn();

	//Display the selected amenity specific room.
	var currentAmenitySpecificRoomButton = jQuery("._villas-365-property-amenities-container ._villas-365-property-amenity-group.active ._villas-365-property-amenity-specific-room-buttons ._villas-365-property-amenity-room-button.active");
	currentAmenitySpecificRoomButton.removeClass("active");
	selected.addClass("active");
}