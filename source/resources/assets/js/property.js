jQuery(document).ready(function() {
	// Set the correct property URL for teh AddToAny share plugin.
	// https://www.addtoany.com/buttons/customize/wordpress/events
	if((typeof(a2a_config) !== "undefined") && (typeof(_villas365CurrentPropertyUrl) !== "undefined"))
	{
		a2a_config.callbacks.push({
			share: function(share_data) {
				return {
					url: _villas365CurrentPropertyUrl,
				};
			},
		});
	}
});
