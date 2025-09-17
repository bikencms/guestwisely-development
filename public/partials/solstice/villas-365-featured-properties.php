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

wp_enqueue_style('_villas-365-fontawesome', "https://use.fontawesome.com/releases/v5.8.2/css/all.css", [], "5.8.2", false);
wp_enqueue_style('_villas-365-styles', plugin_dir_url( __FILE__ ) . "../../assets/solstice/css/villas-365-styles.css", ["_villas-365-fontawesome"], "1.0");
wp_enqueue_style('_villas-365-featured-properties', plugin_dir_url( __FILE__ ) . "../../assets/solstice/css/villas-365-featured-properties.css", ["_villas-365-styles"], "1.0");
wp_enqueue_script('_villas-365-scripts', plugin_dir_url( __FILE__ ) . '../../assets/solstice/js/villas-365-scripts.js', ['jquery'], "1.0", true);

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides(true);
wp_add_inline_style('_villas-365-featured-properties', $villas365CSSOverrides);

$propertiesPerRow = 3;
if(isset($propertiesFeatured_atts) && (count($propertiesFeatured_atts) > 0) && array_key_exists("perrow", $propertiesFeatured_atts) && is_numeric($propertiesFeatured_atts["perrow"]))
{
	$propertiesPerRow = $propertiesFeatured_atts["perrow"];
}

$propertiesLimit = 3;
if(isset($propertiesFeatured_atts) && (count($propertiesFeatured_atts) > 0) && array_key_exists("limit", $propertiesFeatured_atts) && is_numeric($propertiesFeatured_atts["limit"]))
{
	$propertiesLimit = $propertiesFeatured_atts["limit"];
}

$propertyPageURL = null;
if(isset($propertiesFeatured_atts) && (count($propertiesFeatured_atts) > 0) && array_key_exists("propertypageid", $propertiesFeatured_atts) && is_numeric($propertiesFeatured_atts["propertypageid"]))
{
	$propertyPageURL = Helpers365::GetPropertyPageURL($propertiesFeatured_atts["propertypageid"]);
}

//Get the property details
$properties = API365::SearchProperties([
	"isfeatured" => 1,
	"limit" => $propertiesLimit
]);
if(!is_null($properties) && ($properties["count"] > 0)) :
?>
<div class="_villas-365-bootstrap _villas-365-properties _villas-365-properties-featured">
	<div class="container">
		<?php foreach(array_chunk($properties["properties"], $propertiesPerRow) as $propertiesChunk): ?>
		<div class="row">
			<?php foreach($propertiesChunk as $property): ?>
			<div class="_villas-365-property col-md margin-bottom-gutter">
				<div class="row h-100">
					<div class="col-12">
						<?php if (!is_null($property->image_path) && (trim($property->image_path) !== "")) : ?>
						<div class="_villas-365-property-image">
							<a href="<?php echo esc_html($propertyPageURL); ?><?php echo esc_html($property->slug) ?>" class="unstyled-link"><img src="<?php echo esc_html($property->image_small); ?>" alt="" class="img-fluid"></a>
						</div>
						<?php endif; ?>

						<?php if (!is_null($property->name) && (trim($property->name) !== "")) : ?>
						<h5 class="_villas-365-property-name">
							<a href="<?php echo esc_html($propertyPageURL); ?><?php echo esc_html($property->slug) ?>"><?php echo esc_html($property->name); ?></a>
						</h5>
						<?php endif; ?>

						<?php if (!is_null($property->brief) && (trim($property->brief) !== "")) : ?>
						<div class="_villas-365-property-summary">
							<?php echo esc_html(Helpers365::StrLimit(Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]), 175)); ?>
						</div>
						<?php endif; ?>
					</div>

					<div class="w-100"></div>

					<div class="col-12 align-self-end">
						<div class="_villas-365-property-footer">
							<div class="row align-items-center mt-3">
								<?php if ((!is_null($property->bedroom) && (trim($property->bedroom) !== "")) ||
									(!is_null($property->maxguest) && (trim($property->maxguest) !== "")) ||
									(!is_null($property->bathroom) && (trim($property->bathroom) !== ""))) : ?>
								<div class="col-12 col-md mb-3 mb-md-0">
									<div class="_villas-365-property-rooms">
										<?php if (!is_null($property->maxguest) && (trim($property->maxguest) !== "")) : ?>
										<div class="_villas-365-property-room">
											<i class="_villas-365-property-room-icon fas fa-user"></i> <?php echo esc_html($property->maxguest); ?>
										</div>
										<?php endif; ?>
										
										<?php if (!is_null($property->bedroom) && (trim($property->bedroom) !== "")) : ?>
										<div class="_villas-365-property-room">
											<i class="_villas-365-property-room-icon fas fa-bed"></i> <?php echo esc_html($property->bedroom); ?>
										</div>
										<?php endif; ?>

										<?php if (!is_null($property->bathroom) && (trim($property->bathroom) !== "")) : ?>
										<div class="_villas-365-property-room">
											<i class="_villas-365-property-room-icon fas fa-bath"></i> <?php echo esc_html($property->bathroom); ?>
										</div>
										<?php endif; ?>
									</div>
								</div>
								<?php endif; ?>

								<div class="col-12 col-md-auto">
									<a href="<?php echo esc_html($propertyPageURL); ?><?php echo esc_html($property->slug) ?>" class="_villas-365-property-button btn btn-primary btn-block"><?php __v3te("Book Now"); ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>