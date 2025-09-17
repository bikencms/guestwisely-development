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
});