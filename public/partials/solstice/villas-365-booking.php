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

wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../../assets/solstice/css/villas-365-styles.css", ["_villas-365-property"], "1.0");

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides(true);
wp_add_inline_style('_villas-365-styles', $villas365CSSOverrides);
?>

<div class="booking-container">
	<script>
		_365_owner = "<?php echo get_option("villas-365_owner_username_365_api") ?>";
		_365_language = "en";

		<?php if(isset($_GET) && is_array($_GET) && (count($_GET) > 0)) : ?>
			<?php if(array_key_exists("property", $_GET) && !is_null($_GET["property"]) && is_numeric($_GET["property"])) : ?>
			_365_property_id = '<?php echo esc_html($_GET["property"]) ?>';
			<?php endif; ?>

			<?php if(array_key_exists("checkin", $_GET) && !is_null($_GET["checkin"])) : ?>
			_365_start_date = '<?php echo esc_html($_GET["checkin"]) ?>';
			<?php endif; ?>

			<?php if(array_key_exists("checkout", $_GET) && !is_null($_GET["checkout"])) : ?>
			_365_end_date  = '<?php echo esc_html($_GET["checkout"]) ?>';
			<?php endif; ?>

			<?php if(array_key_exists("adults", $_GET) && !is_null($_GET["adults"]) && is_numeric($_GET["adults"])) : ?>
			_365_adult  = '<?php echo esc_html($_GET["adults"]) ?>';
			<?php endif; ?>

			<?php if(array_key_exists("children", $_GET) && !is_null($_GET["children"]) && is_numeric($_GET["children"])) : ?>
			_365_children  = '<?php echo esc_html($_GET["children"]) ?>';
			<?php endif; ?>
		<?php endif; ?>
	</script>
	<script src="https://secure.365villas.com/widget/general-olb.js"></script>
</div>