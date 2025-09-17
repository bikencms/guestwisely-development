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

if(!is_null($property) && property_exists($property, "policies") && !is_null($property->policies)) :

	$readmore = false;
	$readmoreHeight = "300";
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

		wp_enqueue_style('_villas-365-read-more-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-read-more.css", [], VILLAS_365_VERSION);
		wp_enqueue_script('_villas-365-read-more-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-read-more.js", ['jquery'], VILLAS_365_VERSION, true);
		wp_enqueue_script('_villas-365-policies-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-property-policies.js", ['jquery', '_villas-365-read-more-scripts'], VILLAS_365_VERSION, true);
	}
?>
<div class="_villas-365-bootstrap _villas-365-property-policies">
	<?php
	$propertyPolicies = Helpers365::Get365TranslationValue($property, ["policies"], ["languages", "policies"]);
	if($readmore) : ?>
	<div class="_read-more">
		<div class="_read-more-outer" data-collapsed="true" data-initial-height="<?php echo esc_html($readmoreHeight); ?>">
			<div class="_read-more-inner">
				<?php
				foreach($propertyPolicies as $key => $policy) : ?>
				<div class="_villas-365-property-policy">
					<h4 class="_villas-365-title _villas-365-property-policy-title"><?php __v3te(ucwords(str_replace("_", " ", $key))) ?></h4>
					<p><?php echo html_entity_decode(str_replace(PHP_EOL, "<br>", $policy)); ?></p>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="_read-more-more-button clearfix collapsed" data-rotate-class="rotate-90r">
			<span class="hide-on-show"><?php __v3te("More"); ?></span><span class="show-on-show"><?php __v3te("Less"); ?></span>
			<span class="button-icon"><i class="fas fa-chevron-right rotate"></i></span>
		</div>
	</div>
	<?php else :
		foreach($propertyPolicies as $key => $policy) : ?>
		<div class="_villas-365-property-policy">
			<h4 class="_villas-365-title _villas-365-property-policy-title"><?php __v3te(ucwords(str_replace("_", " ", $key))) ?></h4>
			<p><?php echo html_entity_decode(str_replace(PHP_EOL, "<br>", $policy)); ?></p>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<?php endif; ?>