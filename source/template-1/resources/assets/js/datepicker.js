var _villas365DatepickerAdded = false;

jQuery(document).ready(function () {
	if(!_villas365DatepickerAdded && (!Modernizr.inputtypes.date || (jQuery(window).width() >= 768)))
	{
		_villas365AddDatepicker();
	}

	if(Modernizr.inputtypes.date)
	{
		jQuery(window).on("resize", function() {		
			if(jQuery(window).width() < 768)
			{
				if(_villas365DatepickerAdded)
				{
					_villas365RemoveDatepicker();
				}
			}
			else
			{
				if(!_villas365DatepickerAdded)
				{
					_villas365AddDatepicker();
				}
			}
		});
	}
});

function _villas365RemoveDatepicker()
{
	jQuery('._villas-365-date-container').each(function() {
		//Get the original input box.
		var currentInput = jQuery(this).find("._villas-365-date-control");
		currentInput.attr("type", "date");
		currentInput.attr("name", currentInput.data("originalName"));

		var hiddenFieldsContainer = jQuery("#_datetime-hidden-fields");

		//Set the value format correctly.
		var dataInput = hiddenFieldsContainer.find("input[name='" + currentInput.data("originalName") + "']");
		currentInput.val(dataInput.val());

		//Remove the hidden input with the same name.
		dataInput.detach();
	});
	
	//Remove the date picker(s).
	jQuery('._villas-365-date-control').datepicker('destroy');

	jQuery('._villas-365-date-control').each(function() {
		var currentDateControl = jQuery(this);
		currentDateControl.parent(".input-group").find(".input-group-append").off("click");
		// currentDateControl.parent(".input-group").find(".input-group-append").on("click", function() {
		// 	currentDateControl.focus();
		// });
	});

	_villas365DatepickerAdded = false;
}

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
		currentInput.attr("type", "text");

		var currentValue = currentInput.val();

		//Get the original input box name.
		var name = currentInput.attr("name");

		//Create a hidden input with the same name.
		var hiddenFieldsContainer = jQuery("#_datetime-hidden-fields");
		hiddenFieldsContainer.append("<input type='hidden' name='" + name + "' value='" + currentValue + "'>");
		
		//Change the name of the original field.
		currentInput.removeAttr("name");

		//Set the original name on the original field so we can get the hidden field later.
		currentInput.data("originalName", name);

		//Get the current value and check if it is in the correct format.
		//If it isn't then change the value to the correct date format.
		if(currentValue.indexOf("-") !== -1)
		{
			currentInput.val(moment(currentValue, "YYYY-MM-DD").format("MMM/DD/YYYY"));
		}
	});
	
	//Initalise the date picker(s).
	jQuery('._villas-365-date-range-container').datepicker({
		weekStart: 1,
		format: "M/dd/yyyy",
		startDate: "today",
		autoclose: true,
		orientation: "bottom",
		inputs: jQuery('._villas-365-date-control')
	});
	
	jQuery('._villas-365-date-control').on("changeDate", function(event) {
		//When the date changes:
		//Get the original input box.
		var currentInput = jQuery(this);
		
		//Get the hidden date field to hold the value in the correct format.
		var dataInput = jQuery("#_datetime-hidden-fields").find("input[name='" + currentInput.data("originalName") + "']");

		//Set the value in the hidden field. This will be the value used on the server.
		dataInput.val(moment(event.date).format("YYYY-MM-DD"));
	});

	jQuery('._villas-365-date-control').each(function() {
		var currentDateControl = jQuery(this);
		currentDateControl.parent(".input-group").find(".input-group-append").off("click");
		currentDateControl.parent(".input-group").find(".input-group-append").on("click", function() {
			currentDateControl.datepicker("show");
		});
	});

	_villas365DatepickerAdded = true;
}