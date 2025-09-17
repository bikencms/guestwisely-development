var _floaterTopPadding = 0;
var _floaterScrollPoint = 0;
var _footerScrollTop = null;

jQuery(document).ready(function(){
	jQuery("#_property-images-scrolling-features-scroll-left").on("click", function(event){
		event.preventDefault();

		if(_villas365PropertyImagesScrollerShouldScroll())
		{
			_villas365PropertyImagesScrollerScrollLeft();
		}
	});
	
	jQuery("#_property-images-scrolling-features-scroll-right").on("click", function(event){
		event.preventDefault();

		if(_villas365PropertyImagesScrollerShouldScroll())
		{
			_villas365PropertyImagesScrollerScrollRight();
		}
	});

	jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-items ._villas-365-property-amenity-switcher-item").on("click", function () {
		var selectedAmenityGroup = jQuery(this);
		_villas365PropertyAmenitySwitch(selectedAmenityGroup);
		jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-items").fadeOut();
	});

	jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-item-selected").on("click", function () {
		jQuery("._villas-365-property-amenity-switcher ._villas-365-property-amenity-switcher-items").fadeToggle();
	});

	jQuery("._villas-365-property-amenity-switcher-inline ._villas-365-property-amenity-switcher-inline-items ._villas-365-property-amenity-switcher-inline-item").on("click", function () {
		var selectedAmenityGroup = jQuery(this);
		_villas365PropertyAmenitySwitch(selectedAmenityGroup);
		
		jQuery("._villas-365-property-amenity-switcher-inline ._villas-365-property-amenity-switcher-inline-items ._villas-365-property-amenity-switcher-inline-item.active").removeClass("active");
		selectedAmenityGroup.addClass("active");
	});

	jQuery("#_property-images-scrolling-features .property-images-link").on("click", function(event) {
		_villas365PropertyImagesScrollerClickImage(event, jQuery(this));
	});

	jQuery('.banner-carousel').one("slide.bs.carousel", function () {
		jQuery("._villas-365-property-header").fadeOut();
	});

	jQuery('#_villas-365-property-header-close-button').one("click", function () {
		jQuery("._villas-365-property-header").fadeOut();
	});

	jQuery("._villas-365-property-information ._villas-365-property-information-controls ._villas-365-property-information-control").on("click", function() {
		_villas365PropertyInformationItemClicked(jQuery(this));
	});

	calculateFloaterOffsets();
	positionFloater();
	jQuery(window).on("scroll", function() {
		if((jQuery(window).width() >= 992) && (jQuery(window).height() >= 700))
		{
			positionFloater();
		}
	});
	jQuery(window).on("resize", function() {
		if((jQuery(window).width() >= 992) && (jQuery(window).height() >= 700))
		{
			calculateFloaterOffsets();
			positionFloater();
		}
		else if(jQuery(window).height() < 700)
		{
			removeFloater();
		}
	});

	jQuery("#_villas-365-property-floater #_villas-365-adults, #_villas-365-property-floater #_villas-365-children").on("change", function() {
		calculatePrice();
	});

	jQuery("#_villas-365-property-floater ._villas-365-date-control").on("changeDate", function() {
		calculatePrice();
	});

	jQuery("#_villas-365-property-floater #_villas-365-property-floater-book-now-button").on("click", function(event) {
		event.preventDefault();
		goToBookingPage(jQuery(this));
	});

	jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-button").on("click", function(event) {
		showPriceDetails(jQuery(this));
	});
});

function _villas365PropertyImagesScrollerClickImage(event, element)
{
	event.preventDefault();
	var imageKey = element.data("imageKey");
	jQuery(".banner-carousel").carousel(imageKey);
}

function _villas365PropertyImagesScrollerShouldScroll()
{
	var totalWidth = 0;
	jQuery("ul#_property-images-scrolling-features>li").each(function(){
		totalWidth += (jQuery(this).width() + 10);
	});

	return totalWidth > jQuery("#_property-images-scrolling-container").first().width();
}

