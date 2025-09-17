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

if(!is_null($property)) :
	wp_enqueue_script('_villas-365-property-reviews-external-scripts', Helpers365::BaseAppURL() . "/widget/customer-review.js", ["jquery"], VILLAS_365_VERSION, true);
	wp_enqueue_script('_villas-365-property-reviews-scripts', plugin_dir_url( __FILE__ ) . "../../assets/js/villas-365-property-reviews.js", ["jquery", "_villas-365-property-reviews-external-scripts"], VILLAS_365_VERSION, true);

	$scripts365Options = '_365_owner = "' . get_option("villas-365_owner_username_365_api") . '";' .
		'_365_owner_token = "' . get_option("villas-365_owner_key_365_api") . '";' .
		'_365_language = "' . Helpers365::Get365LanguageCode() . '";' .
		'_365_property_id = "' . $property->id . '";' .
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

	wp_add_inline_script('_villas-365-property-reviews-external-scripts', $scripts365Options, "before");
?>
<div class="_villas-365-property-reviews">
</div>
<?php endif; ?>