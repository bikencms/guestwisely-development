#!/bin/bash

# ***************************************************************** #
# For publishing the plugin files.				   					#
# It excludes files that aren't required for the published plugin.	#
# Full file paths should be used.				   					#
# Requires rsync and zip											#
# eg. ./publish.sh frompath topath foldername						#
# foldername should be the plugin name. ie. "villas-365"			#
# ***************************************************************** #

frompath=$1
topath=$2
foldername=$3

ulimit -n 7168 # Set the max open file limit as it is 256 when run from cron.

if [[ "$topath/$foldername" != "" && "$topath/$foldername" != "." && "$topath/$foldername" != "/" && "$topath/$foldername" != "./" ]]; then
	if [[ -d "$topath/$foldername/.git" ]]; then
		# Move the .git folder out and delete the current files.
		mv "$topath/$foldername/.git" "$topath/$foldername_.git"
	fi

	# This uses the trash command from https://github.com/morgant/tools-osx
	# It could be replaced with rm -rf if you don't have it.
	trash "$topath/$foldername"

	# Copy the new files.
	rsync -rtu --delete "$frompath" "$topath" --exclude "source" --exclude ".DS_Store" --exclude "__MACOSX" --exclude ".git" --exclude "Thumbs.db" --exclude "*node_modules*" --exclude "build.mjs" --exclude "package-lock.json" --exclude "package.json" --exclude "postcss.config.js" --exclude "publish.sh" --exclude "webpack.mix.js"
	cd "$topath"
	zip -rq "$foldername.zip" $foldername/* -x \*.DS_Store __MACOSX \*.git\* Thumbs.db \*.gitignore
	mv "$topath/$foldername.zip" "$topath/$foldername/$foldername.zip"

	if [[ -d "$topath/$foldername_.git" ]]; then
		# Put the .git folder back.
		mv "$topath/$foldername_.git" "$topath/$foldername/.git"
	fi
fi

echo "Files copied ready to be pushed to source control '$topath/$foldername'"