function _villas365PropertyImagesScrollerScrollRight()
{
	var scrollingFeatures = jQuery("ul#_property-images-scrolling-features");
	var firstElement = jQuery("ul#_property-images-scrolling-features>li").first();

	firstElement.remove();
	scrollingFeatures.append(firstElement);
	scrollingFeatures.css("left", 0);

	firstElement.off("click").on("click", function(event) {
		_villas365PropertyImagesScrollerClickImage(event, jQuery(this).find(".property-images-link"));
	});
}

function _villas365PropertyImagesScrollerScrollLeft()
{
	var scrollingFeatures = jQuery("ul#_property-images-scrolling-features");
	var lastElement = jQuery("ul#_property-images-scrolling-features>li").last();

	lastElement.remove();
	scrollingFeatures.prepend(lastElement);

	lastElement.off("click").on("click", function(event) {
		_villas365PropertyImagesScrollerClickImage(event, jQuery(this).find(".property-images-link"));
	});
}

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

function _villas365PropertyInformationItemsSetHeight()
{
	jQuery("._villas-365-property-information ._villas-365-property-information-items ._villas-365-property-information-item").each(function() {
		var item = jQuery(this);
		var height = item.find("._villas-365-property-information-item-content").height();
		item.css("height", (height + 60) + "px");
	});
}

function _villas365PropertyInformationItemClicked(selectedItem)
{
	//Get the elements
	var itemContainer = jQuery("#" + selectedItem.data("informationItemId"));
	var currentActiveItemControl = jQuery("._villas-365-property-information ._villas-365-property-information-controls ._villas-365-property-information-control.active");
	var currentActiveItemContainer = jQuery("._villas-365-property-information ._villas-365-property-information-items ._villas-365-property-information-item.active");

	//Remove and add the active classes
	currentActiveItemControl.removeClass("active");
	selectedItem.addClass("active");

	currentActiveItemContainer.removeClass("active");
	itemContainer.addClass("active");
}

function calculateFloaterOffsets()
{
	var floaterAnchor = jQuery("#_villas-365-property-floater-anchor");
	
	var mainHeader = jQuery("#main-header");
	var mainHeaderHeight = 0;
	if(mainHeader.length > 0)
	{
		mainHeaderHeight = mainHeader.height();
	}

	var wordpressAdminBar = jQuery("#wpadminbar");
	var wordpressAdminBarHeight = 0;
	if(wordpressAdminBar.length > 0)
	{
		wordpressAdminBarHeight = wordpressAdminBar.height();
	}

	_floaterTopPadding = mainHeaderHeight + wordpressAdminBarHeight + 60;
	_floaterScrollPoint = floaterAnchor.offset().top - _floaterTopPadding;
}

function positionFloater()
{
	var floater = jQuery("#_villas-365-property-floater");
	var floaterContent = floater.find("._villas-365-property-floater-content");
	var currentScrollTop = jQuery(document).scrollTop();
	
	if(jQuery(document).scrollTop() > _floaterScrollPoint)
	{
		var floaterOffsetBottom = floater.offset().top + floaterContent.height();
		var footerTopOffset = jQuery("footer").offset().top - 50;
		if(((floaterOffsetBottom > footerTopOffset) || (_footerScrollTop != null)) && ((_footerScrollTop == null) || (currentScrollTop >= _footerScrollTop)))
		{
			if(_footerScrollTop == null)
			{
				_footerScrollTop = currentScrollTop;
			}

			floater.css("top", ((_floaterTopPadding - 30) - (currentScrollTop - _footerScrollTop)) + "px");
		}
		else
		{
			_footerScrollTop = null;
			floater.addClass("float");
			floater.css("top", (_floaterTopPadding - 30) + "px");
		}
	}
	else
	{
		floater.removeClass("float");
		floater.css("top", "-30px");
	}
}

