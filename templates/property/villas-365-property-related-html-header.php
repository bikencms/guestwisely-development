<?php
wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", [], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-property-related-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-property-related.css", ["_villas-365-styles"], VILLAS_365_VERSION);
wp_enqueue_script('_villas-365-property-related-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-property-related.js", ["jquery"], VILLAS_365_VERSION, true);