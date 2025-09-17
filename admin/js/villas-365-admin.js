jQuery(document).ready(function () {
	jQuery(".color-picker").colorPicker({
		renderCallback: function(element, toggled) {
			if(element.val())
			{
				var colors = this.color.colors;
				element.val("#" + colors.HEX);
			}
		}
	});

	jQuery(".color-picker-clear-button").on("click", function() {
		var colorFieldId = jQuery(this).data("colorFieldId");
		var colorField = jQuery("#" + colorFieldId);
		colorField.val("");
		colorField.css("background-color", "#fff");
		colorField.css("color", "inherit");
	});
});