<?php
wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", [], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-property-rates-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-property-rates.css", ["_villas-365-styles"], VILLAS_365_VERSION);

$readmore = false;
$readmoreHeight = "300";
if(array_key_exists("readmore", $property_atts) &&
	!is_null($property_atts["readmore"]) &&
	((!is_bool($property_atts["readmore"]) && $property_atts["readmore"] == "true") || (is_bool($property_atts["readmore"]) && $property_atts["readmore"])))
{
	$readmore = true;

	if(array_key_exists("readmoreheight", $property_atts) &&
	!is_null($property_atts["readmoreheight"]) &&
	($property_atts["readmoreheight"] !== "") &&
	is_numeric($property_atts["readmoreheight"]))
	{
		$readmoreHeight = $property_atts["readmoreheight"];
	}

	wp_enqueue_style('_villas-365-read-more-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-read-more.css", [], VILLAS_365_VERSION);
	wp_enqueue_script('_villas-365-read-more-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-read-more.js", ['jquery'], VILLAS_365_VERSION, true);
	wp_enqueue_script('_villas-365-rates-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-property-rates.js", ['jquery', '_villas-365-read-more-scripts'], VILLAS_365_VERSION, true);
}
?>
<div class="_villas-365-bootstrap _villas-365-property-rates-table">
	<?php if(!is_null($ratesTableData)) : ?>
	<div class="row _villas-365-property-rates-table-header d-none d-md-flex">
		<?php if($showRateName) : ?>
		<div class="col-12 col-md"><div class="_villas-365-property-rates-table-item-inner-item-container"><h6 class="pb-0"><?php __v3te("Rates"); ?></h6></div></div>
		<?php endif; ?>

		<?php if($showRateDates) : ?>
		<div class="col-12 <?php echo (!$showRateName ? 'col-md' : 'col-md-3'); ?> text-center"><div class="_villas-365-property-rates-table-item-inner-item-container"><h6 class="pb-0"><?php __v3te("From"); ?></h6></div></div>
		<div class="col-12 <?php echo (!$showRateName ? 'col-md' : 'col-md-3'); ?> text-center"><div class="_villas-365-property-rates-table-item-inner-item-container"><h6 class="pb-0"><?php __v3te("To"); ?></h6></div></div>
		<?php endif; ?>

		<?php if($hasDaily) : ?>
		<div class="col-12 <?php echo (!$showRateName ? 'col-md' : 'col-md-2'); ?> text-center"><div class="_villas-365-property-rates-table-item-inner-item-container"><h6 class="pb-0"><?php __v3te("Daily"); ?></h6></div></div>
		<?php endif; ?>

		<?php if($hasWeekly) : ?>
		<div class="col-12 <?php echo (!$showRateName ? 'col-md' : 'col-md-2'); ?> text-center"><div class="_villas-365-property-rates-table-item-inner-item-container"><h6 class="pb-0"><?php __v3te("Weekly"); ?></h6></div></div>
		<?php endif; ?>
	</div>
<div class="_read-more">
	<div class="_read-more-outer" data-collapsed="true" data-initial-height="<?php echo esc_html($readmoreHeight); ?>">
		<div class="_read-more-inner">
			<?php
			$ratesCounter = 0;
			foreach($ratesTableData->seasons as $ratesTableItem) :
			?>
			<div class="row _villas-365-property-rates-table-item">
				<div class="col">
					<div class="_villas-365-property-rates-table-item-inner<?php echo ($ratesCounter % 2 ? ' _villas-365-property-rates-table-item-inner-alt' : ''); ?>">
						<div class="row align-items-center">
							<?php if($showRateName) : ?>
							<div class="col-12 col-md"><div class="_villas-365-property-rates-table-item-inner-item-container"><?php echo esc_html($ratesTableItem->seasonName); ?></div></div>
							<?php endif; ?>

							<?php if($showRateDates) : ?>
							<div class="col-12 <?php echo (!$showRateName ? 'col-md' : 'col-md-3'); ?> text-md-center">
								<div class="_villas-365-property-rates-table-item-inner-item-container">	
								<?php if(property_exists($ratesTableItem, "startDate")) : ?>
									<strong class="d-inline d-md-none"><?php __v3te("From"); ?>: </strong><?php echo esc_html(date_create_from_format("Y-m-d", $ratesTableItem->startDate)->format("d M Y")); ?>
								<?php else: ?>
									<span class="d-none d-md-inline">&nbsp;</span>
								<?php endif; ?>
								</div>
							</div>
							<div class="col-12 <?php echo (!$showRateName ? 'col-md' : 'col-md-3'); ?> text-md-center">
								<div class="_villas-365-property-rates-table-item-inner-item-container">
								<?php if(property_exists($ratesTableItem, "endDate")) : ?>
									<strong class="d-inline d-md-none"><?php __v3te("To"); ?>: </strong><?php echo esc_html(date_create_from_format("Y-m-d", $ratesTableItem->endDate)->format("d M Y")); ?>
								<?php else: ?>
									<span class="d-none d-md-inline">&nbsp;</span>
								<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>
							
							<?php if($hasDaily) : ?>
							<div class="col-12 <?php echo (!$showRateName ? 'col-md' : 'col-md-2'); ?> text-md-center">
								<div class="_villas-365-property-rates-table-item-inner-item-container">
									<?php if(property_exists($ratesTableItem, "nightly")) : ?>
										<strong class="d-inline d-md-none"><?php __v3te("Daily"); ?>: </strong><?php echo esc_html($ratesTableData->currencySymbol) . esc_html($ratesTableItem->nightly); ?>
									<?php else: ?>
										<?php __v3te("N/A"); ?>
									<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>

							<?php if($hasWeekly) : ?>
							<div class="col-12 <?php echo (!$showRateName ? 'col-md' : 'col-md-2'); ?> text-md-center">
								<div class="_villas-365-property-rates-table-item-inner-item-container">
									<?php if(property_exists($ratesTableItem, "weekly")) : ?>
										<strong class="d-inline d-md-none"><?php __v3te("Weekly"); ?>: </strong><?php echo esc_html($ratesTableData->currencySymbol) . esc_html($ratesTableItem->weekly); ?>
									<?php else: ?>
										<?php __v3te("N/A"); ?>
									<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			$ratesCounter++;
			endforeach;
			else : ?>
			<div class="row _villas-365-property-rates-table-item">
				<div class="col">
					<?php __v3te("There was an error getting the rate information. Please check your API keys and if the issue is still present contact 365villas."); ?>
				</div>
			</div>
			<?php endif;?>
		</div>
		</div>
		<div class="_read-more-more-button clearfix collapsed text-center" data-rotate-class="rotate-90r">
			<span class="hide-on-show"><?php __v3te("More"); ?></span><span class="show-on-show"><?php __v3te("Less"); ?></span>
		</div>
	</div>
</div>