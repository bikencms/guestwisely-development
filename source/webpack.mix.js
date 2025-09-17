let mix = require('laravel-mix');
const publicPath = "../public/assets";

mix.setPublicPath(publicPath);

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// Styles (all styles are in one folder so we don't end up including them twice if we use multiple shortcodes on a page)
mix.scripts([
		'resources/assets/libs/chosen/chosen.js',
		'resources/assets/libs/matchHeight.js',
		'resources/assets/js/utilities.js',
		'resources/assets/js/properties.js',
		'resources/assets/js/search.js',
	], publicPath + '/js/villas-365-scripts.js')
	.sass('resources/assets/sass/styles.scss', publicPath + '/css/villas-365-styles.css')
.options({
	processCssUrls: false
});

// Font Awesome
mix.sass('resources/assets/sass/fontawesome.scss', publicPath + '/css/villas-365-fontawesome.css')
.options({
	processCssUrls: false
});

// Fancy Box
mix.scripts([
	'resources/assets/libs/fancybox/fancybox.js'
], publicPath + '/js/villas-365-fancybox.js');

// Date picker
mix.scripts([
		'resources/assets/libs/mobiscroll/js/mobiscroll.jquery.min.js',
		'resources/assets/libs/modernizr/inputtypes.js',
		'resources/assets/libs/moment.js',
		'resources/assets/js/datepicker.js',
	], publicPath + '/js/villas-365-datepicker.js')
	.sass('resources/assets/sass/datepicker.scss', publicPath + '/css/villas-365-datepicker.css')
.options({
	processCssUrls: false
});

// Chosen
mix.scripts([
	'resources/assets/libs/chosen/chosen.js'
], publicPath + '/js/villas-365-chosen.js');

// Read More
mix.scripts([
	'resources/assets/js/read-more.js',
], publicPath + '/js/villas-365-read-more.js')
.sass('resources/assets/sass/read-more.scss', publicPath + '/css/villas-365-read-more.css')
.options({
	processCssUrls: false
});

// Map
mix.scripts([
	'resources/assets/js/map-helpers.js'
], publicPath + '/js/villas-365-map-helpers.js')
.options({
	processCssUrls: false
});

// Property
mix.scripts([
	'resources/assets/js/property.js'
], publicPath + '/js/villas-365-property.js')
.sass('resources/assets/sass/property.scss', publicPath + '/css/villas-365-property.css')
.options({
	processCssUrls: false
});

// Property Banner
mix.scripts([
		'resources/assets/js/property-banner.js'
	], publicPath + '/js/villas-365-property-banner.js');

// Property Banner Custom
mix.scripts([
	'resources/assets/js/property-banner-custom.js'
], publicPath + '/js/villas-365-property-banner-custom.js')
	.sass('resources/assets/sass/property-banner-custom.scss', publicPath + '/css/villas-365-property-banner-custom.css')
.options({
	processCssUrls: false
});

// Property Image Scroller
mix.scripts([
	'resources/assets/js/property-image-scroller.js'
], publicPath + '/js/villas-365-property-image-scroller.js');

// Property Floater
mix.scripts([
	'resources/assets/js/property-quote-widget.js',
	'resources/assets/js/property-floater.js'
], publicPath + '/js/villas-365-property-floater.js')
	.sass('resources/assets/sass/floater.scss', publicPath + '/css/villas-365-property-floater.css')
.options({
	processCssUrls: false
});

// Property Quote Widget
mix.scripts([
	'resources/assets/js/property-quote-widget.js'
], publicPath + '/js/villas-365-property-quote-widget.js')
	.sass('resources/assets/sass/quote-widget.scss', publicPath + '/css/villas-365-property-quote-widget.css')
.options({
	processCssUrls: false
});

// Property Quote Widget Modal
mix.sass('resources/assets/sass/quote-widget-modal.scss', publicPath + '/css/villas-365-property-quote-widget-modal.css')
.options({
	processCssUrls: false
});

// Property Map
mix.scripts([
	'resources/assets/js/property-map.js'
], publicPath + '/js/villas-365-property-map.js')
	.sass('resources/assets/sass/property-map.scss', publicPath + '/css/villas-365-property-map.css')
