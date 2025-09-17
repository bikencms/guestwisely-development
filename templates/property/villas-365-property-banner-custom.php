<div class="_villas-365-bootstrap _villas-365-property-container _property-banner-container">
	<?php
	$galleryHTML = null;
	if(!is_null($gallery) && (count($gallery) > 1)) : ?>
	<div id="_property-banner-container">
		<div id="_property-banner-features">
			<?php
			$preloadBannerImages = 3;
			$galleryHTML = "";
			foreach($gallery as $key => $banner) :
				$galleryHTML .= '<div class="_property-gallery-image" style="display:none;" data-src="' . esc_html($banner->link) . '" data-caption="' . esc_html($banner->description). '" data-fancybox="_property-images-fancybox"><img src="' . esc_html($banner->link_small) . '"></div>';
				
				if(!$lightBoxOnly || ($key < $preloadBannerImages)) : ?>
				<div class="_property-banner-image" data-src="<?php echo esc_html($banner->link); ?>" data-caption="<?php echo esc_html($banner->description); ?>" data-fancybox-trigger="_property-images-fancybox" data-fancybox-index="<?php echo $key; ?>">
					<?php if($key < $preloadBannerImages): ?>
					<img src="<?php echo esc_html($banner->link); ?>" alt="" class="img-responsive">
					<?php else: ?>
					<img data-src-villas="<?php echo esc_html($banner->link); ?>" data-src-villas-attribute="src" alt="" class="img-responsive _villas-365-lazy-load-image">
					<?php endif; ?>
				</div>
				<?php else : ?>
				<div style="display:none;" data-fancybox-trigger="_property-images-fancybox" data-src="<?php echo esc_html($banner->link); ?>" data-caption="<?php echo esc_html($banner->description); ?>"></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<a id="_property-banner-features-scroll-left" class="carousel-control carousel-control-prev" href="#" <?php if($lightBoxOnly) : ?>style="pointer-events:none;"<?php endif; ?>>
			<div class="banner-control-background">
				<i class="fa fa-angle-left"></i>
			</div>
		</a>
		<a id="_property-banner-features-scroll-right" class="carousel-control carousel-control-next" href="#" <?php if($lightBoxOnly) : ?>style="pointer-events:none;"<?php endif; ?>>
			<div class="banner-control-background">
				<i class="fa fa-angle-right"></i>
			</div>
		</a>
		
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
										<h1 class="_villas-365-property-name"><?php echo esc_html($property->name); ?></h1>
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
		<img src="<?php echo esc_html($property->image_path) ?>" alt="<?php echo (!is_null($property->name) && (trim($property->name) !== "") ? $property->name : ''); ?>" class="img-fluid d-md-none">
		
		<?php if($showIntroBox) : ?>
		<div class="_villas-365-property-header">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-12 col-lg-6 col-xl-4">
						<div class="_villas-365-property-header-inner">
							<div class="_villas-365-property-header-inner-background">
								<div class="row">
									<div class="col">
										<h1 class="_villas-365-property-name"><?php echo esc_html($property->name); ?></h1>
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

	<?php if(!is_null($galleryHTML)): ?>
	<div class="_property-images-gallery"><?php echo $galleryHTML; ?></div>
	<?php endif; ?>
</div>