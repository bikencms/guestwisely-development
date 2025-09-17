var _floaterTopPadding = 0;
var _floaterScrollPoint = 0;
var _footerScrollTop = null;

jQuery(document).ready(function() {
	calculateFloaterOffsets();
	positionFloater();
	jQuery(window).on("scroll", function() {
		if((jQuery(window).width() >= 992) && (jQuery(window).height() >= 700))
		{
			positionFloater();
		}
	});
	jQuery(window).on("resize", function() {
		if((jQuery(window).width() >= 992) && (jQuery(window).height() >= 700))
		{
			calculateFloaterOffsets();
			positionFloater();
		}
		else if(jQuery(window).height() < 700)
		{
			removeFloater();
		}
	});
});

function calculateFloaterOffsets()
{
	let floaterAnchor = jQuery("#_villas-365-property-floater-anchor");
	
	let mainHeader = jQuery("#main-header");
	let mainHeadersHeight = 0;
	if(mainHeader.length > 0)
	{
		mainHeadersHeight = mainHeader.height();
	}
	else
	{
		let mainHeaders = jQuery("._villas-365-main-header");
		if(mainHeaders.length > 0)
		{
			mainHeaders.each(function() {
				mainHeadersHeight += jQuery(this).height();
			});
		}
		else
		{
			let defaultHeader = jQuery("header");
			if(defaultHeader.length > 0)
			{
				mainHeadersHeight = defaultHeader.height();
			}
		}
	}

	let secondaryHeadersHeight = 0;
	let secondaryHeaders = jQuery("._villas-365-secondary-header");
	secondaryHeaders.each(function() {
		secondaryHeadersHeight += jQuery(this).height();
	});

	let wordpressAdminBar = jQuery("#wpadminbar");
	let wordpressAdminBarHeight = 0;
	if(wordpressAdminBar.length > 0)
	{
		wordpressAdminBarHeight = wordpressAdminBar.height();
	}

	_floaterTopPadding = mainHeadersHeight + secondaryHeadersHeight + wordpressAdminBarHeight + 60;
	_floaterScrollPoint = floaterAnchor.offset().top - _floaterTopPadding;
}

function positionFloater()
{
	let floater = jQuery("#_villas-365-property-floater");
	let floaterContent = floater.find("._villas-365-property-floater-content");
	let currentScrollTop = jQuery(document).scrollTop();
	
	if(jQuery(document).scrollTop() > _floaterScrollPoint)
	{
		let floaterOffsetBottom = floater.offset().top + floaterContent.height();
		let footerTopOffset = jQuery("footer").offset().top - 50;
		if(((floaterOffsetBottom > footerTopOffset) || (_footerScrollTop != null)) && ((_footerScrollTop == null) || (currentScrollTop >= _footerScrollTop)))
		{
			if(_footerScrollTop == null)
			{
				_footerScrollTop = currentScrollTop;
			}

			floater.css("top", ((_floaterTopPadding - 30) - (currentScrollTop - _footerScrollTop)) + "px");
		}
		else
		{
			_footerScrollTop = null;
			floater.addClass("float");
			floater.css("top", (_floaterTopPadding - 30) + "px");
		}
	}
	else
	{
		floater.removeClass("float");
		floater.css("top", "-30px");
	}
}

function removeFloater()
{
	var floater = jQuery("#_villas-365-property-floater");
	floater.removeClass("float");
	floater.css("top", "-30px");
}