.options({
	processCssUrls: false
});

// Property Virtual Tour
mix.sass('resources/assets/sass/property-virtual-tour.scss', publicPath + '/css/villas-365-property-virtual-tour.css')
.options({
	processCssUrls: false
});

// Property Rates
mix.sass('resources/assets/sass/property-rates.scss', publicPath + '/css/villas-365-property-rates.css')
.options({
	processCssUrls: false
});

// Property Amenities
mix.scripts([
	'resources/assets/js/property-amenities.js'
], publicPath + '/js/villas-365-property-amenities.js')
	.sass('resources/assets/sass/property-amenities.scss', publicPath + '/css/villas-365-property-amenities.css')
.options({
	processCssUrls: false
});

// Property Related
mix.scripts([
	'resources/assets/js/utilities.js',
	'resources/assets/js/property-related.js'
], publicPath + '/js/villas-365-property-related.js')
	.sass('resources/assets/sass/property-related.scss', publicPath + '/css/villas-365-property-related.css')
.options({
	processCssUrls: false
});

// Property Rooms
mix.sass('resources/assets/sass/property-rooms.scss', publicPath + '/css/villas-365-property-rooms.css')
.options({
	processCssUrls: false
});

// Property Policies
mix.scripts([
	'resources/assets/js/property-policies.js'
], publicPath + '/js/villas-365-property-policies.js');

// Property Reviews
mix.scripts([
	'resources/assets/js/property-reviews.js'
], publicPath + '/js/villas-365-property-reviews.js');

// Property Booking
mix.sass('resources/assets/sass/property-booking.scss', publicPath + '/css/villas-365-booking.css')
.options({
	processCssUrls: false
});

// Property Save
mix.scripts([
	'resources/assets/js/property-save.js'
], publicPath + '/js/villas-365-property-save.js')
.sass('resources/assets/sass/property-save.scss', publicPath + '/css/villas-365-property-save.css')
.options({
	processCssUrls: false
});

// Saved Properties
mix.scripts([
	'resources/assets/js/saved-properties.js'
], publicPath + '/js/villas-365-saved-properties.js')
.sass('resources/assets/sass/saved-properties.scss', publicPath + '/css/villas-365-saved-properties.css')
.options({
	processCssUrls: false
});

// Properties
mix.scripts([
		'resources/assets/libs/matchHeight.js',
		'resources/assets/js/utilities.js',
		'resources/assets/js/properties.js'
	], publicPath + '/js/villas-365-properties-list.js')
	.sass('resources/assets/sass/properties-list.scss', publicPath + '/css/villas-365-properties-list.css')
.options({
	processCssUrls: false
});

// Properties Map
mix.scripts([
		'resources/assets/js/properties-map.js'
	], publicPath + '/js/villas-365-properties-map.js');

// Properties Featured
mix.sass('resources/assets/sass/featured-properties.scss', publicPath + '/css/villas-365-featured-properties.css')
.options({
	processCssUrls: false
});

// Search
mix.scripts([
		'resources/assets/js/utilities.js',
		'resources/assets/js/search.js'
	], publicPath + '/js/villas-365-search.js')
.sass('resources/assets/sass/search.scss', publicPath + '/css/villas-365-search.css')
.options({
	processCssUrls: false
});

// Category Grid
mix.sass('resources/assets/sass/category-grid.scss', publicPath + '/css/villas-365-category-grid.css')
.options({
	processCssUrls: false
});

// Reviews
mix.scripts([
	'resources/assets/js/reviews.js'
], publicPath + '/js/villas-365-reviews.js');

// Language Switcher
mix.sass('resources/assets/sass/language-switcher.scss', publicPath + '/css/villas-365-language-switcher.css')
.options({
	processCssUrls: false
});

// Color Overrides
mix.sass('resources/assets/sass/color-overrides.scss', publicPath + '/css/villas-365-color-overrides.css')
.options({
	processCssUrls: false
});

//Version files
mix.version([
	publicPath + "/img"
]);
