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

$script = "/widget/general-olb.js";
$advanced = false;
if(array_key_exists("advanced", $booking_atts) &&
	!is_null($booking_atts["advanced"]) &&
	((!is_bool($booking_atts["advanced"]) && $booking_atts["advanced"] == "true") || (is_bool($booking_atts["advanced"]) && $booking_atts["advanced"])))
{
	$advanced = true;
	$script = "/widget/bcalendar-more.js";
}
$script = Helpers365::BaseAppURL() . $script;

$customStyle = null;
if(isset($booking_atts) && (count($booking_atts) > 0) && array_key_exists("style", $booking_atts) && !is_null($booking_atts["style"]) && (trim($booking_atts["style"]) !== ""))
{
	$customStyle = $booking_atts["style"];
}

$singleProperty = false;
if(array_key_exists("singleproperty", $booking_atts) &&
	!is_null($booking_atts["singleproperty"]) &&
	((!is_bool($booking_atts["singleproperty"]) && $booking_atts["singleproperty"] == "true") || (is_bool($booking_atts["singleproperty"]) && $booking_atts["singleproperty"])))
{
	$singleProperty = true;
}

$displayMonths = null;
if(isset($booking_atts) && (count($booking_atts) > 0) && array_key_exists("displaymonths", $booking_atts) && !is_null($booking_atts["displaymonths"]) && (trim($booking_atts["displaymonths"]) !== ""))
{
	$displayMonths = $booking_atts["displaymonths"];
}

$showDiscounts = false;
if(array_key_exists("showdiscounts", $booking_atts) &&
	!is_null($booking_atts["showdiscounts"]) &&
	((!is_bool($booking_atts["showdiscounts"]) && $booking_atts["showdiscounts"] == "true") || (is_bool($booking_atts["showdiscounts"]) && $booking_atts["showdiscounts"])))
{
	$showDiscounts = true;
}

$includeBeyondPricing = false;
if(array_key_exists("includebeyondpricing", $booking_atts) &&
	!is_null($booking_atts["includebeyondpricing"]) &&
	((!is_bool($booking_atts["includebeyondpricing"]) && $booking_atts["includebeyondpricing"] == "true") || (is_bool($booking_atts["includebeyondpricing"]) && $booking_atts["includebeyondpricing"])))
{
	$includeBeyondPricing = true;
}
?>

<div class="booking-container">
	<?php
	   $_365_confirm_booking_page = get_option("villas-365_365_setting_confirm_booking_page","");
	   if(isset($_365_confirm_booking_page) && !empty($_365_confirm_booking_page)) {
		   $_365_page_confirm_link = get_page_link($_365_confirm_booking_page);
	   }
	?>
	<script>
		_365_owner = "<?php echo get_option("villas-365_owner_username_365_api") ?>";
		_365_owner_token = "<?php echo get_option("villas-365_owner_key_365_api") ?>";
		_365_language = "<?php echo Helpers365::Get365LanguageCode(); ?>";

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

		<?php if(isset($_GET) && is_array($_GET) && (count($_GET) > 0)) : ?>
			<?php if(array_key_exists("property", $_GET) && !is_null($_GET["property"]) && is_numeric($_GET["property"])) : ?>
			_365_property_id = '<?php echo esc_html($_GET["property"]) ?>';

				<?php if($singleProperty) : ?>
					_365_one_property_only = '<?php echo esc_html($_GET["property"]) ?>';
				<?php endif; ?>

			<?php endif; ?>

			<?php if(array_key_exists("mode", $_GET) && !is_null($_GET["mode"])) : ?>
			_365_button_mode  = '<?php echo esc_html($_GET["mode"]) ?>';
			<?php endif; ?>

			<?php if(array_key_exists("checkin", $_GET) && !is_null($_GET["checkin"])) : ?>
			_365_start_date = '<?php echo esc_html($_GET["checkin"]) ?>';
			<?php endif; ?>

			<?php if(array_key_exists("checkout", $_GET) && !is_null($_GET["checkout"])) : ?>
			_365_end_date  = '<?php echo esc_html($_GET["checkout"]) ?>';
			<?php endif; ?>

			<?php if(array_key_exists("adults", $_GET) && !is_null($_GET["adults"]) && is_numeric($_GET["adults"])) : ?>
			_365_adults  = '<?php echo esc_html($_GET["adults"]) ?>';
			<?php endif; ?>

			<?php if(array_key_exists("children", $_GET) && !is_null($_GET["children"]) && is_numeric($_GET["children"])) : ?>
			_365_children  = '<?php echo esc_html($_GET["children"]) ?>';
			<?php else: ?>
			_365_children  = '0';
			<?php endif; ?>
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

		_365_domain = '<?php echo get_option('home'); ?>';
		
		function _Villas365BookingCookieRead(name)
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

		var villas365VRMemberID = _Villas365BookingCookieRead("<?php echo Helpers365::VR_MEMBER_ID_COOKIE_NAME; ?>");
		if(villas365VRMemberID != null)
		{
			_365_vr_member_id = _Villas365BookingCookieRead("<?php echo Helpers365::VR_MEMBER_ID_COOKIE_NAME; ?>");
		}
	</script>
	<script src="<?php esc_html_e($script); ?>"></script>
</div>