function removeFloater()
{
	var floater = jQuery("#_villas-365-property-floater");
	floater.removeClass("float");
	floater.css("top", "-30px");
}

function calculatePrice()
{
	var priceDetailsContainer = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-row");
	var priceDetailsButton = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-button");

	var perNightField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-per-night");
	var defaultPerNight = perNightField.data("defaultPerNight");
	var rateLabel = perNightField.data("rateLabel");
	var rateLabelPlural = perNightField.data("rateLabelPlural");

	var detailsPerNightLabel = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-label-per-night");
	var detailsPerNightField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-per-night");
	var detailsFeesField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-fees");
	var detailsTaxField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-tax");

	var totalLabel = jQuery("#_villas-365-property-floater #_villas-365-property-floater-total-label");
	var totalField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-total");
	var propertyId = jQuery("#_villas-365-property-floater").data("propertyId");
	var checkIn = jQuery("#_villas-365-property-floater input[name='checkin']").val();
	var checkOut = jQuery("#_villas-365-property-floater input[name='checkout']").val();
	var adults = jQuery("#_villas-365-property-floater #_villas-365-adults").val();
	var children = jQuery("#_villas-365-property-floater #_villas-365-children").val();

	var minimumNightsContainer = jQuery("#_villas-365-property-floater #_villas-365-property-floater-minimum-nights-container");
	var minimumNights = jQuery("#_villas-365-property-floater #_villas-365-property-floater-minimum-nights");
	var minimumNightsDatesText = jQuery("#_villas-365-property-floater #_villas-365-property-floater-minimum-nights-dates-text");

	if((checkIn !== "") &&
		(checkOut !== "") &&
		(adults !== ""))
	{
		totalField.removeClass("text-center");
		totalField.html('<i class="fas fa-spinner fa-spin"></i>');

		perNightField.html('<i class="fas fa-spinner fa-spin"></i>');

		detailsPerNightLabel.html('<i class="fas fa-spinner fa-spin"></i>');
		detailsPerNightField.html('<i class="fas fa-spinner fa-spin"></i>');
		detailsFeesField.html('<i class="fas fa-spinner fa-spin"></i>');
		detailsTaxField.html('<i class="fas fa-spinner fa-spin"></i>');

		jQuery.ajax({
			type: "POST",
			url: _villas_365_wp_ajax.ajax_url,
			dataType: "json",
			data: {
				_ajax_nonce: _villas_365_wp_ajax.nonce,
				action: "_villas_365_calculate_booking",
				propertyId: propertyId,
				checkIn: checkIn,
				checkOut: checkOut,
				adults: adults,
				children: children
			}
		}).done(function(response) {
			if(response.data.status === "success")
			{
				totalLabel.removeClass("d-none");
				totalField.html(response.data.total);

				perNightField.html(response.data.perNight + "/" + rateLabel);

				priceDetailsContainer.removeClass("d-none");
				detailsPerNightLabel.html(response.data.perNight + " x " + response.data.totalNights + " " + (response.data.totalNights > 1 ? rateLabelPlural : rateLabel));
				detailsPerNightField.html(response.data.rentTotal);
				detailsFeesField.html(response.data.serviceTotal);
				detailsTaxField.html(response.data.taxTotal);

				if(response.data.numberOfNightsOk === "false")
				{
					minimumNightsContainer.show();
					minimumNights.text(response.data.minimumNights);
					minimumNightsDatesText.show();
				}
				else
				{
					minimumNightsContainer.hide();
				}
			}
			else if(response.data.status === "error" && response.data.message !== "")
			{
				totalLabel.addClass("d-none");
				totalField.addClass("text-center");
				totalField.html(response.data.message);

				perNightField.html(defaultPerNight);

				showPriceDetails(priceDetailsButton, true);
				priceDetailsContainer.addClass("d-none");
				detailsPerNightLabel.html("?");
				detailsPerNightField.html("?");
				detailsFeesField.html("?");
				detailsTaxField.html("?");

				minimumNightsContainer.show();
				minimumNights.text(minimumNights.data("defaultMinimumNights"));
				minimumNightsDatesText.hide();
			}
			else
			{
				totalLabel.addClass("d-none");
				totalField.addClass("text-center");
				totalField.html("We're sorry, an error occurred.");

				perNightField.html(defaultPerNight);

				showPriceDetails(priceDetailsButton, true);
				priceDetailsContainer.addClass("d-none");
				detailsPerNightLabel.html("?");
				detailsPerNightField.html("?");
				detailsFeesField.html("?");
				detailsTaxField.html("?");

				minimumNightsContainer.show();
				minimumNights.text(minimumNights.data("defaultMinimumNights"));
				minimumNightsDatesText.hide();
			}
		}).fail(function(response) {
			totalLabel.addClass("d-none");
			totalField.addClass("text-center");
			totalField.html("We're sorry, an error occurred.");

			perNightField.html(defaultPerNight);

			showPriceDetails(priceDetailsButton, true);
			priceDetailsContainer.addClass("d-none");
			detailsPerNightLabel.html("?");
			detailsPerNightField.html("?");
			detailsFeesField.html("?");
			detailsTaxField.html("?");

			minimumNightsContainer.show();
			minimumNights.text(minimumNights.data("defaultMinimumNights"));
			minimumNightsDatesText.hide();
		});
	}
	else
	{
		totalLabel.addClass("d-none");
		totalField.html("");

		perNightField.html(defaultPerNight);

		showPriceDetails(priceDetailsButton, true);
		priceDetailsContainer.addClass("d-none");
		detailsPerNightLabel.html("?");
		detailsPerNightField.html("?");
		detailsFeesField.html("?");
		detailsTaxField.html("?");
	}
}

