<div class="wrap">
	<h1><?= esc_html(get_admin_page_title()); ?></h1>
	<form action="admin.php" method="post">
		<?php
		// output security fields for the registered setting "villas_365_options"
		settings_fields('villas-365-network');
		// output setting sections and their fields
		// (sections are registered for "villas_365", each field is registered to a specific section)
		do_settings_sections('villas-365-network');
		// Add a nonce field
		wp_nonce_field('villas_365_nonce', 'villas_365_nonce');
		// output save settings button
		submit_button('Save Settings');
		?>
	</form>
</div>