<div class="wrap">
	<h1><?= esc_html(get_admin_page_title()); ?></h1>
	<form action="options.php" method="post">
		<?php
		// output security fields for the registered setting "villas_365_options"
		settings_fields('villas-365');
		// output setting sections and their fields
		// (sections are registered for "villas_365", each field is registered to a specific section)
		do_settings_sections('villas-365');
		// output save settings button
		submit_button('Save Settings');
		?>
	</form>

	<h2><span style="color: red;">PLEASE NOTE:</span> Due to caching on the site to improve overall performance, some settings may not be immediately visible. eg. color settings changes. These will update once the cache has expired (default cache time is 1 hour).</h2>
</div>