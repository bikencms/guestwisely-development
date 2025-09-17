jQuery(document).ready(function () {
	if(Object.prototype.toString.call(jQuery().chosen) === "[object Function]")
	{
		jQuery(".chosen-select.chosen-select-single").chosen({
			allow_single_deselect: true,
			width: "100%"
		});

		jQuery(".chosen-input-group").each(function() {
			var currentGroup = jQuery(this);
			var chosenElement = currentGroup.find(".chosen-select.chosen-select-single");
			var groupIcon = currentGroup.find(".input-group-append");

			groupIcon.on("click", function(event) {
				event.stopPropagation();
				chosenElement.trigger("chosen:open");
			});
		});

		jQuery(".search-categories .chosen-select.chosen-select-single").on("change", function(event, parameters) {
			var selectedCategoryItem = jQuery(this).find("option:selected");
			var selectedCategoryItemType = selectedCategoryItem.data("type");
			var selectedCategoryItemId = selectedCategoryItem.data("id");

			if(selectedCategoryItemType == "category")
			{
				jQuery("#_villas-365-search-categoryid").val(selectedCategoryItemId);
				jQuery("#_villas-365-search-propertyid").val("");
			}
			else if(selectedCategoryItemType == "property")
			{
				jQuery("#_villas-365-search-categoryid").val("");
				jQuery("#_villas-365-search-propertyid").val(selectedCategoryItemId);
			}
			else
			{
				jQuery("#_villas-365-search-categoryid").val("");
				jQuery("#_villas-365-search-propertyid").val("");
			}
		});

		jQuery(".search-categories .chosen-search input.chosen-search-input").on("keyup", function(event, parameters) {
			var thisValue = jQuery.trim(jQuery(this).val());
			var group = jQuery("._villas-365-search .search-categories .chosen-results .group-result")
			var category = jQuery("._villas-365-search .search-categories .chosen-results .search-category")

			if(thisValue != "")
			{
				group.css("display", "list-item");
				category.css("display", "none");
			}
			else
			{
				group.css("display", "none");
				category.css("display", "list-item");
			}
		});
	}

	jQuery("._villas-365-option-checkbox label").hide();
	jQuery("._villas-365-option-checkbox ._villas-365-option-checkbox-custom-container").show();
	jQuery("._villas-365-option-checkbox").on("click", function() {
		var currentCheckboxContainer = jQuery(this);
		var checkbox = currentCheckboxContainer.find("input[type=checkbox]");

		if(checkbox.prop("checked"))
		{
			checkbox.prop("checked", false);
		}
		else
		{
			checkbox.prop("checked", true);
		}

		currentCheckboxContainer.find("._villas-365-option-checkbox-custom").toggleClass("checked");
	});

	//When the date changes store the value in the browser session object.
	jQuery('._villas-365-checkin._villas-365-date-control._villas-365-date-control-start').on("change", function(event) {
		var formattedDate = "";
		if((typeof(jQuery(this).val()) !== "undefined") && (jQuery(this).val() !== null) && (jQuery(this).val() !== ""))
		{
			formattedDate = moment(jQuery(this).val(), "MMM/DD/YYYY").format("YYYY-MM-DD");
		}

		_CookiesCreate("villas365SearchCheckin", formattedDate);
	});

	jQuery('._villas-365-checkout._villas-365-date-control._villas-365-date-control-end').on("change", function(event) {
		var formattedDate = "";
		if((typeof(jQuery(this).val()) !== "undefined") && (jQuery(this).val() !== null) && (jQuery(this).val() !== ""))
		{
			formattedDate = moment(jQuery(this).val(), "MMM/DD/YYYY").format("YYYY-MM-DD");
		}

		_CookiesCreate("villas365SearchCheckout", formattedDate);
	});

	jQuery(".search-guests .chosen-select.chosen-select-single").on("change", function(event, parameters) {
		var selectedGuestsItem = jQuery(this).find("option:selected");
		var selectedGuestsItemValue = selectedGuestsItem.val();
		_CookiesCreate("villas365SearchGuests", selectedGuestsItemValue);
	});

	jQuery("._villas-365-search-full ._villas-365-filter-button").on("click", function(event) {
		jQuery(this).toggleClass("active");
		jQuery("#_villas-365-search-form-filter").slideToggle();
	});

	jQuery("._villas-365-search-full #_villas-365-search-reset").on("click", function(event) {
		let form = jQuery("._villas-365-search-form");
		form.trigger("reset");
		form.find('input:text, input:password, input:file, input:hidden, select, textarea').val('');
		form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');

		form.find("._villas-365-option-checkbox-custom").removeClass("checked");

		jQuery("._villas-365-search-form .chosen-select.chosen-select-single").trigger('chosen:updated');
	});
});