function goToBookingPage(button)
{
	var url = button.attr("href");
	var propertyId = jQuery("#_villas-365-property-floater").data("propertyId");
	var checkIn = jQuery("#_villas-365-property-floater input[name='checkin']").val();
	var checkOut = jQuery("#_villas-365-property-floater input[name='checkout']").val();
	var adults = jQuery("#_villas-365-property-floater #_villas-365-adults").val();
	var children = jQuery("#_villas-365-property-floater #_villas-365-children").val();

	url = url + "?property=" + propertyId;

	if(checkIn !== "")
	{
		url += "&checkin=" + checkIn;
	}

	if(checkOut !== "")
	{
		url += "&checkout=" + checkOut;
	}

	if(adults !== "")
	{
		url += "&adults=" + adults;
	}

	if(children !== "")
	{
		url += "&children=" + children;
	}

	document.location.href = url;
}

function showPriceDetails(button, forceClose)
{
	if(typeof(forceClose) === "undefined")
	{
		forceClose = false;
	}

	var detailsContainer = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-row ._villas-365-property-floater-price-details-container");
	if(detailsContainer.data("collapsed") && !forceClose)
	{
		detailsContainer.slideDown();

		detailsContainer.data("collapsed", false);
		button.removeClass("collapsed");

		var rotateItem180 = button.find(".rotate");
		var rotateClass = button.data("rotateClass");
		if(typeof(rotateClass) == "undefined")
		{
			rotateClass = "rotate-180";
		}
		rotateItem180.removeClass("rotate-0");
		rotateItem180.addClass(rotateClass);
	}
	else if(!detailsContainer.data("collapsed") || forceClose)
	{
		detailsContainer.slideUp();

		detailsContainer.data("collapsed", true);
		button.addClass("collapsed");
		
		var rotateItem180 = button.find(".rotate");
		var rotateClass = button.data("rotateClass");
		if(typeof(rotateClass) == "undefined")
		{
			rotateClass = "rotate-180";
		}
		rotateItem180.removeClass(rotateClass);
		rotateItem180.addClass("rotate-0");
	}
}