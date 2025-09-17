<div id="_villas-365-property-floater-container" class="_villas-365-bootstrap _villas-365-property-container">
	<div id="_villas-365-property-floater-anchor"></div>
	<div id="_villas-365-property-floater" class="<?php echo (!$quoteWidget ? "d-none d-lg-block" : ""); ?>" data-property-id="<?php echo esc_html($property->id) ?>">
		<div class="container">
			<div class="row <?php echo ($modal ? "justify-content-center" : "justify-content-end"); ?>">
				<div class="<?php echo (!$quoteWidget || $modal ? "col-12 col-lg-4" : "col"); ?>">
					<div class="_villas-365-property-floater-content">
						<div class="_villas-365-property-floater-header">
							<div class="row align-items-center">
								<div class="<?php echo ($modal ? "col-12 mb-half-gutter" : "col-12 col-xl-auto mb-half-gutter mb-xl-0"); ?>">
									<?php __v3te("We're here to help:"); ?>
								</div>
								<?php if(!is_null($contactItems) && is_array($contactItems) && (count($contactItems) > 0)): ?>
								<div class="col-auto _villas-365-property-floater-contact-container">
									<a href="<?php echo esc_html($contactPageUrl); ?>" class="d-flex"><img class="_villas-365-property-floater-icon" src="https://secure.365villas.com/images/common/svg/wordpress/email.svg" alt="<?php __v3te("Contact Us"); ?>"></a>
								</div>
								<?php foreach($contactItems as $contactItem): ?>
								<div class="col-auto _villas-365-property-floater-contact-container">
									<a href="<?php echo esc_html($contactItem->link); ?>" class="d-flex"<?php echo (strtolower($contactItem->name) == "whatsapp" ? ' target="_blank"' : ''); ?>><img class="_villas-365-property-floater-icon" src="<?php echo esc_html($contactItem->icon); ?>" alt="<?php __v3te($contactItem->name); ?>"></a>
								</div>
								<?php endforeach; ?>
								<?php else: ?>
								<div class="col">
									<a href="<?php echo esc_html($contactPageUrl); ?>" class="_villas-365-property-button _villas-365-property-button-alt btn btn-block btn-primary"><?php __v3te("Contact Us"); ?></a>
								</div>
								<?php endif; ?>
							</div>
							<div id="_villas-365-property-floater-close-button">
								<i class="fas fa-times" aria-label="<?php __v3te("Close booking overlay"); ?>"></i>
							</div>
						</div>
						<div class="_villas-365-property-floater-body">
							<div class="row">
								<?php
								$rateValue = Helpers365::GetValueFromObjectProperties($property, ["rateValue"]);
								$rateLabelValue = Helpers365::GetValueFromObjectProperties($property, ["rateLabel"]);;
								$defaultPerNight = __v3t("From") . " " . esc_html($rateValue) . "/" . __v3t(($rateLabelValue == "day" ? "night" : $rateLabelValue));
								$rateLabel = __v3t("night");
								$rateLabelPlural = __v3tn("night", 2);
								?>
								<div id="_villas-365-property-floater-price-per-night"
									class="col-12"
									data-default-per-night="<?php echo $defaultPerNight ?>"
									data-rate-label="<?php echo esc_html($rateLabel) ?>"
									data-rate-label-plural="<?php echo esc_html($rateLabelPlural) ?>">
									<?php echo $defaultPerNight ?>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<hr>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<label><?php __v3te("Dates"); ?></label>
								</div>
							</div>
							<div class="row mb-3 _villas-365-date-range-container">
								<div class="col-6 pr-0">
									<div class="_villas-365-date-container">
										<label class="d-none" for="checkin"><?php __v3te("Arrival Date"); ?></label>
										<input type="text" class="form-control _villas-365-checkin _villas-365-date-control _villas-365-date-control-start" value="" placeholder="<?php __v3te("Check-in"); ?>" data-start-field-id="_villas-365-checkin" data-end-field-id="_villas-365-checkout">
									</div>
								</div>
								<div class="col-6 pl-0">
									<div class="_villas-365-date-container">
										<label class="d-none" for="checkout"><?php __v3te("Departure Date"); ?></label>
										<input type="text" class="form-control _villas-365-checkout _villas-365-date-control _villas-365-date-control-end" value="" placeholder="<?php __v3te("Check-out"); ?>">
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-12">
									<label for="adults"><?php __v3te("Adults"); ?></label>
									<select id="_villas-365-adults" name="adults" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php __v3te("Adults"); ?>" data-placeholder="<?php __v3te("Adults"); ?>">
										<option value=""></option>
										<?php
										for($counter = 1; $counter <= $maxGuests; $counter++) :
										?>
										<option value="<?php echo $counter; ?>"><?php echo $counter; ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-12">
									<label for="children"><?php __v3te("Children"); ?></label>
									<select id="_villas-365-children" name="children" class="form-control search-text chosen-select chosen-select-single" placeholder="<?php __v3te("Children"); ?>" data-placeholder="<?php __v3te("Children"); ?>">
										<option value=""></option>
										<?php
										for($counter = 1; $counter <= $maxGuests; $counter++) :
										?>
										<option value="<?php echo $counter; ?>"><?php echo $counter; ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div id="_villas-365-property-floater-discount-row" class="row mb-3" style="display:none;">
								<div class="col">
									<div class="_villas-365-property-floater-discount">
										<div class="_villas-365-property-discount-label"><i class="fa fa-fw fa-info-circle"></i>&nbsp;<?php __v3te("Discount"); ?></div>
										<div class="_villas-365-property-discount-text"></div>
									</div>
								</div>
							</div>
							<div id="_villas-365-property-floater-total-row" class="row mb-3">
								<div id="_villas-365-property-floater-total-label" class="col-auto d-none"><?php __v3te("Total"); ?></div>
								<div id="_villas-365-property-floater-total" class="col"></div>
							</div>
							<div id="_villas-365-property-floater-price-details-row" class="row mb-3 d-none">
								<div class="col">
									<div class="row">
										<div class="col">
											<div id="_villas-365-property-floater-price-details-button" class="collapsed" data-rotate-class="rotate-180r">
												<span class="hide-on-show"><?php __v3te("Price details"); ?></span><span class="show-on-show"><?php __v3te("Hide details"); ?></span>
												<span class="button-icon"><i class="fas fa-chevron-down rotate"></i></span>
											</div>
										</div>
									</div>

									<div class="_villas-365-property-floater-price-details-container" style="display:none;" data-collapsed="true">
										<div class="row mb-2">
											<div id="_villas-365-property-floater-price-details-label-per-night" class="col _villas-365-property-floater-price-details-label col-auto"><?php __v3te("Rent"); ?></div>
											<div id="_villas-365-property-floater-price-details-per-night" class="_villas-365-property-floater-price-details col"></div>
										</div>
										<div class="row mb-2">
											<div id="_villas-365-property-floater-price-details-label-fees" class="col _villas-365-property-floater-price-details-label col-auto"><?php __v3te("Service fees"); ?></div>
											<div id="_villas-365-property-floater-price-details-fees" class="_villas-365-property-floater-price-details col"></div>
										</div>
										<div class="row">
											<div id="_villas-365-property-floater-price-details-label-tax" class="col _villas-365-property-floater-price-details-label col-auto"><?php __v3te("Tax"); ?></div>
											<div id="_villas-365-property-floater-price-details-tax" class="_villas-365-property-floater-price-details col"></div>
										</div>
										<div id="_villas-365-property-floater-price-details-discount-container" class="row mt-2" style="display:none;">
											<div id="_villas-365-property-floater-price-details-label-discount" class="col _villas-365-property-floater-price-details-label col-auto"><?php __v3te("Discount"); ?></div>
											<div id="_villas-365-property-floater-price-details-discount" class="_villas-365-property-floater-price-details col"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6 pr-1">
									<a href="<?php echo esc_html($bookingPageUrl); ?>" class="_villas-365-property-floater-enquire-button _villas-365-property-button _villas-365-property-button-inverted btn btn-block btn-primary"><?php __v3te($enquireLabel); ?></a>
								</div>
								<div class="col-6 pl-1">
									<a href="<?php echo esc_html($bookingPageUrl); ?>" class="_villas-365-property-floater-book-now-button _villas-365-property-button btn btn-block btn-primary"><?php __v3te("Book Now"); ?></a>
								</div>
							</div>
							<?php if(!is_null($minimumNights)) : ?>
							<div id="_villas-365-property-floater-minimum-nights-container" class="row mt-3">
								<div class="col-12">
									<?php __v3te("There is a %NUMBER night minimum.", ["%NUMBER" => '<span id="_villas-365-property-floater-minimum-nights" data-default-minimum-nights="' . esc_html($minimumNights) . '">' . esc_html($minimumNights) . '</span>']); ?><span id="_villas-365-property-floater-minimum-nights-dates-text" style="display:none;">&nbsp;<?php __v3te("Please %ENQUIRELABEL or extend your stay.", ["%ENQUIRELABEL" => __v3te(strtolower($enquireLabel))]); ?></span>
								</div>
							</div>
							<?php endif; ?>
							<div id="_villas-365-property-floater-powered-by-container" class="row mt-3">
								<div class="col-12">
									<div id="_villas-365-property-floater-powered-by">
										<a href="https://365villas.com/" target="_blank"><?php __v3te("Powered by 365Villas"); ?></a>
									</div>
								</div>
							</div>
							<div id="_datetime-hidden-fields">
								<input id="_villas-365-checkin" type='hidden' name='checkin' value="">
								<input id="_villas-365-checkout" type='hidden' name='checkout' value="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="_villas-365-property-floater-tab-button">
		<?php __v3te("Book or " . $enquireLabel); ?>
	</div>
</div>