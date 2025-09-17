var _floaterHidden = true;
var _priceCalculating = false;

jQuery(document).ready(function() {
	if(typeof(_365_floaterModalBreakPoint) === "undefined")
	{
		_365_floaterModalBreakPoint = 992;
	}

	if(typeof(_365_floaterModal) === "undefined")
	{
		_365_floaterModal = false;
	}

	// if(_365_floaterModal)
	// {
	// 	setupModal();
	// }

	jQuery("#_villas-365-property-floater #_villas-365-adults, #_villas-365-property-floater #_villas-365-children").on("change", function() {
		calculatePrice();
	});

	jQuery("#_villas-365-property-floater #_villas-365-checkin, #_villas-365-property-floater #_villas-365-checkout").on("change", function() {
		calculatePrice();
	});

	jQuery("#_villas-365-property-floater ._villas-365-property-floater-enquire-button").on("click", function(event) {
		event.preventDefault();
		goToBookingPage(jQuery(this), "inquiry");
	});

	jQuery("#_villas-365-property-floater ._villas-365-property-floater-book-now-button").on("click", function(event) {
		event.preventDefault();
		goToBookingPage(jQuery(this), "book");
	});

	jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-button").on("click", function(event) {
		showPriceDetails(jQuery(this));
	});

	var villas365SearchCheckin = _CookieRead("villas365SearchCheckin");
	var villas365SearchCheckout = _CookieRead("villas365SearchCheckout");
	var villas365SearchGuests = _CookieRead("villas365SearchGuests");
	var calculatePriceOnLoad = 0;

	var datePickerInstance = jQuery('._villas-365-date-range-container ._villas-365-date-control-start').mobiscroll("getInst");
	var datePickerValues = [];
	
	if((datePickerInstance !== null) && (villas365SearchCheckin !== undefined) && (villas365SearchCheckin !== null) && (villas365SearchCheckin !== ""))
	{
		datePickerValues.push(moment(villas365SearchCheckin, "YYYY-MM-DD").toDate());
		
		//Get the hidden date field to hold the value in the correct format.
		var dataInput = jQuery("#_villas-365-checkin");

		//Set the value in the hidden field. This will be the value used on the server.
		dataInput.val(villas365SearchCheckin);
		
		calculatePriceOnLoad++;
	}

	if((datePickerInstance !== null) && (villas365SearchCheckout !== undefined) && (villas365SearchCheckout !== null) && (villas365SearchCheckout !== ""))
	{
		datePickerValues.push(moment(villas365SearchCheckout, "YYYY-MM-DD").toDate());
				
		//Get the hidden date field to hold the value in the correct format.
		var dataInput = jQuery("#_villas-365-checkout");

		//Set the value in the hidden field. This will be the value used on the server.
		dataInput.val(villas365SearchCheckout);

		calculatePriceOnLoad++;
	}

	if(datePickerInstance !== null)
	{
		datePickerInstance.setVal(datePickerValues);
	}

	if((villas365SearchGuests !== undefined) && (villas365SearchGuests !== null) && (villas365SearchGuests !== ""))
	{
		var numberOfAdultsInput = jQuery("#_villas-365-adults");
		numberOfAdultsInput.find("option:selected").removeAttr("selected");
		numberOfAdultsInput.find("option[value='" + villas365SearchGuests + "']").attr("selected", "selected");
		numberOfAdultsInput.trigger('chosen:updated');
		calculatePriceOnLoad++;
	}

	if(calculatePriceOnLoad == 3)
	{
		calculatePrice();
	}

	setupTabButton();
});

function hideServiceFeeNotIncluded(serviceFee = "", serviceFeeField) {
	var serviceTotal = parseInt(serviceFee.replaceAll(/[^0-9,]/g, ""));
	if (serviceTotal === 0) {
		serviceFeeField.parent().hide();
	} else {
		serviceFeeField.parent().show();
		serviceFeeField.html(serviceFee);
	}
}

