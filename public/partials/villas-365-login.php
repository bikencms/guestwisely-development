<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Villas_365
 * @subpackage Villas_365/public/partials
 */

wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../assets/css/villas-365-styles.css", ["_villas-365-property"], VILLAS_365_VERSION);

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides();
wp_add_inline_style('_villas-365-styles', $villas365CSSOverrides);

$colours = [
	"buttonColour" => Helpers365::GetOption("_template_color_buttonColour", "villas-365", false),
	"loginBackgroundColour" => Helpers365::GetOption("_template_color_loginBackgroundColour", "villas-365", false)
];

$defaultColors = Helpers365::GetDefaultColors();

//Set any defaults.
if(($colours["buttonColour"] === FALSE) || (is_null($colours["buttonColour"])) || ($colours["buttonColour"] == ""))
{
	$colours["buttonColour"] = $defaultColors["buttonColour"];
}

if(($colours["loginBackgroundColour"] === FALSE) || (is_null($colours["loginBackgroundColour"])) || ($colours["loginBackgroundColour"] == ""))
{
	$colours["loginBackgroundColour"] = $defaultColors["loginBackgroundColour"];
}

$villas365ButtonColour = str_replace("#", "", $colours["buttonColour"]);
$villas365LoginBackgroundColour = str_replace("#", "", $colours["loginBackgroundColour"]);
?>

<div class="login-container">
	<iframe width="100%" height="100%" style="width: 100%; height: 700px;"
		src="<?php esc_html_e(Helpers365::BaseAppURL()); ?>/home/ownerlogin/maincolor/<?php echo $villas365LoginBackgroundColour; ?>/lightcolor/<?php echo $villas365ButtonColour; ?>"
		scrolling="yes" frameborder="0"></iframe>
</div>