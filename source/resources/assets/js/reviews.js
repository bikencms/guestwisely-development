jQuery(document).ready(function () {
	_villas365PropertyLoadReviewsGrid();
	_villas365PropertyLoadReviewsForm();
});

function _villas365PropertyLoadReviewsGrid()
{
	if(typeof(Widget365VillasIframeCustomerReview) !== "undefined")
	{
		var reviewsWidgetGridHtml = Widget365VillasIframeCustomerReview.loadWidget();
		jQuery("._villas-365-property-reviews-grid").html(reviewsWidgetGridHtml);
	}
}

function _villas365PropertyLoadReviewsForm()
{
	if(typeof(Widget365VillasIframeCustomerReviewSubmit) !== "undefined")
	{
		var reviewsWidgetFormHtml = Widget365VillasIframeCustomerReviewSubmit.loadWidget();
		jQuery("._villas-365-property-reviews-form").html(reviewsWidgetFormHtml);
	}
}