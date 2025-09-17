<div class="_villas-365-bootstrap _villas-365-property-container _villas-365-property-banner-container">
	<?php if(!is_null($gallery) && (count($gallery) > 1)) : ?>
	<div class="_banner">
		<div id="property-banner" class="banner-carousel hidden-print carousel slide" data-ride="carousel" data-interval="false<?php /*echo (defined('DEBUG_365') && DEBUG_365 ? 'false' : '10000');*/ ?>">
			<?php if(!$lightBoxOnly) : ?>
			<!-- Indicators -->
			<ol class="carousel-indicators d-none d-lg-flex">
				<?php foreach($gallery as $key => $banner) : ?>
				<li data-target="#property-banner" data-slide-to="<?php echo $key; ?>" class="<?php echo (($key == 0) ? 'active' : ''); ?>"></li>
				<?php endforeach; ?>
			</ol>
			<?php endif; ?>

			<!-- Wrapper for slides -->
			<div class="carousel-inner">
				<?php
				$firstGalleryImage = true;
				foreach($gallery as $key => $banner) :
				if(!$lightBoxOnly || $firstGalleryImage) :
				?>
				<div class="carousel-item <?php echo (($key == 0) ? 'active' : ''); ?>">
					<?php if($firstGalleryImage) : ?>
					<div class="banner-item-container<?php echo $villas365PropertiesFullHeightBannerClass; ?>" style="background-image:url('<?php echo esc_html($banner->link_banner); ?>')" data-fancybox="_property-images-fancybox" data-src="<?php echo esc_html($banner->link); ?>">
						<img src="<?php echo esc_html($banner->link_banner); ?>" alt="" class="img-fluid d-lg-none">
					</div>
					<?php else: ?>
					<div class="banner-item-container<?php echo $villas365PropertiesFullHeightBannerClass; ?> _villas-365-lazy-load-image" data-fancybox="_property-images-fancybox" data-src-villas="<?php echo esc_html($banner->link_banner); ?>" data-src-villas-attribute="background-image" data-src="<?php echo esc_html($banner->link); ?>">
						<img data-src-villas="<?php echo esc_html($banner->link_banner); ?>" data-src-villas-attribute="src" alt="" class="img-fluid d-lg-none _villas-365-lazy-load-image">
					</div>
					<?php endif; ?>
				</div>
				<?php else : ?>
				<div style="display:none;" data-fancybox="_property-images-fancybox" data-src="<?php echo esc_html($banner->link); ?>"></div>
				<?php endif;
				$firstGalleryImage = false;
				?>
				<?php endforeach; ?>
			</div>

			<div class="left carousel-control carousel-control-prev" role="button" <?php if(!$lightBoxOnly) : ?>data-slide="prev" data-target="#property-banner"<?php else: ?>style="pointer-events:none;"<?php endif; ?>>
				<i class="fas fa-chevron-left prev-icon carousel-control-icon" aria-hidden="true"></i>
				<span class="sr-only">Previous</span>
			</div>
			<div class="right carousel-control carousel-control-next" role="button" <?php if(!$lightBoxOnly) : ?>data-slide="next" data-target="#property-banner"<?php else: ?>style="pointer-events:none;"<?php endif; ?>>
				<i class="fas fa-chevron-right next-icon carousel-control-icon" aria-hidden="true"></i>
				<span class="sr-only">Next</span>
			</div>
		</div>

		<?php if($showIntroBox) : ?>
		<div class="_villas-365-property-header">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-12 col-lg-6 col-xl-5">
						<div class="_villas-365-property-header-inner">
							<div class="_villas-365-property-header-inner-background">
								<div id="_villas-365-property-header-close-button"><i class="fas fa-times"></i></div>
								<div class="row">
									<div class="col">
										<h1 class="_villas-365-property-name"><?php echo esc_html($propertyName); ?></h1>
									</div>
								</div>
								
								<?php if (!is_null($property->brief) && (trim($property->brief) !== "")) : ?>
								<div class="row">
									<div class="col">
										<div class="_villas-365-property-summary">
										<?php echo esc_html(Helpers365::StrLimit(Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]), 300)); ?>
										</div>
									</div>
								</div>
								<?php endif; ?>

								<div class="row mt-3">
									<div class="col">
										<a href="<?php echo esc_html($bookingPageUrl); ?>" class="_villas-365-property-button btn btn-primary"><?php __v3te("Book Now"); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<?php else : ?>
	<div id="property-image" class="margin-bottom-gutter" style="background-image:url('<?php echo esc_html($property->image_path) ?>');">
		<img src="<?php echo esc_html($property->image_path) ?>" alt="<?php echo (!is_null($propertyName) && (trim($propertyName) !== "") ? $propertyName : ''); ?>" class="img-fluid d-md-none">
		
		<?php if($showIntroBox) : ?>
		<div class="_villas-365-property-header">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-12 col-lg-6 col-xl-4">
						<div class="_villas-365-property-header-inner">
							<div class="_villas-365-property-header-inner-background">
								<div class="row">
									<div class="col">
										<h1 class="_villas-365-property-name"><?php echo esc_html($propertyName); ?></h1>
									</div>
								</div>
								
								<?php if (!is_null($property->brief) && (trim($property->brief) !== "")) : ?>
								<div class="row">
									<div class="col">
										<div class="_villas-365-property-summary">
										<?php echo esc_html(Helpers365::StrLimit(Helpers365::Get365TranslationValue($property, ["brief"], ["languages", "brief"]), 300)); ?>
										</div>
									</div>
								</div>
								<?php endif; ?>

								<div class="row mt-3">
									<div class="col">
										<a href="<?php echo esc_html($bookingPageUrl); ?>" class="_villas-365-property-button btn btn-primary"><?php __v3te("Book Now"); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>