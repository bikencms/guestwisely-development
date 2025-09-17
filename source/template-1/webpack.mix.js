let mix = require('laravel-mix');
const publicPath = "../../public/assets/template-1";

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
		'resources/assets/js/properties.js',
		'resources/assets/js/search.js',
	], publicPath + '/js/villas-365-scripts.js')
	.sass('resources/assets/sass/styles.scss', publicPath + '/css/villas-365-styles.css')
.options({
	processCssUrls: false
});

mix.scripts([
		'resources/assets/libs/datepicker/datepicker.js',
		'resources/assets/libs/modernizr/inputtypes.js',
		'resources/assets/libs/moment.js',
		'resources/assets/js/datepicker.js',
	], publicPath + '/js/villas-365-datepicker.js')
	.styles([
		'resources/assets/libs/datepicker/datepicker.css'
	], publicPath + '/css/villas-365-datepicker.css')
.options({
	processCssUrls: false
});

// Properties
mix.scripts([
		'resources/assets/js/map-helpers.js',
		'resources/assets/js/properties-list.js'
	], publicPath + '/js/villas-365-properties-list.js')
	.sass('resources/assets/sass/properties-list.scss', publicPath + '/css/villas-365-properties-list.css')
.options({
	processCssUrls: false
});

// Property
mix.scripts([
		'resources/assets/js/read-more.js',
		'resources/assets/js/map-helpers.js',
		'resources/assets/js/property-map.js',
		'resources/assets/js/property.js'
	], publicPath + '/js/villas-365-property.js')
	.sass('resources/assets/sass/featured-properties.scss', publicPath + '/css/villas-365-featured-properties.css')
.options({
	processCssUrls: false
});

// Property
mix.sass('resources/assets/sass/property.scss', publicPath + '/css/villas-365-property.css')
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
