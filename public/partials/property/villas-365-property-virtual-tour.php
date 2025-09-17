<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://loyaltymatters.co.uk
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/public/partials
 */

wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", [], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-property-virtual-tour-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-property-virtual-tour.css", ["_villas-365-styles"], VILLAS_365_VERSION);

$lightbox = false;
if(array_key_exists("lightbox", $property_atts) &&
	!is_null($property_atts["lightbox"]) &
	((!is_bool($property_atts["lightbox"]) && $property_atts["lightbox"] == "true") || (is_bool($property_atts["lightbox"]) && $property_atts["lightbox"])))
{
	$lightbox = true;

	wp_enqueue_style('_villas-365-fancybox-styles', VILLAS_365_PLUGIN_URL . "public/assets/libs/fancybox/fancybox.css", [], "3.5.7");
	wp_enqueue_script('_villas-365-fancybox', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-fancybox.js", ["jquery", "_villas-365-scripts"], "3.5.7", true);
}

$textOnly = false;
if(array_key_exists("textonly", $property_atts) &&
	!is_null($property_atts["textonly"]) &
	((!is_bool($property_atts["textonly"]) && $property_atts["textonly"] == "true") || (is_bool($property_atts["textonly"]) && $property_atts["textonly"])))
{
	$textOnly = true;

	wp_enqueue_style('_villas-365-fontawesome', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-fontawesome.css", [], "5.9.0");
}

$showImageWhenNoVideo = true;
if(array_key_exists("showimagewhennovideo", $property_atts) &&
	!is_null($property_atts["showimagewhennovideo"]) &
	((!is_bool($property_atts["showimagewhennovideo"]) && $property_atts["showimagewhennovideo"] == "false") || (is_bool($property_atts["showimagewhennovideo"]) && !$property_atts["showimagewhennovideo"])))
{
	$showImageWhenNoVideo = false;
}

$embed = false;
$externalVideo = null;
if(array_key_exists("embed", $property_atts) &&
	!is_null($property_atts["embed"]) &
	((!is_bool($property_atts["embed"]) && $property_atts["embed"] == "true") || (is_bool($property_atts["embed"]) && $property_atts["embed"])))
{
	if(!is_null($property) && !is_null($property->property_video) && (trim($property->property_video) !== ""))
	{
		$externalVideo = Helpers365::ParseVideoUrl($property->property_video);
		if(!is_null($externalVideo["video_id"]) && (($externalVideo["type"] == "youtube") || ($externalVideo["type"] == "vimeo")))
		{
			$embed = true;
		}
	}

	if($embed && !is_null($externalVideo))
	{
		$externalVideoScript = null;
		if($externalVideo["type"] == "youtube")
		{
			wp_enqueue_script('_villas-365-youtube-javascript-api', "https://www.youtube.com/iframe_api", [], "1.0.0", true);

			//This code loads the YouTube iframe Player API code asynchronously.
			$externalVideoScript = "var youtubePlayer;" .
				"function onYouTubeIframeAPIReady() {" .
					"youtubePlayer = new YT.Player('_villas-365-property-virtual-tour-video-player', {" .
						"height: '390'," .
						"width: '640'," .
						"videoId: '" . esc_html($externalVideo["video_id"]) . "'," .
						"playerVars: {" .
							"'rel': 0," .
							"'modestbranding': 1," .
							"'wmode': 'transparent'" .
						"}," .
					"});" .
				"};";

			wp_add_inline_script('_villas-365-youtube-javascript-api', $externalVideoScript, 'after');
		}
	}
}

//Get the property
$property = $property_atts["property"];

if(!is_null($property)) :
	$propertyName = Helpers365::Get365TranslationValue($property, ["name"], ["languages", "name"]);
	if (!is_null($property->property_video) && (trim($property->property_video) !== "")) : ?>
		<div class="_villas-365-bootstrap _villas-365-property-image-virtual-tour">
			<?php if($lightbox) : ?>
			<span class="cursor-pointer" data-fancybox="data-fancybox="_property-images-fancybox" data-src="<?php echo esc_html($property->property_video); ?>">
				<?php if($textOnly) : ?>
				<i class="fas fa-video"></i>&nbsp;&nbsp;<?php __v3te("View Video"); ?>
				<?php else: ?>
				<img src="<?php echo esc_html($property->image_path); ?>" alt="<?php echo (!is_null($propertyName) && (trim($propertyName) !== "") ? $propertyName : ''); ?>">
				<?php endif; ?>
			</span>
			<?php elseif($embed) : ?>
				<?php if(!is_null($externalVideo["type"]) == "vimeo"): ?>
					<div id="_villas-365-property-virtual-tour-video-player">
						<iframe src="//player.vimeo.com/video/<?php echo esc_html($externalVideo["video_id"]) ?>?badge=0&portrait=0&title=0&byline=0"
							width="640" height="390" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					</div>
				<?php else: ?>
					<div id="_villas-365-property-virtual-tour-video-player"></div>
				<?php endif; ?>
			<?php else: ?>
			<a href="<?php echo esc_html($property->property_video); ?>" target="_blank" rel="noopener"><?php if($textOnly) : ?>
				<i class="fas fa-video"></i>&nbsp;&nbsp;<?php __v3te("View Video"); ?>
				<?php else: ?>
				<img src="<?php echo esc_html($property->image_path); ?>" alt="<?php echo (!is_null($propertyName) && (trim($propertyName) !== "") ? $propertyName : ''); ?>">
				<?php endif; ?></a>
			<?php endif; ?>
			<?php if(!$textOnly && !$embed) : ?>
			<div class="_property-image-overlay">
				<div class="_property-image-overlay-content">
					<div class="_property-image-overlay-image">
						<i class="fas fa-video"></i>
					</div>
					<div class="_property-image-overlay-text"><?php __v3te("View Video"); ?></div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	<?php elseif(!$textOnly && $showImageWhenNoVideo) : ?>
		<div class="_villas-365-bootstrap _villas-365-property-image-virtual-tour">
			<img src="<?php echo esc_html($property->image_path); ?>" alt="<?php echo (!is_null($propertyName) && (trim($propertyName) !== "") ? $propertyName : ''); ?>" class="img-fluid">
		</div>
	<?php endif; ?>
<?php endif; ?>