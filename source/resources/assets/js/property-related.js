jQuery(document).ready(function () {
	var propertyTitles = jQuery('._villas-365-property-related ._villas-365-property ._villas-365-property-name');

	addMatchHeight(propertyTitles, true);

	jQuery(window).on("resize", function() {		
		if(jQuery(window).width() < 540)
		{
			removeMatchHeight(propertyTitles);
		}
		else
		{
			addMatchHeight(propertyTitles, true);
		}
	});
});