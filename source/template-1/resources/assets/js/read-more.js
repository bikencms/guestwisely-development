jQuery(document).ready(function() {
	var readMoreOuter = jQuery('._read-more-outer');
	var readMoreInner = jQuery('._read-more-inner');
	var readMoreButton = jQuery('._read-more-more-button');

	var readMoreInitialHeight = readMoreOuter.data("initialHeight");
	if(typeof(readMoreInitialHeight) == "undefined")
	{
		readMoreInitialHeight = 300;
	}

	if(readMoreInner.height() <= (readMoreInitialHeight + 100))
	{
		readMoreOuter.css("height", "auto");
		readMoreButton.css("display", "none");
	}
	else
	{
		readMoreOuter.css("height", readMoreInitialHeight + "px");
		readMoreButton.css("display", "block");

		readMoreButton.on("click", function (event) {
			var readMoreInnerHeight = readMoreInner.height();
			var button = jQuery(this);
			if(readMoreOuter.data("collapsed"))
			{
				readMoreOuter.animate({
					height: readMoreInnerHeight
				}, readMoreInitialHeight);

				readMoreOuter.data("collapsed", false);
				button.removeClass("collapsed");

				var rotateItem180 = button.find(".rotate");
				var rotateClass = button.data("rotateClass");
				if(typeof(rotateClass) == "undefined")
				{
					rotateClass = "rotate-180";
				}
				rotateItem180.removeClass("rotate-0");
				rotateItem180.addClass(rotateClass);
			}
			else
			{
				readMoreOuter.animate({
					height: readMoreInitialHeight + "px"
				}, readMoreInitialHeight);

				readMoreOuter.data("collapsed", true);
				button.addClass("collapsed");
				
				var rotateItem180 = button.find(".rotate");
				var rotateClass = button.data("rotateClass");
				if(typeof(rotateClass) == "undefined")
				{
					rotateClass = "rotate-180";
				}
				rotateItem180.removeClass(rotateClass);
				rotateItem180.addClass("rotate-0");
			}
		});
	}
});