function calculatePrice()
{
	if(_priceCalculating === true)
	{
		return;
	}

	_priceCalculating = true;

	var priceDetailsContainer = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-row");
	var priceDetailsButton = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-button");

	var perNightField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-per-night");
	var defaultPerNight = perNightField.data("defaultPerNight");
	var rateLabel = perNightField.data("rateLabel");
	var rateLabelPlural = perNightField.data("rateLabelPlural");

	var detailsDiscountContainer = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-discount-container");

	var detailsPerNightLabel = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-label-per-night");
	var detailsPerNightField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-per-night");
	var detailsFeesField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-fees");
	var detailsTaxField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-tax");
	var detailsDiscountField = jQuery("#_villas-365-property-floater #_villas-365-property-floater-price-details-discount");

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

	let discountRow = jQuery("#_villas-365-property-floater #_villas-365-property-floater-discount-row");
	let discountText = jQuery("#_villas-365-property-floater #_villas-365-property-floater-discount-row ._villas-365-property-discount-text");

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
		detailsDiscountField.html('<i class="fas fa-spinner fa-spin"></i>');

		discountRow.hide();
		discountText.html("");

		detailsDiscountContainer.hide();

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
			_priceCalculating = false;

			if(response.data.status === "success")
			{
				totalLabel.removeClass("d-none");
				totalField.html(response.data.total);

				perNightField.html(response.data.perNight + "/" + rateLabel);

				priceDetailsContainer.removeClass("d-none");
				detailsPerNightLabel.html(response.data.perNight + " x " + response.data.totalNights + " " + (response.data.totalNights > 1 ? rateLabelPlural : rateLabel));
				detailsPerNightField.html(response.data.rentTotal);
				
				hideServiceFeeNotIncluded(response.data.serviceTotal, detailsFeesField);
				hideServiceFeeNotIncluded(response.data.taxTotal, detailsTaxField);

				if((typeof(response.data.discountTotal) !== "undefined") && (response.data.discountTotal !== null) && (response.data.discountTotal !== ""))
				{
					detailsDiscountContainer.show();
					detailsDiscountField.html(response.data.discountTotal);
				}

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

				if((typeof(response.data.discount) !== "undefined") && (response.data.discount.length > 0))
				{
					discountText.text = response.data.discount[0];
					for(const discount of response.data.discount)
					{
						discountText.append("<div class='_villas-365-property-discount-text-item'>&nbsp;-&nbsp;" + discount.name + "</div>")
					}
					discountRow.show();
				}
				else
				{
					discountRow.hide();
					discountText.html("");
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

				detailsDiscountContainer.hide();
				detailsDiscountField.html("?");

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

				detailsDiscountContainer.hide();
				detailsDiscountField.html("?");

				minimumNightsContainer.show();
				minimumNights.text(minimumNights.data("defaultMinimumNights"));
				minimumNightsDatesText.hide();
			}
		}).fail(function(response) {
			_priceCalculating = false;
			
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

			detailsDiscountContainer.hide();
			detailsDiscountField.html("?");

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

		detailsDiscountContainer.hide();
		detailsDiscountField.html("?");

		discountRow.hide();

		_priceCalculating = false;
	}
}

function goToBookingPage(button, mode)
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

	if((typeof(mode) !== "undefined") && (mode !== ""))
	{
		url += "&mode=" + mode;
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

function openModalFloater(body, floater)
{
	body.toggleClass("_villas-365-property-floater-visible", _floaterHidden);
	setTimeout(function() {
		floater.toggleClass("_villas-365-property-floater-show", _floaterHidden);
		_floaterHidden = !_floaterHidden;
	}, 50);
}

function closeModalFloater(body, floater)
{
	floater.removeClass("_villas-365-property-floater-show");
	setTimeout(function() {
		body.removeClass("_villas-365-property-floater-visible");
		_floaterHidden = true;
	}, 350);
}

function setupTabButton()
{
	let body = jQuery("body");
	
	let customTabButton = false;
	let tabButton = jQuery("#_villas-365-property-floater-modal-button");
	if((tabButton.length > 0) && _365_floaterModal)
	{
		customTabButton = true;
		jQuery("#_villas-365-property-floater-tab-button").remove();
	}
	else
	{
		tabButton = jQuery("#_villas-365-property-floater-tab-button");
	}
	
	if(tabButton.length == 0)
	{
		return;
	}

	if(!customTabButton)
	{
		tabButton.remove();
		body.append(tabButton);
	}

	let floater = jQuery("#_villas-365-property-floater");
	tabButton.on("click", function(event) {
		event.preventDefault();

		if(_floaterHidden)
		{
			openModalFloater(body, floater);
		}
		else
		{
			closeModalFloater(body, floater);
		}
	});

	let closeButton = jQuery("#_villas-365-property-floater-close-button");
	closeButton.on("click", function() {
		closeModalFloater(body, floater);
	});

	jQuery(window).on("resize", function() {		
		if((jQuery(window).width() >= _365_floaterModalBreakPoint) && !_floaterHidden)
		{
			body.removeClass("_villas-365-property-floater-visible");
			floater.removeClass("_villas-365-property-floater-show");
			_floaterHidden = true;
		}
	});
}

function setupModal()
{
	let body = jQuery("body");
	let modal = jQuery("#_villas-365-property-floater-container");

	if(modal.length == 0)
	{
		return;
	}

	modal.remove();
	body.append(modal);
}