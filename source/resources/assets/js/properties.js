jQuery(document).ready(function () {
	if(((typeof(_villas365SearchAvailable) === "undefined") || (_villas365SearchAvailable === false)) && (Object.prototype.toString.call(jQuery().chosen) === "[object Function]"))
	{
		jQuery(".chosen-select.chosen-select-single").chosen({
			allow_single_deselect: true,
			width: "100%"
		});
	}

	var propertyTitles = jQuery('._villas-365-properties ._villas-365-property-name');
	addMatchHeight(propertyTitles, true);

	var propertySummaries = jQuery('._villas-365-properties ._villas-365-property-summary');
	addMatchHeight(propertySummaries, true);

	jQuery(window).on("resize", function() {		
		if(jQuery(window).width() < 540)
		{
			removeMatchHeight(propertyTitles);
			removeMatchHeight(propertySummaries);
		}
		else
		{
			addMatchHeight(propertyTitles, true);
			addMatchHeight(propertySummaries, true);
		}
	});

	jQuery("._villas-365-properties-sort-controls ._villas-365-properties-sort-control").on("change", function() {
		let selectControl = jQuery(this);
		let href = selectControl.data("href");
		let selectedOption = selectControl.find("option:selected");
		let selectedOptionValue = null;

		if(selectedOption.length == 0)
		{
			return;
		}

		selectedOptionValue = selectedOption.val();

		if(selectedOptionValue == null)
		{
			return;
		}

		jQuery("#_villas-365-sort").val(selectedOptionValue);

		document.location.href = href + selectedOptionValue + "#properties-list";
	});

	jQuery("._villas-365-properties-view-controls ._villas-365-properties-view-control._villas-365-properties-view-button").on("click", function(event) {
		event.preventDefault();

		let viewControl = jQuery(this);
		let href = viewControl.data("href");
		
		document.location.href = href + "#properties-list";
	});
});