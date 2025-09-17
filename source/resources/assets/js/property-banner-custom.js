jQuery(document).ready(function() {
	jQuery('#_villas-365-property-header-close-button').one("click", function () {
		jQuery("._villas-365-property-header").fadeOut();
	});

	jQuery("#_property-banner-features-scroll-left").on("click", function(event) {
		event.preventDefault();

		if(_villas365PropertyBannerShouldScroll())
		{
			_villas365PropertyBannerScrollLeft();
		}
	});
	
	jQuery("#_property-banner-features-scroll-right").on("click", function(event) {
		event.preventDefault();

		if(_villas365PropertyBannerShouldScroll())
		{
			_villas365PropertyBannerScrollRight();
		}
	});

	jQuery("#_property-banner-features-scroll-left, #_property-banner-features-scroll-right").one("click", function(event) {
		jQuery("._villas-365-property-header").fadeOut();
	});

	if(typeof(_Villas365LazyLoadImages) !== "undefined")
	{
		jQuery("#_property-banner-features-scroll-left, #_property-banner-features-scroll-right").one("click", function(event){
			let lazyLoadImageElements = jQuery("#_property-banner-container ._villas-365-lazy-load-image");

			lazyLoadImageElements.each(function() {
				_Villas365LazyLoadImages(this);
			});
		});
	}
});

function _villas365PropertyBannerShouldScroll()
{
	let totalWidth = 0;
	jQuery("#_property-banner-features>div").each(function(){
		totalWidth += (jQuery(this).width() + 10);
	});

	return totalWidth > jQuery("#_property-banner-container").first().width();
}

function _villas365PropertyBannerScrollRight()
{
	let scrollingFeatures = jQuery("#_property-banner-features");
	let firstElement = jQuery("#_property-banner-features > ._property-banner-image").first();

	let width = firstElement.width();
	scrollingFeatures.animate({
		"left": "-=" + width
	}, 600, function() {
		firstElement.remove();
		scrollingFeatures.append(firstElement);
		scrollingFeatures.css("left", 0);
	});
}

function _villas365PropertyBannerScrollLeft()
{
	let scrollingFeatures = jQuery("#_property-banner-features");
	let lastElement = jQuery("#_property-banner-features > ._property-banner-image").last();

	let width = lastElement.width();
	lastElement.remove();
	scrollingFeatures.prepend(lastElement);
	scrollingFeatures.css("left", "-" + width + "px");
	
	scrollingFeatures.animate({
		"left": "+=" + width
	}, 600, function() {
		scrollingFeatures.css("left", 0);
	});
}
