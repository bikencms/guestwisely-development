jQuery(document).ready(function() {
	jQuery('.banner-carousel').one("slide.bs.carousel", function () {
		jQuery("._villas-365-property-header").fadeOut();
	});

	jQuery('#_villas-365-property-header-close-button').one("click", function () {
		jQuery("._villas-365-property-header").fadeOut();
	});

	if(typeof(_Villas365LazyLoadImages) !== "undefined")
	{
		jQuery('#property-banner').on('slide.bs.carousel', function(event) {	
			let lazyLoadImageElements = jQuery(this).find("._villas-365-lazy-load-image");

			lazyLoadImageElements.each(function() {
				_Villas365LazyLoadImages(this);
			});
		});
	}
});