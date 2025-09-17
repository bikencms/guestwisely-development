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
	wp_enqueue_style('_villas-365-property-booking', plugin_dir_url( __FILE__ ) . "../../assets/css/villas-365-booking.css", [], VILLAS_365_VERSION);

	$script = "/widget/bcalendar.js";
	$advanced = false;
	if(array_key_exists("advanced", $property_atts) &&
		!is_null($property_atts["advanced"]) &&
		((!is_bool($property_atts["advanced"]) && $property_atts["advanced"] == "true") || (is_bool($property_atts["advanced"]) && $property_atts["advanced"])))
	{
		$advanced = true;
		$script = "/widget/bcalendar-more.js";
	}
	$script = Helpers365::BaseAppURL() . $script;

	$customStyle = null;
	if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("style", $property_atts) && !is_null($property_atts["style"]) && (trim($property_atts["style"]) !== ""))
	{
		$customStyle = $property_atts["style"];
	}

	$singleProperty = false;
	if(array_key_exists("singleproperty", $property_atts) &&
		!is_null($property_atts["singleproperty"]) &&
		((!is_bool($property_atts["singleproperty"]) && $property_atts["singleproperty"] == "true") || (is_bool($property_atts["singleproperty"]) && $property_atts["singleproperty"])))
	{
		$singleProperty = true;
	}

	$displayMonths = null;
	if(isset($property_atts) && (count($property_atts) > 0) && array_key_exists("displaymonths", $property_atts) && !is_null($property_atts["displaymonths"]) && (trim($property_atts["displaymonths"]) !== ""))
	{
		$displayMonths = $property_atts["displaymonths"];
	}

	$showDiscounts = false;
	if(array_key_exists("showdiscounts", $property_atts) &&
		!is_null($property_atts["showdiscounts"]) &&
		((!is_bool($property_atts["showdiscounts"]) && $property_atts["showdiscounts"] == "true") || (is_bool($property_atts["showdiscounts"]) && $property_atts["showdiscounts"])))
	{
		$showDiscounts = true;
	}

	$includeBeyondPricing = false;
	if(array_key_exists("includebeyondpricing", $property_atts) &&
		!is_null($property_atts["includebeyondpricing"]) &&
		((!is_bool($property_atts["includebeyondpricing"]) && $property_atts["includebeyondpricing"] == "true") || (is_bool($property_atts["includebeyondpricing"]) && $property_atts["includebeyondpricing"])))
	{
		$includeBeyondPricing = true;
	}
?>
<div id="booking" class="_villas-365-property-booking">
	<script>
		_365_owner = "<?php echo get_option("villas-365_owner_username_365_api"); ?>";
		_365_owner_token = "<?php echo get_option("villas-365_owner_key_365_api"); ?>";
		_365_language = "<?php echo Helpers365::Get365LanguageCode(); ?>";
		_365_property_id = "<?php echo $property->id; ?>";

		_365_redirect_url = "";
		
		<?php
		   $_365_confirm_booking_page = get_option("villas-365_365_setting_confirm_booking_page","");
			 if(isset($_365_confirm_booking_page) && !empty($_365_confirm_booking_page)) {
			   $_365_page_confirm_link = get_page_link($_365_confirm_booking_page);
			}
			if(isset($_365_page_confirm_link) && !empty($_365_page_confirm_link)) {
               ?>
                 _365_redirect_url = "<?php echo $_365_page_confirm_link; ?>";
			   <?php
			}
		?>

		<?php if($includeBeyondPricing) : ?>
		_365_include_js = 'beyondpricing';
		<?php endif; ?>

		<?php if($singleProperty) : ?>
			_365_one_property_only = "<?php echo $property->id; ?>";
		<?php endif; ?>

		<?php if(!is_null($customStyle)) : ?>
			_365_custom_style = '<?php echo esc_html($customStyle) ?>';
		<?php endif; ?>

		<?php if(!is_null($displayMonths)) : ?>
			_365_number_month = '<?php echo esc_html($displayMonths) ?>';
		<?php endif; ?>

		<?php if($showDiscounts) : ?>
			_365_show_star_discount = 1;
		<?php endif; ?>

		function _Villas365PropertyBookingCookieRead(name)
		{
			var nameEquals = name + "=";
			var cookiesArray = document.cookie.split(';');

			for (var i = 0; i < cookiesArray.length; i++)
			{
				var cookie = cookiesArray[i];
				
				while (cookie.charAt(0) == ' ')
				{
					cookie = cookie.substring(1, cookie.length);
				}

				if (cookie.indexOf(nameEquals) == 0)
				{
					return cookie.substring(nameEquals.length, cookie.length);
				}
			}

			return null;
		}

		var villas365VRMemberID = _Villas365PropertyBookingCookieRead("<?php echo Helpers365::VR_MEMBER_ID_COOKIE_NAME; ?>");
		if(villas365VRMemberID != null)
		{
			_365_vr_member_id = _Villas365PropertyBookingCookieRead("<?php echo Helpers365::VR_MEMBER_ID_COOKIE_NAME; ?>");
		}

	</script>
	<script src="<?php esc_html_e($script); ?>"></script>
</div>
<?php endif; ?>