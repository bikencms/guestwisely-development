<div class="_villas-365-bootstrap _villas-365-property-container _property-images-scrolling-container">
	<div id="_property-images-scrolling-container">
		<ul id="_property-images-scrolling-features">
			<?php
			$preloadScrollerImages = 8;
			foreach($gallery as $key => $banner) : ?>
				<li>
					<a class="property-images-link" target="_blank" href="<?php echo esc_html($banner->link); ?>" data-image-key="<?php echo $key; ?>" data-src="<?php echo esc_html($banner->link); ?>" data-caption="<?php echo esc_html($banner->description); ?>" data-fancybox-trigger="_property-images-fancybox" data-fancybox-index="<?php echo $key; ?>">
						<?php if($key < $preloadScrollerImages): ?>
						<img src="<?php echo esc_html($banner->link_banner); ?>" alt="" class="img-responsive">
						<?php else: ?>
						<img data-src-villas="<?php echo esc_html($banner->link_banner); ?>" data-src-villas-attribute="src" alt="" class="img-responsive _villas-365-lazy-load-image">
						<?php endif; ?>
					</a>
					<div class="_property-image-overlay"></div>
				</li>
			<?php
			$firstScrollerImage = false;
			endforeach; ?>
		</ul>
		<a id="_property-images-scrolling-features-scroll-left" class="carousel-control carousel-control-prev" href="#">
			<div class="banner-control-background">
				<i class="fa fa-angle-left"></i>
			</div>
		</a>
		<a id="_property-images-scrolling-features-scroll-right" class="carousel-control carousel-control-next" href="#">
			<div class="banner-control-background">
				<i class="fa fa-angle-right"></i>
			</div>
		</a>
	</div>
</div>