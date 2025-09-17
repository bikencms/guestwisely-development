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

$reviewsDisplayValidTypes = [
	"grid",
	"form"
];
$reviewsDisplayType = "grid";
if(isset($reviews_atts) && (count($reviews_atts) > 0) && array_key_exists("display", $reviews_atts) && is_string($reviews_atts["display"]) && (trim($reviews_atts["display"]) !== "") && in_array($reviews_atts["display"], $reviewsDisplayValidTypes))
{
	$reviewsDisplayType = $reviews_atts["display"];
}

if($reviewsDisplayType === "grid")
{
	wp_enqueue_script('_villas-365-property-reviews-external-grid-scripts', Helpers365::BaseAppURL() . "/widget/customer-review.js", ["jquery"], VILLAS_365_VERSION, true);
	wp_enqueue_script('_villas-365-reviews-scripts', plugin_dir_url( __FILE__ ) . "../assets/js/villas-365-reviews.js", ["jquery", "_villas-365-property-reviews-external-grid-scripts"], VILLAS_365_VERSION, true);
}
elseif($reviewsDisplayType === "form")
{
	wp_enqueue_script('_villas-365-property-reviews-external-form-scripts', Helpers365::BaseAppURL() . "/widget/customer-review-submit.js", ["jquery"], VILLAS_365_VERSION, true);
	wp_enqueue_script('_villas-365-reviews-scripts', plugin_dir_url( __FILE__ ) . "../assets/js/villas-365-reviews.js", ["jquery", "_villas-365-property-reviews-external-form-scripts"], VILLAS_365_VERSION, true);
}

$scripts365Options = '_365_owner = "' . get_option("villas-365_owner_username_365_api") . '";' .
	'_365_owner_token = "' . get_option("villas-365_owner_key_365_api") . '";' .
	'_365_language = "' . Helpers365::Get365LanguageCode() . '";' .
	'_365_review_page_grid = 1;' .
	'_365_manual_customer_review = 1;';

	$scripts365Options .= 'function _Villas365PropertyReviewsCookieRead(name)' .
	'{' .
		'var nameEquals = name + "=";' .
		'var cookiesArray = document.cookie.split(";");' .
		'for (var i = 0; i < cookiesArray.length; i++)' .
		'{' .
			'var cookie = cookiesArray[i];' .
			'while (cookie.charAt(0) == " ")' .
			'{' .
				'cookie = cookie.substring(1, cookie.length);' .
			'}' .
			'if (cookie.indexOf(nameEquals) == 0)' .
			'{' .
				'return cookie.substring(nameEquals.length, cookie.length);' .
			'}' .
		'}' .
		'return null;' .
	'}' .
	'var villas365VRMemberID = _Villas365PropertyReviewsCookieRead("<?php echo Helpers365::VR_MEMBER_ID_COOKIE_NAME; ?>");' .
	'if(villas365VRMemberID != null)' .
	'{' .
		'_365_vr_member_id = _Villas365PropertyReviewsCookieRead("<?php echo Helpers365::VR_MEMBER_ID_COOKIE_NAME; ?>");' .
	'}';

if($reviewsDisplayType === "grid"):
	$scripts365Options .= '_365_hide_review_submit_form = 1;';
	wp_add_inline_script('_villas-365-property-reviews-external-grid-scripts', $scripts365Options, "before");
?>
	<div class="_villas-365-property-reviews-grid"></div>
<?php elseif($reviewsDisplayType === "form"):
	$scripts365Options .= '_365_hide_review_list = 1;';
	wp_add_inline_script('_villas-365-property-reviews-external-form-scripts', $scripts365Options, "before");
?>
	<div class="_villas-365-property-reviews-form"></div>
<?php endif; ?>