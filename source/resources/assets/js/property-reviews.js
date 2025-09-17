jQuery(document).ready(function () {
	var tabs = jQuery(".tabs_fullwidth_map .et_pb_tabs_controls li");

	var reviewsTab = null;
	tabs.each(function(index) {
		var tab = jQuery(this);
		var tabLinkText = tab.find("a").text().toLowerCase();

		if((tabLinkText.includes("reviews")) ||
			(tabLinkText.includes("bewertungen")) || //de
			(tabLinkText.includes("comentarios")) || //es
			(tabLinkText.includes("commentaires")) || //fr
			(tabLinkText.includes("recensioni")) || //it
			(tabLinkText.includes("avaliação")) || //pt
			(tabLinkText.includes("comentários")) //pt
		)
		{
			//If the reviews tab is first and active when the page loads then don't set the variable so the reviews are loaded automatically.
			if(jQuery(tab).hasClass("et_pb_tab_0") || jQuery(tab).hasClass("et_pb_tab_active"))
			{
				reviewsTab = null;
				return false;
			}

			reviewsTab = tab;
			return false;
		}
	});

	//If we have the expected tabs then only load the reviews widget if the tab is clicked.
	if((reviewsTab != null) && (reviewsTab.length > 0))
	{
		reviewsTab.one("click", function() {
			setTimeout(_villas365PropertyLoadReviews, 500);
		});
	}
	else
	{
		//If we don't have the tabs then just try to load the reviews widget immediately.
		_villas365PropertyLoadReviews();
	}
});

function _villas365PropertyLoadReviews()
{
	var reviewsWidgetHtml = Widget365VillasIframeCustomerReview.loadWidget();
	jQuery("._villas-365-property-reviews").html(reviewsWidgetHtml);
}