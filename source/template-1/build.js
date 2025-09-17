//Node version 13
var del = require('./node_modules/del');
var fs = require('./node_modules/fs-extra');
var mkpath = require('./node_modules/mkpath');
var modernizr = require('./node_modules/modernizr');

var resourcePath = "resources/assets";
var publicPath = "../../public/assets/template-1";

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
	fs.copySync("node_modules/jquery-match-height/dist/jquery.matchHeight.js", resourcePath + "/libs/matchHeight.js");
	fs.copySync("node_modules/bootstrap/dist/js/bootstrap.min.js", resourcePath + "/libs/bootstrap.js");
	fs.copySync("node_modules/moment/min/moment.min.js", resourcePath + "/libs/moment.js");
	fs.copySync("node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js", resourcePath + "/libs/datepicker/datepicker.js");
	fs.copySync("node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css", resourcePath + "/libs/datepicker/datepicker.css");

	fs.copySync("node_modules/chosen-js/chosen.jquery.js", resourcePath + "/libs/chosen/chosen.js");
	fs.copySync("node_modules/chosen-js/chosen.min.css", resourcePath + "/libs/chosen/chosen.css");
	fs.copySync("node_modules/chosen-js/chosen-sprite.png", resourcePath + "/libs/chosen/chosen-sprite.png");
	fs.copySync("node_modules/chosen-js/chosen-sprite@2x.png", resourcePath + "/libs/chosen/chosen-sprite@2x.png");
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
		var path = resourcePath + "/libs/modernizr";
		mkpath.sync(path, 0755);
		fs.writeFileSync(path + "/inputtypes.js", file);
	});

	//Copy all static resources
	console.log("Copying static resources...");
	fs.copySync(resourcePath + "/img", publicPath + "/img"); //Copy all images
	fs.copySync(resourcePath + "/libs", publicPath + "/libs"); //Copy all libs
	console.log("Static resources copied!");
}

cleanup();
copyFiles();
