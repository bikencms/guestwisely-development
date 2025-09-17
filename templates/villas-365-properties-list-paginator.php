<div class="row mt-5">
	
	<div class="col-12<?php echo ($propertiesListView === 'map' ? ' col-lg-7' : '') ?>">
		<nav class="_villas-365-navigation" aria-label="Property navigation">
			<div class="row justify-content-between">
				<div class="col-auto">
					<?php if($currentPageNumber != 1) : ?>
					<a class="_villas-365-navigation-link" href="<?php $currentPageURLData["ppage"] = ($currentPageNumber - 1); echo $currentPageURLBase . "?" . http_build_query($currentPageURLData); ?>" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
						<span><?php __v3te("Prev"); ?></span>
					</a>
					<?php endif; ?>
				</div>

				<div class="col text-center">
					<span class="d-none d-md-inline-block"><?php __v3te("Displaying") ?></span>
					<?php
					$maxPropertiesOnPage = $currentPageNumber * $properties["perPage"];
					if($maxPropertiesOnPage > $properties["totalProperties"])
					{
						$maxPropertiesOnPage = $properties["totalProperties"];
					}
					?>
					<span><?php echo (($currentPageNumber * $properties["perPage"]) - ($properties["perPage"] - 1)); ?> - <?php echo $maxPropertiesOnPage; ?> <?php __v3te("of") ?> <?php echo $properties["totalProperties"]; ?></span>
				</div>

				<div class="col-auto text-right">
					<?php if($currentPageNumber != $properties["totalPages"]) : ?>
					<a class="_villas-365-navigation-link" href="<?php $currentPageURLData["ppage"] = ($currentPageNumber + 1); echo $currentPageURLBase . "?" . http_build_query($currentPageURLData); ?>" aria-label="Next">
						<span><?php __v3te("Next"); ?></span>
						<span aria-hidden="true">&raquo;</span>
					</a>
					<?php endif; ?>
				</div>
			</div>
		</nav>
	</div>
</div>