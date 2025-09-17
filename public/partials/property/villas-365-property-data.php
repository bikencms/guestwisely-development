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

//Get the property
$property = $property_atts["property"];

$readmore = false;
$readmoreHeight = "180";
if(array_key_exists("readmore", $property_atts) &&
	!is_null($property_atts["readmore"]) &&
	((!is_bool($property_atts["readmore"]) && $property_atts["readmore"] == "true") || (is_bool($property_atts["readmore"]) && $property_atts["readmore"])))
{
	$readmore = true;

	if(array_key_exists("readmoreheight", $property_atts) &&
	!is_null($property_atts["readmoreheight"]) &&
	($property_atts["readmoreheight"] !== "") &&
	is_numeric($property_atts["readmoreheight"]))
	{
		$readmoreHeight = $property_atts["readmoreheight"];
	}

	wp_enqueue_style('_villas-365-read-more-styles', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-read-more.css", [], VILLAS_365_VERSION);
	wp_enqueue_script('_villas-365-read-more-scripts', plugin_dir_url( __FILE__ ) . '../../assets/js/villas-365-read-more.js', ['jquery'], VILLAS_365_VERSION, true);
}

if(!is_null($sectionData["value"])) :
?>
	<?php if($readmore) : ?>
	<div class="_read-more">
		<div class="_read-more-outer" data-collapsed="true" data-initial-height="<?php echo esc_html($readmoreHeight); ?>">
			<div class="_read-more-inner">
				<<?php echo $sectionData["tag"]; ?> class="_villas-365-property-<?php echo $sectionData["name"]; ?>"><?php echo $sectionData["value"]; ?></<?php echo $sectionData["tag"]; ?>>
			</div>
		</div>
		<div class="_read-more-more-button clearfix collapsed" data-rotate-class="rotate-90r">
			<span class="hide-on-show"><?php __v3te("More"); ?></span><span class="show-on-show"><?php __v3te("Less"); ?></span>
			<span class="button-icon"><i class="fas fa-chevron-right rotate"></i></span>
		</div>
	</div>
	<?php else : ?>
	<?php if ($sectionData["tag"] == 'h1' && $sectionData["name"] == 'name' && !empty($sectionData['seoValue'])) { ?>
		<<?php echo $sectionData["tag"]; ?> class="_villas-365-property-<?php echo $sectionData["name"]; ?>" style="display: none;"><?php echo $sectionData["seoValue"]; ?></<?php echo $sectionData["tag"]; ?>>
		<h2 class="_villas-365-property-<?php echo $sectionData["name"]; ?>"><?php echo $sectionData["value"]; ?></h2>
	<?php } else { ?>
		<<?php echo $sectionData["tag"]; ?> class="_villas-365-property-<?php echo $sectionData["name"]; ?>"><?php echo $sectionData["value"]; ?></<?php echo $sectionData["tag"]; ?>>
	<?php } ?>
	<?php endif; ?>
<?php endif; ?>