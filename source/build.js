//Node version 13
var del = require('./node_modules/del');
var fs = require('./node_modules/fs-extra');
var mkpath = require('./node_modules/mkpath');
var modernizr = require('./node_modules/modernizr');

var publicPath = "../public/assets";

function cleanup()
{
	console.log("Running cleanup...");

	del.sync([
		publicPath,
		"resources/assets/sass/libs",
		"resources/assets/js/libs",
		"resources/assets/img/libs",

		//Delete the assets versioning manifest file
		publicPath + '/mix-manifest.json',
	], {
		force: true
	});

	console.log("Cleanup done!\n");
}

//Function to copy the scripts in known order and then build them with laravel-mix.
function copyFiles()
{
	//Copy libraries
	fs.copySync("node_modules/jquery-match-height/dist/jquery.matchHeight.js", "resources/assets/libs/matchHeight.js");
	fs.copySync("node_modules/bootstrap/dist/js/bootstrap.min.js", "resources/assets/libs/bootstrap.js");
	fs.copySync("node_modules/moment/min/moment.min.js", "resources/assets/libs/moment.js");
	fs.copySync("node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css", "resources/assets/libs/fancybox/fancybox.css");
	fs.copySync("node_modules/@fancyapps/fancybox/dist/jquery.fancybox.js", "resources/assets/libs/fancybox/fancybox.js");
	fs.copySync("node_modules/@fortawesome/fontawesome-free/webfonts", "resources/assets/fonts");
	
	fs.copySync("node_modules/chosen-js/chosen.jquery.js", "resources/assets/libs/chosen/chosen.js");
	fs.copySync("node_modules/chosen-js/chosen.min.css", "resources/assets/libs/chosen/chosen.css");
	fs.copySync("node_modules/chosen-js/chosen-sprite.png", "resources/assets/libs/chosen/chosen-sprite.png");
	fs.copySync("node_modules/chosen-js/chosen-sprite@2x.png", "resources/assets/libs/chosen/chosen-sprite@2x.png");
	console.log("Libraries copied!\n");

	//Build modernizr
	modernizr.build({
		"minify": false,
		"options": [
			"setClasses"
		],
		"feature-detects": [
			"test/inputtypes"
		]
	}, function(file) {
		var path = "resources/assets/libs/modernizr";
		mkpath.sync(path, 0755);
		fs.writeFileSync(path + "/inputtypes.js", file);
	});

	//Copy all static resources
	console.log("Copying static resources...");
	fs.copySync("resources/assets/img", publicPath + "/img"); //Copy all images
	fs.copySync("resources/assets/libs", publicPath + "/libs"); //Copy all libs
	fs.copySync("resources/assets/fonts", publicPath + "/fonts"); //Copy all fonts
	console.log("Static resources copied!");
}

cleanup();
copyFiles();
