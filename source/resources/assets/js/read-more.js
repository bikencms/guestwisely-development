jQuery(document).ready(function() {
	_villas365ReadMore();
});

function _villas365ReadMore(parentElement)
{
	let readMore;

	if(typeof(parentElement) !== "undefined")
	{
		readMore = parentElement.find('._read-more');
	}
	else
	{
		readMore = jQuery('._read-more');
	}

	readMore.each(function() {
		let currentReadMore = jQuery(this);

		let readMoreOuter = currentReadMore.find('._read-more-outer');
		let readMoreInner = currentReadMore.find('._read-more-inner');
		let readMoreButton = currentReadMore.find('._read-more-more-button');

		let readMoreInitialHeight = readMoreOuter.data("initialHeight");
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
				let readMoreInnerHeight = readMoreInner.height();
				let button = jQuery(this);
				if(readMoreOuter.data("collapsed"))
				{
					readMoreOuter.animate({
						height: readMoreInnerHeight
					}, readMoreInitialHeight);

					readMoreOuter.data("collapsed", false);
					button.removeClass("collapsed");

					let rotateItem180 = button.find(".rotate");
					let rotateClass = button.data("rotateClass");
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
					
					let rotateItem180 = button.find(".rotate");
					let rotateClass = button.data("rotateClass");
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
}