<div class="_villas-365-bootstrap _villas-365-language-switcher">
	<?php foreach($languageUrls as $languageCode => $languageUrl):
	/*
	$languageCode is the short language code. eg. en, de, es
	$languageUrl["active"] true or false if we are on the current language.
	$languageUrl["native_name"] the language name in the currently selected language.
	$languageUrl["url"] the URL for the current page for the language
	*/
	?>
	<span class="_villas-365-language-link-container<?php echo ($languageUrl["active"] ? " active" : ""); ?>"><a href="<?php echo esc_html($languageUrl["url"]); ?>" class="_villas-365-language-link" title="<?php echo esc_html($languageUrl["native_name"]); ?>"><?php echo esc_html($languageCode); ?></a></span>
	<?php endforeach; ?>
</div>