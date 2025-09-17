<?php if(isset($property) && !is_null($property)): ?>
<div class="_villas-365-property-save-button loading" data-property-id="<?php echo $property->id ?>">
	<i class="fas fa-spinner fa-spin fa-fw _villas-365-property-save-button-icon _villas-365-property-save-spinner"></i>
	<i class="far fa-heart fa-fw _villas-365-property-save-button-icon _villas-365-property-save-inactive"></i>
	<i class="fas fa-heart fa-fw _villas-365-property-save-button-icon _villas-365-property-save-active"></i>
	<?php if(isset($showText) && !is_null($showText) && is_bool($showText) && $showText): ?>
	<h5 class="_villas-365-property-save-button-text"><?php __v3te("Save"); ?></h5>
	<?php endif; ?>
</div>
<?php endif; ?>