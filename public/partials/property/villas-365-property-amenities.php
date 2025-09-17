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

$propertyAmenitiesHtmlHeaderTemplate = Helpers365::LocateTemplateFile("villas-365-property-amenities-html-header.php", "365villas/templates/property/", VILLAS_365_PLUGIN_PATH . 'templates/property/');
if($propertyAmenitiesHtmlHeaderTemplate === FALSE)
{
	echo "<pre>Property amenities HTML header template could not be found.</pre>";
}
else
{
	include $propertyAmenitiesHtmlHeaderTemplate;
}

if(!is_null($property) && property_exists($property, "amenity") && !is_null($property->amenity)) : ?>
<div class="_villas-365-bootstrap _villas-365-property-amenities">
	<div class="container">
		<?php
		$showOverview = false;
		if(array_key_exists("showoverview", $property_atts) &&
			!is_null($property_atts["showoverview"]) &
			((!is_bool($property_atts["showoverview"]) && $property_atts["showoverview"] == "true") || (is_bool($property_atts["showoverview"]) && $property_atts["showoverview"])))
		{
			$showOverview = true;
		}

		$showSpecificRooms = true;
		if(array_key_exists("amenitiesspecificrooms", $property_atts) &&
			!is_null($property_atts["amenitiesspecificrooms"]) &
			((!is_bool($property_atts["amenitiesspecificrooms"]) && $property_atts["amenitiesspecificrooms"] == "false") || (is_bool($property_atts["amenitiesspecificrooms"]) && !$property_atts["amenitiesspecificrooms"])))
		{
			$showSpecificRooms = false;
		}

		if($showOverview):
		
		$propertyAmenitiesData = API365::GetAmenities([
			"property_id" => $property->id
		]);

		if(!is_null($propertyAmenitiesData))
		{
			$amenityDescription = Helpers365::GetValueFromObjectProperties($propertyAmenitiesData, ["property_amenity_" . $property->id, "description"]);
			$amenityBedOptions = Helpers365::GetValueFromObjectProperties($propertyAmenitiesData, ["property_amenity_" . $property->id, "bedoption"]);
			$amenityAllowChildren = Helpers365::GetValueFromObjectProperties($propertyAmenitiesData, ["property_amenity_" . $property->id, "allowchildren"]);
			$amenityAllowPet = Helpers365::GetValueFromObjectProperties($propertyAmenitiesData, ["property_amenity_" . $property->id, "allowpet"]);
			$amenityAllowSmoking = Helpers365::GetValueFromObjectProperties($propertyAmenitiesData, ["property_amenity_" . $property->id, "allowsmoking"]);
			$amenityUseIcon = Helpers365::GetValueFromObjectProperties($propertyAmenitiesData, ["property_amenity_" . $property->id, "useicon"]);

			if(!is_null($amenityUseIcon) && ($amenityUseIcon == "1"))
			{
				$amenityUseIcon = true;
			}
			else
			{
				$amenityUseIcon = false;
			}
		}

		if(!is_null($propertyAmenitiesData) && (!is_null($amenityDescription) || !is_null($amenityBedOptions) || !is_null($amenityAllowChildren) || !is_null($amenityAllowPet) || !is_null($amenityAllowSmoking))):
		?>
		<div class="_villas-365-property-amenities-overview">
			<?php if(!is_null($amenityDescription) && (trim($amenityDescription) !== "")): ?>
			<div class="row">
				<div class="col-12 mb-3">
					<?php echo nl2br(esc_html($amenityDescription)); ?>
				</div>
			</div>
			<?php endif; ?>

			<?php if(!is_null($amenityAllowChildren) || !is_null($amenityAllowPet) || !is_null($amenityAllowSmoking)): ?>
			<div class="row">
				<div class="col-12 mb-3">
					<div class="_villas-365-property-rooms d-flex align-items-center">
						<?php if(!$amenityUseIcon): ?>
						<ul>
						<?php endif; ?>

						<?php if(!is_null($amenityAllowChildren)): ?>
						<?php if($amenityUseIcon): ?><span class="_villas-365-property-room"><i class="_villas-365-property-room-icon fas fa-child"></i>&nbsp;<?php else: ?><li><?php endif; ?><?php echo ($amenityAllowChildren == "1" ? __v3t("Children welcome") : __v3t("No children")); ?></span><?php if(!$amenityUseIcon): ?></li><?php endif; ?>
						<?php endif; ?>

						<?php if(!is_null($amenityAllowPet)): ?>
						<?php if($amenityUseIcon): ?><span class="_villas-365-property-room"><i class="_villas-365-property-room-icon fas fa-paw"></i>&nbsp;<?php else: ?><li><?php endif; ?><?php echo ($amenityAllowPet == "1" ? __v3t("Pets allowed") : __v3t("No pets")); ?></span><?php if(!$amenityUseIcon): ?></li><?php endif; ?>
						<?php endif; ?>

						<?php if(!is_null($amenityAllowSmoking)): ?>
						<?php if($amenityUseIcon): ?><span class="_villas-365-property-room"><i class="_villas-365-property-room-icon fas fa-<?php echo ($amenityAllowSmoking == "1" ? "smoking" : "smoking-ban"); ?>"></i>&nbsp;<?php else: ?><li><?php endif; ?><?php echo ($amenityAllowSmoking == "1" ? __v3t("Smoking permitted") : __v3t("No smoking")); ?></span><?php if(!$amenityUseIcon): ?></li><?php endif; ?>
						<?php endif; ?>

						<?php if(!is_null($amenityBedOptions) && (($amenityBedOptions == "1") || ($amenityBedOptions == "2"))): ?>
						<?php if($amenityUseIcon): ?><span class="_villas-365-property-room"><i class="_villas-365-property-room-icon fas fa-bed"></i>&nbsp;<?php else: ?><li><?php endif; ?><?php
							if($amenityBedOptions == "1"): ?>
								<?php __v3te("The bedding arrangement never changes."); ?>
							<?php elseif($amenityBedOptions == "2"): ?>
								<?php __v3te("Beds can be moved between rooms to suit guest requirements."); ?>
							<?php endif; ?></span><?php if(!$amenityUseIcon): ?></li><?php endif; ?>
						<?php endif; ?>

						<?php if(!$amenityUseIcon): ?>
						</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php endif;
		endif; ?>

		<div class="row">
			<div class="col-12 col-sm-6 col-lg-4 mb-3 mb-lg-0 _villas-365-property-amenity-switcher-column">
				<div class="_villas-365-property-amenity-switcher d-block d-lg-none">
					<div class="_villas-365-property-amenity-switcher-item-selected">
						<?php foreach (get_object_vars($property->amenity) as $key => $amenityLocation) :
							$amenityLocationLabel = "Other Amenities";
							$amenityLocationAmenities = $amenityLocation;
							if($key !== "extra")
							{
								$amenityLocationLabel = $amenityLocation->label;
								$amenityLocationAmenities = null;
								if($showSpecificRooms && Helpers365::CheckObjectPropertiesExist($amenityLocation, ["specificroomamenities"]))
								{
									$amenityLocationAmenities = (array)$amenityLocation->specificroomamenities;
								}
								else
								{
									$amenityLocationAmenities = $amenityLocation->amenity;
								}
							}
						?>

						<?php if (!is_null($amenityLocationAmenities) && count($amenityLocationAmenities) > 0) : ?>
						<h4 class="_villas-365-property-amenity-switcher-item-selected-text"><?php echo esc_html(__v3t($amenityLocationLabel)); ?></h4>
						<span class="_villas-365-property-amenity-switcher-item-selected-icon"><i class="fas fa-chevron-right"></i></span>
						<?php
						break;
						endif; ?>
						<?php endforeach; ?>
					</div>
					<div class="_villas-365-property-amenity-switcher-items" style="display:none;">
						<?php foreach (get_object_vars($property->amenity) as $key => $amenityLocation) :
							$amenityLocationLabel = "Other Amenities";
							$amenityLocationAmenities = $amenityLocation;
							if($key !== "extra")
							{
								$amenityLocationLabel = $amenityLocation->label;
								$amenityLocationAmenities = null;
								if($showSpecificRooms && Helpers365::CheckObjectPropertiesExist($amenityLocation, ["specificroomamenities"]))
								{
									$amenityLocationAmenities = (array)$amenityLocation->specificroomamenities;
									if(!is_null($amenityLocationAmenities) && count($amenityLocationAmenities) == 1 && array_key_exists("room_1", $amenityLocationAmenities))
									{
										$amenityLocationAmenities = $amenityLocationAmenities["room_1"];
									}
									else
									{
										$hasSpecificRooms = true;
									}
								}
								else
								{
									$amenityLocationAmenities = $amenityLocation->amenity;
								}
							}
						?>

						<?php if (!is_null($amenityLocationAmenities) && count($amenityLocationAmenities) > 0) : ?>
						<h4 class="_villas-365-property-amenity-switcher-item" data-amenity-name="<?php echo esc_html($amenityLocationLabel); ?>">
							<?php echo esc_html(__v3t($amenityLocationLabel)); ?>
						</h4>
						<?php endif; ?>

						<?php endforeach; ?>
					</div>
				</div>

				<div class="_villas-365-property-amenity-switcher-inline d-none d-lg-block">
					<div class="_villas-365-property-amenity-switcher-inline-items">
						<?php
						$isFirstAmenity = true;
						foreach (get_object_vars($property->amenity) as $key => $amenityLocation) :
							$amenityLocationLabel = "Other Amenities";
							$amenityLocationAmenities = $amenityLocation;
							if($key !== "extra")
							{
								$amenityLocationLabel = $amenityLocation->label;
								$amenityLocationAmenities = null;
								if($showSpecificRooms && Helpers365::CheckObjectPropertiesExist($amenityLocation, ["specificroomamenities"]))
								{
									$amenityLocationAmenities = (array)$amenityLocation->specificroomamenities;
								}
								else
								{
									$amenityLocationAmenities = $amenityLocation->amenity;
								}
							}
						?>

						<?php if (!is_null($amenityLocationAmenities) && count($amenityLocationAmenities) > 0) : ?>
						<h4 class="_villas-365-property-amenity-switcher-inline-item<?php echo ($isFirstAmenity ? ' active' : ''); ?>" data-amenity-name="<?php echo esc_html($amenityLocationLabel); ?>">
							<div class="_villas-365-property-amenity-switcher-inline-item-background"></div>
							<span><?php echo esc_html(__v3t($amenityLocationLabel)); ?></span>
							<span><i class="fas fa-chevron-right float-right"></i></span>
						</h4>
						<?php endif; ?>

						<?php
						$isFirstAmenity = false;
						endforeach;
						?>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-8 _villas-365-property-amenities-container">
				<?php
				$isFirstAmenity = true;
				foreach (get_object_vars($property->amenity) as $key => $amenityLocation) :
					$hasSpecificRooms = false;
					$amenityLocationLabel = "Other Amenities";
					$amenityLocationAmenities = $amenityLocation;
					if($key !== "extra")
					{
						$amenityLocationLabel = $amenityLocation->label;
						$amenityLocationAmenities = null;
						if($showSpecificRooms && Helpers365::CheckObjectPropertiesExist($amenityLocation, ["specificroomamenities"]))
						{
							$amenityLocationAmenities = (array)$amenityLocation->specificroomamenities;
							if(!is_null($amenityLocationAmenities) && count($amenityLocationAmenities) == 1 && array_key_exists("room_1", $amenityLocationAmenities))
							{
								$amenityLocationAmenities = $amenityLocationAmenities["room_1"];
							}
							else
							{
								$hasSpecificRooms = true;
							}
						}
						else
						{
							$amenityLocationAmenities = $amenityLocation->amenity;
						}
					}
				?>

				<?php if (!is_null($amenityLocationAmenities) && count($amenityLocationAmenities) > 0) : ?>
				<div class="_villas-365-property-amenity-group<?php echo ($isFirstAmenity ? ' active' : ''); ?>" data-amenity-name="<?php echo esc_html($amenityLocationLabel); ?>"<?php echo (!$isFirstAmenity ? ' style="display:none;"' : ''); ?>>
					<div class="row amenities">
						<div class="col-12">
							<?php if ($hasSpecificRooms):
								$isFirstRoom = true;
								$roomCounter = 1;
								?>
								<div class="row mb-half-gutter">
									<div class="col-12">
										<div class="_villas-365-property-amenity-specific-room-buttons">
											<h5 class="_villas-365-property-amenity-specific-room-buttons-label" data-amenity-room-number="<?php echo esc_html($roomCounter); ?>"><?php echo count($amenityLocationAmenities) . " " . esc_html($amenityLocationLabel); ?>:</h5>
											<?php foreach ($amenityLocationAmenities as $amenityRoom) : ?>
												<div class="_villas-365-property-amenity-room-button<?php echo ($isFirstRoom ? ' active' : ''); ?>" data-amenity-room-number="<?php echo esc_html($roomCounter); ?>"><?php echo $roomCounter; ?></div>
											<?php
											$roomCounter++;
											$isFirstRoom = false;
											endforeach; ?>
										</div>
									</div>
								</div>

								<?php
								$isFirstRoom = true;
								$roomCounter = 1;
								foreach ($amenityLocationAmenities as $amenityRoom) : ?>
								<div class="row _villas-365-property-amenity-specific-room<?php echo ($isFirstRoom ? ' active' : ''); ?>" data-amenity-room-number="<?php echo esc_html($roomCounter); ?>"<?php echo (!$isFirstRoom ? ' style="display:none;"' : ''); ?>>
									<div class="col-12">
										<?php foreach (array_chunk($amenityRoom, 2) as $amenityChunk) : ?>
										<div class="row">
											<?php
											foreach ($amenityChunk as $amenity) : ?>
											<div class="col-6 mb-3">
												<div class="_villas-365-property-amenity">
													<?php
													$amenityClass = Helpers365::GetValueFromObjectProperties($amenity, ["class"]);
													if(is_null($amenityClass))
													{
														$amenityClass = "amenity-extra";
													}
													?>
													<div class="_villas-365-property-amenity-icon <?php echo esc_html($amenityClass); ?> mb-1"></div>
													<div class="_villas-365-property-amenity-name">
														<?php echo esc_html(Helpers365::Get365TranslationValue($amenity, ["name"], ["languages"])); ?> <?php echo ($amenity->qty > 1 ? "x " . $amenity->qty : ""); ?>
													</div>
												</div>
											</div>		
											<?php endforeach; ?>
										</div>
										<?php endforeach; ?>
									</div>
								</div>
								<?php
								$roomCounter++;
								$isFirstRoom = false;
								endforeach;
								?>
							<?php else: ?>
								<?php foreach (array_chunk($amenityLocationAmenities, 2) as $amenityChunk) : ?>
								<div class="row">
									<?php
									foreach ($amenityChunk as $amenity) : ?>
									<div class="col-6 mb-3">
										<div class="_villas-365-property-amenity">
											<?php
											$amenityClass = Helpers365::GetValueFromObjectProperties($amenity, ["class"]);
											if(is_null($amenityClass))
											{
												$amenityClass = "amenity-extra";
											}
											?>
											<div class="_villas-365-property-amenity-icon <?php echo esc_html($amenityClass); ?> mb-1"></div>
											<div class="_villas-365-property-amenity-name">
												<?php echo esc_html(Helpers365::Get365TranslationValue($amenity, ["name"], ["languages"])); ?> <?php echo ($amenity->qty > 1 ? "x " . $amenity->qty : ""); ?>
											</div>
										</div>
									</div>		
									<?php endforeach; ?>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php
				$isFirstAmenity = false;
				endif;
				?>

				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>