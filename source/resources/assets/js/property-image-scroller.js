jQuery(document).ready(function() {
	jQuery("#_property-images-scrolling-features-scroll-left").on("click", function(event){
		event.preventDefault();

		if(_villas365PropertyImagesScrollerShouldScroll())
		{
			_villas365PropertyImagesScrollerScrollLeft();
		}
	});
	
	jQuery("#_property-images-scrolling-features-scroll-right").on("click", function(event){
		event.preventDefault();

		if(_villas365PropertyImagesScrollerShouldScroll())
		{
			_villas365PropertyImagesScrollerScrollRight();
		}
	});

	jQuery("#_property-images-scrolling-features .property-images-link").on("click", function(event) {
		_villas365PropertyImagesScrollerClickImage(event, jQuery(this));
	});

	if(typeof(_Villas365LazyLoadImages) !== "undefined")
	{
		jQuery("#_property-images-scrolling-features-scroll-left, #_property-images-scrolling-features-scroll-right").one("click", function(event){
			let lazyLoadImageElements = jQuery("#_property-images-scrolling-container ._villas-365-lazy-load-image");

			lazyLoadImageElements.each(function() {
				_Villas365LazyLoadImages(this);
			});
		});
	}
});

function _villas365PropertyImagesScrollerShouldScroll()
{
	var totalWidth = 0;
	jQuery("ul#_property-images-scrolling-features>li").each(function(){
		totalWidth += (jQuery(this).width() + 10);
	});

	return totalWidth > jQuery("#_property-images-scrolling-container").first().width();
}

function _villas365PropertyImagesScrollerScrollRight()
{
	var scrollingFeatures = jQuery("ul#_property-images-scrolling-features");
	var firstElement = jQuery("ul#_property-images-scrolling-features>li").first();

	firstElement.remove();
	scrollingFeatures.append(firstElement);
	scrollingFeatures.css("left", 0);

	firstElement.off("click").on("click", function(event) {
		_villas365PropertyImagesScrollerClickImage(event, jQuery(this).find(".property-images-link"));
	});
}

function _villas365PropertyImagesScrollerScrollLeft()
{
	var scrollingFeatures = jQuery("ul#_property-images-scrolling-features");
	var lastElement = jQuery("ul#_property-images-scrolling-features>li").last();

	lastElement.remove();
	scrollingFeatures.prepend(lastElement);

	lastElement.off("click").on("click", function(event) {
		_villas365PropertyImagesScrollerClickImage(event, jQuery(this).find(".property-images-link"));
	});
}

function _villas365PropertyImagesScrollerClickImage(event, element)
{
	event.preventDefault();
	var imageKey = element.data("imageKey");
	jQuery(".banner-carousel").carousel(imageKey);
}
