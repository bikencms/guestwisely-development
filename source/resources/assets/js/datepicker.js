var _villas365DatepickerAdded = false;

jQuery(document).ready(function () {
	_villas365AddDatepicker();
});

function _villas365AddDatepicker()
{
	//Check if we have the hidden field container on the page.
	if(jQuery("#_datetime-hidden-fields").length === 0)
	{
		console.error("A container with the ID: '_datetime-hidden-fields' is required for the date control to function correctly.");
		return;
	}
	
	//For each input date group we need to create a hidden field with the same name and change the original fields name.
	//We need to do this so we can send the date with the correct format back to the server. This format (YYYY-MM-DD) will match
	//the format sent by the built in browser controls in browsers that support the control.
	//The javascript date picker can't output a different date format to the display format (correctly).
	jQuery('._villas-365-date-container').each(function() {
		//Get the original input box.
		var currentInput = jQuery(this).find("._villas-365-date-control");
		var currentValue = currentInput.val();

		//Get the current value and check if it is in the correct format.
		//If it isn't then change the value to the correct date format.
		if(currentValue.indexOf("-") !== -1)
		{
			currentInput.val(moment(currentValue, "YYYY-MM-DD").format("MMM/DD/YYYY"));
		}
	});

	var datesDisabled = [];
	if((typeof(_365_nonAvailableDates) !== "undefined") && (_365_nonAvailableDates != null) && (_365_nonAvailableDates != ""))
	{
		datesDisabled = _365_nonAvailableDates;
	}

	var datesDisabledDisplay = [];
	if((typeof(_365_nonAvailableDatesDisplay) !== "undefined") && (_365_nonAvailableDatesDisplay != null) && (_365_nonAvailableDatesDisplay != ""))
	{
		datesDisabledDisplay = _365_nonAvailableDatesDisplay;
	}

	let datepickerOptions = {
		select: 'range',
		startInput: '._villas-365-date-control-start',
    	endInput: '._villas-365-date-control-end',
		themeVariant: 'light',
		firstDay: typeof _365_startWorkingDayCalendar == 'undefined' ? 0 : _365_startWorkingDayCalendar,
		dateFormat: 'MMM/DD/YYYY',
		min: new Date(),
		invalid: datesDisabled,
		colors: datesDisabledDisplay,
		onInit: function (event, instance) {
			var currentInput = jQuery(instance.props.element);
			var startInput = jQuery("#" + currentInput.data("start-field-id"));
			var endInput = jQuery("#" + currentInput.data("end-field-id"));
			var newValues = [];

			if((startInput !== null) && (startInput !== "") && (startInput.length > 0))
			{
				newValues.push(moment(startInput.val(), "YYYY-MM-DD").toDate());
			}

			if((endInput !== null) && (endInput !== "") && (endInput.length > 0))
			{
				newValues.push(moment(endInput.val(), "YYYY-MM-DD").toDate());
			}

			instance.setVal(newValues);
		},
		onChange: function(event, instance) {
			//When the date changes:
			//Get the original input box.
			var currentInput = jQuery(instance.props.element);
			var startInput = jQuery("#" + currentInput.data("start-field-id"));
			var endInput = jQuery("#" + currentInput.data("end-field-id"));
			
			//Set the value in the hidden fields. This will be the value used on the server.
			if((typeof(event.value[0]) !== "undefined") && (event.value[0] !== null) && (event.value[0] !== ""))
			{
				startInput.val(moment(event.value[0], "MMM/DD/YYYY").format("YYYY-MM-DD"));
			}
			else
			{
				startInput.val("");
			}

			if((typeof(event.value[1]) !== "undefined") && (event.value[1] !== null) && (event.value[1] !== ""))
			{
				endInput.val(moment(event.value[1], "MMM/DD/YYYY").format("YYYY-MM-DD"));
			}
			else
			{
				endInput.val("");
			}

			startInput.trigger("change");
			endInput.trigger("change");
		}
	};
	

	if((typeof(_365_discountDatesDisplay) !== "undefined") && (_365_discountDatesDisplay != null) && (_365_discountDatesDisplay != ""))
	{
		datepickerOptions["marked"] = _365_discountDatesDisplay;
	}

	if((typeof(_villas365CurrentLocaleDatepicker) !== "undefined") && (_villas365CurrentLocaleDatepicker != null) && (_villas365CurrentLocaleDatepicker != ""))
	{
		let datapickerLocale = mobiscroll[_villas365CurrentLocaleDatepicker];

		if((typeof(datapickerLocale) !== "undefined") && (datapickerLocale != null) && (datapickerLocale != ""))
		{
			datepickerOptions["locale"] = datapickerLocale;

			if(_villas365CurrentLocaleDatepicker === "localeFr")
			{
				datepickerOptions["rangeStartLabel"] = "Début";
			}
		}
	}
	
	//Initalise the date picker(s).
	jQuery('._villas-365-date-range-container ._villas-365-date-control-start').mobiscroll().datepicker(datepickerOptions);

	jQuery('._villas-365-date-range-container ._villas-365-date-control').each(function() {
		var currentDateControl = jQuery(this);
		currentDateControl.parent(".input-group").find(".input-group-append").off("click");
		currentDateControl.parent(".input-group").find(".input-group-append").on("click", function() {
			jQuery('._villas-365-date-range-container ._villas-365-date-control-start').mobiscroll("open");
		});
	});

	_villas365DatepickerAdded = true;
}