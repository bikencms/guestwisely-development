<?php if(!is_null($relatedProperty)) : ?>
<div class="_villas-365-property-inner">
	<?php if (!is_null($relatedProperty->image_path) && (trim($relatedProperty->image_path) !== "")) : ?>
	<div class="_villas-365-property-image">
		<a href="<?php echo Helpers365::MakePropertyURL($propertyPageURL, $relatedProperty->slug); ?>" class="unstyled-link"<?php echo $villas365PropertiesOpenInNewTabsHTML; ?>><img src="<?php echo esc_html($relatedProperty->image_small); ?>" alt="" class="img-fluid"></a>
	</div>
	<?php endif; ?>
	<div class="_villas-365-property-details">
		<?php
		$relatedPropertyName = Helpers365::Get365TranslationValue($relatedProperty, ["name"], ["languages", "name"]);
		if (!is_null($relatedPropertyName) && (trim($relatedPropertyName) !== "")) : ?>
		<div class="_villas-365-property-name">
			<a href="<?php echo Helpers365::MakePropertyURL($propertyPageURL, $relatedProperty->slug); ?>"<?php echo $villas365PropertiesOpenInNewTabsHTML; ?>><?php echo esc_html($relatedPropertyName); ?></a>
		</div>
		<?php endif; ?>

		<?php echo do_shortcode('[365-villas-property section=rooms propertyslug="' . $relatedProperty->slug . '"]'); ?>
	</div>
</div>
<?php endif; ?>