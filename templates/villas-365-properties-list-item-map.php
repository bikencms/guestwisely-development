<div class="_villas-365-property-inner">
	<div class="row">
		<div class="col-12 col-lg-6 mb-2 mb-lg-0">
			<div class="_villas-365-property-image">
				<?php if (!is_null($property->image_path) && (trim($property->image_path) !== "")) : ?>
				<a href="<?php echo Helpers365::MakePropertyURL($propertyPageURL, $property->slug); ?>" class="_villas-365-property-image-link unstyled-link" style="background-image:url('<?php echo esc_html($property->image_path); ?>');"<?php echo $villas365PropertiesOpenInNewTabsHTML; ?>>
					<img src="<?php echo esc_html($property->image_path); ?>" alt="" class="img-fluid d-block d-lg-none">
				</a>
				<?php endif; ?>
				<?php if($showDiscountLabel && Helpers365::CheckObjectPropertiesExist($property, ["discounttexts"], false) && !is_null($property->discounttexts) && is_array($property->discounttexts) && (count($property->discounttexts) > 0)) :
					$discountText = null;
					$discountNumber = count($property->discounttexts);
					if($discountNumber >= 2)
					{
						$discountText = $discountNumber . " " . __v3t("discounts available");
					}
					elseif($discountNumber == 1)
					{
						$discountText = $property->discounttexts[0];
					}
				?>
				<div class="_villas-365-property-info-label _villas-365-property-discount">
				<div class="_villas-365-property-discount-label"><?php __v3te("Discount"); ?></div><div class="_villas-365-property-discount-icon"><i class="fa fa-fw fa-info-circle"></i></div><div class="_villas-365-property-discount-text"><?php echo esc_html($discountText) ?></div>
				</div>
				<?php elseif($showFeaturedLabel && Helpers365::CheckObjectPropertiesExist($property, ["isfeatured"], false) && !is_null($property->isfeatured) && ((is_bool($property->isfeatured) && ($property->isfeatured === true)) || ($property->isfeatured == 1))) : ?>
				<div class="_villas-365-property-info-label _villas-365-property-featured">
					<?php __v3te("Featured"); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-12 col-lg">
			<div class="_villas-365-property-inner-text">
				<div class="row h-100">
					<div class="col-12">
						<div class="row">
							<?php
							$propertyName = Helpers365::Get365TranslationValue($property, ["name"], ["languages", "name"]);
							if (!is_null($propertyName) && (trim($propertyName) !== "")) : ?>
							<div class="col-12">
								<h4 class="_villas-365-property-name">
									<a href="<?php echo Helpers365::MakePropertyURL($propertyPageURL, $property->slug); ?>"<?php echo $villas365PropertiesOpenInNewTabsHTML; ?>><?php echo esc_html($propertyName); ?></a>
								</h4>
							</div>
							<?php endif; ?>

							<?php if ((!is_null($property->bedroom) && (trim($property->bedroom) !== "")) ||
								(!is_null($property->maxguest) && (trim($property->maxguest) !== "")) ||
								(!is_null($property->bathroom) && (trim($property->bathroom) !== ""))) : ?>
							<div class="col-12">
								<?php
								$sectionData = [
									"name" => "rooms",
									"icons" => true
								];
								if(Helpers365::CheckObjectPropertiesExist($property, ["maxguest", "bedroom", "bathroom"], false))
								{
									$sectionData["value"] = [
										"Guests" => $property->maxguest,
										"Bedrooms" => $property->bedroom,
										"Bathrooms" => $property->bathroom
									];
								}
								include VILLAS_365_PLUGIN_PATH . 'public/partials/property/' . VILLAS_365_PLUGIN_NAME . '-property-rooms.php'; ?>
							</div>
							<?php endif; ?>

							<?php if (!is_null($property->brief) && (trim($property->brief) !== "")) : ?>
							<div class="col-12">
								<div class="_villas-365-property-summary">
									<?php echo esc_html(Helpers365::StrLimit(Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]), 100)); ?>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>

					<?php
					$rateValue = Helpers365::GetValueFromObjectProperties($property, ["rateValue"]);
					$rateLabel = Helpers365::GetValueFromObjectProperties($property, ["rateLabel"]);
					$rateToolTip = Helpers365::GetValueFromObjectProperties($property, ["rateToolTip"]);
					$bookingPrice = Helpers365::GetValueFromObjectProperties($property, ["bookingprice"]);
					if(!is_null($bookingPrice)) :
						$perNightOrOriginalValue = esc_html($bookingPrice->grandTotalPerNightValue) . "/" . __v3t("night");
						if(Helpers365::GetValueFromObjectProperties($bookingPrice, ["grandTotalBeforeDiscountedValue"]) && ($bookingPrice->grandTotalValue != $bookingPrice->grandTotalBeforeDiscountedValue))
						{
							$perNightOrOriginalValue = "<s>" . esc_html($bookingPrice->grandTotalBeforeDiscountedValue) . "</s>";
						}
					?>
					<div class="w-100"></div>
					<div class="col-12 align-self-end">
						<div class="_villas-365-property-price" title="<?php __v3te($rateToolTip); ?>">
							<span class="_villas-365-property-price-per-night"><?php echo $perNightOrOriginalValue; ?></span> - <span class="_villas-365-property-price-grand-total"><?php echo esc_html($bookingPrice->grandTotalValue); ?> <?php __v3te("total"); ?></span>
						</div>
					</div>
					<?php elseif(!is_null($rateValue) && (trim($rateValue) !== "")) : ?>
					<div class="w-100"></div>
					<div class="col-12 align-self-end mt-half-gutter">
						<div class="row justify-content-between align-items-center">
							<div class="col-auto">
								<div class="_villas-365-property-price mt-0" title="<?php __v3te($rateToolTip); ?>">
									<?php __v3te("From"); ?> <?php echo esc_html($rateValue); ?>/<?php __v3te(($rateLabel == "day" ? "night" : $rateLabel)); ?>
								</div>
							</div>
							<div class="col-auto">
								<?php if(isset($showSaveButton) && ($showSaveButton === true) && isset($propertySaveButtonTemplate) && !is_null($propertySaveButtonTemplate)): ?>
									<?php include $propertySaveButtonTemplate; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>